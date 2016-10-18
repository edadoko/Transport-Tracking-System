<?php
		session_start();
		
		$f = fopen("trackers/bus". 5 .".php" , "w");
		
		fwrite($file, "<?php");
		fclose($file);
		$f = fopen("trackers/bus". 5 .".php", "a");
		
		$t = <<<EOT
		
		session_start();

		if(isset($_SESSION['username'])){
		if($_SESSION['username'] != "bus2driver") 
			die("No Access!");
		}else die("No Access!");
	
		if(isset($_GET['latitude']) && isset($_GET['longitude'])){
		$lat = $_GET['latitude'];
		$long = $_GET['longitude'];
		}else
		die("Insufficient variables!");

		$con = mysql_connect("localhost" , "root" , "") or die("Error connecting to server!");

		$db = mysql_select_db("epokabus") or die("Error connecting to database!");

		$query = "update locations set latitude=$lat, longitude=$long where id=3";

		$exec = mysql_query($query) or die("Error executing query!");

		header('Location: bus2R.php');
		
EOT;
		
		fwrite($file , $t);
		fwrite($file , "?>");
		fclose($file);
		
		$file = fopen("trackers/bus". 5 ."R.php" , "w");
		
		fwrite($file, "<?php");
		fclose($file);
		$file = fopen("trackers/bus". 5 ."R.php", "a");
		
		$text = <<<EOT
		
		session_start();
		if(isset({$_SESSION['username']})){
			if({$_SESSION['username']} != "bus1driver") 	
				die("No Access!");
		}else die("No Access!");		

		<html>
		<body onload="getLocation()">
	
		<p id="demo"></p>
	
	
		<script>
		var x = document.getElementById("demo");
	
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
			window.location="http://bus.ilir.us/ushahidi/bus1.php?latitude="+position.coords.latitude+"&longitude="+position.coords.longitude;
		}
		</script>

		</body>
		</html>	

		
		
EOT;
		
		fwrite($file , $text);
		fwrite($file , "?>");
		fclose($file);
?>