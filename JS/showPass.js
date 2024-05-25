function togglePasswordVisibility() {
    var passwordInput = document.querySelector('.password-input');
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
    } else {
        passwordInput.type = "password";
    }
}
