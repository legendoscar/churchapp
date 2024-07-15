<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        ignore_user_abort(true); // USER CANNOT ABORT TRANSACTION

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
                    isset($_POST['customer_name']) && !empty($_POST['customer_name']) && // STATIC
                    // isset($_POST['customer_phone']) && !empty($_POST['customer_phone']) &&  // STATIC
                    isset($_POST['payment_status']) && !empty($_POST['payment_status']) && // STATIC
                    isset($_POST['invoice_number']) && !empty($_POST['invoice_number']) && // STATIC
                    isset($_POST['invoice_id']) && !empty($_POST['invoice_id'])  // DUPLICATE
                ){


                    $payment_status = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payment_status']));
                    $customer_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['customer_name']));
                    if(in_array(get_customer_data($customer_name, "id"), array("error", "not_found")) ) {
                        exit(server_response("error", "<b>Error:</b> Invalid Payer's ID.", "100"));
                    }

                    $customer_phone = get_customer_data($customer_name, "phone");
                    // $payment_method = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payment_method']));
                    $invoice_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_id']));
                    $invoice_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_number']));

                    $allow_excess_payment = get_company_data("invoice_allow_excess_payment");
                    $invoice_date = get_invoice_data_by_number($invoice_number, "date_created");

                    $search = mysqli_prepare($conn, "SELECT * FROM splitted_payments WHERE invoice_id=? and invoice_number=?");
                    mysqli_stmt_bind_param($search, "is", $invoice_id, $invoice_number);
                    mysqli_stmt_execute($search);
                    $get_result = mysqli_stmt_get_result($search);

                    if(mysqli_num_rows($get_result)>0){
                        while($row = mysqli_fetch_array($get_result)){
                            $data[] = $row['amount'];
                        }
                        $total_splitted = array_sum($data);
                    } else {
                        $total_splitted = 0;
                    }

                    $total_invoice_amount = get_invoice_data_by_number($invoice_number, "total_paid");
                    $total_excess = 0;

                    if($allow_excess_payment != "yes") { // EXCESS NOT ALLOWED
                        if("$total_splitted" != "$total_invoice_amount"){
                            $js_trigger_msg = "<b>You cannot save this invoice. Please finish splitting payments before proceeding</b>";
                            ModalScriptAlert($js_trigger_msg, 30000);
                            exit(server_response("error", "You cannot save this invoice. Please finish splitting payments before proceeding", "100"));
                        }
                    } else { // EXCESS IS ALLOWED
                        /**
                         * BUT SHORTAGE OF PAYMENT STRICTLY NOT ALLOWED
                        */
                        $total_excess = ($total_splitted-$total_invoice_amount);
                        if($total_splitted < $total_invoice_amount) { // SHORT OF PAYMENT ()
                            exit(server_response("error", "<b>Notice: </b> Short payments are not allowed. Please use the <b>CREDIT option</b> instead", "100"));
                        }

                    }

                    if(create_update_excess_payments($invoice_id, $invoice_number, $customer_name, $total_excess, $invoice_date) !== "success") {
                        exit(server_response("error", "<b>ERROR! AUTO-SAVE Excess Payment unexpectedly failed, please contact your Administrator.</b>" , "100"));
                    }

                    $update_2 = mysqli_prepare($conn, "UPDATE invoice SET is_split='yes', method_id='0', customer_name=?, customer_phone=?, payment_method='0', `status`=? WHERE id=? and invoice_number=?");
                    mysqli_stmt_bind_param($update_2, "ssiis", $customer_name, $customer_phone, $payment_status, $invoice_id, $invoice_number);
                    if(mysqli_stmt_execute($update_2)){

                        mysqli_commit($conn);
                        echo server_response("success", "Success! Invoice has been updated", "100");
                    } else {
                        echo server_response("error", "Something went wrong!", "100");
                    }
                } else {
                    echo server_response("error", "Error! All fields are required!", "100");
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