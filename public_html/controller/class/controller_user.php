<?php

include 'class_User.php'; // Ensure you include the class

$p = new Users(); // Create an instance of the Users class

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'add_user') { // Handle user addition
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];
        $role_id = $_POST['role_id'];
        $full_name = $_POST['full_name'];
        $department_id = $_POST['department_id'];

        // Validate input
        if (empty($username) || empty($password) || empty($email) || empty($role_id) || empty($full_name) || empty($department_id)) {
            echo 0; // Return 0 if invalid data
            exit;
        }

        // Call the method to add a user
        $result = $p->them_user($username, $password, $email, $role_id, $full_name, $department_id);

        // Check the result
        if ($result) {
            echo 1; // Return 1 if successful
        } else {
            error_log("Error adding user: " . print_r($p->getConnection()->errorInfo(), true));
            echo 0; // Return 0 if failed
        }
    }

    if ($action === 'update_user') { // Handle user update
        $id = $_POST['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $role_id = $_POST['role_id'];
        $full_name = $_POST['full_name'];
        $department_id = $_POST['department_id'];

        // Validate input
        if (empty($id) || empty($username) || empty($email) || empty($role_id) || empty($full_name) || empty($department_id)) {
            echo 0; // Return 0 if invalid data
            exit;
        }

        // Call the method to update user data
        $result = $p->cap_nhat_user($id, $username, $email, $role_id, $full_name, $department_id);

        // Check the result
        if ($result) {
            echo 1; // Return 1 if successful
        } else {
            echo 0; // Return 0 if failed
        }
    }

    if ($action === 'change_password') { // Handle password change
        $id = $_POST['id'];
        $password = $_POST['password'];

        // Validate input
        if (empty($id) || empty($password)) {
            echo 0; // Return 0 if invalid data
            exit;
        }

        // Hash the password using MD5
        $hashedPassword = md5($password);

        // Call the method to change the password
        $result = $p->changePassword($id, $hashedPassword);

        // Check the result
        if ($result) {
            echo 1; // Return 1 if successful
        } else {
            echo 0; // Return 0 if failed
        }
    }

    if ($action === 'toggle_account_status') { // Handle account activation/deactivation
        $id = $_POST['id'];
        $isActive = $_POST['is_active'] === '1' ? 0 : 1; // Toggle the status

        // Call the method to update account status
        $result = $p->toggleAccountStatus($id, $isActive);

        // Check the result
        if ($result) {
            echo 1; // Return 1 if successful
        } else {
            echo 0; // Return 0 if failed
        }
    }
}
?>