<!DOCTYPE html>

<?php
	include("connect.php");
	
	$statement = "select * from locations";
	$query = mysql_query($statement);
	
	$centerLat = 0.0;
	$centerLong = 0.0;
	
	$stationsLats = array();
	$stationsLongs = array();
	
	$busLats = array();
	$busLongs = array();
	
	while( $row = mysql_fetch_array($query)){
		
		if( $row['type'] == 0){ // MAP CENTER
			$centerLat = $row['latitude'];
			$centerLong = $row['longitude'];
		}else if( $row['type'] == 1){ // STATION
			$stationLats[] = $row['latitude'];
			$stationLongs[] = $row['longitude'];
		}else if( $row['type'] == 2){ // BUSSES
			$busLats[] = $row['latitude'];
			$busLongs[] = $row['longitude'];
		}else{ /* Invalid type */ }
	}
	
	
?>



<html>
	<head>	<title> Epoka Bus </title> 
	<meta name="viewport" content="device-width=width, user-scalable=no, initial-scale=1">
	<link rel="stylesheet" href="style/style.css" type="text/css" />
	
	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDjx2OwfMolEmO1yaTeO1Ex7gIjlFIrxsM"></script>
	<script type="text/javascript" src="style/jquery.js"> </script>
	<script src="style/main.js" type="text/javascript"></script>
	<script type="text/javascript">
	
		var center_lat = '<?php echo $centerLat ;?>';
		var center_lng = '<?php echo $centerLong ;?>';
		
		var bus_locs = [];
		var station_locs = [];
		
		var bus_lats = [];
		var bus_longs = [];
		
		var station_lats = [];
		var station_longs = [];
		
		var bus_markers = [];
		var station_markers = [];
		
		
		<?php foreach( $stationLats as $lat){ ?>
			station_lats.push('<?php echo $lat; ?>');
		<?php }?>
		<?php foreach( $stationLongs as $long){ ?>
			station_longs.push('<?php echo $long; ?>');
		<?php }?>
		
		<?php foreach( $busLats as $lat){ ?>
			bus_lats.push('<?php echo $lat; ?>');
		<?php }?>
		<?php foreach( $busLongs as $long){ ?>
			bus_longs.push('<?php echo $long; ?>');
		<?php }?>
		
		
		for( var i=0; i < station_lats.length; i++){
			station_locs.push(new google.maps.LatLng(station_lats[i] , station_longs[i]));
		}
		for( var i=0; i < bus_lats.length; i++){
			bus_locs.push(new google.maps.LatLng(bus_lats[i] , bus_longs[i]));
		}
		
		var busIcon = 'images/bus.png';
		var stationIcon = 'images/station.png';
		
		function initialize() {
		
			PathDirection = new google.maps.DirectionsRenderer();
			
			var ds = new google.maps.DirectionsService();

			var LocationCenter = new google.maps.LatLng( 41.330233, 19.817282);
			var EpokaUn = "Epoka University";//new google.maps.LatLng( 41.403242, 19.704280);
			
			/*var Path = [
				new google.maps.LatLng( 41.333433, 19.803693), 
				new google.maps.LatLng( 41.335254, 19.798286), 
				new google.maps.LatLng( 41.337203, 19.792793),
				new google.maps.LatLng( 41.339265, 19.786806), 
				new google.maps.LatLng( 41.339410, 19.786742), 
				new google.maps.LatLng( 41.339507, 19.786527), 
				new google.maps.LatLng( 41.339555, 19.786227), 
				new google.maps.LatLng( 41.339652, 19.785712), 
				new google.maps.LatLng( 41.342375, 19.778180), 
				new google.maps.LatLng( 41.348432, 19.760628), 
				new google.maps.LatLng( 41.359868, 19.727454), 
				new google.maps.LatLng( 41.362348, 19.718742), 
				new google.maps.LatLng( 41.365086, 19.709001), 
				new google.maps.LatLng( 41.366374, 19.705567), 
				new google.maps.LatLng( 41.367598, 19.704022), 
				new google.maps.LatLng( 41.370046, 19.703507), 
				new google.maps.LatLng( 41.378032, 19.703636), 
				new google.maps.LatLng( 41.389431, 19.703979), 
				new google.maps.LatLng( 41.400924, 19.704323), 
				new google.maps.LatLng( 41.403242, 19.704280)
			]; */
			
			var mapOptions = {
			  center: new google.maps.LatLng(center_lat, center_lng),
			  zoom: 12
			};
			
			var map = new google.maps.Map(document.getElementById("map"), mapOptions);
			
			PathDirection.setMap(map);
			
			/*var flightPath = new google.maps.Polyline({
				path: Path,
				geodesic: true,
				strokeColor: '#FF0000',
				strokeOpacity: 1.0,
				strokeWeight: 2,
				map: map
			}); */

			
			
			for( var i=0; i < station_locs.length; i++){
				station_markers[i] = new google.maps.Marker({
					position: station_locs[i],
					map: map,
					title: "Station "+i,
					icon: stationIcon
				});
			}
			for( var i=0; i < bus_locs.length; i++){
				bus_markers[i] = new google.maps.Marker({
					position: bus_locs[i],
					map: map,
					title: "Bus "+i,
					icon: busIcon
				});
			}
			
			
			
			var path = {
				origin: LocationCenter,
				destination: EpokaUn,
				travelMode: google.maps.TravelMode.DRIVING
			};
			  
			ds.route(path, function(result, status) {
				if (status == google.maps.DirectionsStatus.OK) {
					PathDirection.setDirections(result);
				}else alert("invlid");
			});
			
			
			
		}
			
		google.maps.event.addDomListener(window, 'load', initialize);
		
		setInterval(function(){
		
		
		
		//function updateMarkers(){
			var xmlhttp;
			var lats,longs,i;
			if (window.XMLHttpRequest)
  			{
  				xmlhttp=new XMLHttpRequest();
  			}else{
  				xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  			}
			//alert("lol");

			xmlhttp.onreadystatechange=function(){
  				if (xmlhttp.readyState==4 && xmlhttp.status==200){
    				xmlDoc=xmlhttp.responseXML;
   					
   					lats = xmlDoc.getElementsByTagName("latitude");
   					longs = xmlDoc.getElementsByTagName("longitude");
   					
   					for(i=0;i < bus_markers.length;i++){
   						var position = new google.maps.LatLng(lats[i].childNodes[0].nodeValue , longs[i].childNodes[0].nodeValue);
   						//alert(position);
   						bus_markers[i].setPosition(position);
   					}
    			}
  			}
			xmlhttp.open("GET","fetchbusses.php",true);
			xmlhttp.send();
		
		}, 5000);
	</script>
	
	
	<head>
	<body>
	
	<div id="ismobile"></div>
	
	<div id="top">
		<span id="title">
			Epoka Bus
		</span>
		
		<a href="driverLogin.php" id="driverButton">Driver Login</a>
		<a href="adminLogin.php" id="adminButton">Admin Login</a>
	</div>
	
	<div id="footer2">
	</div>
	<div id="map" />
	
	
	</body>

</html>