<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "edit-receipt", "", "") === "allowed" ){

            if(!isset($_POST['row_id']) || !isset($_POST['invoice_number']) || !isset($_POST['invoice_id'])) {
                exit(server_response("error", "<b>Some fields are missing!</b>", "200"));
            }

            $row_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['row_id']));
            $invoice_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_number']));
            $invoice_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['invoice_id']));
            $product_id = get_invoice_trn_data2($row_id, "product_id");

            $invoice_item_amount = get_invoice_trn_data2($row_id, "amount");

            for($count = 0; $count < 1; $count++){?>

                <?php $row_tracking = md5(password_hash(rand(45678, 456789), PASSWORD_DEFAULT)); ?>
                <script> $("#edit_item_container").html("<div class='row'> <div class='col-md-6'> <div class='form-group'> <select style='width:200px;' class='select' name='invoice_item' id='invoice_item_<?php echo $row_tracking;?>'><option value='' label='Choose Method'>Choose Products</option><?php echo get_payable_items($product_id);?></select> </div> </div> <div class='col-md-6'> <div class='form-group'> <div class='input-group'><span class='input-group-text'><b>₦</b></span><input type='number' name='invoice_item_amount' value='<?php echo $invoice_item_amount;?>' min='0.01' step='0.01' class='form-control'></div> </div> </div>  <div class='col-md-12' style='margin-top:10px;'> <button id='update-invoice-item-btn' class='btn btn-sm btn-primary'> <span class='fa fa-save'></span> Save changes</button> <button onclick='delete_invoice_item()' id='delete-invoice-item-btn' class='btn btn-sm btn-danger' type='button'> <span class='fa fa-trash-o'></span> Delete </button>  <input type='hidden' name='invoice_id' value='<?php echo $invoice_id;?>' class='form-control'> <input type='hidden' name='invoice_row_id' value='<?php echo $row_id;?>' class='form-control'> <input type='hidden' name='invoice_number' value='<?php echo $invoice_number;?>' class='form-control'> </div> </div>"); </script>
                <script src="./assets/js/custom-select.js"></script>

            <?php
                // if($count == 0){
                //     echo server_response("success", "Success! 1 row has been added!", "200");?>
                <!-- //     <script> -->
                <!-- //         function scrollSmoothToBottom(id) {
                //             var div = document.getElementById(id);
                //             $('#' + id).animate({ scrollTop: div.scrollHeight - div.clientHeight}, 500);
                //         }
                //         scrollSmoothToBottom('<?php // echo $split_modal;?>');
                //         Calculate_Splits(); -->
                <!-- //     </script> -->
                <?php
                // }
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to update this receipt. Please if you think this was a mistake, contact your administrator.", "100");
        }
    }
?>