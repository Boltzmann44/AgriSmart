const form = document.getElementById('form');
const result = document.getElementById('result');

form.addEventListener('submit', function(e) {
  e.preventDefault();   // Impedisco il comportamento predefinito dell'invio del modulo (La pagina non si ricarica)
  const formData = new FormData(form);
  const object = Object.fromEntries(formData);
  const json = JSON.stringify(object);
  result.innerHTML = "Invio..."

    fetch('https://api.web3forms.com/submit', { //richiesta HTTP all'api che si occupa di inviare mail
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: json
        })
        .then(async (response) => {
            let json = await response.json();
            if (response.status == 200) {
                result.innerHTML = "Email inviata correttamente"
            } else {
                console.log(response);
                result.innerHTML = "Invio non riuscito";
            }
        })
        .catch(error => {
            console.log(error);
            result.innerHTML = "Qualcosa è andato storto!";
        })
        .then(function() {
            form.reset();
            setTimeout(() => {
                result.style.display = "none";
            }, 3000);
        });
});