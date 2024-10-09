const tempDisplay = document.getElementById('temp-num');

document.addEventListener('DOMContentLoaded',() => {
  const apiKey = "fce96e8767fe53905beb0cce00060148";

  fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&units=metric&appid=${apiKey}`)
    .then(response => response.json())
    .then(data => {
      const temperature = data.main.temp;
      tempDisplay.textContent = `${temperature} °C`;
    })
    .catch(error => {
      console.error(error);
      tempDisplay.textContent = `Errore`;
    });
});