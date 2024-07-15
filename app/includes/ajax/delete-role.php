<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        ignore_user_abort(false);

        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";

        mysqli_begin_transaction($conn);
        mysqli_autocommit($conn, false);

        require "../../includes/global_function.php";
        require "../../includes/function.php";

        require "../../includes/update_activity.php";

        try {
            if(is_authorized($account_type, "create-user-role", "", "") === "allowed"){
                if(
                    isset($_POST['role_name']) && !empty($_POST['role_name']) &&
                    isset($_POST['role_status']) && !empty($_POST['role_status']) &&
                    isset($_POST['role_id']) && !empty($_POST['role_id'])
                ){

                    $role_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['role_name']));
                    $role_status = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['role_status']));
                    $role_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['role_id']));
                    $alias_name = seo_friendly_url($role_name);

                    //Save New Product
                    if(account_role_data_by_id($role_id, "id") == $role_id) {
                        $update = @mysqli_prepare($conn, "DELETE FROM staff_roles WHERE id=?");
                        @mysqli_stmt_bind_param($update, "i", $role_id);
                        if(mysqli_stmt_execute($update)){

                            $update = @mysqli_prepare($conn, "DELETE FROM privileges WHERE role_id=?");
                            @mysqli_stmt_bind_param($update, "i", $role_id);
                            if(mysqli_stmt_execute($update)){

                                mysqli_commit($conn);
                                echo server_response("success", "User role successfully deleted!", "10");?>
                                    <script>
                                        $("li#row_<?php echo $role_id;?>").remove();
                                        $("#edit_role").modal("hide");
                                    </script>
                                <?php
                            } else {
                                echo server_response("error", "Something went wrong!", "100");
                            }
                        } else {
                            echo server_response("error", "Something went wrong!", "100");
                        }
                    } else {
                        echo server_response("error", "Role Id does not exist", "100");
                    }
                } else {
                    echo server_response("error", "All fields are required!", "100");
                }
            } else { 
                echo server_response("error", "<b>Access Denied!</b> You're not allowed to create user roles. Please if you think this was a mistake, contact your administrator.", "100"); 
            }
        } catch(mysqli_sql_exception $error) {
            mysqli_rollback($conn);
            throw server_response("error", "Something went wrong!", "100");
        } 

    } else {
        echo server_response("error", "Something went wrong!", "100");
    }
?>