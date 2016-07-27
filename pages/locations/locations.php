<?
    include("../blocks/db.php");
    include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
?>
<?
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    //  SECTION 1: DROP PRODUCT
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    #region  DROP PRODUCT
    if (isset($_GET['cmd']) && $_GET['cmd'] == "deleteproduct") {
        if (isset($_GET['product_id'])) {

            $product_id = $_GET['product_id'];
            $tmp = get_product_details($product_id);
            $category_id = $tmp["category_id"];
            if (isset($_GET['id'])) {
                $location_id = $_GET['id'];
                $url = "".$root."pages/locations/locations.php?id=$location_id&category_id=$category_id";
            }
            else {$url = "".$root."pages/locations/locations.php";}

            if (isset($product_id)) {
                /* all ok -> sql_query */
                delete_product ($product_id);
                clearEmptyTransactionProducts();
                clearEmptyTransactions ();
                echo "<meta http-equiv='refresh' content='0;URL=" . $url . "'>";
                exit ();
            }
        }
    }
    #endregion  DROP PRODUCT
?>
<?
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    //  SECTION 2: CANCEL TRANSACTION
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    #region  CANCEL TRANSACTION
    if (isset($_GET['cmd']) && $_GET['cmd'] == "canceltransaction") {
        if (isset($_GET['product_id'])&&isset($_GET['transaction_id'])) {

            $product_id = $_GET['product_id'];
            $transaction_id = $_GET['transaction_id'];

            $url = $root."pages/locations/locations.php";
            if (isset($_GET['category_id'])&&isset($_GET['id'])&&isset($_GET['amount'])) {
                $category_id = $_GET["category_id"];
                $location_id = $_GET["id"];

                /*
                    We need also update the "left" value.
                    1. get transaction amount we about to delete
                    2. get the saved amount
                    3. subtract the old from the current amount
                */
                $amount = $_GET["amount"];
                $old_amount = get_location_product_amount($product_id, $location_id);
                $new_amount = $old_amount - $amount;
                $url .= "?id=$location_id&category_id=$category_id";
                if (isset($product_id)&&isset($transaction_id)) {

                    delete_product_in_transaction($product_id, $transaction_id);
                    update_location_product ($location_id, $product_id, $new_amount, "");
                    clearEmptyTransactionProducts();
                    clearEmptyTransactions ();

                    echo "<meta http-equiv='refresh' content='0;URL=" . $url . "'>";
                    exit ();
                }
            }

        }
    }
    #endregion  CANCEL TRANSACTION
