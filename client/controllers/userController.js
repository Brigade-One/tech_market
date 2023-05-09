export class UserController {
    constructor(model, view) {
        this.model = model;
        this.view = view;
    }

    handleSignUp(confirmPass) {
        if (this.model.validateSignUp(confirmPass)) {
            const jsonData = JSON.stringify(this.model.toJSON());
            this.handleHttpRequest(jsonData, "sign_up");
        } else {
            this.view.showError("Please fill in all fields correctly.");
        }
    }

    handleSignIn() {
        if (this.model.validateSignIn()) {
            const jsonData = JSON.stringify(this.model.toJSON());
            this.handleHttpRequest(jsonData, "sign_in");
        } else {
            this.view.showError("Please fill in all fields correctly.");
        }
    }

    handleHttpRequest(jsonData, url) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../../server/server.php/" + url);
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    const user = response.user;
                    if (response.success === true) {
                        localStorage.setItem("user", user);
                        localStorage.setItem("token", response.token);
                    }
                    this.view.render(response.message);
                    // Add a 1.5 second delay before redirecting to the index page
                    setTimeout(function () {
                        window.location.href = "../pages/index.php";
                    }, 1500); 
                } else {
                    this.view.showError(xhr.status);
                }
            }
        };
        xhr.send(jsonData);
    }

}
