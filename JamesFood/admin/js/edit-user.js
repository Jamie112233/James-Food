const createNewPassword = document.getElementById('create_new_password');
const showPassword = document.getElementById('show-password');

createNewPassword.addEventListener('click', (e) => {
    if(createNewPassword.checked) {
        showPassword.classList.remove('d-none');
    }
    else {
        showPassword.classList.add('d-none');        
    }
});