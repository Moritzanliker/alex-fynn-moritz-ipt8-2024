// Script to handle dropdown menu visibility
document.querySelectorAll('.dropdown-trigger').forEach(trigger => {
    trigger.addEventListener('click', () => {
        const dropdownContent = trigger.nextElementSibling;
        dropdownContent.classList.toggle('show');
    });
});

document.addEventListener('click', function (event) {
    const isClickInside = event.target.closest('.dropdown');
    if (!isClickInside) {
        document.querySelectorAll('.dropdown-content').forEach(dropdown => {
            dropdown.classList.remove('show');
        });
    }
});


const button = document.getElementById('toggle-darkmode');
const body = document.body;

button.addEventListener('click', () => {
    body.classList.toggle('darkmode');
});
