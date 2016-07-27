<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset($db, 'utf8');

	if (isset($_POST['product_id'])) { $product_id = $_POST['product_id']; if(sizeof($product_id)<1) {unset($product_id);}}
	if (isset($_POST['amount'])) { $amount = $_POST['amount']; if(sizeof($amount)<1) {unset($amount);}}
    if (isset($_POST['transaction_id'])) {$transaction_id = $_POST['transaction_id']; if ($transaction_id == "") {unset($transaction_id); }}

    $error = "";
    $sourcePage = "locations";

    //echo show_error("completed: ".$completed, "info",1);
    //echo show_error("to_location($to_location) && from_location($from_location) && product_id($product_id) && amount($amount) && transaction_id($transaction_id): ", "info",1);
?>
<!DOCTYPE html>
<html>
<head>
<?
if (isset($product_id) && isset($amount) && isset($transaction_id)) {

    $product = get_product_details($product_id);
    $category_id = $product['category_id'];
    $transaction = get_transaction($transaction_id);
    $from_location = $transaction['from_location'];
    $to_location = $transaction['to_location'];
    $completed = 1;

    if ($amount != "") {
        $prodTransAmount = get_product_transaction_amount ($product_id, $transaction_id);
        if ($prodTransAmount > 0) {
            $result1 = update_transaction_product ($product_id, $transaction_id, $amount);
        }
        else if ($amount==0) {
            $result1 = delete_product_in_transaction($product_id,$transaction_id);
        }
        else {
            $result1 = add_transaction_product ($transaction_id, $product_id, $amount, $db);
        }


        if ($result1 && $completed == 1) {
            // get old amount and add the new one
            $new_amount = get_product_amount_per_location ($product_id, $to_location);
            //echo "new_amount: ".$new_amount." | ";
            $new_amount += $amount;
            //echo "new_amount: ".$new_amount." | <br>";

            //$error = $error . show_error ("$product_id, $to_location, $new_amount", "success", 0);
            // update the new amount
            $result2 = update_product_amount ($to_location, $product_id, $new_amount);

        }

    }

    if ($completed == 1) {
        complete_transaction ($transaction_id);
    }

    if ($to_location != 0) {
        $location_id = $to_location;
    } else {
        $location_id = $from_location;
    }

    $error = show_error("Изменения были сохранены!","success");
    $footer_links = "<p class='clear'> <a class='fl_left' href='../locations/locations.php?id=".$location_id."&category_id=".$category_id."'>&laquo; Назад</a> <a class='fl_right' href='../../index.php'>На Главную &raquo;</a></p>";

    $url = $root."pages/locations/locations.php?id=".$location_id."&category_id=".$category_id;

    echo "<meta http-equiv='refresh' content='0;URL=".$url."'>";
}
else {
    $error = show_error ("Изменения не были сохранены! <br> <br><strong>причина</strong>:<br>Не все параметры были заполнены!<br>Вот то что мы получили: to_location:$to_location | from_location:$from_location | product_id:$product_id | amount:$amount | transaction_id:$transaction_id");

    $footer_links = "<p class='clear'> <a class='fl_left' href='javascript:history.go(-1)'>&laquo; Назад</a> </p>";
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