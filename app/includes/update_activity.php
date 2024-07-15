<?php

    function update_activity($user_id){
        global $conn;
        $sql = mysqli_prepare($conn, "UPDATE track_inactivity SET `last_check`=NOW() where `user_id`=?");
        mysqli_stmt_bind_param($sql, "s", $user_id);
        if(mysqli_stmt_execute($sql)){
            // return "success";
        } else {
            // return "error";
        }
    }

    echo update_activity($account_id);

?>