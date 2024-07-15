<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";

        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "create-expenses", "", "") === "allowed"){
            if(
                isset($_POST['item_id']) && !empty($_POST['item_id']) &&
                isset($_POST['given_to']) && !empty($_POST['given_to']) &&
                isset($_POST['expenses_date']) && !empty($_POST['expenses_date']) &&
                isset($_POST['description']) && !empty($_POST['description']) &&
                isset($_POST['amount']) && !empty($_POST['amount'])
            ){

                $given_to = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['given_to']));
                $item_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['item_id']));
                $expenses_date = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['expenses_date']));
                $description = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['description']));
                $amount = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['amount']));

                $url_id = md5(password_hash(rand(2345,76543), PASSWORD_DEFAULT));
                $expenses_number = generate_alpha_numeric(rand(3,4), rand(2,3));
                $generate = mysqli_prepare($conn, "INSERT INTO expenses(expenses_number, `user_id`, given_to, item_id, `description`, amount, url_id, `expense_date`) VALUES(?,?,?,?,?,?,?,?)");
                mysqli_stmt_bind_param($generate, "sssissss", $expenses_number, $account_id, $given_to, $item_id, $description, $amount, $url_id, $expenses_date);
                if(mysqli_stmt_execute($generate)){

                    echo server_response("success", "<b>Success!</b> Expenses successfully created! " , "100");

                    if(is_authorized($account_type, "pg-edit-customer", "", "") === "allowed" ) {
                        redirect("./edit-expenses?id=$url_id", "200");
                    } else {
                        redirect("./expenses", "200");
                    } ?>

                    <script>
                        setTimeout(() => {
                            $("#action-btn").attr("disabled","disabled");
                            $("#action-btn").html("<span class='fa fa-spin fa-spinner'></span> Redirecting... Please wait");
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
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to create expenses. Please if you think this was a mistake, contact your administrator.", "100");
        }

    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>