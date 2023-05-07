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
    const item = new Item(JSON.parse(localStorage.getItem('item')).id, JSON.parse(localStorage.getItem('item')).name, JSON.parse(localStorage.getItem('item')).price, JSON.parse(localStorage.getItem('item')).quantity, JSON.parse(localStorage.getItem('item')).quality, JSON.parse(localStorage.getItem('item')).vendorName, JSON.parse(localStorage.getItem('item')).category);
    items.push(item)

    const order = new Order(idGenerator(), formData.get('full_name'), formData.get('phone_number'), formData.get('address'), formData.get('card_number'), formData.get('card_CVV'), items);
    console.log(order);
    const orderView = new OrderView();
    const orderController = new OrderController(order, orderView);
    orderController.sendOrder();
});

function idGenerator() {
    return '_' + Math.random().toString(36).substr(2, 9);
}