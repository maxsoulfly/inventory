<?  //header('Content-type: text/html; charset=windows-1251');
    include("../blocks/db.php");
    include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
?>
<?
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //  SECTION 1: SET PAGE
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    #region  SET PAGE
    $page = "print";
    if (isset($_GET['id'])) {$location_id = $_GET['id'];}
    if (!isset($location_id)||!location_exists($location_id)) {$location_id = 1;}

    $today = date("Y-m-d");
    $title_date = normalize_date($today);

    $location_name = get_location_name ($location_id);

    $allProducts = get_products_received_today_per_location($location_id);


    if (isset($_GET['from_date'])) {$from_date = $_GET['from_date'];}
    else {$from_date = date("Y-m-d");}
    if (isset($_GET['to_date'])) {$to_date = $_GET['to_date'];}
    else {$to_date = date("Y-m-d");}

    if (isset($from_date)&&isset($to_date)) {
        $allProducts = get_products_received_by_day_per_location ($location_id, $from_date, $to_date);
    }

    // SET THE DATE IN THE TITLE:
    if ($from_date!=$today){
        $title_date = normalize_date($from_date)." - ".normalize_date($to_date);
    }

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
<body class="" onload="window.print()">


<!-- content -->
<div class="wrapper row3">
<div id="container">



<div id="content" class="full_width">

<!-- ################################################################################################ -->
<!-- ###                  TITLE                                                                   ### -->
<!-- ################################################################################################ -->
<section class="">
    <input type="hidden" id="location_id" value="<? echo $location_id; ?>">

    <h1 class="title font-xl"><span class="icon-building"></span> <strong><? echo $location_name; ?></strong></h1>
    <h1>Дата: <?=$title_date?></h1>


</section>


<div class="clear push50">


    <!-- ################################################################################################ -->
    <!-- ###                  TABLE                                                                   ### -->
    <!-- ################################################################################################ -->
    <table class="list-table  font-medium">

        <!-- ################################################################################################ -->
        <!-- ###                  TABLE HEADER                                                            ### -->
        <!-- ################################################################################################ -->

        <thead>
        <tr>

            <td class="bold font-large">Продукт</td>
            <td class="bold font-large">Цена</td>
            <td class="bold font-large">Шт. / кг.</td>
            <td class="bold font-large">Получено на &sum;</td>
        </tr>
        </thead>



        <!-- ################################################################################################ -->
        <!-- ###                  TABLE BODY                                                              ### -->
        <!-- ################################################################################################ -->
        <tbody>
        <tr><td colspan="4"><hr></td></tr>
        <?

            $color = "light";

            // if there was data

            if ($allProducts) {
                $products = mysqli_fetch_array($allProducts);
                $thisLocation = $location_id;

                $totalAmount = 0;
                $totalPrice = 0;
                do{
                    $productName = $products['name'];
                    $productPrice = $products['price'];
                    $productAmount = $products['amount'];
                    $receivedSum = $productPrice*$productAmount;

                    $totalAmount += $productAmount;
                    $totalPrice += $receivedSum;
                    echo '
                            <tr class='.$color.'>
                                <td nowrap class="product">'.$productName.'</td>
                                <td nowrap class="price">&#8362; '.$productPrice.'</td>
                                <td class="amount">'.$productAmount.'</td> <!-- amount (x) -->
                                <td class="received left">&#8362; '.$receivedSum.'</td> <!-- received (a*x) -->
                            </tr>

                    ';
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //          END OF TABLE
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    if($color == "light"){
                        $color = "dark";
                    }
                    else {
                        $color = "light";
                    }

                }while ($products = mysqli_fetch_array($allProducts));

                echo '

                <tr><td colspan="4"><hr></td></tr>
                <tr>
                    <td>&nbsp;</td>
                    <td class="bold font-medium">Всего:</td>
                    <td class="bold font-medium">'.$totalAmount.'</td>
                    <td class="bold font-medium">&#8362;'.$totalPrice.'</td>
                </tr>
            ';
            }
            else {

                echo ("<div class='alert-msg warning'>В данный филиал продукты не переводились сегодня<a class='close' href='#'>X</a></div>");
            }
        ?>

        </tbody>

    </table>

</div>
<!-- / Tab Content -->
</div>
</div>
<!-- ################################################################################################ -->
</div>
<!-- ################################################################################################ -->
<div class="clear"></div>



<!-- Footer -->


<? include "../blocks/print_footer.php"; ?>
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