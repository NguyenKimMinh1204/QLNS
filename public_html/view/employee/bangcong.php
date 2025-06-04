<?php
include '../layout_employee/auth.php';
include('../../controller/class/class_bangcong_e.php');

$a = new bangcong();
$user_id = $_SESSION['user_id'];

// Xử lý AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $month = isset($_POST['month']) ? $_POST['month'] : date('n');
    $year = isset($_POST['year']) ? $_POST['year'] : date('Y');

    if (isset($_POST['action']) && $_POST['action'] === 'get_statistics') {
        // Nếu là request lấy thống kê
        // $a->getLeaveStatistics($user_id, $month, $year);
        exit;
    } else if (isset($_POST['action']) && $_POST['action'] === 'load_attendance') {
        // Xử lý yêu cầu AJAX để tải dữ liệu lịch
        $a->loadAttendanceData($user_id, $month, $year);
        exit; // Đảm bảo không tiếp tục xử lý
    } else {
        // Nếu là request lấy dữ liệu lịch
        
        exit;
    }
}

// For the initial page load
$month = date('n');
$year = date('Y');
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bảng Công</title>

    <!-- Stylesheets -->
    <link href="../../assets/employee/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/employee/css/datepicker3.css" rel="stylesheet">
    <link href="../../assets/employee/css/styles.css" rel="stylesheet">
    <link href="../../assets/css/employee/calender.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <script src="../../assets/employee/js/lumino.glyphs.js"></script>
    <link rel="stylesheet" href="../../assets/css/employee/bangcong.css">
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
    <?php include('../layout_employee/header.php'); ?>
    <?php include('../layout_employee/sidebar.php'); ?>

    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="index.php"><svg class="glyph stroked home">
                            <use xlink:href="#stroked-home"></use>
                        </svg></a></li>
                <li class="active">Bảng Công</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Thông tin nghỉ phép</div>
                    <div class="panel-body">
                        <div class="filter-container"
                            style="display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
                            <label for="month">Tháng:</label>
                            <select id="month" name="month" style="padding: 5px;">
                                <?php for ($m = 1; $m <= 12; $m++): ?>
                                <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>>
                                    <?= str_pad($m, 2, '0', STR_PAD_LEFT) ?></option>
                                <?php endfor; ?>
                            </select>

                            <label for="year">Năm:</label>
                            <select id="year" name="year" style="padding: 5px;">
                                <?php for ($y = date("Y"); $y >= 2020; $y--): ?>
                                <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>

                            <button onclick="filterLeave()" class="btn btn-primary">
                                Lọc
                            </button>
                        </div>
                        <div id="leave-calendar" class="row">
                            <div class="col-sm-12">
                                <?php
                                $a->loadAttendanceData($user_id, $month , $year);
                              
                               ?>
                            </div>

                        </div>
                    </div>
                </div>
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
    function filterLeave() {
        var month = document.getElementById('month').value;
        var year = document.getElementById('year').value;

        // Cập nhật lịch nghỉ phép
        $.ajax({
            url: 'bangcong.php', // URL hiện tại
            type: 'POST',
            data: {
                month: month,
                year: year,
                action: 'load_attendance' // Thêm action để phân biệt
            },
            success: function(response) {
                $('#leave-calendar').html(response);
                // Cập nhật thống kê
                updateLeaveStatistics(month, year);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    function updateLeaveStatistics(month, year) {
        $.ajax({
            url: 'bangcong.php', // Sử dụng cùng file
            type: 'POST',
            data: {
                action: 'get_statistics', // Thêm action để phân biệt
                month: month,
                year: year
            },
            success: function(response) {
                $('.leave-summary').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
    </script>
</body>

</html>