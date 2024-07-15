<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST"){

        require "../includes/function.php";

        if(isset($_POST['emailAddress']) && !empty($_POST['emailAddress']) && isset($_POST['password']) && !empty($_POST['password']) ){

            if (!filter_var($_POST['emailAddress'], FILTER_VALIDATE_EMAIL)) {
                server_response("error", "<b>Error!</b> Please enter a valid email address.", "100");
            } else {

                require "../../includes/db-config.php";
                require "../../includes/sanitizer.php";

                $email = strtolower(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['emailAddress'])));
                $password = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['password']));

                //Check for user's email availablility

                $check = @mysqli_prepare(@$conn, "SELECT * FROM user_accounts WHERE email=?");
                @mysqli_stmt_bind_param($check, "s", $email);
                if(@mysqli_stmt_execute($check)){
                    $get_result = mysqli_stmt_get_result($check);
                    if(mysqli_num_rows($get_result) == 1){

                        $row = mysqli_fetch_array($get_result);
                        $d_email = $row['email'];
                        $d_password = $row['user_pwd'];
                        $acc_id = $row['acc_id'];
                        $photo = $row['profile_pic'];
                        $acc_status = $row['account_status'];

                        if(password_verify($password, $d_password)){

                            if($acc_status == "1"){//Active (Can Log In)
                                require "../includes/start_login.php";
                            } else if($acc_status == "2"){// Suspended (Cannot Log In)
                                server_response("error", "<b>Oops!</b> Your account was suspended. Please contact admin.", "100");
                                session_destroy();
                            } else {
                                server_response("error", "<b>Oops!</b> Your request cannot be Granted. Please contact Admin", "100");
                                session_destroy();
                            }
                        } else {
                            server_response("error", "<b>Access Denied</b> Invalid Credentials supplied ", "100");
                            session_destroy();
                        }
                    } else {
                        server_response("error", "<b>Access Denied</b> Invalid Credentials supplied ", "100");
                        session_destroy();
                    }

                } else {
                    server_response("error", "<b>Ouch!</b> An unexpected error occurred. Please check back later. If problem persist, contact Admin.", "100");
                    session_destroy();
                }
            }
        } else {
            server_response("error", "<b>Error!</b> All fields are required.", "100");
            session_destroy();
        }
    } else {
        server_response("error", "<b>ERROR!</b> Something went wrong!", "100");
        session_destroy();
    }
?>