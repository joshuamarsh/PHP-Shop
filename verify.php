<?php
    include("include/header.php");
    $_SESSION['verifymessage'] = '';
    if(isset($_GET['method'])){
        if($_GET['method'] == "resend"){
            $id = $_SESSION['id'];
            $email = $_SESSION['emailverify'];
            
            $result = mysqli_query($con, "SELECT * FROM users WHERE email='$email'");
			if($result->num_rows == 0){
			    $token=substr(md5(mt_rand()),0,32);
			    $checkverifyemailsql = mysqli_query($con, "SELECT * FROM mailverify WHERE id='".$id."'");
        		if($checkverifyemailsql->num_rows != 0){
        			$sql = "UPDATE mailverify SET token='".$token."' WHERE id='".$id."'";
    				if(!mysqli_query($con,$sql)){
    					$_SESSION['verifymessage'] = 'Failed to resend email please try again.';
    				}
    				else{
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
    					$_SESSION['verifymessage'] = 'Email succesfully resent.';
    				}
        		}
			    else{
			        //if email cannot be found
			    }
			}else{
			    $_SESSION['verifymessage'] = 'Email already verified please login.';
			}
        }else{
           if($_GET['method'] == "change"){
            
            } 
        }
    }
    if(isset($_GET['token']) && isset($_GET['id'])){
        $verifycode = $_GET['token'];
        $id = $_GET['id'];
        $checktokensql = mysqli_query($con, "SELECT * FROM mailverify WHERE id='".$id."' AND token='".$verifycode."'");
		if($checktokensql->num_rows == 0){
			$_SESSION['verifymessage'] = 'Failed to verify email please try again or resend a new link..';
		}else{
		    $userdata = mysqli_fetch_row($checktokensql);
		    $email = $userdata[1];
		    $firstname = $userdata[4];
		    $lastname = $userdata[5];
		    $password = $userdata[6];
		    $checkuser = $con->query("SELECT * FROM users WHERE email='$email'");
			if($checkuser->num_rows == 0){
    		    $insertusersql = "INSERT INTO users (firstname, lastname, email, password) VALUES ('$firstname','$lastname','$email','$password')";
    			if(!mysqli_query($con,$insertusersql)){
    				$_SESSION['verifymessage'] = 'Failed to create account please check all fields and try again.';
    			}
    			else{
    			    $userid = $con->insert_id;
    			    mysqli_query($con, "DELETE FROM mailverify WHERE id='".$id."'");
    			    $_SESSION['id'] = $userid;
                    $_SESSION['email'] = $email;
        		    $_SESSION['firstname'] = $firstname;
            		$_SESSION['lastname'] = $lastname;
    				header("location: /index.php");
    			} 
			}else{
			    $_SESSION['verifymessage'] = 'Email already verified please login.';
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
		<div class="verifypagecontent">
		    <div class="verifybox">
		        <div id="errorbox">
					<a id="registererror" style="text-decoration:none"><?= $_SESSION['verifymessage'] ?></a>
				</div>
    		    <div class="verifytitle">
    		        <h1>Please verify your email address.</h1>
    		    </div>
    		    <div class="verifytext">
    		        We have sent an email to <strong><?php echo $_SESSION['emailverify'];?></strong>. Check your inbox and click the link in the email to verify your address. If you can't find it, check your spam folder or click the button to resend the email.
    		    </div>
    		    <div class="verifybuttons">
    		        <a href="verify.php?method=resend" id="resendemailbtn"><span>Resend Email</span></a>
    		    </div>
		    </div>
		</div>
	</body>
</html>
<?php
	if($_SESSION['verifymessage'] != ''){
		echo "<script type=\"text/javascript\"> $('#errorbox').fadeIn();</script>";
	}
?>