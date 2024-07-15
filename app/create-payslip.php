<?php require "./includes/header.php";?>
<?php if(is_authorized($account_type, "create-payslip", "", "") === "allowed") {?>
    <title>Create Payslip | <?php echo get_company_data("company_name");?></title>
    <body>

        <div class="main-wrapper">

            <?php require "./includes/top-nav.php";?>
            <?php require "./includes/sidebar.php";?>

            <div class="page-wrapper"> 

                <div class="content container-fluid">

                    <div class="page-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="page-title">Create Payslip</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Create Payslip</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form method="POST" id="create-payslip">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">
                                            Payslip Form <span id="account_loader" style="font-size:14px;float:right" href="">Basic Salary: ₦0.00</span>
                                        </h4>
                                    </div>

                                    <div id="return_server_msg"></div>

                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Employee <!--<span id='switch_back_btn'></span>--> </label>
                                                    <!-- <span id='customer_switch_for_new' style='display:none'> <input placeholder="Type Payer's Name" type='text' name='' class='form-control'></span> -->
                                                    <span id='customer_switch_for_select'>
                                                        <select required class="select" onchange="load_employee_payroll_data(this.value)" name="employee_name" id="">
                                                            <option value=''>--Select Employee--</option>
                                                            <?php echo get_employees_list("", "payroll");?>
                                                        </select>
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Payslip for (date) <!--<span id='switch_back_btn'></span>--> </label>
                                                    <input required value="<?php echo date("Y-m");?>" type="month" name="month" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Mode of Payment <!--<span id='switch_back_btn'></span>--> </label>
                                                    <select required class="select" name="mode_of_payment" id="mode_of_payment">
                                                        <option value=''>-- Select Mode--</option>
                                                        <?php echo get_payslip_mode_of_payment("");?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <div class="form-group">
                                                    <label>Status <!--<span id='switch_back_btn'></span>--> </label>
                                                    <select required class="select" name="status" id="status">
                                                        <option value=''>-- Select--</option>
                                                        <?php echo get_payslip_status(2);?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Payment date (date) </label>
                                                    <input required value="<?php echo date("Y-m-d");?>" type="date" name="payment_date" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Bank Name:</label>
                                                    <input readonly id="bank_name" type="text" class="form-control" name="bank_name">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Account Number: </label>
                                                    <input readonly type="text" class="form-control" id="account_number" name="account_number">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Account Name: </label>
                                                    <input readonly type="text" class="form-control" id="account_name" name="account_name">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Payment Bank Name:</label>
                                                    <input readonly id="payment_bank_name" type="text" class="form-control" name="payment_bank">
                                                </div>
                                            </div>

                                            <div class="col-md-9">
                                                <div class="form-group">
                                                    <label>Additional Note: <!--<span id='switch_back_btn'></span>--> </label>
                                                    <input placeholder="Write additional note here..." type="text" class="form-control" name="additional_note">
                                                </div>
                                            </div>

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
                                                            <tr>
                                                                <td>
                                                                    <select required class="select form-control" id="earnings_id" name="earnings_id[]">
                                                                        <option value='1'>BASIC SALARY</option>
                                                                        <?php // echo get_payslip_items("", "earnings", "");?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text"><b>₦</b></span>
                                                                        <input onchange='StartAccounting()' id="default_earnings_value" readonly type="number" name="earnings_value[]" value='0.00' min="0.01" step="0.01" class="earnings_value form-control">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <p style="margin-top:20px;">

                                                    <span style="float:right">
                                                        <input type="number" value='1' style="width:50px;" min="1" max="6" id='earnings_rows_number' name="earnings_rows_number">
                                                        <button id="add_more_earnings_btn" type="button" onclick="NewEarningsTableRow()" class="btn btn-sm btn-danger"><span class="fa fa-plus-circle"></span></button>
                                                    </span>

                                                    <b>Earnings: ₦</b><b id="total_earnings">0.00</b>
                                                    <input value="0" name="h_total_earnings" required min="0" type="hidden" step='0.01' class="h_total_earnings form-control" id="h_total_earnings">

                                                    <!-- <br> -->
                                                    <!-- <b style='font-size-:21px;'>
                                                        <b id="excess_payment_front_end_container" class='text-danger alert-danger' style="display:none">
                                                            <span class='fa fa-exclamation-triangle'> </span> <span id="excess_payment_front_end_title"></span>
                                                            <span id='excess_payment_front_end'></span>
                                                        </b>
                                                    </b> -->
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
                                                            <?php $default_row_id = "row_".rand(123, 9000);?>
                                                            <tr id="<?php echo $default_row_id;?>">
                                                                <!-- <td>
                                                                    <select required class="select form-control" id="deductions_id" name="deductions_id[]">
                                                                        <option value=''>-- Select --</option>
                                                                        <?php // echo get_payslip_items("", "deduction", "");?>
                                                                    </select>
                                                                </td>

                                                                <td>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text"><b>₦</b></span>
                                                                        <input onchange="StartAccounting()" onkeydown="StartAccounting()" onkeyup="StartAccounting()" value="0.00" type="number" name="deductions_value[]" min="0.01" step="0.01" class="deductions_value form-control">
                                                                        <a style='float:right' onclick="deleteRow('<?php echo $default_row_id;?>')" href='#!'><span data-toggle='tooltip' title='Remove this row?' class='btn  fa fa-trash-o text-danger'></span></a>
                                                                    </div>
                                                                </td> -->
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <p style="margin-top:20px;">

                                                    <span style="float:right">
                                                        <input type="number" value='1' style="width:50px;" min="1" max="6" id='deductions_rows_number' name="deductions_rows_number">
                                                        <button id="add_more_deductions_btn" type="button" onclick="NewDeductionsTableRow()" class="btn btn-sm btn-danger"><span class="fa fa-plus-circle"></span></button>
                                                    </span>

                                                    <b>Deductions: ₦</b><b class='text-danger' id="total_deductions">0.00</b>
                                                    <input value="0" name="h_total_deductions" required min="0" type="hidden" step='0.01' class="h_total_deductions form-control" id="h_total_deductions">

                                                    <!-- <br> -->
                                                    <!-- <b style='font-size-:21px;'>
                                                        <b id="excess_payment_front_end_container" class='text-danger alert-danger' style="display:none">
                                                            <span class='fa fa-exclamation-triangle'> </span> <span id="excess_payment_front_end_title"></span>
                                                            <span id='excess_payment_front_end'></span>
                                                        </b>
                                                    </b> -->
                                                </p>

                                            </div>

                                            <!-- <hr/>
                                            <p style="margin-top:10px;float:right">
                                                <input type="number" value='1' style="width:50px;" min="1" max="6" id='rows_number' name="rows_number">
                                                <button id="add_more_btn" type="button" onclick="NewTableRow()" class="btn btn-sm btn-danger"><span class="fa fa-plus-circle"></span></button>
                                            </p> -->

                                        </div>

                                        <hr/>

                                        <button id="action-btn" type="submit" class="pull-right btn btn-sm btn-primary"><span class="la la-file-alt"></span> Generate Payslip</button>
                                        <b>NET PAY: ₦</b><b id="total_net_pay">0.00</b>
                                        <input value="0" name="h_total_net_pay" required min="0" type="hidden" step='0.01' class="h_total_net_pay form-control" id="h_total_net_pay">

                                    </div>
                                </div>
                            </div>

                            <!-- <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">More <span class="la la-forward"></span> </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Mode of Payment</label>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#split_invoice_payment" class="form-control btn btn-sm btn-primary"><span class="fa fa-money"></span> Add Payment</button>
                                        </div>

                                        <div class="form-group">
                                            <label>Payment Status</label>
                                            <select class="select" name="payment_status" required>
                                                <option value="">-- Choose Status -- </option>
                                                <?php // echo get_payment_status("");?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Additional note</label>
                                            <input type="text" name="additional_note" id="additional_note" class="form-control">
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" id="create_invoice_btn" class="btn btn-primary">
                                                <span class="la la-file-alt"></span>
                                                Generate receipt
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div> -->

                                        <?php require "./includes/payslip_js.php";?>
                                        <?php // require "./includes/split_modal.php";?>

                        </div>
                    </form>

                </div>

            </div>

        </div>

        <?php require "./includes/footer.php";?>
    </body>
<?php } else { require "./403.php"; } ?>
</html>