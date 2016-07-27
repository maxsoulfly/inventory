<!-- TODO: UPLOAD THIS FILE-->
<div class="wrapper row2 full_width">
  <nav id="topnav"  >
    <ul class="clear">
    
    
        <li <? if ($page=="index") { echo "class='active'"; }?>><a href="<?=$root?>index.php" title="Главная">Главная </a></li>

        <?
            /////////////////////////////////////////////////////////////////////////////////////////////////////////
            //      LOCATIONS
            /////////////////////////////////////////////////////////////////////////////////////////////////////////
            #region      LOCATIONS
            $locationResult = get_all_locations ($db);
            $active = "";

            if ($page=="locations") { $active = 'class="active"'; }

            echo '
              <li '.$active.'><a class="drop first" href="'.$root.'pages/locations/locations.php" title="Филиалы">Филиалы</a>
                <ul id="locations">
            ';
            $locations = mysqli_fetch_array($locationResult);
            do{
                // add the category parameter also, if exists
                $categoryParam = "";
                if (isset($category_id)) {
                    $categoryParam = "&category_id=".$category_id;
                }
                echo "<li><a href='".$root."pages/locations/locations.php?id=".$locations['id'].$categoryParam."'>".$locations['name']."</a></li>";

            }while($locations = mysqli_fetch_array($locationResult));
            if ($_SESSION['type'] == 2) {
                       echo '<li class="alt"><a href="'.$root.'pages/locations/new_location.php"><span class="icon-plus"></span> Добавить Филиал</a></li>';
            }
            echo '
                </ul>
              </li>
            ';

            #endregion
        ?>
        <?

            /////////////////////////////////////////////////////////////////////////////////////////////////////////
            //      PRINT
            /////////////////////////////////////////////////////////////////////////////////////////////////////////
            #region PRINT

            $active = "";
            if ($page=="print") { $active = 'class="active"'; }


            echo '
                <li '. $active .'>
                    <a class="drop last" href="'.$root.'pages/locations/print.php?id='.$location_id.'" title="Принт">Принт</a>
                    <ul id="print">
            ';

            $locationResult = get_all_locations ($db);
            $locations = mysqli_fetch_array($locationResult);
            do{
                echo "<li><a href='".$root."pages/locations/print.php?id=".$locations['id']."'>".$locations['name']."</a></li>";

            }while($locations = mysqli_fetch_array($locationResult));

            echo '
                    </ul>
                </li>
            ';
            #endregion
        ?>
        <?

        /////////////////////////////////////////////////////////////////////////////////////////////////////////
        //      CATEGORIES
        /////////////////////////////////////////////////////////////////////////////////////////////////////////
            #region      CATEGORIES

            if ($_SESSION['type'] == 2) {
                $catresult = get_all_categories ($db);

                $active = "";
                if ($page=="categories") { $active = 'class="active"'; }
                echo  '
                    <li '.$active.'><a class="drop" href="'.$root.'pages/categories/categories.php" title="Категории">Категории</a>
                        <ul id="categories">
                ';
                $categories = mysqli_fetch_array($catresult);
                do{
                    echo "<li><a href='".$root."pages/categories/categories.php?id=".$categories['id']."'>".$categories['name']."</a></li>";
                }while($categories = mysqli_fetch_array($catresult));

                echo  '
                            <li class="alt"><a href="'.$root.'pages/categories/new_category.php"><span class="icon-plus"></span> Добавить Категорию</a></li>
                        </ul>
                    </li>
                ';

            }

            #endregion
        ?>
        <!-- ################################################################################################ -->
        <!-- ######     Операции                                                          ################### -->
        <!-- ################################################################################################ -->
        <?
            if ($_SESSION['type'] == 2) {
        ?>
                <li class=" force_right" ><a class="drop" href="#" title="Операции">Операции</a>
                    <ul id="products">
                        <li class="alt"><a href='<?=$root?>pages/transactions/view_supply.php'><span class="icon-truck"></span> Поставки <span class="icon-caret-right"></span></a>
                            <ul>
                                <li class="alt"><a href='<?=$root?>pages/transactions/view_supply.php'> Все Поставки</a></li>
                                <li class="alt"><a href='<?=$root?>pages/transactions/new_supply.php<? if (isset($location_id)) {echo "?id=".$location_id;} ?>'><span class="icon-plus"></span> Добавить Поставку </a></li>
                            </ul>
                        </li>
                        <li class="alt"><a href='<?=$root?>pages/transactions/view_transaction.php'><span class="icon-exchange"></span> Передача Товаров <span class="icon-caret-right"></span></a>
                            <ul>
                                <li class="alt"><a href='<?=$root?>pages/transactions/view_transaction.php'>Все Передачи Товаров</a></li>
                                <li class="alt"><a href='<?=$root?>pages/transactions/new_transaction.php<? if (isset($location_id)) {echo "?from_location=".$location_id;} ?>'><span class="icon-plus"></span> Передача Товаров</a></li>
                            </ul>
                        </li>
                        <li><a href='<?=$root?>pages/transactions/view_return.php'><span class="icon-reply"></span> Возвраты <span class="icon-caret-right"></span></a>
                            <ul>
                                <li class="alt"><a href='<?=$root?>pages/transactions/view_return.php'>Все Возвраты</a></li>
                                <li class="alt"><a href='<?=$root?>pages/transactions/new_return.php<? if (isset($location_id)) {echo "?from_location=".$location_id;} ?>'><span class="icon-plus"></span>Возврат Продуктов</a></li>
                            </ul>
                        </li>
                        <li><a href='<?=$root?>pages/products/inventory_count.php'><span class="icon-edit"></span> Инвентарный Подсчет</a></li>
                    </ul>
                </li>
        <? } ?>
        <!-- ################################################################################################ -->
        <?
        if ($_SESSION['type'] == 2) { ?>
              <li class="<? if ($page=="bugslog") { echo "active "; } ?>"><a href="<?=$root?>pages/bugslog/bugslog.php" title="[Bugs & Ideas Log]">[Bugs & Ideas Log]</a></li>
        <?  } ?>
        <!-- ################################################################################################ -->
    </ul>
  </nav>
</div>