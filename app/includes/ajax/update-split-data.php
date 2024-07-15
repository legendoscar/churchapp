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

        require "../../includes/update_activity.php";

        try {
            if(is_authorized($account_type, "edit-receipt", "", "") === "allowed"){
                if(
                    isset($_POST['split_invoice_id']) && !empty($_POST['split_invoice_id']) && // STATIC
                    isset($_POST['split_invoice_number']) && !empty($_POST['split_invoice_number']) &&  // STATIC
                    isset($_POST['split_payment_method']) &&  /*!empty($_POST['discount_id']) &&*/ // DUPLICATE
                    isset($_POST['split_payment_date']) &&  /*!empty($_POST['discount_id']) &&*/ // DUPLICATE
                    isset($_POST['split_payment_name']) &&  /*!empty($_POST['discount_id']) &&*/ // DUPLICATE 
                    isset($_POST['split_amount']) 
                ){

                    unset($_SESSION['_is_parameters_empty_']);
                    $_SESSION['_is_parameters_empty_'] = 0;

                    unset($_SESSION['_sum_amount_']);
                    $_SESSION['_sum_amount_'] = 0;

                    unset($_SESSION['_is_split_payment_date_ok_']);
                    $_SESSION['_is_split_payment_date_ok_'] = 0;

                    unset($_SESSION['_excess_credit_error_']);
                    $_SESSION['_excess_credit_error_'] = 0;

                    unset($_SESSION['_is_split_payment_name_ok_']);
                    $_SESSION['_is_split_payment_name_ok_'] = 0;

                    unset($_SESSION['__is_split_done__']);
                    $_SESSION['__is_split_done__'] = 0;

                    $invoice_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['split_invoice_id']));
                    $invoice_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['split_invoice_number']));
                    $date = get_invoice_data_by_number($invoice_number, "date_created");
                    $date_time = get_invoice_data_by_number($invoice_number, "date_time");
                    $customer_name = get_invoice_data_by_number($invoice_number, "customer_name");

                    $allow_excess_payment = get_company_data("invoice_allow_excess_payment");

                    $search = mysqli_prepare($conn, "SELECT * FROM splitted_payments WHERE invoice_id=? and invoice_number=?");
                    mysqli_stmt_bind_param($search, "is", $invoice_id, $invoice_number);
                    mysqli_stmt_execute($search);
                    $get_result = mysqli_stmt_get_result($search);

                    if(mysqli_num_rows($get_result)>0){
                        while($row = mysqli_fetch_array($get_result)){
                            $data[] = $row['id'];
                        }
                        $pending_delete = implode($data, ",");
                    } else {
                        $pending_delete = 0;
                    }

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

                        $split_payment_method = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['split_payment_method'][$count_splits]));
                        $split_amount = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['split_amount'][$count_splits]));

                        if(empty($split_payment_method) || empty($split_amount)){
                            $_SESSION['_is_parameters_empty_'] = ($_SESSION['_is_parameters_empty_']+1);
                        } else {
                            $_SESSION['_sum_amount_'] += $split_amount;
                        }
                        
                    }

                    $total_splited_amount = $_SESSION['_sum_amount_'];
                    $total_invoice_amount = get_invoice_data_by_number($invoice_number, "total_paid");

                    if($_SESSION['_is_parameters_empty_'] == 0){

                        // CHECK IF EXCESS PAYMENT IS ALLOWED IN PAYMENT SPLITTING
                        $total_excess = 0;
                        if($allow_excess_payment  != "yes" ) { // EXCESS PAYMENT NOT ALLOWED
                            if("$total_invoice_amount" != "$total_splited_amount"){
                                exit(server_response("error", "Please review your splitted payments. <b>Excess / Short Payments currently not allowed. Splitted payments must be equal to ".custom_money_format($total_invoice_amount)."</b>", "100"));
                            }
                        } else { // EXCESS PAYMENT ALLOWED
                            $total_excess = ($total_splited_amount-$total_invoice_amount);
                            if($total_splited_amount < $total_invoice_amount) {
                                // $_SESSION["_check_split_errors_2"] = ($_SESSION["_check_split_errors_2"]+1); // COUNT NUMBER OF ERRORS 
                                exit(server_response("error", "<b>Notice: </b> Short payments are not allowed. Please use the <b>CREDIT option</b> instead", "100"));
                            }
                        }

                        $_SESSION['__is_split_done__'] = count($_POST['split_payment_method']);

                        $total_credit = 0;
                        for($count_splits=0; $count_splits<count($_POST['split_payment_method']); $count_splits++){

                            $_SESSION['__is_split_done__'] = ($_SESSION['__is_split_done__']-1);
                            
                            $split_payment_method = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['split_payment_method'][$count_splits]));
                            $split_amount = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['split_amount'][$count_splits]));
                            
                            $split_payment_date = $_POST['split_payment_date'][$count_splits]; // SPLITTED PAYMENT DATE 
                            $split_payment_name = $_POST['split_payment_name'][$count_splits]; // SPLITTED PAYMENT NAME
                            
                            $sql = mysqli_prepare($conn, "SELECT * FROM bank_accounts where id=?");
                            mysqli_stmt_bind_param($sql, "i", $split_payment_method);
                            mysqli_stmt_execute($sql);

                            $get_result = mysqli_stmt_get_result($sql);
                            $row = mysqli_fetch_array($get_result);
                            $method_id = $row['method_id'];

                            if($total_excess > 0 && strtolower($row['account_name']) == "credit") {
                                $_SESSION['_excess_credit_error_'] = ($_SESSION['_excess_credit_error_']+1);
                            }

                            if(strtolower(@$row['account_name']) == "credit") {
                                $total_credit += $split_amount;
                            }

                            $save = mysqli_prepare($conn, "INSERT INTO splitted_payments(invoice_id, invoice_number, payment_method, method_id, amount, date_added, date_created, payment_date, payment_name) VALUES(?,?,?,?,?,?,?,?,?)");
                            mysqli_stmt_bind_param($save, "isiisssss", $invoice_id, $invoice_number, $split_payment_method, $method_id, $split_amount, $date, $date_time, $split_payment_date, $split_payment_name);
                            mysqli_stmt_execute($save);

                        }

                        if($_SESSION['_excess_credit_error_'] > 0) {
                            exit(server_response("error", "<b>Error:</b> There can't be <b>Excess Payment</b> and <b>Credit Sales</b> on same invoice. <b>SUGGESTION: Credit Sales</b> should be: <b>". custom_money_format($total_credit-$total_excess)."</b>", "100"));
                        }

                        if($_SESSION['__is_split_done__'] == 0) {

                            $delete = mysqli_prepare($conn, "DELETE FROM splitted_payments WHERE id IN ($pending_delete) and invoice_id=? and invoice_number=?"); 
                            mysqli_stmt_bind_param($delete, "is", $invoice_id, $invoice_number);
                            mysqli_stmt_execute($delete);

                            $update_invoice = mysqli_prepare($conn, "UPDATE invoice SET is_split='yes', method_id='0', payment_method='0', payment_date='0000-00-00' WHERE id=? and invoice_number=?");
                            mysqli_stmt_bind_param($update_invoice, "is", $invoice_id, $invoice_number);
                            mysqli_stmt_execute($update_invoice);

                            if(($total_excess) < 0) { $total_excess = 0; }
                            if(create_update_excess_payments($invoice_id, $invoice_number, $customer_name, $total_excess, $date) !== "success") {
                                exit(server_response("error", "<b>ERROR! AUTO-SAVE Excess Payment unexpectedly failed, please contact your Administrator.</b>" , "100"));
                            }

                            echo server_response("success", "Payment Method(s) successfully updated", "100");
                            mysqli_commit($conn);

                        }

                    } else {
                        echo server_response("error", "Please review your splitted payments. It could be that some selected fields are empty.", "100");
                    }
                } else {
                    echo server_response("error", "Please review your splitted payments. It could be that some selected fields are empty.", "100");
                }
            } else {
                echo server_response("error", "<b>Access Denied!</b> You're not allowed to update this receipt. Please if you think this was a mistake, contact your administrator.", "100"); 
            }
        } catch(mysqli_sql_exception $exception) {
            mysqli_rollback($conn);
            throw $exception;
        }
    } else {
        echo server_response("error", "Something went wrong!", "100");
    }
?>