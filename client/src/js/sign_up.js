import { UserView } from "../../MVC/views/userView.js";
import { User } from "../../MVC/models/user.js";
import { UserController } from "../../MVC/controllers/userController.js";

const form = document.querySelector('form');

form.addEventListener('submit', function (event) {
    event.preventDefault();

    // Get the form data
    const formData = new FormData(form);
    const user = new User(formData.get('username'), formData.get('email'), formData.get('password'));
    const userView = new UserView();
    const userController = new UserController(user, userView);

    userController.handleSignUp(event);
});
