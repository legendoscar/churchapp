<?php require "./includes/header.php";?>
<?php if(is_authorized($account_type, "employees", "", "") === "allowed") {?>
    <title>Employees | <?php echo get_company_data("company_name");?></title>
    <body>

        <div class="main-wrapper">

            <?php require "./includes/top-nav.php";?>
            <?php require "./includes/sidebar.php";?>

            <div class="page-wrapper">

                <div class="content container-fluid">

                    <div class="page-header">
                        <div class="row">
                            <div class="col">

                                <?php if(is_authorized($account_type, "create-employee", "", "") === "allowed") {?>
                                    <div class="pull-right"> <a href="./create-employee" class="btn btn-sm btn-primary"> <span class="la la-user-plus"></span> Create account</a> </div>
                                <?php } ?>

                                <h3 class="page-title">Employees</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Employees</li>
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
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Designation</label>
                                                    <select name="designation" class="select">
                                                        <option value='all'>All</option>
                                                        <?php echo get_designation_form_list(@$_POST['designation']);?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Department</label>
                                                    <select class="select" name="department">
                                                        <option value='all'>All</option>
                                                        <?php echo get_department_form_list(@$_POST['department']);?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Branch</label>
                                                    <select name="branch" class="select">
                                                        <option value='all'>All</option>
                                                        <?php echo get_branch_form_list(@$_POST['branch']);?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>--</label><br/>
                                                    <button type="submit" class="btn btn-primary"><span class="fa fa-filter"></span> Filter </button>
                                                </div>
                                            </div>

                                        </div>
                                    </form>

                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="datatable table table-hover table-stripped mb-0" style="border:2px solid #eee;">
                                            <thead>
                                                <tr class="btn-primary text-white">
                                                    <th>Employee</th>
                                                    <th>Designation</th>
                                                    <th>Department</th>
                                                    <th>Branch</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php echo get_employees("list");?>
                                            </tbody>
                                        </table>
                                    </div>
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