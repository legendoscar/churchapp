<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../login/includes/sanitizer.php";
        require "../../login/includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";

        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "pg-edit-employee", "", "") === "allowed" && is_authorized($account_type, "sb-employee", "", "") === "allowed" ){
            if(
                isset($_POST['first_name']) && !empty($_POST['first_name']) &&
                isset($_POST['surname']) && !empty($_POST['surname']) &&  
                isset($_POST['other_name']) && 
                isset($_POST['gender']) && !empty($_POST['gender']) && 
                isset($_POST['dob']) && !empty($_POST['dob']) && 
                isset($_POST['contact_address']) && !empty($_POST['contact_address']) &&
                isset($_POST['phone_number']) && !empty($_POST['phone_number']) && 
                isset($_POST['account_status']) && !empty($_POST['account_status']) && 
                isset($_POST['account_id']) && !empty($_POST['account_id']) &&
                // isset($_POST['password']) && !empty($_POST['password']) && 
                isset($_POST['account_type']) && !empty($_POST['account_type']) 
            ){

                //CUSTOMER INFO
                $surname = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['surname'])); 
                $first_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['first_name'])); 
                $other_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['other_name'])); 
                $gender = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['gender'])); 
                $dob = date("Y-m-d", strtotime($_POST['dob'])); 
                $contact_address = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['contact_address'])); 
                $phone_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['phone_number'])); 
                $account_status = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['account_status'])); 
            
                $account_id = strtolower(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['account_id']))); 
                
                // $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

                $account_type = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['account_type'])); 

                if (preg_match("/^[a-zA-Z ]*$/", $first_name)) {
                    if (preg_match("/^[a-zA-Z ]*$/", $surname)) {

                        if(!empty($other_name) && !preg_match("/^[a-zA-Z ]*$/", $other_name)) {
                            echo server_response("error", "<b>Other Name error.</b> Only letters and white space allowed!", "100");
                        } else {
                            // if (filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
                                // if(strlen($_POST['password']) >= 8){
                                    if(in_array($gender, array("male", "female"))){// IS VALID GENDER
                                        if(account_role_name($account_type, "role_name") != "not_found") {// IS VALID ROLE ID
                                            if(get_user_data($account_id, "acc_id") != "not_found"){// IS USER ID EXISTING
                                                
                                                // if(get_user_data($email_address, "email") == "not_found"){ // IS USER EMAIL EXISTING
                                                    $update = mysqli_prepare($conn, "UPDATE user_accounts SET firstname=?, surname=?, othername=?, gender=?, dob=?, phone=?, contact_address=?, acc_type=?, account_status=? WHERE acc_id=?");
                                                    mysqli_stmt_bind_param($update, "ssssssssss", $first_name, $surname, $other_name, $gender, $dob, $phone_number, $contact_address, $account_type, $account_status, $account_id);
                                                    if(mysqli_stmt_execute($update)){

                                                        echo server_response("success", "<b>Success!</b> Account successfully updated! " , "100");
                                                        // redirect("./edit-employee-account?id=$url_id", "200");
                                                    } else {
                                                        echo server_response("error", "Something went wrong!", "100");
                                                    }
                                                // } else {
                                                //     echo server_response("error", "It seems <b>$email_address</b> already exist", "100");
                                                // }
                                            } else {
                                                echo server_response("error", "User id does not exist. Please try again!", "100");
                                            }
                                        } else {
                                            echo server_response("error", "You've selected an invalid account type/role!", "100");
                                        }
                                    } else {
                                        echo server_response("error", "You've selected an invalid <b>Gender</b>!", "100");
                                    }
                                // } else {
                                //     echo server_response("error", "<b>Password error</b> Please enter alteast 8 or more characters length!", "100");
                                // }
                            // } else {
                            //     echo server_response("error", "<b>Email error.</b> Invalid Email Address!", "100");
                            // }
                        }
                    } else {
                        echo server_response("error", "<b>Surname Name error.</b> Only letters and white space allowed!", "100");
                    }
                } else {
                    echo server_response("error", "<b>First Name error.</b> Only letters and white space allowed!", "100");
                }
            } else {
                echo server_response("error", "All fields are required!", "100");
            } 
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to update employee account. Please if you think this was a mistake, contact your administrator.", "100"); 
        }

    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>