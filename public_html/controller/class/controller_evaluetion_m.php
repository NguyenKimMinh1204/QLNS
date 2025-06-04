<?php 
include 'class_evaluetion_m.php'; // Include the class containing the methods

$p = new evaluetion_manager(); // Create an instance of the class

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action']; // Get the action parameter

    // Handle adding a manager evaluation
    if ($action === 'add') {
        $user_id = $_POST['user_id'];
        $manager_id = $_POST['manager_id'];
        $emp_kpi_id = $_POST['emp_kpi_id'];
        $comments = $_POST['comments'];

        // Validate input
        if (empty($user_id) || empty($manager_id) || empty($emp_kpi_id) || empty($comments)) {
            echo 0; // Return 0 if invalid input
            exit;
        }

        // Call the method to add a manager evaluation
        $result = $p->them_danh_gia_quan_ly($user_id, $manager_id, $emp_kpi_id, $comments);

        // Check result
        echo $result ? 1 : 0; // Return 1 if successful, 0 if failed
    }

    // Handle updating a manager evaluation
    if ($action === 'update') {
        $id = $_POST['id'];
        $comments = $_POST['comments'];

        // Validate input
        if (empty($id) || empty($comments)) {
            echo 0; // Return 0 if invalid input
            exit;
        }

        // Call the method to update a manager evaluation
        $result = $p->cap_nhat_danh_gia_quan_ly($id, $comments);

        // Check result
        echo $result ? 1 : 0; // Return 1 if successful, 0 if failed
    }

    // Handle deleting a manager evaluation
    if ($action === 'delete') {
        $id = $_POST['id'];

        // Validate input
        if (empty($id)) {
            echo 0; // Return 0 if invalid input
            exit;
        }

        // Call the method to delete a manager evaluation
        $result = $p->xoa_danh_gia_quan_ly($id);

        // Check result
        echo $result ? 1 : 0; // Return 1 if successful, 0 if failed
    }
}
?>