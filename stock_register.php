<?php
session_start();

if ((isset($_POST["insert"])) && ($_POST["insert"] == "member_new")) 
{
	$dbname="Stock";
	$link=@mysqli_connect('localhost','root','stock12345',$dbname);
	mysqli_query($link,"SET NAMES 'UTF8'");
	mysqli_query($link,'SET CHARACTER SET utf8_bin');
	mysqli_query($link,"SET collation_connection ='utf8_bin'");
		$account = $_POST['account'];
		$password = $_POST['password'];
		$name = $_POST['name'];
		$gender = $_POST['gender'];
		$birthday=$_POST['year'];
		$email = $_POST['email'];