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
    elseif (isset($_POST['id'])) {$location_id = $_POST['id'];}
    if (!isset($location_id)||!location_exists($location_id)) {$location_id = 1;}

    $today = date("Y-m-d");
    $title_date = normalize_date($today);

    $location_name = get_location_name ($location_id);

    $today = date('Y-m-d');
    $allDates = get_supply_dates_between_ratio($location_id, $today, $today);
    //$allProducts = get_products_received_today_per_location($location_id);
    #endregion
?>
<?

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //  SECTION 2: CHANGE DATE
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    #region  CHANGE DATE
    if (isset($_POST['from_date'])) {$from_date = $_POST['from_date'];}
    else {$from_date = date("Y-m-d");}
    if (isset($_POST['to_date'])) {$to_date = $_POST['to_date'];}
    else {$to_date = date("Y-m-d");}
    if (isset($from_date)&&isset($to_date)) {
        $allDates = get_supply_dates_between_ratio($location_id, $from_date, $to_date);
        //$allProducts = get_products_received_by_day_per_location ($location_id, $from_date, $to_date);
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
<body class="">

<!-- ################################################################################################ -->

<? include("../blocks/header.php"); ?>


<? include("../blocks/nav.php"); ?>

<!-- ################################################################################################ -->


<!-- content -->
<div class="wrapper row3">
<div id="container">



<div id="content" class="full_width">

<!-- ################################################################################################ -->
<!-- ###                  TITLE                                                                   ### -->
<!-- ################################################################################################ -->
<section class="">
    <input type="hidden" id="location_id" value="<?=$location_id?>">

    <h1 class="title font-xl"><span class="icon-building"></span> <strong><? echo $location_name; ?></strong> <span class="font-smaller">(<?=$title_date?>)</span></h1>

    <form method="post" action="print.php">
        <input type="hidden" id="id" name="id"  value="<?=$location_id?>">
        <p >
            <label for="from_date"  class="one_quarter first">
                От даты:
                <input type="date" name="from_date" id="from_date" value="<?=$from_date?>">
            </label>
            <label for=to_date" class="one_quarter">
                По дату:
                <input type="date" name="to_date" id="to_date" value="<?=$to_date?>" max="<?=date("Y-m-d")?>">
            </label>
            <span class="one_quarter push_top10"><input type="submit" value="Изменить дату " class=" button medium orange pushTop10"></span>
            <span class="clear"></span>

        </p>
    </form>
    <p class="title font-large"><a href="print_it.php?id=<?=$location_id?>&from_date=<?=$from_date?>&to_date=<?=$to_date?>" target="_blank"><span class="icon-print"></span> Принтировать</a></p>


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

            <th>Продукт</th>
            <th>Цена</th>
            <th nowrap>К-во<br>Шт. / кг.</th>
            <th nowrap>Получено <br>на &sum;</th>
        </tr>
        </thead>


        <!-- ################################################################################################ -->
        <!-- ###                  TABLE BODY                                                              ### -->
        <!-- ################################################################################################ -->
        <tbody>
        <?

            $color = "light";

            // if there was data

            if (!empty($allDates)) {
                $date = mysqli_fetch_array($allDates);

                $totalTotalAmount = 0;
                $totalTotalPrice = 0;
                do {
                    $allProducts = get_products_received_by_day_per_location ($location_id, $date['date'], $date['date']);
                    $products = mysqli_fetch_array ($allProducts);
                    $thisLocation = $location_id;

                    $totalAmount = 0;
                    $totalPrice = 0;
                    echo '
                    
                        <tr>
                            <td colspan="4"><h3>' . normalize_date($date['date']) . '</h3></td>
                        </tr>
                    ';
                    do {
                        $productName = $products['name'];
                        $productPrice = $products['price'];
                        $productAmount = $products['amount'];
                        $receivedSum = $productPrice * $productAmount;

                        // DAILY TOTAL
                        $totalAmount += $productAmount;
                        $totalPrice += $receivedSum;

                        // PERIOD TOTAL
                        $totalTotalAmount += $productAmount;
                        $totalTotalPrice += $receivedSum;
                        echo '
                            <tr class=' . $color . '>
                                <td nowrap class="product">' . $productName . '</td>
                                <td nowrap class="price">&#8362; ' . $productPrice . '</td>
                                <td class="amount center">' . $productAmount . '</td> <!-- amount (x) -->
                                <td class="received left">&#8362; ' . $receivedSum . '</td> <!-- received (a*x) -->
                    ';
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                        //          END OF TABLE
                        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                        if ($color == "light") {
                            $color = "dark";
                        } else {
                            $color = "light";
                        }

                    } while ($products = mysqli_fetch_array ($allProducts));
                    echo '
    
                        <tr><td colspan="4"><hr></td></tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td class="bold font-medium">Всего:</td>
                            <td class="bold font-medium center">' . number_format($totalAmount,2) . '</td>
                            <td class="bold font-medium">&#8362;' . number_format($totalPrice,2) . '</td>
                        </tr>
                    ';
                }while ($date = mysqli_fetch_array($allDates));
                echo '
    
                    <tr><td colspan="4"><hr></td></tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td class="bold font-small"><h4>Всего:</h4></td>
                        <td class="bold font-small center"><h4>' . number_format($totalTotalAmount,2) . '</h4></td>
                        <td class="bold font-small"><h4>&#8362;' . number_format($totalTotalPrice,2) . '</h2></td>
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