<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	if (isset($_POST['product_id'])) { $product_id = $_POST['product_id']; if($product_id=="") {unset($product_id);}}
	if (isset($_POST['location_id'])) { $location_id = $_POST['location_id']; if($location_id=="") {unset($location_id);}}
	if (isset($_POST['price'])) { $price = $_POST['price']; if($price=="") {unset($price);}}

	//echo "this is th values I've got->product_id: $product_id, price: $price, location_id: $location_id";

	if (isset($price)&&isset($location_id)&&isset($product_id)) {
		// all ok -> sqlquery 

        // if there's a product set in the location -> update
        if(isProductInLocation($location_id, $product_id) == 1) {
            $result = mysqli_query ($db, "UPDATE `location_products`
                                    SET `price`='$price'
                                    WHERE `product_id`='$product_id'
                                    AND   `location_id`='$location_id'");

            if ($result) {
                //echo "the price was updated to " . $price;
                echo true;
            } else {
                //echo "wasn't updated";
                echo false;
            }
        }
        else {
            // if the product isn't  set in the location -> add
            add_location_product($location_id, $product_id, $price, 0);
            echo true;
        }
	}
	else {
			echo false;
	}
?>