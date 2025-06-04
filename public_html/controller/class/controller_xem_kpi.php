<?php
include 'clas_kpi_phong.php'; // Kết nối với lớp kpi_depp

$kpiController = new kpi_depp();
// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//     $action = $_GET['action'] ?? '';

//     switch ($action) {
//         case 'fetch_kpis_by_department':
//             if (isset($_GET['department_id'])) {
//                 $departmentId = $_GET['department_id'];

//                 // Giả định: $kpiController là controller chứa logic lấy dữ liệu KPI
//                 $kpis = $kpiController->getKPIsByDepartment($departmentId);

//                 if (!empty($kpis)) {
//                     foreach ($kpis as $kpi) {
//                         echo '<option value="' . htmlspecialchars($kpi['kpi_lib_id']) . '" data-description="' . htmlspecialchars($kpi['kpi_description']) . '">' . htmlspecialchars($kpi['kpi_name']) . '</option>';
//                     }
//                 } else {
//                     echo '<option value="">Không có KPI nào</option>';
//                 }
//             } else {
//                 echo '<option value="">ID phòng ban không hợp lệ</option>';
//             }
//             break;

//         default:
//             echo '<option value="">Action không hợp lệ</option>';
//             break;
//     }
// }
// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//     header("Content-Type: application/json; charset=UTF-8"); // Đặt header trả về JSON
//     $action = $_GET['action'] ?? '';

//     switch ($action) {
//         case 'fetch_kpis_by_department':
//             if (isset($_GET['department_id'])) {
//                 $departmentId = $_GET['department_id'];

//                 // Giả định: $kpiController là controller chứa logic lấy dữ liệu KPI
//                 $kpis = $kpiController->getKPIsByDepartment($departmentId);

//                 if (!empty($kpis)) {
//                     // Trả về dữ liệu KPI dạng JSON
//                     echo json_encode([
//                         'status' => 'success',
//                         'data' => $kpis
//                     ]);
//                 } else {
//                     echo json_encode([
//                         'status' => 'error',
//                         'message' => 'Không có KPI nào'
//                     ]);
//                 }
//             } else {
//                 echo json_encode([
//                     'status' => 'error',
//                     'message' => 'ID phòng ban không hợp lệ'
//                 ]);
//             }
//             break;

//         default:
//             echo json_encode([
//                 'status' => 'error',
//                 'message' => 'Action không hợp lệ'
//             ]);
//             break;
//     }
// }


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    switch ($action) {
      

        case 'fetch_kpis_by_department':
            // Kiểm tra tham số cần thiết
            if (isset($_POST['department_id'])) {
                $departmentId = $_POST['department_id'];
                $kpis = $kpiController->getKPIsByDepartment($departmentId);

                if (!empty($kpis)) {
                    foreach ($kpis as $kpi) {
                        echo '<option value="' . htmlspecialchars($kpi['kpi_lib_id']) . '" data-description="' . htmlspecialchars($kpi['kpi_description']) . '">' . htmlspecialchars($kpi['kpi_name']) . '</option>';
                    }
                } else {
                    echo '<option value="">Không có KPI nào </option>';
                }
            } else {
                echo '<option value="">ID phòng ban không hợp lệ</option>'; // Trả về thông báo nếu thiếu tham số
            }
            break;

        default:
            echo 0; // Trả về 0 nếu action không hợp lệ
            break;
    }
}
?>