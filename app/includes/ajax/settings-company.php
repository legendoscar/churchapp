<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";

        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "company-settings", "", "") === "allowed"){
            if(
                isset($_POST['company_name']) && !empty($_POST['company_name']) &&
                isset($_POST['company_description']) && isset($_POST['company_address']) && !empty($_POST['company_address']) &&
                isset($_POST['company_phone1']) && isset($_POST['company_phone2'])
            ){

                $company_name = Base64_encode(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['company_name'])));
                $company_description = Base64_encode(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['company_description'])));
                $company_address = Base64_encode(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['company_address'])));
                $company_phone1 = Base64_encode(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['company_phone1'])));
                $company_phone2 = Base64_encode(SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['company_phone2'])));

                $id = "1";

                $save = @mysqli_prepare($conn, "UPDATE softdata SET company_name=?, company_description=?, company_address=?, company_phone1=?, company_phone2=? WHERE id=?");
                @mysqli_stmt_bind_param($save, "sssssi", $company_name, $company_description, $company_address, $company_phone1, $company_phone2, $id);
                if(mysqli_stmt_execute($save)){
                    echo server_response("success", "Changes successfully saved!", "400"); 
                } else {
                    echo server_response("error", "Something went wrong!", "1000");
                }
            } else {
                echo server_response("error", "All fields are required!", "100");
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to edit / modify Comapny Settings. Please if you think this was a mistake, contact your administrator.", "100");
        }
    } else {
        echo server_response("error", "Something went wrong!", "100");
    }
?>