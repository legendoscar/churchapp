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
                isset($_POST['pos_name']) && !empty($_POST['pos_name'])
            ){

                if(strlen(str_replace(" ", "", $_POST['pos_name'])) < 3 ) {
                    server_response("error", "Enter a minimum of 3 characters in POS Name.", "100");

                } else {
                    $pos_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['pos_name']));
                    $channel_id = md5(password_hash(rand(200, 1000), PASSWORD_DEFAULT));

                    $method_id = 2;
                    $update = @mysqli_prepare($conn, "INSERT INTO bank_accounts(account_name, url_id, method_id) VALUES(?,?,?)");
                    @mysqli_stmt_bind_param($update, "sss", $pos_name, $channel_id, $method_id);
                    if(mysqli_stmt_execute($update)){
                        echo server_response("success", "Channel successfully created!", "100");
                        redirect("./settings-edit-payment-channels-pos?id=$channel_id", 100);
                        ?>
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