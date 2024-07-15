<?php require "./includes/header.php";?>
<title>Dashboard | <?php echo get_company_data("company_name");?></title>
<body>

    <div class="main-wrapper">

        <?php require "./includes/top-nav.php";?>

        <?php require "./includes/sidebar.php";?>

        <div class="page-wrapper">

            <div class="content container-fluid">

                <div class="page-header">
                    <div class="row">
                        <div class="col-sm-12">
                            <h3 class="page-title">Dashboard</h3>
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item active">
                                    <b> <?php echo strtoupper(day_session(). ", ".$firstname);?> </b>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa fa-money"></i></span>
                                <div class="dash-widget-info">
                                    <h3>
                                    <?php if(is_authorized($account_type, "receipts", "", "") == "allowed"){ ?>
                                        <a href="./receipts" title="Go to Receipts">
                                            ₦<?php echo custom_money_format(get_total_receipt_value("amount"));?></h3>
                                        </a>
                                    <?php } else {?>
                                        ₦<?php echo custom_money_format(get_total_receipt_value("amount"));?></h3>
                                    <?php } ?>
                                    <span>Total Amount</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="la la-file-alt"></i></span>
                                <div class="dash-widget-info">
                                    <h3><?php echo number_format(get_total_invoices(date("Y-m-d"), date("Y-m-d")));?></h3>
                                    <span>Receipts</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-4">
                        <div class="card dash-widget">
                            <div class="card-body">
                                <span class="dash-widget-icon"><i class="fa fa-users"></i></span>
                                <div class="dash-widget-info">
                                    <h3><?php echo number_format(get_employees("count"));?></h3>
                                    <span>Users</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-8 text-center">
                                <div class="card">

                                    <?php echo auto_generate_chart("weekly");?>
                                    <?php echo auto_generate_chart("monthly");?>

                                    <div class="card-body">
                                        <h3 class="card-title">Total Revenue</h3>
                                        <div class="tab-pane fade show active" role="tabpanel">
                                            <canvas id="weekly_chart" class="" style="height:300px;"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4 col-lg--6 col-xl--4 d-flex">
                                <div class="card flex-fill">
                                    <div class="card-body">
                                        <h4 class="card-title">Statistics (today)</h4>
                                        <div class="statistics">
                                            <div class="row">
                                                <div class="col-md-6 col-6 text-center">
                                                    <div class="stats-box mb-4">
                                                        <p>Total Expenses</p>
                                                        <h3>₦<?php echo custom_money_format(get_expenses("sum"));?></h3>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-6 text-center">
                                                    <div class="stats-box mb-4">
                                                        <p>Receipts</p>
                                                        <h3>₦<?php echo custom_money_format(get_total_receipt_value("amount"));?></h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p>Recently added</p>
                                        <div>
                                            <?php echo get_expenses("recent");?>
                                        </div>
                                    </div>
                                    <?php if(is_authorized($account_type, "expenses", "", "") == "allowed"){ ?>
                                        <p> <a href="./expenses" class="form-control btn btn-sm btn-default" style="border:0;">See all</a> </p>
                                    <?php } ?>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                <?php if(is_authorized($account_type, "receipts", "", "") == "allowed"){ ?>
                <div class="row">
                    <div class="col-md-12 d-flex">
                        <div class="card card-table flex-fill">
                            <div class="card-header">
                                <a href="./receipts" class="pull-right btn btn-primary btn-sm"><span class="la la-file-alt"></span> See all receipts</a>
                                <h3 class="card-title mb-0">Receipts</h3>
                            </div>
                            <div class="card-body" style="padding:25px;">
                                <div class="table-responsive">
                                    <table class="datatable- table table-hover table-stripped mb-0" style="border:2px solid #eee;">
                                        <thead>
                                            <tr class="btn-primary text-white">
                                                <th>Receipt No.</th>
                                                <th>Payee</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php echo get_invoice_list("20");?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-md-6 d-flex">
                        <div class="card card-table flex-fill">
                            <div class="card-header">
                                <h3 class="card-title mb-0">Payments</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table custom-table table-nowrap mb-0">
                                        <thead>
                                            <tr>
                                                <th>Invoice ID</th>
                                                <th>Client</th>
                                                <th>Payment Type</th>
                                                <th>Paid Date</th>
                                                <th>Paid Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><a href="invoice-view.html">#INV-0001</a></td>
                                                <td>
                                                    <h2><a href="#">Global Technologies</a></h2>
                                                </td>
                                                <td>Paypal</td>
                                                <td>11 Mar 2019</td>
                                                <td>$380</td>
                                            </tr>
                                            <tr>
                                                <td><a href="invoice-view.html">#INV-0002</a></td>
                                                <td>
                                                    <h2><a href="#">Delta Infotech</a></h2>
                                                </td>
                                                <td>Paypal</td>
                                                <td>8 Feb 2019</td>
                                                <td>$500</td>
                                            </tr>
                                            <tr>
                                                <td><a href="invoice-view.html">#INV-0003</a></td>
                                                <td>
                                                    <h2><a href="#">Cream Inc</a></h2>
                                                </td>
                                                <td>Paypal</td>
                                                <td>23 Jan 2019</td>
                                                <td>$60</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="payments.html">View all payments</a>
                            </div>
                        </div>
                    </div> -->

                </div> 

                <div class="modal fade" id="receipt-payment-transaction" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">

                                <h5 class="modal-title" id="">Transaction details (<span id="receipt_id"></span>)</h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>

                            </div>

                            <div class="modal-body">

                                <div id="receipt_payment_transaction_container">
                                    <center> <b> <span class="fa fa-spin fa-spinner"></span> Loading... Please wait </b> </center>
                                </div>

                                <div id="return_server_msg"></div>

                            </div>

                        </div>
                    </div>
                </div>

                <?php } ?>

            </div>

        </div>

    </div>

    <?php require "./includes/footer.php";?>
</body>

</html>