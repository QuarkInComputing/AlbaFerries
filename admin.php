<!-- 22010875 | 18/11/2025 -->
<!-- Admin Page -->

<?php
include("./php/db/DbConnect.php");

if ($_COOKIE['admin'] != "true"){
    header("Location: index.html");
}
?>

<!DOCTYPE html>
<html>

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Admin Page</title>

        <!--Stylesheets-->
        <link id="theme-desktop" rel="stylesheet" href="css/stylesheet.css"> 
        <link id="theme-mobile" rel="stylesheet" media="screen and (max-width: 992px)" href="css/mobile.css">

        <link rel="stylesheet" href="css/sections/booking.css">

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
                <p class="brandingtext"><b>Alba Wildlife</b> Admin Page</p>
            </div>
            <div class="usernav">
                <div id="admin"></div>
                <button class="userbutton"><img class="usericon uipad" src="./media/icon/language.webp">Language</button> <!-- Make this dynamic to selected language later -->
                <a href="account.php"><button class="userbutton"><img class="usericon" src="./media/icon/account.webp"></button></a>
            </div>
        </div>

        <div class="nav">
            <img class="logo" src="./media/logo.webp">
            <div class="navlinks"><script src="./js/inner/navigation.js"></script></div>
        </div>

        <div class="adminferry">
            <h1>Active Ferries</h1>
            <table>
                <tr>
                    <th>Ferry No</th>
                    <th>Travels From</th>
                    <th>Travels To</th>
                    <th>Current Capacity</th>
                    <th>At Capacity?</th>
                </tr>
                <?php
                    $stmt = $DB->prepare("SELECT DISTINCT FerryNo FROM AlbaBooking");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {

                        $Ferry = $row['FerryNo'];

                        $ferrystmt = $DB->prepare("SELECT RouteDepart, RouteDestination FROM AlbaRoute LEFT JOIN AlbaFerry ON AlbaFerry.RouteNo=AlbaRoute.RouteNo WHERE FerryNo =?");
                        $ferrystmt->bind_param("i", $Ferry);
                        $ferrystmt->execute();
                        $ferrystmt->store_result();
                        $ferrystmt->bind_result($Depart, $Destination);
                        $ferrystmt->fetch();
                        $ferrystmt->close();

                        $ferrystmt = $DB->prepare("SELECT COUNT(p.PassengerNo) AS 'PassengerCount'
                                                    FROM AlbaFerry f
                                                    LEFT JOIN AlbaBooking b ON b.FerryNo = f.FerryNo
                                                    LEFT JOIN AlbaPassenger p ON p.BookingNo = b.BookingNo
                                                    WHERE f.FerryNo = ?");
                        $ferrystmt->bind_param("i", $Ferry);
                        $ferrystmt->execute();
                        $ferrystmt->store_result();
                        $ferrystmt->bind_result($Count);
                        $ferrystmt->fetch();
                        $ferrystmt->close();

                        echo '<tr>';
                        echo '<td>'.$Ferry.'</td>';
                        echo '<td>'.$Depart.'</td>';
                        echo '<td>'.$Destination.'</td>';
                        echo '<td>'.$Count.'</td>';
                        if($Count >= 30){
                            echo '<td style="color: red;">Yes</td>';
                        } else {
                            echo '<td style="color: green;">No</td>';
                        }
                        echo '</tr>';
                    }

                    $stmt->close();
                ?>
            </table>
            
            <h1>Ferries with <i>no</i> bookings</h1>
            <table>
                <tr>
                    <th>Ferry No</th>
                    <th>Travels From</th>
                    <th>Travels To</th>
                </tr>
                <?php
                    $stmt = $DB->prepare("SELECT FerryNo FROM AlbaFerry WHERE FerryNo NOT IN(SELECT FerryNo FROM AlbaBooking) ORDER BY FerryNo ASC");
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        $Ferry = $row['FerryNo'];

                        $ferrystmt = $DB->prepare("SELECT RouteDepart, RouteDestination FROM AlbaRoute LEFT JOIN AlbaFerry ON AlbaFerry.RouteNo=AlbaRoute.RouteNo WHERE FerryNo =?");
                        $ferrystmt->bind_param("i", $Ferry);
                        $ferrystmt->execute();
                        $ferrystmt->store_result();
                        $ferrystmt->bind_result($Depart, $Destination);
                        $ferrystmt->fetch();
                        $ferrystmt->close();

                        echo '<tr>';
                        echo '<td>'.$Ferry.'</td>';
                        echo '<td>'.$Depart.'</td>';
                        echo '<td>'.$Destination.'</td>';
                        echo '</tr>';
                    }
                ?>
            </table>
        </div>
    </body>
</html>