<?php
$id = $_GET['id'];
?>

<html>

<head>
    <title>Product Details</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="http://techmarket/client/src/css/product_details.css">
</head>

<body>
    <script type="module">
        $("#header").load("widgets/header.html");
        $("#footer").load("widgets/footer.html");

        import { Item } from "http://techmarket/client/models/item.js";
        const itemId = <?php echo $id; ?>;
        const xhr = new XMLHttpRequest();
        let itemInstance;
        xhr.open('GET', `http://techmarket/server/server.php/product?id=${itemId}`);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                const responseText = xhr.responseText;
                const response = JSON.parse(responseText);
                itemInstance = new Item(response.id, response.name, response.price, response.quantity, response.quality, response.v_name, response.c_name);
                document.getElementById("product-name").innerHTML = itemInstance.name;
                document.getElementById("product-price").innerHTML = itemInstance.price + "₴";
                document.getElementById("product-vendor").innerHTML = "Vendor: " + itemInstance.vendorName;
                document.getElementById("product-category").innerHTML = "Category: " + itemInstance.category;
                document.getElementById("product-quality").innerHTML = "Quality: " + itemInstance.quality + "/5";
                document.getElementById("product-quantity").innerHTML = "Quantity: " + itemInstance.quantity + " in stock";
            }
        };
        xhr.send();
        $("#buy-now-btn").on("click", function () {
            localStorage.setItem("item", JSON.stringify({ itemInstance }));
            window.location.href = "http://techmarket/client/pages/order.php";
        });
    </script>
    <header>
        <div id="header"></div>
    </header>
    <main>
        <div id="product-container">
            <div id="image-container">
                <img id="product-image" src="https://via.placeholder.com/300" alt="Product Image">
            </div>
            <div id="product-details">
                <h1 id="product-name"></h1>
                <h2 id="product-price"></h2>
                <p id="product-vendor"></p>
                <p id="product-category"></p>
                <p id="product-quality"></p>
                <p id="product-quantity"></p>
            </div>
        </div>
        <div id='buttons'>
            <button>
                <a href="http://techmarket/client/pages/cart.php">Add to cart</a>
            </button>
            <button id="buy-now-btn">
                Buy now
            </button>
        </div>
    </main>
    <footer>
        <div id="footer">
        </div>
    </footer>
</html>