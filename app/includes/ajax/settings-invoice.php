<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";

        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "invoice-settings", "", "") === "allowed"){
            if(
                isset($_POST['footer_text']) && !empty($_POST['footer_text']) && 
                isset($_POST['invoice_printout']) && !empty($_POST['invoice_printout']) && 
                isset($_POST['invoice_allow_excess_payment']) && !empty($_POST['invoice_allow_excess_payment']) && 
                // isset($_POST['invoice_allow_duplicate_contents']) && !empty($_POST['invoice_allow_duplicate_contents']) && 
                isset($_POST['invoice_show_excess_payment_in_invoice']) && !empty($_POST['invoice_show_excess_payment_in_invoice']) && 
                isset($_POST['invoice_show_excess_payment_in_report']) && !empty($_POST['invoice_show_excess_payment_in_report']) && 
                isset($_POST['can_apply_customer_balance_to_invoice']) && !empty($_POST['can_apply_customer_balance_to_invoice']) && 

                isset($_POST['show_app_logo']) && !empty($_POST['show_app_logo'])
            ){

                $invoice_prefix = Base64_encode(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_prefix'])));
                $footer_text = Base64_encode(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['footer_text'])));
                $invoice_printout = Base64_encode(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_printout'])));
                $invoice_allow_excess_payment = Base64_encode(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_allow_excess_payment'])));
                // $invoice_allow_duplicate_contents = Base64_encode(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_allow_duplicate_contents'])));
                $invoice_show_excess_payment_in_invoice = Base64_encode(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_show_excess_payment_in_invoice'])));
                $invoice_show_excess_payment_in_report = Base64_encode(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_show_excess_payment_in_report'])));
                $can_apply_customer_balance_to_invoice = Base64_encode(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['can_apply_customer_balance_to_invoice'])));

                $show_app_logo = Base64_encode(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['show_app_logo'])));

                $id = "1";

                $save = @mysqli_prepare($conn, "UPDATE softdata SET invoice_printout=?, invoice_prefix=?, footer_text=?, show_logo=?, invoice_allow_excess_payment=?, invoice_show_excess_payment_in_invoice=?, invoice_show_excess_payment_in_report=?, can_apply_customer_balance_to_invoice=? WHERE id=?");
                @mysqli_stmt_bind_param($save, "ssssssssi", $invoice_printout, $invoice_prefix, $footer_text, $show_app_logo, $invoice_allow_excess_payment, $invoice_show_excess_payment_in_invoice, $invoice_show_excess_payment_in_report, $can_apply_customer_balance_to_invoice, $id);
                if(mysqli_stmt_execute($save)){
                    echo server_response("success", "Changes successfully saved!", "100");
                } else {
                    echo server_response("error", "Something went wrong!", "100");
                }
            } else {
                echo server_response("error", "All fields are required!", "100");
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to modify invoice settings. Pease if you think this was a mistake, contact your administrator.", "100");
        }
    } else {
        echo server_response("error", "Something went wrong!", "100");
    }
?>