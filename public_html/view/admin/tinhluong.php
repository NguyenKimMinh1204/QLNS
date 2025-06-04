<?php
include '../layout/auth.php';
?>
<?php
include '../../controller/class/class_tinhluong_nv.php';
$a=new tinhluong_nv();


?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quản lý kpi phòng</title>
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
    <style>
    .breadcrumb {
        border-radius: 0;
        padding: 27px 10px;
        background: #e9ecf2;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        margin: 0;
    }

    .table th {
        background-color: #6a329f;
        color: #fff;
        text-align: center;
    }

    .highlight {
        background-color: #c6f3f7;
    }

    .total {
        background-color: #6a329f;
        color: #fff;
        font-weight: bold;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header img {
        width: 100px;
        margin-bottom: 10px;
    }

    .header h1 {
        font-size: 24px;
        margin: 0;
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
                        $month = $_POST['month'] ?? null;
                        $year = $_POST['year'] ?? null;
                        $a->tinhluong_nv($department_id,$month,$year);
                        // Load KPIs based on filters
                  
                        ?>

                    </div>
                </div>




                <script>
                $(document).ready(function() {
                    $('.table').DataTable();
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
                            url: 'http://localhost/KLTN/controller/class/controller_xem_kpi.php',
                            method: 'POST',
                            data: {
                                action: 'fetch_kpis_by_department',
                                department_id: departmentId
                            },
                            success: function(data) {
                                // Cập nht các lựa chọn KPI
                                $('#kpi_id').html(data);
                            }
                        });
                    });
                    // Xử lý sự kiện nhấn nút Thêm KPI
                    $('#addKPIDepartment').on('click', function() {
                        var departmentId1 = $('#department_id1').val();
                        var kpiId = $('#kpi_id').val();
                        var assignedValue = $('#assigned_value').val();
                        var dueDate = $('#due_date').val();
                        var kpiBonus = $('#kpi_bonus').val(); // Get the KPI bonus value
                        console.log('Department ID:', departmentId1);
                        console.log('KPI ID:', kpiId);
                        console.log('Assigned Value:', assignedValue);
                        console.log('Due Date:', dueDate);
                        console.log('KPI Bonus:', kpiBonus);

                        if (!departmentId1 || !kpiId || !assignedValue || !dueDate) {
                            alert('Please fill in all required fields.');
                            return;
                        }

                        // Prepare data for AJAX request
                        var data = {
                            action: 'add',
                            kpi_lib_id: kpiId,
                            department_id: departmentId1,
                            assigned_value: assignedValue,
                            due_date: dueDate
                        };

                        // Include KPI bonus if department_id1 is 5
                        if (departmentId1 == '5') {
                            data.kpi_bonus = kpiBonus;
                        }

                        // Send AJAX request to add KPI
                        $.ajax({
                            url: 'http://localhost/KLTN/controller/class/controller_kpi_phong.php',
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
                                url: 'http://localhost/KLTN/controller/class/controller_kpi_phong.php',
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

    <!-- Add Transaction Modal -->
    <div id="addTransactionModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Transaction</h4>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


    <script>
    $(document).ready(function() {
        // Event listener for the "Thêm thưởng" button
        $(document).on('click', '.calculate-bonus-btn', function() {
            console.log('Button clicked');
            // Get data attributes from the button
            var userId = $(this).data('user_id');
            var totalBonus = $(this).data('totalbonus');
            var date = $(this).data('date');

            // Populate the modal fields with the data
            $('#user_id').val(userId);
            $('#amount').val(totalBonus);
            $('#transaction_date').val(date);

            // Show the modal
            $('#addTransactionModal').modal('show');
        });
    });

    $(document).ready(function() {
        // Event listener for the "Tính Lương" button
        $('#calculateSalary').on('click', function() {
            // Collect the values from your form inputs
            var userId = $('#user_id').val();
            var transactionDate = $('#date').val();
            var amountPc = $('#phucap').val(); // Ph� cấp
            var amountThue = $('#thuecn').val(); // Thu�
            var amountBh = $('#baohiem').val(); // B�o hiểm
            var salary = $('#tongthunhapsauthue').val(); // Tổng thu nhập sau thuế

            // Ensure all values are captured correctly
            console.log('User ID:', userId);
            console.log('Transaction Date:', transactionDate);
            console.log('Phụ cấp:', amountPc);
            console.log('Thuế:', amountThue);
            console.log('Bảo hiểm:', amountBh);
            console.log('Tổng thu nhập sau thuế:', salary);

            // AJAX request to calculate salary
            $.ajax({
                url: '../../controller/class/controller_tinhluong_nv.php', // Adjust the path if necessary
                method: 'POST',
                data: {
                    action: 'addAllTransactions',
                    user_id: userId,
                    transaction_date: transactionDate,
                    amount_pc: amountPc,
                    amount_thue: amountThue,
                    amount_bh: amountBh,
                    salary: salary
                },
                success: function(response) {
                    // Handle the response code
                    switch (parseInt(response)) {
                        case 5:
                            alert('Lương đã được thêm.');
                            break;
                        case 1:
                            alert('Thêm lương thành công!');
                            break;
                        case 2:
                            alert('Phụ cấp đã được thêm.');
                            break;
                        case 3:
                            alert('Thuế đã được thêm.');
                            break;
                        case 4:
                            alert('Bảo hiểm đã được thêm.');
                            break;

                        default:
                            alert('Lỗi khi thêm lương');
                            break;
                    }
                    $('#addTransactionModal').modal('hide'); // Hide the modal
                    location.reload(); // Reload the page to see the changes
                },
                error: function() {
                    alert('An error occurred while adding the transactions.');
                }
            });
        });
    });
    </script>
    <!-- Modal -->
    <div id="salarySlipModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Phiếu Lương</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <div class="header">
                            <img src="../../assets/img/adsagency-logo-2.svg" alt="AdsAgency Logo">
                            <h1>PHIẾU LƯƠNG THÁNG <?php echo $month.'/'.$year ;?></h1>
                        </div>
                        <table class="table table-bordered">
                            <input type="hidden" id="user_id" name="user_id">
                            <input type="hidden" id="date" name="date">

                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Nội dung</th>
                                    <th>Giá trị</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Mã nhân viên</td>
                                    <td><input type="text" id="manv" name="manv"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Họ và tên nhân viên</td>
                                    <td><input type="text" id="fullname" name="fullname"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Email</td>
                                    <td><input type="text" id="email" name="email"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Chức danh công việc</td>
                                    <td><input type="text" id="rolename" name="rolename"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Phòng</td>
                                    <td><input type="text" id="department_name" name="department_name"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Lương chính thức</td>
                                    <td><input type="text" id="luongchinh" name="luongchinh"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>7</td>
                                    <td>Ngày công tiêu chuẩn</td>
                                    <td><input type="text" id="ngaycongtieuchuan" name="ngaycongtieuchuan"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>8</td>
                                    <td>Ngày công thực tế</td>
                                    <td><input type="text" id="ngaycongthucte" name="ngaycongthucte"></td>
                                    <td></td>
                                </tr>
                                <tr class="highlight">
                                    <td>9</td>
                                    <td>Lương theo ngày công LVTT</td>
                                    <td><input type="text" id="luongngaycong" name="luongngaycong"></td>
                                    <td>[1]</td>
                                </tr>
                                <tr>
                                    <td>10</td>
                                    <td>Thưởng</td>
                                    <td><input type="text" id="thuong" name="thuong"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>11</td>
                                    <td>Phụ cấp</td>
                                    <td><input type="text" id="phucap" name="phucap"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>12</td>
                                    <td>Thu nhập khác</td>
                                    <td><input type="text" id="thunhapkhac" name="thunhapkhac"></td>
                                    <td></td>
                                </tr>
                                <tr class="highlight">
                                    <td>13</td>
                                    <td>Tổng Phụ cấp + hoa hồng</td>
                                    <td><input type="text" id="tongphucaphh" name="tongphucaphh"></td>
                                    <td>[2]</td>
                                </tr>
                                <tr class="highlight">
                                    <td>14</td>
                                    <td>Tổng thu nhập</td>
                                    <td><input type="text" id="tongthunhap" name="tongthunhap"></td>
                                    <td>[3] =[1]+[2]</td>
                                </tr>

                                <tr>
                                    <td>16</td>
                                    <td>Vi phạm</td>
                                    <td><input type="text" id="phamvi" name="phamvi"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>17</td>
                                    <td>Bảo hiểm</td>
                                    <td><input type="text" id="baohiem" name="baohiem"></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>18</td>
                                    <td>Thuế thu nhập cá nhân</td>
                                    <td><input type="text" id="thuecn" name="thuecn"></td>
                                    <td></td>
                                </tr>
                                <tr class="highlight">
                                    <td>19</td>
                                    <td>Tổng các khoản trừ</td>
                                    <td><input type="text" id="tongcackhoantru" name="tongcackhoantru"></td>
                                    <td>[4]</td>
                                </tr>


                                <tr class="highlight">
                                    <td>20</td>
                                    <td>Tổng thu nhập sau thuế</td>
                                    <td><input type="text" id="tongthunhapsauthue" name="tongthunhapsauthue"></td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="calculateSalary">Tính Lương</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
    $(document).ready(function() {
        // Event listener for the "Xem chi tiết" button
        $(document).on('click', '#xemluong', function() {
            // Log the data attributes to the console
            console.log('User ID:', $(this).data('user_id'));
            console.log('Full Name:', $(this).data('fullname'));
            console.log('Employee Code:', $(this).data('manv'));
            console.log('Email:', $(this).data('email'));
            console.log('Role Name:', $(this).data('rolename'));
            console.log('Department Name:', $(this).data('department_name'));
            console.log('Base Salary:', $(this).data('luongchinh'));
            console.log('Standard Work Days:', $(this).data('ngaycongtieuchuan'));
            console.log('Actual Work Days Salary:', $(this).data('luongngaycong'));
            console.log('Bonus:', $(this).data('thuong'));
            console.log('Allowance:', $(this).data('phucap'));
            console.log('Penalty:', $(this).data('phat'));
            console.log('Other Income:', $(this).data('thunhapkhac'));
            console.log('Total Allowances:', $(this).data('tongphucaphh'));
            console.log('Total Income:', $(this).data('tongthunhap'));
            console.log('Insurance:', $(this).data('baohiem'));
            console.log('Total Deductions:', $(this).data('tongcackhoantru'));
            console.log('Income Before Tax:', $(this).data('thunhaptruocthue'));
            console.log('Personal Income Tax:', $(this).data('thuethunhapcanhan'));
            console.log('Net Income:', $(this).data('thunhapsauthue'));

            // Set the values of the inputs in the modal
            $('#date').val($(this).data('date'));
            $('#user_id').val($(this).data('user_id'));
            $('#manv').val($(this).data('manv'));
            $('#date').val($(this).data('date'));
            $('#fullname').val($(this).data('fullname'));
            $('#email').val($(this).data('email'));
            $('#rolename').val($(this).data('rolename'));
            $('#department_name').val($(this).data('department_name'));
            $('#luongchinh').val($(this).data('luongchinh'));
            $('#ngaycongtieuchuan').val($(this).data('ngaycongtieuchuan'));
            $('#ngaycongthucte').val($(this).data('ngaycongthucte'));
            $('#luongngaycong').val($(this).data('luongngaycong'));
            $('#thuong').val($(this).data('thuong'));
            $('#phucap').val($(this).data('phucap'));
            $('#thunhapkhac').val($(this).data('thunhapkhac'));
            $('#phamvi').val($(this).data('phat'));
            $('#tongphucaphh').val($(this).data('tongphucaphh'));
            $('#tongthunhap').val($(this).data('tongthunhap'));
            $('#baohiem').val($(this).data('baohiem'));
            $('#tongcackhoantru').val($(this).data('tongcackhoantru'));
            $('#thunhaptruocthue').val($(this).data('thunhaptruocthue'));
            $('#thuecn').val($(this).data('thuethunhapcanhan'));
            $('#tongthunhapsauthue').val($(this).data('thunhapsauthue'));
        });
    });
    </script>
</body>



</html>