import { OrderView } from '../../views/orderView.js';
import { Order } from '../../models/order.js';
import { OrderController } from '../../controllers/orderController.js';
import { Item } from '../../models/item.js';

const form = document.querySelector('#order-form');

form.addEventListener('submit', function (event) {
    event.preventDefault();
    // Get the form data
    const formData = new FormData(form);
    const items = [];
    let localStorageItem = JSON.parse(localStorage.getItem('item'));
    console.log("----localStorageItem----");
    console.log(localStorageItem);
    const item = new Item(localStorageItem.itemInstance["id"], localStorageItem.itemInstance["name"], localStorageItem.itemInstance["price"], localStorageItem.itemInstance["quantity"], localStorageItem.itemInstance["quality"], localStorageItem.itemInstance["vendorName"], localStorageItem.itemInstance["category"]);
    items.push(item)
    console.log("----BEFORE SEND----");
    console.log(items);
    const order = new Order(idGenerator(), formData.get('full_name'), formData.get('phone_number'), formData.get('address'), formData.get('card_number'), formData.get('card_CVV'), items);
    console.log(order);
    const orderView = new OrderView();
    const orderController = new OrderController(order, orderView);
    orderController.sendOrder();
});

function idGenerator() {
    return '_' + Math.random().toString(36).substr(2, 9);
}