<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";

        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "edit-profile", "", "") === "allowed"){
            if(
                isset($_POST['addressed_as']) && !empty($_POST['addressed_as']) &&
                isset($_POST['firstname']) && !empty($_POST['firstname']) &&
                isset($_POST['surname']) && !empty($_POST['surname']) &&
                isset($_POST['other_name']) &&
                isset($_POST['gender']) && !empty($_POST['gender']) &&
                // isset($_POST['email_address']) && !empty($_POST['email_address']) &&
                isset($_POST['date_joined']) && !empty($_POST['date_joined']) &&
                isset($_POST['phone_number']) && !empty($_POST['phone_number']) &&
                isset($_POST['branch']) && !empty($_POST['branch']) &&
                isset($_POST['department']) && !empty($_POST['department']) &&
                isset($_POST['designation']) && !empty($_POST['designation']) &&
                isset($_POST['contact_address']) && !empty($_POST['contact_address'])
                // isset($_POST['employee_account_id']) && !empty($_POST['employee_account_id']) &&
                // isset($_POST['user_has_login']) && !empty($_POST['user_has_login'])
            ){

                $addressed_as = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['addressed_as']));
                $first_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['firstname']));
                $surname = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['surname']));
                $other_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['other_name']));
                $gender = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['gender']));
                // $email_address = strtolower(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['email_address'])));
                $date_joined = date("Y-m-d", strtotime($_POST['date_joined']));
                $phone_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['phone_number']));
                $branch_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['branch']));
                $department = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['department']));
                $designation = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['designation']));
                $contact_address = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['contact_address']));

                if(filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
                    if(in_array($gender, array("male", "female"))){// IS VALID GENDER
                        if(get_user_data($account_id, "acc_id") == $account_id){// IS USER ID EXISTING
                            $generate = mysqli_prepare($conn, "UPDATE user_accounts SET branch=?, department=?, designation=?, account_status=?, title=?, firstname=?, surname=?, othername=?, gender=?, email=?, phone=?, contact_address=?, reg_date=? where acc_id=?");
                            mysqli_stmt_bind_param($generate, "iiiiisssssssss", $branch_name, $department, $designation, $account_status, $addressed_as, $first_name, $surname, $other_name, $gender, $email_address, $phone_number, $contact_address, $date_joined, $account_id);
                            if(mysqli_stmt_execute($generate)){
                                echo server_response("success", "<b>Success!</b> Profile successfully updated! " , "100");
                            } else {
                                echo server_response("error", "Something went wrong!", "100");
                            }
                        } else {
                            echo server_response("error", "User id does not exist. Please try again!", "100");
                        }
                    } else {
                        echo server_response("error", "You've selected an invalid <b>Gender</b>!", "100");
                    }
                } else {
                    echo server_response("error", "<b>Email error.</b> Invalid Email Address!", "100");
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