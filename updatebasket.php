<?php
    $config = include("include/config.php");
    session_start();
	if(isset($_SESSION['email'])){
		$con = mysqli_connect($config['db_ip'],$config['db_username'],$config['db_password']);
		if(!$con)
		{
			echo 'Database connection not succesfull';
		}
		if(!mysqli_select_db($con, $config['db_name']))
		{
			echo 'Database could not be found';
		}
		
		if(!empty($_GET["method"])){
	        $baskettotal = 0;
		    $method = $_GET["method"];
		    $itemean =  $_GET["ean"];  
		    switch ($method) {
                case "reducequantity":
                    if (in_array($_GET["ean"], array_column($_SESSION['basket']['items'], 'ean'))){
                        $index = array_search($itemean, array_column($_SESSION['basket']['items'], 'ean'));
                        if($_SESSION['basket']['items'][$index]['quantity'] > 1){
                            $_SESSION['basket']['items'][$index]['quantity'] = $_SESSION['basket']['items'][$index]['quantity'] - 1;
                            foreach($_SESSION['basket']['items'] as $itemarray)
                		    {
                		        $baskettotal = $baskettotal + ($itemarray['price'] * $itemarray['quantity']);
                		    }
                		    $_SESSION['basket']['total'] = number_format($baskettotal, 2);
                            $cartarray = json_encode(array('quantity'=>$_SESSION['basket']['items'][$index]['quantity'], 'total'=>number_format($baskettotal, 2)));
                            print_r($cartarray);
                        }
                    }
                    break;
                case "addquantity":
                    if (in_array($_GET["ean"], array_column($_SESSION['basket']['items'], 'ean'))){
                        $index = array_search($itemean, array_column($_SESSION['basket']['items'], 'ean'));
                        $_SESSION['basket']['items'][$index]['quantity'] = $_SESSION['basket']['items'][$index]['quantity'] + 1;
                        foreach($_SESSION['basket']['items'] as $itemarray)
            		    {
            		        $baskettotal = $baskettotal + ($itemarray['price'] * $itemarray['quantity']);
            		    }
            		    $_SESSION['basket']['total'] = number_format($baskettotal, 2);
                        $cartarray = json_encode(array('quantity'=>$_SESSION['basket']['items'][$index]['quantity'], 'total'=>number_format($baskettotal, 2)));
                        print_r($cartarray);
                    }
                    break;
                case "removeitem":
                    if (in_array($_GET["ean"], array_column($_SESSION['basket']['items'], 'ean'))){
                        $index = array_search($itemean, array_column($_SESSION['basket']['items'], 'ean'));
                        unset($_SESSION['basket']['items'][$index]);
                        $basket = "";
                        foreach($_SESSION['basket']['items'] as $itemarray)
                		{
                		    $baskettotal = $baskettotal + ($itemarray['price'] * $itemarray['quantity']);
            			    $itemprice = number_format($itemarray['price'], 2);
            			    $basket .=
            			    '<li class="miniitemview">
                			    <form action="" method="GET">
            						<input type="hidden" name="ean" value="'.$itemarray['ean'].'">
                    				<div class="miniviewitemframe">
                    					<div>
                    						<div class="miniitemadd">
                    							<button class="miniitemremovebtn" type="submit" name="method" value="addquantity">
                    							    <span class="miniitemremovebtnx">+</span>
                    							</button>
                    						</div>
                    						<span class="miniviewamount">'.$itemarray['quantity'].'</span>
                    						<div class="miniitemminus">
                    							<button class="miniitemremovebtn" type="submit" name="method" value="reducequantity">
                    						    	<span class="miniitemremovebtnx">-</span>
                    							</button>
                    						</div>
                    					</div>
                    					<span class="miniviewprice">
                    						<span class="itemprice">&pound;</span>
                    						<span> </span>
                    						<span class="itemprice">'.$itemprice.'</span>
                    					</span>
                    					<div class="miniviewimageframe">
                    						<img class="miniviewimage" src="/images/stock/'.$itemarray['image'].'">
                    					</div>
                    					<div class="miniitemnameframe">
                    						<span class="miniitemname">'.$itemarray['name'].'</span>
                    					</div>
                    					<div class="miniitemremove">
                    						<button class="miniitemremovebtn" name="method" value="removeitem" type="submit">
                    						<span class="miniitemremovebtnx">X</span>
                    					</div>
                    				</div>
                				</form>
            			    </li>';
                		}
                        $_SESSION['basket']['total'] = number_format($baskettotal, 2);
                        $cartarray = json_encode(array('items'=>$basket, 'total'=>number_format($baskettotal, 2)));
                        print_r($cartarray);
                    }
                    break;
                case "updatebasket":
                    if(!empty($_GET["ean"])) {
            		    $itemquantity = $_GET["itemamount"];
            		    
                        $itemsql = mysqli_query($con, "SELECT * FROM stock WHERE ean='".$itemean."'");
                        $item = mysqli_fetch_assoc($itemsql);
                        if(isset($_SESSION['basket'])){
                            if (in_array($itemean, array_column($_SESSION['basket']['items'], 'ean'))){
                                $index = array_search($itemean, array_column($_SESSION['basket']['items'], 'ean'));
                                $_SESSION['basket']['items'][$index]['quantity'] = $_SESSION['basket']['items'][$index]['quantity'] + $itemquantity;
                                $_SESSION['order']['items'][$index]['quantity'] = $_SESSION['order']['items'][$index]['quantity'] + $itemquantity;
                            }else{
                                //$_SESSION['basket']['items'][$itemean] = array('name'=>$item["itemname"], 'price'=>$item["itemprice"], 'quantity'=>$itemquantity, 'image'=>$item["itemimage"]);
                                array_push($_SESSION['basket']['items'], array('ean'=>$itemean, 'name'=>$item["itemname"], 'price'=>$item["itemprice"], 'quantity'=>$itemquantity, 'image'=>$item["itemimage"]));
                                array_push($_SESSION['order']['items'], array('ean'=>$itemean, 'quantity'=>$itemquantity));
                            }
                        }else{
            			    //$_SESSION['basket'] = array(count($_SESSION['basket'])=>array('ean'=>$itemean, 'name'=>$item["itemname"], 'price'=>$item["itemprice"], 'quantity'=>$itemquantity));
            			    $_SESSION['basket']['items'][0] =  array('ean'=>$itemean, 'name'=>$item["itemname"], 'price'=>$item["itemprice"], 'quantity'=>$itemquantity, 'image'=>$item["itemimage"]);
            			    $_SESSION['order']['items'][0] =  array('ean'=>$itemean, 'quantity'=>$itemquantity);
                        }
            		}
                    $basket = "";
                    foreach($_SESSION['basket']['items'] as $itemarray)
            		{
            		    $baskettotal = $baskettotal + ($itemarray['price'] * $itemarray['quantity']);
        			    $itemprice = number_format($itemarray['price'], 2);
        			    $basket .=
        			    '<li class="miniitemview">
            			    <form action="" method="GET">
        						<input type="hidden" name="ean" value="'.$itemarray['ean'].'">
                				<div class="miniviewitemframe">
                					<div>
                						<div class="miniitemadd">
                							<button class="miniitemremovebtn" type="submit" name="method" value="addquantity">
                							    <span class="miniitemremovebtnx">+</span>
                							</button>
                						</div>
                						<span class="miniviewamount">'.$itemarray['quantity'].'</span>
                						<div class="miniitemminus">
                							<button class="miniitemremovebtn" type="submit" name="method" value="reducequantity">
                						    	<span class="miniitemremovebtnx">-</span>
                							</button>
                						</div>
                					</div>
                					<span class="miniviewprice">
                						<span class="itemprice">&pound;</span>
                						<span> </span>
                						<span class="itemprice">'.$itemprice.'</span>
                					</span>
                					<div class="miniviewimageframe">
                						<img class="miniviewimage" src="/images/stock/'.$itemarray['image'].'">
                					</div>
                					<div class="miniitemnameframe">
                						<span class="miniitemname">'.$itemarray['name'].'</span>
                					</div>
                					<div class="miniitemremove">
                						<button class="miniitemremovebtn" name="method" value="removeitem" type="submit">
                						<span class="miniitemremovebtnx">X</span>
                					</div>
                				</div>
            				</form>
        			    </li>';
            		}
                    $_SESSION['basket']['total'] = number_format($baskettotal, 2);
                    $cartarray = json_encode(array('items'=>$basket, 'total'=>number_format($baskettotal, 2)));
                    print_r($cartarray);
                    break;
                }
		}
	}
	else{
        header('HTTP/1.1 500 Internal Server Error');
	    exit("login");
	}
?>