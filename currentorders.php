<?php 
    include("include/header.php");
    if(!isset($_SESSION['email'])){
        header("location: /login.php");
    }
?>
<!DOCTYPE html>
<html>
	<head>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script language="javascript" src="./script/script.js"></script>
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
		<div class="orderhistory">
	        <div class="checkoutsubtitle">
	            <h2 class="subtitle">Pending Orders</h2>
	        </div>
	        <table class="orderstable">
	            <thead>
                    <tr>
                        <th class="thorderid">Order Number</th>
                        <th class="thordertimeslot">Timeslot</th>
                        <th class="thorderdateordered">Date Ordered</th>
                        <th class="thordertimeordered">Time Ordered</th>
                        <th class="thtotal">Total</th>
                        <th class="thcancel"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sqlorders = mysqli_query($con, "SELECT * FROM orders WHERE email='".$_SESSION['email']."' AND status='pending'");
                    $orders = mysqli_fetch_all($sqlorders);
                    $orderarray = "";
                    foreach($orders as $order){
                        $totalprice = number_format($order[2], 2);
                        $orderarray .=
        			    '<tr>
                            <td class="tableorderid">'.$order[0].'</td>
                            <td class="tableordertimeslot">'.$order[3].'</td>
                            <td class="tableorderdate">'.$order[4].'</td>
                            <td class="tableordertime">'.$order[5].'</td>
                            <td class="tableordertotal">&pound;'.$totalprice.'</td>
                            <td class="tableordercancel"><input class="cancelorderbtn" data-id="'.$order[0].'" type="button" value="Cancel"></td>
                        </tr>';
                    }
                    print_r($orderarray);
					?>
                </tbody>
	        </table>
	        
	        <div class="checkoutsubtitle">
	            <h2 class="subtitle">Orders Awaiting Collection</h2>
	        </div>
	        <table class="orderstable">
	            <thead>
                    <tr>
                        <th class="thorderid">Order Number</th>
                        <th class="thordertimeslot">Timeslot</th>
                        <th class="thorderdateordered">Date Ordered</th>
                        <th class="thordertimeordered">Time Ordered</th>
                        <th class="thtotal">Total</th>
                        <th class="thcancel"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sqlorders = mysqli_query($con, "SELECT * FROM orders WHERE email='".$_SESSION['email']."' AND status='ready'");
                    $orders = mysqli_fetch_all($sqlorders);
                    $orderarray = "";
                    foreach($orders as $order){
                        $totalprice = number_format($order[2], 2);
                        $orderarray .=
        			    '<tr>
                            <td class="tableorderid">'.$order[0].'</td>
                            <td class="tableordertimeslot">'.$order[3].'</td>
                            <td class="tableorderdate">'.$order[4].'</td>
                            <td class="tableordertime">'.$order[5].'</td>
                            <td class="tableordertotal">&pound;'.$totalprice.'</td>
                            <td class="tableordercancel"><input class="cancelorderbtn" data-id="'.$order[0].'" type="button" value="Cancel"></td>
                        </tr>';
                    }
                    print_r($orderarray);
					?>
                </tbody>
	        </table>
	    </div>
		<div class="accountpagecontent">

	    </div>
	</body>
</html>
