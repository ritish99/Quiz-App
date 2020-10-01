<?php
	session_start();
	include 'database.php';
	$db = new DatabaseManager;

	//Checking whether the session of userID exits
	if(!isset($_SESSION['userID'])){
		header("Location: login.php");
	}
	//Checking if the user is either a student or teacher to limit access to correct authority
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
		

	//Checking whether the question created meets format
	if($_POST){
		if (!preg_match("/^[?A-Za-z0-9]+$/",$_POST['question']) || $_POST['question']==""){
			$nError = "Question does not meet format";
            $flag = false;
		}
		//If flag is true, call the createQuestions function passing through the question user has inputted. 
        if($flag == true){
			$db->createQuestions($_POST['question']);
        }
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"> 
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Create Quiz</title>
</head>
<body>
	<h1>Create Question: </h1>
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
		<span>Create Question: </span>
		<input type="text" autofocus name="question" id="question" value="">
		<span> <?php echo $nError ?> </span>
		</br>
		<input type="submit" name="create quiz">
	</form>
</body>
</html>