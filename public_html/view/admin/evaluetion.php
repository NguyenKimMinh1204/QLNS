<?php
include '../layout/auth.php';

include '../../controller/class/class_evaluetion_admin.php';
$a=new evaluetion();

// Kiểm tra xem có department_id được gửi từ yêu cầu POST không
$department_id = isset($_POST['department_id_ds']) ? $_POST['department_id_ds'] : null;
$user_id=$_SESSION['user_id'];

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Đánh giá</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="../../assets/lib/bootstrap/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="../../assets/lib/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Theme style -->

    <link rel="stylesheet" href="../../assets/lib/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel=" stylesheet" href="../../assets/lib/dist/css/skins/_all-skins.min.css">
    <script src="../../assets/lib/plugins/jQuery/jquery-3.7.1.min.js"></script>
    <script src="../../assets/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>

    <!-- link_table -->
    <!-- Bootstrap 3.3.6 -->
    <!-- <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css"> -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../assets/lib/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../../assets/lib/dist/css/AdminLTE.css">
    <!-- hết -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
    </script>
    <script>
    $(document).ready(function() {
        $('.table').DataTable(); // Khởi tạo DataTable cho bảng

        // Xử lý sự kiện khi chọn phòng ban
        $('#department_id_ds').change(function() {
            var departmentId = $(this).val(); // Lấy ID phòng ban đã chọn
            $.ajax({
                url: 'https://www.quanlynhansuads.io.vn/view/admin/KPI_dep.php', // Adjust the URL as needed
                method: 'POST',
                data: {
                    department_id_ds: departmentId
                },
                success: function(response) {
                    $('.container.mt-5.hight1.width1').html(
                        response); // Cập nhật danh sách người dùng
                },
                error: function() {
                    alert('Error fetching KPIs.'); // Thông báo lỗi
                }
            });
        });
    });
    </script>
    <link rel="stylesheet" href="../../assets/css/employee/main.css">

</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php 
            
                include ("../layout/header.php");
            
            ?>
        <!-- Left side column. contains the logo and sidebar -->
        <?php 
                include ("../layout/sidebar.php");
            ?>

        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row dieuhuong mt-5">
                    <a href="index.php"><i class="fa fa-dashboard"></i> Home</a><i
                        class="fa fa-fw fa-chevron-right"></i>
                    <a href="#">Quản lý KPI phòng ban</a>

                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <h2>Quản lý KPI phòng</h2>
                    </div>
                </div>
                <div class="row mt-5">

                    <div class="col-sm6">
                        <form method="POST" class="form-inline mb-3">
                            <div class="form-group">
                                <label for="department_id">Phòng Ban:</label>
                                <select class="form-control mx-2" id="department_id" name="department_id">
                                    <option value="">Tất cả</option>
                                    <?php
                            $departments = $a->getDepartments(); // Fetch departments
                            foreach ($departments as $department) {
                                echo '<option value="' . htmlspecialchars($department['id']) . '">' . htmlspecialchars($department['department_name']) . '</option>';
                            }
                            ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="month">Tháng:</label>
                                <select class="form-control mx-2" id="month" name="month">
                                    <option value="">Tất cả</option>
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?php echo $m; ?>"><?php echo $m; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="year">Năm:</label>
                                <select class="form-control mx-2" id="year" name="year">
                                    <option value="">Tất cả</option>
                                    <?php
                            $currentYear = date("Y");
                            for ($y = $currentYear - 5; $y <= $currentYear; $y++): ?>
                                    <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Lọc</button>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">
                        <?php 
                        // Check if the form is submitted
                        $department_id = $_POST['department_id'] ?? null;
                        $month = $_POST['month'] ?? null;
                        $year = $_POST['year'] ?? null;

                        // Load KPIs based on filters
                        $a->loadKPIsByFilters($department_id, $month, $year);
                        
                        
                        ?>
                    </div>
                </div>



                <!-- Thêm đánh giá -->
                <div class="modal fade" id="evaluationModal" tabindex="-1" role="dialog"
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
                $('#evaluationModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('dept-kpi-id');

                    var modal = $(this);
                    modal.find('#dept_kpi_id').val(id);


                });

                $('#editEvaluationModal').on('show.bs.modal', function(event) {
                    var button = $(event.relatedTarget);
                    var id = button.data('evaluation-id');
                    var commment = button.data('commment');


                    var modal = $(this);
                    modal.find('#edit_dept_kpi_id').val(id);
                    modal.find('#current_evaluation_comments').val(commment);

                });

                // Xử lý lưu thay đổi khi nhấn nút "Save changes"
                $(document).on('click', '#updateEvaluation', function() {
                    var id = $('#edit_dept_kpi_id').val(); // Lấy id đánh giá
                    var newComments = $('#new_evaluation_comments').val(); // Lấy nội dung chỉnh sửa đánh giá
                    var user_id = <?php echo $user_id ?>;
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
                        url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_evaluetion_admin.php', // Thay đường dẫn phù hợp
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



        <?php 
            // include ("../../layout/main.php");
            include ("../layout/minisidebar.php");
            include ("../layout/footer.php");
        ?>

    </div>
    <!--Add the sidebar 's background. This div must be placed
                immediately after the control sidebar-- >
                <
                div class = "control-sidebar-bg" > < /div>

                    <
                    /div> . /
                wrapper-- >

                <
                !--jQuery 2.2 .0-- >

                <
                !--Bootstrap 3.3 .6-- >


                <
                !--FastClick-- > -->
    <script src="../../assets/lib/plugins/fastclick/fastclick.js">
    </script>
    <!-- AdminLTE App -->
    <script src="../../assets/lib/dist/js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="../../assets/lib/plugins/sparkline/jquery.sparkline.min.js">
    </script>
    <!-- jvectormap -->
    <script src="../../assets/lib/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js">
    </script>
    <script src="../../assets/lib/plugins/jvectormap/jquery-jvectormap-world-mill-en.js">
    </script>
    <!-- SlimScroll 1.3.0 -->
    <script src="../../assets/lib/plugins/slimScroll/jquery.slimscroll.min.js">
    </script>
    <!-- ChartJS 1.0.1 -->
    <script src="../../assets/lib/plugins/chartjs/Chart.min.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="../../assets/lib/dist/js/pages/dashboard2.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../assets/lib/dist/js/demo.js"></script>
    <!--avgvwrbvrbw555555555555555555555555555-->
    <script src="../../plugins/jQuery/jQuery-2.2.0.min.js"></script>
    <!-- Bootstrap 3.3.6 -->

    <!-- DataTables -->
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="../../plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="../../assets/lib/dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../assets/lib/dist/js/demo.js"></script>
    <!-- page script -->
    <script>
    $(function() {
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
    </script>
</body>






</html>

</html>