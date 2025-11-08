document.addEventListener('DOMContentLoaded', () => {
    let map;
    const PUZZLE_SIZE = 2; 

    const mapElement = document.getElementById('map');
    const btnLocation = document.getElementById('btn-location');
    const btnGetMap = document.getElementById('btn-get-map');
    const puzzleSource = document.getElementById('puzzle-source');
    const puzzleBoard = document.getElementById('puzzle-board');

    function initMap() {
        map = L.map(mapElement, {
            zoomControl: false,     
            attributionControl: false
        }).setView([52.23, 21.01], 6);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        }).addTo(map);
    }

    function requestNotificationPermission() {
        if ('Notification' in window) {
            Notification.requestPermission().then(permission => {
                if (permission === 'granted') {
                    console.log('Zgoda na powiadomienia uzyskana.');
                } else {
                    console.warn('Odmówiono zgody na powiadomienia.');
                }
            });
        }
    }

    function createPuzzleBoardSlots() {
        puzzleBoard.innerHTML = '';
        for (let i = 0; i < PUZZLE_SIZE * PUZZLE_SIZE; i++) {
            const slot = document.createElement('div');
            slot.classList.add('puzzle-slot');
            slot.dataset.index = i; 

            addDropListeners(slot);
            puzzleBoard.appendChild(slot);
        }
    }

    function handleLocationClick() {
        if ('geolocation' in navigator) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    const coords = [lat, lon];
                    
                    console.log(`Pobrane współrzędne: ${lat}, ${lon}`);
                    
                    map.setView(coords, 13);

                    L.marker(coords).addTo(map)
                        .bindPopup('Twoja lokalizacja')
                        .openPopup();
                },
                (error) => {
                    console.error('Błąd geolokalizacji:', error);
                    alert('Nie udało się pobrać lokalizacji. Sprawdź uprawnienia.');
                }
            );
        } else {
            alert('Geolokalizacja nie jest wspierana przez Twoją przeglądarkę.');
        }
    }

    function handleGetMapClick() {
        alert('Rozpoczynam generowanie puzzli. Może to chwilę potrwać...');

        html2canvas(mapElement, {
            useCORS: true, 
            logging: true,
            scale: 1
        }).then(canvas => {
            console.log('Mapa wyeksportowana do rastra (canvas).');
            slicePuzzle(canvas);
        }).catch(err => {
            console.error('Błąd html2canvas:', err);
            alert('Wystąpił błąd podczas pobierania mapy. Spróbuj ponownie.');
        });
    }

    function slicePuzzle(sourceCanvas) {
        puzzleSource.innerHTML = '<h3>Wymieszane elementy</h3>';
        createPuzzleBoardSlots(); 

        const pieceWidth = sourceCanvas.width / PUZZLE_SIZE;
        const pieceHeight = sourceCanvas.height / PUZZLE_SIZE;
        
        let pieces = [];

        for (let y = 0; y < PUZZLE_SIZE; y++) {
            for (let x = 0; x < PUZZLE_SIZE; x++) {
                const pieceCanvas = document.createElement('canvas');
                pieceCanvas.width = pieceWidth;
                pieceCanvas.height = pieceHeight;
                
                const context = pieceCanvas.getContext('2d');

                context.drawImage(
                    sourceCanvas,
                    x * pieceWidth, y * pieceHeight, 
                    pieceWidth, pieceHeight,     
                    0, 0,                
                    pieceWidth, pieceHeight     
                );

                const pieceIndex = y * PUZZLE_SIZE + x;
                pieceCanvas.id = `piece-${pieceIndex}`; 
                pieceCanvas.dataset.index = pieceIndex;
                pieceCanvas.classList.add('puzzle-piece');
                pieceCanvas.draggable = true;

                pieceCanvas.addEventListener('dragstart', (e) => {
                    e.dataTransfer.setData('text/plain', e.target.id);
                    e.dataTransfer.effectAllowed = 'move';
                    setTimeout(() => {
                        e.target.style.opacity = '0.5';
                    }, 0);
                });
                pieceCanvas.addEventListener('dragend', (e) => {
                    e.target.style.opacity = '1';
                });

                pieces.push(pieceCanvas);
            }
        }

        shuffleAndDisplay(pieces);
    }

    function shuffleAndDisplay(pieces) {
        for (let i = pieces.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [pieces[i], pieces[j]] = [pieces[j], pieces[i]];
        }

        pieces.forEach(piece => {
            puzzleSource.appendChild(piece);
        });
        console.log('Puzzle podzielone, wymieszane i rozrzucone.');
    }

    function addDropListeners(zone) {
        zone.addEventListener('dragover', (e) => {
            e.preventDefault(); 
            e.dataTransfer.dropEffect = 'move';
        });

        zone.addEventListener('drop', (e) => {
            e.preventDefault();
            
            const pieceId = e.dataTransfer.getData('text/plain');
            const piece = document.getElementById(pieceId);
            
            if (!piece) return;

            if (e.target.classList.contains('puzzle-slot') && e.target.children.length === 0) {
                e.target.appendChild(piece);
            } 

            else if (e.target.id === 'puzzle-source') {
                e.target.appendChild(piece);
            }

            else if (e.target.closest('#puzzle-source') && e.target.id !== 'puzzle-source') {
                puzzleSource.appendChild(piece);
            }

            checkWinCondition();
        });
    }
    
    addDropListeners(puzzleSource);

    function checkWinCondition() {
        const slots = puzzleBoard.querySelectorAll('.puzzle-slot');
        let allCorrect = true;

        for (const slot of slots) {
            const piece = slot.querySelector('.puzzle-piece');

            if (!piece || piece.dataset.index !== slot.dataset.index) {
                allCorrect = false;
                break;
            }
        }

        if (allCorrect) {
            console.log('WYGRANA! Wszystkie elementy na swoim miejscu.');
            showWinNotification(); 
        }
    }

    function showWinNotification() {
        if ('Notification' in window && Notification.permission === 'granted') {
            const notification = new Notification('Gratulacje!', {
                body: 'Udało Ci się poprawnie ułożyć mapę!',
                icon: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png' 
            });
        } else {
            alert('GRATULACJE! Udało Ci się ułożyć puzzle!');
        }
    }

    initMap(); 
    requestNotificationPermission(); 
    createPuzzleBoardSlots(); 

    btnLocation.addEventListener('click', handleLocationClick);
    btnGetMap.addEventListener('click', handleGetMapClick);
});