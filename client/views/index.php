<html>

<head>
    <title>Techmarket | Home</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <header>
        <div id="header">

        </div>
    </header>

    <body>
        <?php
        echo '<h1>TECHMARKET HOME</h1>';
        echo '<br>';
        echo '<button onclick="location.href = \'http://techmarket/client/views/sign_in.php\';" >ВХІД</button>';
        echo '<br><br>';
        echo '<button onclick="location.href = \'http://techmarket/client/views/sign_up.php\';" >РЕЄСТРАЦІЯ</button>';
        ?>
    </body>

    <footer>
        <div id="footer">

        </div>
    </footer>


</body>

<script>
    $("#header").load("widgets/header.html");
    $("#footer").load("widgets/footer.html");
</script>

</html>