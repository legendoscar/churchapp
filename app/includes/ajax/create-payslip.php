<?php session_start();

    if($_SERVER['REQUEST_METHOD'] === "POST") {

        ignore_user_abort(true);

        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";

        mysqli_autocommit($conn, false); // DISABLE AUTO COMMIT
        mysqli_begin_transaction($conn); //BEGIN TRANSACTION

        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";
        require "../../includes/update_activity.php";

        try {
            if(is_authorized($account_type, "create-payslip", "", "") === "allowed"){ 
                if(
                    isset($_POST['employee_name']) && !empty($_POST['employee_name']) && // STATIC
                    isset($_POST['month']) && !empty($_POST['month']) && // STATIC
                    isset($_POST['mode_of_payment']) && !empty($_POST['mode_of_payment']) && // STATIC
                    isset($_POST['status']) && !empty($_POST['status']) && // STATIC
                    isset($_POST['additional_note']) &&
                    isset($_POST['h_total_net_pay']) && // STATIC 
                    isset($_POST['earnings_id']) && !empty($_POST['earnings_id']) && // DYNAMIC 
                    isset($_POST['earnings_value']) && !empty($_POST['earnings_value']) // DYNAMIC
                ){

                    $has_deduction = "no";

                    // Check and Validate Deductions
                    if(isset($_POST['deductions_id'])) {
                        if(empty($_POST['deductions_id']) ) {
                            exit(server_response("error", "All fields are required! Check deducssstions area", "100"));
                        }

                        if(isset($_POST['deductions_value']) && empty($_POST['deductions_value']) ) {
                            exit(server_response("error", "All fields are required! Check deductions area", "100"));
                        }

                        $has_deduction = "yes"; // We detected deductions
                    }

                    //RECEIPT STATIC INFO
                    $employee_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['employee_name']));
                    $mode_of_payment = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['mode_of_payment']));
                    $payslip_status = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['status']));
                    $payslip_month = SanitizeTEXT($_POST['month']);
                    $payment_date = SanitizeTEXT($_POST['payment_date']);
                    $additional_note = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['additional_note']));
                    $payslip_net_pay = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['h_total_net_pay']));
                    $created_by = $account_id;

                    // VERIFY EMPLOYEE ID and make sure employee is enrolled on Payroll (Payslip)
                    if(in_array(get_user_data($employee_name, "id"), array("error", "not_found")) ) {
                        exit(server_response("error", "<b>Error:</b> Invalid Payer ID.", "100"));
                    }

                    if(get_user_data($employee_name, "basic_salary") < 1) { //  Employees with zero salaries are not eligible for payslip / payroll
                        exit(server_response("error", "<b>Error:</b> Employee not eligible for payslip. Basic Salary was not found.", "100"));
                    }

                    $employee_department = get_user_data($employee_name, "department");
                    $employee_designation = get_user_data($employee_name, "designation");
                    $employee_branch = get_user_data($employee_name, "branch");
                    $basic_salary = get_user_data($employee_name, "basic_salary");
                    $payment_bank = get_user_data($employee_name, "payment_bank");

                    // check if employee has an existing payslip for selected month
                    if(get_payslip_data_by_employee_and_date($employee_name, $payslip_month, "payslip_number") != "not_found") {
                        $existing_payslip_id = get_payslip_data_by_employee_and_date($employee_name, $payslip_month, "payslip_number");
                        exit(server_response("error", "<b>Error:</b> We found an existing payslip for this employee.<br/><b>Existing Payslip ID: $existing_payslip_id</b>", "100"));
                    }

                    $url_id = md5(password_hash(rand(rand(456, 5678), rand(45678, 4567898)), PASSWORD_DEFAULT)); // generate payslip id

                    $append = rand(10,99).date("s");
                    $payslip_number = "PSLP".substr(time(), 5, 7).$append;

                    $is_split = "no"; // DEFAULT VALUE FOR NON-SPLITTED INVOICE PAYMENT METHODS

                    // REJECT DUPLICATE ITEMS IN EARNINGS (PAYSLIP)
                    $total_items_in_payslip = count($_POST['earnings_id']);
                    $total_unique_items_in_payslip = count(array_unique($_POST['earnings_id']));
                    if($total_unique_items_in_payslip != $total_items_in_payslip) {
                        exit(server_response("error", "<b>Error:</b> Duplicate items currently not allowed. Check earnings area.", "100"));
                    }

                    // REJECT DUPLICATE ITEMS IN DEDUCTIONS (PAYSLIP)
                    if($has_deduction == "yes") {
                        $total_items_in_deductions = count($_POST['deductions_id']);
                        $total_unique_items_in_deductions = count(array_unique($_POST['deductions_id']));
                        if($total_unique_items_in_deductions != $total_items_in_deductions) {
                            exit(server_response("error", "<b>Error:</b> Duplicate items currently not allowed. Check deduction area.", "100"));
                        }
                    }

                    // DO MATH (EARNINGS)
                    $errors_from_earnings = 0;
                    $errors_from_deductions = 0;
                    $total_sum_of_earnings = 0;
                    $total_sum_of_deductions = 0;
                    $errors_from_salary_alteration = 0;

                    for($count_earnings=0; $count_earnings<count($_POST['earnings_id']); $count_earnings++){

                        $earnings_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['earnings_id'][$count_earnings]));
                        $earnings_value = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['earnings_value'][$count_earnings]));

                        // check validity of items
                        if(get_payslip_items_data_by_id($earnings_id, "id") != $earnings_id || get_payslip_items_data_by_id($earnings_id, "group_name") != "earnings") {
                            $errors_from_earnings = ($errors_from_earnings+1);
                        }

                        if(empty(trim(str_replace(" ","", $earnings_value))) || $earnings_value == 0 ) { $errors_from_earnings = ($errors_from_earnings+1); }

                        $total_sum_of_earnings += $earnings_value;
                        // catch the default item, which is the basic salary
                        if(get_payslip_items_data_by_id($earnings_id, "is_default") == "default") {
                            $b_salary = $earnings_value;
                            // check if employee salary was altered
                            if($b_salary != get_user_data($employee_name, "basic_salary")) {
                                $errors_from_salary_alteration = ($errors_from_salary_alteration+1);
                            }
                        }
                    }

                    if($has_deduction == "yes") {
                        for($count_deductions=0; $count_deductions<count($_POST['deductions_id']); $count_deductions++){

                            $deductions_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['deductions_id'][$count_deductions]));
                            $deductions_value = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['deductions_value'][$count_deductions]));

                            // check validity of items
                            if(get_payslip_items_data_by_id($deductions_id, "id") != $deductions_id || get_payslip_items_data_by_id($deductions_id, "group_name") != "deduction") {
                                $errors_from_deductions = ($errors_from_deductions+1);
                            }

                            if(empty(trim(str_replace(" ","", $deductions_value))) || $deductions_value == 0 ) { $errors_from_deductions = ($errors_from_deductions+1); }
                            $total_sum_of_deductions += $deductions_value;
                        }
                    }

                    if($errors_from_earnings != 0) { exit(server_response("error", "We found $errors_from_earnings error(s) from the earnings area", "100")); }
                    if($errors_from_deductions != 0) { exit(server_response("error", "We found $errors_from_deductions error(s) from the deduction area", "100")); }

                    $total_sum_of_deductions = ($total_sum_of_deductions);
                    $total_sum_of_earnings = ($total_sum_of_earnings);
                    $grand_total_net_pay = ($total_sum_of_earnings-$total_sum_of_deductions);

                    if($grand_total_net_pay != $payslip_net_pay) { exit(server_response("error", "We found a miscalculation. Your request can not be processed.", "100")); }
                    if($errors_from_salary_alteration > 0 ) { exit(server_response("error", "Something must have gone wrong! Employee basic salary is no longer the same.", "100")); }

                    // CREATE INVOICE
                    $generate = mysqli_prepare($conn, "INSERT INTO payslips(employee_branch, payment_date, payment_mode, url_id, payslip_number, employee_id, employee_department, employee_designation, monthly_basic_salary, total_amount, payslip_month, additional_note, payslip_status, created_by) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                    mysqli_stmt_bind_param($generate, "isissiiissssii", $employee_branch, $payment_date, $mode_of_payment, $url_id, $payslip_number, $employee_name, $employee_department, $employee_designation, $basic_salary, $payslip_net_pay, $payslip_month, $additional_note, $payslip_status, $created_by);
                    if(mysqli_stmt_execute($generate)){ // NO ERRORS WHILE CREATING PAYSLIP

                        $payslip_id = mysqli_insert_id($conn);

                        $bank_id = get_user_data($employee_name, "bank_id");
                        $bank_name = get_user_data($employee_name, "bank_name");
                        $account_number = get_user_data($employee_name, "account_number");
                        $account_name = get_user_data($employee_name, "account_name");
                        $url_id2 = md5(password_hash($url_id, PASSWORD_DEFAULT));

                        $generate2 = mysqli_prepare($conn, "INSERT INTO employee_payslip_banks(payment_bank, employee_id, payslip_id, bank_id, bank_name, account_number, account_name, created_by, url_id) VALUES(?,?,?,?,?,?,?,?,?)");
                        mysqli_stmt_bind_param($generate2, "iiiisssss", $payment_bank, $employee_name, $payslip_id, $bank_id, $bank_name, $account_number, $account_name, $created_by, $url_id2);
                        if(mysqli_stmt_execute($generate2)){ // NO ERRORS WHILE CREATING PAYSLIP

                            // MERGE BOTH EARNINGS AND DEDUCTION
                            $array_data_id = $_POST['earnings_id'];
                            $array_data_value = $_POST['earnings_value'];

                            // Does this transaction merging?
                            if($has_deduction == "yes") {
                                $array_data_id = array_merge($_POST['earnings_id'], $_POST['deductions_id']);
                                $array_data_value = array_merge($_POST['earnings_value'], $_POST['deductions_value']);
                            }

                            // ENSURE DATA - VALUE ARRAYS ARE EQUAL
                            if(count($array_data_id) != count($array_data_value)) { exit(server_response("error", "<b>Error! Data mismatched</b>", "100")); }

                            // set default
                            $insert_errors = 0;
                            $counter = 0;

                            for($count_array=0; $count_array<count($array_data_id); $count_array++){
                                $counter++;

                                $item_id = $array_data_id[$count_array];
                                $item_name = get_payslip_items_data_by_id($item_id, "name");
                                $group = get_payslip_items_data_by_id($item_id, "group_name");
                                $is_default = get_payslip_items_data_by_id($item_id, "is_default");
                                $item_value = $array_data_value[$count_array];

                                // start creating payslip transaction details
                                $create = mysqli_prepare($conn, "INSERT INTO payslip_transaction_items(employee_id, item_id, item_group, item_amount, payslip_id, payslip_number, payslip_month) VALUES (?,?,?,?,?,?,?)");
                                mysqli_stmt_bind_param($create, "iississ", $employee_name, $item_id, $group, $item_value, $payslip_id, $payslip_number, $payslip_month);
                                if(!mysqli_stmt_execute($create)){
                                    $insert_errors += ($insert_errors+1);
                                }
                            }

                            // end of creation (loop)
                            if($counter == count($array_data_id)) {
                                // save all transactions, since there are no errors
                                mysqli_commit($conn);

                                if(is_authorized($account_type, "edit-payslip", "", "") === "allowed") {
                                    redirect("./edit-payslip?id=$url_id", "100");
                                } else {
                                    redirect("./view-payslip?id=$url_id", "100");
                                }

                                echo server_response("success", "Invoice successfully created! " , "100");?>

                                <script>
                                    setTimeout(() => {
                                        $("#action-btn").attr("disabled","disabled");
                                        $("#action-btn").html("<span class='fa fa-spin fa-spinner'></span> Redirecting... Please wait");
                                    }, 0);
                                </script>

                                <?php
                            }
                        } else {
                            exit(server_response("error", "Something went wrong!", "100"));
                        }

                    } else {
                        echo server_response("error", "Something went wrong!", "100");
                    }

                } else {
                    echo server_response("error", "All fields are required!", "100");
                }
            } else {
                echo server_response("error", "<b>Access Denied!</b> You're not allowed to create payslips. Please if you think this was a mistake, contact your administrator.", "100"); 
            }
        } catch(mysqli_sql_exception $exception) {
            mysqli_rollback($conn);
            throw $exception;
        }
    } else {
        echo server_response("error", "Something went wrong!", "100");
    }
?>