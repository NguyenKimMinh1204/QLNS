<?php
include '../layout_manager/auth.php';
include '../../controller/class/class_kpi_m.php';

$a = new kpi_m();
$user_id = $_SESSION['user_id'];
$department_id = $a->getDepartmentByUserId($user_id);
$employees = $a->getUserBydepartment($department_id); // Fetch employees for the department

// Retrieve filter values from POST or set defaults
$selectedUserId = $_POST['user_id'] ?? '';
$month = $_POST['month'] ?? '';
$year = $_POST['year'] ?? date('Y'); // Default to current year if not set

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách KPI nhân viên</title>

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

    .mt-5 {
        margin: 0px 5px 10px 5px;
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
                <h1 class="page-header">Danh sách KPI nhân viên</h1>
            </div>
        </div>
        <!--/.row-->

        <div class="row">
            <div class="col-lg-12">
                <form id="filterForm" method="POST" action="" class="form-inline">
                    <div class="form-group">
                        <label for="user_id">Nhân viên:</label>
                        <select name="user_id" id="user_id" class="form-control">
                            <option value="">Tất cả nhân viên</option>
                            <?php foreach ($employees as $employee): ?>
                            <option value="<?php echo $employee['id']; ?>"
                                <?php echo ($employee['id'] == $selectedUserId) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($employee['full_name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="month">Tháng:</label>
                        <select name="month" id="month" class="form-control">
                            <option value="">Chọn tháng</option>
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo $m; ?>" <?php echo ($m == $month) ? 'selected' : ''; ?>>
                                <?php echo $m; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year">Năm:</label>
                        <select name="year" id="year" class="form-control">
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
            <div class="col-lg-12">
                <?php
                // Check if month, year, and user_id are set in POST request
                if (isset($_POST['month']) || isset($_POST['year']) || isset($_POST['user_id'])) {
                    $month = $_POST['month'] ?? null;
                    $year = $_POST['year'] ?? null;
                    $user_id = $_POST['user_id'] ?? null;

                    // Call the filter action
                    $action = 'filter';
                    $a->loadKPIByEmployeeAndTime($department_id, $user_id, $month, $year);
                } else {
                    // Load all KPIs if no filter is applied
                    $a->loadeallkpi($department_id);
                }
                ?>
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

    <!-- Edit KPI Modal -->
    <div class="modal fade" id="Suakpi" tabindex="-1" role="dialog" aria-labelledby="editKPIPersonalModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editKPIPersonalModalLabel">Sửa KPI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editKPIForm">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="emp_kpi_id" id="edit-kpi-id">
                        <div class="form-group">
                            <label for="assigned_value">Giá trị được giao</label>
                            <input type="number" class="form-control" id="assigned_value" name="assigned_value"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="due_date">Ngày hết hạn</label>
                            <input type="datetime-local" class="form-control" id="due_date" name="due_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Progress Modal -->
    <div class="modal fade" id="selectProgressModal" tabindex="-1" role="dialog"
        aria-labelledby="viewProgressModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewProgressModalLabel">Tiến độ KPI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Giá trị tiến độ</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody id="progressTableBody">
                            <!-- Dữ liệu sẽ được tải qua AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Handle the form submission for updating KPI
        $('#editKPIForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission
            $.ajax({
                type: 'POST',
                url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_kpi_m.php',
                data: $(this).serialize(), // Serialize the form data
                success: function(response) {
                    if (response == 1) {
                        alert('Cập nhật KPI thành công!');
                        location.reload(); // Reload the page to see the changes
                    } else {
                        alert('Cập nhật KPI thất bại!');
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra. Vui lòng thử lại.');
                }
            });
        });

        // Handle the click event for the edit button

        $(document).on('click', '.edit-btn', function() {
            var empKpiId = $(this).data('emp-kpi-id');
            var assignedValue = $(this).data('assigned-value');
            var dueDate = $(this).data('due-date'); // This should be in the correct format

            // Format the due date to 'YYYY-MM-DDTHH:MM'
            var formattedDueDate = new Date(dueDate).toISOString().slice(0,
                16); // Convert to ISO format and trim to 'YYYY-MM-DDTHH:MM'

            $('#edit-kpi-id').val(empKpiId);
            $('#assigned_value').val(assignedValue);
            $('#due_date').val(formattedDueDate); // Set the formatted date
        });

        // Handle the click event for the delete button
        $(document).on('click', '.delete-btn', function() {
            var empKpiId = $(this).data('empkpiid');

            if (confirm('Bạn có chắc chắn muốn xóa KPI này không?')) {
                $.ajax({
                    type: 'POST',
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_kpi_m.php',
                    data: {
                        action: 'delete',
                        emp_kpi_id: empKpiId
                    },
                    success: function(response) {
                        if (response ===
                            'Cannot delete KPI: Progress is greater than 0 or KPI does not exist.'
                        ) {
                            alert(response); // Show the error message
                        } else if (response == 1) {
                            alert('Xóa KPI thành công!');
                            location.reload(); // Reload the page to see the changes
                        } else {
                            alert('Xóa KPI thất bại!');
                        }
                    },
                    error: function() {
                        alert('Có lỗi xảy ra trong quá trình xử lý.');
                    }
                });
            }
        });

        // When opening the progress modal
        $('#selectProgressModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Get the button that triggered the modal
            var empKpiId = button.data('empkpiid'); // Extract info from data-* attributes

            // Call AJAX to get KPI progress
            $.ajax({
                url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_kpi_m.php',
                method: 'POST',
                data: {
                    action: 'get_progress_logs',
                    emp_kpi_id: empKpiId
                },
                success: function(response) {
                    var progressLogs = JSON.parse(
                        response); // Convert JSON response to object
                    var tbody = $('#progressTableBody');
                    tbody.empty(); // Clear old data

                    if (progressLogs.length > 0) {
                        progressLogs.forEach(function(log) {
                            tbody.append('<tr>' +
                                '<td>' + log.updated_at + '</td>' +
                                '<td>' + log.progress_update + '</td>' +
                                '<td>' + log.result_detail + '</td>' +
                                '</tr>');
                        });
                    } else {
                        tbody.append(
                            '<tr><td colspan="3" class="text-center">Không có dữ liệu</td></tr>'
                        );
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra trong quá trình xử lý.');
                }
            });
        });
    });
    </script>
</body>

</html>