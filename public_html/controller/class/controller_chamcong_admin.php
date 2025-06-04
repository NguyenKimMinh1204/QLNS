<?php
include 'class_chamcong_admin.php'; // Ensure you include the WiFi class

$a = new WiFi(); // Create an instance of the WiFi class
       
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
//http://localhost/KLTN/controller/class/controller_chamcong_admin.php?action=addwf&ssid=ksdgfjkd&ip_address=123.456.78.2&description=sakjbgvskjbv
//http://localhost/KLTN/controller/class/controller_chamcong_admin.php?action=addwf&ssid=ksdgfjkd&ip_address=123.456.78.2&description=sakjbgvskjbv

// Handle adding a WiFi address
    if ($action === 'addwf') {
       
        $ssid = $_POST['ssid'];
        $ip_address = $_POST['ip_address'];
        $description = $_POST['description'];

        // // Validate input
        if (empty($ssid) || empty($ip_address)) {
            echo 2; 
            
            exit;
        }

        // Call the method to add a WiFi address
        $result = $a->addWiFi($ssid, $ip_address, $description);

        // Check result
        if ($result) {
            
            echo 1; // Return 1 if successful
            
        } else {
           
            error_log("Error adding WiFi: " . print_r($a->getConnection()->errorInfo(), true));
            echo 0; // Return 0 if failed
        }
    }

    // Handle updating a WiFi address
    if ($action === 'updatewf') {

        $id = $_POST['Id'];
        $ssid = $_POST['ssid'];
        $ip_address = $_POST['ip_address'];
        $description = $_POST['description'];

        // Validate input
        if (empty($id) || empty($ssid) || empty($ip_address)) {
            echo 0; // Return 0 if invalid input
            exit;
        }
        
        // Call the method to update a WiFi address
        $result = $a->updateWiFi($id, $ssid, $ip_address, $description);

        // Check result
        if ($result) {
            echo 1; // Return 1 if successful
        } else {
            error_log("Error updating WiFi: " . print_r($a->getConnection()->errorInfo(), true));
            echo 0; // Return 0 if failed
        }
    }

    // Handle deleting a WiFi address
    if ($action === 'delete') {
        $id = $_POST['id'];

        // Call the delete function
        $result = $a->deleteWiFi($id);

        // Check result
        echo $result; // Return 1 if successful, 0 if failed
    }
}
?>