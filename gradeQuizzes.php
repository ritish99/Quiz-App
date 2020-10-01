<?php
//Starting session and including database.
session_start();
include 'database.php';
$db = new DatabaseManager;

//Checking if user exists and user role.
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
				<th><a href=>Quiz ID</a></th>
				<th><a href=>User ID</a></th>
				<th><a href=>Grade</a></th>
				<th><a href=>Edit Grade</a></th>
				<th><a href=>Delete Submission</a></th>
			</tr>
			<?php
			//Retreiving all rows of completed quizzes and outputting them onto a table.
			$completed = $db->getAllCompleted();
			while ($row = mysqli_fetch_assoc($completed)) {
			?>
				<tr>
					<td><?php echo $row['quizID']; ?></td>
					<td><?php echo $row['userID']; ?></td>
					<td><a href='grade.php?quizid=<?php echo $row['quizID']; ?>&userid=<?php echo $row['userID']; ?>'><input type="button" name="grade" value="Grade"></a></td>
					<td><a href='updateGrade.php?quizid=<?php echo $row['quizID']; ?>&userid=<?php echo $row['userID']; ?>'><input type="button" name="update" value="Update"></a></td>
					<td><a href='DeleteSubmission.php?quizid=<?php echo $row['quizID']; ?>&userid=<?php echo $row['userID']; ?>'><input type="button" name="Delete" value="Delete"></a></td>
				</tr>
			<?php
			}
			?>
		</table>

	</form>
</body>