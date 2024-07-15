<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/additional_function.php";
        require "../../includes/function.php";
        require "../../includes/update_activity.php";

        // if(is_authorized($account_type, "edit-profile", "", "") === "allowed"){
            if( isset($_POST['old_password']) && !empty($_POST['old_password']) && 
                isset($_POST['new_password']) && !empty($_POST['new_password']) && 
                isset($_POST['confirm_new_password']) && !empty($_POST['confirm_new_password'])
            ){

                $old_password = password_hash($_POST['old_password'], PASSWORD_DEFAULT);
                $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $confirm_new_password = password_hash($_POST['confirm_new_password'], PASSWORD_DEFAULT);

                if(strlen($_POST['new_password']) >= 8 && strlen($_POST['confirm_new_password']) >= 8){
                    if(get_user_data($account_id, "acc_id") != "not_found"){// IS USER ID EXISTING
                        if(password_verify($_POST['old_password'], get_user_data($account_id, "user_pwd"))){
                            if($_POST['new_password'] == $_POST['confirm_new_password']){
                                $update = mysqli_prepare($conn, "UPDATE user_accounts SET user_pwd=? WHERE acc_id=?");
                                mysqli_stmt_bind_param($update, "ss", $new_password, $account_id);
                                if(mysqli_stmt_execute($update)){
                                    echo server_response("success", "<b>Success!</b> Password successfully changed! " , "100");
                                } else {
                                    echo server_response("error", "Something went wrong!", "100");
                                }
                            } else {
                                echo server_response("error", "New password does not match", "100");
                            }
                        } else {
                            echo server_response("error", "Invalid old password. Please verify and try again", "100");
                        }
                    } else {
                        echo server_response("error", "User id does not exist. Please try again!", "100");
                    }
                } else {
                    echo server_response("error", "<b>Password error</b> Please enter alteast 8 or more characters length!", "100");
                }
            } else {
                echo server_response("error", "All fields are required!", "100");
            }
        // } else {
        //     echo server_response("error", "<b>Access Denied!</b> You're not allowed to execute this action. Please if you think this was a mistake, contact your administrator.", "100"); 
        // }
    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>