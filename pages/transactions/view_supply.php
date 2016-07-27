<?  //header('Content-type: text/html; charset=windows-1251');
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset($db, 'utf8');
	
	$page = "view_supply";

    clearEmptyTransactionProducts();
    clearEmptyTransactions();
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //  SECTION 1: CLEAR EMPTY TRANSACTIONS
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    if (isset($_GET['cmd'])) {
        if($_GET['cmd']=="clearempty"){
            clearEmptyTransactionProducts();
            clearEmptyTransactions();
            echo "<meta http-equiv='refresh' content='0;URL=view_supply.php'>";
        }
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //  SECTION 2: SET PAGE
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	if (isset($_GET['transaction_id'])) {$transaction_id = $_GET['transaction_id'];}
	if (!isset($transaction_id)) {unset($product_id);}

    //$delRes = delete_all_empty_transactions ();
    //echo $delRes;
	
	function all_transactions() {
		echo "<h1 class='title'>Все Поставки</h1>";
		$page = $GLOBALS["page"];
		$where = "WHERE `from_location`=0 AND `to_location` != 0";
		$transRes = get_all_transactions($where);
		if($transRes&&mysqli_num_rows($transRes)>0) {
			$transactions = mysqli_fetch_array ($transRes);
			echo "<ul class='list-table font-medium'>";
			do {
				// transaction date
				$tDate = normalize_date($transactions['date']);

				// transaction id
				$tID = $transactions['id'];
				// transaction title
				$tTitle = $transactions['title'];
				if ($tTitle=="") {
					$tTitle = "Поставка ".$tDate;
				}
                //link to the transaction
                if ($transactions['completed']==0) {
                    $transactionLink = make_link("edit_return.php?transaction_id=",$tID,"[<span class='orange'>Открытый</span>] ".$tTitle." <span class='icon-pencil'></span>");
                }
                else {

                    $transactionLink = make_link($page.".php?transaction_id=",$tID,$tTitle);
                }
                $listItem = "<li class='push10 '>".$transactionLink."</li>";
				echo $listItem;
				
			}while($transactions = mysqli_fetch_array ($transRes));

            if ($_SESSION['type'] == 2) {
                echo "<li><a href='view_supply.php?cmd=clearempty'>Clear all empty transactions</a> </li>";
            }
			echo "</ul>";
		}
		else {
			echo show_error("<p>Транзакции не найдены</p>", "info");
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title><? if (isset($transaction_id)) { echo "Поставка #".$transaction_id;} else { echo "Поставки"; }?></title>
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
    <div id="content" class="three_quarter">
      <section class="clear">
      	<div> <a id="toggle-right" class="hidden font-large" href="#"><span class="icon-reorder"></span></a></div>

        <br>
<?
// Show Transactions List
if (!isset($transaction_id)) {
	all_transactions();
}
else { // Show Transaction #-$transaction_id
	
	$transactionRes = get_transaction_details ($transaction_id, $db);
	if ($transactionRes&&(mysqli_num_rows($transactionRes)>0)) {
		
		$transaction = mysqli_fetch_array ($transactionRes);
		// from Location name
		$fromID = $transaction['from_location'];
		$fromLocationName = get_location_name($fromID);
		// link to location 1
		$fromLink = "Из: ".make_link("locations.php?id=",$fromID, $fromLocationName);
		if ($fromID == '0') { $fromLink = "Поставка";}
		
		// to Location name
		$toID = $transaction['to_location'];
		$toLocationName =  get_location_name($toID);
		// link to location 2
		$toLink = make_link("locations.php?id=", $toID, $toLocationName);
		
		// transaction title
		$tTitle = $transaction['title'];
		if ($tTitle=="") {
			$tTitle = "Поставка #".$transaction_id;
		}
?>			
            <h1 class="font-xl"><a target="_blank" href="print.php?transaction_id=<? echo $transaction_id;?>&page=<? echo $page;?>">
			<? echo $tTitle; ?> <span class="icon-print"></span>
            </a></h1>
            <h2 class="font-large blog-post-meta"><? echo "Дата: ".normalize_date($transaction["date"]); ?></h2>
            <h2 class="blog-post-title one_third first"><? echo $fromLink; ?></a></h2>
            <h2 class="blog-post-title one_third"><? echo "В: ".$toLink; ?></a></h2>
            <div class=" push30 clear"></div>


			<!-- ################################################################################################ -->       
                 
			<!-- ################################################################################################ -->    
            <h1 class="font-xl push30">Продукты</h1>
<?

		$transactionProductsRes = get_transaction_products ($transaction_id, $db);
		if ($transactionRes&&(mysqli_num_rows($transactionRes)>0)) {
			$transactionProducts =  mysqli_fetch_array ($transactionProductsRes);
				do {
					$product_name = get_product_name($transactionProducts['product_id'],$db);
					$amount = $transactionProducts['amount'];
					echo "<p class='first font-large one_third push15'>".$product_name."</p>";
					echo "<p class='font-large one_third'>".$amount."</p>";
				}while ($transactionProducts =  mysqli_fetch_array ($transactionProductsRes));
		}
		else {
			echo show_error("Нет информации о продуктах в данной транзакции", "info");
		}
		echo "<div class='clear '></div><br><br><br><p class='two_third first font-large'>".make_link("view_supply.php","","Все Поставки")."</p>";
	}
	else {
		all_transactions();
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


<? include("../blocks/footer.php"); ?>
<!-- Scripts -->
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script>window.jQuery || document.write('<script src="../../layout/scripts/jquery-latest.min.js"><\/script>\
<script src="/layout/scripts/jquery-ui.min.js"><\/script>')</script>
<script>jQuery(document).ready(function($){ $('img').removeAttr('width height'); });</script>
<script src="/layout/scripts/jquery-mobilemenu.min.js"></script>
<script src="/layout/scripts/custom.js"></script>
</body>
</html>