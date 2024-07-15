<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/additional_function.php";
        require "../../includes/function.php";

        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "create-branches", "", "") == "allowed"){ // ENSURE USER HAS ADMIN PREVILLEGE 
            if( isset($_POST['item_name']) && !empty($_POST['item_name'])){

                $item_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['item_name']));

                $created_by = $account_id;
                $url_id = md5(password_hash(rand(200, 1000), PASSWORD_DEFAULT));

                $create = @mysqli_prepare($conn, "INSERT INTO branches(`name`, created_by, url_id) value(?,?,?)");
                @mysqli_stmt_bind_param($create, "sis", $item_name, $created_by, $url_id);
                if(mysqli_stmt_execute($create)){

                    $item_id = mysqli_insert_id($conn);
                    echo server_response("success", "Item successfully created!", "100");?>

                    <script> window.location.reload(); </script>

                <?php
                } else {
                    echo server_response("error", "Something went wrong!", "1000");
                }
            } else {
                echo server_response("error", "All fields are required!", "100");
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to create new item. Please if you think this was a mistake, contact your administrator.", "100");
        }
    } else {
        echo server_response("error", "Something went wrong!", "100");
    }
?>