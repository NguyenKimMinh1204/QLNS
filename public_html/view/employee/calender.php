<?php
include '../layout_employee/auth.php';
include('../../controller/class/class_calender.php');

$a = new class_calender();
$user_id = $_SESSION['user_id'];

// Handle AJAX request to filter attendance by month and year
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['month']) && isset($_POST['year'])) {
    $month = $_POST['month'];
    $year = $_POST['year'];
    
    // Load both attendance days and data
    $a->loadAttendanceDays($user_id, $month, $year);
    $a->loadAttendanceData1($user_id, $month, $year);
    exit;
}

// For the initial page load, use current month and year if month and year are not provided
$month = date('n'); // Current month
$year = date('Y'); // Current year
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dữ liệu chấm công</title>

    <!-- Stylesheets -->
    <link href="../../assets/employee/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/employee/css/datepicker3.css" rel="stylesheet">
    <link href="../../assets/employee/css/styles.css" rel="stylesheet">
    <link href="../../assets/css/employee/calender.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <script src="../../assets/employee/js/lumino.glyphs.js"></script>
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
                <li class="active">Dữ liệu chấm công</li>
            </ol>
        </div>

        <div class="row">
            <div class="filter-container" style="display: flex; align-items: center; gap: 10px;">
                <label for="month">Tháng:</label>
                <select id="month" name="month" style="padding: 5px;">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                    <option value="<?= $m ?>" <?= $m == $month ? 'selected' : '' ?>>
                        <?= str_pad($m, 2, '0', STR_PAD_LEFT) ?></option>
                    <?php endfor; ?>
                </select>

                <label for="year">Năm:</label>
                <select id="year" name="year" style="padding: 5px;">
                    <?php for ($y = date("Y"); $y >= 2000; $y--): ?>
                    <option value="<?= $y ?>" <?= $y == $year ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>

                <button onclick="filterAttendance()"
                    style="padding: 8px 15px; cursor: pointer; background-color: #007bff; color: #fff; border: none; border-radius: 4px;">
                    Lọc
                </button>
            </div>

            <div id="attendance-data">
                <?php
                    $a->loadAttendanceDays($user_id, $month, $year);
                   
                    // Load initial attendance data for the current month and year
                    $a->loadAttendanceData1($user_id, $month, $year);
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
    function filterAttendance() {
        var month = document.getElementById('month').value;
        var year = document.getElementById('year').value;

        $.ajax({
            url: 'calender.php',
            type: 'POST',
            data: {
                month: month,
                year: year
            },
            success: function(response) {
                $('#attendance-data').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }
    </script>
</body>

</html>