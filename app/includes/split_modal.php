<!-- Modal -->
<?php if(strpos($_SERVER['PHP_SELF'], "new-receipt.php") == true){?>
    <div class="modal fade" id="split_invoice_payment" tabindex="-1" role="dialog" aria-labelledby="split_invoice_payment" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" style="padding:10px;">
                <div class="modal-body">
                    <p>
                        <label><b><input checked type="checkbox" id="allow_auto_fill_remainder_1"> Allow auto fill remainder</b></label> <br/>
                        <button onclick="add_split_payment()" id="add_split_pay_btn" class="btn btn-danger" type="button"> <span class="fa fa-plus"></span> Add More </button>
                        <button onclick="apply_customer_bal()" id="apply_customer_balance_btn" class="btn btn-warning" type="button"> <span class="fa fa-plus"></span> Apply Balance </button>
                    </p>

                    <script>

                        function add_split_payment(){

                            var allow_auto_fill_remainder = 0;
                            var is_auto_fill_checked_1 = document.getElementById('allow_auto_fill_remainder_1');
                            var is_auto_fill_checked_2 = document.getElementById('allow_auto_fill_remainder_2');

                            if(is_auto_fill_checked_1.checked || is_auto_fill_checked_2.checked) {
                                allow_auto_fill_remainder = 1;
                            }

                            var remainder = $('#remainder_text_hidden').val();
                            var dataString = "remainder=" + remainder + "&auto_fill_remainder=" + allow_auto_fill_remainder + "&split_modal=" + "split_invoice_payment";

                            $.ajax({

                                type: "POST",
                                url: "./includes/ajax/add-split-pay",
                                data: dataString,
                                cache: false,
                                beforeSend: function() {
                                    $("button#add_split_pay_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Adding Row...</i>");
                                    $("button#add_split_pay_btn").attr("disabled", "disabled");
                                },
                                success: function(d) {
                                    $('div#return_server_msg').fadeIn('slow').html(d);
                                    $("button#add_split_pay_btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Add More");
                                    $("button#add_split_pay_btn").removeAttr("disabled");
                                },
                                error: function(d) {
                                    $("button#add_split_pay_btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Add More");
                                    $("button#add_split_pay_btn").removeAttr("disabled");
                                    toastr.error("Something went wrong!");
                                }
                            });
                            return false;

                        }

                        function apply_customer_bal(){

                            var allow_auto_fill_remainder = 0;
                            var is_auto_fill_checked_1 = document.getElementById('allow_auto_fill_remainder_1');
                            var is_auto_fill_checked_2 = document.getElementById('allow_auto_fill_remainder_2');
                            var customer_id = document.getElementById('customer_name').value;

                            if(is_auto_fill_checked_1.checked || is_auto_fill_checked_2.checked) {
                                allow_auto_fill_remainder = 1;
                            }

                            var remainder = $('#remainder_text_hidden').val();
                            var dataString = "remainder=" + remainder + "&customer_id=" + customer_id + "&auto_fill_remainder=" + allow_auto_fill_remainder + "&split_modal=" + "split_invoice_payment";

                            $.ajax({

                                type: "POST",
                                url: "./includes/ajax/apply-customer-balance",
                                data: dataString,
                                cache: false,
                                beforeSend: function() {
                                    $("button#apply_customer_balance_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Applying...</i>");
                                    $("button#apply_customer_balance_btn").attr("disabled", "disabled");
                                },
                                success: function(d) {
                                    $('div#return_server_msg').fadeIn('slow').html(d);
                                },
                                error: function(d) {
                                    $("button#apply_customer_balance_btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Apply Balance");
                                    $("button#apply_customer_balance_btn").removeAttr("disabled");
                                    toastr.error("Something went wrong!");
                                }
                            });
                            return false;

                        }
                    </script>

                    <hr/>

                    <div class="table-responsive">
                        <table id="split_table" class="display table dataTables table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th style="max-width:300px;">Payment Method</th>
                                    <th style="width:300px;">Name</th>
                                    <th style="width:250px;">Amount</th>
                                    <th>Date of Payment</th>
                                </tr>
                            </thead>
                            <tbody>

                                <tr>
                                    <td>
                                        <select style="width:200px;" class="select split_payment_method" name="split_payment_method[]" id="payment_method_for_split">
                                            <option value='' label="Choose Method">Choose Method</option>
                                            <?php echo get_payment_methods("", "list", "");?>
                                        </select>
                                    </td>

                                    <td> <input placeholder="Payer's name" id="split_payment_name" type="text" name="split_payment_name[]" class="split_payment_name form-control" style-="width:100px;"> </td>
                                    <td> <input placeholder="Amount paid" type="number" min="0.01" step='0.01' onkeydown='Calculate_Splits()' onkeyup='Calculate_Splits()' onchange='Calculate_Splits()' onclick='Calculate_Splits()' name="split_amount[]" class="split_amount form-control" style-="width:100px;"> </td>
                                    <td>
                                        <a style='position:absolute;' id='rm_1010' style='float:right' onclick="removeSplitRow('#rm_1010')" href='javascript:(void)'><span data-toggle='tooltip' title='Remove this row?' class='fa fa-trash-o text-danger'></span></a>
                                        <input style-='width:200px;' type="date" value="<?php echo date("Y-m-d");?>" class="payment_date form-control" name="split_payment_date[]" id="split_payment_date">
                                    </td>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p>
                        <b>TOTAL: </b> <span id="total_split">0.00</span><br/>
                        <b><span id='remainder_text'> <b class='text-info'>REMAINDER:</b></span> </b> <span id="calc_total_remaining">0.00</span><br/>
                        <b>INVOICE VALUE: </b> <span id="total_invoice_value">0.00</span>
                    </p>

                    <input type="hidden" step='0.01' id="total_split_amount" name="total_split_amount" class="form-control" value="0">
                    <input type="hidden" step='0.01' id="total_invoice_value_hidden" name="" class="form-control" value="0">
                    <input type="hidden" step='0.01' id="remainder_text_hidden" name="" class="form-control" value="0">

                    <div id="split_response"></div>

                    <script>

                        function removeSplitRow(id){
                            $("table").on('click', id, function () {
                                $(this).parent().parent('tr').remove();
                                Calculate_Splits();
                            });
                        }

                        function removeSplitRow2(id){
                            $("table").on('click', id, function () {
                                $(this).parent().parent('tr').remove();
                                $("button#apply_customer_balance_btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Apply Balance");
                                $("button#apply_customer_balance_btn").removeAttr("disabled");
                                $("button#apply_customer_balance_btn").attr("onclick", "apply_customer_bal()");
                                $("#customer_bal_action_btn").html("");
                                Calculate_Splits();
                            });
                        }

                        function Calculate_Splits(){

                            var split_amount = document.getElementsByClassName('split_amount');
                            var total_amount = 0;

                            for (var ii = 0; ii < split_amount.length; ii++) {
                                if(parseFloat(split_amount[ii].value))
                                total_amount += parseFloat(split_amount[ii].value);
                            }

                            document.getElementById('total_split_amount').value = +total_amount.toFixed(3);

                            total_split = +total_amount.toFixed(3);
                            invoice_val = document.getElementById('total_invoice_value_hidden').value;

                            var calc_total_remaining = (invoice_val-total_split).toFixed(3);

                            var method_value = document.getElementById("payment_method");


                            // if(method_value.value == "split") {
                                if(calc_total_remaining < 0) {
                                    document.getElementById('remainder_text').innerHTML = "<b class='text-danger'>EXCESS: </b>";
                                    if(calc_total_remaining != 0) {
                                        document.getElementById('excess_payment_front_end_title').innerHTML = 'EXCESS: ';
                                        document.getElementById('excess_payment_front_end_container').className = 'text-danger alert-danger';
                                        document.getElementById('excess_payment_front_end_container').removeAttribute('style');
                                        // document.getElementById('excess_payment_front_end_container').style.display = 'none';
                                        document.getElementById('excess_payment_front_end').innerHTML = "₦"+Math.abs(calc_total_remaining).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                                    } else {
                                        document.getElementById('excess_payment_front_end_container').style.display = 'none';
                                    }
                                } else {
                                    document.getElementById('remainder_text').innerHTML = "<b class='text-info'>REMAINDER: </b>";
                                    if(calc_total_remaining != 0) {
                                        document.getElementById('excess_payment_front_end_title').innerHTML = 'REMAINDER: ';
                                        document.getElementById('excess_payment_front_end_container').removeAttribute('style');
                                        document.getElementById('excess_payment_front_end_container').className = 'text-info alert-info';
                                        // document.getElementById('excess_payment_front_end_container').style.display = 'none';
                                        // document.getElementById('excess_payment_front_end').innerHTML = "₦0";
                                        document.getElementById('excess_payment_front_end').innerHTML = "₦"+Math.abs(calc_total_remaining).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                                    } else {
                                        document.getElementById('excess_payment_front_end_container').style.display = 'none';
                                    }
                                }

                                if(total_split != invoice_val) {
                                    $("div#split_response").html("<span class='text-danger'> <span class='fa fa-exclamation-triangle'></span> <b>Total split value is not corresponding with invoice value </b></span>");
                                } else {
                                    $("div#split_response").html("");
                                }

                                var trim_val = +total_amount.toFixed(3);
                                document.getElementById('total_split').innerHTML = trim_val.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                                document.getElementById('calc_total_remaining').innerHTML = Math.abs(calc_total_remaining).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                                document.getElementById('remainder_text_hidden').value = calc_total_remaining;
                            // } else {
                            //     document.getElementById('excess_payment_front_end_container').setAttribute('style', 'display:none;');
                            // }
                        }

                    </script>

                    <div id="return_server_msg"></div>

                </div>

                <div class="modal-footer">
                    <p>
                        <label><b><input checked type="checkbox" id="allow_auto_fill_remainder_2"> Allow auto fill remainder</b></label> <br/>
                        <button onclick="add_split_payment()" id="add_split_pay_btn" class="btn btn-danger" type="button"> <span class="fa fa-plus"></span> Add More </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </p>
                </div>

            </div>
        </div>
    </div>
<?php } ?>


