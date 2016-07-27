<? 
	include("../blocks/db.php");
	include("../blocks/functions.php");
	mysqli_set_charset($db, 'utf8');

    if (isset($_POST['product_id'])) { $product_id = $_POST['product_id']; if(sizeof($product_id)<1) {unset($product_id);}}
    if (isset($_POST['amount'])) { $amount = $_POST['amount']; if(sizeof($amount)<1) {unset($amount);}}
    if (isset($_POST['transaction_id'])) {$transaction_id = $_POST['transaction_id']; if ($transaction_id == "") {unset($transaction_id); }}
	

        $storage_id = 1;
        $error = "";

        //echo show_error("transaction_id: $transaction_id, product_id:$product_id, amount:$amount");
        if (isset($transaction_id)&&isset($product_id)&&isset($amount)) {

            $transaction = get_transaction($transaction_id);
            $to_location = $transaction['to_location'];
            $from_location = $transaction['from_location'];
            //echo show_error("to_location: $to_location, from_location:$from_location");

            if ($amount > 0) {
                $prev_amount = get_product_transaction_amount($product_id, $transaction_id);
                // add transaction data
                $result1 = update_transaction_product ($transaction_id, $product_id, $amount);

                if ($result1) {
                    // get old amount and add the new one
                    if ($to_location!=0) {
                        $new_amount = get_product_amount_per_location ($product_id, $to_location, $db);

                        // the product amount subtract the previous and adds the new one
                        $new_amount = $new_amount-$prev_amount+$amount;
                        //echo "new_amount: ".$new_amount." | <br>";

                        $error = $error . show_error ("$product_id, $to_location, $new_amount", "success", 0);
                        // update the new amount
                        if (get_location_product_details ($to_location, $product_id) == 0) {
                            $result2 = add_location_product ($to_location, $product_id, $price, $new_amount);
                        }
                        else {
                            $result2 = update_product_amount ($to_location, $product_id, $new_amount, $db);
                        }


                        $location_id = $to_location;
                        $oper = "+";
                        $transRes = get_all_product_recieves ($product_id, $location_id);
                    }
                    else {
                        // get old amount and add the new one
                        $new_amount = get_product_amount_per_location ($product_id, $from_location, $db);
                        $error = $error . show_error ("product_id: $product_id, from_location:$from_location, /$new_amount:$new_amount", "success", 0);
                        //echo "new_amount: ".$new_amount." | ";
                        $new_amount = $new_amount+$prev_amount-$amount;
                        //echo "new_amount: ".$new_amount." | <br>";
                        // update the new amount
                        if (get_location_product_details ($from_location, $product_id) == 0) {
                            $result3 = add_location_product ($from_location, $product_id, $price, $new_amount);
                        }
                        else {
                            $result3 = update_product_amount ($from_location, $product_id, $new_amount, $db);
                        }
                        $error = show_error ("<span class='icon-thumbs-up'></span> Все чики пуки!", "success", 0);


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
                                    <a href="<?=$root?>pages/locations/locations.php?category_id=<?=$category_id?>&id=<?=$location_id?>&cmd=canceltransaction&transaction_id=<?=$transaction['transaction_id']?>&product_id=<?=$products['id']?>"><?=$oper?><span class="amount"><?=$transaction["amount"]?></span></a>
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
            }

            // TD CONTENT



        }
?>