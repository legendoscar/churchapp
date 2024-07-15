<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "edit-payslip", "", "") === "allowed"){

            if(!isset($_POST['row_id']) || !isset($_POST['payslip_number']) || !isset($_POST['payslip_id'])) {
                exit(server_response("error", "<b>Some fields are missing!</b>", "200"));
            }

            $row_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['row_id']));
            $payslip_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_number']));
            $payslip_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_id']));

            $main_item_row_id = get_payslip_transaction_item_data_by_id($row_id, "item_id");
            $payslip_item_amount = get_payslip_transaction_item_data_by_id($row_id, "item_amount");
            $column = get_payslip_transaction_item_data_by_id($row_id, "item_group");

            $is_default = get_payslip_items_data_by_id($main_item_row_id, "is_default");

            if($is_default == "default") {
                for($count = 0; $count < 1; $count++){?>
                    <?php $row_tracking = md5(password_hash(rand(45678, 456789), PASSWORD_DEFAULT)); ?>
                    <script> $("#edit_item_container").html("<div class='row'> <div class='col-md-6'> <div class='form-group'> <select style='width:200px;' class='select' name='payslip_item' id='payslip_item_<?php echo $row_tracking;?>'><option value='<?php echo $main_item_row_id;?>' label='Choose Method'><?php echo get_payslip_items_data_by_id($main_item_row_id, "name");?></option></select> </div> </div> <div class='col-md-6'> <div class='form-group'> <div class='input-group'><span class='input-group-text'><b>₦</b></span><input type='number' name='payslip_item_amount' value='<?php echo $payslip_item_amount;?>' min='0.01' step='0.01' class='form-control'></div> </div> </div>  <div class='col-md-12' style='margin-top:10px;'> <button id='update-item-btn' class='btn btn-sm btn-primary'> <span class='fa fa-save'></span> Save changes</button><input type='hidden' name='payslip_id' value='<?php echo $payslip_id;?>' class='form-control'> <input type='hidden' name='payslip_row_id' value='<?php echo $row_id;?>' class='form-control'> <input type='hidden' name='payslip_number' value='<?php echo $payslip_number;?>' class='form-control'> </div> </div>"); </script>
                    <script src="./assets/js/custom-select.js"></script>
                <?php
                }
            } else {
                for($count = 0; $count < 1; $count++){?>
                    <?php $row_tracking = md5(password_hash(rand(45678, 456789), PASSWORD_DEFAULT)); ?>
                    <script> $("#edit_item_container").html("<div class='row'> <div class='col-md-6'> <div class='form-group'> <select style='width:200px;' class='select' name='payslip_item' id='payslip_item_<?php echo $row_tracking;?>'><option value='' label='Choose Method'>Choose Item</option><?php echo get_payslip_items($main_item_row_id, "$column", "");?></select> </div> </div> <div class='col-md-6'> <div class='form-group'> <div class='input-group'><span class='input-group-text'><b>₦</b></span><input type='number' name='payslip_item_amount' value='<?php echo $payslip_item_amount;?>' min='0.01' step='0.01' class='form-control'></div> </div> </div>  <div class='col-md-12' style='margin-top:10px;'> <button id='update-item-btn' class='btn btn-sm btn-primary'> <span class='fa fa-save'></span> Save changes</button> <button onclick='delete_payslip_transaction_item()' id='delete-item-btn' class='btn btn-sm btn-danger' type='button'> <span class='fa fa-trash-o'></span> Delete </button>  <input type='hidden' name='payslip_id' value='<?php echo $payslip_id;?>' class='form-control'> <input type='hidden' name='payslip_row_id' value='<?php echo $row_id;?>' class='form-control'> <input type='hidden' name='payslip_number' value='<?php echo $payslip_number;?>' class='form-control'> </div> </div>"); </script>
                    <script src="./assets/js/custom-select.js"></script>
                <?php
                }
            }

        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to update this payslip. Please if you think this was a mistake, contact your administrator.", "100");
        }
    }
?>