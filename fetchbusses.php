<?php
	
	header("content-type: application/xml");
	
	include("connect.php");
	
	$query = mysql_query("select latitude,longitude from locations where type=2");
	echo "<Location>";
	$text = "";
	while($row = mysql_fetch_array($query)){
		$lat = $row['latitude'];
		$long = $row['longitude'];
		
		$text .= "<latitude>$lat</latitude><longitude>$long</longitude>" ;
	}
	
	echo $text;
	echo "</Location>";
	
	
	
?>