<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/additional_function.php";
        require "../../includes/function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "delete-account-title", "", "") == "allowed"){ // ENSURE USER HAS ADMIN PREVILLEGE 
            if( isset($_POST['item_id']) && !empty($_POST['item_id']) ){

                $item_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['item_id']));

                // echo server_response("info", "Deleting record...", "100");

                //Check Brand Existence
                if(get_account_titles_data_by_id($item_id, "name") != "not_found"){//Is Existing

                    //Save New Product
                    $status = "deleted";
                    $update = @mysqli_prepare($conn, "UPDATE account_titles SET `status`=?  where id=?");
                    @mysqli_stmt_bind_param($update, "si", $status, $item_id);
                    if(mysqli_stmt_execute($update)){

                        echo server_response("success", "Item successfully deleted!", "100");?>
                        <script>
                            $("#tbl_row_<?php echo $item_id;?>").remove();
                            $("#total-items").html("<?php echo number_format(get_account_title_list('count'));?>");
                        </script>

                    <?php
                    } else {
                        echo server_response("error", "Something went wrong!", "1000");
                    }
                } else {// Not Existing
                    echo server_response("error", "Item does not exist", "100");
                }
            } else {
                echo server_response("error", "All fields are required!", "100");
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to delete this item. Please if you think this was a mistake, contact your administrator.", "100");
        }

    } else {
        echo server_response("error", "Something went wrong!", "100");
    }
?>