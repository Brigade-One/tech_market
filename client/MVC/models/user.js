export class User {
    constructor(name, email, password) {
        this.name = name;
        this.email = email;
        this.password = password;
    }

    validateSignUp(confirmPassword) {
        if (this.name === '' || this.email === '' || this.password === '') {
            return false;
        }else{
            console.log(this.password);
            //console.log(confirmPassword);
            console.log(document.getElementById("confirmPassword").value);
            if (this.password !== document.getElementById("confirmPassword").value) {
                //window.alert('Passwords do not match');
                return false;
            }
        }

        return true;
    }

    fromJSON(json){
        this.name = json.name;
        this.email = json.email;
        this.password = json.password;
    }

    toJSON(){
        return{
            name: this.name,
            email: this.email,
            password: this.password,
        };
    }
}