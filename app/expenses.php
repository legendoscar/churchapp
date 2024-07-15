<?php require "./includes/header.php";?>
<?php if(is_authorized($user_account_type, "expenses", "", "") === "allowed") {?>
    <title>Expenses | <?php echo get_company_data("company_name");?></title>
    <body>

        <div class="main-wrapper">

            <?php require "./includes/top-nav.php";?>
            <?php require "./includes/sidebar.php";?>

            <div class="page-wrapper">

                <div class="content container-fluid">

                    <div class="page-header">
                        <div class="row">
                            <div class="col">
                                <?php if(is_authorized($user_account_type, "create-expenses", "", "") === "allowed") {?>
                                    <div class="pull-right"> <a  href="./new-expenses" class="btn btn-sm btn-primary"> <span class="fa fa-plus"></span> New Item</a> </div>
                                <?php } ?>

                                <h3 class="page-title">Expenses (<span id='total-items'><?php echo number_format(get_expenses("count"));?></span>)</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Expenses</li>
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
                                                    <label>From</label>
                                                    <input name="from" value="<?php if(isset($_POST['from'])) { echo date("Y-m-d", strtotime(@$_POST['from'])); } else { echo date("Y-m-d"); } ?>"  type="date" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>From</label>
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
                                    <div class="table-responsive">

                                        <table id="item-tbl" class="datatable- table table-hover table-stripped mb-0" style="border:2px solid #eee;">
                                            <thead>
                                                <tr class="btn-primary text-white">
                                                    <th>Date</th>
                                                    <th>Details</th>
                                                    <th>Amount</th>
                                                    <th>Date created</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo get_expenses("");?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="view-expenses" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">

                                    <h5 class="modal-title" id="">Expenses</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>

                                </div>

                                <div class="modal-body">

                                    <div id="expenses_container">
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
<?php } else { require "./403.php"; } ?>
</html>