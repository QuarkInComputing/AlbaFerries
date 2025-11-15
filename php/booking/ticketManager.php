<?php
    Class ticketManager {
        private $TicketType;
        private $Adults;
        private $Children;
        private $Teens;
        private $From;
        private $To;
        private $Departure;
        private $Return;
        private $Day;

        function __construct($TicketType, $Adults, $Teens, $Children, $From, $To, $Departure, $Return) {
            $this->TicketType = $TicketType;
            $this->Adults = $Adults;
            $this->Teens = $Teens;
            $this->Children = $Children;
            $this->From = $From;
            $this->To = $To;
            $this->Departure = $Departure;
            if(!empty($Return)) {
                $this->Return = $Return;
            }

            $this->getDay($Departure);
        }

        function getDay($Date) {
            $DayNo = date('w', strtotime($Date));
            
            switch($DayNo) {
                case 1:
                    $this->Day = "Mon";
                    break;
                case 2:
                    $this->Day = "Tue";
                    break;
                case 3:
                    $this->Day = "Wed";
                    break;
                case 4:
                    $this->Day = "Thu";
                    break;
                case 5:
                    $this->Day = "Fri";
                    break;
                case 6:
                    $this->Day = "Sat";
                    break;
                case 7:
                    $this->Day = "Sun";
                    break;
            }
        }

        //This whole system is probably quite messy, I created this by hand-writing sql, chugging a coffee and praying
        function findTickets($DB) {
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

                while($stmt->fetch()) {
                    // echo '<p>Ferry='.$FerryNo.'</p>';
                    $Ferries[] = $FerryNo;
                }

                $stmt->close();

                // Get all existing routes within that time frame, for each route see if they are at seat capacity
                foreach($Ferries as $Ferry) {
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

                    if($Count + ($this->Adults + $this->Children) >=30) {
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

                        $TotalPrice = $Price * $this->Adults +(($this->Children * 7) + ($this->Teens * 10));

                        $this->echoTicket($Depart, $Arrive, $TotalPrice, $Ferry);
                    }
                }
            }
        }

        function echoTicket($Depart, $Arrive, $Price, $NoDebug) {
            echo '<tr>';
            echo    '<td>'.$this->From.'</td>';
            echo    '<td>'.$this->To.'</td>';
            echo    '<td>'.$this->Departure.' @ '.$Depart.'</td>';
            echo    '<td>'.$this->Departure.' @ '.$Arrive.'</td>';
            echo    '<td>£'.$Price.'</td>'; 
            echo    '<td><a href="#">Buy</a></td>';
            // echo    '<td>DEBUG - USED FERRY: '.$NoDebug.'</td>';
            echo '</tr>';
        }

        function echoNoTicket() {
            echo '<tr>';
            echo    '<td>Ticket sold out</td>';
            echo '</tr>';
        }

        //Getters
        function getDayVar() {
            return $this->Day;
        }
    }
?>