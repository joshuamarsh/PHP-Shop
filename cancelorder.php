<?php
	$config = include("include/config.php");
    if(isset($_GET['orderid'])){
        $con = mysqli_connect($config['db_ip'],$config['db_username'],$config['db_password']);
    	if(!$con)
    	{
    		echo 'Database connection not succesfull';
    	}
    	if(!mysqli_select_db($con, $config['db_name']))
    	{
    		echo 'Database could not be found';
    	}
    	$orderid = $_GET['orderid'];
    	$sqlUpdateOrder = "UPDATE orders SET status='canceled' WHERE orderid='".$orderid."'";
		if(!mysqli_query($con,$sqlUpdateOrder)){
			header('HTTP/1.1 500 Internal Server Error');
		}
		else{
            echo "success";
		}
    }
?>