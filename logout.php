<?php
session_start();
if(!empty($_SESSION['login_user']))
{
	$_SESSION['login_user']='';
	$_SESSION['username']='';
unset($_SESSION['login_user']);
unset($_SESSION['username']);
session_destroy();
}
header("Location:index.php");
?>