<?php

    session_start();

    function alert($Message){
        echo '<script language="javascript">';
        echo 'alert("'.$Message.'")';
        echo '</script>';
    }
        include("./db/DbConnect.php");
        include("./db//user/user.php");


        $Email = $_POST['Email'];
        $Password = $_POST['Password'];

        $type = $_POST['formType'];

        $user = new user($Email);

        if($type == 'login') {
            $stmt = $DB->prepare("SELECT CustomerPassword FROM AlbaCustomer WHERE CustomerEmail=?");
            $stmt->bind_param("s", $Email);

            $stmt->execute();

            $stmt->store_result();
            $stmt->bind_result($Hash);

            $stmt->fetch();

            if(password_verify($Password, $Hash)) {
                $_SESSION['email'] = $Email;
                setcookie("loggedin", "true", time() + (10 * 365 * 24 *60 * 60), "/");
                header('Location: index.html');
            } else {
                alert("Incorrect Details");
            }
        } else { //Register
            $Forename = $_POST['Forename'];
            $Surname = $_POST['Surname'];
            $DOB = $_POST['Dob'];
            $Phone = $_POST['Phone'];

            $Hash = password_hash($Password, PASSWORD_DEFAULT);

            if(!$user->checkExists()){ //User doesnt exist -> create account
                $stmt = $DB->prepare("INSERT INTO AlbaCustomer (CustomerEmail, CustomerForename, CustomerSurname, CustomerDOB, CustomerPhone, CustomerTier, CustomerPassword) VALUES (?,?,?,?,?,?,?)");
                $stmt->bind_param("sssssss", $Email, $Forename, $Surname, $DOB, $Phone, 'user', $Hash);

                $stmt->execute();

                $stmt->store_result();

                if($stmt->num_rows > 0) {
                    $_SESSION['email'] = $Email;
                    setcookie("loggedin", "true", time() + (10 * 365 * 24 *60 * 60), "/");
                    header('Location: index.html');
                } else {
                    alert("Error registering, please try again.");
                }
            } else { //User DOES exist -> Dont create account
                    alert("Account with email ".$Email." already exists.");
            }
        }

    $stmt->close();
    $DB->close();

?>