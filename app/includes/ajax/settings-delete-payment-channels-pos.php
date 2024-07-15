<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "payment-channels", "", "") === "allowed"){
            if( isset($_POST['pos_id']) && !empty($_POST['pos_id'])){

                $pos_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['pos_id']));

                if(get_payment_channels_pos_data_by_url_id($pos_id, "url_id") != $pos_id) { exit(server_response("error", "Invalid Channel ID...", "100")); }

                $update = @mysqli_prepare($conn, "DELETE FROM bank_accounts WHERE url_id=?");
                @mysqli_stmt_bind_param($update, "s", $pos_id);
                if(mysqli_stmt_execute($update)){
                    echo server_response("success", "Channel successfully deleted!", "100");?>

                    <script> $("#pos_tbl_row_<?php echo $pos_id;?>").remove(); </script>

                <?php
                } else {
                    echo server_response("error", "Something went wrong!", "100");
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