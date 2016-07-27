<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
	mysqli_set_charset($db, 'utf8');

    if (isset($_POST['to_location'])) { $to_location = $_POST['to_location']; if($to_location=="") {unset($to_location);}}
    if (isset($_POST['from_location'])) { $from_location = $_POST['from_location']; if($from_location=="") {unset($from_location);}}
    if (isset($_POST['product_id'])) { $product_id = $_POST['product_id']; if(sizeof($product_id)<1) {unset($product_id);}}
    if (isset($_POST['amount'])) { $amount = $_POST['amount']; if(sizeof($amount)<1) {unset($amount);}}
    if (isset($_POST['transaction_id'])) {$transaction_id = $_POST['transaction_id']; if ($transaction_id == "") {unset($transaction_id); }}
    if (isset($_POST['completed'])) {$completed = $_POST['completed'];} if ($completed == "") {$completed = 0;}
	

			$storage_id = 1;
			 //echo "<ul><li>category_id $category_id</li><li>name: $name</li></ul>";
			if (isset($to_location)&&isset($from_location)&&isset($product_id)&&isset($amount)) {
				
				$new_transaction_id = add_transaction ($from_location, $to_location, "", $completed);
				if ($new_transaction_id) {

                    $error = show_error ("Транзакция была добавлена!", "success", 0);

                    if ($amount > 0) {
                        // add transaction data
                        $result1 = add_transaction_product ($new_transaction_id, $product_id, $amount, $db);

                        if ($result1) {
                            // get old amount and add the new one
                            $new_amount = get_product_amount_per_location ($product_id, $to_location, $db);
                            //echo "new_amount: ".$new_amount." | ";
                            $new_amount += $amount;
                            //echo "new_amount: ".$new_amount." | <br>";

                            $error = $error . show_error ("$product_id, $to_location, $new_amount", "success", 0);
                            // update the new amount
                            $result2 = update_product_amount ($to_location, $product_id, $new_amount, $db);


                            if ($from_location != 0) {
                                // get old amount and add the new one
                                $new_amount = get_product_amount_per_location ($product_id, $from_location, $db);
                                $error = $error . show_error ("$product_id, $from_location, $new_amount", "success", 0);
                                //echo "new_amount: ".$new_amount." | ";
                                $new_amount -= $amount;
                                //echo "new_amount: ".$new_amount." | <br>";
                                // update the new amount
                                $result3 = update_product_amount ($from_location, $product_id, $new_amount, $db);

                                $error = show_error ("<span class='icon-thumbs-up'></span> Все чики пуки!", "success", 0);
                            }
                        }
                    }

                    // TD CONTENT



                    if ($to_location != 0) {
                        $location_id = $to_location;
                        $oper = "+";
                        $transRes = get_all_product_recieves ($product_id, $location_id);
                    } else {
                        $location_id = $from_location;
                        $oper = "-";
                        $transRes = get_all_product_returns ($product_id, $location_id);
                    }


                    //if there were transactions this month:
                    if ($transRes) {
                        $transaction = mysqli_fetch_array($transRes);
                        echo "<div class='four_sixth'>
                                        <ul class='list tagcloud rnd8 font-small'>";


                        do{
                            if ($transaction['from_location']==0) {
                                $url = "view_supply.php";
                            }
                            else {
                                $url = "view_transaction.php";
                            }
                            //$link = make_link($url,"?transaction_id=".$transaction["transaction_id"], $oper." ".$transaction["amount"]);
                            if ($transaction["amount"] !=0) {
                                ?>
                                <li>
                                    <a href="#"><?=$oper?><span class="amount"><?=$transaction["amount"]?></span></a>
                                    <input type="hidden" name="transaction_id" value="<?=$transaction['transaction_id']?>">
                                    <input type="hidden" name="product_id" value="<?=$products['id']?>">
                                </li>
                            <?
                            }
                        }while ($transaction = mysqli_fetch_array($transRes));
                        echo "</ul>
                    </div>
                    <div class='hidden editDiv'>
                        <input id='" . $product_id . "' name='transaction' type='text' class='' size='5' value='' title=''>
                        <span class='addSupply'>
                            <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                            <a href='#' class='cancel font-medium'><span
                                    class='icon-remove fl-right red'></span></a>
                        </span>
                        <span class='editSupply'>
                            <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                            <a href='#' class='cancel font-medium'><span class='icon-remove fl-right red'></span></a>
                        </span>
                    </div>
                    <div class='two_sixth add_delete'>
                            <a href='#' name='addSupply'><span class='icon-plus'></span></a>&nbsp;|
                            <a href='../transactions/delete_transactions.php?product_id=" . $products['id'] . "&&sourcePage=" . $page . "&&idParam=" . $location_id . "'><span class='icon-remove red'></span></a>
                    </div>";
                    }
                    else{
                        ?>
                        <td class='center transaction' nowrap>
                        <div class='four_sixth toggle-wrapper'>-</div>
                        <div class='hidden editDiv'>
                            <input id='<? echo $product_id; ?>' name='transaction' type='text' class='' size='5' value='' title="">
                                <span class='editSupply'>
                                    <a href='#' class='save font-medium'><span class='icon-ok green'></span></a>&nbsp;
                                    <a href='#' class='cancel font-medium' ><span class='icon-remove fl-right red'></span></a>
                                </span>
                        </div>
                        <div class='two_sixth add_delete' >
                            <a href='#' name='addSupply'><span class='icon-plus'></span></a>
                        </div>

                    <?
                    }


                    // TD CONTENT END
                }
				else {
                    // error
                    echo "0";
				}
			}
?>