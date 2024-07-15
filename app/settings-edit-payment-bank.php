<?php require "./includes/header.php";?>
<?php if(is_authorized($user_account_type, "payment-channels", "", "") === "allowed") {?>
    <?php if(get_payment_bank_by_url_id($_GET['id'], "url_id") === $_GET['id']){ ?>

        <title>Edit Payment Bank | <?php echo get_company_data("company_name");?></title>

        <body>

            <div class="main-wrapper">

                <?php require "./includes/top-nav.php";?>
                <?php require "./includes/settings-sidebar.php";?>

                <div class="page-wrapper">

                    <div class="content container-fluid">

                        <div class="page-header">
                            <div class="row">
                                <div class="col">
                                    <h3 class="page-title">Edit Payment Bank</h3>
                                    <ul class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Edit Payment Bank</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <form method="POST" id="update-payment-bank">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">

                                        <div id="return_server_msg"></div>

                                        <div class="card-body">

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Bank Name</label>
                                                        <input value="<?php echo get_payment_bank_by_url_id($_GET['id'], "bank_name");?>" id='bank_name' type='text' name='bank_name' class='form-control'>
                                                        <input value="<?php echo get_payment_bank_by_url_id($_GET['id'], "url_id");?>" id='bank_id' type='hidden' name='bank_id' class='form-control'>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="text-end">

                                                <a href="./settings-payment-bank" class="btn btn-danger">
                                                    <span class="fa fa-arrow-left"></span> Go back
                                                </a>

                                                <button type="submit" id="update-payment-bank-btn" class="btn btn-primary">
                                                    <span class="fa fa-save"></span> Update Bank
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