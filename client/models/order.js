export class Order {
    constructor(email, name, phoneNumber, address, cardNumber, cardCVV, items) {

        this.email = email;
        this.name = name;
        this.phoneNumber = phoneNumber;
        this.address = address;
        this.cardNumber = cardNumber;
        this.cardCVV = cardCVV;
        this.items = items;
    }

    validateOrder() {
        if (this.name === "" || this.phoneNumber === "" || this.address === "" || this.cardNumber === "" || this.cardCVV === "" || this.items.length === 0) {
            return false;
        }
        return true;
    }

    fromJSON(json) {
        this.email = json.email;
        this.name = json.name;
        this.phoneNumber = json.phoneNumber;
        this.address = json.address;
        this.cardNumber = json.cardNumber;
        this.cardCVV = json.cardCVV;
        this.items = json.items;
    }
    toJSON() {
        return {

            email: this.email,
            name: this.name,
            phoneNumber: this.phoneNumber,
            address: this.address,
            cardNumber: this.cardNumber,
            cardCVV: this.cardCVV,
            items: this.items
        };
    }

}