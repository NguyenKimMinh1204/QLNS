<?php
include '../layout_manager/auth.php';
include '../../controller/class/class_duyetkpi.php';
$a = new duyetkpim();
$user_id = $_SESSION['user_id'];
$department_id = $a->getDepartmentIdByUserId($user_id);

// Get current month and year
$currentMonth = date("n"); // Current month (1-12)
$currentYear = date("Y"); // Current year

// Check if month and year are set in POST, otherwise use current month and year
$month = isset($_POST['month']) ? $_POST['month'] : $currentMonth;
$year = isset($_POST['year']) ? $_POST['year'] : $currentYear;
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Duyệt KPI</title>

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
                <li><a href="#"><svg class="glyph stroked home">
                            <use xlink:href="#stroked-home"></use>
                        </svg></a></li>
                <li class="active">Duyệt KPI</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-11">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Danh Sách KPI</h3>

                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="POST" action="" class="form-inline">
                                <div class="form-group">
                                    <label for="month">Tháng:</label>
                                    <select name="month" id="month" class="form-control" required>

                                        <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?php echo $m; ?>"
                                            <?php echo ($m == $month) ? 'selected' : ''; ?>><?php echo $m; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="year">Năm:</label>
                                    <select name="year" id="year" class="form-control" required>

                                        <?php
                                        $currentYear = date("Y");
                                        for ($y = $currentYear - 5; $y <= $currentYear; $y++): ?>
                                        <option value="<?php echo $y; ?>"
                                            <?php echo ($y == $year) ? 'selected' : ''; ?>><?php echo $y; ?></option>
                                        <?php endfor; ?>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Lọc</button>
                            </form>
                        </div>
                    </div>
                    <div class="panel-body">
                        <table class="table table-striped">
                            <?php
                            // Call the exportKPIWithActions method to display the KPI data
                            $a->exportKPIWithActions($department_id, $month, $year);
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--/.row-->

        <!-- Modal for Reject Reason -->
        <div class="modal fade" id="rejectReasonModal" tabindex="-1" role="dialog"
            aria-labelledby="rejectReasonModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectReasonModalLabel">Lý Do Từ Chối</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="rejectRequestId" name="request_id" readonly>
                        <form id="rejectForm">
                            <div class="form-group">
                                <label for="rejectReason">Lý Do</label>
                                <textarea class="form-control" id="rejectReason" name="reason_reject"
                                    required></textarea>
                            </div>
                            <button type="submit" class="btn btn-xs btn-danger reject-btn">Gửi Lý Do</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="../../assets/employee/js/jquery-1.11.1.min.js"></script>
    <script src="../../assets/employee/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        // Handle approve button click
        $(document).on('click', 'button[name="approve"]', function() {
            var empKpiId = $(this).data('emp-kpi-id'); // Get employee KPI ID
            var progress = $(this).data('progress'); // Get progress from button
            var logId = $(this).data('log-id'); // Get log ID from button
            var deptKpiId = $(this).data('dep-kpi-id'); // Get department KPI ID

            // Debugging: Log values to console
            console.log("Employee KPI ID: ", empKpiId);
            console.log("Progress: ", progress);
            console.log("Log ID: ", logId);
            console.log("Department KPI ID: ", deptKpiId);

            var confirmation = confirm("Bạn có chắc chắn muốn duyệt KPI này không?"); // Confirm action

            if (confirmation) {
                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_duyetkpim.php',
                    method: 'POST',
                    data: {
                        action: 'duyet_employee_kpi_progress', // Approve action
                        emp_kpi_id: empKpiId,
                        progress: progress,
                        log_id: logId,
                        dept_kpi_id: deptKpiId
                    },
                    success: function(response) {
                        alert(response == 1 ? 'Duyệt thành công!' :
                            'Duyệt thất bại!'); // Show success or failure message
                        if (response == 1) {
                            location.reload(); // Reload page on success
                        }
                    },
                    error: function() {
                        alert('Đã xảy ra lỗi!'); // Show error message
                    }
                });
            } else {
                alert("Đã hủy hành động duyệt KPI."); // Cancel action message
            }
        });
    });
    </script>
    <script>
    $(document).ready(function() {
        // Handle reject button click
        $(document).on('click', 'button[name="reject"]', function() {
            var logId = $(this).data('log-id'); // Get log ID from button
            $('#rejectRequestId').val(logId); // Set log ID in modal
            $('#rejectReasonModal').modal('show'); // Show modal
        });

        // Handle reject form submission
        $('#rejectForm').on('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
            var logId = $('#rejectRequestId').val();
            var reasonReject = $('#rejectReason').val();

            $.ajax({
                url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_duyetkpim.php',
                method: 'POST',
                data: {
                    action: 'tu_choi_employee_kpi_progress', // Reject action
                    log_id: logId,
                    reason_reject: reasonReject // Send reject reason
                },
                success: function(response) {
                    alert(response == 1 ? 'Từ chối thành công!' :
                        'Từ chối thất bại!'); // Show success or failure message
                    if (response == 1) {
                        location.reload(); // Reload page on success
                    }
                },
                error: function() {
                    alert('Đã xảy ra lỗi!'); // Show error message
                }
            });
        });
    });
    </script>
</body>

</html>