<?php
//Starting session, including database.
session_start();
include 'database.php';
$db = new DatabaseManager;

//Checking if userID is existing, also checking for user role.
if (!isset($_SESSION['userID'])) {
	header("Location: login.php");
}
if (isset($_SESSION['userID'])) {
	$role = $db->getRole($_SESSION['userID']);
	while ($row = mysqli_fetch_assoc($role)) {
		if ($row['role'] == "Student") {
			header("Location: index.php");
		}
	}
}
//Checking if both get requests are recieved.
if ($_GET['userid'] && $_GET['quizid']) {
	//Declaring variables and then executing methods to retrieve all questions and answers.
	$quizID = $_GET['quizid'];
	$userID = $_GET['userid'];

	$questions = $db->getAllQuizQuestions($quizID);
	$answers = $db->getAllQuizAnswers($quizID, $userID);
	if (empty($answers)) {
		header("Location: gradeQuizzes.php");
	}
	if (empty($questions)) {
		header("Location: gradeQuizzes.php");
	}
	//Checking if a grade has akready been given to the submission.
	$completeCheck = $db->resultCheck($quizID, $userID);
	if (isset($completeCheck)) {
		header("Location: gradeQuizzes.php");
	}
}

$flag = true;
$nError = "";

//Checking if user input is valid.
if ($_POST) {
	if (!preg_match("/^[1-9][0-9]?$|^100$/", $_POST['grade']) || $_POST['grade'] == "") {
		$nError = "grade does not meet format";
		$flag = false;
	}
	if ($flag == true) {
		$db->grade($_POST['grade'], $_POST['quizID'], $userID);
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Grade Quiz</title>
</head>

<body>
	<!-- main navigation -->
	<a href="index.php"><input type="button" name="home" value="Home"></a>
	<a href="createQuiz.php"><input type="button" name="createQuiz" value="Create Quiz"></a>
	<a href="createQuestions.php"><input type="button" name="createQuestion" value="Create Questions"></a>
	<a href="questionBank.php"><input type="button" name="questionBank" value="Question Bank"></a>
	<a href="gradeQuizzes.php"><input type="button" name="gradeQuizzes" value="Grade Quizzes"></a>
	<a href="myGrades.php"><input type="button" name="gradeQuizzes" value="My Grades"></a>
	<a href="logout.php"><input type="button" name="logout" value="Logout"></a>
	<!-- Outputting question,answers and grade box -->
	<?php
	$questionNumber = 0;
	while ($row = mysqli_fetch_assoc($questions)) {
		$questions2[$questionNumber] = $row['question'];
	?>
		<!-- <h4>Question <?php echo ($questionNumber + 1); ?>:  <?php echo $row['question']; ?></h4> -->
	<?php
		$questionNumber++;
	}
	$answerNumber = 0;
	while ($row = mysqli_fetch_assoc($answers)) {
		$answers2[$answerNumber] = $row['answer'];
	?>
		<!-- <h4><?php echo $row['answer']; ?></h4> -->
	<?php
		$answerNumber++;
	}
	?>
	<?php
	$i = 0;

	//shows all questions and answers in unison
	$questionCounter = 0;
	while ($i < count($questions2)) {
	?>
		<h4>Question <?php echo ($questionCounter + 1); ?>: <?php echo $questions2[$i]; ?></h4>
		<h4>Answer: <?php echo $answers2[$i]; ?></h4>
	<?php

		$i++;
		$questionCounter++;
	}
	?>


	<form action="" method="post">
		<span>Grade: </span>
		<input type="text" autofocus name="grade" id="grade" value=""> <span>%</span>
		<span> <?php echo $nError ?> </span>
		</br>
		<input type="hidden" id="quizID" name="quizID" value="<?php echo $quizID; ?>">
		<input type="submit" name="create grade">
	</form>
</body>

</html>