<?php
session_start();
include 'database.php';
$db = new DatabaseManager;

if (!isset($_SESSION['userID'])) {
	header("Location: login.php");
}
if (!isset($_GET['id'])) {
	header("Location: index.php");
}
if ($_GET['id']) {
	$quizID = $_GET['id'];
	$questions = $db->getAllQuizQuestions($quizID);
	$completeCheck = $db->completedQuizCheck($quizID, $_SESSION['userID']);
	if (!empty($completeCheck)) {
		header("Location: index.php");
	}
	if (empty($questions)) {
		header("Location: index.php");
	}
}

if ($_POST) {
	$db->recordAnswers($_POST['answers'], $_POST['questions'], $_POST['quizID'], $_SESSION['userID']);
	$db->quizCompleted($_SESSION['userID'], $_POST['quizID']);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>quiz</title>


</head>

<body>
	<h1>Quiz</h1>
	<form action="" method="post">

		<?php
		$questionNumber = 0;


		while ($row = mysqli_fetch_assoc($questions)) {
			$questionID[$questionNumber] = $row['questionID'];
		?>
			<h4>Question <?php echo ($questionNumber + 1); ?>: <?php echo $row['question']; ?></h4>
			<h4>Question ID: <?php echo $row['questionID']; ?></h4>
			<input type="hidden" id="questions" name="questions[]" value="<?php echo $row['questionID']; ?>">
			<input type="hidden" id="quizID" name="quizID" value="<?php echo $quizID; ?>">
			<input type="text" id="answers" name="answers[]"><br><br>
		<?php
			$questionNumber++;
		}
		?>
		</br>
		<input type="submit" value="Submit">
	</form>
</body>

</html>