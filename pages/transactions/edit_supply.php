<?  //header('Content-type: text/html; charset=windows-1251');
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset($db, 'utf8');
	
	$page = "edit_supply";
	if (isset($_POST['to_location'])) {$to_location = $_POST['to_location'];}
    else if(isset($_GET['to_location'])) {$to_location = $_GET['to_location'];}
    else if(isset($_GET['location_id'])) {$to_location = $_GET['location_id'];}
    else if(isset($_POST['location_id'])) {$to_location = $_POST['location_id'];}
	if (!isset($to_location)) {unset($to_location);}

    //$transaction_id = "";
    if (isset($_POST['transaction_id'])) {$transaction_id = $_POST['transaction_id'];}
    else if(isset($_GET['transaction_id'])) {$transaction_id = $_GET['transaction_id'];}
    if (!isset($transaction_id)) {unset($transaction_id);}

    if (isset($_POST['sourcePage'])) {$sourcePage = $_POST['sourcePage'];}
    else if(isset($_GET['sourcePage'])) {$sourcePage = $_GET['sourcePage'];}
    if (!isset($sourcePage)) {$sourcePage="locations";}

    if (isset($location_id)) {$backURL = "../locations/locations.php?id=".$location_id;}

?>
<!DOCTYPE html>
<html>
<head>
    <?
        if (!isset($transaction_id)) {
            echo "<meta http-equiv='refresh' content='0;URL=".$root."404.php'>";
        }
        $transaction = get_transaction_details ($transaction_id);
        $transaction = mysqli_fetch_array ($transaction);
        if (isset($to_location)) $location_id = $to_location;
        else {$location_id = $transaction['to_location'];}
        $location_name = get_location_name ($location_id, $db);
    ?>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title><? if (isset($transaction_id)) { $trans = get_transaction($transaction_id); echo $trans['title'];} else { echo "Добавить Поставку"; }?></title>
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
    <form action="update_transaction.php" method="post" name="add_amounts">
        <input type="hidden" id="page" name="page" value="<? echo $page.".php"; ?>">
        <input type="hidden" id="transaction_id" name="transaction_id" value="<?=$transaction_id?>">
      <section class="">
      	<div> <a id="toggle-right" class="hidden font-large" href="#"><span class="icon-reorder"></span></a></div>
        
        <br>
        <h1 class="font-large"><span class="icon-truck icon-large"></span> <?=$transaction['title'];?></h1>

        
            <!-- ################################################################################################ -->

          <input type="hidden" name="to_location" id="to_location" value="<?=$transaction['to_location']?>" size="22" class="fl-left">
          <input type="hidden" name="from_location" id="from_location" value="0" size="22" class="fl-left">

          <!-- ################################################################################################ -->



        <!-- ################################################################################################ -->

      </section>
<!--        <h2>Категории</h2>
-->        <div class="clear push50">
          <!-- ################################################################################################ -->

          <div class="tab-wrapper clear">
            <div class="four_fives first">
<?
    $catResult = get_all_categories ();
        $categories = mysqli_fetch_array($catResult);
        do{
            echo "<div class='toggle-wrapper'><a href='javascript:void(0)' class='toggle-title orange'><span>".$categories['name']."</span></a>";
?>
                <div class="toggle-content" style="display: none;">
                    <? echo ("<div id='tab-".$categories['id']."' class='tab-content clear';>");?>
                      <div class="form-input clear push10">
                        <h2 class="two_sixth first font-medium"><strong>Название:</strong></h2>

                        <h2 class="one_sixth font-medium"><strong>Нынешное:</strong></h2>
                        <h2 class="one_sixth font-medium"><strong>Добавить: </strong></h2>
                    </div><br>
                </div>
                        
                        
				<?
                    $prodResult = get_all_products_from_category ($categories['id'],$db);

                    // if there was data

                    if ($prodResult) {
                        $products = mysqli_fetch_array($prodResult);
                        do{
                            //echo show_error("location: $location_id","info",1);
                            $amount = get_product_amount_per_location ($products['id'], $location_id);
                            $supplyAmount = get_product_transaction_amount($products['id'], $transaction_id);
                            //echo show_error("amount: ".$amount." | supply ".$supplyAmount ,"info",1);
                            if ($supplyAmount == 0) $supplyAmount="";
                            ?>
                            <div class="form-input clear push10">
                                <p class="two_sixth first"><? echo $products['id']; ?>. <a href='../products/view_product.php?id=<? echo $products['id']; ?>'><? echo $products['name']; ?></a></p>

                                <p class="one_sixth font-small"><? echo $amount; ?></p>
                                <p class="one_sixth">
                                    <input type="hidden" name="product_id[]" value="<?=$products['id']?>" size="22" class="fl-left" title="">
                                    <input type="text" name="amount[]" value="<?=$supplyAmount?>" size="22" class="fl-left" title="">
                                </p>
                            </div>
                                
                <?					
                        }while ($products = mysqli_fetch_array($prodResult));
                            
                    }
                    else {

                        echo ("<div class='alert-msg warning'>Категория пока что пуста<a class='close' href='#'>X</a></div>");
                    }
                    ?>
                            </div> </div>
        <?

            }while($categories = mysqli_fetch_array($catResult));
        ?>
            <p class="top40 pullBottom20 font-medium" ><input type="checkbox" id="mylabel" name="completed" value="1" title="" class="one_sixth first"> Закрыть форму после сохранения </p>

            <br><br>
            <div id="form-bottom">
                <ul>
                    <li><input class="button orange" type="submit" value="Сохранить" name="save"></li>
                    <li><input class="button white" type="button" value="Назад" name="back" onclick="location.href='<?=$backURL?>';"}></li>
                    <li><input class="button white" type="button" value="Отменить" name="delete" onclick="location.href='drop_this_transaction.php?transaction_id=<?=$transaction_id?>&sourcePage=<?=$backURL?>'"}></li>
                </ul>
            </div>

        </div>
            </form>
          </div>
          <!-- ################################################################################################ -->
        </div>

</div>
    <!-- ################################################################################################ -->
<div class="clear"></div>
<!-- Footer -->
</div>

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