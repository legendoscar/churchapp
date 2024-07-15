<?php
    function _is_manager_logged_in() {
        if(isset($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W']) && 
        isset($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_real_user_id']) &&
        isset($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_account_type']) &&
        !empty($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_account_type']) &&
        isset($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_dfghjkds_user_email_sdfghjkljgfdssdfghjk']) &&
        !empty($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_dfghjkds_user_email_sdfghjkljgfdssdfghjk']) ) {
            return "success";
        } else {
            return "error";
        }
    }


    if(!_is_manager_logged_in()) {

        session_destroy();
        unset($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_dfghjkds_user_email_sdfghjkljgfdssdfghjk']);

        $_SESSION['error_msg'] = "<b>Error!</b> Please login to continue";
        header("location: ./");
    }
?>