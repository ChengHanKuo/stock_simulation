<?php
	ob_start();

		$link = mysqli_connect("localhost", "root", "stock12345", "Stock") 
				or die("無法開啟資料庫！<br/>");
		mysqli_query($link, 'SET CHARACTER SET utf8');
		mysqli_query($link, "SET collection_connection = 'utf8_general_ci'");

		$sql_checktoday2 = "SELECT * FROM stock_fail";	
		if($result_checktoday2=mysqli_query($link, $sql_checktoday2)){
			$checknumtoday2=mysqli_num_rows($result_checktoday2);
			if($checknumtoday2>0){
				while($row_checktoday2=mysqli_fetch_assoc($result_checktoday2)){
					$sql_changetoundeal="INSERT INTO stock_undeal(SU_unit, SU_status, SU_price, SU_Uid, SU_Sid, SU_time, SU_reason)
						VALUES('".$row_checktoday2["Sf_unit"]."', '".$row_checktoday2["Sf_status"]."', '".$row_checktoday2["Sf_price"]."', '".$row_checktoday2["Sf_Uid"]."', '".$row_checktoday2["Sf_Sid"]."', '".$row_checktoday2["Sf_time"]."', '".$row_checktoday2["Sf_reason"]."')";
					mysqli_query($link, $sql_changetoundeal);
				}
				$sql_deletetoday2="DELETE FROM stock_fail";
				$result_deletetoday2=mysqli_query($link, $sql_deletetoday2)or die(mysqli_error());
			}
		}

		$sql_compare = "SELECT * FROM stock_process"; //連結stock_process資料庫
		if($result_compare=mysqli_query($link, $sql_compare)){
			while($row_compare=mysqli_fetch_assoc($result_compare)){			
				$sql_makesure="INSERT INTO stock_backup(SB_unit, SB_status, SB_buyprice, SB_Uid, SB_Sid, SB_time,SB_reason)
					VALUES('".$row_compare["SP_unit"]."', '".$row_compare["SP_status"]."', '".$row_compare["SP_buyprice"]."', '".$row_compare["SP_Uid"]."', '".$row_compare["SP_Sid"]."', '".$row_compare["SP_time"]."', '".$row_compare["SP_reason"]."')";
				mysqli_query($link, $sql_makesure);	//備份stock_process以確保沒問題			
				
				$sql_compare2 = "SELECT * FROM stock_pricecsv WHERE s_id='".$row_compare["SP_Sid"]."'"; //連結stocks資料庫
				if($result_compare2=mysqli_query($link, $sql_compare2)){
					while($row_compare2=mysqli_fetch_assoc($result_compare2)){
						
						if($row_compare["SP_status"]==1){     //賣出
							if($row_compare["SP_buyprice"]<$row_compare2["s_close"] || $row_compare["SP_buyprice"]==$row_compare2["s_close"]){
								//跟收盤價比較，小於等於表示可以賣
								
								$sql_changetotoday2="INSERT INTO stock_today(ST_unit, ST_status, ST_price, ST_Uid, ST_Sid, ST_time, ST_reason)
													VALUES('".$row_compare["SP_unit"]."', '".$row_compare["SP_status"]."', '".$row_compare2["s_close"]."', '".$row_compare["SP_Uid"]."', '".$row_compare["SP_Sid"]."', '".$row_compare["SP_time"]."', '".$row_compare["SP_reason"]."')";
								//插入stock_today資料庫
								if($result_changetotoday2=mysqli_query($link, $sql_changetotoday2)){
									if($result_changetotoday2>0){										
										//$sql_delete3="DELETE FROM Stock_process WHERE SP_Sid='".$row_compare["SP_Sid"]."' AND SP_Uid='".$row_compare["SP_Uid"]."'";
										//$result_delete3=mysqli_query($link, $sql_delete3)or die(mysqli_error()); //若插入成功，刪除該筆資料
										
										$sql_moneyreturn2="SELECT * FROM User WHERE U_account='".$row_compare["SP_Uid"]."'";
										if($result_moneyreturn2=mysqli_query($link, $sql_moneyreturn2)){
											while($row_moneyreturn2=mysqli_fetch_assoc($result_moneyreturn2)){
												$moneyreturn2=$row_moneyreturn2["U_money"];
											}
										}$mm2=$moneyreturn2+$row_compare2["s_close"]*$row_compare["SP_unit"]*1000;
										$sql_return2="UPDATE User SET U_money='".$mm2."' WHERE U_account='".$row_compare["SP_Uid"]."'";
										mysqli_query($link, $sql_return2); //將賣出的錢給該使用者
									}
								}else{
									die("fail");
								}
							}elseif($row_compare["SP_buyprice"]>$row_compare2["s_close"]){
									//跟收盤價比較，大於表示不可以賣
								$sql_changetofail2="INSERT INTO Stock_fail(Sf_unit, Sf_status, Sf_price, Sf_Uid, Sf_Sid, Sf_time, Sf_reason)
										VALUES('".$row_compare["SP_unit"]."', '".$row_compare["SP_status"]."', '".$row_compare["SP_buyprice"]."', '".$row_compare["SP_Uid"]."', '".$row_compare["SP_Sid"]."', '".$row_compare["SP_time"]."', '".$row_compare["SP_reason"]."')";
								//將資料存入stock_fail資料庫
								if($result_changetofail2=mysqli_query($link, $sql_changetofail2)){
									if($result_changetofail2>0){
										$sql_checkself="SELECT * FROM Stock_self WHERE Ss_Uid='".$row_compare["SP_Uid"]."' AND Ss_Sid='".$row_compare["SP_Sid"]."'";
										//由於stock_Check.php未執行前使用者進行賣出交易時，會先刪掉stock_self裡的股票，所以若未成交，須將股票寫回stock_self
										if($result_checkself=mysqli_query($link, $sql_checkself)){
											$row_checkself=mysqli_fetch_assoc($result_checkself);
											$sselfnum=mysqli_num_rows($result_checkself);
											if($sselfnum>0){
												$unitfail=$row_compare["SP_unit"]+$row_checkself["Ss_unit"];
												$sql_updatefail="UPDATE stock_self SET Ss_unit='".$unitfail."' WHERE Ss_Sid='".$row_compare["SP_Sid"]."' AND Ss_Uid='".$row_compare["SP_Uid"]."'";
												mysqli_query($link, $sql_updatefail);
												//$sql_delete5="DELETE FROM stock_process WHERE SP_Sid='".$row_compare["SP_Sid"]."' AND SP_Uid='".$row_compare["SP_Uid"]."'";
												//$result_delete5=mysqli_query($link, $sql_delete5)or die(mysqli_error());
												//若stock_self裡面有該股票代號的其餘股票，則更新單位就好，然後刪除該筆資料
											}else{
												$sql_backtoself="INSERT INTO Stock_self(Ss_unit, Ss_status, Ss_price, Ss_Uid, Ss_Sid, Ss_reason)
														VALUES('".$row_compare["SP_unit"]."', '0', '".$row_compare["SP_buyprice"]."', '".$row_compare["SP_Uid"]."', '".$row_compare["SP_Sid"]."', '".$row_compare["SP_reason"]."')";
												$result_backtoself=mysqli_query($link, $sql_backtoself);
												//$sql_delete4="DELETE FROM stock_process WHERE SP_Sid='".$row_compare["SP_Sid"]."' AND SP_Uid='".$row_compare["SP_Uid"]."'";
												//$result_delete4=mysqli_query($link, $sql_delete4)or die(mysqli_error());
												//將股票寫回stock_self並刪除該筆資料
											}
										}
									}
								}else{
									die("fail");
								}
							}
						}elseif($row_compare["SP_status"]==0){     //買進
							if($row_compare["SP_buyprice"]>$row_compare2["s_close"] || $row_compare["SP_buyprice"]==$row_compare2["s_close"]){ //如果買的價錢大於等於收盤價
								//跟收盤價比較，大於等於表示可以買
								$sql_selfcheck="SELECT * FROM stock_self WHERE Ss_Sid='".$row_compare["SP_Sid"]."' AND Ss_Uid='".$row_compare["SP_Uid"]."'";
								if($result_selfcheck=mysqli_query($link, $sql_selfcheck)){
									$row_selfcheck=mysqli_fetch_assoc($result_selfcheck);
									$checknum=mysqli_num_rows($result_selfcheck);
									if($checknum>0){
										$newunit=$row_compare["SP_unit"]+$row_selfcheck["Ss_unit"];
										$newprice=($row_compare2["s_close"]*$row_compare["SP_unit"]+$row_selfcheck["Ss_price"]*$row_selfcheck["Ss_unit"])/($row_compare["SP_unit"]+$row_selfcheck["Ss_unit"]);
										$newpricef=round($newprice,2);
										$sql_changeself="UPDATE stock_self SET Ss_unit='".$newunit."' WHERE Ss_Sid='".$row_compare["SP_Sid"]."' AND Ss_Uid='".$row_compare["SP_Uid"]."'";
										mysqli_query($link, $sql_changeself);
										$sql_changeself2="UPDATE stock_self SET Ss_price='".$newpricef."' WHERE Ss_Sid='".$row_compare["SP_Sid"]."' AND Ss_Uid='".$row_compare["SP_Uid"]."'";
										mysqli_query($link, $sql_changeself2);
										//檢查stock_self資料庫，若之前已經買過該股票，則更新單位與價錢就好
															//$sql_todaycheck="SELECT * FROM stock_today WHERE ST_Sid='".$row_compare["SP_Sid"]."' AND ST_Uid='".$row_compare["SP_Uid"]."'";
															//$result_todaycheck=mysqli_query($link, $sql_todaycheck);
															//$row_todaycheck=mysqli_fetch_assoc($result_todaycheck);
															//$checknum2=mysqli_num_rows($result_todaycheck);
															//if($checknum2>0){
																//$newunit2=$row_compare["SP_unit"]+$row_todaycheck["ST_unit"];
																//$newprice2=($row_compare2["s_close"]*$row_compare["SP_unit"]+$row_todaycheck["ST_price"]*$row_todaycheck["ST_unit"])/($row_compare["SP_unit"]+$row_todaycheck["ST_unit"]);
																//$sql_changetoday="UPDATE stock_today SET ST_unit='".$newunit2."' WHERE ST_Sid='".$row_compare["SP_Sid"]."' AND ST_Uid='".$row_compare["SP_Uid"]."'";
																//mysqli_query($link, $sql_changetoday);
																//$sql_changetoday2="UPDATE stock_today SET ST_price='".$newprice2."' WHERE ST_Sid='".$row_compare["SP_Sid"]."' AND ST_Uid='".$row_compare["SP_Uid"]."'";
																//mysqli_query($link, $sql_changetoday2);
															//}else{
																
										$sql_changetotoday="INSERT INTO stock_today(ST_unit, ST_status, ST_price, ST_Uid, ST_Sid, ST_time, ST_reason)
												VALUES('".$row_compare["SP_unit"]."', '".$row_compare["SP_status"]."', '".$row_compare2["s_close"]."', '".$row_compare["SP_Uid"]."', '".$row_compare["SP_Sid"]."', '".$row_compare["SP_time"]."', '".$row_compare["SP_reason"]."')";
										mysqli_query($link, $sql_changetotoday);
										//將資料寫進stock_today資料庫，此為交易紀錄，所以不用更新，直接插入					
															//}
															
										//$sql_deleterepeat="DELETE FROM Stock_process WHERE SP_Sid='".$row_compare["SP_Sid"]."' AND SP_Uid='".$row_compare["SP_Uid"]."'";
										//$result_deleterepeat=mysqli_query($link, $sql_deleterepeat)or die(mysqli_error());
										//刪除stock_process中的該筆資料
									}else{ //如果之前沒有買過這個股票，則直接插入stock_self跟stock_today資料庫
										$sql_changetoself="INSERT INTO stock_self(Ss_unit, Ss_status, Ss_price, Ss_Uid, Ss_Sid, Ss_time, Ss_reason)
												VALUES('".$row_compare["SP_unit"]."', '".$row_compare["SP_status"]."', '".$row_compare2["s_close"]."', '".$row_compare["SP_Uid"]."', '".$row_compare["SP_Sid"]."', '".$row_compare["SP_time"]."', '".$row_compare["SP_reason"]."')";
										if($result_changetoself=mysqli_query($link, $sql_changetoself)){
											if($result_changetoself>0){
												$sql_changetotoday2="INSERT INTO stock_today(ST_unit, ST_status, ST_price, ST_Uid, ST_Sid, ST_time, ST_reason)
												VALUES('".$row_compare["SP_unit"]."', '".$row_compare["SP_status"]."', '".$row_compare2["s_close"]."', '".$row_compare["SP_Uid"]."', '".$row_compare["SP_Sid"]."', '".$row_compare["SP_time"]."', '".$row_compare["SP_reason"]."')";
												mysqli_query($link, $sql_changetotoday2);

												//$sql_delete="DELETE FROM stock_process WHERE SP_Sid='".$row_compare["SP_Sid"]."' AND SP_Uid='".$row_compare["SP_Uid"]."'";
												//$result_delete=mysqli_query($link, $sql_delete)or die(mysqli_error());
												//刪除stock_process中的該筆資料
											}
										}else{
											die("fail");
										}
									}
								}
							}elseif($row_compare["SP_buyprice"]<$row_compare2["s_close"]){
								//跟收盤價比較，小於表示不可以買
								$sql_moneyreturn="SELECT * FROM User WHERE U_account='".$row_compare["SP_Uid"]."'";
								if($result_moneyreturn=mysqli_query($link, $sql_moneyreturn)){
									while($row_moneyreturn=mysqli_fetch_assoc($result_moneyreturn)){
										$moneyreturn=$row_moneyreturn["U_money"];
									}
								}$mm=$moneyreturn+$row_compare2["s_close"]*$row_compare["SP_unit"]*1000;
								$sql_return="UPDATE User SET U_money='".$mm."' WHERE U_account='".$row_compare["SP_Uid"]."'";
								mysqli_query($link, $sql_return); //因為沒有成交，所以把錢退還給使用者
								$sql_changetofail="INSERT INTO Stock_fail(Sf_unit, Sf_status, Sf_price, Sf_Uid, Sf_Sid, Sf_time, Sf_reason)
										VALUES('".$row_compare["SP_unit"]."', '".$row_compare["SP_status"]."', '".$row_compare["SP_buyprice"]."', '".$row_compare["SP_Uid"]."', '".$row_compare["SP_Sid"]."', '".$row_compare["SP_time"]."', '".$row_compare["SP_reason"]."')";
								if($result_changetofail=mysqli_query($link, $sql_changetofail)){
									if($result_changetofail>0){
										//$sql_delete2="DELETE FROM Stock_process WHERE SP_Sid='".$row_compare["SP_Sid"]."' AND SP_Uid='".$row_compare["SP_Uid"]."'";
										//$result_delete2=mysqli_query($link, $sql_delete2)or die(mysqli_error());
										//將資料存入stock_fail資料庫，然後刪除stock_process中的該筆資料
									}
								}else{
									die("fail");
								}
							}
						}													
					}
				}
			}
		}$sql_deleteall="DELETE FROM stock_process";
		 $result_deleteall=mysqli_query($link, $sql_deleteall)or die(mysqli_error());
		 //刪除stock_process中的所有資料
		
		$sql_valuec="UPDATE User SET U_value='0'"; //先將所有使用者的股票價值還原為0
		if(mysqli_query($link, $sql_valuec)){
		
			$sql_valueself = "SELECT * FROM stock_self"; //連接stock_self找出擁有股票的單位
			if($result_valueself=mysqli_query($link, $sql_valueself)){
				while($row_valueself=mysqli_fetch_assoc($result_valueself)){
					$sql_valuestock = "SELECT * FROM stock_pricecsv WHERE s_id='".$row_valueself["Ss_Sid"]."'"; //再連接stocks找出該股票的收盤價
					if($result_valuestock = mysqli_query($link, $sql_valuestock)){
						while($row_valuestock = mysqli_fetch_assoc($result_valuestock)){
							$sql_value = "SELECT * FROM user WHERE U_account='".$row_valueself["Ss_Uid"]."'"; //再連接user找出該股票的持有人
							if($result_value = mysqli_query($link, $sql_value)){
								while($row_value = mysqli_fetch_assoc($result_value)){
									$value=$row_value["U_value"]+$row_valuestock["s_close"]*$row_valueself["Ss_unit"]*1000;
									$sql_valueupdate="UPDATE User SET U_value='".$value."' WHERE U_account='".$row_valueself["Ss_Uid"]."'";
									mysqli_query($link, $sql_valueupdate); //計算股票價值並存入資料庫，並用迴圈跑完self裡面的所有股票來計算
								}
							}
						}
					}
				}
			}
		}

		
	?>