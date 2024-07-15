<?php
    function _is_manager_logged_in() {
        if(isset($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W']) && 
        isset($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_real_user_id']) &&
        isset($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_account_type']) &&
        !empty($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_account_type']) &&
        // isset($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_continue_url']) &&
        isset($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_dfghjkds_user_email_sdfghjkljgfdssdfghjk']) &&
        !empty($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_dfghjkds_user_email_sdfghjkljgfdssdfghjk']) 
        && in_array($_SESSION['churchapp_iwt07wu44Aq9qQ9tQR8v3lips7Li9a2IWmbLipydCJMEBjBi9W_account_type'], array("1", "2", "3") ) ) { 
            return true;
        } else {
            return false;
        }
    }

    if(_is_manager_logged_in()) {
        header("location: ../app");
    }
?>