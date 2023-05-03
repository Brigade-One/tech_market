export class UserController{
    constructor(model, view){
        this.model = model;
        this.view = view;
        const form = document.getElementById("signup-form");
        //form.addEventListener("submit", this.handleSignUp.bind(this));
    }

    handleSignUp(event){
        //event.preventDefault();
        const name = this.model.name;//document.getElementById("name").value;
        const email = this.model.email;//document.getElementById("email").value;
        const password = this.model.password;//document.getElementById("password").value;
        if(this.model.validateSignUp()){
            const jsonData = JSON.stringify(this.model.toJSON());
            console.log(jsonData);
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "../../server/server.php/sign_up");
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.onreadystatechange = () =>{
                if (xhr.readyState === XMLHttpRequest.DONE){
                    if(xhr.status === 200){
                        const response = JSON.parse(xhr.responseText);
                        this.view.render(response.message);
                    }else {
                        this.view.showError(xhr.status);
                    }
                }
            };
            xhr.send(jsonData);
        } else {
            this.view.showError("Name, email and password fields are required.")
        }
    }


}