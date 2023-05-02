<html>

<head>
    <title>Sign Up</title>
</head>

<body>
    <h1>Sign Up</h1>
    <form id="signup-form" method="post" action="/server/server.php/sign_up">
        <label for="username">Username:</label>
        <input type="text" name="username" required>
        <br>

        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>

        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>

        <label for="confirmPassword">Confirm Password:</label>
        <input type="password" name="confirmPassword" required>
        <br>

        <input type="submit" value="Register">
    </form>
    <script type="module" src="../src/js/sign_up.js"></script>
    <div id="status">

    </div>

</html>