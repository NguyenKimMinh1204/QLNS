<?php
include 'class_KPI.php'; // Ensure you include the KPI class

$p = new kpi_dep(); // Create an instance of the KPI class

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // Handle adding a department KPI
    if ($action === 'add_department_kpi') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $department_id = $_POST['department_id'];

        // Validate input
        if (empty($name) || empty($department_id)) {
            echo 0; // Return 0 if invalid input
            exit;
        }

        // Debugging: Output received data
        error_log("Adding KPI: Name = $name, Description = $description, Department ID = $department_id");

        // Call the method to add a department KPI
        $result = $p->them_kpi_phong_ban($department_id, $name, $description);

        // Check result
        if ($result) {
            echo 1; // Return 1 if successful
        } else {
            echo 0; // Return 0 if failed
        }
    }

    // Handle updating a department KPI
    if ($action === 'update_department_kpi') {
        
        $id = $_POST['id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        
                          
        // Validate input
        if (empty($id) || empty($name)) {
            echo 0; // Return 0 if invalid input
            exit;
        }

        // Call the method to update a department KPI without department_id
        $result = $p->cap_nhat_kpi_phong_ban($id, $name, $description);

        // Check result
        if ($result) {
            echo 1; // Return 1 if successful
        } else {
            echo 0; // Return 0 if failed
        }
    }


    // Handle deleting a department KPI
    if ($action === 'delete_department_kpi') {
        $id = $_POST['id'];
        $tableName = 'kpi_department'; // Specify your department KPI table name

        // Call the delete function
        $result = $p->xoa_kpi_phong_ban($id);

        // Check result
        echo $result; // Return 1 if successful, 0 if failed
    }

    // Handle adding a personal KPI
    if ($action === 'add_personal_kpi') {
        $user_id = $_POST['user_id'];
        $kpi_name = $_POST['kpi_name'];
        $evaluated_by = $_POST['evaluated_by'];
        $date_start = $_POST['date_start'];
        $date_end = $_POST['date_end'];
        $target = $_POST['target'];
        $department_id = $_POST['department_id'];
        $id_dep_task = $_POST['id_dep_task'];
        $priority = $_POST['priority'];

        // Validate input
        if (empty($user_id) || empty($kpi_name) || empty($target) || empty($date_start)) {
            echo 0; // Return 0 if invalid input
            exit;
        }

        // Call the method to add a personal KPI
        $result = $p->them_kpi_nhan_vien($user_id, $kpi_name, $evaluated_by, $date_start, $date_end, $target, $department_id, $id_dep_task, $priority);

        // Check result
        if ($result) {
            echo 1; // Return 1 if successful
        } else {
            echo 0; // Return 0 if failed
        }
    }

    // Handle updating a personal KPI
    if ($action === 'update_personal_kpi') {
        $id = $_POST['id'];
        $kpi_name = $_POST['kpi_name'];
        $target = $_POST['target'];
        $priority = $_POST['priority'];

        // Validate input
        if (empty($id) || empty($kpi_name) || empty($target)) {
            echo 0; // Return 0 if invalid input
            exit;
        }

        // Call the method to update a personal KPI
        $result = $p->cap_nhat_kpi_nhan_vien($id, $kpi_name, $target, $priority);

        // Check result
        if ($result) {
            echo 1; // Return 1 if successful
        } else {
            echo 0; // Return 0 if failed
        }
    }

    // Handle deleting a personal KPI
    if ($action === 'delete_personal_kpi') {
        $id = $_POST['id'];
        $tableName = 'kpi_personal'; // Specify your personal KPI table name

        // Call the delete function
        $result = $p->xoa_kpi_nhan_vien($id);

        // Check result
        echo $result; // Return 1 if successful, 0 if failed
    }
}
?>