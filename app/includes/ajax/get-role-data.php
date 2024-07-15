<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "edit-user-role", "", "") === "allowed") {

            if(!isset($_POST['role_id']) ) {
                exit(server_response("error", "<b>Some fields are missing!</b>", "200"));
            }

            $role_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['role_id']));

            if(account_role_data_by_id($role_id, "id") != $role_id) { exit(server_response("error", "Invalid Role Id!", "100")); }

            $role_name = account_role_data_by_id($role_id, "role_name");
            $role_status = account_role_data_by_id($role_id, "status");

            for($count = 0; $count < 1; $count++){?>

                <?php $row_tracking = md5(password_hash(rand(45678, 456789), PASSWORD_DEFAULT)); ?>
                <script> $("#role_container").html("<div class='row'> <div class='col-md-6'> <div class='form-group'> <input type='text' placeholder='Enter Role Name' name='role_name' value='<?php echo $role_name;?>' class='form-control'> </div> </div> <div class='col-md-6'> <div class='form-group'> <select class='select' name='role_status'><option <?php if($role_status == "can_login") { echo "selected"; } ?>  value='can_login'>Can Login</option><option <?php if($role_status != "can_login") { echo "selected"; } ?> value='cannot_login'>Cannot Login</option></select> </div> </div>  <div class='col-md-12' style='margin-top:10px;'> <button id='update-role-btn' class='btn btn-sm btn-primary'> <span class='fa fa-save'></span> Save changes</button> <button onclick='delete_role_item()' id='delete-role-btn' class='btn btn-sm btn-danger' type='button'> <span class='fa fa-trash-o'></span> Delete </button> <input type='hidden' name='role_id' value='<?php echo $role_id;?>' class='form-control'> </div> </div>"); </script>
                <script src="./assets/js/custom-select.js"></script>
            <?php
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to execute this action. Please if you think this was a mistake, contact your administrator.", "100");
        }
    }
?>