<?php
    define("SERVER_NAME", "localhost");
    define("USER_NAME", "root");
    define("PASS", "");
    define("DB_NAME", "gymstore");

    $conn = new mysqli(SERVER_NAME, USER_NAME, PASS, DB_NAME);

    if (!$conn) 
    {
        die("connection failed.");
    }

    //echo "successfully connected.";
?>