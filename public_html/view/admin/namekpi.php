<?php
include '../layout/auth.php';
?>
<?php
include '../../controller/class/class_namekpi.php';
$a=new namekpi();

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quản lý tên KPI phòng ban</title>
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
            <div class="container-fluid ">
                <div class="row dieuhuong mt-5">
                    <a href="index.php"><i class="fa fa-dashboard"></i> Home</a><i
                        class="fa fa-fw fa-chevron-right"></i>
                    <a href="#">Quản lý tên KPI</a>

                </div>
                <div class="row">

                    <div class="col-sm-3">
                        <h2>Quản lý tên KPI</h2>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-1"> <button class="btn btn-success mt-3 add_css" data-toggle="modal"
                            data-target="#addModal">Thêm tên kpi
                        </button></div>
                </div>
                <div class="row">
                    <div class="col-sm-12"> <?php $a->loadKpiData();?></div>

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
                                        <label for="department_id">Department</label>
                                        <select class="form-control" id="department_id" name="department_id" required>
                                            <?php
                                                $departments = $a->getDepartments(); // Fetch departments
                                                foreach ($departments as $department) {
                                                    echo '<option value="' . htmlspecialchars($department['id']) . '">' . htmlspecialchars($department['department_name']) . '</option>';
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

                var modal = $(this);
                modal.find('#kpi_id').val(kpiId);
                modal.find('#kpi_name').val(kpiName);
                modal.find('#kpi_description').val(kpiDescription);
            });

            // Handle save changes for editing KPI
            $(document).on('click', '#saveChanges', function() {
                var kpiId = $('#kpi_id').val();
                var kpiName = $('#kpi_name').val();
                var kpiDescription = $('#kpi_description').val();

                // Log the values to the console
                console.log("Update KPI - KPI ID:", kpiId);
                console.log("Update KPI - KPI Name:", kpiName);
                console.log("Update KPI - KPI Description:", kpiDescription);

                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_namekpi.php', // Update with correct path
                    method: 'POST',
                    data: {
                        action: 'update',
                        kpi_id: kpiId,
                        kpi_name: kpiName,
                        kpi_description: kpiDescription
                    },
                    success: function(response) {
                        console.log("Update KPI Response:", response);
                        if (response == 1) {
                            alert('Cập nhật thành công!');
                            location.reload();
                        } else {
                            alert('Lỗi khi cập nhật.');
                        }
                    }
                });
            });

            // Handle delete KPI
            $(document).on('click', '.delete-btn', function() {
                var kpiId = $(this).data('id');
                if (confirm('Bạn có muốn xóa KPI này?')) {
                    $.ajax({
                        url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_namekpi.php', // Update with correct path
                        method: 'POST',
                        data: {
                            action: 'delete',
                            kpi_id: kpiId
                        },
                        success: function(response) {
                            if (response == 1) {
                                alert('Xóa KPI thành công!');
                                location.reload();
                            } else {
                                alert('Lỗi xóa KPI.');
                            }
                        }
                    });
                }
            });

            // Handle add KPI
            $(document).on('click', '#addKpi', function() {
                var departmentId = $('#department_id').val();
                var kpiName = $('#add_kpi_name').val();
                var kpiDescription = $('#add_kpi_description').val();

                // Log the values to the console
                console.log("Add KPI - Department ID:", departmentId);
                console.log("Add KPI - KPI Name:", kpiName);
                console.log("Add KPI - KPI Description:", kpiDescription);

                if (departmentId.trim() === '' || kpiName.trim() === '') {
                    alert('Department and KPI Name cannot be empty.');
                    return;
                }

                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_namekpi.php', // Update with correct path
                    method: 'POST',
                    data: {
                        action: 'add',
                        department_id: departmentId,
                        kpi_name: kpiName,
                        kpi_description: kpiDescription
                    },
                    success: function(response) {
                        console.log("Add KPI Response:", response);
                        if (response == 1) {
                            alert('Thêm KPI thành công!');
                            location.reload();
                        } else {
                            alert('Lỗi thêm KPI.');
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