?>
<?
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //  SECTION 3: ADD NEW PRODUCT
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    #region  ADD NEW PRODUCT
    if (!empty($_POST)) {
        if (isset($_POST['category_id'])) { $category_id = $_POST['category_id']; if($category_id=="") {unset($category_id);}}
        if (isset($_POST['location_id'])) { $location_id = $_POST['location_id']; if($location_id=="") {unset($location_id);}}
        if (isset($_POST['name'])) { $name = $_POST['name']; if($name=="") {unset($name);}}
        if (isset($_POST['price'])) { $price = $_POST['price']; if($price=="") {unset($price);}}
        if (isset($_POST['amount'])) { $amount = $_POST['amount']; if($amount=="") {unset($amount);}}

        if (isset($name)&isset($category_id)&isset($price)&isset($amount)&isset($location_id)) {

            $category_name = get_category_name ($category_id);

            // ADD NEW PRODUCT
            $new_id = add_product($category_id, $name);

            //echo show_message($new_id);

            if ($new_id > 0) {
                $error = show_message ("Продукт <strong>\"$name\"</strong> был удачно добавлен в Категорию <strong>\"$category_name\"</strong>!", "success", 0);

                $sql = get_all_locations ();

                while ($all_locations = mysqli_fetch_array ($sql)) {
                    //echo show_message("ADDING location: $location_id | price: $price | amount: $amount");
                    $res = add_location_product ($location_id, $new_id, $price, $amount);

                    if ($all_locations['id'] == $location_id && $amount > 0) {
                        // add new transaction, no title, complete it
                        $new_transaction_id = add_transaction (0, $location_id,"",1);
                        if ($new_transaction_id) {
                            $result1 = add_transaction_product ($new_transaction_id, $new_id, $amount);
                        }
                    }
//                    else {
//                        $res = add_location_product ($location_id, $new_id, 0, 0);
//                    }
                }
                $footer_links = "<p class='clear'><a class='fl_left' href='../categories/categories.php?id=" . $category_id . "'>&laquo; Назад</a> <a class='fl_right' href='../../index.php'>На Главную &raquo;</a></p>";
                //$error = $error.add_zero_amount_to_all ($new_id, $db);


                $url = "".$root."pages/locations/locations.php?id=" . $location_id . "&category_id=". $category_id;
                echo "<meta http-equiv='refresh' content='0; URL=$url'>";
                exit();
            }
        }
        else {
            $mysql_error = mysqli_error($db);
            $error = show_error("Продукт <strong>\"$name\"</strong> не был добавлен! <br> <br><strong>Причина</strong>:<br>$mysql_error", "error", 0);
            $footer_links = "<p class='clear'><a class='fl_left' href='javascript:history.go(-1)'>&laquo; Назад</a></p>";
        }
        if (isset($location_id)) {
            $idParam = "id=" . $location_id;
        }
        if (isset($category_id)) {
            $catParam ="&category_id=". $category_id;
        }
        
        $url = "".$root."pages/locations/locations.php?" . $idParam . $catParam;
        echo "<meta http-equiv='refresh' content='0; URL=$url'>";
        exit();
    }
    #endregion  ADD NEW PRODUCT
?>
<?
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //  SECTION 4: SET PAGE
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    #region  SET PAGE
    $page = "locations";
    if (isset($_GET['id'])) {$location_id = $_GET['id'];}
    if (!isset($location_id)||!location_exists($location_id)) {$location_id = 1;}

    $location_name = get_location_name ($location_id);

    if (isset($_GET['category_id'])) {$category_id = $_GET['category_id'];}
    if (isset($category_id)&&$category_id=="") unset($category_id);

    if (isset($category_id)) {
        $category_name = get_category_name($category_id);
    }
    else {
        // Select first category, alphabetically:
        $thisCategory = get_first_category_alphabetically();
        //echo show_error($thisCategory);
        $category_name = $thisCategory['name'];
        $category_id = $thisCategory['id'];
    }

    $deleteURL = "".$root."pages/locations/locations.php?id=$location_id&cmd=deleteproduct";
    #endregion
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

<!-- ################################################################################################ -->
<!-- ###                  TITLE                                                                   ### -->
<!-- ################################################################################################ -->
<section class="">


    <input type="hidden" id="location_id" value="<? echo $location_id; ?>">

    <h1 class="title font-xl"><span class="icon-building"></span> <strong><? echo $location_name; ?></strong>

            <span class="edit-title">|&nbsp;<a href='#' id="editLocationName" ><span class='icon-pencil fl-right'></span></a>
                <? if (!is_location_undeletable ($location_id)&&($_SESSION['type'] == 2)) { ?>
                    &nbsp;<a href='delete_location.php?id=<? echo $location_id; ?>' ><span class='icon-remove fl-right red'></span></a>
                <? } ?>
            </span>
    </h1>

    <!-- ###                  INPUT                                                                   ### -->
    <!-- ################################################################################################ -->
    <h1 class="title edit_on hidden font-xl pullTop25 push20">
        <span class="icon-building fl_left right10 "></span><strong><input id='<? echo $location_id; ?>' name='location_id' type='text' class='three_sixth font-small right55' style="padding-left: 2px; margin-top: -4px; font-weight:bold;"  value='<? echo $location_name; ?>' title=""></strong>
            <span class='edit_pan two_sixth'>
                <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right red'></span></a>
            </span>
    </h1>
    <div class="clear"></div>
    <h1 class="left10">
        <a href="#" class="btn btn-primary" data-action="toggle" data-side="left"><i class="icon-reorder"></i> Категории</a>
    </h1>

    <h1 class="title font-xl"><a href="print.php?id=<?=$location_id?>"><span class="icon-print"></span></a></h1>
