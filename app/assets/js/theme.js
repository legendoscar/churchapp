// var doc = new jsPDF();
// var specialElementHandlers = {
//     '#editor': function(element, renderer) {
//         return true;
//     }
// };

//margins.left, // x coord   margins.top, { // y coord
// $('#generatePDF').click(function() {
//     doc.fromHTML($('#htmlContent').html(), 15, 15, {
//         'width': 700,
//         'elementHandlers': specialElementHandlers
//     });
//     doc.save('Payslip.pdf');
// });

$("#add-product").submit(function(e) {
    e.preventDefault();

    var dataString = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/add-product",
        data: dataString,
        cache: false,
        beforeSend: function() {
            $("button#create_product_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Creating Product...</i>");
            $("button#create_product_btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#create_product_btn").fadeIn("slow").html("<span class='fa fa-paper-plane'></span> Create Product");
            $("button#create_product_btn").removeAttr("disabled");
        },
        error: function(d) {
            toastr.error("Something went wrong!");
            $("button#create_product_btn").fadeIn("slow").html("<span class='fa fa-paper-plane'></span> Try Again?");
            $("button#create_product_btn").removeAttr("disabled");
        }
    });
    return false;
});





$("#update-product").submit(function(e) {
    e.preventDefault();

    // var dataString = "brand_name=" + $("#product_brand_name").val() +

    //     "&product_name=" + $("#product_name").val() +
    //     "&product_id=" + $("#product_id").val() +

    //     "&single_unit_type=" + $("#single_unit_type").val() +
    //     "&wholesale_unit_type=" + $("#wholesale_unit_type").val() +

    //     "&quantity=" + $("#quantity").val() +

    //     "&stock_price=" + $("#stock_price").val() +

    //     "&wholesale_selling_price=" + $("#wholesale_selling_price").val() +
    //     "&retail_selling_price=" + $("#retail_selling_price").val() +

    //     "&size=" + $("#size").val();

    var dataString = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-product",
        data: dataString,
        cache: false,
        beforeSend: function() {
            $("button#update_product_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Updating Product...</i>");
            $("button#update_product_btn").attr("disabled", "disabled");
        },

        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update_product_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Update");
            $("button#update_product_btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update_product_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
            $("button#update_product_btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
});


$("#update-invoice-btn").click(function(e) {
    // e.preventDefault();

    var customer_name = $("#customer_name").val();
    var payment_status = $("#payment_status").val();
    var additional_note = $("#additional_note").val();
    var invoice_number = $("#invoice_number").val();
    var invoice_id = $("#invoice_id").val();

    var form_data = "customer_name=" + customer_name + "&payment_status=" + payment_status + "&additional_note=" + additional_note + "&invoice_number=" + invoice_number + "&invoice_id=" + invoice_id;

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-invoice",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-invoice-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Saving changes...</i>");
            $("button#update-invoice-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-invoice-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Save changes");
            $("button#update-invoice-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-invoice-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#update-invoice-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
});

function get_item_data(invoice_id, invoice_number, row_id) {

    $("#edit_item_container").html("<center><b><span class='fa fa-spin fa-spinner'></span> Loading... Please wait</b></center>");

    var form_data = "row_id=" + row_id + "&invoice_number=" + invoice_number + "&invoice_id=" + invoice_id;

    $.ajax({
        type: "POST",
        url: "./includes/ajax/get-receipt-item-data",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("#item_row_id_" + row_id).fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span>");
            $("#item_row_id_" + row_id).attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("#item_row_id_" + row_id).fadeIn("slow").html("<span class='fa fa-pencil'></span>");
            $("#item_row_id_" + row_id).removeAttr("disabled");
        },
        error: function(d) {
            $("#item_row_id_" + row_id).fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("#edit_item_container").html("<center>Something went wrong! Close and try again.</center>");
            $("#item_row_id_" + row_id).removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

}

function get_receipt_payment_transaction(invoice_id, invoice_number) {

    $("#receipt_payment_transaction_container").html("<center><b><span class='fa fa-spin fa-spinner'></span> Loading... Please wait</b></center>");
    $("#receipt_id").html(invoice_number);

    var form_data = "invoice_number=" + invoice_number + "&invoice_id=" + invoice_id;

    $.ajax({
        type: "POST",
        url: "./includes/ajax/get-receipt-payment-transaction-data",
        data: form_data,
        cache: false,
        beforeSend: function() {
            // $("#amount_id_" + invoice_id).fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span>");
            // $("#amount_id_" + invoice_id).attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            // $("#amount_id_" + invoice_id).fadeIn("slow").html("<span class='fa fa-pencil'></span>");
            // $("#amount_id_" + invoice_id).removeAttr("disabled");
        },
        error: function(d) {
            // $("#amount_id_" + invoice_id).fadeIn("slow").append("<span class='fa fa-undo'></span> Try Again?");
            $("#receipt_payment_transaction_container").html("<center>Something went wrong! Close and try again.</center>");
            // $("#amount_id_" + invoice_id).removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

}


function view_expenses(expenses_id) {

    $("#expenses_container").html("<center><b><span class='fa fa-spin fa-spinner'></span> Loading... Please wait</b></center>");

    var form_data = "expenses_id=" + expenses_id;

    $.ajax({
        type: "POST",
        url: "./includes/ajax/get-expenses-data",
        data: form_data,
        cache: false,
        beforeSend: function() {
            // $("#amount_id_" + invoice_id).fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span>");
            // $("#amount_id_" + invoice_id).attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            // $("#amount_id_" + invoice_id).fadeIn("slow").html("<span class='fa fa-pencil'></span>");
            // $("#amount_id_" + invoice_id).removeAttr("disabled");
        },
        error: function(d) {
            // $("#amount_id_" + invoice_id).fadeIn("slow").append("<span class='fa fa-undo'></span> Try Again?");
            $("#expenses_container").html("<center>Something went wrong! Close and try again.</center>");
            // $("#amount_id_" + invoice_id).removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

}

$("#create-expenses").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-expenses",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#action-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Create expenses...</i>");
            $("button#action-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#action-btn").fadeIn("slow").html("<span class='la la-file-alt'></span> Create Expenses");
            $("button#action-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#action-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#action-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});

$("#update-expenses").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-expenses",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#action-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Update expenses...</i>");
            $("button#action-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#action-btn").fadeIn("slow").html("<span class='la la-file-alt'></span> Update Expenses");
            $("button#action-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#action-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#action-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


$("#update-invoice-item").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-invoice-item",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-invoice-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Updating item...</i>");
            $("button#update-invoice-item-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-invoice-item-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Save changes");
            $("button#update-invoice-item-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-invoice-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#update-invoice-item-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});

function delete_invoice_item() {

    if (confirm("Are you sure you want to delete this item?")) {
        var form_data = $("form#update-invoice-item").serialize();

        $.ajax({
            type: "POST",
            url: "./includes/ajax/delete-invoice-item",
            data: form_data,
            cache: false,
            beforeSend: function() {
                $("button#delete-invoice-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Deleting item...</i>");
                $("button#delete-invoice-item-btn").attr("disabled", "disabled");
            },
            success: function(d) {
                $('div#return_server_msg').fadeIn('slow').html(d);
                $("button#delete-invoice-item-btn").fadeIn("slow").html("<span class='fa fa-trash-o'></span> Delete");
                $("button#delete-invoice-item-btn").removeAttr("disabled");
            },
            error: function(d) {
                $("button#delete-invoice-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
                $("button#delete-invoice-item-btn").removeAttr("disabled");
                toastr.error("Something went wrong!");
            }
        });
        return false;
    }
}

function deleteExpenses(target, item_id) {

    if (confirm("Are you sure you want to delete this item?")) {

        var form_data = $("form#update-invoice-item").serialize();
        var form_data = "item_id=" + item_id + "&target=" + target;

        $.ajax({
            type: "POST",
            url: "./includes/ajax/delete-expenses",
            data: form_data,
            cache: false,
            beforeSend: function() {
                $("#btn_" + item_id).fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span>");
                $("#btn_" + item_id).attr("disabled", "disabled");
            },
            success: function(d) {
                $('div#return_server_msg').fadeIn('slow').html(d);
                $("#btn_" + item_id).fadeIn("slow").html("<span class='fa fa-trash-o'></span>");
                $("#btn_" + item_id).removeAttr("disabled");
            },
            error: function(d) {
                $("#btn_" + item_id).fadeIn("slow").html("<span class='fa fa-undo'></span>");
                $("#btn_" + item_id).removeAttr("disabled");
                toastr.error("Something went wrong!");
            }
        });
        return false;
    }
}


$("#add-more-invoice-item").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/add-more-invoice-item",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#add-invoice-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Adding item...</i>");
            $("button#add-invoice-item-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#add-invoice-item-btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Add item");
            $("button#add-invoice-item-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#add-invoice-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#add-invoice-item-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


$("#create-invoice").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serializeArray();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-invoice",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#create_invoice_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Generating receipt...</i>");
            $("button#create_invoice_btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#create_invoice_btn").fadeIn("slow").html("<span class='fa fa-file-o'></span> Generate receipt");
            $("button#create_invoice_btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#create_invoice_btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#create_invoice_btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});

$("#create-payslip").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serializeArray();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-payslip",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#action-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Generating payslip...</i>");
            $("button#action-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#action-btn").fadeIn("slow").html("<span class='fa fa-file-o'></span> Generate Payslip");
            $("button#action-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#action-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#action-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});



function get_payslip_item_data(payslip_id, payslip_number, row_id) {

    $("#edit_item_container").html("<center><b><span class='fa fa-spin fa-spinner'></span> Loading... Please wait</b></center>");

    var form_data = "row_id=" + row_id + "&payslip_number=" + payslip_number + "&payslip_id=" + payslip_id;

    $.ajax({
        type: "POST",
        url: "./includes/ajax/get-payslip-item-data",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("#item_row_id_" + row_id).fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span>");
            $("#item_row_id_" + row_id).attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("#item_row_id_" + row_id).fadeIn("slow").html("<span class='fa fa-pencil'></span>");
            $("#item_row_id_" + row_id).removeAttr("disabled");
        },
        error: function(d) {
            $("#item_row_id_" + row_id).fadeIn("slow").html("<span class='fa fa-undo'></span>");
            $("#edit_item_container").html("<center>Something went wrong! Close and try again.</center>");
            $("#item_row_id_" + row_id).removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

}


$("#add-more-payslip-earnings-item").submit(function(e) {

    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/add-more-payslip-item",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#add-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Adding item...</i>");
            $("button#add-item-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#add-item-btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Add item");
            $("button#add-item-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#add-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#add-item-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});



$("#add-more-payslip-deductions-item").submit(function(e) {

    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/add-more-payslip-item",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#add-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Adding item...</i>");
            $("button#add-item-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#add-item-btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Add item");
            $("button#add-item-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#add-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#add-item-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});

$("#update-payslip-data").submit(function(e) {
    e.preventDefault();
});

$("#update-payslip-btn").click(function(e) {

    e.preventDefault();

    var form_data = $("#update-payslip-data").serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-payslip",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-payslip-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Updating payslip...</i>");
            $("button#update-payslip-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-payslip-btn").fadeIn("slow").html("<span class='la la-file-alt'></span> Update payslip");
            $("button#update-payslip-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-payslip-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#update-payslip-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});

$("#update-payslip-transaction-item").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-payslip-transaction-item",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Updating item...</i>");
            $("button#update-item-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-item-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Save changes");
            $("button#update-item-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#update-item-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


function delete_payslip_transaction_item() {

    if (confirm("Are you sure you want to delete this item?")) {
        var form_data = $("form#update-payslip-transaction-item").serialize();

        $.ajax({
            type: "POST",
            url: "./includes/ajax/delete-payslip-transaction-item",
            data: form_data,
            cache: false,
            beforeSend: function() {
                $("button#delete-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Deleting item...</i>");
                $("button#delete-item-btn").attr("disabled", "disabled");
            },
            success: function(d) {
                $('div#return_server_msg').fadeIn('slow').html(d);
                $("button#delete-item-btn").fadeIn("slow").html("<span class='fa fa-trash-o'></span> Delete");
                $("button#delete-item-btn").removeAttr("disabled");
            },
            error: function(d) {
                $("button#delete-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
                $("button#delete-item-btn").removeAttr("disabled");
                toastr.error("Something went wrong!");
            }
        });
        return false;
    }
}


function mini_removal() {
    $("#customer_balance_0").remove();
    $("button#apply_customer_balance_btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Apply Balance");
    $("button#apply_customer_balance_btn").removeAttr("disabled");
    $("button#apply_customer_balance_btn").attr("onclick", "apply_customer_bal()");
    $("#customer_bal_action_btn").html("");
    Calculate_Splits();
}


function display_custom_select() {
    $("#customer_switch_for_select").removeAttr("style");
    $("#customer_switch_for_new").attr("style", "display:none");

    $("#customer_switch_for_select select").attr("name", "payer_name");
    $("#customer_switch_for_new input").removeAttr("name");

    $("#customer_switch_for_select select").attr("id", "customer_name");
    $("#customer_switch_for_new input").removeAttr("id");

    $("#switch_back_btn").html("");
    $("#switch_tracker").val("returning-customer");
    Calculate_Splits();
    mini_removal();
}


function load_customer_bal(customer_id) {
    if (customer_id == "create-account") {
        $("#customer_switch_for_new").removeAttr("style");

        $("#customer_switch_for_new input").attr("name", "payer_name");
        $("#customer_switch_for_select select").removeAttr("name");

        $("#customer_switch_for_new input").attr("id", "customer_name");
        $("#customer_switch_for_select select").removeAttr("id");

        $("#customer_switch_for_select").attr("style", "display:none");
        $("#customer_account_loader").html("");
        $("#switch_tracker").val("create-account");
        $("#switch_back_btn").html("<a id='show_select' onclick='display_custom_select()' style='margin-left:10px;' href='javascript:(void)'><b>Select</b></a>");
        Calculate_Splits();
        mini_removal();
    } else {
        var dataString = "customer_id=" + customer_id;
        $.ajax({
            type: "POST",
            url: "./includes/ajax/get-customer-account-details",
            data: dataString,
            cache: false,
            beforeSend: function() {
                $("#customer_account_loader").html("<span class='text-danger fa fa-spin fa-spinner'></span> <i class='text-danger'><b>fetching records...</b></i>");
            },
            success: function(d) {
                $('#customer_account_loader').fadeIn('slow').html(d);
            },
            error: function(d) {
                $("#customer_account_loader").html("<span class='text-danger'><b>Error!. Try again.</b><span>");
                toastr.error("Something went wrong!");
            }
        });
    }
}


function load_employee_payroll_data(employee_id) {

    var dataString = "employee_id=" + employee_id;
    $.ajax({
        type: "POST",
        url: "./includes/ajax/get-employee-account-details",
        data: dataString,
        cache: false,
        beforeSend: function() {
            $("#account_loader").html("<span class='text-danger fa fa-spin fa-spinner'></span> <i class='text-danger'><b>fetching records...</b></i>");
        },
        success: function(d) {
            $('#account_loader').fadeIn('slow').html(d);
        },
        error: function(d) {
            $("#account_loader").html("<span class='text-danger'><b>Error!. Try again.</b><span>");
            toastr.error("Something went wrong!");
        }
    });
}


$("#create-purchase-invoice").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serializeArray();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-purchase-invoice",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#create_invoice_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i>Creating Invoice...</i>");
            $("button#create_invoice_btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#create_invoice_btn").fadeIn("slow").html("<span class='fa fa-check'></span> Create Invoice");
            $("button#create_invoice_btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#create_invoice_btn").fadeIn("slow").html("<span class='fa fa-check'></span> Try Again?");
            $("button#create_invoice_btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


$("#setup_daily_stock").click(function(e) {

    $.ajax({
        type: "POST",
        url: "./includes/ajax/setup-daily-stock",
        cache: false,
        beforeSend: function() {
            $("button#setup_daily_stock").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Setting Up...</i>");
            $("button#setup_daily_stock").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#setup_daily_stock").fadeIn("slow").html("<span class='fa fa-check'></span> Setup Today's Stock");
            $("button#setup_daily_stock").removeAttr("disabled");
        },
        error: function(d) {
            $("button#setup_daily_stock").fadeIn("slow").html("<span class='fa fa-check'></span> Try Again?");
            $("button#setup_daily_stock").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


$("#create-payment-account").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-payment-account",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#create-payment-account-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Creating Account...</i>");
            $("button#create-payment-account-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#create-payment-account-btn").fadeIn("slow").html("<span class='fa fa-user-plus'></span> Create Account");
            $("button#create-payment-account-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#create-payment-account-btn").fadeIn("slow").html("<span class='fa fa-user-plus'></span> Try Again?");
            $("button#create-payment-account-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


$("#create-new-role").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-new-role",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Creating Item...</i>");
            $("button#new-item-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Create Item");
            $("button#new-item-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#new-item-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


function get_role_data(role_id) {

    $("#role_container").html("<center><b><span class='fa fa-spin fa-spinner'></span> Loading... Please wait</b></center>");

    var form_data = "role_id=" + role_id;

    $.ajax({
        type: "POST",
        url: "./includes/ajax/get-role-data",
        data: form_data,
        cache: false,
        beforeSend: function() {},
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
        },
        error: function(d) {
            $("#role_container").html("<center>Something went wrong! Close and try again.</center>");
            toastr.error("Something went wrong!");
        }
    });
    return false;

}


$("#update_role").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-role",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-role-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Saving changes...</i>");
            $("button#update-role-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-role-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Save changes");
            $("button#update-role-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-role-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#update-role-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


function delete_role_item(role_id) {

    if (confirm("Are you sure you want to delete this role?\nIf you delete this role, users assigned to it will no longer be able to login until they are assigned to a new role.")) {
        var form_data = $("#update_role").serialize();

        $.ajax({
            type: "POST",
            url: "./includes/ajax/delete-role",
            data: form_data,
            cache: false,
            beforeSend: function() {
                $("button#delete-role-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Delete changes...</i>");
                $("button#delete-role-btn").attr("disabled", "disabled");
            },
            success: function(d) {
                $('div#return_server_msg').fadeIn('slow').html(d);
                $("button#delete-role-btn").fadeIn("slow").html("<span class='la la-trash-alt'></span> Delete ");
                $("button#delete-role-btn").removeAttr("disabled");
            },
            error: function(d) {
                $("button#delete-role-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
                $("button#delete-role-btn").removeAttr("disabled");
                toastr.error("Something went wrong!");
            }
        });
        return false;
    }
}



$("#create-payment-channels-bank").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/settings-create-payment-channels-bank",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#create-payment-channels-bank-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Creating account...</i>");
            $("button#create-payment-channels-bank-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#create-payment-channels-bank-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Create Account");
            $("button#create-payment-channels-bank-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#create-payment-channels-bank-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#create-payment-channels-bank-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


$("#update-payment-channels-bank").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/settings-update-payment-channels-bank",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-payment-channels-bank-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating account...</i>");
            $("button#update-payment-channels-bank-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-payment-channels-bank-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Update Account");
            $("button#update-payment-channels-bank-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-payment-channels-bank-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#update-payment-channels-bank-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


$("#create-payment-channels-pos").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/settings-create-payment-channels-pos",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#create-payment-channels-pos-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Creating account...</i>");
            $("button#create-payment-channels-pos-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#create-payment-channels-pos-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Create Account");
            $("button#create-payment-channels-pos-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#create-payment-channels-pos-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#create-payment-channels-pos-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


$("#update-payment-bank").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/settings-update-payment-bank",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-payment-bank-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating bank...</i>");
            $("button#update-payment-bank-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-payment-bank-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Update Bank");
            $("button#update-payment-bank-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-payment-bank-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#update-payment-bank-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


$("#create-payment-bank").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/settings-create-payment-bank",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#create-payment-bank-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Creating bank...</i>");
            $("button#create-payment-bank-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#create-payment-bank-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Create Bank");
            $("button#create-payment-bank-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#create-payment-bank-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#create-payment-bank-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


$("#update-payment-channels-pos").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/settings-update-payment-channels-pos",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-payment-channels-pos-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating account...</i>");
            $("button#update-payment-channels-pos-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-payment-channels-pos-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Update Account");
            $("button#update-payment-channels-pos-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-payment-channels-pos-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#update-payment-channels-pos-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});



$("#update-payment-account").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-payment-account",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-payment-account-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating account...</i>");
            $("button#update-payment-account-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-payment-account-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Update Account");
            $("button#update-payment-account-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-payment-account-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#update-payment-account-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


$("#update-employee-account").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-employee-account",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-employee-account").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating Account...</i>");
            $("button#update-employee-account").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-employee-account").fadeIn("slow").html("<span class='fa fa-save'></span> Update Account");
            $("button#update-employee-account").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-employee-account").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
            $("button#update-employee-account").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


$("#update-group").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-group-info",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-group-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Saving changes...</i>");
            $("button#update-group-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-group-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Save changes");
            $("button#update-group-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-group-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
            $("button#update-group-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});

$("#update-customer-account").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-customer-account",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-customer-account").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating Account...</i>");
            $("button#update-customer-account").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-customer-account").fadeIn("slow").html("<span class='fa fa-save'></span> Update Account");
            $("button#update-customer-account").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-customer-account").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
            $("button#update-customer-account").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});

$("#employee-password").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-employee-password",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-password-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating Password...</i>");
            $("button#update-password-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-password-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Update Password");
            $("button#update-password-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-password-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
            $("button#update-password-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});

$("#update-password").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-password",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-password-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating Password...</i>");
            $("button#update-password-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-password-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Update Password");
            $("button#update-password-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-password-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
            $("button#update-password-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


$("#update_stock").submit(function(e) {
    e.preventDefault();

    var form_data = $("#update_stock").serialize();

    if (confirm("Are you sure you want to update product's Stock?")) {
        $.ajax({
            type: "POST",
            url: "./includes/ajax/update-stock",
            data: form_data,
            cache: false,
            beforeSend: function() {
                $("button#update_stock_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating Stock...</i>");
                $("button#update_stock_btn").attr("disabled", "disabled");
            },
            success: function(d) {
                $('div#return_server_msg').fadeIn('slow').html(d);
                $("button#update_stock_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Update Stock");
                $("button#update_stock_btn").removeAttr("disabled");
            },
            error: function(d) {
                $("button#update_stock_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
                $("button#update_stock_btn").removeAttr("disabled");
                toastr.error("Something went wrong!");
            }
        });
        return false;
    }


});


$("#create-new-payable-item").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    // if (confirm("Are you sure you want to update product's Stock?")) {
    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-payable-item",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Creating item...</i>");
            $("button#new-item-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Create Item");
            $("button#new-item-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#new-item-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
    // }


});

$("#create-new-payslip-item").submit(function(e) {
    e.preventDefault();
    var form_data = $(this).serialize();
    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-payslip-item",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Creating item...</i>");
            $("button#new-item-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Create Item");
            $("button#new-item-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#new-item-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
});

$("#create-new-expenses-item").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    // if (confirm("Are you sure you want to update product's Stock?")) {
    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-expenses-item",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Creating item...</i>");
            $("button#new-item-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Create Item");
            $("button#new-item-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#new-item-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
    // }


});

$("#create-new-branch").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    // if (confirm("Are you sure you want to update product's Stock?")) {
    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-branch",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Creating item...</i>");
            $("button#new-item-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Create Item");
            $("button#new-item-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#new-item-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
    // }


});


$("#create-employee").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-employee",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#action-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Creating account...</i>");
            $("button#action-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#action-btn").fadeIn("slow").html("<span class='la la-user-plus'></span> Create account");
            $("button#action-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#action-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#action-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
    // }


});


$("#edit-employee").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-employee",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#action-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating account...</i>");
            $("button#action-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#action-btn").fadeIn("slow").html("<span class='la la-user-plus'></span> Update account");
            $("button#action-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#action-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#action-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
    // }


});


$("#edit-profile").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-profile",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#action-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating account...</i>");
            $("button#action-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#action-btn").fadeIn("slow").html("<span class='la la-user-plus'></span> Update account");
            $("button#action-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#action-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#action-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
    // }


});



$("#update-user-payroll-settings-btn").click(function(e) {
    e.preventDefault();

    var form_data = "employee_id=" + $("#employee_id").val() + "&payment_bank=" + $("#payment_bank").val() + "&user_has_payroll=" + $("#user_has_payroll").val() + "&payroll_employee_url_id=" + $("#payroll_employee_url_id").val() + "&basic_salary=" + $("#basic_salary").val() + "&account_name=" + $("#account_name").val() + "&account_number=" + $("#account_number").val() + "&bank_name=" + $("#bank_name").val();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-user-payroll-settings",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-user-payroll-settings-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span>");
            $("button#update-user-payroll-settings-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-user-payroll-settings-btn").fadeIn("slow").html("<span class='fa fa-save'></span>");
            $("button#update-user-payroll-settings-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-user-payroll-settings-btn").fadeIn("slow").html("<span class='fa fa-undo'></span>");
            $("button#update-user-payroll-settings-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
    // }


});



$("#update-self-payroll-settings-btn").click(function(e) {
    e.preventDefault();

    var form_data = "user_has_payroll=" + $("#user_has_payroll").val() + "&basic_salary=" + $("#basic_salary").val() + "&account_name=" + $("#account_name").val() + "&account_number=" + $("#account_number").val() + "&bank_name=" + $("#bank_name").val();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-profile-payroll-settings",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-self-payroll-settings-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span>");
            $("button#update-self-payroll-settings-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-self-payroll-settings-btn").fadeIn("slow").html("<span class='fa fa-save'></span>");
            $("button#update-self-payroll-settings-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-self-payroll-settings-btn").fadeIn("slow").html("<span class='fa fa-undo'></span>");
            $("button#update-self-payroll-settings-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
});



$("#create-new-department").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-department",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Creating item...</i>");
            $("button#new-item-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Create Item");
            $("button#new-item-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#new-item-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
    // }


});


$("#create-new-account-title").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-account-title",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Creating item...</i>");
            $("button#new-item-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Create Item");
            $("button#new-item-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#new-item-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
    // }


});


$("#create-new-designation").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-designation",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Creating item...</i>");
            $("button#new-item-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-plus'></span> Create Item");
            $("button#new-item-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#new-item-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#new-item-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
    // }


});


$("#company-settings").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/settings-company",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-company-settings-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Saving changes...</i>");
            $("button#update-company-settings-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-company-settings-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Save changes");
            $("button#update-company-settings-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-company-settings-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#update-company-settings-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
});


$("#company-invoice").submit(function(e) {
    e.preventDefault();

    var form_data = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/settings-invoice",
        data: form_data,
        cache: false,
        beforeSend: function() {
            $("button#update-invoice-settings-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Saving changes...</i>");
            $("button#update-invoice-settings-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-invoice-settings-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Save changes");
            $("button#update-invoice-settings-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-invoice-settings-btn").fadeIn("slow").html("<span class='fa fa-undo'></span> Try Again?");
            $("button#update-invoice-settings-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
});


$("#update_retail_stock").submit(function(e) {
    e.preventDefault();

    var form_data = $("#update_retail_stock").serialize();

    if (confirm("Are you sure you want to update product's Stock?")) {
        $.ajax({
            type: "POST",
            url: "./includes/ajax/update-retail-stock",
            data: form_data,
            cache: false,
            beforeSend: function() {
                $("button#update_retail_stock_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating Stock...</i>");
                $("button#update_retail_stock_btn").attr("disabled", "disabled");
            },
            success: function(d) {
                $('div#return_server_msg').fadeIn('slow').html(d);
                $("button#update_retail_stock_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Update Stock");
                $("button#update_retail_stock_btn").removeAttr("disabled");
            },
            error: function(d) {
                $("button#update_retail_stock_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
                $("button#update_retail_stock_btn").removeAttr("disabled");
                toastr.error("Something went wrong!");
            }
        });
        return false;
    }
});





$("#reject_update_stock").submit(function(e) {
    e.preventDefault();

    var form_data = $("#reject_update_stock").serialize();

    if (confirm("Are you sure you want to update Rejected Stock?")) {
        $.ajax({
            type: "POST",
            url: "./includes/ajax/update-reject-stock",
            data: form_data,
            cache: false,
            beforeSend: function() {
                $("button#reject_update_stock_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating Stock...</i>");
                $("button#reject_update_stock_btn").attr("disabled", "disabled");
            },
            success: function(d) {
                $('div#return_server_msg').fadeIn('slow').html(d);
                $("button#reject_update_stock_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Update Stock");
                $("button#reject_update_stock_btn").removeAttr("disabled");
            },
            error: function(d) {
                $("button#reject_update_stock_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
                $("button#reject_update_stock_btn").removeAttr("disabled");
                toastr.error("Something went wrong!");
            }
        });
        return false;
    }

});



$("#reject_update_retail_stock").submit(function(e) {
    e.preventDefault();

    var form_data = $("#reject_update_retail_stock").serialize();

    if (confirm("Are you sure you want to update product's Stock?")) {
        $.ajax({
            type: "POST",
            url: "./includes/ajax/update-retail-reject-stock",
            data: form_data,
            cache: false,
            beforeSend: function() {
                $("button#reject_update_retail_stock_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating Stock...</i>");
                $("button#reject_update_retail_stock_btn").attr("disabled", "disabled");
            },
            success: function(d) {
                $('div#return_server_msg').fadeIn('slow').html(d);
                $("button#reject_update_retail_stock_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Update Stock");
                $("button#reject_update_retail_stock_btn").removeAttr("disabled");
            },
            error: function(d) {
                $("button#reject_update_retail_stock_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
                $("button#reject_update_retail_stock_btn").removeAttr("disabled");
                toastr.error("Something went wrong!");
            }
        });
        return false;
    }
});


function track_inactivity() {
    var form_data;
    $.ajax({
        type: "POST",
        url: "./includes/ajax/check_inactivity",
        data: form_data,
        cache: false,
        beforeSend: function() {
            toastr.error("<i><b>Checking Active Session...</b></i>");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update-password-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Update Password");
            $("button#update-password-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update-password-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
            $("button#update-password-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
}

setInterval(track_inactivity, 60000); // 30 Minutes 1800000 


function check_network_availability() {
    var form_data;

    $.ajax({
        type: "POST",
        url: "./includes/ajax/network_check",
        data: form_data,
        cache: false,
        success: function() {
            loadModal("hide");
            $("select.product_id_loader").removeAttr("disabled", "disabled");
        },
        error: function() {
            loadModal("show");
            $("select.product_id_loader").attr("disabled", "disabled");

        }
    });

}

setInterval(check_network_availability, 10000); // Check server responds every 10 seconds 

function loadModal(action) {
    var modal = document.getElementById('network-error');
    if (action == "show") { modal.style.display = "block"; } else { modal.style.display = "none"; }
}



$("#exec_data_export_bk").submit(function(e) {
    e.preventDefault();

    if (confirm("Ready to export data?")) {
        $.ajax({
            type: "POST",
            url: "./includes/ajax/export",
            cache: false,
            beforeSend: function() {
                $("button#exec_data_export_bk_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Exporting Data, please wait...</i>");
                $("button#exec_data_export_bk_btn").attr("disabled", "disabled");
            },
            success: function(d) {
                $('div#return_server_msg').fadeIn('slow').html(d);
                $("button#exec_data_export_bk_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Backup Data");
                $("button#exec_data_export_bk_btn").removeAttr("disabled");
            },
            error: function(d) {
                $("button#exec_data_export_bk_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
                $("button#exec_data_export_bk_btn").removeAttr("disabled");
                toastr.error("Something went wrong!");
            }
        });
        return false;
    }


});



$("#save-settings").submit(function(e) {
    e.preventDefault();

    var dataString = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/save-settings",
        data: dataString,
        cache: false,
        beforeSend: function() {
            $("button#save-settings-btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Saving Changes...</i>");
            $("button#save-settings-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#save-settings-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Update Settings");
            $("button#save-settings-btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#save-settings-btn").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
            $("button#save-settings-btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
});



$("#set_privileges").submit(function(e) {
    e.preventDefault();

    var dataString = $("#set_privileges").serialize();
    $.ajax({
        type: "POST",
        url: "./includes/ajax/save-privileges",
        data: dataString,
        cache: false,
        beforeSend: function() {
            $("button#exec_data_export_bk_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Exporting Data, please wait...</i>");
            $("button#exec_data_export_bk_btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg2').fadeIn('slow').html(d);
            $("button#exec_data_export_bk_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Backup Data");
            $("button#exec_data_export_bk_btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#exec_data_export_bk_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
            $("button#exec_data_export_bk_btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
});



$("#customizer_form").submit(function(e) {
    e.preventDefault();

    var dataString = $("#customizer_form").serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/save-customizer",
        data: dataString,
        cache: false,
        beforeSend: function() {
            toastr.info("<b>Saving changes...</b>");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
        },
        error: function(d) {
            toastr.error("Something went wrong!");
        }
    });
    return false;
});


$("#create_user_role").submit(function(e) {
    e.preventDefault();

    dataString = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/create-user-role",
        data: dataString,
        cache: false,
        beforeSend: function() {
            $("button#create_user_role_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Creating Role...</i>");
            $("button#create_user_role_btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#create_user_role_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Create Role ");
            $("button#create_user_role_btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#create_user_role_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
            $("button#create_user_role_btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});


$("#update_user_role").submit(function(e) {
    e.preventDefault();

    dataString = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/update-user-role",
        data: dataString,
        cache: false,
        beforeSend: function() {
            $("button#update_user_role_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Updating Role...</i>");
            $("button#update_user_role_btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#update_user_role_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Update Role ");
            $("button#update_user_role_btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#update_user_role_btn").fadeIn("slow").html("<span class='fa fa-save'></span> Try Again?");
            $("button#update_user_role_btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;

});



$("#save_sales_report_additional_note").submit(function(e) {
    e.preventDefault();

    dataString = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/save-report-additional-report",
        data: dataString,
        cache: false,
        beforeSend: function() {
            $("button#save_additional_note_btn").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Saving note...</i>");
            $("button#save_additional_note_btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#return_server_msg').fadeIn('slow').html(d);
            $("button#save_additional_note_btn").fadeIn("slow").html("<span class='fa fa-file'></span> Save note ");
            $("button#save_additional_note_btn").removeAttr("disabled");
        },
        error: function(d) {
            $("button#save_additional_note_btn").fadeIn("slow").html("<span class='fa fa-check'></span> Try Again?");
            $("button#save_additional_note_btn").removeAttr("disabled");
            toastr.error("Something went wrong!");
        }
    });
    return false;
});


$("#import-form-js").submit(function(e) {
    e.preventDefault();

    setTimeout(() => {
        var form_data = new FormData(this);

        var file = $("input#upload-file")[0].files;

        if (file.length == 1) {

            $.ajax({
                type: "POST",
                url: "./includes/ajax/import",
                data: form_data,
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function(a) {
                    $("span#import-status").fadeIn("slow").html("<span class='fa fa-spin fa-spinner'></span> <i> Getting ready...</i>");
                    $("span#import-status").removeClass("text-danger");
                    $("span#import-status").addClass("text-secondary");
                    $("button#import-btn").html("<span class='fa fa-spin fa-spinner'></span> <i> Processing... </i>");
                    $("button#import-btn").attr("disabled", "disabled");
                },
                success: function(d) {
                    $('div#return_server_msg').fadeIn('slow').html(d);
                    $("button#create-hotel-btn").fadeIn("slow").html("CREATE HOTEL");
                    $("button#create-hotel-btn").removeAttr("disabled");
                },
                error: function(e) {
                    toastr.error("Something went wrong!");
                    $("button#import-btn").html("<span class='fa fa-download'></span> Import Data");
                    $("button#import-btn").attr("disabled", "disabled");
                }
            });
            return false;
        } else {
            toastr.error("Please select an SQL file!");
        }
    }, 10);
});


$("#upload_invoice_logo").submit(function(e) {
    e.preventDefault();

    setTimeout(() => {
        var form_data = new FormData(this);

        var file = $("input#upload-invoice-logo")[0].files;

        if (file.length == 1) {

            $.ajax({
                type: "POST",
                url: "./includes/ajax/upload-invoice-logo",
                data: form_data,
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function(a) {
                    $("button#invoice-image-upload-btn").html("<span class='fa fa-spin fa-spinner'></span> <i> Uploading image... </i>");
                    $("button#invoice-image-upload-btn").attr("disabled", "disabled");
                },
                success: function(d) {
                    $('div#return_server_msg').fadeIn('slow').html(d);
                    $("button#invoice-image-upload-btn").html("<span class='fa fa-upload'></span> Upload Image");
                    $("button#invoice-image-upload-btn").removeAttr("disabled");
                },
                error: function(e) {
                    toastr.error("Something went wrong!");
                    $("button#invoice-image-upload-btn").html("<span class='fa fa-upload'></span> Upload Image");
                    $("button#invoice-image-upload-btn").removeAttr("disabled");
                }
            });
            return false;
        } else {
            toastr.error("Please select an Image!");
        }
    }, 10);
});