<?
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	if (isset($_POST['category_id'])) { $category_id = $_POST['category_id'];}
	if (isset($_POST['author'])) { $author = $_POST['author']; }
	if (isset($_POST['text'])) { $text = $_POST['text'];}
	if (isset($_POST['id'])) { $id = $_POST['id'];}
	
	if (isset($author)){$author = trim($author);}
	else {$author = "";}
	if (isset($text)){$text = trim($text);}
	else {$text = "";}
	
	if (empty ($author) or empty ($text)) {
		$error = show_error("<p>You haven't filled all the fields, please go back and fill them all!</p>", "warning", 0);
	}
	else {
		$author = stripslashes($author);
		$text = stripslashes($text);
		$author = htmlspecialchars($author);
		//$text = htmlspecialchars($text);
		
		
		$date = date("Y-m-d H:i:s");
		
		//echo "'$author', '$text', '$date'";
		$result2 = mysqli_query ($db, "INSERT INTO comments (author, category_id, text, date) 
                                            VALUES ('$author', '$category_id', '$text', '$date')");
		//$myrow = mysqli_fetch_array($result);
		
		if ($result2) {$error = show_error("<h1>Your Idea was saved</h1>", "success", 0); $success = 1;}
		else { $error = show_error("<h1>Your Idea was saved</h1>".mysqli_error());}
			
	}
  
  
  
  ?>
<!DOCTYPE html>
<html>
<head>
<?
	if ($success == 1) {
		
		$url = "bugslog.php";
		echo "<meta http-equiv='refresh' content='0; URL=$url'>";
		//exit();
	}

?>
<title>comments.php</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../../layout/styles/main.css" rel="stylesheet" type="text/css" media="all">
<link href="../../layout/styles/mediaqueries.css" rel="stylesheet" type="text/css" media="all">
<!--[if lt IE 9]>
    <link href="../../layout/styles/ie/ie8.css" rel="stylesheet" type="text/css" media="all">
    <script src="../../layout/scripts/ie/css3-mediaqueries.min.js"></script>
    <script src="../../layout/scripts/ie/html5shiv.min.js"></script>
    <![endif]-->
</head>
<body class="">

<? include("../blocks/header.php"); ?>

<!-- ################################################################################################ -->

<? include("../blocks/nav.php"); ?>
<div class="wrapper row3">
  	<div id="container">
<?
		echo $error;
?>
	</div>
</div>	

<!-- ################################################################################################ -->

<? include("../blocks/footer.php"); ?>
</body>