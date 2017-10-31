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
<SCRIPT type="text/javascript">
       <!-- 此check()函式在最後的「傳送」案鈕會用到 -->
        function check()
        {
				if(reg.stock_reason.value == "space" )
                {
                        alert("you must to choose one!");
                }
                <!-- 若以上條件皆不符合，也就是表單資料皆有填寫的話，則將資料送出 -->
                else reg.submit();
         }
</SCRIPT>
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
		$sql_search = "SELECT s_id , s_name FROM stock_pricecsv";
		mysqli_query($link, 'SET CHARACTER SET utf8');
		mysqli_query($link, "SET collection_connection = 'utf8_general_ci'");
	?>
<br>

	<form name="stock" method="post" action="stock_buycheck.php">
	<div class="row" style="margin:0px 0px 0px 30px ; overflow-y:scroll;
	height:100%;width:95%; word-wrap: break-word; word-break: normal; ">
		<fieldset>
			<legend>Order Information</legend>
			<br>
			<?php
			$sql_user="SELECT * FROM User WHERE U_account='".$_SESSION["uid"]."'";
			if($result_user=mysqli_query($link, $sql_user)){
				while($row_user=mysqli_fetch_assoc($result_user)){
					$umoney=$row_user["U_money"];
					$_SESSION["umoney"]=$umoney;
				}
			}echo "&nbsp;&nbsp;Hi!&nbsp".$_SESSION["uid"].",Your account have：".$umoney." NT dollars<br>";
			?>

			<br>
			&nbsp;&nbsp;Stock Code & Name :
			<select name="stock_name">
				<?php
					if($result = mysqli_query($link, $sql_search)){
						while($row = mysqli_fetch_assoc($result)){
							echo "<option value='".$row["s_id"]."'>".$row["s_id"]."&nbsp;".$row["s_name"]."</option>";
							//$select_stock=$_POST["stock_name"];
							//switch($select_stock){
							//	case $row["s_id"]:
							//		echo "</select>&nbsp;&nbsp;
						  	//			  代碼：
						  	//			  <input name='stock_code' type='text' value='".$row["s_id"]."'>&nbsp;&nbsp;"; break;				
							//}
						}
					}

				?>
			
			</select>&nbsp;&nbsp;
			
			<!--買/賣-->
			<select name="stock_transaction">
				<option value="buy">Buy</option>
				<option value="sell">Sell</option>
			</select>&nbsp;&nbsp;

			Order Price:
				<input name="stock_price" type="text" style="width: 60px;">&nbsp;&nbsp;

			<!--<? 
				$stock_unit = $_POST["stock_unit"]; 
				if($stock_unit=="")  
					$stock_unit=1; 
				else
					$stock_unit++;
				echo "unit:".$stock_unit; 
			?> 
				<input type="hidden" name="stock_unit" value="<?=$stock_unit?>"> 
				<input type="submit" value="+">
				<input type="hidden" name="stock_unit" value="<?=$stock_unit?>"> 
				<input type="submit" value="-">
			-->
			unit:
				<input id="unit" name="stock_unit" type="text" value="1" style="width: 30px;">&nbsp;
				<!--
				<input type="button" value="+" onclick="QuantityPlus1('Stock')">
				<input type="button" value="-" onclick="QuantityMinus1('Stock')">
				-->
			reason:
			<select name="stock_reason" width="100">
				<option value="space">&lt;choose&gt;</option>
				<option value="CurrentRatio">Current ratio</option>
				<option value="NetWorkingCapital">Net working capital</option>
				<option value="AccountsReceivableTurnover">Accounts receivable turnover</option>
				<option value="InventoryTurnover">Inventory turnover</option>
				<option value="TotalAssetTurnover">Total asset turnover</option>
				<option value="Debt-equityRatio">Debt-equity ratio</option>
				<option value="TimesInterestEarned">Times interest earned</option>
				<option value="NetProfitMargin">Net profit margin</option>
				<option value="ROA">ROA</option>
				<option value="ROE">ROE</option>
				<option value="PEG_Ratio">PEG ratio</option>
				<option value="DividendsPerShare">Dividends per share</option>
				<option value="PayoutRatio">Payout ratio</option>
				<option value="BookValuePerShare">Book value per share</option>
				<option value="Price-to-book-value">Price-to-book-value</option>
				<option value="Others">other reason</option>
			</select>&nbsp;&nbsp;
			
			<input name="Others_write" type="text" style="width: 200px;" placeholder="other reason">&nbsp;&nbsp;
			
			<input type="submit" name="insert" value="send" onClick="check()" />
			<input type="reset" value="reset" />
			<!--
			onclick="return(confirm('confirm your information correct or not again？'))"
			<br><br>
			<div class="col-lg-6">
				<div class="input-group">
				<form name="searchstock" method="post" action="stock_BuySell.php">
				&nbsp;&nbsp;<input name="searchword" type="text" class="form-control" placeholder="Search for...(enter the name)">
				<span class="input-group-btn">
					<button class="btn btn-default" name="search" type="submit">Go!</button>
				</span></form>
				</div>
			</div> -->
			
			<?php
			/*
			if(isset($_POST["search"])){
				$sql_search = "SELECT * FROM stock_pricecsv WHERE s_name='".$_POST["searchword"]."'";
				if($result_search=mysqli_query($link, $sql_search)){
					while($row_search=mysqli_fetch_assoc($result_search)){
						echo "<table>
								<tr>
								<th align=center>代碼</th>
								<th align=center>名稱</th>
								<th align=center>收盤價(close)</th>
								</tr><tr>
							  <td align=center>".$row_search["s_id"]."</td>
							  <td align=center>".$row_search["s_name"]."</td>
							  <td align=center><font color='red'>".$row_search["s_close"]."</td></table>";
					}
				}
				$sql_search2 = "SELECT * FROM stock_pricecsv WHERE s_id='".$_POST["searchword"]."'";
				if($result_search2=mysqli_query($link, $sql_search2)){
					while($row_search2=mysqli_fetch_assoc($result_search2)){
						echo "<table>
								<tr>
								<th align=center>代碼</th>
								<th align=center>名稱</th>
								<th align=center>收盤價(close)</th>
								</tr><tr>
							  <td align=center>".$row_search2["s_id"]."</td>
							  <td align=center>".$row_search2["s_name"]."</td>
							  <td align=center><font color='red'>".$row_search2["s_close"]."</td></table>";
					}
				}
			}
			*/
			?>
			<br><br>
		</fieldset>
	</div>
	</form>
</body>
</html>