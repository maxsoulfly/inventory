<?  //header('Content-type: text/html; charset=windows-1251');
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	
	$page = "categories";
	
	if (isset($_GET['id'])) {$category_id = $_GET['id'];}
	if (!isset($category_id)) {$category_id = 1;}
	if(!category_exists($category_id)){$category_id = 1;}
	
	$category_name = get_category_name ($category_id);
	
	$currentDate = date("Y-m");
	
	//-------[ CATEGORIES ]--------------------------------------//
	//$catResult = get_all_categories ();
	
	//-------[ PRODUCTS ]--------------------------------------//
	//$catResult = get_all_products_orderby_category_id ();
?>
<!DOCTYPE html>
<html>
<head>
<script src="../blocks/src/jquery.sidebar.js"></script>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title><?=$category_name?></title>
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

      <section class="clear">
      	<div> <a id="toggle-right" class="hidden font-large" href="#"><span class="icon-reorder"></span></a></div>
        <br>
        <h1 class="title"><strong><span></span><? echo $category_name; ?></strong>
            <span class="edit-title">|&nbsp;<a href='#' id="editCategoryName" name="editCategoryName" ><span class='icon-pencil fl-right'></span></a>
			<? if (category_empty ($category_id)) { ?>
                    |&nbsp;<a href='delete_category.php?id=<? echo $category_id; ?>' ><span class='icon-remove fl-right red'></span></a>
            <? } ?>
            </span>
        </h1>
        
        <h1 class="title edit_on hidden"><span></span><strong><input id='<? echo $category_id; ?>' name='category_id' type='text' class='three_sixth font-small' style="padding-left: 2px; margin-top: -4px; font-weight:bold;"  value='<? echo $category_name; ?>' title=""></strong>
            <span class='edit_pan one_sixth'>
                <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right red'></span></a>
            </span>
        </h1>
      </section>
      <section class='push100'>
      
          
        <!-- ################################################################################################ -->
        
            <div class="tab-container ">
              <!-- Tab Content -->
                <table class="list-table">
                  <thead>
                    <tr>
                      <th>Филиал</th>
                      <th>Продукт</th>
                      <th>Цена<br>(a)</th>
                      <th nowrap>Операции <a href='../transactions/new_supply.php?id=1&&cat=<? echo $category_id; ?>'><span class='icon-plus'></span></a></th>
                      <th nowrap>К-во<br>Шт. / кг.<br>(x)</th>
                      <th nowrap>Получено на<br>&sum;<br>(a*x)</th>
                      <th nowrap>Возвраты (z) <a href='../transactions/new_return.php?id=1'><span class='icon-plus'></span></a><br></th>
                      <th nowrap>Возвраты на<br>&sum;<br>(a*z)</th>
                      <th>Осталось<br>(y)</th>
                      <th nowrap>Продано<br>[(x-z-y)*a]</th>
                    </tr>
                  </thead>
                  <tbody>
    <?
        $prodResult = get_all_products_and_locations_from_category ($category_id);
        $color = "light";
		
		// if there was data
		if (!$prodResult) {
			
			echo ("<div class='alert-msg warning'>Категория пока что пуста<a class='close' href='#'>X</a></div>");
		}
		else{
			$products = mysqli_fetch_array($prodResult);
			do{
                // I use 2 variables for 1 value because I copies the code from [ locations.php ] and there are some bugs because of the difference
                $thisLocation = $products['location_id'];
                $location_id = $thisLocation;

                $price = $products['price'];
                $product_id = $products['product_id'];
                $location_name = get_location_name($thisLocation);
                $returnAmount = get_location_product_return_amount ($product_id, $thisLocation);
                $returnPrice = $returnAmount*$price;
                if (!$returnAmount) {$returnAmount = "-"; $returnedPrice = "-";}

                $thisLocProdData = get_location_product_details ($thisLocation, $product_id);
                $amount = get_location_product_amount ($product_id, $thisLocation);
                if (!$amount) {
                    $amount = 0;
                }
                $amountLeft = get_product_amount_per_location ($product_id, $thisLocation);
                $received = $amount*$price;

                // reset

                $returnAmount = get_location_product_return_amount ($product_id, $thisLocation);
                $returnPrice = $returnAmount*$price;
                $soldTotal = ($amount-$returnAmount-$amountLeft)*$price;

                echo "<tr class='$color'>
					
						  <td><a href='../locations/locations.php?id=" .$thisLocation."'>".$location_name."</a></td> <!-- id -->";
?>
        						<input type="hidden" id="location_id" value="<? echo $thisLocation; ?>">
						  
<!--########################################[            product        ]#######################################################################################################-->
                    <td nowrap class='product'>
                    	<span class="default five_sixth"><a href='#' name='changeName'><? echo $products['name']; ?></a></span>
                        <span class="delete-product one_sixth">
                        	<a href='../products/delete_product.php?id=<? echo $product_id."&&sourcePage=".$page."&&idParam=".$location_id; ?>' class="delete font-medium left5 right10" >
                                			<span class='icon-remove fl-right red '></span></a>
                        </span>
                    	<span class="edit_on hidden">
                        	<input id='<? echo $product_id; ?>' name='product_id' type='text' class='four_sixth' value='<? echo $products['name']; ?>' title="">
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
                        	<input id='<? echo $product_id; ?>' name='price' type='text' class='' size='1' value='<? echo $price; ?>' title="">
                            <span class='edit_pan'>
                                <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right red'></span></a>
                            </span>
                        </span>
                    </td>
<!--########################################[           /price (a)        ]#####################################################################################################-->

<?
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
          
						$transRes = get_all_product_recieves ($product_id, $thisLocation);
						//if there were transactions this month:
						if ($transRes) {
							$transaction = mysqli_fetch_array($transRes);
							echo "<td class='center'><div class='toggle-wrapper'><a href='javascript:void(0)' class='toggle-title orange'><span class='icon-eye-open icon-large'></span></a>
									  <div class='toggle-content'><ul class='list tagcloud rnd8'>";
							do{
								  if ($transaction['to_location']==$thisLocation) {
									$oper = "+";
								  }
								  if ($transaction['from_location']==$thisLocation) {
									 $oper = "-";
								  }
								  if ($transaction["amount"] !=0) {
							  	  	echo "<li><a href='../transactions/view_transaction.php?transaction_id=" .$transaction["transaction_id"]."'>".$oper." ".$transaction["amount"]."</a></li>";
								  }
							}while ($transaction = mysqli_fetch_array($transRes));
							echo "</ul></div></div></td> <!-- transactions -->";
						}
						else{
							echo "<td class='center'>-</td> <!-- transactions -->";
						}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


					//echo  show_error($returnAmount,"info");
					if ($returnAmount == "-") {
						$returnLink=$returnAmount;
					}
					else {
						$returnLink = make_link("view_return.php","?product_id=".$product_id."&location_id=".$thisLocation,$returnAmount);
					}
					echo" <td class='center'>".$amount."</td> <!-- amount (x) -->
						  <td class='left'>&#8362; ".$received."</td> <!-- received (a*x) -->";
						  
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
						 
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

						  //<td class='center'>".$returnLink."</td> <!-- returns (z) -->
						  
						  
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
						
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				  
						  
						  
					echo "<td class='left'>&#8362; ".$returnPrice."</td> <!-- returned price (a*z) -->";
					
					
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////				
?>					
					
						<!--  <td class='center amountLeft'>".$amountLeft."</td> <!-- last count (y) -->
						  
						<td nowrap class='amountLeft center'>
                            <span class="default"><a href='#' name='changeAmountLeft'><? echo $amountLeft; ?></a></span>
                            <span class="edit_on hidden">&nbsp;
                                <input id='<? echo $product_id; ?>' name='amountLeft' type='text' class='' size='1' value='<? echo $amountLeft; ?>' title="">
                                <span class='edit_pan'>
                                    <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                    <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right red'></span></a>
                                </span>
                            </span>
                        </td>  
						  
<?	
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
		?>
					  </tbody>
					</table>
				  </div>
              <!-- / Tab Content -->
                    <div class="one_third push100">
            			<p class=" font-medium "><a href='../products/new_product.php?category_id=<? echo $category_id; ?>&page=<? echo $page; ?>'><span class="icon-plus"></span>&nbsp;&nbsp;Добавить Новый Продукт</a></p>
                    </div>
          <!-- ################################################################################################ -->
          </section>
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