<?php { ?>

    <?php if(is_authorized($account_type, "sb-products", "", "") === "allowed" && is_authorized($account_type, "sb-manage-bank-accounts", "", "") === "allowed"){?> 
        <!-- BANK LIST -->
        <div class="modal fade" id="get_bank_list" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title">Manage Bank Accounts</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <?php if(is_authorized($account_type, "ft-create-bank-account", "", "") === "allowed" && is_authorized($account_type, "sb-products", "", "") === "allowed" && is_authorized($account_type, "sb-manage-bank-accounts", "", "") === "allowed"){?> 
                            <p><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#col_new_bank_form" aria-expanded="false" aria-controls="collapseExample">Add Accounts</button></p>
                            
                            <div class="row collapse" id="col_new_bank_form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="Account Name" class="form-control" value="" id="account_name"/>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="Account Number" class="form-control" value="" id="account_number"/>
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="Bank Name" class="form-control" value="" id="bank_name"/>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button type="button" id="add_bank" onclick="save_new_bank()" class="btn btn-md btn-success"><span class="fa fa-save"></span> Save</button>
                                    </div>
                                </div>
                            </div>

                            <script>
                                function save_new_bank(){
                                    var dataString = "account_name=" + $("#account_name").val() + "&bank_name=" + $("#bank_name").val() + "&account_number=" + $("#account_number").val();
                                    $.ajax({
                                        type: "POST",
                                        url: "./includes/ajax/add-bank",
                                        data: dataString,
                                        cache: false,
                                        beforeSend: function() {
                                            $("button#add_bank").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Saving Account...</i>");
                                            $("button#add_bank").attr("disabled", "disabled");
                                        },
                                        success: function(d) {
                                            $('div#return_server_msg').fadeIn('slow').html(d);
                                            $("button#add_bank").fadeIn("slow").html("<span class='fa fa-save'></span> Save");
                                            $("button#add_bank").removeAttr("disabled");
                                        },
                                        error: function(d) {
                                            $("button#add_bank").fadeIn("slow").html("<span class='fa fa-save'></span> Save");
                                            $("button#add_bank").removeAttr("disabled");
                                            toastr.error("Something went wrong!");
                                        }
                                    });
                                    return false;

                                }
                            </script>
                        <?php } ?>
                        
                        <hr/>
                        <div class="table-responsive">
                            <table id="bank_table" class="display table dataTables table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <?php if((is_authorized($account_type, "ft-edit-bank-account", "", "") === "allowed" || is_authorized($account_type, "ft-delete-bank-account", "", "") === "allowed") && is_authorized($account_type, "sb-products", "", "") === "allowed" && is_authorized($account_type, "sb-manage-bank-accounts", "", "") === "allowed"){?> 
                                            <th></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo get_bank_list_in_table();?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        
                                        <?php if((is_authorized($account_type, "ft-edit-bank-account", "", "") === "allowed" || is_authorized($account_type, "ft-delete-bank-account", "", "") === "allowed") && is_authorized($account_type, "sb-products", "", "") === "allowed" && is_authorized($account_type, "sb-manage-bank-accounts", "", "") === "allowed"){?> 
                                            <th></th>
                                        <?php } ?>

                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div id="return_server_msg"></div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if(is_authorized($account_type, "sb-products", "", "") === "allowed" && is_authorized($account_type, "sb-manage-pos", "", "") === "allowed"){?> 
        <!-- BANK LIST -->
        <div class="modal fade" id="get_pos_list" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title">Manage POS </h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        
                        <?php if(is_authorized($account_type, "ft-create-pos", "", "") === "allowed" && is_authorized($account_type, "sb-products", "", "") === "allowed" && is_authorized($account_type, "sb-manage-pos", "", "") === "allowed"){?> 
                            <p><button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#col_new_pos_form" aria-expanded="false" aria-controls="collapseExample">Add POS</button></p>
                            <div class="row collapse" id="col_new_pos_form">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" placeholder="POS Name" class="form-control" value="" id="pos_name"/>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button type="button" id="save_pos" onclick="save_new_pos()" class="btn btn-md btn-success"><span class="fa fa-save"></span> Save</button>
                                    </div>
                                </div>

                            </div>
                        <?php } ?>

                        <script>
                            function save_new_pos(){
                                var dataString = "pos_name=" + $("#pos_name").val();
                                $.ajax({
                                    type: "POST",
                                    url: "./includes/ajax/add-pos",
                                    data: dataString,
                                    cache: false,
                                    beforeSend: function() {
                                        $("button#save_pos").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Saving POS...</i>");
                                        $("button#save_pos").attr("disabled", "disabled");
                                    },
                                    success: function(d) {
                                        $('div#return_server_msg').fadeIn('slow').html(d);
                                        $("button#save_pos").fadeIn("slow").html("<span class='fa fa-save'></span> Save");
                                        $("button#save_pos").removeAttr("disabled");
                                    },
                                    error: function(d) {
                                        $("button#save_pos").fadeIn("slow").html("<span class='fa fa-save'></span> Save");
                                        $("button#save_pos").removeAttr("disabled");
                                        toastr.error("Something went wrong!");
                                    }
                                });
                                return false;

                            }
                        </script>

                        <hr/>

                        <div class="table-responsive">
                            <table id="pos_table" class="display table dataTables table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <?php if((is_authorized($account_type, "ft-edit-pos", "", "") === "allowed" ||  is_authorized($account_type, "ft-delete-pos", "", "") === "allowed") && is_authorized($account_type, "sb-products", "", "") === "allowed" && is_authorized($account_type, "sb-manage-pos", "", "") === "allowed"){?>
                                            <th></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php echo get_pos_list_in_table();?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <?php if((is_authorized($account_type, "ft-edit-pos", "", "") === "allowed" ||  is_authorized($account_type, "ft-delete-pos", "", "") === "allowed") && is_authorized($account_type, "sb-products", "", "") === "allowed" && is_authorized($account_type, "sb-manage-pos", "", "") === "allowed"){?>
                                            <th></th>
                                        <?php } ?>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div id="return_server_msg"></div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="modal fade" id="update-my-password" tabindex="-1" role="dialog" aria-labelledby="update-my-password" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    
                    <h5 class="modal-title" id="update-my-password">Change Password</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="update-password">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Old Password</label>
                                    <input type="password" name="old_password" class="form-control"/>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>New Password</label>
                                    <input type="hidden" value="<?php echo $account_id;?>" name="account_id" class="form-control" value=""/>
                                    <input type="password" name="new_password" class="form-control" value=""/>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Confirm New Password</label>
                                    <input type="password" name="confirm_new_password" class="form-control" value=""/>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" id="update-password-btn" class="btn btn-md btn-success"><span class="fa fa-save"></span> Update Password </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php if(is_authorized($account_type, "pg-edit-employee", "", "") === "allowed" && is_authorized($account_type, "sb-employee", "", "") === "allowed" ){?>
        <div class="modal fade" id="update-employee-password" tabindex="-1" role="dialog" aria-labelledby="update-employee-password" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        
                        <h5 class="modal-title" id="update-employee-password">Change Password</h5>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="employee-password">
                            <div class="row">
                                <!-- <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Old Password</label>
                                        <input type="password" name="old_password" class="form-control"/>
                                    </div>
                                </div> -->
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>New Password</label>
                                        <input type="hidden" value="<?php echo $account_id;?>" name="account_id" class="form-control" value=""/>
                                        <input type="password" name="new_password" class="form-control" value=""/>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Confirm New Password</label>
                                        <input type="password" name="confirm_new_password" class="form-control" value=""/>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" id="update-password-btn" class="btn btn-md btn-success"><span class="fa fa-save"></span> Update Password </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="modal fade" id="inactivity_modal_settings" tabindex="-1" role="dialog" aria-labelledby="inactivity_modal_settings" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="app_customizer">Inactivity Checker</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
    
                    <div class="alert alert-secondary">
                        <b> Choose when you will be Signed Out if <span class='text-primary'> [SYSTEM]</span> detects <b><span class='text-danger'>[X MINUTES]</span></b> of inactivity on your account. </b>
                    </div>
    
                    <form method="POST" id="inactivity_checker_settings_form">
                        <div class="row">
    
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="sidebar_layout">Select Minutes</label> 
                                    <select onchange="auto_save_inactivity_checker()" name="minutes" class="form-control" id="minutes">
                                        <?php echo get_max_timing_data("list", get_user_data($account_id, 'signout_inactivity_after'), "");?>
                                    </select>
                                </div>
                            </div>
    
                            <script>
                                
                                function auto_save_inactivity_checker(){
    
                                    var dataString = $("#inactivity_checker_settings_form").serialize();
    
                                    $.ajax({
                                        type: "POST",
                                        url: "./includes/ajax/save-inactivity",
                                        data: dataString,
                                        cache: false,
                                        beforeSend: function() { toastr.info("<b>Saving changes...</b>"); },
                                        success: function(d) { $('div#return_server_msg').fadeIn('slow').html(d); },
                                        error: function(d) { toastr.error("Something went wrong!"); }
                                    });
                                }
    
                            </script>
    
                        </div>
                    </form>
    
                    <div id="return_server_msg"></div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal2" id="network-error" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content modal-content2">
                <div class="modal-header"> 
                    <h5 class="modal-title" id="update-my-password"><span class="fa fa-wifi"></span> <b>Network Status</b></h5>
                </div>
                <div class="modal-body">
                    <h2> <b> <span class="fa fa-exclamation-triangle text-warning"></span> Server can't be reached. </b> </h2>
                    <h4> Please try checking your connection.</h4>
                    <p><span class="fa fa-spin fa-spinner"></span> <b><i>Hold on, network availability is being checked automatically.</i></b></p>
                </div>
            </div>
        </div>
    </div>

    <style>
        .modal2 {
            display: none; 
            position: fixed;
            z-index: 10000000000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4); 

        }

        .modal-content2 {
            background-color: brown;
            color:white;
            width: 100%;
        }

        .modal2-content {
            background-color: red;
            color:white;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        .close2 {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close2:hover,
        .close2:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>

    <?php if(is_authorized($account_type, "sb-settings", "", "") === "allowed" && is_authorized($account_type, "sb-settings-identity", "", "") === "allowed" ){ ?>
        <div class="modal fade" id="upload_app_logo" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="get_brand_list">Upload Invoice Logo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <form method="POST" id="upload_invoice_logo">

                            <div style="padding:10px;" align="center" id="uploaded_image_file">
                                <?php
                                    // if(get_company_data("show_logo") == "yes") {
                                        if(!empty(get_company_data("uploaded_logo")) && is_file("./dist/images/uploaded/".get_company_data("uploaded_logo")) ) { ?>
                                            <img style="border-radius:50%;padding:5px;border:1px #000 solid;width:50px;" src="./dist/images/uploaded/<?php echo get_company_data("uploaded_logo");?>">
                                        <?php } else {?>
                                            <img style="border-radius:50%;padding:5px;border:1px #000 solid;width:50px;" src="./dist/images/logo.png">
                                        <?php
                                        }
                                    // }
                                ?>
                            </div>

                            <p style="padding:10px;border:1px #000 solid;width:100%;"> <input id="upload-invoice-logo" name="upload-invoice-logo" type="file" accept=".png,.jpg,.jpeg" style="width:100%;"> </p>

                            <p>
                                <button id="invoice-image-upload-btn" class="btn btn-danger" type="submit">
                                    <span class="fa fa-upload"></span> Upload Image
                                </button>
                            </p>

                        </form>

                        <hr/>

                        <div id="return_server_msg"></div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>

    <div class="modal fade" id="inactivity_modal_settings" tabindex="-1" role="dialog" aria-labelledby="inactivity_modal_settings" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="app_customizer">Inactivity Checker</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
    
                    <div class="alert alert-secondary">
                        <b> Choose when you will be Signed Out if <span class='text-primary'> [SYSTEM]</span> detects <b><span class='text-danger'>[X MINUTES]</span></b> of inactivity on your account. </b>
                    </div>
    
                    <form method="POST" id="inactivity_checker_settings_form">
                        <div class="row">
    
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="sidebar_layout">Select Minutes</label> 
                                    <select onchange="auto_save_inactivity_checker()" name="minutes" class="form-control" id="minutes">
                                        <?php echo get_max_timing_data("list", get_user_data($account_id, 'signout_inactivity_after'), "");?>
                                    </select>
                                </div>
                            </div>
    
                            <script>
                                
                                function auto_save_inactivity_checker(){
    
                                    var dataString = $("#inactivity_checker_settings_form").serialize();
    
                                    $.ajax({
                                        type: "POST",
                                        url: "./includes/ajax/save-inactivity",
                                        data: dataString,
                                        cache: false,
                                        beforeSend: function() { toastr.info("<b>Saving changes...</b>"); },
                                        success: function(d) { $('div#return_server_msg').fadeIn('slow').html(d); },
                                        error: function(d) { toastr.error("Something went wrong!"); }
                                    });
                                }
    
                            </script>
    
                        </div>
                    </form>
    
                    <div id="return_server_msg"></div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

<?php } ?>