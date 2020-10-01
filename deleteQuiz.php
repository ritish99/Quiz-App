<?php
    //Starting session and connecting to database.
    session_start();
    include 'database.php';
    $db = new DatabaseManager;

    //Checking if userID is set.
    if(!isset($_SESSION['userID'])){
        header("Location: login.php");
    }
    //Checking if a get request was made and then checking for the role of the user.
    if($_GET['id']){
        if(isset($_SESSION['userID'])){
            $role = $db->getRole($_SESSION['userID']);
            while($row = mysqli_fetch_assoc($role)){
                if($row['role'] == "Student"){
                    header("Location: index.php");
                }
                //If the user is a teacher the deleteQuiz function will be executed.
                if($row['role'] == "Teacher"){
                    $db->deleteQuiz($_GET['id']);
                    header("Location: index.php");
                }
            }
        }
    }
    //If there is no get request the user will be redirected.
    if(!isset($_GET['id'])){
        header("Location: index.php");
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
</head>
<body>
</body>
</html>