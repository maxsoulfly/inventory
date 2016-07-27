<?  //header('Content-type: text/html; charset=windows-1251');
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset($db, 'utf8');
	
	
	if (isset($_GET['transaction_id'])) {$transaction_id = $_GET['transaction_id'];}
	if (!isset($transaction_id)) {unset($transaction_id);}
	
	if (isset($_GET['page'])) {$page = $_GET['page'];}
	if (!isset($page)) {$page = "view_transaction";}
	
	if(isset($transaction_id)) {
        $transaction = get_transaction($transaction_id);
    }
	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title><? if (isset($transaction)) { echo $transaction['title'];} else { echo "Транзакции"; }?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../../layout/styles/main.css" rel="stylesheet" type="text/css" media="all">
    <link href="../../layout/styles/style-print.css" rel="stylesheet" type="text/css" media="all">
<link href="../../layout/styles/mediaqueries.css" rel="stylesheet" type="text/css" media="all">

<!--[if lt IE 9]>
<link href="../../layout/styles/ie/ie8.css" rel="stylesheet" type="text/css" media="all">
<script src="../../layout/scripts/ie/css3-mediaqueries.min.js"></script>
<script src="../../layout/scripts/ie/html5shiv.min.js"></script>
<![endif]-->
</head>
<body  onload="window.print()">

<!-- ################################################################################################ -->

<?
    // Show Transactions
    if (!isset($transaction_id)) {

}
    else { // Show Transaction #-$transaction_id

    $transactionRes = get_transaction_details ($transaction_id, $db);
    if ($transactionRes&&(mysqli_num_rows($transactionRes)>0)) {

    $transaction = mysqli_fetch_array ($transactionRes);
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
<div id="wrapper">

    <div id="header">
        <h2 class="font-medium blog-post-meta"><? echo "Дата: ".normalize_date($transaction["date"]); ?></h2>
        <h1 class="font-large bold"><? echo $tTitle; ?></h1>
        <div class=" push30 clear"></div>
    </div><!-- #header -->

    <div id="content">
        <h2 class="font-large push30">Продукты</h2>
        <?

            $transactionProductsRes = get_transaction_products ($transaction_id, $db);
            if ($transactionRes&&(mysqli_num_rows($transactionRes)>0)) {
            $transactionProducts =  mysqli_fetch_array ($transactionProductsRes);
            do {
                $product_name = get_product_name($transactionProducts['product_id'],$db);
                $amount = $transactionProducts['amount'];
                echo "<p class='first font-medium one_half push15'>".$product_name."</p>";
                echo "<p class='font-medium one_third'>".$amount."</p><hr class='clear'>";
            }while ($transactionProducts =  mysqli_fetch_array ($transactionProductsRes));

        ?>
    </div><!-- #content -->

    <div id="footer">
        <p style="" class="font-large">
            חתימה

        </p>
        <hr>
    </div><!-- #footer -->
    <?
        }

        }
        else {
            echo "NAN";
        }

        }
    ?>
</div><!-- #wrapper -->

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