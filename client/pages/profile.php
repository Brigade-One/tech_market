<html>

<head>
    <title>Your profile</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="http://techmarket/client/src/css/sign_pages.css">
    <link rel="stylesheet" href="http://techmarket/client/src/css/profile.css">
</head>

<body>
    <script>
        const user = JSON.parse(localStorage.getItem("user"));
        if (!user) {
            window.location.href = "http://techmarket/client/pages/sign_in.php";
        }
    </script>
    <header>
        <div id="header"></div>
    </header>
    <main>
        <div class="card">
            <div class="card-content">
                <div class="profile">
                    <div class="profile-image-container">
                        <img class="profile-image" src="https://via.placeholder.com/150" alt="Profile Image">
                    </div>
                    <h2 class="profile-name"> </h2>
                    <p class="profile-email"></p>
                </div>
            </div>
            <div id="buttons"><button id="cart-btn">Your cart</button>
                <button id="sign-out-btn">Sign Out</button>
            </div>
        </div>

    </main>

    <footer>
        <div id="footer">
        </div>
    </footer>

    <script>
        $("#header").load("widgets/header.html");
        $("#footer").load("widgets/footer.html");

        // Get the user data from localStorage and display it in the main section

        const profileName = document.querySelector(".profile-name");
        const profileEmail = document.querySelector(".profile-email");
        profileName.textContent = user.name;
        profileEmail.textContent = user.email;
        $("#sign-out-btn").on("click", function () {
            localStorage.removeItem("user");
            window.location.href = "http://techmarket/client/pages/sign_in.php";
        });
    </script>

</html>