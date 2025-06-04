<?php
include '../layout/auth.php';
?>
<?php
include '../../controller/class/class_thuongkpi_nv_a.php';
$a=new thuongkpi_nv_a();


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quản lý thưởng KPI nhân viên</title>
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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
    </script>
    <link rel="stylesheet" href="../../assets/css/employee/main.css">
    <style>
    .hight1 {
        min-height: 900px;

    }

    .my-2 {
        margin: 5px;
    }

    .mt-5 {
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .dieuhuong {
        position: relative;

    }

    .minheight {
        min-height: 900px;
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
            <div class="container-fluid minheight">
                <div class="row dieuhuong mt-5">
                    <a href="index.php"><i class="fa fa-dashboard"></i> Home</a><i
                        class="fa fa-fw fa-chevron-right"></i>
                    <a href="#">Tính thưởng cho nhân viên</a>


                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <h2>Tính thưởng cho nhân viên</h2>

                    </div>
                </div>
                <div class="row mt-5">

                    <div class="col-sm6">
                        <?php
                        // Retrieve submitted values or set defaults
                        $department_id = $_POST['department_id'] ?? 0;
                        $month = $_POST['month'] ?? date('n');
                        $year = $_POST['year'] ?? date('Y');
                        ?>
                        <form method="POST" class="form-inline mb-3">
                            <div class="form-group">
                                <label for="department_id">Phòng Ban:</label>
                                <select class="form-control mx-2" id="department_id" name="department_id">
                                    <option value="0" <?php echo ($department_id == 0) ? 'selected' : ''; ?>>Tất cả
                                    </option>
                                    <?php
                            $departments = $a->getDepartments(); // Fetch departments
                            foreach ($departments as $department) {
                                $selected = ($department_id == $department['id']) ? 'selected' : '';
                                echo '<option value="' . htmlspecialchars($department['id']) . '" ' . $selected . '>' . htmlspecialchars($department['department_name']) . '</option>';
                            }
                            ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="month">Tháng:</label>
                                <select class="form-control mx-2" id="month" name="month">
                                    <option value="" <?php echo ($month == '') ? 'selected' : ''; ?>>Tất cả</option>
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?php echo $m; ?>" <?php echo ($month == $m) ? 'selected' : ''; ?>>
                                        <?php echo $m; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="year">Năm:</label>
                                <select class="form-control mx-2" id="year" name="year">
                                    <option value="" <?php echo ($year == '') ? 'selected' : ''; ?>>Tất cả</option>
                                    <?php
                            $currentYear = date("Y");
                            for ($y = $currentYear - 5; $y <= $currentYear + 5; $y++): ?>
                                    <option value="<?php echo $y; ?>" <?php echo ($year == $y) ? 'selected' : ''; ?>>
                                        <?php echo $y; ?></option>
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
                        // $month = $_POST['month'] ?? null;
                        // $year = $_POST['year'] ?? null;
                        
                        // Load KPIs based on filters
                        $a->loadKPIByMonthYearUser($department_id, $month, $year);
                        ?>
                    </div>
                </div>




                <script>
                $(document).ready(function() {
                    $('.table').DataTable();
                });
                </script>






                <script>

                </script>


            </div>
        </div>

        <?php 
            // include ("../../layout/main.php");
            include ("../layout/minisidebar.php");
            include ("../layout/footer.php");
        ?>
    </div> <!-- Content Wrapper. Contains page content -->

    <!-- /.content-wrapper -->


    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->

    <!-- ./wrapper -->

    <!-- jQuery 2.2.0 -->

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

    <!-- Add Transaction Modal -->
    <div id="addTransactionModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Transaction</h4>
                </div>
                <div class="modal-body">
                    <form id="addTransactionForm">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    
                                    <div class="col-sm-3">
                                        <input type="hidden" name="user_id" id="user_id">
                                        <input type="hidden" name="totalbonus" id="totalbonus">
                                        <label for="manv">Mã nhân viên:</label>
                                        <input type="text" class="form-control" id="manv" name="manv" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="fullname">Họ và tên</label>
                                        <input type="text" class="form-control" id="fullname" name="fullname" readonly>
                                    </div>
                                    <div class="col-sm-3">
                                        <label for="transaction_date">Tháng tính thưởng:</label>
                                        <input type="text" class="form-control" id="transaction_date"
                                            name="transaction_date" readonly>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên KPI</th>
                                            <th>Mô tả KPI</th>
                                            <th>Giá trị được giao</th>
                                            <th>Giá trị thực tế</th>
                                            <th>Tiến độ</th>
                                            <th>Ngày được giao</th>
                                            <th>Ngày kết thúc</th>
                                            <th>Trạng thái</th>
                                            <th>Thưởng</th>
                                        </tr>
                                    </thead>
                                    <tbody id="kpiTableBody">
                                         KPI data will be populated here 
                                    </tbody>
                                </table>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="totalGoalBasedIncentive">Tổng Tổng thưởng:</label>
                            <span id="totalGoalBasedIncentive"></span>
                        </div>
                        <button type="submit" class="btn btn-primary">Thêm thưởng</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Add this button to your HTML where you want it to appear -->


    <script>
    $(document).ready(function() {
        // Event listener for the button click
        $('#loadKPIButton').on('click', function() {
            // AJAX request to load KPI data
            $.ajax({
                url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_tinhthuongnhanvien.php',
                method: 'POST',
                data: {
                    action: 'loadKPIByuser',
                    user_id: 59, // Fixed user_id
                    month: 11, // Fixed month
                    year: 2024 // Fixed year
                },
                success: function(response) {
                    console.log('KPI Data:', response);
                    // You can parse the response if it's JSON
                    // var data = JSON.parse(response);
                    // console.log(data);
                },
                error: function() {
                    console.error('An error occurred while loading KPI data.');
                }
            });
        });
    });
    </script>

    <script>
    $(document).ready(function() {
        // Event listener for the "Thêm thưởng" button
        $(document).on('click', '.calculate-bonus-btn', function() {
            console.log('Calculate Bonus Button Clicked');

            var userId = $(this).data('user_id');
            var fullname = $(this).data('fullname');
            var manv = $(this).data('manv');
            var date = $(this).data('date');
            var totalbonus = $(this).data('totalbonus');

            console.log('userId:', userId);
            console.log('fullname:', fullname);
            console.log('manv:', manv);
            console.log('date:', date);
            console.log('totalbonus:', totalbonus);
            // Populate the modal fields with the data
            $('#user_id').val(userId);
            $('#fullname').val(fullname);
            $('#manv').val(manv);
            $('#transaction_date').val(date);
            $('#totalbonus').val(totalbonus);
            // Fetch KPI data for the user
            $.ajax({
                url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_tinhthuongnhanvien.php',
                method: 'POST',
                data: {
                    action: 'loadKPIByuser',
                    user_id: userId,
                    month: new Date(date).getMonth() + 1, // Extract month from date
                    year: new Date(date).getFullYear() // Extract year from date
                },
                success: function(response) {
                    console.log('KPI Data:', response);
                    var data = JSON.parse(response);
                    var tableContent = '';
                    var totalGoalBasedIncentive = 0;

                    if (data && data.length > 0) {
                        data.forEach(function(item, index) {
                            var progressRatio = item.progress / item.assigned_value;
                            var action = item.is_active == 0 ? 'Chưa tính thưởng' :
                                item.goalBasedIncentive;

                            tableContent += '<tr>' +
                                '<td>' + (index + 1) + '</td>' +
                                '<td>' + item.kpi_name + '</td>' +
                                '<td>' + item.kpi_description + '</td>' +
                                '<td>' + item.assigned_value + '</td>' +
                                '<td>' + item.progress + '</td>' +
                                '<td>' + progressRatio.toFixed(2) + '</td>' +
                                '<td>' + item.assigned_date + '</td>' +
                                '<td>' + item.due_date + '</td>' +
                                '<td>' + item.name_status_kpi + '</td>' +
                                '<td>' + (item.goalBasedIncentive ? item
                                    .goalBasedIncentive : 0) + '</td>' +
                                '</tr>';

                            if (item.is_active == 1) {
                                totalGoalBasedIncentive += parseFloat(item
                                    .goalBasedIncentive);
                            }
                        });
                    } else {
                        tableContent =
                            '<tr><td colspan="10" class="text-center">Không có dữ liệu</td></tr>';
                    }

                    $('#kpiTableBody').html(tableContent);
                    $('#totalGoalBasedIncentive').text(totalGoalBasedIncentive.toFixed(2));

                    // Show the modal
                    $('#addTransactionModal').modal('show');
                },
                error: function() {
                    alert('An error occurred while fetching KPI data.');
                }
            });
        });
    });

    $(document).ready(function() {
        // Event listener for the "Thêm thưởng" button


        // Handle form submission
        $('#addTransactionForm').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission
            
            $.ajax({
                url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_tinhthuongnhanvien.php', // Adjust the path if necessary
                method: 'POST',
                data: {
                    action: 'addTransaction',
                    user_id: $('#user_id').val(),
                    amount: $('#totalbonus').val(),
                    transaction_date: $('#transaction_date').val()
                },
                success: function(response) {
                    alert(response); // Display the response from the server
                    $('#addTransactionModal').modal('hide'); // Hide the modal
                    location.reload(); // Reload the page to see the changes
                },
                error: function() {
                    alert('An error occurred while adding the transaction.');
                }
            });
        });
    });
    </script>


</body>



</html>