<?php session_start();?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="assets/images/favicon.ico" rel="icon" />
    <link rel="stylesheet" href="./assets/pos/pos_css.css">
    <link rel="stylesheet" href="./assets/pos/a4_css.css">
    <?php require "./../includes/sanitizer.php";?>
    <?php require "./../includes/db-config.php";?>
    <?php require "./includes/check_if_login.php";?>
    <?php require "./includes/global_function.php";?>
    <?php require "./includes/additional_function.php";?>
    <?php require "./includes/function.php";?>
    <link rel="stylesheet" href="assets/vendors/fontawesome/css/all.min.css">

    <?php if(is_authorized($user_account_type, "print-receipt", "", "") === "allowed") {?>

    <title>POS Receipt - <?php echo get_company_data("company_name");?></title>
    <?php if(get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "id") != "not_found") {?>

    <link href="assets/images/favicon.ico" rel="icon" />
    <title>Invoice - <?php echo get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "invoice_number");?>  - <?php echo get_company_data("company_name");?> </title>
    <meta name="author" content="Digiweb Developers">
    <?php $invoice_number = get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "invoice_number");?>

    <?php
        $print_on = get_company_data("invoice_printout");
    ?>


</head>

    <body style="font-family:'Arial Blsack'">

        <div id="invoice-POS2">
            <div id="<?php echo $print_on;?>">

                <center id="top">
                    <!-- <div class="logo"></div> -->
                    <div class="info">
                        <h2 style="margin-bottom:-5px;">
                            <?php
                                if(get_company_data("show_logo") == "yes") {
                                    if(!empty(get_company_data("uploaded_logo")) && is_file("./assets/images/uploaded/".get_company_data("uploaded_logo")) ) { ?>
                                        <p style="margin-bottom:0px;"> <img style="width:60px;" src="./assets/images/uploaded/<?php echo get_company_data("uploaded_logo");?>"> </p>
                                    <?php } else {?>
                                        <p style="margin-bottom:0px;"> <img style="width:60px;" src="./assets/images/logo.png"> </p>
                                    <?php
                                    }
                                }
                            ?>
                        </h2>

                        <p align="right" style="margin-bottom:-4px;">
                            Receipt No: <?php echo $invoice_id = get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "id");?>
                            (Printed: <?php echo date("Y-m-d");?>)
                        </p>

                        <h2 class="inv-title" style="margin-bottom:-5px;">
                            <?php echo get_company_data("company_name");?>
                            <!-- <br/> <span style="fsont-size:8px;"> <?php //echo get_company_data("company_description");?>.</span> -->
                        </h2>
                    </div>
                    <!--End Info-->
                    <!-- <div id="mid" > -->
                        <div class="info info2">
                            <p> <?php echo get_company_data("company_address");?></br>
                            <?php echo get_company_data("company_phone1");?>, <?php echo get_company_data("company_phone2");?></br>
                            <!-- (<b>Invoice No:</b> <?php // echo get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "invoice_number");?>) </br> -->
                                (<?php echo get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "invoice_number");?>) </br>
                            </p>
                        </div>
                        <hr/>
                        <p align="left"  class="inv-section-2" style="font-weight:bolder;margin-bottom:3px;margin-top:-7px;">
                            <b>Received from</b> :
                                <?php $data1 = strtoupper(get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "customer_name"));?>
                                <?php
                                    if(!in_array(get_customer_data(get_invoice_data_by_number($invoice_number, "customer_name"), "customer_id"), array("error", "not_found")) ) {
                                        $customer_id = get_customer_data2(get_invoice_data_by_number($invoice_number, "customer_name"), "customer_id");
                                        $customer_name = get_customer_data2(get_invoice_data_by_number($invoice_number, "customer_name"), "surname")." ".get_customer_data2(get_invoice_data_by_number($invoice_number, "customer_name"), "firstname")." ".get_customer_data2(get_invoice_data_by_number($invoice_number, "customer_name"), "othername");
                                        echo "<b>$customer_name</b>";
                                        // echo " $customer_id ($customer_name) ";
                                    } else {
                                        echo "<b>$data1</b>";
                                    }
                                ?>
                                </br>
                            <b>Phone</b> : <?php echo strtoupper(get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "customer_phone"));?></br>
                            <b>Method of Payment </b> :
                                <?php 
                                    if(get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "is_split") == "no"){
                                    echo
                                    strtoupper(
                                        get_payment_methods(
                                            get_invoice_data(
                                                SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])), "method_id"
                                            ), "", "methods"
                                        )
                                    );
                                } else if(get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "is_split") == "yes"){
                                    echo strtoupper(get_splitted_payments("linear_list", get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "id"), get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "invoice_number"), "method_id"));
                                }
                                ?>
                            </br>
                            <b>Transaction Date </b> : <?php echo date("Y-m-d h:i:sA", strtotime(get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "date_time")));?> </br>
                            <b>Issued By</b> : <?php echo strtoupper(get_user_data(get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "created_by"), 'firstname')). " ".strtoupper(get_user_data(get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "created_by"), 'surname'));?> </br>
                        </p>
                </center>

                <?php
                    $total_credit_sales = (total_split_payments_invoice_id("4", $invoice_id) + customers_by_payment_method_and_invoice(4, $invoice_id));
                    $total_invoice_value = get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "total_paid");
                    $total_balance_paid = ($total_invoice_value - $total_credit_sales);
                    $total_discount = get_invoice_transactions2(get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "id"), get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "invoice_number"), "discount");

                    $excess_payment = 0;
                    $amount_paid = $total_invoice_value;
                    $has_split_payment = get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "is_split");
                    $total_split_payments = get_splitted_payments("", $invoice_id, $invoice_number, "amount");
                    if($has_split_payment == "yes") {
                        $excess_payment = ($total_split_payments-$total_invoice_value);
                        $amount_paid = ($total_split_payments-$total_credit_sales);
                    }
                ?>

                <center> <p> <b>TRANSACTION DETAILS</b> </p> </center>

                <div id="bot">
                    <div id="table">
                        <table cellspacing="0" style="border: 1px #000 solid; font-weight:bolder;">
                            <tr class="tabletitle">
                                <td class="item">
                                    <h3>DESCRIPTION</h3>
                                </td>
                                <td class="Rate">
                                    <h3>AMOUNT</h3>
                                </td>
                            </tr>

                            <?php echo get_invoice_transactions2(get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "id"), get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "invoice_number"), "");?>

                        </table>

                        <table cellspacing="0" style="border: 1px #000 solid;border-top:none;  font-weight:bolder;">
                            <tr class="<?php if($print_on != "a4") { echo "tabletitle"; } ?> --stabletitle" style="--width:100%">
                                <td class="" style="width:50%"> <h3 style="line-height:23px;">Total Amount: ₦<?php echo custom_money_format($total_invoice_value);?> </h3> </td>
                                <td class="" style="width:50%"> <h3 style="line-height:23px;">Amount Paid: ₦<?php echo custom_money_format($amount_paid);?> </h3> </td>
                            </tr>

                            <tr class="<?php if($print_on != "a4") { echo "tabletitle"; } ?> --stabletitle" style="--width:100%">
                                <?php if( get_company_data("invoice_show_excess_payment_in_invoice") === "yes") {?>
                                    <td colspan="2" class="" style="width:50%"> <h3 style="line-height:23px;">Balance: ₦<?php echo custom_money_format($excess_payment);?> </h3> </td>
                                <?php } ?>
                                <!-- <td class="" <?php if( get_company_data("invoice_show_excess_payment_in_invoice") === "yes") {?> colspan="2" <?php } ?> style="width:50%"> <h3 style="line-height:23px;">Credit: ₦<?php echo custom_money_format($total_credit_sales);?></h3></td> -->
                            </tr>

                            <tr class="tabletitle">
                                <td colspan="2" class="" style="width:50%">
                                    <h3 style="line-height:20px;">
                                        Payment Status:
                                        <?php
                                            if(get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "status") == 1) {
                                                echo "PAYMENT HAS BEEN CONFIRMED.<br/>";
                                            } else {
                                                echo "Payment has not been confirmed.<br/>";
                                            }
                                        ?>

                                        <?php
                                            if(!empty(str_replace(array(" ", ""), array("",""), get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "additional_note")))) {
                                                echo "Additional Note: " . get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "additional_note");
                                            }
                                        ?>
                                    </h3>
                                </td>
                            </tr>

                        </table>
                    </div>

                    <?php echo strtoupper(get_splitted_payments3(get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "id"), get_invoice_data(SanitizeTEXT(mysqli_real_escape_string($conn, @$_GET['id'])) , "invoice_number")));?>

                    <script language="javascript">
                        function PrintInvoice(DivID) {
                            var disp_setting="toolbar=yes,location=no,";
                            disp_setting+="directories=yes,menubar=yes,";
                            disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25";
                            var content_vlue = document.getElementById(DivID).innerHTML;
                            var docprint=window.open("","",disp_setting);
                            docprint.document.open();
                            docprint.document.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"');
                            docprint.document.write('"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
                            docprint.document.write('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">');

                            docprint.document.write('<title>POS Receipt <?php //echo  $from_date;?> - <?php echo get_company_data("company_name");?> </title>');
                            docprint.document.write('<link rel="stylesheet" href="./assets/pos/pos_css.css">');
                            docprint.document.write('<link rel="stylesheet" href="./assets/pos/a4_css.css">');
                            docprint.document.write('</head><body onLoad="self.print()"><center><small><span style="font-size:18px;">');
                            docprint.document.write(content_vlue);
                            docprint.document.write('</span></small></center></body></html>');
                            docprint.document.close();
                            docprint.focus();
                        }

                    </script>

                    <div id="legalcopy">
                        <p class="legal">
                            <strong style="font-size:1.1em;"><?php echo get_company_data("footer_text");?></strong>
                            <?php
                                if(get_company_data("show_app_version") === "yes") {?>
                                    <br/> <strong>APP VERSION: <?php echo get_company_data("app_version");?></strong>
                                    <?php
                                }
                            ?>
                            <!-- <br/> -->
                            <!-- <strong>SOFTWARE SUPPORT: <?php // echo get_company_data("support");?> </strong> -->
                            <!-- <br/> <small><b>https://digiwebdevelopers.com</b></small> -->
                        </p>
                    </div>
                </div>
                <!--End InvoiceBot-->
            </div>
        </div>

        <center style="margin-bottom:50px;margin-top:50px;">
            <p>
                <label>
                    <b>Adjust Output:</b><br/>
                    <select id="adjust" onchange="adjust()" onkeydown="adjust()" onkeyup="adjust()">
                        <?php 
                            for($x=65; $x<=150; $x++) {?>
                                <option value="<?php echo $x;?>mm">
                                    <?php echo $x;?>mm
                                    <?php 
                                        if($x === 65) {
                                            echo "(Default)";
                                        }
                                    ?>
                                </option>
                            <?php
                            }
                        ?>
                    </select>
                </label>
                <script>
                    function adjust(){
                        var input = document.getElementById("adjust");
                        var target = document.getElementById("invoice-POS");
                        target.style.margin = "0 auto";
                        target.style.width = input.value;
                    }
                </script>
            </p>
            <button style="background-color:green;font-weight:bolder;color:white;" type="button" onclick="PrintInvoice('invoice-POS2')"><span class="fa fa-print"></span> Print </button>
            <button style="background-color:blue;font-weight:bolder;color:white;" type="button" onclick="window.open('./new-receipt', '_self')"><span class="fa fa-plus"></span> New Inv. </button>
        </center>

        <!--End Invoice-->

        <?php } else {echo "Data not found";} ?>

    </body>
<?php } else {
    require "./403.php";
} 
?>
</html>