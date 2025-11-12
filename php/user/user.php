<?php
    Class user{
        private $Email;
        private $Forename;
        private $Surname;
        private $DOB;
        private $Phone;
        private $Tier;
        private $Password; //Hashed

        function __construct($Email) {
            $this->Email = $Email;
        }

        function queryDetails($DB) {
            $stmt = $DB->prepare("SELECT CustomerForename, CustomerSurname, CustomerDOB, CustomerPhone, CustomerTier, CustomerPassword FROM AlbaCustomer WHERE CustomerEmail=?");
            $stmt->bind_param("s", $this->Email);
            $stmt->execute();

            $stmt->store_result();
            $stmt->bind_result($Forename, $Surname, $DOB, $Phone, $Tier, $Password);

            $stmt->fetch();

            $this->Forename = $Forename;
            $this->Surname = $Surname;
            $this->DOB = $DOB;
            $this->Phone = $Phone;
            $this->Tier = $Tier;
            $this->Password = $Password;
        }

        function checkExists($Email, $DB) {
            $stmt = $DB->prepare("SELECT * FROM AlbaCustomer WHERE CustomerEmail=?");
            $stmt->bind_param("s", $Email);
            $stmt->execute();

            $stmt->store_result();

            if($stmt->num_rows > 0) {
                return true;
            } else {
                return false;
            }
        }

        function updateDetails($DB, $firstEmail) {
            $stmt = $DB->prepare("UPDATE AlbaCustomer SET CustomerForename = CONCAT(?, ''), CustomerSurname = CONCAT(?, ''), CustomerDOB = DATE_ADD(?, INTERVAL 0 DAY), CustomerPhone = CONCAT(?, ''), CustomerPassword = CONCAT(?, '') WHERE CustomerEmail = ?;");
            //Concat will hopefully trick mysql into thinking the details have changed, even when they havent
            $stmt->bind_param("ssssss", $this->Forename, $this->Surname, $this->DOB, $this->Phone, $this->Password, $firstEmail);

            if (!$stmt->execute()) {
                return false;
            } else {
                return true;
            }
        }

        //Getters & Setters
        function getEmail() {
            return $this->Email;
        }

        function setEmail($Email) {
            $this->Email = $Email;
        }

        function setForename($Forename) {
            $this->Forename = $Forename;
        }

        function getForename(){
            return $this->Forename;
        }

        function setSurname($Surname) {
            $this->Surname = $Surname;
        }

        function getSurname() {
            return $this->Surname;
        }

        function setDOB($DOB) {
            $this->DOB = $DOB;
        }

        function getDOB() {
            return $this->DOB;
        }

        function setPhone($Phone) {
            $this->Phone = $Phone;
        }

        function getPhone() {
            return $this->Phone;
        }

        function setTier($Tier) {
            $this->Tier = $Tier;
        }

        function getTier() {
            return $this->Tier;
        }

        function setPassword($Password) {
            $this->Password = $Password;
        }

        function getPassword() {
            return $this->Password;
        }
    }
?>