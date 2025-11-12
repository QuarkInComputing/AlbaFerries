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

    $user = new user($_SESSION['email']);
    $user->queryDetails($DB);
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
            <a href="#" id="accountlink" class="accountlink activelink" onClick="showSection('account')">Account</a>
            <a href="#" id="ticketlink" class="accountlink" onClick="showSection('ticket')">Tickets</a>
            <a href="#" id ="settingslink" class="accountlink" onClick="showSection('settings')">Settings</a>
        </div>

        <section id="account">
            <div class="accountmain">
                <div class="thinleftbox">
                    <img class="pfp" src="./media/icon/account-large.webp">
                    <?php
                        echo '<b><p>'.$user->getForename().' '.$user->getSurname().'</p></b>';
                        echo '<p>'.$_SESSION['email'].'</p>';
                    ?>
                    <!-- 
                    Current Tickets < Ill get around to this once I have the ordering system sorted
                    Total Tickets 
                    -->
                </div>

                <div class="thickrightbox">
                    <form id="updateform" method="POST">
                        <table>

                            <!-- <label for="email">Email</label><br>
                            <?php
                            echo '<input type="text" id="email" name="Email" size="60" value="'.$user->getEmail().'"><br>';
                            ?>
                            <span id="emailError" class="error"></span>

                            <br> -->

                            <label for="forename">Forename</label><br>
                            <?php
                            echo '<input type="text" id="forename" name="Forename" size="60" value="'.$user->getForename().'"><br>';
                            ?>
                            <span id="forenameError" class="error"></span>

                            <br>

                            <label for="surname">Surname</label><br>
                            <?php
                            echo '<input type="text" id="surname" name="Surname" size="60" value="'.$user->getSurname().'"><br>';
                            ?>
                            <span id="surnameError" class="error"></span>

                            <br>

                            <label for="dob">Date Of Birth</label><br>
                            <?php
                            echo '<input type="date" id="dob" name="Dob" value="'.$user->getDOB().'"><br>';
                            ?>
                            <span id="dobError" class="error"></span>

                            <br>

                            <label for="password">Password</label><br>
                            <input type="password" id="password" name="Password" size="60"><br>
                            <span id="passwordError" class="error"></span>

                            <br>

                            <label for="phone">Phone Number (+44)</label><br>
                            <?php
                            echo '<input type="text" id="phone" name="Phone" size="60" maxlength="11" value="'.$user->getPhone().'"><br>';
                            ?>
                            <span id="phoneError" class="error"></span>

                            <br>
                                
                            <input id="applychanges" type="submit" value="Update Details" disabled="true"/>

                        </table>
                    </form>
                </div>
            </div>
        </section>

        <section id="ticket">

        </section>

        <section id="settings">

        </section>
    </body>

    <script src="./js/account.js"></script>
    <script src="./js/sections/account.js"></script>
    <script src="./js/ajax/updateHandler.js"></script>

</html>