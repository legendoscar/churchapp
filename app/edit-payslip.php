<?php require "./includes/header.php";?>

<?php if(!in_array(get_payslip_data_by_urlid(@$_GET['id'], "id"), array("not_found", "error")) ){ ?>

    <?php
        $payslip_id = get_payslip_data_by_urlid(@$_GET['id'], "id");
        $payslip_number = get_payslip_data_by_urlid(@$_GET['id'], "payslip_number");
    ?>

    <?php if(is_authorized($account_type, "edit-payslip", "", "") === "allowed") {?>
        <title>Edit Payslip | <?php echo get_company_data("company_name");?></title>
        <body>

            <div class="main-wrapper">

                <?php require "./includes/top-nav.php";?>
                <?php require "./includes/sidebar.php";?>

                <div class="page-wrapper">

                    <div class="content container-fluid">

                        <div class="page-header">
                            <div class="row">
                                <div class="col">
                                    <h3 class="page-title">Edit Payslip</h3>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Edit Payslip</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">

                                    <div class="alert alert-primary" style="padding:5px;"> <b>Note: If you update "Employee", remember to update employee Basic Salary and Bank details manually.</b> </div>

                                    <div class="card-header">
                                        <h4 class="card-title mb-0">
                                            Payslip Form
                                            (<?php echo $payslip_number;?>)
                                            <button id="update-payslip-btn" type="submit" class="pull-right btn btn-sm btn-primary"><span class="la la-file-alt"></span> Update Payslip</button>
                                            <!-- <span id="account_loader" style="font-size:14px;float:right" href=""></span> -->
                                        </h4>
                                    </div>

                                    <div id="return_server_msg"></div>

                                    <div class="card-body">
                                        <form method="POST" id="update-payslip-data">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>Employee </label>
                                                        <span id='customer_switch_for_select'>
                                                            <!-- <select required class="select" onchange="load_employee_payroll_data(this.value)" name="employee_name" id=""> -->
                                                            <select required class="select" name="employee_name" id="">
                                                                <option value=''>--Select Employee--</option>
                                                                <?php echo get_employees_list(get_payslip_data_by_urlid(@$_GET['id'], "employee_id"), "payroll");?>
                                                            </select>
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Payslip for (date)</label>
                                                        <input required value="<?php echo date("Y-m", strtotime(get_payslip_data_by_urlid(@$_GET['id'], "payslip_month")));?>" type="month" name="month" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Mode of Payment </label>
                                                        <select required class="select" name="mode_of_payment" id="mode_of_payment">
                                                            <option value=''>-- Select Mode--</option>
                                                            <?php echo get_payslip_mode_of_payment(get_payslip_data_by_urlid(@$_GET['id'], "payment_mode"));?>
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="col-md-2">
                                                    <div class="form-group">
                                                        <label>Status</label>
                                                        <select required class="select" name="status" id="status">
                                                            <option value=''>-- Select--</option>
                                                            <?php echo get_payslip_status(get_payslip_data_by_urlid(@$_GET['id'], "payslip_status"));?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Payment date (date) </label>
                                                        <input required value="<?php echo date("Y-m-d", strtotime(get_payslip_data_by_urlid(@$_GET['id'], "payment_date")));?>" type="date" name="payment_date" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Bank Name:</label>
                                                        <select class="select form-control" name="bank_name" id="bank_name">
                                                            <option value="">Select Bank Name</option>
                                                            <?php echo get_banks(get_payslip_employee_bank_details_by_payslip_id(get_payslip_data_by_urlid(@$_GET['id'], "id"), get_payslip_data_by_urlid(@$_GET['id'], "employee_id"), "bank_id"));?>
                                                        </select>
                                                   </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Account Number: </label>
                                                        <input value="<?php echo get_payslip_employee_bank_details_by_payslip_id(get_payslip_data_by_urlid(@$_GET['id'], "id"), get_payslip_data_by_urlid(@$_GET['id'], "employee_id"), "account_number");?>" type="text" class="form-control" id="account_number" name="account_number">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Account Name: </label>
                                                        <input value="<?php echo get_payslip_employee_bank_details_by_payslip_id(get_payslip_data_by_urlid(@$_GET['id'], "id"), get_payslip_data_by_urlid(@$_GET['id'], "employee_id"), "account_name");?>" type="text" class="form-control" id="account_name" name="account_name">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Payment Bank </label>
                                                        <select class="select form-control" name="payment_bank" id="payment_bank">
                                                            <option value="">Select Bank Name</option>
                                                            <?php echo get_payment_banks(get_payslip_employee_bank_details_by_payslip_id(get_payslip_data_by_urlid(@$_GET['id'], "id"), get_payslip_data_by_urlid(@$_GET['id'], "employee_id"), "payment_bank"));?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <label>Additional Note: </label>
                                                        <input value="<?php echo get_payslip_data_by_urlid(@$_GET['id'], "additional_note");?>" placeholder="Write additional note here..." type="text" class="form-control" name="additional_note">
                                                        <input value="<?php echo get_payslip_data_by_urlid(@$_GET['id'], "id");?>" type="hidden" class="form-control" name="payslip_id">
                                                        <input value="<?php echo get_payslip_data_by_urlid(@$_GET['id'], "payslip_number");?>" type="hidden" class="form-control" name="payslip_number">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div class="row">
                                            <div class="col-lg-6" style="margin-top:10px;">
                                                <div class="table-responsive">
                                                    <table id="earnings_table" class="table table-bordered table-hover mb-0">
                                                        <thead class="alert alert-warning">
                                                            <tr style="padding-top:0px;">
                                                                <th style="width:290px;">EARNINGS</th>
                                                                <th style="width:290px;">AMOUNT</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php echo get_payslip_transactions($payslip_id, $payslip_number, "list", "earnings");?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <p style="margin-top:20px;">

                                                    <span style="float:right">
                                                        <button onclick="is_earings()" data-bs-toggle="modal" data-bs-target="#add_more_payslip_item" type="button" class="btn btn-sm btn-danger"><span class="fa fa-plus-circle"></span></button>
                                                    </span>

                                                    <b>Earnings: ₦</b><b id="total_earnings"><?php echo custom_money_format(get_payslip_transactions($payslip_id, $payslip_number, "total", "earnings"));?></b>
                                                    <input value="<?php echo (get_payslip_transactions($payslip_id, $payslip_number, "total", "earnings"));?>" name="h_total_earnings" required min="0" type="hidden" step='0.01' class="h_total_earnings form-control" id="h_total_earnings">

                                                </p>
                                            </div>

                                            <div class="col-lg-6" style="margin-top:10px;">
                                                <div class="table-responsive">
                                                    <table id="deductions_table" class="table table-bordered table-hover mb-0">
                                                        <thead class="alert alert-danger">
                                                            <tr style="padding-top:0px;">
                                                                <th style="width:290px;">DEDUCTIONS</th>
                                                                <th style="width:290px;">AMOUNT</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                            <?php echo get_payslip_transactions($payslip_id, $payslip_number, "list", "deduction");?>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <p style="margin-top:20px;">

                                                    <span style="float:right">
                                                        <button onclick="is_deductions()" data-bs-toggle="modal" data-bs-target="#add_more_payslip_item" type="button" class="btn btn-sm btn-danger"><span class="fa fa-plus-circle"></span></button>
                                                    </span>

                                                    <b>Deductions: ₦</b><b class='text-danger' id="total_deductions"><?php echo custom_money_format(get_payslip_transactions($payslip_id, $payslip_number, "total", "deduction"));?></b>
                                                    <input value="<?php echo (get_payslip_transactions($payslip_id, $payslip_number, "total", "deduction"));?>" name="h_total_deductions" required min="0" type="hidden" step='0.01' class="h_total_deductions form-control" id="h_total_deductions">
                                                </p>

                                            </div>

                                        </div>

                                        <hr/>

                                        <b>NET PAY: ₦</b><b id="total_net_pay"><?php echo custom_money_format(get_payslip_transactions($payslip_id, $payslip_number, "total", "earnings")-(get_payslip_transactions($payslip_id, $payslip_number, "total", "deduction")));?></b>
                                        <input value="<?php echo (get_payslip_transactions($payslip_id, $payslip_number, "total", "earnings")-(get_payslip_transactions($payslip_id, $payslip_number, "total", "deduction")));?>" name="h_total_net_pay" required min="0" type="hidden" step='0.01' class="h_total_net_pay form-control" id="h_total_net_pay">

                                        <span class="pull-right">
                                            <a href="./payslips" class="btn btn-sm btn-danger"><span class="fa fa-arrow-left"></span> Back to Payslips</a>
                                            <a href="./create-payslip" class="btn btn-sm btn-primary"><span class="fa fa-plus"></span> New Payslip</a>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <?php require "./includes/payslip_js.php";?>

                            <div class="modal fade" id="edit_payslip_item" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">

                                            <h5 class="modal-title" id="">Edit Item</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>

                                        </div>

                                        <form method="POST" id="update-payslip-transaction-item">
                                            <div class="modal-body">

                                                <div id="edit_item_container">
                                                    <center> <b> <span class="fa fa-spin fa-spinner"></span> Loading... Please wait </b> </center>
                                                </div>

                                                <div id="return_server_msg"></div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="add_more_payslip_item" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">

                                            <h5 class="modal-title" id="">Add more Item</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>

                                        </div>

                                        <div id="earnings_data_form">
                                            <form method="POST" id="add-more-payslip-earnings-item">

                                                <div class="modal-body">

                                                    <div class='row'>
                                                        <div class='col-md-6'>
                                                            <div class='form-group'>
                                                                <select style='width:200px;' class='select' name='payslip_item'>
                                                                    <option value='' label='Choose Method'>Choose Products</option>
                                                                    <?php echo get_payslip_items("", "earnings", "");?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class='col-md-6'>
                                                            <div class='form-group'>
                                                                <div class='input-group'>
                                                                    <span class='input-group-text'> <b>₦</b> </span>
                                                                    <input type='number' name='payslip_item_amount' min='0.01' step='0.01' class='form-control'>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class='col-md-12' style='margin-top:10px;'>
                                                            <button id='add-item-btn' class='btn btn-sm btn-primary'> <span class='fa fa-plus'></span> Add item </button>
                                                            <input type='hidden' name='payslip_id' value='<?php echo $payslip_id;?>' class='form-control'>
                                                            <input type='hidden' name='payslip_number' value='<?php echo $payslip_number;?>' class='form-control'>
                                                        </div>
                                                    </div>

                                                    <div id="return_server_msg"></div>

                                                </div>

                                            </form>
                                        </div>

                                        <div id="deductions_data_form">
                                            <form method="POST" id="add-more-payslip-deductions-item">

                                                <div class="modal-body">

                                                    <div class='row'>
                                                        <div class='col-md-6'>
                                                            <div class='form-group'>
                                                                <select style='width:200px;' class='select' name='payslip_item'>
                                                                    <option value='' label='Choose Method'>Choose Products</option>
                                                                    <?php echo get_payslip_items("", "deduction", "");?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class='col-md-6'>
                                                            <div class='form-group'>
                                                                <div class='input-group'>
                                                                    <span class='input-group-text'> <b>₦</b> </span>
                                                                    <input type='number' name='payslip_item_amount' min='0.01' step='0.01' class='form-control'>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class='col-md-12' style='margin-top:10px;'>
                                                            <button id='add-item-btn' class='btn btn-sm btn-primary'> <span class='fa fa-plus'></span> Add item </button>
                                                            <input type='hidden' name='payslip_id' value='<?php echo $payslip_id;?>' class='form-control'>
                                                            <input type='hidden' name='payslip_number' value='<?php echo $payslip_number;?>' class='form-control'>
                                                        </div>
                                                    </div>

                                                    <div id="return_server_msg"></div>

                                                </div>

                                            </form>
                                        </div>

                                        <script>
                                            function is_earings() {
                                                $("#earnings_data_form").attr("style","display:block");
                                                $("#deductions_data_form").attr("style","display:none");
                                            }

                                            function is_deductions() {
                                                $("#earnings_data_form").attr("style","display:none");
                                                $("#deductions_data_form").attr("style","display:block");
                                            }
                                        </script>
                                    </div>
                                </div>
                            </div>



                        </div>

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