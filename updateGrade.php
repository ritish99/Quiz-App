<?php
session_start();
include 'database.php';
$db = new DatabaseManager;

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
//Checking if both get requests exist.
if ($_GET['userid'] && $_GET['quizid']) {
	//Declaring variables.
	$quizID = $_GET['quizid'];
	$userID = $_GET['userid'];
	//Retrieving all quiz questions and the answer the user had given.
	$questions = $db->getAllQuizQuestions($quizID);
	$answers = $db->getAllQuizAnswers($quizID, $userID);
	if (empty($answers)) {
		header("Location: gradeQuizzes.php");
	}
	if (empty($questions)) {
		header("Location: gradeQuizzes.php");
	}
	//If a grade has not been given the user will be redirected.
	$completeCheck = $db->resultCheck($quizID, $userID);
	if (empty($completeCheck)) {
		header("Location: gradeQuizzes.php");
	}
}

$flag = true;
$nError = "";

//Checking if grade meets format.
if ($_POST) {
	if (!preg_match("/^[1-9][0-9]?$|^100$/", $_POST['grade']) || $_POST['grade'] == "") {
		$nError = "grade does not meet format";
		$flag = false;
	}
	if ($flag == true) {
		$db->updateGrade($_POST['grade'], $_POST['quizID'], $userID);
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
	<?php
	$questionNumber = 0;
	while ($row = mysqli_fetch_assoc($questions)) {
		$questions2[$questionNumber] = $row['question'];
		$questionNumber++;
	}
	$answerNumber = 0;
	while ($row = mysqli_fetch_assoc($answers)) {
		$answers2[$answerNumber] = $row['answer'];
		$answerNumber++;
	}
	?>
	<?php
	$i = 0;

	//Outputs all questions and answers in unison.
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