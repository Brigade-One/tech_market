export class OrderController {
    constructor(model, view) {
        this.model = model;
        this.view = view;
    }

    sendOrder() {
        if (this.model.validateOrder()) {
            const jsonData = JSON.stringify(this.model.toJSON());
            this.handleHttpRequest(jsonData);
        } else {
            this.view.showError("Please fill in all fields correctly.");
        }
    }


    handleHttpRequest(jsonData) {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "../../server/server.php/order");
        xhr.setRequestHeader("Content-Type", "application/json");
        xhr.onreadystatechange = () => {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    this.view.render(response.message);
                } else {
                    this.view.showError(xhr.status);
                }
            }
        };
        xhr.send(jsonData);
    }

}