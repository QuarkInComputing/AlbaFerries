<?php
    session_start();

    if(!isset($_SESSION['email'])) {
        header('Location: login.html');
    }
?>

<!DOCTYPE html>
<html>

    <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <title>Account Page</title>

            <!--Stylesheets-->
            <link id="theme-desktop" rel="stylesheet" href="css/stylesheet.css"> 
            <link id="theme-mobile" rel="stylesheet" media="screen and (max-width: 992px)" href="css/mobile.css">

            <!--Favicon-->

            <!--Google Font-->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Istok+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="topnav">
            <div class="branding">
                <p class="brandingtext"><b>Alba Wildlife</b> Ferries</p>
            </div>
            <div class="usernav">
                <button class="userbutton"><img class="usericon uipad" src="./media/icon/language.webp">Language</button> <!-- Make this dynamic to selected language later -->
                <a href="account.php"><button class="userbutton"><img class="usericon" src="./media/icon/account.webp"></button></a>
            </div>
        </div>

        <div class="nav">
            <img class="logo" src="./media/logo.webp">
            <div class="navlinks"><script src="./js/inner/navigation.js"></script></div>
        </div>

        <div class="accountnav">
            <a href="#" class="accountlink">Account</a>
            <a href="#" class="accountlink">Tickets</a>
            <a href="#" class="accountlink">Settings</a>
        </div>
    </body>

</html>