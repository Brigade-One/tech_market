<html>

<head>
    <title>Sign Up</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="http://techmarket/client/src/css/sign_pages.css">
</head>

<body>


    <header>
        <div id="header"></div>
    </header>

    <form id="sign-form" method="post" action="/server/server.php/sign_up">
        <h3 id="form_label">Sign Up</h3>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" placeholder="Enter your name" required>
        <br>

        <label for="email">Email:</label>
        <input type="email" name="email" name="email" placeholder="Enter your email address" required>
        <br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" placeholder="Create a password" required>
        <br>

        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" name="confirmPassword" id="confirmPassword" placeholder="Confirm your password"
            required>
        <br>

        <input type="submit" value="Register">
    </form>
    <script type="module" src="../src/js/sign_up.js"></script>
    <div id="status">

    </div>

    <footer>
        <div id="footer">
        </div>
    </footer>

    <script>
        $("#header").load("widgets/header.html");
        $("#footer").load("widgets/footer.html");
    </script>

</html>