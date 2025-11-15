<?php
    session_start();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if(!isset($_SESSION['email'])) {
        header('Location: login.html');
    }

    include("./php/db/DbConnect.php");
    include("./php/user/user.php");
    include("./php/booking/ticketManager.php");

    $user = new user($_SESSION['email']);
    $user->queryDetails($DB);

    include("./php/booking/getVars.php");
    $ticketManager = new ticketManager($TicketType, $Adults, $Children, $From, $To, $Departure, $Return);
?>

<!DOCTYPE html>
<html>

    <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <title>Booking</title>

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

        <a class="backlink" href="..">Back to booking</a>

        <div id="debug">
            <h1>DEBUG</h1>
            <?php
                echo 'TicketType: ' . $TicketType . '<br>';
                echo 'Adults: ' . $Adults . '<br>';
                echo 'Children: ' . $Children . '<br>';
                echo 'From: ' . $From . '<br>';
                echo 'To: ' . $To . '<br>';
                echo 'Departure: ' . $Departure . '<br>';
                echo 'Return: ' . $Return . '<br>';
                echo 'Day: ' . $ticketManager->getDayVar();
            ?>
        </div>        

        <div id="tickets" class="tickets">
            <table>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>Leaves</th>
                    <th>Arrives</th>
                    <th>Price</th>
                    <th></th> <!-- <a href="#">Buy</a> -->
                </tr>
                <?php
                    $ticketManager->findTickets($DB);
                ?>
            </table>
        </div>
    </body>
</html>