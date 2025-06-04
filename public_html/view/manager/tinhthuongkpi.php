<?php
include '../layout_manager/auth.php';
include '../../controller/class/class_thuong_kpi_m.php';

$a = new thuong_kpi_m();
$user_id = $_SESSION['user_id'];
$department_id = $a->getDepartmentIdByUserId($user_id);

// Retrieve filter values from POST or set defaults
$month = $_POST['month'] ?? date('m'); // Default to current month if not set
$year = $_POST['year'] ?? date('Y');   // Default to current year if not set

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tính thưởng KPI</title>

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

                <li class="active"><a href="tinhthuongkpi.php">Tính Thưởng KPI</a></li>
            </ol>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Tính Thưởng KPI</h1>
            </div>
        </div>
        <!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <form method="POST" action="tinhthuongkpi.php" class="form-inline">
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
           
                
                $a->loadKPIByMonthYearUser($department_id, $month, $year);
          
            ?>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
        $(document).ready(function() {
            console.log('Document is ready!'); // Kiểm tra xem mã JavaScript đã được tải đúng chưa

            // Handle the "Tính Thưởng" button click event
            $(document).on('click', '.calculate-bonus-btn', function() {
                console.log('Button clicked'); // Kiểm tra xem có vào đây không

                var empKpiId = $(this).data('id');
                var bonusAmount = $(this).data('bonus');

                // Populate the modal with bonus details
                $('#bonusAmount').text(bonusAmount);
                $('#empKpiId').val(empKpiId);

                console.log('Employee KPI ID:', empKpiId);
                console.log('Bonus Amount:', bonusAmount);

                // Show the modal
                $('#bonusModal').modal('show');
            });

            // Handle the "Xác nhận" button click in the modal
            $('#confirmBonus').on('click', function() {
                console.log('Confirm button clicked'); // Kiểm tra xem có vào đây không

                var empKpiId = $('#empKpiId').val(); // Get value from hidden input
                var bonusAmount = $('#bonusAmount').text().replace(/,/g, ''); // Remove commas

                // Log the values to verify they are correct
                console.log('Employee KPI ID:', empKpiId);
                console.log('Bonus Amount:', bonusAmount);

                // Make an AJAX request to update the is_active status
                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_thuongkpi_emp.php', // Adjust the path as needed
                    method: 'POST',
                    data: {
                        action: 'confirm_bonus',
                        emp_kpi_id: empKpiId,
                        goalBasedIncentive: bonusAmount // Send the formatted bonus amount
                    },
                    success: function(response) {
                        if (response == 1) {
                            alert('Tính lương thành công!');
                            location.reload(); // Reload the page to see changes
                        } else {
                            alert('Tính lương thất bại');
                        }
                    }
                });
            });
        });
        </script>

        <!-- Bonus Modal -->
        <div class="modal fade" id="bonusModal" tabindex="-1" role="dialog" aria-labelledby="bonusModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bonusModalLabel">Bonus Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Bonus Amount: <span id="bonusAmount" name="bonusAmount"></span></p>
                        <input type="hidden" id="empKpiId">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Chưa Tính</button>
                        <button type="button" class="btn btn-primary" id="confirmBonus">Tính Thưởng</button>
                    </div>
                </div>
            </div>
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