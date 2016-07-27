<?

/////////////////////////////////////
//       location Functions
/////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_all_locations ()
//
//		returns all the locations
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_all_locations () {
	$db = $GLOBALS["db"];
	
	// get the data from the DB
	$result = mysqli_query ($db, "SELECT * FROM locations");
	
	// if there was an error
	if (!$result) {
		$error = "<p>get_all_locations (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
	}
	
	// if nothing returned
	if (mysqli_num_rows($result)>0) {
		return $result;
	}
	// if nothing returned
	else {
		return 0;
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_locations_num ()
//
//		returns all the locations
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_locations_num () {
    $db = $GLOBALS["db"];

    // get the data from the DB
    $result = mysqli_query ($db, "SELECT COUNT(id) as num FROM locations");

    // if there was an error
    if (!$result) {
        $error = "<p>get_all_locations (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);

        if ($GLOBALS["debug_mode"]==1) {
            echo show_error($error);
        }
    }

    // if nothing returned
    if (mysqli_num_rows($result)>0) {
        $tmp = mysqli_fetch_array($result);
        return $tmp['num'];
    }
    // if nothing returned
    else {
        return 0;
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_first_location_id ()
//
//		returns all the locations
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_first_location_id () {
    $db = $GLOBALS["db"];

    // get the data from the DB
    $result = mysqli_query ($db, "SELECT * FROM locations ORDER BY id LIMIT 1");

    // if there was an error
    if (!$result) {
        $error = "<p>get_first_location (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);

        if ($GLOBALS["debug_mode"]==1) {
            echo show_error($error);
        }
    }

    // if nothing returned
    if (mysqli_num_rows($result)>0) {
        $tmp = mysqli_fetch_array($result);
        return $tmp['id'];
    }
    // if nothing returned
    else {
        return 0;
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_last_location ()
//
//		returns all the locations
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_last_location_id () {
    $db = $GLOBALS["db"];

    // get the data from the DB
    $result = mysqli_query ($db, "SELECT * FROM locations ORDER BY id DESC LIMIT 1");

    // if there was an error
    if (!$result) {
        $error = "<p>get_last_location (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);

        if ($GLOBALS["debug_mode"]==1) {
            echo show_error($error);
        }
    }

    // if nothing returned
    if (mysqli_num_rows($result)>0) {
        $tmp = mysqli_fetch_array($result);
        return $tmp['id'];
    }
    // if nothing returned
    else {
        return 0;
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_location_name ($id)
//
//		returns location name
//

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function get_location_name ($id) {
	$db = $GLOBALS["db"];
	// get the data from the DB
	$result = mysqli_query ($db, "SELECT name FROM locations WHERE id='$id'");
	
	// if there was an error
	if (!$result) {
		$error = "<p>get_location_name (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
	}
	
	// if nothing returned
	if (mysqli_num_rows($result)>0) {
		$tmp = mysqli_fetch_array ($result);
		return $tmp['name'];
	}
	// if nothing returned
	else {
		return 0;
	}
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		delete_location ($id) 
//
//		returns location name
//

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function delete_location ($id) {
	$db = $GLOBALS["db"];
	/* all ok -> sqlquery */
	
	echo "<h1>$id</h1>";
	$location_name = get_location_name ($id);
	$result1 = mysqli_query ($db, "DELETE FROM location_products WHERE `location_id` = '$id'");
	if ($result1) {
		$error = "Информация о продуктах филиала \"<strong>$location_name</strong>\" была успешно удалена!<br><br>";
		$result2 = mysqli_query ($db, "DELETE FROM locations WHERE `id` = '$id'");
		
		if ($result2) {
			$error = show_error($error."Филиал \"<strong>$location_name</strong>\" был успешно удален!", "success", 0);
			
			echo "<meta http-equiv='refresh' content='0;URL=locations.php'>";
		}
		else {
			$mysqli_error = mysqli_error($db);
			$error = show_error($error."Филиал \"<strong>$location_name</strong>\" был успешно удален!", "success", 0);
	
			echo "<meta http-equiv='refresh' content='0;URL=locations.php'>";
		}
		
		return $error;
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_location_id ($name)
//
//		returns location id
//

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function get_location_id ($name) {
	$db = $GLOBALS["db"];
	// get the data from the DB
	$result = mysqli_query ($db, "SELECT id FROM locations WHERE name='$name'");
	
	// if there was an error
	if (!$result) {
		$error = "<p>get_location_id (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
	}
	
	// if nothing returned
	if (mysqli_num_rows($result)>0) {
		$tmp = mysqli_fetch_array ($result);
		return $tmp['id'];
	}
	// if nothing returned
	else {
		return 0;
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		location_products_num ($id)
//
//		returns number of products in the location
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function location_products_num ($id) {
	$db = $GLOBALS["db"];
	// get the data from the DB
	$result = mysqli_query ($db, "SELECT count(product_id) as num FROM location_products WHERE location_id='$id'");
	
	// if there was an error
	if (!$result) {
		$error = "<p>location_products_num (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
	}
	
	$tmp = mysqli_fetch_array($result);
	
	return $tmp['num'];
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		location_exists ($id)
//
//		returns if location exists
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function location_exists ($id) {
	$db = $GLOBALS["db"];
	// get the data from the DB
	$result = mysqli_query ($db, "SELECT *
							FROM locations
							WHERE id='$id'");
	
	if (!$result) {
		$error = "<p>location_exists (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		
		if ($GLOBALS["debug_mode"]==1) {
			if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
		}
	}
	// if there was an error
	if (mysqli_num_rows($result)>0) {
		return true;
	}
	else {
		return false;
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		is_location_deletable ($id)
//
//		returns if brach can be deleted
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function is_location_undeletable ($id) {
	$db = $GLOBALS["db"];
	// get the data from the DB
	$result = mysqli_query ($db, "SELECT undeletable FROM locations WHERE id='$id'");
	
	// if there was an error
	if (!$result) {
		$error = "<p>is_location_undeletable (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
	}
	
	 
	$tmp = mysqli_fetch_array($result);
	return $tmp['undeletable'];
}


/**
 * @param     $username
 * @param     $password
 * @param int $type
 * @param int $location_id
 *
 * @return resource
 */
function add_new_user($username, $password, $type = 0, $location_id = 0) {
    $db = $GLOBALS["db"];

    $result = mysqli_query ($db, "INSERT INTO `users`(`username`, `password`, `type`, `location_id`, `email`) VALUES ('$username','$password','$type','$location_id','')");

return $result;
}


    /**
     * @param $location_id
     * @param $product_id
     *
     * @return int
     */
function isProductInLocation($location_id, $product_id) {
    $db = $GLOBALS["db"];

    $sql = mysqli_query ($db, "SELECT * FROM `location_products` WHERE `product_id`='$product_id' AND `location_id`='$location_id' LIMIT 1");

    return mysqli_num_rows($sql);
}


?>