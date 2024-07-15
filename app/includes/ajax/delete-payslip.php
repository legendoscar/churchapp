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
        require "../../includes/additional_function.php";

        require "../../includes/update_activity.php";

        try {
            if(is_authorized($account_type, "delete-payslip", "", "") === "allowed"){ 

                if( isset($_POST['payslip_number']) && !empty($_POST['payslip_number']) && isset($_POST['payslip_id']) && !empty($_POST['payslip_id'])){

                    $payslip_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_number']));
                    $payslip_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_id']));

                    if(get_payslip_data_by_number($payslip_number, "id") == "not_found"){// IS PAYSLIP EXISTING?
                        exit(server_response("error", "Payslip no longer exist!", "100"));
                    }

                    // DELETE PAYSLIP TRANSACTIONS
                    $delete = mysqli_prepare($conn, "DELETE FROM payslip_transaction_items WHERE payslip_id=? and payslip_number=?");
                    mysqli_stmt_bind_param($delete, "is", $payslip_id, $payslip_number);
                    if(mysqli_stmt_execute($delete)){

                        // DELETE PAYSLIP 
                        $delete0 = mysqli_prepare($conn, "DELETE FROM payslips WHERE id=? and payslip_number=?");
                        mysqli_stmt_bind_param($delete0, "is", $payslip_id, $payslip_number);
                        if(mysqli_stmt_execute($delete0)){

                            $delete0 = mysqli_prepare($conn, "DELETE FROM employee_payslip_banks WHERE payslip_id=?");
                            mysqli_stmt_bind_param($delete0, "i", $payslip_id);
                            if(mysqli_stmt_execute($delete0)){

                                mysqli_commit($conn);
                                echo server_response("success", "Success! Payslip successfully deleted", "100");?>
                                    <script> $("#payslip_id_<?php echo $payslip_id;?>").remove(); </script>
                                <?php

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
                echo server_response("error", "<b>Access Denied!</b> You're not allowed to delete this payslip. Please if you think this was a mistake, contact your administrator.", "100"); 
            }
        } catch(mysqli_sql_exception $exception) {
            mysqli_rollback($conn);
            throw $exception;
        } 
    } else {
        echo server_response("error", "Something went wrong!", "100");
    }

?>