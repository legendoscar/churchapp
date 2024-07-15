<?php if(isset($_SESSION['error_msg'])) :?>

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> <?php echo $_SESSION['error_msg'];?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>

    <?php unset($_SESSION['error_msg']);?>
<?php endif?>


<?php if(isset($_SESSION['success_msg'])) :?>

    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> <?php echo $_SESSION['success_msg'];?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>

    <?php unset($_SESSION['success_msg']);?>

<?php endif?>


<?php if(isset($_SESSION['success_msg_a'])) :?>

    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> <?php echo $_SESSION['success_msg_a'];?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>

    <?php // unset($_SESSION['success_msg_a']);?>

<?php endif?>