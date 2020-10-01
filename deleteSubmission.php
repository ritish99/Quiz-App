<?php
    //Staring session and connecting to database.
    session_start();
    include 'database.php';
    $db = new DatabaseManager;
    
    //Checking if the userID session exist's and what role the user is.
    if(!isset($_SESSION['userID'])){
        header("Location: login.php");
    }
    if(isset($_SESSION['userID'])){
		$role = $db->getRole($_SESSION['userID']);
		while($row = mysqli_fetch_assoc($role)){
			if($row['role'] == "Student"){
				header("Location: index.php");
			}
		}
	}

    if(!isset($_GET['id'])){
        header("Location: gradeQuizzes.php");
    }
    //Checking for both get requests.
    if($_GET['userid'] && $_GET['quizid']){
        //Declaring variables.
		$quizID = $_GET['quizid'];
		$userID = $_GET['userid'];
        //Executing all functions if both userid and quizid exist and are set.
        $db->deleteAnswers($quizID, $userID);
        $db->deleteQuizCompleted($quizID, $userID);
        $resultCheck = $db->resultCheck($quizID, $userID);
        //Checking if there is any result made for the specific submission.
        if(isset($resultCheck)){
            $db->deleteQuizResult($quizID, $userID);
        }
        else{
            header("Location: gradeQuizzes.php");
        }
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
</head>
<body>
</body>
</html>