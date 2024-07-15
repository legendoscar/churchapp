<?php require "./includes/header.php";?>
<?php if(is_authorized($account_type, "create-employee", "", "") === "allowed") {?>
    <title>Create Employee | <?php echo get_company_data("company_name");?></title>
    <body>

        <div class="main-wrapper">

            <?php require "./includes/top-nav.php";?>
            <?php require "./includes/sidebar.php";?>

            <div class="page-wrapper">

                <div class="content container-fluid">

                    <div class="page-header">
                        <div class="row">
                            <div class="col">

                                <?php if(is_authorized($account_type, "employees", "", "") === "allowed") {?>
                                    <div class="pull-right"> <a href="./employees" class="btn btn-sm btn-primary"> <span class="la la-users"></span> See all employees</a> </div>
                                <?php } ?>

                                <h3 class="page-title">Create Employee</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Create Employee</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form method="POST" id="create-employee">
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
                                                    <select name="addressed_as" class="select">
                                                        <option value=''>Select Title</option>
                                                        <?php echo get_account_title_form_list("");?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">First Name </label>
                                                    <input name="firstname" class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Surname</label>
                                                    <input name="surname" class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Other names</label>
                                                    <input name="other_name" class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Gender</label>
                                                    <select name="gender" class="select">
                                                        <option value=''>Select gender</option>
                                                        <option value='male'>Male</option>
                                                        <option value='female'>Female</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Email </label>
                                                    <input name="email_address" class="form-control" placeholder="Email Address" type="email">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Employee ID </label>
                                                    <input name="employee_id" type="text" value="FT-0001" readonly class="form-control floating">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Joining Date </label>
                                                    <input name="date_joined" class="form-control" type="date" value="<?php echo date("Y-m-d");?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Phone </label>
                                                    <input name="phone_number" class="form-control" type="text">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Branch</label>
                                                    <select name="branch" class="select">
                                                        <option value=''>Select Branch</option>
                                                        <?php echo get_branch_form_list("");?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Department </label>
                                                    <select class="select" name="department">
                                                        <option value=''>Select Department</option>
                                                        <?php echo get_department_form_list("");?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label> Designation </label>
                                                    <select name="designation" class="select">
                                                        <option value=''>Select Designation</option>
                                                        <?php echo get_designation_form_list("");?>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label> Contact Address </label>
                                                    <input class="form-control" name="contact_address" placeholder="Contact Address" type="text">
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
                                                $("#user_password").removeAttr("disabled");
                                                $("#user_account_type").removeAttr("disabled");
                                                $("#user_login_status").removeAttr("disabled");
                                            } else {
                                                $("#user_password").attr("disabled", "disabled");
                                                $("#user_account_type").attr("disabled", "disabled");
                                                $("#user_login_status").attr("disabled", "disabled");
                                            }
                                        }
                                    </script>

                                    <div class="card-body">

                                        <div class="form-group">
                                            <label>Login Details</label>
                                            <select onchange="login_switch(this.value)" name="user_has_login" class="select">
                                                <option value=''>Select</option>
                                                <option selected value="without_login">No Login Details</option>
                                                <option value="has_login">User Can Login</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Login Password</label>
                                            <input id="user_password" disabled class="form-control" name="password" placeholder="*********" type="password">
                                        </div>

                                        <div class="form-group">
                                            <label>Account Type</label>
                                            <select id="user_account_type" disabled name="account_type" class="select">
                                                <option value="">-- Account Type/Role --</option>
                                                <?php echo get_account_type("");?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label>Login Status</label>
                                            <select id="user_login_status" disabled name="login_status" class="select">
                                                <option value=''>Select</option>
                                                <?php echo get_account_status("list", 1, "") ;?>
                                            </select>
                                        </div>

                                        <div class="text-end">
                                            <button type="submit" class="btn btn-primary" id="action-btn"><span class="la la-user-plus"></span> Create account</button>
                                        </div>

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
<?php
} else {
    require "./403.php";
}
?>
</html>