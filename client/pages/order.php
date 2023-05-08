<html>

<head>
    <title>Order page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="http://techmarket/client/src/css/sign_pages.css">
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
        <div id="form-container">
            <div id="order-details">
                <form id="order-form">
                    <h3 id="form_label">Order Details</h3>
                    <label for="full_name">Full name:</label>
                    <input type="text" name="full_name" placeholder="Enter your full name" required>
                    <br>
                    <label for="phone_number">Phone number (0xx xxx xxxx):</label>
                    <input type="text" name="phone_number" placeholder="Enter your phone number" required
                        pattern="\d{3} \d{3} \d{4}">
                    <br>
                    <label for="address">Address:</label>
                    <input type="text" name="address" placeholder="Enter your address" required>
                    <br>
                    <label for="card_number">Card number (xxxx xxxx xxxx xxxx):</label>
                    <input type="text" name="card_number" placeholder="Enter your card number" required
                        pattern="\d{4} \d{4} \d{4} \d{4}">
                    <label for="card_CVV">CVV (xxx):</label>
                    <input type="text" name="card_CVV" placeholder="Enter your CVV" required pattern="\d{3}">
                    <br>
                    <input type="submit" value="Order">
                    <div id=status></div>
                </form>
                <div id="order-items">
                    <div id="items-container"></div>
                </div>
            </div>
            <script type="module" src="../src/js/order.js"></script>
    </main>
    <script type="module">
        import { Item } from '../models/item.js';
        let localStorageItem = JSON.parse(localStorage.getItem('item'));
        const item = new Item(localStorageItem.itemInstance["id"], localStorageItem.itemInstance["name"], localStorageItem.itemInstance["price"], localStorageItem.itemInstance["quantity"], localStorageItem.itemInstance["quality"], localStorageItem.itemInstance["vendorName"], localStorageItem.itemInstance["category"]);
        const itemContainer = document.getElementById("items-container");
        const itemHTML = `  <img src="https://via.placeholder.com/100" alt="Product Image" id="order_image">
                            <div class="item-details">
                                <h3>${item.name}</h3>
                                <p>Price: ${item.price}</p>
                            </div>   `;
        itemContainer.innerHTML = itemHTML;
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