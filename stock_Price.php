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
		$sql2 = "SELECT * FROM stock_pricecsv";
		mysqli_query($link, 'SET CHARACTER SET utf8');
		mysqli_query($link, "SET collection_connection = 'utf8_general_ci'");

	?>
	
	<br>
	<div class="col-lg-6">
		<div class="input-group">
		<form name="searchstock" method="post" action="stock_Price.php">
		<input name="searchword" type="text" class="form-control" placeholder="Search for...(enter the name)">
		<span class="input-group-btn">
			<button class="btn btn-default" name="search" type="submit">Go!</button>
			<button class="btn btn-default" name="all" type="submit">All Stock</button>
		</span>
		</div><!-- /input-group -->
	</div><!-- /.col-lg-6 -->
	
	<div class="content">
		<div id="myTabContent" class="tab-content">
		<div class="row" style="margin:0px 0px 0px 30px ; overflow-y:scroll; height:100%;width:95%; word-wrap: break-word; word-break: normal; ">
			<fieldset>
			<legend>Today's Price</legend>
				<table>
					<tr>
					<th align=center>代碼</th>
					<th align=center>名稱</th>
					<th align=center>成交股數</th>
					<th align=center>成交筆數</th>
					<th align=center>成交金額</th>
					<th align=center>開盤價</th>
					<th align=center>最高價</th>
					<th align=center>最低價</th>
					<th align=center>收盤價(close)</th>
					<th align=center>漲跌(+/-)</th>
					<th align=center>漲跌價差</th>
					<th align=center>最後揭示買價</th>
					<th align=center>最後揭示買量</th>
					<th align=center>最後揭示賣價</th>
					<th align=center>最後揭示賣量</th>
					<th align=center>本益比</th>
					</tr>
	<?php
		if(isset($_POST["search"])){
		  $sql_search = "SELECT * FROM stock_pricecsv WHERE s_name='".$_POST["searchword"]."'";
		  if($result_search=mysqli_query($link, $sql_search)){
			while($row_search=mysqli_fetch_assoc($result_search)){
				echo "<tr>
					  <td align=center>".$row_search["s_id"]."</td>
					  <td align=center>".$row_search["s_name"]."</td>
					  <td align=center>".$row_search["s_transaction_stock"]."</td>
					  <td align=center>".$row_search["s_transaction_number"]."</td>
					  <td align=center>".$row_search["s_transaction_money"]."</td>
					  <td align=center>".$row_search["s_start"]."</td>
					  <td align=center>".$row_search["s_max"]."</td>
					  <td align=center>".$row_search["s_min"]."</td>
					  <td align=center><font color='red'>".$row_search["s_close"]."</td>
					  <td align=center>".$row_search["s_Increase/decrease"]."</td>
					  <td align=center>".$row_search["s_Increase/decrease_spread"]."</td>
					  <td align=center>".$row_search["s_close_buy"]."</td>
					  <td align=center>".$row_search["s_close_buy_number"]."</td>
					  <td align=center>".$row_search["s_close_sell"]."</td>
					  <td align=center>".$row_search["s_close_sell_number"]."</td>
					  <td align=center>".$row_search["s_PER"]."</td></tr>";
			}
		  }
		  $sql_search2 = "SELECT * FROM stock_pricecsv WHERE s_id='".$_POST["searchword"]."'";
		  if($result_search2=mysqli_query($link, $sql_search2)){
			while($row_search2=mysqli_fetch_assoc($result_search2)){
				echo "<tr>
					  <td align=center>".$row_search2["s_id"]."</td>
					  <td align=center>".$row_search2["s_name"]."</td>
					  <td align=center>".$row_search2["s_transaction_stock"]."</td>
					  <td align=center>".$row_search2["s_transaction_number"]."</td>
					  <td align=center>".$row_search2["s_transaction_money"]."</td>
					  <td align=center>".$row_search2["s_start"]."</td>
					  <td align=center>".$row_search2["s_max"]."</td>
					  <td align=center>".$row_search2["s_min"]."</td>
					  <td align=center><font color='red'>".$row_search2["s_close"]."</td>
					  <td align=center>".$row_search2["s_Increase/decrease"]."</td>
					  <td align=center>".$row_search2["s_Increase/decrease_spread"]."</td>
					  <td align=center>".$row_search2["s_close_buy"]."</td>
					  <td align=center>".$row_search2["s_close_buy_number"]."</td>
					  <td align=center>".$row_search2["s_close_sell"]."</td>
					  <td align=center>".$row_search2["s_close_sell_number"]."</td>
					  <td align=center>".$row_search2["s_PER"]."</td></tr>";
			}
		  }
		}elseif(isset($_POST["all"])){
			if($result2 = mysqli_query($link, $sql2)){
				while($row2 = mysqli_fetch_assoc($result2)){
					echo "<tr>
						  <td align=center>".$row2["s_id"]."</td>
						  <td align=center>".$row2["s_name"]."</td>
						  <td align=center>".$row2["s_transaction_stock"]."</td>
						  <td align=center>".$row2["s_transaction_number"]."</td>
						  <td align=center>".$row2["s_transaction_money"]."</td>
						  <td align=center>".$row2["s_start"]."</td>
						  <td align=center>".$row2["s_max"]."</td>
						  <td align=center>".$row2["s_min"]."</td>
						  <td align=center><font color='red'>".$row2["s_close"]."</td>
						  <td align=center>".$row2["s_Increase/decrease"]."</td>
						  <td align=center>".$row2["s_Increase/decrease_spread"]."</td>
						  <td align=center>".$row2["s_close_buy"]."</td>
						  <td align=center>".$row2["s_close_buy_number"]."</td>
						  <td align=center>".$row2["s_close_sell"]."</td>
						  <td align=center>".$row2["s_close_sell_number"]."</td>
						  <td align=center>".$row2["s_PER"]."</td></tr>
						";
				}
			}
		}else{
			if($result2 = mysqli_query($link, $sql2)){
				while($row2 = mysqli_fetch_assoc($result2)){
					echo "<tr>
						  <td align=center>".$row2["s_id"]."</td>
						  <td align=center>".$row2["s_name"]."</td>
						  <td align=center>".$row2["s_transaction_stock"]."</td>
						  <td align=center>".$row2["s_transaction_number"]."</td>
						  <td align=center>".$row2["s_transaction_money"]."</td>
						  <td align=center>".$row2["s_start"]."</td>
						  <td align=center>".$row2["s_max"]."</td>
						  <td align=center>".$row2["s_min"]."</td>
						  <td align=center><font color='red'>".$row2["s_close"]."</td>
						  <td align=center>".$row2["s_Increase/decrease"]."</td>
						  <td align=center>".$row2["s_Increase/decrease_spread"]."</td>
						  <td align=center>".$row2["s_close_buy"]."</td>
						  <td align=center>".$row2["s_close_buy_number"]."</td>
						  <td align=center>".$row2["s_close_sell"]."</td>
						  <td align=center>".$row2["s_close_sell_number"]."</td>
						  <td align=center>".$row2["s_PER"]."</td></tr>
						";
				}
			}
		}
	?>
				</table>
			</fieldset>
		</div>
		</div>
	</div>
	<br>
</body>
</html>