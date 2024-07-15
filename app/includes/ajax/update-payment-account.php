<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "edit-payment-account", "", "") === "allowed"){

            if(
                isset($_POST['account_name']) && !empty($_POST['account_name']) &&
                isset($_POST['contact_address']) && isset($_POST['email_address']) &&
                isset($_POST['phone_number']) && isset($_POST['customer_id'])
            ){

                // CUSTOMER INFO
                $account_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['account_name']));
                $contact_address = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['contact_address']));
                $phone_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['phone_number']));
                $email_address = strtolower(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['email_address'])));
                $customer_id = strtolower(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['customer_id'])));

                if(get_customer_data($customer_id, "customer_id") == "not_found"){ exit(server_response("error", "<b>Success!</b> Account does not exist" , "100")); }

                $generate = mysqli_prepare($conn, "UPDATE customers SET surname=?, phone=?, `address`=?, email_address=? WHERE customer_id=?");
                mysqli_stmt_bind_param($generate, "sssss", $account_name, $phone_number, $contact_address, $email_address, $customer_id);
                if(mysqli_stmt_execute($generate)){
                    echo server_response("success", "<b>Success!</b> Account has been successfully updated!" , "100");
                } else {
                    echo server_response("error", "Something went wrong!", "100");
                }
            } else {
                echo server_response("error", "All fields are required!", "100");
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to update this account. Please if you think this was a mistake, contact your administrator.", "100"); 
        }

    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>