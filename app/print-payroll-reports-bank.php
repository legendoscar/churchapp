<?php session_start();?>
<?php {?>
<?php if(isset($_GET['month']) && !empty($_GET['month']) && isset($_GET['bank']) && !empty($_GET['bank'])){ $from_date = date("Y-m", strtotime($_GET['month'])); $payment_bank = ($_GET['bank']); } else { $from_date = date("Y-m"); $payment_bank = 0; }?>
<html lang="en">

<head>
    <?php require "./../includes/db-config.php";?>
    <?php require "./includes/check_if_login.php";?>
    <?php require "./includes/global_function.php";?>
    <?php require "./includes/function.php";?>
    <?php require "./includes/additional_function.php";?>

    <meta charset="UTF-8">
    <link rel="shortcut icon" href="dist/images/favicon.ico" />
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <title>MONTHLY PAYROLL REPORT (BY BANK) FOR <?php echo $from_date;?></title>

</head>

    <?php require "./includes/update_activity.php";?>
    <?php if(is_authorized($account_type, "print-expenses-report", "", "") === "allowed"){ ?>

    <?php $payment_bank = mysqli_real_escape_string($conn, $_GET['bank']);?>

    <?php
        if(get_payment_bank_by_id($payment_bank, "id") == $payment_bank) {
            $bank_name = strtoupper(get_payment_bank_by_id($payment_bank, "bank_name"));
            $title = "$bank_name PAYROLL REPORT FOR " . strtoupper(date("F, Y", strtotime($from_date)));
        } else {
            $title = "";
        }
    ?>

    <body style="-f-ont-weight:bolder;font-family:Arial" onLoad="self.print()">
        <div id="frame" style="font-size:20px;">
            <div class="row">
                <div class="col-12 col-lg-12 col-xl-12 mt-3">

                    <center>
                        <h2><b><?php echo get_company_data("company_name");?></b></h2>
                        <h3><b><?php echo get_company_data("company_address");?> <br/> Tel: <?php echo get_company_data("company_phone1");?>, <?php echo get_company_data("company_phone2");?></b></h3>
                        <hr/>
                        <h4><b> <?php echo $title;?> </h4>
                    </center>

                        <style>
                            table, th, td {
                                border: 2px solid black;
                            }

                            table {
                                border-collapse: collapse;
                                border: 1px solid black;
                                width: 100%;
                            }

                            th {
                                /* height: 50px; */
                                /* text-align: left; */
                            }

                            td {
                                padding-left:4px;
                            }

                            th, td {
                                /* border-bottom: 1px solid #ddd; */
                            }
                        </style>

                    <div class="card-body">
                        <div class="table-responssive" stylse="overflow-x:auto;" id="report_result">

                            <table class="datatable- table table-hover table-stripped mb-0" style="border:2px solid #eee;">
                                <thead>
                                    <tr class="btn-primary text-white">
                                        <th>S/N</th>
                                        <th>ACCOUNT NAME</th>
                                        <th>ACCOUNT NUMBER </th>
                                        <th>NET PAY </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo get_payroll_reports2($from_date, $payment_bank);?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </body>
    <?php } else { echo "<center style='margin-top:100px;color:red'><b>ACCESS DENIED</b></center>";} ?>
<?php } ?>