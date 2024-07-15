<?php
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        $string = array( 
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );

        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    function server_response($type, $msg, $time){?>
        <script> 
            setTimeout( function(){ 
                toastr.options = {
                    "tapToDismiss" : false,
                    "debug": true,
                    "newestOnTop": true,
                    "positionClass": "toast-top-right",
                    "closeButton": true,
                    "progressBar": true,
                    "fadeIn" : 100,
                    "fadeOut" : 100,
                    "timeOut" : 6000
                };
                toastr.<?php echo $type;?>("<?php echo $msg;?>"); 
            }, 
            <?php echo $time;?>
            );</script>
    <?php
    }

    function redirect($location, $time){?>
        <script> setTimeout( function(){ window.location.replace("<?php echo $location;?>"); }, <?php echo $time;?>);</script>
    <?php
    }

    function get_payment_methods($id, $type, $data){

        global $conn;
        // $id = "";

        if($type == "list"){
            $method_type = "other_customer_balance";
            $sql = mysqli_prepare($conn, "SELECT * FROM payment_method where not `type`=?");
            mysqli_stmt_bind_param($sql, "s", $method_type);
        } else {
            $sql = mysqli_prepare($conn, "SELECT * FROM payment_method where `id`=?");
            mysqli_stmt_bind_param($sql, "i", $id);
        }

        if(mysqli_stmt_execute($sql)){

            $get_result = mysqli_stmt_get_result($sql);
            if($type == "list"){
                if(mysqli_num_rows($get_result)>0){
                    while($row = mysqli_fetch_array($get_result)){?> <optgroup label='<?php echo $row['methods'];?>'> <?php $method_id= $row['id']; /*if(in_array($row['id'], array("2", "3", "1", "4"))) {*/ $sql2 = mysqli_prepare($conn, "SELECT * FROM bank_accounts WHERE method_id=? and not `status`='deleted'"); mysqli_stmt_bind_param($sql2, "s", $method_id); if(mysqli_stmt_execute($sql2)){ $get_result2 = mysqli_stmt_get_result($sql2); while($row2 = mysqli_fetch_array($get_result2)){?> <option <?php if($data == $row2['id']) {echo "selected"; } ;?>  value='<?php echo $row2['id'];?>'> <?php echo $row2['account_name'];?> <?php if($row2['method_id'] == "3") {?> (<?php echo $row2['bank_name']. " - " . $row2['account_number'];?> ) <?php } ?> </option> <?php } } ?> </optgroup> <?php /*}*/ } } else {?> <option value="">No record found</option> <?php }
            } else {
                if(mysqli_num_rows($get_result) == 1){
                    $row = mysqli_fetch_array($get_result);
                    return $row[$data];
                } else {
                    return "not_found";
                }
            }
        }
    }


    function get_payment_methods_data($method_id, $payment_method, $invoice_id, $invoice_number){

        global $conn;
        $type = "";

        $sql = mysqli_prepare($conn, "SELECT methods, `type`, bank_accounts.id, bank_accounts.account_name, bank_accounts.account_number, bank_accounts.bank_name, bank_accounts.method_id FROM payment_method LEFT JOIN bank_accounts ON bank_accounts.method_id=payment_method.id where payment_method.`id`=? and bank_accounts.id=?");
        mysqli_stmt_bind_param($sql, "ii", $method_id, $payment_method);
        if(mysqli_stmt_execute($sql)){

            $get_result = mysqli_stmt_get_result($sql);
            if(mysqli_num_rows($get_result) == 1){
                $row = mysqli_fetch_array($get_result);
                $append = "";
                if($row['method_id'] == "3") { $append = "(".$row['bank_name']. " - " . $row['account_number'].")"; }
                return $row['account_name']." ".$append;
            } else {
                return "not_found";
            }
        }
    }
?>