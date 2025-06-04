<?php
include '../layout/auth.php';

include '../../controller/class/class_KPI.php';
$a=new kpi_dep();

// Kiểm tra xem có department_id được gửi từ yêu cầu POST không
$department_id = isset($_POST['department_id_ds']) ? $_POST['department_id_ds'] : null;
$user_id=$_SESSION['user_id'];

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quản lý KPI phòng</title>
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
            <div class="container mt-5 width1">
                <div class="row">
                    <h2>KPI department Management</h2>
                </div>
                <div class="row">
                    <button class="btn btn-success mt-3 add_css" data-toggle="modal"
                        data-target="#addKPIDepartmentModal">Add Department KPI</button>
                    <div class="col-sm-3">
                        <form method="post" action="" id="departmentForm" class="form-timkiem">
                            <div class="form-group">
                                <!-- <label for="department_id_ds">Chọn phòng ban</label> -->
                                <select class="form-control add_css" id="department_id_ds" name="department_id_ds"
                                    required onchange="this.form.submit()">
                                    <option value="0"
                                        <?php echo (!isset($_POST['department_id_ds']) || $_POST['department_id_ds'] == 0) ? 'selected' : ''; ?>>
                                        Tất cả phòng ban
                                    </option>
                                    <?php
                                            $departments = $a->getDepartments(); // Fetch departments
                                            foreach ($departments as $department) {
                                                $selected = (isset($_POST['department_id_ds']) && $_POST['department_id_ds'] == $department['id']) ? 'selected' : '';
                                                echo '<option value="' . htmlspecialchars($department['id']) . '" ' . $selected . '>' . htmlspecialchars($department['department_name']) . '</option>';
                                            }
                                        ?>
                                </select>
                            </div>
                        </form>
                    </div>



                </div>




            </div>
            <div class="container mt-5 hight1 width1">


                <?php 
               if($department_id===NULL || $department_id==0 ){
                $a->loadKPIDepartment();
               }else if($department_id!=NULL){
                $a->loadKPIDepartment_m($department_id);
               }
                
                

                

                    ?>




                <!-- Modal Add kpi dep -->

                <div class="modal fade" id="addKPIDepartmentModal" tabindex="-1" role="dialog"
                    aria-labelledby="addKPIDepartmentModalLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="addKPIDepartmentModalLabel">Add Department KPI</h4>
                            </div>
                            <div class="modal-body">
                                <form id="addKPIDepartmentForm">
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
                                        <label for="kpi_dept_name">KPI Name</label>
                                        <input type="text" class="form-control" id="kpi_dept_name" name="kpi_dept_name"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kpi_dept_description">Description</label>
                                        <textarea class="form-control" id="kpi_dept_description"
                                            name="kpi_dept_description" required></textarea>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="addKPIDepartment">Add
                                    Department KPI</button>
                            </div>
                        </div>
                    </div>
                </div>




                <!-- edit modal kpi dep -->
                <div class="modal fade" id="editKPIDepartmentModal" tabindex="-1" role="dialog"
                    aria-labelledby="editKPIDepartmentModalLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="editKPIDepartmentModalLabel">Edit Department KPI</h4>
                            </div>
                            <div class="modal-body">
                                <form id="editKPIDepartmentForm">
                                    <input type="hidden" name="edit_kpi_dept_id" id="edit_kpi_dept_id">
                                    <div class="form-group">
                                        <label for="edit_kpi_dept_name">KPI Name</label>
                                        <input type="text" class="form-control" id="edit_kpi_dept_name"
                                            name="edit_kpi_dept_name" required>
                                    </div>


                                    <div class="form-group">
                                        <label for="edit_kpi_dept_description">Description</label>
                                        <textarea class="form-control" id="edit_kpi_dept_description"
                                            name="edit_kpi_dept_description" required></textarea>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="saveKPIChanges">Save
                                    Changes</button>
                            </div>
                        </div>
                    </div>
                </div>


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
            <script type="text/javascript" charset="utf8"
                src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
            </script>
            <script>
            // Edit Department KPI Modal
            $('#editKPIDepartmentModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var id = button.data('id');
                var name = button.data('name');
                var description = button.data('description');



                var modal = $(this);
                modal.find('#edit_kpi_dept_id').val(id);
                modal.find('#edit_kpi_dept_name').val(name);
                modal.find('#edit_kpi_dept_description').val(description);

            });

            // Save changes for editing a KPI
            $(document).on('click', '#saveKPIChanges', function() {
                var kpiDeptId = $('#edit_kpi_dept_id').val();
                var kpiDeptName = $('#edit_kpi_dept_name').val();
                var kpiDeptDescription = $('#edit_kpi_dept_description').val();

                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_kpi.php',
                    method: 'POST',
                    data: {
                        action: 'update_department_kpi',
                        id: kpiDeptId,
                        name: kpiDeptName,
                        description: kpiDeptDescription
                    },
                    success: function(response) {
                        if (response == 1) {
                            alert('KPI updated successfully!');
                            location.reload();
                        } else {
                            alert('Error updating KPI: ' + response);
                            location.reload();
                        }
                    }
                });
            });

            // Adding new department KPI
            $(document).on('click', '#addKPIDepartment', function() {
                var department_id = $('#department_id').val();
                var kpiDeptName = $('#kpi_dept_name').val();
                var kpiDeptDescription = $('#kpi_dept_description').val();

                if (department_id == 0 || kpiDeptName.trim() === '') {
                    alert('Please select a department and fill all fields.');
                    return;
                }

                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_kpi.php',
                    method: 'POST',
                    data: {
                        action: 'add_department_kpi',
                        name: kpiDeptName,
                        description: kpiDeptDescription,
                        department_id: department_id
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

            // Deleting a Department KPI
            // $(document).on('click', '.delete-kpi-btn', function() {
            //     var kpiDeptId = $(this).data('id');
            //     if (confirm('Are you sure you want to delete this KPI?')) {
            //         $.ajax({
            //             url: 'controller_kpi.php',
            //             method: 'POST',
            //             data: {
            //                 action: 'delete_department_kpi',
            //                 id: kpiDeptId
            //             },
            //             success: function(response) {
            //                 if (response == 1) {
            //                     alert('KPI deleted successfully!');
            //                     location.reload();
            //                 } else {
            //                     alert('Error deleting KPI.');
            //                 }
            //             }
            //         });
            //     }
            // });
            $(document).on('click', '.delete-kpi-btn', function() {
                var kpiDeptId = $(this).data('id');
                if (confirm('Are you sure you want to delete this KPI?')) {
                    $.ajax({
                        url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_kpi.php',
                        method: 'POST',
                        data: {
                            action: 'delete_department_kpi',
                            id: kpiDeptId
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
            </script>






        </div>

    </div>

    <?php 
            // include ("../../layout/main.php");
            include ("../layout/minisidebar.php");
            include ("../layout/footer.php");
        ?>


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