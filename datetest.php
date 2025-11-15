<!DOCTYPE html>
<html>

    <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">

            <title>Date Test</title>

            <!--Stylesheets-->
            <link id="theme-desktop" rel="stylesheet" href="css/stylesheet.css"> 
            <link id="theme-mobile" rel="stylesheet" media="screen and (max-width: 992px)" href="css/mobile.css">

            <!--Favicon-->

            <!--Google Font-->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Istok+Web:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    </head>

    <body>

    <?php
        function getWeekday($date) {
            return date('w', strtotime($date));
        }

        $no = getWeekday('2025-06-11');

        switch($no) {
            case 1:
                echo "mon";
                break;
            case 2:
                echo "tue";
                break;
            case 3:
                echo "wed";
                break;
            case 4:
                echo "thu";
                break;
            case 5:
                echo "fri";
            case 6:
                echo "sat";
            case 7:
                echo "fri";
        }
    ?>

    </body>

</html>