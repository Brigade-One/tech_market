import { UserView } from "../../views/userView.js";
import { User } from "../../models/user.js";
import { UserController } from "../../controllers/userController.js";

const form = document.querySelector('#sign-form');

form.addEventListener('submit', function (event) {
    event.preventDefault();
    // Get the form data
    const formData = new FormData(form);
    const user = new User('', formData.get('email'), formData.get('password'));
    const userView = new UserView();
    const userController = new UserController(user, userView);
    userController.handleSignIn();
});
