<?php
    include("pages/blocks/db.php");
    include("pages/blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
?>
<!DOCTYPE html>
<html>
<head>
<title>RS-1200 Prototype 8 | Pages | 404</title>
<meta charset="iso-8859-1">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="layout/styles/main.css" rel="stylesheet" type="text/css" media="all">
<link href="layout/styles/mediaqueries.css" rel="stylesheet" type="text/css" media="all">
<!--[if lt IE 9]>
<link href="layout/styles/ie/ie8.css" rel="stylesheet" type="text/css" media="all">
<script src="layout/scripts/ie/css3-mediaqueries.min.js"></script>
<script src="layout/scripts/ie/html5shiv.min.js"></script>
<![endif]-->
</head>
<body class="">

<? include("pages/blocks/header.php"); ?>
<!-- ################################################################################################ -->

<? include("pages/blocks/nav.php"); ?>
<!-- content -->
<div class="wrapper row3">
  <div id="container">
    <!-- ################################################################################################ -->
    <div id="fof" class="clear">
      <!-- ####################################################################################################### -->
      <div class="clear">
        <div class="one_half first">
          <h1>404</h1>
        </div>
        <div class="one_half">
          <h2>Error - Sorry Something Went Wrong !</h2>
        </div>
      </div>
      <div class="divider2"></div>
      <p class="notice">For Some Reason The Page You Requested Could Not Be Found On Our Server</p>
      <p class="clear"><a class="fl_left" href="javascript:history.go(-1)">&laquo; Go Back</a> <a class="fl_right" href="index.php">Go Home &raquo;</a></p>
      <!-- ####################################################################################################### -->
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