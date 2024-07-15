<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/additional_function.php";
        require "../../includes/function.php";

        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "create-payable-item", "", "") == "allowed"){ // ENSURE USER HAS ADMIN PREVILLEGE 
            if( isset($_POST['item_name']) && !empty($_POST['item_name'])){

                $item_name = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['item_name']));

                $created_by = $account_id;
                $url_id = md5(password_hash(rand(200, 1000), PASSWORD_DEFAULT));

                $create = @mysqli_prepare($conn, "INSERT INTO payable_items(`name`, created_by, url_id) value(?,?,?)");
                @mysqli_stmt_bind_param($create, "sis", $item_name, $created_by, $url_id);
                if(mysqli_stmt_execute($create)){

                    $item_id = mysqli_insert_id($conn);
                    echo server_response("success", "Item successfully created!", "100");?>

                    <script>

                        var table = document.getElementById("payable-item-tbl");
                        var row = table.insertRow(1);

                        row.setAttribute("id", "tbl_row_<?php echo $item_id;?>");

                        var ceil_1 = row.insertCell(0);
                        var ceil_2 = row.insertCell(1);
                        var ceil_3 = row.insertCell(2);

                        ceil_1.innerHTML = "<p id='edit_item_<?php echo $item_id;?>'> <span id='_edit_item_<?php echo $item_id;?>'><?php echo $item_name;?></span></p>";
                        ceil_2.innerHTML = "<?php echo date("Y-m-d h:i:s");?>";
                        ceil_3.innerHTML = "<a href='#!' id='editbtn_<?php echo $item_id;?>' onclick='editProductName_<?php echo $item_id;?>()' data-toggle='modal' data-target='#edit_brand_<?php echo $item_id;?>'  class='btn-sm btn-primary btn'><span class='fa fa-edit'></span> </a>\
                        <a href='#!' onclick='delete_<?php echo $item_id;?>()' class='btn-sm btn-outline-danger btn'><span class='fa fa-trash'></span> </a>";

                        function editProductName_<?php echo $item_id;?>(){

                            // var replace_btn = document.getElementById("editbtn_<?php echo $item_id;?>").innerHTML = "<span class='fa fa-check'></span>";
                            document.getElementById("editbtn_<?php echo $item_id;?>").innerHTML = "<span class='fa fa-save'></span>";
                            document.getElementById("editbtn_<?php echo $item_id;?>").setAttribute("class", "btn btn-sm btn-success");
                            document.getElementById("editbtn_<?php echo $item_id;?>").setAttribute("onclick", "savebtn_<?php echo $item_id;?>()");

                            // alert(document.getElementById("_edit_item_<?php echo $item_id;?>").textContent);
                            
                            var new_input = "<input id='new_item_name_<?php echo $item_id;?>' type='text' class='form-control' value='"+document.getElementById("_edit_item_<?php echo $item_id;?>").textContent+"'> <a href='javascript:(void)' onclick='cancel_<?php echo $item_id;?>()' ><span class='text-danger fa fa-times'></span></a>";
                            var target_elem = document.getElementById("edit_item_<?php echo $item_id;?>").innerHTML = new_input;
                        }

                        function savebtn_<?php echo $item_id;?>(){
                            
                            var dataString = "item_id=" + "<?php echo $item_id;?>" + 
                                "&item_name=" + $("#new_item_name_<?php echo $item_id;?>").val();
                                
                            $.ajax({
                                type: "POST",
                                url: "./includes/ajax/update-payable-item",
                                // RealPath
                                data: dataString,
                                cache: false,
                                beforeSend: function() {
                                    $("button#savebtn_<?php echo $item_id;?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                    $("button#savebtn_<?php echo $item_id;?>").attr("disabled", "disabled");
                                },
                                success: function(d) {
                                    $('div#return_server_msg').fadeIn('slow').html(d);
                                    $("button#savebtn_<?php echo $item_id;?>").fadeIn("slow").html("Log In");
                                    $("button#savebtn_<?php echo $item_id;?>").removeAttr("disabled");
                                },
                                error: function(d) {
                                    toastr.error("Something went wrong!");
                                }
                            });
                            return false;
                        }

                        function cancel_<?php echo $item_id;?>(){
                            document.getElementById("editbtn_<?php echo $item_id;?>").innerHTML = "<span class='fa fa-edit'></span>";
                            document.getElementById("editbtn_<?php echo $item_id;?>").setAttribute("class", "btn btn-sm btn-primary");
                            document.getElementById("editbtn_<?php echo $item_id;?>").setAttribute("onclick", "editProductName_<?php echo $item_id;?>()");

                            var new_input = "<span id='_edit_item_<?php echo $item_id;?>'>" + document.getElementById("new_item_name_<?php echo $item_id;?>").value+ "</span> (<?php echo get_product_list('count', $item_id);?>)";

                            var target_elem = document.getElementById("edit_item_<?php echo $item_id;?>").innerHTML = new_input;
                        }

                        function delete_<?php echo $item_id;?>(){

                            if (confirm("Are you sure you want to delete this item?")) {
                                var dataString = "item_id=" + "<?php echo $item_id;?>";
                                $.ajax({
                                    type: "POST",
                                    url: "./includes/ajax/delete-payable-item",
                                    // RealPath
                                    data: dataString,
                                    cache: false,
                                    beforeSend: function() {
                                        $("button#delbtn_<?php echo $item_id;?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                        $("button#savebtn_<?php echo $item_id;?>").attr("disabled", "disabled");
                                    },
                                    success: function(d) {
                                        $('div#return_server_msg').fadeIn('slow').html(d);
                                        $("button#delbtn_<?php echo $item_id;?>").fadeIn("slow").html("Log In");
                                        $("button#delbtn_<?php echo $item_id;?>").removeAttr("disabled");
                                    },
                                    error: function(d) {
                                        toastr.error("Something went wrong!");
                                    }
                                });
                                return false;
                            }
                        }

                        $("#total-payable-items").html("<?php echo number_format(get_payable_items_list('count'));?>");

                    </script>
                <?php 
                } else {
                    echo server_response("error", "Something went wrong!", "1000");
                }
            } else {
                echo server_response("error", "All fields are required!", "100");
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to create new item. Please if you think this was a mistake, contact your administrator.", "100");
        }
    } else {
        echo server_response("error", "Something went wrong!", "100");
    }
?>