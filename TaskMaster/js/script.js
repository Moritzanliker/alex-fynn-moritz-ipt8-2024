const button = document.getElementById('toggle-darkmode');
const body = document.body;

button.addEventListener('click', () => {
    body.classList.toggle('darkmode');
});
