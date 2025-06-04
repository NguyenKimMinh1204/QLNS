<?php
include 'class_dep.php'; // Ensure you include the class

$p = new department(); // Create an instance of the department class

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    // Handle adding a department
    if ($action === 'add') {
        $dept_name = $_POST['dept_name'];
        $maphong = $_POST['maphong']; // Department code

        // Validate input
        if (empty($dept_name) || empty($maphong)) {
            echo 0; // Return 0 if invalid input
            exit;
        }

        // Call the method to add a department
        $result = $p->them_phong_ban($dept_name, $maphong);

        // Check result
        if ($result) {
            echo 1; // Return 1 if successful
        } else {
            error_log("Error adding department: " . print_r($p->getConnection()->errorInfo(), true));
            echo 0; // Return 0 if failed
        }
    }

    // Handle updating a department
    if ($action === 'update') {
        $dept_id = $_POST['id'];
        $dept_name = $_POST['dept_name'];
        $maphong = $_POST['maphong'];

        // Validate input
        if (empty($dept_id) || empty($dept_name)) {
            echo 0; // Return 0 if invalid input
            exit;
        }

        // Call the method to update a department
        $result = $p->cap_nhat_phong_ban($dept_id, $dept_name, $maphong);
        
        // Check result
        if ($result) {
            echo 1; // Return 1 if successful
        } else {
            echo 0; // Return 0 if failed
        }
    }

    // Handle deleting a department
    if ($action === 'delete') {
        $id = $_POST['id'];
        $tableName = 'departments'; // Specify your table name

        // Call the delete function
        $result = $p->xoabypara($tableName, $id);

        // Check result
        echo $result; // Return 1 if successful, 0 if failed
    }
}
?>