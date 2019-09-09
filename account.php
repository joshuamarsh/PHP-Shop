<?php 
    include("include/header.php");
    if(!isset($_SESSION['email'])){
        header("location: /login.php");
    }
    $_SESSION['updatemessage'] = '';
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
		//see if passwords match
		if($_POST['password'] == $_POST['confirmpassword']){
		    $id = $_SESSION['id'];
		    $firstname = $_POST['firstname'];
			$lastname = $_POST['lastname'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$hashedpassword = password_hash($password, PASSWORD_DEFAULT);
			if($_POST['password'] != ''){
		        $updatepasswordsql = ", password='".$hashedpassword."'";
		    }
			$domain = ltrim(stristr($email, '@'), '@') . '.';
			if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !checkdnsrr($domain, 'MX')) {
				$_SESSION['updatemessage'] = 'Invalid Email Address';
			}
			else{
			    if($email != $_SESSION['email']){
    			    $result = $con->query("SELECT * FROM users WHERE email='$email'");
    				if($result->num_rows == 0){
    				    $updatesql = "UPDATE users SET firstname='".$firstname."', lastname='".$lastname."' WHERE id='".$id."'";
        				if(!mysqli_query($con,$updatesql)){
        					$_SESSION['updatemessage'] = 'Failed to update account please try again.';
        				}else{
        				    
        				}
    				}
    				else{
    					$_SESSION['updatemessage'] = 'An error has occurred please try again later.';
    				}
			    }else{
			        $updatesql = "UPDATE users SET firstname='".$firstname."', lastname='".$lastname."' ".$updatepasswordsql." WHERE id='".$id."'";
    				if(!mysqli_query($con,$updatesql)){
    					$_SESSION['updatemessage'] = 'Failed to update account please try again.';
    				}else{
    				    $_SESSION['updatemessage'] = 'Account succesfully updated.';
    				    $_SESSION['firstname'] = $firstname;
    				    $_SESSION['lastname'] = $lastname;
    				}
			    }
			}
		}else{
			$_SESSION['updatemessage'] = 'Failed to update account as passwords do not match';
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
	    <nav class="account_out">
		
			<ul class="account_menu">
				<li class="account_item">
    				<a class="account_title" href="../account.php">Account Details</a>
    			</li>
    			<li class="account_item">
    				<a class="account_title" href="../currentorders.php">Current Orders</a>
    			</li>	
    			<li class="account_item">
    				<a class="account_title" href="../orders.php">Order History</a>
    			</li>	
			</ul>
			
		</nav>
		<div class="accountpagecontent">
		    <div class="accountinfo">
		        <h1>Account Details</h1>
		        <div id="errorbox">
					<a id="registererror" style="text-decoration:none"><?= $_SESSION['updatemessage'] ?></a>
				</div>
		        <form autocomplete="off" method="post" action="/account.php">
		            <p>
		                <label for="firstname">First Name:</label>
		                <input name="firstname" id="firstname" type="text" class="accounttextinput" value=<?php echo '"'.$_SESSION['firstname'].'"'?>>
                    </p>
                    <p>
		                <label for="lastname">Last Name:</label>
		                <input name="lastname" id="lastname" type="text" class="accounttextinput" value=<?php echo '"'.$_SESSION['lastname'].'"'?>>
                    </p>
                    <p>
		                <label for="email">Email:</label>
		                <input name="email" id="email" type="email" class="accounttextinput" value=<?php echo '"'.$_SESSION['email'].'"'?> readonly>
                    </p>
                    <p>
		                <label for="password">New Password:</label>
		                <input name="password" id="password" type="password" autocomplete="new-password" class="accounttextinput">
                    </p>
                    <p>
		                <label for="confirmpassword">Confirm Password:</label>
		                <input name="confirmpassword" id="confirmpassword" type="password" autocomplete="new-password" class="accounttextinput">
                    </p>
                    
                    <p>&nbsp;</p>
                    
                    <div class="accountbtn">
                        <input name="submit" type="submit" value="Update" id="updateaccountinfo">
                    </div>
		        </form>
		    </div>
	    </div>
	</body>
</html>
<?php
	if($_SESSION['updatemessage'] != ''){
		echo "<script type=\"text/javascript\"> $('#errorbox').fadeIn();</script>";
	}
?>
