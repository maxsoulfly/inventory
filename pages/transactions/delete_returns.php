<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset($db, 'utf8');
	
	
	if (isset($_GET['product_id'])) {$product_id = $_GET['product_id'];}
    if (isset($_GET['location_id'])) {$location_id = $_GET['location_id'];} if (!isset($location_id)||$location_id=="") {$location_id=1;}
    if (isset($_GET['category_id'])) {$category_id = $_GET['category_id'];} if (!isset($category_id)||$category_id=="") {$category_id=1;}
	
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
		$res = mysqli_query ($db, "SELECT * FROM products WHERE id = $product_id");
		
		if (!$res) {
			$error = show_error ("<p>Ошибка при получении данных из БД. Пожалуйста, сообщите администратору по support@freeurmind.net <br> <STRONG> Код ошибки:</strong></p>".mysqli_error($db));
			exit(mysqli_error($db));
		}
		if (mysqli_num_rows($res)>0) {
			$data = mysqli_fetch_array($res);
?>
			<h1><em class="icon-remove"></em> Вы Уверены?</h1>
            <p class="font-large">Вы собираетесь стереть информацию о возвратах продукта "<strong><? echo $data['name']; ?></strong>", Вы Уверены?</p>
            <?
				echo show_error ("<p class='font-medium'>Вся информация о операциях будет потеряна.</p>","warning", 0);
			?>
        <form action="drop_returns.php" method="post" name="delete_returns">
        	  <div class="form-input clear">
                  <input type="hidden" name="id" id="id" value="<? echo $data['id']; ?>" size="22">
                  <input type="hidden" name="location_id" id="location_id" value="<? echo $location_id; ?>" size="22">
                  <input type="hidden" name="category_id" id="category_id" value="<? echo $category_id; ?>" size="22">
              </div><br>
                
                <p class='clear'>
					<input type='submit' class='red button' value='Стереть Операции!!!'>
					<span class=''>&nbsp;&nbsp;&nbsp;| <a class='' href='javascript:history.go(-1)'>&nbsp; Отменить</a></span>
				</p>
        </form>


<?
		}
		else {
			echo show_error("<p>Продукт не существует!</p>","warning",0);
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