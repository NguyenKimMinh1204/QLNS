<?php
include '../layout_employee/auth.php';
include('../../controller/class/class_e_kpi.php');
$user_id = $_SESSION['user_id'];

$a = new ekpi();
//$emp_kpi_id=13; $dept_kpi_id=14;$result=6; $mota='mô tả mới nhất';
// $a->capnhat_tiendo($emp_kpi_id,$dept_kpi_id,$result, $mota);
// if($a->them_kpi_progress_log($emp_kpi_id, $result,  $mota )==1){
//     echo 'thanhcong';
// }
// else echo 'thatbai';
// if($a->cap_nhat_employee_kpi_progress($emp_kpi_id, $result)==1){
//     echo 'thanhcong';
// }
// else echo 'thatbai';
// if($a->them_kpi_result($emp_kpi_id, $result, $mota)==1){
//     echo 'thanhcong';
// }
// else echo 'thatbai';
// if($a->cap_nhat_department_kpi_progress($dept_kpi_id, $result)==1){
//     echo 'thanhcong';
// }
// else echo 'thatbai';

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
    <title>Danh sách KPI</title>

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

    .my-2 {
        margin: 5px;
    }

    .mt-5 {
        margin-left: 20px;
        margin-top: 10px;
        margin-bottom: 10px;
    }
    </style>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
    </script>
    <script>
    $(document).ready(function() {
        // Initialize DataTable on the desired table
        $('.table').DataTable();
    });
    </script>
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
                        <div class="col-md-12">
                            <!-- Form for filtering by month and year -->


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
            url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_r_kpi.php',
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
                } else if (response == 2) {
                    alert('Thiếu thông tin cần thiết!');
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
    // Hàm xử lý khi xóa KPI progress log
    // function deleteProgressLog(logId) {
    //     if (confirm('Bạn có chắc chắn muốn xóa bản ghi tiến độ KPI này?')) {
    //         // Gửi Ajax request để xóa
    //         $.ajax({
    //             url: 'http://localhost/KLTN/controller/class/controller_r_kpi.php',
    //             method: 'POST',
    //             data: {
    //                 action: 'delete_progress_log',
    //                 log_id: logId
    //             },
    //             success: function(response) {
    //                 console.log('Response:', response);
    //                 if (response == 1) {
    //                     alert('Xóa thành công!');
    //                     location.reload(); // Tải lại trang sau khi xóa
    //                 } else {
    //                     alert('Xóa thất bại!');
    //                 }
    //             },
    //             error: function(xhr, status, error) {
    //                 console.error('Error details:', {
    //                     xhr,
    //                     status,
    //                     error
    //                 });
    //                 alert('Có lỗi xảy ra: ' + error);
    //             }
    //         });
    //     }
    // }

    // Gọi hàm xóa khi nhấn nút xóa
    // Giả sử bạn có nút xóa trong mỗi dòng dữ liệu, ví dụ:
    // <button class="btn btn-danger" onclick="deleteProgressLog(logId)">Xóa</button>
    </script>

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

                // Kiểm tra nu c dữ liệu và lặp qua để thêm vào bảng
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

    <script>
    $(document).ready(function() {
        // When the "Select All" checkbox is changed
        $('#selectAll').change(function() {
            // Check or uncheck all checkboxes based on the "Select All" checkbox state
            $('.select-item').prop('checked', $(this).prop('checked'));
        });

        // When any individual checkbox is changed
        $('.select-item').change(function() {
            // If all checkboxes are checked, check the "Select All" checkbox
            // Otherwise, uncheck the "Select All" checkbox
            $('#selectAll').prop('checked', $('.select-item:checked').length == $('.select-item')
                .length);
        });
    });
    </script>

    <!-- View IT Task Progress Modal -->
    <div class="modal fade" id="viewITTaskProgressModal" tabindex="-1" role="dialog"
        aria-labelledby="viewITTaskProgressModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewITTaskProgressModalLabel">Tiến độ công việc IT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody id="itTaskProgressTableBody">
                            <!-- Dữ liệu sẽ được tải qua AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        // Populate the view modal with data
        $('#viewITTaskProgressModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var empKpiId = button.data('emp-kpi-id');

            $.ajax({
                url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_r_kpi.php',
                method: 'POST',
                data: {
                    action: 'get_it_task_progress_logs',
                    emp_kpi_id: empKpiId
                },
                success: function(response) {
                    var progressLogs = JSON.parse(response);
                    var tableBody = $('#itTaskProgressTableBody');
                    tableBody.empty();

                    if (progressLogs.length > 0) {
                        progressLogs.forEach(function(log) {
                            var statusText = '';
                            switch (log.status) {
                                case 'pending':
                                    statusText = 'Đang chờ';
                                    break;
                                case 'approved':
                                    statusText = 'Đã duyệt';
                                    break;
                                case 'rejected':
                                    statusText = 'Từ chối';
                                    break;
                            }

                            var row = '<tr>' +
                                '<td>' + log.updated_at + '</td>' +
                                '<td>' + statusText + '</td>' +
                                '</tr>';
                            tableBody.append(row);
                        });
                    } else {
                        tableBody.append(
                            '<tr><td colspan="2" class="text-center">Không có dữ liệu</td></tr>'
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

    <!-- Update IT Task Progress Modal -->
    <div class="modal fade" id="updateITTaskProgressModal" tabindex="-1" role="dialog"
        aria-labelledby="updateITTaskProgressModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateITTaskProgressModalLabel">Cập nhật tiến độ công việc IT</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateITTaskForm">
                        <input type="hidden" name="emp_kpi_id1" id="update-emp-kpi-id1">
                        <input type="hidden" class="form-control" id="progress-update1" name="progress_update1"
                            required>
                        <div class="form-group">
                            <label for="progress-update">Cập nhật tiến độ</label>
                            <input type="number" class="form-control" id="progress-update12" name="progress_update12"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="progress-description">Mô tả</label>
                            <textarea class="form-control" id="progress-description" name="progress_description"
                                rows="3" required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-primary" id="submitProgressButton">Lưu thay đổi</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    // $('#updateITTaskProgressModal').on('show.bs.modal', function(event) {
    //     console.log("Modal is being shown!");
    // });

    $(document).ready(function() {
        // Ensure the page is fully loaded
        console.log("Page is ready!");

        // Bind the event to the modal
        $('#updateITTaskProgressModal').on('show.bs.modal', function(event) {
            console.log("Modal is being shown!");

            // Retrieve the button that triggered the modal
            var button = $(event.relatedTarget);

            // Extract data attributes from the button
            var taskId = button.data('emp-kpi-id-it');
            var taskValue = button.data('assigned-value-it');

            // console.log("Task ID:", taskId);
            // console.log("Task Value:", taskValue);

            // Set the data in the modal
            var modal = $(this);
            modal.find('#update-emp-kpi-id1').val(taskId);
            modal.find('#progress-update1').val(taskValue);
        });
    });
    // $(document).ready(function() {
    //     console.log("Hello:");
    //     // Lấy button đã click
    //     var button = $(event.relatedTarget);
    //     // Lấy dữ liệu từ data-attributes
    //     var taskId = button.data('emp-kpi-id1'); // Lấy emp_kpi_id1
    //     var taskValue = button.data('assigned-value1'); // Lấy assigned_value1

    //     console.log("Task ID:", taskId);
    //     console.log("Task Value:", taskValue);

    //     var modal = $(this);
    //     modal.find('#update-emp-kpi-id1').val(taskId);
    //     modal.find('#progress-update1').val(taskValue); // Set progress to task value
    // });
    </script>
    <script>
    $(document).ready(function() {
        // Bind the submit function to the button
        $('#submitProgressButton').on('click', function() {
            // Retrieve existing values
            var empKpiId = $('#update-emp-kpi-id1').val(); // Task ID
            var assignedValue = $('#progress-update1').val(); // Task Value

            // Retrieve new input values
            var progressUpdate = $('#progress-update12').val(); // New progress update
            var progressDescription = $('#progress-description').val(); // New progress description

            // Validate inputs
            if (!progressUpdate || !progressDescription) {
                alert('Vui lòng điền đầy đủ thông tin');
                return;
            }



            // Send Ajax request
            $.ajax({
                url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_r_kpi.php',
                method: 'POST',
                data: {
                    action: 'update_it_task_progress',
                    emp_kpi_id: empKpiId,
                    assigned_value: assignedValue, // Include the assigned value
                    progress_update: progressUpdate,
                    progress_description: progressDescription
                },
                success: function(response) {
                    console.log('Response:', response);
                    if (response == 1) {
                        alert('Cập nhật tiến độ thành công!');
                        location.reload();
                    } else {
                        alert('Cập nhật tiến độ thất bại!');
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
                    // Re-enable the button
                    $('#submitProgressButton').prop('disabled', false);
                }
            });
        });
    });
    </script>

</body>

</html>