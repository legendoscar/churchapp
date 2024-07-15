<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "create-payment-account", "", "") === "allowed"){
            if(
                isset($_POST['account_name']) && !empty($_POST['account_name']) &&
                isset($_POST['contact_address']) && isset($_POST['email_address']) &&
                isset($_POST['phone_number'])
            ){

                //CUSTOMER INFO
                $account_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['account_name']));
                $contact_address = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['contact_address']));
                $phone_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['phone_number']));
                $email_address = strtolower(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['email_address'])));

                $customer_id = "acc00".(get_customers("count")+1);

                if(get_customer_data($customer_id, "customer_id") != "not_found"){ $customer_id = "acct00".(get_customers("count")+1); }

                $generate = mysqli_prepare($conn, "INSERT INTO customers(customer_id, surname, phone, `address`, email_address) VALUES(?,?,?,?,?)");
                mysqli_stmt_bind_param($generate, "sssss", $customer_id, $account_name, $phone_number, $contact_address, $email_address);
                if(mysqli_stmt_execute($generate)){

                    echo server_response("success", "<b>Success!</b> Account has been successfully created! " , "100");

                    if(is_authorized($account_type, "pg-edit-customer", "", "") === "allowed" ) { redirect("./edit-payment-account?id=$customer_id", "100"); }
                    else { redirect("./payment-accounts", "100"); } ?>

                        <script>
                            setTimeout(() => {
                                $("#create-payment-account-btn").attr("disabled","disabled");
                                $("#create-payment-account-btn").html("<span class='fa fa-spin fa-spinner'></span> Redirecting... Please wait");
                            }, 0);
                        </script>

                    <?php

                } else {
                    echo server_response("error", "Something went wrong!", "100");
                }
            } else {
                echo server_response("error", "All fields are required!", "100");
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to create this account. Please if you think this was a mistake, contact your administrator.", "100"); 
        }

    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>