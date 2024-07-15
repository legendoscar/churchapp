<?php require "./includes/header.php";?>

<?php if(!in_array(get_invoice_data(@$_GET['id'], "id"), array("not_found", "error")) ){ ?>

    <?php if(is_authorized($account_type, "edit-receipt", "", "") == "allowed"){ ?>

        <?php if(in_array($user_account_type, array("1")) || ( get_invoice_data(@$_GET['id'], "date_created") == date("Y-m-d") ) ) {?>


            <title>Edit Receipt | <?php echo get_company_data("company_name");?></title>
            <body>

                <div class="main-wrapper">

                    <?php require "./includes/top-nav.php";?>
                    <?php require "./includes/sidebar.php";?>

                    <div class="page-wrapper">

                        <div class="content container-fluid">

                            <div class="page-header">
                                <div class="row">
                                    <div class="col">
                                        <h3 class="page-title">Edit Receipt</h3>
                                        <ul class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Edit Receipt</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <?php require "./includes/split_modal.php";?>

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
                                                            <select class="select" name="payer_name" id="customer_name">
                                                                <option value=''>--Select--</option>
                                                                <?php echo get_customer_list("list", get_invoice_data(@$_GET['id'], "customer_name"));?>
                                                            </select>

                                                            <input type="hidden" required class="url_id form-control" value="<?php echo get_invoice_data(@$_GET['id'], "url_id");?>" name="url_id" id="url_id">
                                                            <input type="hidden" required class="row_id form-control" value="<?php echo get_invoice_data(@$_GET['id'], "id");?>" name="row_id" id="row_id">
                                                            <input type="hidden" required class="invoice_id form-control" value="<?php echo get_invoice_data(@$_GET['id'], "id");?>" name="invoice_id" id="invoice_id">
                                                            <input type="hidden" required class="invoice_number form-control" value="<?php echo get_invoice_data(@$_GET['id'], "invoice_number");?>" name="invoice_number" id="invoice_number">

                                                        </span>
                                                        <input id='switch_tracker' name='switch_tracker' value='returning-customer' type='hidden' name='' class='form-control'></span>
                                                    </div>
                                                </div>

                                                <!-- <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Branch</label>
                                                        <select class="select">
                                                            <option>Select</option>
                                                            <option value="1">A+</option>
                                                            <option value="2">O+</option>
                                                            <option value="3">B+</option>
                                                            <option value="4">AB+</option>
                                                        </select>
                                                    </div>
                                                </div> -->

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
                                                                <?php echo get_invoice_transactions(get_invoice_data(@$_GET['id'], "id"), get_invoice_data(@$_GET['id'], "invoice_number"));?>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <p style="margin-top:20px;">
                                                        <b>Receipt Value: ₦</b><b id="total_paid"><?php echo number_format(get_invoice_data(@$_GET['id'], "total_paid"), 2);?></b> <br>
                                                        <b style='font-size-:21px;'>
                                                            <b id="excess_payment_front_end_container" class='
                                                                <?php if(get_invoice_data(@$_GET['id'], "is_split") == "yes" && $total_split > $total_invoice_value) {?> text-danger alert-danger <?php } ?>
                                                                <?php if(get_invoice_data(@$_GET['id'], "is_split") == "yes" && $total_split < $total_invoice_value) {?> text-info alert-info <?php } ?>' style="<?php if(get_invoice_data(@$_GET['id'], "is_split") == "yes" && $total_split == $total_invoice_value) {?> display:none <?php } else if(get_invoice_data(@$_GET['id'], "is_split") != "yes") {?> display:none <?php } ?>">

                                                                <span class='fa fa-exclamation-triangle'> </span>
                                                                <span id="excess_payment_front_end_title">
                                                                    <?php if(get_invoice_data(@$_GET['id'], "is_split") == "yes" && $total_split > $total_invoice_value) {?> EXCESS: <?php } ?>
                                                                    <?php if(get_invoice_data(@$_GET['id'], "is_split") == "yes" && $total_split < $total_invoice_value) {?> REMAINDER: <?php } ?>
                                                                </span>
                                                                <span id='excess_payment_front_end'>
                                                                    <?php if(get_invoice_data(@$_GET['id'], "is_split") == "yes" && $total_split > $total_invoice_value) { echo "₦".custom_money_format(abs($excess)); } ?>
                                                                    <?php if(get_invoice_data(@$_GET['id'], "is_split") == "yes" && $total_split < $total_invoice_value) { echo "₦".custom_money_format(abs($excess)); } ?>
                                                                </span>
                                                            </b>
                                                        </b>

                                                        <input type="hidden" id="payment_method" name="payment_method" value='split'>
                                                        <input value="<?php echo get_invoice_data(@$_GET['id'], "total_paid");?>" name="h_total_paid" required min="0" type="hidden" step='0.01' class="h_total_paid form-control" id="h_total_paid">
                                                        <div style="padding:5px;" class="alert alert-secondary"> <b>Amount in words: </b> <b id="amount_in_words"></b></div>
                                                    </p>

                                                    <p style="margin-top:10px;float:right">
                                                        <button id="add_more_btn" type="button" data-bs-toggle="modal" data-bs-target="#add_more_receipt_item" class="btn btn-sm btn-danger"><span class="fa fa-plus-circle"></span> Add More Item(s) </button>
                                                        <a href="./view-receipt?id=<?php echo $_GET['id'];?>" target="_blank" id="add_more_btn" class="btn btn-sm btn-warning"><span class="fa fa-eye"></span> View Invoice </a>
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
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#edit_split_invoice_payment" class="form-control btn btn-sm btn-primary"><span class="fa fa-money"></span> Add/Edit Payment</button>
                                            </div>

                                            <div class="form-group">
                                                <label>Payment Status</label>
                                                <select id="payment_status" class="select" name="payment_status" required>
                                                    <option value="">-- Choose Status -- </option>
                                                    <?php echo get_payment_status(get_invoice_data(@$_GET['id'], "status"));?>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Additional note</label>
                                                <input type="text" value="<?php echo get_invoice_data(@$_GET['id'], "additional_note");?>" name="additional_note" id="additional_note" class="form-control">
                                            </div>

                                            <!-- <div class="form-group">
                                                <label>Upload Evidence <span class="text-">(optional)</small></label>
                                                <input class="form-control" type="file">
                                            </div> -->

                                            <div class="text-end">
                                                <button type="button" id="update-invoice-btn" class="btn btn-primary">
                                                    <span class="la la-save"></span> Save changes
                                                </button>
                                            </div>

                                            <script>

                                                function update_Split_Data(){

                                                    if(confirm("Ready to save?")){

                                                        var form_data = $("#update_split_data").serialize();

                                                        $.ajax({
                                                            type: "POST",
                                                            url: "./includes/ajax/update-split-data",
                                                            data: form_data,
                                                            cache: false,
                                                            beforeSend: function() {
                                                                $("button#update_split_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Updating...</i>");
                                                                $("button#update_split_btn").attr("disabled", "disabled");
                                                            },
                                                            success: function(d) {
                                                                $('div#return_server_msg').fadeIn('slow').html(d);
                                                                $("button#update_split_btn").fadeIn("slow").html("Update Again?");
                                                                $("button#update_split_btn").removeAttr("disabled");
                                                            },
                                                            error: function(d) {
                                                                toastr.error("Something went wrong!");
                                                                $("button#update_split_btn").fadeIn("slow").html("Try Again?");
                                                                $("button#update_split_btn").removeAttr("disabled");
                                                            }
                                                        });
                                                        return false;
                                                    }

                                                }

                                            </script>

                                            <?php // require "./includes/invoice_js.php";?>

                                        </div>
                                    </div>
                                </div>

                                <script>
                                    function autoCals() {

                                        // TOTAL AMOUNT
                                        var elems_amount_total = document.getElementsByClassName('h_total');
                                        var total_amount_each = 0;
                                        for (var ii = 0; ii < elems_amount_total.length; ii++) {
                                            if(parseFloat(elems_amount_total[ii].value))
                                            total_amount_each += parseFloat(elems_amount_total[ii].value);
                                        }

                                        document.getElementById('total_paid').innerHTML = total_amount_each.valueOf().toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                                        var new_val = +total_amount_each.toFixed(2);
                                        document.getElementById('h_total_paid').value = new_val.toString();
                                        document.getElementById('total_invoice_value_1').innerHTML = new_val.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",").valueOf();
                                        document.getElementById('total_invoice_value_hidden_1').value = new_val.toString();

                                        // number_to_words( parseFloat(document.getElementById("total_paid").textContent.toString().replace(/\,/g,'')) );
                                        let div = document.querySelector('#amount_in_words');
                                        // alert("aa");
                                        var amount_in_word = number_to_words( parseFloat(document.getElementById("total_paid").textContent.toString().replace(/\,/g,'')) );

                                        if(amount_in_word != ""){ div.innerHTML = amount_in_word+"  naira"; }
                                        else { div.innerHTML = amount_in_word; }

                                        Calculate_Splits();
                                    }

                                    function number_to_words(s) {
                                        // System for American Numbering
                                        var th_val = ['', 'thousand', 'million', 'billion', 'trillion'];
                                        // System for uncomment this line for Number of English 
                                        // var th_val = ['','thousand','million', 'milliard','billion'];
                                        var dg_val = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
                                        var tn_val = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
                                        var tw_val = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

                                        s = s.toString();
                                            s = s.replace(/[\, ]/g, '');
                                            if (s != parseFloat(s))
                                                return 'not a number ';
                                            var x_val = s.indexOf('.');
                                            if (x_val == -1)
                                                x_val = s.length;
                                            if (x_val > 15)
                                                return 'too big';
                                            var n_val = s.split('');
                                            var str_val = '';
                                            var sk_val = 0;
                                            for (var i = 0; i < x_val; i++) {
                                                if ((x_val - i) % 3 == 2) {
                                                    if (n_val[i] == '1') {
                                                        str_val += tn_val[Number(n_val[i + 1])] + ' ';
                                                        i++;
                                                        sk_val = 1;
                                                    } else if (n_val[i] != 0) {
                                                        str_val += tw_val[n_val[i] - 2] + ' ';
                                                        sk_val = 1;
                                                    }
                                                } else if (n_val[i] != 0) {
                                                    str_val += dg_val[n_val[i]] + ' ';
                                                    if ((x_val - i) % 3 == 0)
                                                        str_val += 'hundred ';
                                                    sk_val = 1;
                                                }
                                                if ((x_val - i) % 3 == 1) {
                                                    if (sk_val)
                                                        str_val += th_val[(x_val - i - 1) / 3] + ' ';
                                                    sk_val = 0;
                                                }
                                            }
                                            if (x_val != s.length) {
                                                var y_val = s.length;
                                                str_val += 'point ';
                                                for (var i = x_val + 1; i < y_val; i++)
                                                    str_val += dg_val[n_val[i]] + ' ';
                                            }
                                            return str_val.replace(/\s+/g, ' ');
                                    }
                                </script>

                            </div>

                        </div>

                    </div>

                </div>

                <?php require "./includes/footer.php";?>
            </body>
        <?php } else { require "./403.php"; } ?>

    <?php } else { require "./403.php"; } ?>

<?php } else { ?>
    <?php require "./404.php";?>
<?php } ?>

</html>