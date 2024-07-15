<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";

        require "../../includes/update_activity.php";

        if(is_authorized($user_account_type, "delete-expenses", "", "") === "allowed") {
            if( 
                isset($_POST['item_id']) && !empty($_POST['item_id']) &&
                isset($_POST['target']) && !empty($_POST['target'])
            ){

                $item_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['item_id']));
                $target = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['target']));

                if(get_expenses_data_by_urlid($item_id, "url_id") == "not_found") { exit(server_response("error", "Expenses ID not found!", "100")); }

                $delete = @mysqli_prepare($conn, "DELETE FROM expenses where url_id=?");
                @mysqli_stmt_bind_param($delete, "s", $item_id);
                if(mysqli_stmt_execute($delete)){

                    echo server_response("success", "Item successfully deleted!", "100");?>
                    <script>
                        document.getElementById("total-items").innerHTML = "<?php echo number_format(get_expenses("count"));?>";
                        document.getElementById("<?php echo $target;?>").innerHTML = "";
                    </script>
                <?php
                } else {
                    echo server_response("error", "Something went wrong!", "1000");
                }
            } else {
                echo server_response("error", "All fields are required!", "100");
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to delete this item. Please if you think this was a mistake, contact your administrator.", "100"); 
        }

    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>