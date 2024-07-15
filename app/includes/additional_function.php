<?php

function number_to_words(float $number) {
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();

    $words = array(0 => '', 1 => 'one', 2 => 'two',
        3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
        7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve',
        13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
        16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
        19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty',
        70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
    $digits = array('', 'hundred','thousand','hundred', 'crore');

    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? '' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }

    $naira = implode('', array_reverse($str));
    $kobo = ($decimal > 0) ? " point " . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Kobo' : '';
    return ucwords(($naira ? $naira . 'Naira ' : '') . $kobo);
}

// function number_to_words($num) {
//     $split = explode('.',$num);
//     $whole = convertNumber($split[0].".0");
//     $cents = convertNumber($split[1].".0");
//     return $whole." and ".$cents." kobo";
// }



    function get_payable_items($item_id) {
        global $conn;

        $id = 'deleted';
        $sql = mysqli_prepare($conn, 'SELECT * FROM payable_items WHERE not `status`=?');
        mysqli_stmt_bind_param($sql, 's', $id);
        if(!mysqli_stmt_execute($sql)) {
            return 'error';
        } else {
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){?><option <?php if($item_id == $row['id']){ echo'selected'; } ?> value='<?php echo $row['id'];?>'><?php echo $row['name'];?></option><?php }
            } else { }
        }
    }

    function get_payable_items_data($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM payable_items WHERE url_id=? OR id=?");
        mysqli_stmt_bind_param($sql, "ss", $id, $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    function get_payable_items_data_by_id($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM payable_items WHERE id=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) { 
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    function get_department_data_by_id($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM departments WHERE id=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) { 
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    function get_designation_data_by_id($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM designations WHERE id=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    function get_account_titles_data_by_id($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM account_titles WHERE id=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_branch_data_by_id($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM branches WHERE id=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    
    function create_easy_customer_account($customer_id, $customer_name, $customer_phone) {
        global $conn, $system_currency_symbol;

        $sql = mysqli_prepare($conn, "INSERT INTO customers(customer_id, surname, phone) VALUES(?,?,?)");
        mysqli_stmt_bind_param($sql, "sss", $customer_id, $customer_name, $customer_phone);
        if(mysqli_stmt_execute($sql)) { return "success"; } else { return "error"; }

    }



    function validate_customer_name($customer_name) {

        $customer_name_to_array  = explode(" ", $customer_name);
        $total_customer_name = count($customer_name_to_array);

        if($total_customer_name < 2) {
            return "Error! Single / One name not allowed!";
        } else {

            for($i=0; $i<$total_customer_name; $i++) {

                $each_name = $customer_name_to_array[$i];

                $nth = ($i+1);
                if(strlen(trim($each_name)) < 2 ) {
                    if($total_customer_name < 3) {
                        $msg = date("jS", strtotime("0000-00-".($i+1)));
                        return "Error! The length of the $msg name  must have 2 or more characters";
                        exit();
                    }
                }

                if($nth == $total_customer_name) { return "success"; }

            }
        }
    }


    function get_payment_status($status_id) {
        global $conn;

        $id = '';
        $sql = mysqli_prepare($conn, 'SELECT * FROM invoice_status WHERE not id=?');
        mysqli_stmt_bind_param($sql, 's', $id);
        if(!mysqli_stmt_execute($sql)) {
            return 'error';
        } else {
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){?><option <?php if($status_id == $row['id']){ echo'selected'; } ?> value='<?php echo $row['id'];?>'><?php echo $row['name'];?></option><?php }
            } else { }
        }
    }


    // TABLE LIST
    function get_payable_items_list($type) {
        global $conn, $account_type;

        $id = "deleted";
        $sql = mysqli_prepare($conn, "SELECT * FROM payable_items WHERE not `status`=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);

            if($type == "count") {
                return mysqli_num_rows($get_result);
            } else {
                while($row = mysqli_fetch_array($get_result)){?>
                    <tr class="place" id="tbl_row_<?php echo $row['id'];?>">
                        <form method="POST">
                            <td><p id="edit_item_<?php echo $row['id'];?>"><span id="_edit_item_<?php echo $row['id'];?>"><?php echo $row['name'];?></span></span></p></td>
                            <td> <?php echo date("Y-m-d h:i:s A", strtotime($row["last_updated"]));?> </td>

                            <?php if((is_authorized($account_type, "edit-payable-item", "", "") === "allowed" || is_authorized($account_type, "delete-payable-item", "", "") === "allowed") ) {?>
                                <td>
                                    <?php if(is_authorized($account_type, "edit-payable-item", "", "") === "allowed"){?>
                                        <a href="javascript:(void)" id="editbtn_<?php echo $row['id'];?>" onclick="editProductName_<?php echo $row['id'];?>()" data-toggle="modal" data-target="#edit_brand_<?php echo $row['id'];?>"  class="btn-sm btn-primary btn"><span class="fa fa-edit"></span> </a>
                                    <?php } ?>

                                    <?php if(is_authorized($account_type, "delete-payable-item", "", "") === "allowed"){?>
                                        <a href="javascript:(void)" onclick="delete_<?php echo $row['id'];?>()" class="btn-sm btn-outline-danger btn"><span class="fa fa-trash"></span> </a> 
                                    <?php } ?>
                                </td>
                            <?php } ?>

                        </form>
                    </tr>

                    <script>

                        <?php if(is_authorized($account_type, "edit-payable-item", "", "") === "allowed"){?>

                            function editProductName_<?php echo $row['id'];?>(){

                                document.getElementById("editbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-save'></span>";
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-success");
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("onclick", "savebtn_<?php echo $row['id'];?>()");

                                var new_input = "<input id='new_item_name_<?php echo $row['id'];?>' type='text' class='form-control' value='"+document.getElementById("_edit_item_<?php echo $row['id'];?>").textContent+"'> <a href='javascript:(void)' onclick='cancel_<?php echo $row['id'];?>()' ><span class='text-danger fa fa-times'></span></a>";
                                var target_elem = document.getElementById("edit_item_<?php echo $row['id'];?>").innerHTML = new_input;
                            }

                            function savebtn_<?php echo $row['id'];?>(){

                                var dataString = "item_id=" + "<?php echo $row['id'];?>" + 
                                    "&item_name=" + $("#new_item_name_<?php echo $row['id'];?>").val();
                                $.ajax({
                                    type: "POST",
                                    url: "./includes/ajax/update-payable-item",
                                    // RealPath
                                    data: dataString,
                                    cache: false,
                                    beforeSend: function() {
                                        $("button#savebtn_<?php echo $row['id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                        $("button#savebtn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                    },
                                    success: function(d) {
                                        $('div#return_server_msg').fadeIn('slow').html(d);
                                        $("button#savebtn_<?php echo $row['id'];?>").fadeIn("slow").html("Log In");
                                        $("button#savebtn_<?php echo $row['id'];?>").removeAttr("disabled");
                                    },
                                    error: function(d) {
                                        toastr.error("Something went wrong!");
                                    }
                                });
                                return false;
                            }

                            function cancel_<?php echo $row['id'];?>(){
                                document.getElementById("editbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-edit'></span>";
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-primary");
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("onclick", "editProductName_<?php echo $row['id'];?>()");

                                var new_input = "<span id='_edit_item_<?php echo $row['id'];?>'>" + document.getElementById("new_item_name_<?php echo $row['id'];?>").value+ "</span>";
                                var target_elem = document.getElementById("edit_item_<?php echo $row['id'];?>").innerHTML = new_input;
                            }

                        <?php } ?>

                        <?php if(is_authorized($account_type, "delete-payable-item", "", "") === "allowed"){?>

                            function delete_<?php echo $row['id'];?>(){

                                var dataString = "item_id=" + "<?php echo $row['id'];?>";


                                if (confirm("Are you sure you want to delete this item?")) {
                                    $.ajax({
                                        type: "POST",
                                        url: "./includes/ajax/delete-payable-item",
                                        // RealPath
                                        data: dataString,
                                        cache: false,
                                        beforeSend: function() {
                                            $("button#delbtn_<?php echo $row['id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                            $("button#savebtn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                        },
                                        success: function(d) {
                                            $('div#return_server_msg').fadeIn('slow').html(d);
                                            $("button#delbtn_<?php echo $row['id'];?>").fadeIn("slow").html("Log In");
                                            $("button#delbtn_<?php echo $row['id'];?>").removeAttr("disabled");
                                        },
                                        error: function(d) {
                                            toastr.error("Something went wrong!");
                                        }
                                    });
                                    return false;
                                }
                            }
                        <?php } ?>
                    </script>
                <?php
                }
            }
        } else {

        }

    }


    function get_department_list($type) {
        global $conn, $account_type;

        $id = "deleted";
        $sql = mysqli_prepare($conn, "SELECT * FROM departments WHERE not `status`=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);

            if($type == "count") {
                return mysqli_num_rows($get_result);
            } else {
                while($row = mysqli_fetch_array($get_result)){?>
                    <tr class="place" id="tbl_row_<?php echo $row['id'];?>">
                        <form method="POST">
                            <td><p id="edit_item_<?php echo $row['id'];?>"><span id="_edit_item_<?php echo $row['id'];?>"><?php echo $row['name'];?></span></span></p></td>
                            <td> <?php echo date("Y-m-d h:i:s A", strtotime($row["last_updated"]));?> </td>
                            <?php if((is_authorized($account_type, "edit-department", "", "") === "allowed" || is_authorized($account_type, "delete-department", "", "") === "allowed")){?>
                                <td>
                                    <?php if(is_authorized($account_type, "edit-department", "", "") === "allowed"){?>
                                        <a href="javascript:(void)" id="editbtn_<?php echo $row['id'];?>" onclick="editProductName_<?php echo $row['id'];?>()" data-toggle="modal" data-target="#edit_brand_<?php echo $row['id'];?>"  class="btn-sm btn-primary btn"><span class="fa fa-edit"></span> </a>
                                    <?php } ?>

                                    <?php if(is_authorized($account_type, "delete-department", "", "") === "allowed"){?>
                                        <a href="javascript:(void)" onclick="delete_<?php echo $row['id'];?>()" class="btn-sm btn-outline-danger btn"><span class="fa fa-trash"></span> </a> 
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </form>
                    </tr>

                    <script>
                        function editProductName_<?php echo $row['id'];?>(){

                            document.getElementById("editbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-save'></span>";
                            document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-success");
                            document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("onclick", "savebtn_<?php echo $row['id'];?>()");

                            var new_input = "<input id='new_item_name_<?php echo $row['id'];?>' type='text' class='form-control' value='"+document.getElementById("_edit_item_<?php echo $row['id'];?>").textContent+"'> <a href='javascript:(void)' onclick='cancel_<?php echo $row['id'];?>()' ><span class='text-danger fa fa-times'></span></a>";
                            var target_elem = document.getElementById("edit_item_<?php echo $row['id'];?>").innerHTML = new_input;
                        }

                        function savebtn_<?php echo $row['id'];?>(){

                            var dataString = "item_id=" + "<?php echo $row['id'];?>" + 
                                "&item_name=" + $("#new_item_name_<?php echo $row['id'];?>").val();
                            $.ajax({
                                type: "POST",
                                url: "./includes/ajax/update-department",
                                // RealPath
                                data: dataString,
                                cache: false,
                                beforeSend: function() {
                                    $("button#savebtn_<?php echo $row['id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                    $("button#savebtn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                },
                                success: function(d) {
                                    $('div#return_server_msg').fadeIn('slow').html(d);
                                    $("button#savebtn_<?php echo $row['id'];?>").fadeIn("slow").html("Log In");
                                    $("button#savebtn_<?php echo $row['id'];?>").removeAttr("disabled");
                                },
                                error: function(d) {
                                    toastr.error("Something went wrong!");
                                }
                            });
                            return false;
                        }

                        function cancel_<?php echo $row['id'];?>(){
                            document.getElementById("editbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-edit'></span>";
                            document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-primary");
                            document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("onclick", "editProductName_<?php echo $row['id'];?>()");

                            var new_input = "<span id='_edit_item_<?php echo $row['id'];?>'>" + document.getElementById("new_item_name_<?php echo $row['id'];?>").value+ "</span>";
                            var target_elem = document.getElementById("edit_item_<?php echo $row['id'];?>").innerHTML = new_input;
                        }

                        function delete_<?php echo $row['id'];?>(){

                            var dataString = "item_id=" + "<?php echo $row['id'];?>";

                            if (confirm("Are you sure you want to delete this item?")) {
                                $.ajax({
                                    type: "POST",
                                    url: "./includes/ajax/delete-department",
                                    // RealPath
                                    data: dataString,
                                    cache: false,
                                    beforeSend: function() {
                                        $("button#delbtn_<?php echo $row['id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                        $("button#savebtn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                    },
                                    success: function(d) {
                                        $('div#return_server_msg').fadeIn('slow').html(d);
                                        $("button#delbtn_<?php echo $row['id'];?>").fadeIn("slow").html("Log In");
                                        $("button#delbtn_<?php echo $row['id'];?>").removeAttr("disabled");
                                    },
                                    error: function(d) {
                                        toastr.error("Something went wrong!");
                                    }
                                });
                                return false;
                            }
                        }
                        
                    </script>
                <?php
                }
            }
        } else {

        }

    }

    function get_designation_list($type) {
        global $conn, $account_type;

        $id = "deleted";
        $sql = mysqli_prepare($conn, "SELECT * FROM designations WHERE not `status`=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);

            if($type == "count") {
                return mysqli_num_rows($get_result);
            } else {
                while($row = mysqli_fetch_array($get_result)){?>
                    <tr class="place" id="tbl_row_<?php echo $row['id'];?>">
                        <form method="POST">
                            <td><p id="edit_item_<?php echo $row['id'];?>"><span id="_edit_item_<?php echo $row['id'];?>"><?php echo $row['name'];?></span></span></p></td>
                            <td> <?php echo date("Y-m-d h:i:s A", strtotime($row["last_updated"]));?> </td>
                            <?php if((is_authorized($account_type, "edit-designation", "", "") === "allowed" || is_authorized($account_type, "delete-designation", "", "") === "allowed")){?>
                                <td>
                                    <?php if(is_authorized($account_type, "edit-designation", "", "") === "allowed"){?>
                                        <a href="javascript:(void)" id="editbtn_<?php echo $row['id'];?>" onclick="editProductName_<?php echo $row['id'];?>()" data-toggle="modal" data-target="#edit_brand_<?php echo $row['id'];?>"  class="btn-sm btn-primary btn"><span class="fa fa-edit"></span> </a>
                                    <?php } ?>

                                    <?php if(is_authorized($account_type, "delete-designation", "", "") === "allowed"){?>
                                        <a href="javascript:(void)" onclick="delete_<?php echo $row['id'];?>()" class="btn-sm btn-outline-danger btn"><span class="fa fa-trash"></span> </a> 
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </form>
                    </tr>

                    <script>
                    
                        <?php if(is_authorized($account_type, "edit-designation", "", "") === "allowed"){?>
                            function editProductName_<?php echo $row['id'];?>(){

                                document.getElementById("editbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-save'></span>";
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-success");
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("onclick", "savebtn_<?php echo $row['id'];?>()");

                                var new_input = "<input id='new_item_name_<?php echo $row['id'];?>' type='text' class='form-control' value='"+document.getElementById("_edit_item_<?php echo $row['id'];?>").textContent+"'> <a href='javascript:(void)' onclick='cancel_<?php echo $row['id'];?>()' ><span class='text-danger fa fa-times'></span></a>";
                                var target_elem = document.getElementById("edit_item_<?php echo $row['id'];?>").innerHTML = new_input;
                            }

                            function savebtn_<?php echo $row['id'];?>(){

                                var dataString = "item_id=" + "<?php echo $row['id'];?>" + 
                                    "&item_name=" + $("#new_item_name_<?php echo $row['id'];?>").val();
                                $.ajax({
                                    type: "POST",
                                    url: "./includes/ajax/update-designation",
                                    // RealPath
                                    data: dataString,
                                    cache: false,
                                    beforeSend: function() {
                                        $("button#savebtn_<?php echo $row['id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                        $("button#savebtn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                    },
                                    success: function(d) {
                                        $('div#return_server_msg').fadeIn('slow').html(d);
                                        $("button#savebtn_<?php echo $row['id'];?>").fadeIn("slow").html("Log In");
                                        $("button#savebtn_<?php echo $row['id'];?>").removeAttr("disabled");
                                    },
                                    error: function(d) {
                                        toastr.error("Something went wrong!");
                                    }
                                });
                                return false;
                            }

                            function cancel_<?php echo $row['id'];?>(){
                                document.getElementById("editbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-edit'></span>";
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-primary");
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("onclick", "editProductName_<?php echo $row['id'];?>()");

                                var new_input = "<span id='_edit_item_<?php echo $row['id'];?>'>" + document.getElementById("new_item_name_<?php echo $row['id'];?>").value+ "</span>";
                                var target_elem = document.getElementById("edit_item_<?php echo $row['id'];?>").innerHTML = new_input;
                            }
                        <?php } ?>

                        <?php if(is_authorized($account_type, "delete-designation", "", "") === "allowed"){?>
                            function delete_<?php echo $row['id'];?>(){

                                var dataString = "item_id=" + "<?php echo $row['id'];?>";

                                if (confirm("Are you sure you want to delete this item?")) {
                                    $.ajax({
                                        type: "POST",
                                        url: "./includes/ajax/delete-designation",
                                        // RealPath
                                        data: dataString,
                                        cache: false,
                                        beforeSend: function() {
                                            $("button#delbtn_<?php echo $row['id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                            $("button#savebtn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                        },
                                        success: function(d) {
                                            $('div#return_server_msg').fadeIn('slow').html(d);
                                            $("button#delbtn_<?php echo $row['id'];?>").fadeIn("slow").html("Log In");
                                            $("button#delbtn_<?php echo $row['id'];?>").removeAttr("disabled");
                                        },
                                        error: function(d) {
                                            toastr.error("Something went wrong!");
                                        }
                                    });
                                    return false;
                                }
                            }
                        <?php } ?>
                        
                    </script>
                <?php
                }
            }
        } else {

        }

    }

    function get_account_title_list($type) {
        global $conn, $account_type;

        $id = "deleted";
        $sql = mysqli_prepare($conn, "SELECT * FROM account_titles WHERE not `status`=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);

            if($type == "count") {
                return mysqli_num_rows($get_result);
            } else {
                while($row = mysqli_fetch_array($get_result)){?>
                    <tr class="place" id="tbl_row_<?php echo $row['id'];?>">
                        <form method="POST">
                            <td><p id="edit_item_<?php echo $row['id'];?>"><span id="_edit_item_<?php echo $row['id'];?>"><?php echo $row['name'];?></span></span></p></td>
                            <td> <?php echo date("Y-m-d h:i:s A", strtotime($row["last_updated"]));?> </td>
                            <?php if((is_authorized($account_type, "edit-account-title", "", "") === "allowed" || is_authorized($account_type, "delete-account-title", "", "") === "allowed")){?>
                                <td>
                                    <?php if(is_authorized($account_type, "edit-account-title", "", "") === "allowed" ){?>
                                        <a href="javascript:(void)" id="editbtn_<?php echo $row['id'];?>" onclick="editProductName_<?php echo $row['id'];?>()" data-toggle="modal" data-target="#edit_brand_<?php echo $row['id'];?>"  class="btn-sm btn-primary btn"><span class="fa fa-edit"></span> </a>
                                    <?php } ?>

                                    <?php if(is_authorized($account_type, "delete-account-title", "", "") === "allowed" ){?>
                                        <a href="javascript:(void)" onclick="delete_<?php echo $row['id'];?>()" class="btn-sm btn-outline-danger btn"><span class="fa fa-trash"></span> </a> 
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </form>
                    </tr>

                    <script>
                        <?php if(is_authorized($account_type, "edit-account-title", "", "") === "allowed" ){?>
                            function editProductName_<?php echo $row['id'];?>(){

                                document.getElementById("editbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-save'></span>";
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-success");
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("onclick", "savebtn_<?php echo $row['id'];?>()");

                                var new_input = "<input id='new_item_name_<?php echo $row['id'];?>' type='text' class='form-control' value='"+document.getElementById("_edit_item_<?php echo $row['id'];?>").textContent+"'> <a href='javascript:(void)' onclick='cancel_<?php echo $row['id'];?>()' ><span class='text-danger fa fa-times'></span></a>";
                                var target_elem = document.getElementById("edit_item_<?php echo $row['id'];?>").innerHTML = new_input;
                            }

                            function savebtn_<?php echo $row['id'];?>(){

                                var dataString = "item_id=" + "<?php echo $row['id'];?>" + 
                                    "&item_name=" + $("#new_item_name_<?php echo $row['id'];?>").val();
                                $.ajax({
                                    type: "POST",
                                    url: "./includes/ajax/update-account-title",
                                    // RealPath
                                    data: dataString,
                                    cache: false,
                                    beforeSend: function() {
                                        $("button#savebtn_<?php echo $row['id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                        $("button#savebtn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                    },
                                    success: function(d) {
                                        $('div#return_server_msg').fadeIn('slow').html(d);
                                        $("button#savebtn_<?php echo $row['id'];?>").fadeIn("slow").html("Log In");
                                        $("button#savebtn_<?php echo $row['id'];?>").removeAttr("disabled");
                                    },
                                    error: function(d) {
                                        toastr.error("Something went wrong!");
                                    }
                                });
                                return false;
                            }

                            function cancel_<?php echo $row['id'];?>(){
                                document.getElementById("editbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-edit'></span>";
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-primary");
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("onclick", "editProductName_<?php echo $row['id'];?>()");

                                var new_input = "<span id='_edit_item_<?php echo $row['id'];?>'>" + document.getElementById("new_item_name_<?php echo $row['id'];?>").value+ "</span>";
                                var target_elem = document.getElementById("edit_item_<?php echo $row['id'];?>").innerHTML = new_input;
                            }
                        <?php } ?>

                        <?php if(is_authorized($account_type, "delete-account-title", "", "") === "allowed" ){?>

                            function delete_<?php echo $row['id'];?>(){

                                var dataString = "item_id=" + "<?php echo $row['id'];?>";

                                if (confirm("Are you sure you want to delete this item?")) {
                                    $.ajax({
                                        type: "POST",
                                        url: "./includes/ajax/delete-account-title",
                                        // RealPath
                                        data: dataString,
                                        cache: false,
                                        beforeSend: function() {
                                            $("button#delbtn_<?php echo $row['id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                            $("button#savebtn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                        },
                                        success: function(d) {
                                            $('div#return_server_msg').fadeIn('slow').html(d);
                                            $("button#delbtn_<?php echo $row['id'];?>").fadeIn("slow").html("Log In");
                                            $("button#delbtn_<?php echo $row['id'];?>").removeAttr("disabled");
                                        },
                                        error: function(d) {
                                            toastr.error("Something went wrong!");
                                        }
                                    });
                                    return false;
                                }
                            }
                        <?php } ?>
                    </script>
                <?php
                }
            }
        } else {

        }

    }

    function get_branch_list($type) {
        global $conn, $account_type;

        $id = "deleted";
        $sql = mysqli_prepare($conn, "SELECT * FROM branches WHERE not `status`=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);

            if($type == "count") {
                return mysqli_num_rows($get_result);
            } else {
                while($row = mysqli_fetch_array($get_result)){?>
                    <tr class="place" id="tbl_row_<?php echo $row['id'];?>">
                        <form method="POST">
                            <td><p id="edit_item_<?php echo $row['id'];?>"><span id="_edit_item_<?php echo $row['id'];?>"><?php echo $row['name'];?></span></span></p></td>
                            <td> <?php echo date("Y-m-d h:i:s A", strtotime($row["last_updated"]));?> </td>
                            <?php if((is_authorized($account_type, "delete-branches", "", "") === "allowed" || is_authorized($account_type, "edit-branches", "", "") === "allowed")){?>
                                <td>
                                    <?php if(is_authorized($account_type, "edit-branches", "", "") === "allowed"){?>
                                        <a href="javascript:(void)" id="editbtn_<?php echo $row['id'];?>" onclick="editProductName_<?php echo $row['id'];?>()" data-toggle="modal" data-target="#edit_brand_<?php echo $row['id'];?>"  class="btn-sm btn-primary btn"><span class="fa fa-edit"></span> </a>
                                    <?php } ?>

                                    <?php if(is_authorized($account_type, "delete-branches", "", "") === "allowed"){?>
                                        <a href="javascript:(void)" onclick="delete_<?php echo $row['id'];?>()" class="btn-sm btn-outline-danger btn"><span class="fa fa-trash"></span> </a> 
                                    <?php } ?>
                                </td>
                            <?php } ?>
                        </form>
                    </tr>

                    <script>
                        function editProductName_<?php echo $row['id'];?>(){

                            document.getElementById("editbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-save'></span>";
                            document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-success");
                            document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("onclick", "savebtn_<?php echo $row['id'];?>()");

                            var new_input = "<input id='new_item_name_<?php echo $row['id'];?>' type='text' class='form-control' value='"+document.getElementById("_edit_item_<?php echo $row['id'];?>").textContent+"'> <a href='javascript:(void)' onclick='cancel_<?php echo $row['id'];?>()' ><span class='text-danger fa fa-times'></span></a>";
                            var target_elem = document.getElementById("edit_item_<?php echo $row['id'];?>").innerHTML = new_input;
                        }

                        function savebtn_<?php echo $row['id'];?>(){

                            var dataString = "item_id=" + "<?php echo $row['id'];?>" + 
                                "&item_name=" + $("#new_item_name_<?php echo $row['id'];?>").val();
                            $.ajax({
                                type: "POST",
                                url: "./includes/ajax/update-branch",
                                // RealPath
                                data: dataString,
                                cache: false,
                                beforeSend: function() {
                                    $("button#savebtn_<?php echo $row['id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                    $("button#savebtn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                },
                                success: function(d) {
                                    $('div#return_server_msg').fadeIn('slow').html(d);
                                    $("button#savebtn_<?php echo $row['id'];?>").fadeIn("slow").html("Log In");
                                    $("button#savebtn_<?php echo $row['id'];?>").removeAttr("disabled");
                                },
                                error: function(d) {
                                    toastr.error("Something went wrong!");
                                }
                            });
                            return false;
                        }

                        function cancel_<?php echo $row['id'];?>(){
                            document.getElementById("editbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-edit'></span>";
                            document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-primary");
                            document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("onclick", "editProductName_<?php echo $row['id'];?>()");

                            var new_input = "<span id='_edit_item_<?php echo $row['id'];?>'>" + document.getElementById("new_item_name_<?php echo $row['id'];?>").value+ "</span>";
                            var target_elem = document.getElementById("edit_item_<?php echo $row['id'];?>").innerHTML = new_input;
                        }

                        function delete_<?php echo $row['id'];?>(){

                            var dataString = "item_id=" + "<?php echo $row['id'];?>";

                            if (confirm("Are you sure you want to delete this item?")) {
                                $.ajax({
                                    type: "POST",
                                    url: "./includes/ajax/delete-branch",
                                    // RealPath
                                    data: dataString,
                                    cache: false,
                                    beforeSend: function() {
                                        $("button#delbtn_<?php echo $row['id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                        $("button#savebtn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                    },
                                    success: function(d) {
                                        $('div#return_server_msg').fadeIn('slow').html(d);
                                        $("button#delbtn_<?php echo $row['id'];?>").fadeIn("slow").html("Log In");
                                        $("button#delbtn_<?php echo $row['id'];?>").removeAttr("disabled");
                                    },
                                    error: function(d) {
                                        toastr.error("Something went wrong!");
                                    }
                                });
                                return false;
                            }
                        }
                        
                    </script>
                <?php
                }
            }
        } else {

        }

    }


    // FORM SELECT LIST

    function get_account_title_form_list($id) {
        global $conn;

        $status = 'deleted';
        $sql = mysqli_prepare($conn, 'SELECT * FROM account_titles WHERE not `status`=?');
        mysqli_stmt_bind_param($sql, 's', $status);
        if(!mysqli_stmt_execute($sql)) {
            return 'error';
        } else {
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){?><option <?php if($id == $row['id']){ echo'selected'; } ?> value='<?php echo $row['id'];?>'><?php echo $row['name'];?></option><?php }
            } else { }
        }
    }

    function get_branch_form_list($id) {
        global $conn;

        $status = 'deleted';
        $sql = mysqli_prepare($conn, 'SELECT * FROM branches WHERE not `status`=?');
        mysqli_stmt_bind_param($sql, 's', $status);
        if(!mysqli_stmt_execute($sql)) {
            return 'error';
        } else {
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){?><option <?php if($id == $row['id']){ echo'selected'; } ?> value='<?php echo $row['id'];?>'><?php echo $row['name'];?></option><?php }
            } else { }
        }
    }

    function get_department_form_list($id) {
        global $conn;

        $status = 'deleted';
        $sql = mysqli_prepare($conn, 'SELECT * FROM departments WHERE not `status`=?');
        mysqli_stmt_bind_param($sql, 's', $status);
        if(!mysqli_stmt_execute($sql)) {
            return 'error';
        } else {
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){?><option <?php if($id == $row['id']){ echo'selected'; } ?> value='<?php echo $row['id'];?>'><?php echo $row['name'];?></option><?php }
            } else { }
        }
    }

    function get_designation_form_list($id) {
        global $conn;

        $status = 'deleted';
        $sql = mysqli_prepare($conn, 'SELECT * FROM designations WHERE not `status`=?');
        mysqli_stmt_bind_param($sql, 's', $status);
        if(!mysqli_stmt_execute($sql)) {
            return 'error';
        } else {
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){?><option <?php if($id == $row['id']){ echo'selected'; } ?> value='<?php echo $row['id'];?>'><?php echo $row['name'];?></option><?php }
            } else { }
        }
    }


    function get_invoice_transaction_by_day_range($product_id, $data){
        global $conn;
    
        if(isset($_POST['from']) && !empty($_POST['from']) && isset($_POST['to']) && !empty($_POST['to']) ){
            $from_date = date("Y-m-d", strtotime($_POST['from']));
            $to_date = date("Y-m-d", strtotime($_POST['to']));

        } else if(isset($_GET['from']) && !empty($_GET['from']) && isset($_GET['to']) && !empty($_GET['to'])){

            $from_date = date("Y-m-d", strtotime($_GET['from']));
            $to_date = date("Y-m-d", strtotime($_GET['to']));

        } else {
            $from_date = date("Y-m-d");
            $to_date = date("Y-m-d");
        }

        $sql = mysqli_prepare($conn, "SELECT sum($data) FROM invoice_trn_details where product_id=? and date_created BETWEEN '$from_date' and '$to_date' ");
        mysqli_stmt_bind_param($sql, "i", $product_id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        $row = mysqli_fetch_array($get_result);
        return $row["sum($data)"];
    }


    function get_payment_reports(){
        global $conn;

        if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['from']) && !empty($_POST['from'])){
            $to_date = date("Y-m-d", strtotime(@$_POST['to']));
            $from_date = date("Y-m-d", strtotime($_POST['from']));
        } else if($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['from']) && !empty($_GET['from'])){
            $to_date = date("Y-m-d", strtotime(@$_GET['to']));
            $from_date = date("Y-m-d", strtotime($_GET['from']));
        } else {
            $to_date = date("Y-m-d");
            $from_date = date("Y-m-d");
        }

        if(isset($_GET['item']) && !empty($_GET['item']) && get_payable_items_data_by_id(mysqli_real_escape_string($conn, $_GET['item']), "id") == $_GET['item']){

            $item_id  = mysqli_real_escape_string($conn, $_GET['item']);
            $id = "";

            $products_for_stock = mysqli_prepare($conn, "SELECT * FROM invoice_trn_details WHERE `product_id`=? AND invoice_number IN(select invoice_number from invoice where date_created between '$from_date' and '$to_date') ORDER BY id ASC");
            mysqli_stmt_bind_param($products_for_stock, "s", $item_id);
            if(mysqli_stmt_execute($products_for_stock)){
                $get_result2 = mysqli_stmt_get_result($products_for_stock);
                if(mysqli_num_rows($get_result2) > 0){
                    $n = "";
                    while($row2 = mysqli_fetch_array($get_result2)){

                        $n++;
                        $grand_total[] = $row2['amount'];?>
                        <tr>
                            <td><?php echo $n;?></td>
                            <td colspan=""> <b> <?php echo $row2['invoice_number'];?> - <?php echo date("dS F, Y", strtotime(get_invoice_data_by_number($row2['invoice_number'], "date_created")));?> <?php // echo get_payable_items_data_by_id($row2['product_id'], "name");?> </b></td>
                            <td align="right" colspan=""> <b> <?php echo custom_money_format($row2['amount']);?> </b></td>
                        </tr>
                        <?php
                    } ?>

                    <tr>
                        <td></td>
                        <td> <b> TOTAL</b> </td>
                        <td align="right"> <b> ₦<?php echo custom_money_format(array_sum($grand_total));?> </b> </td>
                    </tr>
                    <?php
                } else {?>
                    <tr>
                        <td colspan="3">
                            <center><b>Reports not available for <?php echo $from_date;?></b></center>
                        </td>
                    </tr>
                <?php
                }
            }

        } else {

            //Fetch all products from stock
            $id = "";
            $products_for_stock = mysqli_prepare($conn, "SELECT * FROM payable_items WHERE not `id`=? ORDER BY id ASC");
            mysqli_stmt_bind_param($products_for_stock, "s", $id);
            if(mysqli_stmt_execute($products_for_stock)){
                $get_result2 = mysqli_stmt_get_result($products_for_stock);
                if(mysqli_num_rows($get_result2) > 0){
                    $n = "";
                    while($row2 = mysqli_fetch_array($get_result2)){

                        if($row2['status'] != "deleted" || get_invoice_transaction_by_day_range($row2['id'], "amount")) {
                            $n++;
                            $grand_total[] = get_invoice_transaction_by_day_range($row2['id'], "amount");?>
                            <tr>
                                <td><?php echo $n;?></td>
                                <td colspan="">
                                    <a href="?from=<?php echo $from_date;?>&to=<?php echo $to_date;?>&item=<?php echo $row2['id'];?>" style='text-decoration:none;color:#000;'> <b> <?php echo $row2['name'];?> </b></a>
                                </td>
                                <td align="right" colspan=""> <b> <?php echo custom_money_format(get_invoice_transaction_by_day_range($row2['id'], "amount"));?> </b></td>
                            </tr>
                            <?php
                        }
                    } ?>

                    <tr>
                        <td></td>
                        <td> <b> TOTAL</b> </td>
                        <td align="right"> <b> ₦<?php echo custom_money_format(array_sum($grand_total));?> </b> </td>
                    </tr>
                    <?php
                } else {?>
                    <tr>
                        <td colspan="3">
                            <center><b>Reports not available for <?php echo $from_date;?></b></center>
                        </td>
                    </tr>
                <?php
                }
            }
        }
    }

    function get_transaction_reports(){
        global $conn;

        if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['from']) && !empty($_POST['from'])){
            $to_date = date("Y-m-d", strtotime(@$_POST['to']));
            $from_date = date("Y-m-d", strtotime($_POST['from']));
        } else if($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['from']) && !empty($_GET['from'])){
            $to_date = date("Y-m-d", strtotime(@$_GET['to']));
            $from_date = date("Y-m-d", strtotime($_GET['from']));
        } else {
            $to_date = date("Y-m-d");
            $from_date = date("Y-m-d");
        }

        //Fetch all products from stock
        $products_for_stock = mysqli_prepare($conn, "SELECT * FROM payment_method WHERE not `id`=? ORDER BY id ASC");
        mysqli_stmt_bind_param($products_for_stock, "s", $from_date);
        if(mysqli_stmt_execute($products_for_stock)){
            $get_result2 = mysqli_stmt_get_result($products_for_stock);
            if(mysqli_num_rows($get_result2) > 0){
                $n = "";
                while($row2 = mysqli_fetch_array($get_result2)){

                    $n++;
                    $methods = $row2['id'];
                    $grand_total[] = total_split_payments($row2['id']);?>
                    <tr>
                        <td><?php echo $n;?></td>
                        <td colspan=""> <b><?php echo strtoupper($row2['methods']);?> </b> </td>
                        <td align="right" colspan=""> <b> <?php echo custom_money_format(total_split_payments($row2['id']));?> </b></td>
                    </tr>

                    <?php

                    $pid = "deleted";
                    $sql = mysqli_prepare($conn, "SELECT * FROM bank_accounts WHERE not `status`=? and method_id=?");
                    mysqli_stmt_bind_param($sql, "si", $pid, $methods);
                    if(mysqli_stmt_execute($sql)){
                        $get_result = mysqli_stmt_get_result($sql);
                        while($row = mysqli_fetch_array($get_result)){

                            $method_id = $row['method_id'];
                            $id = $row['id'];

                            if(!in_array($methods, array("1","4","5"))) { ?>

                            <tr>
                                <td></td>
                                <td colspan="">
                                    <u><b>
                                    <?php echo $row['account_name'];?>
                                    <?php
                                        if($methods == "3"){?>
                                        - <?php echo $row['bank_name'];?> - <?php echo $row['account_number'];?> 
                                        <?php
                                        }
                                    ?>
                                    </b></u>
                                </td>

                                <td align="right">
                                    <!-- <span class="float-left">₦</span> -->
                                    <u>
                                        <b>
                                            <?php echo custom_money_format(get_split_sales_by_date_method($row['id'], "amount"));?>
                                        </b>
                                    </u>
                                </td>
                            </tr>

                        <?php
                            } else {
                            }
                            
                            // <!-- GET CUSTOMERS WHO PAID TO THIS ACCOUNT -->
                            echo customers_by_payment_method("list", "$method_id", $id);
                        }
                    } else {
                        echo "Error";
                    }
                } ?>
                <tr>
                    <td></td>
                    <td> <b> TOTAL</b> </td>
                    <td align="right"> <b> ₦<?php echo custom_money_format(array_sum($grand_total));?> </b> </td>
                </tr>

            </table>

                <?php
            } else {?>
                <tr>
                    <td colspan="3">
                        <center><b>Reports not available for <?php echo $from_date;?></b></center>
                    </td>
                </tr>
            <?php
            }
        }
    }


    function get_total_receipt_value($data){
        global $conn;

        if(isset($_POST['from']) && !empty($_POST['from']) && isset($_POST['to']) && !empty($_POST['to']) ){
            $from_date = date("Y-m-d", strtotime($_POST['from']));
            $to_date = date("Y-m-d", strtotime($_POST['to']));

        } else if(isset($_GET['from']) && !empty($_GET['from']) && isset($_GET['to']) && !empty($_GET['to'])){

            $from_date = date("Y-m-d", strtotime($_GET['from']));
            $to_date = date("Y-m-d", strtotime($_GET['to']));

        } else {
            $from_date = date("Y-m-d");
            $to_date = date("Y-m-d");
        }

        $id = "";
        $sql = mysqli_prepare($conn, "SELECT sum($data) FROM invoice_trn_details where not id=? and date_created BETWEEN '$from_date' and '$to_date' ");
        mysqli_stmt_bind_param($sql, "s", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        $row = mysqli_fetch_array($get_result);
        return $row["sum($data)"];
    }


    function get_payroll_reports($payslip_month) {
        global $conn;

        $payslip_month = mysqli_real_escape_string($conn, $payslip_month);

        $sql = mysqli_prepare($conn, "SELECT * FROM payslips where payslip_month=?");
        mysqli_stmt_bind_param($sql, "s", $payslip_month);
        if(!mysqli_stmt_execute($sql)) { echo "Something went wrong!"; }


        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0) {

            $total_deductions = array();
            $total_earnings = array();
            $total_netpay = array();
            $total_gross = array();
            $total_basic = array();

            while($row = mysqli_fetch_array($get_result)) {
                $earnings = (get_total_payslip_item_transactions($row['id'], $row['payslip_number'], "earnings"));
                $deduction = get_total_payslip_item_transactions($row['id'], $row['payslip_number'], "deduction"); 

                // GRAND TOTALS
                $total_deductions[] = $deduction;
                $total_earnings[] = ($earnings-$row['monthly_basic_salary']);
                $total_netpay[] = ($earnings-$deduction);
                $total_gross[] = ($earnings);
                $total_basic[] = ($row['monthly_basic_salary']);

                ?>

                    <tr>
                        <td> <?php echo date("F, Y", strtotime($row['payslip_month']));?> </td>
                        <td> <?php echo strtoupper(get_user_data($row['employee_id'], "firstname").' '.get_user_data($row['employee_id'], "surname"));?> </td>
                        <td> <?php echo get_designation_data_by_id($row['employee_designation'], "name");?> / <?php echo get_department_data_by_id($row['employee_department'], "name");?></td>
                        <td> <?php echo get_branch_data_by_id($row['employee_branch'], "name");?></td>
                        <td> <?php echo "₦".custom_money_format($row['monthly_basic_salary']);?> </td>
                        <td> <?php echo "₦".custom_money_format($earnings-$row['monthly_basic_salary']);?> </td>
                        <td> <?php echo "₦".custom_money_format($deduction);?> </td>
                        <td> <?php echo "₦".custom_money_format(($earnings-$deduction));?> </td>
                        <td> <?php echo "₦".custom_money_format(($earnings));?> </td>
                        <td> <?php if($row['payslip_status'] == 1) { echo $row['payment_date']; } else { echo "-"; }?> </td>
                        <td> <?php if($row['payslip_status'] == 1) { echo "PAID"; } else { echo "UNPAID"; };?> </td>
                        <td> <?php echo $row['payslip_number'];?> </td>
                    </tr>

                <?php

            } ?>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td> <?php echo "₦".custom_money_format(array_sum($total_basic));?> </td>
                    <td> <?php echo "₦".custom_money_format(array_sum($total_earnings));?> </td>
                    <td> <?php echo "₦".custom_money_format(array_sum($total_deductions));?> </td>
                    <td> <?php echo "₦".custom_money_format(array_sum($total_netpay));?> </td>
                    <td> <?php echo "₦".custom_money_format(array_sum($total_gross));?> </td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

            <?php
        }

    }


    function get_payroll_reports2($payslip_month, $payment_bank) {
        global $conn;

        $payslip_month = mysqli_real_escape_string($conn, $payslip_month);
        $payment_bank = mysqli_real_escape_string($conn, $payment_bank);

        $sql = mysqli_prepare($conn, "SELECT * FROM payslips where payslip_month=? and id IN (SELECT payslip_id FROM employee_payslip_banks where employee_payslip_banks.payment_bank=?)");
        mysqli_stmt_bind_param($sql, "si", $payslip_month, $payment_bank);
        if(!mysqli_stmt_execute($sql)) { echo "Something went wrong!"; }

        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0) {

            $total_deductions = array();
            $total_earnings = array();
            $total_netpay = array();
            $total_gross = array();
            $total_basic = array();

            $n="";
            while($row = mysqli_fetch_array($get_result)) { $n++;
                $earnings = (get_total_payslip_item_transactions($row['id'], $row['payslip_number'], "earnings"));
                $deduction = get_total_payslip_item_transactions($row['id'], $row['payslip_number'], "deduction"); 

                // GRAND TOTALS
                $total_deductions[] = $deduction;
                $total_earnings[] = ($earnings-$row['monthly_basic_salary']);
                $total_netpay[] = ($earnings-$deduction);
                $total_gross[] = ($earnings);
                $total_basic[] = ($row['monthly_basic_salary']);

                ?>

                    <tr>
                        <td> <?php echo $n;?>. </td>
                        <td> <?php echo get_payslip_employee_bank_details_by_payslip_id($row['id'], $row['employee_id'], "account_name");?> </td>
                        <td> <?php echo get_payslip_employee_bank_details_by_payslip_id($row['id'], $row['employee_id'], "account_number");?> </td>
                        <td> <?php echo "₦".custom_money_format(($earnings-$deduction));?> </td>

                        <!-- <td> <?php // echo date("F, Y", strtotime($row['payslip_month']));?> </td> -->
                        <!-- <td> <?php // echo strtoupper(get_user_data($row['employee_id'], "firstname").' '.get_user_data($row['employee_id'], "surname"));?> </td> -->
                        <!-- <td> <?php // echo get_designation_data_by_id($row['employee_designation'], "name");?> / <?php // echo get_department_data_by_id($row['employee_department'], "name");?></td> -->
                        <!-- <td> <?php // echo get_branch_data_by_id($row['employee_branch'], "name");?></td> -->
                        <!-- <td> <?php // echo "₦".custom_money_format($row['monthly_basic_salary']);?> </td> -->
                        <!-- <td> <?php // echo "₦".custom_money_format($earnings-$row['monthly_basic_salary']);?> </td> -->
                        <!-- <td> <?php // echo "₦".custom_money_format($deduction);?> </td> -->
                        <!-- <td> <?php // echo "₦".custom_money_format(($earnings-$deduction));?> </td> -->
                        <!-- <td> <?php // echo "₦".custom_money_format(($earnings));?> </td> -->
                        <!-- <td> <?php // if($row['payslip_status'] == 1) { echo $row['payment_date']; } else { echo "-"; }?> </td> -->
                        <!-- <td> <?php // if($row['payslip_status'] == 1) { echo "PAID"; } else { echo "UNPAID"; };?> </td> -->
                        <!-- <td> <?php // echo $row['payslip_number'];?> </td> -->

                    </tr>

                <?php

            } ?>

                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td> <?php echo "₦".custom_money_format(array_sum($total_netpay));?> </td>

                    <!-- <td> <?php // echo "₦".custom_money_format(array_sum($total_basic));?> </td>
                    <td> <?php // echo "₦".custom_money_format(array_sum($total_earnings));?> </td>
                    <td> <?php // echo "₦".custom_money_format(array_sum($total_deductions));?> </td>
                    <td> <?php // echo "₦".custom_money_format(array_sum($total_netpay));?> </td>
                    <td> <?php // echo "₦".custom_money_format(array_sum($total_gross));?> </td> -->
                    <!-- <td></td>
                    <td></td>
                    <td></td> -->
                </tr>

            <?php
        }

    }

    function get_payment_banks($target_id) {
        global $conn;

        $id = 'deleted';
        $sql = mysqli_prepare($conn, "SELECT * FROM payment_banks WHERE not id=?");
        mysqli_stmt_bind_param($sql, 's', $id);

        if(!mysqli_stmt_execute($sql)) {
            return 'error';
        } else {
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){?><option <?php if($target_id == $row['id']){ echo'selected'; } ?> value='<?php echo $row['id'];?>'><?php echo $row['bank_name'];?></option><?php }
            } else { }
        }

    }

    function get_payment_bank_by_id($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM payment_banks WHERE id=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_payment_bank_by_url_id($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM payment_banks WHERE url_id=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_expenses($type) {
        global $conn, $account_type;

        if(isset($_POST['from']) && !empty($_POST['from']) && isset($_POST['to']) && !empty($_POST['to']) ){

            $from_date = date("Y-m-d", strtotime($_POST['from']));
            $to_date = date("Y-m-d", strtotime($_POST['to']));

        } else if(isset($_GET['from']) && !empty($_GET['from']) && isset($_GET['to']) && !empty($_GET['to'])){

            $from_date = date("Y-m-d", strtotime($_GET['from']));
            $to_date = date("Y-m-d", strtotime($_GET['to']));

        } else {
            $from_date = date("Y-m-d");
            $to_date = date("Y-m-d");
        }

        if($type == "sum") {
            $id = "deleted";
            $sql = mysqli_prepare($conn, "SELECT sum(amount) FROM expenses WHERE not id=? and expense_date BETWEEN '$from_date' AND '$to_date'");
            mysqli_stmt_bind_param($sql, "s", $id);
            if(mysqli_stmt_execute($sql)){
                $get_result = mysqli_stmt_get_result($sql);
                $row = mysqli_fetch_array($get_result);
                return $row["sum(amount)"];
            } else {
                return "error";
            }

        } else if($type == "recent") {

            $id = "deleted";
            $sql = mysqli_prepare($conn, "SELECT * FROM expenses WHERE not id=? and expense_date BETWEEN '$from_date' AND '$to_date' LIMIT 5");
            mysqli_stmt_bind_param($sql, "s", $id);
            if(mysqli_stmt_execute($sql)){
                $get_result = mysqli_stmt_get_result($sql);

                while($row = mysqli_fetch_array($get_result)){?>
                    <p><i class="fa fa-dot-circle-o text-purple me-2"></i><?php echo get_expenses_item_data_by_id($row['item_id'], "name");?> <span class="float-end">₦<?php echo custom_money_format($row['amount']);?></span></p>
                <?php
                }
            }
        } else {
            $id = "deleted";
            $sql = mysqli_prepare($conn, "SELECT * FROM expenses WHERE not id=? and date_created BETWEEN '$from_date' AND '$to_date'");
            mysqli_stmt_bind_param($sql, "s", $id);
            if(mysqli_stmt_execute($sql)){
                $get_result = mysqli_stmt_get_result($sql);

                if($type == "count") {
                    return mysqli_num_rows($get_result);
                } else {
                    while($row = mysqli_fetch_array($get_result)){?>
                        <tr id="target_<?php echo $row['url_id'];?>">
                            <td><?php echo $row['expense_date'];?></td>
                            <td><?php echo get_expenses_item_data_by_id($row['item_id'], "name");?></td>
                            <td><u data-bs-target="#view-expenses" data-bs-toggle="modal" onclick="view_expenses('<?php echo $row['url_id'];?>')" style="cursor:pointer;"><b>₦<?php echo custom_money_format($row['amount']);?> <span class="fa fa-external-link"></span></b></u></td>
                            <td><?php echo $row['date_created'];?></td>
                            <td>
                                <?php if(is_authorized($account_type, "edit-expenses", "", "") === "allowed") {?>
                                    <a class="btn btn-sm btn-primary" href="./edit-expenses?id=<?php echo $row['url_id'];?>"><span class="fa fa-pencil"></span> </a>
                                <?php } ?>

                                <?php if(is_authorized($account_type, "delete-expenses", "", "") === "allowed") {?>
                                    <button id="btn_<?php echo $row['url_id'];?>" onclick="deleteExpenses('target_<?php echo $row['url_id'];?>', '<?php echo $row['url_id'];?>')" class="btn btn-sm btn-danger"><span class="fa fa-trash-o"></span> </button>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php
                    }
                }
            } else {
                echo "Something went wrong!";
            }
        }

    }


    function get_expenses_reports() {
        global $conn, $account_type;

        if(isset($_POST['from']) && !empty($_POST['from']) && isset($_POST['to']) && !empty($_POST['to']) ){
            $from_date = date("Y-m-d", strtotime($_POST['from']));
            $to_date = date("Y-m-d", strtotime($_POST['to']));

        } else if(isset($_GET['from']) && !empty($_GET['from']) && isset($_GET['to']) && !empty($_GET['to'])){

            $from_date = date("Y-m-d", strtotime($_GET['from']));
            $to_date = date("Y-m-d", strtotime($_GET['to']));

        } else {
            $from_date = date("Y-m-d");
            $to_date = date("Y-m-d");
        }

        $id = "deleted";
        $sql = mysqli_prepare($conn, "SELECT * FROM expenses WHERE not id=? and expense_date BETWEEN '$from_date' AND '$to_date' ORDER BY expense_date DESC, date_created DESC");
        mysqli_stmt_bind_param($sql, "s", $id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);

            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){

                    $grand_total[] = $row["amount"];?>

                    <tr id="target_<?php echo $row['url_id'];?>">
                        <td><?php echo $row['expense_date'];?></td>
                        <td><?php echo get_expenses_item_data_by_id($row['item_id'], "name");?></td>
                        <td align="right"><b>₦<?php echo custom_money_format($row['amount']);?></b></td>
                    </tr>
                <?php
                } ?>
                    <tr>
                        <td></td>
                        <td> <b> TOTAL</b> </td>
                        <td align="right"> <b> ₦<?php echo custom_money_format(array_sum($grand_total));?> </b> </td>
                    </tr>
                <?php
            } else {?>
                <tr>
                    <td colspan="3">
                        <center><b>Reports not available (date: <?php echo $from_date;?> to <?php echo $to_date;?></b></center>
                    </td>
                </tr>
            <?php
            }
        } else {
            echo "Something went wrong!";
        }
    }


    function get_expenses_list($expenses_id){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM expenses WHERE url_id=?");
        mysqli_stmt_bind_param($sql, "s", $expenses_id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result)>0){?>
                <div style='border:2px solid #eee;padding:12px;'><?php while($row = mysqli_fetch_array($get_result)){ $total_amount[] = $row['amount'];?>  <a class='pull-right btn-sm btn-danger' href='./edit-expenses?id=<?php echo $row['url_id'];?>'><span class='fa fa-edit'></span> </a>  <p style='margin-bottom:-10px;margin-top:-10px;'><b>Date</b>: <?php echo $row['expense_date'];?><br/><b>Item</b>: <?php echo get_expenses_item_data_by_id($row['item_id'], "name");?><br/><b>Narration</b>: <?php echo ($row['description']);?> <br/><b>Amount:</b> ₦<?php echo custom_money_format($row['amount']);?>  <br/><b>Cashier Name:</b> <?php echo get_user_data($row['user_id'], "firstname");?> <?php echo get_user_data($row['user_id'], "surname");?> <?php echo get_user_data($row['user_id'], "othername");?> <br/><b>Given to / Collected by:</b> <?php echo ($row['given_to']);?> </br/><b>Date created:</b> <?php echo date('Y-m-d', strtotime($row['date_created']));?></p><hr/> <?php } ?> <b>TOTAL</b>: ₦<?php echo custom_money_format(array_sum($total_amount));?> </div> <?php
            } else {
                echo 'Something went wrong!';
            }
        }
    }

    function get_expenses_item_data_by_urlid($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM expenses_item WHERE url_id=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_expenses_item_data_by_id($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM expenses_item WHERE id=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_expenses_item_form_list($item_id) {
        global $conn;

        $id = 'deleted';
        $sql = mysqli_prepare($conn, 'SELECT * FROM expenses_item WHERE not `status`=?');
        mysqli_stmt_bind_param($sql, 's', $id);
        if(!mysqli_stmt_execute($sql)) {
            return 'error';
        } else {
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){?><option <?php if($item_id == $row['id']){ echo 'selected'; } ?> value='<?php echo $row['id'];?>'><?php echo $row['name'];?></option><?php }
            } else { }
        }
    }


    function get_expenses_data_by_urlid($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM expenses WHERE url_id=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_expenses_data_by_id($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM expenses WHERE id=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_expenses_items_list($type) {
        global $conn, $account_type;

        $id = "deleted";
        $sql = mysqli_prepare($conn, "SELECT * FROM expenses_item WHERE not `status`=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);

            if($type == "count") {
                return mysqli_num_rows($get_result);
            } else {
                while($row = mysqli_fetch_array($get_result)){?>

                    <tr class="place" id="tbl_row_<?php echo $row['id'];?>">
                        <form method="POST">
                            <td><p id="edit_item_<?php echo $row['id'];?>"><span id="_edit_item_<?php echo $row['id'];?>"><?php echo $row['name'];?></span></span></p></td>
                            <td> <?php echo date("Y-m-d h:i:s A", strtotime($row["last_updated"]));?> </td>

                            <?php if((is_authorized($account_type, "edit-expenses-item", "", "") === "allowed" || is_authorized($account_type, "delete-expenses-item", "", "") === "allowed")){?>
                                <td>

                                    <?php if(is_authorized($account_type, "edit-expenses-item", "", "") === "allowed" ){?>
                                        <a href="javascript:(void)" id="editbtn_<?php echo $row['id'];?>" onclick="editProductName_<?php echo $row['id'];?>()" data-toggle="modal" data-target="#edit_brand_<?php echo $row['id'];?>"  class="btn-sm btn-primary btn"><span class="fa fa-edit"></span> </a>
                                    <?php } ?>

                                    <?php if(is_authorized($account_type, "delete-expenses-item", "", "") === "allowed"){?>
                                        <a href="javascript:(void)" onclick="delete_<?php echo $row['id'];?>()" class="btn-sm btn-outline-danger btn"><span class="fa fa-trash"></span> </a> 
                                    <?php } ?>

                                </td>
                            <?php } ?>

                        </form>
                    </tr>

                    <script>
                    
                        <?php if(is_authorized($account_type, "edit-expenses-item", "", "") === "allowed") {?>
                            function editProductName_<?php echo $row['id'];?>(){

                                document.getElementById("editbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-save'></span>";
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-success");
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("onclick", "savebtn_<?php echo $row['id'];?>()");

                                var new_input = "<input id='new_item_name_<?php echo $row['id'];?>' type='text' class='form-control' value='"+document.getElementById("_edit_item_<?php echo $row['id'];?>").textContent+"'> <a href='javascript:(void)' onclick='cancel_<?php echo $row['id'];?>()' ><span class='text-danger fa fa-times'></span></a>";
                                var target_elem = document.getElementById("edit_item_<?php echo $row['id'];?>").innerHTML = new_input;
                            }

                            function savebtn_<?php echo $row['id'];?>(){

                                var dataString = "item_id=" + "<?php echo $row['id'];?>" + 
                                    "&item_name=" + $("#new_item_name_<?php echo $row['id'];?>").val();
                                $.ajax({
                                    type: "POST",
                                    url: "./includes/ajax/update-expenses-item",
                                    // RealPath
                                    data: dataString,
                                    cache: false,
                                    beforeSend: function() {
                                        $("button#savebtn_<?php echo $row['id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                        $("button#savebtn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                    },
                                    success: function(d) {
                                        $('div#return_server_msg').fadeIn('slow').html(d);
                                        $("button#savebtn_<?php echo $row['id'];?>").fadeIn("slow").html("Log In");
                                        $("button#savebtn_<?php echo $row['id'];?>").removeAttr("disabled");
                                    },
                                    error: function(d) {
                                        toastr.error("Something went wrong!");
                                    }
                                });
                                return false;
                            }

                            function cancel_<?php echo $row['id'];?>(){
                                document.getElementById("editbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-edit'></span>";
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-primary");
                                document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("onclick", "editProductName_<?php echo $row['id'];?>()");

                                var new_input = "<span id='_edit_item_<?php echo $row['id'];?>'>" + document.getElementById("new_item_name_<?php echo $row['id'];?>").value+ "</span>";
                                var target_elem = document.getElementById("edit_item_<?php echo $row['id'];?>").innerHTML = new_input;
                            }
                        <?php } ?>

                        <?php if(is_authorized($account_type, "delete-expenses-item", "", "") === "allowed") {?>
                            function delete_<?php echo $row['id'];?>(){

                                var dataString = "item_id=" + "<?php echo $row['id'];?>";

                                if (confirm("Are you sure you want to delete this item?")) {
                                    $.ajax({
                                        type: "POST",
                                        url: "./includes/ajax/delete-expenses-item",
                                        // RealPath
                                        data: dataString,
                                        cache: false,
                                        beforeSend: function() {
                                            $("button#delbtn_<?php echo $row['id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                            $("button#savebtn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                        },
                                        success: function(d) {
                                            $('div#return_server_msg').fadeIn('slow').html(d);
                                            $("button#delbtn_<?php echo $row['id'];?>").fadeIn("slow").html("Log In");
                                            $("button#delbtn_<?php echo $row['id'];?>").removeAttr("disabled");
                                        },
                                        error: function(d) {
                                            toastr.error("Something went wrong!");
                                        }
                                    });
                                    return false;
                                }
                            }
                        <?php } ?>

                    </script>

                <?php
                }
            }
        } else {

        }
    }


    function get_payslip_items($item_id, $group_name, $is_default) {
        global $conn;

        $id = 'deleted';
        if($is_default == "default") { //  GET DEFAULTS TOO
            $sql = mysqli_prepare($conn, "SELECT * FROM payslip_items WHERE not `status`=? AND group_name='$group_name'");
            mysqli_stmt_bind_param($sql, 's', $id);
        } else { // DO NOT INCLUDE DEFAULT
            $sql = mysqli_prepare($conn, "SELECT * FROM payslip_items WHERE not `status`=? and not is_default='default' AND group_name='$group_name'");
            mysqli_stmt_bind_param($sql, 's', $id);
        }

        if(!mysqli_stmt_execute($sql)) {
            return 'error';
        } else {
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){?><option <?php if($item_id == $row['id']){ echo'selected'; } ?> value='<?php echo $row['id'];?>'><?php echo $row['name'];?></option><?php }
            } else { }
        }
    }

    function get_payslip_items_data_by_urlid($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM payslip_items WHERE url_id=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    function get_payslip_items_data_by_id($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM payslip_items WHERE id=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_payslips_list($limit){
        global $conn, $user_account_type;

        $id = "";

        if(isset($_POST['from']) && isset($_POST['to'])) {

            // $from_date = date("Y-m", strtotime($_POST['from']));
            // $to_date = date("Y-m", strtotime($_POST['to']));
            $from_date = date("Y-m-d", strtotime($_POST['from']));
            $to_date = date("Y-m-d", strtotime($_POST['to']));

            $sql = mysqli_prepare($conn, "SELECT * FROM payslips WHERE not id=? and (date_created_only BETWEEN '$from_date' and '$to_date') ORDER BY id DESC");

        } else {
            if($limit > 0){
                $sql = mysqli_prepare($conn, "SELECT * FROM payslips WHERE not id=? ORDER BY id DESC LIMIT $limit");
            } else {
                // $from_date = date("Y-m");
                // $to_date = date("Y-m");
                $from_date = date("Y-m-d");
                $to_date = date("Y-m-d");
                $sql = mysqli_prepare($conn, "SELECT * FROM payslips WHERE not id=? and (date_created_only BETWEEN '$from_date' and '$to_date') ORDER BY id DESC");
            }
        }

        mysqli_stmt_bind_param($sql, "s", $id);

        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0){
                while($row = mysqli_fetch_array($get_result)){
                    $payslip_number = $row['payslip_number'];?>

                    <tr id="payslip_id_<?php echo $row['id'];?>">
                        <form id="item_list_row_<?php echo $row['id'];?>">
                            <td>
                                <a target='_blank' href='./view-payslip?id=<?php echo $row['url_id'];?>'>
                                    <b>
                                        <u>
                                            <?php echo get_account_titles_data_by_id(get_user_data($row['employee_id'], "title"), "name");?>
                                            <?php echo get_user_data($row['employee_id'], "firstname");?> <?php echo get_user_data($row['employee_id'], "surname");?>
                                            <br/><?php echo $payslip_number;?> 
                                            <span class="fa fa-external-link"></span>
                                        </u>
                                    </b>
                                </a>
                                <span class="badge badge-<?php echo get_payslip_status_by_id($row['payslip_status'], "color");?>"><?php echo get_payslip_status_by_id($row['payslip_status'], "name");?></span>
                            </td>
                            <td> ₦<?php echo custom_money_format($row['total_amount']);?> </td>
                            <td> <?php echo date('F, Y', strtotime($row['payslip_month']));?></i> </td>
                            <td><?php echo date('Y-m-d', strtotime($row['date_created']));?> <i><?php if(date('Y-m-d', strtotime($row['date_created'])) == date('Y-m-d', strtotime('yesterday')) ) { echo '(Yesterday)';} else if(date('Y-m-d', strtotime($row['date_created'])) == date('Y-m-d', strtotime('today')) ) { echo '(Today)';} ?></i> </td>
                            <td>

                                <div>

                                    <?php if(is_authorized($user_account_type, "print-payslip", "", "") === "allowed") {?>
                                        <a target="_blank" href='./view-payslip?id=<?php echo $row['url_id'];?>' class='ml-2 btn btn-sm btn-secondary' title="Print Invoice" data-toggle="tooltip">
                                            <i class='fa fa-print text-white'></i>
                                        </a>
                                    <?php } ?>

                                    <?php if(is_authorized($user_account_type, "edit-payslip", "", "") === "allowed") {?>
                                        <a href='./edit-payslip?id=<?php echo $row['url_id'];?>' class='btn btn-warning btn-sm ml-2'>
                                            <i class='fa fa-edit'></i>
                                        </a>
                                    <?php } ?>

                                    <?php if(is_authorized($user_account_type, "delete-payslip", "", "") === "allowed") {?>
                                        <input type="hidden" name="payslip_number" value="<?php echo $row['payslip_number'];?>">
                                        <input type="hidden" name="payslip_id" value="<?php echo $row['id'];?>">
                                        <button id="action_btn_<?php echo $row['id'];?>" type="button" href="javascript:(void)"  title="Delete Invoice" data-toggle="tooltip" onclick="deleteRow_<?php echo $row['id'];?>()" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </td>
                        </form>
                    </tr>

                    <?php if(is_authorized($user_account_type, "delete-payslip", "", "") === "allowed") {?>
                        <script>
                            function deleteRow_<?php echo $row['id'];?>(){
                                if(confirm("Are you sure you want to delete item?")){
                                    var dataString = $("#item_list_row_<?php echo $row['id'];?>").serialize();
                                    $.ajax({
                                        type: "POST",
                                        url: "./includes/ajax/delete-payslip",
                                        data: dataString,
                                        cache: false,
                                        beforeSend: function() {
                                            $("#action_btn_<?php echo $row['id'];?>").html("<span class='fa fa-spin fa-spinner'></span>");
                                            $("#action_btn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                        },
                                        success: function(d) {
                                            $('div#return_server_msg').fadeIn('slow').html(d);
                                            $("#action_btn_<?php echo $row['id'];?>").html("<span class='fa fa-trash-o'></span>");
                                            $("#action_btn_<?php echo $row['id'];?>").removeAttr("disabled");
                                        },
                                        error: function(d) {
                                            $("#action_btn_<?php echo $row['id'];?>").html("<span class='fa fa-trash-o'></span>");
                                            $("#action_btn_<?php echo $row['id'];?>").removeAttr("disabled");
                                            toastr.error("Something went wrong!");
                                        }
                                    });
                                    return false;
                                }
                            }
                        </script>
                    <?php } ?>

                <?php
                }
            } else {?>

            <?php
            }
        } else {
            echo "Something went wrong!";
        }
    }


    function get_payslip_status($target_id) {
        global $conn;

        $id = 'deleted';
        $sql = mysqli_prepare($conn, "SELECT * FROM payslip_status WHERE not id=?");
        mysqli_stmt_bind_param($sql, 's', $id);

        if(!mysqli_stmt_execute($sql)) {
            return 'error';
        } else {
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){?><option <?php if($target_id == $row['id']){ echo'selected'; } ?> value='<?php echo $row['id'];?>'><?php echo $row['name'];?></option><?php }
            } else { }
        }
    }

    function get_payslip_mode_of_payment($target_id) {
        global $conn;

        $id = 'deleted';
        $sql = mysqli_prepare($conn, "SELECT * FROM payslip_mode_of_payment WHERE not id=?");
        mysqli_stmt_bind_param($sql, 's', $id);

        if(!mysqli_stmt_execute($sql)) {
            return 'error';
        } else {
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){?><option <?php if($target_id == $row['id']){ echo'selected'; } ?> value='<?php echo $row['id'];?>'><?php echo $row['name'];?></option><?php }
            } else { }
        }
    }


    function get_payslip_status_by_id($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM payslip_status WHERE id=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_payslip_data_by_employee_and_date($employee_id, $payslip_month, $data) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM payslips WHERE employee_id=? and payslip_month=?");
        mysqli_stmt_bind_param($sql, "is", $employee_id, $payslip_month);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_payslip_data_by_urlid($target, $data) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM payslips WHERE url_id=?");
        mysqli_stmt_bind_param($sql, "s", $target);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    function get_payslip_data_by_id($target, $data) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM payslips WHERE id=?");
        mysqli_stmt_bind_param($sql, "s", $target);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    function get_payslip_data_by_number($target, $data) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM payslips WHERE payslip_number=?");
        mysqli_stmt_bind_param($sql, "s", $target);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_payslip_transactions($payslip_id, $payslip_number, $data_type, $column) {
        global $conn;

        if($column == "") { $sql = mysqli_prepare($conn, "SELECT * FROM payslip_transaction_items WHERE payslip_id=? and payslip_number=?"); }
        else { $sql = mysqli_prepare($conn, "SELECT * FROM payslip_transaction_items WHERE payslip_id=? and payslip_number=? and item_group='$column'"); }

        mysqli_stmt_bind_param($sql, "is", $payslip_id, $payslip_number);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);

            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){

                    $payslip_transaction_item_id = $row['id'];

                    if($data_type == "total") {
                        $grand_total[] = $row["item_amount"];
                    } else {?>

                        <?php
                            if($column != "earnings") { $class_column = $column."s"; } 
                            else { $class_column = $column; }
                        ?>

                        <tr id="row_id_<?php echo $row['id'];?>">
                            <td>
                                <select required disabled class="select form-control" id="earnings_id_<?php echo $row['id'];?>" name="earnings_id[]">
                                    <option value='' label='loading...'><?php echo get_payslip_items_data_by_id($row['item_id'], "name");?></option>
                                </select>
                            </td>
                            <td>
                                <!-- <div class="input-group">
                                    <span class="input-group-text"><b>₦</b></span>
                                    <input disabled onchange='StartAccounting()' id="default_earnings_value" readonly type="number" name="earnings_value[]" value='<?php echo $row["item_amount"];?>' min="0.01" step="0.01" class="earnings_value form-control">
                                </div> -->

                                    <div class="input-group mb-3" style0="width:350px;">
                                        <span class="input-group-text"><b>₦</b></span>
                                        <input disabled value="<?php echo $row["item_amount"];?>" name="earnings_value" readonly required min="0" type="number" step='0.01'  onchange="StartAccounting_<?php echo $row['id'];?>()" onkeydown="StartAccounting_<?php echo $row['id'];?>()" onkeyup="StartAccounting_<?php echo $row['id'];?>()" class="<?php echo $class_column;?>_value form-control" id="<?php echo $class_column;?>_value_<?php echo $row['id'];?>">
                                        <div style="z-" class="btn-group">
                                            <button data-bs-toggle="modal" data-bs-target="#edit_payslip_item" class="btn-xs btn-primary text-white" style="padding:6px;" onclick="get_payslip_item_data('<?php echo $payslip_id;?>','<?php echo $payslip_number;?>', '<?php echo $payslip_transaction_item_id;?>');" id="item_row_id_<?php echo $row['id'];?>" type="button">
                                                <span data-toggle='tooltip' id="edit_btn_text_<?php echo $row['id'];?>" class='fa fa-pencil text-primsary'></span>
                                            </button>
                                        </div>
                                    </div>
                            </td>
                        </tr>

                    <?php
                    }
                }

                if($data_type == "total") {
                    return array_sum($grand_total);
                }
            } else {?> No <?php echo $column; }
        } else {
            echo "Something went wrong!";
        }
    }


    function get_total_payslip_item_transactions($payslip_id, $payslip_number, $column) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT sum(item_amount) FROM payslip_transaction_items WHERE payslip_id=? and payslip_number=? and item_group='$column'");
        mysqli_stmt_bind_param($sql, "is", $payslip_id, $payslip_number);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result)> 0) {
                $row = mysqli_fetch_array($get_result);
                return $row["sum(item_amount)"];
            } else {
                return 0;
            }
        } else {
            echo "Something went wrong!";
        }
    }


    function get_payslip_transactions_view($payslip_id, $payslip_number, $data_type, $column) {
        global $conn;

        if($column == "") { $sql = mysqli_prepare($conn, "SELECT * FROM payslip_transaction_items WHERE payslip_id=? and payslip_number=?"); }
        else { $sql = mysqli_prepare($conn, "SELECT * FROM payslip_transaction_items WHERE payslip_id=? and payslip_number=? and item_group='$column'"); }

        mysqli_stmt_bind_param($sql, "is", $payslip_id, $payslip_number);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);

            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){
                    if($data_type == "total") {
                        $grand_total[] = $row["item_amount"];
                    } else {?>
                        <tr>
                            <td>
                                <strong><?php echo get_payslip_items_data_by_id($row['item_id'], "name");?></strong> 
                                <span class="float-end">₦<?php echo custom_money_format($row["item_amount"]);?></span>
                            </td>
                        </tr>
                    <?php
                    }
                }

                if($data_type == "total") {
                    return array_sum($grand_total);
                }
            } else {?> No <?php echo $column; }
        } else {
            echo "Something went wrong!";
        }
    }


    function get_payslip_transaction_item_data_by_id($target, $data) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM payslip_transaction_items WHERE id=?");
        mysqli_stmt_bind_param($sql, "i", $target);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_payslip_transaction_item_data_by_item_id($item_id, $payslip_id, $payslip_number, $data) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM payslip_transaction_items WHERE item_id=? and payslip_id=? and payslip_number=?");
        mysqli_stmt_bind_param($sql, "iis", $item_id, $payslip_id, $payslip_number);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_payslip_items_list($type, $group) {
        global $conn, $account_type;

        $id = "deleted";

        if(!empty($group)) {
            $sql = mysqli_prepare($conn, "SELECT * FROM payslip_items WHERE not `status`=? and group_name=?");
            mysqli_stmt_bind_param($sql, "ss", $id, $group);
            if(!mysqli_stmt_execute($sql)){
                exit("Something went wrong");
            }
        } else {
            $sql = mysqli_prepare($conn, "SELECT * FROM payslip_items WHERE not `status`=?");
            mysqli_stmt_bind_param($sql, "s", $id);
            if(!mysqli_stmt_execute($sql)){
                exit("Something went wrong");
            }
        }

        $get_result = mysqli_stmt_get_result($sql);
        if($type == "count") {
            return mysqli_num_rows($get_result);
        } else {
            while($row = mysqli_fetch_array($get_result)){?>
                <tr class="place" id="tbl_row_<?php echo $row['id'];?>">
                    <form method="POST">
                        <td><p id="edit_item_<?php echo $row['id'];?>"><span id="_edit_item_<?php echo $row['id'];?>"><?php echo $row['name'];?> <?php if($row['is_default'] == "default") {?> - <span -style="background-color:#eee;" class="text-secondary"> default</span> <?php } ?> </span></span></p></td>
                        <td> <?php echo date("Y-m-d h:i:s A", strtotime($row["last_updated"]));?> </td>
                        <?php if(is_authorized($account_type, "edit-payslip-item", "", "") === "allowed" || is_authorized($account_type, "delete-payslip-item", "", "") === "allowed") {?>
                            <td>

                                <?php if($row['is_default'] != "default") {?>

                                    <?php if(is_authorized($account_type, "edit-payslip-item", "", "") === "allowed") {?>
                                        <a href="javascript:(void)" id="editbtn_<?php echo $row['id'];?>" onclick="editProductName_<?php echo $row['id'];?>()" data-toggle="modal" data-target="#edit_brand_<?php echo $row['id'];?>"  class="btn-sm btn-primary btn"><span class="fa fa-edit"></span> </a>
                                    <?php } ?>

                                    <?php if(is_authorized($account_type, "delete-payslip-item", "", "") === "allowed") {?>
                                        <a href="javascript:(void)" onclick="delete_<?php echo $row['id'];?>()" class="btn-sm btn-outline-danger btn"><span class="fa fa-trash"></span> </a> 
                                    <?php } ?>

                                <?php } ?>


                            </td>
                        <?php } ?>

                    </form>
                </tr>

                <script>
                    <?php if(is_authorized($account_type, "edit-payslip-item", "", "") === "allowed") {?>
                        function editProductName_<?php echo $row['id'];?>(){

                            document.getElementById("editbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-save'></span>";
                            document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-success");
                            document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("onclick", "savebtn_<?php echo $row['id'];?>()");

                            var new_input = "<input id='new_item_name_<?php echo $row['id'];?>' type='text' class='form-control' value='"+document.getElementById("_edit_item_<?php echo $row['id'];?>").textContent+"'> <a href='javascript:(void)' onclick='cancel_<?php echo $row['id'];?>()' ><span class='text-danger fa fa-times'></span></a>";
                            var target_elem = document.getElementById("edit_item_<?php echo $row['id'];?>").innerHTML = new_input;
                        }

                        function savebtn_<?php echo $row['id'];?>(){

                            var dataString = "item_id=" + "<?php echo $row['id'];?>" + 
                                "&item_name=" + $("#new_item_name_<?php echo $row['id'];?>").val();
                            $.ajax({
                                type: "POST",
                                url: "./includes/ajax/update-payslip-item",
                                // RealPath
                                data: dataString,
                                cache: false,
                                beforeSend: function() {
                                    $("button#savebtn_<?php echo $row['id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                    $("button#savebtn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                },
                                success: function(d) {
                                    $('div#return_server_msg').fadeIn('slow').html(d);
                                    $("button#savebtn_<?php echo $row['id'];?>").fadeIn("slow").html("Log In");
                                    $("button#savebtn_<?php echo $row['id'];?>").removeAttr("disabled");
                                },
                                error: function(d) {
                                    toastr.error("Something went wrong!");
                                }
                            });
                            return false;
                        }

                        function cancel_<?php echo $row['id'];?>(){
                            document.getElementById("editbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-edit'></span>";
                            document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-primary");
                            document.getElementById("editbtn_<?php echo $row['id'];?>").setAttribute("onclick", "editProductName_<?php echo $row['id'];?>()");

                            var new_input = "<span id='_edit_item_<?php echo $row['id'];?>'>" + document.getElementById("new_item_name_<?php echo $row['id'];?>").value+ "</span>";
                            var target_elem = document.getElementById("edit_item_<?php echo $row['id'];?>").innerHTML = new_input;
                        }
                    <?php } ?>

                    <?php if(is_authorized($account_type, "delete-payslip-item", "", "") === "allowed") {?>
                        function delete_<?php echo $row['id'];?>(){

                            var dataString = "item_id=" + "<?php echo $row['id'];?>";

                            if (confirm("Are you sure you want to delete this item?")) {
                                $.ajax({
                                    type: "POST",
                                    url: "./includes/ajax/delete-payslip-item",
                                    // RealPath
                                    data: dataString,
                                    cache: false,
                                    beforeSend: function() {
                                        $("button#delbtn_<?php echo $row['id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                        $("button#savebtn_<?php echo $row['id'];?>").attr("disabled", "disabled");
                                    },
                                    success: function(d) {
                                        $('div#return_server_msg').fadeIn('slow').html(d);
                                        $("button#delbtn_<?php echo $row['id'];?>").fadeIn("slow").html("Log In");
                                        $("button#delbtn_<?php echo $row['id'];?>").removeAttr("disabled");
                                    },
                                    error: function(d) {
                                        toastr.error("Something went wrong!");
                                    }
                                });
                                return false;
                            }
                        }
                    <?php } ?>
                </script>
            <?php
            }
        }
    }



    function get_payslip_by_employee_id($employee_id, $data_type) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM payslips WHERE employee_id=? ORDER BY payslip_month ASC, date_created DESC");
        mysqli_stmt_bind_param($sql, "i", $employee_id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);


            if($data_type == "count") {
                return mysqli_num_rows($get_result);
            } else {
                if(mysqli_num_rows($get_result) > 0) {
                    while($row = mysqli_fetch_array($get_result)){

                        if($data_type == "total") {
                            $grand_total[] = $row["total_amount"];
                        } else {?>

                            <div class="col-lg-4 col-sm-6 col-md-4 col-xl-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="dropdown profile-action">
                                            <a aria-expanded="false" data-bs-toggle="dropdown" class="action-icon dropdown-toggle" href="#"><i class="material-icons">more_vert</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="./edit-payslip?id=<?php echo $row['url_id'];?>" class="dropdown-item"><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                                <!-- <button class="dropdown-item"><i class="fa fa-trash-o m-r-5"></i> Delete</button> -->
                                            </div>
                                        </div>

                                        <h4 class="project-title"><a target="_blank" href="./view-payslip?id=<?php echo $row['url_id'];?>"><?php echo $row['payslip_number'];?></a></h4>
                                        <small class="block text-ellipsis m-b-15"> <span class="text-muted"><span class="fa fa-calendar"></span> <?php echo date("F, Y", strtotime($row['payslip_month']));?></span> </small>
                                        <!-- <p class="text-muted">Lorem Ipsum is simply dummy text of the printing and typesetting industry. When an unknown printer took a galley of type and scrambled it...</p> -->
                                        <div class="pro-deadline m-b-15">
                                            <div class="sub-title"> Date created </div>
                                            <div class="text-muted"> <?php echo date("jS F, Y", strtotime($row['date_created']));?> </div>
                                        </div>

                                        <div class="project-members m-b-15">
                                            <div>Created by:</div>
                                            <div class="text-muted"> <?php echo get_user_data($row['created_by'], "firstname")." ".get_user_data($row['created_by'], "surname");?> </div>
                                        </div>

                                        <p style="margin-bottom:0px;" class="m-b-5">
                                            Status
                                                <span class="text-<?php echo get_payslip_status_by_id($row['payslip_status'], "color");?> float-end">
                                                    <?php if(get_payslip_status_by_id($row['payslip_status'], "color") == "success") {?>
                                                        <span class="fa fa-check"></span>
                                                    <?php } ?>
                                                    <?php echo get_payslip_status_by_id($row['payslip_status'], "name");?>
                                                </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        <?php
                        }
                    }

                    if($data_type == "total") {
                        return array_sum($grand_total);
                    }
                } else {?> No Payslips <?php }
            }

        } else {
            echo "Something went wrong!";
        }
    }


    function get_payslip_employee_bank_details_by_payslip_id($payslip_id, $employee_id, $data) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM employee_payslip_banks WHERE payslip_id=? and employee_id=?");
        mysqli_stmt_bind_param($sql, "ss", $payslip_id, $employee_id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    function get_payslip_employee_bank_details_by_url_id($target, $target2, $data) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM employee_payslip_banks WHERE url_id=? and employee_id=?");
        mysqli_stmt_bind_param($sql, "ss", $target, $target2);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0 ) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }
    
    function get_employees_list($target_id, $list_type) {
        global $conn;

        $id = 'deleted';
        if($list_type == "payroll") { //  GET DEFAULTS TOO
            $sql = mysqli_prepare($conn, "SELECT * FROM user_accounts WHERE not id=? and basic_salary>0");
            mysqli_stmt_bind_param($sql, 's', $id);
        } else { // DO NOT INCLUDE DEFAULT
            $sql = mysqli_prepare($conn, "SELECT * FROM user_accounts WHERE not `id`=?");
            mysqli_stmt_bind_param($sql, 's', $id);
        }

        if(!mysqli_stmt_execute($sql)) {
            return 'error';
        } else {
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)){?><option <?php if($target_id == $row['acc_id']){ echo'selected'; } ?> value='<?php echo $row['acc_id'];?>'><?php echo get_account_titles_data_by_id($row['title'], "name")." ".$row['firstname']." ".$row['surname'];?></option><?php }
            } else { }
        }
    }





    

?>