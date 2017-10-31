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
			<li class='has-sub'><a href="stock_function.php">funciton</a>
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
		<div class="row" style="margin:0px 0px 0px 30px ; overflow-y:scroll; height:80%;width:95%; word-wrap: break-word; word-break: normal; ">
			<fieldset>
				<legend>edit</legend>
				<br>

				<?php
					if(isset($_POST["edit"])){						
						if($_POST["pw"]==$_POST["pw2"]){
							$sql_updateuser="UPDATE user SET U_password='".$_POST["pw"]."', U_gender='".$_POST["gender"]."', U_bir='".$_POST["birthday"]."', U_email='".$_POST["email"]."' WHERE U_account='".$_SESSION["uid"]."'";
							if($result_updateuser=mysqli_query($link, $sql_updateuser)){
								echo 'success!';
								echo '<meta http-equiv=REFRESH CONTENT=2;url=stock_Member.php>';
							}else{
								echo 'fail!';
								echo '<meta http-equiv=REFRESH CONTENT=2;url=stock_Member.php>';
							}
						}else{
							echo 'password is not the same, please enter again!';
							echo '<meta http-equiv=REFRESH CONTENT=2;url=stock_userEdit.php>';
						}						
					}
				
					$sql_edit = "SELECT * FROM user WHERE U_account='".$_SESSION["uid"]."'";
					$result_edit = mysqli_query($link, $sql_edit);
					$row_edit = mysqli_fetch_assoc($result_edit);
				
					echo "<form name='form' method='post' action='stock_userEdit.php'>";
					echo "account：".$row_edit["U_account"]."(This item con not edit!) <br><br>";
					echo "password：<input type='password' name='pw' value='".$row_edit["U_password"]."' > <br><br>";
					echo "password again：<input type='password' name='pw2'> <br><br>";
					//echo "名稱：<input type='text' name='nickname' value='".$row_edit["U_name"]."'><br> <br>";
					if($row_edit["U_gender"]="male"){
						echo "gender：<input type='radio' name='gender' value='male' checked>male
								<input type='radio' name='gender' value='female'>female <br><br>";
					}elseif($row_edit["U_gender"]="female"){
						echo "gender：<input type='radio' name='gender' value='male'>male
								<input type='radio' name='gender' value='female' checked>female <br><br>";
					}else{
						echo "gender：<input type='radio' name='gender' value='male'>male
								<input type='radio' name='gender' value='female'>female <br><br>";
					}
					echo "birthday：<input type='date' name='birthday' value='".$row_edit["U_bir"]."'>(yyyy-mm-dd)<br><br>";
					echo "e-mail：<input type='text' name='email' value='".$row_edit["U_email"]."'><br> <br>";
					echo "<input type='submit' name='edit' value='edit'>";
					echo "</form>";
				?>		
			</fieldset>
		</div>
		</div>
	<div>
	
</body>
</html>