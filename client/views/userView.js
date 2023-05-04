export class UserView{
    constructor(){
        this.statusDiv = document.getElementById("status");
    }

    render(message){
        this.statusDiv.innerHTML = message;
    }

    showError(message){
        this.statusDiv.innerHTML = "Error: " + message;
    }
}