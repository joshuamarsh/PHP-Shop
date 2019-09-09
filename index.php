<?php 
    function GetPageUrl($pagenum) {
        if($pagenum == "..."){
            
        }else{
            $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $url_parts = parse_url($url);
            parse_str($url_parts['query'], $params);
            $params['page'] = $pagenum;
            $url_parts['query'] = http_build_query($params);
            echo '"/?'.$url_parts['query'].'"';
        }
    }
	include("include/header.php");
	$page = 1;
	$page0 = 2;
    $page1 = 3;
    $page2 = 4;
    $page3 = 5;
	if(isset($_GET['page'])){
	    $page = $_GET['page'];
	    if($page > 4){
	        $page0 = "...";
	        $page1 = $page-1;
	        $page2 = $page;
	        $page3 = $page+1;
	    }
	}else{
	    
	}
	
	$itemsperpage = 20;
	if(isset($_GET['count'])){
	    $itemsperpage = $_GET['count'];
	}
	$numberofdisplayed = "";
	
	if(isset($_GET['show'])){
	    $sqlitems = mysqli_query($con, "SELECT stock.ean, stock.itemname, stock.itemprice, stock.itemimage, stock.category, stock.subcategory, SUM(orderline.quantity) 
	                                    FROM `stock`, `orders`, `orderline` WHERE orders.email = '".$_SESSION['email']."' AND orderline.orderid = orders.orderid AND orderline.ean = stock.ean GROUP BY stock.ean");
	}else{
    	$sortbysql = "";
    	$sortby = "";
    	if(isset($_GET['sortby'])){
    	    $sortby = $_GET['sortby'];
    	    switch ($sortby) {
                case "AZ":
                    $sortbysql = " ORDER BY itemname ASC";
                    break;
                case "ZA":
                    $sortbysql = " ORDER BY itemname DESC";
                    break;
                case "LH":
                    $sortbysql = " ORDER BY itemprice ASC";
                    break;
                case "HL":
                    $sortbysql = " ORDER BY itemprice DESC";
                    break;
                case "CA":
                    $sortbysql = " ORDER BY category ASC";
                    break;
                case "PO":
                    $sortbysql = " ORDER BY quantitysold DESC";
                    break;
            }
    	}
    	
    	$searchitemsql = "";
    	if(isset($_GET['search'])){
    	    $searchitem = $_GET['search'];
    	    $searchitemsql = " WHERE itemname LIKE '%$searchitem%'";
    	}
    	
    	if(isset($_GET['category'])){
            if(isset($_GET['subcategory'])){
                $sqlitems = mysqli_query($con, "SELECT * FROM stock WHERE category='".$_GET['category']."' AND subcategory='".$_GET['subcategory']."'".$sortbysql);
            }else{
                $sqlitems = mysqli_query($con, "SELECT * FROM stock WHERE category='".$_GET['category']."'".$sortbysql);
            }
        }else{
            $sqlitems = mysqli_query($con, "SELECT * FROM stock".$searchitemsql.$sortbysql);
        }
	}
    $stock = mysqli_fetch_all($sqlitems);
	
	
	$numberofitems = mysqli_num_rows($sqlitems);
	if($numberofitems > $itemsperpage){
	    $numberofdisplayed = $itemsperpage;
	}
	else{
	    $numberofdisplayed = $numberofitems;
	}
	$numberofpages = intdiv($numberofitems, $itemsperpage) + 1;
?>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Day2Day</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" type="text/css" href="css/jquery.notyfy.css">
		<link rel="stylesheet" type="text/css" href="css/jquery.notyfy.default.css">
		<script language="javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script language="javascript" src="./script/script.js"></script>
		<script type="text/javascript" src="./script/jquery.notyfy.js"></script>
	</head>
	<body>
        <div class="filtermenubackground">
            <div class="filtermenuinner">
		        <div class="filter desc">
                    <span>Displaying products <?php echo (($page * $itemsperpage) - $itemsperpage)+1; ?> to <?php if($numberofitems > ($page * $itemsperpage)){ echo $page * $itemsperpage; }else{ echo $numberofitems; } ?> of <?php echo $numberofitems; ?> </span>
                </div>
                <div class="filter perpage">
                    <form action="" method="get" name="itemsperpage">
                        <label for="count">Show per page:</label>
                        <select name="count" id="itemsperpage">
                            <option <?php if ($itemsperpage == '20') echo ' selected="selected"'; ?> value="20">20</option>
                            <option <?php if ($itemsperpage == '40') echo ' selected="selected"'; ?> value="40">40</option>
                            <option <?php if ($itemsperpage == '60') echo ' selected="selected"'; ?> value="60">60</option>
                            <option <?php if ($itemsperpage == '80') echo ' selected="selected"'; ?> value="80">80</option>
                            <option <?php if ($itemsperpage == '100') echo ' selected="selected"'; ?> value="100">100</option>
                        </select>
                        <input type="submit" name="go" value="Go" style="display: none">
                    </form>
                </div>
			    <div class="filter sort">
					<form name="itemsortmenu" action="" method="get">
                        <label for="sortby">Sort by:</label>
                        <?php 
                            if(isset($_GET['search'])){
                                echo '<input type="hidden" name="search" value="'.$_GET['search'].'">';
                            }
                            if(isset($_GET['category'])){
                                echo '<input type="hidden" name="category" value="'.$_GET['category'].'">';
                                if(isset($_GET['subcategory'])){
                                    echo '<input type="hidden" name="subcategory" value="'.$_GET['subcategory'].'">';
                                }
                            }
                        ?>
                        <select name="sortby" id="sortitem">
                            <option <?php if ($sortby == 'PO') echo ' selected="selected"'; ?> value="PO">Popularity</option>
                            <option <?php if ($sortby == 'AZ') echo ' selected="selected"'; ?> value="AZ">Name (A-Z)</option>
                            <option <?php if ($sortby == 'ZA') echo ' selected="selected"'; ?> value="ZA">Name (Z-A)</option>
                            <option <?php if ($sortby == 'LH') echo ' selected="selected"'; ?> value="LH">Price (Low-High)</option>
                            <option <?php if ($sortby == 'HL') echo ' selected="selected"'; ?> value="HL">Price (High-Low)</option>
                            <option <?php if ($sortby == 'CA') echo ' selected="selected"'; ?> value="CA">Category</option>
                        </select>
                    </form>
                </div>
                <div class="filter pagenum">
                    <ul>
                        <?php
                            if($page != "1"){
                                echo '<li><a href=';
                                GetPageUrl($page-1); 
                                echo '>Prev</a></li>';
                            }
                        ?>
                        <li <?php if ($page == "1" || !isset($_GET['page'])) echo ' class="current"'; ?>>
                            <a href= <?php GetPageUrl("1"); ?> >1</a>
                        </li>
                        
                        <?php 
                        
                        if($numberofpages > 1){
                            echo '<li';
                            if ($page == $page0) echo ' class="current"'; 
                            echo '><a href= ';
                            GetPageUrl($page0);
                            echo '>' ;
                            echo $page0.'</a></li>';
                        }
                        
                        if($numberofpages > 2){
                            echo '<li';
                            if ($page == $page1) echo ' class="current"'; 
                            echo '><a href= ';
                            GetPageUrl($page1);
                            echo '>' ;
                            echo $page1.'</a></li>';
                        }
                        
                        if($numberofpages > 3){
                            echo '<li';
                            if ($page == $page2) echo ' class="current"'; 
                            echo '><a href= ';
                            GetPageUrl($page2);
                            echo '>' ;
                            echo $page2.'</a></li>';
                        }
                        
                        if($numberofpages > 4){
                            echo '<li';
                            if ($page == $page3) echo ' class="current"'; 
                            echo '><a href= ';
                            GetPageUrl($page3);
                            echo '>' ;
                            echo $page3.'</a></li>';
                        }
                        
                        if($numberofpages > 5){
                            echo '<li><a>…</a></li>';
                        }
                        
                        if($numberofpages > 5){
                            echo '<li><a>80</a></li>';
                        }
                        
                        if($page != $numberofpages){
                            echo '<li><a href=';
                            GetPageUrl($page+1); 
                            echo '>Next</a></li>';
                        }
                        ?>
                    </ul>
				</div>
			</div>
		</div>
		<div class="pagecontent">
		    
			<div class="maincontent">
			    
				<ul id="products">
					<?php
					$stock = array_slice($stock, ($page * $itemsperpage) - $itemsperpage, $page * $itemsperpage); 
					foreach($stock as $item){
						$stockprice = number_format($item[2], 2);
						echo 
						'<li class="itemframe">
								<div class="item">
									<div class="itemimgframe">
										<img class="itemimg" src="/images/stock/'.$item[3].'">
									</div>
										<div class="itemtitleframe">
											<p name="itemname" class="itemtitle">'.$item[1].'</p>
										</div>
										<div class="itempriceframe">
											<span>
												<span class="itemprice">&pound;</span>
												<span> </span>
												<span name="itemprice" class="itemprice">'.$stockprice.'</span>
											</span>
										</div>
										<div class="additemframe">
											<form action="updatebasket.php" method="GET">
											<input type="hidden" name="ean" value="'.$item[0].'">
											<input type="hidden" name="method" value="updatebasket">
											<input name="itemamount" type="number" class="itemamount" autocomplete="off" value="1" min="1" max="99">
											<input data-id="'.$item[4].'" class="additembtn" type="submit" value="Add">
											</form>
										</div>
								</div>
						</li>';
					}
					
					?>
				</ul>
			</div>
			<div class="sidebar">
				<div id="basketview">
					<div id="minibasketviewheader">
						<?php 
						if(isset($_SESSION['basket'])){
							if($_SESSION['basket']['total'] != 0){ 
								echo '<span style="color: black;">Total Price: </span><span id="baskettotal">'.'&pound; ' . $_SESSION['basket']['total'].' </span>';
							} 
							else{ 
								echo '<p style="text-align: center; width: 100%;">Basket is empty</p>';
							}
						}
						else{ 
								echo '<p style="text-align: center; width: 100%;">Basket is empty</p>';
						}
						?>
							<a href="checkout.php" class="checkoutbtn"><span>CHECKOUT</span></a>
					</div>
					<ul style="overflow-y: scroll; height: 100%; padding-bottom: 5px;">
						<?php
						$cartarray = "";
						if(isset($_SESSION['basket'])){
				            foreach($_SESSION['basket']['items'] as $itemarray)
                    		{
                    			$itemprice = number_format($itemarray['price'], 2);
                    			$cartarray .=
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
                    		print_r($cartarray);
						}
						?>
					</ul>
				</div>
			</div>
		</div>
	</body>
	<footer>
	    <div class="filtermenubackground">
            <div class="filtermenuinner">
                <div class="filter pagenum">
                    <ul>
                        <?php
                            if($page != "1"){
                                echo '<li><a href=';
                                GetPageUrl($page-1); 
                                echo '>Prev</a></li>';
                            }
                        ?>
                        <li <?php if ($page == "1" || !isset($_GET['page'])) echo ' class="current"'; ?>>
                            <a href= <?php GetPageUrl("1"); ?> >1</a>
                        </li>
                        
                        <?php 
                        
                        if($numberofpages > 1){
                            echo '<li';
                            if ($page == $page0) echo ' class="current"'; 
                            echo '><a href= ';
                            GetPageUrl($page0);
                            echo '>' ;
                            echo $page0.'</a></li>';
                        }
                        
                        if($numberofpages > 2){
                            echo '<li';
                            if ($page == $page1) echo ' class="current"'; 
                            echo '><a href= ';
                            GetPageUrl($page1);
                            echo '>' ;
                            echo $page1.'</a></li>';
                        }
                        
                        if($numberofpages > 3){
                            echo '<li';
                            if ($page == $page2) echo ' class="current"'; 
                            echo '><a href= ';
                            GetPageUrl($page2);
                            echo '>' ;
                            echo $page2.'</a></li>';
                        }
                        
                        if($numberofpages > 4){
                            echo '<li';
                            if ($page == $page3) echo ' class="current"'; 
                            echo '><a href= ';
                            GetPageUrl($page3);
                            echo '>' ;
                            echo $page3.'</a></li>';
                        }
                        
                        if($numberofpages > 5){
                            echo '<li><a>…</a></li>';
                        }
                        
                        if($numberofpages > 5){
                            echo '<li><a>80</a></li>';
                        }
                        
                        if($page != $numberofpages){
                            echo '<li><a href=';
                            GetPageUrl($page+1); 
                            echo '>Next</a></li>';
                        }
                        ?>
                    </ul>
				</div>
			</div>
		</div>
	</footer>
</html>
<?php
    if(isset($_SESSION['email'])){
        $checkorderchangesql = mysqli_query($con, "SELECT * FROM orders WHERE email='".$_SESSION['email']."'");
        $checkorderchangerow = mysqli_fetch_assoc($checkorderchangesql);
        print_r($checkorderchangerow);
        $orderstatus = $checkorderchangerow['status'];
        $message = "Order: ".$checkorderchangerow['orderid']." has been canceled Reason: ".$checkorderchangerow['message'];
        $messageread = $checkorderchangerow['messageread'];
        if($orderstatus == "rejected"){
            if($messageread == 0){
                echo "<script>
                    var notyfy = notyfy({text: '".$message."', type: 'error'});
                </script>";
                mysqli_query($con, "UPDATE orders SET messageread=1 WHERE orderid='".$checkorderchangerow['orderid']."'");
            }
        }
    }
?>
