document.getElementById('spinButton').addEventListener('click', function() {
    const slots = document.querySelectorAll('.slot');
    slots.forEach(slot => {
        slot.classList.add('spin');
    });

    setTimeout(() => {
        slots.forEach(slot => {
            slot.classList.remove('spin');
        });
    }, 1000); 
});