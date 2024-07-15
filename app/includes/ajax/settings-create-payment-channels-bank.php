<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "payment-channels", "", "") === "allowed"){
            if( 
                isset($_POST['account_name']) && !empty($_POST['account_name']) && 
                isset($_POST['account_number']) && !empty($_POST['account_number']) && 
                isset($_POST['bank_name']) && !empty($_POST['bank_name'])
            ){

                if(strlen(str_replace(" ", "", $_POST['account_name'])) < 3 ) {
                    server_response("error", "Enter a minimum of 3 characters in Account Name.", "100");

                } else if(!preg_match("/^[0-9]*$/", $_POST['account_number'])) {
                    server_response("error", "Invalid Account Number. Only digits are allowed.", "100");

                } else {

                    $account_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['account_name']));
                    $account_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['account_number']));
                    $bank_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['bank_name']));

                    if(get_bank_data_by_id($bank_name, "id") != $bank_name) { exit(server_response("error", "Invalid Bank selected", "100")); }

                    $bank_id = get_bank_data_by_id($bank_name, "id");
                    $bank_name = get_bank_data_by_id($bank_name, "name");

                    $channel_id = md5(password_hash(rand(200, 1000), PASSWORD_DEFAULT));

                    $method_id = 3;
                    $update = @mysqli_prepare($conn, "INSERT INTO bank_accounts(account_name, account_number, bank_id, bank_name, url_id, method_id) VALUES(?,?,?,?,?,?)");
                    @mysqli_stmt_bind_param($update, "ssssss", $account_name, $account_number, $bank_id, $bank_name, $channel_id, $method_id);
                    if(mysqli_stmt_execute($update)){
                        echo server_response("success", "Channel successfully created!", "100");
                        redirect("./settings-edit-payment-channels-bank?id=$channel_id", 100); ?>
                    <?php 
                    } else {
                        echo server_response("error", "Something went wrong!", "100");
                    }
                }
            } else {
                echo server_response("error", "All fields are required!", "100");
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to execute this action. Please if you think this was a mistake, contact your administrator.", "100"); 
        }
    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>