<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";

        require "../../includes/update_activity.php";

    if(is_authorized($account_type, "privilege-settings", "", "") === "allowed"){ 
        if(
            isset($_POST['role_id']) && !empty($_POST['role_id']) && 
            isset($_POST['group_id']) && !empty($_POST['group_id']) 
        ){
            unset($_SESSION['_last_ids_']);
            unset($_SESSION['_last_page_ids_']);
            unset($_SESSION['_has_error_']);

            $_SESSION['_last_ids_'] = "";
            $_SESSION['_has_error_'] = "";
            $_SESSION['_last_page_ids_'] = "";

            $role_id = (SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['role_id']))); 

            $total_group = count($_POST['group_id']);
            

            for($group_loop=0;$group_loop<$total_group; $group_loop++) {

                $group_id = (SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['group_id'][$group_loop]))); 

                $total_page = count($_POST["data_$group_id"]);
                $group_data = $_POST['group_id'][$group_loop];
                $total_per_group = count($_POST["page_name_$group_id"]);

                for($page_loop=0;$page_loop<($total_per_group); $page_loop++) {

                    $page_id = $_POST["page_id_$group_id"][$page_loop];
                    $page_name = $_POST["page_name_$group_id"][$page_loop];

                    $checkbox = (SanitizeTEXT(mysqli_real_escape_string($conn, $_POST["data_$group_id"][$page_loop])));

                    if($checkbox === "allowed") {

                        $save = mysqli_prepare($conn, "INSERT INTO privileges(role_id, page_id, `value`) VALUES(?, ?, 'allowed')");
                        mysqli_stmt_bind_param($save, "ii", $role_id, $page_id);
                        if(mysqli_stmt_execute($save)){
                            $_SESSION['_last_ids_'] .= mysqli_insert_id($conn).",";
                            $_SESSION['_last_page_ids_'] .= $page_id.",";
                        } else {
                            $_SESSION['_has_error_'] = ($_SESSION['_has_error_']+1);
                            $_SESSION['_last_ids_'] .= @mysqli_insert_id($conn).",";
                            $_SESSION['_last_page_ids_'] .= $page_id.",";
                        }
                    }
                }
            }

            if($_SESSION['_has_error_'] == 0){

                $inserted_rows = rtrim($_SESSION['_last_ids_'], ",");
                $inserted_page_ids = rtrim($_SESSION['_last_page_ids_'], ",");

                if(empty($inserted_rows)){
                    $inserted_rows = 0;
                }
                
                $delete = mysqli_prepare($conn, "DELETE FROM privileges WHERE NOT id IN ($inserted_rows) and role_id=?"); 
                mysqli_stmt_bind_param($delete, "i", $role_id);
                if(mysqli_stmt_execute($delete)){
                    echo server_response("success", "<b>Changes successfully saved!</b>...", "100");
                } else {

                    $delete = mysqli_prepare($conn, "DELETE FROM privileges WHERE id IN ($inserted_rows) and page_id IN ($inserted_page_ids) and role_id=?"); 
                    mysqli_stmt_bind_param($delete, "i", $role_id);
                    mysqli_stmt_execute($delete);
                    echo server_response("error", "Something went wrong!", "100");

                }
            } else {

                $delete = mysqli_prepare($conn, "DELETE FROM privileges WHERE id IN ($inserted_rows) and page_id IN ($inserted_page_ids) and role_id=?"); 
                mysqli_stmt_bind_param($delete, "i", $role_id);
                mysqli_stmt_execute($delete);
                echo server_response("error", "Something went wrong!", "100");
            }
        } else {
            echo server_response("error", "All fields are required!", "100");
        }
    } else {
        echo server_response("error", "<b>Access Denied!</b> You're not allowed to modify Privileges. Please if you think this was a mistake, contact your administrator.", "100");
    }
} else {
    echo server_response("error", "Something went wrong!", "100");
}
        
        


?>