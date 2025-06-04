<?php
include '../layout/auth.php';
?>
<?php
include '../../controller/class/class_thuong_kpi.php';
$a=new tinhluong();


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tính thưởng cho phòng</title>
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
                    <a href="#">Tính thưởng cho phòng</a>

                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <h2>Tính thưởng cho phòng ban</h2>
                    </div>
                </div>
                <div class="row mt-5">

                    <div class="col-sm6">
                        <?php
                        // Retrieve submitted values or set defaults
                        $department_id = $_POST['department_id'] ?? 0;
                        $month = $_POST['month'] ?? '';
                        $year = $_POST['year'] ?? '';
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
                        $month = $_POST['month'] ?? null;
                        $year = $_POST['year'] ?? null;

                        // Load KPIs based on filters
                        $a->loadKPIsByFilters($department_id, $month, $year);
                        ?>
                    </div>
                </div>




                <script>
                $(document).ready(function() {
                    $('.table').DataTable();
                });
                </script>








                <script>
                $(document).ready(function() {
                    // Handle the "Tính Thưởng" button click event
                    $(document).on('click', '.calculate-bonus-btn', function() {
                        var deptKpiId = $(this).data('id');
                        var bonusAmount = $(this).data('bonus');

                        // Populate the modal with bonus details
                        $('#bonusAmount').text(bonusAmount);
                        $('#deptKpiId').val(deptKpiId);

                        console.log('Department KPI ID:', deptKpiId);
                        console.log('Bonus Amount:', bonusAmount);

                        // Show the modal
                        $('#bonusModal').modal('show');
                    });

                    // Handle the "Xác nhận" button click in the modal
                    $('#confirmBonus').on('click', function() {
                        // Retrieve values from the modal
                        var deptKpiId = $('#deptKpiId').val(); // Get value from hidden input
                        var bonusAmount = $('#bonusAmount').text().replace(/,/g, ''); // Remove commas

                        // Log the values to verify they are correct
                        console.log('Department KPI ID:', deptKpiId);
                        console.log('Bonus Amount:', bonusAmount);

                        // Make an AJAX request to update the is_active status
                        $.ajax({
                            url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_tinh_thuong_KPI.php', // Adjust the path as needed
                            method: 'POST',
                            data: {
                                action: 'confirm_bonus',
                                dept_kpi_id: deptKpiId,
                                goalBasedIncentive: bonusAmount // Send the formatted bonus amount
                            },
                            success: function(response) {
                                if (response == 1) {
                                    alert('Tính lương thành công!');
                                    location.reload(); // Reload the page to see changes
                                } else {
                                    alert('Tính lương thất bại');
                                }
                            }
                        });
                    });
                });
                </script>

                <!-- Bonus Modal -->
                <div class="modal fade" id="bonusModal" tabindex="-1" role="dialog" aria-labelledby="bonusModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="bonusModalLabel">Bonus Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Bonus Amount: <span id="bonusAmount" name="bonusAmount"></span></p>
                                <input type="hidden" id="deptKpiId">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Chưa Tính</button>
                                <button type="button" class="btn btn-primary" id="confirmBonus">Tính Thưởng</button>
                            </div>
                        </div>
                    </div>
                </div>

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



</body>



</html>