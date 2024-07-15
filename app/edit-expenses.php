<?php require "./includes/header.php";?>
<?php if(is_authorized($account_type, "edit-expenses", "", "") === "allowed"){ ?>
    <?php if(get_expenses_data_by_urlid($_GET['id'], "url_id") != "not_found"){?>

            <title>Edit Expenses | <?php echo get_company_data("company_name");?></title>
            <body>

                <div class="main-wrapper">

                    <?php require "./includes/top-nav.php";?>
                    <?php require "./includes/sidebar.php";?>

                    <div class="page-wrapper">

                        <div class="content container-fluid">

                            <div class="page-header">
                                <div class="row">
                                    <div class="col">
                                        <h3 class="page-title">Edit Expenses</h3>
                                        <ul class="breadcrumb">
                                            <li class="breadcrumb-item"><a href="./">Dashboard</a></li>
                                            <li class="breadcrumb-item active">Edit Expenses</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" id="update-expenses">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card">

                                            <div id="return_server_msg"></div>

                                            <div class="card-body">

                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="given_to">Expenses Given to / Collected by:</label>
                                                            <input id='given_to' value="<?php echo get_expenses_data_by_urlid($_GET['id'], "given_to");?>" placeholder="Expenses Given to / Collected by" type='text' name='given_to' class='form-control'>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Item </label>
                                                            <select class="select" name="item_id" id="item_id">
                                                                <option value=''>--Select--</option>
                                                                <?php echo get_expenses_item_form_list(get_expenses_data_by_urlid($_GET['id'], "item_id"));?>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Date </label>
                                                            <input id='expenses_date' value="<?php echo date("Y-m-d", strtotime(get_expenses_data_by_urlid($_GET['id'], "expense_date")));?>" type='date' name='expenses_date' class='form-control'>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Description </label>
                                                            <input value="<?php echo get_expenses_data_by_urlid($_GET['id'], "description");?>" id='description' type='text' name='description' class='form-control'>
                                                            <input value="<?php echo get_expenses_data_by_urlid($_GET['id'], "url_id");?>" id='url_id' type='hidden' name='url_id' class='form-control'>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Amount </label>
                                                            <input id='amount' type='number' value="<?php echo get_expenses_data_by_urlid($_GET['id'], "amount");?>" step="0.01" min="0.01" name='amount' class='form-control'>
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="text-end">
                                                    <a href="./expenses" type="submit" id="action-btn" class="btn btn-danger">
                                                        <span class="la la-arrow-left"></span> Back to expenses
                                                    </a>

                                                    <button type="submit" id="action-btn" class="btn btn-primary">
                                                        <span class="la la-file-alt"></span> Update Expenses
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

            </body>
        <?php
    } else {
        require "404.php";
    }
} else {
    require "403.php";
}
?>

<?php require "./includes/footer.php";?>

</html>