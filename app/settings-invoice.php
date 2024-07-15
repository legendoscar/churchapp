<?php require "./includes/header.php";?>
<title>Invoice Settings | <?php echo get_company_data("company_name");?></title>
<body>

    <div class="main-wrapper">

        <?php require "./includes/top-nav.php";?>
        <?php require "./includes/settings-sidebar.php";?>

        <div class="page-wrapper">

            <div class="content container-fluid">

                <div class="page-header">
                    <div class="row">
                        <div class="col">
                            <h3 class="page-title">Invoice Settings</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                <li class="breadcrumb-item">Settings</li>
                                <li class="breadcrumb-item active">Invoice Settings</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">

                        <form method="POST" id="company-invoice">

                            <div class="row">
                                <!-- <div class="col-md-3">
                                    <div class="form-group row">
                                        <label for="show_app_version" class="col-sm-12 col-form-label">Show App Version</label>
                                        <div class="col-sm-12">
                                            <select name="show_app_version" id="show_app_version">
                                                <option <?php if(get_company_data("show_app_version") === "yes" ){ echo "selected";}?> value="yes">Yes</option> 
                                                <option <?php if(get_company_data("show_app_version") != "yes" ){ echo "selected";}?> value="no">No</option> 
                                            </select>
                                        </div>
                                    </div>
                                </div> -->

                                <!-- <div class="col-md-2">
                                    <div class="form-group row">
                                        <label for="app_version" class="col-sm-12 col-form-label">App Version</label>
                                        <div class="col-sm-12">
                                            <input required type="text" name="app_version" class="form-control" value="<?php echo get_company_data("app_version");?>" id="app_version" placeholder="App Version"> 
                                        </div>
                                    </div>
                                </div> -->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Invoice Prefix</label>
                                        <input name="invoice_prefix" value="<?php echo get_company_data("invoice_prefix");?>" class="form-control" type="text">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="footer_text">Invoice Footer Text</label>
                                        <input required type="text" name="footer_text" class="form-control" value="<?php echo get_company_data("footer_text");?>" id="footer_text" placeholder="Invoice Footer Text"> 
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="show_app_logo">Show Invoice Logo</label>
                                        <div class="col-sm-12">
                                            <select class="select" name="show_app_logo" id="show_app_logo">
                                                <option <?php if(get_company_data("show_logo") === "yes" ){ echo "selected";}?> value="yes">Yes</option> 
                                                <option <?php if(get_company_data("show_logo") != "yes" ){ echo "selected";}?> value="no">No</option> 
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="show_invoice_trn_additional_text">Show Additional Text (Invoice)</label>
                                        <div class="col-sm-12">
                                            <select class="select" name="show_invoice_trn_additional_text" id="show_invoice_trn_additional_text">
                                                <option <?php // if(get_company_data("show_invoice_item_addtional_text") === "yes" ){ echo "selected";}?> value="yes"> Yes </option>
                                                <option <?php // if(get_company_data("show_invoice_item_addtional_text") != "yes" ){ echo "selected";}?> value="no"> No </option>
                                            </select>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="invoice_printout">Invoice Printout</label>
                                        <div class="col-sm-12">
                                            <select class="select" name="invoice_printout" id="invoice_printout">
                                                <option <?php if(get_company_data("invoice_printout") === "invoice-POS" ){ echo "selected";}?> value="invoice-POS"> Thermal Printer (Default) </option>
                                                <option <?php if(get_company_data("invoice_printout") != "invoice-POS" ){ echo "selected";}?> value="A4"> A4 </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="invoice_allow_excess_payment">Allow Excess Payments (Invoice)</label>
                                        <div class="col-sm-12">
                                            <select class="select" name="invoice_allow_excess_payment" id="invoice_allow_excess_payment">
                                                <option <?php if(get_company_data("invoice_allow_excess_payment") === "yes" ){ echo "selected";}?> value="yes"> Yes, allow </option>
                                                <option <?php if(get_company_data("invoice_allow_excess_payment") != "yes" ){ echo "selected";}?> value="no"> No, disallow </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- <div class="col-md-3">
                                    <div class="form-group row">
                                        <label for="invoice_allow_duplicate_contents" class="col-sm-12 col-form-label">Allow Duplicate Contents (Invoice Items)</label>
                                        <div class="col-sm-12">
                                            <select name="invoice_allow_duplicate_contents" id="invoice_allow_duplicate_contents">
                                                <option <?php if(get_company_data("invoice_allow_duplicate_contents") === "yes" ){ echo "selected";}?> value="yes"> Yes, allow </option>
                                                <option <?php if(get_company_data("invoice_allow_duplicate_contents") != "yes" ){ echo "selected";}?> value="no"> No, disallow </option>
                                            </select>
                                        </div>
                                    </div>
                                </div> -->

                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="invoice_show_excess_payment_in_invoice">Show Excess Payment (Customer Invoice)</label>
                                        <div class="col-sm-12">
                                            <select class="select" name="invoice_show_excess_payment_in_invoice" id="invoice_show_excess_payment_in_invoice">
                                                <option <?php if(get_company_data("invoice_show_excess_payment_in_invoice") === "yes" ){ echo "selected";}?> value="yes"> Yes, allow </option>
                                                <option <?php if(get_company_data("invoice_show_excess_payment_in_invoice") != "yes" ){ echo "selected";}?> value="no"> No, disallow </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="invoice_show_excess_payment_in_report">Show Excess Payment (Sales Report)</label>
                                        <div class="col-sm-12">
                                            <select class="select" name="invoice_show_excess_payment_in_report" id="invoice_show_excess_payment_in_report">
                                                <option <?php if(get_company_data("invoice_show_excess_payment_in_report") === "yes" ){ echo "selected";}?> value="yes"> Yes, allow </option>
                                                <option <?php if(get_company_data("invoice_show_excess_payment_in_report") != "yes" ){ echo "selected";}?> value="no"> No, disallow </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label for="can_apply_customer_balance_to_invoice">Can apply user balance to invoice</label>
                                        <div class="col-sm-12">
                                            <select class="select" name="can_apply_customer_balance_to_invoice" id="can_apply_customer_balance_to_invoice">
                                                <option <?php if(get_company_data("can_apply_customer_balance_to_invoice") === "yes" ){ echo "selected";}?> value="yes"> Yes, allow </option>
                                                <option <?php if(get_company_data("can_apply_customer_balance_to_invoice") != "yes" ){ echo "selected";}?> value="no"> No, disallow </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-primary -submit-btn" id="update-invoice-settings-btn"><span class="fa fa-save"></span> Save changes</button>

                        </form>

                        <!-- <form>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Invoice prefix</label>
                                <div class="col-lg-9">
                                    <input type="text" value="INV" class="form-control" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 col-form-label">Invoice Logo</label>
                                <div class="col-lg-7">
                                    <input type="file" class="form-control" />
                                    <span class="form-text text-muted">Recommended image size is 200px x 40px</span>
                                </div>
                                <div class="col-lg-2">
                                    <div class="img-thumbnail float-end"><img src="assets/img/logo.png" class="img-fluid" alt="" width="140" height="40" /></div>
                                </div>
                            </div>
                            <div class="submit-section">
                                <button class="btn btn-primary submit-btn">Save</button>
                            </div>
                        </form> -->

                    </div>
                </div>

            </div>

        </div>

    </div>

    <?php require "./includes/footer.php";?>
</body>

</html>