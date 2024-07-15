<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        ignore_user_abort(true); // USER CANNOT ABORT TRANSACTION
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";

        mysqli_autocommit($conn, false); // DISABLE AUTO COMMIT
        mysqli_begin_transaction($conn); // BEGIN TRANSACTION

        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";
        require "../../includes/update_activity.php";

        try {
            if(is_authorized($account_type, "edit-payslip", "", "") === "allowed"){
                if(
                    isset($_POST['payslip_id']) && !empty($_POST['payslip_id']) && 
                    isset($_POST['payslip_number']) && !empty($_POST['payslip_number']) && 
                    isset($_POST['payslip_item']) && !empty($_POST['payslip_item']) && 
                    isset($_POST['payslip_item_amount']) && !empty($_POST['payslip_item_amount']) ){

                    $payslip_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_id']));
                    $payslip_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_number']));
                    $payslip_item = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_item']));
                    $payslip_item_amount = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['payslip_item_amount']));

                    $item_group = get_payslip_items_data_by_id($payslip_item, "group_name");
                    $employee_id = get_payslip_data_by_number($payslip_number, "employee_id");
                    $payslip_month = get_payslip_data_by_number($payslip_number, "payslip_month");

                    $class_group_name = $item_group;
                    if($item_group != "earnings") {
                        $class_group_name = $item_group."s";
                    }

                    if(get_payslip_transaction_item_data_by_item_id($payslip_item, $payslip_id, $payslip_number, "id") != "not_found") {
                        exit(server_response("error", "Item already exist!", "100"));
                    }

                    $update = @mysqli_prepare($conn, "INSERT INTO payslip_transaction_items(`item_id`, item_group, item_amount, payslip_id, payslip_number, employee_id, payslip_month) VALUES(?,?,?,?,?,?,?)");
                    @mysqli_stmt_bind_param($update, "issisis", $payslip_item, $item_group, $payslip_item_amount, $payslip_id, $payslip_number, $employee_id, $payslip_month);
                    if(mysqli_stmt_execute($update)){

                        $payslip_row_id = mysqli_insert_id($conn);

                        $total_earnings = get_payslip_transactions($payslip_id, $payslip_number, "total", "earnings");
                        $total_deduction = get_payslip_transactions($payslip_id, $payslip_number, "total", "deduction");

                        $net_pay = ($total_earnings-$total_deduction);

                        $update = mysqli_prepare($conn, "UPDATE payslips SET total_amount=? WHERE id=? and payslip_number=?");
                        mysqli_stmt_bind_param($update, "sis", $net_pay, $payslip_id, $payslip_number);
                        if(mysqli_stmt_execute($update)){

                            mysqli_commit($conn);
                            echo server_response("success", "Item successfully updated!", "100");?>

                            <script>

                                var table = document.getElementById("<?php echo $class_group_name;?>_table");
                                var row = table.insertRow(table.rows.length-1);
                                row.setAttribute("id", "row_id_<?php echo $payslip_row_id;?>");

                                var ceil_1 = row.insertCell(0);
                                var ceil_2 = row.insertCell(1);

                                ceil_1.innerHTML = "<select class='select' disabled style='width:250px;'><option value='<?php echo $payslip_item;?>'><?php echo get_payslip_items_data_by_id($payslip_item,"name");?></option></select>";
                                ceil_2.innerHTML = "<div class='input-group'><span class='input-group-text'><b>₦</b></span>\
                                <input disabled value='<?php echo $payslip_item_amount;?>' name='h_total' required min='0' type='number' step='0.01' class='<?php echo $class_group_name;?>_value h_total_<?php echo $payslip_row_id;?> form-control' id='h_total_<?php echo $payslip_row_id;?>'>\
                                <div class='btn-group'>\
                                    <button data-bs-toggle='modal' data-bs-target='#edit_payslip_item' class='btn-xs btn-primary text-white' style='padding:6px;' onclick=get_payslip_item_data('<?php echo $payslip_id;?>','<?php echo $payslip_number;?>',<?php echo $payslip_row_id;?>); type='button'>\
                                        <span data-toggle='tooltip' class='fa fa-pencil text-primsary'></span>\
                                    </button>\
                                </div></div>";

                                autoCals();
                            </script>

                            <?php // echo get_payable_items_data_by_id($payslip_item,"name");?>
                            <script src="./assets/js/custom-select.js"></script>
                            <?php
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
                echo server_response("error", "<b>Access Denied!</b> You're not allowed to update payslips. Please if you think this was a mistake, contact your administrator.", "100");
            }
        } catch(mysqli_sql_exception $exception) {
            mysqli_rollback($conn);
            throw $exception;
        }
    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>