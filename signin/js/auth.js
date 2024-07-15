$("#sign-in").submit(function(e) {
    e.preventDefault();

    var dataString = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/login_form",
        data: dataString,
        cache: false,
        beforeSend: function() {
            $("button#login_btn").fadeIn("slow").html("Signing In...");
            $("button#login_btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#server-response').fadeIn('slow').html(d);
            $("button#login_btn").fadeIn("slow").html("Sign In <span class='bx bx-log-in'></span>");
            $("button#login_btn").removeAttr("disabled");
        },
        error: function(a) {
            toastr.error("Something went wrong.");
            $("button#login_btn").fadeIn("slow").html("Sign In <span class='bx bx-log-in'></span>");
            $("button#login_btn").removeAttr("disabled");
        }
    });
    return false;
});

$("#register").submit(function(e) {
    e.preventDefault();

    var dataString = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/ajax/register",
        data: dataString,
        cache: false,
        beforeSend: function() {
            $("button#register-btn").fadeIn("slow").html("Processing registration...");
            $("button#register-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#server_response').fadeIn('slow').html(d);
            $("button#register-btn").fadeIn("slow").html("Register ");
            $("button#register-btn").removeAttr("disabled");
        },
        error: function(a) {
            // $("div#server_response").fadeIn("slow").html("<div class='alert alert-danger'> <b>Something went wrong</b></div>");
            toastr.error("Something went wrong.");
            $("button#register-btn").fadeIn("slow").html("Register");
            $("button#register-btn").removeAttr("disabled");
        }
    });
    return false;
});

$("#password-reset").submit(function(e) {
    e.preventDefault();

    var dataString = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/process_password",
        data: dataString,
        cache: false,
        beforeSend: function() {
            $("button#password_btn").fadeIn("slow").html("<span class='fa fa-spinner fa-spin'></span> <i>Processing</i>...");
            $("button#password_btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#server-response').fadeIn('slow').html(d);
            $("button#password_btn").fadeIn("slow").html("Reset Password");
            $("button#password_btn").removeAttr("disabled");
        },
        error: function(a) {
            toastr.error("Something went wrong.");
            $("button#password_btn").fadeIn("slow").html("Reset Password");
            $("button#password_btn").removeAttr("disabled");

        }
    });
    return false;
});


$("#change-password").submit(function(e) {

    e.preventDefault();
    var dataString = $(this).serialize();

    $.ajax({
        type: "POST",
        url: "./includes/reset-password",
        data: dataString,
        cache: false,
        beforeSend: function() {
            $("button#reset-btn").fadeIn("slow").html("<span class='fa fa-spinner fa-spin'></span> <i>Processing</i>...");
            $("button#reset-btn").attr("disabled", "disabled");
        },
        success: function(d) {
            $('div#server-response').fadeIn('slow').html(d);
            $("button#reset-btn").fadeIn("slow").html("Reset");
            $("button#reset-btn").removeAttr("disabled");
        },
        error: function(a) {
            toastr.error("Something went wrong.");
            $("button#reset-btn").fadeIn("slow").html("Reset");
            $("button#reset-btn").removeAttr("disabled");
        }
    });
    return false;
});