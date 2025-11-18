<!-- 22010875 | 18/11/2025 -->
<!-- Responsible for inputting booking data to "buy" tickets -->

<?php

    session_start();

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include __DIR__ . "/../db/DbConnect.php";
    include __DIR__ . "/../user/user.php";
    include __DIR__ . "/ticketManager.php";

    $user = new user($_SESSION['email']);
    $user->queryDetails($DB);

    //Ticket Information
    $TicketType    = $_POST['tickettype'];
    $Adults        = $_POST['adults'];
    $Teens         = $_POST['teens'];
    $Children      = $_POST['children'];
    $From          = $_POST['from'];
    $To            = $_POST['to'];
    $Departure     = $_POST['departure'];
    $Return        = $_POST['return'];
    $DepartureTime = $_POST['departuretime'];
    $ArrivalTime   = $_POST['arrivaltime'];
    $Day           = $_POST['day'];
    $Cost          = $_POST['cost'];
    $FerryNo       = $_POST['ferryno'];
    $FerryNo2      = $_POST['ferryno2'];

    //Passenger Information
    $BookingForename   = $_POST['bookingforename'];
    $BookingSurname    = $_POST['bookingsurname'];
    $BookingWheelchair = isset($_POST['bookingwheelchair']);

    if($TicketType == "single"){
        for ($i = 1; $i < $Adults; $i++) { // < NOT <= to account for person booking
            ${'AdultForename' . ($i + 1)}   = $_POST['adult' . ($i + 1) . 'forename'];
            ${'AdultSurname' . ($i + 1)}    = $_POST['adult' . ($i + 1) . 'surname'];
            ${'AdultWheelchair' . ($i + 1)} = isset($_POST['adult' . ($i + 1) . 'wheelchair']);
        }

        for ($i = 1; $i <= $Teens; $i++) {
            ${'TeenForename' . $i}   = $_POST['teen' . $i . 'forename'];
            ${'TeenSurname' . $i}    = $_POST['teen' . $i . 'surname'];
            ${'TeenWheelchair' . $i} = isset($_POST['teen' . $i . 'wheelchair']);
        }

        for ($i = 1; $i <= $Children; $i++) {
            ${'ChildForename' . $i}   = $_POST['child' . $i . 'forename'];
            ${'ChildSurname' . $i}    = $_POST['child' . $i . 'surname'];
            ${'ChildWheelchair' . $i} = isset($_POST['child' . $i . 'wheelchair']);
        }

        $Status = "Booked";
        $BookingEmail = $user->getEmail();
        $stmt = $DB->prepare("INSERT INTO AlbaBooking (CustomerEmail, FerryNo, BookingStatus, BookingDate, BookingCost) VALUES
                            (?, ?, ?, ?, ?);");
        $stmt->bind_param("sissi", $BookingEmail, $FerryNo, $Status, $Departure, $Cost);
        $stmt->execute();

        $BookingNo = $DB->insert_id;

        $stmt->close();

        //Adult making booking
        $AgeBracket = "Adult";
        $stmt = $DB->prepare("INSERT INTO AlbaPassenger (PassengerForename, PassengerSurname, PassengerAgeBracket, PassengerWheelchair, BookingNo) VALUES
                            (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssii", $BookingForename, $BookingSurname, $AgeBracket, $BookingWheelchair, $BookingNo);
        $stmt->execute();
        
        $stmt->close();

        //Other Adults
        if($Adults > 1 ){
            for($i = 1; $i < $Adults; $i++) {
                $stmt = $DB->prepare("INSERT INTO AlbaPassenger (PassengerForename, PassengerSurname, PassengerAgeBracket, PassengerWheelchair, BookingNo) VALUES
                            (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssii", ${'AdultForename' . ($i + 1)}, ${'AdultSurname' . ($i + 1)}, $AgeBracket, ${'AdultWheelchair' . ($i + 1)}, $BookingNo);
                $stmt->execute();

                $stmt->close();
            }
        }

        //Teens
        $AgeBracket = "Teen";
        for($i = 1; $i <=$Teens; $i++) {
            $stmt = $DB->prepare("INSERT INTO AlbaPassenger (PassengerForename, PassengerSurname, PassengerAgeBracket, PassengerWheelchair, BookingNo) VALUES
                            (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssii", ${'TeenForename' . $i}, ${'TeenSurname' . $i}, $AgeBracket, ${'TeenWheelchair' . $i}, $BookingNo);
            $stmt->execute();

            $stmt->close();
        }

        //Children
        $AgeBracket = "Child";

        for($i = 1; $i <=$Children; $i++) {
            $stmt = $DB->prepare("INSERT INTO AlbaPassenger (PassengerForename, PassengerSurname, PassengerAgeBracket, PassengerWheelchair, BookingNo) VALUES
                            (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssii", ${'ChildForename' . $i}, ${'ChildSurname' . $i}, $AgeBracket, ${'ChildWheelchair' . $i}, $BookingNo);
            $stmt->execute();

            $stmt->close();
        }
        $DB->close();

        header('Location: ../../account.php');
    }

    if($TicketType == "return"){
        for ($i = 1; $i < $Adults; $i++) { // < NOT <= to account for person booking
        ${'AdultForename' . ($i + 1)}   = $_POST['adult' . ($i + 1) . 'forename'];
        ${'AdultSurname' . ($i + 1)}    = $_POST['adult' . ($i + 1) . 'surname'];
        ${'AdultWheelchair' . ($i + 1)} = isset($_POST['adult' . ($i + 1) . 'wheelchair']);
        }

        for ($i = 1; $i <= $Teens; $i++) {
            ${'TeenForename' . $i}   = $_POST['teen' . $i . 'forename'];
            ${'TeenSurname' . $i}    = $_POST['teen' . $i . 'surname'];
            ${'TeenWheelchair' . $i} = isset($_POST['teen' . $i . 'wheelchair']);
        }

        for ($i = 1; $i <= $Children; $i++) {
            ${'ChildForename' . $i}   = $_POST['child' . $i . 'forename'];
            ${'ChildSurname' . $i}    = $_POST['child' . $i . 'surname'];
            ${'ChildWheelchair' . $i} = isset($_POST['child' . $i . 'wheelchair']);
        }

        $Status = "Booked";
        $BookingEmail = $user->getEmail();
        $BookingEmail = $user->getEmail();
        $stmt = $DB->prepare("INSERT INTO AlbaBooking (CustomerEmail, FerryNo, BookingStatus, BookingDate, BookingCost) VALUES
                            (?, ?, ?, ?, ?);");
        $stmt->bind_param("sissi", $BookingEmail, $FerryNo, $Status, $Departure, $Cost);
        $stmt->execute();
        $stmt->close();

        $BookingNo = $DB->insert_id;

        if($Adults > 1 ){
            for($i = 1; $i < $Adults; $i++) {
                $stmt = $DB->prepare("INSERT INTO AlbaPassenger (PassengerForename, PassengerSurname, PassengerAgeBracket, PassengerWheelchair, BookingNo) VALUES
                            (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssii", ${'AdultForename' . ($i + 1)}, ${'AdultSurname' . ($i + 1)}, $AgeBracket, ${'AdultWheelchair' . ($i + 1)}, $BookingNo);
                $stmt->execute();

                $stmt->close();
            }
        }

        $AgeBracket = "Teen";
        for($i = 1; $i <=$Teens; $i++) {
            $stmt = $DB->prepare("INSERT INTO AlbaPassenger (PassengerForename, PassengerSurname, PassengerAgeBracket, PassengerWheelchair, BookingNo) VALUES
                            (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssii", ${'TeenForename' . $i}, ${'TeenSurname' . $i}, $AgeBracket, ${'TeenWheelchair' . $i}, $BookingNo);
            $stmt->execute();

            $stmt->close();
        }

        $AgeBracket = "Child";

        for($i = 1; $i <=$Children; $i++) {
            $stmt = $DB->prepare("INSERT INTO AlbaPassenger (PassengerForename, PassengerSurname, PassengerAgeBracket, PassengerWheelchair, BookingNo) VALUES
                            (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssii", ${'ChildForename' . $i}, ${'ChildSurname' . $i}, $AgeBracket, ${'ChildWheelchair' . $i}, $BookingNo);
            $stmt->execute();

            $stmt->close();
        }

        $stmt = $DB->prepare("INSERT INTO AlbaBooking (CustomerEmail, FerryNo, BookingStatus, BookingDate, BookingCost) VALUES
                            (?, ?, ?, ?, ?);");
        $stmt->bind_param("sissi", $BookingEmail, $FerryNo2, $Status, $Return, $Cost);
        $stmt->execute();
        $stmt->close();

        $BookingNo = $DB->insert_id;    

        if($Adults > 1 ){
            for($i = 1; $i < $Adults; $i++) {
                $stmt = $DB->prepare("INSERT INTO AlbaPassenger (PassengerForename, PassengerSurname, PassengerAgeBracket, PassengerWheelchair, BookingNo) VALUES
                            (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssii", ${'AdultForename' . ($i + 1)}, ${'AdultSurname' . ($i + 1)}, $AgeBracket, ${'AdultWheelchair' . ($i + 1)}, $BookingNo);
                $stmt->execute();

                $stmt->close();
            }
        }

        $AgeBracket = "Teen";
        for($i = 1; $i <=$Teens; $i++) {
            $stmt = $DB->prepare("INSERT INTO AlbaPassenger (PassengerForename, PassengerSurname, PassengerAgeBracket, PassengerWheelchair, BookingNo) VALUES
                            (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssii", ${'TeenForename' . $i}, ${'TeenSurname' . $i}, $AgeBracket, ${'TeenWheelchair' . $i}, $BookingNo);
            $stmt->execute();

            $stmt->close();
        }

        $AgeBracket = "Child";

        for($i = 1; $i <=$Children; $i++) {
            $stmt = $DB->prepare("INSERT INTO AlbaPassenger (PassengerForename, PassengerSurname, PassengerAgeBracket, PassengerWheelchair, BookingNo) VALUES
                            (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssii", ${'ChildForename' . $i}, ${'ChildSurname' . $i}, $AgeBracket, ${'ChildWheelchair' . $i}, $BookingNo);
            $stmt->execute();

            $stmt->close();
        }

        $DB->close();

        header('Location: ../../account.php');
    }
?>

<!-- <?php
    echo '<h2>Booking information</h2><br>';
    echo 'TicketType: ' . $TicketType . '<br>';
    echo 'Adults: ' . $Adults . '<br>';
    echo 'Teens: ' . $Teens . '<br>';
    echo 'Children: ' . $Children . '<br>';
    echo 'From: ' . $From . '<br>';
    echo 'To: ' . $To . '<br>';
    echo 'Departure: ' . $Departure . '<br>';
    echo 'Return: ' . $Return . '<br>';
    echo 'Departure Time: ' . $DepartureTime . '<br>';
    echo 'Arrival Time: ' . $ArrivalTime . '<br>';
    echo 'Day: ' . $Day . '<br>';
    echo 'Ferry: ' . $FerryNo . '<br>';
    echo 'BookingNo: ' . $BookingNo . '<br>';
?>

<?php
    echo '<h2>Person booking</h2><br>';
    echo 'Booking forename: ' . $BookingForename . '<br>';
    echo 'Booking surname: ' . $BookingSurname . '<br>';
    echo 'Booking Wheelchair: ' . ($BookingWheelchair ? 'true' : 'false') . '<br>';
?>

<?php
    if ($Adults > 1) {
        echo '<h2>Adults</h2><br>';
        for ($i = 1; $i < $Adults; $i++) {
            echo 'Adult' . ($i + 1) . ' forename: ' . ${'AdultForename' . ($i + 1)} . '<br>';
            echo 'Adult' . ($i + 1) . ' surname: ' . ${'AdultSurname' . ($i + 1)} . '<br>';
            echo 'Adult' . ($i + 1) . ' wheelchair: ' . (${'AdultWheelchair' . ($i + 1)} ? 'true' : 'false') . '<br>';
        }
    }
?>

<?php
    if ($Teens > 0) {
        echo '<h2>Teens</h2><br>';
        for ($i = 1; $i <= $Teens; $i++) {
            echo 'Teen' . $i . ' forename: ' . ${'TeenForename' . $i} . '<br>';
            echo 'Teen' . $i . ' surname: ' . ${'TeenSurname' . $i} . '<br>';
            echo 'Teen' . $i . ' wheelchair: ' . (${'TeenWheelchair' . $i} ? 'true' : 'false') . '<br>';
        }
    }
?>

<?php
    if ($Children > 0) {
        echo '<h2>Children</h2><br>';
        for ($i = 1; $i <= $Children; $i++) {
            echo 'Child' . $i . ' forename: ' . ${'ChildForename' . $i} . '<br>';
            echo 'Child' . $i . ' surname: ' . ${'ChildSurname' . $i} . '<br>';
            echo 'Child' . $i . ' wheelchair: ' . (${'ChildWheelchair' . $i} ? 'true' : 'false') . '<br>';
        }
    }
?> -->