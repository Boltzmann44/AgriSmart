document.addEventListener("DOMContentLoaded", function() {
    // Seleziona gli slider
    const fieldSlider = document.querySelector('.fields-bar .slider');
    const equipSlider = document.querySelector('.equip-bar .slider');
    
    // Seleziona i bottoni
    const fieldPrev = document.querySelector('.fields-bar .prev');
    const fieldNext = document.querySelector('.fields-bar .next');
    const equipPrev = document.querySelector('.equip-bar .prev');
    const equipNext = document.querySelector('.equip-bar .next');

    // Funzione di scorrimento
    function slideLeft(slider) {
        slider.scrollBy({
            top: 0,
            left: -300, // Valore negativo per scorrere a sinistra
            behavior: 'smooth'
        });
    }

    function slideRight(slider) {
        slider.scrollBy({
            top: 0,
            left: 300, // Valore positivo per scorrere a destra
            behavior: 'smooth'
        });
    }

    // Assegna le funzioni di scorrimento ai bottoni
    fieldPrev.addEventListener('click', () => slideLeft(fieldSlider));
    fieldNext.addEventListener('click', () => slideRight(fieldSlider));
    equipPrev.addEventListener('click', () => slideLeft(equipSlider));
    equipNext.addEventListener('click', () => slideRight(equipSlider));
});
