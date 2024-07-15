<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";

        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "create-employee", "", "") === "allowed"){
            if(
                isset($_POST['addressed_as']) && !empty($_POST['addressed_as']) &&
                isset($_POST['firstname']) && !empty($_POST['firstname']) &&
                isset($_POST['surname']) && !empty($_POST['surname']) &&
                isset($_POST['other_name']) &&
                isset($_POST['gender']) && !empty($_POST['gender']) &&
                isset($_POST['email_address']) && !empty($_POST['email_address']) &&
                isset($_POST['date_joined']) && !empty($_POST['date_joined']) &&
                isset($_POST['phone_number']) && !empty($_POST['phone_number']) &&
                isset($_POST['branch']) && !empty($_POST['branch']) &&
                isset($_POST['department']) && !empty($_POST['department']) &&
                isset($_POST['designation']) && !empty($_POST['designation']) &&
                isset($_POST['contact_address']) && !empty($_POST['contact_address']) &&
                isset($_POST['user_has_login']) && !empty($_POST['user_has_login'])
            ){

                $password = "";
                $employee_account_type = "";
                $account_status = "";

                if($_POST['user_has_login'] == "has_login") {

                    if(
                        (!isset($_POST['password']) || empty($_POST['password'])) &&
                        (!isset($_POST['account_type']) || empty($_POST['account_type'])) &&
                        (!isset($_POST['login_status']) || empty($_POST['login_status']))
                    ) {
                        exit(server_response("error", "All fields are required!", "100"));
                    }

                    $password = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['password']));
                    $employee_account_type = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['account_type']));
                    $account_status = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['login_status']));

                    if(strlen($_POST['password']) < 8){ exit(server_response("error", "<b>Password error</b> Please enter alteast 8 or more characters length!", "100")); }
                    $password = password_hash($password, PASSWORD_DEFAULT);

                    if(account_role_name($employee_account_type, "role_name") == "not_found") {// IS VALID ROLE ID
                        exit(server_response("error", "You've selected an invalid account type/role!", "100"));
                    }
                }

                //CUSTOMER INFO
                $addressed_as = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['addressed_as']));
                $first_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['firstname']));
                $surname = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['surname']));
                $other_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['other_name']));
                $gender = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['gender']));
                $email_address = strtolower(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['email_address'])));
                $date_joined = date("Y-m-d", strtotime($_POST['date_joined']));
                $phone_number = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['phone_number']));
                $branch_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['branch']));
                $department = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['department']));
                $designation = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['designation']));
                $contact_address = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['contact_address']));
                $url_id = md5(password_hash(rand(rand(456, 5678), rand(45678, 4567898)), PASSWORD_DEFAULT));

                $employee_id = "STF/".generate_numbers(3, 2);
                $account_id = generate_numbers(4, 3);

                if(preg_match("/^[a-zA-Z ]*$/", $first_name)) {
                    if (preg_match("/^[a-zA-Z ]*$/", $surname)) {

                        if(!empty($other_name) && !preg_match("/^[a-zA-Z ]*$/", $other_name)) {
                            echo server_response("error", "<b>Other Name error.</b> Only letters and white space allowed!", "100");
                        } else {
                            if(filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
                                if(in_array($gender, array("male", "female"))){// IS VALID GENDER
                                    if(get_user_data($account_id, "acc_id") == "not_found"){// IS USER ID EXISTING

                                        if(get_user_data($email_address, "email") == "not_found"){ // IS USER EMAIL EXISTING
                                            $generate = mysqli_prepare($conn, "INSERT INTO user_accounts(branch, department, designation, account_status, title, firstname, surname, othername, gender, email, phone, contact_address, user_pwd, acc_id, acc_type, employee_id, url_id, reg_date) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                                            mysqli_stmt_bind_param($generate, "iiiiisssssssssssss", $branch_name, $department, $designation, $account_status, $addressed_as, $first_name, $surname, $other_name, $gender, $email_address, $phone_number, $contact_address, $password, $account_id, $employee_account_type, $employee_id, $url_id, $date_joined);
                                            if(mysqli_stmt_execute($generate)){

                                                echo server_response("success", "<b>Success!</b> Account has been successfully created! " , "100");

                                                // redirect("./edit-employee?id=$url_id", "200");
                                                if(is_authorized($account_type, "pg-edit-employee", "", "") === "allowed" ) { redirect("./edit-employee?id=$url_id", "200"); }
                                                else { redirect("./employees", "200"); } ?>

                                                <script>
                                                    setTimeout(() => {
                                                        $("#action-btn").attr("disabled","disabled");
                                                        $("#action-btn").html("<span class='fa fa-spin fa-spinner'></span> Redirecting... Please wait");
                                                    }, 0);
                                                </script>

                                                <?php
                                            } else {
                                                echo server_response("error", "Something went wrong!", "100");
                                            }
                                        } else {
                                            echo server_response("error", "It seems <b>$email_address</b> already exist", "100");
                                        }
                                    } else {
                                        echo server_response("error", "User id already exist. Please try again!", "100");
                                    }
                                } else {
                                    echo server_response("error", "You've selected an invalid <b>Gender</b>!", "100");
                                }
                            } else {
                                echo server_response("error", "<b>Email error.</b> Invalid Email Address!", "100");
                            }
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
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to create employee account. Please if you think this was a mistake, contact your administrator.", "100"); 
        }

    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>