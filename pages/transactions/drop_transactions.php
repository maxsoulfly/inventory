<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset($db, 'utf8');
	
	if (isset($_POST['id'])) { $product_id = $_POST['id']; if($product_id=="") {unset($product_id);}}
    if (isset($_POST['location_id'])) {$location_id = $_POST['location_id'];} if (!isset($location_id)||$location_id=="") {$location_id=1;}
    if (isset($_POST['category_id'])) {$category_id = $_POST['category_id'];} if (!isset($category_id)||$category_id=="") {$category_id=1;}

?>
<!DOCTYPE html>
<html>
<head>

<?
			//echo "<ul><li>product_id $product_id</li><li>location_id: $location_id</li></ul>";
			if (isset($product_id)&&isset($location_id)) {

				/* all ok -> sqlquery */
				$error = delete_product_transactions_in_location ($product_id, $location_id);

                clearEmptyTransactions();

				$footer_links = "<p class='clear'>
										<a class='fl_left' href='../locations/locations.php'>&laquo; Назад</a>
										<a class='fl_right' href='../../index.php'>На Главную &raquo;</a>
									</p>";
			}
			else {
				$error =  show_error("Вы не выбрали product_id", "warning", 0);
				$footer_links = "<p class='clear'>
										<a class='fl_left' href='javascript:history.go(-1)'>&laquo; Назад</a>
									</p>";
			}
            $url = "".$root."pages/locations/locations.php?id=$location_id&category_id=$category_id";
            echo "<meta http-equiv='refresh' content='0;URL=".$url."'>";
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


    <!-- ################################################################################################ -->
    <div class="three_quarter">
      <section class="clear">
      	<h1><em class="icon-beer"> </em>Обработчик</h1>
        	<? 
				// echo $error;
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