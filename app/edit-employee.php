<?php require "./includes/header.php";?>
<?php if(is_authorized($account_type, "edit-employee", "", "") === "allowed" || is_authorized($account_type, "edit-employee-basic-salary", "", "") === "allowed") {?>
    <?php if(get_user_data($_GET['id'], "url_id") != "not_found"){

        $employee_account_id = get_user_data($_GET['id'], "acc_id");

        if(get_user_data($_GET['id'], "acc_id") != $account_id){ ?>

            <title>Edit Employee | <?php echo get_company_data("company_name");?></title>

            <body>

                <div class="main-wrapper">

                    <?php require "./includes/top-nav.php";?>
                    <?php require "./includes/sidebar.php";?>

                    <div class="page-wrapper">

                        <div class="content container-fluid">

                            <div class="page-header">
                                <div class="row">
                                    <div class="col">
                                        <div class="pull-right">
                                            <a href="./employees" class="btn btn-sm btn-primary"> <span class="la la-users"></span> See all employees</a>
                                            <!-- <a href="./employees" class="btn btn-sm btn-info"> <span class="la la-user-plus"></span> </a> -->
                                        </div>
                                        <h3 class="page-title">Edit Employee</h3>
                                        <ul class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Edit Employee</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" id="edit-employee">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title mb-0">Employee Form</h4>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Addressed as  </label>
                                                            <select <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> name="addressed_as" class="select">
                                                                <option value=''>Select Title</option>
                                                                <?php echo get_account_title_form_list(get_user_data($_GET['id'], "title"));?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">First Name </label>
                                                            <input <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> value="<?php echo get_user_data($_GET['id'], "firstname");?>" name="firstname" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Surname</label>
                                                            <input  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> value="<?php echo get_user_data($_GET['id'], "surname");?>" name="surname" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Other names</label>
                                                            <input  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?>  value="<?php echo get_user_data($_GET['id'], "othername");?>" name="other_name" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Gender</label>
                                                            <select  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> name="gender" class="select">
                                                                <option value=''>Select gender</option>
                                                                <option <?php if(get_user_data($_GET['id'], "gender") == "male" ){ echo "selected"; } ?> value='male'>Male</option>
                                                                <option <?php if(get_user_data($_GET['id'], "gender") == "female" ){ echo "selected"; } ?> value='female'>Female</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Email </label>
                                                            <input <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?>  value="<?php echo get_user_data($_GET['id'], "email");?>" name="email_address" class="form-control" placeholder="Email Address" type="email">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Employee ID </label>
                                                            <input name="employee_id" type="text" value="<?php echo get_user_data($_GET['id'], "employee_id");?>" disabled class="form-control floating">
                                                            <input  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> name="employee_account_id" type="hidden" value="<?php echo get_user_data($_GET['id'], "acc_id");?>" class="form-control">
                                                            <input  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> name="url_id" type="hidden" value="<?php echo get_user_data($_GET['id'], "url_id");?>" class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Joining Date </label>
                                                            <input  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> name="date_joined" class="form-control" type="date" value="<?php echo date("Y-m-d", strtotime(get_user_data($_GET['id'], "reg_date")));?>">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Phone </label>
                                                            <input  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> value="<?php echo get_user_data($_GET['id'], "phone");?>" name="phone_number" class="form-control" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Branch</label>
                                                            <select  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> name="branch" class="select">
                                                                <option value=''>Select Branch</option>
                                                                <?php echo get_branch_form_list(get_user_data($_GET['id'], "branch"));?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Department </label>
                                                            <select  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> class="select" name="department">
                                                                <option value=''>Select Department</option>
                                                                <?php echo get_department_form_list(get_user_data($_GET['id'], "department"));?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label> Designation </label>
                                                            <select  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> name="designation" class="select">
                                                                <option value=''>Select Designation</option>
                                                                <?php echo get_designation_form_list(get_user_data($_GET['id'], "designation"));?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label> Contact Address </label>
                                                            <input  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> value="<?php echo get_user_data($_GET['id'], "contact_address");?>" class="form-control" name="contact_address" placeholder="Contact Address" type="text">
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">

                                        <div class="card">
                                            <div class="card-header">
                                                <h4 class="card-title mb-0">More <span class="la la-forward"></span> </h4>
                                            </div>
                                            <script>
                                                function login_switch(value) {
                                                    if(value == "has_login") {
                                                        $("#password_modal").removeAttr("disabled");
                                                        $("#user_account_type").removeAttr("disabled");
                                                        $("#user_login_status").removeAttr("disabled");
                                                    } else {
                                                        $("#password_modal").attr("disabled", "disabled");
                                                        $("#user_account_type").attr("disabled", "disabled");
                                                        $("#user_login_status").attr("disabled", "disabled");
                                                    }
                                                }
                                            </script>

                                            <div class="card-body">

                                                <div class="form-group">
                                                    <label>Login Details</label>
                                                    <select  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> onchange="login_switch(this.value)" name="user_has_login" class="select">
                                                        <option value=''>Select</option>
                                                        <option <?php if(empty(get_user_data($_GET['id'], "user_pwd")) || empty(get_user_data($_GET['id'], "acc_type"))) { echo "selected"; } ?> value="without_login">No Login Details</option>
                                                        <option <?php if(!empty(get_user_data($_GET['id'], "user_pwd")) || !empty(get_user_data($_GET['id'], "acc_type")) ) { echo "selected"; } ?> value="has_login">User Can Login</option>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Login Password</label><br/>
                                                    <button  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> id="password_modal" data-bs-toggle="modal" data-bs-target="#update-employee-password" type="button" <?php if(empty(get_user_data($_GET['id'], "user_pwd"))) { echo "disabled"; } ?> class="btn btn-primary form-control">Update Password </button>
                                                </div>

                                                <div class="form-group">
                                                    <label>Account Type</label>
                                                    <select  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> id="user_account_type" <?php if(empty(get_user_data($_GET['id'], "user_pwd"))) { echo "disabled"; } ?> name="account_type" class="select">
                                                        <option value="">-- Account Type/Role --</option>
                                                        <?php echo get_account_type(get_user_data($_GET['id'], "acc_type"));?>
                                                    </select>
                                                </div>

                                                <div class="form-group">
                                                    <label>Login Status</label>
                                                    <select  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> id="user_login_status" <?php if(empty(get_user_data($_GET['id'], "user_pwd"))) { echo "disabled"; } ?> name="login_status" class="select">
                                                        <option value=''>Select</option>
                                                        <?php echo get_account_status("list", get_user_data($_GET['id'], "account_status"), "");?>
                                                    </select>
                                                </div>

                                                <div class="text-end">
                                                    <button  <?php if(is_authorized($account_type, "edit-employee", "", "") !== "allowed") { echo "disabled"; } ?> type="submit" class="btn btn-primary" id="action-btn"><span class="la la-user-plus"></span> Update account</button>
                                                </div>

                                            </div>
                                        </div>

                                        <?php if(is_authorized($account_type, "edit-employee-basic-salary", "", "") === "allowed") {?>
                                            <div class="card">
                                                <div class="card-header">
                                                    <button type="button" class="pull-right btn btn-primary" id="update-user-payroll-settings-btn"><span class="fa fa-save"></span></button>
                                                    <h4 class="card-title mb-0">Payroll <span class="la la-forward"></span> </h4>
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
                                                            <option <?php if(!empty(str_replace(array("0.00"), array(""), get_user_data($_GET['id'], "basic_salary"))) ) { echo "selected"; } ?> value="yes">Yes</option>
                                                            <option <?php if(empty(str_replace(array("0.00"), array(""), get_user_data($_GET['id'], "basic_salary"))) ) { echo "selected"; } ?> value="no">No</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label> Basic Salary </label>
                                                        <input id="basic_salary" <?php if(empty(str_replace(array("0.00"), array(""), get_user_data($_GET['id'], "basic_salary"))) ) { echo "disabled"; } ?> step="0.01" min="0.00" value="<?php echo get_user_data($_GET['id'], "basic_salary");?>" class="form-control" name="basic_salary" placeholder="Basic Salary" type="number">
                                                        <input id="payroll_employee_url_id" value="<?php echo get_user_data($_GET['id'], "url_id");?>" class="form-control" name="payroll_employee_url_id" type="hidden">
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Account Name</label>
                                                        <input id='account_name' value='<?php echo get_user_data($_GET['id'], "account_name");?>' type='text' name='account_name' class='form-control'>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Account Number</label>
                                                        <input id='account_number' value='<?php echo get_user_data($_GET['id'], "account_number");?>' type='text' name='account_number' class='form-control'>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Bank Name </label>
                                                        <select class="select form-control" name="bank_name" id="bank_name">
                                                            <option value="">Select Bank Name</option>
                                                            <?php echo get_banks(get_user_data($_GET['id'], "bank_id"));?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Payment Bank </label>
                                                        <select class="select form-control" name="payment_bank" id="payment_bank">
                                                            <option value="">Select Bank Name</option>
                                                            <?php echo get_payment_banks(get_user_data($_GET['id'], "payment_bank"));?>
                                                        </select>
                                                    </div>

                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                </div>
                            </form>
                        </div>

                    </div>


                    <div class="modal fade" id="update-employee-password" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
                        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">

                                    <h5 class="modal-title" id="">Update Password</h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>

                                </div>

                                <form method="POST" id="employee-password">

                                    <div class="modal-body">

                                        <div class='row'>
                                            <div class='col-md-12'>
                                                <div class='form-group'>
                                                    <input type='password' name='password' placeholder="*********" class='form-control'>
                                                </div>
                                            </div>
                                            <div class='col-md-12' style='margin-top:10px;'>
                                                <button id='update-password-btn' class='btn btn-sm btn-primary'>
                                                    <span class='fa fa-save'></span> Update Password
                                                </button>
                                                <input type='hidden' id="employee_id" name='employee_id' value='<?php echo get_user_data($_GET['id'], "acc_id");?>' class='form-control'>
                                                <!-- <input type='hidden' id="employee_url_id" name='employee_id' value='<?php //echo get_user_data($_GET['id'], "url_id");?>' class='form-control'> -->
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

            <?php
        } else {?>
            <script>window.location.replace('./my-account')</script>
        <?php
        }
    } else {
        require "./404.php";
    }
} else {
    require "./403.php";
}
?>
</html>