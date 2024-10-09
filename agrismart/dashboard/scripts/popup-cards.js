document.addEventListener('DOMContentLoaded', function() {   
    const rmvBtn = document.getElementById('upbar-remove-btn');
    const cardRmvBtns = document.getElementsByClassName('card-delete');
    const addBtn = document.getElementById('upbar-add-btn');
    const popup = document.getElementById('popup');
    const separators = document.getElementsByClassName('card-separator');

    addBtn.addEventListener('click', function() {
        popup.classList.remove('disabled');
    });

    
    rmvBtn.addEventListener('click', function() {
        Array.from(cardRmvBtns).forEach(btn => {
            btn.classList.remove('disabled');
        });
        Array.from(separators).forEach(sep => {
            sep.classList.add('extend');
        });
    });
    
    document.addEventListener('click', function(event) {
        if (!popup.contains(event.target) && event.target !== addBtn) {
            popup.classList.add('disabled');
        }
        if (!Array.from(cardRmvBtns).some(btn => btn.contains(event.target)) && event.target !== rmvBtn) {
            Array.from(cardRmvBtns).forEach(btn => {
                btn.classList.add('disabled');
            });
            Array.from(separators).forEach(sep => {
                sep.classList.remove('extend');
            });
        }
        
    });
});