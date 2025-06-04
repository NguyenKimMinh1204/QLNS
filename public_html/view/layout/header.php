<header class="main-header">

    <!-- Logo -->
    <a href="index.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <!-- <span class="logo-mini"><img src="../../assets/img/adsagency-logo-2.svg" alt="AdsAgency Logo"
                style="width:100px;"></span> -->
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><img src="../../assets/img/adsagency-logo-2.svg" alt="AdsAgency Logo"
                style="width:100px;"></span>
        <span></span>

    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">



                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <img src="../../assets/img/<?php echo $a->xuatavatarTheoId($user_id);?>" class="user-image"
                            alt="User Image">
                        <span class="hidden-xs"><?php echo $a->xuatUsernameTheoId($user_id);?> </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="../../assets/img/<?php echo $a->xuatavatarTheoId($user_id);?>" class="img-circle"
                                alt="User Image">

                            <p>
                                <?php echo $a->xuatUsernameTheoId($user_id);?>-
                                <?php echo $a->xuatPhongbanTheoId($user_id);?>
                                <small></small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">


                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="info_admin.php" class="btn btn-default btn-flat">Thông tin cá nhân</a>
                            </div>
                            <div class="pull-right">
                                <a href="../../logout.php" class="btn btn-default btn-flat">Đăng xuất</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                <li>
                    <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                </li>
            </ul>
        </div>

    </nav>
</header>