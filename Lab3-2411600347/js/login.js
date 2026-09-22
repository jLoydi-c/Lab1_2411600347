// grabbing the elements needed
const loginBtn = document.getElementById('loginBtn');
const usernameInput = document.getElementById('username');
const passwordInput = document.getElementById('password');
const errorAlert = document.getElementById('errorAlert');

//demo credentials for the exercise pang demo purposes
const correctUsername = 'admin';
const correctPassword = 'admin123';

loginBtn.addEventListener('click', function () {
    const username = usernameInput.value.trim();
    const password = passwordInput.value.trim();

    // hardcoded demo credentials for this exercise
    if (username !== correctUsername) {
        errorAlert.textContent = 'Username not found. Please check and try again.';
        errorAlert.classList.remove('d-none');
    } else if (password !== correctPassword) {
        errorAlert.textContent = 'Incorrect password. Please try again.';
        errorAlert.classList.remove('d-none');
    } else {
        // both match, save the username so dashboard.js knows who's logged in
        errorAlert.classList.add('d-none');
        localStorage.setItem('loggedInUser', username);
        window.location.href = 'dashboard.html';
    }
});

// so users can just hit enter instead of clicking the button
passwordInput.addEventListener('keyup', function (e) {
    if (e.key === 'Enter') {
        loginBtn.click();
    }
});