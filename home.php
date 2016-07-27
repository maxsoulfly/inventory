<?php
session_start();
if(empty($_SESSION['login_user']))
{
header('Location: index.php');
}

?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Home - Login Box Shake Effect</title>
<link rel="stylesheet" href="css/style.css"/>
</head>
<body>
<div id="main">
<h1>Welcome to Home Page</h1>
<p>How are you, <? echo $_SESSION['username']; ?>?</p>
<a href="logout.php">Logout</a>
</div>
</body>
</html>