<?php
include '../layout/auth.php';
?>
<?php
include '../../controller/class/class_duyetdon_admin.php';
$a=new duyetdon();

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
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Duyệt đơn nghỉ phép</title>
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- With the full version -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
    </script>
    <!-- Bootstrap và jQuery -->
    <!-- Replace this slim version -->
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- With the full version -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">

    </script>
    <link rel="stylesheet" href="../../assets/css/employee/main.css">
    <style>
    .hight1 {
        min-height: 900px;

    }

    .dieuhuong {
        position: relative;

    }
    </style>
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
                    <a href="#">Duyệt đơn nghỉ phép</a>

                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <h2>Duyệt đơn nghỉ phép</h2>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">

                        </div>
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
                                                    <?php echo ($m == $month) ? 'selected' : ''; ?>>
                                                    <?php echo $m; ?>
                                                </option>
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
                                                    <?php echo ($y == $year) ? 'selected' : ''; ?>>
                                                    <?php echo $y; ?>
                                                </option>
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
                                    $a->showLeaveRequestsByMonthYear( $month, $year);
                                } else {
                                    $a->showLeaveRequests();
                                }
                                ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">



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
                                            <form id="rejectForm">
                                                <input type="hidden" id="id1" name="id1" readonly>

                                                <input type="hidden" name="request_id" id="rejectRequestId">
                                                <div class="form-group">
                                                    <label for="rejectReason">Lý Do</label>
                                                    <textarea class="form-control" id="rejectReason" name="rejectReason"
                                                        required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-xs btn-danger reject-btn">Gửi Lý
                                                    Do</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            <script>
            $('#rejectReasonModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Nút kích hoạt modal
                var id = button.data('idu'); // Thống nhất là 'data-id'
                console.log("ID:");
                var modal = $(this);
                modal.find('#rejectRequestId').val(id); // Gán ID vào trường input
            });
            $(document).ready(function() {
                // Xử lý gửi lý do từ chối
                $('#rejectForm').on('submit', function(e) {
                    e.preventDefault(); // Ngăn chặn gửi form mặc định
                    var requestId = $('#rejectRequestId').val();
                    var reasonReject = $('#rejectReason').val();

                    $.ajax({
                        url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_duyetdon_admin.php',
                        method: 'POST',
                        data: {
                            request_id: requestId,
                            action: 'reject_request', // Hành động từ chối
                            reason_reject: reasonReject // Gửi lý do từ chối
                        },
                        success: function(response) {
                            var result = JSON.parse(response);
                            alert(result
                                .message); // Hiển thị thông báo bằng alert
                            if (result.status === 1) {
                                location
                                    .reload(); // Tải lại trang nếu thành công
                            }
                        },
                        error: function() {
                            alert('Đã xảy ra lỗi!'); // Thông báo lỗi
                        }
                    });
                });

            });
            $(document).ready(function() {
                // Xử lý duyệt đơn
                $('.approve-btn').on('click', function() {
                    var requestId = $(this).data('id'); // Lấy ID yêu cầu từ nút
                    var confirmation = confirm(
                        "Bạn có chắc chắn muốn duyệt đơn này không?"); // Xác nhận

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
                                alert(result
                                    .message
                                ); // Hiển thị thông báo bằng alert
                                if (result.status === 1) {
                                    location
                                        .reload(); // Tải lại trang nếu thành công
                                }
                            },
                            error: function() {
                                alert('Đã xảy ra lỗi!'); // Thông báo lỗi
                            }
                        });
                    } else {
                        alert(
                            "Đã hủy hành động duyệt đơn."
                        ); // Thông báo nếu người dùng hủy
                    }
                });
            });
            </script>




        </div>

        <?php 
            // include ("../../layout/main.php");
            include ("../layout/minisidebar.php");
            include ("../layout/footer.php");
        ?>

        <!-- Content Wrapper. Contains page content -->

        <!-- /.content-wrapper -->


        <!-- Control Sidebar -->

        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>

    </div>
    <!-- ./wrapper -->

    <!-- jQuery 2.2.0 -->

    <script src="../../assets/lib/plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="../../assets/lib/dist/js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="../../assets/lib/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="../../assets/lib/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="../../assets/lib/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="../../assets/lib/plugins/slimScroll/jquery.slimscroll.min.js"></script>
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

</html>

</html>