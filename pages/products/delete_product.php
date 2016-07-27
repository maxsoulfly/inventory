<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
?>
<?
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    //  SECTION 1: DROP PRODUCT
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////
    if (isset($_POST['id'])) {
        $product_id = $_POST['id'];
        if (isset($_POST['url'])) {$url = $_POST['url']; }
        if (isset($product_id)) {
            /* all ok -> sql_query */
            $error = delete_product ($product_id, $db);

            echo "<meta http-equiv='refresh' content='0;URL=" . $url . "'>";
            exit ();
        }
    }
?>
<?
	if (isset($_GET['id'])) {
        $product_id = $_GET['id'];
        if (isset($_GET['url'])) {$url = $_GET['url']; }
    }
	
?>
<!DOCTYPE html>
<html>
<head>
<title>Удаление Категории</title>
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
    
<?
	
	if (!isset($product_id) || !product_exists ($product_id, $db)){
		
		show_404();
	}
	else {
?>
	<div class="three_quarter">
      <section class="clear">
<?
        //$res = mysqli_query($db, "SELECT * FROM products WHERE 'product_id' = '$product_id'");
        $product = get_product_details($product_id);
		if (!$product) {
			$error = show_error ("<p>Ошибка при получении данных из БД. Пожалуйста, сообщите администратору по support@freeurmind.net <br> <STRONG> Код ошибки:</strong></p>".mysqli_error($db));
		}
		else {
?>
			<h1><em class="icon-remove"></em> Вы Уверены?</h1>
            <p class="font-large">Вы собираетесь стереть "<strong><? echo $product['name']; ?></strong>", Вы Уверены?</p>
            <?
				echo show_error ("<p class='font-medium'>Вся информация о продукте будет потеряна.</p>","warning", 0);
			?>
        <form action="delete_product.php" method="post" name="delete_product">
        	  <div class="form-input clear">
                  <input type="hidden" name="id" id="id" value="<? echo $product['id']; ?>" size="22">
                  <input type="hidden" name="url" id="url" value="<? echo $url; ?>">
              </div><br>
                
                <p class='clear'>
					<input type='submit' class='red button' value='Стереть Продукт!!!'>
					<span class=''>&nbsp;&nbsp;&nbsp;| <a class='' href='javascript:history.go(-1)'>&nbsp; Отменить</a></span>
				</p>
        </form>


<?
		}
?>
      
      </section>
    </div>	
<? } ?> 
    <!-- ################################################################################################ -->
    
    <!-- ################################################################################################ -->
    <div class="clear"></div>
    
  </div>
</div>
<!-- Footer -->
<div class="wrapper row4">
  <?  include ("/../blocks/footer.php"); ?>
</div>
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