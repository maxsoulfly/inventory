<div class="wrapper row1">
  <header id="header" class="full_width clear ">
    <div id="hgroup">
      <h1><a href="<?=$root?>index.php">Где.<span class="orange bold">товар</span>?</a></h1>
      <h2 class="grey">by <em><a href="">freeurmind</a></em></h2>
    </div>
    
	<? 
        if(!empty($_SESSION['login_user'])) { 
            echo "<div id='user'><p>Пользователь: <strong>".$_SESSION['username']."</strong></p><p class='logout'><a href='".$root."logout.php'>Выйти <span class='icon-signout'></span></a></p></div>";
        } 
    ?>
    
    <div id="header-contact">
      <ul class="list none">
        <li><span class="icon-envelope"></span> <a href="mailto:support@freeurmind.net" target="_blank">support@freeurmind.net</a></li>
        <li><span class="icon-phone"></span> +972 <span class="orange">54 9491204</span></li>
      </ul>
    </div>
    
  </header>
</div>