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

                    $group = get_payslip_transaction_item_data_by_id($payslip_row_id, "item_group");

                    // Check Invoice existence
                    if(get_payslip_data_by_number($payslip_number, "id") == "not_found") {
                        exit(server_response("error", "Payslip does not exist", "100"));
                    }

                    if(get_payslip_transaction_item_data_by_id($payslip_row_id, "id") == "not_found") {
                        exit(server_response("error", "Item does not exist", "100"));
                    }

                    $previous_item = get_payslip_transaction_item_data_by_id($payslip_row_id, "item_id");
                    $new_item = $payslip_item;

                    if($previous_item != $new_item) {
                        if(get_payslip_transaction_item_data_by_item_id($new_item, $payslip_id, $payslip_number, "id") != "not_found") {
                            exit(server_response("error", "Item already exist!", "100"));
                        }
                    }

                    $update = @mysqli_prepare($conn, "UPDATE payslip_transaction_items SET `item_id`=?, item_amount=? where id=? and payslip_id=? and payslip_number=?");
                    @mysqli_stmt_bind_param($update, "isiis", $payslip_item, $payslip_item_amount, $payslip_row_id, $payslip_id, $payslip_number);
                    if(mysqli_stmt_execute($update)){

                        $total_earnings = get_payslip_transactions($payslip_id, $payslip_number, "total", "earnings");
                        $total_deduction = get_payslip_transactions($payslip_id, $payslip_number, "total", "deduction");

                        $net_pay = ($total_earnings-$total_deduction);

                        $is_default = get_payslip_items_data_by_id($payslip_row_id, "is_default");

                        $monthly_basic_salary = get_payslip_data_by_number($payslip_number, "monthly_basic_salary");
                        if($is_default == "default") { $monthly_basic_salary = $payslip_item_amount; }

                        $update = mysqli_prepare($conn, "UPDATE payslips SET total_amount=?, monthly_basic_salary=? WHERE id=? and payslip_number=?");
                        mysqli_stmt_bind_param($update, "ssis", $net_pay, $monthly_basic_salary, $payslip_id, $payslip_number);
                        if(mysqli_stmt_execute($update)){

                            mysqli_commit($conn);
                            echo server_response("success", "Item successfully updated!", "100");?>

                            <script>
                                $("#row_id_<?php echo $payslip_row_id;?> select").html("<option value='' label='Choose Product'><?php echo get_payslip_items_data_by_id($payslip_item, "name");?></option>");
                                $("#row_id_<?php echo $payslip_row_id;?> div.input-group input").val("<?php echo $payslip_item_amount;?>");
                                autoCals();
                            </script>

                        <?php
                        } else {
                            echo server_response("error", "Something went wrong!", "100");
                        }
                    } else {
                        echo server_response("error", "Something went wrong!", "100");
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