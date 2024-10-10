

const button = document.getElementById('toggle-darkmode');
const body = document.body;

button.addEventListener('click', () => {
    body.classList.toggle('darkmode');
});



// Login
let isLogin = true;

function toggleForm() {
  const formTitle = document.getElementById('form-title');
  const submitBtn = document.getElementById('submit-btn');
  const switchText = document.getElementById('switch-text');
  
  isLogin = !isLogin;

  if (isLogin) {
    formTitle.textContent = 'Login';
    submitBtn.textContent = 'Login';
    switchText.innerHTML = `Don't have an account? <a href="#" onclick="toggleForm()">Register</a>`;
  } else {
    formTitle.textContent = 'Register';
    submitBtn.textContent = 'Register';
    switchText.innerHTML = `Already have an account? <a href="#" onclick="toggleForm()">Login</a>`;
  }
}

function submitForm() {
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;
  const errorMessage = document.getElementById('error-message');

  // Simple client-side validation
  if (username === '' || password === '') {
    errorMessage.textContent = 'Please fill out all fields';
    return;
  }

  // AJAX to send data to the server (assuming a PHP backend)
  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'auth.php', true);
  xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
  
  xhr.onload = function() {
    if (this.status === 200) {
      const response = JSON.parse(this.responseText);
      if (response.success) {
        window.location.href = 'welcome.html'; // Redirect on success
      } else {
        errorMessage.textContent = response.message;
      }
    }
  };

  xhr.send(`username=${username}&password=${password}&action=${isLogin ? 'login' : 'register'}`);
}
