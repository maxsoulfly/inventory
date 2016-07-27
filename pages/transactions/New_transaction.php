<?  //header('Content-type: text/html; charset=windows-1251');
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset($db, 'utf8');
	
	$page = "new_transaction";
	if (isset($_GET['from_location'])) {$from_location = $_GET['from_location'];}
    else if (isset($_POST['from_location'])) {$from_location = $_POST['from_location'];}
	if (!isset($from_location)) {$from_location = get_first_location_id();}
	if (isset($_GET['to_location'])) {$to_location = $_GET['to_location'];}
    else if (isset($_POST['to_location'])) {$from_location = $_POST['to_location'];}
	if (!isset($to_location)) {$to_location = get_last_location_id();}
	
	$location_name = get_location_name ($from_location, $db);
	
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
        //echo show_error(isSupplyList_open ($location_id), "info",1);
/*
        if (!isTransfersList_open($to_location)) {
            $thisTransferID = add_transaction ($from_location,$to_location);
        }
        else {
            $thisTransferID = get_last_supply_id ($to_location);
            echo "<meta http-equiv='refresh' content='0;URL=edit_transaction.php?transaction_id=".$thisTransferID."&to_location=".$to_location."&sourcePage=".$page."'>";
        }

        $transaction_id = $thisTransferID;
*/
    ?>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Передача Товаров</title>
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
      
      
          
        <!-- ################################################################################################ -->
        
<!--        <h2>Категории</h2>
-->        <div class="clear push50">
          <!-- ################################################################################################ -->
<form action="add_transaction.php" method="post" name="add_amounts">
    <input type="hidden" name="page" id="page" value="<?=$page?>">
<section class="">
      	<div> <a id="toggle-right" class="hidden font-large" href="#"><span class="icon-reorder"></span></a></div>
        
        <br>
        <div class="form-input clear">
        	<h1 class="font-large"><span class="icon-truck icon-large"></span> Передача Товаров</h1>
        
        
    <!-- ################################################################################################ -->
              <h1 class="font-medium"><span class="icon-signout"></span> <label class="" for="from_location">Из Филиала<br>
              	<select name='from_location' id='from_location' class='first two_third font-medium'>
<?	
			$res = get_all_locations($db);
			$row = mysqli_fetch_array($res);
			
			do{
				echo "<option value='".$row['id']."' ";
				if ($row['id']==$from_location) { echo "selected"; }
				echo ">".$row['name']."</option>";
				
			}while ($row = mysqli_fetch_array($res));
?>
				</select></label></h1>
        </div><br>
    <!-- ################################################################################################ -->
    
        
    <!-- ################################################################################################ -->
        <div class="form-input clear">
              <h1 class="font-medium"><span class="icon-signin"></span> <label class="" for="to_location">В Филиал<br>
              	<select name='to_location' id='to_location' class='first two_third font-medium'>
<?	
			$res = get_all_locations($db);
			$row = mysqli_fetch_array($res);
			
			do{
                if ($row['id']==$from_location) {
                }
                else{
                    echo "<option value='" . $row['id'] . "' ";
                    if ($row['id'] == $to_location) {
                        echo "selected";
                    }
                    echo ">" . $row['name'] . "</option>";
                }

				
			}while ($row = mysqli_fetch_array($res));
?>
				</select></label></h1>
        </div>
    <!-- ################################################################################################ -->
      </section>
          <div class="tab-wrapper clear">
            <div class="four_fives first">
<?
        $catresult = get_all_categories ($db);
        $categories = mysqli_fetch_array($catresult);
        do{
            echo "<div class='toggle-wrapper'><a href='javascript:void(0)' class='toggle-title orange'><span>".$categories['name']."</span></a>";
?>
                  	<div class="toggle-content" style="display: none;">
						<? echo ("<div id='tab-".$categories['id']."' class='tab-content clear';>");?>
                          <div class="form-input clear push10">
                            <h2 class="two_sixth first font-medium"><strong>Название:</strong></h2>
                                
                            <h2 class="one_sixth font-medium"><strong>Количество: </strong></h2>
                        </div><br>
                        </div>
 
          <!-- ################################################################################################ -->
          <!-- ################################################################################################ -->                       
                        
				<?
                        $prodresult = get_all_products_from_category ($categories['id'],$db);
                        
                        // if there was data
                        
                        if ($prodresult) {
                            $products = mysqli_fetch_array($prodresult);
                            do{
                                $amount = get_product_amount_per_location ($products['id'], $from_location, $db);
                ?>				
                                <div class="form-input clear push10">
                                    <p class="two_sixth first"><? echo $products['id']; ?>. <a href='../products/view_product.php?id=<? echo $products['id']; ?>'><? echo $products['name']; ?></a></p>
                                    
                                    <p class="one_sixth">
                                        <input type="hidden" name="product_id[]" value="<? echo $products['id']; ?>" size="22" class="fl-left">
                                        <input type="text" name="amount[]" value="" size="22" class="fl-left">
                                    </p>
                                </div>
                                
                <?					
                            }while ($products = mysqli_fetch_array($prodresult));
                            
                        }
                        else {
                            
                            echo ("<div class='alert-msg warning'>Категория пока что пуста<a class='close' href='#'>X</a></div>");
                        }
                        ?>       
                                </div> </div>
                    <?
                
                        }while($categories = mysqli_fetch_array($catresult));
                    ?>
                    <p class="top40 pullBottom20 font-medium" ><input type="checkbox" id="mylabel" name="completed" value="1" title="" class="one_sixth first"> Закрыть форму и переправить товар</p>

                    <br><br>

                    <div id="form-bottom">
                        <ul>
                            <li><input class="button orange" type="submit" value="Сохранить" name="save"></li>
                            <li><input class="button white" type="button" value="Назад" name="back" onclick="location.href='<?=$backURL?>';"}></li>
                            <li><input class="button white" type="button" value="Отменить" name="delete" onclick="location.href='drop_this_transaction.php?transaction_id=<?=$transaction_id?>&sourcePage=<?=$backURL?>'"}></li>
                        </ul>
                    </div>
                </form>
            </div> 
          </div>
          <!-- ################################################################################################ -->
        </div>

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
<script src="../../layout/scripts/jquery-ui.min.js"><\/script>')</script>
<script>jQuery(document).ready(function($){ $('img').removeAttr('width height'); });</script>
<script src="../../layout/scripts/jquery-mobilemenu.min.js"></script>
<script src="../../layout/scripts/custom.js"></script>
</body>
</html>