<?php
include '../layout/auth.php';
?>
<?php
include '../../controller/class/clas_kpi_phong.php';
$a=new kpi_depp();


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
                    <a href="#">Quản lý KPI phòng ban</a>

                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <h2>Quản lý KPI phòng</h2>
                    </div>
                </div>
                <div class="row mt-5">
                    <div class="col-sm-2"><button class="btn btn-success" data-toggle="modal"
                            data-target="#addKPIDepartmentModal">Thêm
                            KPI
                            phòng</button></div>
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



                <!-- Modal Add Department KPI -->
                <div class="modal fade" id="addKPIDepartmentModal" tabindex="-1" role="dialog"
                    aria-labelledby="addKPIDepartmentModalLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="addKPIDepartmentModalLabel">Thêm KPI phòng</h4>
                            </div>
                            <div class="modal-body">
                                <form id="addKPIDepartmentForm">
                                    <div class="form-group">
                                        <label for="department_id1">Chọn Phòng</label>
                                        <select class="form-control" id="department_id1" name="department_id1" required>
                                            <option value="" disabled selected>Chọn phòng ban</option>
                                            <?php
                                            $departmentss = $a->getDepartments(); // Fetch departments
                                            foreach ($departmentss as $departments) {
                                                echo '<option value="' . htmlspecialchars($departments['id']) . '">' . htmlspecialchars($departments['department_name']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="kpi_id">Danh sách kpi</label>
                                        <select class="form-control" id="kpi_id" name="kpi_id" required>
                                            <option value="">Chọn kpi</option>

                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="kpi_description">Mô tả KPI</label>
                                        <p id="kpi_description">Please select a KPI to view the description.</p>
                                    </div>
                                    <div class="form-group">
                                        <label for="assigned_value">Giá trị mục tiêu</label>
                                        <input type="number" class="form-control" id="assigned_value"
                                            name="assigned_value" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="assigned_date">Ngày bắt đầu</label>
                                        <input type="datetime-local" class="form-control" id="assigned_date"
                                            name="assigned_date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="due_date">Ngày đáo hạn</label>
                                        <input type="datetime-local" class="form-control" id="due_date" name="due_date"
                                            required>
                                    </div>

                                    <div class="form-group" id="kpi_bonus_field" name="kpi_bonus_field"
                                        style="display: none;">
                                        <label for="kpi_bonus">Thưởng KPI</label>
                                        <input type="text" id="kpi_bonus" name="kpi_bonus" class="form-control">
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="addKPIDepartment">Thêm
                                    KPI phòng</button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                document.getElementById('department_id1').addEventListener('change', function() {
                    var kpiBonusField = document.getElementById('kpi_bonus_field');
                    if (this.value == '5') {
                        kpiBonusField.style.display = 'block';
                    } else {
                        kpiBonusField.style.display = 'none';
                    }
                });
                </script>
                <script>
                $(document).ready(function() {
                    $('.table').DataTable();
                });
                </script>




                <!-- Edit KPI Modal -->
                <div class="modal fade" id="editKPIDepartmentModal" tabindex="-1" role="dialog"
                    aria-labelledby="editKPIDepartmentModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editKPIDepartmentModalLabel">Edit KPI</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="editKPIDepartmentForm">
                                    <input type="hidden" id="edit_dept_kpi_id" name="dept_kpi_id">
                                    <div class="form-group">
                                        <label for="edit_assigned_value">Assigned Value</label>
                                        <input type="number" class="form-control" id="edit_assigned_value"
                                            name="assigned_value" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_due_date">Due Date</label>
                                        <input type="datetime-local" class="form-control" id="edit_due_date"
                                            name="due_date" required>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="saveKPIChanges">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                // Handle the edit button click event

                $(document).on('click', '.edit-btn', function() {
                    var button = $(this);
                    var deptKpiId = button.data('dept-kpi-id');
                    var assignedValue = button.data('assigned-value');
                    var dueDate = button.data('due-date');

                    // Gán giá trị vào các trường trong modal
                    $('#edit_dept_kpi_id').val(deptKpiId);
                    $('#edit_assigned_value').val(assignedValue);
                    $('#edit_due_date').val(dueDate);

                    // Xuất ra console
                    console.log('Department KPI ID:', deptKpiId);
                    console.log('Assigned Value:', assignedValue);
                    console.log('Due Date:', dueDate);

                    // Hiện modal
                    $('#editKPIDepartmentModal').modal('show');
                });

                // Handle the save changes button click event
                $('#saveKPIChanges').on('click', function() {
                    var deptKpiId = $('#edit_dept_kpi_id').val();
                    var assignedValue = $('#edit_assigned_value').val();
                    var dueDate = $('#edit_due_date').val();

                    // Validate inputs
                    if (!deptKpiId || !assignedValue || !dueDate) {
                        alert('Vui lòng điền vào tất cả các trường bắt buộc.');
                        return;
                    }

                    // Make an AJAX request to update the KPI
                    $.ajax({
                        url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_kpi_phong.php', // Cập nhật với đường dẫn đến controller
                        method: 'POST',
                        data: {
                            action: 'update',
                            dept_kpi_id: deptKpiId,
                            assigned_value: assignedValue,
                            due_date: dueDate
                        },
                        success: function(response) {
                            if (response == 1) {
                                alert('Cập nhật KPI thành công!');
                                location.reload(); // Reload the page to see changes
                            } else {
                                alert('Lỗi khi cập nhật KPI.');
                            }
                        },
                        error: function() {
                            alert('Đã xảy ra lỗi khi cập nhật KPI.');
                        }
                    });
                });
                </script>
                <script>
                var id = 0;

                $(document).ready(function() {
                    // Xử lý thay đổi chọn KPI để hiển thị mô tả
                    $('#kpi_id').on('change', function() {
                        var selectedOption = this.options[this.selectedIndex];
                        var kpiDescription = selectedOption.getAttribute(
                            'data-description'); // Lấy mô tả từ thuộc tính data

                        // Cập nhật mô tả KPI trong modal
                        $('#kpi_description').text(kpiDescription);
                    });
                });
                $(document).ready(function() {
                    // Xử lý thay đổi phòng ban để lấy KPIs của phòng ban
                    $(document).on('change', '#department_id1', function() {
                        var departmentId = $(this).val();
                        id = departmentId
                        console.log('departmentId', departmentId)
                        // Fetch KPIs based on the selected department
                        $.ajax({
                            url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_xem_kpi.php',
                            method: 'POST',
                            data: {
                                action: 'fetch_kpis_by_department',
                                department_id: departmentId
                            },
                            success: function(data) {
                                // Cập nhật các lựa chọn KPI
                                $('#kpi_id').html(data);
                            }
                        });
                    });
                    // Xử lý sự kiện nhấn nút Thêm KPI
                    $('#addKPIDepartment').on('click', function() {
                        var departmentId1 = $('#department_id1').val();
                        var kpiId = $('#kpi_id').val();
                        var assignedValue = $('#assigned_value').val();
                        var assignedDate = $('#assigned_date').val();
                        var dueDate = $('#due_date').val();
                        var kpiBonus = $('#kpi_bonus').val(); // Get the KPI bonus value
                        console.log('Department ID:', departmentId1);
                        console.log('KPI ID:', kpiId);
                        console.log('Assigned Value:', assignedValue);
                        console.log('Due Date:', dueDate);
                        console.log('KPI Bonus:', kpiBonus);

                        if (!departmentId1 || !kpiId || !assignedValue || !assignedDate || !dueDate) {
                            alert('Please fill in all required fields.');
                            return;
                        }

                        // Prepare data for AJAX request
                        var data = {
                            action: 'add',
                            kpi_lib_id: kpiId,
                            department_id: departmentId1,
                            assigned_value: assignedValue,
                            assigned_date: assignedDate,
                            due_date: dueDate
                        };

                        // Include KPI bonus if department_id1 is 5
                        if (departmentId1 == '5') {
                            data.kpi_bonus = kpiBonus;
                        }

                        // Send AJAX request to add KPI
                        $.ajax({
                            url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_kpi_phong.php',
                            method: 'POST',
                            data: data,
                            success: function(response) {
                                if (response == 1) {
                                    alert('Thêm KPI thành công!');
                                    location.reload(); // Reload the page to see changes
                                } else {
                                    alert('Lỗi khi thêm KPI.');
                                }
                            },
                            error: function() {
                                alert('Đã xảy ra lỗi khi thêm KPI.');
                            }
                        });
                    });

                });


                $(document).ready(function() {
                    // Xử lý sự kiện nhấn nút Xóa KPI
                    $(document).on('click', '.delete-btn', function() {
                        var deptKpiId = $(this).data('id');

                        if (confirm('Bạn có muốn xóa KPI này không?')) {
                            // Gửi yêu cầu AJAX để xóa KPI
                            $.ajax({
                                url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_kpi_phong.php',
                                method: 'POST',
                                data: {
                                    action: 'delete',
                                    dept_kpi_id: deptKpiId
                                },
                                success: function(response) {
                                    if (response == 1) {
                                        alert('Xóa KPI thành công!');
                                        location
                                            .reload(); // Reload the page to see changes
                                    } else {
                                        alert('Lỗi khi xóa KPI.');
                                    }
                                },
                                error: function() {
                                    alert('Đã xảy ra lỗi khi xóa KPI.');
                                }
                            });
                        }
                    });
                });
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



</body>



</html>