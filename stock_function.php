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
		<meta charset="UTF-8" />
		<title>online investing</title>
		<link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/CreativeCSS3AnimationMenus/demo.css" />
        <link rel="stylesheet" type="text/css" href="css/CreativeCSS3AnimationMenus/style10.css" />
        <link href='http://fonts.googleapis.com/css?family=Terminal+Dosis' rel='stylesheet' type='text/css' />
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
		<ul class="ca-menu" style="position:absolute;top:50%;left:50%;margin-left:-420;margin-top:-125;">
                    <li>
                        <a href="stock_Member.php">
                            <span class="ca-icon">Q</span>
                            <div class="ca-content">
                                <h2 class="ca-main">會員資訊<br/>Information</h2>
                                <h3 class="ca-sub">Upload your photo!</h3>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="stock_Price.php">
                            <span class="ca-icon">L</span>
                            <div class="ca-content">
                                <h2 class="ca-main">今日股價<br/>Today's Price</h2>
                                <h3 class="ca-sub">Upload your photo!</h3>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="stock_BuySell.php">
                            <span class="ca-icon" id="heart">B</span>
                            <div class="ca-content">
                                <h2 class="ca-main">下單<br/>Buy/Sell</h2>
                                <h3 class="ca-sub">Understanding visually</h3>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="stock_OrderTicket.php">
                            <span class="ca-icon">D</span>
                            <div class="ca-content">
                                <h2 class="ca-main">委託單<br/>Order Ticket</h2>
                                <h3 class="ca-sub">Professionals in action</h3>
                            </div>
                        </a>
                    </li>
				</ul>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>

		</div><!-- /container -->
	</body>
</html>