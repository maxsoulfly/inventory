<?php

    $host = "localhost";
    $username = "maxsoulf_invuser";
    $password = "3xB}BKI^B))w";
    $database = "maxsoulf_inventory";

//$db = mysql_connect ("localhost","inventoryuser","12345");
//mysql_select_db(,$db);

$db = mysqli_connect($host,$username,$password,$database) or die ('ERROR: Could not connect to database!');

session_start();
if(isset($_POST['username']) && isset($_POST['password']))
{
// username and password sent from Form
$username=$_POST['username']; 
$password=$_POST['password']; 

$result=mysqli_query($db, "SELECT * FROM users WHERE username='$username' and password='$password'");
$count=mysqli_num_rows($result);

$row=mysqli_fetch_array($result);
// If result matched $myusername and $mypassword, table row must be 1 row
if($count==1)
{
$_SESSION['login_user']=$row['id'];
$_SESSION['username']=$row['username'];
$_SESSION['type']=$row['type'];
$_SESSION['location_id']=$row['location_id'];
echo $row['id'];
echo $row['type'];
}

}
?>