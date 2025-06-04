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
        case 'add':
            // Kiểm tra các tham số cần thiết
            if (isset($_POST['kpi_lib_id'], $_POST['department_id'], $_POST['assigned_value'], $_POST['due_date'])) {
                $kpi_lib_id = $_POST['kpi_lib_id'];
                $department_id = $_POST['department_id'];
                $assigned_value = $_POST['assigned_value'];
                $assigned_date = $_POST['assigned_date'];
                $due_date = $_POST['due_date'];
               
                if ($department_id == 5) {
                    // Gọi hàm thêm KPI
                     $goalBasedIncentive = $_POST['kpi_bonus'];
                    $result = $kpiController->them_kpiIT($kpi_lib_id, $department_id, $assigned_value, $assigned_date, $due_date, $goalBasedIncentive);
                } else {
                    $result = $kpiController->them_kpi($kpi_lib_id, $department_id, $assigned_value, $assigned_date, $due_date);
                }
                echo $result ? 1 : 0; // Trả về 1 nếu thành công, 0 nếu thất bại
            } else {
                echo 0; // Trả về 0 nếu thiếu tham số
            }
            break;

        case 'update':
            // Kiểm tra các tham số cần thiết
            if (isset($_POST['dept_kpi_id'], $_POST['assigned_value'], $_POST['due_date'])) {
                $dept_kpi_id = $_POST['dept_kpi_id'];
                $assigned_value = $_POST['assigned_value'];
                $due_date = $_POST['due_date'];

                // Gọi hàm sửa KPI
                $result = $kpiController->sua_kpi($dept_kpi_id, $assigned_value, $due_date);
                echo $result ? 1 : 0; // Trả về 1 nếu thành công, 0 nếu thất bại
            } else {
                echo 0; // Trả về 0 nếu thiếu tham số
            }
            break;

        case 'delete':
            // Kiểm tra tham số cần thiết
            if (isset($_POST['dept_kpi_id'])) {
                $dept_kpi_id = $_POST['dept_kpi_id'];

                // Gọi hàm xóa KPI
                $result = $kpiController->xoa_kpi($dept_kpi_id);
                echo $result ? 1 : 0; // Trả về 1 nếu thành công, 0 nếu thất bại
            } else {
                echo 0; // Trả về 0 nếu thiếu tham số
            }
            break;

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
// }  $kpi_lib_id = 28;
//                 $department_id = 5;
//                 $assigned_value =100;
//                 $due_date = '2024-10-04 08:20:00';
//                 $goalBasedIncentive = 10000000;
                
//                     // Gọi hàm thêm KPI
                    
//                     $result = $kpiController->them_kpiIT($kpi_lib_id, $department_id, $assigned_value, $due_date, $goalBasedIncentive);
//                 if ($result==1) {echo 'success';
//                 }else {
//                     echo 'fail';
//                 }
?>