<?php
	$config = include('config.php');
	session_start();
	$con = mysqli_connect($config['db_ip'],$config['db_username'],$config['db_password']);
	if(!$con)
	{
		echo 'Database connection not succesfull';
	}
	if(!mysqli_select_db($con, $config['db_name']))
	{
		echo 'Database could not be found';
	}
?>

<header>

	<div class="header_upper">
	
		<div class="logo">
			<a href="http://day2daystore.co.uk/">
				<img class="logoimg" src="/images/logo.png">
			</a>
		</div>
		<div class="searchdiv">
			<form id="searchform" action="index.php" method="get">
				<input id="searchbox" type="text" name="search" placeholder="Search Products" <?php if(isset($_GET['search'])) echo 'value= "'.$_GET['search'].'"';  ?> autocomplete="off" onkeyup="searchitem();" />
				<input type="submit" value="SEARCH"/>
			</form>
			<ul id="itemsuggest"></ul>
		</div>
			<?php
			if(isset($_SESSION['firstname'])){
				echo '<div id="headerinfo">';
				echo '<p id="displayname">Welcome back, '.$_SESSION['firstname'].' '.$_SESSION['lastname'].'</p>';
				echo '<a style="text-decoration:none;" id="accountbtn" href="/account.php">View Account</a>';
				echo '<a style="text-decoration:none;" id="logoutbtn" href="/logout.php">Logout</a>';
				echo '</div>';
			}
			else{
				echo '<a style="text-decoration:none" class="loginbtn" href="/login.php">Sign into your account</a>';
			}
			?>
	</div>
	
	<div class="header_lower">
		<nav class="nav_out">
		
			<ul class="nav_menu">
				<?php
				$sqlfilters = mysqli_query($con, "SELECT DISTINCT category FROM stock");
				$filters = mysqli_fetch_all($sqlfilters);
				foreach($filters as $filter){
					$sqlsubfilters = mysqli_query($con, "SELECT DISTINCT subcategory FROM stock WHERE category='".$filter[0]."'");
					$subfilters = mysqli_fetch_all($sqlsubfilters);
					$filtertitle = str_replace("-", " ", $filter[0]);
					echo 
					'<li class="nav_item">
						<a href="../?category='.$filter[0].'" class="nav_title">'.$filtertitle.'</a>
						<ul>';
						foreach($subfilters as $subfilter){
							$subtitle = str_replace("-", " ", $subfilter[0]);
							echo '<li><a href="../?category='.$filter[0].'&subcategory='.$subfilter[0].'">'.$subtitle.'</a></li>';
						}
					echo 
						'</ul>
					</li>';
				}
				echo '<li class="nav_item"><a href="../?show=mostbought" class="nav_title">Most Bought</a></li>';
				?>
			</ul>
			
		</nav>
	</div>
	
</header>