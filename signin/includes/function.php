<?php

    function generate_userid($group_num = 5, $pair_num = 2){
        $letters = '1230987645';
        $key = '';
        for($i=0; $i <= $group_num; $i++){
        $key .= substr($letters, rand(0, (strlen($letters) - $pair_num)), $pair_num).rand(1,0). '';
        }
        $key[strlen($key)-1] = ' ';
        return trim($key);
    }


    function server_response2($type, $msg){?>
        <div class="alert alert-<?php echo $type;?>"><b><?php echo $msg;?></b></div>
    <?php
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

    function server_response($type, $msg, $time){?>
        <script> setTimeout( function(){ toastr.<?php echo $type;?>("<?php echo $msg;?>"); }, <?php echo $time;?>);</script>
    <?php
    }

    function redirect($location, $time){?>
        <script> setTimeout( function(){ window.location.replace("<?php echo $location;?>"); }, <?php echo $time;?>);</script>
    <?php
    }

    function activate_activity($user_id) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM track_inactivity WHERE `user_id`=?");
        mysqli_stmt_bind_param($sql, "s", $user_id);
        if(mysqli_stmt_execute($sql)){

            $get_result = mysqli_stmt_get_result($sql);

            if(mysqli_num_rows($get_result) == 1){

                $sql = mysqli_prepare($conn, "UPDATE track_inactivity SET `last_check`=NOW() where `user_id`=?");
                mysqli_stmt_bind_param($sql, "s", $user_id);
                if(mysqli_stmt_execute($sql)){
                    return "success";
                } else {
                    return "error";
                }

            } else {

                $sql = mysqli_prepare($conn, "INSERT INTO track_inactivity(`user_id`) VALUES(?)");
                mysqli_stmt_bind_param($sql, "s", $user_id);
                if(mysqli_stmt_execute($sql)){
                    return "success";
                } else {
                    return "error";
                }

            }
        } else {

            return "error";

        }

    }

    function verify_code($data, $hash, $reason) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM email_verification WHERE `hashed`=? and reason=?");
        mysqli_stmt_bind_param($sql, "ss", $hash, $reason);
        if(mysqli_stmt_execute($sql)) {
            $get_result = mysqli_stmt_get_result($sql);

            if(mysqli_num_rows($get_result) == 1) {
                $row = mysqli_fetch_array($get_result);
                return $row[$data];
            } else {
                return "not_found";
            }
        }
    }


    function delete_verification($user_id, $reason) {
        global $conn;

        $delete = mysqli_prepare($conn, "DELETE FROM email_verification WHERE `user_id`=? and reason=?");
        mysqli_stmt_bind_param($delete, "ss", $user_id, $reason);
        if(mysqli_stmt_execute($delete)) {
            return "success";
        } else {
            return "not_found";
        }
    }

    function check_account_existence($data, $id) {
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM user_accounts WHERE `email`=? OR url_id=? OR acc_id=?");
        mysqli_stmt_bind_param($sql, "sss", $id, $id, $id);
        if(mysqli_stmt_execute($sql)) {
            $get_result = mysqli_stmt_get_result($sql);

            if(mysqli_num_rows($get_result) == 1) {
                $row = mysqli_fetch_array($get_result);
                return $row[$data];
            } else if(mysqli_num_rows($get_result) > 1) {
                return "error";
            } else {
                return "not_found";
            }
        }
    }

    function setupAccount($verification_id) {
        global $conn;

        $user_id = check_account_existence("acc_id", verify_code("user_id", $verification_id, "verification-for-registration"));

        $status = 1;

        // ACTIVATE USER ACCOUNT (ACTIVE / VERIFIED)
        $sql = mysqli_prepare($conn, "UPDATE user_accounts SET account_status=? WHERE `acc_id`=?");
        mysqli_stmt_bind_param($sql, "ii", $status, $user_id);
        if(mysqli_stmt_execute($sql)) {

            // CREATE USER ACCOUNT BALANCE (DEFAULT: 0.00)
            $sql = mysqli_prepare($conn, "INSERT INTO customer_balance(customer_id) VALUES(?)");
            mysqli_stmt_bind_param($sql, "i", $user_id);
            if(mysqli_stmt_execute($sql)) {

                // CLEAR REGISTRATION TOKEN
                $sql = mysqli_prepare($conn, "DELETE FROM email_verification WHERE `user_id`=?");
                mysqli_stmt_bind_param($sql, "i", $user_id);
                if(mysqli_stmt_execute($sql)) {
                    return "success";
                } else {
                    return "error";
                }
            } else {
                return "error";
            }
        } else {
            return "error";
        }
    }


    function get_email_settings_smtp_security($target){
        global $conn, $system_currency_symbol;

        $id = "deleted";
        $sql = mysqli_prepare($conn, "SELECT * FROM email_settings_smtp_security WHERE not id=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0 ) {
                while($row = mysqli_fetch_array($get_result)){?><option <?php if($target == $row['id']){ echo 'selected'; } ?> value='<?php echo $row['id'];?>'><?php echo $row['name'];?></option><?php }
            }
        } else {
            echo "Something went wrong!";
        }
    }


    function get_email_settings_smtp_port($target){
        global $conn, $system_currency_symbol;

        $id = "deleted";
        $sql = mysqli_prepare($conn, "SELECT * FROM email_settings_smtp_port WHERE not id=?");
        mysqli_stmt_bind_param($sql, "s", $id);
        if(mysqli_stmt_execute($sql)){
            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) > 0 ) {
                while($row = mysqli_fetch_array($get_result)){?><option <?php if($target == $row['id']){ echo 'selected'; } ?> value='<?php echo $row['id'];?>'><?php echo $row['name'];?></option><?php }
            }
        } else {
            echo "Something went wrong!";
        }
    }


    function get_email_settings_data_by_id($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM email_settings WHERE id=?");
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

    function get_email_settings_smtp_port_data_by_id($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM email_settings_smtp_port WHERE id=?");
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


    function get_email_settings_smtp_security_data_by_id($id, $data){
        global $conn;

        $sql = mysqli_prepare($conn, "SELECT * FROM email_settings_smtp_security WHERE id=?");
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
?>