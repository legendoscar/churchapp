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
            if(is_authorized($account_type, "edit-user-role", "", "") === "allowed"){
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
                        $update = @mysqli_prepare($conn, "UPDATE staff_roles SET `role_name`=?, alias_name=?, `status`=? WHERE id=?");
                        @mysqli_stmt_bind_param($update, "sssi", $role_name, $alias_name, $role_status, $role_id);
                        if(mysqli_stmt_execute($update)){

                            mysqli_commit($conn);
                            echo server_response("success", "User role successfully created!", "10");

                            $role_name = account_role_data_by_alias($alias_name, "role_name");
                            $status = account_role_data_by_alias($alias_name, "status");
                            $icon = account_role_data_by_alias($alias_name, "icon");
                            $link = "window.location.href='?role=$alias_name'"; ?>

                            <script> $("li#row_<?php echo $role_id;?>").html("<a href='javascript:(void)' <?php if($role_status != 'can_login') { ?> style='color:red;text-decoration:line-through;' <?php } ?> ><span onclick=<?php echo $link;?>><span class='la la-<?php echo $icon;?>'></span> <?php echo $role_name;?></span><span class='role-action'><span class='action-circle large' data-bs-toggle='modal' data-bs-target='#edit_role' onclick='get_role_data(<?php echo $role_id;?>)'><i class='fa fa-pencil'></i></span></span></a>"); </script>

                            <?php
                        } else {
                            echo server_response("error", "Something went wrong!", "100");
                        }
                    } else {
                        echo server_response("error", "Name already exist", "100");
                    }
                } else {
                    echo server_response("error", "All fields are required!", "100");
                }
            } else {
                echo server_response("error", "<b>Access Denied!</b> You're not allowed to execute this action. Please if you think this was a mistake, contact your administrator.", "100"); 
            }
        } catch(mysqli_sql_exception $error) {
            mysqli_rollback($conn);
            throw server_response("error", "Something went wrong!", "100");
        } 

    } else {
        echo server_response("error", "Something went wrong!", "100");
    }
?>