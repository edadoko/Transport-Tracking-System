<?php
	
	include("connect.php");
	
	session_start();
	$logged = false;	
	
	if(isset($_POST['logout'])){
		 	session_destroy();
			header("Location : index.php"); 	
	}
	if(isset($_SESSION['admin'])){
		$logged = true;	
		$user = $_SESSION['admin'];	
	}
	
	if(isset($_POST['latitude']) && isset($_POST['longitude'])  && isset($_POST['station']) ){
		
		$lat = $_POST['latitude'];
		$long = $_POST['longitude'];
		$name = $_POST['station'];

		$query = mysql_query("insert into locations(latitude, longitude, type, locationName) values ($lat, $long, 1, '$name')");
		
	}
	
	if(isset($_POST['drivers'])){
		$drivers = $_POST['drivers'];
		
		for($i = 0; $i < count($drivers); $i++){
			$temp = $drivers[$i];
			$query = mysql_query("select id,busid from drivers where username='$temp'");
			
			$result = mysql_fetch_array($query);
			
			$dId = $result['id'];	
			$bId = $result['busid'];
			
			unlink("trackers/bus".$bId.".php");
			unlink("trackers/bus".$bId."R.php");	
			
			mysql_query("delete from drivers where id = $dId");
			mysql_query("delete from locations where id = $bId");

			 
		}
	}
	if(isset($_POST['locations'])){
		$locations = $_POST['locations'];
		
		for($i = 0; $i < count($locations); $i++){
			$temp = $locations[$i];
			
			$query = mysql_query("select id from locations where locationName='$temp'");
			
			$result = mysql_fetch_array($query);
			
			$lId = $result['id'];	
			
			mysql_query("delete from locations where id = $lId");

			 
		}
	}
	
	if(isset($_POST['busdriver']) && isset($_POST['busdriverpass']) && isset($_POST['busName'])){
	
		$drivername = $_POST["busdriver"];
		$driverpass = $_POST["busdriverpass"];
		$nameOfBus = $_POST["busName"];
		
		$query = mysql_query("insert into locations(latitude, longitude, type) values (0 ,0 ,2)");
		
		$query = mysql_query("select id from locations");
		
		while($result = mysql_fetch_array($query))
			$busid = $result["id"];
		
		$statement = "update locations set locationName='$nameOfBus' where id='$busid'";
		$query = mysql_query($statement);
		
		$statement = "insert into drivers(username , password, busid) values ('$drivername','$driverpass', '$busid')";
		$query = mysql_query($statement);
		
		$f = fopen("trackers/bus". $busid .".php" , "w");
		
		fwrite($f, "<?php");
		fclose($f);
		$f = fopen("trackers/bus". $busid .".php", "a");
		
		$t = <<<EOT
		
		session_start();

		if(isset(\$_SESSION['username'])){

		if(\$_SESSION['username'] != "{$drivername}") 
			die("No Access!");
		}else die("No Access!");
	
		if(isset(\$_GET['latitude']) && isset(\$_GET['longitude'])){
		\$lat = \$_GET['latitude'];
		\$long = \$_GET['longitude'];
		}else
		die("Insufficient variables!");

		\$con = mysql_connect("localhost" , "root" , "") or die("Error connecting to server!");

		\$db = mysql_select_db("epokabus") or die("Error connecting to database!");

		\$query = "update locations set latitude=\$lat, longitude=\$long where id={$busid}";

		\$exec = mysql_query("\$query") or die("Error executing query!");

		header('Location: bus{$busid}R.php');
		
EOT;
		
		fwrite($f , $t);
		fwrite($f , "?>");
		fclose($f);
		
		$file = fopen("trackers/bus". $busid ."R.php" , "w");
		
		fwrite($file, "<?php");
		fclose($file);
		$file = fopen("trackers/bus". $busid ."R.php", "a");
		
		$text = "";
		
		$text = <<<EOT
		
		session_start();
		if(isset(\$_SESSION['username'])){
			if(\$_SESSION['username'] != "{$drivername}") 	
				die("No Access!");
		}else die("No Access!");		
EOT;
		$text2 = <<<EOT
		<html>
		<body onload="getLocation()">
	
		<p id="location"></p>
	
	
		<script>
		var x = document.getElementById("location");
	
		function getLocation() {
 		   if (navigator.geolocation) {
 		       navigator.geolocation.getCurrentPosition(showPosition);
 		       setTimeout(navigator.geolocation.getCurrentPosition(updateLoc) , 3000);
 		   } else { 
 	    	   x.innerHTML = "Geolocation is not supported by this browser.";
 	   		}
		}

		function showPosition(position) {
	    	x.innerHTML=position.coords.latitude + 
	 		   " " + position.coords.longitude;	
		}
	
		function updateLoc(position){
			window.location="http://localhost/BusT/trackers/bus{$busid}.php?latitude="+position.coords.latitude+"&longitude="+position.coords.longitude;
		}
		</script>

		</body>
		</html>	
EOT;
		
		

		
		fwrite($file , $text);
		fwrite($file , "?>");
		fwrite($file , $text2);
		fclose($file);
	}	
	if(isset($_POST['admin'])){
		if(isset($_POST['password'])){
			$username = $_POST['admin'];	
			$password = $_POST['password'];
			$query = mysql_query("Select * from admins where username = '$username' and password = '$password'");
			$count = mysql_num_rows($query);
			if($count >= 1){
				$_SESSION['admin'] = $username;
				$user = $username;				
				$logged = true;
			}else $showerror = 1;	
						
		}else $showerror = 1;
				
	}	
		
