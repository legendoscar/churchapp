<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";
        require "../../includes/update_activity.php";

        if( is_authorized($account_type, "edit-expenses", "", "") === "allowed"){
            if(
                isset($_POST['item_id']) && !empty($_POST['item_id']) &&
                isset($_POST['given_to']) && !empty($_POST['given_to']) &&
                isset($_POST['expenses_date']) && !empty($_POST['expenses_date']) &&
                isset($_POST['description']) && !empty($_POST['description']) &&
                isset($_POST['url_id']) && !empty($_POST['url_id']) &&
                isset($_POST['amount']) && !empty($_POST['amount'])
            ){

                $item_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['item_id']));
                $given_to = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['given_to']));
                $expenses_date = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['expenses_date']));
                $description = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['description']));
                $amount = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['amount']));
                $url_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['url_id']));

                if(get_expenses_data_by_urlid($url_id, "url_id") == "not_found") { exit(server_response("error", "Expenses ID not found!", "100")); }

                $generate = mysqli_prepare($conn, "UPDATE expenses SET given_to=?, item_id=?, `description`=?, amount=?, `expense_date`=? where url_id=?");
                mysqli_stmt_bind_param($generate, "sissss", $given_to, $item_id, $description, $amount, $expenses_date, $url_id);
                if(mysqli_stmt_execute($generate)){
                    echo server_response("success", "<b>Success!</b> Expenses successfully updated! " , "100");
                } else {
                    echo server_response("error", "Something went wrong!", "100");
                }
            } else {
                echo server_response("error", "All fields are required!", "100");
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to update expenses. Please if you think this was a mistake, contact your administrator.", "100");
        }

    } else {
        echo server_response("error", "Something went wrong!", "100");
    }
?>