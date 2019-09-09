<?php
	include("include/header.php");
	$_SESSION['message'] = '';
	
	if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		//see if passwords match
		if($_POST['password'] == $_POST['confirmpassword']){
			$firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$hashedpassword = password_hash($password, PASSWORD_DEFAULT);
			$domain = ltrim(stristr($email, '@'), '@') . '.';
			if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !checkdnsrr($domain, 'MX')) {
				$_SESSION['message'] = 'Invalid Email Address';
			}
			else{
			    if(strlen($password) < 17 && strlen($password) > 7){
			        if(strlen($firstname) < 128 && strlen($firstname) > 0 && strlen($lastname) < 128 && strlen($lastname)){
        				$result = $con->query("SELECT * FROM users WHERE email='$email'");
        				if($result->num_rows == 0){
        				    $token=substr(md5(mt_rand()),0,32);
        				    $checkverifyemailsql = mysqli_query($con, "SELECT * FROM mailverify WHERE email='".$email."'");
                    		if($checkverifyemailsql->num_rows != 0){
                    			mysqli_query($con, "DELETE FROM mailverify WHERE email='".$email."'");
                    		}
        				    $sql = "INSERT INTO mailverify (firstname, lastname, email, password, token) VALUES ('$firstname','$lastname','$email','$hashedpassword','$token')";
        					if(!mysqli_query($con,$sql)){
        						$_SESSION['message'] = 'Failed to create account please check all fields and try again.';
        					}
        					else{
                                $id = $con->insert_id;
                                $to=$email;
                                $subject="Please verify your email for day2day";
                                $body='
                                    <body>
                                        <a href="http://day2daystore.co.uk/verify.php?id='.$id.'&token='.$token.'" target="_blank" style="background-color:#fec52e; color:#2b3336; display:inline-block; font-family:Arial,sans-serif; 
                                                font-size:12px; font-weight:700; letter-spacing:2px; line-height:40px; margin:0; padding:0; text-align:center; text-decoration:none; white-space:nowrap; width:160px; 
                                                word-break:break-word; word-wrap:break-word">CONFIRM EMAIL</a>
                                    </body>';
                                $headers = "From: Day2Day <no-reply@day2daystore.co.uk>\r\n";
                                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                                mail($to,$subject,$body,$headers);
                                $_SESSION['emailverify'] = $email;
                                $_SESSION['id'] = $id;
        						header("location: /verify.php");
        					} 
        				}
        				else{
        					$_SESSION['message'] = 'Email Already Registered.';
        				}
			        }else{
			            $_SESSION['message'] = 'Firstname or lastname must be between 1 and 128 characters long.';
			        }
			    }else{
			        $_SESSION['message'] = 'Password must be between 8 and 16 characters long.';
			    }
			}
		} 
		else{
			$_SESSION['message'] = 'Failed to create account as passwords do not match';
		}
	}
	if(isset($_SESSION['email'])){
		header("location: /index.php");
	}
	if(!isset($_SESSION['firstname']) && $_SESSION['message'] != ''){
		echo "<script type=\"text/javascript\"> $('#errorbox').fadeIn();</script>";
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Day2Day</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>
	<body>
		<div class="loginmargin">
			<div class="loginbox">
				<form action="/register.php" method="post">
					<h1 class="loginlbl">REGISTER</h1>
					<div id="errorbox">
						<a id="registererror" style="text-decoration:none"><?= $_SESSION['message'] ?></a>
					</div>
					<input name="firstname" id="registerfirstname" placeholder="First Name" required></input>
					<input name="lastname" id="registersecondname" placeholder="Last Name" required></input>
					<input name="email" id="registeremail" placeholder="Email" required></input>
					<input name="password" type="password" id="registerpassword" placeholder="Password" required></input>
					<input name="confirmpassword" type="password" id="registerpassword" placeholder="Confirm Password" required></input>
					<input type="submit" id="registerbtn" value="CREATE ACCOUNT">
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