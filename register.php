<?php

	include 'database.php';
	$db = new DatabaseManager;
	
	$eErrorMessage = "";
	$pErrorMessage = "";
	$cErrorMessage = "";
	$rErrorMessage = "";
	$flag = true;

	if($_POST){
		if (!preg_match("/^[A-Za-z0-9_.+-]+@[A-Za-z0-9-]+\.[A-Za-z0-9.-]+$/",$_POST['email']) && !$_POST['email']==""){
            $flag = false;
            $eErrorMessage = "Email format is not correct";
        }
        //Checking for password and its requirements.
        if (preg_match("/^[A-Za-z!+%$0-9-]*$/",$_POST['password'])){
        	//echo "match";
        	if (strtolower($_POST['password']) != $_POST['password']) {
			}
			else{
				$flag = false;
    			$pErrorMessage = "Password does not meet requirement";
			}
        }
        //Checking for white spaces.
        if (preg_match('/\s/', $_POST['password'])){
        	$flag = false;
    		$pErrorMessage = "Password does not meet requirement";
		}
		//Checking if passwords inputted are not blank and if they equal to each other.
        if(!$_POST['password'] == "" && !$_POST['cpassword'] == "" && $_POST['password'] == $_POST['cpassword']){
        		$flag = true;
        	}
        	else{
        		$cErrorMessage = "Passwords are not the same";
        		$flag = false;
			}
		if(!isset($_POST['role'])){
			$flag = false;
			$rErrorMessage = "Role not declared";
		}
		if($flag == true){
			$db->register($_POST['email'],$_POST['password'],$_POST['role']);
		}
	}	
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>register</title>
</head>

<body>
	<h1>Register</h1>
	<form action="" method="post">
		<!--Creating text tags, and inputs also giving instructions -->
		<span>User Name (Email): </span>
		<input type="text" autofocus name="email" id="email" value="<?php 
			if(isset($_POST['email'])){
				echo $_POST['email'];
			}
			?>">
			<span> <?php echo $eErrorMessage ?> </span>
		<br>

		<span>Password: </span>
		<input type="password" name="password" id="password" value="">
		<span> <?php echo $pErrorMessage ?> </span>
		<br>


		<span>Confirm Password: </span>
		<input type="password" name="cpassword" id="cpassword" value="">
		<span> <?php echo $cErrorMessage ?> </span>
		<br>

		<input type="radio" name="role" value="Student">Student
		<input type="radio" name="role" value="Teacher">Teacher
		<br>
		<span> <?php echo $rErrorMessage ?> </span>
		<br>

		<input type="submit" value="Submit">
	</form>
</body>
</html>