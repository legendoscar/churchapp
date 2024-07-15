<?php require "./includes/header.php";?>
<?php if(is_authorized($account_type, "create-receipt", "", "") == "allowed"){ ?>
    <title>New Receipt | <?php echo get_company_data("company_name");?></title>
    <body>

        <div class="main-wrapper">

            <?php require "./includes/top-nav.php";?>
            <?php require "./includes/sidebar.php";?>

            <div class="page-wrapper">

                <div class="content container-fluid">

                    <div class="page-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="page-title">New Receipt</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item active">New Receipt</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form method="POST" id="create-invoice">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">
                                            Receipt Form

                                            <span id="customer_account_loader" style="float:right" href=""></span>

                                        </h4>
                                    </div>

                                    <div id="return_server_msg"></div>

                                    <div class="card-body">

                                        <div class="row">

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Payment Received from <span id='switch_back_btn'></span> </label>

                                                    <span id='customer_switch_for_new' style='display:none'> <input placeholder="Type Payer's Name" type='text' name='' class='form-control'></span>

                                                    <span id='customer_switch_for_select'>
                                                        <select class="select" onchange="load_customer_bal(this.value)" name="payer_name" id="customer_name">
                                                            <option value=''>--Select--</option>
                                                            <option value='create-account'>CREATE PAYER ACCOUNT</option>
                                                            <?php echo get_customer_list("list", "");?>
                                                        </select>
                                                    </span>
                                                    <input id='switch_tracker' name='switch_tracker' value='returning-customer' type='hidden' name='' class='form-control'></span>
                                                </div>
                                            </div>

                                            <!-- <div class="col-md-12">
                                                <div style="margin-top:20px;padding:5px;" class="alert -alert-secondary">
                                                    <b>TRANSACTION DETAILS</b>
                                                </div>
                                            </div> -->

                                            <div class="col-lg-12" style="margin-top:10px;">
                                                <div class="table-responsive">
                                                    <table id="invoice_table" class="table table-bordered table-hover mb-0">
                                                        <thead class="alert alert-warning">
                                                            <tr style="padding-top:0px;">
                                                                <th style="width:290px;">ITEM</th>
                                                                <th style="width:290px;">AMOUNT</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <select required class="select" id="product_id" name="product_id[]">
                                                                        <option value=''>-- Select -- </option>
                                                                        <?php echo get_payable_items('');?>
                                                                    </select>
                                                                </td>
                                                                <td>
                                                                    <div class="input-group">
                                                                        <span class="input-group-text"><b>₦</b></span>
                                                                        <input onchange='StartAccounting()' onkeyup='StartAccounting()' onkeydown='StartAccounting()' type="number" name="h_total[]" value='0.00' min="0.01" step="0.01" class="amount form-control">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <p style="margin-top:20px;">
                                                    <b>Receipt Value: ₦</b><b id="total_paid">0.00</b> <br>
                                                    <b style='font-size-:21px;'>
                                                        <b id="excess_payment_front_end_container" class='text-danger alert-danger' style="display:none">
                                                            <span class='fa fa-exclamation-triangle'> </span> <span id="excess_payment_front_end_title"></span>
                                                            <span id='excess_payment_front_end'></span>
                                                        </b>
                                                    </b>

                                                    <input type="hidden" name="payment_method" value='split'>
                                                    <input value="0" name="h_total_paid" required min="0" type="hidden" step='0.01' class="h_total_paid form-control" id="h_total_paid">
                                                    <div style="padding:5px;" class="alert alert-secondary"> <b>Amount in words: </b> <b id="amount_in_words"></b></div>

                                                </p>

                                                <p style="margin-top:10px;float:right">
                                                    <input type="number" value='1' style="width:50px;" min="1" max="6" id='rows_number' name="rows_number">
                                                    <button id="add_more_btn" type="button" onclick="NewTableRow()" class="btn btn-sm btn-danger"><span class="fa fa-plus-circle"></span> Add More Item(s)</button>
                                                </p>
                                            </div>

                                        </div>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">More <span class="la la-forward"></span> </h4>
                                    </div>
                                    <div class="card-body">
                                        <!-- <div class="form-group">
                                            <label>Payment Date</label>
                                            <input required type="date" class="form-control" value="<?php // echo date("Y-m-d");?>" name="payment_date">
                                        </div> -->

                                        <div class="form-group">
                                            <label>Mode of Payment</label>
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#split_invoice_payment" class="form-control btn btn-sm btn-primary"><span class="fa fa-money"></span> Add Payment</button>
                                        </div>

                                        <div class="form-group">
                                            <label>Payment Status</label>
                                            <select class="select" name="payment_status" required>
                                                <option value="">-- Choose Status -- </option>
                                                <?php echo get_payment_status("");?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Additional note</label>
                                            <input type="text" name="additional_note" id="additional_note" class="form-control">
                                        </div>

                                        <!-- <div class="form-group">
                                            <label>Upload Evidence <span class="text-">(optional)</small></label>
                                            <input class="form-control" type="file">
                                        </div> -->

                                        <div class="text-end">
                                            <button type="submit" id="create_invoice_btn" class="btn btn-primary">
                                                <span class="la la-file-alt"></span>
                                                Generate receipt
                                            </button>
                                        </div>

                                        <?php require "./includes/invoice_js.php";?>
                                        <?php require "./includes/split_modal.php";?>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>

                </div>

            </div>

        </div>

        <?php require "./includes/footer.php";?>
    </body>
<?php } else { require "./403.php"; } ?>
</html>