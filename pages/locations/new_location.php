<?  //header('Content-type: text/html; charset=windows-1251');
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
?>
<!DOCTYPE html>
<html>
<head>
<title>Новый Филиал</title>
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
      	<h1><em class="icon-plus"></em> Добавить Новый Филиал</h1>
        
        <form action="add_location.php" method="post" name="new_location">
        	  <div class="form-input clear">
                <label class="one_third first dkgrey font-large" for="name">Название <span class="required red">*</span><br>
                  <input type="text" name="name" id="name" value="" size="22">
                </label>
                <label class="one_third top10 first" for="username">Выберете имя пользователя для данного филиала <span class="required red">*</span><br><span class="required red">латинскими буквами</span>
                    <input type="text" name="username" id="username" value="" size="22">
                </label>
              </div><br>
                
                &nbsp;
                <p class="clear"><input type="submit" value="Добавить">&nbsp;&nbsp;&nbsp;|<a class="" href="javascript:history.go(-1)">&nbsp; Отменить</a></p>
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