?>
<html>
	<head> <title> ADMIN Login </title>
	<meta name="viewport" content="width=device-width,initial-scale=1, user-scalable=no">
	<link href="style/adminstyle.css" rel="stylesheet" type="text/css"/>

	</head>

	<body>
		
		
		<?php if(!$logged){ ?>
		
		<span id="banner"> ADMIN LOGIN </span>		
		<div id="logForm">
			<form id="log" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
				<input type="text" placeholder="username" id="username" name ="admin" /> <br/><br/>
				<input type="password" placeholder="password" id="password" name ="password"/> <br/>
				<input type="submit" value="LOGIN" id="sub" name="subbed" />
			</form>
			<?php if(isset($showerror)){ ?>
				<p id="redT"> There was an error trying to log in! </p>
			<?php } ?>	
		</div>
		
		<?php }else { ?>
			<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="cdriver-form">
				<span id="title"> Create Driver </span> <br/>
				<input type="text" name="busdriver" id="drivername" placeholder="driver username" /><br/><br/>
				<input type="password" name="busdriverpass" id="driverpassword" placeholder="driver password" /><br/> 
				<input type="text" name="busName" id="busName" placeholder="bus name" /><br/> 
				<input type="submit" name="sub" id="subC" value="CREATE" />
			</form>	
			
			<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" id="ddriver-form">
				<span id="title"> Delete Driver </span> <br/> <br/>
				<?php 
				$statement = "select * from drivers";
				$query = mysql_query($statement);
				
				while($row = mysql_fetch_array($query)){
					$dname = $row['username'];	
					echo "<input type='checkbox' name='drivers[]' id = 'drivers' value='$dname' />".$dname."<br/>";											
				}

				?>
				
				<br/>
				<input type="submit" name="sub" id="subC" value="DELETE" />
			</form>
			
			
			<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class='margin-10' id="cstation-form">
				<span id = "title"> Create Station </span><br/> 
				<input type="text" name="station" id="stationName" placeholder="station name" /><br/>
				<input type="text" name="latitude" id="lat" placeholder="latitude" /> <br/>
				<input type="text" name="longitude" id="long" placeholder="longitude" /> <br/>
				<input type="submit" name="sub3" id="sub4" value="CREATE" /> 
			</form>
		
			<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" class='margin-10' id="dstation-form">
				<span id = "title"> Delete Station </span><br/> <br/>
				<?php 
				$statement = "select * from locations";
				$query = mysql_query($statement);
				
				while($row = mysql_fetch_array($query)){
					$locName = $row['locationName'];	
					if($row['type'] == 1) 
						echo "<input type='checkbox' name='locations[]' id = 'locations' value='$locName' />$locName<br/>";											
				}

				?>
				<input type="submit" name="sub3" id="sub4" value="DELETE" /> 
			</form>
		
		
		<?php }?>
		<?php if($logged) { ?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
			<input type="submit" name="logout" id="logout" value="LOGOUT" />
		</form>	
		<?php } ?>
		<?php if(isset($msg)){ ?>
				<?php if(!$msg) {?><p id="redT"> There was an error creating the driver! </p> <?php } ?>
				<?php if($msg) {?><p id="greenT"> driver created successfully! </p> <?php } ?>
		<?php } ?>	
		<div id="footer">
			<p id = "home"> &copy; 2014 - <a href = "wwww.epoka.edu.al" name = "home" id = "home" > Epoka University </a> - 
			<a href = "index.php" name = "home" id = "home" > Home </a> </p>
		</div>
	</body>
</html>
