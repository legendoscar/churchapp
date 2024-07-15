<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";

        require "../../includes/update_activity.php";

        // $can_apply_customer_balance = get_company_data("can_apply_customer_balance_to_invoice");

        if( isset($_POST['employee_id']) && !empty($_POST['employee_id']) ){

            $employee_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['employee_id']));
            $employee_basic_salary = get_user_data($employee_id, "basic_salary");?>

            <span class="alert-primary" style="font-size:14px;background-color:#eee;padding: 7px 0px;width:400px;border--radius:10px;">
                <span> <b>Basic Salary: </b> ₦<?php echo custom_money_format($employee_basic_salary);?> </span>
            </span>

            <script>
                $("#default_earnings_value").val("<?php echo $employee_basic_salary;?>");
                StartAccounting();

                $("#bank_name").val("<?php echo get_user_data($employee_id, "bank_name");?>");
                $("#account_number").val("<?php echo get_user_data($employee_id, "account_number");?>");
                $("#account_name").val("<?php echo get_user_data($employee_id, "account_name");?>");
                $("#payment_bank_name").val("<?php echo get_payment_bank_by_id(get_user_data($employee_id, "payment_bank"), "bank_name");?>");

            </script>

            <?php
        } else { ?>
            <span class="alert-primary" style="font-size:14px;background-color:#eee;padding: 7px 0px;width:400px;border--radius:10px;">
                <span> <b>Basic Salary: </b> ₦0.00</span>
            </span>

            <script>
                $("#default_earnings_value").val("0.00");
                StartAccounting();

                $("#bank_name").val("-");
                $("#account_number").val("-");
                $("#account_name").val("-");
                $("#payment_bank_name").val("-");

            </script>
        <?php
            // echo server_response("error", "All fields are required!", "100");
        }

    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>