<?php
$search = $_GET['search'];
?>

<html>

<head>
    <title>
        Search - TechMarket
    </title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="http://techmarket/client/src/css/products.css">
    <link rel="stylesheet" href="http://techmarket/client/src/css/search_page.css">

</head>
<header>
    <div id="header"></div>
</header>

<body>
    <div id="search-content">
        <div id="filter">
            <fieldset>
                <legend>Category</legend>
                <label>
                    <input type="checkbox" class="filter-checkbox" id="filter-by-display" name="display">
                    Display
                </label>
                <label>
                    <input type="checkbox" class="filter-checkbox" id="filter-by-videocard" name="gpu">
                    GPU
                </label>
                <label>
                    <input type="checkbox" class="filter-checkbox" id="filter-by-cpu" name="cpu">
                    CPU
                </label>
                <label>
                    <input type="checkbox" class="filter-checkbox" id="filter-by-memory" name="memory">
                    Memory
                </label>
            </fieldset>
            <fieldset>
                <legend>Quality</legend>
                <input type="radio" name="quality" id="quality" value="1,2,3,4,5" checked>
                All
                </label>
                <input type="radio" name="quality" id="quality" value="4,5">
                Excellent
                </label>
                <label>
                    <input type="radio" name="quality" id="quality" value="3,4">
                    Very good
                </label>
                <label>
                    <input type="radio" name="quality" id="quality" value="2,3">
                    Good
                </label>
                <label>
                    <input type="radio" name="quality" id="quality" value="1">
                    Poor
                </label>

            </fieldset>
            <fieldset>
                <legend>Price</legend>
                <label>
                    From <input type="number" name="minPrice" id="minPrice" min="0" max="1000000" step="100"
                        value="1000">
                </label>
                <label>
                    To <input type="number" name="maxPrice" id="maxPrice" min="1000000" max="2000000" step="1000"
                        value="100000">
                </label>

            </fieldset>

            <button id="filter-button">Filter</button>
        </div>

        <div class="product-grid">
            <div class="count-items"></div>
            <div class="product-container"></div>
        </div>
    </div>
</body>

<footer>
    <div id="footer">
    </div>
</footer>

<script>
    $("#header").load("widgets/header.html");
    $("#footer").load("widgets/footer.html");
    getSearchResults();
    $(document).ready(function () {});
    $(document).ready(function () {
        $('#searchField').val("<?php echo $search; ?>");
        $('#searchField').on('input', function () {
            changeUrlQuery($(this).val());
        });
        $('#filter-button').click(function () {
            getSearchResults();
        });
        $('#searchField').keypress(function (e) {
            if (e.which == 13) {
                getSearchResults();
            }
        });
    });



    function getSearchResults() {
        var searchQuery = window.location.search.split('=')[1];

        // Get the selected filters
        var filters = getFilters();
        console.log(filters);
        // Construct the search query
        var searchUrl = 'http://techmarket/server/server.php/search?query=' + searchQuery;
        if (filters.category.length > 0) {
            searchUrl += '&category=' + filters.category.join(',');
        }
        if (filters.quality) {
            searchUrl += '&quality=' + filters.quality;
        }
        if (filters.minPrice) {
            searchUrl += '&minPrice=' + filters.minPrice;
        }
        if (filters.maxPrice) {
            searchUrl += '&maxPrice=' + filters.maxPrice;
        }

        // Send the search request
        var xhr = new XMLHttpRequest();
        xhr.open('GET', searchUrl);
        xhr.onload = function () {
            if (xhr.status === 200) {
                if (xhr.responseText == '') {
                    $('.product-container').html('<h1>No results found</h1>');
                    $('.count-items').html('    <h2>0 items found</h2>');
                    return;
                }
                var searchResults = JSON.parse(xhr.responseText);
                $('.count-items').html('    <h2>' + searchResults.length + ' items found</h2>');
                _loadGrid(searchResults);
            } else {
                console.log('Request failed.  Returned status of ' + xhr.status);
            }
        };
        xhr.send();
    }


    function _getDBItems() {
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
            setTimeout(_getDBItems, 10000);
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



    function getFilters() {
        var filters = {};

        // Get the selected category filters
        filters.category = [];
        var categoryCheckboxes = document.querySelectorAll('.filter-checkbox');
        for (var i = 0; i < categoryCheckboxes.length; i++) {
            if (categoryCheckboxes[i].checked) {
                filters.category.push(categoryCheckboxes[i].name);
            }
        }
        // Get the selected quality filter
        filters.quality = $('input[name="quality"]:checked').val();
        filters.minPrice = $('#minPrice').val();
        filters.maxPrice = $('#maxPrice').val();
        return filters;
    }

    function changeUrlQuery(searchQuery) {
        // Clear the query string from the URL
        let url = new URL(window.location.href);
        url.searchParams.delete('search');
        url.searchParams.append('search', searchQuery);
        history.replaceState(null, '', url);
    }


</script>

</html>