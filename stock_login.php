<html>
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
	<title>login online investing</title>
	<!--<link rel="shortcut icon" href="../favicon.ico">-->
    <!--<link rel="stylesheet" type="text/css" href="css/CreativeCSS3AnimationMenus/demo.css" />-->
    <!--<link rel="stylesheet" type="text/css" href="css/CreativeCSS3AnimationMenus/style10.css" />-->
    <!--<link href='http://fonts.googleapis.com/css?family=Terminal+Dosis' rel='stylesheet' type='text/css' />-->
	<link rel="stylesheet" type="text/css" href="css/menu.css" media="screen">
	<link rel="stylesheet" type="text/css" href="css/style_notice.css" />
	<style>
	body {
		margin:0;
		padding:0;
		background: #000 url("./stock_background.png") center center fixed no-repeat;
		-moz-background-size: cover;
		background-size: cover;
	}		
	</style>
</head>
<body background="./stock_background.png">
<body>
	<div id='cssmenu'>
		<ul>
			<!--<li class='has-sub'><a href="main_new.php">+register</a>
	    	</li>-->
    	</ul>
    </div>
    <center><br/><br/><br/>
	<img src="./title.png" width="900" height="150" border=0 alt="">
	</center>
<?php
	ob_start();
	session_start();
	unset($_SESSION["login_session"]);
?>
	<form method="post" action="stock_login_check.php">
	<br/><br/>
	<font color='white' size='4'>
	<center>
		account：<input type="text" name="account" size="20"/>
		<br/><br/>
		password：<input type="password" name="pwd" size="20"/>
		<br/><br/>

		<input type="submit" value="log in">
		<input type="reset" value="reset">
	</center>
</body>
</html>
