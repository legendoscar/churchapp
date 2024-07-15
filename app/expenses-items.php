<?php require "./includes/header.php";?>
<?php if(is_authorized($account_type, "expenses-item", "", "") === "allowed") {?>

    <title>Expenses Items | <?php echo get_company_data("company_name");?></title>
    <body>

        <div class="main-wrapper">

            <?php require "./includes/top-nav.php";?>
            <?php require "./includes/sidebar.php";?>

            <div class="page-wrapper">

                <div class="content container-fluid">

                    <div class="page-header">
                        <div class="row">
                            <div class="col">

                                <?php if(is_authorized($account_type, "create-expenses-item", "", "") === "allowed") {?>
                                    <div class="pull-right"> <a data-bs-toggle="modal" data-bs-target="#new-expenses-item" class="btn btn-sm btn-primary"> <span class="fa fa-plus"></span> New Item</a> </div>
                                <?php } ?>

                                <h3 class="page-title">Expenses Items (<span id='total-items'><?php echo number_format(get_expenses_items_list("count"));?></span>)</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Expenses Items</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="table-responsive">

                                        <table id="item-tbl" class="datatable- table table-hover table-stripped mb-0" style="border:2px solid #eee;">
                                            <thead>
                                                <tr class="btn-primary text-white">
                                                    <th>Item Name</th>
                                                    <th>Date Created</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo get_expenses_items_list("");?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php if(is_authorized($account_type, "create-expenses-item", "", "") === "allowed") {?>
                    <?php if(strpos($_SERVER['PHP_SELF'], "expenses-items.php") == true){?>
                        <div class="modal fade" id="new-expenses-item" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">

                                        <h5 class="modal-title" id="">New Expenses Item</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>

                                    </div>

                                    <form method="POST" id="create-new-expenses-item">

                                        <div class="modal-body">

                                            <div class='row'>
                                                <div class='col-md-12'>
                                                    <div class='form-group'>
                                                        <input type='text' name='item_name' class='form-control'>
                                                    </div>
                                                </div>

                                                <div class='col-md-12' style='margin-top:10px;'>
                                                    <button id='new-item-btn' class='btn btn-sm btn-primary'>
                                                        <span class='fa fa-plus'></span> Create Item
                                                    </button>
                                                </div>
                                            </div>

                                            <div id="return_server_msg"></div>

                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                <?php } ?>

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