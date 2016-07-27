<?  //header('Content-type: text/html; charset=windows-1251');
    include("../blocks/db.php");
    include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');

    $page = "locations";
    if (isset($_GET['id'])) {$location_id = $_GET['id'];}
    if (!isset($location_id)||!location_exists($location_id)) {$location_id = 1;}

    $location_name = get_location_name ($location_id);

    if (isset($_GET['category_id'])) {$cID = $_GET['category_id'];}
    if (!isset($cID)) {$cID = 1;}


    //-------[ CATEGORIES ]--------------------------------------//
    //$catresult = get_all_categories ($db);

    //-------[ PRODUCTS ]--------------------------------------//
    //$prodresult = get_all_products_orderby_category_id ($db);
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <title><?=$location_name?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../layout/styles/main.css" rel="stylesheet" type="text/css" media="all">
    <link href="../../layout/styles/mediaqueries.css" rel="stylesheet" type="text/css" media="all">

    <!--[if lt IE 9]>
    <link href="../../layout/styles/ie/ie8.css" rel="stylesheet" type="text/css" media="all">
    <script src="../../layout/scripts/ie/css3-mediaqueries.min.js"></script>
    <script src="../../layout/scripts/ie/html5shiv.min.js"></script>
    <![endif]-->

    <!-- SIDEBAR -->
        <script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
        <script src="../../layout/scripts/jquery.sidebar.js"></script>
        <script src="http://jillix.github.io/jQuery-sidebar/js/handlers.js"></script>

</head>
<body class="">

<!-- ################################################################################################ -->

<? include("../blocks/header.php"); ?>


<? include("../blocks/nav.php"); ?>

<!-- ################################################################################################ -->


<!-- content -->
<div class="wrapper row3">
<div id="container">


<!-- ################################################################################################ -->
<?  include("../blocks/sidebar.php"); ?>

<!-- ################################################################################################ -->


<div id="content" class="full_width">
<section class="">
    <input type="hidden" id="location_id" value="<? echo $location_id; ?>">

    <!-- ################################################################################################ -->
    <!-- ###                  TITLE                                                                   ### -->
    <!-- ################################################################################################ -->
    <h1 class="title left20 font-xl"><span class="icon-building"></span> <strong><? echo $location_name; ?></strong>

            <span class="edit-title">|&nbsp;<a href='#' id="editLocationName" ><span class='icon-pencil fl-right'></span></a>
                <? if (!is_location_undeletable ($location_id)) { ?>
                    &nbsp;<a href='delete_location.php?id=<? echo $location_id; ?>' ><span class='icon-remove fl-right red'></span></a>
                <? } ?>
            </span>
    </h1>

    <h1 class="left30">
        <a href="#" class="btn btn-primary" data-action="toggle" data-side="left"><span> <i class="icon-reorder"></i> Категории</span></a>
    </h1>
    <!-- ################################################################################################ -->
    <h1 class="title edit_on hidden left20">
        <span class="icon-building fl_left right10"></span><strong><input id='<? echo $location_id; ?>' name='location_id' type='text' class='two_sixth font-small' style="padding-left: 2px; margin-top: -4px; font-weight:bold;"  value='<? echo $location_name; ?>' title=""></strong>
            <span class='edit_pan one_sixth'>
                <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right red'></span></a>
            </span>
    </h1>

</section>


<!-- ################################################################################################ -->

<!--        <h2>Категории</h2>
-->        <div class="clear push50">
<!-- ################################################################################################ -->
<div class="tab-wrapper clear">
<ul class="tab-nav clear">
    <?
        // $catResult - mysqli_query object contains all categories information
        $catResult = get_all_categories ($db);
        $categories = mysqli_fetch_array($catResult);
        do{
            $category_id = $categories['id'];
            if($categories['id']==$cID){
                ?><li class='ui-tabs-active ui-state-active' tabindex='0' aria-selected='true' ><a href='#tab-<?= $category_id; ?>'><?= $categories['name']; ?></a></li><?
            }
            else {
                ?><li class='ui-state-default ui-corner-top' tabindex='-1' aria-selected='false'><a href='#tab-<?= $category_id; ?>'><?= $categories['name']; ?></a></li><?
            }
        }while($categories = mysqli_fetch_array($catResult));
    ?>
