<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "create-receipt", "", "") === "allowed" || is_authorized($account_type, "edit-receipt", "", "") === "allowed"){

            echo server_response("info", "Processing request... ", "200");

            $split_modal = @$_POST['split_modal'];
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

            for($count = 0; $count < 1; $count++){?>

                <?php $row_tracking = md5(password_hash(rand(45678, 456789), PASSWORD_DEFAULT)); ?>

                <script>

                    var table = document.getElementById("split_table");
                    var row_id = table.rows.length-0;
                    var row = table.insertRow(row_id);
                    row.setAttribute("id", "del_split_<?php echo $row_tracking;?>");

                    var ceil_1 = row.insertCell(0);
                    var ceil_2 = row.insertCell(1);
                    var ceil_3 = row.insertCell(2);
                    var ceil_4 = row.insertCell(3);

                    ceil_1.innerHTML = "<select style='width:200px;' class='select split_payment_method' name='split_payment_method[]' id='split_payment_method_<?php echo $row_tracking;?>'><option value='' label='Choose Method'>Choose Method</option> <?php echo get_payment_methods('', 'list', '');?> </select>";
                    ceil_2.innerHTML = "<input placeholder=\"Payer\'s Name\" type='text' name='split_payment_name[]' class='split_payment_name form-control' style-='width:100px;'>";
                    ceil_3.innerHTML = "<input placeholder='Amount paid' value='<?php echo $remainder_;?>' type='number' onkeydown='Calculate_Splits()' onkeyup='Calculate_Splits()' onchange='Calculate_Splits()' onclick='Calculate_Splits()' min='0.01' step='0.01' name='split_amount[]' class='split_amount form-control' style-='width:100px;'>";
                    ceil_4.innerHTML = "<a style='position:absolute;' id='rm_<?php echo $row_tracking;?>' onclick='"+removeSplitRow('#rm_<?php echo $row_tracking;?>')+"' href='javascript:(void)'><span data-toggle='tooltip' style='float:right' title='Remove this row?' class='fa fa-trash-o text-danger'></span></a><input type='date' class='payment_date form-control' name='split_payment_date[]' id='payment_date'>";

                </script>

                <script src="./assets/js/custom-select.js"></script>

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
                    </script>
                <?php
                }
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to execute this action. Please if you think this was a mistake, contact your administrator.", "100");
        }
    }
?>