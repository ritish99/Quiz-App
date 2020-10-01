<?php
    session_start();

    //Unset UserID session variable.
    if(isset($_SESSION['userID'])){
        unset($_SESSION['userID']);
    }
    //Redirects user to login.
    header("Location: login.php");
?>