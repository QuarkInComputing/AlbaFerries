<?php
    Class ticketManager {
        private $TicketType;
        private $Adults;
        private $Children;
        private $From;
        private $To;
        private $Departure;
        private $Return;

        function __construct($TicketType, $Adults, $Children, $From, $To, $Departure, $Return) {
            $this->TicketType = $TicketType;
            $this->Adults = $Adults;
            $this->Children = $Children;
            $this->From = $From;
            $this->To = $To;
            $this->Departure = $Departure;
            $this->Return = $Return;
        }

        //This whole system is probably quite messy, I created this by hand-writing sql, chugging a coffee and praying
        function findTickets($DB) {
            if ($this->TicketType == "single") {
                // Check if routes exist and if they are avaliable in the users selected timeframe
                $stmt = $DB->prepare("SELECT FerryNo FROM AlbaFerry WHERE 
                        RouteNo=(SELECT RouteNo FROM AlbaRoute WHERE RouteDepart=? AND RouteDestination=?) AND
                        FerryStart > ? AND
                        FerryEnd < ?"
                );

                $stmt->bind_param("ssss", $this->From, $this->To, $this->Departure, $this->Departure);
                $stmt->execute();

                $stmt->store_result();
                $stmt->bind_result($FerryNo);

                while($stmt->fetch()) {
                    $Ferries[] = $FerryNo;
                }

                // Get all existing routes within that time frame, for each route see if they are at seat capacity
                foreach($Ferries as $Ferry) {
                    $stmt = £DB->prepare("SELECT COUNT(p.PassengerNo) AS 'PassengerCount' FROM AlbaFerry f
                        LEFT JOIN AlbaBooking b ON b.FerryNo = f.FerryNo
                        LEFT JOIN AlbaPassenger p ON p.BookingNo = b.BookingNo
                        WHERE f.FerryNo =?"
                    );

                    $stmt->bind_param("i", $Ferry);
                    $stmt->execute();
                    $stmt->bind_result($Count);
                    $stmt->fetch();

                    if($Count >=30) {
                        //Seats taken, ticket not purchasable
                    } else {
                        //Seats not taken, ticket is purchasable
                    }
                }
            }
        }
    }
?>