import { User } from '../../models/user.js';

const form = document.querySelector('form');

form.addEventListener('submit', function (event) {
    event.preventDefault();

    // Get the form data
    const formData = new FormData(form);
    const user = new User('', formData.get('email'), formData.get('password'));

    // Convert the data to a JSON string
    const jsonData = JSON.stringify(user);

    // Send the data to the server using XMLHttpRequest
    const xhr = new XMLHttpRequest();
    xhr.open('POST', '../../server/server.php/sign_in');
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                alert(response.message);
                /*  window.location.href = ' ../views/index.php'; */
            } else {
                const response = JSON.parse(xhr.responseText);
                console.log(response);
                alert('Error: ' + xhr.status + ' ' + response.message);
            }
        }
    };
    xhr.send(jsonData);


});
