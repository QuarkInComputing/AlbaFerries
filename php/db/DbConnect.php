<?php
    DEFINE ('DB_USER', 'admin');
    DEFINE ('DB_PASSWORD','admin');
    DEFINE ('DB_HOST', 'localhost');
    DEFINE ('DB_NAME', 'Alba');
    $DB = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    if (mysqli_connect_errno())
    {
    echo 'Cannot connect to the database: ' . mysqli_connect_error();
    }
?>