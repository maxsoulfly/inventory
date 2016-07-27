<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
    mysqli_set_charset ($db, 'utf8');
	
	if (isset($_POST['product_id'])) { $product_id = $_POST['product_id']; if($product_id=="") {unset($product_id);}}
	if (isset($_POST['location_id'])) { $location_id = $_POST['location_id']; if($location_id=="") {unset($location_id);}}
	if (isset($_POST['amountLeft'])) { $amountLeft = $_POST['amountLeft']; if($amountLeft=="") {unset($amountLeft);}}

	//echo "this is th values I've got->product_id: $product_id, amount: $amountLeft, location_id: $location_id";

	if (isset($amountLeft)&&isset($location_id)&&isset($product_id)) {
		// all ok -> sqlquery 
		
		$result = mysqli_query ($db, "UPDATE `location_products`
								SET `amount`='$amountLeft'
								WHERE `product_id`='$product_id'
								AND   `location_id`='$location_id'");
		
		if ($result) {
			echo "the price was updated to ".$price;
		}
		else {
			echo "wasn't updated";
		}
	}
	else {
			echo false;
	}
?>