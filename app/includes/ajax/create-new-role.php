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
                if( isset($_POST['role_name']) && !empty($_POST['role_name']) ){

                    $role_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['role_name']));
                    $alias_name = seo_friendly_url($role_name);

                    //Save New Product
                    if(account_role_data_by_alias($alias_name, "id") === "not_found") {
                        $create = @mysqli_prepare($conn, "INSERT INTO staff_roles(`role_name`, alias_name) value(?,?)");
                        @mysqli_stmt_bind_param($create, "ss", $role_name, $alias_name);
                        if(mysqli_stmt_execute($create)){
                            $new_role_id = mysqli_insert_id($conn);

                            mysqli_commit($conn);
                            echo server_response("success", "User role successfully created!", "900");

                            $role_name = account_role_data_by_alias($alias_name, "role_name");
                            $status = account_role_data_by_alias($alias_name, "status");
                            $icon = account_role_data_by_alias($alias_name, "icon");
                            $link = "window.location.href='?role=$alias_name'"; ?>

                            <script> $("#roles-menu li").last().append("<li><a href='javascript:(void)'><span onclick=<?php echo $link;?>><span class='la la-<?php echo $icon;?>'></span><?php echo $role_name;?></span><span class='role-action'><span class='action-circle large' data-bs-toggle='modal' data-bs-target='#edit_role' onclick='get_role_data(<?php echo $new_role_id;?>)'><i class='material-icons'>edit</i></span></span></a></li>"); </script>

                            <?php
                        } else {
                            echo server_response("error", "Something went wrong!", "1000");
                        }
                    } else {
                        echo server_response("error", "Name already exist", "100");
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