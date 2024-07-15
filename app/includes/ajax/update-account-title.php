<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/additional_function.php";
        require "../../includes/function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "edit-account-title", "", "") == "allowed"){ // ENSURE USER HAS ADMIN PREVILLEGE 
            if(
                isset($_POST['item_name']) && !empty($_POST['item_name']) &&
                isset($_POST['item_id']) && !empty($_POST['item_id']) ){

                $item_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['item_name']));
                $item_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['item_id']));

                //Check Brand Existence
                if(get_account_titles_data_by_id($item_id, "name") != "not_found"){//Is Existing

                    $update = @mysqli_prepare($conn, "UPDATE account_titles SET `name`=?  where id=?");
                    @mysqli_stmt_bind_param($update, "si", $item_name, $item_id);
                    if(mysqli_stmt_execute($update)){

                        echo server_response("success", "Item successfully updated!", "100");?>

                        <script>
                            document.getElementById("editbtn_<?php echo $item_id;?>").innerHTML = "<span class='fa fa-edit'></span>";
                            document.getElementById("editbtn_<?php echo $item_id;?>").setAttribute("class", "btn btn-sm btn-primary");
                            document.getElementById("editbtn_<?php echo $item_id;?>").setAttribute("onclick", "editProductName_<?php echo $item_id;?>()");
                            document.getElementById("new_item_name_<?php echo $item_id;?>").value = "<?php echo $item_name;?>";
                            var new_input = "<span id='_edit_item_<?php echo $item_id;?>'><?php echo $item_name;?></span>";
                            var target_elem = document.getElementById("edit_item_<?php echo $item_id;?>").innerHTML = new_input;
                        </script>
                    <?php
                    } else {
                        echo server_response("error", "Something went wrong!", "100");
                    }

                } else {// Not Existing
                    echo server_response("error", "Item does not exist", "100");
                }
            } else {
                echo server_response("error", "All fields are required!", "100");
            } 
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to update this item. Please if you think this was a mistake, contact your administrator.", "100");
        }

    } else {
        echo server_response("error", "Something went wrong!", "100");
    }


?>