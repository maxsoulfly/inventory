<?

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		update_location_product ($location_id, $product_id, $amount) 
//
//		returns last_transaction
//

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function update_location_product ($location_id, $product_id, $new_amount, $reason_id) {
	
	$prevProductDetail = get_location_product_details ($location_id, $product_id);
	// Beginning of the mount update:
	$prevCountDate = $prevProductDetail["count_date"];
	$prevMonth = get_month($prevCountDate);
	
	$currYear  = date("Y");
	$currMonth = date("m");
	$currDay   = date("d");
	$now = date($currYear."-".$currMonth."-".$currDay);
	
	$db = $GLOBALS["db"];
	$prev_amount = get_product_amount_per_location ($product_id, $location_id);
	$location_name 	= get_location_name	($location_id);
	$product_name 	= get_product_name	($product_id);
	$count_date = date("y-m-d");
	
	// this will be true on the first time of the mount count
	if ($currMonth>$prevMonth) {
						
		$result = mysqli_query ($db, "UPDATE  location_products
							SET  `amount` =  '$new_amount', `prev_amount` =  '$prev_amount', `count_date` =  '$count_date', `reason_id` =  '$reason_id', `bom_amount` =  '$new_amount'
							WHERE  `product_id` = '$product_id' AND  `location_id` = '$location_id';");
	}
	else {
		$result = mysqli_query ($db, "UPDATE  location_products
							SET  `amount` =  '$new_amount', `prev_amount` =  '$prev_amount', `count_date` =  '$count_date', `reason_id` =  '$reason_id'
							WHERE  `product_id` = '$product_id' AND  `location_id` = '$location_id';");
	}
	
	// if there was an error
	if (!$result) {
		$error = "<p>update_location_product (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
		
		return false;
	}
	else {
		$error = "<p>update_location_product (): </p>
					<p>The amount of <strong>$product_name</strong> in <strong>$location_name</strong>
					 changed from <strong>$prev_amount</strong> to <strong>$new_amount</strong></p>";
		
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error, "success", 1);
		}
		return true;
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_location_product_details ($location_id, $product_id)
//
//		returns location_product details
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_location_product_details ($location_id, $product_id) {
	$db = $GLOBALS["db"];
	// get the data from the DB
	$result = mysqli_query ($db, "SELECT * FROM location_products WHERE product_id = '$product_id' AND location_id = '$location_id'");
	
	// if there was an error
	if (!$result) {
		$error = "<p>get_location_product_details (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
		
		return 0;
	}
	
	// if nothing returned
	if (mysqli_num_rows($result)>0) {
		$tmp = mysqli_fetch_array ($result);
		return $tmp;
	}
	// if nothing returned
	else {
		return 0;
	}
}




?>