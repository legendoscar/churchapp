<?php require "./includes/header.php";?>
<?php if(is_authorized($account_type, "privilege-settings", "", "") === "allowed"){ ?>
    <title>Roles & Permissions | <?php echo get_company_data("company_name");?></title>
    <body>

        <div class="main-wrapper">

            <?php require "./includes/top-nav.php";?>
            <?php require "./includes/settings-sidebar.php";?>

            <div class="page-wrapper">

                <div class="content container-fluid">

                    <div class="page-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="page-title">Roles & Permissions</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item">Settings</li>
                                    <li class="breadcrumb-item active">Roles & Permissions</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4 col-md-4 col-lg-4 col-xl-3">
                            <?php if(is_authorized($account_type, "create-user-role", "", "") === "allowed"){ ?>
                                <a href="#" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#add_role"><i class="fa fa-plus"></i> Add Roles</a>
                            <?php } ?>
                            <div class="roles-menu" id="roles-menu">
                                <ul> <?php echo get_user_roles("list");?> </ul>
                            </div>
                        </div>

                        <div class="col-sm-8 col-md-8 col-lg-8 col-xl-9">
                            <h6 class="card-title m-b-20">Assign Privileges</h6>

                            <div class="card mb-0">
                                <div class="card-body">
                                    <?php
                                        if(isset($_GET['role']) && !empty($_GET['role']) && account_role_data_by_alias($_GET['role'], "id") != "not_found" && account_role_data_by_alias($_GET['role'], "nature") === "editable") {?>

                                            <h4> Selected: <?php echo account_role_data_by_alias($_GET['role'], "role_name");?> </h4>
                                            <div class="alert alert-danger">
                                                <b> <span class="fa fa-info-circle"></span>
                                                    If you just created this role, please kindly assign basic functions/privilleges to this role.
                                                </b>
                                            </div>

                                            <?php $role_id = account_role_data_by_alias($_GET['role'], "id");?>

                                            <form method="POST" id="set_privileges">

                                                <input type='hidden' name='role_id' value='<?php echo $role_id;?>'>
                                                <div class="faq-card">
                                                    <div class="card">
                                                        <?php echo get_privilege_settings($role_id);?>
                                                    </div>
                                                </div>
                                                    <hr>
                                                </form>
                                            <div id="return_server_msg2"></div>
                                        <?php 
                                        } else {?>
                                        <center>

                                            <h2>
                                                <!-- <p> <img src="./dist/images/logo.png"></p> -->
                                                <small>Welcome to</small><br/>
                                                <b>User Privilege Management</b>
                                            </h2>

                                            <p style="font-size:20px;">
                                                Control what your Employees / Staff can do.
                                                <br/>
                                                To get started, select any of the options.
                                            </p>
                                        </center>
                                        <?php 
                                        } 
                                    ?>
                                </div>
                            </div>

                        </div>

                    </div>

                    <!-- ROLES AND PRIVILEGES MODAL -->
                    <?php if(is_authorized($account_type, "create-user-role", "", "") === "allowed") {?>
                        <div class="modal fade" id="add_role" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">

                                        <h5 class="modal-title" id="">Create New Role </h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>

                                    </div>

                                    <form method="POST" id="create-new-role">

                                        <div class="modal-body">

                                            <div class='row'>
                                                <div class='col-md-12'>
                                                    <div class='form-group'>
                                                        <input type='text' name='role_name' class='form-control'>
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

                    <?php if(is_authorized($account_type, "edit-user-role", "", "") === "allowed") {?>
                        <div class="modal fade" id="edit_role" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                            <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">

                                        <h5 class="modal-title" id="">Edit Role</h5>
                                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>

                                    </div>

                                    <div class="modal-body">

                                        <form method="POST" id="update_role">
                                            <div id="role_container">
                                                <center> <b> <span class="fa fa-spin fa-spinner"></span> Loading... Please wait </b> </center>
                                            </div>
                                        </form>

                                        <div id="return_server_msg"></div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- ROLES AND PRIVILEGES MODAL -->

                </div>

            </div>

        </div>

        <?php require "./includes/footer.php";?>
    </body>
<?php } else { require "./403.php"; } ?>
</html>