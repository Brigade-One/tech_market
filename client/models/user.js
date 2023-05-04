export class User {
    constructor(name, email, password) {
        this.name = name;
        this.email = email;
        this.password = password;
    }

    validateSignUp() {
        if (this.name === '' || this.email === '' || this.password === '') {
            return false;
        } else {
            if (this.password !== document.getElementById("confirmPassword").value) {
                return false;
            }
        }
        return true;
    }

    validateSignIn() {
        if (this.email === '' || this.password === '') {
            return false;
        }
        return true;
    }

    fromJSON(json) {
        this.name = json.name;
        this.email = json.email;
        this.password = json.password;
    }

    toJSON() {
        return {
            name: this.name,
            email: this.email,
            password: this.password,
        };
    }
}