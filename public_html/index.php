<?php
session_start(); // Khởi động phiên
include ('class_login.php'); // Thêm dòng này để bao gồm lớp LoginSystem
$p = new Database();
if (!$p->getConnection()) {
    die("Failed to connect to database");
}
$loginSystem = new LoginSystem($p); // Khởi tạo đối tượng LoginSystem

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['UserName'];
    $password = $_POST['password'];
    // $id=$_POST['id'];
    // Sử dụng phương thức login từ lớp LoginSystem, đã có điều hướng bên trong
    $loginSystem->login($username, $password);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background-color: #f8f9fa;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }

    .login-container {
        max-width: 400px;
        padding: 30px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .login-container h3 {
        text-align: center;
        margin-bottom: 20px;
    }

    .login-container .form-control {
        margin-bottom: 15px;
    }
    </style>
</head>

<body>

    <div class="login-container">
        <h3>Đăng Nhập</h3>
        <form method="POST">
            <div class="mb-3">
                <label for="UserName" class="form-label">Tài Khoản</label>
                <input type="text" class="form-control" id="UserName" name="UserName" placeholder="Nhập tài khoản"
                    required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mật Khẩu</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Nhập mật khẩu"
                    required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Nhớ Mật Khẩu</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
            <div class="mt-3 text-center">
                <a href="#">Quên Mật Khẩu?</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>