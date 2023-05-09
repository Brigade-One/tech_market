<html>

<head>
    <title>Order history</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="http://techmarket/client/src/css/order_history.css">
</head>

<body>
    <script>
        if (localStorage.getItem('user') === null) {
            window.location.href = "http://techmarket/client/pages/sign_in.php";
        }
    </script>
    <header>
        <div id="header"></div>
    </header>
    <main>
        <div id="items-container">
            <h1>Order history</h1>
            <div id="items">

            </div>
        </div>
    </main>
    <script type="module">
        import { Item } from '../models/item.js';
        import { Order } from '../models/order.js';
        /* let localStorageItem = JSON.parse(localStorage.getItem('item'));
         const item = new Item(localStorageItem.itemInstance["id"], localStorageItem.itemInstance["name"], localStorageItem.itemInstance["price"], localStorageItem.itemInstance["quantity"], localStorageItem.itemInstance["quality"], localStorageItem.itemInstance["vendorName"], localStorageItem.itemInstance["category"]);
         const itemContainer = document.getElementById("items-container");
         const itemHTML = `  <img src="https://via.placeholder.com/100" alt="Product Image" id="order_image">
                             <div class="item-details">
                                 <h3>${item.name}</h3>
                                 <p>Price: ${item.price}</p>
                             </div>   `;
         itemContainer.innerHTML = itemHTML; */
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'http://techmarket/server/server.php/get_order_history?token=' + localStorage.getItem('token'));
        xhr.send();
        xhr.onload = function () {
            const itemsContainer = document.getElementById("items");
            if (xhr.status != 200) {
                itemsContainer.innerHTML = `History is empty.`;
            } else {
                const orders = JSON.parse(xhr.response).orders.reverse();
                if (orders.length === 0) {
                    itemsContainer.innerHTML = `History is empty.`;
                    return;
                }
                orders.forEach(order => {
                    var id = order.id;
                    var order = new Order(order.email, order.name, order.phoneNumber, order.address, order.cardNumber, order.cardCVV, order.items);
                    console.log(order);
                    const item = order.items[0];
                    const orderHTML = `<div class="order">
                                            <h3>Order ID: ${id}</h3>
                                            <p>Name of the buyer: ${order.name}</p>
                                            <p>Phone number: ${order.phoneNumber}</p>
                                            <p>Address: ${order.address}</p>

                                            <div class="order-items">
                                                <h3>Items:</h3>
                                                <div class="item">
                                                    <img src="https://via.placeholder.com/100" alt="Product Image" id="order_image">
                                                    <div class="item-details">
                                                        <h3>${item.name}</h3>
                                                        <p>Price: ${item.price}</p>
                                                    </div>
                                                </div>
                                        </div>`;
                    itemsContainer.innerHTML += orderHTML;
                });
            }
        };

    </script>
    <footer>
        <div id="footer">
        </div>
    </footer>

    <script>
        $("#header").load("widgets/header.html");
        $("#footer").load("widgets/footer.html");
    </script>

</html>