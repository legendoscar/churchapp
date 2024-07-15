<?php session_start();
    if($_SERVER['REQUEST_METHOD'] === "POST") {
        require "../../includes/check_if_login.php";

        require "../../../includes/sanitizer.php";
        require "../../../includes/db-config.php";
        require "../../includes/global_function.php";
        require "../../includes/function.php";
        require "../../includes/additional_function.php";
        require "../../includes/update_activity.php";

        if(is_authorized($account_type, "expenses", "", "") === "allowed"){

            if(!isset($_POST['expenses_id']) || !isset($_POST['expenses_id'])) {
                exit(server_response("error", "<b>Some fields are missing!</b>", "200"));
            }

            $expenses_id = SanitizeTEXT(mysqli_real_escape_string($conn, $_POST['expenses_id']));?>
            <script> $("#expenses_container").html("<div id='print_Ex'><?php get_expenses_list($expenses_id);?></div><hr/><button onclick=printExpense('print_Ex') class='btn btn-sm btn-danger'><span class='fa fa-print'></span> Print </button>");</script>

            <script>

                function printExpense(DivID) {
                    var disp_setting="toolbar=yes,location=no,";
                    disp_setting+="directories=yes,menubar=yes,";
                    disp_setting+="scrollbars=yes,width=650, height=600, left=100, top=25";
                    var content_vlue = document.getElementById(DivID).innerHTML;
                    var docprint=window.open("","",disp_setting);
                    docprint.document.open();
                    docprint.document.write('<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"');
                    docprint.document.write('"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">');
                    docprint.document.write('<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">');

                    docprint.document.write('<title>Payment Voucher - <?php echo get_company_data("company_name");?> </title>');
                    docprint.document.write('<link rel="stylesheet" href="./assets/pos/pos_css.css">');
                    docprint.document.write('<link rel="stylesheet" href="./assets/pos/a4_css.css">');
                    docprint.document.write('</head><body onLoad="self.print()"><center><h4><?php echo get_company_data("company_name");?><br/><small><?php echo get_company_data("company_address");?></small></h4><small><span style="font-size:18px;"><h4>PAYMENT VOUCHER</h4></span></center><span style="font-size:18px;">');
                    docprint.document.write(content_vlue);
                    docprint.document.write('</span></small></body></html>');
                    docprint.document.close();
                    docprint.focus();
                }

            </script>

            <?php
        } else {
            echo server_response("error", "<b>Access Denied!</b> You're not allowed to see this transaction. Please if you think this was a mistake, contact your administrator.", "100");
        }
    }
?>