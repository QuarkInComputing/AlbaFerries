<?php
    //All basic information
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
    $FerryNo       = $_POST['ferryno'];

    //All possible passenger information
    $BookingForename = $_POST['bookingforename'];
    $BookingSurname = $_POST['bookingsurname'];
    $BookingWheelchair = isset($_POST['bookingwheelchair']);

    for($i=1; $i<$Adults; $i++){ // < NOT <= to account for person booking
        ${$AdultForename . ($i + 1)} = $_POST['adult'.($i + 1).'forename'];
        ${$Surname . ($i + 1)} = $_POST['adult'.($i + 1).'surname'];
        ${$AdultWheelchair . ($i + 1)} = $_POST['adult'.($i + 1).'wheelchair'];
    }
?>

<?php
    echo '<h2>Booking information</h2><br>';
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
    echo 'Ferry: ' . $FerryNo;
?>

<?php
    echo '<br>';
    echo '<h2>Person booking</h2><br>';
    echo 'Booking forename: '.$BookingForename . '<br>';
    echo 'Booking surname: '.$BookingSurname . '<br>';
    echo 'Booking Wheelchair: '.($BookingWheelchair ? 'true' : 'false') . '<br>';
?>

<?php
    echo '<h2>Adults</h2><br>';
    echo 'Booking forename: '.$BookingForename . '<br>';
    echo 'Booking surname: '.$BookingSurname . '<br>';
    echo 'Booking Wheelchair: '.($BookingWheelchair ? 'true' : 'false') . '<br>';
?>