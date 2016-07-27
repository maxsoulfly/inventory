<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	
	if (isset($_GET['id'])) {$category_id = $_GET['id'];}
    if (!isset($category_id)&&$category_id="") {unset($category_id);}

    if (isset($_GET['location_id'])) {$location_id = $_GET['location_id'];}
    if (!isset($location_id)||$location_id="") {$location_id = 1;}
	
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
    <div class="three_quarter">

      <section class="clear">
<?
	
	if (!isset($category_id) || !category_exists($category_id)){

		echo "<h1>Выберите категорию:</h1>";
		$cat_res = mysqli_query ($db, "SELECT * FROM" . " categories");
		
		if (!$cat_res) {
			echo show_error ("<p>Ошибка при получении данных из БД. Пожалуйста, сообщите администратору по support@freeurmind.net <br> <STRONG> Код ошибки:</strong></p>".mysqli_error($db), "error", 0);
			//exit();
		}
		else if (mysqli_num_rows($cat_res)>0) { ?>
 			<form action='drop_category.php' method='post' name='del_category'>
            		<p><select name='id' id='id' class='two_third font-medium'>
                		<option value='0'> </option>
<?	
			$cat_row = mysqli_fetch_array($cat_res);

			do{
                echo "<option value='".$cat_row['id']."'>".$cat_row['name']."</option>";
			}while ($cat_row = mysqli_fetch_array($cat_res));
			//echo "</ul>";
?>
				</select></p>
                <br><br>
                <p class='clear'>
                    <input type='hidden' id='location_id' name='location_id' value='$location_id'>
					<input type='submit' class='red button' value='Стереть Категорию!!!'>
					<span class=''>&nbsp;&nbsp;&nbsp;| <a class='' href='javascript:history.go(-1)'>&nbsp; Отменить</a></span>
				</p>
			</form>
<?
            
		}
		else {
			echo show_error("<p>БД пуста!</p>","warning",0);
			//exit();
		}
		
	}
	else {
		$cat_res = mysqli_query ($db, "SELECT * FROM categories WHERE id = $category_id");
		
		if (!$cat_res) {
			$error = show_error ("<p>Ошибка при получении данных из БД. Пожалуйста, сообщите администратору по support@freeurmind.net <br> <STRONG> Код ошибки:</strong></p>".mysqli_error($db), "error", 0);
			exit(mysqli_error($db));
		}
		if (mysqli_num_rows($cat_res)>0) {
			$cat_row = mysqli_fetch_array($cat_res);
?>
			<h1><em class="icon-remove"></em> Вы Уверены?</h1>
            <p class="font-large">Вы собираетесь стереть Категорию "<strong><? echo $cat_row['name']; ?></strong>", Вы Уверены?</p>

            <?
            if(!category_empty ($category_id, $db)) {
                echo show_error("В категории присутствуют продукты!","warning", 1);
            }
            ?>
        <form action="drop_category.php" method="post" name="edit_category">
        	  <div class="form-input clear">
                  <input type="hidden" name="id" id="id" value="<? echo $cat_row['id']; ?>" size="22">
              </div><br>
                
                <p class='clear'>
					<input type='submit' class='red button' value='Стереть Категорию!!!'>
					<span class=''>&nbsp;&nbsp;&nbsp;| <a class='' href='javascript:history.go(-1)'>&nbsp; Отменить</a></span>
				</p>
        </form>


<?
		}
		else {
			echo show_error("<p>Категория не существует!</p>","warning",0);
		}
?>
      	
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
    <? include "../blocks/footer.php"; ?>
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