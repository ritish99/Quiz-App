<?php
    //Staring session and connecting to database.
    session_start();
    include 'database.php';
    $db = new DatabaseManager;

    //Checking if userID is set, other wise the user will be redirected. 
    if(!isset($_SESSION['userID'])){
        header("Location: login.php");
    }
    //Checking what role the user has in order to determine whether they have access to the page.
    if(isset($_SESSION['userID'])){
		$role = $db->getRole($_SESSION['userID']);
		while($row = mysqli_fetch_assoc($role)){
			if($row['role'] == "Student"){
				header("Location: index.php");
			}
		}
	}

    //If there is no get ID when entering the page, the user will be redirected
    if(!isset($_GET['id'])){
        header("Location: questionBank.php");
    }
    //If there is a get ID the function will be executed.
    if($_GET['id']){
        $db->deleteQuestion($_GET['id']);
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
</head>
<body>
</body>
</html>