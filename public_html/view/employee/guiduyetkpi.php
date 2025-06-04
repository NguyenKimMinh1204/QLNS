<?php
include '../layout_employee/auth.php';
include('../../controller/class/class_guiduyet_kpi.php');
$user_id = $_SESSION['user_id'];

$a = new duyetkpi();

// Get current month and year if not set
$month = isset($_POST['month']) ? $_POST['month'] : date('m');
$year = isset($_POST['year']) ? $_POST['year'] : date('Y');

// Get department ID
$department_id = $a->getDepartmentIdByUserId($user_id);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gửi duyệt KPI</title>

    <link href="../../assets/employee/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/employee/css/datepicker3.css" rel="stylesheet">
    <link href="../../assets/employee/css/styles.css" rel="stylesheet">

    <!--Icons-->
    <script src="../../assets/employee/js/lumino.glyphs.js"></script>

    <style>
    .breadcrumb {
        border-radius: 0;
        padding: 27px 10px;
        background: #e9ecf2;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        margin: 0;
    }

    .my-2 {
        margin: 5px;
    }

    .mt-5 {
        margin-left: 20px;
        margin-top: 10px;
        margin-bottom: 10px;
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
                <li class="active">KPI</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <form method="POST" class="form-inline mb-3">
                    <div class="form-group">
                        <label for="month">Tháng:</label>
                        <select name="month" id="month" class="form-control">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo $m; ?>" <?php if ($m == $month) echo 'selected'; ?>>
                                <?php echo $m; ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year">Năm:</label>
                        <select name="year" id="year" class="form-control">
                            <?php for ($y = date('Y') - 5; $y <= date('Y'); $y++): ?>
                            <option value="<?php echo $y; ?>" <?php if ($y == $year) echo 'selected'; ?>>
                                <?php echo $y; ?>
                            </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Lọc</button>
                </form>
            </div>
        </div>
        <div class="row">
            <form id="itTasksForm">
                <table class="table table-bordered table-hover">
                    <div class="row">

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <?php
                            // Conditionally load tasks or KPIs based on department ID
                            if ($department_id == 5 || $department_id == 4) {
                                $a->loadITTasksByMonthYearUser($user_id, $month, $year);
                            } else {
                                $a->loadKPIByMonthYearUser($user_id, $month, $year);
                            }
                            ?>
                        </div>
                    </div>
                </table>
            </form>
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

    <!-- Modal Cập nhật tiến độ -->
    <div class="modal fade" id="updateProgressModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Cập nhật tiến độ KPI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateProgressForm">
                        <input type="hidden" name="emp_kpi_id" id="emp_kpi_id">
                        <input type="hidden" name="dept_kpi_id" id="dept_kpi_id">

                        <div class="form-group">
                            <label for="progress_value">Giá trị cập nhật</label>
                            <input type="number" class="form-control" id="progress_value" name="progress_value" min="0"
                                max="100" required>
                        </div>

                        <div class="form-group">
                            <label for="progress_description">Mô tả</label>
                            <textarea class="form-control" id="progress_description" name="progress_description"
                                rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" onclick="submitProgress()">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Xử lý khi modal được mở
    $('#updateProgressModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var empKpiId = button.data('emp-kpi-id');
        var deptKpiId = button.data('dept-kpi-id');
        var modal = $(this);
        modal.find('#emp_kpi_id').val(empKpiId);
        modal.find('#dept_kpi_id').val(deptKpiId);
    });

    // Hàm xử lý khi submit form
    function submitProgress() {
        var empKpiId = $('#emp_kpi_id').val();
        var deptKpiId = $('#dept_kpi_id').val();
        var progressValue = $('#progress_value').val();
        var description = $('#progress_description').val();

        // Validate inputs
        if (!progressValue || !description) {
            alert('Vui lòng điền đầy đủ thông tin');
            return;
        }

        // Hiển thị loading
        $('#updateProgressModal .modal-footer button').prop('disabled', true);

        // Gửi Ajax request
        $.ajax({
            url: 'http://localhost/KLTN/controller/class/controller_r_kpi.php',
            method: 'POST',
            data: {
                action: 'update_progress',
                emp_kpi_id: empKpiId,
                dept_kpi_id: deptKpiId,
                progress: progressValue,
                description: description
            },
            success: function(response) {
                console.log('Response:', response);
                if (response == 1) {
                    alert('Cập nhật thành công!');
                    location.reload();
                } else {
                    alert('Cập nhật thất bại!');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error details:', {
                    xhr,
                    status,
                    error
                });
                alert('Có lỗi xảy ra: ' + error);
            },
            complete: function() {
                // Bỏ trạng thái loading
                $('#updateProgressModal .modal-footer button').prop('disabled', false);
            }
        });
    }

    // Reset form khi modal đóng
    $('#updateProgressModal').on('hidden.bs.modal', function() {
        $('#updateProgressForm')[0].reset();
    });
    </script>

    <script>
    $('#selectProgressModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var empKpiId = button.data('emp-kpi-id');

        // Gửi Ajax request
        $.ajax({
            url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_r_kpi.php',
            method: 'POST',
            data: {
                action: 'get_single_progress',
                emp_kpi_id: empKpiId
            },
            success: function(response) {
                var data = JSON.parse(response);
                if (data) {
                    $('#modal_progress_update').text(data.progress_update || 'Không có dữ liệu');
                    $('#modal_result_detail').text(data.result_detail || 'Không có dữ liệu');
                    $('#modal_updated_at').text(data.updated_at || 'Không có dữ liệu');
                } else {
                    $('#modal_progress_update').text('Không có dữ liệu');
                    $('#modal_result_detail').text('Không có dữ liệu');
                    $('#modal_updated_at').text('Không có dữ liệu');
                }
            },
            error: function() {
                alert('Có lỗi xảy ra khi tải dữ liệu');
            }
        });
    });
    </script>

    <!-- Modal để xem tiến độ KPI -->
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
                                <th>Giá trị</th>
                                <th>Nội dung chuyển khoản</th>
                            </tr>
                        </thead>
                        <tbody id="progressTableBody">
                            <!-- Dữ liệu sẽ được thêm vào đây -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
    $('#selectProgressModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Lấy nút đã nhấn
        var empKpiId = button.data('emp-kpi-id'); // Lấy emp_kpi_id từ data-attribute
        var user_id =
            <?php echo $_SESSION['user_id']; ?>; // Lấy user_id từ session (hoặc có thể thay bằng giá trị khác)

        console.log(empKpiId);
        console.log(user_id);

        // Gửi yêu cầu AJAX để lấy dữ liệu tiến độ KPI
        $.ajax({
            url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_r_kpi.php', // Đường dẫn tới controller
            method: 'POST',
            data: {
                action: 'get_kpi_progress_logs', // Thực hiện hành động 'get_kpi_progress_logs'
                emp_kpi_id: empKpiId,
                user_id: user_id // Truyền emp_kpi_id và user_id vào
            },
            success: function(response) {
                // Chuyển đổi dữ liệu JSON từ PHP thành đối tượng JavaScript
                var progressLogs = JSON.parse(response);

                // Xử lý và hiển thị dữ liệu progressLogs vào bảng trong modal
                var tableBody = $('#progressTableBody'); // Chọn tbody trong modal

                // Xóa dữ liệu cũ trong bảng nếu có
                tableBody.empty();

                // Kiểm tra nếu có dữ liệu và lặp qua để thêm vào bảng
                if (progressLogs.length > 0) {
                    progressLogs.forEach(function(log) {
                        var row = '<tr>' +
                            '<td>' + log.updated_at + '</td>' +
                            '<td>' + log.progress_update + '</td>' +
                            '<td>' + log.result_detail + '</td>' +
                            '</tr>';
                        tableBody.append(row); // Thêm hàng vào bảng
                    });
                } else {
                    // Nếu không có dữ liệu, hiển thị thông báo
                    tableBody.append(
                        '<tr><td colspan="3" class="text-center">Không có dữ liệu tiến độ KPI.</td></tr>'
                    );
                }
            },
            error: function() {
                console.log("Có lỗi xảy ra khi lấy dữ liệu KPI progress.");
            }
        });
    });

    // Hàm xử lý xóa KPI progress log
    function deleteProgressLog(logId) {
        if (confirm('Bạn có chắc chắn muốn xóa bản ghi tiến độ KPI này?')) {
            // Gửi Ajax request để xóa
            $.ajax({
                url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_r_kpi.php',
                method: 'POST',
                data: {
                    action: 'delete_progress_log',
                    log_id: logId
                },
                success: function(response) {
                    console.log('Response:', response);
                    if (response == 1) {
                        alert('Xóa thành công!');
                        location.reload(); // Tải lại trang sau khi xóa
                    } else {
                        alert('Xóa thất bại!');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error details:', {
                        xhr,
                        status,
                        error
                    });
                    alert('Có lỗi xảy ra: ' + error);
                }
            });
        }
    }
    </script>
</body>

</html>