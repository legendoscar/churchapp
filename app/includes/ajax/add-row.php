<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {

        require "../../includes/check_if_login.php";
        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/additional_function.php";
        require "../../includes/function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "create-receipt", "", "") == "allowed"){
            if($_POST['rows_number'] > 0){

                for($count = 0; $count < $_POST['rows_number']; $count++){?>

                    <?php $row_tracking = md5(password_hash(rand(45678, 456789), PASSWORD_DEFAULT)); ?>
                    <?php $row_tracking2 = rand(1234, 456789); ?>

                    <script>

                        var table = document.getElementById("invoice_table");
                        var row = table.insertRow(table.rows.length-0); // var row = table.insertRow(table.rows.length-1);

                        row.setAttribute("id", "del_<?php echo $row_tracking;?>");

                        // AUTO CREATE TABLE COLUMNS
                        var ceil_1 = row.insertCell(0);
                        var ceil_2 = row.insertCell(1);
                        // var ceil_3 = row.insertCell(2);
                        // var ceil_4 = row.insertCell(3);
                        // var ceil_5 = row.insertCell(4);

                        // discount_pattern_input = "<input type='hidden' value='<?php // echo (auto_encode_discount_pattern(get_company_data("discount_pattern")));?>' id='discount_pattern_<?php // echo $row_tracking;?>' name='discount_pattern[]' class='discount_pattern form-control'>";
                        discount_pattern_input = "<input type='hidden' value='<?php echo (($_POST['discount_pattern']));?>' id='discount_pattern_<?php echo $row_tracking;?>' name='discount_pattern[]' class='discount_pattern form-control'>";

                        // TABLE COLUMN DATA
                        appendHidden = "<input type='hidden' name='for_qty_id[]' value='product_qty_<?php echo $row_tracking;?>' id='for_qty_id_<?php echo $row_tracking;?>' class='qty form-control'>\
                        <input type='hidden' name='for_rate_id[]' value='product_rate_<?php echo $row_tracking;?>' id='for_rate_id_<?php echo $row_tracking;?>' class='form-control'>\
                        <input type='hidden' name='for_amount_id[]' value='product_total_<?php echo $row_tracking;?>' id='for_amount_id_<?php echo $row_tracking;?>' class='form-control'>\
                        <input type='hidden' name='for_h_total[]' value='h_total_<?php echo $row_tracking;?>' id='for_h_total_<?php echo $row_tracking;?>' class='form-control'>\
                        <input type='hidden' value='discount_id_<?php echo $row_tracking;?>' id='for_discount_id_<?php echo $row_tracking;?>' class='form-control'>\
                        <input type='hidden' value='switchWR_<?php echo $row_tracking;?>' id='switch_id_<?php echo $row_tracking;?>' name='switch_id[]' class='form-control'>\
                        <input type='hidden' value='wr_btn_text_<?php echo $row_tracking;?>' id='wr_btn_<?php echo $row_tracking;?>' name='wr_btn[]' class='form-control'>";

                        // additional = "<input title='Write additional text...' style='display:none;background-color:#fff;' placeholder='Write here...' id='ShowAdditionalText_<?php echo $row_tracking2;?>' type='text' name='additional[]' class='form-control'>";

                        // <?php // if(get_company_data("show_invoice_item_addtional_text") == "yes") { ?> additional_btn = "<a onclick='ShowAdditionalText(<?php // echo $row_tracking2;?>)' title='Additional Text' style='float:right' href='javascript:(void)' class='btn-primary btn-xs'><span class='fa fa-pen'></span></a>"; <?php // } else {?> additional_btn = ""; <?php // } ?>
                        // ceil_1.innerHTML = "aa";
                        // ceil_1.innerHTML = "<select class='form-control select product_id_loader' name='product_id[]' id='-product_id_<?php // echo $row_tracking;?>' onchange='-getProductPrice_<?php echo $row_tracking;?>()'><option value='' label='Choose Product'>Choose Product</option><?php // echo get_grouped_brand_product_list();?></select>";

                        ceil_1.innerHTML = "<select class='form-control select product_id_loader' name='product_id[]' onchange-='getProductPrice_<?php echo $row_tracking;?>()'><option value='' label='Choose Product'>-- Select --</option><?php echo get_payable_items('');?></select>"+appendHidden;
                        ceil_2.innerHTML = "<div class='input-group'><span class='input-group-text'><b>₦</b></span>\
                        <input onchange='StartAccounting_<?php echo $row_tracking;?>()' onkeyup='StartAccounting_<?php echo $row_tracking;?>()' onkeydown='StartAccounting_<?php echo $row_tracking;?>()' value='0.00' name='h_total[]' required min='0' type='number' step='0.01' class='amount h_total_<?php echo $row_tracking;?> form-control' id='h_total_<?php echo $row_tracking;?>'>\
                        <a style='float:right' onclick=deleteRow('del_<?php echo $row_tracking;?>') href='#!'><span data-toggle='tooltip' title='Remove this row?' class='btn  fa fa-trash-o text-danger'></span></a></div>";

                        // ceil_3.innerHTML = "<input readonly step='0.001' type='number' value='0' min='0.001' name='product_rate[]' id='product_rate_<?php // echo $row_tracking;?>' class='form-control' placeholder='Rate'>";
                        <?php
                            // if(get_company_data("discount_input_type") == "form_select") {?>
                                // discount_field = "<select onchange='StartAccounting2_<?php // echo $row_tracking;?>()' name='discount_id[]' id='discount_id_<?php echo $row_tracking;?>' class='form-control' style='width:200px;' required> <option value='0' label='Choose Method'>No discount</option><?php // echo get_discount_values('');?></select>";
                                <?php
                                // } else { ?>
                                // discount_field = "<input value='0' name='discount_id[]' required min='0' type='number' step='0.001' onchange='StartAccounting2()' onkeydown='StartAccounting2()' onkeyup='StartAccounting2()' class='discount_id form-control' placeholder='Discount' id='discount_id' aria-label='Discount' aria-describedby='discount'>";
                                // discount_field = "<input onchange='StartAccounting2_<?php // echo $row_tracking;?>()' onkeyup='StartAccounting2_<?php echo $row_tracking;?>()' onkeydown='StartAccounting2_<?php echo $row_tracking;?>()' type='number' min='0' step='0.001' name='discount_id[]' id='discount_id_<?php // echo $row_tracking;?>' class='form-control' value='0' placeholder='Discount'>";
                                <?php
                            // }
                        ?>

                        // <input onchange='StartAccounting2_<?php // echo $row_tracking;?>()' onkeyup='StartAccounting2_<?php // echo $row_tracking;?>()' onkeydown='StartAccounting2_<?php // echo $row_tracking;?>()' type='number' min='0' step='0.001' name='discount_id[]' id='discount_id_<?php // echo $row_tracking;?>' class='form-control' value='0' placeholder='Discount'>

                        // append1 = "<div class='row'> <div class='col-sm-12'>" + discount_field + "</div>";
                        // append2 = "<div class='col-sm-6' style='display:none;' id='switch_holder_<?php echo $row_tracking;?>'><select class='product_id_loader' id='switchWR_<?php echo $row_tracking;?>' name='switchWR[]' onchanges='Load_Retail_<?php echo $row_tracking;?>()' required> <option value='' label='Choose Method'>Choose Method</option> <option value='retail' label='Is Retail'>Is Retail</option> <option selected value='wholesale' label='Is Wholesale'>Is Wholesale</option> <!--<option value='h_wholesale' label='Half QTY sold as Wholesale'> Half QTY sold as Wholesale </option>--> </select> </div></div>";
                        // ceil_4.innerHTML = (append1+append2);

                        // ceil_5.innerHTML = "<b>₦</b><b class='amount' id='product_total_<?php echo $row_tracking;?>'>0.00</b> \
                        // <input value='0' name='h_total[]' required min='0' type='hidden' step='0.001' class='h_total_<?php echo $row_tracking;?> form-control' placeholder='Discount' id='h_total_<?php echo $row_tracking;?>'>\
                        // <a style='float:right' onclick=deleteRow('del_<?php echo $row_tracking;?>') href='#!'><span data-toggle='tooltip' title='Remove this row?' class='btn  fa fa-times text-danger'></span></a>\
                        // <span id='loader_<?php echo $row_tracking;?>'></span>";

                        function getProductPrice_<?php echo $row_tracking;?>(){

                            var dataString = "product_id=" + $("#product_id_<?php echo $row_tracking;?>").val() +
                                // "&quantity_id=" + document.getElementById("for_qty_id_<?php // echo $row_tracking;?>").value +
                                "&amount_id=" + document.getElementById("for_amount_id_<?php echo $row_tracking;?>").value +
                                "&h_total=" + document.getElementById("for_h_total_<?php echo $row_tracking;?>").value +
                                "&discount_id=" + document.getElementById("for_discount_id_<?php echo $row_tracking;?>").value +
                                // "&switch_id=" + document.getElementById("switch_id_<?php // echo $row_tracking;?>").value +
                                // "&discount_pattern=" + document.getElementById("pattern").value +
                                // "&switchWR=" + document.getElementById("switchWR_<?php // echo $row_tracking;?>").value +
                                // "&for_h_input_total=" + "nulla" +
                                "&wr_btn=" + document.getElementById("wr_btn_<?php echo $row_tracking;?>").value +
                                "&rate_id=" + document.getElementById("for_rate_id_<?php echo $row_tracking;?>").value;

                            var load_url = "./includes/ajax/get-product-price";

                            $.ajax({
                                type: "POST",
                                url: load_url,
                                data: dataString,
                                cache: false,
                                beforeSend: function() {
                                    $("span#loader_<?php echo $row_tracking;?>").html("<br/><span class='text-danger fa fa-spin fa-spinner'></span> <i class='text-danger'><b>loading...</b></i>");
                                },
                                success: function(d) { 
                                    $('div#return_server_msg').fadeIn('slow').html(d);
                                    $("span#loader_<?php echo $row_tracking;?>").html("");
                                },
                                error: function(d) {
                                    $("span#loader_<?php echo $row_tracking;?>").html("<br/><span class='text-danger'><b>Error!. Try again.</b><span>");
                                    toastr.error("Something went wrong!");
                                }
                            });
                            return false;
                        }

                        function StartAccounting_<?php echo $row_tracking;?>(){
                            autoCals();
                        }

                        function StartAccounting2_<?php echo $row_tracking;?>(){
                            var get_qty = document.getElementById("product_qty_<?php echo $row_tracking;?>").value;
                            var get_amount_val = document.getElementById("product_total_<?php echo $row_tracking;?>").textContent;
                            var get_rate_value = parseFloat(document.getElementById("product_rate_<?php echo $row_tracking;?>").value);
                            var get_discount = parseFloat(document.getElementById("discount_id_<?php echo $row_tracking;?>").value);

                            var get_discount_value = document.getElementById("discount_id_<?php echo $row_tracking;?>").value;
                            var actual_amount = (get_rate_value * get_qty);


                            //
                            var default_price = get_rate_value;
                            var new_qty = get_qty;
                            var pre_discount = get_discount_value;

                            if( (between(get_discount, 0, actual_amount) && actual_amount > 0)){

                                var total_discounted_amount = 0;
                                <?php
                                    // CHECK IF DISCOUNT PATTERN IS UNIT-DISCOUNT-CALCULATION ( (Actual-Rate * qty) - (Unit Discount * total_qty) )
                                    if(auto_decode_discount_pattern($_POST['discount_pattern']) == "cal_by_unit_discount") { ?>
                                        var total_discounted_amount = (default_price * new_qty) - (pre_discount * new_qty);
                                    <?php
                                    // OR
                                    // TOTAL-DISCOUNT-CALCULATE ( (TOTAL AMOUNT) - (Total Discount) )
                                    } else if(auto_decode_discount_pattern($_POST['discount_pattern']) == "cal_by_total_discount") { ?>
                                        var total_discounted_amount = ((default_price * new_qty) - pre_discount);
                                    <?php
                                    }
                                ?>

                                var get_new_discount = (total_discounted_amount);
                                // document.getElementById('product_total_<?php //echo $row_tracking;?>').innerHTML = get_new_discount.toFixed(3).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",").valueOf();

                                // var get_new_discount = actual_amount - get_discount;
                                var new_val = +get_new_discount.toFixed(3);
                                document.getElementById('product_total_<?php echo $row_tracking;?>').innerHTML = new_val.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",").valueOf();
                                // document.getElementById('product_total_<?php echo $row_tracking;?>').innerHTML = get_new_discount.valueOf().toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                            } else {
                                document.getElementById('product_total_<?php echo $row_tracking;?>').innerHTML = "0.00";
                            }

                            autoCals();
                            
                            document.getElementById('h_total_<?php echo $row_tracking;?>').value = +get_new_discount.toFixed(3).toString();
                            document.getElementById('h_total_paid').value = +get_new_discount.valueOf().toString();
                            
                            document.getElementById('h_qty').value = get_qty;

                            autoCals();
                        }

                        autoCals();

                    </script>

                    <!-- <script src="./assets/js/select2.min.js"></script> -->
                    <script src="./assets/js/custom-select.js"></script>

                <?php
                    if($count == 0){
                        echo server_response("success", "Success! ".$_POST['rows_number']." row(s) has been added!", "200");
                    }
                }
            } else {
                echo server_response("danger", "Please enter at least one digit ", "200");
            }
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to this action. Please if you think this was a mistake, contact your administrator.", "100"); 
        }
    }
?>