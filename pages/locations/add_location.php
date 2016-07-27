<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	if (isset($_POST['name'])) { $name = $_POST['name']; if($name=="") {unset($name);}}
    if (isset($_POST['username'])) { $username = $_POST['username']; if($username=="") {unset($username);}}
	
	 

	
?>
<!DOCTYPE html>
<html>
<head>
<?
	if (isset($name)&&isset($username)) {
		/* all ok -> sqlquery */
		
		$result = mysqli_query ($db, "INSERT INTO locations (`name`) VALUES ('$name')");
		
		if ($result) {
			$error = show_error("Филиал  <strong>\"$name\"</strong> был удачно добавлен!", "success", 0);
			
			$new_id = get_location_id ($name, $db);

            $userRes = add_new_user ($username, $username, 0 ,$new_id);

            $footer_links = "<p class='clear'><a class='fl_left' href='locations.php?id=".$new_id. "'>&laquo; Назад</a> <a class='fl_right' href='../../index.php'>На Главную &raquo;</a></p>";

            if ($userRes) {$error .= show_error("Пользователь $username был добавлен ", "success");}
            else {$error .= show_error("Пользователь $username не был добавлен ".mysqli_error($db));}

			//echo "<meta http-equiv='refresh' content='0;URL=locations.php'>";

		}
		else { 
			$mysql_error = mysqli_error($db);
			$error = show_error("Филиал <strong>\"$name\"</strong> не был добавлен! <br> <br><strong>Причина</strong>:<br>$mysql_error", "error", 0);
			$footer_links = "<p class='clear'><a class='fl_left' href='javascript:history.go(-1)'>&laquo; Назад</a></p>";
		}
	}
	else {
		$error = show_error("Вы не заполнили название филиала. Пожалуйста, вернитесь назад и заполните название.", "warning", 0);
		$footer_links = "<p class='clear'><a class='fl_left' href='javascript:history.go(-1)'>&laquo; Назад</a></p>";
	}


?>
<title>Обработчик</title>
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

<div class="wrapper row1">
  <?  include("../blocks/header.php"); ?>
</div>

<!-- ################################################################################################ -->
<div class="wrapper row2">
  <?  include("../blocks/nav.php"); ?>
</div>


<!-- content -->
<div class="wrapper row3">
  <div id="container">
    <!-- ################################################################################################ -->
  	<?  include("../blocks/sidebar.php"); ?>
    
    <!-- ################################################################################################ -->
    <div class="three_quarter">
      <section class="clear">
      	<h1><em class="icon-beer"> </em>Обработчик</h1>
        
        	<? 
				echo $error; 
				echo $footer_links; 
			?>
      </section>
    </div>
    <!-- ################################################################################################ -->
    
    <!-- ################################################################################################ -->
    <div class="clear"></div>
    
  </div>
</div>
<!-- Footer -->
<div class="wrapper row4">
  <?  include("../blocks/footer.php"); ?>
</div>
<!-- Scripts -->
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script>window.jQuery || document.write('<script src="../../layout/scripts/jquery-latest.min.js"><\/script>\
<script src="../../layout/scripts/jquery-ui.min.js"><\/script>')</script>
<script>jQuery(document).ready(function($){ $('img').removeAttr('width height'); });</script>
<script src="../../layout/scripts/jquery-mobilemenu.min.js"></script>
<script src="../../layout/scripts/custom.js"></script>
</body>
</html>