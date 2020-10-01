<?php
//Stating session and including database.
session_start();
include 'database.php';
$db = new DatabaseManager;

//Checking if userID is set and printing out user role.
if (!isset($_SESSION['userID'])) {
	header("Location: login.php");
}
if (isset($_SESSION['userID'])) {
	$role = $db->getRole($_SESSION['userID']);
	while ($row = mysqli_fetch_assoc($role)) {
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
	<h1>Welcome to Quiz Up</h1>
	<form action="" method="post">
		<!-- main navigations -->
		<a href="index.php"><input type="button" name="home" value="Home"></a>
		<a href="createQuiz.php"><input type="button" name="createQuiz" value="Create Quiz"></a>
		<a href="createQuestions.php"><input type="button" name="createQuestion" value="Create Questions"></a>
		<a href="questionBank.php"><input type="button" name="questionBank" value="Question Bank"></a>
		<a href="gradeQuizzes.php"><input type="button" name="gradeQuizzes" value="Grade Quizzes"></a>
		<a href="myGrades.php"><input type="button" name="gradeQuizzes" value="My Grades"></a>
		<a href="logout.php"><input type="button" name="logout" value="Logout"></a>

		<table>
			<tr>
				<th><a href=>Quiz ID</a></th>
				<th><a href=>Quiz Name</a></th>
				<th><a href=>Take Quiz</a></th>
				<th><a href=>Edit Quiz</a></th>
				<th><a href=>Assign Questions</a></th>
				<th><a href=>Delete Quiz</a></th>
			</tr>
			<?php
			$products = $db->getAllQuiz();
			while ($row = mysqli_fetch_assoc($products)) {
			?>
				<tr>
					<td><?php echo $row['quizID']; ?></td>
					<td><?php echo $row['quizName']; ?></td>
					<td><a href='quiz.php?id=<?php echo $row['quizID']; ?>'><input type="button" name="take" value="Take"></a></td>
					<td><a href='editQuiz.php?id=<?php echo $row['quizID']; ?>'><input type='button' name='Edit' value='Edit'></a></td>
					<td><a href='assignQuestion.php?id=<?php echo $row['quizID']; ?>'><input type="button" name="assign" value="Assign"></a></td>
					<td><a href='deleteQuiz.php?id=<?php echo $row['quizID']; ?>'><input type='button' name='delete' value='delete'></a></td>
				</tr>
			<?php
			}
			?>
		</table>

	</form>
</body>