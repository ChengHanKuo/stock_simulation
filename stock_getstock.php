<?php  
$toURL = "http://www.twse.com.tw/ch/trading/exchange/MI_INDEX/MI_INDEX.php";
$date = (date("Y") - 1911).date("/m/d");
$post = array(
  "download"=>"csv",
  "qdate"=>$date,
  "selectType"=>"ALLBUT0999",
);
$ch = curl_init();
$options = array(
  CURLOPT_URL=>$toURL,
  CURLOPT_HEADER=>0,
  CURLOPT_VERBOSE=>0,
  CURLOPT_RETURNTRANSFER=>true,
  CURLOPT_USERAGENT=>"Mozilla/4.0 (compatible;)",
  CURLOPT_POST=>true,
  CURLOPT_POSTFIELDS=>http_build_query($post),
);
curl_setopt_array($ch, $options);
// CURLOPT_RETURNTRANSFER=true 會傳回網頁回應,
// false 時只回傳成功與否
$result = curl_exec($ch); 


curl_close($ch);
$result_final = mb_convert_encoding($result, 'UTF-8', 'BIG5');
$split_pattern = '"證券代號","證券名稱","成交股數","成交筆數","成交金額","開盤價","最高價","最低價","收盤價","漲跌(+/-)","漲跌價差","最後揭示買價","最後揭示買量","最後揭示賣價","最後揭示賣量","本益比"';
$split_pattern2 = '"說明';
$rows = explode($split_pattern, $result_final);
$data = explode('"說明', $rows[1]);

$filename = "tmp".date("YmdHis").rand(10000,19999).".csv";
file_put_contents($filename, trim($data[0]));
$handle = fopen($filename, "r");
if ($handle) {
	
	$link = mysqli_connect("localhost", "root", "stock12345", "Stock") 
		or die("無法開啟資料庫！<br/>");
	mysqli_query($link, 'SET CHARACTER SET utf8');
	mysqli_query($link, "SET collection_connection = 'utf8_general_ci'");
    while (($line = fgets($handle)) !== false) {
		$fields = explode(",", $line);
		$fs = array();
		foreach ($fields as $field) {
			array_push($fs, fieldfilt($field));
		}
		$sql = "INSERT INTO `stock`.`stock_pricecsv` (`id`, `s_id`, `s_name`, `s_transaction_stock`, `s_transaction_number`, `s_transaction_money`, `s_start`, `s_max`, `s_min`, `s_close`, `s_Increase/decrease`, `s_Increase/decrease_spread`, `s_close_buy`, `s_close_buy_number`, `s_close_sell`, `s_close_sell_number`, `s_PER`) 
		VALUES (
			NULL, 
			'$fs[0]',
			'$fs[1]',
			'$fs[2]',
			'$fs[3]',
			'$fs[4]',
			'$fs[5]',
			'$fs[6]',
			'$fs[7]',
			'$fs[8]',
			'$fs[9]',
			'$fs[10]',
			'$fs[11]',
			'$fs[12]',
			'$fs[13]',
			'$fs[14]',
			'$fs[15]'
			);";
		
		$sql = "UPDATE `stock`.`stock_pricecsv` SET 
				`s_transaction_stock` = '$fs[2]',
				`s_transaction_number` = '$fs[3]',
				`s_transaction_money` = '$fs[4]',
				`s_start` = '$fs[5]',
				`s_max` = '$fs[6]',
				`s_min` = '$fs[7]',
				`s_close` = '$fs[8]',
				`s_Increase/decrease` = '$fs[9]',
				`s_Increase/decrease_spread` = '$fs[10]',
				`s_close_buy` = '$fs[11]',
				`s_close_buy_number` = '$fs[12]', 
				`s_close_sell` = '$fs[13]', 
				`s_close_sell_number` = '$fs[14]', 
				`s_PER` = '$fs[15]'
			WHERE `stock_pricecsv`.`s_id` = '$fs[0]';";
			
        echo $sql.'<hr>';
		mysqli_query($link, $sql);
    }
    fclose($handle);
} else {
}
unlink($filename);

function fieldfilt($f) {
	$tmpf = str_replace("=", "", $f);
	$tmpf = str_replace("\"", "", $tmpf);
	$tmpf = trim($tmpf);
	return $tmpf;
}

?>