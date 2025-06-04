<?php
session_start();
include 'class_thuong_kpi.php';

$p = new tinhluong();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'confirm_bonus') {
        $dept_kpi_id = $_POST['dept_kpi_id'];
        $goalBasedIncentive = $_POST['goalBasedIncentive'];
        
        
        // Update is_active status
        $result = $p->updateDepartmentKpis($dept_kpi_id, $goalBasedIncentive);
        
        if ($result) {
            echo 1;
        } else {
            error_log("Failed to update department KPIs for ID: $dept_kpi_id");
            echo 0;
        }
    }
    
}


?>