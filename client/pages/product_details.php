<?php
$id = $_GET['id'];
?>

<html>

<head>
    <title>Product Details</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="http://techmarket/client/src/css/sign_pages.css">
    <link rel="stylesheet" href="http://techmarket/client/src/css/profile.css">
    <link rel="stylesheet" href="http://techmarket/client/src/css/product_details.css">
</head>

<body>
    <script>
        const itemId = <?php echo $id; ?>;
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `http://techmarket/server/server.php/product?id=${itemId}`);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status === 200) {
                const responseText = xhr.responseText;
                const parser = new DOMParser();
                const parsedHTML = parser.parseFromString(responseText, 'text/html');
                const main = document.querySelector('#product-details');
                main.appendChild(parsedHTML.querySelector('#product-details'));
            }
        };
        xhr.send();

    </script>
    <header>
        <div id="header"></div>
    </header>
    <main>
        <div id="product-container">
            <div id="image-container">
                <img id="product-image" src="https://via.placeholder.com/300" alt="Product Image">
            </div>
            <div id="product-details"></div>
        </div>
        <div id='buttons'>
            <button>
                <a href="http://techmarket/client/pages/cart.php">Add to cart</a>
            </button> <button>
                <a href="http://techmarket/client/pages/cart.php">Buy now</a>
            </button>
        </div>
    </main>

    <footer>
        <div id="footer">
        </div>
    </footer>

    <script>
        $("#header").load("widgets/header.html");
        $("#footer").load("widgets/footer.html");
    </script>

</html>