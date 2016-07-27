<?php
    include("pages/blocks/db.php");
	include("pages/blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');

    $sql = get_all_locations();

    $page = "index"

?>
<!DOCTYPE html>
<html>
<head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Где.товар?</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="layout/styles/main.css" rel="stylesheet" type="text/css" media="all">
<link href="layout/styles/mediaqueries.css" rel="stylesheet" type="text/css" media="all">

<script src="js/jquery.min.js"></script>
</head>
<body class="">


<? include("pages/blocks/header.php"); ?>

<!-- ################################################################################################ -->

<? if(!empty($_SESSION['login_user'])) { include("pages/blocks/nav.php");} ?>

<!-- ################################################################################################ -->
<div class="wrapper row2">
  <!-- ################################################################################################ -->
  
  <!-- ################################################################################################ -->
  <div class="clear"></div>
</div>
<!-- content -->
<div class="wrapper row3">
  <div id="container">
    <!-- ################################################################################################ -->
    <div id="homepage" class="clear">
      <section class="clear">
		<div id="main">
            <div class="one_six">&nbsp;</div>
            <div class="five_sixth">
                <h1>Добро пожаловать на главную страницу!</h1>
                <p class="font-large">Выберите Филиал для начала работы:</p>
                <ul>
<?
                if (mysqli_num_rows($sql)>0) {
                    $locations = mysqli_fetch_array($sql);
                    do{
                        echo "<li  class='font-large'><a href='pages/locations/locations.php?id=".$locations['id']."'>".$locations['name']."</a></li>";

                        $i++;
                    }while($locations = mysqli_fetch_array($sql));
                }

?>
                </ul>
            </div>
            <div id="box" class="two_sixth">
            </div>
        </div>
      </section>
    </div>
    <!-- ################################################################################################ -->
    <div class="clear"></div>
    
    
    
  </div>
</div>
<!-- Footer -->
<? include("pages/blocks/footer.php"); ?>
<!-- Scripts -->
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script>window.jQuery || document.write('<script src="layout/scripts/jquery-latest.min.js"><\/script>\
<script src="layout/scripts/jquery-ui.min.js"><\/script>')</script>
<script>jQuery(document).ready(function($){ $('img').removeAttr('width height'); });</script>
<script src="layout/scripts/jquery-mobilemenu.min.js"></script>
<script src="layout/scripts/custom.js"></script>
</body>
</html>