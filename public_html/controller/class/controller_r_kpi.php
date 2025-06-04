<?php
 // Start the session to use session variables
include 'class_e_kpi.php'; // Ensure you include the KPI class

$p = new ekpi(); // Create an instance of the KPI class
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'update_progress') {
        $emp_kpi_id = $_POST['emp_kpi_id'];
        $progress = $_POST['progress'];
        $description = $_POST['description'];

        // Check if any variable is missing
        if (empty($emp_kpi_id) ||  empty($progress) || empty($description)) {
             // Return 2 if any variable is missing
             echo 2;
            return;
        }

       
        
        // Add error logging
        error_log("Updating progress - EMP_KPI_ID: $emp_kpi_id, DEPT_KPI_ID: Progress: $progress");
        
        $result = $p->them_kpi_progress_log($emp_kpi_id, $progress, $description);
    
        
        if ($result == 1) {  // Use strict comparison
            echo 1; // Success
        } else {
            echo 0; // Return 0 for failure
        }
    }if ($action === 'update_it_task_progress') {
        $emp_kpi_id = $_POST['emp_kpi_id'];
        $assigned_value = $_POST['assigned_value'];
        $progress_update = $_POST['progress_update'];
        $progress_description = $_POST['progress_description'];

        // Check if any variable is missing
        if (empty($emp_kpi_id) || empty($assigned_value) || empty($progress_update) || empty($progress_description)) {
          
            return 2;
        }

        // Calculate the progress based on the percentage
        $progress = ($progress_update * $assigned_value) / 100;
        
        $result = $p->them_kpi_progress_log($emp_kpi_id, $progress, $progress_description);
      
        if ($result == 1) {  // Use strict comparison
            return 1; // Success
        } else {
            return 0; // Return 0 for failure
        }
    }
     // Kiểm tra hành động xóa progress log
    if ($action === 'delete_progress_log') {
        $log_id = $_POST['log_id'];

        // Check if log_id is missing
        if (empty($log_id)) {
            echo 2; // Return 2 if log_id is missing
            return;
        }

        $ekpi = new ekpi();
        
        // Add error logging
        error_log("Deleting progress log with LOG_ID: $log_id");
        
        $result = $ekpi->xoa_kpi_progress_log($log_id); // Call the delete function
        
        // Add error logging
        error_log("Delete result: $result");
        
        if ($result === 1) {  // Use strict comparison
            echo $result; // Success
        } else {
            echo 0; // Return 0 for failure
        }
    }
   
     if ($action === 'get_kpi_progress_logs') {
        $emp_kpi_id = $_POST['emp_kpi_id']; // Get emp_kpi_id from POST data
        $user_id=$_POST['user_id'];
        $kpiLogs = $p->get_kpi_progress_logs($emp_kpi_id,$user_id); // Fetch the progress logs
        
        // Return the logs as JSON
        echo json_encode($kpiLogs);
    }
} 
        // $emp_kpi_id =237;
        // $progress = 2000000;
        // $description = 'mô tả';

        // $result = $p->them_kpi_progress_log($emp_kpi_id, $progress, $description);
        // echo $result;
// if ($_SERVER['REQUEST_METHOD'] === 'GET') {
//     $action1 = $_GET['action'];
//     if ($action1 === 'xemGD') {
    
//         $p->xemGD(); // Call the xemGD method to fetch transaction data
//     } if($action1=='id'){
//         $id=59;
//         $type=1;
//     // echo $p->generateProductCode($type,$id);
//     echo '<pre>';
//     print_r ($p->updateKpiProgressFromApi());
//     echo '<pre>';
    
//     }
//     if ($action1 === 'get_kpi_progress_logs') {
//         $emp_kpi_id = $_POST['emp_kpi_id'];
//         $user_id = $_POST['user_id'];

//         // Check if any variable is missing
//         if (empty($emp_kpi_id) || empty($user_id)) {
//             echo 2; // Return 2 if any variable is missing
//             return;
//         }

//         $kpiLogs = $p->get_kpi_progress_logs($emp_kpi_id,$user_id); // Fetch the progress logs
        
//         // Return the logs as JSON
//         echo json_encode($kpiLogs);
//     }
// }
?>