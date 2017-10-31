<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<title>正在重新導向...</title>
</head>

<body>
	<?php
		ob_start();
		session_start();
		
		$account=$_POST["account"];
		$pwd=$_POST["pwd"];

		if ($account !="" && $pwd !=""){
			$link = mysqli_connect("localhost", "root", "stock12345", "Stock") 
				or die("無法開啟資料庫！<br/>");
			mysqli_query($link, 'SET CHARACTER SET utf8');
			mysqli_query($link, "SET collection_connection = 'utf8_general_ci'");

			$sql = "SELECT * FROM User WHERE U_password='".$pwd."' AND U_account='".$account."'";
			$result = mysqli_query($link, $sql);
			$total_record = mysqli_num_rows($result);

			if ( $total_record > 0 ){
				$_SESSION["login_session"] = true;
				$_SESSION["uid"]=$account;
				echo "Log in Success！<br/>";
				echo '<meta http-equiv=REFRESH CONTENT=1;url=stock_function.php>';
				//header ('Location: stock_main.php');
			}else{
				echo "<font color='red'>";
				echo "Wrong Account or password! Leading to login page after 3 seconds!<br/>";
				echo "</font>";
				$_SESSION["login_session"] = false;
				echo '<meta http-equiv=REFRESH CONTENT=3;url=stock_login.php>';
				//header("Location: stock_login.php");
			}
		}
	?>
</body>
</html>