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
        echo '<button onclick="location.href = \'http://techmarket/client/pages/sign_in.php\';" >ВХІД</button>';
        echo '<br><br>';
        echo '<button onclick="location.href = \'http://techmarket/client/pages/sign_up.php\';" >РЕЄСТРАЦІЯ</button>';
        ?>

        <div class="product-grid">
            <div class="product-container"></div>
        </div>

    </body>

    <footer>
        <div id="footer">
        </div>
    </footer>


</body>

<script>
    $("#header").load("widgets/header.html");
    $("#footer").load("widgets/footer.html");


    fetch('widgets/product.html')
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const productWidgetTemplate = doc.querySelector('#product-widget').content;

            function createProductWidget(product) {
                const widget = productWidgetTemplate.cloneNode(true);

                widget.querySelector('img').src = product.imageUrl;
                widget.querySelector('.product-name').textContent = product.name;
                widget.querySelector('.product-price').textContent = product.description;
                widget.querySelector('.cart-button').addEventListener('click', () => {
                    alert('Product added to cart');
                });
                widget.querySelector('.details-button').addEventListener('click', () => {
                    alert('Details button clicked');
                });


                return widget;
            }

            const newProduct = {
                imageUrl: 'https://via.placeholder.com/150',
                name: 'Product name',
                description: '$100'
            };

            const productContainer = document.querySelector('.product-container');
            for (let i = 0; i < 20; i++) {
                productContainer.appendChild(createProductWidget(newProduct));
            }

        });




</script>

</html>