<?  //header('Content-type: text/html; charset=windows-1251');
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	
	if (isset($_GET['id'])) {$product_id = $_GET['id'];}
	
?>
<!DOCTYPE html>
<html>
<head>
<title>Редактирование Продукта</title>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
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

<div class="wrapper row1">
  <?  include("../blocks/header.php"); ?>
</div>

<!-- ################################################################################################ -->
<div class="wrapper row2">
  <?  include("../blocks/nav.php"); ?>
</div>


<!-- content -->
<div class="wrapper row3">
  <div id="container">
    <!-- ################################################################################################ -->
  	<?  include("../blocks/sidebar.php"); ?>
    
    <!-- ################################################################################################ -->
<?
	if (!isset($product_id) || !product_exists($product_id, $db)){
		show_404 ();
	}
	else {
		$product = get_product_details($product_id, $db);
		$category_id = $product['category_id'];
		
?>
    <div class="three_quarter">
      <section class="clear">
      	<h1><em class="icon-pencil"></em> Редактирование Продукта</h1>
        
        <form action="update_product.php" method="post" name="new_Product">
        	  <div class="form-input clear">
              	
                <input type="hidden" name="id" id="id" value="<? echo $product['id']; ?>" size="22">
                <label class="one_third first" for="name">Название <span class="required">*</span><br>
                  <input type="text" name="name" id="name" value="<? echo $product['name']; ?>" size="22">
                </label>
              </div><br>
              <div class="form-input clear">
              <p><label class="" for="category_id">В категорию <span class="required">*</span><br>
              	<select name='category_id' id='category_id' class='first one_third font-medium'>
<?	
			$res = get_all_categories($db);
			$row = mysqli_fetch_array($res);
			
			do{
				echo "<option value='".$row['id']."' ";
				if ($row['id']==$category_id) { echo "selected"; }
				echo ">".$row['name']."</option>";

			}while ($row = mysqli_fetch_array($res));
?>
				</select></label></p>
                
              </div><br>
              <div class="form-input clear push20">
                <label class="one_fifth first" for="price">Цена в &#8362; <span class="required">*</span><br>
                  <input type="text" name="price" id="price" value="<? echo $product['price']; ?>" size="22">
                </label>
              </div><br>
             
                <div class="push40 clear"></div>
                <p class="clear"><input type="submit" value="Сохранить Изменения">&nbsp;&nbsp;&nbsp;|<a class="" href="javascript:history.go(-1)">&nbsp; Отменить</a></p>
<!--                <input type="button" value="Отменить" id="cancel" onClick="">
-->              </p>
        </form>
<?
	}
?>
      </section>
    </div>
    <!-- ################################################################################################ -->
    
    <!-- ################################################################################################ -->
    <div class="clear"></div>
  </div>
</div>
<!-- Footer -->
<div class="wrapper row4">
  <?  include("../blocks/footer.php"); ?>
</div>
<!-- Scripts -->
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script>window.jQuery || document.write('<script src="../../layout/scripts/jquery-latest.min.js"><\/script>\
<script src="../../layout/scripts/jquery-ui.min.js"><\/script>')</script>
<script>jQuery(document).ready(function($){ $('img').removeAttr('width height'); });</script>
<script src="../../layout/scripts/jquery-mobilemenu.min.js"></script>
<script src="../../layout/scripts/custom.js"></script>

<script>
	$('#cancel').click(function() {
    window.location.href = '/some/new/page';
    return false;
});
</script>
</body>
</html>