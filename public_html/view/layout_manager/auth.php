<?php
// session_name("manager_session");
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {  
        header("Location: ../../index.php");  
        exit();  
    }  
$user_id=$_SESSION['user_id'];
    
?>