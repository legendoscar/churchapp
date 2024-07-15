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
                    isset($_POST['employee_name']) && !empty($_POST['employee_name']) &&
                    isset($_POST['month']) && !empty($_POST['month']) &&
                    isset($_POST['mode_of_payment']) && !empty($_POST['mode_of_payment']) &&
                    isset($_POST['status']) && !empty($_POST['status']) &&
                    isset($_POST['payment_bank']) && !empty($_POST['payment_bank']) &&
                    isset($_POST['payment_date']) && !empty($_POST['payment_date']) &&

                    isset($_POST['account_name']) && !empty($_POST['account_name']) &&
                    isset($_POST['account_number']) && !empty($_POST['account_number']) &&
                    isset($_POST['bank_name']) && !empty($_POST['bank_name']) &&

                    isset($_POST['additional_note']) ){

                    $payslip_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_id']));
                    $payslip_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_number']));
                    $employee_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['employee_name']));
                    $month = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['month']));
                    $mode_of_payment = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['mode_of_payment']));
                    $status = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['status']));
                    $additional_note = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['additional_note']));
                    $payment_bank = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payment_bank']));
                    $payment_date = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payment_date']));

                    // Check Invoice existence
                    if(get_payslip_data_by_number($payslip_number, "id") == "not_found") {
                        exit(server_response("error", "Payslip does not exist", "100"));
                    }

                    // check duplicate
                    if(get_payslip_data_by_number($payslip_number, "employee_id") != $employee_name || get_payslip_data_by_number($payslip_number, "payslip_month") != $month) {
                        if(get_payslip_data_by_employee_and_date($employee_name, $month, "id") != "not_found") {
                            exit(server_response("error", "Payslip already exist", "100"));
                        }

                        if(get_user_data($employee_name, "basic_salary") <= 0 ) {
                            exit(server_response("error", "We couldn't find employee basic salary.", "100"));
                        }
                    }

                    $update = @mysqli_prepare($conn, "UPDATE payslip_transaction_items SET `employee_id`=?, payslip_month=? where payslip_id=? and payslip_number=?");
                    @mysqli_stmt_bind_param($update, "isis", $employee_name, $month, $payslip_id, $payslip_number);
                    if(mysqli_stmt_execute($update)){

                        $update = mysqli_prepare($conn, "UPDATE payslips SET employee_id=?, payslip_month=?, additional_note=?, payslip_status=?, payment_mode=?, payment_date=? WHERE id=? and payslip_number=?");
                        mysqli_stmt_bind_param($update, "issiisis", $employee_name, $month, $additional_note, $status, $mode_of_payment, $payment_date, $payslip_id, $payslip_number);
                        if(mysqli_stmt_execute($update)){

                            $account_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['account_name']));
                            $account_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['account_number']));
                            $bank_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['bank_name']));

                            if(get_bank_data_by_id($bank_name, "id") != $bank_name) {
                                exit(server_response("error", "Invalid Bank selected", "100"));
                            }

                            $bank_id = get_bank_data_by_id($bank_name, "id");
                            $bank_name = get_bank_data_by_id($bank_name, "name");

                            $generate2 = mysqli_prepare($conn, "UPDATE employee_payslip_banks SET payment_bank=?, employee_id=?, bank_id=?, bank_name=?, account_number=?, account_name=? WHERE payslip_id=?");
                            mysqli_stmt_bind_param($generate2, "isisssi", $payment_bank, $employee_name, $bank_id, $bank_name, $account_number, $account_name, $payslip_id);
                            if(mysqli_stmt_execute($generate2)){

                                mysqli_commit($conn);
                                echo server_response("success", "Payslip successfully updated!", "100");

                            } else {
                                exit(server_response("error", "Something went wrong!", "100"));
                            }

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