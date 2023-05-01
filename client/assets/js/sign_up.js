import { User } from '../../models/user.js';

const form = document.querySelector('form');

form.addEventListener('submit', function (event) {
    event.preventDefault();

    // Get the form data
    const formData = new FormData(form);
    const user = new User(formData.get('username'), formData.get('email'), formData.get('password'));

    if (!user.validate()) {
        alert('Invalid data');
        return;
    }
    console.log(user.password);
    console.log(formData.get('confirmPassword'));
    if (user.password !== formData.get('confirmPassword')) {
        alert('Passwords do not match');
        return;
    }

    // Convert the data to a JSON string
    const jsonData = JSON.stringify(user);
    console.log(jsonData);
    // Send the data to the server using XMLHttpRequest
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../../server/server.php/sign_up');
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                alert(response.message);
                /*    window.location.href = ' ../views/index.php'; */
            } else {
                alert('Error: ' + xhr.status);
            }
        }
    };
    xhr.send(jsonData);


});
