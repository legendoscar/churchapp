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
                isset($_POST['pos_name']) && !empty($_POST['pos_name']) && 
                isset($_POST['channel_id']) && !empty($_POST['channel_id']) 
            ){

                if(strlen(str_replace(" ", "", $_POST['pos_name'])) < 3 ) {
                    server_response("error", "Enter a minimum of 3 characters in POS Name.", "100");

                } else {
                    $pos_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['pos_name']));
                    $channel_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['channel_id']));

                    if(get_payment_channels_pos_data_by_url_id($channel_id, "url_id") != $channel_id) { exit(server_response("error", "Invalid Channel ID...", "100")); }

                    $update = @mysqli_prepare($conn, "UPDATE bank_accounts SET account_name=? WHERE url_id=? and method_id='2'");
                    @mysqli_stmt_bind_param($update, "ss", $pos_name, $channel_id);
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