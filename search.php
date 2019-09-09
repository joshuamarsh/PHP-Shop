<?php
	$config = include("include/config.php");
	$con = mysqli_connect($config['db_ip'],$config['db_username'],$config['db_password']);
	if(!$con)
	{
		echo 'Database connection not succesfull';
	}
	if(!mysqli_select_db($con, $config['db_name']))
	{
		echo 'Database could not be found';
	}
	
	if(isset($_POST['itemsearch'])) {
	    $itemsfound = "";
		$searchitem = $_POST['itemsearch'];
		$mysqlseachitem = mysqli_query($con, "SELECT * FROM stock WHERE itemname LIKE '%$searchitem%'");
		$searchcount = mysqli_num_rows($mysqlseachitem);
		if($searchcount > 0){
			$itemtodisplay = mysqli_fetch_all($mysqlseachitem);
			foreach($itemtodisplay as $item){
						$itemsfound .= 
						'<li class="">
								<p class="itemtag">'.$item[1].'</p>
						</li>';
					}
		}else{
			$itemsfound = '<li class="">
								<p class="itemtag">No Items Found</p>
						</li>';
		}
		echo $itemsfound;
	}
	if(isset($_POST['itemsort'])){
	    $sortby = $_POST['itemsort'];

	    switch ($sortby) {
            case "AZ":
                $orderbysql = "SELECT * FROM stock ORDER BY itemname ASC";
                break;
            case "ZA":
                $orderbysql = "SELECT * FROM stock ORDER BY itemname DESC";
                break;
            case "LH":
                $orderbysql = "SELECT * FROM stock ORDER BY itemprice ASC";
                break;
            case "HL":
                $orderbysql = "SELECT * FROM stock ORDER BY itemprice DESC";
                break;
            case "CA":
                $orderbysql = "SELECT * FROM stock ORDER BY category ASC";
                break;
        }
	    $mysqlsortitem = mysqli_query($con, $orderbysql);
	    $itemsinorder = mysqli_fetch_all($mysqlsortitem);
	    print_r($itemsinorder);
	}
?>	