<?php require "./includes/header.php";?>

<?php if(!in_array(get_payslip_data_by_urlid(@$_GET['id'], "id"), array("not_found", "error")) ){ ?>

    <?php
        $payslip_id = get_payslip_data_by_urlid(@$_GET['id'], "id");
        $payslip_number = get_payslip_data_by_urlid(@$_GET['id'], "payslip_number");
    ?>

    <?php if(is_authorized($user_account_type, "print-payslip", "", "") === "allowed"){ ?>

        <title>View Payslip | <?php echo get_company_data("company_name");?></title>
        <body>

            <div class="main-wrapper">

                <?php require "./includes/top-nav.php";?>
                <?php require "./includes/sidebar.php";?>

                <div class="page-wrapper">

                    <div class="content container-fluid">

                        <div class="page-header">
                            <div class="row">
                                <div class="col">
                                    <h3 class="page-title">View Payslip</h3>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                        <li class="breadcrumb-item active">View Payslip</li>
                                    </ul>
                                </div>

                                <div class="col-auto float-end ms-auto">
                                    <div class="btn-group btn-group-sm">
                                        <a target="_blank" href="./print-payslip?id=<?php echo $_GET['id'];?>" class="btn btn-white"><i class="fa fa-print fa-lg"></i> Print</a>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <div id="">
                            <div class="row" id="htmlContent">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <h4 class="payslip-title">Payslip for the month of <?php echo date("F, Y", strtotime(get_payslip_data_by_urlid(@$_GET['id'], "payslip_month")));?></h4>
                                            <div class="row">
                                                <div class="col-sm-6 m-b-20">
                                                    <!-- <img src="assets/img/logo.png" class="inv-logo" alt=""> -->
                                                    <ul class="list-unstyled mb-0">
                                                        <li><?php echo get_company_data("company_name");?></li>
                                                        <li><?php echo get_company_data("company_address");?></li>
                                                        <li><?php echo get_company_data("company_phone1");?>, <?php echo get_company_data("company_phone2");?></li>
                                                    </ul>
                                                </div>
                                                <div class="col-sm-6 m-b-20">
                                                    <div class="invoice-details">
                                                        <h3 class="text-uppercase">#<?php echo get_payslip_data_by_urlid(@$_GET['id'], "payslip_number");?></h3>
                                                        <ul class="list-unstyled">
                                                            <li>Salary Month: <span><?php echo date("F, Y", strtotime(get_payslip_data_by_urlid(@$_GET['id'], "payslip_month")));?></span></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row" id="">
                                                <div class="col-lg-12 m-b-20">
                                                    <ul class="list-unstyled">
                                                        <li> <h5 class="mb-0"><strong><?php echo strtoupper(get_user_data(get_payslip_data_by_urlid(@$_GET['id'], "employee_id"), "firstname"));?> <?php echo strtoupper(get_user_data(get_payslip_data_by_urlid(@$_GET['id'], "employee_id"), "surname"));?></strong></h5> </li>
                                                        <li><span><?php echo get_designation_data_by_id(get_payslip_data_by_urlid(@$_GET['id'], "employee_designation"), "name");?></span></li>
                                                        <li><span><?php echo get_department_data_by_id(get_payslip_data_by_urlid(@$_GET['id'], "employee_department"), "name");?></span></li>
                                                        <li><b>BANK:</b> <span><?php echo get_payslip_employee_bank_details_by_payslip_id(get_payslip_data_by_urlid(@$_GET['id'], "id"), get_payslip_data_by_urlid(@$_GET['id'], "employee_id"), "bank_name");?></span></li>
                                                        <li><b>ACCOUNT NUMBER:</b> <span><?php echo get_payslip_employee_bank_details_by_payslip_id(get_payslip_data_by_urlid(@$_GET['id'], "id"), get_payslip_data_by_urlid(@$_GET['id'], "employee_id"), "account_number");?></span></li>
                                                        <li><b>ACCOUNT NAMER:</b> <span><?php echo get_payslip_employee_bank_details_by_payslip_id(get_payslip_data_by_urlid(@$_GET['id'], "id"), get_payslip_data_by_urlid(@$_GET['id'], "employee_id"), "account_name");?></span></li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div>
                                                        <h4 class="m-b-10"><strong>Earnings</strong></h4>

                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <?php echo get_payslip_transactions_view($payslip_id, $payslip_number, "list", "earnings");?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div>
                                                        <h4 class="m-b-10"><strong>Deductions</strong></h4>
                                                        <table class="table table-bordered">
                                                            <tbody>
                                                                <?php echo get_payslip_transactions_view($payslip_id, $payslip_number, "list", "deduction");?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <p><strong>Net Salary: ₦<?php echo custom_money_format(get_payslip_data_by_urlid(@$_GET['id'], "total_amount"));?></strong> (<?php echo number_to_words(get_payslip_data_by_urlid(@$_GET['id'], "total_amount"));?>)</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="editor"></div>

                    </div>

                </div>

            </div>

            <?php require "./includes/footer.php";?>
        </body>

    <?php } else { require "./403.php"; } ?>
<?php } else { ?>
    <?php require "./404.php";?>
<?php } ?>

</html>