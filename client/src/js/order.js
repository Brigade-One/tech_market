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
    let user = JSON.parse(localStorage.getItem('user'));
    console.log(user);
    const item = new Item(localStorageItem.itemInstance["id"], localStorageItem.itemInstance["name"], localStorageItem.itemInstance["price"], localStorageItem.itemInstance["quantity"], localStorageItem.itemInstance["quality"], localStorageItem.itemInstance["vendorName"], localStorageItem.itemInstance["category"]);
    items.push(item)
    const order = new Order(user['email'], formData.get('full_name'), formData.get('phone_number'), formData.get('address'), formData.get('card_number'), formData.get('card_CVV'), items);
    const orderView = new OrderView();
    const orderController = new OrderController(order, orderView);
    orderController.sendOrder();
});
