<?php session_start();?>
<?php {?>
<?php if(isset($_GET['from']) && !empty($_GET['from']) && isset($_GET['to']) && !empty($_GET['to'])){ $from_date = date("Y-m-d", strtotime($_GET['from'])); $to_date = date("Y-m-d", strtotime($_GET['to'])); } else { $from_date = date("Y-m-d"); $to_date = date("Y-m-d"); }?> 
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

    <title>RECEIPT PAYMENT REPORTS from <?php echo $from_date;?> to <?php echo $to_date;?></title>

</head>

    <?php require "./includes/update_activity.php";?>
    <?php if(is_authorized($account_type, "print-payment-report", "", "") === "allowed"){ ?>

    <?php
        $item_name = "";
        if(isset($_GET['item']) && !empty($_GET['item']) && get_payable_items_data_by_id(mysqli_real_escape_string($conn, $_GET['item']), "id") == $_GET['item']) {
            $item_name = "<br/>".get_payable_items_data_by_id(mysqli_real_escape_string($conn, $_GET['item']), "name");
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
                        <h4><b>RECEIPT PAYMENT REPORTS
                            <?php if($from_date == $to_date) { ?>
                                AS AT <?php echo strtoupper(date("dS F, Y", strtotime($from_date)));?>
                            <?php } else { ?>
                                FROM <?php echo strtoupper(date("dS F, Y", strtotime($from_date)));?> TO <?php echo strtoupper(date("dS F, Y", strtotime($to_date)));?> </b>
                            <?php } ?>
                            <?php echo $item_name;?>
                        </h4>
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
                                        <th style="width:100px;">S/N</th>
                                        <th>ITEM</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo get_payment_reports();?>
                                </tbody>
                            </table>
                        </div>
                        <hr>

                        <hr>
                    </div>
                </div>
            </div>

        </div>
    </body>
    <?php } else { echo "<center style='margin-top:100px;color:red'><b>ACCESS DENIED</b></center>";} ?>
<?php } ?>