<html>

<head>
    <title>Sign In</title>
</head>

<body>
    <h1>Sign In</h1>
    <form id="signin-form" method="post" action="/server/server.php/sign_in">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>

        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>

        <input type="submit" value="Увійти">
    </form>
    <script type="module" src="../src/js/sign_in.js"></script>

    <div id="status">

    </div>

</html>