<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div class="sidebar-menu">
            <ul>
                <li>
                    <a href="./"><i class="la la-home"></i> <span>Back to Home</span></a>
                </li>

                <li class="menu-title">Settings</li>

                <?php if(is_authorized($account_type, "company-settings", "", "") === "allowed") {?>
                    <li class="<?php if(strpos($_SERVER['PHP_SELF'], "settings-company.php")) { echo "active"; } ?>"> <a href="./settings-company"><i class="la la-building"></i> <span>Company Settings</span></a> </li>
                <?php } ?>

                <?php if(is_authorized($account_type, "privilege-settings", "", "") === "allowed") {?>
                    <li class="<?php if(strpos($_SERVER['PHP_SELF'], "settings-roles-permissions.php")) { echo "active"; } ?>"> <a href="./settings-roles-permissions"><i class="la la-key"></i> <span>Roles & Permissions</span></a> </li>
                <?php } ?>

                <?php if(is_authorized($account_type, "invoice-settings", "", "") === "allowed") {?>
                    <li class="<?php if(strpos($_SERVER['PHP_SELF'], "settings-invoice.php")) { echo "active"; } ?>"> <a href="./settings-invoice"><i class="la la-pencil-square"></i> <span>Invoice Settings</span></a> </li>
                <?php } ?>

                <?php if(is_authorized($account_type, "payment-channels", "", "") === "allowed") {?>
                    <li class="<?php if(strpos($_SERVER['PHP_SELF'], "settings-create-payment-channels-bank.php") || strpos($_SERVER['PHP_SELF'], "settings-edit-payment-channels-bank.php") || strpos($_SERVER['PHP_SELF'], "settings-payment-channels-bank.php")) { echo "active"; } ?>"> <a href="./settings-payment-channels-bank"><i class="la la-building"></i> <span>Bank Acct. </span></a> </li>
                    <li class="<?php if(strpos($_SERVER['PHP_SELF'], "settings-create-payment-channels-pos.php") || strpos($_SERVER['PHP_SELF'], "settings-edit-payment-channels-pos.php") || strpos($_SERVER['PHP_SELF'], "settings-payment-channels-pos.php")) { echo "active"; } ?>"> <a href="./settings-payment-channels-pos"><i class="la la-calculator"></i> <span>POS</span></a> </li>
                    <li class="<?php if(strpos($_SERVER['PHP_SELF'], "settings-create-payment-bank.php") || strpos($_SERVER['PHP_SELF'], "settings-edit-payment-bank.php") || strpos($_SERVER['PHP_SELF'], "settings-payment-bank.php")) { echo "active"; } ?>"> <a href="./settings-payment-bank"><i class="la la-building"></i> <span>Payment Bank</span></a> </li>
                <?php } ?>

                <!-- <li class="<?php if(in_array($_SERVER['PHP_SELF'], array("/church-software/app/settings-localization.php"))) { echo "active"; } ?>"> <a href="./settings-localization"><i class="la la-clock-o"></i> <span>Localization</span></a> </li> -->
                <!-- <li class="<?php if(in_array($_SERVER['PHP_SELF'], array("/church-software/app/settings-theme.php"))) { echo "active"; } ?>"> <a href="./settings-theme"><i class="la la-photo"></i> <span>Theme Settings</span></a> </li> -->
                <!-- <li class="<?php if(in_array($_SERVER['PHP_SELF'], array("/church-software/app/settings-email.php"))) { echo "active"; } ?>"> <a href="./settings-email"><i class="la la-at"></i> <span>Email Settings</span></a> </li> -->
                <!-- <li class="<?php if(in_array($_SERVER['PHP_SELF'], array("/church-software/app/settings-salary.php"))) { echo "active"; } ?>"> <a href="./settings-salary"><i class="la la-money"></i> <span>Salary Settings</span></a> </li> -->
                <!-- <li class="<?php if(in_array($_SERVER['PHP_SELF'], array("/church-software/app/settings-notifications.php"))) { echo "active"; } ?>"> <a href="./settings-notifications"><i class="la la-globe"></i> <span>Notifications</span></a> </li> -->
            </ul>
        </div>
    </div>
</div>