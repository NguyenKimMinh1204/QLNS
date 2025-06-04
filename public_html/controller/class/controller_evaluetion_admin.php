<?php 
include 'class_evaluetion_admin.php'; // Include the class containing the methods

$p = new evaluetion(); // Create an instance of the class

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action']; // Get the action parameter

    // Handle adding an evaluation
    if ($action === 'add') {
        $user_id = $_POST['user_id'];
        $dep_kpi_id = $_POST['dep_kpi_id'];
        $comments = $_POST['comments'];

        // Validate input
        if (empty($user_id) || empty($dep_kpi_id) || empty($comments)) {
            echo 0; // Return 0 if invalid input
            exit;
        }

        // Call the method to add an evaluation
        $result = $p->them_danh_gia($user_id, $dep_kpi_id, $comments);

        // Check result
        echo $result ? 1 : 0; // Return 1 if successful, 0 if failed
    }

    // Handle updating an evaluation
    if ($action === 'update') {
        $id = $_POST['id'];
        $user_id = $_POST['user_id'];
        $comments = $_POST['comments'];

        // Validate input
        if (empty($id) || empty($user_id) || empty($comments)) {
            echo 0; // Return 0 if invalid input
            exit;
        }

        // Call the method to update an evaluation
        $result = $p->cap_nhat_danh_gia($id, $user_id, $comments);

        // Check result
        echo $result ? 1 : 0; // Return 1 if successful, 0 if failed
    }

    // Handle deleting an evaluation
    if ($action === 'delete') {
        $id = $_POST['id'];

        // Validate input
        if (empty($id)) {
            echo 0; // Return 0 if invalid input
            exit;
        }

        // Call the method to delete an evaluation
        $result = $p->xoa_danh_gia($id);

        // Check result
        echo $result ? 1 : 0; // Return 1 if successful, 0 if failed
    }
    
}
?>