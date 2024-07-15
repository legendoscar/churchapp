<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";

        $sql = mysqli_prepare($conn, "SELECT * FROM track_inactivity WHERE `user_id`=?");
        mysqli_stmt_bind_param($sql, "s", $account_id);
        if(mysqli_stmt_execute($sql)){
            
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result)==1){
                
                $row = mysqli_fetch_array($get_result);

                $last_check = $row['last_check'];

                $maximum_time_id = get_user_data($account_id, 'signout_inactivity_after');
                $maximum_time_in_minutes = get_max_timing_data("", $maximum_time_id, "name");
                $maximum_time_in_second = ($maximum_time_in_minutes * 60);
                
                if(is_inactivity_checker_activated() && (time() - strtotime($last_check) > ($maximum_time_in_second) ) ) { 

                    $_SESSION['__session_locked_email__'] = $email;
                    $_SESSION['__session_locked_userid__'] = $account_id;
                    $_SESSION['__session_locked_fullname__'] = $firstname. " ". $surname;

                    unset($_SESSION['alennsar_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W']);
                    unset($_SESSION['alennsar_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_real_user_id']);
                    unset($_SESSION['alennsar_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_account_type']);
                    unset($_SESSION['alennsar_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_dfghjkds_user_email_sdfghjkljgfdssdfghjk']);
                    $_SESSION['server_error_msg'] = "For Security Reasons. You have been locked out of your account. Please enter your password to continue.";
                    redirect("./login/account-locked", "100");

                } else {
                    
                    if(is_inactivity_checker_activated()) {
                        if(gmdate("H", ($maximum_time_in_second - (time() - strtotime($last_check)))) > 0 ) {
                            // $time = gmdate("H:i:s", ($maximum_time_in_second - (time() - strtotime($last_check))));
                            $time = gmdate("H", ($maximum_time_in_second - (time() - strtotime($last_check)))) . " hr " . gmdate("i", ($maximum_time_in_second - (time() - strtotime($last_check)))) . " minutes " . gmdate("s", ($maximum_time_in_second - (time() - strtotime($last_check)))) . " secs ";
                        } else {
                            // $time = gmdate("i:s", ($maximum_time_in_second - (time() - strtotime($last_check))));
                            $time = gmdate("i", ($maximum_time_in_second - (time() - strtotime($last_check)))) . " minutes " . gmdate("s", ($maximum_time_in_second - (time() - strtotime($last_check)))) . " secs ";
                        }
                        echo server_response("error", "I noticed you're no longer active. Please for security reasons, kindly logout of your account when you're not working.", "7000");
                        echo server_response("warning", "You will be signed out of your account when $time elapse ", "15000");
                    }
                }
            } else {
                echo server_response("error", "Something went wrong!", "100");
            }
        } else {
            echo server_response("error", "Something went wrong!", "100");
        }
    } else {
        echo server_response("error", "Something went wrong!", "100");
        redirect("", "100");
    }
?>