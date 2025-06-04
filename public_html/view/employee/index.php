<?php
include '../layout_employee/auth.php';
include('../../controller/class/class_info.php');
$user_id = $_SESSION['user_id'];

$a = new info();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Trang chủ nhân viên</title>

    <link href="../../assets/employee/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/employee/css/datepicker3.css" rel="stylesheet">
    <link href="../../assets/employee/css/styles.css" rel="stylesheet">
    <link href="../../assets/css/employee/giaodien.css" rel="stylesheet">

    <!--Icons-->
    <script src="../../assets/employee/js/lumino.glyphs.js"></script>

</head>

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
.avatar img {
    width: 90%;
    height: 80%;
    border-radius: 10%;
}
.details{
    display: flex;
    flex-direction: column;
    justify-content: center;
}
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
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <div class="col-md-12">
                <?php
                    $a->loaddulieu_user($user_id);
                ?>
            </div>
        </div>
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