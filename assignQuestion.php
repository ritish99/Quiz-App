<?php
	//Starting session and including database.
	session_start();
	include 'database.php';
	$db = new DatabaseManager;
	
	//Checking if the userID session exists, if it does not the user will be redirected.
	if(!isset($_SESSION['userID'])){
		header("Location: login.php");
	}
	//Checking role of the user, if they are a student they will be redirected.
	if(isset($_SESSION['userID'])){
		$role = $db->getRole($_SESSION['userID']);
		while($row = mysqli_fetch_assoc($role)){
			if($row['role'] == "Student"){
				header("Location: index.php");
			}
		}
	}
	
	//Declaring variables
	$quizID = $_GET['id'];
	$ErrorMessage = ""; 
	$flag = true; 

	//Checking if questions were selected, if questions were selected they will be stored in the database. Otherwise, an error will appear on the page.
	if($_POST){
		if(empty($_POST['questions']) == false){
			$ErrorMessage = "";

			$length = count($_POST['questions']);
			if($length > 0){
				$db->assignQuestions($_POST['questions'],$quizID);
			}
		}
		else{
			$ErrorMessage = "No options were selected";
			$flag = false;
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

		td, th {
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
	<h1>Assign Questions</h1>
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
				<th><a href=>Question</a></th>
				<th><a href=>Select</a></th>
			</tr>
  			<?php
			  	//Retreiving all eligible questions from the data base for user selection
			  	$questions = $db->getAllQuestionsAssigning();
				while($row = mysqli_fetch_assoc($questions)){
			?>
					<tr>	
						<!-- Printing out all questions with their ID's -->
						<td><?php echo $row['questionID'];?></td>
						<td><?php echo $row['question'];?></td>
						<td><input type="checkbox" id="<?php echo $row['question'];?>" name="questions[]" value="<?php echo $row['question'];?>"></td>
					</tr>
			<?php
				}
			?>
		</table>
		<br>
		<?php echo $ErrorMessage;?>
		<br>
		<input type="submit" value="Submit">
	</form>
</body>