</ul>
<div class="tab-container">
<!-- Tab Content -->
<?
    $catResult = get_all_categories ($db);
    $categories = mysqli_fetch_array($catResult);
    do{
?>

<div id='tab-<? echo $categories['id']; ?>' class='tab-content clear <? if($categories['id']==$cID){echo " ui-state-active";} ?>';>

<h1 class="title"><strong><span></span><? echo $categories['name']; ?></strong>
                    <span class="edit-title">|&nbsp;<a href='#' id="editCategoryName" name="editCategoryName"><span class='icon-pencil fl-right'></span></a>
                        <? if (category_empty ($categories['id'])) { ?>
                            |&nbsp;<a href='../categories/delete_category.php?id=<? echo $categories['id']; ?>' ><span class='icon-remove fl-right red'></span></a>
                        <? } ?>
                    </span>
</h1>
<h1 class="title edit_on hidden"><span></span><strong><input id='<? echo $categories['id']; ?>' name='category_id' type='text' class='three_sixth font-small push30' style=" margin-top: -30px; font-weight:bold;"  value='<? echo $categories['name']; ?>' title=""></strong>
                    <span class='edit_pan one_sixth push30' style="padding-left: 2px; margin-top: -30px; font-weight:bold;">
                        <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                        <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right red'></span></a>
                    </span>
</h1>
<table class="list-table">
<thead>
<tr>
    <th>Продукт</span></th>
    <th>Цена<br>(a)</th>
    <th nowrap>Операции <a href='../transactions/new_supply.php?id=<? echo $location_id; ?>'><span class='icon-plus'></span></a></th>
    <th nowrap>К-во<br>Шт. / кг.<br>(x)</th>
    <th nowrap>Получено <br>на &sum;<br>(a*x)</th>
    <th nowrap>Возвраты (z) <a href='../transactions/new_return.php?id=<? echo $location_id; ?>'><span class='icon-plus'></span></a><br></th>
    <th nowrap>Возвраты <br>на &sum;<br>(a*z)</th>
    <th>Осталось<br>(y)</th>
    <th nowrap>Продано<br>[(x-z-y)*a]</th>
</tr>
</thead>
<tbody>
<?

    $prodResult = get_all_products_from_category ($categories['id']);
    $color = "light";

    // if there was data

    if ($prodResult) {
        $products = mysqli_fetch_array($prodResult);
        $thisLocation = $location_id;
        do{
            $thisLocProdData = get_location_product_details ($location_id, $products['id']);
            $amount = get_location_product_amount ($products['id'], $location_id);
            if (!$amount) {
                $amount = 0;
            }
            $price 	= $thisLocProdData["price"];
            $amountLeft = get_product_amount_per_location ($products['id'], $location_id);
            $received = $amount*$price;
            $product_id = $products['id'];

            // reset

            $returnAmount = get_location_product_return_amount ($product_id, $thisLocation);
            $returnPrice = $returnAmount*$price;
            $soldTotal = ($amount-$returnAmount-$amountLeft)*$price;
            if (!$returnAmount) {$returnAmount = "-"; $returnedPrice = "-";}
            //echo show_error("$product_id, $thisLocation, $returnAmount");
            echo"<tr class='$color'>";


            ?>

            <!--########################################[            product        ]#######################################################################################################-->
            <td nowrap class='product'>
                <span class="default five_sixth"><a href='#' name='changeName'><? echo $products['name']; ?></a></span>
                        <span class="delete-product one_sixth">
                        	<a href='../products/delete_product.php?id=<? echo $products['id']."&sourcePage=".$page."&idParam=".$location_id; ?>' class="delete font-medium left5 right10" >
                                <span class='icon-remove fl-right red '></span></a>
                        </span>
                    	<span class="edit_on hidden">
                        	<input id='<? echo $products['id']; ?>' name='product_id' type='text' class='four_sixth' value='<? echo $products['name']; ?>' title="">
                            <span class='edit_pan one_sixth'>
                                <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right red'></span></a>
                            </span>
                        </span>
            </td>
            <!--########################################[           /product        ]#######################################################################################################-->

            <!--########################################[            price (a)        ]#####################################################################################################-->
            <td nowrap class='price'>
                <span class="default">&#8362; <a href='#' name='changePrice'><? echo $price; ?></a></span>
                    	<span class="edit_on hidden">&#8362;&nbsp;
                        	<input id='<? echo $products['id']; ?>' name='price' type='text' class='' size='1' value='<? echo $price; ?>' title="">
                            <span class='edit_pan'>
                                <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right red'></span></a>
                            </span>
                        </span>
            </td>
            <!--########################################[           /price (a)        ]#####################################################################################################-->


            <!--########################################[            Transactions        ]##################################################################################################-->
            <?
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            $transRes = get_all_product_recieves ($product_id, $location_id);
            //if there were transactions this month:
            if ($transRes) {
                $transaction = mysqli_fetch_array($transRes);
                echo "<td class='center' style='max-width: 200px;'><div class='toggle-wrapper five_sixth'>
									  <a href='javascript:void(0)' class='toggle-title orange'><span class='icon-eye-open icon-large'></span></a>
									  <div class='toggle-content'><ul class='list tagcloud rnd8 font-small'>";

                $bom_amount = $thisLocProdData["bom_amount"];
                do{
                    $oper = "+";
                    if ($transaction['from_location']==0) {
                        $url = "view_supply.php";
                    }
                    else {
                        $url = "view_transaction.php";
                    }
                    $link = make_link($url,"?transaction_id=".$transaction["transaction_id"], $oper." ".$transaction["amount"]);
                    if ($transaction["amount"] !=0) {
                        echo "<li>".$link."</li>";
                    }
                }while ($transaction = mysqli_fetch_array($transRes));
                echo "</ul></div></div>
								<div class='one_sixth edit-title'>
										<a href='../transactions/delete_transactions.php?product_id=" .$products['id']."&&sourcePage=".$page."&&idParam=".$location_id."'><span class='icon-remove red'></span></a>
									</div>
								</td> <!-- transactions -->";
            }
            else{
                echo "<td class='center' nowrap>
									<div class='five_sixth'>-</div>
									<div class='one_sixth'>
									</div>
								</td> <!-- transactions -->";
            }

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            echo" <td class='amount center'>".$amount."</td> <!-- amount (x) -->
						  <td class='received left'>&#8362; ".$received."</td> <!-- received (a*x) -->";


            ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            $transRes = get_all_product_returns ($product_id, $location_id);
            //if there were transactions this month:
            if ($transRes) {
                $transaction = mysqli_fetch_array($transRes);
                echo "<td class='center' style='max-width: 200px;'><div class='toggle-wrapper five_sixth'>
									  <a href='javascript:void(0)' class='toggle-title orange'><span class='icon-eye-open icon-large'></span></a>
									  <div class='toggle-content'><ul class='list tagcloud rnd8 font-small'>";

                $bom_amount = $thisLocProdData["bom_amount"];
                do{
                    $oper = "-";
                    if ($transaction['from_location']==0) {
                        $url = "view_supply.php";
                    }
                    else {
                        $url = "view_transaction.php";
                    }
                    $link = make_link($url,"?transaction_id=".$transaction["transaction_id"], $oper." ".$transaction["amount"]);
                    if ($transaction["amount"] !=0) {
                        echo "<li>".$link."</li>";
                    }
                }while ($transaction = mysqli_fetch_array($transRes));
                echo "</ul></div><input name='returnAmount' type='hidden' value='$returnAmount'></div>
								<div class='one_sixth edit-title'>
										<a href='../transactions/delete_returns.php?product_id=" .$products['id']."&&sourcePage=".$page."&&idParam=".$location_id."'><span class='icon-remove red'></span></a>
									</div>
								</td> <!-- returns (z) -->";
            }
            else{
                echo "<td class='center' nowrap>
									<div class='five_sixth'>-<input name='returnAmount' type='hidden' value='0'></div>
									<div class='one_sixth'>
									</div>
								</td> <!-- returns (z) -->";
            }

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


            echo "<td class='left'>&#8362; ".$returnPrice."</td> <!-- returned price (a*z) -->";


            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            ?>

            <!--  <td class='center amountLeft'>".$amountLeft."</td> <!-- last count (y) -->

            <td nowrap class='amountLeft center'>
                <span class="default"><a href='#' name='changeAmountLeft'><? echo $amountLeft; ?></a></span>
                            <span class="edit_on hidden">&nbsp;
                                <input id='<? echo $products['id']; ?>' name='amountLeft' type='text' class='' size='1' value='<? echo $amountLeft; ?>' title="">
                                <span class='edit_pan'>
                                    <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                    <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right red'></span></a>
                                </span>
                            </span>
            </td>

            <?
            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($soldTotal <0 ) {$soldTotal = 0;}
            echo "<td class='left'>₪ ".$soldTotal."</td> <!-- sold total [(x-z-y)*a] -->
						</tr>";
            if($color == "light"){
                $color = "dark";
            }
            else {
                $color = "light";
            }
        }while ($products = mysqli_fetch_array($prodResult));

    }
    else {

        echo ("<div class='alert-msg warning'>Категория пока что пуста<a class='close' href='#'>X</a></div>");
    }
?>
</tbody>
</table>
<div class="full_width ">
    <p class="left30 font-medium ">
        <a class=" one_third" href='../products/new_product.php?category_id=<? echo $categories['id']; ?>&location_id=<? echo $location_id; ?>&page=<? echo $page; ?>'><span class="icon-plus"></span>&nbsp;&nbsp;Добавить Новый Продукт</a>

        <? if (!isSupplyList_open ($location_id)) {?>
            <a class="one_third"  href='<?=$root?>pages/transactions/new_supply.php?to_location=<?=$location_id?>&sourcePage=<?=$page?>' name="new_supply"><span class="icon-plus"></span> Добавить Поставку </a>
        <? } ?>
    </p>
</div>
</div>
<?
    }while($categories = mysqli_fetch_array($catResult));
?>
<!-- / Tab Content -->
</div>
</div>
<!-- ################################################################################################ -->
</div>
</div>
<!-- ################################################################################################ -->
<div class="clear"></div>
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