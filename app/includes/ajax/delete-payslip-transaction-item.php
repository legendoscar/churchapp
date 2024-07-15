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
            if(is_authorized($account_type, "edit-payslip", "", "") === "allowed"){ 
                if(
                    isset($_POST['payslip_id']) && !empty($_POST['payslip_id']) && 
                    isset($_POST['payslip_number']) && !empty($_POST['payslip_number']) && 
                    isset($_POST['payslip_item']) && !empty($_POST['payslip_item']) && 
                    isset($_POST['payslip_row_id']) && !empty($_POST['payslip_row_id']) && 
                    isset($_POST['payslip_item_amount']) && !empty($_POST['payslip_item_amount']) ){

                    $payslip_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_id']));
                    $payslip_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_number']));
                    $payslip_item = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_item']));
                    $payslip_item_amount = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_item_amount']));
                    $payslip_row_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_row_id']));

                    $update = @mysqli_prepare($conn, "DELETE FROM payslip_transaction_items where id=? and payslip_id=? and payslip_number=?");
                    @mysqli_stmt_bind_param($update, "iis", $payslip_row_id, $payslip_id, $payslip_number);
                    if(mysqli_stmt_execute($update)){

                        $total_earnings = get_payslip_transactions($payslip_id, $payslip_number, "total", "earnings");
                        $total_deduction = get_payslip_transactions($payslip_id, $payslip_number, "total", "deduction");

                        $net_pay = ($total_earnings-$total_deduction);

                        $update = mysqli_prepare($conn, "UPDATE payslips SET total_amount=? WHERE id=? and payslip_number=?");
                        mysqli_stmt_bind_param($update, "sis", $net_pay, $payslip_id, $payslip_number);
                        if(mysqli_stmt_execute($update)){

                            mysqli_commit($conn);
                            echo server_response("success", "Item successfully updated!", "100");?>
                            <script>
                                $("#row_id_<?php echo $payslip_row_id;?>").html("");
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
                echo server_response("error", "<b>Access Denied!</b> You're not allowed to update payslips. Please if you think this was a mistake, contact your administrator.", "100");
            }
        } catch(mysqli_sql_exception $exception) {
            mysqli_rollback($conn);
            throw $exception;
        }
    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>