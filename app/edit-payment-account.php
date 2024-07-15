<?php require "./includes/header.php";?>

<?php if(is_authorized($user_account_type, "edit-payment-account", "", "") === "allowed") {?>
    <?php if(get_customer_data($_GET['id'], "customer_id") != "not_found"){ ?>

        <title>Edit Payment Accounts | <?php echo get_company_data("company_name");?></title>

        <body>

            <div class="main-wrapper">

                <?php require "./includes/top-nav.php";?>
                <?php require "./includes/sidebar.php";?>

                <div class="page-wrapper">

                    <div class="content container-fluid">

                        <div class="page-header">
                            <div class="row">
                                <div class="col">
                                    <h3 class="page-title">Edit Payment Accounts</h3>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Edit Payment Accounts</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <form method="POST" id="update-payment-account">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">

                                        <div id="return_server_msg"></div>

                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Account Name</label>
                                                        <input value="<?php echo get_customer_data($_GET['id'], "surname");?>" id='account_name' type='text' name='account_name' class='form-control'>
                                                        <input value="<?php echo get_customer_data($_GET['id'], "customer_id");?>" id='customer_id' type='hidden' name='customer_id' class='form-control'>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Phone Number</label>
                                                        <input value="<?php echo get_customer_data($_GET['id'], "phone");?>" id='phone_number' type='text' name='phone_number' class='form-control'>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Contact Address </label>
                                                        <input value="<?php echo get_customer_data($_GET['id'], "address");?>" id='contact_address' type='text' name='contact_address' class='form-control'>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Email Address </label>
                                                        <input value="<?php echo get_customer_data($_GET['id'], "email_address");?>" id='email_address' type='text' name='email_address' class='form-control'>
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="text-end">

                                                <a href="./payment-accounts" class="btn btn-danger">
                                                    <span class="fa fa-arrow-left"></span> Back to accounts
                                                </a>

                                                <button type="submit" id="update-payment-account-btn" class="btn btn-primary">
                                                    <span class="fa fa-save"></span> Update Account
                                                </button>

                                            </div>

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </form>

                    </div>

                </div>

            </div>

            <?php require "./includes/footer.php";?>
        </body>

    <?php } else {?>
        <?php require "./404.php"; ?>
    <?php } ?>
<?php } else {?>
    <?php require "./403.php"; ?>
<?php } ?>

</html>