<html>

<head>
    <title>Order page</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="http://techmarket/client/src/css/sign_pages.css">
</head>

<body>
    <script>
        const item = JSON.parse(localStorage.getItem("item"));
    </script>
    <header>
        <div id="header"></div>
    </header>
    <main>
        <h1>Order page</h1>
        <!--   <div id="order-details">
            <div id="order-items">
                <h3>Item</h3>
                <div id="items-container"></div>
            </div>
            <div id="order-summary"  -->
        <div id="form-container">
            <form id="order-form">
                <h3 id="form_label">Order Details</h3>
                <label for="full_name">Full name:</label>
                <input type="text" name="full_name" placeholder="Enter your full name" required>
                <br>
                <label for="phone_number">Phone number:</label>
                <input type="text" name="phone_number" placeholder="Enter your phone number" required>
                <br>
                <label for="address">Address:</label>
                <input type="text" name="address" placeholder="Enter your address" required>
                <br>
                <label for="card_number">Card number:</label>
                <input type="text" name="card_number" placeholder="Enter your card number" required>
                <label for="card_CVV">CVV:</label>
                <input type="text" name="card_CVV" placeholder="Enter your CVV" required>
                <br>
                <input type="submit" value="Order">

                <div id=status></div>
            </form>
        </div>

        <script type="module" src="../src/js/order.js"></script>
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