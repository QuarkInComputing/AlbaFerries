<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();

        include(__DIR__ . "/../db/DbConnect.php");
        include(__DIR__ . "/user.php"); //Absolute paths to relieve the headache of ajax

        $Email = $_POST['Email'];
        $Password = $_POST['Password'];

        $type = $_POST['formType'];

        $user = new user($Email, $DB);

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
                echo "redirect:index.html";
            } else {
                echo "Incorrect Details";
            }
        } else { //Register
            $Forename = $_POST['Forename'];
            $Surname = $_POST['Surname'];
            $DOB = $_POST['Dob'];
            $Phone = $_POST['Phone'];

            $Hash = password_hash($Password, PASSWORD_DEFAULT);

            if(!$user->checkExists($Email, $DB)){ //User doesnt exist -> create account
                $stmt = $DB->prepare("INSERT INTO AlbaCustomer (CustomerEmail, CustomerForename, CustomerSurname, CustomerDOB, CustomerPhone, CustomerTier, CustomerPassword) VALUES (?,?,?,?,?,?,?)");
                $Tier = 'user';
                $stmt->bind_param("sssssss", $Email, $Forename, $Surname, $DOB, $Phone, $Tier, $Hash);

                $stmt->execute();

                $stmt->store_result();

                if($stmt->affected_rows > 0) {
                    $_SESSION['email'] = $Email;
                    setcookie("loggedin", "true", time() + (10 * 365 * 24 *60 * 60), "/");
                    echo "redirect:index.html";;
                } else {
                    echo "Error registering, please try again.";
                }
            } else { //User DOES exist -> Dont create account
                    echo "Account with email ".$Email." already exists.";
            }
        }

    $stmt->close();
    $DB->close();

?>