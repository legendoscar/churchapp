<div class="header">

    <div class="header-left">
        <a href="./" class="logo">
            <img src="./assets/img/logo.png" width="40" height="40" alt="">
        </a>
    </div>

    <a id="toggle_btn" href="javascript:void(0);">
        <span class="bar-icon">
            <span></span>
            <span></span>
            <span></span>
        </span>
    </a>

    <div class="page-title-box" style="margin-top:-10px;">
        <h3>
            <b><?php echo get_company_data("company_name");?></b>
            <br/>
            <small style="color:blue;font-size:15px;">Currently logged in as: <?php echo account_role_name($account_type, "role_name") ;?></small>
        </h3>
    </div>

    <a id="mobile_btn" class="mobile_btn" href="#sidebar"><i class="text-secondary la la-bars"></i></a>

    <ul class="nav user-menu">

        <li class="nav-item dropdown has-arrow main-drop">
            <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
                <span class="user-img"><img src="./assets/img/profiles/no-image.jpg" alt=""><span class="status offline"></span></span>
                <span><?php echo $firstname." ".$surname;?></span>
            </a>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="./profile">My Profile</a>
                <a class="dropdown-item" href="./profile#change-password">Change Password</a>
                <a class="dropdown-item" href="./logout">Logout</a>
            </div>
        </li>
    </ul>

    <div class="dropdown mobile-user-menu">
        <a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown">
            <span class="user-img"><img src="./assets/img/profiles/no-image.jpg" alt=""><span class="status offline"></span></span>
            <span><?php echo $firstname." ".$surname;?></span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="./profile">My Profile</a>
            <a class="dropdown-item" href="./profile#change-password">Change Password</a>
            <a class="dropdown-item" href="./logout">Logout</a>
        </div>
    </div>

</div>
