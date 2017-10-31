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
		$sql_priceinclose = "SELECT s_close FROM stock_pricecsv WHERE s_id='".$_POST["stock_name"]."'";
		mysqli_query($link, 'SET CHARACTER SET utf8');
		mysqli_query($link, "SET collection_connection = 'utf8_general_ci'");

		date_default_timezone_set('Asia/Taipei');
		$datetime = date ("Y-m-d H:i:s");

		if(isset($_POST["insert"])){
			if($_POST["stock_transaction"]=="buy"){
				if($result_price=mysqli_query($link, $sql_priceinclose)){
					$row_price=mysqli_fetch_assoc($result_price);
					$moneycheck=$_SESSION["umoney"]-$row_price["s_close"]*$_POST["stock_unit"]*1000;
				}
				if($moneycheck<0){
					echo "Your money is not enough! Please select again! ";
					echo '<meta http-equiv=REFRESH CONTENT=1;url=stock_BuySell.php>';
				}else{
					$sql_plus="UPDATE User SET U_money='".$moneycheck."' WHERE U_account='".$_SESSION["uid"]."'";
					mysqli_query($link, $sql_plus);
					$_SESSION["umoney"]=$moneycheck;
					
					//$sql_processcheck="SELECT * FROM stock_process WHERE SP_Sid='".$_POST["stock_name"]."' AND SP_Uid='".$_SESSION["uid"]."'";
					//if($result_processcheck=mysqli_query($link, $sql_processcheck)){
						//$row_processcheck=mysqli_fetch_assoc($result_processcheck);
						//$pchecknum=mysqli_num_rows($result_processcheck);
						//if($pchecknum>0){
							//$pnewunit=$_POST["stock_unit"]+$row_processcheck["SP_unit"];
							//$pnewprice=($_POST["stock_price"]*$_POST["stock_unit"]+$row_processcheck["SP_buyprice"]*$row_processcheck["SP_unit"])/($_POST["stock_unit"]+$row_processcheck["SP_unit"]);
							//$pnewpricef=round($pnewprice,2);
							//$sql_changep="UPDATE stock_process SET SP_unit='".$pnewunit."' WHERE SP_Sid='".$_POST["stock_name"]."' AND SP_Uid='".$_SESSION["uid"]."'";
							//mysqli_query($link, $sql_changep);
							//$sql_changep2="UPDATE stock_process SET SP_buyprice='".$pnewpricef."' WHERE SP_Sid='".$_POST["stock_name"]."' AND SP_Uid='".$_SESSION["uid"]."'";
							//mysqli_query($link, $sql_changep2);
							//$sql_changep3="UPDATE stock_process SET SP_time='".$datetime."' WHERE SP_Sid='".$_POST["stock_name"]."' AND SP_Uid='".$_SESSION["uid"]."'";
							//mysqli_query($link, $sql_changep3);
						//}else{
							$spstatus=0;
							$sql_insert="INSERT INTO stock_process(SP_unit, SP_status, SP_buyprice, SP_Uid, SP_Sid, SP_time, SP_reason)VALUES('".$_POST["stock_unit"]."', '".$spstatus."', '".$_POST["stock_price"]."', '".$_SESSION["uid"]."', '".$_POST["stock_name"]."', '".$datetime."', '".$_POST["stock_reason"]."')";
							if($result_insert=mysqli_query($link, $sql_insert)){
								if($result_insert>0){
									echo '<meta http-equiv=REFRESH CONTENT=1;url=stock_OrderTicket.php>';
								}
							}else{
								die("fail");
							}
						//}						
					//}
				}
			}elseif($_POST["stock_transaction"]=="sell"){
				$sql_checkstock="SELECT * FROM Stock_self WHERE Ss_Uid='".$_SESSION["uid"]."' AND Ss_Sid='".$_POST["stock_name"]."'";
				if($result_checkstock=mysqli_query($link, $sql_checkstock)){
					$row_checkstock=mysqli_fetch_assoc($result_checkstock);
					
					$stocknum=mysqli_num_rows($result_checkstock);
					if($stocknum>0){
						if($row_checkstock["Ss_unit"]>$_POST["stock_unit"] || $row_checkstock["Ss_unit"]==$_POST["stock_unit"]){
							$spstatus=1;
							$sql_insert2="INSERT INTO Stock_process(SP_unit, SP_status, SP_buyprice, SP_Uid, SP_Sid, SP_time, SP_reason)VALUES('".$_POST["stock_unit"]."', '".$spstatus."', '".$_POST["stock_price"]."', '".$_SESSION["uid"]."', '".$_POST["stock_name"]."', '".$datetime."', '".$_POST["stock_reason"]."')";
							if($result_insert2=mysqli_query($link, $sql_insert2)){
								if($result_insert2>0){
									if($row_checkstock["Ss_unit"]==$_POST["stock_unit"]){
										$sql_deletesell="DELETE FROM Stock_self WHERE Ss_Sid='".$_POST["stock_name"]."' AND Ss_Uid='".$_SESSION["uid"]."'";
										$result_deletesell=mysqli_query($link, $sql_deletesell)or die(mysqli_error());
									}else{
										$unitafter=$row_checkstock["Ss_unit"]-$_POST["stock_unit"];
										$sql_updatesell="UPDATE stock_self SET Ss_unit='".$unitafter."' WHERE Ss_Sid='".$_POST["stock_name"]."' AND Ss_Uid='".$_SESSION["uid"]."'";
										mysqli_query($link, $sql_updatesell);
									}
									echo '<meta http-equiv=REFRESH CONTENT=1;url=stock_OrderTicket.php>';
								}
							}else{
								die("fail");
							}
						}else{
							echo "You only have".$row_checkstock["Ss_unit"]." unit, can not sell ".$_POST["stock_unit"]."units,Please select again.";
							echo '<meta http-equiv=REFRESH CONTENT=1;url=stock_BuySell.php>';
						}
						
					}else{
						echo "You don't have this one, can not sell it, please select again!";
						echo '<meta http-equiv=REFRESH CONTENT=1;url=stock_BuySell.php>';
					}
				}
			}			
		}
	?>
				<!--
				<script type="text/javascript">
				alert("您未持有該股票，無法賣出，請重新選擇。");
				history.back();
				</script>
			-->

</body>
</html>