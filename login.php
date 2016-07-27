<?php
session_start();

    if($_SESSION['login_user']){
        //header('Location: '.$root.'index.php');
        echo '<meta http-equiv="refresh" content="0; URL=\'/index.php\'" />';
    }

	include_once "pages/blocks/functions.php";
    mysqli_set_charset ($db, 'utf8');
?>
<!DOCTYPE html>
<html>
<head> <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Где.товар?</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="layout/styles/main.css" rel="stylesheet" type="text/css" media="all">
<link href="layout/styles/mediaqueries.css" rel="stylesheet" type="text/css" media="all">

    <!-- SIDEBAR -->
    <script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="layout/scripts/jquery.sidebar.js"></script>
    <script src="http://jillix.github.io/jQuery-sidebar/js/handlers.js"></script>

<script src="js/jquery.min.js"></script>
<script src="js/jquery.ui.shake.js"></script>
	<script>
			$(document).ready(function() {
			
				$('#login').click(function()
				{
					var username=$("#username").val();
					var password=$("#password").val();
					var dataString = 'username='+username+'&password='+password;
					if($.trim(username).length>0 && $.trim(password).length>0)
					{
						$.ajax({
							type: "POST",
							url: "ajaxLogin.php",
							data: dataString,
							cache: false,
							beforeSend: function(){ $("#login").val('Подключаюсь...');},
							success: function(data){
								if(data) {
                                         $("body").fadeOut(500);
                                        window.location.href = "<?=$root?>index.php";
								}
								else{
									
									$('#box').shake();
									$("#login").val('Login');
									$("#error").html("<span style='color:#cc0000'>Error:</span> Неверные Имя ползователя и/или Пароль. ");
								}
							}
						});
			
			}
			return false;
			});
			
				
			});
		</script>
</head>
<body class="">


<? include("pages/blocks/header.php"); ?>

<!-- ################################################################################################ -->

<? if(!empty($_SESSION['login_user'])) { include("pages/blocks/nav.php");} ?>

<!-- ################################################################################################ -->
<div class="wrapper row2">
  <!-- ################################################################################################ -->
  
  <!-- ################################################################################################ -->
  <div class="clear"></div>
</div>
<!-- content -->
<div class="wrapper row3">
  <div id="container">
    <!-- ################################################################################################ -->
    <div id="homepage" class="clear">
      <section class="clear">
		<div id="main">
            <div class="one_six">&nbsp;</div>
            <div class="five_sixth">
                <h1>Добро пожаловать в систему!</h1>
                <p class="font-medium">Введите ваш имя пользователя и пароль</p>
            </div>
            <div id="box" class="two_sixth">
                <form action="" method="post">
                    <label>Имя пользователя </label>
                    <input type="text" name="username" class="input" autocomplete="off" id="username"/>
                    <label>Пароль </label>
                    <input type="password" name="password" class="input" autocomplete="off" id="password"/><br/>
                    <input type="submit" class="button button-primary" value="Aвторизоваться" id="login"/>
                    <span class='msg'></span> 
                
                    <div id="error">
                    </div>
          	
        		</form>	
            </div>
        </div>
      </section>
    </div>
    <!-- ################################################################################################ -->
    <div class="clear"></div>
    
    
    
  </div>
</div>
<!-- Footer -->
<? include("pages/blocks/footer.php"); ?>
<!-- Scripts -->
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
<script>window.jQuery || document.write('<script src="layout/scripts/jquery-latest.min.js"><\/script>\
<script src="layout/scripts/jquery-ui.min.js"><\/script>')</script>
<script>jQuery(document).ready(function($){ $('img').removeAttr('width height'); });</script>
<script src="layout/scripts/jquery-mobilemenu.min.js"></script>
<script src="layout/scripts/custom.js"></script>
</body>
</html>