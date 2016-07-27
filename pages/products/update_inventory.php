<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	if (isset($_POST['location_id'])) 	{ $location_id 	= $_POST['location_id']; 	if($location_id=="") 		{unset($location_id);}}
	if (isset($_POST['product_id'])) 	{ $product_id 	= $_POST['product_id']; 	if(sizeof($product_id)<1) 	{unset($product_id);}}
	if (isset($_POST['new_amount']))	{ $new_amount 	= $_POST['new_amount']; 	if(sizeof($new_amount)<1) 	{unset($new_amount);}}
	if (isset($_POST['reason_id'])) 	{ $reason_id 	= $_POST['reason_id']; 		if(sizeof($reason_id)<1) 	{unset($reason_id);}}
	
?>
<!DOCTYPE html>
<html>
<head>
<?		
			 //echo "<ul><li>category_id $category_id</li><li>name: $name</li></ul>";
			if (isset($location_id)&&isset($product_id)&&isset($new_amount)) {
				
				// debug message
				$msg = "";
				$err = "";
				
				// For every $product_id
				for ($i=0; $i<sizeof($product_id); $i++) {
					// get previous amount
					$prevAmount = get_product_amount_per_location ($product_id[$i], $location_id);
					
					// debug -------------------------------
					$line = "<br>prod #".$product_id[$i]." - prev count: ".$prevAmount." - curr count:".$new_amount[$i];
					//--------------------------------------
					
					// if the new count is not empty or if there was a change
					if (($new_amount[$i]!="")&&($new_amount[$i]!=NULL)&&($new_amount[$i]!=$prevAmount)) {
						
						// debug -------------------------------
						$line = "<span class='green'>".$line." - will be edited</span>";
						//--------------------------------------
						
						// update to new amount
						if (update_location_product ($location_id, $product_id[$i], $new_amount[$i], $reason_id)) {
							
							// debug -------------------------------
							$line = $line." <span class='green icon-ok-sign'></span>";
							//--------------------------------------
						}
						else {
							// debug -------------------------------
							$line = $line." <span class='red icon-remove-sign'></span>";
							//--------------------------------------
						}
					}
					// debug -------------------------------
					$msg = $msg.$line;
					//--------------------------------------
				}
				
				
			}
	
?>

<title>Обработчик</title>
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
      	<h1><em class="icon-beer"> </em>Обработчик</h1>
        	<? 
				echo show_error($msg, "info");
				echo $error; 
				echo $footer_links; 
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
</body>
</html>