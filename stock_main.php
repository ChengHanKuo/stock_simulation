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
	<title>Stock Investment</title>
	<style type="text/css">

	p {font-weight: bold}
	img {float: left;margin: 10px}

	</style>
</head>
<body>
	<?php
		$link = mysqli_connect("localhost", "root", "stock12345", Stock") 
				or die("無法開啟資料庫！<br/>");
		$sql = "SELECT s_id , s_name FROM Stocks";
		$sql2 = "SELECT * FROM Stocks";
		mysqli_query($link, 'SET CHARACTER SET utf8');
		mysqli_query($link, "SET collection_connection = 'utf8_general_ci'");

	?>
	<div class="content">
		<div id="myTabContent" class="tab-content">
		<div class="row" style="margin:0px 0px 5px 0px ; overflow-y:scroll; height:50%;width:100%; word-wrap: break-word; word-break: normal; ">
			<fieldset>
			<legend>今日股價</legend>
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
					<th align=center>收盤價</th>
					<th align=center>漲跌(+/-)</th>
					<th align=center>漲跌價差</th>
					<th align=center>最後揭示買價</th>
					<th align=center>最後揭示買量</th>
					<th align=center>最後揭示賣價</th>
					<th align=center>最後揭示賣量</th>
					<th align=center>本益比</th>
					</tr>
	<?php
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
					  <td align=center>".$row2["s_close"]."</td>
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
	?>
				</table>
			</fieldset>
		</div>
		</div>
	</div>
	<br/>
	<form name="stock" method="post" action="stock_main.php">
		<fieldset>
			<legend>會員下單資訊</legend>
			<?php
			$sql_user="SELECT * FROM User WHERE U_account='a7891905'";
			if($result_user=mysqli_query($link, $sql_user)){
				while($row_user=mysqli_fetch_assoc($result_user)){
					$uid=$row_user["U_account"];
					$umoney=$row_user["U_money"];
				}
			}if(isset($_POST["insert"])){
				$sql_newm="SELECT s_close FROM Stocks WHERE s_id='".$_POST["stock_name"]."'";
				if($result_newm=mysqli_query($link, $sql_newm)){
					while($row_newm=mysqli_fetch_assoc($result_newm)){
						$sclose=$row_newm["s_close"];
					}
				}if($_POST["stock_transaction"]=="buy"){
					$plusmoney=$umoney-$sclose*1000;
					$sql_plus="UPDATE User SET U_money='".$plusmoney."' WHERE U_account='".$uid."'";
					mysqli_query($link, $sql_plus);
					echo "您的賬戶餘額：".$plusmoney."<br/>";
				}elseif($_POST["stock_transaction"]=="sell"){
					$minusmoney=$umoney+$sclose*1000;
					$sql_minus="UPDATE User SET U_money='".$minusmoney."' WHERE U_account='".$uid."'";
					mysqli_query($link, $sql_minus);
					echo "您的賬戶餘額：".$minusmoney."<br/>";
				}
			}else{
				echo "您的賬戶餘額：".$umoney."<br/>";
			}
			?>
			名稱:
			<select name="stock_name">
				<?php
					if($result = mysqli_query($link, $sql)){
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
			
			代碼:
				<input name="stock_code" type="text">&nbsp;&nbsp;

			<!--買/賣-->
			<select name="stock_transaction">
				<option value="buy">買進</option>
				<option value="sell">賣出</option>
			</select>&nbsp;&nbsp;

			價位:
				<input name="stock_price" type="text">&nbsp;&nbsp;

			<!--<? 
				$stock_unit = $_POST["stock_unit"]; 
				if($stock_unit=="")  
					$stock_unit=1; 
				else
					$stock_unit++;
				echo "單位:".$stock_unit; 
			?> 
				<input type="hidden" name="stock_unit" value="<?=$stock_unit?>"> 
				<input type="submit" value="+">
				<input type="hidden" name="stock_unit" value="<?=$stock_unit?>"> 
				<input type="submit" value="-">
			-->
			張數:
				<input id="unit" name="stock_unit" type="text" value="1">
				<input type="button" value="+" onclick="QuantityPlus1('Stock')">
				<input type="button" value="-" onclick="QuantityMinus1('Stock')">

			<input type="submit" name="insert" value="送出" onclick="return(confirm('確認您所填寫的資訊是否正確？'))" />
			<input type="reset" value="清除" />
		</fieldset>
	</form>
	<fieldset>
	<legend>委託單</legend>
	<?php
		if(isset($_POST["insert"])){
			if($_POST["stock_transaction"]=="buy"){
				$spstatus=0;
			}elseif($_POST["stock_transaction"]=="sell"){
				$spstatus=1;
			}
			$sql_insert="INSERT INTO Stock_process(SP_unit, SP_status, SP_price, SP_Uid, SP_Sid)
						 VALUES('".$_POST["stock_unit"]."', '".$spstatus."', '".$_POST["stock_price"]."', '".$uid."', '".$_POST["stock_name"]."')";
			if($result_insert=mysqli_query($link, $sql_insert)){
				if($result_insert>0){
					echo "<table>
						    <tr>
						      	<th width=80 align=center>代碼</th>
						      	<th width=80 align=center>股票名稱</th>
						      	<th width=80 align=center>買／賣</th>
						      	<th width=80 align=center>價格</th>
						      	<th width=80 align=center>張數</th>
						    </tr>";
					$sql_have = "SELECT * FROM Stock_process WHERE SP_Uid='".$uid."'";
					if($result_have=mysqli_query($link, $sql_have)){
						while($row_have=mysqli_fetch_assoc($result_have)){
							if($row_have["SP_status"]==0){
								$spstatusci="買進";
							}elseif($row_have["SP_status"]==1){
								$spstatusci="賣出";
							}
							$sql_name = "SELECT * FROM Stocks WHERE s_id='".$row_have["SP_Sid"]."'";
							if($result_name=mysqli_query($link, $sql_name)){
								while($row_name=mysqli_fetch_assoc($result_name)){
									$sname=$row_name["s_name"];
								}
							}
							echo "<tr>
						      		<td align=center>".$row_have["SP_Sid"]."</td>
						      		<td align=center>".$sname."</td>
						      		<td align=center>".$spstatusci."</td>
						      		<td align=center>".$row_have["SP_price"]."</td>
						      		<td align=center>".$row_have["SP_unit"]."</td>
						      	  </tr>";
					    }
					}echo "</table>";
				}
			}else{
				die("fail");
			}
		}
	?>
	</fieldset>
</body>
</html>