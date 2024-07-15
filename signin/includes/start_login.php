<?php

    $firstname = $row['firstname'];
    $surname = $row['surname'];
    $othername = $row['othername'];
    $account_type_name = $row['acc_type'];

    $_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W'] = true;
    $_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_dfghjkds_user_email_sdfghjkljgfdssdfghjk'] = strtolower($d_email);
    $_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_real_user_id'] = $row['acc_id'];
    $_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_account_type'] = $row['acc_type'];

    $db_account_type_name = account_role_name($account_type_name, "id");
    if($account_type_name == $db_account_type_name) {
        if(activate_activity($row['acc_id']) == "success") {
            $_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W'] = $row;
            server_response("success", "Login successful! Please wait...", "100");
            redirect("../app", 500);
        } else {
            server_response("error", "The SignIn Module could not understood request!", "100");
        }
    } else {
        server_response("error", "<b>Error!</b> Sorry, we can't sign you in. Contact Admin", "100");
    }
?>