<?php
include '../layout/auth.php';
?>
<?php
include '../../controller/class/class_namekpi_emp.php';
$a=new namekpi_emp();

?>
<!DOCTYPE html>
<html>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quản lý tên KPI nhân viên</title>
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
    #departmentForm {
        display: flex;
        align-items: center;
    }

    #departmentForm .form-group {
        margin-right: 10px;
        /* Khoảng cách giữa các phần tử */
    }

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
            <div class="container-fluid ">
                <div class="row dieuhuong mt-5">
                    <a href="index.php"><i class="fa fa-dashboard"></i> Home</a><i
                        class="fa fa-fw fa-chevron-right"></i>
                    <a href="#">Quản lý tên KPI nhân viên</a>

                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <h2>Quản lý tên KPI nhân viên</h2>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-2"> <button class="btn btn-success mt-3 " data-toggle="modal"
                            data-target="#addModal">Thêm tên kpi
                        </button></div>
                    <div class="col-sm-6">
                        <form method="post" action="" id="departmentForm" class="form-inline">
                            <div class="form-group">
                                <label for="department_id">Chọn phòng ban:</label>
                                <select class="form-control" id="department_id" name="department_id">
                                    <option value="">Tất cả</option>
                                    <?php
            $departments = $a->getDepartments();
            foreach ($departments as $department) {
                echo '<option value="' . htmlspecialchars($department['id']) . '">' . htmlspecialchars($department['department_name']) . '</option>';
            }
            ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Lọc</button>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <?php 
                        // Kiểm tra xem có department_id được gửi từ yêu cầu POST không
                        $department_id = isset($_POST['department_id']) ? $_POST['department_id'] : null;
                        $a->loadKpiData($department_id); // Gọi hàm với department_id
                        ?>
                    </div>
                </div>


                <!-- Modal Edit Department -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="editModalLabel">Update KPI</h4>
                            </div>
                            <div class="modal-body">
                                <form id="editForm">
                                    <input type="hidden" id="kpi_id" name="kpi_id">
                                    <div class="form-group">
                                        <label for="kpi_name">KPI Name</label>
                                        <input type="text" class="form-control" id="kpi_name" name="kpi_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kpi_description">KPI Description</label>
                                        <textarea class="form-control" id="kpi_description" name="kpi_description"
                                            required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="is_counted">Is Counted</label>
                                        <select class="form-control" id="is_counted" name="is_counted" required>
                                            <option value="1">Có</option>
                                            <option value="0">Không</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="saveChanges">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Add KPI -->
                <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="addModalLabel">Add KPI</h4>
                            </div>
                            <div class="modal-body">
                                <form id="addKpiForm">
                                    <div class="form-group">
                                        <label for="namkpidep">Tên KPI phòng</label>
                                        <select class="form-control" id="namkpidep" name="namkpidep" required>
                                            <?php
                                                $getnameKPIDeps = $a->getnameKPIDep(); // Fetch departments
                                                foreach ($getnameKPIDeps as $getnameKPIDep) {
                                                    echo '<option value="' . htmlspecialchars($getnameKPIDep['kpi_lib_id']) . '">' . htmlspecialchars($getnameKPIDep['kpi_name']) . '</option>';
                                                }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="add_kpi_name">KPI Name</label>
                                        <input type="text" class="form-control" id="add_kpi_name" name="kpi_name"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="add_kpi_description">KPI Description</label>
                                        <textarea class="form-control" id="add_kpi_description" name="kpi_description"
                                            required></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="add_is_counted">Is Counted</label>
                                        <select class="form-control" id="add_is_counted" name="is_counted" required>
                                            <option value="1">Có</option>
                                            <option value="0">Không</option>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="addKpi">Add KPI</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <script>
            $(document).ready(function() {
                $('.table').DataTable();
            });
            </script>
            <script>
            // Populate the edit modal with the current KPI data
            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var kpiId = button.data('id');
                var kpiName = button.data('name');
                var kpiDescription = button.data('description');
                var isCounted = button.data('iscounted') === 'có' ? 1 : 0; // Convert to 1 or 0

                var modal = $(this);
                modal.find('#kpi_id').val(kpiId);
                modal.find('#kpi_name').val(kpiName);
                modal.find('#kpi_description').val(kpiDescription);
                modal.find('#is_counted').val(isCounted); // Set the value for is_counted
            });

            // Handle save changes for editing KPI
            $(document).on('click', '#saveChanges', function() {
                var kpiId = $('#kpi_id').val();
                var kpiName = $('#kpi_name').val();
                var kpiDescription = $('#kpi_description').val();
                var isCounted = $('#is_counted').val(); // Get the value of is_counted

                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_namekpi.php', // Update with correct path
                    method: 'POST',
                    data: {
                        action: 'update',
                        kpi_id: kpiId,
                        kpi_name: kpiName,
                        kpi_description: kpiDescription,
                        is_counted: isCounted // Send is_counted value
                    },
                    success: function(response) {
                        if (response == 1) {
                            alert('KPI updated successfully!');
                            location.reload();
                        } else {
                            alert('Error updating KPI.');
                        }
                    }
                });
            });

            // Handle delete KPI
            $(document).on('click', '.delete-btn', function() {
                var kpiId = $(this).data('id');
                if (confirm('Are you sure you want to delete this KPI?')) {
                    $.ajax({
                        url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_namekpi.php', // Update with correct path
                        method: 'POST',
                        data: {
                            action: 'delete',
                            kpi_id: kpiId
                        },
                        success: function(response) {
                            if (response == 1) {
                                alert('KPI deleted successfully!');
                                location.reload();
                            } else {
                                alert('Error deleting KPI.');
                            }
                        }
                    });
                }
            });

            // Handle add KPI
            $(document).on('click', '#addKpi', function() {
                var departmentId = $('#namkpidep').val(); // Get the selected department ID
                var kpiName = $('#add_kpi_name').val();
                var kpiDescription = $('#add_kpi_description').val();
                var isCounted = $('#add_is_counted').val(); // Get the value of is_counted

                if (departmentId.trim() === '' || kpiName.trim() === '') {
                    alert('Department and KPI Name cannot be empty.');
                    return;
                }

                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_namekpi.php', // Update with correct path
                    method: 'POST',
                    data: {
                        action: 'add',
                        kpi_lib_id: departmentId,
                        kpi_name: kpiName,
                        kpi_description: kpiDescription,
                        is_counted: isCounted // Send is_counted value
                    },
                    success: function(response) {
                        if (response == 1) {
                            alert('KPI added successfully!');
                            location.reload();
                        } else {
                            alert('Error adding KPI.');
                        }
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