const apiKey = '4ca8aa15d7312c84bc1afd1b85bd79b9'; 

document.getElementById('getWeatherBtn').addEventListener('click', function() {
    const city = document.getElementById('cityInput').value;
    
    if(!city) {
        alert("Wpisz nazwę miasta!");
        return;
    }

    document.getElementById('currentWeatherSection').classList.remove('hidden');
    document.getElementById('forecastSection').classList.remove('hidden');
    
    document.getElementById('current-weather').innerHTML = 'Pobieranie danych...';
    document.getElementById('forecast-container').innerHTML = 'Pobieranie danych...';

    getCurrentWeatherXHR(city);
    getForecastFetch(city);
});

//XMLHttpRequest
function getCurrentWeatherXHR(city) {
    const url = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric&lang=pl`;
    
    const xhr = new XMLHttpRequest();
    xhr.open("GET", url, true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            const data = JSON.parse(xhr.responseText);
        
            console.log("Otrzymana odpowiedź Current Weather (XHR):", data);

            // Generowanie HTML
            const html = `
                <div class="weather-main">${data.name}, ${data.sys.country}</div>
                <img src="https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png" alt="Ikona pogody">
                <div><strong>${data.main.temp.toFixed(1)} °C</strong></div>
                <div>Odczuwalna: ${data.main.feels_like.toFixed(1)} °C</div>
                <div style="text-transform: capitalize;">${data.weather[0].description}</div>
                <div style="margin-top:10px; font-size:0.9em; color:#555">Wilgotność: ${data.main.humidity}% | Ciśnienie: ${data.main.pressure} hPa</div>
            `;
            document.getElementById('current-weather').innerHTML = html;
        } else {
            console.error("Błąd XHR:", xhr.statusText);
            document.getElementById('current-weather').innerHTML = `<p style="color:red">Nie znaleziono miasta (XHR: ${xhr.status})</p>`;
        }
    };

    xhr.onerror = function() {
        console.error("Błąd sieci XHR");
        document.getElementById('current-weather').innerHTML = "Błąd połączenia sieciowego.";
    };

    xhr.send();
}

// forecast

function getForecastFetch(city) {
    const url = `https://api.openweathermap.org/data/2.5/forecast?q=${city}&appid=${apiKey}&units=metric&lang=pl`;

    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log("Otrzymana odpowiedź Forecast (Fetch):", data);

            const container = document.getElementById('forecast-container');
            container.innerHTML = ''; 

            data.list.forEach(item => {
                const dateObj = new Date(item.dt * 1000);

                const dateString = dateObj.toLocaleDateString('pl-PL', { 
                    weekday: 'short', 
                    day: 'numeric',
                    month: 'numeric'
                });
                const timeString = dateObj.toLocaleTimeString('pl-PL', {
                    hour: '2-digit',
                    minute: '2-digit'
                });

                const card = document.createElement('div');
                card.className = 'forecast-card';
                
                card.innerHTML = `
                    <div class="forecast-date">${dateString}</div>
                    <div class="forecast-date" style="font-weight:bold">${timeString}</div>
                    <img src="https://openweathermap.org/img/wn/${item.weather[0].icon}.png" alt="icon">
                    <div class="forecast-temp">${item.main.temp.toFixed(1)} °C</div>
                    <div style="font-size:0.8em; text-transform: capitalize;">${item.weather[0].description}</div>
                `;
                container.appendChild(card);
            });
        })
        .catch(error => {
            console.error("Błąd Fetch:", error);
            document.getElementById('forecast-container').innerHTML = `<p style="color:red">Błąd pobierania prognozy: ${error.message}</p>`;
        });
}