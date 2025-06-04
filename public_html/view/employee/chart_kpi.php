<?php
include '../layout_employee/auth.php';
include('../../controller/class/class_e_kpi.php');
$user_id = $_SESSION['user_id'];

$emp_kpi_id = $_REQUEST['emp_kpi_id'];
$a = new ekpi();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Biểu đồ</title>

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

    .chart-container {
        position: relative;
        margin: auto;
        height: 400px;
        /* Chiều cao của biểu đồ */
        width: 80%;
        /* Chiều rộng của biểu đồ */
        max-width: 800px;
        /* Chiều rộng tối đa */
        background-color: #f8f9fa;
        /* Màu nền cho biểu đồ */
        border-radius: 8px;
        /* Bo góc cho biểu đồ */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        /* Đổ bóng cho biểu đồ */
        padding: 20px;
        /* Khoảng cách bên trong */
    }

    h2 {
        text-align: center;
        /* Căn giữa tiêu đề */
        color: #343a40;
        /* Màu chữ tiêu đề */
        margin-bottom: 20px;
        /* Khoảng cách dưới tiêu đề */
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
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="index.php"><svg class="glyph stroked home">
                            <use xlink:href="#stroked-home"></use>
                        </svg></a></li>
                <li class="active"><a href="list_kpi.php">KPI</a></li>
                <li class="active">Biểu đồ</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="chart-container">
                    <h2>Biểu đồ tiến độ KPI</h2>
                    <canvas id="kpiChart" width="400" height="200"></canvas>
                </div>
                <?php
                    // Fetch KPI data for the chart
                        $kpiData = $a->get_kpi_progress_logs1($emp_kpi_id,$user_id); // Assuming this method returns the necessary data
                        $labels = [];
                        $progressValues = [];
                        $completionRates = []; // New array for completion rates
                        $cumulativeProgress = 0; // Initialize cumulative progress
                        foreach ($kpiData as $log) {
                            $labels[] = date('d-m', strtotime($log['updated_at'])); // Định dạng lại ngày tháng
                            $cumulativeProgress += $log['progress_update']; // Update cumulative progress
                            $progressValues[] = $log['progress_update']; // Progress value
                            $completionRates[] = ($log['assigned_value'] > 0) ? 
                                round(($cumulativeProgress / $log['assigned_value']) * 100, 2) : 0; // Calculate cumulative completion rate
                        }
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



    <!-- Add Chart.js library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>



    <script>
    const ctx = document.getElementById('kpiChart').getContext('2d');
    const kpiChart = new Chart(ctx, {
        type: 'line', // Thay đổi loại biểu đồ thành 'line'
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                    label: 'Giá trị thực tế',
                    data: <?php echo json_encode($progressValues); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: true, // Đổ màu bên dưới đường
                    tension: 0.3 // Độ cong của đường
                },
                {
                    label: 'Tỷ lệ hoàn thành (%)', // New dataset for completion rate
                    data: <?php echo json_encode($completionRates); ?>,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: false, // Do not fill under the line
                    tension: 0.3, // Độ cong của đường
                    yAxisID: 'y1' // Use the secondary y-axis
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Giá trị'
                    }
                },
                y1: { // Secondary y-axis for percentage
                    type: 'linear',
                    position: 'right',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Tỷ lệ hoàn thành (%)'
                    },
                    ticks: {
                        // Define the ticks for the percentage scale
                        callback: function(value) {
                            return value + '%'; // Append '%' to the tick labels
                        },
                        stepSize: 10, // Set step size for ticks
                        min: 0,
                        max: 100
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Ngày'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top', // Vị trí của legend
                },
                tooltip: {
                    mode: 'index', // Hiển thị tooltip cho tất cả các điểm trên cùng một trục
                    intersect: false // Không chỉ hiển thị tooltip khi hover đúng vào điểm
                }
            }
        }
    });
    </script>
</body>

</html>