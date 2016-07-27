jQuery(document).ready(function ($) {

	var root = "http://inventory.freeurmind.net/";
	var fieldOldValue = "";
	var fieldNewValue = "";
	/* list-table */

	/*######### change product name ##############################################################################################*/
    $("a[name=changeName]").click(function () {
        var thisName = $(this);
		// default span (with the link) reference
        fieldOldValue = thisName.text();
		var defaultSpan = thisName.parent();
		// edit-title span (with the delete link) reference 
		var deleteSpan = defaultSpan.next();
		// edit span (with the input) reference 
		var editSpan = deleteSpan.next();
		// hide the span with link
		defaultSpan.hide();
		deleteSpan.hide();
		// hide the span with input + edit icons
		editSpan.removeClass("hidden");
		
		// put in the input the value of the current name
		editSpan.children(0).val(defaultSpan.find("[name=changeName]").text());
		editSpan.children(0).focus();
		
		return false;
	});
	
	$(".list-table .product a.cancel").click(function() {
		var thisBtn = $(this);
		// Get the input
		var thisInput = thisBtn.parent().prev();
		// get the whole edit panel reference
		var editNameSpan = thisBtn.parent().parent();
		// delete span (with the link) reference 
		var deleteSpan = editNameSpan.prev();
		// default span (with the link) reference
		var defaultSpan = deleteSpan.prev();
		
		editNameSpan.addClass("hidden");
		defaultSpan.show();
		deleteSpan.show();
	});
	
	$(".list-table .product a.save").click(function(evt) {
		var thisBtn = $(this);
		// Get the input
		var thisInput = thisBtn.parent().prev();
		// get the whole edit panel reference
		var editNameSpan = thisBtn.parent().parent();
		// delete span (with the link) reference 
		var deleteSpan = editNameSpan.prev();
		// default span (with the link) reference 
		var defaultSpan = editNameSpan.prev().prev();
		// get the edit span area with the save|cancel links
				
		var product_id = thisInput.attr('id');
		var location_id = $("#location_id").val();
		/* UPDATE TABLE AREA */
		// get the new field value
		fieldNewValue = thisInput.val();
		if (fieldNewValue != fieldOldValue) {

			thisBtn.children(0).removeClass("icon-ok").addClass(" icon-spinner icon-spin");
			thisBtn.next().toggle();

            var tmp = thisBtn.parent().parent().parent();
			$.ajax({
				type: "POST",
				url: root+"/pages/ajax/update_product_name.php",
				data: {id: product_id, name: fieldNewValue},
				beforeSend: function(){
				},
				success: function(){
                    //var sameProductsOnPage = $('a:contains('+fieldOldValue+')');
                    var sameProductsOnPage = $('a').filter(function(index) { return $(this).text() === fieldOldValue; });
					defaultSpan.find("a[name=changeName]").text(fieldNewValue);
                    sameProductsOnPage.text(fieldNewValue);

					tmp.addClass("bg_green", "fast", function(){tmp.removeClass("bg_green", "slow");});
                    sameProductsOnPage.parent().parent().addClass("bg_green", "fast", function(){sameProductsOnPage.parent().parent().removeClass("bg_green", "slow");});
					// whatever is the value in the field, put the one was there before

				},
				error: function(){
					//alert("oh no!");
					//$("#putshithere").val("the field wasn't updated");
					tmp.addClass("bg_red", "fast", function(){tmp.removeClass("bg_red", "slow");});
					thisInput.focus().val(fieldOldValue);
				}
			});
			$(this).children(0).removeClass("icon-spinner").removeClass("icon-spin").addClass(" icon-ok");
			$(this).next().toggle();

			editNameSpan.addClass("hidden");
			defaultSpan.show();
			deleteSpan.show();

		}
		
	});




    /*######### change product price ######################################################################################################*/
    $("a[name=changePrice]").click(function (evt){
        var thisPrice = $(this);
        // default span (with the link) reference
        var defaultSpan = thisPrice.parent();
        // edit span (with the input) reference
        var editSpan = defaultSpan.next();

        // hide the span with link
        defaultSpan.hide();
        // hide the span with input + edit icons
        editSpan.removeClass("hidden");

        editSpan.children(0).val(defaultSpan.find("[name=changePrice]").text());
        editSpan.children(0).focus().select();
        return false;
    });

    $(".list-table .price a.cancel").click(function(evt) {
        var thisBtn = $(this);
        // Get the input
        var thisInput = thisBtn.parent().prev();
        // get the whole edit panel reference
        var editPriceSpan = $(this).parent().parent();
        // default span (with the link) reference
        var defaultSpan = editPriceSpan.prev();

        editPriceSpan.addClass("hidden");
        defaultSpan.show();
    });

    $(".list-table .price a.save").click(function(evt) {
        var thisBtn = $(this);
        // Get the input
        var thisInput = thisBtn.parent().prev();
        // get the whole edit panel reference
        var editPriceSpan = thisBtn.parent().parent();
        // default span (with the link) reference
        var defaultSpan = editPriceSpan.prev();
        // get the edit span area with the save|cancel links


        var product_id = thisInput.attr('id');
        var location_id = $("#location_id").val();
        /* UPDATE TABLE AREA */
        // get the new field value
        fieldNewValue = thisInput.val();
        if (fieldNewValue != fieldOldValue) {

            $(this).children(0).removeClass("icon-ok").addClass(" icon-spinner icon-spin");
            $(this).next().toggle();

            // DEBUG:
            // alert ("product_id:"+ product_id+", price:"+ fieldNewValue+", location_id:"+ location_id);

            $.ajax({
                type: "POST",
                url: root+"/pages/ajax/update_product_price.php",
                data: {product_id: product_id, price: fieldNewValue, location_id: location_id},
                beforeSend: function(){
                },
                success: function(){

                    // fieldNewValue - is the new value of the fieldn, in this case - price (a)
                    var thisTD = thisBtn.parent().parent().parent(); 	// price 	(a) TD
                    var thisAmount = thisTD.next().next(); 				// amount 	(x) TD
                    var thisRecieved = thisAmount.next();				// recieved (a*x) TD
                    var thisReturnAmount = thisRecieved.next().find("input[name=returnAmount]").val(); // return amount (z)
                    var thisReturned = thisRecieved.next().next();		// return amount (z) TD
                    var thisLeft = thisReturned.next();					// left amount (y) TD
                    var thisSoldTotal = thisLeft.next();				// sold total [(x-z-y)*a] TD
                    var tmpSoldVal = "";
                    defaultSpan.find("a[name=changePrice]").text(fieldNewValue);


                    // visible update of related value
                    thisRecieved.text("₪ " + (fieldNewValue*thisAmount.text()));
                    thisReturned.text("₪ " + (fieldNewValue*thisReturnAmount));
                    tmpSoldVal = (thisAmount.text()-thisReturnAmount-thisLeft.text())*fieldNewValue;
                    if (tmpSoldVal<0) tmpSoldVal = 0;
                    thisSoldTotal.text("₪ " + tmpSoldVal);

                    // mark green updated values
                    thisTD.addClass("bg_green", "fast", function(){thisTD.removeClass("bg_green", "slow");});
                    thisRecieved.addClass("bg_green", "fast", function(){thisRecieved.removeClass("bg_green", "slow");});
                    thisReturned.addClass("bg_green", "fast", function(){thisReturned.removeClass("bg_green", "slow");});
                    thisSoldTotal.addClass("bg_green", "fast", function(){thisSoldTotal.removeClass("bg_green", "slow");});
                },
                error: function(){
                    //alert("oh no!");
                    //$("#putshithere").val("the field wasn't updated");
                    thisTD.addClass("bg_red", "fast", function(){thisTD.removeClass("bg_red", "slow");});
                    defaultSpan.find(a[name=changePrice]).text(fieldOldValue);
                }
            });
            $(this).children(0).removeClass("icon-spinner").removeClass("icon-spin").addClass(" icon-ok");
            $(this).next().toggle();


            editPriceSpan.addClass("hidden");
            defaultSpan.show();

        }

    });










/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//     EDIT TRANSACTION / RETURN BUTTON
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // clicked on transaction product count link    [  example: (+10) ]
    // get the value
    // show input
    //      - hide the whole toggle_wrapper div
    // show the edit save | cancel (V X) buttons
    //     - hide the ones for add
    $(".list-table .list.tagcloud li a").click(function (evt){
        /*
        // ---------- editSupply ------------------------------------------//
        var thisLink = $(this);
        var toggle_wrapper = thisLink.parent().parent().parent().parent();
        var thisTD = toggle_wrapper.parent();

        var transaction_id = thisTD.find("input[name=transaction_id]").val();
        var product_id = thisTD.find("input[name=product_id]").val();
        // get the transaction for edit

        // default span (with the link) reference
        // console.log(toggle_wrapper.parent().html());
        // edit span (with the input) reference
        var inputDiv = toggle_wrapper.next();
        var addSupply = inputDiv.find(".addSupply");
        var editSupply = inputDiv.find(".editSupply");


        var add_delete = inputDiv.next();

        // HIDE & SHOW AREA
        // hide the span with link
        toggle_wrapper.hide();
        // hide the span with input + edit icons
        inputDiv.removeClass("hidden");
        add_delete.hide();
        addSupply.hide();
        editSupply.show();

        // SETTING THE INPUT
        var theInput = inputDiv.children(0);
        theInput.append("<input type='hidden' name='transaction_id' value='"+transaction_id+"'>").append("<input type='hidden' name='product_id' value='"+product_id+"'>");
        theInput.val(thisLink.find(".amount").text());
        theInput.focus().select();

        return false;
        */
    });




/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//     ADD TRANSACTION / RETURN BUTTON
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // ---------- addSupply ------------------------------------------//
    // also true for add return
	$("a[name=addSupply]").click(function (evt){

		var thisLink = $(this);
        var thisTD = thisLink.parent().parent();

        // the add_delete div
        var add_delete = thisTD.find(".add_delete");

		// toggle_wrapper div (with the link) reference(s)
		var toggle_wrapper = thisTD.children(0);
		// editDiv (with the input) reference
		var inputDiv = toggle_wrapper.next();
        var addSupply = inputDiv.find(".addSupply");
        var editSupply = inputDiv.find(".editSupply");
		// hide the span with link
        toggle_wrapper.hide();

		// hide the span with input + edit icons
        inputDiv.removeClass("hidden");
        inputDiv.show();
        add_delete.hide();
        editSupply.hide();
        addSupply.show();

        var theInput = inputDiv.children(0);
        theInput.val(toggle_wrapper.find("[name=addSupply]").text());
        theInput.focus().select();
		return false;
	});

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//     CANCEL BUTTON
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $(".list-table .return .addSupply a.cancel").click(cancelBtn);
    $(".list-table .return .editSupply a.cancel").click(cancelBtn);
	$(".list-table .transaction .addSupply a.cancel").click(cancelBtn);
    $(".list-table .transaction .editSupply a.cancel").click(cancelBtn);
    function cancelBtn() {
        var thisBtn = $(this);
        //alert(thisBtn.html());
        // get the whole edit panel reference
        var inputDiv = thisBtn.parent().parent();
        var thisInput = inputDiv.children(0);
        // default span (with the link) reference
        var toggle_wrapper = inputDiv.prev();
        var plusDiv = inputDiv.next();

        inputDiv.addClass("hidden");
        toggle_wrapper.show();
        plusDiv.show()
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//     SAVE TRANSACTION BUTTON
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    $(".list-table .transaction .editSupply a.save").click(function() {
        var thisTD = $(this).parent().parent().parent().parent().find('.transaction');
        var thisInput = thisTD.find("input[name=transaction]");

        // get the whole edit panel reference
        var inputDiv = thisTD.find(".inputDiv");
        // default span (with the link) reference
        var toggle_wrapper = thisTD.find(".toggle-wrapper");
        var add_delete = thisTD.find(".add_delete");

        // variables that gonna go to the add_transaction.php ajax page
        var to_location, from_location, product_id, completed, transaction_id;
        product_id = thisTD.find("input[name=product_id]").val();
        transaction_id = thisTD.find("input[name=transaction_id]").val();
        to_location = $("#location_id").val();
        from_location = 0;
        completed = 1;
        /* UPDATE TABLE AREA */
        // get the new field value
        fieldNewValue = thisInput.val();

        if (fieldNewValue != fieldOldValue) {

            $(this).children(0).removeClass("icon-ok").addClass(" icon-spinner icon-spin");
            $(this).next().toggle();

            console.log("transaction_id:"+transaction_id+", product_id:"+product_id+", amount:"+fieldNewValue);
            $.ajax({
                type: "POST",
                url: root+"/pages/ajax/update_transaction.php",
                data: {
                    transaction_id: transaction_id,
                    product_id: product_id,
                    amount: fieldNewValue
                },
                beforeSend: function () {
                    //thisTD.html("<span class='icon-spinner icon-spin'></span>");
                },
                success: function (data) {
                    // fieldNewValue - is the new value of the field, in this case - price (a)
                    var thisAmount = thisTD.next(); 				// amount 	(x) TD
                    var thisReceived = thisAmount.next();				// recieved (a*x) TD
                    var thisReturnAmount = thisReceived.next().find("input[name=returnAmount]").val(); // return amount (z)
                    var thisReturned = thisReceived.next().next();		// return amount (z) TD
                    var thisLeft = thisReturned.next();					// left amount (y) TD
                    var thisSoldTotal = thisLeft.next();				// sold total [(x-z-y)*a] TD
                    var tmpSoldVal = "";

                    // put the data into the TD
                    thisTD.html(data);


                    $.ajax({type: "POST",url: "../ajax/get_location_product_amount.php",data: { location_id: to_location,product_id: product_id}, success: function(result){
                        thisAmount.text(result);

                        var price = thisTD.prev().find('a').text();
                        // visible update of related value
                        thisReceived.text("₪ " + (price * thisAmount.text()));
                        thisReturned.text("₪ " + (price * thisReturnAmount));


                        thisLeft.text(parseInt(thisLeft.text())+parseInt(fieldNewValue));
                        tmpSoldVal = (thisAmount.text() - thisReturnAmount - thisLeft.text()) * price;
                        if (tmpSoldVal < 0) tmpSoldVal = 0;
                        thisSoldTotal.text("₪ " + tmpSoldVal);

                        // mark green updated values
                        thisTD.addClass("bg_green", "fast", function () {
                            thisTD.removeClass("bg_green", "slow");
                        });
                        thisAmount.addClass("bg_green", "fast", function () {
                            thisAmount.removeClass("bg_green", "slow");
                        });
                        thisReceived.addClass("bg_green", "fast", function () {
                            thisReceived.removeClass("bg_green", "slow");
                        });
                        thisReturned.addClass("bg_green", "fast", function () {
                            thisReturned.removeClass("bg_green", "slow");
                        });
                        thisLeft.addClass("bg_green", "fast", function () {
                            thisLeft.removeClass("bg_green", "slow");
                        });
                        thisSoldTotal.addClass("bg_green", "fast", function () {
                            thisSoldTotal.removeClass("bg_green", "slow");
                        });
                    }});


                },
                error: function () {
                    //alert("oh no!");
                    //$("#putshithere").val("the field wasn't updated");
                    thisTD.addClass("bg_red", "fast", function () {
                        thisTD.removeClass("bg_red", "slow");
                    });
                    toggle_wrapper.find('a[name=addSupply]').text(fieldOldValue);
                }
            });
            $(this).children(0).removeClass("icon-spinner").removeClass("icon-spin").addClass(" icon-ok");
            $(this).next().toggle();
            inputDiv.addClass("hidden");
            toggle_wrapper.show();
            add_delete.show();
        }



    });

    $(".list-table .transaction .addSupply a.save").click(function() {
        var thisTD = $(this).parent().parent().parent().parent().find('.transaction');
        var thisInput = $(this).parent().prev();

        // get the whole edit panel reference
        var inputDiv = $(this).parent().parent();
        // default span (with the link) reference
        var toggle_wrapper = inputDiv.prev();
        var add_delete = inputDiv.next();

        // variables that gonna go to the add_transaction.php ajax page
        var to_location, from_location, product_id, completed;
        product_id = thisInput.attr('id');
        to_location = $("#location_id").val();
        from_location = 0;
        completed = 1;
        /* UPDATE TABLE AREA */
        // get the new field value
        fieldNewValue = thisInput.val();

        if (fieldNewValue != fieldOldValue) {

            $(this).children(0).removeClass("icon-ok").addClass(" icon-spinner icon-spin");
            $(this).next().toggle();

            // DEBUG: alert ("to_location:"+to_location+", from_location:"+from_location+", product_id:"+product_id+", amount:"+fieldNewValue+", completed:"+completed);
            $.ajax({
                type: "POST",
                url: root+"/pages/ajax/add_transaction.php",
                data: {
                    to_location: to_location,
                    from_location: from_location,
                    product_id: product_id,
                    amount: fieldNewValue,
                    completed: completed
                },
                beforeSend: function () {
                    thisTD.html("<span class='icon-spinner icon-spin'></span>");
                },
                success: function (data) {
                    location.reload();
                },
                error: function () {
                    //alert("oh no!");
                    //$("#putshithere").val("the field wasn't updated");
                    thisTD.addClass("bg_red", "fast", function () {
                        thisTD.removeClass("bg_red", "slow");
                    });
                    toggle_wrapper.find('a[name=addSupply]').text(fieldOldValue);
                }
            });
            $(this).children(0).removeClass("icon-spinner").removeClass("icon-spin").addClass(" icon-ok");
            $(this).next().toggle();
            inputDiv.addClass("hidden");
            toggle_wrapper.show();
            add_delete.show();
        }


	});


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//     SAVE EDIT TRANSACTION BUTTON
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
    $(".list-table .transaction .editSupply a.save").click(function() {
        var thisTD = $(this).parent().parent().parent().parent().find('.transaction');
        var thisInput = $(this).parent().prev();

        // get the whole edit panel reference
        var inputDiv = $(this).parent().parent();
        // default span (with the link) reference
        var toggle_wrapper = inputDiv.prev();
        var add_delete = inputDiv.next();

        // variables that gonna go to the add_transaction.php ajax page
        var to_location, from_location, product_id, completed;
        product_id = thisInput.attr('id');
        to_location = $("#location_id").val();
        from_location = 0;
        completed = 1;
        /* UPDATE TABLE AREA
        // get the new field value
        fieldNewValue = thisInput.val();

        if (fieldNewValue != fieldOldValue) {

            $(this).children(0).removeClass("icon-ok").addClass(" icon-spinner icon-spin");
            $(this).next().toggle();

            // DEBUG: alert ("to_location:"+to_location+", from_location:"+from_location+", product_id:"+product_id+", amount:"+fieldNewValue+", completed:"+completed);
            $.ajax({
                type: "POST",
                url: root+"/pages/ajax/add_transaction.php",
                data: {
                    to_location: to_location,
                    from_location: from_location,
                    product_id: product_id,
                    amount: fieldNewValue,
                    completed: completed
                },
                beforeSend: function () {
                    //alert("precessing");
                },
                success: function (data) {
                    // fieldNewValue - is the new value of the field, in this case - price (a)
                    var thisAmount = thisTD.next(); 				// amount 	(x) TD
                    var thisReceived = thisAmount.next();				// recieved (a*x) TD
                    var thisReturnAmount = thisReceived.next().find("input[name=returnAmount]").val(); // return amount (z)
                    var thisReturned = thisReceived.next().next();		// return amount (z) TD
                    var thisLeft = thisReturned.next();					// left amount (y) TD
                    var thisSoldTotal = thisLeft.next();				// sold total [(x-z-y)*a] TD
                    var tmpSoldVal = "";


                    thisTD.html(data);
                    $.ajax({type: "POST",url: "../ajax/get_location_product_amount.php",data: { location_id: to_location,product_id: product_id}, success: function(result){
                        thisAmount.text(result);

                        var price = thisTD.prev().find('a').text();
                        // visible update of related value
                        thisReceived.text("₪ " + (price * thisAmount.text()));
                        thisReturned.text("₪ " + (price * thisReturnAmount));


                        thisLeft.text(parseInt(thisLeft.text())+parseInt(fieldNewValue));
                        tmpSoldVal = (thisAmount.text() - thisReturnAmount - thisLeft.text()) * price;
                        if (tmpSoldVal < 0) tmpSoldVal = 0;
                        thisSoldTotal.text("₪ " + tmpSoldVal);

                        // mark green updated values
                        thisTD.addClass("bg_green", "fast", function () {
                            thisTD.removeClass("bg_green", "slow");
                        });
                        thisAmount.addClass("bg_green", "fast", function () {
                            thisAmount.removeClass("bg_green", "slow");
                        });
                        thisReceived.addClass("bg_green", "fast", function () {
                            thisReceived.removeClass("bg_green", "slow");
                        });
                        thisReturned.addClass("bg_green", "fast", function () {
                            thisReturned.removeClass("bg_green", "slow");
                        });
                        thisLeft.addClass("bg_green", "fast", function () {
                            thisLeft.removeClass("bg_green", "slow");
                        });
                        thisSoldTotal.addClass("bg_green", "fast", function () {
                            thisSoldTotal.removeClass("bg_green", "slow");
                        });
                    }});


                },
                error: function () {
                    //alert("oh no!");
                    //$("#putshithere").val("the field wasn't updated");
                    thisTD.addClass("bg_red", "fast", function () {
                        thisTD.removeClass("bg_red", "slow");
                    });
                    toggle_wrapper.find('a[name=addSupply]').text(fieldOldValue);
                }
            });
            $(this).children(0).removeClass("icon-spinner").removeClass("icon-spin").addClass(" icon-ok");
            $(this).next().toggle();
            inputDiv.addClass("hidden");
            toggle_wrapper.show();
            add_delete.show();
        }


    });
*/

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//     SAVE NEW RETURN BUTTON
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $(".list-table .return .addSupply a.save").click(function() {
        var thisTD = $(this).parent().parent().parent().parent().find('.return');
        var thisInput = $(this).parent().prev();

        // get the whole edit panel reference
        var inputDiv = $(this).parent().parent();
        // default span (with the link) reference
        var toggle_wrapper = inputDiv.prev();
        var add_delete = inputDiv.next();

        // variables that gonna go to the add_transaction.php ajax page
        var to_location, from_location, product_id, completed;
        product_id = thisInput.attr('id');
        to_location = 0;
        from_location = $("#location_id").val();;
        completed = 1;
        /* UPDATE TABLE AREA */
        // get the new field value
        fieldNewValue = thisInput.val();

        if (fieldNewValue != fieldOldValue) {

            $(this).children(0).removeClass("icon-ok").addClass(" icon-spinner icon-spin");
            $(this).next().toggle();

            // DEBUG: alert ("to_location:"+to_location+", from_location:"+from_location+", product_id:"+product_id+", amount:"+fieldNewValue+", completed:"+completed);
            $.ajax({
                type: "POST",
                url: root+"/pages/ajax/add_transaction.php",
                data: {
                    to_location: to_location,
                    from_location: from_location,
                    product_id: product_id,
                    amount: fieldNewValue,
                    completed: completed
                },
                beforeSend: function () {
                    thisTD.html("<span class='icon-spinner icon-spin'></span>");
                },
                success: function (data) {
                    location.reload();
                },
                error: function () {
                    //alert("oh no!");
                    //$("#putshithere").val("the field wasn't updated");
                    thisTD.addClass("bg_red", "fast", function () {
                        thisTD.removeClass("bg_red", "slow");
                    });
                    toggle_wrapper.find('a[name=addSupply]').text(fieldOldValue);
                }
            });
            $(this).children(0).removeClass("icon-spinner").removeClass("icon-spin").addClass(" icon-ok");
            $(this).next().toggle();
            inputDiv.addClass("hidden");
            toggle_wrapper.show();
            add_delete.show();
        }


    });



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//     CHANGE AMOUNT LEFT BUTTON
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$("a[name=changeAmountLeft]").click(function (evt){
		var thisAmountLeft = $(this);
		// default span (with the link) reference 
		var defaultSpan = thisAmountLeft.parent();
		// edit span (with the input) reference 
		var editSpan = defaultSpan.next();
		
		//editSpan.val(defaultSpan.find("[name=changeAmountLeft]").text());
		// hide the span with link
		defaultSpan.hide();
		// hide the span with input + edit icons
		editSpan.removeClass("hidden");
		
		editSpan.children(0).val(defaultSpan.find("[name=changeAmountLeft]").text());
		editSpan.children(0).focus().select();
		return false;
	});

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//     CANCEL AMOUNT LEFT BUTTON
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$(".list-table .amountLeft a.cancel").click(function(evt) {
		var thisBtn = $(this);
		// Get the input
		var thisInput = thisBtn.parent().prev();
		// get the whole edit panel reference
		var editAmountLeftSpan = $(this).parent().parent();
		// default span (with the link) reference 
		var defaultSpan = editAmountLeftSpan.prev();
		
		editAmountLeftSpan.addClass("hidden");
		defaultSpan.show();
	});

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//
//     SAVE AMOUNT LEFT BUTTON
//
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$(".list-table .amountLeft a.save").click(function(evt) {
		var thisBtn = $(this);
		// Get the input
		var thisInput = thisBtn.parent().prev();
		// get the whole edit panel reference
		var editAmountLeftSpan = thisBtn.parent().parent();
		// default span (with the link) reference 
		var defaultSpan = editAmountLeftSpan.prev();
		// get the edit span area with the save|cancel links

		
		var product_id = thisInput.attr('id');
		var location_id = $("#location_id").val();
		
		var tmpSoldVal = "";
		/* UPDATE TABLE AREA */
		// get the new field value
		fieldNewValue = thisInput.val();
		if (fieldNewValue != fieldOldValue) {
			
			$(this).children(0).removeClass("icon-ok").addClass(" icon-spinner icon-spin");
			$(this).next().toggle(); 
			
			$.ajax({
				type: "POST",
				url: root+"/pages/ajax/update_product_amountLeft.php",
				data: {product_id: product_id, amountLeft: fieldNewValue, location_id: location_id},
				beforeSend: function(){ 
					//alert("precessing");
				},
				success: function(data){ 
					var thisTD = thisBtn.parent().parent().parent(); 										// Осталось(y)
                    var thisTR = thisTD.parent();
					var thisSoldTotal = thisTD.next();														// sold total [(x-z-y)*a] TD 
					var thisReturnAmount = thisTR.find("input[name=returnAmount]").val(); 	// return amount (z)
                    console.log(thisTR.find("input[name=returnAmount]").val());
					var thisAmount = thisTR.find("td.amount");									// recieved (x) TD
					var price = thisTR.find("a[name=changePrice]");
					//var thisReceived = thisAmount.next();
					var thisLeft = defaultSpan.find("a[name=changeAmountLeft]");
					thisLeft.text(fieldNewValue);
					
					// visible update of related value
					tmpSoldVal = (parseInt(thisAmount.text())-parseInt(thisReturnAmount)-parseInt(thisLeft.text()))*parseInt(price.text());

					console.log(tmpSoldVal +"=("+thisAmount.text()+"-"+thisReturnAmount+"-"+thisLeft.text()+")*"+price.text());

					if (tmpSoldVal<0) tmpSoldVal = 0;
					thisSoldTotal.text("₪ " + tmpSoldVal);
					// mark green updated values
					thisTD.addClass("bg_green", "fast", function(){thisTD.removeClass("bg_green", "slow");});
					thisSoldTotal.addClass("bg_green", "fast", function(){thisSoldTotal.removeClass("bg_green", "slow");});
				},
				error: function(){

                    var thisTD = thisBtn.parent().parent().parent();
                    //alert("oh no!");
					//$("#putshithere").val("the field wasn't updated");
					thisTD.addClass("bg_red", "fast", function(){thisTD.removeClass("bg_red", "slow");});
					defaultSpan.find("a[name=changeAmountLeft]").text(fieldOldValue);
				}
			});
			$(this).children(0).removeClass("icon-spinner").removeClass("icon-spin").addClass(" icon-ok");
			$(this).next().toggle();
			
			
			editAmountLeftSpan.addClass("hidden");
			defaultSpan.show();
			
		}
		
	});
	
	
/*############################################################################################################################################################################*/
/* Sidebar Toggle */
/*############################################################################################################################################################################*/


  $(function() {
    // run the currently selected effect
    function runEffect() {
      // get effect type from
      var selectedEffect = $( "#effectTypes" ).val();
 
      // most effect types need no options passed by default
      var options = {};
      // some effects have required parameters
      if ( selectedEffect === "scale" ) {
        options = { percent: 0 };
      } else if ( selectedEffect === "size" ) {
        options = { to: { width: 200, height: 60 } };
      }
 
      // run the effect
      $( "#sidebar_1" ).toggle( "slide", options, 100 );
		};
	 
		// set effect from select menu value
		$( "#toggle-left" ).click(function() {
			
		  runEffect();
		  
		  $(this).hide();
		  $( "#toggle-right" ).show();
		  
		  $( "#content.three_quarter" ).switchClass("three_quarter", "full-width", 100);
		});
		$( "#toggle-right" ).click(function() {
			
		  runEffect();
		  
		  $(this).hide();
		  $( "#toggle-left" ).show();
		  $( "#content.full-width" ).switchClass("full-width", "three_quarter", 100);
		  
		});
  });
  
  
	/* Sidebar Links Group Toggle */ 
  $("#sidebar_1").find("a.toggleDiv").click(function() {
	  $(this).next().toggle("fast");
	  return false;
  });
  
  

    /* Alert Messages */
	
    $(".alert-msg .close").click(function () {
        $(this).parent().animate({
            "opacity": "0"
        }, 400).slideUp(400);
        //return false;
    });

    /* Accordions */

    $(".accordion-title").click(function () {
        $(".accordion-title").removeClass("active");
        $(".accordion-content").slideUp("normal");
        if ($(this).next().is(":hidden") == true) {
            $(this).addClass("active");
            $(this).next().slideDown("normal");
        }
    });
    $(".accordion-content").hide();

    /* Toggles */

    $(".toggle-title").click(function () {
        $(this).toggleClass("active").next().slideToggle("fast");
        return false;
    });

    /* Tabs */

    $(".tab-wrapper").tabs({
        event: "click"
    });

    /* Vertically Centre Text On Images */

    $.fn.flexVerticalCenter = function (onAttribute) {
        return this.each(function () {
            var $this = $(this);
            var attribute = onAttribute || 'margin-top';
            var resizer = function () {
                $this.css(
                attribute, (($this.parent().height() - $this.height()) / 2));
            };
            resizer();
            $(window).resize(resizer);
        });
    };

    // To run the function:
    $('.viewit').flexVerticalCenter();
	
	// to_location change in forms
	$("select[name='to_location']").change(function() {
		//var newLocationID = $(this).children(':selected').val();
		var newLocationID = $("#to_location").val();
		var url = $("#page").val();

		window.location.replace(url + "?to_location=" + newLocationID);

	});
	
	
	// from_location change in forms
	$("select[name='from_location']").change(function() {
		//var newLocationID = $(this).children(':selected').val();
		var newLocationID = $("#from_location").val();
		var url = $("#page").val();
		//$( "document" ).load( "new_supply.php?id=" + newLocationID );
        ///pages/transactions/new_transaction.php?from_location=

        //alert(url);
		window.location.replace(url + ".php?from_location=" + newLocationID);

	});
	
	
	// from_location change in forms
	$("select[name='location_id']").change(function() {
		//var newLocationID = $(this).children(':selected').val();
		var newLocationID = $("#location_id").val();
		var url = $("#page").val();
		//$( "document" ).load( "new_supply.php?id=" + newLocationID );
		window.location.replace(url + "?location_id=" + newLocationID);

	});
	
/*##############################################################################################################################################################################*/
	var updateURL = "";
	
	
	$("#editLocationName").click(editLocationName);
    function editLocationName(evt) {
        updateURL = "update_location_name.php";
        var thisName = $(this);
        var h1Title, h1Input, theInputItself;
        // reference of the h1 title
        h1Title = thisName.parent().parent();
        // reference of the h1 title span above input
        h1Input = h1Title.next();
        // the title input itself
        theInputItself = h1Input.children(0).next().children(0);
        // hide the span with link
        h1Title.hide();
        // hide the span with input + edit icons
        h1Input.removeClass("hidden");
        // put in the input the value of the current name
        theInputItself.select().val(h1Title.find("strong").text());

        return false;
    }

    /*##############################################################################################################################################################################*/

	$("[name=editCategoryName]").click(editCategoryName);
    function editCategoryName(evt) {
        updateURL = "update_category_name.php";
        console.log("updateURL changed to " + updateURL);
        var thisName = $(this);
        var h1Title, h1Input, theInputItself;
        // reference of the h1 title
        h1Title = thisName.parent().parent();
        // reference of the h1 title span above input
        h1Input = h1Title.next();
        // the title input itself
        theInputItself = h1Input.children(0).next().children(0);
        // hide the span with link
        h1Title.hide();
        // hide the span with input + edit icons
        h1Input.removeClass("hidden");
        // put in the input the value of the current name
        theInputItself.select().val(h1Title.find("strong").text());

        return false;
    }


    /*##############################################################################################################################################################################*/

	$("h1.title a.cancel").click(cancelUpdateTitle);
    function cancelUpdateTitle(evt) {
        var thisBtn = $(this);
        var h1Title, h1Input, theInputItself;
        // reference of the h1 title span above input
        h1Input = thisBtn.parent().parent();
        // reference of the h1 title
        h1Title = h1Input.prev();
        // the title input itself
        theInputItself = h1Input.children(0).next().children(0);
        // show the span with the H1
        h1Title.show();
        // hide the span with input + edit icons
        h1Input.addClass("hidden");
    }


    $("h1.title a.save").click(updateTitle);
    function updateTitle(evt) {
        var thisBtn = $(this);
        var h1Title, h1Input, theInputItself, tmp;
        // reference of the h1 title span above input
        h1Input = thisBtn.parent().parent();
        // reference of the h1 title
        h1Title = h1Input.prev();
        // the title input itself
        thisInput = h1Input.children(0).next().children(0);
        // get the edit span area with the save|cancel links

        var product_id = thisInput.attr('id');

        var id = "";
        if (updateURL == "update_location_name.php") {
                id = $("#location_id").val();
        }
        else {
        id = $("#category_id").val();
        }
                /* UPDATE TABLE AREA */
        // get the new field value
                fieldNewValue = thisInput.val();

                if (fieldNewValue != fieldOldValue) {


                    $.ajax({
                        type: "POST",
                        url: root+"/pages/ajax/" + updateURL,
                        data: {id: id, name: fieldNewValue},
                        beforeSend: function () {
                            thisBtn.next().toggle();
                            thisBtn.children(0).removeClass("icon-ok").addClass(" icon-spinner icon-spin");
                        },
                        success: function (data) {
                            h1Title.find("strong").text(fieldNewValue);
                            if (data == 1) {
                                h1Title.addClass("bg_green", "fast", function () {
                                    h1Title.removeClass("bg_green", "slow");
                                });
                            }
                            else if (data == 0) {
                                h1Title.addClass("bg_red", "fast", function () {
                                    h1Title.removeClass("bg_red", "slow");
                                });
                            }
                            // whatever is the value in the field, put the one was there before

                        },
                        error: function () {
                            //alert("oh no!");
                            //$("#putshithere").val("the field wasn't updated");
                            h1Title.addClass("bg_red", "fast", function () {
                                h1Title.removeClass("bg_red", "slow");
                            });
                            thisInput.focus().val(fieldOldValue);
                        }
                    });
                    $(this).children(0).removeClass("icon-spinner").removeClass("icon-spin").addClass(" icon-ok");
                    $(this).next().toggle();

                    h1Title.show();
                    // hide the span with input + edit icons
                    h1Input.addClass("hidden");

                }

    }

    // edit_supply add_supply & etc. - amount input -> onclick select the value - so we can edit it:

    $('input[name^=amount]').click(function(){
        var thisInput = $(this);
        thisInput.val();

        $(this).focus().select();
    });
    /*##############################################################################################################################################################################*/





    $('.openModal').on("click",function () {
        var location_id = $(this).attr(id);
        var modalWindow = $('#openModal');
        alert(location_id);
    });












/*
    $("#sidebar_1 a[name = new_supply]").click(function() {
        var location_id = $("#location_id").val();
        var newSupplyID = "";
        var thisLI = $(this).parent();


        $.ajax({
            type: "POST",
            url: root+"/pages/ajax/new_supply.php",
            data: {location_id: location_id},
            beforeSend: function(){
            },
            success: function(data){
                newSupplyID = data;
               // $(document).load(root+"/pages/transactions/edit_supply.php", { "to_location": location_id, "transaction_id": newSupplyID });
            },
            error: function(){
                $(this).parent().addClass(" bg_red", "fast", function(){ $(this).parent().removeClass(" bg_red", "slow");});

            }
        });
    });

*/



});