

<div class="sidebar left">
  <aside>
    <!-- ########################################################################################## -->
<?
	//if ($page == "locations") {
?>		
		
        <a href=""  class="toggleDiv"><h2><span class="icon-building"></span> Филиалы</h2></a>
        <nav >
            <ul>
            <?
            $locationResult = get_all_locations ($db);
            $locations = mysqli_fetch_array($locationResult);
            do{
              //  echo "<li><strong>$id</strong>";
                if ($location_id == $locations['id']) {
                    echo ("<strong><a href='".$root."pages/locations/locations.php?id=".$locations['id']."'>".$locations['name']."</a></strong>");
                }
                else {
                    if ($page == "add_amounts") {
                        echo ("<a href='".$root."pages/locations/add_amounts.php?id=".$locations['id']."'>".$locations['name']."</a>");
                    }
                    else {
                        echo ("<a href='".$root."pages/locations/locations.php?id=".$locations['id']."'>".$locations['name']."</a>");
                    }
                }
                echo "";
                echo ("</li>");

            }while($locations = mysqli_fetch_array($locationResult));

            ?>

                <li class="alt"><a href='<?=$root?>pages/locations/new_location.php'><span class="icon-plus"></span> Добавить Филиал</a></li>
            </ul>

        </nav>
    <!-- /nav -->
<?	//} ?>
    <!-- ########################################################################################## -->
<?
	//if ($page == "categories") {
?>

        <a href="" class="toggleDiv"><h2><span class="icon-tasks"></span> Категории</h2></a>
        <nav>
            <ul>
            <?
            $catResult = get_all_categories ($db);
            $categories = mysqli_fetch_array($catResult);
            do{
               // echo ("<li><strong>$id</strong>");
                if ($category_id == $categories['id']) {
                    echo ("<a href='".$root."pages/categories/categories.php?id=".$categories['id']."'><strong>".$categories['name']."</strong></a>");
                }
                else {
                    echo ("<a href='".$root."pages/categories/categories.php?id=".$categories['id']."'>".$categories['name']."</a>");
                }
                echo ("</li>");

            }while($categories = mysqli_fetch_array($catResult));

            ?>
                <li class="alt"><a href='<?=$root?>pages/categories/new_category.php'><span class="icon-plus"></span> Добавить Категорию</a></li>
            </ul>
        </nav>
        <!-- /nav -->



        <a href="" class="toggleDiv grey"><h2><span class="icon-truck"></span> Все Открытие Поставки</h2></a>
        <nav style="display: none">
          <ul>
              <?

                  $supplyRes = get_opened_supplies ();
                  if ($supplyRes && mysqli_num_rows($supplyRes)>0) {
                      $openSupplies = mysqli_fetch_array ($supplyRes);
                      do {
                          echo "<li>
                                    <a class='black' href='".$root."pages/transactions/edit_supply.php?transaction_id=".$openSupplies['id']."&sourcePage=".$page."'>
                                        ".$openSupplies['title']."
                                    </a>
                                </li>";

                      } while ($openSupplies = mysqli_fetch_array ($supplyRes));
                  }
              ?>
              <li class="alt"><?=make_link($root."pages/transactions/view_supply.php","","<span class='icon-list'></span> Все Поставки")?></li>
              <li class="alt"><a href='<?=$root?>pages/transactions/new_supply.php?to_location=<?=$location_id?>&sourcePage=<?=$page?>' name="new_supply"><span class="icon-plus"></span> Добавить Поставку </a></li>
          </ul>
        </nav>
        <!-- /nav -->


      <a href="" class="toggleDiv grey"><h2><span class="icon-reply"></span> Возвраты Продуктов</h2></a>
      <nav style="display: none">
          <ul>
              <?

                  $returnRes = get_opened_returns ();
                  if ($returnRes && mysqli_num_rows($returnRes)>0) {
                      $openReturns = mysqli_fetch_array ($returnRes);
                      do {
                          echo "<li class='dkgrey'>
                                    <a href='".$root."pages/transactions/edit_return.php?transaction_id=".$openReturns['id']."&sourcePage=".$page."'>
                                        ".$openReturns['title']."
                                    </a>
                                </li>";

                      } while ($openReturns = mysqli_fetch_array ($returnRes));
                  }
              ?>
              <li class="alt"><?=make_link($root."pages/transactions/view_return.php","","<span class='icon-list'></span> Все Возвраты")?></li>
              <li class="alt"><a href='<?=$root?>pages/transactions/new_return.php?from_location=<? echo $location_id; ?>&sourcePage=<?=$page?>''><span class='icon-plus'></span> Новый Возврат</a></li>
          </ul>
      </nav>
      <!-- /nav -->

<? if (get_locations_num()>1) { ?>
      <a href="" class="toggleDiv grey"><h2><span class="icon-exchange"></span> Передачи Товаров</h2></a>
      <nav style="display: none">
          <ul>
              <?

                  $transactionRes = get_opened_transfers ();
                  if ($supplyRes && mysqli_num_rows($transactionRes)>0) {
                      $openTransactions = mysqli_fetch_array ($transactionRes);
                      do {
                          echo "<li class='dkgrey'>
                                    <a href='".$root."pages/transactions/edit_transaction.php?transaction_id=".$openTransactions['id']."&sourcePage=".$page."'>
                                        ".$openTransactions['title']."
                                    </a>
                                </li>";

                      } while ($openTransactions = mysqli_fetch_array ($transactionRes));
                  }
              ?>
              <li class="alt"><a href='<?=$root?>pages/transactions/view_transaction.php'><span class='icon-list'></span> Все Передачи Товаров</a></li>
              <li class="alt"><a href='<?=$root?>pages/transactions/new_transaction.php<? if (isset($location_id)) {echo "?from_location=".$location_id;} ?>'><span class="icon-plus"></span> Передача Товаров</a></li>
          </ul>
      </nav>
      <!-- /nav -->
<? } ?>
      <a href="../products/inventory_count.php" class="grey"><h2><span class="icon-edit"></span></span> Переучет Товара</h2></a>
      <!-- /nav -->

    <!-- ########################################################################################## -->
  </aside>
</div>