</section>


<div class="clear push50">

    <!-- ################################################################################################ -->
    <!-- ###                  CATEGORY TITLE                                                          ### -->
    <!-- ################################################################################################ -->


    <h1 class="title left10"><strong><?=$category_name?></strong>
        <input type="hidden" id="category_id" name="category_id" value="<?=$category_id?>">
                        <span class="edit-title">|&nbsp;<a href='#' id="editCategoryName" name="editCategoryName"><span class='icon-pencil fl-right'></span></a>

                                |&nbsp;<a href='../categories/delete_category.php?id=<?=$category_id?>&location_id=<?=$location_id?>' class="red" ><span class='icon-remove fl-right '></span></a>

                        </span>
    </h1>
    <h1 class="title edit_on hidden"><span></span><strong><input id='<? echo $categories['id']; ?>' name='category_id' type='text' class='three_sixth font-small push30' style=" margin-top: -30px; font-weight:bold;"  value='<? echo $categories['name']; ?>' title=""></strong>
                        <span class='edit_pan one_sixth push30' style="padding-left: 2px; margin-top: -30px; font-weight:bold;">
                            <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                            <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right red'></span></a>
                        </span>
    </h1>


    <!-- ################################################################################################ -->
    <!-- ###                  TABLE                                                                   ### -->
    <!-- ################################################################################################ -->
    <table class="list-table  font-medium">

        <!-- ################################################################################################ -->
        <!-- ###                  TABLE HEADER                                                            ### -->
        <!-- ################################################################################################ -->

        <thead>
        <tr>

            <th>Продукт</span></th>
            <th>Цена<br>(a)</th>
            <th nowrap style="min-width: 150px">Поставки <a href='../transactions/new_supply.php?to_location=<?=$location_id?>&category_id=<?=$category_id?>'><span class='icon-plus'></span></a></th>
            <th nowrap>К-во<br>Шт. / кг.<br>(x)</th>
            <th nowrap>Получено <br>на &sum;<br>(a*x)</th>
            <th nowrap style="min-width: 150px">Возвраты (z) <a href='../transactions/new_return.php?location_id=<?=$location_id?>&category_id=<?=$category_id?>'><span class='icon-plus'></span></a><br></th>
            <th nowrap>Возвраты <br>на &sum;<br>(a*z)</th>
            <th>Осталось<br>(y)</th>
            <th nowrap>Продано<br>[(x-z-y)*a]</th>
            <th nowrap>Удалить</th>
        </tr>
        </thead>


        <!-- ################################################################################################ -->
        <!-- ###                  TABLE BODY                                                              ### -->
        <!-- ################################################################################################ -->
        <tbody>
        <?

            $prodResult = get_all_products_from_category ($category_id);
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

                    $amount=decimal_format($amount);
                    $price 	= $thisLocProdData["price"];
                    if ($price == "") $price = 0;
                    $price = number_format($price,2,"."," ");
                    $amountLeft = get_product_amount_per_location ($products['id'], $location_id);
                    $amountLeft=decimal_format($amountLeft);
                    $received = $amount*$price;
                    $received=decimal_format($received);
                    $product_id = $products['id'];

                    // reset

                    $returnAmount = get_location_product_return_amount ($product_id, $thisLocation);

                    $returnAmount=decimal_format($returnAmount);
                    $returnPrice = $returnAmount*$price;
                    $returnAmount=decimal_format($returnAmount);
                    $soldTotal = ($amount-$returnAmount-$amountLeft)*$price;
                    $soldTotal=decimal_format($soldTotal);
                    if (!$returnAmount) {$returnAmount = "-"; $returnedPrice = "-";}


                    ?>
                    <tr class='<?=$color?>'>

        <!--########################################[            product        ]#######################################################################################################-->
                    <td nowrap class='product'>
                        <span class="default full_width ">
