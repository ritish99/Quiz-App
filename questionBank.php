<?php
session_start();
include 'database.php';
$db = new DatabaseManager;

//Checking if the userID is set and what role the user is.
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>index</title>
	<style>
		table {
			font-family: arial, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		td,
		th {
			border: 1px solid #dddddd;
			text-align: left;
			padding: 8px;
		}

		tr:nth-child(even) {
			background-color: #dddddd;
		}
	</style>
</head>

<body>
	<h1>Question bank</h1>
	<form action="" method="post">
		<!-- main navigation -->
		<a href="index.php"><input type="button" name="home" value="Home"></a>
		<a href="createQuiz.php"><input type="button" name="createQuiz" value="Create Quiz"></a>
		<a href="createQuestions.php"><input type="button" name="createQuestion" value="Create Questions"></a>
		<a href="questionBank.php"><input type="button" name="questionBank" value="Question Bank"></a>
		<a href="gradeQuizzes.php"><input type="button" name="gradeQuizzes" value="Grade Quizzes"></a>
		<a href="myGrades.php"><input type="button" name="gradeQuizzes" value="My Grades"></a>
		<a href="logout.php"><input type="button" name="logout" value="Logout"></a>


		<table>
			<tr>
				<th><a href=>Question ID</a></th>
				<th><a href=>Question QuizID</a></th>
				<th><a href=>Question</a></th>
				<th><a href=>Edit Quiz</a></th>
				<th><a href=>Delete Question</a></th>
			</tr>
			<?php
			$products = $db->getAllQuestions();
			while ($row = mysqli_fetch_assoc($products)) {
			?>
				<tr>
					<td><?php echo $row['questionID']; ?></td>
					<td><?php echo $row['quizID']; ?></td>
					<td><?php echo $row['question']; ?></td>
					<td><a href='editQuestion.php?id=<?php echo $row['questionID']; ?>'><input type='button' name='Edit' value='Edit'></a></td>
					<td><a href='deleteQuestion.php?id=<?php echo $row['questionID']; ?>'><input type='button' name='delete' value='delete'></a></td>
				</tr>
			<?php
			}
			?>
		</table>

	</form>
</body>