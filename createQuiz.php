<?php
	//Starting session and connecting to the database.
	session_start();
	include 'database.php';
	$db = new DatabaseManager;
	
	//Checking if userID session exists, if not the user will be redirected.
	if(!isset($_SESSION['userID'])){
		header("Location: login.php");
	}
	//Checking for the role of the current user to see if they are allowed to access the page.
	if(isset($_SESSION['userID'])){
		$role = $db->getRole($_SESSION['userID']);
		while($row = mysqli_fetch_assoc($role)){
			if($row['role'] == "Student"){
				header("Location: index.php");
			}
		}
	}

	//Declaring variables
	$nError = "";
	$flag = true; 
		
	if($_POST){
		if (!preg_match("/^[A-Za-z0-9 ]+$/",$_POST['name']) || $_POST['name']==""){
			$nError = "Name does not meet format";
            $flag = false;
        }
        if($flag == true){
			$db->createQuiz($_POST['name']);
        }
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"> 
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Create Quiz</title>
</head>
<body>
	<h1>Create Quiz</h1>
	<form action="" method="post">
	<!-- main navigation -->
	<a href="index.php"><input type="button" name="home" value="Home"></a>
	<a href="createQuiz.php"><input type="button" name="createQuiz" value="Create Quiz"></a>
	<a href="createQuestions.php"><input type="button" name="createQuestion" value="Create Questions"></a>
	<a href="questionBank.php"><input type="button" name="questionBank" value="Question Bank"></a>
	<a href="gradeQuizzes.php"><input type="button" name="gradeQuizzes" value="Grade Quizzes"></a>
	<a href="myGrades.php"><input type="button" name="gradeQuizzes" value="My Grades"></a>
	<a href="logout.php"><input type="button" name="logout" value="Logout"></a>
	<br>
		<span>Name: </span>
		<input type="text" autofocus name="name" id="name" value="">
		<span> <?php echo $nError ?> </span>
		</br>
		<input type="submit" name="create quiz">
	</form>
</body>
</html>