<?php
// session_name("employee_session");
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {  
        header("Location: ../../index.php");  
        exit();  
    }  
    $user_id=$_SESSION['user_id'];
?>