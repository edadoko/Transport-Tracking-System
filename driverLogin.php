<?php
	
	include("connect.php");
	
	session_start();
	
	$location = "";
	
	if(isset($_POST['logout'])){
		 	session_destroy();
			header("Location : index.php"); 	
	}
		
	$logged = false;
	if(isset($_SESSION['username'])){
		$logged = true;	
		$user = $_SESSION['username'];
		
		$row = mysql_fetch_array(mysql_query("select busid from drivers where username='$user'"));
		$busid =  $row['busid'];
		$location = "trackers/bus".$busid."R.php";			
	}

		if(isset($_POST['username'])){
			if(isset($_POST['password'])){
				$username = $_POST['username'];	
				$password = $_POST['password'];


				$query = mysql_query("Select * from drivers where username = '$username' and password = '$password'");
				$count = mysql_num_rows($query);
	
				if($count >= 1){
					$_SESSION['username'] = $username;
					$user = $username;				
					$logged = true;
				}else $showerror = 1;	
						
			}else $showerror = 1;
				
		}	
		
?>
<html>
	<head> <title> Driver Login </title>
	<meta name="viewport" content="width=device-width,initial-scale=1, user-scalable=no">
	<style type="text/css">
		body {
			background: url("images/bodytile.jpg");
			background-repeat: repeat;
		}
		#banner{
			position:absolute;
			left:46%;
			top:50px;
			font-size:24px;
			font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
			color: lightblue;
		}
		#welcome{
			margin-top: 5%;
			margin-left: 5%;
			font-size:16px;
			font-family:"Lucida Sans Unicode", "Lucida Grande", sans-serif;
		}
		#logForm{
			position:absolute;
			width:240px;
			height:180px;
			left:41%;
			top:100px;
			background: lightblue;
			border:1px solid slategray;
		}
		#log{
			position: absolute;
			top: 40px;
			width:100%;
		}
		#username , #password{
			border:0;
			width:90%;
			font-size:12px;
			margin-left: 5%;
			height:30px;
			text-align:center;
		}	
		#sub , #logout{
			height:30px;
			border:0;
			background: pink;
			color: white;
			width:90%;
			font-size:12px;
			margin-left: 5%;
		}
		#logout{
			background: transparent;
			color: blue;
			width: 100px;
		}
		#sub:hover , #sub2:hover{
			background: lightpink;
		}
		#link{
			text-decoration:none;
			color: darkslateblue;
			font-weight: bold;
		}
		#link:hover{
			color: slateblue;
		}
		#logout{
			position: fixed;
			right: 10px;
			bottom: 10px;
			margin: 0;
		}
		#logout:hover{
			color: pink;
		}
		#home{
			padding: 0px;
			text-decoration: none;
			color: gray;
			//background-color: transparent;
			//width: 30px;
			text-align: center; 
			font-weight: 400;
			font-size: 12px;
		}
		#footer{
			position: absolute;
			bottom:10px;
			left: 33%;
			width: 400px;
		}
		@media screen and (max-device-width: 768px){
			#banner{
				position:absolute;
				left:30%;
			}
			#logform{
				left: 20%;
			}
			#footer{
			position: absolute;
			bottom:10px;
			left: 5%;
			width: 320px;
		}
		}
	</style>

	</head>

	<body>
	
		<?php if(!$logged){ ?>
		<span id="banner"> BUS LOGIN </span>	
		<div id="logForm">
			<form id="log" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
				<input type="text" placeholder="username" id="username" name ="username" /> <br/><br/>
				<input type="password" placeholder="password" id="password" name ="password"/> <br/><br/>
				<input type="submit" value="LOGIN" id="sub" name="subbed" />
			</form>
			<?php if(isset($showerror)){ ?>
				<p id="redT"> There was an error trying to log in! </p>
			<?php } ?>	
		</div>
		<?php }else echo "<p id='welcome'> Welcome $user </p>
				<p id='welcome'> <a href='$location' id='link'> Click here to start your bus </a> <br/> </p>"; ?>
		<?php if($logged) { ?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" >
			<input type="submit" name="logout" id="logout" value="LOGOUT" />
		</form>	
		<?php } ?>
		<div id="footer">
			<p id = "home"> &copy; 2014 - <a href = "wwww.epoka.edu.al" name = "home" id = "home" > Epoka University </a> - 
			<a href = "index.php" name = "home" id = "home" > Home </a> </p>
		</div>
	</body>
</html>
