<!-- DELETE THIS FILE UPON SUBMISSION -->
<!DOCTYPE html>
<html>
<body>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);


        include("./php/db/DbConnect.php");

        $Forename="Test";

        $stmt = $DB->prepare("SELECT CustomerEmail FROM AlbaCustomer WHERE CustomerForename=?");
        $stmt->bind_param("s", $Forename);    

        $stmt->execute();

        $stmt->store_result();
        $stmt->bind_result($Email);

        $stmt->fetch();

        echo '<h1>'.$Email.'</h1>';

        $stmt->close();
        $DB->close();
    ?>
</body>
</html>