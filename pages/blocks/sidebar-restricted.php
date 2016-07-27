
<!--TODO UPLOAD THIS-->

<div class="sidebar left">
  <aside>


        <h2><a href="#" class="btn btn-primary" data-action="toggle" data-side="left"><i class="icon-reorder"></i> Категории</a></h2>
        <nav>
            <ul>
            <?
            $catResult = get_all_categories ($db);
            $categories = mysqli_fetch_array($catResult);
            do{
                $link = '<a href="locations-restricted.php?category_id='.$categories['id'].'&id='.$location_id.'">'.$categories['name'].'</a>';

                if ($category_id == $categories['id']) { $link = "<strong>".$link."</strong>"; }

                echo "<li>".$link."</li>";

            }while($categories = mysqli_fetch_array($catResult));

            ?>
            </ul>
        </nav>
  </aside>
</div>