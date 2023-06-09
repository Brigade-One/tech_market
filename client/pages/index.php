<html>

<head>
    <title>Techmarket | Home</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="http://techmarket/client/src/css/products.css">
    <link rel="stylesheet" href="http://techmarket/client/src/css/banner.css">
</head>

<body>

    <header>
        <div id="header"></div>
    </header>

    <main>
        <div class="banner">
            <img src="http://techmarket/client/assets/images/banner-image.jpg" alt="Banner Image">
            <div class="banner-text">
                <h1>Welcome to Techmarket</h1>
                <p>Shop the latest tech gadgets and accessories at affordable prices.</p>
                <a href="http://techmarket/client/pages/search_page.php?search=" class=" banner-button">Shop Now</a>
            </div>
        </div>
    </main>


    <body>
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
                console.log('Error loading grid!');
            }
            // Our long polling
            setTimeout(_getAllDBItems, 2000);
        };
    }

    function _loadGrid(result) {
        fetch('widgets/product.html')
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const productWidgetTemplate = doc.querySelector('#product-widget').content;

                const productContainer = document.querySelector('.product-container');
                productContainer.innerHTML = '';
                for (let i = 0; i < result.length; i++) {
                    productContainer.appendChild(createProductWidget(result[i]));
                }

                function createProductWidget(product) {
                    const widget = productWidgetTemplate.cloneNode(true);

                    widget.querySelector('img').src = product['imgUrl'];

                    widget.querySelector('.product-name').textContent = product['name'];
                    widget.querySelector('.product-price').textContent = "₴" + product['price'];

                    widget.querySelector('.cart-button').addEventListener('click', () => {
                        alert('Product added to cart');
                    });
                    widget.querySelector('.details-button').addEventListener('click', () => {
                        window.location.href = "http://techmarket/client/pages/product_details.php?id=" + product['id'];

                    });

                    return widget;
                }
            });

    }

</script>

</html>