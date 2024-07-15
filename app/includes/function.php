<?php
    function logout() {
        session_destroy();
        session_start();
        header("location: ../");
    }

    if(account_role_name($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_account_type'], "id") === "not_found") {
        logout();
    }

    function seo_friendly_url($string){
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string);
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
        return strtolower(trim($string, '-'));
    }

    $email_address =  $_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_dfghjkds_user_email_sdfghjkljgfdssdfghjk'];

    $sql = mysqli_prepare($conn, "SELECT  * FROM user_accounts where email=?");

    mysqli_stmt_bind_param($sql, 's', $email_address);
    mysqli_stmt_execute($sql);
    $get_data = mysqli_stmt_get_result($sql);

    if(mysqli_num_rows($get_data) ==1 ){ 
        while($adm_row = mysqli_fetch_array($get_data)){
            $account_id = $adm_row['acc_id'];
            $surname = $adm_row['surname'];
            $firstname = $adm_row['firstname'];
            $othername = $adm_row['othername'];
            $email = $adm_row['email'];
            $gender = $adm_row['gender'];
            $dob = $adm_row['dob'];
            $country = $adm_row['country'];
            $account_type = $adm_row['acc_type'];
            $user_account_type = $adm_row['acc_type'];
            $phone = $adm_row['phone'];
            $profile_pic = $adm_row['profile_pic'];
            $user_password = $adm_row['user_pwd'];
            $account_status = $adm_row['account_status'];
            $reg_date = $adm_row['reg_date'];
        }
    } else {
        logout();
    }


    function is_real_user() {
        global $conn, $account_type, $account_id, $account_status;
        
        if(account_role_name($account_type, "status") === "can_login" && $account_status == 1) {
            return true;
        } else {
            logout();
        }
    }

    is_real_user();


    function account_role_name($id, $data){
        global $conn;

        $sql= mysqli_prepare($conn, "SELECT * FROM staff_roles where id=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) == 1){
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

  
    function get_bank_list_in_table(){
        global $conn, $account_type;

        $id = "deleted";
        $sql = mysqli_prepare($conn, "SELECT * FROM bank_accounts WHERE not `status`=? and method_id='3'");
        mysqli_stmt_bind_param($sql, "s", $id);

        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            $n = "";
            while($row = mysqli_fetch_array($get_result)){$n++?>

                <tr class="place" id="bank_tbl_row_<?php echo $row['url_id'];?>">
                    <td> <?php echo $n;?> </td>

                    <td> <?php echo $row['account_name'];?></td>
                    <td> <?php echo $row['account_number'];?> / <?php echo $row['bank_name'];?> </td>
                    <td> <?php echo $row['date_created'];?> </td>
                    <td>
                        <a href="./settings-edit-payment-channels-bank?id=<?php echo $row['url_id'];?>" class="btn text-white btn-sm btn-primary"><i class="la la-edit"></i></a>
                        <button onclick="deletebank_<?php echo $row['id'];?>()" id="delete_<?php echo $row['url_id'];?>" class="btn text-white btn-sm btn-danger"><i class="la la-trash"></i></button>
                    </td>

                    <script>
                        function deletebank_<?php echo $row['id'];?>() {

                            var dataString = "bank_id=<?php echo $row['url_id'];?>";

                            if(confirm("Are you sure you want to delete this bank account?")) {
                                $.ajax({
                                    type: "POST",
                                    url: "./includes/ajax/settings-delete-payment-channels-bank",
                                    data: dataString,
                                    cache: false,
                                    beforeSend: function() {
                                        $("button#delete_<?php echo $row['url_id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span>");
                                        $("button#delete_<?php echo $row['url_id'];?>").attr("disabled", "disabled");
                                    },
                                    success: function(d) {
                                        $('div#return_server_msg').fadeIn('slow').html(d);
                                        $("button#delete_<?php echo $row['url_id'];?>").fadeIn("slow").html("<span class='la la-trash-o'></span>");
                                        $("button#delete_<?php echo $row['url_id'];?>").removeAttr("disabled");
                                    },
                                    error: function(d) {
                                        $("button#delete_<?php echo $row['url_id'];?>").fadeIn("slow").html("<span class='la la-trash-o'></span>");
                                        $("button#delete_<?php echo $row['url_id'];?>").removeAttr("disabled");
                                        toastr.error("Something went wrong!");
                                    }
                                });
                                return false;
                            }
                        }
                    </script>

                </tr>

            <?php
            }
        } else {

        }

    }


    function get_banks($target){
        global $conn, $account_type;

        $id = "deleted";
        $sql = mysqli_prepare($conn, "SELECT * FROM banks WHERE not `id`=?");
        mysqli_stmt_bind_param($sql, "s", $id);

        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            $n = "";
            while($row = mysqli_fetch_array($get_result)){$n++?>
                <option <?php if($target == $row['id']) { echo "selected"; } ?> value="<?php echo $row['id'];?>"> <?php echo $row['name'] ;?></option>
            <?php
            }
        } else {

        }

    }


    function get_bank_data_by_id($id, $data){
        global $conn;
        $sql= mysqli_prepare($conn, "SELECT * FROM banks where id=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) == 1){
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    function get_payment_channels_bank_data_by_url_id($id, $data){
        global $conn;
        $sql= mysqli_prepare($conn, "SELECT * FROM bank_accounts where url_id=? and method_id='3'");
        mysqli_stmt_bind_param($sql, "s", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) == 1){
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    function get_payment_channels_pos_data_by_url_id($id, $data){
        global $conn;
        $sql= mysqli_prepare($conn, "SELECT * FROM bank_accounts where url_id=? and method_id='2'");
        mysqli_stmt_bind_param($sql, "s", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) == 1){
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }
  
    function get_pos_list_in_table2(){
        global $conn, $account_type;

        $id = "deleted";
        $sql = mysqli_prepare($conn, "SELECT * FROM bank_accounts WHERE not `status`=? and method_id='2'");
        mysqli_stmt_bind_param($sql, "s", $id);
        
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            while($row = mysqli_fetch_array($get_result)){?>

                <tr class="place" id="pos_tbl_row_<?php echo $row['id'];?>">
                    <td>
                        <p id="show_pos_name_<?php echo $row['id'];?>"> <span id="_edit_pos_name_<?php echo $row['id'];?>"><?php echo $row['account_name'];?> </p>
                        
                        <div id="hide_pos_name_<?php echo $row['id'];?>" style="display:none" class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" value="<?php echo $row['account_name'];?>" placeholder="POS Name" class="form-control" value="" name="pos_name" id="pos_name_<?php echo $row['id'];?>"/>
                                    <input type="hidden" value="<?php echo $row['id'];?>" class="form-control" value="" name="pos_id" id="pos_id_<?php echo $row['id'];?>"/>
                                </div>
                            </div>
                        </div>
                    </td>

                    
                    <?php if((is_authorized($account_type, "ft-edit-pos", "", "") === "allowed" || is_authorized($account_type, "ft-delete-pos", "", "") === "allowed") && is_authorized($account_type, "sb-products", "", "") === "allowed" && is_authorized($account_type, "sb-manage-pos", "", "") === "allowed"){?>
                        <td>

                            <?php if((is_authorized($account_type, "ft-edit-pos", "", "") === "allowed") && is_authorized($account_type, "sb-products", "", "") === "allowed" && is_authorized($account_type, "sb-manage-pos", "", "") === "allowed"){?>
                                <a href="#!" id="poseditbtn_<?php echo $row['id'];?>" onclick="editposName_<?php echo $row['id'];?>()" data-toggle="modal" data-target="#edit_unit_<?php echo $row['id'];?>"  class="btn-sm btn-primary btn"><span class="fa fa-edit"></span> </a>
                            <?php } ?>

                            <a href="#!" style="display:none;" id="poscancelbtn_<?php echo $row['id'];?>" onclick="CancelposName_<?php echo $row['id'];?>()" data-toggle="modal" data-target="#edit_unit_<?php echo $row['id'];?>"  class="btn-sm btn-primary btn">Cancel</a>

                            <?php if((is_authorized($account_type, "ft-delete-pos", "", "") === "allowed") && is_authorized($account_type, "sb-products", "", "") === "allowed" && is_authorized($account_type, "sb-manage-pos", "", "") === "allowed"){?>
                                <a href="#!" id="delete_pos_<?php echo $row['id'];?>" onclick="deletepos_<?php echo $row['id'];?>()" class="btn-sm btn-outline-danger btn"><span class="fa fa-trash"></span> </a>

                                <script>

                                    function deletepos_<?php echo $row['id'];?>() {
                                        
                                        var dataString = "pos_id=" + $("#pos_id_<?php echo $row['id'];?>").val();
                                        
                                        $.ajax({
                                            type: "POST",
                                            url: "./includes/ajax/delete-pos",
                                            data: dataString,
                                            cache: false,
                                            beforeSend: function() {
                                                // $("button#login_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                                // $("button#login_btn").attr("disabled", "disabled");
                                            },
                                            success: function(d) {
                                                $('div#return_server_msg').fadeIn('slow').html(d);
                                            },
                                            error: function(d) {
                                                toastr.error("Something went wrong!");
                                            }
                                        });
                                        return false;
                                    }
                                </script>

                            <?php } else {?>
                                <div id="delete_pos_<?php echo $row['id'];?>"></div>
                            <?php } ?>
                        </td>
                    <?php } ?>

                    <?php if((is_authorized($account_type, "ft-edit-pos", "", "") === "allowed") && is_authorized($account_type, "sb-products", "", "") === "allowed" && is_authorized($account_type, "sb-manage-pos", "", "") === "allowed"){?>
                        <script>
                            function SaveposName_<?php echo $row['id'];?>() {

                                var dataString = "pos_name=" + $("#pos_name_<?php echo $row['id'];?>").val() +
                                    "&pos_id=" + $("#pos_id_<?php echo $row['id'];?>").val();

                                $.ajax({
                                    type: "POST",
                                    url: "./includes/ajax/update-pos",
                                    // RealPath
                                    data: dataString,
                                    cache: false,
                                    beforeSend: function() {
                                        // $("button#login_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Sending Request...</i>");
                                        // $("button#login_btn").attr("disabled", "disabled");
                                    },
                                    success: function(d) {
                                        $('div#return_server_msg').fadeIn('slow').html(d);
                                    },
                                    error: function(d) {
                                        toastr.error("Something went wrong!");
                                    }
                                });
                                return false;
                            

                            }
                        </script>
                    <?php } ?>

                        <script>
                            function editposName_<?php echo $row['id'];?>(){

                                document.getElementById("poseditbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-save'></span>";
                                document.getElementById("poseditbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-success");
                                document.getElementById("poseditbtn_<?php echo $row['id'];?>").setAttribute("onclick", "SaveposName_<?php echo $row['id'];?>()");

                                document.getElementById("poscancelbtn_<?php echo $row['id'];?>").removeAttribute("style");
                                document.getElementById("delete_pos_<?php echo $row['id'];?>").setAttribute("style", "display:none");
                                
                                document.getElementById("show_pos_name_<?php echo $row['id'];?>").setAttribute("style", "display:none");
                                document.getElementById("hide_pos_name_<?php echo $row['id'];?>").removeAttribute("style");

                            }


                            function CancelposName_<?php echo $row['id'];?>(){

                                document.getElementById("poseditbtn_<?php echo $row['id'];?>").innerHTML = "<span class='fa fa-edit'></span>";
                                document.getElementById("poseditbtn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-sm btn-primary");
                                document.getElementById("poseditbtn_<?php echo $row['id'];?>").setAttribute("onclick", "editposName_<?php echo $row['id'];?>()");

                                document.getElementById("poscancelbtn_<?php echo $row['id'];?>").setAttribute("style", "display:none");
                                document.getElementById("delete_pos_<?php echo $row['id'];?>").removeAttribute("style");

                                document.getElementById("show_pos_name_<?php echo $row['id'];?>").removeAttribute("style");
                                
                                document.getElementById("hide_pos_name_<?php echo $row['id'];?>").setAttribute("style", "display:none");

                            }

                        </script>
                </tr>

            <?php
            }
        } else {

        }

    }

    function get_pos_list_in_table(){
        global $conn, $account_type;

        $id = "deleted";
        $sql = mysqli_prepare($conn, "SELECT * FROM bank_accounts WHERE not `status`=? and method_id='2'");
        mysqli_stmt_bind_param($sql, "s", $id);

        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            $n = "";
            while($row = mysqli_fetch_array($get_result)){$n++?>

                <tr class="place" id="pos_tbl_row_<?php echo $row['url_id'];?>">
                    <td> <?php echo $n;?> </td>

                    <td> <?php echo $row['account_name'];?></td>
                    <td> <?php echo $row['date_created'];?> </td>
                    <td>
                        <a href="./settings-edit-payment-channels-pos?id=<?php echo $row['url_id'];?>" class="btn text-white btn-sm btn-primary"><i class="la la-edit"></i></a>
                        <button onclick="deletepos_<?php echo $row['id'];?>()" id="delete_<?php echo $row['url_id'];?>" class="btn text-white btn-sm btn-danger"><i class="la la-trash"></i></button>
                    </td>

                    <script>
                        function deletepos_<?php echo $row['id'];?>() {

                            var dataString = "pos_id=<?php echo $row['url_id'];?>";

                            if(confirm("Are you sure you want to delete this pos?")) {
                                $.ajax({
                                    type: "POST",
                                    url: "./includes/ajax/settings-delete-payment-channels-pos",
                                    data: dataString,
                                    cache: false,
                                    beforeSend: function() {
                                        $("button#delete_<?php echo $row['url_id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span>");
                                        $("button#delete_<?php echo $row['url_id'];?>").attr("disabled", "disabled");
                                    },
                                    success: function(d) {
                                        $('div#return_server_msg').fadeIn('slow').html(d);
                                        $("button#delete_<?php echo $row['url_id'];?>").fadeIn("slow").html("<span class='la la-trash-o'></span>");
                                        $("button#delete_<?php echo $row['url_id'];?>").removeAttr("disabled");
                                    },
                                    error: function(d) {
                                        $("button#delete_<?php echo $row['url_id'];?>").fadeIn("slow").html("<span class='la la-trash-o'></span>");
                                        $("button#delete_<?php echo $row['url_id'];?>").removeAttr("disabled");
                                        toastr.error("Something went wrong!");
                                    }
                                });
                                return false;
                            }
                        }
                    </script>

                </tr>

            <?php
            }
        } else {

        }

    }

    function get_payment_bank_list_in_table(){
        global $conn;

        $id = "deleted";
        $sql = mysqli_prepare($conn, "SELECT * FROM payment_banks WHERE not `id`=?");
        mysqli_stmt_bind_param($sql, "s", $id);

        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            $n = "";
            while($row = mysqli_fetch_array($get_result)){$n++?>

                <tr class="place" id="bank_tbl_row_<?php echo $row['url_id'];?>">
                    <td> <?php echo $n;?> </td>

                    <td> <?php echo $row['bank_name'];?></td>
                    <td> <?php echo $row['date_created'];?> </td>
                    <td>
                        <a href="./settings-edit-payment-bank?id=<?php echo $row['url_id'];?>" class="btn text-white btn-sm btn-primary"><i class="la la-edit"></i></a>
                        <button onclick="delete_<?php echo $row['id'];?>()" id="delete_<?php echo $row['url_id'];?>" class="btn text-white btn-sm btn-danger"><i class="la la-trash"></i></button>
                    </td>

                    <script>
                        function delete_<?php echo $row['id'];?>() {

                            var dataString = "bank_id=<?php echo $row['url_id'];?>";

                            if(confirm("Are you sure you want to delete this bank?")) {
                                $.ajax({
                                    type: "POST",
                                    url: "./includes/ajax/settings-delete-payment-bank",
                                    data: dataString,
                                    cache: false,
                                    beforeSend: function() {
                                        $("button#delete_<?php echo $row['url_id'];?>").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span>");
                                        $("button#delete_<?php echo $row['url_id'];?>").attr("disabled", "disabled");
                                    },
                                    success: function(d) {
                                        $('div#return_server_msg').fadeIn('slow').html(d);
                                        $("button#delete_<?php echo $row['url_id'];?>").fadeIn("slow").html("<span class='la la-trash-o'></span>");
                                        $("button#delete_<?php echo $row['url_id'];?>").removeAttr("disabled");
                                    },
                                    error: function(d) {
                                        $("button#delete_<?php echo $row['url_id'];?>").fadeIn("slow").html("<span class='la la-trash-o'></span>");
                                        $("button#delete_<?php echo $row['url_id'];?>").removeAttr("disabled");
                                        toastr.error("Something went wrong!");
                                    }
                                });
                                return false;
                            }
                        }
                    </script>

                </tr>

            <?php
            }
        } else {

        }

    }

    function get_invoice_data($url_id, $data){
        global $conn;
        
        $sql = mysqli_prepare($conn, "SELECT * FROM invoice where url_id=?");
        mysqli_stmt_bind_param($sql, "s", $url_id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) == 1){
                $row = mysqli_fetch_array($get_result);
                return $row[$data];
            } else {
                return "not_found";
            }
        } else {
            return "error";
        }
    }
    

    function get_additional_note_data($date, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM report_additional_notes where note_dates=?");
        mysqli_stmt_bind_param($sql, "s", $date);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) == 1){
                $row = mysqli_fetch_array($get_result);
                return $row[$data];
            } else {
                return "-";
            }
        } else {
            return "error";
        }
    }

    function get_invoice_data_by_number($invoice_number, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM invoice where invoice_number=?");
        mysqli_stmt_bind_param($sql, "s", $invoice_number);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) == 1){
                $row = mysqli_fetch_array($get_result);
                return $row[$data];
            } else {
                return "not_found";
            }
        } else {
            return "error";
        }
    }

    function get_invoice_transactions($invoice_id, $invoice_number){
        global $conn, $product_id;

        $sql = mysqli_prepare($conn, "SELECT * FROM invoice_trn_details where invoice_id=? and invoice_number=?");
        mysqli_stmt_bind_param($sql, "is", $invoice_id, $invoice_number);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0){
                $n = "";
                while($row = mysqli_fetch_array($get_result)){$n++;
                    $product_id = $row['product_id'];

                    $product_type = $row['transaction_type'];

                    if(strpos($_SERVER['PHP_SELF'], "view-invoice.php") == true) {?>

                            <tr>
                                <td class="col-5"><?php echo get_product_data($row['product_id'], "product_name") ;?></td>
                                <td colspan="3" class="col-1 text-1 text-right"><?php echo $row['qty'];?> </td>
                                <td colspan="3" class="col-2  text-right">
                                    <span class="float-left"> ₦</span> 
                                    <span class="text-right"> <?php echo custom_money_format($row['amount']);?></span>
                                </td>
                            </tr>

                    <?php } else { ?>
                    
                        <form method="POST" id="form_row_<?php echo $row['id'];?>">
                            <tr id="row_id_<?php echo $row['id'];?>">
                                <td>

                                    <?php $transaction_id = $row['id'];?>

                                    <div class="input-group mb-3">

                                        <input type="hidden" value="total_qty" id="total_qty_<?php echo $row['id'];?>" name="total_qty_<?php echo $row['id'];?>" class="total_qty_<?php echo $row['id'];?> form-control edit_<?php echo $row['id'];?>">

                                        <select style-="width:250px;" disabled onchange="getProductPrice_<?php echo $row['id'];?>()" class="select product_id edit_<?php echo $row['id'];?>" name="product_id" id="product_id_<?php echo $row['id'];?>">
                                            <option value='' label="Choose Product">Choose Product</option>
                                            <?php echo get_payable_items($row['product_id']);?>
                                        </select>

                                        <?php if(get_company_data("show_invoice_item_addtional_text") === "yes" ){?>
                                            <a onclick='ShowAdditionalText(<?php echo $transaction_id;?>)' title='Additional Text' style='float:right' href='javascript:(void)' class='btn-primary btn-xs'><span class='fa fa-pen'></span></a>
                                        <?php } ?>
                                    </div>

                                    <?php if(get_company_data("show_invoice_item_addtional_text") === "yes" ){?>
                                        <input value="<?php echo $row['additional_text'];?>" style='display:none;background-color:#fff;' placeholder='Write here...' id='ShowAdditionalText_<?php echo $transaction_id;?>' type='text' name='additional' class='form-control'>
                                    <?php } ?>

                                    <input disabled type="hidden" value="row_id_<?php echo $row['id'];?>" id="tr_row_id_<?php echo $row['id'];?>" name="tr_row_id" class="tr_row_id_<?php echo $row['id'];?> form-control edit_<?php echo $row['id'];?>">
                                    <!-- <input disabled type="hidden" value="product_qty_<?php // echo $row['id'];?>" id="for_qty_id_<?php echo $row['id'];?>" name="for_qty_id" class="for_qty_id form-control edit_<?php echo $row['id'];?>"> -->
                                    <input disabled type="hidden" value="product_rate_<?php echo $row['id'];?>" id="for_rate_id_<?php echo $row['id'];?>" name="for_rate_id" class="for_rate_id form-control edit_<?php echo $row['id'];?>">
                                    <input disabled type="hidden" value="product_total_<?php echo $row['id'];?>" id="for_amount_id_<?php echo $row['id'];?>" name="for_amount_id" class="for_amount_id form-control edit_<?php echo $row['id'];?>">

                                    <input disabled type="hidden" value="discount_id_<?php echo $row['id'];?>" id="for_discount_id_<?php echo $row['id'];?>" name="for_discount_id" class="for_discount_id form-control edit_<?php echo $row['id'];?>">

                                    <input disabled type="hidden" value="switchWR_<?php echo $row['id'];?>" id="switch_id_<?php echo $row['id'];?>" name="switch_id" class="form-control edit_<?php echo $row['id'];?>">
                                    <input disabled type="hidden" value="wr_btn_text2_<?php echo $row['id'];?>" id="wr_btn_<?php echo $row['id'];?>" name="wr_btn" class="form-control edit_<?php echo $row['id'];?>">

                                    <input disabled  type="hidden" step='0.001' class="form-control" value="h_total_paid" id="for_h_grand_total_<?php echo $row['id'];?>">

                                    <input disabled  type="hidden" value="<?php echo $row['invoice_number'];?>" id="invoice_number_<?php echo $row['id'];?>" name="invoice_number" class="invoice_number form-control edit_<?php echo $row['id'];?>">
                                    <input disabled type="hidden" value="<?php echo $row['invoice_id'];?>" id="invoice_id_<?php echo $row['id'];?>" name="invoice_id" class="invoice_id form-control edit_<?php echo $row['id'];?>">
                                    <input disabled type="hidden" value="<?php echo $row['id'];?>" id="invoice_trn_id_<?php echo $row['id'];?>" name="invoice_trn_id" class="invoice_trn_id form-control edit_<?php echo $row['id'];?>">
                                    <input disabled type="hidden" value="h_total" id="for_h_total_<?php echo $row['id'];?>" name="for_h_total" class="for_h_total form-control edit_<?php echo $row['id'];?>">

                                    <input disabled type="hidden" value="h_total_<?php echo $row['id'];?>" id="for_h_input_total_<?php echo $row['id'];?>" name="for_h_input_total" class="for_h_input_total form-control edit_<?php // echo $row['id'];?>">
                                    <!-- <input disabled value="<?php // echo get_invoice_data(@$_GET['id'], "total_qty");?>" name="total_qty" required min="0" type="hidden" step='0.001' class="h_qty form-control edit_<?php // echo $row['id'];?>"> -->
                                    <!-- <input disabled value="<?php // echo get_invoice_data(@$_GET['id'], "total_paid");?>" name="grand_total" required min="0" type="hidden" step='0.001' class="h_total form-control edit_<?php // echo $row['id'];?>"> -->

                                </td>

                                <td>

                                    <div class="input-group mb-3" style="width:350px;">
                                        <span class="input-group-text"><b>₦</b></span>
                                        <input disabled value="<?php echo $row['rate'];?>" name="h_total" readonly required min="0" type="number" step='0.01'  onchange="StartAccounting_<?php echo $row['id'];?>()" onkeydown="StartAccounting_<?php echo $row['id'];?>()" onkeyup="StartAccounting_<?php echo $row['id'];?>()" class="h_total form-control edit_<?php echo $row['id'];?>" placeholder="Selling Price" id="h_total_<?php echo $row['id'];?>" aria-describedby="h_total">

                                        <div style="z-" class="btn-group">
                                            <button data-bs-toggle="modal" data-bs-target="#edit_receipt_item" class="btn-xs btn-primary text-white" style="padding:6px;" onclick="get_item_data('<?php echo $row['invoice_id'];?>','<?php echo $row['invoice_number'];?>', '<?php echo $transaction_id;?>');" id="item_row_id_<?php echo $row['id'];?>" type="button">
                                                <span data-toggle='tooltip' id="edit_btn_text_<?php echo $row['id'];?>" title='' class='fa fa-pencil text-primsary'></span>
                                            </button>
                                        </div>

                                        <!-- <a class="btn btn-warning btn-xs" style="display:none" type="submit" onclick="CancelEditingRowData_<?php echo $row['id'];?>(); StartAccounting_<?php echo $row['id'];?>(); StartAccounting2_<?php echo $row['id'];?>()" id="cancel_inv_trn_btn_<?php echo $row['id'];?>" style='background-color:transparent;border:none;' href='javascript:(void)'><u> <span class='fa fa-times'></span> Cancel</u></a>
                                        <a class="btn btn-danger btn-xs" style="display:none" onclick="DeleteRowData_<?php echo $row['id'];?>()" id="delete_inv_trn_btn_<?php echo $row['id'];?>" style='background-color:transparent;border:none;' href='javascript:(void)'><span data-toggle='tooltip' title='Delete this row' class='fa fa-trash-alt text-dangers'></span></a>  -->
                                    </div>

                                    <!-- <input id="discount_pattern_<?php // echo $row['id'];?>" name="discount_pattern" type="hidden" value="<?php // echo (auto_encode_discount_pattern(get_company_data("discount_pattern")));?>" class="discount_pattern form-control"> -->

                                    <b style="display:none;">₦</b><b style="display:none;" class="amount" id="product_total_<?php echo $row['id'];?>"><?php echo custom_money_format($row['amount'], 3);?></b>
                                    <input value="<?php echo $row['amount'];?>" name="h_total" required min="0" type="hidden" step='0.001' class="form-control" id="h_total_<?php echo $row['id'];?>">
                                    <!-- <br/> -->

                                    <div class="col-sm-6" id="switch_holder_<?php echo $row['id'];?>" style="display:none;">

                                    <input disabled value="<?php echo $row['transaction_type'];?>" name="switchWR" required type="hidden" class="form-control edit_<?php echo $row['id'];?>" id="check_box_value_<?php echo $row['id'];?>">

                                    <select name='switchWR' disabled id='switchWR_<?php echo $row['id'];?>' onchange="Load_Retail_<?php echo $row['id'];?>()" required class="edit_<?php echo $row['id'];?>">
                                        <option value=''>Choose Method</option>
                                        <option <?php if($row['transaction_type'] == "retail") {echo "selected"; } ?> value='retail' label="Is Retail">Is Retail</option>
                                        <option <?php if($row['transaction_type'] == "wholesale") {echo "selected"; } ?> value='wholesale' label="Is Wholesale">Is Wholesale</option>
                                        <?php if($row['transaction_type'] == "h_wholesale") {?>
                                            <option <?php if($row['transaction_type'] == "h_wholesale") {echo "selected"; } ?> value='h_wholesale' label="Half QTY sold as Wholesale">Half QTY sold as Wholesale</option>
                                        <?php } ?>
                                    </select>

                                    </div>
                                   <span id="loader_<?php echo $row['id'];?>"></span>

                                    <script>

                                        function EditRowData_<?php echo $row['id'];?>(){
                                            
                                            if (getCookie("row_name") != ""){
                                                toastr.error("Please finish editing row " + getCookie("row_name") + " of this invoice.");
                                            } else {
                                                
                                                document.cookie = "row_name=<?php echo $n;?>";
                                                
                                                document.cookie = "row_id=row_id_<?php echo $row['id'];?>";

                                                document.getElementById("row_id_<?php echo $row['id'];?>").style.backgroundColor = "#b3b3b3";

                                                document.getElementById("edit_inv_trn_btn_<?php echo $row['id'];?>").setAttribute("onclick", "SaveRowData_<?php echo $row['id'];?>()");
                                   
                                                document.getElementById("edit_inv_trn_btn_<?php echo $row['id'];?>").setAttribute("class", "btn btn-success btn-xs text-white");
             
                                                document.getElementById("edit_btn_text_<?php echo $row['id'];?>").setAttribute("title", "Save Changes"); 

                                                document.getElementById("edit_btn_text_<?php echo $row['id'];?>").setAttribute("class", "fa fa-save text-white");

                                                document.getElementById("delete_inv_trn_btn_<?php echo $row['id'];?>").removeAttribute("style");

                                                document.getElementById("cancel_inv_trn_btn_<?php echo $row['id'];?>").removeAttribute("style");
                                                

                                                var elems_qty_total = document.getElementsByClassName('edit_<?php echo $row['id'];?>');

                                                var total_qty = 0;
                                                for (var ii = 0; ii < elems_qty_total.length; ii++) {
                                                    total_qty += elems_qty_total[ii].removeAttribute("disabled");
                                                }
                                            }

                                        }


                                        function CancelEditingRowData_<?php echo $row['id'];?>(){

                                            document.getElementById("form_row_<?php echo $row['id'];?>").reset();

                                            document.cookie = "row_name=";
                                            document.cookie = "row_id=";

                                            document.getElementById("row_id_<?php echo $row['id'];?>").removeAttribute("style");
                                            document.getElementById("edit_inv_trn_btn_<?php echo $row['id'];?>").setAttribute("onclick", "EditRowData_<?php echo $row['id'];?>()");
                                            document.getElementById("edit_btn_text_<?php echo $row['id'];?>").setAttribute("title", "Edit this row");
                                            document.getElementById("edit_btn_text_<?php echo $row['id'];?>").setAttribute("class", "fa fa-edit text-primary");
                                            document.getElementById("delete_inv_trn_btn_<?php echo $row['id'];?>").removeAttribute("style");
                                            document.getElementById("cancel_inv_trn_btn_<?php echo $row['id'];?>").setAttribute("style", "display:none");

                                            var elems_qty_total = document.getElementsByClassName('edit_<?php echo $row['id'];?>');

                                            var total_qty = 0;
                                            for (var ii = 0; ii < elems_qty_total.length; ii++) {
                                                total_qty += elems_qty_total[ii].setAttribute("disabled", "disabled");
                                            }

                                            window.location.replace('');

                                        }
                                    </script>

                                    <?php $get_track_id = $row['id'];?>
                                    <?php require "./includes/edit_invoice_js.php";?>
                                </td>

                            </tr>
                        </form>

                    <?php } ?>

                <?php
                }
            } else {
                return "not_found";
            }
        } else {
            return "error";
        }

        
    }


    function interpret_page_id($data, $page_id, $default){
        global $conn;

        // INTERPRET PAGE ID
        $sql_interpret = mysqli_prepare($conn, "SELECT * FROM privilege_id WHERE page_id=?");
        mysqli_stmt_bind_param($sql_interpret, "s", $page_id);
        mysqli_stmt_execute($sql_interpret);
        $get_result = mysqli_stmt_get_result($sql_interpret);

        if(mysqli_num_rows($get_result) == 1){
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return $default;
        }
    }

    function is_authorized($role_id, $page_id, $a, $b){
        global $conn;

        $page_id = interpret_page_id("id", $page_id, $page_id);

        $sql = mysqli_prepare($conn, "SELECT * FROM privileges WHERE role_id=? and page_id=?");
        mysqli_stmt_bind_param($sql, "ii", $role_id, $page_id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);

        if(mysqli_num_rows($get_result)==1){
            $row = mysqli_fetch_array($get_result);
            if($row['value'] === "allowed"){
                return $row['value'];
            } else {
                return "not_allowed";
            }
        } else {
            return "not_allowed";
        }
    }

    function get_invoice_trn_details_by_date_and_product($product_id, $date, $action, $data){
        global $conn;

        if($action == "sum"){
            $sql = mysqli_prepare($conn, "SELECT $data, transaction_type FROM invoice_trn_details where product_id=? and date_created=?");
        } else {
            $sql = mysqli_prepare($conn, "SELECT * FROM invoice_trn_details where product_id=? and date_created=?");
        }
        mysqli_stmt_bind_param($sql, "is", $product_id, $date);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);

            if($action == "sum"){
                $a[] = 0;
                while($row = mysqli_fetch_array($get_result)){
                    
                    if($row['transaction_type'] == "wholesale"){
                        $wholesale = ($row[$data] * get_product_data($product_id, "pair_qty") );
                    }

                    $a[] = ($wholesale);
                }

                return array_sum($a);

             } else {
                 if(mysqli_num_rows($get_result) > 0){
                     return "sold";
                 } else {
                     return "not_sold";
                 }
            }
        } else {
            return "error";
        }
    }

    function get_employees($type){
        global $conn, $account_id, $account_type;

        if(in_array($type, array("list", "count") ) ) {

            $id = "";
            if(isset($_POST['designation']) && isset($_POST['department']) && isset($_POST['branch'])) {

                $designation = (int)(mysqli_real_escape_string($conn, $_POST['designation']));
                $department = mysqli_real_escape_string($conn, $_POST['department']);
                $branch = mysqli_real_escape_string($conn, $_POST['branch']);

                if($designation == 'all' || $designation == '') { $designation = ''; } else { $designation = " and designation='$designation' "; }
                if($department == 'all' || $department == '') { $department = ''; } else { $department = " and department='$department' "; }
                if($branch == 'all' || $branch == '') { $branch = ''; } else { $branch = " and branch='$branch' "; }

                $sql = mysqli_prepare($conn, "SELECT * FROM user_accounts WHERE not id=? $designation $department $branch");
                mysqli_stmt_bind_param($sql, "s", $id);

            } else {
                $sql = mysqli_prepare($conn, "SELECT * FROM user_accounts WHERE not id=?");
                mysqli_stmt_bind_param($sql, "s", $id);
            }

            if(mysqli_stmt_execute($sql)){
                $get_result = mysqli_stmt_get_result($sql);

                if($type == "count"){
                    return mysqli_num_rows($get_result);
                } else if($type == "list"){
                    if(mysqli_num_rows($get_result) > 0){
                        while($row = mysqli_fetch_array($get_result)){?>
                        <?php if($row['acc_id'] != $account_id){ $profile_url = "./edit-employee?id=".$row['url_id']; } else { $profile_url = "./my-account"; } ?>
                        <tr <?php if($row['acc_id'] == $account_id) {?> style="background-color:#eee;" <?php } ?>>
                            <td>
                                <b><?php echo get_account_titles_data_by_id($row['title'], "name");?> <?php echo $row['firstname']. " ". $row['surname'];?> </b><br/>
                                Last seen:
                                <?php 
                                    if(activity_time("last_check", $row['acc_id']) != "not_found"){?>
                                        <?php echo time_elapsed_string(activity_time("last_check", $row['acc_id']));?>
                                    <?php } else { echo "Not Available"; }
                                ?>
                            </td>
                            <td><?php echo str_replace("not_found","not-assigned", get_designation_data_by_id($row['designation'], "name"));?></td>
                            <td><?php echo str_replace("not_found","not-assigned", get_department_data_by_id($row['department'], "name"));?></td>
                            <td><?php echo str_replace("not_found","not-assigned", get_branch_data_by_id($row['branch'], "name"));?></td>
                            <td align="right">
                                <?php if($row['acc_id'] != $account_id) {?>
                                    <?php if(is_authorized($account_type, "edit-employee", "", "") === "allowed" ) { ?>
                                        <a href="<?php echo $profile_url;?>" data-bs-toggle="tooltip" title="Edit Profile" data-bs-placement="top" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a>
                                        <a href="./employee-profile?id=<?php echo $row['url_id'];?>" data-bs-toggle="tooltip" title="View Profile" data-bs-placement="top" class="btn text-white btn-sm btn-danger"><i class="la la-user-tie"></i></a>
                                    <?php } ?>
                                <?php } else { ?>
                                    <?php if(is_authorized($account_type, "profile", "", "") === "allowed" ) { ?>
                                        <a href="<?php echo $profile_url;?>" data-bs-toggle="tooltip" title="My Profile" data-bs-placement="top" class="btn btn-sm btn-danger"><i class="la la-user-tie"></i></a>
                                    <?php } ?>
                                <?php } ?>
                                <!-- <button class="btn btn-sm btn-danger"> <span class="fa fa-trash-o"></span></button> -->
                            </td>
                        </tr>
                        <?php
                        }
                    }
                }
            }
        }
    }

    function get_user_roles2($type){
        global $conn, $account_id, $account_type;

        if(in_array($type, array("list", "count") ) ) {
            $id = "";
            $sql = mysqli_prepare($conn, "SELECT * FROM staff_roles WHERE not id=? ORDER BY is_default DESC");
            mysqli_stmt_bind_param($sql, "s", $id);
            if(mysqli_stmt_execute($sql)){
                $get_result = mysqli_stmt_get_result($sql);

                if($type == "count"){
                    return mysqli_num_rows($get_result);
                } else if($type == "list"){

                    if(mysqli_num_rows($get_result) > 0){
                        while($row = mysqli_fetch_array($get_result)){?>

                            <li class="<?php if(isset($_GET['role']) && $_GET['role'] == $row['alias_name']) { echo "active"; } ?>">
                                <a <?php if($row['status'] != "can_login") { ?> style="color:red;text-decoration:line-through;" <?php } ?> href="javascript:(void)">
                                    <span onclick="window.location.href='?role=<?php echo $row['alias_name'];?>'">
                                        <span class="la la-<?php echo $row['icon'];?>"></span>
                                        <?php echo $row['role_name'];?> <?php if($row['is_default'] == "default") { echo " <span style='color:#eee;'>- default</span>"; } ?>
                                    </span>
                                    <?php if($row['is_default'] != "default") {?>
                                        <span class="role-action">
                                            <span class="action-circle large" data-bs-toggle="modal" data-bs-target="#edit_role">
                                                <i class="material-icons">edit</i>
                                            </span>
                                        </span>
                                    <?php } ?>
                                </a>
                            </li>

                        <?php
                        }
                    }
                }
            }
        }
    }


    function get_user_roles($type){
        global $conn, $account_id, $account_type;
        
        if(in_array($type, array("list", "count") ) ) {
            $id = "";
            $sql = mysqli_prepare($conn, "SELECT * FROM staff_roles WHERE not id=? ORDER BY is_default DESC");
            mysqli_stmt_bind_param($sql, "s", $id);
            if(mysqli_stmt_execute($sql)){
                $get_result = mysqli_stmt_get_result($sql);

                if($type == "count"){
                    return mysqli_num_rows($get_result);
                } else if($type == "list"){

                    if(mysqli_num_rows($get_result) > 0){

                        while($row = mysqli_fetch_array($get_result)){?>

                            <li id="row_<?php echo $row['id'];?>" class="<?php if(isset($_GET['role']) && $_GET['role'] == $row['alias_name']) { echo "active"; } ?>">
                                <a <?php if($row['status'] != "can_login") { ?> style="color:red;text-decoration:line-through;" <?php } ?> href="javascript:(void)">
                                    <span onclick="window.location.href='?role=<?php echo $row['alias_name'];?>'">
                                        <span class="la la-<?php echo $row['icon'];?>"></span>
                                        <?php echo $row['role_name'];?> <?php if($row['is_default'] == "default") { echo " <span style='color:#eee;'>- default</span>"; } ?>
                                    </span>
                                    <?php if($row['is_default'] != "default") {?>
                                        <?php if(is_authorized($account_type, "edit-user-role", "", "") === "allowed") {?>
                                            <span class="role-action">
                                                <span onclick="get_role_data(<?php echo $row['id'];?>)" class="action-circle large" data-bs-toggle="modal" data-bs-target="#edit_role">
                                                    <span class="fa fa-pencil"></span>
                                                </span>
                                            </span>
                                        <?php } ?>
                                    <?php } ?>
                                </a>
                            </li>

                        <?php
                        }
                    }
                }
            }
        }
    }



    function account_role_data_by_id($id, $data){
        global $conn;

        $id = mysqli_real_escape_string($conn, $id);
        $sql = mysqli_prepare($conn, "SELECT * FROM staff_roles where id=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) == 1){
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_customizer($data, $data_type, $type){
        global $conn, $account_id; 
        $sql = mysqli_prepare($conn, "SELECT $data FROM customizer WHERE `user_id`=?");
        mysqli_stmt_bind_param($sql, "s", $account_id);
        if(mysqli_stmt_execute($sql)){ 
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result)==1){ 
                $row = mysqli_fetch_array($get_result);
                if($type == "interpreted") {
                    return interpret_customizer("value", $data_type, $row[$data]);
                } else {
                    return $row[$data];
                }
            } else {
                return "not_found";
            }
        } else {
            return "error";
        }
    }

    function interpret_customizer($data, $table, $id) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT $data FROM soft_$table WHERE `id`=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result)==1){
                $row = mysqli_fetch_array($get_result);
                return $row[$data];
            } else {
                return "not_found";
            }
        } else {
            return "error";
        }
    }

    function get_customizer_settings($table, $id) {
        global $conn;

        $a = "";
        $sql = mysqli_prepare($conn, "SELECT * FROM soft_$table WHERE not `id`=?");
        mysqli_stmt_bind_param($sql, "s", $a);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result)>0){
                while($row = mysqli_fetch_array($get_result)){?>
                    <option <?php if($id == $row['id']) { echo "selected"; }?> value="<?php echo $row['id'];?>"><?php echo $row['name'];?></option>
                <?php
                }
            } else {
                return "not_found";
            }
        } else {
            return "error";
        }
    }


    function activity_time($data, $account_id){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM track_inactivity WHERE `user_id`=?");
        mysqli_stmt_bind_param($sql, "s", $account_id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result)==1){
                $row = mysqli_fetch_array($get_result);
                return $row[$data];
            } else {
                return "not_found";
            }
        } else {
            return "error";
        }
    }

    function get_account_type($account_id){
        global $conn;

        $id = "";
        $sql = mysqli_prepare($conn, "SELECT * FROM staff_roles where not id=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            while($row = mysqli_fetch_array($get_result)){?>
                <option value="<?php echo $row['id'];?>" <?php if($row['id'] == $account_id) { echo "selected";} ?>>
                        <?php echo strtoupper($row['role_name']);?>
                </option>
            <?php
            }
        } else {
            echo "Something went wrong!";
        }
    }


    function get_account_type2(){
        global $conn;

        $nature = "editable";
        $sql = mysqli_prepare($conn, "SELECT * FROM staff_roles where nature=?");
        mysqli_stmt_bind_param($sql, "s", $nature);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            while($row = mysqli_fetch_array($get_result)){?>
            
                <div class="col-12 col-sm-6 col-md-3 col-sxl mt-3">
                    <div class="card bg-<?php echo $row['color'];?> text-white">
                        <a class="text-white" href="?role=<?php echo $row['alias_name'];?>">
                            <div class="card-body py-4 d-flex justify-content-center">
                                <div class="p-2"> 
                                    <span class="card-title h5 font-weight-bold mt-3">
                                        <span class="fa fa-<?php echo $row['icon'];?>"></span> <?php echo ($row['role_name']);?>
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            <?php
            }
        } else {
            echo "Something went wrong!";
        }
    }

    function account_role_data_by_alias($id, $data){
        global $conn;

        $id = mysqli_real_escape_string($conn, $id);

        $sql= mysqli_prepare($conn, "SELECT * FROM staff_roles where alias_name=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) == 1){
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    function account_role_data_by_alias_by_id($alias_name, $id, $data){
        global $conn;

        $id = mysqli_real_escape_string($conn, $id);
        $sql = mysqli_prepare($conn, "SELECT * FROM staff_roles where id=? and alias_name=?");
        mysqli_stmt_bind_param($sql, "is", $id, $alias_name);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) == 1){
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_privilege_settings($role_id){
        global $conn;

        $id = "";
        $sql = mysqli_prepare($conn, "SELECT * FROM privilege_groups WHERE not id=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        
        if(mysqli_num_rows($get_result)>0) {
            $n = "";
            while($row = mysqli_fetch_array($get_result)){
                $n++;
                
                $default_tab = "";
                $default_tab2 = "";

                if($n == "1"){
                    $default_tab = "-show-";
                    $default_tab2 = "true";
                }
                
                $group_id = $row['id'];
                
                echo "<input type='hidden' name='group_id[]' value='$group_id'>";?>

                    <div id="accordion<?php echo $row['id'];?>" class="accordion-alt" role="tablist">
                        <div class="mb-2">

                            <div class="card-header" style="background-color:#ffebda;">
                                <h4  style="font-size:15px;" class="card-title mb-0" data-bs-toggle="tooltip" title-="<?php echo $row['group_name'];?> - [CLICK TO EXPAND]">
                                    <a style="font-size:16px;" class="text-uppercase collapsed" data-bs-toggle="collapse" href="#collapse<?php echo $row['id'];?>" aria-expanded="<?php echo $default_tab2;?>" aria-controls="collapse<?php echo $row['id'];?>">
                                        <b> <?php echo $row['group_name'];?> </b>
                                    </a>
                                </h4>
                            </div>

                            <div id="collapse<?php echo $row['id'];?>" style="<?php echo $default_tab;?>" class="collapse <?php echo $default_tab;?>" role="tabpanel" data-parent="#accordion<?php echo $row['id'];?>"> 

                                <div class="card-body" style="padding:0px;">

                                    <?php
                                        // FETCH ITEMS BASED ON GROUP ID;

                                        $sql2 = mysqli_prepare($conn, "SELECT * FROM privilege_id WHERE group_id=? ORDER BY ordering ASC");
                                        mysqli_stmt_bind_param($sql2, "i", $group_id);
                                        mysqli_stmt_execute($sql2);
                                        $get_result2 = mysqli_stmt_get_result($sql2);

                                        if(mysqli_num_rows($get_result2)>0) {
                                            $n2 = "";
                                            while($row2 = mysqli_fetch_array($get_result2)){$n2++;
                                                // echo $n2;

                                                $page_id = $row2['id'];

                                                $page_name = $row2['page_id'];
                                                
                                                $is_checked = is_privilege_checked($role_id, $page_id, "");

                                                $a = "page_id_$group_id"."[]";
                                                $b = "page_name_$group_id"."[]";
                                                
                                                echo "<input type='hidden' name='$a' value='$page_id'>";
                                                echo "<input type='hidden' name='$b' value='$page_name'>"; ?> 

                                                <?php $dynamic = strtolower(str_replace(array(" ", "-"), array("_","_"), $page_name));?>  

                                                <input type='hidden' id="value_<?php echo $dynamic."_".$page_id;?>" name='data_<?php echo $group_id;?>[]' value='<?php echo is_privilege_checked($role_id, $page_id, "value");?>'>

                                                <style>
                                                    .priv_hover:hover {
                                                        background-color:#eee;
                                                    }
                                                </style>

                                                <h5 class="priv_hover table-hover border stripped" style="padding:10px 5px;-margin-top:20px;-margin-bottom:20px;" id="">
                                                    <span class='fa fa-minus'></span>
                                                    <?php $checkbox_id = "checkbox_$dynamic"."_"."$page_id";?>

                                                    <?php echo $row2['description'];?>

                                                    <div class="status-toggle">
                                                        <input class="check" onchange="is_checked_<?php echo $dynamic;?>();" value='allowed' id="<?php echo $checkbox_id;?>" data-on='Allow' type='checkbox' <?php echo $is_checked;?> data-toggle='toggle' data-size='xs' data-width='70'> 
                                                        <label for="<?php echo $checkbox_id;?>" class="checktoggle">checkbox</label>
                                                    </div>

                                                    <!-- <div class="status-toggle">
                                                        <input type="checkbox" id="staff_module" class="check">
                                                        <label for="staff_module" class="checktoggle">checkbox</label>
                                                    </div> -->

                                                </h5>

                                                <script>

                                                    function is_checked_<?php echo $dynamic;?>(){

                                                        var data_value = document.getElementById("value_<?php echo $dynamic."_".$page_id;?>");
                                                        var checkbox = document.getElementById("checkbox_<?php echo $dynamic."_".$page_id;?>");

                                                        if(checkbox.checked == true){
                                                            data_value.value = checkbox.value;
                                                        } else {
                                                            data_value.value = "un_checked";
                                                        }

                                                        var dataString = $("#set_privileges").serialize();

                                                        $.ajax({
                                                            type: "POST",
                                                            url: "./includes/ajax/save-privileges",
                                                            data: dataString,
                                                            cache: false,
                                                            beforeSend: function() {
                                                                // document.getElementById("<?php //echo //$checkbox_id;?>").setAttribute("style", "background-color:red");
                                                                // document.getElementById("<?php //echo //$checkbox_id;?>").setAttribute("disabled", "disabled");
                                                            },
                                                            success: function(d) {
                                                                $('div#return_server_msg2').fadeIn('slow').html(d);
                                                                // $("button#exec_data_export_bk_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Backup Data");
                                                                // $("button#exec_data_export_bk_btn").removeAttr("disabled");
                                                            },
                                                            error: function(d) {
                                                                // $("button#exec_data_export_bk_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Backup Data");
                                                                // $("button#exec_data_export_bk_btn").removeAttr("disabled");
                                                                toastr.error("Something went wrong!");
                                                            }
                                                        });
                                                    }
                                                </script>
                                            
                                            <?php 
                                            }
                                            
                                        }
                                        
                                        echo "</div></div></div></div>";
                
            }
        } else {
            echo "Settings Group not found!";
        }




    }

    function is_privilege_checked($role_id, $page_id, $data){
        global $conn;

        $value = "allowed";
        $sql= mysqli_prepare($conn, "SELECT * FROM privileges where role_id=? and page_id=? and `value`=?");
        mysqli_stmt_bind_param($sql, "iis", $role_id, $page_id, $value);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) == 1){
            $row = mysqli_fetch_array($get_result);
            if(!empty($data)){
                return $row[$data];
            } else {
                return "checked";
            }
        } else {
            return "not_checked";
        }
    }

    function get_account_status($type, $status_id, $data){
        global $conn;

        if($type == "list"){
            $id = "";
            $sql = mysqli_prepare($conn, "SELECT * FROM account_status where not id=?");
            mysqli_stmt_bind_param($sql, "s", $id);
            if(mysqli_stmt_execute($sql)){
                $get_result = mysqli_stmt_get_result($sql);
                while($row = mysqli_fetch_array($get_result)){?>
                    <option value="<?php echo $row['id'];?>" <?php if($row['id'] == $status_id) { echo "selected";} ?>>
                            <?php echo strtoupper($row['other_name']);?>
                    </option>
                <?php
                }
            } else {
                echo "Something went wrong!";
            }
        } else {
            $sql = mysqli_prepare($conn, "SELECT $data FROM account_status where id=?");
            mysqli_stmt_bind_param($sql, "s", $id);
            if(mysqli_stmt_execute($sql)){
                $get_result = mysqli_stmt_get_result($sql);
                if(mysqli_num_rows($get_result) == 1){
                    $row = mysqli_fetch_array($get_result);
                    return $row[$data];
                } else {
                    return "not_found";
                }
            } else {
                return "error";
            }
        }
    }

    function day_session(){
        if(date("H") > -1 && date("H") < 12 ) {
            return "Good Morning";
        } else if(date("H") > 11 && date("H") < 16 ) {
            return "Good Afternoon";
        } else if(date("H") > 15) {
            return "Good Evening";
        }
    }


    function get_splitted_payments($type, $invoice_id, $invoice_number, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM splitted_payments WHERE invoice_id=? and invoice_number=?");
        mysqli_stmt_bind_param($sql, "is", $invoice_id, $invoice_number);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);

            if(mysqli_num_rows($get_result)>0){

                if($type == "tbl_list"){

                    while($row = mysqli_fetch_array($get_result)){
                        $a[] = $row['amount'];
                        $tr_id = "del_split_".md5($row['id']);?>

                        <tr id="<?php echo $tr_id;?>" style="width:250px;">

                            <td>
                                <select style="width: 100px;" class="select split_payment_method" name="split_payment_method[]" id="payment_method_for_split_<?php echo $row['id'];?>" style="width:250px;">
                                    <option value='' label="Choose Method">Choose Method</option>
                                    <?php echo get_payment_methods("", "list", $row['payment_method']);?>
                                </select>
                            </td>

                            <td> <input value="<?php echo $row['payment_name'];?>" id="split_payment_name" type="text" name="split_payment_name[]" class="split_payment_name form-control" style-="width:100px;"> </td>

                            <td> <input type="number" value="<?php echo $row['amount'] ;?>" min="1" step='0.01' onkeydown='Calculate_Splits()' onkeyup='Calculate_Splits()' onchange='Calculate_Splits()' onclick='Calculate_Splits()' name="split_amount[]" class="split_amount form-control" style-="width:100px;"> </td>

                            <td>
                                <a onclick="removeSplitRow_<?php echo $tr_id;?>()" href="#!" style="position:absolute" class=""><span class="fa text-danger fa-trash-o" style="float:right"></span> </a> 
                                <input style-='width:200px;' type="date" value="<?php echo date("Y-m-d", strtotime($row['payment_date']));?>" class="payment_date form-control" name="split_payment_date[]" id="split_payment_date"> 
                            </td>

                            <script>
                                function removeSplitRow_<?php echo $tr_id;?>(){
                                    if(confirm('Are you sure to remove?')){
                                        document.getElementById("<?php echo $tr_id;?>").innerHTML = "";
                                        document.getElementById("not_msg").innerHTML = "<div class='alert alert-primary'> Click <b>'Save Changes'</b> to save your changes</div>";
                                        Calculate_Splits();
                                    }
                                }
                            </script>

                        </tr>
 

                     <?php } ?>
                        <div id="not_msg"></div>
                     <?php
                } else if($type == "linear_list"){
                    while($row = mysqli_fetch_array($get_result)){
                        $a[] = get_payment_methods( $row[$data], "", "methods" );
                    }
                    return implode(array_unique($a), ", ");
                } else {
                    while($row = mysqli_fetch_array($get_result)){
                        $a[] = $row[$data];
                    }
                    return array_sum($a);
                }
            }
        } else {
            echo "Something went wrong!";
        }
    }


    function get_splitted_payments2($invoice_id, $invoice_number){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM splitted_payments WHERE invoice_id=? and invoice_number=?");
        mysqli_stmt_bind_param($sql, "is", $invoice_id, $invoice_number);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result)>0){?>
                <div style='border:2px solid #eee;padding:12px;'> <?php while($row = mysqli_fetch_array($get_result)){ $total_amount[] = $row['amount'];?> <p style='margin-bottom:-10px;margin-top:-10px;'><b>Channel</b>: <?php echo get_payment_methods_data($row['method_id'], $row['payment_method'], $row['invoice_id'], $row['invoice_number']);?><br/><b>Payer's Name</b>: <?php echo $row['payment_name'];?> <br/><b>Amount Paid:</b> ₦<?php echo custom_money_format($row['amount']);?> </br/><b>Payment date:</b> <?php echo date('Y-m-d', strtotime($row['payment_date']));?></p><hr/> <?php } ?> <b>TOTAL</b>: ₦<?php echo custom_money_format(array_sum($total_amount));?> </div> <?php
            } else {
                echo "Something went wrong!";
            }
        }
    }

    function get_splitted_payments3($invoice_id, $invoice_number){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM splitted_payments WHERE invoice_id=? and invoice_number=?");
        mysqli_stmt_bind_param($sql, "is", $invoice_id, $invoice_number);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result)>0){?>
                <div style='border:2px solid #000;padding:12px;font-weight:bolder;'> <p style="margin-top:-5px;margin-bottom:3px;"><b>PAYMENT ANALYSIS</b></p> <?php while($row = mysqli_fetch_array($get_result)){ $total_amount[] = $row['amount'];?> <p style='margin-bottom:2px;margin-top:2px;'><b>Channel</b>: <?php echo get_payment_methods_data($row['method_id'], $row['payment_method'], $row['invoice_id'], $row['invoice_number']);?><br/><b>Payer's Name</b>: <?php echo $row['payment_name'];?> <br/><b>Amount Paid:</b> ₦<?php echo custom_money_format($row['amount']);?> </br/><b>Payment date:</b> <?php echo date('Y-m-d', strtotime($row['payment_date']));?></p><hr style='margin:0;padding:0;'/> <?php } ?> <b>TOTAL</b>: ₦<?php echo custom_money_format(array_sum($total_amount));?> </div> <?php
            } else {
                echo "Something went wrong!";
            }
        }
    }


    function get_excess_payment_data($invoice_number, $data) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM invoice_excess_payments WHERE invoice_number=?");
        mysqli_stmt_bind_param($sql, "s", $invoice_number);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);

        if(mysqli_num_rows($get_result) == 1) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    function create_update_excess_payments($invoice_id, $invoice_number, $customer_id, $excess_amount, $date) {
        global $conn;

        /**
         * CHECK IF EXCESS PAYMENT IDENTITY EXIST OR NOT
         * IF EXIST LET EXIST = YES
         * ELSE LET EXIST = NO
        */

        if(get_excess_payment_data($invoice_number, "invoice_id") == "not_found") {  // NO, CREATE INVOICE EXCESS PAYMENT DATA
            if($excess_amount > 0) {
                $sql = mysqli_prepare($conn, "INSERT INTO invoice_excess_payments(excess_amount, customer_id, invoice_id, invoice_number, main_date) VALUES(?,?,?,?,?)");
                mysqli_stmt_bind_param($sql, "ssiss", $excess_amount, $customer_id, $invoice_id, $invoice_number, $date);
                if(mysqli_stmt_execute($sql)) { return "success"; } else { return "failed"; }
            } else { return "success"; }
        } else {// YES, UPDATE INVOICE EXCESS PAYMENT DATA
            $sql = mysqli_prepare($conn, "UPDATE invoice_excess_payments SET excess_amount=?, customer_id=? where invoice_id=? and invoice_number=?");
            mysqli_stmt_bind_param($sql, "ssis", $excess_amount, $customer_id, $invoice_id, $invoice_number);
            if(!mysqli_stmt_execute($sql)) { return "failed"; }
            if($excess_amount <= 0) {

                $sql = mysqli_prepare($conn, "DELETE FROM invoice_excess_payments WHERE invoice_id=? and invoice_number=?");
                mysqli_stmt_bind_param($sql, "is", $invoice_id, $invoice_number);
                if(mysqli_stmt_execute($sql)) { return "success"; } else { return "failed"; }

            } else { return "success"; }
        }

    }


    function customers_by_payment_method($type, $method, $payment_id){
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

        if($type == "list"){

            $id = "";
            if($method == "1"){
                $sql = mysqli_prepare($conn, "SELECT * FROM invoice where not id=? and method_id IN ('$method', 0) and date_created BETWEEN '$from_date' and '$to_date'");
            } else {
                $sql = mysqli_prepare($conn, "SELECT * FROM invoice where not id=? and payment_method IN ('$payment_id', 0) and method_id IN ('$method', 0) and date_created BETWEEN '$from_date' and '$to_date'");
            }

            mysqli_stmt_bind_param($sql, "s", $id);
            mysqli_stmt_execute($sql);
            $get_result = mysqli_stmt_get_result($sql);

            while($row = mysqli_fetch_array($get_result)){

                // $discountid = $row['discountid'];
                $invoice_number = $row['invoice_number'];
                $invoice_id = $row['id'];
                $method_id = $row['method_id'];
                $payment_method = $row['payment_method'];
                $amount_paid = $row['total_paid'];

                if($row['is_split'] == "yes"){
                    // echo "This is Split";
                    $split = mysqli_prepare($conn, "SELECT * FROM splitted_payments WHERE invoice_number=? and invoice_id=? and method_id=? and payment_method=?");
                    mysqli_stmt_bind_param($split, "siii", $invoice_number, $invoice_id, $method, $payment_id);
                    if(mysqli_stmt_execute($split)){
                        $get_result2 = mysqli_stmt_get_result($split);

                        while($row2 = mysqli_fetch_array($get_result2)){
                            $method_id = $row2['method_id'];
                            $payment_method = $row2['payment_method'];
                            $amount_paid = $row2['amount'];
                            $customer_name = get_customer_data2(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "surname");?>

                                <tr>
                                    <td></td>
                                    <td>
                                        <?php $data1 = strtoupper(get_invoice_data_by_number($row['invoice_number'], "customer_name"));?>
                                        <?php
                                            if(!in_array(get_customer_data(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "customer_id"), array("error", "not_found")) ) {
                                                $customer_id = get_customer_data2(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "customer_id");
                                                $customer_name = get_customer_data2(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "surname");
                                                echo " $customer_id ($customer_name) ";
                                            } else {
                                                echo " $data1";
                                            }
                                        ?>

                                        <i>(Receipt -
                                            <b><?php echo $row['invoice_number'];?></b> <br/>
                                                <b> - Name: 
                                                    <?php
                                                        if( !empty(str_replace(array(" ","-"), array("",""), $row2['payment_name'])) ) {
                                                            $data1 = strtoupper($row2['payment_name']);
                                                            if(!in_array(get_customer_data($row2['payment_name'], "customer_id"), array("error", "not_found")) ) {
                                                                $customer_id = get_customer_data2($row2['payment_name'], "customer_id");
                                                                $customer_name = get_customer_data2($row2['payment_name'], "surname");
                                                                echo " $customer_id ($customer_name) ";
                                                            } else {
                                                                echo " $data1";
                                                            }
                                                        } else {
                                                            echo "Not provided";
                                                        }
                                                    ?>
                                                </b>
                                                <br/>
                                                <b> - On:
                                                    <?php 
                                                        if( !empty(str_replace(array("0000-00-00",""), array("",""), $row2['payment_date'])) ) {
                                                            echo date("l dS F, Y", strtotime($row2['payment_date']));
                                                        } else {
                                                            echo "Not provided";
                                                        }
                                                    ?>
                                                </b>
                                            )
                                        </i>
                                    </td>

                                    <td align="right" colspan=""><span class="float-left">₦</span> <u><b>
                                        <?php echo custom_money_format($amount_paid);?>
                                    </b></u></td>
                                </tr>

                        <?php 
                        }
                    } else {
                        echo "Something went wrong here";
                    }
                } else {
                    //$customer_name = get_customer_data2(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "surname")." ".get_customer_data2(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "firstname")." ".get_customer_data2(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "othername");
                    ?>
                    <tr>
                        <td></td>
                        <td></td>

                        <td colspan="3"> 
                            <?php // echo get_customer_data2(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "customer_id");?> 
                            
                            <?php $data1 = strtoupper(get_invoice_data_by_number($row['invoice_number'], "customer_name"));?>
                            <?php
                                if(!in_array(get_customer_data(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "customer_id"), array("error", "not_found")) ) {
                                    $customer_id = get_customer_data2(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "customer_id");
                                    $customer_name = get_customer_data2(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "surname")." ".get_customer_data2(get_invoice_data_by_number($invoice_number, "customer_name"), "firstname")." ".get_customer_data2(get_invoice_data_by_number($invoice_number, "customer_name"), "othername");
                                    echo " $customer_id ($customer_name) ";
                                } else {
                                    echo " $data1";
                                }
                            ?>


                            <?php //echo $customer_name;?>
                            <i>(Invoice - 
                                <?php if($_SERVER['PHP_SELF'] == "/alennsar2.app/generate-reports.php") { ?>
                                    <a target="_blank" href="./view-receipt?id=<?php echo get_invoice_data_by_number($row['invoice_number'], "url_id");?>"> 
                                        <u><?php echo $row['invoice_number'];?></u>
                                    </a>
                                <?php } else { ?>
                                    <b><?php echo $row['invoice_number'];?></b>
                                <?php } ?>
                                <br/>   <b> 
                                            - Name:
                                            <?php

                                                if( !empty(str_replace(array(" ","-"), array("",""), $row['customer_name'])) ) {

                                                    // echo get_customer_data2(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "customer_id");

                                                    $data1 = strtoupper($row['customer_name']);

                                                    if(!in_array(get_customer_data(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "customer_id"), array("error", "not_found")) ) {
                                                        $customer_id = get_customer_data2($row['customer_name'], "customer_id");
                                                        $customer_name = get_customer_data2($row['customer_name'], "surname")." ".get_customer_data2($row['customer_name'], "firstname")." ".get_customer_data2($row['customer_name'], "othername");
                                                        echo " $customer_id ($customer_name) ";
                                                    } else {
                                                        echo " $data1";
                                                    }

                                                    // echo $customer_name;
                                                } else {
                                                    echo "Not provided";
                                                }
                                            ?>
                                        </b>
                                <br/>   <b> - On:
                                            <?php
                                                if( !empty(str_replace(array("0000-00-00",""), array("",""), $row['payment_date'])) ) {
                                                    echo date("l dS F, Y", strtotime($row['payment_date']));
                                                } else {
                                                    echo "Not provided";
                                                }
                                            ?>
                                        </b>
                                )
                            </i> 
                        </td>

                        <td align="right" colspan="3"><span class="float-left">₦</span> <u><b>
                            <?php echo custom_money_format($amount_paid);?>
                        </b></u></td>
                        <td align="right" colspan="9"></td>
                    </tr>
                    <?php 
                }

            }
        } else if($type == "total"){

            $sql2 = mysqli_prepare($conn, "SELECT discounts.amount, invoice_trn_details.discountid, invoice_trn_details.qty FROM discounts 
            LEFT JOIN invoice_trn_details ON discounts.discountid = invoice_trn_details.discountid
            where date_added=?");

            mysqli_stmt_bind_param($sql2, "s", $from_date);
            mysqli_stmt_execute($sql2);
            
            $get_result2 = mysqli_stmt_get_result($sql2);
            while($row = mysqli_fetch_array($get_result2)){
                $data2[] = $row["amount"];
            }

            if(@array_sum($data2) == ""){
                return "0.00";
            } else {
                return @array_sum($data2);
            }
        }
    }

    function get_customer_balance($customer_id, $status, $cmd, $data) {
        global $conn;

        // Convert friendly text (status) to DB status
        if(in_array($status, array("paid","used","pending"))) {
            if($status == "paid") { $status = 1; } 
            else if($status == "used") { $status = 2;} 
            else if($status == "pending") { $status = 3; } 
        } else { $status = 0; }

        $sql = mysqli_prepare($conn, "SELECT $cmd($data) FROM invoice_excess_payments WHERE customer_id=? and excess_status=?");
        mysqli_stmt_bind_param($sql, "si", $customer_id, $status);
        if(!mysqli_stmt_execute($sql)) { return "error"; }
        $get_result = mysqli_stmt_get_result($sql);
        $row = mysqli_fetch_array($get_result);
        
        if(empty($row["$cmd($data)"]) || $row["$cmd($data)"] < 1) {
            return 0.00;
        } else {
            return ($row["$cmd($data)"]+0);
        }
    }

    function get_excess_invoice_data($data, $customer_id, $current_status) {
        global $conn;

        if(in_array($current_status, array("paid","used","pending"))) {
            $status = $current_status;
            if($status == "paid") { $status = 1; } 
            else if($status == "used") { $status = 2;} 
            else if($status == "pending") { $status = 3; }
        } else { $status = 0; }

        $sql = mysqli_prepare($conn, "SELECT * FROM invoice_excess_payments WHERE excess_status=? and customer_id=?");
        mysqli_stmt_bind_param($sql, "is", $status, $customer_id);
        if(!mysqli_stmt_execute($sql)) { return "error"; }
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result)>0) {
            while($row = mysqli_fetch_array($get_result)) { $dataset[] = $row["$data"]; }
            return implode($dataset, ",");
            // return mysqli_num_rows($get_result);
        }
    }

    function auto_update_customer_balance_status($customer_id, $status_as, $current_status, $invoice_initiated_on) {
        global $conn, $account_id;

        // Convert friendly text (status) to DB status
        if(in_array($status_as, array("paid","used","pending"))) {
            if($status_as == "paid") { $status_as = 1; } 
            else if($status_as == "used") { $status_as = 2;} 
            else if($status_as == "pending") { $status_as = 3; } 
        } else { $status_as = 0; }

        if(in_array($current_status, array("paid","used","pending"))) {
            if($current_status == "paid") { $current_status = 1; } 
            else if($current_status == "used") { $current_status = 2;} 
            else if($current_status == "pending") { $current_status = 3; } 
        } else { $current_status = 0; }

        // INVOICE IDs
        $dataset = explode(",", get_excess_invoice_data("invoice_number", $customer_id, "pending"));
        $total_set = count($dataset);

        $errors = 0;
        for($set=0; $set<($total_set); $set++) { // Loop THROUGH INVOICE (Number) DATASET

            $invoice_number = $dataset[$set]; // Invoice Number
            $used_bal = get_excess_payment_data_by_invoice($invoice_number, $customer_id, "excess_amount"); // Balance to Use
            $invoice_id = get_excess_payment_data_by_invoice($invoice_number, $customer_id, "invoice_id"); // Invoice Number attached to Balance

            $sql = mysqli_prepare($conn, "INSERT INTO excess_track_used_paid_balance(customer_id, used_bal, invoice_id, invoice_number, invoice_initiated, initiated_by) VALUES(?,?,?,?,?,?)");
            mysqli_stmt_bind_param($sql, "ssissi", $customer_id, $used_bal, $invoice_id, $invoice_number, $invoice_initiated_on, $account_id);
            if(!mysqli_stmt_execute($sql)) { $errors += $errors+1; } // Count / track errors

        }

        if($errors == 0) {
            $sql = mysqli_prepare($conn, "UPDATE invoice_excess_payments SET excess_status=? WHERE excess_status=? and customer_id=?");
            mysqli_stmt_bind_param($sql, "iis", $status_as, $current_status, $customer_id);
            if(!mysqli_stmt_execute($sql)) { return "error"; }
            else { return "success"; }
        } else {
            return "error";
        }

    }

    function auto_reverse_customer_excess($invoice_number) { // Auto Reverse Customer Excess Balance On Invoice Cancellation
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM excess_track_used_paid_balance WHERE invoice_initiated=?");
        mysqli_stmt_bind_param($sql, "s", $invoice_number);
        if(!mysqli_stmt_execute($sql)) { return "error"; }
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0) {
            $err = 0;
            while($row = mysqli_fetch_array($get_result)) {
                @$n--;
                /**
                 * Invoice excess which was used to reconcile another invoice
                 */
                $excess_invoice_number = $row['invoice_number']; // Invoice Number
                $excess_invoice_id = $row['invoice_id']; // Invoice id

                /**
                 * Find and reverse used balance
                 */
                $excess_status = 3;
                $sql_find = mysqli_prepare($conn, "UPDATE invoice_excess_payments SET excess_status=? WHERE invoice_number=? and invoice_id=?");
                mysqli_stmt_bind_param($sql_find, "ssi", $excess_status, $excess_invoice_number, $excess_invoice_id);
                if(!mysqli_stmt_execute($sql_find)) { $err = (@$err+1); }

                /**
                 * At the end of each loop (i.e Reversing all used excess balance)
                 * Delete / Clear all of it's Transaction History / Tracies
                 */

                $delete = mysqli_prepare($conn, "DELETE FROM excess_track_used_paid_balance WHERE invoice_initiated=? OR invoice_number=? OR invoice_id=?");
                mysqli_stmt_bind_param($delete, "ssi", $invoice_number, $excess_invoice_number, $excess_invoice_id);
                if(!mysqli_stmt_execute($delete)) { $err = (@$err+1); }

                /**
                 * IF THE TERMINATED / CANCELLED / DELETED IS DOES HAVE AN EXCESS,
                 * DELETE ALL OF IT'S EXCESS RELATED BALANCES
                 */

                $delete2 = mysqli_prepare($conn, "DELETE FROM invoice_excess_payments WHERE invoice_number=?");
                mysqli_stmt_bind_param($delete2, "s", $invoice_number);
                if(!mysqli_stmt_execute($delete2)) { $err = (@$err+1); }
            }

            // Check for Errors
            if($err == 0) { return "success"; } else { return "failed"; }

        } else {
            return "success";
        }
    }

    function get_excess_payment_data_by_invoice($invoice_number, $customer_id, $data) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT $data FROM invoice_excess_payments WHERE invoice_number=? and customer_id=?");
        mysqli_stmt_bind_param($sql, "ss", $invoice_number, $customer_id);
        if(!mysqli_stmt_execute($sql)) { return "error"; }
        $get_result = mysqli_stmt_get_result($sql);
        $row = mysqli_fetch_array($get_result);
        return $row["$data"];
    }

    function is_customer_balance_used($invoice_number) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM excess_track_used_paid_balance WHERE invoice_initiated=?");
        mysqli_stmt_bind_param($sql, "s", $invoice_number);
        if(!mysqli_stmt_execute($sql)) { return "error"; }
        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0) { return "success"; }
        else { return "not_found"; }
    }


    function customers_by_payment_method_and_invoice($method_id, $invoice_id){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT sum(total_paid) FROM invoice WHERE id=? and method_id=?");
        mysqli_stmt_bind_param($sql, "ii", $invoice_id, $method_id); 
        mysqli_stmt_execute($sql);

        $get_result = mysqli_stmt_get_result($sql);

        while($row2 = mysqli_fetch_array($get_result)){
            $data2[] = $row2["sum(total_paid)"];
        }
        
        if(@array_sum($data2) == ""){ 
            return "0.00";
        } else {
            return @array_sum($data2);
        }
    }


    function get_payment_methods_by_id($type, $method){
        global $conn;

        if($method == "single"){

            $id = "deleted";
            $sql = mysqli_prepare($conn, "SELECT * FROM bank_accounts WHERE not `status`=? and method_id=?");
            mysqli_stmt_bind_param($sql, "si", $id, $type);

            if(mysqli_stmt_execute($sql)){
                $get_result = mysqli_stmt_get_result($sql);
                while($row = mysqli_fetch_array($get_result)){

                    $method_id = $row['method_id'];
                    $id = $row['id'];

                    if(!in_array($type, array("1","4","5"))) { ?>

                    <tr>
                        <td></td>
                        <td colspan="">
                            <u><b>
                            <?php echo $row['account_name'];?> 
                            <?php
                                if($type == "3"){?>
                                   - <?php echo $row['bank_name'];?> - <?php echo $row['account_number'];?> 
                                <?php
                                }
                            ?>
                            </b></u>
                        </td>

                        <td align="right">
                            <span class="float-left">₦</span>
                            <u>
                                <b>
                                    <?php echo custom_money_format(get_split_sales_by_date_method($row['id'], "amount"));?>
                                </b>
                            </u>
                        </td>
                    </tr>

                <?php
                    }

                    // <!-- GET CUSTOMERS WHO PAID TO THIS ACCOUNT -->
                    echo customers_by_payment_method("list", "$method_id", $id);

                }
            } else {
                echo "Error";
            }
        } else {

            if(isset($_POST['from']) && !empty($_POST['from'])){
                $from_date = date("Y-m-d", strtotime($_POST['from']));
            } else if(isset($_GET['from']) && !empty($_GET['from'])){
                $from_date = date("Y-m-d", strtotime($_GET['from']));
            } else {
                $from_date = date("Y-m-d");
            }

            $sql = mysqli_prepare($conn, "SELECT sum(total_paid) FROM invoice WHERE method_id=? and date_created=?");
    
            mysqli_stmt_bind_param($sql, "is", $type, $from_date);
            mysqli_stmt_execute($sql);
    
            $get_result = mysqli_stmt_get_result($sql);
    
            while($row2 = mysqli_fetch_array($get_result)){
                $data2[] = $row2["sum(total_paid)"];
            }
            
            if(@array_sum($data2) == ""){
                return "0.00";
            } else {
                return @array_sum($data2);
            }
        }
    }


    function total_split_payments($type){
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
        $sql = mysqli_prepare($conn, "SELECT sum(amount) FROM splitted_payments WHERE method_id=? and date_added BETWEEN '$from_date' and '$to_date'");
        mysqli_stmt_bind_param($sql, "i", $type);
        mysqli_stmt_execute($sql);

        $get_result = mysqli_stmt_get_result($sql);

        while($row2 = mysqli_fetch_array($get_result)){
            $data2[] = $row2["sum(amount)"];
        }

        if(@array_sum($data2) == ""){
            return "0.00";
        } else {
            return @array_sum($data2);
        }

    }



    function total_split_payments_invoice_id($type, $invoice_id){
        global $conn;
    
        $sql = mysqli_prepare($conn, "SELECT sum(amount) FROM splitted_payments WHERE invoice_id=? and method_id=?");
        mysqli_stmt_bind_param($sql, "ii", $invoice_id, $type);
        mysqli_stmt_execute($sql);

        $get_result = mysqli_stmt_get_result($sql);

        while($row2 = mysqli_fetch_array($get_result)){
            $data2[] = $row2["sum(amount)"];
        }
        
        if(@array_sum($data2) == ""){
            return "0.00";
        } else {
            return @array_sum($data2);
        }

    }

    function get_invoice_trn_data($row_id, $data){
        global $conn;

        $check = mysqli_prepare($conn, "SELECT * FROM invoice_trn_details WHERE id=? or discountid=?");
        mysqli_stmt_bind_param($check, "is", $row_id, $row_id);
        if(mysqli_stmt_execute($check)){
            $get_result = mysqli_stmt_get_result($check);
            if(mysqli_num_rows($get_result)==1){
                $row = mysqli_fetch_array($get_result);
                return $row[$data];
            } else {
                return "not_found";
            }
        }
    }

    function get_invoice_trn_data2($row_id, $data){
        global $conn;

        $check = mysqli_prepare($conn, "SELECT * FROM invoice_trn_details WHERE id=?");
        mysqli_stmt_bind_param($check, "i", $row_id);
        if(mysqli_stmt_execute($check)){
            $get_result = mysqli_stmt_get_result($check);
            if(mysqli_num_rows($get_result)==1){
                $row = mysqli_fetch_array($get_result);
                return $row[$data];
            } else {
                return "not_found";
            }
        }
    }

    function get_invoice_trn_whole_data($invoice_id, $invoice_number, $data){
        global $conn;
        
        $check = mysqli_prepare($conn, "SELECT sum($data) FROM invoice_trn_details WHERE invoice_id=? and invoice_number=?");
        mysqli_stmt_bind_param($check, "is", $invoice_id, $invoice_number);
        if(mysqli_stmt_execute($check)){
            $get_result = mysqli_stmt_get_result($check);
            if(mysqli_num_rows($get_result)==1){
                $row = mysqli_fetch_array($get_result);
                return $row["sum($data)"];
            } else {
                return "not_found";
            }
        }
    }


    function get_split_sales_by_date_method($method, $data){
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

        $id = "";
        $sql = mysqli_prepare($conn, "SELECT sum($data) FROM splitted_payments WHERE payment_method IN ($method) and not id=? and date_added BETWEEN '$from_date' and '$to_date'");
        mysqli_stmt_bind_param($sql, "s", $id);
        mysqli_stmt_execute($sql);

        $get_result = mysqli_stmt_get_result($sql);

        while($row = mysqli_fetch_array($get_result)){
            $data2[] = $row["sum($data)"];
        }

        if(@array_sum($data2) == ""){
            return "0.00";
        } else {
            return @array_sum($data2);
        }
    }

    function get_sales_by_date_method($method, $data){
        global $conn;

        if(isset($_POST['from']) && !empty($_POST['from'])){
            $from_date = date("Y-m-d", strtotime($_POST['from']));

        } else if(isset($_GET['from']) && !empty($_GET['from'])){

            $from_date = date("Y-m-d", strtotime($_GET['from']));
            
        } else {
            $from_date = date("Y-m-d");
        }
        
        $sql = mysqli_prepare($conn, "SELECT sum($data) FROM invoice WHERE payment_method IN ($method) and date_created=?");

        mysqli_stmt_bind_param($sql, "s", $from_date);
        mysqli_stmt_execute($sql);

        $get_result = mysqli_stmt_get_result($sql);

        while($row = mysqli_fetch_array($get_result)){
            $data2[] = $row["sum($data)"];
        }
        
        if(@array_sum($data2) == ""){
            return "0.00";
        } else {
            return @array_sum($data2);
        }

    }


    function get_company_data($data) {
        global $conn;
        
        $id = "1";
        $sql = mysqli_prepare($conn, "SELECT * FROM softdata WHERE id=?");
        mysqli_stmt_bind_param($sql, "i", $id);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        $row = mysqli_fetch_array($get_result);
        return Base64_decode($row[$data]);

    }


    function auto_generate_chart($type){
        global $conn;
        
        if($type == "weekly"){
            
            if(isset($_POST['from']) && !empty($_POST['from']) && isset($_POST['to']) && !empty($_POST['to'])){
            
                $date2 = date("Y-m-d", strtotime($_POST['from'])); // FROM
                $date = date("Y-m-d", strtotime($_POST['to']));// TO

                $time_frame = round(((strtotime(date("Y-m-d", strtotime($date))) - strtotime(date("Y-m-d", strtotime($date2)))) / 86400 ), 0);

                $date2 = date("Y-m-d", strtotime(($time_frame+1) . " days ago"));

            } else {
                $date = date("Y-m-d");
                $date2 = date("Y-m-d", strtotime("7 days ago"));
            }

            $id = "";
            
            $delete = mysqli_prepare($conn, "DELETE FROM weekly_chart_reports where not report_id=?");
            mysqli_stmt_bind_param($delete, 'i', $id);
            mysqli_stmt_execute($delete);
            
            $sql = mysqli_prepare($conn, "SELECT DISTINCT(EXTRACT(YEAR FROM `date_created`)), EXTRACT(MONTH FROM `date_created`), EXTRACT(DAY FROM `date_created`) FROM invoice_trn_details where not id=? and (`date_created` BETWEEN '$date2' and '$date')");
            mysqli_stmt_bind_param($sql, 's', $date);
            mysqli_stmt_execute($sql);
            
            $get_result = mysqli_stmt_get_result($sql);
            
            if(mysqli_num_rows($get_result)>0){
                while($row = mysqli_fetch_array($get_result)){
                    $months = $row['EXTRACT(MONTH FROM `date_created`)'];
                    $years = $row['(EXTRACT(YEAR FROM `date_created`))'];
                    $days = $row['EXTRACT(DAY FROM `date_created`)'];

                    $sql2 = mysqli_prepare($conn, "SELECT SUM(amount) from invoice_trn_details where EXTRACT(YEAR FROM `date_created`)=? AND EXTRACT(MONTH FROM `date_created`)=? AND EXTRACT(DAY FROM `date_created`)=? ORDER BY EXTRACT(DAY FROM `date_created`) ASC, EXTRACT(MONTH FROM `date_created`) ASC, EXTRACT(YEAR FROM `date_created`) ASC");
                    mysqli_stmt_bind_param($sql2, 'iii', $years, $months, $days);
                    mysqli_stmt_execute($sql2);
                    
                    $get_result2 = mysqli_stmt_get_result($sql2);
                    $row2 = mysqli_fetch_array($get_result2);

                    $dayName = date("j", mktime(0, 0, 0, $days, 10));
                    $monthName = date("F", strtotime($years."-".$months."-".$days));
                    $monthNameYear = $days. " " . $monthName . ", ". $years;
            
                    $total_amount = $row2['SUM(amount)'];


                    $sql4 = mysqli_prepare($conn, "SELECT `year` from weekly_chart_reports where `year`=? ORDER BY `year` ASC");
                    mysqli_stmt_bind_param($sql4, 's', $monthNameYear);
                    mysqli_stmt_execute($sql4);
            
                    $get_result4 = mysqli_stmt_get_result($sql4);
                    if(mysqli_num_rows($get_result4)>0){
                        $sql3 = mysqli_prepare($conn, "UPDATE weekly_chart_reports SET amount=? WHERE `year`=?");
                        mysqli_Stmt_bind_param($sql3, "ss", $total_amount, $monthNameYear);
                        mysqli_stmt_execute($sql3);
                    } else {
                        $sql3 = mysqli_prepare($conn, "INSERT INTO weekly_chart_reports(`year`, amount) VALUE(?,?)");
                        mysqli_Stmt_bind_param($sql3, "ss", $monthNameYear, $total_amount);
                        mysqli_stmt_execute($sql3);
                    }
                }
            } else {
                echo "No result";
            }
        } else if($type == "monthly"){
            
            $date = date("Y-m-d");
            $date2 = date("Y-m-d", strtotime("12 months ago"));
            
            $id = "";
            
            $delete = mysqli_prepare($conn, "DELETE FROM monthly_chart_reports where not report_id=?");
            mysqli_stmt_bind_param($delete, 'i', $id);
            mysqli_stmt_execute($delete);
                
            $sql = mysqli_prepare($conn, "SELECT DISTINCT(EXTRACT(YEAR FROM `date_created`)), EXTRACT(MONTH FROM `date_created`), EXTRACT(DAY FROM `date_created`) FROM invoice_trn_details where not id=? and (`date_created` BETWEEN '$date2' and '$date')");
            mysqli_stmt_bind_param($sql, 's', $date);
            mysqli_stmt_execute($sql);
            
            $get_result = mysqli_stmt_get_result($sql);
            
            if(mysqli_num_rows($get_result)>0){
                while($row = mysqli_fetch_array($get_result)){

                    $months = $row['EXTRACT(MONTH FROM `date_created`)'];
                    $years = $row['(EXTRACT(YEAR FROM `date_created`))'];
                    $days = $row['EXTRACT(DAY FROM `date_created`)'];

                    $sql2 = mysqli_prepare($conn, "SELECT SUM(amount) from invoice_trn_details where EXTRACT(YEAR FROM `date_created`)=? AND EXTRACT(MONTH FROM `date_created`)=? ORDER BY EXTRACT(MONTH FROM `date_created`) ASC, EXTRACT(YEAR FROM `date_created`) ASC");
                    mysqli_stmt_bind_param($sql2, 'ii', $years, $months);
                    mysqli_stmt_execute($sql2);

                    $get_result2 = mysqli_stmt_get_result($sql2);
                    $row2 = mysqli_fetch_array($get_result2);

                    $monthName = date("M", mktime(0, 0, 0, $months, 10));
                    $monthNameYear = $monthName . ", ". $years;

                    $total_amount = $row2['SUM(amount)'];
                    $sql4 = mysqli_prepare($conn, "SELECT `year` from monthly_chart_reports where `year`=? ORDER BY `year` ASC");
                    mysqli_stmt_bind_param($sql4, 's', $monthNameYear);
                    mysqli_stmt_execute($sql4);
            
                    $get_result4 = mysqli_stmt_get_result($sql4);
                    if(mysqli_num_rows($get_result4)>0){
                        $sql3 = mysqli_prepare($conn, "UPDATE monthly_chart_reports SET amount=? WHERE `year`=?");
                        mysqli_Stmt_bind_param($sql3, "ss", $total_amount, $monthNameYear);
                        mysqli_stmt_execute($sql3);
                    } else {
                        $sql3 = mysqli_prepare($conn, "INSERT INTO monthly_chart_reports(`year`, amount) VALUE(?,?)");
                        mysqli_Stmt_bind_param($sql3, "ss", $monthNameYear, $total_amount);
                        mysqli_stmt_execute($sql3);
                    }
                }
            } else {
                echo "No result";
            }
        }
    }


    function get_invoice_transactions2($invoice_id, $invoice_number, $type){
        global $conn, $product_id;

        $sql = mysqli_prepare($conn, "SELECT * FROM invoice_trn_details where invoice_id=? and invoice_number=?");
        mysqli_stmt_bind_param($sql, "is", $invoice_id, $invoice_number);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0){
                while($row = mysqli_fetch_array($get_result)){

                    if($type == "discount") {
                        $discount[] = $row['discount'];
                    } else {
                        $product_id = $row['product_id'];?>
                            <tr class="service">
                                <td class="tableitem">
                                    <p class="itemtext">
                                        <?php echo get_payable_items_data_by_id($row['product_id'], "name") ;?>
                                        <?php
                                            // if( get_company_data("show_invoice_item_addtional_text") === "yes" && 
                                                // !empty(str_replace(array(" ", "-"), array("", ""), $row['additional_text'] ) ) ) { ?>
                                                <!-- <br/> <?php // echo $row['additional_text'];?> -->
                                        <?php // } ?>
                                    </p>
                                </td>
                                <td class="tableitem">
                                    <p class="itemtext">₦<?php echo custom_money_format($row['amount']);?></p>
                                </td>
                            </tr>
                        <?php
                    }
                }

                if($type == "discount") {
                    return array_sum(@$discount);
                }
            } else {
                return "not_found";
            }
        } else {
            return "error";
        }

    }


    function calc_pos($invoice_id, $invoice_number, $type) {

        global $conn, $product_id;

        $sql = mysqli_prepare($conn, "SELECT * FROM invoice_trn_details where invoice_id=? and invoice_number=? and transaction_type=?");
        mysqli_stmt_bind_param($sql, "iss", $invoice_id, $invoice_number, $type);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0){

                while($row = mysqli_fetch_array($get_result)){
                    $data[] = $row['qty'];
                }
                
                return array_sum($data);

            }
            
        }               
    }

    function solve_for_quanties($product_id, $total_units, $units_per_carton, $data){
        global $conn;

        $m2 = (int) ($total_units / $units_per_carton); // CONVERT TOTAL UNITS TO WHOLESALE
        $wholesale = $m2; // TOTAL WHOLESALE REMAINING 
        
        return @$$data;
    }

    function ModalScriptAlert($js_trigger_msg, $time){?>
    
        <script>
            $("#edit_split_invoice_payment").modal('show');
            document.getElementById('error_balance_account_trigger').setAttribute("class", "alert alert-danger");
            $('#error_balance_account_trigger').fadeIn("slow").html("<?php echo $js_trigger_msg;?>").delay('<?php echo $time;?>').fadeOut('slow');
        </script>

    <?php
    }

    function generate_invoice_number($group_num = 4, $pair_num = 2){
        $letters = '0982176345';
        $key = '';
        for($i=0; $i <= $group_num; $i++){
            $key .= substr($letters, rand(0, (strlen($letters) - $pair_num)) , $pair_num). '';
        }
        $key[strlen($key)-1] = ' ';
        return trim($key);
    }

    function generate_numbers($group_num = 4, $pair_num = 2){
        $letters = '0982176345';
        $key = '';
        for($i=0; $i <= $group_num; $i++){
            $key .= substr($letters, rand(0, (strlen($letters) - $pair_num)) , $pair_num). '';
        }
        $key[strlen($key)-1] = ' ';
        return trim($key);
    }

    function generate_alpha_numeric($group_num = 4, $pair_num = 2){
        $letters = 'AB9WNCS8LD2XERTM1FVY7GQ6H3IU4PZJO5K';
        $key = '';
        for($i=0; $i <= $group_num; $i++){
            $key .= substr($letters, rand(0, (strlen($letters) - $pair_num)) , $pair_num). '';
        }
        $key[strlen($key)-1] = ' ';
        return trim($key);
    }

    function get_total_sales($from_date, $to_date, $data, $type, $transaction_type){
        global $conn;

        if(isset($_POST['from']) && !empty($_POST['from']) && isset($_POST['to']) && !empty($_POST['to'])){
            
            $from_date = date("Y-m-d", strtotime($_POST['from']));
            $to_date = date("Y-m-d", strtotime($_POST['to']));

        } else if(isset($_GET['from']) && !empty($_GET['from'])) {

            $from_date = date("Y-m-d", strtotime($_GET['from']));
            $to_date = date("Y-m-d", strtotime($_GET['from']));

        }

        $id = "";
        if($transaction_type == ""){
            
            if($type == "total_sales"){
                $fetch = mysqli_prepare($conn, "SELECT $data, qty FROM invoice_trn_details WHERE not id=? and (date_created BETWEEN '$from_date' and '$to_date')");
            } else if($type == "net_sales" || $type == "quantity"){
                $fetch = mysqli_prepare($conn, "SELECT sum($data) FROM invoice_trn_details WHERE not id=? and (date_created BETWEEN '$from_date' and '$to_date')");
            }

        } else {
            if($type == "total_sales"){
                $fetch = mysqli_prepare($conn, "SELECT $data, qty FROM invoice_trn_details WHERE not id=? and (date_created BETWEEN '$from_date' and '$to_date') and transaction_type='$transaction_type'");
                
            } else if($type == "net_sales" || $type == "quantity"){
                $fetch = mysqli_prepare($conn, "SELECT sum($data) FROM invoice_trn_details WHERE not id=? and (date_created BETWEEN '$from_date' and '$to_date') and transaction_type='$transaction_type'");
            }    
        }
                
        mysqli_stmt_bind_param($fetch, "s", $id);
        
        if(mysqli_stmt_execute($fetch)){
            $get_result = mysqli_stmt_get_result($fetch);
            if(mysqli_num_rows($get_result) > 0){
                if($type == "total_sales"){
                    while($row = mysqli_fetch_array($get_result)){
                        $data2[] = $row[$data] * $row['qty'];
                    }
                    if(@array_sum($data2) == ""){
                        return "0.00";
                    } else {
                        return @array_sum($data2);
                    }        
                } else if($type == "net_sales"){
                    
                    $row = mysqli_fetch_array($get_result);
                    return $row["sum($data)"];

                } else if($type == "quantity"){
                    $row = mysqli_fetch_array($get_result);
                    return $row["sum($data)"];
                }
            } else {
                return "0";
            }
        } else {
            return "error";
        }
    }

    function get_total_invoices($from_date, $to_date){
        global $conn;

        if(isset($_POST['from']) && !empty($_POST['from']) && isset($_POST['to']) && !empty($_POST['to'])){
            $from_date = date("Y-m-d", strtotime($_POST['from']));
            $to_date = date("Y-m-d", strtotime($_POST['to']));
        }

        $id = "";
        $fetch = mysqli_prepare($conn, "SELECT count(invoice_number) FROM invoice WHERE not id=? and (date_created BETWEEN '$from_date' and '$to_date')");
        mysqli_stmt_bind_param($fetch, "s", $id);
        if(mysqli_stmt_execute($fetch)){
            $get_result = mysqli_stmt_get_result($fetch);
            if(mysqli_num_rows($get_result) > 0){
                $row = mysqli_fetch_array($get_result);
                return $row['count(invoice_number)'];
            } else {
                return "0";
            }
        } else {
            return "error";
        }
    }


    function get_user_data($account_id, $data){
        global $conn;

        $account_id = mysqli_real_escape_string($conn, $account_id);
        
        $sql = mysqli_prepare($conn, "SELECT  $data FROM user_accounts where acc_id=? or email=? or url_id=?");
        mysqli_stmt_bind_param($sql, 'sss', $account_id, $account_id, $account_id);
        mysqli_stmt_execute($sql);
        $get_data = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_data) == 1){
            $row = mysqli_fetch_array($get_data);
            return $row[$data];
        } else {
            return "not_found";
        }
    }


    function get_invoice_list($limit){
        global $conn, $user_account_type;

        $id = "";

        if(isset($_POST['from']) && isset($_POST['to'])) {

            $from_date = date("Y-m-d", strtotime($_POST['from']));
            $to_date = date("Y-m-d", strtotime($_POST['to']));

            $sql = mysqli_prepare($conn, "SELECT * FROM invoice WHERE not id=? and (date_created BETWEEN '$from_date' and '$to_date') ORDER BY id DESC");

        } else {
            if($limit > 0){
                $sql = mysqli_prepare($conn, "SELECT * FROM invoice WHERE not id=? ORDER BY id DESC LIMIT $limit");
            } else {
                $from_date = date("Y-m-d");
                $to_date = date("Y-m-d");
                $sql = mysqli_prepare($conn, "SELECT * FROM invoice WHERE not id=? and (date_created BETWEEN '$from_date' and '$to_date') ORDER BY id DESC");
            }
        }

        mysqli_stmt_bind_param($sql, "s", $id);

        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0){
                while($row = mysqli_fetch_array($get_result)){

                    $invoice_number = $row['invoice_number'];
                    $customer_name = get_customer_data2(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "surname")." ".get_customer_data2(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "firstname")." ".get_customer_data2(get_invoice_data_by_number($row['invoice_number'], "customer_name"), "othername");?>

                    <tr id="invoice_id_<?php echo $row['id'];?>">
                        <form id="invoice_list_row_<?php echo $row['id'];?>">
                            <td>
                                <a target='_blank' href='./view-receipt?id=<?php echo $row['url_id'];?>'>
                                    <b>
                                        <u>
                                            <?php echo $row['invoice_number'];?>
                                            <span class="fa fa-external-link"></span>
                                        </u>
                                        <!-- - <span class='fa fa-eye'></span> <br/> Receipt No: <?php echo $row['id'];?> -->
                                    </b>
                                </a>
                            </td>
                            <td>
                                <?php $data1 = strtoupper(get_invoice_data_by_number($row['invoice_number'], "customer_name"));?>
                                <?php
                                    if(!in_array(get_customer_data(get_invoice_data_by_number($invoice_number, "customer_name"), "customer_id"), array("error", "not_found")) ) {
                                        $customer_id = get_customer_data2(get_invoice_data_by_number($invoice_number, "customer_name"), "customer_id");
                                        $customer_name = get_customer_data2(get_invoice_data_by_number($invoice_number, "customer_name"), "surname")." ".get_customer_data2(get_invoice_data_by_number($invoice_number, "customer_name"), "firstname")." ".get_customer_data2(get_invoice_data_by_number($invoice_number, "customer_name"), "othername");
                                        echo " $customer_id ($customer_name) ";
                                    } else {
                                        echo " $data1";
                                    }
                                ?> 
                                <br/>
                            </td>

                            <td>

                                <?php
                                    $total_invoice_value = get_invoice_data($row['url_id'], "total_paid");
                                    $total_discount = get_invoice_transactions2($row['id'], $row['invoice_number'], "discount");
                                    $total_credit_sales = (total_split_payments_invoice_id(4, $row['id']) + customers_by_payment_method_and_invoice(4, $row['id']));
                                    $total_balance_paid = ($total_invoice_value - $total_credit_sales);
                                ?>

                                <b id="amount_id_<?php echo $row['id'];?>" onclick="get_receipt_payment_transaction('<?php echo $row['id'];?>','<?php echo $row['invoice_number'];?>');" style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#receipt-payment-transaction"><u>₦<?php echo custom_money_format($row['total_paid']);?> <span class="fa fa-external-link"></span> </u></b>

                                <!-- <?php // if($total_discount > 0) { $total_discount = custom_money_format($total_discount); echo "<div><b>Discount: ₦$total_discount"; } ?></b></div>  -->
                                <!-- <?php // if($total_credit_sales > 0) { $total_credit_sales = custom_money_format($total_credit_sales); echo "<div><b>Credit: ₦$total_credit_sales"; } ?></b></div> -->

                            </td>
                            <!-- <td><?php // echo strtoupper(get_user_data($row['created_by'], 'firstname'). ' ' . get_user_data($row['created_by'], 'surname'));?></td> -->
                            <td><?php echo date('Y/m/d', strtotime($row['date_created']));?> <i><?php if(date('Y-m-d', strtotime($row['date_created'])) == date('Y-m-d', strtotime('yesterday')) ) { echo '(Yesterday)';} else if(date('Y-m-d', strtotime($row['date_created'])) == date('Y-m-d', strtotime('today')) ) { echo '(Today)';} ?></i> </td>

                            <td>
                                <div>
                                    <?php if(is_authorized($user_account_type, "print-receipt", "", "") === "allowed") {?>
                                        <a target="_blank" href='./view-receipt?id=<?php echo $row['url_id'];?>' class='ml-2 btn btn-sm btn-secondary' title="Print Invoice" data-toggle="tooltip">
                                            <i class='fa fa-print'></i>
                                        </a>
                                    <?php } ?>

                                    <?php if(is_authorized($user_account_type, "edit-receipt", "", "") === "allowed") {?>
                                        <a href='./edit-receipt?id=<?php echo $row['url_id'];?>' class='btn btn-primary btn-sm ml-2'>
                                            <i class='fa fa-edit'></i>
                                        </a>
                                    <?php } ?>

                                    <?php if(is_authorized($user_account_type, "delete-receipt", "", "") === "allowed") {?>
                                        <input type="hidden" name="invoice_number" value="<?php echo $row['invoice_number'];?>">
                                        <input type="hidden" name="invoice_id" value="<?php echo $row['id'];?>">
                                        <button type="button" href="javascript:(void)"  title="Delete Invoice" data-toggle="tooltip" onclick="deleteRow_<?php echo $row['id'];?>()" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    <?php } ?>
                                </div>
                            </td>
                        </form>
                    </tr>

                    <?php if(is_authorized($user_account_type, "ft-delete-invoice", "", "") === "allowed") {?>
                        <script>
                            function deleteRow_<?php echo $row['id'];?>(){
                                if(confirm("Attention! This action will affect all reports in the system and cannot be undone.\n Are you sure to delete this invoice?\n ")){
                                    var dataString = $("#invoice_list_row_<?php echo $row['id'];?>").serialize();
                                    $.ajax({
                                        type: "POST",
                                        url: "./includes/ajax/delete-invoice",
                                        data: dataString,
                                        cache: false,
                                        beforeSend: function() {},
                                        success: function(d) {
                                            $('div#return_server_msg').fadeIn('slow').html(d);
                                        },
                                        error: function(d) {
                                            toastr.error("Something went wrong!");
                                        }
                                    });
                                    return false;
                                } else {
                                    alert("Good :) ! You\'ve taken the right decision.");
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


    function get_payment_methods_by_id_by_user($userid, $type, $method, $from_date, $to_date){ 
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT sum(total_paid) FROM invoice WHERE created_by=? and method_id=? and (date_created BETWEEN '$from_date' and '$to_date')");
        mysqli_stmt_bind_param($sql, "ii", $userid, $type);
        mysqli_stmt_execute($sql);

        $get_result = mysqli_stmt_get_result($sql);

        while($row2 = mysqli_fetch_array($get_result)){
            $data2[] = $row2["sum(total_paid)"];
        }

        if(@array_sum($data2) == ""){
            return "0.00";
        } else {
            return @array_sum($data2);
        }
    }

    function get_total_invoices_by_user($userid, $from_date, $to_date){
        global $conn;

        if(isset($_POST['from']) && !empty($_POST['from']) && isset($_POST['to']) && !empty($_POST['to'])){
            $from_date = date("Y-m-d", strtotime($_POST['from']));
            $to_date = date("Y-m-d", strtotime($_POST['to']));
        }

        $fetch = mysqli_prepare($conn, "SELECT count(invoice_number) FROM invoice WHERE created_by=? and (date_created BETWEEN '$from_date' and '$to_date')");
        mysqli_stmt_bind_param($fetch, "i", $userid);
        if(mysqli_stmt_execute($fetch)){
            $get_result = mysqli_stmt_get_result($fetch);
            if(mysqli_num_rows($get_result) > 0){
                $row = mysqli_fetch_array($get_result);
                return $row['count(invoice_number)'];
            } else {
                return "0";
            }
        } else {
            return "error";
        }
    }

    
    function total_split_payments_by_user($userid, $type, $from_date, $to_date){ 
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM invoice WHERE created_by=? and method_id='0' and (date_created BETWEEN '$from_date' and '$to_date')");
        mysqli_stmt_bind_param($sql, "i", $userid);
        mysqli_stmt_execute($sql);
        $get_result = mysqli_stmt_get_result($sql);
        
        while($row2 = mysqli_fetch_array($get_result)){
            // $data2[] = $row2["sum(total_paid)"];

            $invoice_id = $row2['id'];
            $invoice_number = $row2['invoice_number'];
            $invoice_date = $row2['date_created'];

            $sql3 = mysqli_prepare($conn, "SELECT sum(amount) FROM splitted_payments WHERE invoice_id=? and invoice_number=? and method_id=? and date_added=?");
            mysqli_stmt_bind_param($sql3, "isis", $invoice_id, $invoice_number, $type, $invoice_date);
            mysqli_stmt_execute($sql3);

            $get_result3 = mysqli_stmt_get_result($sql3);

            while($row3 = mysqli_fetch_array($get_result3)){
                $data3[] = $row3["sum(amount)"]; 
            }
        }

        if(@array_sum($data3) == ""){
            return "0.00";
        } else {
            return @array_sum($data3);
        }

    }

    function generate_unique_report() {
        global $conn;

        $account_type = "3";
        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d");

        if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['from']) && !empty($_POST['from'])){
            $to_date = date("Y-m-d", strtotime(@$_POST['to']));
            $from_date = date("Y-m-d", strtotime($_POST['from']));
        } else if($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['from']) && !empty($_GET['from']) && isset($_GET['to']) && !empty($_GET['to'])){
            $to_date = date("Y-m-d", strtotime(@$_GET['to']));
            $from_date = date("Y-m-d", strtotime($_GET['from']));
        }

        $id = "-";
        $sql = mysqli_prepare($conn, "SELECT * FROM user_accounts where not id=? ORDER BY surname ASC");
        mysqli_stmt_bind_param($sql, "s", $id);
        if(mysqli_stmt_execute($sql)) {
            $get_result = mysqli_stmt_get_result($sql);
            $n = "";
            
            if(mysqli_num_rows($get_result) > 0) {
                while($row = mysqli_fetch_array($get_result)) {
                
                    $total_invoice = (get_total_invoices_by_user($row['acc_id'], $from_date, $to_date));

                    $total_bank = (get_payment_methods_by_id_by_user($row['acc_id'], "3", "", $from_date, $to_date) + total_split_payments_by_user($row['acc_id'], "3", $from_date, $to_date));
                    $total_pos = (get_payment_methods_by_id_by_user($row['acc_id'], "2", "", $from_date, $to_date) + total_split_payments_by_user($row['acc_id'], "2", $from_date, $to_date));
                    $total_cash = (get_payment_methods_by_id_by_user($row['acc_id'], "1", "", $from_date, $to_date) + total_split_payments_by_user($row['acc_id'], "1", $from_date, $to_date));
                    $total_credit = (get_payment_methods_by_id_by_user($row['acc_id'], "4", "", $from_date, $to_date) + total_split_payments_by_user($row['acc_id'], "4", $from_date, $to_date));
                    $total_discount = get_total_discounts_by_user($row['acc_id'], $from_date, $to_date);
                    
                    $total_sales = ($total_bank + $total_pos + $total_cash + $total_discount);

                    $total_net_sales = ($total_sales - ($total_discount + $total_credit) );

                    // ARRAY DATA FOR GRAND TOTALING
                    $grand_total_invoice[] = $total_invoice;
                    $grand_total_bank[] = $total_bank;
                    $grand_total_pos[] = $total_pos;
                    $grand_total_cash[] = $total_cash;
                    $grand_total_sales[] = $total_sales;
                    $grand_total_credit[] = $total_credit;
                    $grand_total_discount[] = $total_discount;
                    $grand_total_net_sales[] = $total_net_sales; 
    
                    if($total_invoice > 0 || $total_discount > 0) {
                        $n++;
                        $grand_total_found = $n;?>
                            <tr>
                                <td> <?php echo $n;?>. </td>
                                
                                <!-- USER'S NAME -->
                                <td> <?php echo $row['surname']." ".$row['firstname']." ".$row['othername'];?> (<?php echo strtoupper(substr($row['gender'], 0, 1));?>) - <b><?php echo account_role_name($row['acc_type'], "role_name");?></b></td>

                                <!-- TOTAL INVOICE -->
                                <td align="right"> <?php echo number_format($total_invoice);?></td>
                                
                                <!-- TOTAL BANK -->
                                <td align="right"> <?php echo custom_money_format(($total_bank));?></td>

                                <!-- TOTAL POS -->
                                <td align="right"> <?php echo custom_money_format(($total_pos));?></td>

                                <!-- TOTAL CASH -->
                                <td align="right"> <?php echo custom_money_format(($total_cash));?></td>
                                
                                <!-- TOTAL CREDIT -->
                                <td align="right"> <?php echo custom_money_format(($total_credit));?></td>

                                <!-- TOTAL SALES -->
                                <td align="right"> <?php echo custom_money_format(($total_sales));?></td>

                                <!-- DISCOUNT -->
                                <td align="right"> <?php echo custom_money_format($total_discount);?></td>

                                <!-- NET SALES -->
                                <td align="right"> <?php echo custom_money_format(($total_net_sales));?></td>
                                
                            </tr>
                        <?php
                    } else {
                    }
                }
                ?>
                    <tr>
                        <td><b>GRAND TOTAL</b></td>
                        <td align="right"><b><?php echo number_format((@$grand_total_found));?></b></td>
                        <td align="right"><?php echo array_sum($grand_total_invoice);?></td>
                        <td align="right">₦<?php echo custom_money_format(array_sum($grand_total_bank));?></td>
                        <td align="right">₦<?php echo custom_money_format(array_sum($grand_total_pos));?></td>
                        <td align="right">₦<?php echo custom_money_format(array_sum($grand_total_cash));?></td>
                        <td align="right">₦<?php echo custom_money_format(array_sum($grand_total_credit));?></td>
                        <td align="right">₦<?php echo custom_money_format(array_sum($grand_total_sales));?></td>
                        <td align="right">₦<?php echo custom_money_format(array_sum($grand_total_discount));?></td>
                        <td align="right">₦<?php echo custom_money_format(array_sum($grand_total_net_sales));?></td>
                    </tr>

                <?php
            }
        } else {

        }
    }

    function custom_money_format($number,  $decimals = 9) {
        $decimals = 9;
        return rtrim(rtrim(number_format($number, $decimals), '0'), '.');
    }


    function get_max_timing_data($type, $target, $data) {
        global $conn, $account_id;

        if($type == "list"){
            $id = "";
            $sql = mysqli_prepare($conn, "SELECT DISTINCT(`name`), id FROM max_timing where not id=? ORDER BY name ASC");
            mysqli_stmt_bind_param($sql, "s", $id);
            if(mysqli_stmt_execute($sql)){
                $get_result = mysqli_stmt_get_result($sql);
                while($row = mysqli_fetch_array($get_result)){?>
                    <option value="<?php echo $row['id'];?>" <?php if($row['id'] == $target) { echo "selected";} ?> >
                        <?php if($row['name'] == 0) {?>
                            Never
                        <?php } else if($row['name'] == 1 ){ ?>
                            <?php echo strtoupper($row['name']);?> minute
                        <?php } else { ?>
                            <?php echo strtoupper($row['name']);?> minutes
                        <?php } ?>
                    </option>
                <?php
                }
            } else {
                echo "Something went wrong!";
            }
        } else {

            $sql = mysqli_prepare($conn, "SELECT `name` FROM max_timing where id=?");
            mysqli_stmt_bind_param($sql, "i", $target);
            if(mysqli_stmt_execute($sql)){
                $get_result = mysqli_stmt_get_result($sql);
                if(mysqli_num_rows($get_result) == 1){
                    $row = mysqli_fetch_array($get_result);
                    return $row[$data];
                } else {
                    return "not_found";
                }
            } else {
                return "error";
            }
        }
    }


    function is_inactivity_checker_activated() {
        global $account_id;

        $maximum_time_id = get_user_data($account_id, 'signout_inactivity_after');
        if( get_max_timing_data("", $maximum_time_id, "name") != "not_found" && get_max_timing_data("", $maximum_time_id, "name") > 0 ) { 
            return true;
        } else {
            return false;
        }
    }

    function get_customer_data($customer_id, $data) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM customers WHERE customer_id=? OR id=?");
        mysqli_stmt_bind_param($sql, "si", $customer_id, $customer_id);
        if(!mysqli_stmt_execute($sql)) {
            return "error";
        }

        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result)==1) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    function get_customer_data2($customer_id, $data) {
        if( ! in_array(get_customer_data($customer_id, $data), array("error", "not_found")) ) {
            return get_customer_data($customer_id, $data);
        } else if(get_customer_data($customer_id, $data) == "not_found") {
            return $customer_id;
        } else {
            return "error";
        }
    }

    function get_customer_list($type, $target) {
        global $conn;

        $customer_id = "";
        $sql = mysqli_prepare($conn, "SELECT * FROM customers WHERE NOT customer_id=?");
        mysqli_stmt_bind_param($sql, "s", $customer_id);
        if(!mysqli_stmt_execute($sql)) {
            return "error";
        }

        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result)>0) {
            while($row = mysqli_fetch_array($get_result)) { ?>
                <option <?php if($target == $row['customer_id'] || $target == $row['id']) { echo "selected"; }?> value="<?php echo $row['customer_id'];?>"><?php echo $row['customer_id']. " " . " (".$row['surname']. " ". $row['firstname']. " ". $row['othername'].")";?></option>
            <?php
            }
        }
    }


    function get_customers($type){
        global $conn, $account_id, $account_type;

        if(in_array($type, array("list", "count", "table") ) ) {
            $id = "";
            $sql = mysqli_prepare($conn, "SELECT * FROM customers WHERE not id=?");
            mysqli_stmt_bind_param($sql, "s", $id);
            if(mysqli_stmt_execute($sql)){
                $get_result = mysqli_stmt_get_result($sql);

                if($type == "count"){
                    return mysqli_num_rows($get_result);
                } else if($type == "table") {
                    if(mysqli_num_rows($get_result) > 0){
                        while($row = mysqli_fetch_array($get_result)){@$n++;
                        $profile_url = "./edit-payment-account?id=".$row['customer_id'];?>

                        <tr>
                            <td><?php echo @$n;?></td>
                            <td>
                                <?php echo $row['surname'];?>
                                <br/>
                                (<?php if(get_customer_balance($row['customer_id'], "pending", "sum", "excess_amount") > 0) {?>
                                    <b>BAL: ₦<?php echo custom_money_format(get_customer_balance($row['customer_id'], "pending", "sum", "excess_amount"), 2);?></b>
                                <?php } else {?>
                                    <b>BAL: ₦<?php echo custom_money_format(get_customer_balance($row['customer_id'], "pending", "sum", "excess_amount"), 2);?></b>
                                <?php } ?>) - <?php echo $row['customer_id'];?>
                            </td>
                            <td><?php echo $row['phone'];?></td>
                            <td><?php echo $row['date_created'];?></td>
                            <td>
                                <?php if(is_authorized($account_type, "edit-payment-account", "", "") === "allowed") {?>
                                    <a href="<?php echo $profile_url;?>" data-toggle="tooltip" title="Edit Profile" data-placement="bottom" class="btn btn-sm btn-primary ml-2"><i class="fa fa-edit"></i></a>
                                <?php } ?>
                            </td>
                        </tr>

                        <?php
                        }
                    }
                }
            }
        }
    }

    function get_groups($type){
        global $conn, $account_id, $account_type;

        if(in_array($type, array("list", "count", "table") ) ) {
            $id = "";
            $sql = mysqli_prepare($conn, "SELECT * FROM customers WHERE not id=?");
            mysqli_stmt_bind_param($sql, "s", $id);
            if(mysqli_stmt_execute($sql)){
                $get_result = mysqli_stmt_get_result($sql);

                if($type == "count"){
                    return mysqli_num_rows($get_result);
                } else if($type == "list"){

                    if(mysqli_num_rows($get_result) > 0){

                        while($row = mysqli_fetch_array($get_result)){

                            $profile_url = "./edit-group?id=".$row['group_id'];
                            $profile_url2 = "./edit-group?p=customers&id=".$row['group_id'];?>

                            <li class="contact  py-3 px-2 col-md-4">
                                <div class="media d-flex w-100">
                                    <?php if(is_authorized($account_type, "pg-edit-customer", "", "") === "allowed" ) { ?>
                                        <a href="<?php echo $profile_url;?>">
                                            <img src="./dist/images/profile/no-image.jpg" alt="<?php echo $row['group_id']." ".$row['name'];?>" class="img-fluid ml-0 mt-2 ml-md-2 rounded-circle" width="40">
                                        </a>
                                    <?php } else { ?>
                                        <img src="./dist/images/profile/no-image.jpg" alt="<?php echo $row['group_id']." ".$row['name'];?>" class="img-fluid ml-0 mt-2 ml-md-2 rounded-circle" width="40">
                                    <?php } ?>

                                    <div class="media-body align-self-center pl-2">
                                        <b class="mb-0">

                                            <?php if(is_authorized($account_type, "pg-edit-customer", "", "") === "allowed" ) { ?>
                                                <a href="<?php echo $profile_url;?>"> <?php echo $row['name'];?> </a>
                                            <?php } else { ?>
                                                <?php echo $row['name'];?>
                                            <?php } ?>

                                        </b><br/>
                                        <b>GROUP ID: </b> <?php echo $row['group_id'];?>
                                        <br/>

                                        <p class="mb-0 text-muted">
                                            <i class="fa fa-calendar-alt pr-2"></i> <?php echo date("Y-m-d", strtotime($row['date_created']));?>
                                        </p>

                                    </div>

                                    <div class="ml-auto mail-tools">
                                        <?php if(is_authorized($account_type, "pg-edit-customer", "", "") === "allowed" ) { ?>
                                            <a href="<?php echo $profile_url2;?>" data-toggle="tooltip" title="Customers" data-placement="bottom" class="text-primary ml-2"><i class="fa fa-users"></i></a>
                                            <a href="<?php echo $profile_url;?>" data-toggle="tooltip" title="Edit Group" data-placement="bottom" class="text-primary ml-2"><i class="icon-pencil"></i></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </li>

                        <?php
                        }
                    }

                } else if($type == "table") {

                    if(mysqli_num_rows($get_result) > 0){
                        while($row = mysqli_fetch_array($get_result)){@$n++;
                        $profile_url = "./edit-customer-account?id=".$row['group_id'];?>

                        <tr>
                            <td><?php echo @$n;?></td>
                            <td><?php echo $row['group_id'];?></td>
                            <td><?php echo $row['surname']." ". $row['firstname']." ". $row['othername'];?></td>
                            <td><?php echo $row['phone'];?></td>
                            <td><?php echo $row['address'];?></td>
                            <td>
                                <?php if(is_authorized($account_type, "pg-edit-customer", "", "") === "allowed" ) { ?>
                                    <a href="<?php echo $profile_url;?>" data-toggle="tooltip" title="Edit Profile" data-placement="bottom" class="text-primary ml-2"><i class="icon-pencil"></i></a>
                                <?php } ?>
                            </td>
                        </tr>

                        <?php
                        }
                    }

                }
            }
        }
    }

    function get_group_data($group_id, $data) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM customer_groups WHERE group_id=? OR id=?");
        mysqli_stmt_bind_param($sql, "si", $group_id, $group_id);
        if(!mysqli_stmt_execute($sql)) {
            return "error";
        }

        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result)==1) {
            $row = mysqli_fetch_array($get_result);
            return $row[$data];
        } else {
            return "not_found";
        }
    }

    function get_group_data2($group_id, $data) {
        if( ! in_array(get_group_data($group_id, $data), array("error", "not_found")) ) {
            return get_group_data($group_id, $data);
        } else if(get_group_data($group_id, $data) == "not_found") {
            return $customer_id;
        } else {
            return "error";
        }
    }

    //  STATMENT
    function generate_sales_by_customers($from, $to) {
        global $conn;

        $id = "";
        $sql = mysqli_prepare($conn, "SELECT * FROM customers where not id=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        if(!mysqli_stmt_execute($sql)) {
            return "error";
        }

        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0) {
            while($row = mysqli_fetch_array($get_result)) {
                $amount = calculate_invoice_by_customer($row['customer_id'], $from, $to, "sum", "total_paid");
                $invoice_total = calculate_invoice_by_customer($row['customer_id'], $from, $to, "", "count");

                if($amount != 0 && $invoice_total != 0) {
                    @$n++?>
                        <tr>
                            <td><?php echo @$n;?></td>
                            <td> <b><?php echo $row['customer_id'];?> </b> </td>
                            <td> <?php echo $row['surname']." ".$row['firstname']. " ".$row['othername'];?> </td>
                            <td> <?php echo number_format(calculate_invoice_by_customer($row['customer_id'], $from, $to, "", "count"));?> </td>
                            <td> <?php echo custom_money_format(calculate_invoice_by_customer($row['customer_id'], $from, $to, "sum", "total_paid"));?> </td>
                        </tr>
                        <?php
                    }
                ?>
            <?php
            }
        }
    }

    // STATEMENT 
    function calculate_invoice_by_customer($customer_id, $from, $to, $type, $operation) {
        global $conn;

        $id = "";

        $from = date("Y-m-d", strtotime($from));
        $to = date("Y-m-d", strtotime($to));

        if($operation == "count") {
            $sql = mysqli_prepare($conn, "SELECT $operation(id) FROM invoice where customer_name=? and date_created BETWEEN '$from' and '$to'");
        } else {
            $sql = mysqli_prepare($conn, "SELECT $type($operation) FROM invoice where customer_name=? and date_created BETWEEN '$from' and '$to'");
        }

        mysqli_stmt_bind_param($sql, "s", $customer_id);
        if(!mysqli_stmt_execute($sql)) {
            return "error";
        }

        $get_result = mysqli_stmt_get_result($sql);
        if(mysqli_num_rows($get_result) > 0) {
            
            $row = mysqli_fetch_array($get_result);
            if($operation == "count") {
                return $row["$operation(id)"];
            } else {
                return $row["$type($operation)"];
            }
            
        }
    }

    function auto_encode_discount_pattern($string) {
        $string = base64_encode("|/?*".$string."\-?/|*");
        return  $string;
        // return str_replace(array("?","*","|","/","-","\\"), array(""), $string);
    }

    function auto_decode_discount_pattern($string) {
        $string = base64_decode($string);
        return str_replace(array("?","*","|","/","-","\\"), array(""), $string);
    }

?>