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
            if(is_authorized($user_account_type, "delete-receipt", "", "") === "allowed") {

                if( isset($_POST['invoice_number']) && !empty($_POST['invoice_number']) && isset($_POST['invoice_id']) && !empty($_POST['invoice_id'])){ 

                    $invoice_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_number']));
                    $invoice_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_id']));

                    $invoice_date = get_invoice_data_by_number($invoice_number, "date_created");
                    $current_date = date("Y-m-d");

                    if(get_invoice_data_by_number($invoice_number, "id") != "not_found"){// IS INVOICE EXISTING?

                        //GET ALL INVOICE TRANSACTION DETAILS, RETURN THEM TO STOCK, EMPTY DISCOUNTS, DELETE INVOICE AND ITS TRANSACTIONS

                        // VERIFY INVOICE TRANSACTION DETAILS
                        // $check = mysqli_prepare($conn, "SELECT * FROM invoice_trn_details WHERE invoice_id=? and invoice_number=?");
                        // mysqli_stmt_bind_param($check, "is", $invoice_id, $invoice_number);
                        // if(mysqli_stmt_execute($check)){
                            // $get_result = mysqli_stmt_get_result($check);

                            // while($row = mysqli_fetch_array($get_result)){

                            //     $discountid = $row['discountid'];

                            //     $transaction_type = $row['transaction_type'];
                            //     $product_id = $row['product_id'];
                            //     $old_qty = $row['qty'];
                            //     $half_qty = $row['half_qty'];

                            //     $row_id = $row['id'];
                                
                            //     $invoice_type = get_invoice_trn_data($row_id, "type");
                                
                            //     auto_balance_product_stock($product_id, $invoice_date);
                            //     $old_stock = get_product_data($product_id, "in_stock");
                            //     $old_closing_stock = get_trk_product_details($product_id, $invoice_date, "closing_stock");

                            //     if(discountController("delete_discount", $invoice_id, $invoice_number, $product_id, "", $discountid, "", "") != "success") {
                            //         exit(server_response("error", "Something went wrong. Discount Integrity failed unexpectedly!!", "100"));
                            //     }

                            //     if($transaction_type == "wholesale") {

                            //         /** 
                            //          * RETURN TRANSACTION'S WHOLESALE QUANTITY TO STOCK
                            //          *  START ==============================================================================
                            //          */
                            //             if($current_date != $invoice_date){

                            //                 $wholesale_quantity = $old_qty;
                            //                 $returned_stock = $old_closing_stock + $wholesale_quantity;
                            //                 $total_whole_remaining = $returned_stock; // TOTAL WHOLESALE REMAINING 

                            //                 if(TrackStockController($total_whole_remaining, $product_id, $invoice_date) != "success") { 
                            //                     exit(server_response("error", "Something went wrong. Discount Integrity failed unexpectedly!!", "100"));
                            //                 }

                            //             } else {

                            //                 $wholesale_quantity = $old_qty;
                            //                 $returned_stock = $old_stock + $wholesale_quantity;
                            //                 $total_whole_remaining = $returned_stock; // TOTAL WHOLESALE REMAINING 

                            //                 if(TrackStockController($total_whole_remaining, $product_id, $invoice_date) != "success") {
                            //                     exit(server_response("error", "Something went wrong. Discount Integrity failed unexpectedly!!", "100")); 
                            //                 }

                            //                 if(StockController($product_id, $total_whole_remaining) != "success") { 
                            //                     exit(server_response("error", "Something went wrong. Discount Integrity failed unexpectedly!!", "100"));
                            //                 }
                            //             }
                            //         /** 
                            //          * RETURN TRANSACTION'S WHOLESALE QUANTITY TO STOCK
                            //          *  END ==============================================================================
                            //          */

                            //     } else {
                            //         exit(server_response("error", "Transaction failed!", "100")); 
                            //     }
                            // }

                            // DELETE INVOICE TRANSACTIONS
                            $delete_inv_trn = mysqli_prepare($conn, "DELETE FROM invoice_trn_details WHERE invoice_id=? and invoice_number=?");
                            mysqli_stmt_bind_param($delete_inv_trn, "is", $invoice_id, $invoice_number);
                            if(mysqli_stmt_execute($delete_inv_trn)){

                                // DELETE INVOICE
                                $delete_invoice = mysqli_prepare($conn, "DELETE FROM invoice WHERE id=? and invoice_number=?");
                                mysqli_stmt_bind_param($delete_invoice, "is", $invoice_id, $invoice_number);
                                if(mysqli_stmt_execute($delete_invoice)){

                                    $delete_invoice = mysqli_prepare($conn, "DELETE FROM splitted_payments WHERE invoice_id=? and invoice_number=?");
                                    mysqli_stmt_bind_param($delete_invoice, "is", $invoice_id, $invoice_number);
                                    if(mysqli_stmt_execute($delete_invoice)){

                                        $delete_invoice = mysqli_prepare($conn, "DELETE FROM invoice_excess_payments WHERE invoice_id=? and invoice_number=?");
                                        mysqli_stmt_bind_param($delete_invoice, "is", $invoice_id, $invoice_number);
                                        mysqli_stmt_execute($delete_invoice);

                                        if(auto_reverse_customer_excess($invoice_number) === "success") {

                                            mysqli_commit($conn);
                                            echo server_response("success", "Success! Invoice successfully deleted", "100");?>
                                                <script>
                                                    $("#invoice_id_<?php echo $invoice_id;?>").remove();
                                                </script>
                                            <?php

                                        } else {
                                            echo server_response("error", "Something went wrong!", "100");
                                        }

                                    } else {
                                        echo server_response("error", "Something went wrong!", "100");
                                    }
                                } else {
                                    echo server_response("error", "Something went wrong!", "100");
                                }
                            } else {
                                echo server_response("error", "Something went wrong!", "100");
                            }
                        // } else {
                        //     echo server_response("error", "Something went wrong!", "100");
                        // }
                    } else {
                        echo server_response("error", "Invoice no longer exist!", "100");
                    }
                } else {
                    echo server_response("error", "All fields are required!", "100");
                }
            } else {
                echo server_response("error", "<b>Access Denied!</b> You're not allowed to delete this receipt. Please if you think this was a mistake, contact your administrator.", "100"); 
            }
        } catch(mysqli_sql_exception $exception) {
            mysqli_rollback($conn);
            throw $exception;
        } 
    } else {
        echo server_response("error", "Something went wrong!", "100");
    }

?>