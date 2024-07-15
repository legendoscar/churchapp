<?php require "./includes/header.php";?>
<?php if(is_authorized($user_account_type, "payment-channels", "", "") === "allowed") {?>
    <title>Bank Accounts | <?php echo get_company_data("company_name");?></title>
    <body>

        <div class="main-wrapper">

            <?php require "./includes/top-nav.php";?>
            <?php require "./includes/settings-sidebar.php";?>

            <div class="page-wrapper">

                <div class="content container-fluid">

                    <div class="page-header">
                        <div class="row">
                            <div class="col">

                                <a class="pull-right btn-primary btn btn-sm" href="./settings-create-payment-channels-bank"><span class="la la-file-alt"></span> New Account </a>

                                <h3 class="page-title">Bank Accounts</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Bank Accounts</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mb-0">

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="datatable table table-hover table-stripped mb-0" style="border:2px solid #eee;">
                                            <thead>
                                                <tr class="btn-primary text-white">
                                                    <th>S/N</th>
                                                    <th>Account Name</th>
                                                    <th>Acc. No/Bank</th>
                                                    <th>Date created</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo get_bank_list_in_table();?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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

                </div>

            </div>

        </div>

        <?php require "./includes/footer.php";?>
    </body>
<?php } else {
    require "./403.php";
}
?>
</html>