<?php require "./includes/header.php";?>
<?php if(is_authorized($account_type, "company-settings", "", "") === "allowed"){ ?>
    <title>Company Settings | <?php echo get_company_data("company_name");?></title>
    <body>

        <div class="main-wrapper">

            <?php require "./includes/top-nav.php";?>
            <?php require "./includes/settings-sidebar.php";?>

            <div class="page-wrapper">

                <div class="content container-fluid">

                    <div class="page-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="page-title">Company Settings</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item">Settings</li>
                                    <li class="breadcrumb-item active">Company Settings</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <form method="POST" id="company-settings">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Company Name <span class="text-danger">*</span></label>
                                            <input name="company_name" value="<?php echo get_company_data("company_name");?>" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <input name="company_description" value="<?php echo get_company_data("company_description");?>" class="form-control" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label>Address</label>
                                            <input name="company_address" class="form-control" value="<?php echo get_company_data("company_address");?>" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Phone Number 1</label>
                                            <input name="company_phone1" class="form-control" value="<?php echo get_company_data("company_phone1");?>" type="text">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <label>Phone Number 2</label>
                                            <input name="company_phone2" class="form-control" value="<?php echo get_company_data("company_phone2");?>" type="text">
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-primary -submit-btn" id="update-company-settings-btn"><span class="fa fa-save"></span> Save changes</button>

                            </form>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <?php require "./includes/footer.php";?>
    </body>
<?php } else { require "./403.php"; } ?>
</html>