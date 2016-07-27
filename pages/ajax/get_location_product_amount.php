<?php
/**
 * Created by PhpStorm.
 * User: Max
 * Date: 05/05/2016
 * Time: 6:39 PM
 */

    include("../blocks/db.php");
    include("../blocks/functions.php");
    mysqli_set_charset($db, 'utf8');

    if (isset($_POST['location_id'])) { $location_id = $_POST['location_id']; if($location_id=="") {unset($location_id);}}
    if (isset($_POST['product_id'])) { $product_id = $_POST['product_id']; if($product_id=="") {unset($product_id);}}

    if(isset($location_id)&&isset($product_id)) {
        $tmp = get_location_product_amount ($product_id, $location_id);

        echo $tmp;
    }
    else echo 0;