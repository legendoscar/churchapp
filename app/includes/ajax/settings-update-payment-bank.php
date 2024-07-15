<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "payment-channels", "", "") === "allowed"){

            if( isset($_POST['bank_name']) && !empty($_POST['bank_name']) && isset($_POST['bank_id']) && !empty($_POST['bank_id']) ){

                if(strlen(str_replace(" ", "", $_POST['bank_name'])) < 3 ) {
                    server_response("error", "Enter a minimum of 3 characters in Bank Name.", "100");

                } else {

                    $bank_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['bank_name']));
                    $bank_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['bank_id']));

                    if(get_payment_bank_by_url_id($bank_id, "url_id") != $bank_id) { exit(server_response("error", "Invalid Bank ID...", "100")); }

                    $update = @mysqli_prepare($conn, "UPDATE payment_banks SET bank_name=? WHERE url_id=?");
                    @mysqli_stmt_bind_param($update, "ss", $bank_name, $bank_id);
                    if(mysqli_stmt_execute($update)){
                        echo server_response("success", "Payment Bank successfully updated!", "100");?>
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