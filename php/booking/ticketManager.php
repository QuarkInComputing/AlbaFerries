<!-- 22010875 | 18/11/2025 -->
<!-- Object used to help with repetetive queries required as part of the booking system -->

<?php
class ticketManager
{
    private $TicketType;
    private $Adults;
    private $Teens;
    private $Children;
    private $From;
    private $To;
    private $Departure;
    private $Return;
    private $Day;
    private $Day2;

    public function __construct($TicketType, $Adults, $Teens, $Children, $From, $To, $Departure, $Return)
    {
        $this->TicketType = $TicketType;
        $this->Adults     = $Adults;
        $this->Teens      = $Teens;
        $this->Children   = $Children;
        $this->From       = $From;
        $this->To         = $To;
        $this->Departure  = $Departure;
        if (! empty($Return)) {
            $this->Return = $Return;
        }

        $this->Day = $this->getDay($Departure);
        if($TicketType == "return") {
            $this->Day2 = $this->getDay($Return);
        }
    }

    public function getDay($Date)
    {
        $DayNo = date('w', strtotime($Date));

        switch ($DayNo) {
            case 1:
                return "Mon";
                break;
            case 2:
                return "Tue";
                break;
            case 3:
                return "Wed";
                break;
            case 4:
                return "Thu";
                break;
            case 5:
                return "Fri";
                break;
            case 6:
                return "Sat";
                break;
            case 7:
                return "Sun";
                break;
        }
    }

