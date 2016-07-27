<?php
session_start();
header('Content-type: text/html; charset=utf-8');

$root = "/inventory/";

if(empty($_SESSION['login_user']))
{
header('Location: '.$root.'login.php');
}

if ($_SESSION['location_id']>0){header('Location: '.$root.'pages/locations/locations-restricted.php');}


    $host = "localhost";
    $username = "maxsoulf_invuser";
    $password = "3xB}BKI^B))w";
    $database = "maxsoulf_inventory";

//$db = mysql_connect ("localhost","inventoryuser","12345");
//mysql_select_db(,$db);

$db = mysqli_connect($host,$username,$password,$database) or die ('ERROR: Could not connect to database!');
?>