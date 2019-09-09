<?php
    date_default_timezone_set("Europe/London");
    session_start();
    if(isset($_POST['timeslot'])){
        $id = $_SESSION['id'];
		$email = $_SESSION['email'];
		$firstname = $_SESSION['firstname'];
		$lastname = $_SESSION['lastname'];
		$dateordered = date("d/m/Y");
		$timeordered = date("H:i:s");
		$userid = str_pad($id, 3, '0', STR_PAD_LEFT); 
		$ordernum = date("siHymd") . $userid;
		$ordertotal = $_SESSION['basket']['total'];
		$items = json_encode(array('items' => $_SESSION['basket']['items']));
		$finalorder = array(
			'ordernumber' => $ordernum,
			'ordertotal' => $ordertotal,
			'email' => $email,
			'firstname' => $firstname,
			'lastname' => $lastname,
			'dateordered' => $dateordered,
			'timeordered' => $timeordered,
			'timeslot'=> $_POST['timeslot'],
			'items'=> $_SESSION['order']['items']
		);
        $order = json_encode($finalorder);
        print_r($order);
        
        //create socket
        if(!($socket = socket_create(AF_INET, SOCK_STREAM, 0)))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            
            die("Couldn't create socket: [$errorcode] $errormsg \n");
        }
        
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array("sec"=>10, "usec"=>0));
        //Connect socket to remote server
        if(!socket_connect($socket , '151.224.75.121' , 443))
        {
            $errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            
            die("Could not connect: [$errorcode] $errormsg \n");
        }
        
        
        //sets max timeout to 10 second
        
        //Send the message to the server
        
        if( ! socket_send ( $socket, $order, strlen($order), 0))
        {
        	$errorcode = socket_last_error();
            $errormsg = socket_strerror($errorcode);
            
            die("Could not send data: [$errorcode] $errormsg \n");
        }else{
            header('Location: index.php');
        }
        
        while ($status = socket_read($socket, 2048)) {
            echo $status;
        }
        
    }
    
?>