<?php if(strpos($_SERVER['PHP_SELF'], "edit-receipt.php") == true && isset($_GET['id'])){?>

    <div class="modal fade" id="edit_split_invoice_payment" tabindex="-1" role="dialog" aria-labelledby="edit_split_invoice_payment_" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="edit_split_invoice_payment_">Split Payment</h5>

                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>

                <form method="POST" id="update_split_data">

                    <div class="modal-body">

                        <label><b><input checked type="checkbox" id="allow_auto_fill_remainder_1"> Allow auto fill remainder</b></label> <br/>
                        <p> <button onclick="add_split_payment()" id="add_split_pay_btn" class="btn btn-danger" type="button"> <span class="fa fa-plus"></span> Add More </button> </p>

                        <script>
                            function add_split_payment(){

                                var allow_auto_fill_remainder = 0;
                                var is_auto_fill_checked_1 = document.getElementById('allow_auto_fill_remainder_1');
                                var is_auto_fill_checked_2 = document.getElementById('allow_auto_fill_remainder_2');

                                if(is_auto_fill_checked_1.checked || is_auto_fill_checked_2.checked) {
                                    allow_auto_fill_remainder = 1;
                                }

                                var remainder = $('#remainder_text_hidden').val();
                                var dataString = "remainder=" + remainder + "&auto_fill_remainder=" + allow_auto_fill_remainder + "&split_modal=" + "edit_split_invoice_payment";

                                $.ajax({
                                    type: "POST",
                                    url: "./includes/ajax/add-split-pay",
                                    data: dataString,
                                    cache: false,
                                    beforeSend: function() {
                                        $("button#add_split_pay_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Adding Row...</i>");
                                        $("button#add_split_pay_btn").attr("disabled", "disabled");
                                    },
                                    success: function(d) {
                                        $('div#return_server_msg').fadeIn('slow').html(d);
                                        $("button#add_split_pay_btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Add More");
                                        $("button#add_split_pay_btn").removeAttr("disabled");
                                    },
                                    error: function(d) {
                                        $("button#add_split_pay_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Add More");
                                        $("button#add_split_pay_btn").removeAttr("disabled");
                                        toastr.error("Something went wrong!");
                                    }
                                });
                                return false;

                            }
                        </script>

                        <hr/>

                        <div id="error_balance_account_trigger"></div>

                        <div class="table-responsive">
                            <table id="split_table" class="display table dataTables table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width:30%;">Payment Method</th>
                                        <th style="width:30%;">Name</th>
                                        <th style="width:25%;">Amount</th>
                                        <th>Date of Payment</th>
                                    </tr>
                                </thead>
                                <tbody id="split_data">
                                    <?php get_splitted_payments("tbl_list", get_invoice_data(@$_GET['id'], "id"), get_invoice_data(@$_GET['id'], "invoice_number"), ""); ?>
                                </tbody>
                            </table>
                        </div>

                        <?php $total_split = get_splitted_payments("", get_invoice_data(@$_GET['id'], "id"), get_invoice_data(@$_GET['id'], "invoice_number"), "amount");?>
                        <?php $excess = ($total_split - get_invoice_data(@$_GET['id'], "total_paid"));?>
                        <?php $total_invoice_value = (get_invoice_data(@$_GET['id'], "total_paid"));?>
                        <?php $err_msg = "<span class='text-danger'> <span class='fa fa-exclamation-triangle'></span> <b>Total split value is not corresponding with invoice value </b></span>";?>

                        <p>
                            <b>TOTAL: </b> <span id="total_split_1"><?php echo custom_money_format(get_splitted_payments("", get_invoice_data(@$_GET['id'], "id"), get_invoice_data(@$_GET['id'], "invoice_number"), "amount"));?></span>
                            <br/>
                            <b> <span id='remainder_text'> <?php if($excess > 0) { ?> <b class='text-danger'>EXCESS:</b> <?php } else { ?> <b class='text-info'>REMAINDER:</b> <?php } ?> </span> </b>
                            <span id="calc_total_remaining"> <?php echo custom_money_format(abs($excess));?> </span><br/>
                            <b>INVOICE VALUE: </b> <span id="total_invoice_value_1"><?php echo custom_money_format(get_invoice_data(@$_GET['id'], "total_paid"));?></span>
                        </p>

                        <input type="hidden" step='0.01' id="total_invoice_value_hidden_1" name="" class="form-control" value="<?php echo get_invoice_data(@$_GET['id'], "total_paid");?>">
                        <div id="split_response_1"> <?php if($excess != 0) { echo $err_msg; } ?> </div>

                        <input type="hidden" step='0.01' id="total_split_amount_1" value="<?php echo get_splitted_payments("", get_invoice_data(@$_GET['id'], "id"), get_invoice_data(@$_GET['id'], "invoice_number"), "amount");?>" name="total_split_amount_1" class="form-control">

                        <input type="hidden" value="<?php echo get_invoice_data(@$_GET['id'], "id");?>" name="split_invoice_id" class="form-control">
                        <input type="hidden" value="<?php echo get_invoice_data(@$_GET['id'], "invoice_number");?>" name="split_invoice_number" class="form-control">
                        <input type="hidden" step='0.01' id="remainder_text_hidden" name="" class="form-control" value="0">

                        <script>

                            function removeSplitRow(id){
                                $("table").on('click', id, function () {
                                    $(this).parent().parent('tr').remove();
                                    Calculate_Splits();
                                });
                            }

                            function Calculate_Splits(){

                                var split_amount = document.getElementsByClassName('split_amount');
                                var total_amount = 0;
                                
                                for (var ii = 0; ii < split_amount.length; ii++) {
                                    if(parseFloat(split_amount[ii].value))
                                    total_amount += parseFloat(split_amount[ii].value);
                                }
                                
                                document.getElementById('total_split_amount_1').value = +total_amount.toFixed(3);

                                total_split = +total_amount.toFixed(3);
                                invoice_val = document.getElementById('total_invoice_value_hidden_1').value;
                                var calc_total_remaining = (invoice_val-total_split).toFixed(3);
                                var method_value = document.getElementById("payment_method");

                                if(method_value.value == "split") {

                                    if(calc_total_remaining < 0) {
                                        document.getElementById('remainder_text').innerHTML = "<b class='text-danger'>EXCESS: </b>";
                                        if(calc_total_remaining != 0) {
                                            document.getElementById('excess_payment_front_end_title').innerHTML = 'EXCESS: ';
                                            document.getElementById('excess_payment_front_end_container').className = 'text-danger alert-danger';
                                            document.getElementById('excess_payment_front_end_container').removeAttribute('style');
                                            // document.getElementById('excess_payment_front_end_container').style.display = 'none';
                                            document.getElementById('excess_payment_front_end').innerHTML = "₦"+Math.abs(calc_total_remaining).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                                        } else {
                                            document.getElementById('excess_payment_front_end_container').style.display = 'none';
                                        }

                                    } else {
                                        document.getElementById('remainder_text').innerHTML = "<b class='text-info'>REMAINDER: </b>";
                                        if(calc_total_remaining != 0) {
                                            document.getElementById('excess_payment_front_end_title').innerHTML = 'REMAINDER: ';
                                            document.getElementById('excess_payment_front_end_container').removeAttribute('style');
                                            document.getElementById('excess_payment_front_end_container').className = 'text-info alert-info';
                                            // document.getElementById('excess_payment_front_end_container').style.display = 'none';
                                            // document.getElementById('excess_payment_front_end').innerHTML = "₦0";
                                            document.getElementById('excess_payment_front_end').innerHTML = "₦"+Math.abs(calc_total_remaining).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                                        } else {
                                            document.getElementById('excess_payment_front_end_container').style.display = 'none';
                                        }
                                    }

                                    if(total_split != invoice_val) { 
                                        $("div#split_response_1").html("<?php echo  $err_msg;?>");
                                        // $("div#split_response_1").html("<span class='text-danger'> <span class='fa fa-exclamation-triangle'></span> Total split value must be same with invoice value </span>");
                                    } else {
                                        $("div#split_response_1").html("");
                                    }

                                    var trim_val = +total_amount.toFixed(3);
                                    document.getElementById('total_split_1').innerHTML = trim_val.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                                    document.getElementById('calc_total_remaining').innerHTML = Math.abs(calc_total_remaining).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                                    document.getElementById('remainder_text_hidden').value = calc_total_remaining;

                                } else {
                                    document.getElementById('excess_payment_front_end_container').setAttribute('style', 'display:none;');
                                }

                            }

                        </script>

                        <button type="button" onclick="update_Split_Data()" id="update_split_btn" class="float-risght btn btn-sm btn-success"><span class="fa fa-save"></span> Save Changes</button>

                        <div id="return_server_msg"></div>

                    </div>

                </form>

                <div class="modal-footer">
                    <label><b><input checked type="checkbox" id="allow_auto_fill_remainder_2"> Allow auto fill remainder</b></label>
                    <button onclick="add_split_payment()" id="add_split_pay_btn" class="btn btn-danger" type="button"> <span class="fa fa-plus"></span> Add More </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="edit_receipt_item" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="">Edit Item</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>

                <form method="POST" id="update-invoice-item">

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

    <div class="modal fade" id="add_more_receipt_item" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">

                    <h5 class="modal-title" id="">Add more Item</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>

                <form method="POST" id="add-more-invoice-item">

                    <div class="modal-body">

                        <div class='row'>
                            <div class='col-md-6'>
                                <div class='form-group'>
                                    <select style='width:200px;' class='select' name='invoice_item'>
                                        <option value='' label='Choose Method'>Choose Products</option>
                                        <?php echo get_payable_items($product_id);?>
                                    </select>
                                </div>
                            </div>

                            <div class='col-md-6'>
                                <div class='form-group'>
                                    <div class='input-group'>
                                        <span class='input-group-text'> <b>₦</b> </span>
                                        <input type='number' name='invoice_item_amount' value='<?php echo $invoice_item_amount;?>' min='0.01' step='0.01' class='form-control'>
                                    </div>
                                </div>
                            </div>

                            <div class='col-md-12' style='margin-top:10px;'>
                                <button id='add-invoice-item-btn' class='btn btn-sm btn-primary'>
                                    <span class='fa fa-plus'></span> Add item
                                </button>
                                <input type='hidden' name='invoice_id' value='<?php echo get_invoice_data(@$_GET['id'], "id");?>' class='form-control'>
                                <input type='hidden' name='invoice_number' value='<?php echo get_invoice_data(@$_GET['id'], "invoice_number");?>' class='form-control'> 
                            </div>
                        </div>

                        <div id="return_server_msg"></div>

                    </div>

                </form>
            </div>
        </div>
    </div>

<?php } ?>