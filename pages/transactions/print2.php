<?  //header('Content-type: text/html; charset=windows-1251');
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset($db, 'utf8');
	
	
	if (isset($_GET['transaction_id'])) {$transaction_id = $_GET['transaction_id'];}
	if (!isset($transaction_id)) {unset($product_id);}
	
	if (isset($_GET['page'])) {$page = $_GET['page'];}
	if (!isset($page)) {$page = "view_transaction";}
	
	
	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title><? if (isset($transaction_id)) { echo $title.$transaction_id;} else { echo "Транзакции"; }?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../../layout/styles/main.css" rel="stylesheet" type="text/css" media="all">
<link href="../../layout/styles/mediaqueries.css" rel="stylesheet" type="text/css" media="all">

<!--[if lt IE 9]>
<link href="../../layout/styles/ie/ie8.css" rel="stylesheet" type="text/css" media="all">
<script src="../../layout/scripts/ie/css3-mediaqueries.min.js"></script>
<script src="../../layout/scripts/ie/html5shiv.min.js"></script>
<![endif]-->
</head>
<body onload="window.print()">

<!-- ################################################################################################ -->


<!-- content -->
<div class="wrapper row3">
  <div id="container">
    <!-- ################################################################################################ -->
    <div id="sidebar_1" class="sidebar one_sixth first left"><aside>&nbsp;</aside></div>
    <!-- ################################################################################################ -->
    <div id="content" class="three_quarter">
    <section class="clear">
<?
// Show Transactions
if (!isset($transaction_id)) {
	
}
else { // Show Transaction #-$transaction_id
	
	$transactionRes = get_transaction_details ($transaction_id, $db);
	if ($transactionRes&&(mysql_num_rows($transactionRes)>0)) {
		
		$transaction = mysql_fetch_array ($transactionRes);
		$from_name = get_location_name ($transaction["from_location"],$db);
		$to_name = get_location_name ($transaction["to_location"],$db);
		$tTitle = $transaction['title'];
		if ($tTitle=="") {
			switch ($page){
			case "view_return":
				$tTitle = "Возврат #".$transaction['id'];
				break;
			case "view_supply":
				$tTitle = "Поставка #".$transaction['id'];
				break;
			default:
				$tTitle = "Транзакция #".$transaction['id'];
		}
			}
?>			
            <h1 class="font-xl"><? echo $tTitle; ?></h1>
            <h2 class="font-large blog-post-meta"><? echo "Дата: ".normalize_date($transaction["date"]); ?></h2>
            <h2 class="blog-post-title one_half first"><? echo "Из: <a href='locations.php?id=".$transaction["from_location"]."'>".$from_name; ?></a></h2>
            <h2 class="blog-post-title one_half first"><? echo "В: <a href='locations.php?id=".$transaction["to_location"]."'>".$to_name; ?></a></h2>
            <div class=" push30 clear"></div>
            
        </section>
         <section class="clear">
            <h1 class="font-xl push30">Продукты</h1>
<?
		
		$transactionProductsRes = get_transaction_products ($transaction_id, $db);
		if ($transactionRes&&(mysql_num_rows($transactionRes)>0)) {
			$transactionProducts =  mysql_fetch_array ($transactionProductsRes);
			do {
				$product_name = get_product_name($transactionProducts['product_id'],$db);
				$amount = $transactionProducts['amount'];
				echo "<p class='first font-large one_half push15'>".$product_name."</p>";
				echo "<p class='font-large one_third'>".$amount."</p><hr class='clear'>";
			}while ($transactionProducts =  mysql_fetch_array ($transactionProductsRes));
		}
		
	}
	else {
		echo "NAN";
	}
	
	
?>
		


<?	
}
?>
        
      </section>
      
    </div>
    <!-- ################################################################################################ -->
    <div class="clear"></div>
  </div>
</div>
<!-- Footer -->


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