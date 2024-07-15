<?php require "./includes/header.php";?>
<?php if(is_authorized($user_account_type, "payment-channels", "", "") === "allowed") {?>
    <title>Create POS | <?php echo get_company_data("company_name");?></title>

    <body>

        <div class="main-wrapper">

            <?php require "./includes/top-nav.php";?>
            <?php require "./includes/settings-sidebar.php";?>

            <div class="page-wrapper">

                <div class="content container-fluid">

                    <div class="page-header">
                        <div class="row">
                            <div class="col">
                                <h3 class="page-title">Create POS</h3>
                                <ul class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Create POS</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <form method="POST" id="create-payment-channels-pos">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">

                                    <div id="return_server_msg"></div>

                                    <div class="card-body">

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>POS Name</label>
                                                    <input id='pos_name' type='text' name='pos_name' class='form-control'>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="text-end">

                                            <a href="./settings-payment-channels-pos" class="btn btn-danger">
                                                <span class="fa fa-arrow-left"></span> Go back
                                            </a>

                                            <button type="submit" id="create-payment-channels-pos-btn" class="btn btn-primary">
                                                <span class="fa fa-plus"></span> Create Account
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
    <?php require "./403.php"; ?>
<?php } ?>

</html>