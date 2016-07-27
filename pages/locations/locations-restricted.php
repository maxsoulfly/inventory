<?
    session_start();
header('Content-type: text/html; charset=utf-8');


// TODO UPLOAD THIS
//	if(empty($_SESSION['login_user']))	{
//		header('Location: index.php');
//	}

//	$db = mysql_connect ("localhost","inventoryuser","12345");
//	mysql_select_db("inventory",$db);


    include("../blocks/db-restricted.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');

?>
<?
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //  SECTION 1: SET PAGE
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    #region  SET PAGE
    $page = "locations";
    if (isset($_GET['id'])) {$location_id = $_GET['id'];}
    if (!isset($location_id)||!location_exists($location_id)) {$location_id = 1;}


    if (isset($_SESSION['location_id'])){$location_id = $_SESSION['location_id'];}

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

    #endregion
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Филиал: <? echo ($location_id.'. '.$location_name); ?></title>
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

<? include("../blocks/header.php"); ?>

<!-- ################################################################################################ -->


<!-- content -->
<div class="wrapper row3">
  <div id="container">



  <!-- ################################################################################################ -->
  <?  include("../blocks/sidebar-restricted.php"); ?>

  <!-- ################################################################################################ -->


  <div id="content" class="full_width">

  <!-- ################################################################################################ -->
  <!-- ###                  TITLE                                                                   ### -->
  <!-- ################################################################################################ -->
  <section class="">
      <h1 class="title font-xl"><span class="icon-building"></span> <strong><? echo $location_name; ?></strong> </h1>

      <div class="clear"></div>
      <h1 class="left10">
          <a href="#" class="btn btn-primary" data-action="toggle" data-side="left"><i class="icon-reorder"></i> Категории</a>
      </h1>

  </section>


  <div class="clear push50">

  <!-- ################################################################################################ -->
  <!-- ###                  CATEGORY TITLE                                                          ### -->
  <!-- ################################################################################################ -->


  <h1 class="title left10"><strong><?=$category_name?></strong></h1>


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
      <th>Цена<br>(a)</th><th nowrap>К-во<br>Шт. / кг.<br>(x)</th>
      <th nowrap>Получено <br>на &sum;<br>(a*x)</th>
      <th>Возвраты<br>(z)</th>
      <th nowrap>Возвраты <br>на &sum;<br>(a*z)</th>
      <th>Осталось<br>(y)</th>
      <th nowrap>Продано<br>[(x-z-y)*a]</th>
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
              $price 	= $thisLocProdData["price"];
              if ($price == "") $price = 0;
              $amountLeft = get_product_amount_per_location ($products['id'], $location_id);
              $received = $amount*$price;
              $product_id = $products['id'];

              // reset

              $returnAmount = get_location_product_return_amount ($product_id, $thisLocation);
              $returnPrice = $returnAmount*$price;
              $soldTotal = ($amount-$returnAmount-$amountLeft)*$price;
              if (!$returnAmount) {$returnAmount = "-"; $returnedPrice = "-";}
              if ($soldTotal <0 ) {$soldTotal = 0;}

              ?>
              <tr class='<?=$color?>'>

                    <td nowrap class='product'><span class="default full_width "><?=$products['name']?></span></td><!-- product -->
                    <td nowrap class='price'><span class="default">&#8362; <?=$price?></span> </td><!-- price -->
                    <td class='amount center'><?=$amount?></td> <!-- amount (x) -->
                    <td class='received left'>&#8362; <?=$received?></td> <!-- received (a*x) -->
                    <td class='received center'><?=$returnAmount?></td> <!-- returned (z) -->
                    <td class='left'>&#8362; <?=$returnPrice?></td> <!-- returned price (a*z) -->
                    <td class='center amountLeft'><?=$amountLeft?></td> <!-- last count (y) -->
                    <td class='left'>&#8362; <?=$soldTotal?></td> <!-- sold total [(x-z-y)*a] -->


                    <? if($color == "light"){ $color = "dark"; } else {  $color = "light"; } ?>
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


  </div>
  <!-- / Tab Content -->
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
<script>window.jQuery || document.write('<script src="../layout/scripts/jquery-latest.min.js"><\/script>\
<script src="../layout/scripts/jquery-ui.min.js"><\/script>')</script>
<script>jQuery(document).ready(function($){ $('img').removeAttr('width height'); });</script>
<script src="../../layout/scripts/jquery-mobilemenu.min.js"></script>
<script src="../../layout/scripts/custom.js"></script>
</body>
</html>