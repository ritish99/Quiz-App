<?php
//Starting session and including database.
session_start();
include 'database.php';
$db = new DatabaseManager;

//Checking if userID is set
if (!isset($_SESSION['userID'])) {
	header("Location: login.php");
}
//Checking for role of user. A student will be shown his own grades while a teacher will be shown everyone's grades.
if (isset($_SESSION['userID'])) {
	$role = $db->getRole($_SESSION['userID']);
	while ($row = mysqli_fetch_assoc($role)) {
		$roleCheck = $row['role'];
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
				<th><a href=>Quiz Result ID</a></th>
				<th><a href=>Grade</a></th>
				<th><a href=>QuizID</a></th>
				<th><a href=>UserID</a></th>
			</tr>
			<?php
			if ($roleCheck == "Teacher") {
				$results = $db->getAllResults();
			} else {
				$results = $db->getResults($_SESSION['userID']);
			}
			while ($row = mysqli_fetch_assoc($results)) {
			?>
				<tr>
					<td><?php echo $row['quizResultID']; ?></td>
					<td><?php echo $row['grade']; ?></td>
					<td><?php echo $row['quizID']; ?></td>
					<td><?php echo $row['userID']; ?></td>
				</tr>
			<?php
			}
			?>
		</table>

	</form>
</body>