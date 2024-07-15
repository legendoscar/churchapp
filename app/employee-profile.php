<?php require "./includes/header.php";?>

<?php if(is_authorized($account_type, "employees", "", "") === "allowed" ){ ?>
    <?php if(get_user_data($_GET['id'], "url_id") != "not_found"){

        $employee_account_id = get_user_data($_GET['id'], "acc_id");

        if(get_user_data($_GET['id'], "acc_id") != $account_id){ ?>

            <title>Employee Profile | <?php echo get_company_data("company_name");?></title>
            <body>

                <div class="main-wrapper">

                    <?php require "./includes/top-nav.php";?>
                    <?php require "./includes/sidebar.php";?>

                    <div class="page-wrapper">

                        <div class="content container-fluid">

                            <div class="page-header">
                                <div class="row">
                                    <div class="col">
                                        <h3 class="page-title">Employee Profile</h3>
                                        <ul class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Employee Profile</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="profile-view">
                                                <div class="profile-img-wrap">
                                                    <div class="profile-img">
                                                        <a href="#"><img alt="" src="./assets/img/profiles/no-image.jpg"></a>
                                                    </div>
                                                </div>
                                                <div class="profile-basic">
                                                    <div class="row">
                                                        <div class="col-md-5">
                                                            <div class="profile-info-left">
                                                                <h3 class="user-name m-t-0 mb-0"><?php echo get_user_data($_GET['id'], "firstname")." ".get_user_data($_GET['id'], "surname");?></h3>
                                                                <small class="text-muted"><?php echo get_designation_data_by_id(get_user_data($_GET['id'], "designation"), "name").", ".get_department_data_by_id(get_user_data($_GET['id'], "department"), "name");?> - <?php echo get_branch_data_by_id(get_user_data($_GET['id'], "branch"), "name");?></small>
                                                                <div class="staff-id">Employee ID : <?php echo get_user_data($_GET['id'], "employee_id");?></div>
                                                                <div class="small doj text-muted">Date of Join : <?php echo date("jS F Y", strtotime(get_user_data($_GET['id'], "reg_date")));?></div>
                                                                <!-- <div class="staff-msg"><a class="btn btn-custom" href="chat.html">Send Message</a></div> -->
                                                            </div>
                                                        </div>
                                                        <div class="col-md-7">
                                                            <ul class="personal-info">
                                                                <li>
                                                                    <div class="title">Phone:</div>
                                                                    <div class="text"><a href="javascript:(void)"><?php echo get_user_data($_GET['id'], "phone");?></a></div>
                                                                </li>
                                                                <li>
                                                                    <div class="title">Email:</div>
                                                                    <div class="text"><a href="javascript:(void)"><?php echo get_user_data($_GET['id'], "email");?></a></div>
                                                                </li>
                                                                <li>
                                                                    <div class="title">Address:</div>
                                                                    <div class="text"><?php echo get_user_data($_GET['id'], "contact_address");?></div>
                                                                </li>
                                                                <li>
                                                                    <div class="title">Gender:</div>
                                                                    <div class="text"><?php echo strtoupper(get_user_data($_GET['id'], "gender"));?></div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="pro-edit"><a data-bs-target="" data-bs-toggle="" class="edit-icon" href="./edit-employee?id=<?php echo get_user_data($_GET['id'], "url_id");?>"><i class="fa fa-pencil"></i></a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card tab-box">
                                <div class="row user-tabs">
                                    <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
                                        <ul class="nav nav-tabs nav-tabs-bottom">
                                            <!-- <li class="nav-item"><a href="#emp_profile" data-bs-toggle="tab" class="nav-link ">Profile</a></li> -->
                                            <li class="nav-item"><a href="#payslips" data-bs-toggle="tab" class="nav-link active">Payslips</a></li>
                                            <!-- <li class="nav-item"><a href="#bank_statutory" data-bs-toggle="tab" class="nav-link">Bank & Statutory <small class="text-danger">(Admin Only)</small></a></li> -->
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-content" style="margin-top:-30px;">

                                <p> <b>Payslips (<?php echo number_format(get_payslip_by_employee_id($employee_account_id, "count"));?>) </b> </p>

                                <div class="tab-pane fade show active" id="payslips">
                                    <div class="row">
                                        <?php echo get_payslip_by_employee_id($employee_account_id, "");?>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="bank_statutory">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title"> Basic Salary Information</h3>
                                            <form>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Salary basis <span class="text-danger">*</span></label>
                                                            <select class="select">
                                                                <option>Select salary basis type</option>
                                                                <option>Hourly</option>
                                                                <option>Daily</option>
                                                                <option>Weekly</option>
                                                                <option>Monthly</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Salary amount <small class="text-muted">per month</small></label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">$</span>
                                                                <input type="text" class="form-control" placeholder="Type your salary amount" value="0.00">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Payment type</label>
                                                            <select class="select">
            <option>Select payment type</option>
            <option>Bank transfer</option>
            <option>Check</option>
            <option>Cash</option>
            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h3 class="card-title"> PF Information</h3>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">PF contribution</label>
                                                            <select class="select">
            <option>Select PF contribution</option>
            <option>Yes</option>
            <option>No</option>
            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">PF No. <span class="text-danger">*</span></label>
                                                            <select class="select">
            <option>Select PF contribution</option>
            <option>Yes</option>
            <option>No</option>
            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Employee PF rate</label>
                                                            <select class="select">
            <option>Select PF contribution</option>
            <option>Yes</option>
            <option>No</option>
            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Additional rate <span class="text-danger">*</span></label>
                                                            <select class="select">
            <option>Select additional rate</option>
            <option>0%</option>
            <option>1%</option>
            <option>2%</option>
            <option>3%</option>
            <option>4%</option>
            <option>5%</option>
            <option>6%</option>
            <option>7%</option>
            <option>8%</option>
            <option>9%</option>
            <option>10%</option>
            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Total rate</label>
                                                            <input type="text" class="form-control" placeholder="N/A" value="11%">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Employee PF rate</label>
                                                            <select class="select">
            <option>Select PF contribution</option>
            <option>Yes</option>
            <option>No</option>
            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Additional rate <span class="text-danger">*</span></label>
                                                            <select class="select">
            <option>Select additional rate</option>
            <option>0%</option>
            <option>1%</option>
            <option>2%</option>
            <option>3%</option>
            <option>4%</option>
            <option>5%</option>
            <option>6%</option>
            <option>7%</option>
            <option>8%</option>
            <option>9%</option>
            <option>10%</option>
            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Total rate</label>
                                                            <input type="text" class="form-control" placeholder="N/A" value="11%">
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <h3 class="card-title"> ESI Information</h3>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">ESI contribution</label>
                                                            <select class="select">
            <option>Select ESI contribution</option>
            <option>Yes</option>
            <option>No</option>
            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">ESI No. <span class="text-danger">*</span></label>
                                                            <select class="select">
            <option>Select ESI contribution</option>
            <option>Yes</option>
            <option>No</option>
            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Employee ESI rate</label>
                                                            <select class="select">
            <option>Select ESI contribution</option>
            <option>Yes</option>
            <option>No</option>
            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Additional rate <span class="text-danger">*</span></label>
                                                            <select class="select">
            <option>Select additional rate</option>
            <option>0%</option>
            <option>1%</option>
            <option>2%</option>
            <option>3%</option>
            <option>4%</option>
            <option>5%</option>
            <option>6%</option>
            <option>7%</option>
            <option>8%</option>
            <option>9%</option>
            <option>10%</option>
            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="form-group">
                                                            <label class="col-form-label">Total rate</label>
                                                            <input type="text" class="form-control" placeholder="N/A" value="11%">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="submit-section">
                                                    <button class="btn btn-primary submit-btn" type="submit">Save</button>
                                                </div>
                                            </form>
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