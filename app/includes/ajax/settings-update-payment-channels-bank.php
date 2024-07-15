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
                isset($_POST['bank_name']) && !empty($_POST['bank_name']) && 
                isset($_POST['channel_id']) && !empty($_POST['channel_id']) 
            ){

                if(strlen(str_replace(" ", "", $_POST['account_name'])) < 3 ) {
                    server_response("error", "Enter a minimum of 3 characters in Account Name.", "100");

                } else if(!preg_match("/^[0-9]*$/", $_POST['account_number'])) {
                    server_response("error", "Invalid Account Number. Only digits are allowed.", "100");

                } else {
                    $account_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['account_name']));
                    $account_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['account_number']));
                    $bank_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['bank_name']));
                    $channel_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['channel_id']));

                    if(get_bank_data_by_id($bank_name, "id") != $bank_name) {
                        exit(server_response("error", "Invalid Bank selected", "100"));
                    }

                    $bank_id = get_bank_data_by_id($bank_name, "id");
                    $bank_name = get_bank_data_by_id($bank_name, "name");

                    if(get_payment_channels_bank_data_by_url_id($channel_id, "url_id") != $channel_id) { exit(server_response("error", "Invalid Channel ID...", "100")); }

                    $update = @mysqli_prepare($conn, "UPDATE bank_accounts SET account_name=?, account_number=?, bank_id=?, bank_name=? WHERE url_id=? and method_id='3'");
                    @mysqli_stmt_bind_param($update, "sssss", $account_name, $account_number, $bank_id, $bank_name, $channel_id);
                    if(mysqli_stmt_execute($update)){
                        echo server_response("success", "Channel successfully updated!", "100");?>
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