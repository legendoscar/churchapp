<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/additional_function.php";
        require "../../includes/function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "create-payslip", "", "") === "allowed" ){
            if($_POST['rows_number'] > 0){

                if($_POST['rows_number'] <= 6) {
                    for($count = 0; $count < $_POST['rows_number']; $count++){?>

                        <?php $row_tracking = md5(password_hash(rand(45678, 456789), PASSWORD_DEFAULT)); ?>
                        <?php $row_tracking2 = rand(1234, 456789); ?>

                        <script>

                            var table = document.getElementById("deductions_table");
                            var row = table.insertRow(table.rows.length-0); // var row = table.insertRow(table.rows.length-1);

                            row.setAttribute("id", "del_<?php echo $row_tracking;?>");

                            // AUTO CREATE TABLE COLUMNS
                            var ceil_1 = row.insertCell(0);
                            var ceil_2 = row.insertCell(1);

                            // TABLE COLUMN DATA
                            appendHidden = "<input type='hidden' name='for_qty_id[]' value='product_qty_<?php echo $row_tracking;?>' id='for_qty_id_<?php echo $row_tracking;?>' class='qty form-control'>\
                            <input type='hidden' name='for_rate_id[]' value='product_rate_<?php echo $row_tracking;?>' id='for_rate_id_<?php echo $row_tracking;?>' class='form-control'>\
                            <input type='hidden' name='for_amount_id[]' value='product_total_<?php echo $row_tracking;?>' id='for_amount_id_<?php echo $row_tracking;?>' class='form-control'>\
                            <input type='hidden' name='for_h_total[]' value='deductions_value_<?php echo $row_tracking;?>' id='for_deductions_value_<?php echo $row_tracking;?>' class='form-control'>\
                            <input type='hidden' value='switchWR_<?php echo $row_tracking;?>' id='switch_id_<?php echo $row_tracking;?>' name='switch_id[]' class='form-control'>\
                            <input type='hidden' value='wr_btn_text_<?php echo $row_tracking;?>' id='wr_btn_<?php echo $row_tracking;?>' name='wr_btn[]' class='form-control'>";

                            ceil_1.innerHTML = "<select class='form-control select' name='deductions_id[]'><option value='' label='Choose Product'>-- Select --</option><?php echo get_payslip_items("", "deduction", "");?></select>"+appendHidden;
                            ceil_2.innerHTML = "<div class='input-group'><span class='input-group-text'><b>₦</b></span>\
                            <input onchange='StartAccounting_<?php echo $row_tracking;?>()' onkeyup='StartAccounting_<?php echo $row_tracking;?>()' onkeydown='StartAccounting_<?php echo $row_tracking;?>()' value='0.00' name='deductions_value[]' required min='0' type='number' step='0.01' class='deductions_value deductions_value_<?php echo $row_tracking;?> form-control' id='deductions_value_<?php echo $row_tracking;?>'>\
                            <a style='float:right' onclick=deleteRow('del_<?php echo $row_tracking;?>') href='#!'><span data-toggle='tooltip' title='Remove this row?' class='btn  fa fa-trash-o text-danger'></span></a></div>";

                            function StartAccounting_<?php echo $row_tracking;?>(){
                                autoCals();
                            }

                            autoCals();
                        </script>

                        <script src="./assets/js/custom-select.js"></script>

                    <?php
                        if($count == 0){
                            echo server_response("success", "Success! ".$_POST['rows_number']." row(s) has been added!", "200");
                        }
                    }
                } else {
                    echo server_response("error", "Maximum row is 6.", "200");
                }
            } else {
                echo server_response("error", "Please enter at least one digit ", "200");
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to execute this action. Please if you think this was a mistake, contact your administrator.", "100"); 
        }
    }
?>