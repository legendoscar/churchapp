<?php require "./includes/header.php";?>
<title> Sign In to your account | <?php echo get_company_data("company_name");?></title>

<body>
    <div class="min-h-100 w-100 p-0 p-6 p-sm-6 d-flex align-items-stretch-">
        <div  style="margin:-20px;" class="card w-25x flex-grow-1 flex-sm-grow-0 m-sm-auto">
            <div class="card-body p-sm-5 m-sm-3 flex-grow-0">
                <h1 class="mb-0 fs-3">Sign In</h1>
                <div class="fs-exact-14 text-muted mt-2 pt-1 mb-5 pb-2">Log in to your account to continue.</div>

                <form method="POST" id="sign-in">
                    <div class="mb-4">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="emailAddress" class="form-control form-control-lg" />
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg" />
                    </div>
                    <!-- <div class="mb-4 row py-2 flex-wrap">
                        <div class="col-auto d-flex align-items-center">
                            <br/> <a href="./forgot-password">Forgot password?</a>
                        </div>
                    </div> -->
                    <div><button id="login_btn" type="submit" class="btn btn-primary btn-lg w-100">Sign In</button></div>
                    <!-- <center style="margin-top:10px;"><a href="./register">Register an Account</a> </center> -->
                    <!-- <center styles="margin-top:10px;"><a href="../"><span class="fa fa-home"></span> Home</a> </center> -->
                    <div id="server-response"></div>
                </form>

            </div>

        </div>
    </div><!-- scripts -->

    <?php require "./includes/footer-script.php";?>
</body>

</html>