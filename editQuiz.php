<?php
//Starting session and including database.
session_start();
include 'database.php';
$db = new DatabaseManager;

//Checking whether userID is set and what the user role is.
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


$nError = "";
$flag = true;

if (!isset($_GET['id'])) {
    header("Location: index.php");
}
//Checking if user input is valid.
if ($_POST) {
    if (!preg_match("/^[A-Za-z0-9_ -]+$/", $_POST['quizName']) || $_POST['quizName'] == "") {
        $nError = "Name does not meet format";
        $flag = false;
    }
}
if ($_POST && $flag == true) {
    $db->editQuiz($_GET['id'], $_POST['quizName']);
}

$result = $db->getOne($_GET['id']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

</head>

<body>
    <h1>Edit Quiz</h1>
    <!-- main navigation -->
    <a href="index.php"><input type="button" name="home" value="Home"></a>
    <a href="createQuiz.php"><input type="button" name="createQuiz" value="Create Quiz"></a>
    <a href="createQuestions.php"><input type="button" name="createQuestion" value="Create Questions"></a>
    <a href="questionBank.php"><input type="button" name="questionBank" value="Question Bank"></a>
    <a href="gradeQuizzes.php"><input type="button" name="gradeQuizzes" value="Grade Quizzes"></a>
    <a href="myGrades.php"><input type="button" name="gradeQuizzes" value="My Grades"></a>
    <a href="logout.php"><input type="button" name="logout" value="Logout"></a>
    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <form action="" method="post">
            <span>Name: </span>
            <input type="text" autofocus name="quizName" id="quizName" value="<?php echo $row['quizName']; ?>">
            <span> <?php echo $nError ?> </span>
            </br>
            <input type="submit" name="submit">
        </form>
    <?php } ?>
</body>

</html>