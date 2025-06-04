<?php
include '../layout_manager/auth.php';
include '../../controller/class/class_evaluetion_m.php';

$a = new evaluetion_manager();
$user_id = $_SESSION['user_id'];
$department_id = $a->getDepartmentByUserId($user_id);
$employees = $a->getUserBydepartment($department_id);

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
    <title>Đánh giá KPI</title>

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
        margin: 0px 10px 10px 10px;
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



                <!-- Thêm đánh giá -->
                <div class="modal fade" id="evaluationModal1" tabindex="-1" role="dialog"
                    aria-labelledby="evaluationModalLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="evaluationModalLabel">Đánh giá KPI</h4>
                            </div>
                            <div class="modal-body">
                                <form id="evaluationForm">
                                    <input type="text" id="emp_kpi_id" name="emp_kpi_id">
                                    <input type="text" id="user_id" name="user_id">
                                    <div class="form-group">
                                        <label for="evaluation_comments" class="form-label">Nội dung đánh giá</label>
                                        <textarea class="form-control" id="evaluation_comments" name="comments" rows="3"
                                            required></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-success" id="saveEvaluation">Lưu</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- sửa đánh giá -->
                <div class="modal fade" id="editEvaluationModal1" tabindex="-1" role="dialog"
                    aria-labelledby="editEvaluationModalLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="editEvaluationModalLabel">Sửa đánh giá KPI</h4>
                            </div>
                            <div class="modal-body">
                                <form id="editEvaluationForm">
                                    <input type="hidden" id="edit_emp_kpi_id" name="emp_kpi_id">
                                    <input type="text" id="edit_user_id" name="edit_user_id">
                                    <!-- Hiển thị đánh giá hiện tại -->
                                    <div class="form-group">
                                        <label for="current_evaluation_comments" class="form-label">Đánh giá hiện
                                            tại</label>
                                        <textarea class="form-control" id="current_evaluation_comments"
                                            name="current_comments" rows="3" disabled></textarea>
                                    </div>

                                    <!-- Chỉnh sửa đánh giá -->
                                    <div class="form-group">
                                        <label for="new_evaluation_comments" class="form-label">Chỉnh sửa đánh
                                            giá</label>
                                        <textarea class="form-control" id="new_evaluation_comments" name="new_comments"
                                            rows="3" required></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                <button type="button" class="btn btn-success" id="updateEvaluation">Cập nhật</button>
                                <button type="button" class="btn btn-danger" id="deleteEvaluation">Xóa</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                $('#evaluationModal1').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('emp-kpi-id'); // Lấy ID KPI nhân viên
                    var user_id = button.data('user-id'); // Lấy ID người dùng

                    var modal = $(this);
                    modal.find('#emp_kpi_id').val(id); // Đặt giá trị vào input hidden
                    modal.find('#user_id').val(user_id); // Đặt giá trị vào input hidden
                });
                $(document).on('click', '#saveEvaluation', function() {
                    var deptKpiId = $('#dept_kpi_id').val(); // Lấy ID KPI của phòng ban
                    var comments = $('#evaluation_comments').val(); // Lấy nội dung đánh giá
                    var user_id = <?php echo $user_id ?>;
                    // Kiểm tra dữ liệu trước khi gửi
                    if (comments.trim() === '') {
                        alert('Nội dung đánh giá không được để trống.');
                        return;
                    }

                    // Gửi AJAX $user_id = $_POST['user_id'];
                    // $dep_kpi_id = $_POST['dep_kpi_id'];
                    // $comments = $_POST['comments'];

                    $.ajax({
                        url: https://www.quanlynhansuads.io.vn/controller/class/controller_evaluetion_admin.php', // Thay đường dẫn phù hợp
                        method: 'POST',
                        data: {
                            action: 'add',
                            user_id: user_id,
                            dep_kpi_id: deptKpiId,
                            comments: comments
                        },
                        success: function(response) {
                            if (response == 1) {
                                alert('Đánh giá đã được thêm thành công!');
                                location.reload(); // Tải lại trang để cập nhật giao diện
                            } else {
                                alert('Lỗi khi thêm đánh giá.');
                            }
                        },
                        error: function() {
                            alert('Đã xảy ra lỗi trong quá trình gửi yêu cầu.');
                        }
                    });
                });
                </script>
                <script>
                $('#editEvaluationModal1').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('emp_kpi_id');
                    var commment = button.data('commment');
                    var manager_id = <?php $user_id?>;
                    var user_id = button.data('user_id');

                    var modal = $(this);
                    modal.find('#edit_emp_kpi_id').val(id);
                    modal.find('#edit_user_id').val(user_id);
                    modal.find('#current_evaluation_comments').val(commment);

                });

                // Xử lý lưu thay đổi khi nhấn nút "Save changes"
                $(document).on('click', '#updateEvaluation', function() {
                    var id = $('#emp_kpi_id').val(); // Lấy id đánh giá
                    var newComments = $('#new_evaluation_comments').val(); // Lấy nội dung chỉnh sửa đánh giá
                    var manager_id = <?php echo $user_id ?>;
                    var user_id = $('#edit_user_id').val();

                    // Kiểm tra dữ liệu trước khi gửi
                    if (newComments.trim() === '') {
                        alert('Nội dung đánh giá không được để trống.');
                        return;
                    }

                    // Gửi AJAX
                    //$id = $_POST['id'];
                    // $user_id = $_POST['user_id'];
                    // $comments = $_POST['comments'];
                    $.ajax({
                        url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_evaluetion_admin.php', // Thay đường dẫn phù hợp
                        method: 'POST',
                        data: {
                            action: 'update',
                            id: id,
                            user_id: user_id,
                            comments: newComments,
                        },
                        success: function(response) {
                            if (response == 1) {
                                alert('Đánh giá đã được cập nhật thành công!');
                                location.reload(); // Tải lại trang để cập nhật giao diện
                            } else {
                                alert('Lỗi khi cập nhật đánh giá.');
                            }
                        },
                        error: function() {
                            alert('Đã xảy ra lỗi trong quá trình gửi yêu cầu.');
                        }
                    });
                });
                </script>
                <script>

                </script>
                <script>
                $(document).on('click', '#deleteEvaluation', function() {
                    var id = $('#edit_dept_kpi_id').val(); // Lấy ID của đánh giá từ modal

                    // Xác nhận trước khi xóa
                    if (confirm('Bạn có chắc chắn muốn xóa đánh giá này không?')) {
                        // Gửi AJAX
                        $.ajax({
                            url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_evaluetion_admin.php', // Thay đường dẫn phù hợp
                            method: 'POST',
                            data: {
                                action: 'delete',
                                id: id
                            },
                            success: function(response) {
                                if (response == 1) {
                                    alert('Đánh giá đã được xóa thành công!');
                                    location.reload(); // Tải lại trang để cập nhật giao diện
                                } else {
                                    alert('Lỗi khi xóa đánh giá.');
                                }
                            },
                            error: function() {
                                alert('Đã xảy ra lỗi trong quá trình gửi yêu cầu.');
                            }
                        });
                    }
                });
                </script>

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
    <!-- Thêm đánh giá -->
    <div class="modal fade" id="evaluationModal" tabindex="-1" role="dialog" aria-labelledby="evaluationModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="evaluationModalLabel">Đánh giá KPI</h4>
                </div>
                <div class="modal-body">
                    <form id="evaluationForm">
                        <input type="hidden" id="dept_kpi_id" name="dept_kpi_id">
                        <div class="form-group">
                            <label for="evaluation_comments" class="form-label">Nội dung đánh giá</label>
                            <textarea class="form-control" id="evaluation_comments" name="comments" rows="3"
                                required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="saveEvaluation">Lưu</button>
                </div>
            </div>
        </div>
    </div>
    <!-- sửa đánh giá -->
    <div class="modal fade" id="editEvaluationModal" tabindex="-1" role="dialog"
        aria-labelledby="editEvaluationModalLabel">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="editEvaluationModalLabel">Sửa đánh giá KPI</h4>
                </div>
                <div class="modal-body">
                    <form id="editEvaluationForm">
                        <input type="hidden" id="edit_dept_kpi_id" name="dept_kpi_id">

                        <!-- Hiển thị đánh giá hiện tại -->
                        <div class="form-group">
                            <label for="current_evaluation_comments" class="form-label">Đánh giá hiện
                                tại</label>
                            <textarea class="form-control" id="current_evaluation_comments" name="current_comments"
                                rows="3" disabled></textarea>
                        </div>

                        <!-- Chỉnh sửa đánh giá -->
                        <div class="form-group">
                            <label for="new_evaluation_comments" class="form-label">Chỉnh sửa đánh
                                giá</label>
                            <textarea class="form-control" id="new_evaluation_comments" name="new_comments" rows="3"
                                required></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" class="btn btn-success" id="updateEvaluation">Cập nhật</button>
                    <button type="button" class="btn btn-danger" id="deleteEvaluation">Xóa</button>
                </div>
            </div>
        </div>
    </div>



</body>

</html>