<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "receipts", "", "") === "allowed"){

            if(!isset($_POST['invoice_number']) || !isset($_POST['invoice_id'])) {
                exit(server_response("error", "<b>Some fields are missing!</b>", "200"));
            }
            $invoice_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_number']));
            $invoice_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_id'])); ?>
            <script> $("#receipt_payment_transaction_container").html("<?php get_splitted_payments2($invoice_id, $invoice_number);?>"); </script>
            <?php
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to view this transaction. Please if you think this was a mistake, contact your administrator.", "100");
        }
    }
?>