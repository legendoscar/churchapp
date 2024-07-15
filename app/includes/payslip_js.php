
<script>

    function deleteRow(rowid) {
        var row = document.getElementById(rowid);
        row.parentNode.removeChild(row);

        return autoCals();
    }


    function NewEarningsTableRow(){

        var dataString = "rows_number=" + $("#earnings_rows_number").val();
        $.ajax({
            type: "POST",
            url: "./includes/ajax/add-earnings-row",
            data: dataString,
            cache: false,
            beforeSend: function() {
                $("button#add_more_earnings_btn").html("<span class='fa fa-spin fa-spinner'></span>");
                $("button#add_more_earnings_btn").attr("disabled", "disabled");
            },
            success: function(d) {
                $('div#return_server_msg').fadeIn('slow').html(d);
                $("button#add_more_earnings_btn").html("<span class='fa fa-plus-circle'></span>");
                $("button#add_more_earnings_btn").removeAttr("disabled", "disabled");
            },
            error: function(d) {
                toastr.error("Something went wrong!");
                $("button#add_more_earnings_btn").html("<span class='fa fa-plus-circle'></span>");
                $("button#add_more_earnings_btn").removeAttr("disabled", "disabled");
            }
        });
        return false;
    }



    function NewDeductionsTableRow(){

        var dataString = "rows_number=" + $("#deductions_rows_number").val();

        $.ajax({
            type: "POST",
            url: "./includes/ajax/add-deductions-row",
            data: dataString,
            cache: false,
            beforeSend: function() {
                $("button#add_more_deductions_btn").html("<span class='fa fa-spin fa-spinner'></span>");
                $("button#add_more_deductions_btn").attr("disabled", "disabled");
            },
            success: function(d) {
                $('div#return_server_msg').fadeIn('slow').html(d);
                $("button#add_more_deductions_btn").html("<span class='fa fa-plus-circle'></span>");
                $("button#add_more_deductions_btn").removeAttr("disabled", "disabled");
            },
            error: function(d) {
                toastr.error("Something went wrong!");
                $("button#add_more_deductions_btn").html("<span class='fa fa-plus-circle'></span>");
                $("button#add_more_deductions_btn").removeAttr("disabled", "disabled");
            }
        });
        return false;
    }


    function activate_split(){
        var method_value = document.getElementById("payment_method");
        var split_btn = document.getElementById("split_modal_btn");

        if(method_value.value == "split"){
            split_btn.removeAttribute("disabled");
            split_btn.setAttribute("data-toggle", "modal");
            split_btn.setAttribute("data-target", "#split_invoice_payment");
            document.getElementById("payment_date_form").style.display = "none";
            // document.getElementById("payment_name_form").style.display = "none";
            document.getElementById("payment_date").removeAttribute("required");
            document.getElementById('excess_payment_front_end_container').removeAttribute('style');
            Calculate_Splits();

        } else {
            split_btn.removeAttribute("data-toggle");
            split_btn.removeAttribute("data-target");
            split_btn.setAttribute("disabled", "disabled");
            document.getElementById("payment_date_form").style.display = "block";
            // document.getElementById("payment_name_form").style.display = "block";
            document.getElementById("payment_date").setAttribute("required", "required");
            document.getElementById('excess_payment_front_end_container').setAttribute('style', 'display:none;');
        }
    }

    function getProductPrice(id){

        var checkBox = document.getElementById("switchWR");

        if (checkBox.value == "retail"){
            var load_url = "./includes/ajax/get-product-retail-price";
        } else if (checkBox.value == "h_wholesale"){
            var load_url = "./includes/ajax/half_wholesale_price";
        } else {
            var load_url = "./includes/ajax/get-product-price";
        }

        var dataString = "product_id=" + $("#product_id").val() + 
            "&quantity_id=" + document.getElementById("for_qty_id").value + 
            "&amount_id=" + document.getElementById("for_amount_id").value + 
            "&h_total=" + document.getElementById("for_h_total").value + 
            "&discount_id=" + document.getElementById("for_discount_id").value + 
            "&discount_id2=" + document.getElementById("discount_id").value + 
            "&switch_id=" + document.getElementById("switch_id").value + 
            "&discount_pattern=" + document.getElementById("pattern").value + 
            "&for_h_input_total=" + "null" + 
            "&switchWR=" + document.getElementById("switchWR").value + 
            "&wr_btn=" + document.getElementById("wr_btn").value + 
            "&rate_id=" + document.getElementById("for_rate_id").value;

        $.ajax({
            type: "POST",
            url: load_url,
            data: dataString,
            cache: false,
            beforeSend: function() {
                $("span#loader_"+id).html("<br/><span class='text-danger fa fa-spin fa-spinner'></span> <i class='text-danger'><b>loading...</b></i>");
            },
            success: function(d) {
                $('div#return_server_msg').fadeIn('slow').html(d);
                $("span#loader_"+id).html("");
            },
            error: function(d) {
                $("span#loader_"+id).html("<br/><span class='text-danger'><b>Error!. Try again.</b><span>");
                toastr.error("Something went wrong!");
            }
        });
        return false;

    }

    function StartAccounting(){
        autoCals();
    }

    function between(x, min, max) {
        return x >= min && x <= max;
        return calQuantity();
    }

    function ShowAdditionalText(id){
        var target = id.toString();
        $("#ShowAdditionalText_"+target).toggle();
    }

    function StartAccounting2(){
        var get_qty = document.getElementById("product_qty_45678").value;
        var get_amount_val = document.getElementById("product_total").textContent;
        var get_rate_value = parseFloat(document.getElementById("product_rate").value);
        var get_discount = parseFloat(document.getElementById("discount_id").value);
        var get_discount_value = document.getElementById("discount_id").value;
        var actual_amount = (get_rate_value * get_qty);

        //
        var default_price = document.getElementById("product_rate").value;
        var new_qty = get_qty;
        var pre_discount = get_discount_value;

        if( (between(get_discount, 0, actual_amount) && actual_amount > 0)){


            var total_discounted_amount = 0;
            <?php
                // CHECK IF DISCOUNT PATTERN IS UNIT-DISCOUNT-CALCULATION ( (Actual-Rate * qty) - (Unit Discount * total_qty) )
                if(get_company_data("discount_pattern") == "cal_by_unit_discount") { ?>
                    var total_discounted_amount = (default_price * new_qty) - (pre_discount * new_qty);
                <?php
                // OR
                // TOTAL-DISCOUNT-CALCULATE ( (TOTAL AMOUNT) - (Total Discount) )
                } else if(get_company_data("discount_pattern") == "cal_by_total_discount") { ?>
                    var total_discounted_amount = ((default_price * new_qty) - pre_discount);
                <?php
                }
            ?>

            var get_new_discount = (total_discounted_amount);
            // var get_new_discount = actual_amount - get_discount_value;
            var new_val = +get_new_discount.toFixed(3);
            document.getElementById('product_total').innerHTML = new_val.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",").valueOf();
            document.getElementById('h_total').value = +get_new_discount.toFixed(3).toString();

        } else {
            document.getElementById('product_total').innerHTML = "0.00";
            document.getElementById('h_total').value = "0.00";
        }

        
        autoCals();
        return calQuantity();
    }

    function calQuantity(){

        var get_qty = document.getElementById("product_qty_45678").value;
        var get_amount_val = document.getElementById("product_total").textContent;
        var get_rate_value = parseFloat(document.getElementById("product_rate").value);
        var get_discount = parseFloat(document.getElementById("discount_id").value);

        var get_discount_value = document.getElementById("discount_id").value;

        var actual_amount = (get_rate_value * get_qty);

        if( (between(get_discount, 0, actual_amount) && actual_amount > 0)){
            return autoCals();


        }
    }


    function autoCals() {

        // CALCULATE FOR EARNINGS

        // TOTAL EARNINGS (START)
            var elems_earnings_amount_total = document.getElementsByClassName('earnings_value');
            var total_earnings_amount_each = 0;
            for (var ii = 0; ii < elems_earnings_amount_total.length; ii++) {
                if(parseFloat(elems_earnings_amount_total[ii].value))
                total_earnings_amount_each += parseFloat(elems_earnings_amount_total[ii].value);
            }

            document.getElementById('total_earnings').innerHTML = total_earnings_amount_each.valueOf().toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
            var new_earnings_val = +total_earnings_amount_each.toFixed(2);
            document.getElementById('h_total_earnings').value = new_earnings_val.toString();
            // document.getElementById('total_invoice_value').innerHTML = new_val.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",").valueOf();
            // document.getElementById('total_invoice_value_hidden').value = new_val.toString();

            let div = document.querySelector('#amount_in_words')
            // var amount_in_word = number_to_words( parseFloat(document.getElementById("total_paid").textContent.toString().replace(/\,/g,'')) );

            // if(amount_in_word != ""){ div.innerHTML = amount_in_word+"  naira"; }
            // else { div.innerHTML = amount_in_word; }

            // Calculate_Splits();

        // TOTAL EARNINGS (END)


        // TOTAL deductions (START)
            var elems_deductions_amount_total = document.getElementsByClassName('deductions_value');
            var total_deductions_amount_each = 0;
            for (var ii = 0; ii < elems_deductions_amount_total.length; ii++) {
                if(parseFloat(elems_deductions_amount_total[ii].value))
                total_deductions_amount_each += parseFloat(elems_deductions_amount_total[ii].value);
            }

            document.getElementById('total_deductions').innerHTML = total_deductions_amount_each.valueOf().toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
            var new_deductions_val = +total_deductions_amount_each.toFixed(2);
            document.getElementById('h_total_deductions').value = new_deductions_val.toString();
            // document.getElementById('total_invoice_value').innerHTML = new_val.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",").valueOf();
            // document.getElementById('total_invoice_value_hidden').value = new_val.toString();

            // let div = document.querySelector('#amount_in_words')
            // var amount_in_word = number_to_words( parseFloat(document.getElementById("total_paid").textContent.toString().replace(/\,/g,'')) );

            // if(amount_in_word != ""){ div.innerHTML = amount_in_word+"  naira"; }
            // else { div.innerHTML = amount_in_word; }

            // Calculate_Splits();
        // TOTAL deductions (END)

        // GRAND TOTAL

            document.getElementById('total_net_pay').innerHTML = (new_earnings_val-new_deductions_val).valueOf().toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
            var grand_total = (new_earnings_val-new_deductions_val).toFixed(2);
            document.getElementById('h_total_net_pay').value = grand_total.toString();
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