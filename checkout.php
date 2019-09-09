<?php 
	date_default_timezone_set("Europe/London");
	include("include/header.php");
	if(isset($_SESSION['ordertime'])){
		$timeclass = "timeslotselected";
	}
	else{
		$timeclass = "timeslotavaliable";
	}
	if(!isset($_SESSION['basket']) || $_SESSION['basket'] == ""){
		header("location: /index.php");
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Day2Day</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script language="javascript" src="./script/script.js"></script>
		<script language="javascript" src="./script/date.js"></script>
	</head>
	<body>
		<div class="checkoutcontent">
		    <div class="checkoutbasket">
		        <div class="checkoutsubtitle">
		            <h2 class="subtitle">Basket</h2>
		        </div>
		        <table class="baskettable">
		            <thead>
                        <tr>
                            <th class="thitemimg"></th>
                            <th class="thitemname"></th>
                            <th class="thitemprice">Price</th>
                            <th class="thitemqty">Quantity</th>
                            <th class="thtotal">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
						if(isset($_SESSION['basket'])){
				            foreach($_SESSION['basket']['items'] as $itemarray)
                    		{
                    			$itemprice = number_format($itemarray['price'], 2);
                    			$totalitemprice = number_format($itemprice * $itemarray['quantity'], 2);
                			    
                			    $cartarray .=
                			    '<tr>
                                    <td class="tablebasketitemimg">
                                        <img width="30" height="30" class="tableimg" alt="" src="/images/stock/'.$itemarray['image'].'">
                                    </td>
                                    <td class="tablebasketitemname">'.$itemarray['name'].'</td>
                                    <td class="tablebasketitemprice">&pound;'.$itemprice.'</td>
                                    <td class="tablebasketitemqty">'.$itemarray['quantity'].'</td>
                                    <td class="tablebasketitemtotalprice">&pound;'.$totalitemprice.'</td>
                                </tr>';
                    		}
                    		print_r($cartarray);
						}
						?>
                    </tbody>
                    <tfoot>
					    <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
						    <td class="tablebasketitemtotalprice">&pound;<?php echo $_SESSION['basket']['total']; ?></td>
					    </tr>
				    </tfoot>
		        </table>
		    </div>
			<div class="selecttimeframe">
				<div class="checkoutsubtitle">
		            <h2 class="subtitle">Select Time</h2>
		        </div>
				<table class="choosetimetable">
					<thead>
						<th class="tabledayfiller"></th>
						<th class="tableday">
							<span style="color: grey;"><?php echo date("D"); ?></span>
							<span><?php echo date("d"); ?></span>
						</th>
						<th class="tableday">
							<span style="color: grey;"><?php echo date("D" , strtotime(' +1 day')); ?></span>
							<span><?php echo date("d" , strtotime(' +1 day')); ?></span>
						</th>
						<th class="tableday">
							<span style="color: grey;"><?php echo date("D" , strtotime(' +2 day')); ?></span>
							<span><?php echo date("d" , strtotime(' +2 day')); ?></span>
						</th>
						<th class="tableday">
							<span style="color: grey;"><?php echo date("D" , strtotime(' +3 day')); ?></span>
							<span><?php echo date("d" , strtotime(' +3 day')); ?></span>
						</th>
						<th class="tableday">
							<span style="color: grey;"><?php echo date("D" , strtotime(' +4 day')); ?></span>
							<span><?php echo date("d" , strtotime(' +4 day')); ?></span>
						</th>
						<th class="tableday">
							<span style="color: grey;"><?php echo date("D" , strtotime(' +5 day')); ?></span>
							<span><?php echo date("d" , strtotime(' +5 day')); ?></span>
						</th>
						<th class="tableday">
							<span style="color: grey;"><?php echo date("D" , strtotime(' +6 day')); ?></span>
							<span><?php echo date("d" , strtotime(' +6 day')); ?></span>
						</th>
					</thead>
					<tbody>
						<tr>
							<th scope="row" class="tabletimes">08:00 - 10:00</th>
							<td>
								 <div id=<?php if(strtotime(date("H:i")) > strtotime('7:00')){
									echo '"timeslotunavaliable">';
									echo '<span class="timeunavalibletag">Unavailable<span>';
								}
								else{
									echo $timeclass;
									echo '>';
									echo '<input type="hidden" name="time" value="'.date("d/m/Y 10:00-12:00").'">';
									echo '<input type="submit" class="timeslotbtn" value="Avaliable">';}?>
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 08:00-10:00" , strtotime(' +1 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 08:00-10:00" , strtotime(' +2 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 08:00-10:00" , strtotime(' +3 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 08:00-10:00" , strtotime(' +4 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 08:00-10:00" , strtotime(' +5 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 08:00-10:00" , strtotime(' +6 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
						</tr>
							
						<tr>
							<th scope="row" class="tabletimes">10:00 - 12:00</th>
							<td>
								<div id=<?php if(strtotime(date("H:i")) > strtotime('9:00')){
									echo '"timeslotunavaliable">';
									echo '<span class="timeunavalibletag">Unavailable<span>';
								}
								else{
									echo $timeclass;
									echo '>';
									echo '<input type="hidden" name="time" value="'.date("d/m/Y 10:00-12:00").'">';
									echo '<input type="submit" class="timeslotbtn" value="Avaliable">';}?>
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 10:00-12:00" , strtotime(' +1 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 10:00-12:00" , strtotime(' +2 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 10:00-12:00" , strtotime(' +3 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 10:00-12:00" , strtotime(' +4 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 10:00-12:00" , strtotime(' +5 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 10:00-12:00" , strtotime(' +6 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
						</tr>
							
						<tr>
							<th scope="row" class="tabletimes">12:00 - 14:00</th>
							<td>
								<div id=<?php if(strtotime(date("H:i")) > strtotime('11:00')){
									echo '"timeslotunavaliable">';
									echo '<span class="timeunavalibletag">Unavailable<span>';
								}
								else{
									echo $timeclass;
									echo '>';
									echo '<input type="hidden" name="time" value="'.date("d/m/Y 12:00-14:00").'">';
									echo '<input type="submit" class="timeslotbtn" value="Avaliable">';}?>
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 12:00-14:00" , strtotime(' +1 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 12:00-14:00" , strtotime(' +2 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 12:00-14:00" , strtotime(' +3 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 12:00-14:00" , strtotime(' +4 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 12:00-14:00" , strtotime(' +5 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 12:00-14:00" , strtotime(' +6 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
						</tr>
							
						<tr>
							<th scope="row" class="tabletimes">14:00 - 16:00</th>
							<td>
								<div id=<?php if(strtotime(date("H:i")) > strtotime('13:00')){
									echo '"timeslotunavaliable">';
									echo '<span class="timeunavalibletag">Unavailable<span>';
								}
								else{
									echo $timeclass;
									echo '>';
									echo '<input type="hidden" name="time" value="'.date("d/m/Y 14:00-16:00").'">';
									echo '<input type="submit" class="timeslotbtn" value="Avaliable">';}?>
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 14:00-16:00" , strtotime(' +1 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 14:00-16:00" , strtotime(' +2 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 14:00-16:00" , strtotime(' +3 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 14:00-16:00" , strtotime(' +4 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 14:00-16:00" , strtotime(' +5 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 14:00-16:00" , strtotime(' +6 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
						</tr>
							
						<tr>
							<th scope="row" class="tabletimes">16:00 - 18:00</th>
							<td>
								<div id=<?php if(strtotime(date("H:i")) > strtotime('15:00')){
									echo '"timeslotunavaliable">';
									echo '<span class="timeunavalibletag">Unavailable<span>';
								}
								else{
									echo $timeclass;
									echo '>';
									echo '<input type="hidden" name="time" value="'.date("d/m/Y 16:00-18:00").'">';
									echo '<input type="submit" class="timeslotbtn" value="Avaliable">';}?>
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 16:00-18:00" , strtotime(' +1 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 16:00-18:00" , strtotime(' +2 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 16:00-18:00" , strtotime(' +3 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 16:00-18:00" , strtotime(' +4 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 16:00-18:00" , strtotime(' +5 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 16:00-18:00" , strtotime(' +6 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
						</tr>
						
						<tr>
							<th scope="row" class="tabletimes">18:00 - 20:00</th>
							<td>
								<div id=<?php if(strtotime(date("H:i")) > strtotime('17:00')){
									echo '"timeslotunavaliable">';
									echo '<span class="timeunavalibletag">Unavailable<span>';
								}
								else{
									echo $timeclass;
									echo '>';
									echo '<input type="hidden" name="time" value="'.date("d/m/Y 18:00-20:00").'">';
									echo '<input type="submit" class="timeslotbtn" value="Avaliable">';}?>
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 18:00-20:00" , strtotime(' +1 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 18:00-20:00" , strtotime(' +2 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 18:00-20:00" , strtotime(' +3 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 18:00-20:00" , strtotime(' +4 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 18:00-20:00" , strtotime(' +5 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
							<td>
								<div id="<?php echo $timeclass?>">
									<?php echo '<input type="hidden" name="time" value="'.date("d/m/Y 18:00-20:00" , strtotime(' +6 day')).'">';?>
									<input type="submit" class="timeslotbtn" value="Avaliable">
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
	        <div class="overview">
	            <div class="checkoutsubtitle">
    	            <h2 class="subtitle">Overview</h2>
    	        </div>
    	        <div class="overviewdata">
	                <p id="timeslotoverview">Please select a time slot to continue</p>
	            </div>
	        </div>
		</div>
	</body>
	<script>	
			var timeslot;
			var displaytimeslot;
			var header = document.getElementById("timeslotavaliable");
			var btns = document.getElementsByClassName("timeslotbtn");
			var showtimeelement = document.createElement("p"); 
			var completeorderbtn = document.createElement("form");
			completeorderbtn.method = "post";
			completeorderbtn.action = "sendorder.php";
			for (var i = 0; i < btns.length; i++) {
			  btns[i].addEventListener("click", function() {
				timeslot = $(this).parent('#timeslotavaliable').children('input[name="time"]').attr("value");
				displaytimeslot = Date.parse(timeslot.substring(0, timeslot.length - 12)).toString("dddd dS MMMM yyyy");
				if ($("#timeslotselected").length > 0){
					var currentselected = document.getElementById("timeslotselected");
					currentselected.parentElement.id = "timeslotavaliable";
					currentselected.value = "Avaliable";
					currentselected.className = "timeslotbtn";
					currentselected.removeAttribute("id");
					showtimeelement.innerHTML = "Selected time: " + timeslot;   
					document.getElementById("timeslotoverview").textContent= "Requested pickup time: " + displaytimeslot + " between " + timeslot.substring(timeslot.length-12, timeslot.length); 
				}  
				else{
					showtimeelement.innerHTML = "Selected time: " + timeslot;   
					document.getElementById("timeslotoverview").textContent= "Requested pickup time: " + displaytimeslot + " between " + timeslot.substring(timeslot.length-12, timeslot.length); 
					completeorderbtn.innerHTML = '<input type="hidden" name="timeslot" value="' + timeslot + '"><input id="completeorderbtn" type="submit" value="COMPLETE ORDER">'					
					document.getElementsByClassName("overview")[0].appendChild(completeorderbtn);			
				}
				this.parentElement.id = "slotselected";
				this.removeAttribute("class");
				this.id = "timeslotselected";
				this.value = "Selected";
			  });
			}
	</script>
</html>
