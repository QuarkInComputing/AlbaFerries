<?php
    DEFINE ('DB_USER', 'in22010875');
    DEFINE ('DB_PASSWORD','220108759');
    DEFINE ('DB_HOST', 'compserver.uhi.ac.uk');
    DEFINE ('DB_NAME', 'in22010875');
    $DB = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
    if (mysqli_connect_errno())
    {
    echo 'Cannot connect to the database: ' . mysqli_connect_error();
    }
?>