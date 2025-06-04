<?php
include ('class_thuong_kpi_m.php');
$p = new thuong_kpi_m();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action']; 

    if ($action === 'confirm_bonus') {
        $emp_kpi_id = $_POST['emp_kpi_id'];
        $goalBasedIncentive = $_POST['goalBasedIncentive'];
        
        
        // Update is_active status
        $result = $p->updateEmployeeKpis($emp_kpi_id, $goalBasedIncentive);
        
        if ($result) {
            echo 1;
        } else {
            error_log("Failed to update department KPIs for ID: $emp_kpi_id");
            echo 0;
        }
    }}
    
    // $emp_kpi_id =222;
    // $goalBasedIncentive = 20;
    //  $result = $p->updateEmployeeKpis($emp_kpi_id, $goalBasedIncentive);
    
    // if($result==1){
    //     echo "success";
    // }else{
    //     echo "fail";
    // }
       
?>