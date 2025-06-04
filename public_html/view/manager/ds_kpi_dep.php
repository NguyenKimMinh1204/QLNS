<?php
include '../layout_manager/auth.php';
include '../../controller/class/class_kpi_m.php';

$a = new kpi_m();
$user_id = $_SESSION['user_id'];
$department_id = $a->getDepartmentByUserId($user_id);

// Retrieve filter values from POST or set defaults
$month = $_POST['month'] ?? '';
$year = $_POST['year'] ?? date('Y'); // Default to current year if not set

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách KPI phòng</title>

    <link href="../../assets/employee/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/employee/css/datepicker3.css" rel="stylesheet">
    <link href="../../assets/employee/css/styles.css" rel="stylesheet">

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
    include ('../layout_manager/header.php');
    ?>
    <?php
    include ('../layout_manager/sidebar.php');
    ?>
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="index.php"><svg class="glyph stroked home">
                            <use xlink:href="#stroked-home"></use>
                        </svg></a></li>

                <li class="active"><a href="ds_kpi_dep.php">Danh sách KPI phòng ban</a></li>
            </ol>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Danh sách KPI phòng ban</h1>
            </div>
        </div>
        <!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <form method="POST" action="ds_kpi_dep.php" class="form-inline">
                    <div class="form-group">
                        <label for="month">Tháng:</label>
                        <select name="month" id="month" class="form-control" required>
                            <option value="">Chọn tháng</option>
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo $m; ?>" <?php echo ($m == $month) ? 'selected' : ''; ?>>
                                <?php echo $m; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year">Năm:</label>
                        <select name="year" id="year" class="form-control" required>
                            <option value="">Chọn năm</option>
                            <?php
                            $currentYear = date("Y");
                            for ($y = $currentYear - 5; $y <= $currentYear; $y++): ?>
                            <option value="<?php echo $y; ?>" <?php echo ($y == $year) ? 'selected' : ''; ?>>
                                <?php echo $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Lọc KPI</button>
                </form>
            </div>
        </div>

        <div class="row">
            <?php
            // Check if month and year are set in POST request
            if (isset($_POST['month']) && isset($_POST['year'])) {
                $month = $_POST['month'];
                $year = $_POST['year'];
                $a->loadKPIsByDepartmentbytime($department_id, $month, $year);
            } else {
                // Load all KPIs if no filter is applied
                $a->loadKPIsByDepartment($department_id);
            }
            ?>
        </div>

        <!--/.row-->
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