<?php require "./includes/header.php";?>
<?php // if(is_authorized($account_type, "edit-profile", "", "") === "allowed" || is_authorized($account_type, "edit-own-basic-salary", "", "") === "allowed") {?>

    <title>Profile | <?php echo get_company_data("company_name");?></title>
    <body>

        <div class="main-wrapper">

            <?php require "./includes/top-nav.php";?>
            <?php require "./includes/sidebar.php";?>

            <div class="page-wrapper">

                <div class="content container-fluid">

                    <div class="page-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="page-title">Profile</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Profile</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-8">
                            <form method="POST" id="edit-profile">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Edit My Profile</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Addressed as  </label>
                                                    <select <?php if(is_authorized($account_type, "edit-profile", "", "") !== "allowed") { echo "disabled"; } ?> name="addressed_as" class="select">
                                                        <option value=''>Select Title</option>
                                                        <?php echo get_account_title_form_list(get_user_data($account_id, "title"));?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">First Name </label>
                                                    <input <?php if(is_authorized($account_type, "edit-profile", "", "") !== "allowed") { echo "disabled"; } ?> value="<?php echo get_user_data($account_id, "firstname");?>" name="firstname" class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Surname</label>
                                                    <input  <?php if(is_authorized($account_type, "edit-profile", "", "") !== "allowed") { echo "disabled"; } ?> value="<?php echo get_user_data($account_id, "surname");?>" name="surname" class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Other names</label>
                                                    <input  <?php if(is_authorized($account_type, "edit-profile", "", "") !== "allowed") { echo "disabled"; } ?>  value="<?php echo get_user_data($account_id, "othername");?>" name="other_name" class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Gender</label>
                                                    <select  <?php if(is_authorized($account_type, "edit-profile", "", "") !== "allowed") { echo "disabled"; } ?> name="gender" class="select">
                                                        <option value=''>Select gender</option>
                                                        <option <?php if(get_user_data($account_id, "gender") == "male" ){ echo "selected"; } ?> value='male'>Male</option>
                                                        <option <?php if(get_user_data($account_id, "gender") == "female" ){ echo "selected"; } ?> value='female'>Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Email </label>
                                                    <input disabled <?php if(is_authorized($account_type, "edit-profile", "", "") !== "allowed") { echo "disabled"; } ?>  value="<?php echo get_user_data($account_id, "email");?>" name="email_address" class="form-control" placeholder="Email Address" type="email">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Employee ID </label>
                                                    <input name="employee_id" type="text" value="<?php echo get_user_data($account_id, "employee_id");?>" disabled class="form-control floating">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Joining Date </label>
                                                    <input  <?php if(is_authorized($account_type, "edit-profile", "", "") !== "allowed") { echo "disabled"; } ?> name="date_joined" class="form-control" type="date" value="<?php echo date("Y-m-d", strtotime(get_user_data($account_id, "reg_date")));?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Phone </label>
                                                    <input  <?php if(is_authorized($account_type, "edit-profile", "", "") !== "allowed") { echo "disabled"; } ?> value="<?php echo get_user_data($account_id, "phone");?>" name="phone_number" class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Branch</label>
                                                    <select  <?php if(is_authorized($account_type, "edit-profile", "", "") !== "allowed") { echo "disabled"; } ?> name="branch" class="select">
                                                        <option value=''>Select Branch</option>
                                                        <?php echo get_branch_form_list(get_user_data($account_id, "branch"));?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Department </label>
                                                    <select  <?php if(is_authorized($account_type, "edit-profile", "", "") !== "allowed") { echo "disabled"; } ?> class="select" name="department">
                                                        <option value=''>Select Department</option>
                                                        <?php echo get_department_form_list(get_user_data($account_id, "department"));?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Designation </label>
                                                    <select  <?php if(is_authorized($account_type, "edit-profile", "", "") !== "allowed") { echo "disabled"; } ?> name="designation" class="select">
                                                        <option value=''>Select Designation</option>
                                                        <?php echo get_designation_form_list(get_user_data($account_id, "designation"));?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label> Contact Address </label>
                                                    <input  <?php if(is_authorized($account_type, "edit-profile", "", "") !== "allowed") { echo "disabled"; } ?> value="<?php echo get_user_data($account_id, "contact_address");?>" class="form-control" name="contact_address" placeholder="Contact Address" type="text">
                                                </div>
                                            </div>

                                            <div class="text-end">
                                                <button  <?php if(is_authorized($account_type, "edit-profile", "", "") !== "allowed") { echo "disabled"; } ?> type="submit" class="btn btn-primary" id="action-btn"><span class="la la-user-plus"></span> Update account</button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-4">

                            <div class="card" id="change-password">
                                <div class="card-header">
                                    <h4 class="card-title mb-0"> More <span class="la la-forward"></span> </h4>
                                </div>

                                <div class="card-body">
                                    <div class="form-group">
                                        <label>Login Password</label><br/>
                                        <button  id="password_modal" data-bs-toggle="modal" data-bs-target="#update-password-form" type="button" <?php if(empty(get_user_data($account_id, "user_pwd"))) { echo "disabled"; } ?> class="btn btn-primary form-control">Update Password </button>
                                    </div>

                                </div>
                            </div>

                            <?php if(is_authorized($account_type, "edit-own-basic-salary", "", "") === "allowed") {?>
                                <div class="card">
                                    <div class="card-header">
                                        <button type="button" class="pull-right btn btn-primary" id="update-self-payroll-settings-btn"><span class="fa fa-save"></span></button>
                                        <h4 class="card-title mb-0">Payroll / Basic Salary <span class="la la-forward"></span> </h4>
                                    </div>

                                    <script>
                                        function payroll_switch(value) {
                                            if(value == "yes") {
                                                $("#basic_salary").removeAttr("disabled");
                                            } else {
                                                $("#basic_salary").attr("disabled", "disabled");
                                            }
                                        }
                                    </script>

                                    <div class="card-body">

                                        <div class="form-group">
                                            <label>Enroll into Payroll</label>
                                            <select onchange="payroll_switch(this.value)" id="user_has_payroll" name="user_has_payroll" class="select">
                                                <option value=''>Select</option>
                                                <option <?php if(!empty(str_replace(array("0.00"), array(""), get_user_data($account_id, "basic_salary"))) ) { echo "selected"; } ?> value="yes">Yes</option>
                                                <option <?php if(empty(str_replace(array("0.00"), array(""), get_user_data($account_id, "basic_salary"))) ) { echo "selected"; } ?> value="no">No</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label> Basic Salary </label>
                                            <input id="basic_salary" <?php if(empty(str_replace(array("0.00"), array(""), get_user_data($account_id, "basic_salary"))) ) { echo "disabled"; } ?> step="0.01" min="0.00" value="<?php echo get_user_data($account_id, "basic_salary");?>" class="form-control" name="basic_salary" placeholder="Basic Salary" type="number">
                                        </div>

                                        <div class="form-group">
                                            <label>Account Name</label>
                                            <input id='account_name' value='<?php echo get_user_data($account_id, "account_name");?>' type='text' name='account_name' class='form-control'>
                                        </div>

                                        <div class="form-group">
                                            <label>Account Number</label>
                                            <input id='account_number' value='<?php echo get_user_data($account_id, "account_number");?>' type='text' name='account_number' class='form-control'>
                                        </div>

                                        <div class="form-group">
                                            <label>Bank Name </label>
                                            <select class="select form-control" name="bank_name" id="bank_name">
                                                <option value="">Select Bank Name</option>
                                                <?php echo get_banks(get_user_data($account_id, "bank_id"));?>
                                            </select>
                                        </div>


                                    </div>
                                </div>

                            <?php } else {?>
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title mb-0">Basic Salary </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <input disabled id="basic_salary" value="<?php echo get_user_data($account_id, "basic_salary");?>" class="form-control" name="basic_salary" placeholder="Basic Salary" type="number">
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>

                    </div>
                </div>

            </div>


            <div class="modal fade" id="update-password-form" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">

                            <h5 class="modal-title" id="">Change Password</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>

                        </div>

                        <form method="POST" id="update-password">

                            <div class="modal-body">

                                <div class='row'>
                                    <div class='col-md-12'>
                                        <div class='form-group'>
                                            <label>Old Password</label>
                                            <input type='password' name='old_password' placeholder="*********" class='form-control'>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class='form-group'>
                                            <label>New Password</label>
                                            <input type='password' name='new_password' placeholder="*********" class='form-control'>
                                        </div>
                                    </div>
                                    <div class='col-md-6'>
                                        <div class='form-group'>
                                            <label>Confirm New Password</label>
                                            <input type='password' name='confirm_new_password' placeholder="*********" class='form-control'>
                                        </div>
                                    </div>
                                    <div class='col-md-12' style='margin-top:10px;'>
                                        <button id='update-password-btn' class='btn btn-sm btn-primary'>
                                            <span class='fa fa-save'></span> Update Password
                                        </button>
                                    </div>
                                </div>

                                <div id="return_server_msg"></div>

                            </div>

                        </form>

                    </div>
                </div>
            </div>

        </div>

        <?php require "./includes/footer.php";?>
    </body>

<?php // } else { require "./403.php"; } ?>
</html>