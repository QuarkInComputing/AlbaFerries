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
    //Checkout spesefic vars
    $Day = $_GET['day'];
    $FerryNo = $_GET['ferry'];
    $DepartureTime = $_GET['departuretime'];
    $ArrivalTime = $_GET['arrivaltime'];
    $Cost = $_GET['cost'];
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
            <div id="favicon"><script src="./js/inner/favicon.js"></script></div>

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

        <!-- <div id="debug">
            <h2>Debug</h2>
            <?php
                echo 'TicketType: ' . $TicketType . '<br>';
                echo 'Adults: ' . $Adults . '<br>';
                echo 'Teens: ' . $Teens . ' <br>';
                echo 'Children: ' . $Children . '<br>';
                echo 'From: ' . $From . '<br>';
                echo 'To: ' . $To . '<br>';
                echo 'Departure: ' . $Departure . '<br>';
                echo 'Return: ' . $Return . '<br>';
                echo 'Departure Time: ' . $DepartureTime . '<br>';
                echo 'Arrival Time: ' . $ArrivalTime . '<br>';
                echo 'Day: ' . $Day . '<br>';
                echo 'Ferry: '. $FerryNo;
            ?>
        </div> -->

        <div class="checkout">
            <div class="thinleftbox">
                <form id="checkoutform" method="POST" action="./php/booking/buyTickets.php">
                    <h4>Please enter your details</h4>
                    <p style="font-size: 0.6vw;"><i>These details are for the person purchasing the ticket</i></p>
                        <?php
                        echo '<div class="checkoutfields">';
                            echo '<div>';
                                echo '<h4 class="fieldtitle">Forename</h4>';
                                echo '<input type="text" id="bookingforename" name="bookingforename" value="'.$user->getForename().'" maxlength="30">';
                            echo '</div>';

                            echo '<div>';
                                echo '<h4 class="fieldtitle">Surname</h4>';
                                echo '<input type="text" id="bookingsurname" name="bookingsurname" value="'.$user->getSurname().'" maxlength="40">';
                            echo '</div>';

                            echo '<div>';
                                echo '<h4 class="fieldtitle">Requires Wheelchair Access?</h4>';
                                echo '<input type="checkbox" id="wheelchair" name="bookingwheelchair">';
                            echo '</div>';
                        echo '</div>';

                        for ($i = 1; $i < $Adults; $i++) { // < NOT <= to account for person booking
                            echo '<h4>Adult '.($i+1).'</h4>';
                            echo '<div class="checkoutfields">';
                                echo '<div>';
                                    echo '<h4 class="fieldtitle">Forename</h4>';
                                    echo '<input type="text" id="adult'.($i+1).'forename" name="adult'.($i+1).'forename" maxlength="30">';
                                echo '</div>';

                                echo '<div>';
                                    echo '<h4 class="fieldtitle">Surname</h4>';
                                    echo '<input type="text" id="adult'.($i+1).'surname" name="adult'.($i+1).'surname" maxlength="40">';
                                echo '</div>';

                                echo '<div>';
                                    echo '<h4 class="fieldtitle">Requires Wheelchair Access?</h4>';
                                    echo '<input type="checkbox" id="wheelchair" name="adult'.($i+1).'wheelchair">';
                                echo '</div>';
                            echo '</div>';
                        }

                        for ($i = 1; $i <= $Teens; $i++) { 
                            echo '<h4>Teen '.$i.'</h4>';
                            echo '<div class="checkoutfields">';
                                echo '<div>';
                                    echo '<h4 class="fieldtitle">Forename</h4>';
                                    echo '<input type="text" id="teen'.$i.'forename" name="teen'.$i.'forename" maxlength="30">';
                                echo '</div>';

                                echo '<div>';
                                    echo '<h4 class="fieldtitle">Surname</h4>';
                                    echo '<input type="text" id="teen'.$i.'surname" name="teen'.$i.'surname" maxlength="40">';
                                echo '</div>';

                                echo '<div>';
                                    echo '<h4 class="fieldtitle">Requires Wheelchair Access?</h4>';
                                    echo '<input type="checkbox" id="wheelchair" name="teen'.$i.'wheelchair">';
                                echo '</div>';
                            echo '</div>';
                        }

                        for ($i = 1; $i <= $Children; $i++) {
                            echo '<h4>Child '.$i.'</h4>';
                            echo '<div class="checkoutfields">';
                                echo '<div>';
                                    echo '<h4 class="fieldtitle">Forename</h4>';
                                    echo '<input type="text" id="child'.$i.'forename" name="child'.$i.'forename" maxlength="30">';
                                echo '</div>';

                                echo '<div>';
                                    echo '<h4 class="fieldtitle">Surname</h4>';
                                    echo '<input type="text" id="child'.$i.'surname" name="child'.$i.'surname" maxlength="40">';
                                echo '</div>';

                                echo '<div>';
                                    echo '<h4 class="fieldtitle">Requires Wheelchair Access?</h4>';
                                    echo '<input type="checkbox" id="wheelchair" name="child'.$i.'wheelchair">';
                                echo '</div>';
                            echo '</div>';
                        }
                        ?>
                </form>
            </div>

            <div class="thickrightbox">
                <h1>Your Order</h1>
                <?php
                    echo '<h2>'.$From.' ('.$DepartureTime.') - '.$To.' ('.$ArrivalTime.')';
                    echo '<h2>'.$Departure.' ('.$Day.')</h2>';
                    echo '<h3>Adults</h3>';
                    echo '<p>'.$Adults.'</p>';
                    if(!empty($Teens)){
                        echo '<h3>Teens</h3>';
                        echo '<p>'.$Teens.'</p>';
                    }
                    if(!empty($Children)){
                        echo '<h3>Children</h3>';
                        echo '<p>'.$Children.'</p>';
                    }
                    echo '<h3>Total Cost</h3>';
                    echo '<p>£'.$Cost.'</p>';
                ?>
                <button class="purchasebutton" id="purchasebutton" disabled="true" type="submit" form="checkoutform">Purchase</button>
            </div>
        </div>
    </body>

    <script src="./js/checkout.js"></script>
</html>