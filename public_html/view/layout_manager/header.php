<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="background-color: #999797">
    <div class="container-fluid">
        <div class="navbar-header">
            <img src="../../assets/img/adsagency-logo-2.svg" alt="AdsAgency Logo"
                style="width: 10%;     margin-top: 7px;">

            <ul class="user-menu">
                <li class="dropdown pull-right">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        <p
                            style="float:right; width:120px; height:30px; padding-top: 10px; color: white; font-family: arial; margin-left: 5px;">
                            <?php echo $a->xuatUsernameTheoId($user_id);?>
                        </p>
                        <img src="../../assets/img/<?php echo $a->xuatavatarTheoId($user_id);?>" class="img-circle"
                            alt="User Image" style="width:40px; height:40px; float:right;">
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="info.php"><svg class="glyph stroked male-user">
                                    <use xlink:href="#stroked-male-user"></use>
                                </svg> Thông tin cá nhân</a></li>
                        <li><a href="../../logout.php"><svg class="glyph stroked cancel">
                                    <use xlink:href="#stroked-cancel"></use>
                                </svg> Đăng xuất</a></li>
                    </ul>
                </li>
            </ul>
        </div>

    </div><!-- /.container-fluid -->
</nav>