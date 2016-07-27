

<div class="sidebar left">
  <aside>


        <h2><a href="#" class="btn btn-primary" data-action="toggle" data-side="left"><i class="icon-reorder"></i> Категории</a></h2>
        <nav>
            <ul>
            <?
            $catResult = get_all_categories ($db);
            $categories = mysqli_fetch_array($catResult);
            do{
               // echo ("<li><strong>$id</strong>");
                if ($category_id == $categories['id']) {
                    echo "<a href='".$root."pages/locations/locations.php?category_id=".$categories['id']."&id=$location_id'><strong>".$categories['name']."</strong></a>";
                }
                else {
                    echo "<a href='".$root."pages/locations/locations.php?category_id=".$categories['id']."&id=$location_id'>".$categories['name']."</a>";
                }
                echo ("</li>");

            }while($categories = mysqli_fetch_array($catResult));

            ?>
                <li class="alt"><a href='<?=$root?>pages/categories/new_category.php?location_id=<?=$location_id?>'><span class="icon-plus"></span> Добавить Категорию</a></li>
            </ul>
        </nav>
  </aside>
</div>