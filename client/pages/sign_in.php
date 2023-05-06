<html>

<head>
    <title>Sign In</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="http://techmarket/client/src/css/sign_pages.css">
</head>

<body>
    <header>
        <div id="header"></div>
    </header>

    <form id="sign-form" method="post" action="/server/server.php/sign_in">
        <h3 id="form_label">Sign In</h3>
        <label for="email">Email:</label>
        <input type="email" name="email" placeholder="Enter your email" required>
        <br>

        <label for="password">Password:</label>
        <input type="password" name="password" placeholder="Enter your password" required>
        <br>

        <input type="submit" value="Увійти">
        
        <div id="status">

        </div>
    </form>
    <script type="module" src="../src/js/sign_in.js"></script>



    <footer>
        <div id="footer">
        </div>
    </footer>

    <script>
        $("#header").load("widgets/header.html");
        $("#footer").load("widgets/footer.html");
    </script>

</html>