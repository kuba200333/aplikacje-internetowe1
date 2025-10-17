let zadania = [];
let frazaSzukania = '';
const kontenerListy = document.getElementById('listaZadan');

function zaladujZadania() {
    const zapisaneZadania = localStorage.getItem('zadaniaLABB');
    if (zapisaneZadania) {
        zadania = JSON.parse(zapisaneZadania);
    }
    rysujListe();
}

function zapiszZadania() {
    localStorage.setItem('zadaniaLABB', JSON.stringify(zadania));
}

function walidujZadanie(text, date) {
    if (text.length < 3 || text.length > 255) {
        alert('Zadanie musi mieć od 3 do 255 znaków.');
        return false;
    }
    if (date) {
        const dzis = new Date();
        dzis.setHours(0, 0, 0, 0);
        const dataZadania = new Date(date);
        if (dataZadania < dzis) {
            alert('Data wykonania musi być w przyszłości lub pusta.');
            return false;
        }
    }
    return true;
}

function dodajZadanie(text, date, inputTekst, inputData) {
    if (!walidujZadanie(text, date)) {
        return;
    }

    const noweZadanie = {
        id: Date.now(),
        text: text,
        date: date,
        done: false
    };

    zadania.push(noweZadanie);
    zapiszZadania();
    rysujListe();

    inputTekst.value = '';
    inputData.value = '';
}

function usunZadanie(id) {
    zadania = zadania.filter(zadanie => zadanie.id !== id);
    zapiszZadania();
    rysujListe();
}

function zmienStatus(id) {
    const zadanie = zadania.find(z => z.id === id);
    if (zadanie) {
        zadanie.done = !zadanie.done;
        zapiszZadania();
        rysujListe();
    }
}

function zapiszEdycje(id, nowyText, nowaData) {
    const itemElement = document.querySelector(`.zadanie-item[data-id="${id}"]`);
    if (itemElement && !itemElement.querySelector('.edycja-input')) {
        return; 
    }
    
    if (!walidujZadanie(nowyText, nowaData)) {
        alert("Walidacja nieudana, pozostań w edycji.");
        return false;
    }
    
    const zadanie = zadania.find(z => z.id === id);
    if (zadanie) {
        zadanie.text = nowyText;
        zadanie.date = nowaData;
        zapiszZadania();
        rysujListe(); 
        return true;
    }
    return false;
}

function filtrujZadania() {
    if (frazaSzukania.length < 2) {
        return zadania;
    }
    const fraza = frazaSzukania.toLowerCase();
    return zadania.filter(zadanie => zadanie.text.toLowerCase().includes(fraza));
}

function rysujListe() {
    kontenerListy.innerHTML = '';
    const listaDoWyswietlenia = filtrujZadania();

    listaDoWyswietlenia.forEach(zadanie => {
        const item = document.createElement('div');
        item.className = 'zadanie-item';
        item.dataset.id = zadanie.id;

        let tekstZadania = zadanie.text;
        const jestUkonczone = zadanie.done ? 'ukonczone' : '';
        
        // Podświetlanie frazy
        if (frazaSzukania.length >= 2) {
            const regex = new RegExp(`(${frazaSzukania})`, 'gi');
            tekstZadania = tekstZadania.replace(regex, '<span class="highlight">$1</span>');
        }

    item.innerHTML = `
        <input type="checkbox" ${zadanie.done ? 'checked' : ''} data-akcja="zmienStatus">
        <span class="tekst-zadania ${jestUkonczone}" data-akcja="edycja">${tekstZadania}</span>
        <span class="data-zadania ${jestUkonczone}" data-akcja="edycja">${zadanie.date || ''}</span>
        <button class="przycisk-usun" data-akcja="usun">🗑️</button>`;
        
        kontenerListy.appendChild(item);
    });
}

function wejdzDoEdycji(itemElement, zadanie) {
    if (itemElement.querySelector('.edycja-input')) return; 

    const id = zadanie.id;
    const obecnyTekstElement = itemElement.querySelector('.tekst-zadania');
    const obecnaDataElement = itemElement.querySelector('.data-zadania');
    const przyciskUsun = itemElement.querySelector('.przycisk-usun');

    obecnyTekstElement.innerHTML = `<input type="text" class="edycja-input" value="${zadanie.text}" id="inputEdycja-${id}">`;
    
    obecnaDataElement.innerHTML = `<input type="date" class="edycja-data" value="${zadanie.date || ''}" id="dataEdycja-${id}">`;
    
    przyciskUsun.style.display = 'none';
    
    const inputTekst = document.getElementById(`inputEdycja-${id}`);
    const inputData = document.getElementById(`dataEdycja-${id}`);

    const zapiszPoBlur = (e) => {
        setTimeout(() => {
            if (document.activeElement !== inputTekst && document.activeElement !== inputData) {
                const zapisano = zapiszEdycje(id, inputTekst.value.trim(), inputData.value);
                if (!zapisano) {
                    inputTekst.focus(); 
                }
            }
        }, 50);
    };

    inputTekst.addEventListener('blur', zapiszPoBlur);
    inputData.addEventListener('blur', zapiszPoBlur);

    inputTekst.focus();
}


function ustawListenery() {
    document.getElementById('przyciskDodaj').addEventListener('click', () => {
        const inputTekst = document.getElementById('inputTekst');
        const inputData = document.getElementById('inputData');
        dodajZadanie(inputTekst.value.trim(), inputData.value, inputTekst, inputData);
    });

    document.getElementById('inputSzukaj').addEventListener('input', (e) => {
        frazaSzukania = e.target.value.trim();
        rysujListe();
    });

    kontenerListy.addEventListener('click', (e) => {
        const target = e.target;
        const akcja = target.dataset.akcja;
        const item = target.closest('.zadanie-item');
        
        if (!item) return;
        const id = parseInt(item.dataset.id);
        const zadanie = zadania.find(z => z.id === id);

        switch (akcja) {
            case 'usun':
                usunZadanie(id);
                break;
            case 'zmienStatus':
                break;
            case 'edycja':
                if (zadanie) {
                    wejdzDoEdycji(item, zadanie);
                }
                break;
        }
    });
    
    kontenerListy.addEventListener('change', (e) => {
        if (e.target.type === 'checkbox' && e.target.dataset.akcja === 'zmienStatus') {
            const item = e.target.closest('.zadanie-item');
            if (item) {
                zmienStatus(parseInt(item.dataset.id));
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    ustawListenery();
    zaladujZadania();
});