<?php
include '../layout_employee/auth.php';
include('../../controller/class/class_info.php');
$user_id = $_SESSION['user_id'];
$a = new info();

// Thêm xử lý form submit trước khi hiển thị HTML
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "update_info") {
    $result = $a->updateUserInfo(
        $user_id,
        $_POST["email"],
        $_POST["cccd"],
        $_POST["address"],
        $_POST["phone"],
        $_POST["birthdate"]
    );
    echo "<script>alert('" . $result . "');</script>";
    // Refresh trang sau khi cập nhật
    echo "<script>window.location.href = window.location.href;</script>";
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thông tin cá nhân</title>

    <link href="../../assets/employee/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/employee/css/datepicker3.css" rel="stylesheet">
    <link href="../../assets/employee/css/styles.css" rel="stylesheet">

    <!-- <link href="../../assets/css/employee/info.css" rel="stylesheet"> -->

    <link href="../../assets/css/employee/info.css" rel="stylesheet">


    <!--Icons-->
    <script src="../../assets/employee/js/lumino.glyphs.js"></script>

    <!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->
    <style>

    .breadcrumb {
        border-radius: 0;
        padding: 27px 10px;
        background: #e9ecf2;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        margin: 0;
    }

    </style>
   
</head>

<body>
    <?php
    include ('../layout_employee/header.php');
    ?>
    <?php
    include ('../layout_employee/sidebar.php');
    ?>
    <?php
    //include ('../layout_employee/main.php');
    ?>
    <div class="col-sm-12 col-sm-offset-3 col-lg-12 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="index.php"><svg class="glyph stroked home">
                            <use xlink:href="#stroked-home"></use>
                        </svg></a></li>
                <li class="active">Thông tin nhân viên</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-sm-1"></div>
            <div class="col-lg-8 bg-secondary" style="background-color: white; min-height:700px;"><?php
                    $a->infoUser($user_id);
                ?></div>
            <div class="col-sm-1"></div>

        </div>

        <script src="../../assets/employee/js/jquery-1.11.1.min.js"></script>
        <script src="../../assets/employee/js/bootstrap.min.js"></script>
        <script src="../../assets/employee/js/chart.min.js"></script>
        <script src="../../assets/employee/js/chart-data.js"></script>
        <script src="../../assets/employee/js/easypiechart.js"></script>
        <script src="../../assets/employee/js/easypiechart-data.js"></script>
        <script src="../../assets/employee/js/bootstrap-datepicker.js"></script>
        <script>
        $('#calendar').datepicker({});

        ! function($) {
            $(document).on("click", "ul.nav li.parent > a > span.icon", function() {
                $(this).find('em:first').toggleClass("glyphicon-minus");
            });
            $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
        }(window.jQuery);

        $(window).on('resize', function() {
            if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
        })
        $(window).on('resize', function() {
            if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
        })
        </script>
</body>

</html>