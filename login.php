<?php 
    include("include/header.php");
	$_SESSION['message'] = '';
	if(isset($_GET['error'])){
	    if($_GET['error'] == "1"){
	        $_SESSION['message'] = 'Please login or register first';
	    }
	}
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		$email = $_POST['email'];
		$password = $_POST['password'];
		$checkemail = mysqli_query($con, "SELECT id FROM users WHERE email='".$email."'");
		if($checkemail->num_rows == 0) {
			$_SESSION['message'] = "Incorrect login please check details are correct";
		} else {
			$userpasswordsql = mysqli_query($con, "SELECT password FROM users WHERE email='".$email."'");
			$userpassword = mysqli_fetch_row($userpasswordsql);
			if(password_verify($password, $userpassword[0])){
			    mysqli_query($con, "UPDATE users SET lastseen=CURRENT_TIMESTAMP WHERE email='".$email."'");
				//correct login
				$userinfosql = mysqli_query($con, "SELECT id,firstname,lastname FROM users WHERE email='".$email."'");
				$userinfo = mysqli_fetch_all($userinfosql);
				$_SESSION['id'] = $userinfo[0][0];
				$_SESSION['firstname'] = ucwords($userinfo[0][1]);
				$_SESSION['lastname'] = ucwords($userinfo[0][2]);
				$_SESSION['email'] = $email;
				header('Location: index.php');
			}
			else{
				$_SESSION['message'] = "Incorrect login please check details are correct";
			}
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<meta charset="UTF-8">
		<title>Day2Day</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
	</head>
	<body>
		<div class="loginmargin">
			<div class="loginbox">
				<form action="login.php" method="post">
					<h1 class="loginlbl">LOGIN</h1>
					<div id="errorbox">
						<a id="registererror" style="text-decoration:none"><?= $_SESSION['message'] ?></a>
					</div>
					<input name="email" id="loginemail" placeholder="Email" required></input>
					<input type="password" name="password" id="loginpassword" placeholder="Password" required></input>
					<input type="submit" id="loginbtn" value="SIGN IN">
					<p style="width: 100%; text-align:center; margin-top: 10px;">
						<a id="registerlbl" href="register.php" style="text-decoration:none">Register</a>
					</p>
				</form>
			</div>
		</div>
	</body>

</html>
<?php
	if(!isset($_SESSION['firstname']) && $_SESSION['message'] != ''){
		echo "<script type=\"text/javascript\"> $('#errorbox').fadeIn();</script>";
	}
?>