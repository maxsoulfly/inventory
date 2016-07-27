<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	
	if (isset($_GET['id'])) {$location_id = $_GET['id'];}
	
?>
<!DOCTYPE html>
<html>
<head>
<title>Удаление Филиала</title>
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
    <div class="three_quarter">

      <section class="clear">
<?
	
	if (!isset($location_id) || !location_exists ($location_id, $db)|| is_location_undeletable ($location_id, $db)){
		
		if(is_location_undeletable ($location_id, $db)) {
			echo show_error("Филиал запрещен для удаления.","warning", 1);
		}
		echo "<h1>Выберите Филиал:</h1>";
		$res = mysqli_query ($db, "SELECT * FROM locations WHERE undeletable = 0");
		
		if (!$res) {
			echo show_error ("<p>Ошибка при получении данных из БД. Пожалуйста, сообщите администратору по support@freeurmind.net <br> <STRONG> Код ошибки:</strong></p>".mysqli_error($db), "error", 0);
			//exit();
		}
		else if (mysqli_num_rows($res)>0) { 
			$data = mysqli_fetch_array($res);
			
			echo "<ul class='font-large list indent disc '>";
			do{
				echo "<li><a class='full_width' href='delete_location.php?id=".$data['id']."' name='".$data['name']."'>".$data['name']."</a></li>";
			}while ($data = mysqli_fetch_array($res));
			echo "</ul>";
            
		}
		else {
			echo show_error("<p>БД пуста!</p>","warning",0);
			//exit();
		}
		
	}
	else {
		$res = mysqli_query ($db, "SELECT * FROM locations WHERE id = $location_id");
		
		if (!$res) {
			$error = show_error ("<p>Ошибка при получении данных из БД. Пожалуйста, сообщите администратору по support@freeurmind.net <br> <STRONG> Код ошибки:</strong></p>".mysqli_error($db), "error", 0);
			exit(mysqli_error($db));
		}
		if (mysqli_num_rows($res)>0) {
			$data = mysqli_fetch_array($res);
?>
			<h1><em class="icon-remove"></em> Вы Уверены?</h1>
            <p class="font-large">Вы собираетесь стереть Филиал "<strong><? echo $data['name']; ?></strong>", Вы Уверены?</p>
            <?
				$prodnum = location_products_num($location_id,$db);
				if ($prodnum > 0) {
					echo show_error ("В базе данных имеется информация о <strong class='font-large red'>".$prodnum."</strong> продуктах. Удаляя этот филиал, данная информация сотрётся тоже. ","warning", 0);
				}
			?>
        <form action="drop_location.php" method="post" name="edit_category">
        	  <div class="form-input clear">
                  <input type="hidden" name="id" id="id" value="<? echo $location_id; ?>" size="22">
              </div><br>
                
                <p class='clear'>
					<input type='submit' class='red button' value='Стереть Филиал!!!'>
					<span class=''>&nbsp;&nbsp;&nbsp;| <a class='' href='javascript:history.go(-1)'>&nbsp; Отменить</a></span>
				</p>
        </form>


<?
		}
		else {
			echo show_error("<p>Филиал не существует!</p>","warning",0);
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