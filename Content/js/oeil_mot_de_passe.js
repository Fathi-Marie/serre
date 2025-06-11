function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eye-toggle');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.src = "Content/Images/eye_closed_icon.png"; // Change to eye closed
    } else {
        passwordInput.type = 'password';
        eyeIcon.src = "Content/Images/eye_icon.png"; // Change back to eye open
    }
}