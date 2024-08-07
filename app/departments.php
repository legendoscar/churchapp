<?php require "./includes/header.php";?>
<?php if(is_authorized($account_type, "departments", "", "") === "allowed") {?>
    <title>Departments | <?php echo get_company_data("company_name");?></title>
    <body>

        <div class="main-wrapper">

            <?php require "./includes/top-nav.php";?>
            <?php require "./includes/sidebar.php";?>

            <div class="page-wrapper">

                <div class="content container-fluid">

                    <div class="page-header">
                        <div class="row">
                            <div class="col">

                                <?php if(is_authorized($account_type, "create-department", "", "") === "allowed") {?>
                                    <div class="pull-right"> <a data-bs-toggle="modal" data-bs-target="#new-department" href="./create-employee" class="btn btn-sm btn-primary"> <span class="fa fa-plus"></span> New Item</a> </div>
                                <?php } ?>

                                <h3 class="page-title">Departments (<span id='total-items'><?php echo number_format(get_department_list("count"));?></span>)</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Departments</li>
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
                                                    <th>Department Name</th>
                                                    <th>Date Created</th>
                                                    <!-- <th>Created by</th> -->
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo get_department_list("");?>
                                                <!-- <tr>
                                                    <td><b>Tithe</b></td>
                                                    <td>2022-07-27</td>
                                                    <td>Ugonna Ozoemena</td>
                                                    <td align="right">
                                                        <a href="./edit-receipt" class="btn btn-sm btn-primary"> <span class="fa fa-pencil"></span></a>
                                                        <button class="btn btn-sm btn-danger"> <span class="fa fa-trash-o"></span></button>
                                                    </td>
                                                </tr> -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if(strpos($_SERVER['PHP_SELF'], "departments.php") == true){?>

                        <?php if(is_authorized($account_type, "create-department", "", "") === "allowed") {?>
                            <div class="modal fade" id="new-department" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">

                                            <h5 class="modal-title" id="">New Item</h5>
                                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>

                                        </div>

                                        <form method="POST" id="create-new-department">

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
<?php
} else {
    require "./403.php";
}
?>
</html>