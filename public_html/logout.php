<?php
session_start(); // Khởi động phiên
include ('./class_login.php'); // Bao gồm lớp LoginSystem
$p = new Database();
$loginSystem = new LoginSystem($p); // Khởi tạo đối tượng LoginSystem

// Gọi phương thức logout
$loginSystem->logout();
?>