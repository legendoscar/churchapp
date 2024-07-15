<?php if(isset($_SESSION['success_msg'])) { ?>
    <script src="dist/vendors/jquery/jquery-3.3.1.min.js"></script>
    <script src="dist/vendors/jquery-ui/jquery-ui.min.js"></script>

    <script>
        $(window).on("load", function(event) {
            toastr.success('<?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg'])?>');
        });
    </script>
<?php } ?>

<?php if(isset($_SESSION['error_msg'])) { ?>
    <script src="dist/vendors/jquery/jquery-3.3.1.min.js"></script>
    <script src="dist/vendors/jquery-ui/jquery-ui.min.js"></script>
    <script>
        $(window).on("load", function(event) {
            toastr.error('<?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg'])?>');
        });
    </script>
<?php } ?>