<!--                            &nbsp;<a href="#openModal" class="openModal" id="loc---><?php //echo $location_id; ?><!--"><span class="icon-file-alt orange"></span></a>&nbsp;-->
                            <a href='#' name='changeName'><? echo $products['name']; ?></a>
                        </span>
                        <span class="">
                        </span>
                        <span class="edit_on hidden">
                            <input id='<? echo $products['id']; ?>' name='product_id' type='text' class='four_sixth' value='<? echo $products['name']; ?>' title="">
                            <span class='edit_pan one_sixth'>
                                <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right '></span></a>
                            </span>
                        </span>
                    </td>
        <!--########################################[           /product        ]#######################################################################################################-->

        <!--########################################[            price (a)        ]#####################################################################################################-->
                    <td nowrap class='price'>
                        <span class="default">&#8362; <a href='#' name='changePrice'><? echo $price; ?></a></span>
                        <span class="edit_on hidden">&#8362;&nbsp;
                            <input id='<?=$products['id']?>' name='price' type='text' class='' size='1' value='<? echo $price; ?>' title="">
                            <span class='edit_pan'>
                                <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right '></span></a>
                            </span>
                        </span>
                    </td>
        <!--########################################[           /price (a)        ]#####################################################################################################-->


        <!--########################################[            Transactions        ]##################################################################################################-->
                    <?
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //           TRANSACTIONS (SUPPLY)
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    $transRes = get_all_product_recieves ($product_id, $location_id);
                    //if there were transactions this month:
                    if ($transRes) {
                        $transaction = mysqli_fetch_array($transRes);
                        echo "<td class='center transaction' style='max-width: 300px;' >
                                    <div class='toggle-wrapper four_sixth'>
                                              <a href='javascript:void(0)' class='toggle-title orange'><span class='icon-eye-open icon-large'></span></a>
                                              <div class='toggle-content'>
                                                <ul class='list tagcloud rnd8 font-small'>";

                        do{
                            $oper = "+";
                            if ($transaction['from_location']==0) {
                                $url = "view_supply.php";
                            }
                            else {
                                $url = "view_transaction.php";
                            }
                            if ($transaction["amount"] !=0) {
                                $transaction_amount = $transaction["amount"];
                                $transaction_amount=decimal_format($transaction_amount);
        ?>
                                <li>
                                    <a href="<?=$root?>pages/locations/locations.php?category_id=<?=$category_id?>&id=<?=$location_id?>&cmd=canceltransaction&transaction_id=<?=$transaction['transaction_id']?>&product_id=<?=$products['id']?>&amount=<?=$transaction_amount?>"><?=$oper?><span class="amount"><?=$transaction_amount?></span></a>
                                    <input type="hidden" name="transaction_id" value="<?=$transaction['transaction_id']?>">
                                    <input type="hidden" name="product_id" value="<?=$products['id']?>">
                                </li>
        <?
                            }
                        }while ($transaction = mysqli_fetch_array($transRes));
                        echo "</ul>
                            </div></div>
                            <div class='hidden inputDiv'>
                                        <input id='" . $products['id'] . "' name='transaction' type='text' class='' size='5' value='' title=''>
                                <span class='addSupply'>
                                    <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                    <a href='#' class='cancel font-medium'><span class='icon-remove fl-right'></span></a>
                                </span>
                                <span class='editSupply'>
                                    <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                    <a href='#' class='cancel font-medium'><span class='icon-remove fl-right '></span></a>
                                </span>
                            </div>
                            <div class='two_sixth add_delete'>
                                    <a href='#' name='addSupply'><span class='icon-plus'></span></a>&nbsp;|
                                    <a href='../transactions/delete_transactions.php?product_id=" . $products['id'] . "&location_id=" . $location_id . "&category_id=" . $category_id . "'><span class='icon-remove red'></span></a>
                            </div>
                        </td> <!-- /TD transactions -->";
                    }
                    else{
                        ?>
                        <td class='center transaction' nowrap>
                            <div class='four_sixth toggle-wrapper'>-</div>
                            <div class='hidden inputDiv'>
                                <input id='<? echo $products['id']; ?>' name='transaction' type='text' class='' size='5' value='' title="">
                                <span class='addSupply'>
                                    <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                    <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right red'></span></a>
                                </span>
                            </div>
                            <div class='two_sixth add_delete' >
                                <a href='#' name='addSupply'><span class='icon-plus'></span></a>
                            </div>

                        </td> <!-- transactions -->
                    <?
                    }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //              AMOUNT (x) + RECEIVED (a*x)
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


                    echo" <td class='amount center'>".$amount."</td> <!-- amount (x) -->
                    <td class='received left'>&#8362; ".$received."</td> <!-- received (a*x) -->";


        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //           Returns
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    $transRes = get_all_product_returns ($product_id, $location_id);
                    //if there were transactions this month:
                    if ($transRes) {
                        $transaction = mysqli_fetch_array($transRes);
                        echo "<td class='center return' style='max-width: 300px;' >
                                    <div class='toggle-wrapper four_sixth'>
                                              <a href='javascript:void(0)' class='toggle-title orange'><span class='icon-eye-open icon-large'></span></a>
                                              <div class='toggle-content'>
                                                <ul class='list tagcloud rnd8 font-small'>";

                        do{
                            $oper = "-";
                            if ($transaction['from_location']==0) {
                                $url = "view_supply.php";
                            }
                            else {
                                $url = "view_transaction.php";
                            }
                            //$link = make_link($url,"?transaction_id=".$transaction["transaction_id"], $oper." ".$transaction["amount"]);
                            //$link = make_link("#","", $oper." ".$transaction["amount"]);
                            if ($transaction["amount"] !=0) {
                                $transaction_amount = $transaction["amount"];
                                $transaction_amount = decimal_format($transaction_amount);
                                ?>
                                    <li>
                                        <a href="<?=$root?>pages/locations/locations.php?category_id=<?=$category_id?>&id=<?=$location_id?>&cmd=canceltransaction&transaction_id=<?=$transaction['transaction_id']?>&product_id=<?=$products['id']?>"><?=$oper?><span class="amount"><?=$transaction_amount?></span></a>
                                        <input type="hidden" name="transaction_id" value="<?=$transaction['transaction_id']?>">
                                        <input type="hidden" name="product_id" value="<?=$products['id']?>">
                                    </li>
                                <?
                            }
                        }while ($transaction = mysqli_fetch_array($transRes));
                        $thisProductReturnAmount = get_product_return_amount($product_id, $location_id);
                        echo "</ul>
                            <input type='hidden' name='returnAmount' value='".$thisProductReturnAmount."'>
                            </div></div>
                            <div class='hidden inputDiv'>
                                <input id='" . $products['id'] . "' name='transaction' type='text' class='' size='5' value='' title=''>
                                <span class='addSupply'>
                                    <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                    <a href='#' class='cancel font-medium'><span class='icon-remove fl-right '></span></a>
                                </span>
                                <span class='editSupply'>
                                    <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                    <a href='#' class='cancel font-medium'><span class='icon-remove fl-right '></span></a>
                                </span>
                            </div>
                            <div class='two_sixth add_delete'>
                                    <a href='#' name='addSupply'><span class='icon-plus'></span></a>&nbsp;|
                                    <a href='".$root."pages/transactions/delete_returns.php?product_id=" . $products['id'] . "&location_id=" . $location_id . "&category_id=" . $category_id . "'><span class='icon-remove red'></span></a>
                            </div>
                        </td> <!-- /TD transactions -->";
                    }
                    else{

                        ?>
                        <td class='center return' nowrap>
                            <div class='four_sixth toggle-wrapper'>-<input type='hidden' name='returnAmount' value='0'></div>
                            <div class='hidden inputDiv'>
                                <input id='<? echo $products['id']; ?>' name='transaction' type='text' class='' size='5' value='' title="">
                                <span class='addSupply'>
                                    <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                    <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right '></span></a>
                                </span>
                            </div>
                            <div class='two_sixth add_delete' >
                                <a href='#' name='addSupply'><span class='icon-plus'></span></a>
                            </div>

                        </td> <!-- transactions -->
                    <?
                    }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //          RETURNED PRICE
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



                    echo "<td class='left'>&#8362; ".$returnPrice."</td> <!-- returned price (a*z) -->";


        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //          AMOUNT LEFT
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    ?>

                    <!--  <td class='center amountLeft'>".$amountLeft."</td> <!-- last count (y) -->

                    <td nowrap class='amountLeft center'>
                        <span class="default"><a href='#' name='changeAmountLeft'><? echo $amountLeft; ?></a></span>
                                    <span class="edit_on hidden">&nbsp;
                                        <input id='<? echo $products['id']; ?>' name='amountLeft' type='text' class='' size='1' value='<? echo $amountLeft; ?>' title="">
                                        <span class='edit_pan'>
                                            <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                            <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right '></span></a>
                                        </span>
                                    </span>
                    </td>

                    <?
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //          SOLD TOTAL
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    if ($soldTotal <0 ) {$soldTotal = 0;}
                    echo "<td class='left'>₪ ".$soldTotal."</td> <!-- sold total [(x-z-y)*a] -->
                                ";
                    if($color == "light"){
                        $color = "dark";
                    }
                    else {
                        $color = "light";
                    }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //          DELETE
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    ?>

                    <td nowrap class='delete center'>
                        <span class="">
                            <a href='<?=$deleteURL."&product_id=".$products['id']?>' class="delete font-medium left5 right10" >
                                <span class='icon-remove fl-right red '></span></a>
                        </span>
                    </td>
                    </tr>
                    <?
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //          END OF TABLE
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                }while ($products = mysqli_fetch_array($prodResult));

            }
            else {

                echo ("<div class='alert-msg warning'>Категория пока что пуста<a class='close' href='#'>X</a></div>");
            }
        ?>
        </tbody>

    </table>

    <!--########################################[            add product        ]#######################################################################################################-->
    <div class="full_width">
        <form name="add_product" method="post" action="locations.php" >
            <div class="form-input clear">
                <label class="one_third first" for="name">Название <span class="required">*</span><br>
                    <input type="text" name="name" id="name" value="" size="22">
                </label>

                <input type="hidden" name="location_id" value="<?=$location_id?>">
                <input type="hidden" name="category_id" value="<?=$category_id?>">
                <label class="one_sixth" for="name">Количество<br>
                    <input type="text" name="amount" value="" size="22">
                </label>
                <label class="one_sixth" for="price">Цена в &#8362;<br>
                    <input type="text" name="price" id="price" value="" size="22" class="fl-left">
                </label>
                <label  class="one_sixth" for="submit"><br>
                    <input class="button orange"  type="submit" name="submit" value="Добавить Продукт ">
                </label>
            </div>
        </form>
    </div>

    <div id="openModal" class="modalDialog">
        <div>
            <a href="#close" title="Close" class="close">X</a>
            <h2>Modal Box</h2>
            <p>This is a sample modal box that can be created using the powers of CSS3.</p>
            <p>You could do a lot of things here like have a pop-up ad that shows when your website loads, or create a login/register form for users.</p>
        </div>
    </div>


</div>
<!-- / Tab Content -->
</div>
</div>
<!-- ################################################################################################ -->
</div>
<!-- ################################################################################################ -->
<div class="clear"></div>



<!-- Footer -->


<? include "../blocks/footer.php"; ?>
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