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
			<li class='has-sub'><a href="stock_userEdit.php">edit</a>
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
	<div class="content">
		<div id="myTabContent" class="tab-content">
		<div class="row" style="margin:0px 0px 0px 30px ; overflow-y:scroll; height:35%;width:95%; word-wrap: break-word; word-break: normal; ">
			<fieldset>
				<legend>Member's information</legend>
				<br>
				<table>
				<?php
					$sql_member = "SELECT * FROM user WHERE U_account='".$_SESSION["uid"]."'";
					if($result_member=mysqli_query($link, $sql_member)){
						while($row_member=mysqli_fetch_assoc($result_member)){
							echo "
							      <tr><th>&nbsp;&nbsp;&nbsp;account：</th><td>&nbsp;&nbsp;".$row_member["U_account"]."</td><tr>
								  <tr><th>&nbsp;&nbsp;&nbsp;gender：</th><td>&nbsp;&nbsp;".$row_member["U_gender"]."</td><tr>
								  <tr><th>&nbsp;&nbsp;&nbsp;birthday：</th><td>&nbsp;&nbsp;".$row_member["U_bir"]."</td><tr>
							      <tr><th>&nbsp;&nbsp;&nbsp;e-mail：</th><td>&nbsp;&nbsp;".$row_member["U_email"]."</td><tr>
								  <tr><th>&nbsp;&nbsp;&nbsp;money in account：</th><td>&nbsp;&nbsp;".$row_member["U_money"]."</td><tr>
								  <tr><th>&nbsp;&nbsp;&nbsp;stocks' value：</th><td>&nbsp;&nbsp;".$row_member["U_value"]."</td><tr>";
						}
					}
				?>
				</table>				
			</fieldset>
		</div>
		</div>
	<div>
	<div class="content2">
		<div id="myTabContent2" class="tab-content2">
		<div class="row" style="margin:0px 0px 0px 30px ; overflow-y:scroll; height:80%;width:95%; word-wrap: break-word; word-break: normal; ">
			<fieldset>
				<legend>Stocks you have</legend>
			<br>
			<table>
			<tr>
				<th width=80 align=center>code</th>
				<th width=80 align=center>name</th>
				<th width=80 align=center>buying price</th>
				<th width=80 align=center>now price</th>
				<th width=80 align=center>unit</th>
			</tr>
			<?php
			
			$sql_own = "SELECT * FROM stock_self WHERE Ss_Uid='".$_SESSION["uid"]."'";
			if($result_own=mysqli_query($link, $sql_own)){
				while($row_own=mysqli_fetch_assoc($result_own)){
					if($row_own["Ss_status"]==0){
						
						$sql_own2 = "SELECT * FROM stock_pricecsv WHERE s_id='".$row_own["Ss_Sid"]."'";
						if($result_own2=mysqli_query($link, $sql_own2)){
							while($row_own2=mysqli_fetch_assoc($result_own2)){
								echo "<tr>
										<td align=center>".$row_own2["s_id"]."</td>
										<td align=center>".$row_own2["s_name"]."</td>
										<td align=center>".$row_own["Ss_price"]."</td>
										<td align=center>".$row_own2["s_close"]."</td>
										<td align=center>".$row_own["Ss_unit"]."</td>
									</tr>";								
							}
						}
					}
				}
			}echo "</table>";
			?>
			<br>
			</fieldset>
		</div>
		</div>
	<div>
	
</body>
</html>