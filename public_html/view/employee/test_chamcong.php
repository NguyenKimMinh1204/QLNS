<?php
include '../layout_employee/auth.php';
include('../../controller/class/class_chamcong_e.php');
$a = new chamconge();
$user_id = $_SESSION['user_id'];

// Lấy địa chỉ IP Wi-Fi
$wifi_address = $a->getWiFiIPv4();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $ip_address = $_POST['ip_address'];
    $timestamp = $_POST['timestamp'];
    $attendance_id = $_POST['attendance_id'] ?? null;

    if ($action === 'clock_in') {
        $clockInResult = $a->clockInTest($user_id, $ip_address, $timestamp);
        echo json_encode($clockInResult);
    } elseif ($action === 'clock_out' && $attendance_id) {
        $clockOutResult = $a->clockOutTest($attendance_id, $ip_address, $timestamp);
        echo json_encode($clockOutResult);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Chấm Công</title>
    <link href="../../assets/employee/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/employee/css/datepicker3.css" rel="stylesheet">
    <link href="../../assets/employee/css/styles.css" rel="stylesheet">

    <!--Icons-->
    <script src="../../assets/employee/js/lumino.glyphs.js"></script>

    <!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<body>
    <div class="container">
        <h2>Test Chấm Công</h2>
        <form method="POST">
            <div class="form-group">
                <label for="action">Action:</label>
                <select name="action" id="action" class="form-control">
                    <option value="clock_in">Clock In</option>
                    <option value="clock_out">Clock Out</option>
                </select>
            </div>
            <div class="form-group">
                <label for="ip_address">IP Address:</label>
                <input type="text" name="ip_address" id="ip_address" class="form-control"
                    value="<?php echo $wifi_address; ?>" required>
            </div>
            <div class="form-group">
                <label for="timestamp">Timestamp:</label>
                <input type="datetime-local" name="timestamp" id="timestamp" class="form-control" required>
            </div>
            <div class="form-group" id="attendanceIdGroup" style="display: none;">
                <label for="attendance_id">Attendance ID:</label>
                <input type="number" name="attendance_id" id="attendance_id" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <script>
    document.getElementById('action').addEventListener('change', function() {
        const attendanceIdGroup = document.getElementById('attendanceIdGroup');
        if (this.value === 'clock_out') {
            attendanceIdGroup.style.display = 'block';
        } else {
            attendanceIdGroup.style.display = 'none';
        }
    });
    </script>
</body>

</html>