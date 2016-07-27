<?  //header('Content-type: text/html; charset=windows-1251');
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	$page = "products";
	if (isset($_GET['id'])) {$product_id = $_GET['id'];}
	if (!isset($product_id)) {$product_id = 1;}
	
	
	
	$product = get_product_details ($product_id);
	$category_id = $product['category_id'];
	$category_name = get_category_name($category_id);
	
	//-------[ CATEGORIES ]--------------------------------------//
	//$catresult = get_all_categories ($db);
	
	//-------[ PRODUCTS ]--------------------------------------//
	//$prodresult = get_all_products_orderby_category_id ($db);
?>
<!DOCTYPE html>
<html>
<head>
<title>Продукт: <? echo ($product_id.'. '.$product['name']); ?></title>
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

<!-- content -->
<div class="wrapper row3">
  <div id="container">
    <!-- ################################################################################################ -->
    <? include("../blocks/sidebar.php"); ?>
    <!-- ################################################################################################ -->
    <div id="content" class="three_quarter">
      <section class="clear">
      	<div> <a id="toggle-right" class="hidden font-large" href="#"><span class="icon-reorder"></span></a></div>
        <br>
        <h1 class="title">
            Продукт: <strong><? echo $product['name']; ?></strong>&nbsp;
            <span class="edit-title">
                    	|&nbsp;<a href='edit_product.php?id=<? echo $product_id; ?>' ><span class='icon-pencil fl-right'></span></a>
                    	|&nbsp;<a href='delete_product.php?id=<? echo $product_id; ?>' ><span class='icon-remove fl-right red'></span></a>
					</span>
		</h1>
        <h2 class="font-medium">Из Категории: <a href="../categories/categories.php?id=<? echo $category_id; ?>"><? echo $category_name; ?></a></h2>
        
      </section>
      <div class="clear">
          <h2 class="font-medium">Количество, по филиалам:</h2>
       
        <table class="list-table">
          <thead>
            <tr>
              <th>Филиал</th>
              <th>Цена<br>(a)</th>
              <th>Опер-и</th>
              <th>К-во<br>Шт. / кг.<br>(x)</th>
              <th>Получено на<br>&sum;<br>(a*x)</th>
              <th>Возвраты<br>(z)</th>
              <th>Возвраты на<br>&sum;<br>(a*z)</th>
              <th>Осталось<br>(y)</th>
              <th>Продано<br>[(x-z-y)*a]</th>
            </tr>
          </thead>
          <tbody>
    <?
        $prodresult = get_all_locations_by_product ($product['id']);
        $color = "light";
		
		// if there was data
		
		if ($prodresult) {
			$location_prod = mysqli_fetch_array($prodresult);
			do{
					
					$amount = get_location_product_amount ($product_id, $location_prod['location_id']);
					if (!$amount) {
						$amount = 0;
					}
					$price = $location_prod['price'];
					$received = $price*$amount;
					$thisLocation = $location_prod['location_id'];
					$location_name = get_location_name($thisLocation);
					$product_id = $location_prod['product_id'];
					
					
					
					
					$returnAmount = get_location_product_return_amount ($product_id, $thisLocation);
					$returnPrice = $returnAmount*$price;
					echo show_error($returnPrice);
					if (!$returnAmount) {$returnAmount = "-"; $returnedPrice = "-";}
					echo"<tr class='$color'>
						  <td>".$location_prod['location_id']." - <a href='locations.php?id=".$location_prod['location_id']."'>".$location_name."</a></td> <!-- id -->
						  <td>&#8362; ".$price."</td> <!-- price (a) -->";


       // <!-- ################################################################################################ -->

          
						$transRes = get_product_transactions_by_month_by_location ($product_id, $thisLocation);
						//if there were transactions this month:
						if ($transRes) {
							$transaction = mysqli_fetch_array($transRes);
							echo "<td class='center'><div class='toggle-wrapper'><a href='javascript:void(0)' class='toggle-title orange'><span class='icon-eye-open icon-large'></span></a>
									  <div class='toggle-content'><ul class='list tagcloud rnd8'>";
							do{
								  if ($transaction['to_location']==$thisLocation) {
									$oper = "+";
								  }
								  if ($transaction['from_location']==$thisLocation) {
									 $oper = "-";
								  }
								  if ($transaction["amount"] !=0) {
							  	  	echo "<li><a href='../transactions/view_transaction.php?transaction_id=" .$transaction["transaction_id"]."'>".$oper." ".$transaction["amount"]."</a></li>";
								  }
							}while ($transaction = mysqli_fetch_array($transRes));
							echo "</ul></div></div></td> <!-- transactions -->";
						}
						else{
							echo "<td>-</td> <!-- transactions -->";
						}

		
       // <!-- ################################################################################################ -->

					if ($returnAmount == "-") {
						$returnLink=$returnAmount;
					}
					else {
						$returnLink = make_link("view_return.php","?product_id=".$product_id."&location_id=".$thisLocation,$returnAmount);
					}
					echo " <td>".$amount."</td> <!-- amount (x) -->
						  <td class='left'>&#8362; ".$received."</td> <!-- received (a*x) -->
						  <td class='center'>".$returnLink."</td> <!-- returns (z) -->
						  <td class='left'>&#8362; ".$returnPrice."</td> <!-- returned price (a*z) -->
						  <td class='center'>-</td> <!-- last count (y) -->
						  <td class='center'>-</td> <!-- sold total [(x-z-y)*a] -->

						</tr>";
					if($color == "light"){
						$color = "dark";
					}
					else {
						$color = "light";
					}
			}while ($location_prod = mysqli_fetch_array($prodresult));
			
		}
		else {
			
			echo ("<div class='alert-msg warning'>Категория пока что пуста<a class='close' href='#'>X</a></div>");
		}
		?>
					  </tbody>
					</table>
			 </div>
              <!-- / Tab Content -->

      </section>
      
    </div>
    <!-- ################################################################################################ -->
    <div class="clear"></div>
  </div>
</div>
<!-- Footer -->


<? include("../blocks/footer.php"); ?>
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