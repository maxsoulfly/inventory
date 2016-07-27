<?  //header('Content-type: text/html; charset=windows-1251');
	include("../blocks/db.php");
	include("../blocks/functions.php");
	mysql_set_charset('utf8',$db);
	
	if (isset($_GET['category_id'])) {$category_id = $_GET['category_id'];}
	else {$category_id = 1;}
	
	if (isset($_GET['location_id'])) {$page_id = $_GET['location_id'];}
	else {$page_id = $category_id;}
	
	
	if (isset($_GET['page'])) {$page = $_GET['page'];}
	else {$page = "locations";}
	
?>
<!DOCTYPE html>
<html>
<head>
<title>Новый Продукт</title>
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
      	<h1><em class="icon-plus"></em> Добавить Новый Продукт</h1>
        
        <form action="add_product.php" method="post" name="new_Product">
        	  <input type="hidden" id="page_id" name="page_id" value="<? echo $page_id; ?>">
        	  <input type="hidden" id="page" name="page" value="<? echo $page; ?>">
              <div class="form-input clear">
              <p><label class="" for="category_id">В категорию <span class="required">*</span><br>
              	<select name='category_id' id='category_id' class='first one_third font-medium'>
<?	
			$cat_res = get_all_categories($db);
			$cat_row = mysqli_fetch_array($cat_res);
			
			do{
				echo "<option value='".$cat_row['id']."' ";
				if ($cat_row['id']==$category_id) { echo "selected"; }
				echo ">".$cat_row['name']."</option>";
				
			}while ($cat_row = mysqli_fetch_array($cat_res));
?>
				</select></label></p>
              <h1 class="one_third">Всем:</h1>

              </div><br>
              <div class="form-input clear">
                <label class="one_third first" for="name">Название <span class="required">*</span><br>
                  <input type="text" name="name" id="name" value="" size="22">
                </label>
                
                 <input type="hidden" name="location_id[]" value="0">
          		 <label class="one_sixth" for="name">Количество<br>
                  <input type="text" name="amount[]" value="" size="22">
                </label>
                <label class="one_sixth" for="price">Цена в &#8362;<br>
                  <input type="text" name="price[]" id="price[]" value="" size="22" class="fl-left">
                </label>

              </div><br>
              <h3 class="font-large "><strong>Начальное Количество:</strong> </h3>
              
              <div class="form-input clear">
<?
			$locRes = get_all_locations();
			if (mysqli_num_rows($locRes)>0) {
				$locations = mysqli_fetch_array($locRes);
				
				$i=0;
				do {
					$location_name = get_location_name ($locations["id"]);

?>
					<div class="one_third  <? if ($i % 6 == 0) { echo "first"; } $i++;?>">
                    	<h2><? echo $location_name; ?></h2>
                        <input type="hidden" name="location_id[]" value="<? echo $locations["id"]; ?>">
                        <label class="one_half first" for="amount[]">Количество<br>
                          <input type="text" name="amount[]" value="" size="22" class="fl-left">
                        </label>
                        <label class="one_half " for="price">Цена в &#8362;<br>
                          <input type="text" name="price[]" id="price[]" value="" size="22" class="fl-left">
                        </label>
                    </div> 
<?
					
				}while($locations = mysqli_fetch_array($locRes));
			}
?>     
                </div>
                <div class="push40 clear"></div>
                <p class="clear"><input type="submit" value="Добавить">&nbsp;&nbsp;&nbsp;|<a class="" href="<? echo $page; ?>.php?id=<? echo $page_id; if(isset($category_id)) { echo "category_id=".$category_id; } ?>">&nbsp; Отменить</a></p>
<!--                <input type="button" value="Отменить" id="cancel" onClick="">
-->              </p>
        </form>
        
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