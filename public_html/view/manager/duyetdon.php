<?php
include '../layout_manager/auth.php';
include '../../controller/class/class_nghiphep.php';
$a = new nghiphep();
$user_id=$_SESSION['user_id'];
$department_id=$a->getDepartmentId($user_id);

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
    <title>Duyệt Đơn</title>

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
                <li class="active">Duyệt Đơn</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-11">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Danh Sách Đơn Xin Nghỉ Phép / Đi Trễ</h3>
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
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Tên Nhân Viên</th>
                                    <th>Phòng Ban</th>
                                    <th>Loại Đơn</th>
                                    <th>Lý Do</th>
                                    <th>Thời Gian Bắt Đầu</th>
                                    <th>Thời Gian Kết Thúc</th>
                                    <th>Trạng Thái</th>
                                    <th>Lý do từ chối</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Call the appropriate method based on the selected month and year
                                if (!empty($month) && !empty($year)) {
                                    $a->showLeaveRequestsByMonthYear($department_id, $month, $year);
                                } else {
                                    $a->showLeaveRequests($department_id);
                                }
                                ?>
                            </tbody>
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
                        <input type="hidden" id="id1" name="id1" readonly>
                        <form id="rejectForm">
                            <input type="hidden" name="request_id" id="rejectRequestId">
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
        // Xử lý gửi lý do từ chối
        $('#rejectForm').on('submit', function(e) {
            e.preventDefault(); // Ngăn chặn gửi form mặc định
            var requestId = $('#rejectRequestId').val();
            var reasonReject = $('#rejectReason').val();

            $.ajax({
                url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_nghiphep.php',
                method: 'POST',
                data: {
                    request_id: requestId,
                    action: 'reject_request', // Hành động từ chối
                    reason_reject: reasonReject // Gửi lý do từ chối
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    alert(result.message); // Hiển thị thông báo bằng alert
                    if (result.status === 1) {
                        location.reload(); // Tải lại trang nếu thành công
                    }
                },
                error: function() {
                    alert('Đã xảy ra lỗi!'); // Thông báo lỗi
                }
            });
        });

        // Xử lý duyệt đơn
        $('.approve-btn').on('click', function() {
            var requestId = $(this).data('id'); // Lấy ID yêu cầu từ nút
            var confirmation = confirm("Bạn có chắc chắn muốn duyệt đơn này không?"); // Xác nhận

            if (confirmation) {
                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_nghiphep.php',
                    method: 'POST',
                    data: {
                        request_id: requestId,
                        action: 'approve_request' // Hành động duyệt
                    },
                    success: function(response) {
                        var result = JSON.parse(response);
                        alert(result.message); // Hiển thị thông báo bằng alert
                        if (result.status === 1) {
                            location.reload(); // Tải lại trang nếu thành công
                        }
                    },
                    error: function() {
                        alert('Đã xảy ra lỗi!'); // Thông báo lỗi
                    }
                });
            } else {
                alert("Đã hủy hành động duyệt đơn."); // Thông báo nếu người dùng hủy
            }
        });

        // Xử lý mở modal và hiển thị ID
        $('#rejectReasonModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Lấy nút đã kích hoạt modal
            var id = button.data('idu'); // Lấy giá trị data-id từ nút
            var modal = $(this);
            modal.find('#id1').val(id); // Hiển thị ID vào trường input
            modal.find('#rejectRequestId').val(id); // Cập nhật request_id
        });
    });
    </script>
</body>

</html>