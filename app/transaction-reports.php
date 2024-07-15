<?php require "./includes/header.php";?>
<?php if(is_authorized($account_type, "transaction-report", "", "") === "allowed"){ ?>
    <title>Transaction Reports | <?php echo get_company_data("company_name");?></title>
    <body>

        <?php
            if(isset($_POST['to']) && isset($_POST['from']) && !empty($_POST['to']) && !empty($_POST['from'])) {
                $from_date = date("Y-m-d", strtotime(@$_POST['from']));
                $to_date = date("Y-m-d", strtotime(@$_POST['to']));
            } else {
                $from_date = date("Y-m-d");
                $to_date = date("Y-m-d");
            }
        ?>

        <div class="main-wrapper">

            <?php require "./includes/top-nav.php";?>
            <?php require "./includes/sidebar.php";?>

            <div class="page-wrapper">

                <div class="content container-fluid">

                    <div class="page-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="page-title">Transaction Reports</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Transaction Reports</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mb-0">
                                <div class="card-header" style="padding:15px 35px;padding-bottom-:-90px;">
                                    <form method="POST">
                                        <div class="row" style="backgro-und-color:#eee;">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>From </label>
                                                    <input name="from" value="<?php if(isset($_POST['from'])) { echo date("Y-m-d", strtotime(@$_POST['from'])); } else { echo date("Y-m-d"); } ?>"  type="date" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>To</label>
                                                    <input name="to" value="<?php if(isset($_POST['to'])) { echo date("Y-m-d", strtotime(@$_POST['to'])); } else { echo date("Y-m-d"); } ?>"  type="date" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>--</label> <br/>
                                                    <button class="btn btn-primary"><span class="fa fa-filter"></span> Filter </button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="card-body">

                                    <h4>
                                        Transaction reports from
                                        <span style="background-color:#eee;padding:5px;border:2px solid #989898; border-radius:5px;"><?php echo date("jS F Y", strtotime($from_date));?></span>
                                        to <span style="background-color:#eee;padding:5px;border:2px solid #989898; border-radius:5px;"><?php echo date("jS F Y", strtotime($to_date));?></span>
                                    </h4>

                                    <div class="table-responsive">
                                        <table class="datatable- table table-hover table-stripped mb-0" style="border:2px solid #eee;">
                                            <thead>
                                                <tr class="btn-primary text-white">
                                                    <th style="width:100px;">S/N</th>
                                                    <th>Mode of Payment</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo get_transaction_reports();?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div style="margin-top:20px;" class="pull-right"> <a href="./print-transaction-reports?from=<?php echo $from_date;?>&to=<?php echo $to_date;?>" target="_blank" class="btn btn-danger"><span class="fa fa-print"></span> Print </a> </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <?php require "./includes/footer.php";?>
    </body>
<?php } else { require "./403.php"; } ?>
</html>