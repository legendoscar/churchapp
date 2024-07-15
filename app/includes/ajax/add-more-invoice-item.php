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
                    isset($_POST['invoice_item_amount']) && !empty($_POST['invoice_item_amount']) ){

                    $invoice_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_id']));
                    $invoice_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_number']));
                    $invoice_item = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_item']));
                    $invoice_item_amount = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_item_amount']));
                    // $invoice_row_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_row_id']));

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

                    $update = @mysqli_prepare($conn, "INSERT INTO invoice_trn_details(`product_id`, rate, amount, invoice_id, invoice_number) VALUES(?,?,?,?,?)");
                    @mysqli_stmt_bind_param($update, "issis", $invoice_item, $invoice_item_amount, $invoice_item_amount, $invoice_id, $invoice_number);
                    if(mysqli_stmt_execute($update)){

                        $trn_row_id = mysqli_insert_id($conn);

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

                                var table = document.getElementById("invoice_table");
                                var row = table.insertRow(table.rows.length-1);
                                row.setAttribute("id", "row_id_<?php echo $trn_row_id;?>");

                                var ceil_1 = row.insertCell(0);
                                var ceil_2 = row.insertCell(1);

                                appendHidden = "<input type='hidden' value='row_id_<?php echo $trn_row_id;?>' id='tr_row_id_<?php echo $trn_row_id;?>' name='tr_row_id' class='tr_row_id_<?php echo $trn_row_id;?> form-control'>\
                                    <input type='hidden' value='product_rate_<?php echo $trn_row_id;?>' id='for_rate_id_<?php echo $trn_row_id;?>' name='for_rate_id' class='for_rate_id form-control'>\
                                    <input type='hidden' value='product_total_<?php echo $trn_row_id;?>' id='for_amount_id_<?php echo $trn_row_id;?>' name='for_amount_id' class='for_amount_id form-control'>\
                                    <input type='hidden' value='wr_btn_text_<?php echo $trn_row_id;?>' id='wr_btn_<?php echo $trn_row_id;?>' name='wr_btn' class='form-control'>\
                                    <input type='hidden' step='0.001' class='form-control' value='grand_total_paid' id='for_h_grand_total_<?php echo $trn_row_id;?>'>\
                                    <input type='hidden' value='<?php echo $invoice_number;?>' id='invoice_number_<?php echo $trn_row_id;?>' name='invoice_number' class='invoice_number form-control'>\
                                    <input type='hidden' value='<?php echo $invoice_id;?>' id='invoice_id_<?php echo $trn_row_id;?>' name='invoice_id' class='invoice_id form-control'>\
                                    <input type='hidden' value='<?php echo $trn_row_id;?>' id='invoice_trn_id_<?php echo $trn_row_id;?>' name='invoice_trn_id' class='invoice_trn_id form-control'>\
                                    <input type='hidden' value='h_total' id='for_h_total_<?php echo $trn_row_id;?>' name='for_h_total' class='for_h_total form-control'>\
                                    <input type='hidden' value='h_total_<?php echo $trn_row_id;?>' id='for_h_input_total_<?php echo $trn_row_id;?>' name='for_h_input_total' class='for_h_input_total form-control'>\
                                    <input value='<?php echo get_invoice_data($trn_row_id, 'total_qty');?>' name='total_qty' required min='0' type='hidden' step='0.001' class='h_qty form-control'>\
                                    <input value='<?php echo get_invoice_data($trn_row_id, 'total_paid');?>' name='grand_total' required min='0' type='hidden' step='0.001' class='h_total form-control' value='grand_total_paid'>";

                                    ceil_1.innerHTML = "<select class='select' disabled style='width:250px;' name='product_id' id='product_id_<?php echo $trn_row_id;?>'><option value='<?php echo $invoice_item;?>'><?php echo get_payable_items_data_by_id($invoice_item,"name");?></option></select>"+appendHidden;
                                    ceil_2.innerHTML = "<div class='input-group'><span class='input-group-text'><b>₦</b></span>\
                                    <input disabled value='<?php echo $invoice_item_amount;?>' name='h_total' required min='0' type='number' step='0.01' class='h_total h_total_<?php echo $trn_row_id;?> form-control' id='h_total_<?php echo $trn_row_id;?>'>\
                                    <div class='btn-group'>\
                                        <button data-bs-toggle='modal' data-bs-target='#edit_receipt_item' class='btn-xs btn-primary text-white' style='padding:6px;' onclick=get_item_data('<?php echo $invoice_id;?>','<?php echo $invoice_number;?>',<?php echo $trn_row_id;?>); type='button'>\
                                            <span data-toggle='tooltip' class='fa fa-pencil text-primsary'></span>\
                                        </button>\
                                    </div></div>";

                                    autoCals();
                            </script>
                            <?php // echo get_payable_items_data_by_id($invoice_item,"name");?>
                            <script src="./assets/js/custom-select.js"></script>

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