<?  //header('Content-type: text/html; charset=windows-1251');
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset($db, 'utf8');
	
	$page = "new_return";

    if (isset($_POST['location_id'])) {$to_location = $_POST['location_id'];}
    else if(isset($_GET['location_id'])) {$to_location = $_GET['location_id'];}
    if (!isset($to_location)) {$to_location = 1;}

    if (isset($_POST['category_id'])) {$category_id = $_POST['category_id'];}
    else if(isset($_GET['category_id'])) {$category_id = $_GET['category_id'];}
    if ($category_id=="") {unset($category_id);}

    if (isset($_POST['transaction_id'])) {$transaction_id = $_POST['transaction_id'];}
    else if(isset($_GET['transaction_id'])) {$transaction_id = $_GET['transaction_id'];}
    if (!isset($transaction_id)) {unset($transaction_id);}

    if (isset($_POST['sourcePage'])) {$sourcePage = $_POST['sourcePage'];}
    else if(isset($_GET['sourcePage'])) {$sourcePage = $_GET['sourcePage'];}
    if (!isset($sourcePage)) {$sourcePage="locations";}

    $location_id = $to_location;
    $location_name = get_location_name ($location_id, $db);

    if (isset($_GET['cat'])) {$cat = $_GET['cat'];}
    if (!isset($cat)) {$cat = 0;}
	
	
	//-------[ CATEGORIES ]--------------------------------------//
	//$catresult = get_all_categories ($db);
	
	//-------[ PRODUCTS ]--------------------------------------//
	//$prodresult = get_all_products_orderby_category_id ($db);
?>
<!DOCTYPE html>
<html>
<head>
    <?
//        //echo show_error(isSupplyList_open ($location_id), "info",1);
//
//        if (!isReturnList_open ($location_id)) {
//            $thisReturnID = add_transaction ($location_id,0);
//        }
//        else {
//            $thisReturnID = get_last_return_id ($location_id);
//            echo "<meta http-equiv='refresh' content='0;URL=edit_supply.php?transaction_id=".$thisSupplyID."&to_location=".$location_id."&sourcePage=".$page."'>";
//        }
//

    ?>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Возврат Продуктов</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="../../layout/styles/main.css" rel="stylesheet" type="text/css" media="all">
<link href="../../layout/styles/mediaqueries.css" rel="stylesheet" type="text/css" media="all">
    <style type="text/css">
        #category_id {
        }
    </style>

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

      <div id="content" class="three_quarter">
      
      
          
        <!-- ################################################################################################ -->
        
<!--        <h2>Категории</h2>
-->        <div class="clear push50">
          <!-- ################################################################################################ -->
<form action="add_transaction.php" method="post" name="add_amounts">

<section class="">
        <input type="hidden" id="page" name="page" value="<? echo $page.".php"; ?>">

        <br>
        <h1 class="font-large"><span class="icon-truck icon-large"></span> Возврат Продуктов из <strong><a href="<?=$root?>pages/locations/locations.php?id=<?=$location_id?>"><? echo $location_name; ?></a></strong></h1>
        <!-- ################################################################################################ -->
        <div class="form-input clear">

            <input type="hidden" name="to_location" id="to_location" value="0" size="22" class="fl-left">
            <input type="hidden" name="from_location" id="from_location" value="<? echo $location_id; ?>" size="22" class="fl-left">

         </div>
    <!-- ################################################################################################ -->

    <!-- ################################################################################################ -->
          <div class="tab-wrapper clear">
            <div class="four_fives first">
<?
        $catresult = get_all_categories ($db);
        $categories = mysqli_fetch_array($catresult);
        do{
            if (!category_empty($categories['id'])) {
?>
                <div class='toggle-wrapper'><a href='javascript:void(0)'
                                               class='toggle-title orange'><span><?= $categories['name'] ?></span></a>

                    <div class="toggle-content" style="display: none;">
                        <? echo ("<div id='tab-" . $categories['id'] . "' class='tab-content clear';>"); ?>
                        <div class="form-input clear push10">
                            <h2 class="two_sixth first font-medium"><strong>Название:</strong></h2>

                            <h2 class="one_sixth font-medium"><strong>Нынешное:</strong></h2>

                            <h2 class="one_sixth font-medium"><strong>Возвратить: </strong></h2>
                        </div>
                        <br>
                    </div>

                    <!-- ################################################################################################ -->
                    <!-- ################################################################################################ -->

                    <?
                        $prodresult = get_all_products_from_category ($categories['id'], $db);

                        // if there was data

                        if ($prodresult) {
                            $products = mysqli_fetch_array ($prodresult);
                            do {
                                $amount = get_product_amount_per_location ($products['id'], $to_location, $db);
                                ?>
                                <div class="form-input clear push10">
                                    <p class="two_sixth first"><? echo $products['id']; ?>. <a
                                            href='../products/view_product.php?id=<? echo $products['id']; ?>'><? echo $products['name']; ?></a>
                                    </p>

                                    <p class="one_sixth font-small"><? echo $amount; ?></p>

                                    <p class="one_sixth">
                                        <input type="hidden" name="product_id[]" value="<? echo $products['id']; ?>"
                                               size="22" class="fl-left">
                                        <input type="text" name="amount[]" value="" size="22" class="fl-left">
                                    </p>
                                </div>

                            <?
                            } while ($products = mysqli_fetch_array ($prodresult));

                        } else {

                            echo ("<div class='alert-msg warning'>Категория пока что пуста<a class='close' href='#'>X</a></div>");
                        }
                    ?>
                </div>
            </div>
              <?
                  }
        }while($categories = mysqli_fetch_array($catresult));
    ?>
                    <br><br>

                    <div id="form-bottom">
                        <ul>
                            <input type="hidden" id="completed" name="completed" value="1" title="">
                            <input type="hidden" id="category_id" name="category_id" value="<?=$category_id?>" title="">
                            <li><input class="button orange" type="submit" value="Сохранить" name="save"></li>
                            <li><input class="button white" type="button" value="Назад" name="back" onclick="location.href='<?=$root?>pages/locations/locations.php?id=<? echo $location_id; if(isset($category_id)){echo "&category_id=".$category_id; }?>';"}></li>
                        </ul>
                    </div>
            </div> 
          </div>
          <!-- ################################################################################################ -->
        </div>

      </section>

    </form>
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