<html>

<head>
    <title>Techmarket | Home</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="http://techmarket/client/src/css/products.css">
</head>

<body>

    <header>
        <div id="header"></div>
    </header>

    <body>
        <?php
        echo '<h1>TECHMARKET HOME</h1>';
        echo '<br>';
        echo '<button onclick="location.href = \'http://techmarket/client/views/sign_in.php\';" >ВХІД</button>';
        echo '<br><br>';
        echo '<button onclick="location.href = \'http://techmarket/client/views/sign_up.php\';" >РЕЄСТРАЦІЯ</button>';
        ?>
        <br><br>

        <div class="product-grid">
            <div class="product-container"></div>
        </div>
        <div id="items-container"></div>

    </body>

    <footer>
        <div id="footer">
        </div>
    </footer>


</body>

<script>
    $("#header").load("widgets/header.html");
    $("#footer").load("widgets/footer.html");

    _getAllDBItems();

    function _getAllDBItems() {
        const xhr = new XMLHttpRequest();
        xhr.open('GET', 'http://techmarket/server/server.php/get_all_items');
        xhr.send();
        xhr.onload = () => {
            if (xhr.status == 200) {
                var result = JSON.parse(xhr.response);
                _loadGrid(result);
            } else {
                console.log('Error!');
            }
        };
    }

    function _loadGrid(result) {
        fetch('widgets/product.html')
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const productWidgetTemplate = doc.querySelector('#product-widget').content;

                function createProductWidget(product) {
                    const widget = productWidgetTemplate.cloneNode(true);

                    widget.querySelector('img').src = 'https://via.placeholder.com/150';//TODO: product.imageUrl;

                    widget.querySelector('.product-name').textContent = product['name'];
                    widget.querySelector('.product-price').textContent = "₴" + product['price'];
                    console.log(product['price']);
                    widget.querySelector('.cart-button').addEventListener('click', () => {
                        alert('Product added to cart');
                    });
                    widget.querySelector('.details-button').addEventListener('click', () => {
                        alert('Details button clicked');
                    });


                    return widget;
                }

                const productContainer = document.querySelector('.product-container');
                for (let i = 0; i < 20; i++) {
                    console.log(result[i]);
                    productContainer.appendChild(createProductWidget(result[i]));
                }

            });
    }





</script>

</html>