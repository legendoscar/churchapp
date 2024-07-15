<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/additional_function.php";
        require "../../includes/function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "edit-employee", "", "") === "allowed"){

            if( isset($_POST['employee_id']) && !empty($_POST['employee_id']) && isset($_POST['password']) && !empty($_POST['password']) ){

                $account_id = strtolower(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['employee_id'])));
                $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                // if(strlen($_POST['password']) >= 8 && strlen($_POST['password']) >= 8){
                if(strlen($_POST['password']) >= 8){
                    if(get_user_data($account_id, "acc_id") != "not_found"){// IS USER ID EXISTING
                        // if($_POST['new_password'] == $_POST['confirm_new_password']){
                            $update = mysqli_prepare($conn, "UPDATE user_accounts SET user_pwd=? WHERE acc_id=?");
                            mysqli_stmt_bind_param($update, "ss", $new_password, $account_id);
                            if(mysqli_stmt_execute($update)){
                                echo server_response("success", "<b>Success!</b> Password successfully changed! " , "100");
                            } else {
                                echo server_response("error", "Something went wrong!", "100");
                            }
                        // } else {
                        //     echo server_response("error", "New password does not match", "100");
                        // }
                    } else {
                        echo server_response("error", "User id does not exist. Please try again!", "100");
                    }
                } else {
                    echo server_response("error", "<b>Password error</b> Please enter alteast 8 or more characters length!", "100");
                }

            } else {
                echo server_response("error", "All fields are required!", "100");
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to change employee account. Please if you think this was a mistake, contact your administrator.", "100"); 
        }
    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>