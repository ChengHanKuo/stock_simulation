<?php
/*
	ob_start();
	session_start();
	if(!isset($_SESSION["login_session"]))
	{
		echo "Forbidden!";
		header ("Location: stock_login.php");
	}
*/
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<title>Online Investing</title>
	<style type="text/css">

	p {font-weight: bold}
	img {float: left;margin: 10px}

	</style>
	<meta charset="UTF-8" />
		
	<!--<link rel="shortcut icon" href="../favicon.ico">
    <link rel="stylesheet" type="text/css" href="css/CreativeCSS3AnimationMenus/demo.css" />
    <link rel="stylesheet" type="text/css" href="css/CreativeCSS3AnimationMenus/style10.css" />
    <link href='http://fonts.googleapis.com/css?family=Terminal+Dosis' rel='stylesheet' type='text/css' />-->
	<link rel="stylesheet" type="text/css" href="css/menu.css" media="screen">
	<link rel="stylesheet" type="text/css" href="css/style_notice.css" />
</head>
<body>
	<div id='cssmenu'>
		<ul>
	    	<li class='has-sub'><a href="stock_function.php">function</a>
			<li class='has-sub'><a href="stock_Logout.php">log out</a>
	   		</li>
		</ul>
	</div>
	<?php
		$link = mysqli_connect("localhost", "root", "stock12345", "Stock") 
				or die("無法開啟資料庫！<br/>");
		mysqli_query($link, 'SET CHARACTER SET utf8');
		mysqli_query($link, "SET collection_connection = 'utf8_general_ci'");

	?>
<br><br>
	
	<?php		
		$sql_static = "SELECT * FROM user";
		if($result_static=mysqli_query($link, $sql_static)){
			while($row_static=mysqli_fetch_assoc($result_static)){
				$total=$row_static["U_money"]+$row_static["U_value"];
				$sql_totalupdate="UPDATE User SET U_total='".$total."' WHERE U_account='".$row_static["U_account"]."'";
				mysqli_query($link, $sql_totalupdate);
			}
			echo "<div class='row' style='margin:0px 0px 0px 100px ; overflow-y:scroll; height:30%;width:80%; word-wrap: break-word; word-break: normal; '>
						<fieldset><legend>Top3</legend><table>
						<tr>
							<th width=80 align=center>Money</th>
							<th width=80 align=center>Value</th>
							<th width=80 align=center>Balance</th>
						</tr>";
			
			$sql_rank = "SELECT * FROM user ORDER BY U_total DESC LIMIT 3";
			if($result_rank=mysqli_query($link, $sql_rank)){
				while($row_rank=mysqli_fetch_assoc($result_rank)){
					echo "<tr>
							<td align=center>".$row_rank["U_money"]."</td>
							<td align=center>".$row_rank["U_value"]."</td>
							<td align=center>".$row_rank["U_total"]."</td>
						  </tr>";
				}
			}
			
			echo "</table></fieldset></div>";
			
			echo "<div class='row' style='margin:0px 0px 0px 100px ; overflow-y:scroll; height:30%;width:80%; word-wrap: break-word; word-break: normal; '>
						<fieldset><legend>Last3</legend><table>
						<tr>
							<th width=80 align=center>Money</th>
							<th width=80 align=center>Value</th>
							<th width=80 align=center>Balance</th>
						</tr>";
			
			$sql_rank2 = "SELECT * FROM user ORDER BY U_total LIMIT 3";
			if($result_rank2=mysqli_query($link, $sql_rank2)){
				while($row_rank2=mysqli_fetch_assoc($result_rank2)){
					echo "<tr>
							<td align=center>".$row_rank2["U_money"]."</td>
							<td align=center>".$row_rank2["U_value"]."</td>
							<td align=center>".$row_rank2["U_total"]."</td>
						  </tr>";
				}
			}
			
			echo "</table></fieldset></div>";
		}
	?>
</body>
</html>