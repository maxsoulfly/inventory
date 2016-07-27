<?  //header('Content-type: text/html; charset=windows-1251');
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	$page = "inventory_count";
	if (isset($_GET['location_id'])) {$location_id = $_GET['location_id'];}
	if (!isset($location_id)) {$location_id = 1;}
	//$location_id = 1;
	
	$location_name = get_location_name ($location_id, $db);
		
	
	//-------[ CATEGORIES ]--------------------------------------//
	//$catresult = get_all_categories ();
	
	//-------[ PRODUCTS ]--------------------------------------//
	//$prodresult = get_all_products_orderby_category_id ();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title>Инвентарный Подсчет</title>
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
    <form action="update_inventory.php" method="post" name="update_inventory">
	<input type="hidden" id="page" name="page" value="<? echo $page.".php"; ?>">
      <section class="">
      	<div> <a id="toggle-right" class="hidden font-large" href="#"><span class="icon-reorder"></span></a></div>
        
        <br>
        
        <h1 class="font-large"><span class="icon-edit icon-large"></span> Инвентарный Подсчет</h1>
        
<!--        <h1 class="font-medium"><span class="icon-signin"></span> В Филиал:<br><span class="font-large"><strong><? echo $location_name; ?></strong></span></h1>
        <input type="hidden" name="to_location" id="to_location" value="<? echo $location_id; ?>" size="22" class="fl-left">
-->        
        
            <!-- ################################################################################################ -->
        <div class="form-input clear">
              <h1 class="font-medium"><span class="fl_left font-large"><span class="icon-building"></span> Филиал</span> 
              	<select name='location_id' id='location_id' class='one_third font-medium'>
<?	
			$res = get_all_locations($db);
			$row = mysqli_fetch_array($res);
			
			do{
				echo "<option value='".$row['id']."' ";
				if ($row['id']==$location_id) { echo "selected"; }
				echo ">".$row['name']."</option>";
				
			}while ($row = mysqli_fetch_array($res));
?>
				</select></h1>
        </div>
    <!-- ################################################################################################ -->
        
      </section>
      
          
        <!-- ################################################################################################ -->
        
<!--        <h2>Категории</h2>
-->        <div class="clear push50">
          <!-- ################################################################################################ -->

          <div class="tab-wrapper clear">
            <div class="four_fives first">
<?
        $catresult = get_all_categories ();
        $categories = mysqli_fetch_array($catresult);
        do{
            echo "<div class='toggle-wrapper'><a href='javascript:void(0)' class='toggle-title orange'><span>".$categories['name']."</span></a>";
?>
                  	<div class="toggle-content" style="display: none;">
						<? echo ("<div id='tab-".$categories['id']."' class='tab-content clear';>");?>
                             <input type="hidden" name="from_location" id="from_location" value="0" size="22" class="fl-left">
                          <div class="form-input clear push10">
                            <h2 class="two_sixth first font-medium"><strong>Название:</strong></h2>
                                
                            <h2 class="one_sixth font-medium"><strong>Нынешное:</strong></h2>
                            <h2 class="one_sixth font-medium"><strong>Новый Подсчет: </strong></h2>
                        </div><br>
                        </div>
                        
                        
				<?
                        $prodresult = get_all_products_from_category ($categories['id'],$db);
                        
                        // if there was data
                        
                        if ($prodresult) {
                            $products = mysqli_fetch_array($prodresult);
                            do{
                                $amount = get_product_amount_per_location ($products['id'], $location_id, $db);
                ?>				
                                <div class="form-input clear push10">
                                    <p class="two_sixth first"><? echo $products['id']; ?>. <a href='view_product.php?id=<? echo $products['id']; ?>'><? echo $products['name']; ?></a></p>
                                    
                                    <p class="one_sixth font-small"><? echo $amount; ?></p>
                                    <p class="one_sixth">
                                        <input type="hidden" name="product_id[]" value="<? echo $products['id']; ?>" size="22" class="fl-left">
                                        <input type="text" name="new_amount[]" value="" size="22" class="fl-left">
                                    </p>
                                </div>
                                
                <?					
                            }while ($products = mysqli_fetch_array($prodresult));
                            
                        }
                        else {
                            $error = "Категория пока что пуста.";
                            echo show_error($error, "warning", 1);
                        }
                        ?>       
                                </div> </div>
                    <?
                
                        }while($categories = mysqli_fetch_array($catresult));
                    ?>  
                    <br><br>
                    <p class="clear"><input type="submit" value="Сохранить Изменения">&nbsp;&nbsp;&nbsp;|<a class="" href="javascript:history.go(-1)">&nbsp; Отменить</a></p>
                
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