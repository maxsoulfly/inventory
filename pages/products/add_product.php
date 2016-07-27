<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	if (isset($_POST['name'])) { $name = $_POST['name']; if($name=="") {unset($name);}}
	if (isset($_POST['category_id'])) { $category_id = $_POST['category_id']; if($category_id=="") {unset($category_id);}}
	if (isset($_POST['price'])) { $price = $_POST['price']; if($price=="") {unset($price);}}
	if (isset($_POST['amount'])) { $amount = $_POST['amount']; if($amount=="") {unset($amount);}}
	if (isset($_POST['location_id'])) { $location_id = $_POST['location_id']; if($location_id=="") {unset($location_id);}}
	if (isset($_POST['page'])) { $page = $_POST['page']; if($page=="") {unset($page);}}
	if (isset($_POST['page_id'])) { $page_id = $_POST['page_id']; if($page_id=="") {unset($page_id);}}
	
	 

	
?>
<!DOCTYPE html>
<html>
<head>
<?
		//echo "name($name) - &category_id($category_id) - price($price) - amount($amount) - location_id($location_id) - page($page)";
		if (isset($name)&isset($category_id)&isset($price)&isset($amount)&isset($location_id)&isset($page)) {
			
			$category_name = get_category_name ($category_id, $db);
			
			// ADD NEW PRODUCT
			//$result = mysqli_query ("INSERT INTO products (`category_id`, `name`) VALUES ('$category_id', '$name');",$db);
            $result = add_product($category_id, $name);
			if ($result) {
				$error = show_error("Продукт <strong>\"$name\"</strong> был удачно добавлен в Категорию <strong>\"$category_name\"</strong>!", "success", 0);
				
				$new_id = get_product_id ($name, $db);
				
				for ($i=1; $i<sizeof($location_id); $i++) {
					
					if ($amount[0]==0||$amount[0]=="") {$tempAmount = $amount[$i];}
					else {$tempAmount = $amount[0];}
					
					if ($price[0]==0||$price[0]=="") {$tempPrice = $price[$i];}
					else {$tempPrice = $price[0];}
					
					//echo "amount[0]=$amount[0], price[0]=$price[0]<br>";
					//echo "tempAmount=$tempAmount, tempPrice=$tempPrice<br>";
					
					$res = add_location_product ($location_id[$i], $new_id, $tempPrice, $tempAmount);
					
					$new_transaction_id = add_transaction (0,$location_id[$i]);
					if ($new_transaction_id&&$tempAmount>0) {
						$result1 = add_transaction_product ($new_transaction_id, $new_id, $tempAmount);
					}
				}
				
				$footer_links = "<p class='clear'><a class='fl_left' href='../categories/categories.php?id=" .$category_id. "'>&laquo; Назад</a> <a class='fl_right' href='../../index.php'>На Главную &raquo;</a></p>";
				//$error = $error.add_zero_amount_to_all ($new_id, $db);
				

                $url = "../".$page."/".$page.".php?id=".$page_id;
				echo "<meta http-equiv='refresh' content='0; URL=$url'>";
			}
			else { 
				$mysql_error = mysqli_error($db);
				$error = show_error("Продукт <strong>\"$name\"</strong> не был добавлен! <br> <br><strong>Причина</strong>:<br>$mysql_error", "error", 0);
				$footer_links = "<p class='clear'><a class='fl_left' href='javascript:history.go(-1)'>&laquo; Назад</a></p>";
			}
		}
		else {
			$error = show_error("Вы не все поля. Пожалуйста, вернитесь назад и заполните все поля.", "warning", 0);
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