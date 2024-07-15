<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/update_activity.php";

        function reverse_request() {?>
            <script>
                $("button#apply_customer_balance_btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Apply Balance");
                $("button#apply_customer_balance_btn").removeAttr("disabled");
                $("button#apply_customer_balance_btn").attr("onclick", "apply_customer_bal()");
            </script>
        <?php 
        } ?>

            <script>
                $("#customer_balance_0").remove();
                Calculate_Splits();
            </script>

        <?php

        if(is_authorized($account_type, "receipts", "", "") === "allowed" ){

            if(get_company_data("can_apply_customer_balance_to_invoice") != "yes" ) {
                reverse_request();
                exit(server_response("error", "<b>Error:</b> This feature has been disabled. You can not apply customer's balance to an invoice.", "100"));
            }

            // echo server_response("info", "Processing request... ", "200");

            $split_modal = @$_POST['split_modal'];
            $customer_id = @$_POST['customer_id'];

            if(in_array(get_customer_data($customer_id, "id"), array("error", "not_found")) ) {
                reverse_request();
                exit(server_response("error", "<b>Error:</b> Invalid Payer ID. Please select a valid customer.", "100"));
            }

            $auto_fill_remainder = @$_POST['auto_fill_remainder'];
            $remainder_ = "";
            if($auto_fill_remainder == 1) {
                $remainder = @$_POST['remainder'];
                if($remainder <= 0) {
                    $remainder = 0;
                } else {
                    $remainder_ = str_replace(',', '', custom_money_format($remainder));
                }
            }

            $customer_balance = get_customer_balance($customer_id, "pending", "sum", "excess_amount");

            if($customer_balance <= 0) {
                reverse_request();
                exit(server_response("error", "<b>Error!</b> Balance is insufficient.", "100"));
            }

            for($count = 0; $count < 1; $count++){?>

                <?php $row_tracking = md5(password_hash(rand(45678, 456789), PASSWORD_DEFAULT)); ?>

                <script>

                    var table = document.getElementById("split_table");
                    var row_id = 1;
                    // var row_id = table.rows.length-1;
                    var row = table.insertRow(row_id);
                    row.setAttribute('style', 'background-color:#007bff12');
                    row.setAttribute("id", "customer_balance_0");

                    var ceil_1 = row.insertCell(0);
                    var ceil_2 = row.insertCell(1);
                    var ceil_3 = row.insertCell(2);
                    var ceil_4 = row.insertCell(3);

                    ceil_1.innerHTML = "<select style='width:200px;' class='select customer_balance_payment_method' name='customer_balance_payment_method' id='customer_balance_payment_method_<?php echo $row_tracking;?>'><option value='customer_balance' label='Balance'>Balance</option></select>";
                    ceil_2.innerHTML = "<input value='-' readonly type='text' name='split_payment_name' class='split_payment_name form-control' style='background-color:#eee;'>";
                    ceil_3.innerHTML = "<input readonly style='background-color:#eee;' value='<?php echo $customer_balance;?>' type='number' onkeydown='Calculate_Splits()' onkeyup='Calculate_Splits()' onchange='Calculate_Splits()' onclick='Calculate_Splits()' min='0.001' max='<?php echo $customer_balance;?>' step='0.001' name='customer_balance_amount' class='split_amount form-control' style-='width:100px;'>";
                    ceil_4.innerHTML = "<a id='rm_<?php echo $row_tracking;?>' style='position:absolute;' onclick='"+removeSplitRow2('#rm_<?php echo $row_tracking;?>')+"' href='javascript:(void)'><span style='float:right' data-toggle='tooltip' title='Remove this row?' class='fa fa-trash-o text-danger'></span></a><input disabled value='<?php echo date("Y-m-d");?>' type='date' class='payment_date form-control' name='split_payment_date[]' id='payment_date'>";

                    $("#customer_bal_action_btn").html("<a id='rm_<?php echo $row_tracking;?>' style='' onclick='mini_removal()' href='javascript:(void)'><span data-toggle='tooltip' title='Remove' class='btn  fa fa-times text-danger'></span></a>");

                    function mini_removal() {
                        $("#customer_balance_0").remove();
                        $("button#apply_customer_balance_btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Apply Balance");
                        $("button#apply_customer_balance_btn").removeAttr("disabled");
                        $("button#apply_customer_balance_btn").attr("onclick", "apply_customer_bal()");
                        $("#customer_bal_action_btn").html("");
                        Calculate_Splits();
                    }
                </script>

            <?php
                if($count == 0){
                    echo server_response("success", "Success! 1 row has been added!", "200");?>
                    <script>
                        function scrollSmoothToBottom(id) {
                            var div = document.getElementById(id);
                            $('#' + id).animate({ scrollTop: div.scrollHeight - div.clientHeight}, 500);
                        }
                        scrollSmoothToBottom('<?php echo $split_modal;?>');
                        Calculate_Splits();
                        $("button#apply_customer_balance_btn").fadeIn("slow").html("<span class='fa fa-check'></span> Balance Applied ");
                        $("button#apply_customer_balance_btn").removeAttr("onclick");
                    </script>
                    <script src="./assets/js/custom-select.js"></script>
                <?php
                }
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to execute this account. Please if you think this was a mistake, contact your administrator.", "100");
        }
    }
?>