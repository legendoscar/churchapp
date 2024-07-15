<?php session_start();

    if($_SERVER['REQUEST_METHOD'] === "POST") {

        ignore_user_abort(true);

        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";

        mysqli_autocommit($conn, false); // DISABLE AUTO COMMIT
        mysqli_begin_transaction($conn); //BEGIN TRANSACTION

        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";
        require "../../includes/update_activity.php";

        $invoice_type = "casual";

        try {
            if(is_authorized($account_type, "create-receipt", "", "") === "allowed"){ 
                if(
                    isset($_POST['payer_name']) && !empty($_POST['payer_name']) && // STATIC
                    isset($_POST['product_id']) && !empty($_POST['product_id']) && // DUPLICATE
                    isset($_POST['h_total']) && !empty($_POST['h_total']) && // DUPLICATE {TOTAL AMOUNT PER ROW}
                    // isset($_POST['payment_method']) && !empty($_POST['payment_method']) && // STATIC
                    isset($_POST['payment_status']) && !empty($_POST['payment_status']) && // STATIC
                    isset($_POST['additional_note']) && // STATIC
                    isset($_POST['h_total_paid']) && !empty($_POST['h_total_paid']) // STATIC
                ){

                    // if(check_setup_daily_product_stocks() == "no_setup"){ // USER HAS SETUP DAY'S STOCK
                    //     exit(server_response("error", "<b>Invoice disabled. Please setup today's stock</b>", "100"));
                    // }

                    // $AllowAdditionalText = get_company_data("show_invoice_item_addtional_text");

                    $allow_excess_payment = get_company_data("invoice_allow_excess_payment");
                    $allow_duplicate_contents = get_company_data("invoice_allow_duplicate_contents");
                    $can_apply_customer_balance = get_company_data("can_apply_customer_balance_to_invoice");

                    // if( $AllowAdditionalText === "yes" && !isset($_POST['additional']) ) {
                    //     exit(server_response("error", "<b>Additional text is required!</b>", "100"));
                    // }

                    /**
                     * UNSET ALL SESSIONS USED FOR TRACKING ERROR
                     */
                    unset($_SESSION['_err_msg_2']);
                    unset($_SESSION['__error_checker__']);
                    unset($_SESSION['__discount_error_checker__']);
                    unset($_SESSION['pattern_error']);
                    unset($_SESSION['__is_discount_greater_than_amount__']);
                    unset($_SESSION['_err_msg_']);
                    unset($_SESSION['_err_msg_empty_cell_']);
                    unset($_SESSION['_is_split_payment_date_ok_']);
                    unset($_SESSION['_is_split_payment_name_ok_']);
                    unset($_SESSION['_err_msg_trn_details_']);
                    unset($_SESSION['_get_single_payment_method_']);
                    unset($_SESSION['__check_method__']);
                    unset($_SESSION['_excess_credit_error_']);
                    unset($_SESSION['_check_split_errors_']);
                    unset($_SESSION['_check_split_errors_2']);
                    unset($_SESSION['_check_split_errors_no_excess']);
                    unset($_SESSION['_is_split_saved_']);
                    unset($_SESSION['_is_out_of_stock_']);
                    unset($_SESSION['_is_saved_splitted_ok_']);
                    unset($_SESSION['__transaction_type_error__']);
                    unset($_SESSION['IsDiscountAllowed']);
                    unset($_SESSION['__rate_error__']);

                    /**
                     * RESET ALL SESSIONS USED FOR TRACKING ERROR
                     */

                    $_SESSION["_err_msg_"] = 0;
                    $_SESSION["__error_checker__"] = 0;
                    $_SESSION["pattern_error"] = 0;
                    $_SESSION["__discount_error_checker__"] = 0;
                    $_SESSION["_is_split_saved_"] = 0;
                    $_SESSION['__rate_error__'] = 0;
                    $_SESSION["_is_out_of_stock_"] = 0;
                    $_SESSION['_check_split_errors_'] = 0;
                    $_SESSION['_check_split_errors_2'] = 0;
                    $_SESSION['_check_split_errors_no_excess'] = 0;
                    $_SESSION['_get_single_payment_method_'] = 0;
                    $_SESSION['__is_discount_greater_than_amount__'] = 0;
                    $_SESSION["_err_msg_2"] = 0;
                    $_SESSION['_is_split_payment_date_ok_'] = 0;
                    $_SESSION['_is_split_payment_name_ok_'] = 0;
                    $_SESSION["_err_msg_empty_cell_"] = 0;
                    $_SESSION["_err_msg_trn_details_"] = 0;
                    $_SESSION["__check_method__"] = 0;
                    $_SESSION["_excess_credit_error_"] = 0;
                    $_SESSION['IsDiscountAllowed'] = 0;
                    $_SESSION["_is_saved_splitted_ok_"] = 0;
                    $_SESSION["__transaction_type_error__"] = 0;

                    /**
                     * NONE DUPLICATE VARIABLES
                     */

                    //CUSTOMER INFO

                    $payer_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payer_name']));

                    $switch_tracker = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['switch_tracker']));

                    // IF THIS IS A FIRST TIME CUSTOMER, LET'S CREATE AN ACCOUNT
                    if($switch_tracker == "create-account") {

                        if(is_authorized($account_type, "sb-new-customer", "", "") != "allowed"){
                            exit(server_response("error", "<b>You're not allowed to create customer account!</b>", "100"));
                        }

                        // CUSTOMER NAME CHECK
                        if(validate_customer_name($payer_name) != "success") {
                            exit(server_response("error", "<b>".validate_customer_name($payer_name)."</b>", "100"));
                        }

                        $payer_id = "CST00".(get_customers("count")+1);
                        create_easy_customer_account($payer_id, $payer_name, "");
                        // NEW CUSTOMER ID
                        $payer_name = $payer_id;
                    }

                    if(in_array(get_customer_data($payer_name, "id"), array("error", "not_found")) ) {
                        exit(server_response("error", "<b>Error:</b> Invalid Payer ID.", "100"));
                    }

                    $customer_phone = get_customer_data($payer_name, "phone");
                    // $grand_qty_total = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['h_qty']));
                    $grand_total_paid = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['h_total_paid']));
                    $payment_method = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payment_method']));
                    $additional_note = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['additional_note']));
                    $invoice_status = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payment_status']));

                    // $pattern_decode = auto_decode_discount_pattern(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['pattern'])));
                    // $pattern = (SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['pattern'])));

                    $url_id = md5(password_hash(rand(rand(456, 5678), rand(45678, 4567898)), PASSWORD_DEFAULT));

                    $append = rand(10,99).date("s");
                    $invoice_number = get_company_data("invoice_prefix").substr(time(), 5, 7).$append;
                    $created_by = $account_id;

                    $is_split = "no"; // DEFAULT VALUE FOR NON-SPLITTED INVOICE PAYMENT METHODS

                    $total_excess = 0;
                    $total_credit = 0;

                    if( (isset($_POST['customer_balance_payment_method']) || isset($_POST['customer_balance_amount'])) && $payment_method != "split" ) {
                        exit(server_response("error", "<b>Error:</b> For none-splitted payments please remove Payer balance. Payer's balance can only be applied / used on splitted payments.", "100"));
                    }

                    if($payment_method == "split"){ // IS PAYMENT METHOD SPLITTED

                        $is_split = "yes"; // CHANGE DEFAULT VALUE

                        $total_split_amount = 0; // DEFAULT TOTAL SPLITTED AMOUNT
                        if(isset($_POST['total_split_amount']) && !empty($_POST['total_split_amount'])) {
                            $total_split_amount = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['total_split_amount'])); // TOTAL SPLITTED AMOUNT
                        }

                        // LOOP THROUGH SPLITTED PAYMENT METHODS AND INPUTED AMOUNTS

                        if(isset($_POST['split_payment_method']) && isset($_POST['split_amount']) && isset($_POST['total_split_amount']) && !empty($_POST['total_split_amount'])){ // ENSURE THAT SPLITTED INVOICE PAYMENT METHODS ARE SET

                            $allow_short_payment = "no";
                            // $allow_excess_payment = "yes";

                            // if($allow_short_payment  != "yes" ) { // EXCESS PAYMENT NOT ALLOWED
                            //     if($total_split_amount != $_POST['h_total_paid']) {
                            //         $_SESSION["_check_split_errors_no_excess"] = ($_SESSION["_check_split_errors_no_excess"]+1); // COUNT NUMBER OF ERRORS 
                            //     }
                            // } else { // EXCESS PAYMENT ALLOWED
                            //     $total_excess = ($total_split_amount-$_POST['h_total_paid']);
                            //     if($total_split_amount < $_POST['h_total_paid']) {
                            //         $_SESSION["_check_split_errors_2"] = ($_SESSION["_check_split_errors_2"]+1); // COUNT NUMBER OF ERRORS 
                            //     }
                            // }

                            // CHECK IF EXCESS PAYMENT IS ALLOWED IN PAYMENT SPLITTING
                            if($allow_excess_payment  != "yes") { // EXCESS PAYMENT NOT ALLOWED

                                // Short payments not allowed, and splitted amount is less than invoice/receipt value
                                if($allow_short_payment != "yes" && $total_split_amount < $_POST['h_total_paid']) {
                                    $_SESSION["_check_split_errors_no_excess"] = ($_SESSION["_check_split_errors_no_excess"]+1); // COUNT NUMBER OF ERRORS

                                // excess payments not allowed, and splitted payment is greater than invoice/receipt value.
                                } else if($total_split_amount > $_POST['h_total_paid']) {
                                    $_SESSION["_check_split_errors_no_excess"] = ($_SESSION["_check_split_errors_no_excess"]+1); // COUNT NUMBER OF ERRORS
                                }

                            } else { // EXCESS PAYMENT ALLOWED

                                $total_excess = ($total_split_amount-$_POST['h_total_paid']);

                                // short payment not allowed, and total splitted payment is less than invoice/receipt value
                                if($allow_short_payment  != "yes" && $total_split_amount < $_POST['h_total_paid']) {
                                    $_SESSION["_check_split_errors_2"] = ($_SESSION["_check_split_errors_2"]+1); // COUNT NUMBER OF ERRORS
                                }
                            }
                                // if($allow_short_payment == "yes" && )

                            // LOOP THROUGH SPLITTED PAYMENT METHODS AND INPUTED AMOUNTS
                            for($count_splits=0; $count_splits<count($_POST['split_payment_method']); $count_splits++){

                                $split_payment_method = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['split_payment_method'][$count_splits]));
                                $split_amount = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['split_amount'][$count_splits]));

                                $sql = mysqli_prepare($conn, "SELECT * FROM bank_accounts where id=?");
                                mysqli_stmt_bind_param($sql, "i", $split_payment_method);
                                mysqli_stmt_execute($sql);

                                $get_result = mysqli_stmt_get_result($sql);
                                $row = mysqli_fetch_array($get_result);

                                if($total_excess > 0 && strtolower(@$row['account_name']) == "credit") {
                                    $_SESSION['_excess_credit_error_'] = ($_SESSION['_excess_credit_error_']+1);
                                }

                                if(strtolower(@$row['account_name']) == "credit") {
                                    $total_credit += $split_amount;
                                }

                                // TRACK ERRORS
                                // if( mysqli_num_rows($get_result) == 0 || empty($split_payment_method) || empty($split_amount) || $total_split_amount != $_POST['h_total_paid']){ // 
                                if( mysqli_num_rows($get_result) == 0 || empty($split_payment_method) || empty($split_amount) ){ // 
                                    $_SESSION["_check_split_errors_"] = ($_SESSION["_check_split_errors_"]+1); // COUNT NUMBER OF ERRORS
                                }

                            }

                            if($_SESSION['_excess_credit_error_'] > 0) {
                                if(($total_credit-$total_excess) < 0) {
                                    exit(server_response("error", "<b>Error:</b> There can't be <b>Excess Payment</b> and <b>Credit Sales</b> on same invoice. <b>SUGGESTION: Credit Sales</b> should be: <b> Removed </b>", "100"));
                                } else {
                                    exit(server_response("error", "<b>Error:</b> There can't be <b>Excess Payment</b> and <b>Credit Sales</b> on same invoice. <b>SUGGESTION: Credit Sales</b> should be: <b>". custom_money_format($total_credit-$total_excess)."</b>", "100"));
                                }
                            }
                        } else { // SPLITTED INVOICE PAYMENT METHOD IS SPLITTED BUT REQUIRED PARAMETERS ARE NOT SET 
                            $_SESSION["_check_split_errors_"] = ($_SESSION["_check_split_errors_"]+1); // COUNT NUMBER OF ERRORS
                        }

                        if( !isset($_POST['split_payment_method']) && isset($_POST['customer_balance_payment_method']) && isset($_POST['customer_balance_amount'])) {
                            $_SESSION["_check_split_errors_"] = 0; // AUTO RESET ERRORS
                        }

                        if(isset($_POST['customer_balance_payment_method']) && isset($_POST['customer_balance_amount']) ){ // CHECK TO SEE IF CUSTOMER BALANCE IS APPLIED

                            if($allow_excess_payment  != "yes" ) { // EXCESS PAYMENT NOT ALLOWED
                                if($total_split_amount != $_POST['h_total_paid']) {
                                    $_SESSION["_check_split_errors_no_excess"] = ($_SESSION["_check_split_errors_no_excess"]+1); // COUNT NUMBER OF ERRORS
                                }
                            } else { // EXCESS PAYMENT ALLOWED
                                $total_excess = ($total_split_amount-$_POST['h_total_paid']);
                                if($total_split_amount < $_POST['h_total_paid']) {
                                    $_SESSION["_check_split_errors_2"] = ($_SESSION["_check_split_errors_2"]+1); // COUNT NUMBER OF ERRORS
                                }
                            }

                            $customer_balance_payment_method = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['customer_balance_payment_method']));
                            $customer_balance_amount = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['customer_balance_amount']));

                            if($can_apply_customer_balance != "yes" ) {
                                exit(server_response("error", "<b>Error:</b> Application of Payers's Balance has been disabled. Please remove already added balance of ₦$customer_balance_amount", "100"));
                            }

                            if($customer_balance_payment_method != "customer_balance") {
                                exit(server_response("error", "<b>Error:</b> Something went wrong with Customer Balance.", "100"));
                            }

                            $customer_balance_payment_method = "other_".$customer_balance_payment_method;
                            $customer_balance = get_customer_balance($payer_name, "pending", "sum", "excess_amount");
                            if($customer_balance_amount != get_customer_balance($payer_name, "pending", "sum", "excess_amount")) {
                                exit(server_response("error", "<b>Error:</b> ₦$customer_balance_amount is no longer customer's balance. Customer's balance is ₦".number_format($customer_balance).". <b>SUGGESTION:</b> Please Re-apply customer's balance.", "100"));
                            }

                            /**
                             * UPDATE CUSTOMER BALANCE IMMEDIATELY
                             * (This will be kept pending at the meantime)
                             * (Reason: We might detect error on the way).
                             */

                            // Set status as USED
                            $invoice_initiated_on = $invoice_number;
                            if(auto_update_customer_balance_status($payer_name, "used", "pending", $invoice_initiated_on) != "success") {
                                exit(server_response("error", "<b>Error occurred</b> while processing your request for customer's balance application.", "100"));
                            }
                        }
                    }

                    
                    // CHECK IF DUPLICATE CONTENTS (INVOICE ITEMS) ARE ALLOWED IN INVOICE 
                    if($allow_duplicate_contents != "yes") { // IS NOT ALLOWED
                        $total_items_in_invoice = count($_POST['product_id']);
                        $total_unique_items_invoice = count(array_unique($_POST['product_id']));
                        if($total_unique_items_invoice != $total_items_in_invoice) {
                            exit(server_response("error", "<b>Error:</b> Duplicate items currently not allowed. You may contact your admin to enable this feature.", "100"));
                        }
                    }

                    // DISCOUNT PATTERN 
                    // $total_discount_pattern = count($_POST['discount_pattern']);
                    // $total_unique_discount_pattern = count(array_unique($_POST['discount_pattern']));
                    // $current_pattern = get_company_data("discount_pattern");

                    // if($total_items_in_invoice != $total_discount_pattern) {
                    //     exit(server_response("error", "<b>Error:</b> Discount pattern unexpectedly failed. Please contact your admin.", "100"));
                    // }

                    // if($total_unique_discount_pattern != 1 || $pattern_decode != auto_decode_discount_pattern($pattern)) {
                    //     exit(server_response("error", "<b>Error:</b> We detected a change on your Discount Pattern. Please contact your admin.", "100"));
                    // }

                    // for($count_products=0; $count_products<count($_POST['product_id']); $count_products++){

                    //     $pattern_1 = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['discount_pattern'][$count_products]));
                    //     $pattern_1_decode = auto_decode_discount_pattern($pattern_1);

                    //     if($pattern_1 != $pattern || $pattern_1_decode != $pattern_decode) {
                    //         $_SESSION['pattern_error'] = ($_SESSION['pattern_error'] + 1);
                    //     }

                    //     if($_SESSION['pattern_error'] > 0) {
                    //         exit(server_response("error", "<b>Error:</b> We detected a change on your Discount Pattern. Please contact your admin.", "100"));
                    //     }
                    // }

                    
                    if($_SESSION['_check_split_errors_2'] > 0) {
                        exit(server_response("error", "<b>Notice: </b> Short payments are not allowed. Please use the <b>CREDIT option</b> instead", "100"));
                    }

                    if($_SESSION['_check_split_errors_'] > 0){ //MAKE SURE THERE ARE NO ERRORS
                        exit(server_response("error", "Please review your splitted payments. It could be that some selected fields are empty or probably, you've enter an invalid payment method", "100"));
                    }

                    if($_SESSION['_check_split_errors_no_excess'] > 0){ //MAKE SURE THERE ARE NO ERRORS
                        exit(server_response("error", "Please review your splitted payments. <b>Excess / Short Payments currently not allowed. Splitted payments must be equal to ".custom_money_format($_POST['h_total_paid'])."</b>", "100"));
                    }

                    if($is_split === "no"){ // SINGLE PAYMENT METHODS

                        $sql = mysqli_prepare($conn, "SELECT * FROM bank_accounts where id=?");
                        mysqli_stmt_bind_param($sql, "i", $payment_method);
                        mysqli_stmt_execute($sql);

                        $get_result = mysqli_stmt_get_result($sql);

                        if(mysqli_num_rows($get_result) == 1){

                            $row = mysqli_fetch_array($get_result);

                            $method_id = $row['method_id'];

                            $_SESSION['_get_single_payment_method_'] = $method_id; // APPEND METHOD ID TO A SESSION
                            
                        } else {
                            $_SESSION["__check_method__"] = ($_SESSION["__check_method__"]+1);
                        }
                    }

                    if($_SESSION['__check_method__']===0){

                        for($count_products=0; $count_products<count($_POST['product_id']); $count_products++){

                            $product_id_1 = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['product_id'][$count_products]));
                            // $product_qty_1 = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['product_qty'][$count_products]));
                            // $product_rate_1 = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['h_total'][$count_products]));
                            // $switchWR = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['switchWR'][$count_products]));
                            // $discount_id_1 = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['discount_id'][$count_products]));

                            // if(is_authorized($account_type, "ft-give-discount", "", "") != "allowed" && $discount_id_1 > 0) {
                            //     $_SESSION['IsDiscountAllowed'] = ($_SESSION['IsDiscountAllowed'] + 1); // 1 = NO, 0 = YES
                            // }

                            $product_total_amount_1 = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['h_total'][$count_products]));

                            if( empty($product_id_1) || empty($product_total_amount_1) ){
                                $_SESSION["_err_msg_empty_cell_"] = ($_SESSION["_err_msg_empty_cell_"]+1);
                            }
                        }

                        if($_SESSION['_err_msg_empty_cell_'] < 1 ) {

                            if($_SESSION['_get_single_payment_method_'] > 0){
                                $method_id = $_SESSION['_get_single_payment_method_'];
                            } else {
                                $method_id = 0;
                                $payment_method = 0;
                            }

                            if($_SESSION['_get_single_payment_method_'] == 0){ // THIS IS SPLITTED PAYMENT 
                                $single_payment_date = "0000-00-00";
                            } else {

                                if(!isset($_POST['payment_date']) || empty($_POST['payment_date'])) {
                                    exit(server_response("error", "Payment date is required!", "100"));
                                }

                                $single_payment_date = @$_POST['payment_date']; // SINGLE PAYMENT METHOD
                            }

                            // CREATE INVOICE
                            $generate = mysqli_prepare($conn, "INSERT INTO invoice(url_id, invoice_number, customer_name, customer_phone, total_paid, created_by, payment_method, method_id, is_split, payment_date, additional_note, `status`) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
                            mysqli_stmt_bind_param($generate, "sssssiiisssi", $url_id, $invoice_number, $payer_name, $customer_phone, $grand_total_paid, $created_by, $payment_method, $method_id, $is_split, $single_payment_date, $additional_note, $invoice_status);
                            if(mysqli_stmt_execute($generate)){ // NO ERRORS WHILE CREATING INVOICE

                                $invoice_id = mysqli_insert_id($conn);

                                if($_SESSION['_get_single_payment_method_'] == 0){ // IS INVOICE PAYMENT METHOD SPLITTED

                                    $total_split_amount = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['total_split_amount'])); // TOTAL SPLITTED AMOUNT 

                                    if( isset($_POST['split_payment_method']) || (isset($_POST['customer_balance_payment_method']) && isset($_POST['customer_balance_amount'])) ) {

                                        if( isset($_POST['split_payment_method'])) {

                                            for($count_splits_=0; $count_splits_<count($_POST['split_payment_method']); $count_splits_++){

                                                if(!isset($_POST['split_payment_date']) || empty($_POST['split_payment_date'][$count_splits_])) {
                                                    $_SESSION['_is_split_payment_date_ok_'] = ($_SESSION['_is_split_payment_date_ok_']+1);
                                                }

                                                if(!isset($_POST['split_payment_name']) || empty($_POST['split_payment_name'][$count_splits_])) { 
                                                    $_SESSION['_is_split_payment_name_ok_'] = ($_SESSION['_is_split_payment_name_ok_']+1);
                                                }
                                            }

                                            if($_SESSION['_is_split_payment_date_ok_'] > 0) { // USER IGNORE ONE OR TWO PAYMENT METHODS 
                                                exit(server_response("error", "Payment date for splitted payment(s) is/are required!", "100"));
                                            }

                                            if($_SESSION['_is_split_payment_name_ok_'] > 0) { // USER IGNORE ONE OR TWO PAYMENT METHODS 
                                                exit(server_response("error", "Payment name for splitted payment(s) is/are required!", "100"));
                                            }

                                            for($count_splits=0; $count_splits<count($_POST['split_payment_method']); $count_splits++){

                                                $split_payment_method = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['split_payment_method'][$count_splits])); // SPLITTED PAYMENT METHOD
                                                $split_amount = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['split_amount'][$count_splits])); // SPLITTED AMOUNT
                                                $split_payment_date = $_POST['split_payment_date'][$count_splits]; // SPLITTED PAYMENT METHOD
                                                $split_payment_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['split_payment_name'][$count_splits])); // SPLITTED PAYMENT NAME

                                                // CHECK PAYMENT METHOD VALIDATE
                                                $sql = mysqli_prepare($conn, "SELECT * FROM bank_accounts where id=?");
                                                mysqli_stmt_bind_param($sql, "i", $split_payment_method);
                                                mysqli_stmt_execute($sql);

                                                $get_result = mysqli_stmt_get_result($sql);

                                                if(mysqli_num_rows($get_result) == 1){ // ENSURE PAYMENT METHOD IS VALID

                                                    $row = mysqli_fetch_array($get_result);
                                                    $method_id = $row['method_id'];

                                                    $save_split = mysqli_prepare($conn, "INSERT INTO splitted_payments(invoice_id, invoice_number, payment_method, method_id, amount, payment_date, payment_name) VALUES(?,?,?,?,?,?,?)");
                                                    mysqli_stmt_bind_param($save_split, "isiisss", $invoice_id, $invoice_number, $split_payment_method, $method_id, $split_amount, $split_payment_date, $split_payment_name);
                                                    if(!mysqli_stmt_execute($save_split)){
                                                        $_SESSION['_is_saved_splitted_ok_'] = ($_SESSION['_is_saved_splitted_ok_']+1);
                                                    }
                                                } else {
                                                    $_SESSION['_is_saved_splitted_ok_'] = ($_SESSION['_is_saved_splitted_ok_']+1);
                                                }
                                            
                                            }
                                        }

                                        if( isset($_POST['customer_balance_payment_method'])) {

                                            $customer_balance_payment_method = $customer_balance_payment_method; // SPLITTED PAYMENT METHOD
                                            $customer_balance_amount = $customer_balance_amount;

                                            // CHECK PAYMENT METHOD VALIDATE
                                            $sql = mysqli_prepare($conn, "SELECT * FROM payment_method where `type`=?");
                                            mysqli_stmt_bind_param($sql, "s", $customer_balance_payment_method);
                                            mysqli_stmt_execute($sql);
                                            $get_result = mysqli_stmt_get_result($sql);

                                            if(mysqli_num_rows($get_result) == 1){ // ENSURE PAYMENT METHOD IS VALID 

                                                $row = mysqli_fetch_array($get_result);
                                                $method_id = $row['id'];
                                                $null = 0;
                                                $split_payment_date = date("Y-m-d");
                                                $split_payment_name = "";
                                                $save_split = mysqli_prepare($conn, "INSERT INTO splitted_payments(invoice_id, invoice_number, payment_method, method_id, amount, payment_date, payment_name) VALUES(?,?,?,?,?,?,?)");
                                                mysqli_stmt_bind_param($save_split, "isiisss", $invoice_id, $invoice_number, $null, $method_id, $customer_balance_amount, $split_payment_date, $split_payment_name);
                                                if(!mysqli_stmt_execute($save_split)){
                                                    $_SESSION['_is_saved_splitted_ok_'] = ($_SESSION['_is_saved_splitted_ok_']+1);
                                                }
                                            } else {
                                                $_SESSION['_is_saved_splitted_ok_'] = ($_SESSION['_is_saved_splitted_ok_']+1);
                                            }
                                        }
                                    }
                                }


                                if($_SESSION['_is_saved_splitted_ok_'] == 0){ // ENSURE THERE ARE NO ERRORS IN SAVING SPLITTED PAYMENTS

                                    // for($count_products=0; $count_products<count($_POST['product_id']); $count_products++){
                                    //     $switchWR = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['switchWR'][$count_products]));
                                    //     if( !in_array($switchWR, array("wholesale", "retail", "h_wholesale")) ) {
                                    //         $_SESSION["__transaction_type_error__"] = ($_SESSION["__transaction_type_error__"]+1);
                                    //     }
                                    // }

                                    // for($count_products=0; $count_products<count($_POST['product_id']); $count_products++){
                                    //     $product_id_1 = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['product_id'][$count_products])); 
                                    //     $product_rate_1 = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['product_rate'][$count_products])); 

                                    //     if( get_product_data($product_id_1, "selling_price") != ($product_rate_1) ) {
                                    //         $_SESSION['__rate_error__'] = ($_SESSION["__rate_error__"]+1); 
                                    //     }
                                    // }

                                    // for($count_products=0; $count_products<count($_POST['product_id']); $count_products++){

                                    //     $discount_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['discount_id'][$count_products]));

                                    //     if(is_authorized($account_type, "ft-give-discount", "", "") != "allowed" && $discount_id > 0) { 
                                    //         $_SESSION['IsDiscountAllowed'] = ($_SESSION['IsDiscountAllowed'] + 1); // 1 = NO, 0 = YES 
                                    //     }

                                    //     $product_total_amount = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['h_total'][$count_products]));

                                    //     $product_qty_1 = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['product_qty'][$count_products]));
                                    //     $product_rate_1 = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['product_rate'][$count_products]));
                                    //     $actual_amount = $product_qty_1 * $product_rate_1;

                                    //     if($discount_id > $actual_amount){
                                    //         $_SESSION["__is_discount_greater_than_amount__"] = ($_SESSION["__is_discount_greater_than_amount__"]+1);
                                    //     }
                                    // }

                                    // if($_SESSION['__is_discount_greater_than_amount__'] == 0){
                                        if($_SESSION['__transaction_type_error__'] == 0){
                                            // if($_SESSION['_err_msg_trn_details_'] == 0){

                                                // if($_SESSION['__rate_error__'] == 0) {
                                                    // if($_SESSION['_err_msg_'] == 0){

                                                        $total_rows  = (count($_POST['product_id']));

                                                        for($count_products=0; $count_products<$total_rows; $count_products++){

                                                            // $discountid = md5(password_hash( password_hash(rand("5", "23456"), PASSWORD_DEFAULT), PASSWORD_DEFAULT));
                                                            $product_id_1 = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['product_id'][$count_products]));
                                                            // $discount_id_1 = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['discount_id'][$count_products]));

                                                            // $switchWR = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['switchWR'][$count_products]));
                                                            $product_total_amount_1 = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['h_total'][$count_products]));

                                                            // CHECK IF DISCOUNT PATTERN IS UNIT-DISCOUNT-CALCULATION ( Unit Discount * total_qty )
                                                            // if(get_company_data("discount_pattern") == "cal_by_unit_discount") {
                                                            // $additional_text = "";

                                                            // if($AllowAdditionalText == "yes") {
                                                            //     $additional_text = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['additional'][$count_products]));
                                                            // }

                                                            //CHECK INVOICE TRANSACTION EXISTENCE
                                                            // IMPORT PRODUCTS/TRANSACTION DETAILS TO INVOICE

                                                            // $transaction_type = $switchWR;
                                                            $invoice_type = "casual";
                                                            // $product_rate_1 = get_product_data($product_id_1, "selling_price"); // PRODUCT STOCK

                                                            $import = mysqli_prepare($conn, "INSERT INTO invoice_trn_details(invoice_id, invoice_number, product_id, rate, amount, updated_by) VALUES (?,?,?,?,?,?)");
                                                            mysqli_stmt_bind_param($import, "isissi", $invoice_id, $invoice_number, $product_id_1, $product_total_amount_1, $product_total_amount_1, $created_by);
                                                            if(!mysqli_stmt_execute($import)){
                                                                // UNKNOWN ISSUE HAD OCCURED
                                                                $_SESSION['__error_checker__'] = ($_SESSION['__error_checker__'] + 1);
                                                            } else {

                                                                if($_SESSION['__error_checker__'] == 0){

                                                                    $invoice_date = get_invoice_data_by_number($invoice_number, "date_created");
                                                                }
                                                            }
                                                        }


                                                            if($_SESSION['__error_checker__'] == 0){

                                                                if(($total_excess) < 0) {
                                                                    $total_excess = 0;
                                                                }


                                                                if(create_update_excess_payments($invoice_id, $invoice_number, $payer_name, $total_excess, $invoice_date) !== "success") {
                                                                    exit(server_response("error", "<b>ERROR! AUTO-SAVE Excess Payment unexpectedly failed, please contact your Administrator.</b>" , "100"));
                                                                }

                                                                // for($count_products=0; $count_products<$total_rows; $count_products++){
                                                                //     $product_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['product_id'][$count_products]));
                                                                //     echo auto_balance_product_stock($product_id, $invoice_date);
                                                                // }

                                                                mysqli_commit($conn);

                                                                if(is_authorized($account_type, "pg-edit-invoice", "", "") === "allowed") {
                                                                    if(is_customer_balance_used($invoice_number) == "not_found" ) { redirect("./edit-receipt?id=$url_id", "100"); }
                                                                    else { redirect("./view-receipt?id=$url_id", "100"); }
                                                                } else { redirect("./view-receipt?id=$url_id", "100"); }

                                                                echo server_response("success", "Invoice successfully created! " , "100");?>

                                                                <script>
                                                                    setTimeout(() => {
                                                                        $("#create_invoice_btn").attr("disabled","disabled");
                                                                        $("#create_invoice_btn").html("<span class='fa fa-spin fa-spinner'></span> Redirecting... Please wait");
                                                                    }, 0);
                                                                </script>

                                                                <?php
                                                            } else {
                                                                echo server_response("error", "$pattern Something went wrong. Transaction Integrity failed unexpectedly! " , "100");
                                                            }
                                                    //     } else {
                                                    //         echo server_response("error", "It seems some products are out of stock!", "100"); 
                                                    //     }
                                                    // // } else {
                                                    //     echo server_response("error", "It seems some products are out of stock!", "100");
                                                    // }
                                                // } else {
                                                //     echo server_response("error", "Please review product rates.", "100");
                                                // }
                                                
                                            // } else {
                                            //     echo server_response("error", "This product already exist with this invoice!", "100");
                                            // }
                                        } else {
                                            echo server_response("error", "<b>Invalid Argument</b>", "100");
                                        }
                                    // } else {
                                    //     echo server_response("error", "<b>Error!</b> Discount cannot be greater than actual amount. Please review this.", "100");
                                    // }
                                } else {
                                    echo server_response("error", "Something went wrong while splitting payments!", "100");
                                }
                            } else {
                                echo server_response("error", "Something went wrong!", "100");
                            }
                        } else {
                            echo server_response("error", "All fields are required ", "100");
                        }
                    } else {
                        echo server_response("error", "Invalid Payment Method!", "100");
                    }

                } else { 
                    echo server_response("error", "All fields are required!", "100");
                }
            } else {
                echo server_response("error", "<b>Access Denied!</b> You're not allowed to create a receipt. Please if you think this was a mistake, contact your administrator.", "100");
            }
        } catch(mysqli_sql_exception $exception) {
            mysqli_rollback($conn);
            throw $exception;
        }
    } else {
        echo server_response("error", "Something went wrong!", "100");
    }
?>