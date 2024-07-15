<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";

        require "../../includes/update_activity.php";

        $can_apply_customer_balance = get_company_data("can_apply_customer_balance_to_invoice");

        if( isset($_POST['customer_id']) ){

            $customer_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['customer_id']));
            $customer_balance = get_customer_balance($customer_id, "pending", "sum", "excess_amount");?>

            <span class="alert-primary" style="font-size:14px;background-color:#eee;padding: 7px 0px;width:400px;border--radius:10px;">
                <?php if($customer_balance > 0) {?>
                    <button <?php if($can_apply_customer_balance === "yes") {?> onclick="apply_customer_bal()" <?php } else { echo "disabled"; } ?> id="apply_customer_balance_btn" class="btn btn-sm btn-warning" type="button"> <span class="fa fa-plus"></span> Apply Balance </button>
                <?php } ?>
                <span id="customer_bal_action_btn"></span>
                <span> <b>ACC/BAL: </b> ₦<?php echo custom_money_format($customer_balance);?> </span> 
                <!-- <br/> -->
            </span>

            <script>
                $("#customer_balance_0").remove();
                $("button#apply_customer_balance_btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Apply Balance");
                $("button#apply_customer_balance_btn").removeAttr("disabled");
                $("button#apply_customer_balance_btn").attr("onclick", "apply_customer_bal()");
                $("#customer_bal_action_btn").html("");

                <?php if($can_apply_customer_balance != "yes") {?>
                    $("button#apply_customer_balance_btn").attr("disabled", "disabled");
                    $("button#apply_customer_balance_btn").removeAttr("onclick");
                <?php } ?>


                Calculate_Splits();

            </script>
            <?php
        } else {
            echo server_response("error", "All fields are required!", "100");
        }

    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>