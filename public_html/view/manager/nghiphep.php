<?php
include '../layout_manager/auth.php';
include('../../controller/class/class_nghiphep.php');
$user_id = $_SESSION['user_id'];
// Giả sử có department_id trong session
$a = new nghiphep();
$department_id = $a->getDepartmentId($user_id); 
// Xử lý khi form được submit
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_leave"])) {
    // Kết hợp ngày và giờ
    $start_datetime = $_POST['start_date'] . ' ' . $_POST['start_time'];
    $end_datetime = $_POST['end_date'] . ' ' . $_POST['end_time'];
    
    $type = ($_POST['type'] == '1') ? 'nghi_phep' : 'di_tre';
    
    $result = $a->addLeaveRequest(
        $user_id,
        $department_id,
        $type,
        $_POST['reason'],
        $start_datetime,
        $end_datetime
    );
    
    echo "<script>alert('" . $result . "');</script>";
    echo "<script>window.location.href = window.location.href;</script>";
}

// Lấy lịch sử đơn xin phép
$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$leave_history = $a->getLeaveHistory($user_id, $month, $year);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Xin nghỉ phép</title>

    <link href="../../assets/employee/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/employee/css/datepicker3.css" rel="stylesheet">
    <link href="../../assets/employee/css/styles.css" rel="stylesheet">
    <link href="../../assets/css/employee/info.css" rel="stylesheet">



    <!--Icons-->
    <script src="../../assets/employee/js/lumino.glyphs.js"></script>

    <!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

    <style>
    .breadcrumb {
        border-radius: 0;
        padding: 27px 10px;
        background: #e9ecf2;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        margin: 0;
    }

    .form-inline .form-control {
        margin-right: 10px;
    }

    .mb-3 {
        margin-bottom: 15px;
    }
    </style>

</head>

<body>
    <?php
    include ('../layout_manager/header.php');
    include ('../layout_manager/sidebar.php');
    ?>
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="index.php"><svg class="glyph stroked home">
                            <use xlink:href="#stroked-home"></use>
                        </svg></a></li>
                <li class="active">Nghỉ Phép</li>
            </ol>
        </div>
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Đơn Xin Nghỉ Phép</h3>
                </div>
                <div class="panel-body">
                    <!-- Button to open the modal -->
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#leaveRequestModal">
                        Gửi Đơn
                    </button>
                </div>
            </div>

            <!-- Modal structure -->
            <div class="modal fade" id="leaveRequestModal" tabindex="-1" role="dialog"
                aria-labelledby="leaveRequestModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="leaveRequestModalLabel">Đơn Xin Nghỉ Phép</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="" method="POST" class="form-horizontal">
                                <div class="form-group">
                                    <label>Loại Đơn</label>
                                    <select name="type" class="form-control" required>
                                        <option value="">-- Chọn loại đơn --</option>
                                        <option value="1">Nghỉ phép</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Lý Do</label>
                                    <textarea name="reason" class="form-control" rows="3" required></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Ngày Bắt Đầu</label>
                                    <input type="date" name="start_date" class="form-control" required>
                                    <input type="time" name="start_time" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label>Ngày Kết Thúc</label>
                                    <input type="date" name="end_date" class="form-control" required>
                                    <input type="time" name="end_time" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="submit_leave" class="btn btn-primary">Gửi Đơn</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hiển thị lịch sử đơn xin phép -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Lịch Sử Đơn Xin Phép</h3>
                </div>
                <div class="panel-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form class="form-inline" method="GET">
                                <select name="month" class="form-control mr-2">
                                    <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($month == $i ? 'selected' : '') ?>>
                                        Tháng <?= $i ?>
                                    </option>
                                    <?php endfor; ?>
                                </select>
                                <select name="year" class="form-control mr-2">
                                    <?php 
                                    $currentYear = date('Y');
                                    for ($i = $currentYear - 2; $i <= $currentYear; $i++): 
                                    ?>
                                    <option value="<?= $i ?>" <?= ($year == $i ? 'selected' : '') ?>>
                                        Năm <?= $i ?>
                                    </option>
                                    <?php endfor; ?>
                                </select>
                                <button type="submit" class="btn btn-primary">Lọc</button>
                            </form>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Loại Đơn</th>
                                <th>Lý Do</th>
                                <th>Ngày Bắt Đầu</th>
                                <th>Ngày Kết Thúc</th>
                                <th>Trạng Thái</th>
                                <th>Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stt = 1;
                            foreach ($leave_history as $leave) {
                                echo "<tr>";
                                echo "<td>" . $stt++ . "</td>";
                                echo "<td>" . ($leave['type'] == 'nghi_phep' ? 'Nghỉ phép' : 'Đi trễ') . "</td>";
                                echo "<td>" . $leave['reason'] . "</td>";
                                echo "<td>" . date('d/m/Y H:i', strtotime($leave['start_date'])) . "</td>";
                                echo "<td>" . date('d/m/Y H:i', strtotime($leave['end_date'])) . "</td>";
                                echo "<td>";
                                switch($leave['status']) {
                                    case 'pending':
                                        echo '<span class="label label-warning">Đang chờ</span>';
                                        break;
                                    case 'approved':
                                        echo '<span class="label label-success">Đã duyệt</span>';
                                        break;
                                    case 'rejected':
                                        echo '<span class="label label-danger">Từ chối</span>';
                                        break;
                                }
                                echo "</td>";
                                echo "<td><button class='btn btn-info' data-toggle='modal' data-target='#viewLeaveRequestModal' data-reason='{$leave['reason']}' data-start-date='{$leave['start_date']}' data-end-date='{$leave['end_date']}' data-type='{$leave['type']}'>Xem Chi Tiết</button></td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal to view leave request details -->
            <div class="modal fade" id="viewLeaveRequestModal" tabindex="-1" role="dialog"
                aria-labelledby="viewLeaveRequestModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="viewLeaveRequestModalLabel">Chi Tiết Đơn Xin Nghỉ Phép</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form>
                                <div class="form-group">
                                    <label>Loại Đơn</label>
                                    <input type="text" class="form-control" id="leaveType" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Lý Do</label>
                                    <textarea class="form-control" id="leaveReason" rows="3" readonly></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Ngày Bắt Đầu</label>
                                    <input type="text" class="form-control" id="leaveStartDate" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Ngày Kết Thúc</label>
                                    <input type="text" class="form-control" id="leaveEndDate" readonly>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/employee/js/jquery-1.11.1.min.js"></script>
    <script src="../../assets/employee/js/bootstrap.min.js"></script>
    <script>
    // Script to populate the modal with leave request details
    $('#viewLeaveRequestModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var reason = button.data('reason'); // Extract info from data-* attributes
        var startDate = button.data('start-date');
        var endDate = button.data('end-date');
        var type = button.data('type');

        // Update the modal's content
        var modal = $(this);
        modal.find('#leaveType').val(type == 'nghi_phep' ? 'Nghỉ phép' : 'Đi trễ');
        modal.find('#leaveReason').val(reason);
        modal.find('#leaveStartDate').val(startDate);
        modal.find('#leaveEndDate').val(endDate);
    });
    </script>
</body>

</html>