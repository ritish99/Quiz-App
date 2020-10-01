<?php
//Starting session and including database.
session_start();
include 'database.php';
$db = new DatabaseManager;

//if the user name exists the user will automatically be redirected to index.
if (isset($_SESSION['userID'])) {
	header("Location: index.php");
}
//declaring variables
$uErrorMessage = "";
$pErrorMessage = "";
$flag = true;

if ($_POST) {
	if (!preg_match("/^[A-Za-z0-9_.+-]+@[A-Za-z0-9-]+\.[A-Za-z0-9.-]+$/", $_POST['email']) && !$_POST['email'] == "") {
		$uErrorMessage = "email or password are not valid";
		$flag = false;
	}

	if ($flag == true) {
		$db->login($_POST['email'], $_POST['password']);
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Login</title>
</head>

<body>
	<h1>Login</h1>
	<form action="" method="post">
		<span>User Name (Email): </span>
		<input type="text" autofocus name="email" id="email" value="<?php if (isset($_POST['email'])) {echo $_POST['email'];}?>">
		<br>
		<span>Password: </span>
		<input type="password" name="password" id="password" value="">
		<br>
		<span> <?php echo $uErrorMessage ?> </span>
		<br>
		<span> <?php echo $pErrorMessage ?> </span>
		<br>
		<input type="submit" value="Submit">
	</form>
</body>

</html>