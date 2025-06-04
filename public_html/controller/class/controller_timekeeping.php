<?php
require '../../assets/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Include the class containing importTimekeepingData
include 'class_timekeeping.php';

// Initialize an instance of the timekeeping class
$p = new timekeeping();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    if ($action === 'export') {
        $user_id = $_POST['user_id'] ?? null;
        $monthYear = $_POST['monthYear'] ?? null;

        if ($user_id && $monthYear) {
            $data = $p->getTimekeepingData($user_id, $monthYear);
            $p->exportTimekeepingDataToExcel($data, $monthYear);
        } else {
            echo '<div class="alert alert-danger">Error: User ID and Month-Year are required.</div>';
        }
    } elseif ($action === 'import' && isset($_FILES['importFile'])) {
        $fileType = $_FILES['importFile']['type'];
        $fileSize = $_FILES['importFile']['size'];
        $allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'];

        if (!in_array($fileType, $allowedTypes) || $fileSize > 5 * 1024 * 1024) {
            echo 'Loại hoặc kích thước tệp không hợp lệ. Vui lòng tải lên tệp Excel hợp lệ.';
        } else {
            $filePath = $_FILES['importFile']['tmp_name'];
            $importResult = $p->importTimekeepingDataFromExcel($filePath);

            if ($importResult==1) {
                echo 'Dữ liệu chấm công được nhập thành công';
            } else {
                echo 'Không thể nhập dữ liệu chấm công.';
            }
        }
    } else {
        echo 'Hành động không hợp lệ hoặc thiếu tham số.';
    }
}
// Test data
// $employeeCode = '24676289';
// $clockInTime = '2024-10-08 12:45:00';
// $clockOutTime = '2024-10-08 17:21:00';
// try {
//     // Get user_id from employee code
//     $userId = $p->getUserIdFromEmployeeCode($employeeCode);
//     if (!$userId) {
//         throw new Exception("User ID not found for employee code: $employeeCode");
//     }

//     // Clock in
//     $attendanceId = $p->clockInTest($userId, $clockInTime);
//     echo "Clock-in successful. Attendance ID: $attendanceId\n";

//     // Clock out
//     $clockOutResult = $p->clockOutTest($attendanceId, $clockOutTime);
//     if ($clockOutResult['success']) {
//         echo "Clock-out successful. Shift ID: " . $clockOutResult['shift_id'] . "\n";
//     } else {
//         echo "Clock-out failed.\n";
//     }
// } catch (Exception $e) {
//     echo "Error: " . $e->getMessage() . "\n";
// }
?>