<?php
include ('../../controller/class/class_duyetkpi.php');
$p = new duyetkpim();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action']; 
    
    if ($action == 'duyet_employee_kpi_progress') {
        $emp_kpi_id = $_POST['emp_kpi_id'];
        $progress = $_POST['progress'];
        $log_id = $_POST['log_id'];
        $dept_kpi_id = $_POST['dept_kpi_id'];
        $result = $p->duyet_employee_kpi_progress($emp_kpi_id, $progress, $log_id, $dept_kpi_id);
        echo $result;
    } elseif ($action == 'tu_choi_employee_kpi_progress') {
        $log_id = $_POST['log_id'];
        $reason_reject = $_POST['reason_reject'];
        $result = $p->tu_choi_employee_kpi_progress($log_id, $reason_reject);
        echo $result;
    }
}

// // Test functions
// function test_update_kpi_progress_log_status($p) {
//     $log_id = 139; // Example log ID
//     $status = 'approved'; // Example status
//     $result = $p->update_kpi_progress_log_status($log_id, $status);
//     echo "Test update_kpi_progress_log_status: " . ($result == 1 ? "Success" : "Fail") . "\n";
// }

// function test_tu_choi_employee_kpi_progress($p) {
//     $log_id = 139; // Example log ID
//     $result = $p->tu_choi_employee_kpi_progress($log_id);
//     echo "Test tu_choi_employee_kpi_progress: " . ($result == 1 ? "Success" : "Fail") . "\n";
// }

// function test_duyet_employee_kpi_progress($p) {
//     $emp_kpi_id = 222; // Example employee KPI ID
//     $progress = 20; // Example progress
//     $log_id = 139; // Example log ID
//     $dept_kpi_id = 78; // Example department KPI ID
//     $result = $p->duyet_employee_kpi_progress($emp_kpi_id, $progress, $log_id, $dept_kpi_id);
//     echo "Test duyet_employee_kpi_progress: " . ($result == 1 ? "Success" : "Fail") . "\n";
// }

// // Run tests
// test_update_kpi_progress_log_status($p);
// // test_tu_choi_employee_kpi_progress($p);
// // test_duyet_employee_kpi_progress($p);
?>