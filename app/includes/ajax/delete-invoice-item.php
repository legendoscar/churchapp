<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        ignore_user_abort(true); // USER CANNOT ABORT TRANSACTION
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";

        mysqli_autocommit($conn, false); // DISABLE AUTO COMMIT
        mysqli_begin_transaction($conn); // BEGIN TRANSACTION

        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";
        require "../../includes/update_activity.php";

        try {
            if(is_authorized($account_type, "edit-receipt", "", "") === "allowed"){
                if(
                    isset($_POST['invoice_id']) && !empty($_POST['invoice_id']) && 
                    isset($_POST['invoice_number']) && !empty($_POST['invoice_number']) && 
                    isset($_POST['invoice_item']) && !empty($_POST['invoice_item']) && 
                    isset($_POST['invoice_row_id']) && !empty($_POST['invoice_row_id']) && 
                    isset($_POST['invoice_item_amount']) && !empty($_POST['invoice_item_amount']) ){

                    $invoice_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_id']));
                    $invoice_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_number']));
                    $invoice_item = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_item']));
                    $invoice_item_amount = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_item_amount']));
                    $invoice_row_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_row_id']));

                    $allow_excess_payment = get_company_data("invoice_allow_excess_payment");
                    $customer_name = get_invoice_data_by_number($invoice_number, "customer_name");
                    $total_splitted_amount = get_splitted_payments("", $invoice_id, $invoice_number, "amount");
                    $invoice_date = get_invoice_data_by_number($invoice_number, "date_created");

                    // Check Invoice existence
                    if(get_invoice_data_by_number($invoice_number, "id") == "not_found") {
                        exit(server_response("error", "Receipt does not exist", "1000"));
                    }

                    $js_trigger_msg = "<b>YOU CHANGED THE INVOICE AMOUNT, TO BE ON THE SAFER SIDE, PLEASE CONSIDER UPDATING THE SPLITTED PAYMENTS TOO.</b>";
                    $js_trigger_msg2 = "<b>WE DETECTED A CHANGE IN THE INVOICE AMOUNT, YOU MAY WANT TO UPDATE SPLITTED PAYMENTS TOO. EXCESS PAYMENTS AUTOMATICALLY LOGGED</b>";

                    $update = @mysqli_prepare($conn, "DELETE FROM invoice_trn_details where id=? and invoice_id=? and invoice_number=?");
                    @mysqli_stmt_bind_param($update, "iis", $invoice_row_id, $invoice_id, $invoice_number);
                    if(mysqli_stmt_execute($update)){

                        $fetch = @mysqli_prepare($conn, "SELECT sum(amount) from invoice_trn_details  WHERE invoice_id=? and invoice_number=?");
                        @mysqli_stmt_bind_param($fetch, "is", $invoice_id, $invoice_number);
                        if(mysqli_stmt_execute($fetch)){
                            $get_result = mysqli_stmt_get_result($fetch);
                            $row = mysqli_fetch_array($get_result);
                            $total_amount = $row["sum(amount)"];
                        }

                        $total_excess = 0;
                        if($allow_excess_payment != "yes") { // EXCESS NOT ALLOWED
                            if(get_invoice_data_by_number($invoice_number, "is_split") == "yes"){ // IS SPLITTED
                                if($total_splitted_amount != $total_amount){
                                    ModalScriptAlert($js_trigger_msg, 30000);
                                    // exit(server_response("error", "You cannot save this invoice. Please finish splitting payments before proceeding", "100"));
                                }
                            }
                        } else { // EXCESS IS ALLOWED
                            /**
                             * BUT SHORTAGE OF PAYMENT STRICTLY NOT ALLOWED
                            */
                            $total_excess = ($total_splitted_amount-$total_amount);
                            if($total_splitted_amount < $total_amount) { // SHORT OF PAYMENT ()
                                exit(server_response("error", "<b>Notice: </b> Short payments are not allowed. Updating this row will incur short payments. <br/><b>SUGGESTIONS:</b> (1) Splitted Payments should have additional excess ₦".custom_money_format(abs($total_excess))." or more in order to update this row.<br/> (2) Apply / Save remainder value on splitted payments before saving this row", "100"));
                            }

                            if(get_invoice_data_by_number($invoice_number, "is_split") == "yes"){ // IS SPLITTED
                                if($total_splitted_amount != $total_amount){ // IS NOT BALANCED
                                    ModalScriptAlert($js_trigger_msg2, 30000);
                                }
                            }
                        }

                        if(create_update_excess_payments($invoice_id, $invoice_number, $customer_name, $total_excess, $invoice_date) !== "success") {
                            exit(server_response("error", "<b>ERROR! AUTO-SAVE Excess Payment unexpectedly failed, please contact your Administrator.</b>" , "100"));
                        }

                        $update = mysqli_prepare($conn, "UPDATE invoice SET total_paid=? WHERE id=? and invoice_number=?");
                        mysqli_stmt_bind_param($update, "sis", $total_amount, $invoice_id, $invoice_number);
                        if(mysqli_stmt_execute($update)){

                            mysqli_commit($conn);
                            echo server_response("success", "Item successfully updated!", "100");?>
                            <script>
                                $("#row_id_<?php echo $invoice_row_id;?>").html("");
                                autoCals();
                            </script>

                        <?php
                        } else {
                            echo server_response("error", "Something went wrong!", "1000");
                        }
                    } else {
                        echo server_response("error", "Something went wrong!", "1000");
                    }
                } else {
                    echo server_response("error", "All fields are required!", "100");
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