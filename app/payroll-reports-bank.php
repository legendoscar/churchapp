<?php require "./includes/header.php";?>
<?php if(is_authorized($account_type, "payroll-report", "", "") === "allowed"){ ?>
    <title>Payroll Reports (V2) | <?php echo get_company_data("company_name");?></title>
    <body>

        <?php
            if(isset($_POST['month']) && !empty($_POST['month']) && isset($_POST['payment_bank']) && !empty($_POST['payment_bank'])) {
                $from_date = date("Y-m", strtotime(@$_POST['month']));
                $payment_bank = mysqli_real_escape_string($conn, $_POST['payment_bank']);
            } else {
                $from_date = date("Y-m");
                $payment_bank = 0;
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
                                <h3 class="page-title">Payroll Reports (V2)</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Payroll Reports (V2)</li>
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
                                                    <label>Month </label>
                                                    <input name="month" value="<?php if(isset($_POST['month'])) { echo date("Y-m", strtotime(@$_POST['month'])); } else { echo date("Y-m"); } ?>"  type="month" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Payment Bank </label>
                                                    <select required class="select form-control" name="payment_bank" id="payment_bank">
                                                        <option value="">Select Bank Name</option>
                                                        <?php echo get_payment_banks(@$_POST['payment_bank']);?>
                                                    </select>
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

                                    <h4> Payroll Reports for the Month of <?php echo date("F Y", strtotime($from_date));?> </h4>

                                    <div class="table-responsive">
                                        <table class="datatable- table table-hover table-stripped mb-0" style="border:2px solid #eee;">
                                            <thead>
                                                <tr class="btn-primary text-white">
                                                    <th>S/N</th>
                                                    <th>ACCOUNT NAME</th>
                                                    <th>ACCOUNT NUMBER</th>
                                                    <th>NET PAY</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo get_payroll_reports2($from_date, $payment_bank);?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <?php if(isset($_POST['month']) && !empty($_POST['month']) && isset($_POST['payment_bank']) && !empty($_POST['payment_bank'])) {?>
                                        <div style="margin-top:20px;" class="pull-right"> <a href="./print-payroll-reports-bank?month=<?php echo $from_date;?>&bank=<?php echo $payment_bank;?>" target="_blank" class="btn btn-danger"><span class="fa fa-print"></span> Print </a> </div>
                                    <?php } ?>

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