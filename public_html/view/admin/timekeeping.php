<?php
include '../layout/auth.php';
include '../../controller/class/class_timekeeping.php';

$a = new timekeeping();

$department_id = $_POST['department_id'] ?? date('Y-m-d H:i:s');
$monthYear = $_POST['monthYear'] ?? date('Y-m-d H:i:s');

// Check if department_id or monthYear is not set
// if (!$department_id || !$monthYear) {
//     // Call loadTimekeeping if no filters are applied
//     $a->loadTimekeeping();
// } else {
//     // Call loadTimekeepingByFilters if filters are applied
//     $a->loadTimekeepingByFilters($department_id, $monthYear);
// }
// ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quản lý chấm công</title>
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
    </script>
    <!-- icon font - awsome -->

    <style>
    .hight1 {
        min-height: 900px;
    }

    .calendar {
        border-collapse: collapse;
        width: 100%;
    }

    .calendar th {
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
        position: relative;
    }

    .calendar td {
        width: 150px;
        height: 200px;
        border: 1px solid #ccc;
        padding: 10px;
        text-align: center;
        position: relative;
        vertical-align: top;
    }

    .off-day {
        background-color: #f8f9fa;
    }

    .date {
        position: absolute;
        top: 10px;
        left: 10px;
        font-weight: bold;
        font-size: 16px;
    }

    .cong {
        font-size: 24px;
        font-weight: bold;
        color: green;
        display: block;
        margin-top: 10px;
        height: 4em;
        display: flex;
        flex-direction: column;
        justify-content: space-around;

    }

    .time-in,
    .time-out {
        display: block;
        font-size: 14px;
        width: 100%;
        text-align: center;
        margin-top: 10px;
    }

    .time-in i,
    .time-out i {

        display: block;
        font-size: 18px;
        color: #007bff;
        float: left;
        width: 45%;
        /* Blue color for icons */
    }

    .time-in p,
    .time-out p {
        display: block;
        float: left;
        width: 45%;
        font-size: 14px;
    }

    /* ### */
    </style>
    <style>
    /* Custom table styles */
    table {
        width: 100%;
        margin: 20px 0;
        border-collapse: collapse;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px 12px;
        text-align: center;
    }

    th {
        background-color: #f4f4f4;
    }

    .paid {
        color: green;
    }

    .absent {
        color: red;
    }

    .weekend {
        background-color: #e6f7e6;
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

                <div class="row bg-secondary" style="background-color: #79a3a3;">
                    <div class="col-sm-8">
                        <h2>Quản lý chấm công</h2>
                    </div>
                    <div class="col-sm-4" style="padding-top:10px;">
                        <div class="col-sm-6">
                            <i class="fa fa-fw fa-users"></i>

                            <p><?php echo $a->getSum_user($department_id);?> nhân viên</p>
                        </div>
                        <div class="col-sm-6">
                            <i class="fa fa-fw fa-calendar"></i>
                            <p>
                                <?php echo $a->getWeekdaysCountFromRange($monthYear); ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-7">
                        <form method="POST" class="form-inline mb-3">
                            <div class="form-group">
                                <label for="department_id">Phòng Ban:</label>
                                <select class="form-control mx-2" id="department_id" name="department_id">
                                    <option value="">Tất cả</option>
                                    <?php
                            $departments = $a->getDepartments(); // Fetch departments
                            foreach ($departments as $department) {
                                $selected = ($department_id == htmlspecialchars($department['id'])) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($department['id']) . '" ' . $selected . '>' . htmlspecialchars($department['department_name']) . '</option>';
                            }
                            ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="monthYear">Chọn tháng và năm:</label>
                                <input type="month" id="monthYear" name="monthYear" class="form-control mx-2"
                                    value="<?php echo htmlspecialchars($monthYear); ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Lọc</button>
                        </form>
                    </div>
                    <div class="col-sm-4">

                        <button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#exportModal">Export Timekeeping</button>
                        <button type="button" class="btn btn-secondary" data-toggle="modal"
                            data-target="#importModal">Import Timekeeping</button>

                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive">

                            <?php 
                            
                            $a->loadTimekeepingByFilters($department_id, $monthYear);
                            // $a->loadTimekeeping();
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Export Modal -->
            <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exportModalLabel"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exportModalLabel">Export Timekeeping</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="exportForm">
                            <div class="modal-body">
                                <div class="form-group">

                                    <label for="user">Chọn người dùng</label>
                                    <select id="user" name="user" class="form-control">
                                        <?php  $a->generateUserOptions(); ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="monthYear1">Month-Year (YYYY-MM):</label>
                                    <input type="month" id="monthYear1" name="monthYear1" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Thoát</button>
                                <button type="button" class="btn btn-primary" id="exportButton">Xuất</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
            function importTimekeeping(files) {
                // Handle file import logic here
                alert('Chức năng nhập chưa được triển khai.');
            }

            document.getElementById('exportButton').addEventListener('click', function() {
                let userId = document.getElementById('user').value;
                let monthYear1 = document.getElementById('monthYear1').value;

                console.log('Export button clicked');
                console.log('User ID:', userId);
                console.log('Month-Year:', monthYear1);

                if (userId && monthYear1) {
                    console.log('Sending AJAX request');
                    $.ajax({
                        url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_timekeeping.php',
                        type: 'POST',
                        data: {
                            action: 'export',
                            user_id: userId,
                            monthYear: monthYear1
                        },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function(data) {
                            console.log('AJAX request successful');
                            let blob = new Blob([data], {
                                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                            });
                            let url = window.URL.createObjectURL(blob);
                            let a = document.createElement('a');
                            a.href = url;
                            a.download = 'attendance_report_' + monthYear1 + '.xlsx';
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                            window.URL.revokeObjectURL(url);
                        },
                        error: function() {
                            console.error('Lỗi khi xuất dữ liệu');
                            alert('Lỗi khi xuất dữ liệu. Hãy thử lại!!.');
                        }
                    });
                } else {
                    console.log('Xác thực không thành công');
                    alert('Hãy nhập đầy đủ thông tin.');
                }
            });
            </script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        </div>

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
        <script>
        $(document).ready(function() {
            $('.table').DataTable();
        });
        </script>
        <!-- //modal -->
        <div class="modal fade" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="attendanceModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title" id="attendanceModalLabel">Attendance Details</h4>
                    </div>
                    <div class="modal-body">
                        <form id="attendanceForm">
                            <div class="form-group">
                                <label for="modal-clock-in"><strong>Clock In:</strong></label>
                                <span id="modal-clock-in"></span>
                            </div>

                            <div class="form-group">
                                <label for="modal-clock-out"><strong>Clock Out:</strong></label>
                                <span id="modal-clock-out"></span>
                            </div>

                            <div class="form-group">
                                <label for="modal-wifi-in"><strong>WiFi In:</strong></label>
                                <span id="modal-wifi-in"></span>
                            </div>

                            <div class="form-group">
                                <label for="modal-wifi-out"><strong>WiFi Out:</strong></label>
                                <span id="modal-wifi-out"></span>
                            </div>

                            <div class="form-group">
                                <label for="modal-work-time"><strong>Work Time:</strong></label>
                                <input type="text" class="form-control" id="modal-work-time" name="modal-work-time"
                                    required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="saveWorkTime">Save Work Time</button>
                    </div>
                </div>
            </div>
        </div>


        <script>
        $(document).on('click', '.has-data', function() {
            $('#modal-clock-in').text($(this).data('clock-in'));
            $('#modal-clock-out').text($(this).data('clock-out'));
            $('#modal-wifi-in').text($(this).data('wifi-in'));
            $('#modal-wifi-out').text($(this).data('wifi-out'));
            $('#modal-work-time').text($(this).data('work-time'));
        });
        </script>

        <!-- Import Modal -->
        <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Timekeeping</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="importForm" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="importFile">Choose Excel File</label>
                                <input type="file" class="form-control" id="importFile" name="importFile" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="importButton">Import</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function validateFile(fileInput) {
            const file = fileInput.files[0];
            const allowedExtensions = /(\.xlsx|\.xls)$/i;
            const maxSize = 5 * 1024 * 1024; // 5 MB

            if (!allowedExtensions.exec(file.name)) {
                alert('Vui lòng tải lên tệp Excel hợp lệ (.xlsx or .xls).');
                fileInput.value = '';
                return false;
            }

            if (file.size > maxSize) {
                alert('Kích thước tệp vượt quá 5 MB. Vui lòng tải lên một tệp nhỏ hơn.');
                fileInput.value = '';
                return false;
            }

            return true;
        }

        $(document).ready(function() {
            $('#importButton').click(function() {
                const fileInput = document.getElementById('importFile');
                if (!validateFile(fileInput)) {
                    return;
                }

                var formData = new FormData($('#importForm')[0]);
                formData.append('action', 'import');

                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_timekeeping.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        alert(response);
                        $('#importModal').modal('hide');
                    },
                    error: function() {
                        alert('Lỗi khi thêm dữ liệu. Vui lòng thử lại!!.');
                    }
                });
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