<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	if (isset($_GET['id'])) {$category_id = $_GET['id'];}
	
?>
<!DOCTYPE html>
<html>
<head>
<title>Редактор Названия</title>
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
    <div class="three_quarter">

      <section class="clear">
<?
	if (!isset($category_id) || !category_exists($category_id, $db)){
		echo "<h1>Выберите категорию для редактирования:</h1>";
		$cat_res = mysqli_query ($db, "SELECT * FROM categories");
		
		if (!$cat_res) {
			echo "<p>Ошибка при получении данных из БД. Пожалуйста, сообщите администратору по support@freeurmind.net <br> <STRONG> Код ошибки:</strong></p>";
			exit(mysqli_error($db));
		}
		if (mysqli_num_rows($cat_res)>0) {
			$cat_row = mysqli_fetch_array($cat_res);
			
			echo "<ul class='font-large list indent disc '>";
			do{
				//echo "<li><a class='full_width' href='edit_category.php?id=".$cat_row['id']."' title='".$cat_row['title']."'>".$cat_row['title']."</a></li>";
				echo "<li ><a class='full_width' href='edit_category.php?id=".$cat_row['id']."' title='".$cat_row['name']."'>".$cat_row['name']."</a></li>";
			}while ($cat_row = mysqli_fetch_array($cat_res));
			echo "</ul>";
		}
		else {
			echo show_error("<p>БД пуста!</p>","warning",0);
			//exit();
		}
		
	}
	else {
		$cat_res = mysqli_query ($db, "SELECT * FROM categories WHERE id = $category_id");
		
		if (!$cat_res) {
			echo "<p>Ошибка при получении данных из БД. Пожалуйста, сообщите администратору по support@freeurmind.net <br> <STRONG> Код ошибки:</strong></p>";
			exit(mysqli_error($db));
		}
		if (mysqli_num_rows($cat_res)>0) {
			$cat_row = mysqli_fetch_array($cat_res);
		}
		else {
			echo show_error("<p>Категория не существует!</p>","warning",0);
		}
?>
      	<h1><em class="icon-pencil"></em> Редактор Названия Категории</h1>
        <form action="update_category.php" method="post" name="edit_category">
        	  <div class="form-input clear">
                <label class="one_third first font-large" for="name">Новое Название<span class="required">:</span><br>
                  <input type="hidden" name="id" id="id" value="<? echo $cat_row['id']; ?>" size="22">
                  <input class="font-small" type="text" name="name" id="name" value="<? echo $cat_row['name']; ?>" size="22">
                </label>
              </div><br>
                
                &nbsp;
                <p class="clear"><input type="submit" value="Изменить">&nbsp;&nbsp;&nbsp;|<a class="" href="javascript:history.go(-1)">&nbsp; Отменить</a></p>
<!--                <input type="button" value="Отменить" id="cancel" onClick="">
-->              </p>
        </form>
<? } ?> 
      </section>
    </div>
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