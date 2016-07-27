<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	$page = "bugslog";
	
?>
<!DOCTYPE html>
<html>
<head>
<title>[bugslog]</title>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script>
  $(function() {
    $('a[href*="#"]:not([href="#"])').click(function() {
      if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
        if (target.length) {
          $('html, body').animate({
            scrollTop: target.offset().top
          }, 1000);
          return false;
        }
      }
    });
  });
	</script>
    
<script src="nicEdit/nicEdit.js" type="text/javascript"></script>
<script type="text/javascript">
bkLib.onDomLoaded(function() {
	//new nicEditor({fullPanel : true}).panelInstance('text');
    new nicEditor({iconsPath : 'nicEdit/nicEditorIcons.gif'}).panelInstance('text');
});
</script>
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
    <div id="content" class="three_quarter">
      <section class="clear">
      	
        <h1 id="top" class="font-xl">Bugs & Improvement Ideas Log</h1>
        <p><a href="#bottom">Bottom</a></p>
		<div class="clear">
        <? 
			$allCategoriesRes = get_comments_categories();
			if ($allCategoriesRes&&(mysqli_num_rows($allCategoriesRes)>0)) {
				
				$allCategories = mysqli_fetch_array($allCategoriesRes);
				
				do {
					$commentsResult = get_category_comments($allCategories["category_id"]);
					
					if (!$commentsResult) {
						$error = "<p>Error while retrieving data from the DB. Please notify the administrator on admin@travelblog.com<br><strong>Error code:</strong></p>".mysql_error();
						$show_error = 1;
					}
					else if (mysqli_num_rows($commentsResult)>0) {
						$comments = mysqli_fetch_array($commentsResult);
					}
					else {
						$error =  "<p>No data was received</p>";
						$show_error = 1;
						//exit();
					} 
				?>    
						<!-- ################################################################################################ -->
						<div class="clear"></div>
      					<div class="accordion-wrapper"><a href="javascript:void(0)" class="accordion-title orange active"><span><? echo $allCategories['title'];?></span></a>
        					<div class="accordion-content" style="display: block;">
						
				<?
						if ($commentsResult&&mysqli_num_rows($commentsResult)>0) {
							
							do {
				?>
						<div class="testimonial clear alert-msg info2 rnd10">
						  <div class="pad20">
							<a class="close drop_comment" href="drop_comment.php?id=<? echo $comments['id'];?>">X</a>
							<blockquote>
							  <div class="marks">&ldquo;</div>
							  <p class=""><strong><? echo $comments['author'];?></strong> (<? echo $comments['date']; ?>):</p>
							  <div class=""><? echo $comments['text']; ?></div>
							</blockquote>
							
							</div>
						</div>
				<?
                            }while ($comments = mysqli_fetch_array($commentsResult));
						}
						else {
							echo show_error($error, "info", 1);
						}
						
					
					
					//if ($show_error == 1) { echo show_error($error, "info", 1); }
				?>
		  <!-- ################################################################################################ -->	
        </div>
	<?
			}while ($allCategories = mysqli_fetch_array($allCategoriesRes));
		}
		?>
      <!-- ################################################################################################ -->
      </div>

      
      
      <!-- ################################################################################################ -->
    </div>
      <!-- ################################################################################################ -->
   		<div class="clear"></div>
        <div class="testimonial clear">
          <div class="pad40 ">
            <blockquote>
              <div class="marks">&ldquo;</div>
            <h2  id="bottom"  class="font-large">Add Your Text:</h2>
            <form class="rnd6" action="comment.php" method="post">
                <input type="hidden" name="id" id="id" value="<? echo $post_id; ?>" size="22">
                <div class="form-input clear">
                  <p><label class="one_third first" for="author">Name <span class="required">*</span><br>
                    <input type="text" name="author" id="author" value="" size="22">
                  </label></p>
                  </div><br>
                  
                  <div class="form-input clear">
                  <p><label class="" for="category_id">Category <span class="required">*</span><br>
                    <select name='category_id' id='category_id' class='first one_third font-medium'>
    <?	
                $allCategoriesRes = get_comments_categories();
				if ($allCategoriesRes&&(mysqli_num_rows($allCategoriesRes)>0)) {
					
					$allCategories = mysqli_fetch_array($allCategoriesRes);
					
					do{
						echo "<option value='".$allCategories['category_id']."' >".$allCategories['title']."</option>";
						
					}while ($allCategories = mysqli_fetch_array($allCategoriesRes));
				}
    ?>
                    </select></label></p>
                  
                </div><br>
                <div class="form-message push15">
                  <textarea name="text" id="text" cols="25" rows="10" class=""></textarea>
                </div>
                
                <p>
                  <input name="sub_com" type="submit" value="Submit">
                  &nbsp;
                  <input type="reset" value="Reset">
                </p>
            </form>
        
            </blockquote>
            
            </div>
        </div>
                <br><br><p><a href="#top">Top</a></p>
      <!-- ################################################################################################ -->	
      </section>
    </div>
    <!-- ################################################################################################ -->
    
    <!-- ################################################################################################ -->

    <div class="clear"></div>
    
  </div>
</div>
<!-- Footer -->
<div class="wrapper row2">
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