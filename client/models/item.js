export class Item {
    constructor(id, name, price, quantity, quality, vendorName, category, imgUrl) {
        this.id = id;
        this.name = name;
        this.price = price;
        this.quantity = quantity;
        this.quality = quality;
        this.vendorName = vendorName;
        this.category = category;
        this.imgUrl = imgUrl;
    }
    toJSON() {
        return {
            id: this.id,
            name: this.name,
            price: this.price,
            quantity: this.quantity,
            quality: this.quality,
            vendorName: this.vendorName,
            category: this.category,
            imgUrl: this.imgUrl
        };
    }
    fromJSON(json) {
        this.id = json.id;
        this.name = json.name;
        this.price = json.price;
        this.quantity = json.quantity;
        this.quality = json.quality;
        this.vendorName = json.vendorName;
        this.category = json.category;
        this.imgUrl = json.imgUrl;
    }
}