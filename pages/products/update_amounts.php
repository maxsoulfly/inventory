<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	if (isset($_POST['location_id'])) { $location_id = $_POST['location_id']; if($location_id=="") {unset($location_id);}}
	if (isset($_POST['product_id'])) { $product_id = $_POST['product_id']; if(sizeof($product_id)<1) {unset($product_id);}}
	if (isset($_POST['amount'])) { $amount = $_POST['amount']; if(sizeof($amount)<1) {unset($amount);}}
	
?>
<!DOCTYPE html>
<html>
<head>
<?		
			$storage_id = 1;
			 //echo "<ul><li>category_id $category_id</li><li>name: $name</li></ul>";
			if (isset($location_id)&&isset($product_id)&&isset($amount)) {
				
				$new_transaction_id = add_transaction ($storage_id, $location_id, $db);
				if ($new_transaction_id) {
					
					$error =  show_error("Транзакция была добавлена!", "success", 0);
					for ($i=0;$i<sizeof($product_id);$i++) {
						if ($amount[$i]>0){
						// add transaction data
							$result1 = add_transaction_product ($new_transaction_id, $product_id[$i], $amount[$i], $db);
							
							if ($result1) {
								// get old amount and add the new one
								$new_amount = get_product_amount_per_location ($product_id[$i], $location_id, $db);
								$error =  $error.show_error("$product_id[$i], $location_id,$new_amount", "success", 0);
								//echo "new_amount: ".$new_amount." | ";
								$new_amount += $amount[$i];
								
								// update the new amout
								$result2 = update_product_amount ($location_id, $product_id[$i], $new_amount, $db);
							}
						}
					}
					$footer_links = "<p class='clear'>
										<a class='fl_left' href='../locations/locations.php?id=" .$id. "'>&laquo; Назад</a>
										<a class='fl_right' href='../../index.php'>На Главную &raquo;</a>
									</p>";
								
					echo "<meta http-equiv='refresh' content='0;URL=locations.php?id=".$location_id."'>";
				}
				else {
					$mysql_error = mysqli_error($db);
					$error =  show_error("Изменения не были сохранены! <br> <br><strong>причина</strong>:<br>$mysql_error");
				
					$footer_links = "<p class='clear'>
										<a class='fl_left' href='javascript:history.go(-1)'>&laquo; Назад</a>
									</p>";
				}
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