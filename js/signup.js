// Doing form validation here first to make sure we're all good

document.querySelector('#signup-form').onsubmit = function () {
    if (document.querySelector('#name').value.trim().length == 0) {
        document.querySelector('#name').classList.add('is-invalid');
    } else {
        document.querySelector('#name').classList.remove('is-invalid');
    }

    if (document.querySelector('#email').value.trim().length == 0) {
        document.querySelector('#email').classList.add('is-invalid');
    } else {
        document.querySelector('#email').classList.remove('is-invalid');
    }

    if (document.querySelector('#username').value.trim().length == 0) {
        document.querySelector('#username').classList.add('is-invalid');
    } else {
        document.querySelector('#username').classList.remove('is-invalid');
    }

    if (document.querySelector('#password').value.trim().length == 0) {
        document.querySelector('#password').classList.add('is-invalid');
    } else {
        document.querySelector('#password').classList.remove('is-invalid');
    }

    return (!document.querySelectorAll('.is-invalid').length > 0);
}