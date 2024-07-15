<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";

        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "edit-employee-basic-salary", "", "") === "allowed"){

            if(
                isset($_POST['user_has_payroll']) && !empty($_POST['user_has_payroll']) &&
                isset($_POST['payroll_employee_url_id']) && !empty($_POST['payroll_employee_url_id']) &&
                isset($_POST['bank_name']) && !empty($_POST['bank_name']) && 
                isset($_POST['account_name']) && isset($_POST['account_number']) &&
                isset($_POST['payment_bank']) && !empty($_POST['payment_bank']) &&
                isset($_POST['employee_id']) && !empty($_POST['employee_id'])
            ){

                $account_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['account_name']));
                $account_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['account_number']));
                $bank_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['bank_name']));
                $payment_bank = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payment_bank']));

                $basic_salary = "0";
                if($_POST['user_has_payroll'] == "yes") {
                    if( (!isset($_POST['basic_salary']) /** || empty($_POST['basic_salary']) **/ ) ) { exit(server_response("error", "All fields are required!", "100")); }
                    $basic_salary = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['basic_salary']));

                    if(strlen(str_replace(" ", "", $_POST['account_name'])) < 3 ) {
                        exit(server_response("error", "Enter a minimum of 3 characters in Account Name.", "100"));
                    }

                    if(!preg_match("/^[0-9]*$/", $_POST['account_number'])) {
                        exit(server_response("error", "Invalid Account Number. Only digits are allowed.", "100"));
                    }

                }

                //CUSTOMER INFO
                $user_has_payroll = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['user_has_payroll']));
                $employee_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['employee_id']));
                $payroll_employee_url_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payroll_employee_url_id']));

                if( !empty($bank_name) && $bank_name != "" && get_bank_data_by_id($bank_name, "id") != $bank_name) { exit(server_response("error", "Invalid Bank selected", "100")); }
                if( !empty($payment_bank) && $payment_bank != "" && get_payment_bank_by_id($payment_bank, "id") != $payment_bank) { exit(server_response("error", "Invalid Payment Bank selected", "100")); }

                $bank_id = get_bank_data_by_id($bank_name, "id");
                $bank_name = str_replace("not_found", "", get_bank_data_by_id($bank_name, "name"));

                if(get_user_data($employee_id, "acc_id") != $employee_id) {// IS EMPLOYEE ID VALID
                    exit(server_response("error", "Invalid Employee ID!", "100"));
                }

                $generate = mysqli_prepare($conn, "UPDATE user_accounts SET payment_bank=?, basic_salary=?, bank_id=?, bank_name=?, account_number=?, account_name=? where url_id=? and acc_id=?");
                mysqli_stmt_bind_param($generate, "issssssi", $payment_bank, $basic_salary, $bank_id, $bank_name, $account_number, $account_name, $payroll_employee_url_id, $employee_id);
                if(mysqli_stmt_execute($generate)){

                    echo server_response("success", "<b>Success!</b> Payroll successfully updated! " , "100");

                } else {
                    echo server_response("error", "Something went wrong!", "100");
                }
            } else {
                echo server_response("error", "All fields are required!", "100");
            } 
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to execute this account. Please if you think this was a mistake, contact your administrator.", "100"); 
        }

    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>