    //This whole system is probably quite messy, I created this by hand-writing sql, chugging a coffee and praying
    public function findTickets($DB)
    {
        if ($this->TicketType == "single") {
            // Check if routes exist and if they are available in the users selected timeframe
            $stmt = $DB->prepare("
                    SELECT FerryNo
                    FROM AlbaFerry
                    WHERE RouteNo=(SELECT RouteNo FROM AlbaRoute WHERE RouteDepart=? AND RouteDestination=?)
                    AND FerryStart < ?
                    AND FerryEnd > ?
                    AND FerryDay=?
                    ");

            $stmt->bind_param("sssss", $this->From, $this->To, $this->Departure, $this->Departure, $this->Day);
            $stmt->execute();

            $stmt->store_result();
            $stmt->bind_result($FerryNo);

            $Ferries = [];

            while ($stmt->fetch()) {
                // echo '<p>Ferry='.$FerryNo.'</p>';
                $Ferries[] = $FerryNo;
            }

            $stmt->close();

            // Get all existing routes within that time frame, for each route see if they are at seat capacity
            foreach ($Ferries as $Ferry) {
                // Count passengers
                $stmt = $DB->prepare("
                        SELECT COUNT(p.PassengerNo) AS 'PassengerCount'
                        FROM AlbaFerry f
                        LEFT JOIN AlbaBooking b ON b.FerryNo = f.FerryNo
                        LEFT JOIN AlbaPassenger p ON p.BookingNo = b.BookingNo
                        WHERE f.FerryNo = ?
                    ");

                $stmt->bind_param("i", $Ferry);
                $stmt->execute();
                $stmt->bind_result($Count);
                $stmt->fetch();
                $stmt->close();

                if ($Count + ($this->Adults + $this->Children) >= 30) {
                    $this->echoNoTicket();
                } else {
                    $stmt = $DB->prepare("
                            SELECT FerryDepart, FerryArrive, FerrySingle
                            FROM AlbaFerry
                            WHERE FerryNo = ?
                        ");
                    $stmt->bind_param("i", $Ferry);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($Depart, $Arrive, $Price);
                    $stmt->fetch();
                    $stmt->close();

                    $TotalPrice = $Price * $this->Adults + (($this->Children * 7) + ($this->Teens * 10));

                    $this->echoTicket($Depart, $Arrive, $TotalPrice, $Ferry, "single", "", "", 0);
                }
            }
        }

        if ($this->TicketType == "return"){
            // Check if routes exist and if they are available in the users selected timeframe
            $stmt = $DB->prepare("
                    SELECT FerryNo
                    FROM AlbaFerry
                    WHERE RouteNo=(SELECT RouteNo FROM AlbaRoute WHERE RouteDepart=? AND RouteDestination=?)
                    AND FerryStart < ?
                    AND FerryEnd > ?
                    AND FerryDay=?
                    ");
            $stmt->bind_param("sssss", $this->From, $this->To, $this->Departure, $this->Departure, $this->Day);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($FerryNo);

            $Ferries = [];
            while ($stmt->fetch()) {
                $Ferries[] = $FerryNo;
            }
            $stmt->close();

            $stmt = $DB->prepare("
                        SELECT FerryNo
                        FROM AlbaFerry
                        WHERE RouteNo=(SELECT RouteNo FROM AlbaRoute WHERE RouteDepart=? AND RouteDestination=?)
                        AND FerryStart < ?
                        AND FerryEnd > ?
                        AND FerryDay=?
                    ");
            $stmt->bind_param("sssss", $this->To, $this->From, $this->Return, $this->Return, $this->Day2);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($FerryNo2);

            $Ferries2 = [];
            while($stmt->fetch()) {
                $Ferries2[] = $FerryNo2;
            }
            $stmt->close();

            $minLength = min(count($Ferries), count($Ferries2));

            $Ferries = array_slice($Ferries, 0, $minLength);
            $Ferries2 = array_slice($Ferries2, 0, $minLength);

            // Get all existing routes within that time frame, for each route see if they are at seat capacity
            for ($i=0; $i < count($Ferries); $i++) {
                // Count passengers

                $stmt = $DB->prepare("
                        SELECT COUNT(p.PassengerNo) AS 'PassengerCount'
                        FROM AlbaFerry f
                        LEFT JOIN AlbaBooking b ON b.FerryNo = f.FerryNo
                        LEFT JOIN AlbaPassenger p ON p.BookingNo = b.BookingNo
                        WHERE f.FerryNo = ?
                        ");

                $stmt->bind_param("i", $Ferries[$i]);
                $stmt->execute();
                $stmt->bind_result($Count);
                $stmt->fetch();
                $stmt->close();

                $stmt = $DB->prepare("
                        SELECT COUNT(p.PassengerNo) AS 'PassengerCount'
                        FROM AlbaFerry f
                        LEFT JOIN AlbaBooking b ON b.FerryNo = f.FerryNo
                        LEFT JOIN AlbaPassenger p ON p.BookingNo = b.BookingNo
                        WHERE f.FerryNo = ?
                        ");

                $stmt->bind_param("i", $Ferries2[$i]);
                $stmt->execute();
                $stmt->bind_result($Count2);
                $stmt->fetch();
                $stmt->close();

                if (($Count + ($this->Adults + $this->Children) < 30) && ($Count2 + ($this->Adults + $this->Children) < 30)) {
                    //Outbound Ticket
                    $stmt = $DB->prepare("
                            SELECT FerryDepart, FerryArrive, FerryReturn
                            FROM AlbaFerry
                            WHERE FerryNo = ?
                        ");
                    $stmt->bind_param("i", $Ferries[$i]);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($DepartOutbound, $ArriveOutbound, $Price);
                    $stmt->fetch();
                    $stmt->close();

                    //Return Ticket
                    $stmt = $DB->prepare("
                            SELECT FerryDepart, FerryArrive
                            FROM AlbaFerry
                            WHERE FerryNo = ?
                        ");
                    $stmt->bind_param("i", $Ferries2[$i]);
                    $stmt->execute();
                    $stmt->store_result();
                    $stmt->bind_result($DepartReturn, $ArriveReturn);
                    $stmt->fetch();
                    $stmt->close();

                    $TotalPrice = ($Price * $this->Adults + (($this->Children * 7) + ($this->Teens * 10))) * 2;

                    $this->echoTicket($DepartOutbound, $ArriveOutbound, $TotalPrice, $Ferries[$i], "return", $DepartReturn, $ArriveReturn, $Ferries2[$i]);
                }
            }
        }
    }

    public function echoTicket($Depart, $Arrive, $Price, $FerryNo, $TicketType, $DepartReturn, $ArriveReturn, $FerryNo2)
    {
        if($TicketType == "single"){
            echo '<tr>';
            echo '<td>' . $this->From . '</td>';
            echo '<td>' . $this->To . '</td>';
            echo '<td>' . $this->Departure . ' @ ' . $Depart . '</td>';
            echo '<td>' . $this->Departure . ' @ ' . $Arrive . '</td>';
            echo '<td>£' . number_format($Price, 2) . '</td>';
            echo '<td><a href="./checkout.php?tickettype=' . $this->TicketType .
            '&adults=' . $this->Adults .
            '&teens=' . $this->Teens .
            '&children=' . $this->Children .
            '&from=' . $this->From .
            '&to=' . $this->To .
            '&departure=' . $this->Departure .
            '&return=' . $this->Return .
            '&departuretime=' . $Depart .
            '&arrivaltime=' . $Arrive .
            '&day=' . $this->Day .
            '&cost=' . number_format($Price, 2) .
                '&ferry=' . $FerryNo .
                '">Buy</a></td>';
            echo '</tr>';
        }

        if($TicketType == "return"){ 
                echo '<tr>';
                echo '<td>' . $this->From . '</td>';
                echo '<td>' . $this->To . '</td>';
                echo '<td>' . $this->Departure . ' @ ' . $Depart . '</td>';
                echo '<td>' . $this->Departure . ' @ ' . $Arrive . '</td>';
                echo '<td>£' . number_format($Price, 2) . '</td>';
                echo '<td><a href="./checkout.php?tickettype=' . $this->TicketType .
                '&adults=' . $this->Adults .
                '&teens=' . $this->Teens .
                '&children=' . $this->Children .
                '&from=' . $this->From .
                '&to=' . $this->To .
                '&departure=' . $this->Departure .
                '&return=' . $this->Return .
                '&departuretime=' . $Depart .
                '&arrivaltime=' . $Arrive .
                '&day=' . $this->Day .
                '&cost=' . number_format($Price, 2) .
                    '&ferry=' . $FerryNo .
                    '&ferry2=' .$FerryNo2 .
                    '">Buy</a></td>';
                echo '</tr>';

                echo '<tr>';
                echo '<td>' . $this->To . '</td>';
                echo '<td>' . $this->From . '</td>';
                echo '<td>' . $this->Return . ' @ ' . $DepartReturn . '</td>';
                echo '<td>' . $this->Return . ' @ ' . $ArriveReturn . '</td>';
                echo '<td></td>';
                echo '<td></td>';
                echo '</tr>';
            }
        }

    public function echoNoTicket()
    {
        echo '<tr>';
        echo '<td>Ticket sold out</td>';
        echo '</tr>';
    }

    //Getters
    public function getDayVar()
    {
        return $this->Day;
    }

    public function getDay2Var(){
        return $this->Day2;
    }
}
