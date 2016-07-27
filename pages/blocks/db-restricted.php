<?php
session_start();
header('Content-type: text/html; charset=utf-8');

$root = "/";

if(empty($_SESSION['login_user'])) { header('Location: '.$root.'login.php'); }

    $host = "localhost";
    $username = "maxsoulf_invuser";
    $password = "3xB}BKI^B))w";
    $database = "maxsoulf_inventory";

$db = mysqli_connect($host,$username,$password,$database) or die ('ERROR: Could not connect to database!');
?>