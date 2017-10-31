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
	    	<li class='has-sub'><a href="stock_function.php">功能</a>
	    	<li class='has-sub'><a href='#'><span>通知</span></a>
			<li class='has-sub'><a href="stock_Logout.php">登出</a>
	   		</li>
		</ul>
	</div>
	<?php
		$link = mysqli_connect("localhost", "root", "stock12345", "Stock") 
				or die("無法開啟資料庫！<br/>");
		$sql = "SELECT s_id , s_name FROM stock_pricecsv";
		$sql2 = "SELECT * FROM stock_pricecsv";
		mysqli_query($link, 'SET CHARACTER SET utf8');
		mysqli_query($link, "SET collection_connection = 'utf8_general_ci'");

	?>
<br><br>
	
	<?php		
		$sql_have = "SELECT * FROM stock_process WHERE SP_Uid='".$_SESSION["uid"]."'";
		if($result_have=mysqli_query($link, $sql_have)){
			
				echo "<div class='row' style='margin:0px 0px 0px 100px ; overflow-y:scroll; height:30%;width:80%; word-wrap: break-word; word-break: normal; '>
						<fieldset><legend>交易記錄</legend><table>
						<tr>
							<th width=80 align=center>買進/賣出</th>
							<th width=80 align=center>代碼</th>
							<th width=80 align=center>股票名稱</th>
							<th width=80 align=center>買／賣</th>
							<th width=80 align=center>下單價格</th>
							<th width=80 align=center>張數</th>
							<th width=80 align=center>下單時間</th>
						</tr>";
				while($row_have=mysqli_fetch_assoc($result_have)){
					if($row_have["SP_status"]==0){
						$spstatusci="買進";
					}elseif($row_have["SP_status"]==1){
						$spstatusci="賣出";
					}
					$sql_name = "SELECT * FROM stock_pricecsv WHERE s_id='".$row_have["SP_Sid"]."'";
					if($result_name=mysqli_query($link, $sql_name)){
						while($row_name=mysqli_fetch_assoc($result_name)){
							$sname=$row_name["s_name"];
						}
					}
					echo "<tr>
							<td align=center>".$row_have["SP_Sid"]."</td>
							<td align=center>".$row_have["SP_Sid"]."</td>
							<td align=center>".$sname."</td>
							<td align=center>".$spstatusci."</td>
							<td align=center>".$row_have["SP_buyprice"]."</td>
							<td align=center>".$row_have["SP_unit"]."</td>
							<td align=center>".$row_have["SP_time"]."</td>
						  </tr>";
				}echo "</table></fieldset></div>";
			
		}
	?>
</body>
</html>