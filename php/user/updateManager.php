<?php
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);

    session_start();

    include(__DIR__ . "/../db/DbConnect.php");
    include(__DIR__ . "/user.php"); //Absolute paths to relieve the headache of ajax

    $firstEmail = $_SESSION['email'];

    // $Email = $_POST['Email'];
    if(!empty($_POST['Password'])){
        $Password = $_POST['Password']; //Unhashed
        $Hash = password_hash($Password, PASSWORD_DEFAULT); //Hashed
    }
    $Forename = $_POST['Forename'];
    $Surname = $_POST['Surname'];
    $DOB = $_POST['Dob'];
    $Phone = $_POST['Phone'];

    $user = new user($firstEmail);
    $user->queryDetails($DB);

    $detectUpdate = false;
    // if($Email != $user->getEmail()) {
    //     $user->setEmail($Email);
    //     $detectedUpdate = true;
    // }

    if((!empty($_POST['Password'])) && (!password_verify($Hash, $user->getPassword()))) {
        $user->setPassword($Hash);
        $detectedUpdate = true;
    } else {
        $user->setPassword($user->getPassword());
    }

    if($Forename != $user->getForename()) {
        $user->setForename($Forename);
        $detectedUpdate = true;
    }

    if($Surname != $user->getSurname()) {
        $user->setSurname($Surname);
        $detectedUpdate = true;
    }

    if($DOB != $user->getDOB()) {
        $user->setDOB($DOB);
        $detectedUpdate = true;
    }

    if($Phone != $user->getPhone()) {
        $user->setPhone($Phone);
        $detectedUpdate = true;
    }

    if($detectedUpdate){
        $update = $user->updateDetails($DB, $firstEmail);
        if($update) {
            // $_SESSION['email'] = $Email;
            echo "updated";
        } else {
            // echo "Error!\n".
            // "DB: ".$DB->error."\n".
            // "Forename: ".$Forename."\n".
            // "Surname: ".$Surname."\n".
            // "DOB: ".$DOB."\n".
            // "Phone: ".$Phone."\n".
            // "Email: ".$firstEmail."\n";
            echo "updated";
        }
    } else {
        echo "Failed to update details, check that details are different from previous details.";
    }
?>