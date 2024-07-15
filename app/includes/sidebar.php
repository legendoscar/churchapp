<div class="sidebar" id="sidebar">
    <div class="sidebar-inner slimscroll">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <li class="menu-title">
                    <span>Main</span>
                </li>

                <li class="<?php if(strpos($_SERVER['PHP_SELF'], "index.php")) { echo "active"; } ?>">
                    <a href="./"><i class="la la-dashboard"></i> <span> Dashboard</span></a>
                </li>

                <?php if(is_authorized($user_account_type, "create-receipt", "", "") === "allowed" || is_authorized($user_account_type, "receipts", "", "") === "allowed" || is_authorized($user_account_type, "payable-items", "", "") === "allowed" || is_authorized($user_account_type, "payment-accounts", "", "") === "allowed" || is_authorized($user_account_type, "create-payment-account", "", "") === "allowed") {?>
                    <li class="submenu">
                        <a href="#"><i class="la la-file-alt"></i> <span> Receipts</span> <span class="menu-arrow"></span></a>

                        <ul style="display: none;">

                            <?php if(is_authorized($account_type, "create-receipt", "", "") == "allowed"){ ?>
                                <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "new-receipt.php")) { echo "active"; } ?>" href="./new-receipt">New Receipt</a></li>
                            <?php } ?>

                            <?php if(is_authorized($account_type, "receipts", "", "") == "allowed"){ ?>
                                <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "receipts.php") || strpos($_SERVER['PHP_SELF'], "edit-receipt.php")) { echo "active"; } ?>" href="./receipts">All Receipts</a></li>
                            <?php } ?>

                            <?php if(is_authorized($user_account_type, "payable-items", "", "") === "allowed") {?>
                                <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "payable-items.php")) { echo "active"; } ?>" href="./payable-items">Payable Items</a></li>
                            <?php } ?>


                            <?php if(is_authorized($user_account_type, "payment-accounts", "", "") === "allowed" || is_authorized($user_account_type, "create-payment-account", "", "") === "allowed") {?>
                            <li class="submenu">
                                <a class="<?php if(strpos($_SERVER['PHP_SELF'], "/payment-accounts") || strpos($_SERVER['PHP_SELF'], "/new-payment-account") || strpos($_SERVER['PHP_SELF'], "/edit-payment-account")) { echo "active"; } ?>" href="javascript:void(0);"> <span>Users/Accounts</span> <span class="menu-arrow"></span></a>
                                <ul>
                                    <?php if(is_authorized($user_account_type, "create-payment-account", "", "") === "allowed") {?>
                                        <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "/new-payment-account")) { echo "active"; } ?>" href="./new-payment-account"><span>Create Account</span></a></li>
                                    <?php } ?>

                                    <?php if(is_authorized($user_account_type, "payment-accounts", "", "") === "allowed") {?>
                                    <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "/payment-accounts") || strpos($_SERVER['PHP_SELF'], "/edit-payment-account")) { echo "active"; } ?>" href="./payment-accounts"><span>Manage Accounts</span></a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                            <?php } ?>

                        </ul>
                    </li>
                <?php } ?>

                <?php if(is_authorized($user_account_type, "expenses", "", "") === "allowed" || is_authorized($user_account_type, "create-expenses", "", "") === "allowed" || is_authorized($user_account_type, "expenses-item", "", "") === "allowed") {?>
                    <li class="submenu">
                        <a href="#"><i class="la la-money-bill"></i> <span> Expenses</span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">

                            <?php if(is_authorized($user_account_type, "create-expenses", "", "") === "allowed") {?>
                            <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "new-expenses.php")) { echo "active"; } ?>" href="./new-expenses">New Expenses</a></li>
                            <?php } ?>

                            <?php if(is_authorized($user_account_type, "expenses", "", "") === "allowed") {?>
                            <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "/expenses.php") || strpos($_SERVER['PHP_SELF'], "/edit-expenses.php") ) { echo "active"; } ?>" href="./expenses">All Expenses</a></li>
                            <?php } ?>

                            <?php if(is_authorized($user_account_type, "expenses-item", "", "") === "allowed") {?>
                            <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "expenses-items.php")) { echo "active"; } ?>" href="./expenses-items">Expenses Items</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                <?php } ?>

                <li class="<?php if(strpos($_SERVER['PHP_SELF'], "profile.php") || strpos($_SERVER['PHP_SELF'], "edit-profile.php")) { echo "active"; } ?>"> <a href="./profile"><i class="la la-user-tie"></i> <span> My Profile</span></a> </li>


                <?php if(
                    is_authorized($user_account_type, "employees", "", "") === "allowed" ||
                    is_authorized($user_account_type, "create-employee", "", "") === "allowed" ||
                    is_authorized($user_account_type, "departments", "", "") === "allowed" ||
                    is_authorized($user_account_type, "designations", "", "") === "allowed" ||
                    is_authorized($user_account_type, "account-titles", "", "") === "allowed" ||
                    is_authorized($user_account_type, "branches", "", "") === "allowed")
                {?>

                    <li class="submenu">
                        <a href="#" class="noti-dot-"><i class="la la-users"></i> <span> Employees</span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">


                            <?php if( is_authorized($user_account_type, "create-employee", "", "") === "allowed"){?>
                                <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "create-employee.php")) { echo "active"; } ?>" href="./create-employee">Create Employee</a></li>
                            <?php } ?>

                            <?php if( is_authorized($user_account_type, "employees", "", "") === "allowed"){?>
                                <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "employees.php") || strpos($_SERVER['PHP_SELF'], "edit-employee.php") || strpos($_SERVER['PHP_SELF'], "employee-profile.php") ) { echo "active"; } ?>" href="./employees">All Employees</a></li>
                            <?php } ?>

                            <?php if( is_authorized($user_account_type, "departments", "", "") === "allowed"){?>
                            <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "departments.php")) { echo "active"; } ?>" href="./departments">Departments</a></li>
                            <?php } ?>

                            <?php if( is_authorized($user_account_type, "designations", "", "") === "allowed"){?>
                            <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "designations.php")) { echo "active"; } ?>" href="./designations">Designations</a></li>
                            <?php } ?>

                            <?php if( is_authorized($user_account_type, "account-titles", "", "") === "allowed"){?>
                            <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "account-title.php")) { echo "active"; } ?>" href="./account-title">Account Titles</a></li>
                            <?php } ?>

                            <?php if( is_authorized($user_account_type, "account-titles", "", "") === "allowed"){?>
                            <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "branches.php")) { echo "active"; } ?>" href="./branches">Branches</a></li>
                            <?php } ?>

                        </ul>
                    </li>
                <?php } ?>

                <?php if(
                    is_authorized($user_account_type, "payslips", "", "") === "allowed" ||
                    is_authorized($user_account_type, "create-payslip", "", "") === "allowed" ||
                    is_authorized($user_account_type, "payslip-items", "", "") === "allowed") { ?>

                    <li class="submenu">
                        <a href="#"><i class="la la-money"></i> <span> Payroll </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">

                        <?php if(is_authorized($user_account_type, "create-payslip", "", "") === "allowed"){?>
                            <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "create-payslip.php")) { echo "active"; } ?>" href="./create-payslip"> Create Payslip</a></li>
                        <?php } ?>

                        <?php if(is_authorized($user_account_type, "payslips", "", "") === "allowed"){?>
                            <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "payslips.php") || strpos($_SERVER['PHP_SELF'], "edit-payslip.php")) { echo "active"; } ?>" href="./payslips"> Payslips </a></li>
                        <?php } ?>

                        <?php if(is_authorized($user_account_type, "payslip-items", "", "") === "allowed"){?>
                            <li class="submenu">
                                <a class="<?php if(strpos($_SERVER['PHP_SELF'], "/payslip-items-earnings") || strpos($_SERVER['PHP_SELF'], "/payslip-items-deductions")) { echo "active"; } ?>" href="javascript:void(0);"> <span>Payslip Items</span> <span class="menu-arrow"></span></a>
                                <ul>
                                    <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "/payslip-items-earnings")) { echo "active"; } ?>" href="./payslip-items-earnings"><span>Earnings</span></a></li>
                                    <li> <a href="./payslip-items-deductions"> <span>Deductions</span></a> </li>
                                </ul>
                            </li>
                        <?php } ?>

                        </ul>
                    </li>

                <?php } ?>

                <?php if(
                    is_authorized($user_account_type, "payment-report", "", "") === "allowed" ||
                    is_authorized($user_account_type, "transaction-report", "", "") === "allowed" ||
                    is_authorized($user_account_type, "expenses-report", "", "") === "allowed") { ?>
                    <li class="submenu">
                        <a href="#"><i class="la la-pie-chart"></i> <span> Reports </span> <span class="menu-arrow"></span></a>
                        <ul style="display: none;">
                            <?php if(is_authorized($user_account_type, "payment-report", "", "") === "allowed"){?>
                                <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "payment-reports.php")) { echo "active"; } ?>" href="./payment-reports"> Payment Reports </a></li>
                            <?php } ?>

                            <?php if(is_authorized($user_account_type, "transaction-report", "", "") === "allowed"){?>
                                <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "transaction-reports.php")) { echo "active"; } ?>" href="./transaction-reports"> Transaction Reports </a></li>
                            <?php } ?>

                            <?php if(is_authorized($user_account_type, "expenses-report", "", "") === "allowed"){?>
                                <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "expenses-reports.php")) { echo "active"; } ?>" href="./expenses-reports"> Expenses Reports </a></li>
                            <?php } ?>

                            <?php if(is_authorized($user_account_type, "payroll-report", "", "") === "allowed"){?>
                                <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "payroll-reports.php")) { echo "active"; } ?>" href="./payroll-reports"> Payroll Reports </a></li>
                                <li><a class="<?php if(strpos($_SERVER['PHP_SELF'], "payroll-reports-bank.php")) { echo "active"; } ?>" href="./payroll-reports-bank"> Payroll Reports (v2) </a></li>
                            <?php } ?>

                        </ul>
                    </li>
                <?php } ?>

                <?php
                    $settings_link = "";
                    if(is_authorized($account_type, "company-settings", "", "") === "allowed") { $settings_link = "./settings-company"; }
                    else if(is_authorized($account_type, "privilege-settings", "", "") === "allowed") { $settings_link = "./settings-roles-permissions"; }
                    else if(is_authorized($account_type, "invoice-settings", "", "") === "allowed") { $settings_link = "./settings-invoice"; }
                    else if(is_authorized($account_type, "payment-channels", "", "") === "allowed") { $settings_link = "./settings-payment-channels-bank"; }
                ?>

                <?php if($settings_link != "") {?>
                    <li> <a href="<?php echo $settings_link;?>"><i class="la la-cog"></i> <span>Settings</span></a> </li>
                <?php } ?>

                <li> <a href="./logout" class="text-danger"><i class="la la-sign-out"></i> <span>Log out</span></a> </li>
            </ul>
        </div>
    </div>
</div>