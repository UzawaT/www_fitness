<?php
    //start the session
    session_start();

    //clear the session
    $_SESSION = [];

    //redirect to login page
    header("Location: index.php");
?>