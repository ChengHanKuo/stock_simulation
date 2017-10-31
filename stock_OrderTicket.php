<?php
	ob_start();
	session_start();
	if(!isset($_SESSION["login_session"]))
	{
		echo "Forbidden!";
		header ("Location: stock_login.php");
	}
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
		$sql_have = "SELECT * FROM stock_process WHERE SP_Uid='".$_SESSION["uid"]."'";
		if($result_have=mysqli_query($link, $sql_have)){
			$row_num=mysqli_num_rows($result_have);
			if($row_num>0){
				echo "<div class='row' style='margin:0px 0px 0px 100px ; overflow-y:scroll; height:30%;width:80%; word-wrap: break-word; word-break: normal; '>
						<fieldset><legend>Order Ticket</legend><table>
						<tr>
							<th width=80 align=center>code</th>
							<th width=80 align=center>name</th>
							<th width=80 align=center>buy/sell</th>
							<th width=80 align=center>order price</th>
							<th width=80 align=center>unit</th>
							<th width=80 align=center>time</th>
						</tr>";
				while($row_have=mysqli_fetch_assoc($result_have)){
					if($row_have["SP_status"]==0){
						$spstatusci="buy";
					}elseif($row_have["SP_status"]==1){
						$spstatusci="sell";
					}
					$sql_name = "SELECT * FROM stock_pricecsv WHERE s_id='".$row_have["SP_Sid"]."'";
					if($result_name=mysqli_query($link, $sql_name)){
						while($row_name=mysqli_fetch_assoc($result_name)){
							$sname=$row_name["s_name"];
						}
					}
					echo "<tr>
							<td align=center>".$row_have["SP_Sid"]."</td>
							<td align=center>".$sname."</td>
							<td align=center>".$spstatusci."</td>
							<td align=center>".$row_have["SP_buyprice"]."</td>
							<td align=center>".$row_have["SP_unit"]."</td>
							<td align=center>".$row_have["SP_time"]."</td>
						  </tr>";
				}echo "</table></fieldset></div>";
			}else{
				
				$sql_deal = "SELECT * FROM stock_today WHERE ST_Uid='".$_SESSION["uid"]."'";
				if($result_deal=mysqli_query($link, $sql_deal)){
					echo "<div class='row' style='margin:0px 0px 0px 100px ; overflow-y:scroll; height:30%;width:80%; word-wrap: break-word; word-break: normal; '>
					<fieldset><legend>Deal</legend><table>
					<tr>
						<th width=80 align=center>code</th>
						<th width=80 align=center>name</th>
						<th width=80 align=center>buy/sell</th>
						<th width=80 align=center>deal price</th>
						<th width=80 align=center>unit</th>
						<th width=80 align=center>time</th>
					</tr>";
					while($row_deal=mysqli_fetch_assoc($result_deal)){
						if($row_deal["ST_status"]==0){
							$spstatuscp="buy";
						}elseif($row_deal["ST_status"]==1){
							$spstatuscp="sell";
						}
						
						$sql_deal2 = "SELECT * FROM stock_pricecsv WHERE s_id='".$row_deal["ST_Sid"]."'";
						if($result_deal2=mysqli_query($link, $sql_deal2)){
							while($row_deal2=mysqli_fetch_assoc($result_deal2)){
								echo "<tr>
										<td align=center>".$row_deal2["s_id"]."</td>
										<td align=center>".$row_deal2["s_name"]."</td>
										<td align=center>".$spstatuscp."</td>
										<td align=center>".$row_deal["ST_price"]."</td>
										<td align=center>".$row_deal["ST_unit"]."</td>
										<td align=center>".$row_deal["ST_time"]."</td>
									</tr>";								
							}
						}
					}echo "</table></fieldset></div><br>";
				}
						
				$sql_undeal = "SELECT * FROM stock_fail WHERE Sf_Uid='".$_SESSION["uid"]."'";
				if($result_undeal=mysqli_query($link, $sql_undeal)){
					echo "<div class='row' style='margin:0px 0px 0px 100px ; overflow-y:scroll; height:30%;width:80%; word-wrap: break-word; word-break: normal; '>
						<fieldset><legend>Not Deal</legend><table>
						<tr>
							<th width=80 align=center>code</th>
							<th width=80 align=center>name</th>
							<th width=80 align=center>buy/sell</th>
							<th width=80 align=center>order price</th>
							<th width=80 align=center>unit</th>
							<th width=80 align=center>time</th>
						</tr>";
					while($row_undeal=mysqli_fetch_assoc($result_undeal)){
						if($row_undeal["Sf_status"]==0){
							$spstatuscpno="buy";
						}elseif($row_undeal["Sf_status"]==1){
							$spstatuscpno="sell";
						}
						
						$sql_undeal2 = "SELECT * FROM stock_pricecsv WHERE s_id='".$row_undeal["Sf_Sid"]."'";
						if($result_undeal2=mysqli_query($link, $sql_undeal2)){
							while($row_undeal2=mysqli_fetch_assoc($result_undeal2)){
								echo "<tr>
										<td align=center>".$row_undeal2["s_id"]."</td>
										<td align=center>".$row_undeal2["s_name"]."</td>
										<td align=center>".$spstatuscpno."</td>
										<td align=center>".$row_undeal["Sf_price"]."</td>
										<td align=center>".$row_undeal["Sf_unit"]."</td>
										<td align=center>".$row_undeal["Sf_time"]."</td>
									</tr>";
							}
						}
					}echo "</table></fieldset></div>";
				}			
			}
		}
	?>
</body>
</html>