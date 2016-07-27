<?
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		transactionExists ($transaction_id)
//
//		returns $result - list of all product transactions
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function transactionExists ($transaction_id) {
    $db = $GLOBALS["db"];
    $result = mysqli_query ($db, "SELECT * FROM transactions WHERE id='$transaction_id'");
    if($result && mysqli_num_rows($result)>0) { return 0; }
    else {return 1;}
}
    
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		clearEmptyTransactions ()
//
//		returns $result - list of all product transactions
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function clearEmptyTransactions () {
    $db = $GLOBALS["db"];
    $result = mysqli_query ($db, "DELETE FROM `transactions` WHERE `id` NOT IN (SELECT `transaction_id` FROM  `transaction_products` )");
    if($result) { return 0; }
    else {return 1;}
}
    
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		clearEmptyTransactionProducts ()
//
//		returns $result - list of all product transactions
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function clearEmptyTransactionProducts () {
    $db = $GLOBALS["db"];
    $result = mysqli_query ($db, "DELETE FROM `transaction_products` WHERE `product_id` NOT IN (SELECT `id` FROM  `products` )");
    if($result) { return 0; }
    else {return 1;}
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_transaction_details ($transaction_id)
//
//		returns $result - list of all product transactions
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_transaction_details ($transaction_id) {
	$db = $GLOBALS["db"];
	$result = mysqli_query ($db, "SELECT * FROM transactions WHERE id='$transaction_id'");
	if($result && mysqli_num_rows($result)>0) { return $result; }
	else {return false;}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_transaction_details ($transaction_id)
//
//		returns $result - list of all product transactions
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_transaction_products ($transaction_id) {
	$db = $GLOBALS["db"];
	$result = mysqli_query ($db, "SELECT * FROM transaction_products WHERE transaction_id='$transaction_id'");
	if($result || mysqli_num_rows($result)>0) { return $result; }
	else {return false;}
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_product_transactions ($product_id, $to_location)
//
//		returns $result - list of all product transactions
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_product_transactions ($product_id, $to_location) {
	$db = $GLOBALS["db"];
	$result = mysqli_query ($db, "SELECT `transaction_products`.`transaction_id`,`date`,`from_location`,`to_location`,`product_id`, `amount`
							FROM `transaction_products`, `transactions`
							WHERE `product_id` = '$product_id'
							AND `transaction_products`.`transaction_id` = `transactions`.`id`
							AND `to_location` = '$to_location'");
	if ($result && mysqli_num_rows($result)>0) {
		//$date = mysqli_fetch_array($result);
		return $result;
	}
	else {
		$error = "<p>get_product_transactions (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
		return false;
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_product_transactions_by_month ($product_id, $from_id) 
//
//		returns $result - list of all product transactions
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_product_transactions_by_month ($product_id,  $date = "thisMonth") {
	$db = $GLOBALS["db"];
	if ($date == "thisMonth") {
		$date = date("Y-m");
	}
	$date_begin = $date;
	$date++;
	$date_end = $date;
	
	$date_begin = $date_begin."-01";
	$date_end = $date_end."-01";


	$result = mysqli_query ($db, "SELECT  `transaction_products`.`transaction_id` ,`date`,`from_location` ,  `to_location` ,  `product_id` ,  `amount`
							FROM  `transaction_products` ,  `transactions`
							WHERE  `product_id` =  '$product_id'
							  AND  `transaction_products`.`transaction_id` =  `transactions`.`id`
							  AND  date>='$date_begin' AND date<'$date_end'");
	if (!$result) {
		$error = "<p>get_product_transactions_by_month (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
		return false;	}
	else {
		if(mysqli_num_rows($result)>0) {
			return $result;
		}
		return 0;
	}
	
	
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_all_product_recieves ($product_id, $location_id)
//
//		returns $result - list of all product transactions
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_all_product_recieves ($product_id, $location_id) {
	$db = $GLOBALS["db"];
	


	$result = mysqli_query ($db, "SELECT  `transaction_products`.`transaction_id` ,`date`, `from_location` ,  `to_location` ,  `product_id` ,  `amount`
							FROM  `transaction_products` ,  `transactions`
							WHERE  `product_id` =  '$product_id'
							  AND  `transaction_products`.`transaction_id` =  `transactions`.`id`
							  AND  `transactions`.`to_location` =  '$location_id'
							  AND  `transactions`.`completed` =  1 ");
	if (!$result) {
		$error = "<p>get_all_product_recieves (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
		return false;	}
	else {
		if(mysqli_num_rows($result)>0) {
			return $result;
		}
		return 0;
	}
	
	
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_all_product_returns ($product_id, $location_id)
//
//		returns $result - list of all product transactions
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_all_product_returns ($product_id, $location_id) {
	$db = $GLOBALS["db"];
	


	$result = mysqli_query ($db, "SELECT  `transaction_products`.`transaction_id` ,`date`, `from_location` ,  `to_location` ,  `product_id` ,  `amount`
							FROM  `transaction_products` ,  `transactions`
							WHERE  `product_id` =  '$product_id'
							  AND  `transaction_products`.`transaction_id` =  `transactions`.`id`
							  AND  `transactions`.`from_location` =  '$location_id'
                              AND  `transactions`.`completed` =  1");
	if (!$result) {
		$error = "<p>get_all_product_returns (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
		return false;	}
	else {
		if(mysqli_num_rows($result)>0) {
			return $result;
		}
		return 0;
	}
	
	
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_location_product_amount ($product_id, $location_id)
//
//		returns $result - amount of all product transactions
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_location_product_amount ($product_id, $location_id) {
	$db = $GLOBALS["db"];
	$result = mysqli_query ($db, "SELECT SUM(`amount`) as amount
							FROM  `transaction_products`,`transactions`
							WHERE  `product_id` =  '$product_id'
							  AND  `transaction_products`.`transaction_id` =  `transactions`.`id`
							  AND  `transactions`.`to_location` =  '$location_id' ");
	if (!$result) {
		$error = "<p>get_location_product_amount (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
		return 0;	}
	else {
		if(mysqli_num_rows($result)>0) {
			$tmp = mysqli_fetch_array($result);
			return $tmp['amount'];
		}
		else {
			return 0;
		}
	}
	
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_location_product_return_amount ($product_id, $location_id)
//
//		returns $result - amount of all product transactions
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_location_product_return_amount ($product_id, $location_id) {
	$db = $GLOBALS["db"];
	$result = mysqli_query ($db, "SELECT SUM(`amount`) as amount
							FROM  `transaction_products`,`transactions`
							WHERE  `product_id` =  '$product_id'
							  AND  `transaction_products`.`transaction_id` =  `transactions`.`id`
							  AND  `transactions`.`from_location` =  '$location_id' ");
	if (!$result) {
		$error = "<p>get_location_product_return_amount (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
		return 0;	}
	else {
		if(mysqli_num_rows($result)>0) {
			$tmp = mysqli_fetch_array($result);
			return $tmp['amount'];
		}
		else {
			return 0;
		}
	}
	
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_product_transactions_by_month_by_location ($product_id, $location_id,  $date = "thisMonth")
//
//		returns $result - list of all product transactions
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_product_transactions_by_month_by_location ($product_id, $location_id,  $date = "thisMonth") {
	$db = $GLOBALS["db"];
	if ($date == "thisMonth") {
		$date = date("Y-m");
	}
	$date_begin = $date;
	$date++;
	$date_end = $date;
	
	$date_begin = $date_begin."-01";
	$date_end = $date_end."-01";


	$result = mysqli_query ($db, "SELECT  `transaction_products`.`transaction_id` ,`date`,`from_location` ,  `to_location` ,  `product_id` ,  `amount`
							FROM  `transaction_products` ,  `transactions`
							WHERE  `product_id` =  '$product_id'
							  AND  `transaction_products`.`transaction_id` =  `transactions`.`id`
							  AND  `transactions`.`to_location` =  '$location_id'
							  AND  date>='$date_begin' AND date<'$date_end'

							  OR
							  		`product_id` =  '$product_id'
							  AND  `transaction_products`.`transaction_id` =  `transactions`.`id`
							  AND  `transactions`.`from_location` = '$location_id'
							  AND  date>='$date_begin' AND date<'$date_end'");
	if (!$result) {
		$mysql_error = mysqli_error($db);
		$error = "<p>get_product_transactions_by_month (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".$mysql_error;
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
		return false;	}
	else {
		if(mysqli_num_rows($result)>0) {
			return $result;
		}
		return 0;
	}
	
	
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_all_transactions ($where = "WHERE 1")
//
//		returns last_transaction
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function get_all_transactions ($where = "WHERE 1"){
	$db = $GLOBALS["db"];
	//echo show_error($where, "info");

	$result = mysqli_query ($db, "SELECT * FROM transactions $where");
	if ($result) {
		return $result;
	}
	else {
		$mysql_error = mysqli_error($db);

		$error = "Не получается вывести трансации из БД! <br> <br><strong>причина</strong>:<br>$mysql_error";
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
		return false;
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_opened_supplies ($where = "WHERE 1")
//
//		returns last_transaction
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function get_opened_supplies  (){
    $db = $GLOBALS["db"];
    //echo show_error($where, "info");

    $result = mysqli_query ($db, "SELECT *
                            FROM `transactions`
                            WHERE `completed`=0 AND `from_location`=0");
    if ($result) {
        return $result;
    }
    else {
        $mysql_error = mysqli_error($db);

        $error = "get_opened_supplies() ERROR: Не получается вывести транзации из БД! <br> <br><strong>причина</strong>:<br>$mysql_error";
        if ($GLOBALS["debug_mode"]==1) {
            echo show_error($error);
        }
        return false;
    }
}

/**
 * @param $location_id
 *
 * @return int
 */
function isSupplyList_open  ($location_id){
    $db = $GLOBALS["db"];
    //echo show_error($where, "info");

    $result = mysqli_query ($db, "SELECT COUNT(id) as num
                            FROM transactions
                            WHERE `completed`=0 AND `to_location`='$location_id'");
    if ($result) {
        $tmp = mysqli_fetch_array($result);
        if ($GLOBALS["debug_mode"]==1) {
           // echo show_error ("There are " . $tmp['num'] . " supply lists opened for #" . $location_id . " location", "info", 1);
        }
        if ($tmp['num']>0)
        return 1;
    }
    else {
        if ($GLOBALS["debug_mode"]==1) {
           // echo show_error ("There are 0 supply lists opened for #" . $location_id . " location", "info", 1);
        }
        return 0;
    }

    return 0;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_opened_returns ($where = "WHERE 1")
//
//		returns last_transaction
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function get_opened_returns  (){
    $db = $GLOBALS["db"];
    //echo show_error($where, "info");

    $result = mysqli_query ($db, "SELECT *
                                    FROM `transactions`
                                    WHERE `completed`=0 AND `to_location`=0");
    if ($result) {
        return $result;
    }
    else {
        $mysql_error = mysqli_error($db);

        $error = "get_opened_supplies() ERROR: Не получается вывести транзации из БД! <br> <br><strong>причина</strong>:<br>$mysql_error";
        if ($GLOBALS["debug_mode"]==1) {
            echo show_error($error);
        }
        return false;
    }
}

/**
 * @param $location_id
 *
 * @return int
 */
function isReturnList_open  ($location_id){
    $db = $GLOBALS["db"];
    //echo show_error($where, "info");

    $result = mysqli_query ($db, "SELECT COUNT(id) as num
                                FROM transactions
                                WHERE `completed`=0 AND `from_location`='$location_id'");
    if ($result) {
        $tmp = mysqli_fetch_array($result);
        if ($GLOBALS["debug_mode"]==1) {
            // echo show_error ("There are " . $tmp['num'] . " supply lists opened for #" . $location_id . " location", "info", 1);
        }
        if ($tmp['num']>0)
            return 1;
    }
    else {
        if ($GLOBALS["debug_mode"]==1) {
            // echo show_error ("There are 0 supply lists opened for #" . $location_id . " location", "info", 1);
        }
        return 0;
    }

    return 0;
}


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_opened_transfers ($where = "WHERE 1")
//
//		returns last_transaction
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function get_opened_transfers  (){
    $db = $GLOBALS["db"];
    //echo show_error($where, "info");

    $result = mysqli_query ($db, "SELECT *
                                FROM `transactions`
                                WHERE `completed`=0
                                AND `to_location`!=0
                                AND `from_location`!=0");
    if ($result) {
        return $result;
    }
    else {
        $mysql_error = mysqli_error($db);

        $error = "get_opened_transfers() ERROR: Не получается вывести транзации из БД! <br> <br><strong>причина</strong>:<br>$mysql_error";
        if ($GLOBALS["debug_mode"]==1) {
            echo show_error($error);
        }
        return false;
    }
}

/**
 * @param $location_id
 *
 * @return int
 */
function isTransfersList_open  ($location_id){
    $db = $GLOBALS["db"];
    //echo show_error($where, "info");

    $result = mysqli_query ($db, "SELECT COUNT(id) as num
                            FROM transactions
                            WHERE `completed`=0
                              AND `to_location`='$location_id'
                              AND `from_location`!=0");
    if ($result) {
        $tmp = mysqli_fetch_array($result);
        if ($GLOBALS["debug_mode"]==1) {
            // echo show_error ("There are " . $tmp['num'] . " supply lists opened for #" . $location_id . " location", "info", 1);
        }
        if ($tmp['num']>0)
            return 1;
    }
    else {
        if ($GLOBALS["debug_mode"]==1) {
            // echo show_error ("There are 0 supply lists opened for #" . $location_id . " location", "info", 1);
        }
        return 0;
    }

    return 0;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		add_transaction ($from_id, $to_id, $title = "", $completed = 0)
//
//		returns last_transaction
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function add_transaction ($from_id, $to_id, $title = "", $completed = 0) {
	$db = $GLOBALS["db"];
	$date = date("Y-m-d");
    $dateForTitle = date("d/m");
    if ($title == "") {
        if ($from_id == 0) {
            $locationName = get_location_name($to_id);
            $title = "Поставка в ".$locationName." $dateForTitle";
        }
        elseif ($to_id == 0) {
            $locationName = get_location_name($from_id);
            $title = "Возврат из  ".$locationName." $dateForTitle";
        }
        else {
            $locationName = get_location_name($to_id);
            $title = "Перевод в ".$locationName." $dateForTitle";
        }
    }
	$result = mysqli_query ($db, "INSERT INTO  transactions (`date`,`from_location`,`to_location`,`title`,`completed`)
							      VALUES ('$date',  '$from_id',  '$to_id', '$title', '$completed')");
	if ($result) {
		
		
		$error = show_error("Была создана новая транзакция!", "success", 1);
		if ($GLOBALS["debug_mode"]==1) {
			echo $error;
		}
		return get_last_transaction_id();
	}
	else {
		$mysql_error = mysqli_error($db);
		
		$error =  show_error("Транзакция не была создана! <br> <br><strong>причина</strong>:<br>$mysql_error");
		if ($GLOBALS["debug_mode"]==1) {
			echo $error;
		}		return false;
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		complete_transaction ($transaction_id)
//
//		returns last_transaction
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function complete_transaction ($transaction_id) {
    $db = $GLOBALS["db"];
    $date = date ("Y-m-d");

    $result = mysqli_query ($db, "UPDATE transactions SET `completed`=1, `date`='$date' WHERE `id`=$transaction_id;");

    if(!$result) {return false;}

    return true;

}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_transaction ($transaction_id) 																														//
//																																									//
//		returns last_transaction																																	//																																									
//																																									//
																																									//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_transaction ($transaction_id) {
	$db = $GLOBALS["db"];
		// get the data from the DB
	$result = mysqli_query ($db, "SELECT * FROM transactions WHERE id = '$transaction_id'");
	
	// if there was an error
	if (!$result) {
		$error = "<p>get_transaction (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		
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
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_last_transaction () 
//
//		returns last_transaction
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_last_transaction () {
	$db = $GLOBALS["db"];
		// get the data from the DB
	$result = mysqli_query ($db, "SELECT * FROM transactions ORDER BY id DESC LIMIT 1");
	
	// if there was an error
	if (!$result) {
		$error = "<p>get_last_transaction (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_last_transaction_id ()
//
//		returns last_transaction
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * @return int
     */
    function get_last_transaction_id () {
	$db = $GLOBALS["db"];
		// get the data from the DB
	$result = mysqli_query ($db, "SELECT id FROM transactions ORDER BY id DESC LIMIT 1");

	// if there was an error
	if (!$result) {
		$error = "<p>get_last_transaction_id (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);

		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}

		return 0;
	}

	// if nothing returned
	if (mysqli_num_rows($result)>0) {
		$tmp = mysqli_fetch_array ($result);
		return $tmp["id"];
	}
	// if nothing returned
	else {
		return 0;
	}
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_last_supply_id ($location_id)
//
//		returns last_transaction
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_last_supply_id ($location_id) {
    $db = $GLOBALS["db"];
    // get the data from the DB
    $result = mysqli_query ($db, "SELECT `id`
                            FROM `transactions`
                            WHERE `to_location`='$location_id'
                            ORDER BY id DESC LIMIT 1");

    // if there was an error
    if (!$result) {
        $error = "<p>get_last_supply_id (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);

        if ($GLOBALS["debug_mode"]==1) {
            echo show_error($error);
        }

        return 0;
    }

    // if nothing returned
    if (mysqli_num_rows($result)>0) {
        $tmp = mysqli_fetch_array ($result);
        return $tmp["id"];
    }
    // if nothing returned
    else {
        return 0;
    }
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_last_return_id ($location_id)
//
//		returns last_transaction
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_last_return_id ($location_id) {
    $db = $GLOBALS["db"];
    // get the data from the DB
    $result = mysqli_query ($db, "SELECT `id`
                        FROM `transactions`
                        WHERE `from_location`='$location_id'
                        ORDER BY id DESC LIMIT 1");

    // if there was an error
    if (!$result) {
        $error = "<p>get_last_return_id (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);

        if ($GLOBALS["debug_mode"]==1) {
            echo show_error($error);
        }

        return 0;
    }

    // if nothing returned
    if (mysqli_num_rows($result)>0) {
        $tmp = mysqli_fetch_array ($result);
        return $tmp["id"];
    }
    // if nothing returned
    else {
        return 0;
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_product_transaction_amount ($product_id, $transaction_id)
//
//		returns product amount
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * @param $product_id
 * @param $transaction_id
 */
function get_product_transaction_amount ($product_id, $transaction_id){
    $db = $GLOBALS["db"];
    // get the data from the DB
    $result = mysqli_query ($db, "SELECT `amount`
                                    FROM `transaction_products`
                                    WHERE `transaction_id`='$transaction_id'
                                      AND `product_id`='$product_id' ");

    // if there was an error
    if ($result&&mysqli_num_rows($result)>0) {
        $tmp = mysqli_fetch_array($result);
        return $tmp['amount'];
    }

    return 0;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//      update_transaction_product ($product_id, $transaction_id, $amount) //
//		returns product amount
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function update_transaction_product ($product_id, $transaction_id, $amount) {
    $db = $GLOBALS["db"];
    // get the data from the DB
    $result = mysqli_query ($db,
                            "UPDATE `transaction_products`
                              SET `transaction_id`='$transaction_id',`product_id`='$product_id',`amount`='$amount'
                            WHERE `transaction_id`='$transaction_id'
                              AND `product_id`='$product_id'");

    // if there was an error
    if (!$result) {
        $error = "<p>update_transaction_product (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);

        if ($GLOBALS["debug_mode"]==1) {
            echo show_error($error);
        }

        return false;
    }
    else {
        $error = "<p>update_transaction_product (): Transaction $transaction_id Was Successfully Updated";
        if ($GLOBALS["debug_mode"]==1) {
            echo show_error($error, "success");
        }
        return true;
    }
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		add_transaction_product ($transaction_id, $product_id, $amount)
//
//		returns last_transaction
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function add_transaction_product ($transaction_id, $product_id, $amount) {
	$db = $GLOBALS["db"];
	// get the data from the DB
	$result = mysqli_query ($db, "INSERT INTO `transaction_products` (`transaction_id`, `product_id`, `amount`) VALUES ('$transaction_id', '$product_id', '$amount')");
	
	// if there was an error
	if (!$result) {
		$error = "<p>add_transaction_product (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
		
		return false;
	}
	else {
		return true;
	}
}


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		get_all_reasons () 
//
//		returns last_transaction
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function get_all_reasons () {
	$db = $GLOBALS["db"];
		// get the data from the DB
	$result = mysqli_query ($db, "SELECT * FROM reasons ORDER BY type DESC");
	
	// if there was an error
	if (!$result) {
		$error = "<p>get_all_reasons (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db);
		
		if ($GLOBALS["debug_mode"]==1) {
			echo show_error($error);
		}
		
		return 0;
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
//		delete_product_transactions_in_location ($product_id, $location_id) 
//
//		returns location name
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/**
 * @param $product_id
 * @param $location_id
 * @return bool|string
 */
function delete_product_transactions_in_location ($product_id, $location_id) {
	$db = $GLOBALS["db"];
	$transResult = get_all_product_recieves ($product_id, $location_id);
    $error = "";
	if ($transResult){
		$transactionData = mysqli_fetch_array($transResult);
		
		do {
			$product_id = $transactionData['product_id'];
			$transaction_id = $transactionData['transaction_id'];
			$product_name = get_product_name($product_id);
			$result1 = mysqli_query ($db, "DELETE FROM transaction_products WHERE `transaction_id` = '$transaction_id' AND `product_id` = '$product_id'");
			if ($result1) {
				$tmp = show_error("<p>Информация по поставкам \"<strong>".$product_name."</strong>\" была успешно удалена!</p>","success");
				
				$error = $error.$tmp;
			}		

			else {
				$tmp = show_error("<p>delete_product_transactions_in_location (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db));
				
				$error = $error.$tmp;
			}
			
		}while($transactionData = mysqli_fetch_array($transResult));
		return $error;
	}
	
	return false;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		delete_product_transactions_in_location ($product_id, $location_id)
//
//		returns location name
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @param $product_id
     * @param $location_id
     * @return bool|string
     */
function delete_product_in_transaction ($product_id, $transaction_id) {
        $db = $GLOBALS["db"];
        if ($GLOBALS["debug_mode"]==1) {
            echo show_error("product_id: $product_id | transaction_id: $transaction_id","info",1);
        }
        $error = "";
        $product_name = get_product_name($product_id);
        $result1 = mysqli_query ($db, "DELETE FROM transaction_products WHERE `transaction_id` = '$transaction_id' AND `product_id` = '$product_id'");
        if ($result1) {
            $tmp = show_error("<p>Информация по поставкам \"<strong>".$product_name."</strong>\" была успешно удалена!</p>","success");

            $error = $error.$tmp;

            return $error;
            //return true;
        }

        else {
            $tmp = show_error("<p>delete_product_in_transaction (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db));

            $error = $error.$tmp;
            return $error;
        }

    }

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		delete_product_returns_in_location ($product_id, $location_id) 
//
//		returns location name
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function delete_product_returns_in_location ($product_id, $location_id) {
	$db = $GLOBALS["db"];
	$transResult = get_all_product_returns ($product_id, $location_id);
    $error = "";
	
	if ($transResult){
		$transactionData = mysqli_fetch_array($transResult);
		
		do {
			$product_id = $transactionData['product_id'];
			$transaction_id = $transactionData['transaction_id'];
			$product_name = get_product_name($product_id);
			$result1 = mysqli_query ($db, "DELETE FROM transaction_products WHERE `transaction_id` = '$transaction_id' AND `product_id` = '$product_id'");
			if ($result1) {
				$tmp = show_error("<p>Информация по поставкам \"<strong>".$product_name."</strong>\" была успешно удалена!</p>","success");
				
				$error = $error.$tmp;
			}		

			else {
				$tmp = show_error("<p>delete_product_returns_in_location (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db));
				
				$error = $error.$tmp;
			}
			
		}while($transactionData = mysqli_fetch_array($transResult));
		return $error;
	}
	
	return false;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//		delete_transaction delete_transaction ($transaction_id)
//
//		returns true/false
//
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function delete_transaction ($transaction_id) {
    $db = $GLOBALS["db"];
    $error = "";
    $transaction = get_transaction($transaction_id);

    $result = mysqli_query($db, "DELETE FROM `transactions` WHERE `id`='$transaction_id'");
    if ($result) {
        $tmp = show_error("<p>Информация по поставкам \"<strong>".$transaction['name']."</strong>\" была успешно удалена!</p>","success");

        $error = $error.$tmp;
    }

    else {
        $tmp = show_error("<p>delete_transaction (): Error while retrieving data from the DB. Please notify the administrator on support@freeurmind.net<br><strong>Error code:</strong></p>".mysqli_error($db));

        //$error = $error.$tmp;
    }
    if ($GLOBALS["debug_mode"]==1) {
        echo $error;
    }

    return $result;
}

/*
 *  get_products_received_today_per_location ($location_id)
 *
 */
    function get_products_received_today_per_location  ($location_id) {
        $db = $GLOBALS["db"];
        $result = mysqli_query ($db, "  SELECT `transactions`.`id`, `transaction_products`.`product_id`,`name`,`price` ,`transaction_products`.`amount`
                                        FROM `transactions`, `transaction_products`, `products`, `location_products`
                                        WHERE `transactions`.`date` = CURDATE()
                                        AND `transactions`.`id` = `transaction_products`.`transaction_id`
                                        AND `products`.`id` = `transaction_products`.`product_id`
                                        AND `location_products`.`product_id` = `transaction_products`.`product_id`
                                        AND `transactions`.`to_location` = '$location_id'
                                ");
        if ($result) {
            return $result;
        }
        else {
            $mysql_error = mysqli_error($db);

            $error = "get_products_received_today_per_location() ERROR: Не получается вывести транзации из БД! <br> <br><strong>причина</strong>:<br>$mysql_error";
            if ($GLOBALS["debug_mode"]==1) {
                echo show_error($error);
            }
            return false;
        }
    }

    /*
     *  get_products_received_today_per_location ($location_id)
     *
     */
    function get_products_received_by_day_per_location  ($location_id, $from, $to) {
        $db = $GLOBALS["db"];
        $result = mysqli_query ($db, "  SELECT `transactions`.`id`, `transaction_products`.`product_id`,`name`,`price` ,`transaction_products`.`amount`
                                        FROM `transactions`, `transaction_products`, `products`, `location_products`
                                        WHERE `transactions`.`date`  BETWEEN '$from' AND '$to'
                                        AND `transactions`.`id` = `transaction_products`.`transaction_id`
                                        AND `products`.`id` = `transaction_products`.`product_id`
                                        AND `location_products`.`product_id` = `transaction_products`.`product_id`
                                        AND `transactions`.`to_location` = '$location_id'
                                ");
        if ($result) {
            return $result;
        }
        else {
            $mysql_error = mysqli_error($db);

            $error = "get_products_received_by_day_per_location() ERROR: Не получается вывести транзации из БД! <br> <br><strong>причина</strong>:<br>$mysql_error";
            if ($GLOBALS["debug_mode"]==1) {
                echo show_error($error);
            }
            return false;
        }
    }


	function get_supply_dates_between_ratio ($location_id, $from, $to) {
		$db = $GLOBALS["db"];
		$result = mysqli_query ($db, "  SELECT DISTINCT `date`
                                        FROM `transactions`
                                        WHERE `transactions`.`date`  BETWEEN '$from' AND '$to'
                                        AND `transactions`.`to_location` = '$location_id'
                                ");
		if ($result) {
			return $result;
		}
		else {
			$mysql_error = mysqli_error($db);

			$error = "get_supply_dates_between_ratio() ERROR: Не получается вывести транзации из БД! <br> <br><strong>причина</strong>:<br>$mysql_error";
			if ($GLOBALS["debug_mode"]==1) {
				echo show_error($error);
			}
			return false;
		}
	}
?>