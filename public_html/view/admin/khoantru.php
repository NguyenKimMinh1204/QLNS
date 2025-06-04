<?php
include '../layout/auth.php';
?>
<?php
include '../../controller/class/controller_khoantru.php';
$a=new AttendanceReport();
$departments = $a->getDepartments(); // Fetch departments

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
    <title>Quản lý phòng ban</title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                    <a href="#">Tính các khoản trừ</a>

                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <h2>Tính các khoản trừ</h2>
                    </div>

                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <form method="POST" action="" class="form-inline mb-3">
                            <div class="form-group">
                                <label for="month">Tháng:</label>
                                <select name="month" id="month" class="form-control" required>
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                    <option value="<?php echo $m; ?>" <?php echo ($m == $month) ? 'selected' : ''; ?>>
                                        <?php echo $m; ?>
                                    </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="year">Năm:</label>
                                <select name="year" id="year" class="form-control" required>
                                    <?php for ($y = $currentYear - 3; $y <= $currentYear+1; $y++): ?>
                                    <option value="<?php echo $y; ?>" <?php echo ($y == $year) ? 'selected' : ''; ?>>
                                        <?php echo $y; ?>
                                    </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="department">Phòng ban:</label>
                                <select name="department_id" id="department" class="form-control">
                                    <option value="">Tất cả</option> <!-- Option for all departments -->
                                    <?php foreach ($departments as $department): ?>
                                    <option value="<?php echo $department['id']; ?>">
                                        <?php echo htmlspecialchars($department['department_name']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Lọc</button>
                        </form>
                    </div>

                </div>
                <div class="row">

                    <div class="col-sm-12"><?php
                
                     $department_id = isset($_POST['department_id']) ? $_POST['department_id'] : null; // Get department ID from POST
                     $a->getAttendanceDeductions($month,$year,$department_id);?></div>
                </div>




                <!-- Modal -->
                <div class="modal fade" id="deductionModal" tabindex="-1" role="dialog"
                    aria-labelledby="deductionModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        d <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deductionModalLabel">Chi tiết các khoản trừ</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Employee Info -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <input type="hidden" id="user_id" name="user_id">
                                        <input type="hidden" id="total_deduction" name="total_deduction">
                                        <input type="hidden" id="date" name="date">
                                        <p><strong>Họ tên:</strong> <span id="employeeName"></span></p>
                                        <p><strong>Mã nhân viên:</strong> <span id="employeeId"></span></p>
                                        <p><strong>Tháng/Năm:</strong> <span id="deductionMonthYear"></span></p>
                                    </div>
                                </div>

                                <!-- Left Table: Leave Days -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Ngày</th>
                                                    <th>Giá trị trừ</th>
                                                </tr>
                                            </thead>
                                            <tbody id="leaveDaysTable">
                                                <!-- Dynamic rows will be inserted here -->
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Right Table: Late Penalties -->
                                    <div class="col-md-6">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>STT</th>
                                                    <th>Giờ vào</th>
                                                    <th>Giờ ra</th>
                                                    <th>Thời gian trễ</th>
                                                    <th>Phạt tiền</th>
                                                </tr>
                                            </thead>
                                            <tbody id="latePenaltiesTable">
                                                <!-- Dynamic rows will be inserted here -->
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4"><strong>Tổng phạt</strong></td>
                                                    <td id="totalLatePenalty"></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <!-- Total Monthly Penalty -->
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <p id="totalMonthlyPenalty" class="text-right"><strong>Tổng phạt tháng:
                                            </strong></p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                <button type="button" class="btn btn-primary" id="tinhkhoantru">Tính các khoản
                                    trừ</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- New Modal Structure for Tính các khoản trừ -->
                <div class="modal fade" id="deductionCalculationModal" tabindex="-1" role="dialog"
                    aria-labelledby="deductionCalculationModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deductionCalculationModalLabel">Chi Tiết Tính Khoản Trừ</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <label for="calcEmployeeName">Tên Nhân Viên:</label>
                                        <input type="text" class="form-control" id="calcEmployeeName" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="calcTotalDeduction">Tổng Khoản Trừ:</label>
                                        <input type="text" class="form-control" id="calcTotalDeduction" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="calcDeductionMonth">Tháng Tính Trừ Tiền:</label>
                                        <input type="text" class="form-control" id="calcDeductionMonth" readonly>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="calculateBonus">Tính các khoản
                                    trừ</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                // $(document).ready(function() {
                //     $('#deductionCalculationModal').on('show.bs.modal', function(event) {
                //         // Get the button that triggered the modal
                //         const button = $(event.relatedTarget);

                //         // Extract info from data-* attributes
                //         const userId = button.data('user-id');
                //         const totalDeduction = button.data('total-deduction');
                //         const date = button.data('date');
                //         const employeeName = button.data('employee-name');

                //         // Update the modal's content using .val() for input fields
                //         $('#calcEmployeeName').val(employeeName);
                //         $('#calcTotalDeduction').val(totalDeduction);
                //         $('#calcDeductionMonth').val(date);
                //     });
                //     // Handle the "Tính Lương" button click inside the new modal
                //     $('#calculateSalary').on('click', function() {
                //         // Logic to calculate salary or perform any action
                //         alert('Tính lương cho nhân viên!');
                //         // Close the modal after action
                //         $('#deductionCalculationModal').modal('hide');
                //     });
                // });
                </script>
            </div>


            <script>
            $(document).ready(function() {
                $('.table').DataTable();
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
    <script>
    // $(document).ready(function() {
    //     // Handle the "Tính các khoản trừ" button click
    //     $('button.btn-success').on('click', function() {
    //         // Get data from the button's data attributes
    //         const userId = $(this).data('user-id');
    //         const totalDeduction = $(this).data('total-deduction');
    //         const date = $(this).data('date');
    //         const employeeName = $(this).data('employee-name');

    //         // Populate the modal with the data
    //         $('#employeeName').text(employeeName);
    //         $('#totalDeduction').text(totalDeduction);
    //         $('#deductionMonth').text(date);

    //         // Open the modal
    //         $('#deductionModal').modal('show');
    //     });

    //     // Handle the "Tính Lương" button click inside the modal
    //     $('#calculateSalary').on('click', function() {
    //         // Logic to calculate salary or perform any action
    //         alert('Tính lương cho nhân viên!');
    //         // Close the modal after action
    //         $('#deductionModal').modal('hide');
    //     });
    // });
    </script>
    <script>
    function setUserId(userId) {
        const month = <?php echo $month; ?>; // Example month
        const year = <?php echo $year; ?>; // Example year

        $.ajax({
            url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_khoantru.php',
            method: 'POST',
            data: {
                action: 'getDeductionDetails',
                user_idd: userId,
                month: month,
                year: year
            },
            success: function(response) {
                const data = JSON.parse(response);

                // Set the values in the input fields
                $('#user_id').val(userId);
                // Assuming totalDeduction is part of the response
                $('#date').val(`${year}-${month}-05`);

                // Populate leave days table
                const leaveDaysTable = $('#leaveDaysTable');
                leaveDaysTable.empty(); // Clear existing rows

                // Leave days with permission
                const leaveDaysWithPermission = data.leaveDaysWithPermission;
                const excessLeaveDays = Math.max(0, leaveDaysWithPermission.length - 1);
                if (leaveDaysWithPermission.length > 0) {
                    leaveDaysTable.append(`<tr>
                        <td rowspan="${leaveDaysWithPermission.length}">Ngày nghỉ có phép</td>
                        <td>${leaveDaysWithPermission[0]}</td>
                        <td>${excessLeaveDays > 0 ? 200000 : 0}</td>
                    </tr>`);
                    for (let i = 1; i < leaveDaysWithPermission.length; i++) {
                        leaveDaysTable.append(`<tr>
                            <td>${leaveDaysWithPermission[i]}</td>
                            <td>${i >= 2 ? 200000 : 0}</td>
                        </tr>`);
                    }
                }

                // Unapproved leave days
                const unapprovedLeaveDays = data.unapprovedLeaveDays;
                if (unapprovedLeaveDays.length > 0) {
                    leaveDaysTable.append(`<tr>
                        <td rowspan="${unapprovedLeaveDays.length}">Ngày nghỉ không phép</td>
                        <td>${unapprovedLeaveDays[0]}</td>
                        <td>${200000}</td>
                    </tr>`);
                    for (let i = 1; i < unapprovedLeaveDays.length; i++) {
                        leaveDaysTable.append(`<tr>
                            <td>${unapprovedLeaveDays[i]}</td>
                            <td>${200000}</td>
                        </tr>`);
                    }
                }

                // Total deductions
                const totalWithPermission = excessLeaveDays * 200000;
                const totalWithoutPermission = unapprovedLeaveDays.length * 200000;
                const totalLeaveDeduction = totalWithPermission + totalWithoutPermission;

                leaveDaysTable.append(`<tr>
                    <td>Tổng có phép</td>
                    <td></td>
                    <td>${totalWithPermission ? totalWithPermission : 0}</td>
                </tr>`);
                leaveDaysTable.append(`<tr>
                    <td>Tổng không phép</td>
                    <td></td>
                    <td>${totalWithoutPermission ? totalWithoutPermission : 0}</td>
                </tr>`);
                leaveDaysTable.append(`<tr>
                    <td>Tổng giá trị</td>
                    <td></td>
                    <td>${totalLeaveDeduction ? totalLeaveDeduction:0}</td>
                </tr>`);

                // Populate late penalties table
                const tableBody = $('#latePenaltiesTable');
                tableBody.empty(); // Clear existing rows

                let totalPenalty = 0;
                data.attendanceDeductions.forEach((deduction, index) => {
                    const row = `<tr>
                        <td>${index + 1}</td>
                        <td>${deduction.clock_in_time}</td>
                        <td>${deduction.clock_out_time}</td>
                        <td>${deduction.lateLeaveTime}</td>
                        <td>${deduction.totalDeduction}</td>
                    </tr>`;
                    tableBody.append(row);
                    totalPenalty += parseInt(deduction.totalDeduction);
                });

                $('#totalLatePenalty').text(totalPenalty);

                // Calculate total penalty for the month
                var totalMonthlyPenaltysum = totalLeaveDeduction + totalPenalty;
                totalMonthlyPenaltysum ? totalMonthlyPenaltysum : 0;
                $('#total_deduction').val(totalMonthlyPenaltysum);
                $('#totalMonthlyPenalty').html(
                    `<strong>Tổng phạt tháng ${month}/${year} là: ${totalMonthlyPenaltysum.toLocaleString('vi-VN')} VNĐ</strong>`
                );

                // Set employee info
                if (data.attendanceDeductions.length > 0) {
                    $('#employeeName').text(data.attendanceDeductions[0].full_name);
                    $('#employeeId').text(data.attendanceDeductions[0].manv);
                } else {
                    $('#employeeName').text('N/A');
                    $('#employeeId').text('N/A');
                }
                $('#deductionMonthYear').text(`${month}/${year}`);
            },
            error: function() {
                alert('Error fetching deduction details.');
            }
        });
    }
    </script>



    <script>
    // $(document).ready(function() {
    //     // Handle the "Tính Lương" button click inside the modal
    //     $('#tinhkhoantru').on('click', function() {
    //         // Get the necessary data
    //         const userId = $('#user_id').val();

    //         // Extract the totalMonthlyPenalty from the formatted string
    //         const totalMonthlyPenaltyText = $('#totalMonthlyPenalty').text();
    //         const totalMonthlyPenaltyMatch = totalMonthlyPenaltyText.match(/[\d,.]+/);
    //         const totalMonthlyPenalty = totalMonthlyPenaltyMatch ? parseInt(totalMonthlyPenaltyMatch[0]
    //             .replace(/\./g, '').replace(/,/g, '')) : 0;

    //         const monthYear = $('#deductionMonthYear').text().split('/');
    //         const month = monthYear[0];
    //         const year = monthYear[1];
    //         const transactionDate =
    //             `${year}-${month}-05`; // Assuming the transaction date is the 5th of the month
    //         console.log(userId);
    //         console.log(totalMonthlyPenalty);
    //         console.log(transactionDate);

    // Send an AJAX request to add the transaction
    // $.ajax({
    //     url: '../../controller/class/controller_khoantru.php',
    //     method: 'POST',
    //     data: {
    //         action: 'addTransaction',
    //         user_id: userId,
    //         amount: totalMonthlyPenalty,
    //         transaction_date: transactionDate
    //     },
    //     success: function(response) {
    //         if (response == 1) {
    //             alert('Thêm khoản trừ vi phạm thành công');
    //         } else {
    //             alert('Thêm khoản trừ vi phạm thất bại');
    //         }
    //         // Close the modal after action
    //         $('#deductionCalculationModal').modal('hide');
    //     },
    //     error: function() {
    //         alert('Lỗi khi gửi dữ liệu.');
    //     }
    // });
    // });
    // });
    </script>
    <script>
    $(document).ready(function() {
        // Handle the "Tính Lương" button click inside the modal
        $('#tinhkhoantru').on('click', function() {
            // Retrieve values from the input fields
            const userId = $('#user_id').val();
            const totalDeduction = $('#total_deduction').val();
            const date = $('#date').val();

            console.log('User ID:', userId); // Debugging: Check the userId value
            console.log('Total Deduction:',
                totalDeduction); // Debugging: Check the totalDeduction value
            console.log('Date:', date); // Debugging: Check the date value

            if (!userId || !totalDeduction || !date) {
                alert('Missing data. Please ensure all fields are filled.');
                return;
            }

            // Send an AJAX request to add the transaction
            $.ajax({
                url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_khoantru.php',
                method: 'POST',
                data: {
                    action: 'addTransaction',
                    user_idd: userId,
                    amount: totalDeduction,
                    transaction_date: date
                },
                success: function(response) {
                    console.log('response',
                        response);
                    if (response == 1) {
                        alert('Thêm khoản trừ vi phạm thành công');
                    } else if (response == 2) {
                        alert('Tháng này đã tính các khoản rừ rồi');
                    } else {
                        alert('Thêm khoản trừ vi phạm thất bại');
                    }
                    // Close the modal after action
                    $('#deductionCalculationModal').modal('hide');
                },
                error: function() {
                    alert('Lỗi khi gửi dữ liệu.');
                }
            });
        });

        // Store the button that triggered the modal
        $('#deductionModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget); // Button that triggered the modal
            console.log('Button triggering modal:', button); // Debugging
            console.log('Data attributes:', button.data()); // Debugging

            $(this).data('triggeredBy', button); // Store the button in the modal's data
        });
    });
    </script>
</body>

</html>

</html>

</html>

</html>