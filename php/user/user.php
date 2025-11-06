<?php
    Class user{
        private $Email;
        private $Forename;
        private $Surname;
        private $Tier;
        private $Password; //Hashed

        function __construct($Email) {
            $this->Email = $Email;
        }

        function queryDetails($DB) {
            $stmt = $DB->prepare("SELECT CustomerForename, CustomerSurname, CustomerTier, CustomerPassword FROM AlbaCustomer WHERE CustomerEmail=?");
            $stmt->bind_param("s", $this->Email);
            $stmt->execute();

            $stmt->store_result();
            $stmt->bind_result($Forename, $Surname, $Tier, $Password);

            $stmt->fetch();
        }

        function checkExists($Email) {
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

        //Getters & Setters
        function getEmail() {
            return $this->Email;
        }

        function setForename($Forename) {
            $this->Forename = $Forename;
        }

        function getForename(){
            return $this->Forename;
        }

        function setSurname($Surname) {
            $this->Surname;
        }

        function getSurname() {
            return $this->Surname;
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