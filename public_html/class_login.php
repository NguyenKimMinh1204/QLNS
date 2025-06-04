<?php


include ('db/connetion.php'); // Đảm bảo đường dẫn này là chính xác
$p = new Database();

class LoginSystem {
  
    private $db;
    // private $table_name = "users";
    public function __construct($db) {
        $this->db = $db; // Khởi tạo kết nối cơ sở dữ liệu
    }
    public function login($username, $password) {
        try {
            $fetch = $this->db->conn->prepare("SELECT id, role_id, password,is_account_active
                FROM users WHERE username = ?");
            $fetch->execute(array($username));
            $is_account_active=$fetch;
            if ($fetch->rowCount() > 0) {
                $row = $fetch->fetch();
                $hashPassword = md5($password); // Mã hóa mật khẩu
                // So sánh mật khẩu đã mã hóa
                if ($hashPassword === $row['password']&& $row['is_account_active'] == 1) {
                    // Khởi tạo session
                    session_start();
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['role_id'] = $row['role_id'];
                    $_SESSION['username'] = $username; // Lưu tên đăng nhập vào session nếu cần
                    $_SESSION['id'] = $row['id']; // Lưu id vào session
                    // Set last activity time

                    // Điều hướng dựa trên role_id
                    switch ($row['role_id']) {
                        case 0:
                            
                            header("Location: view/admin/index.php");
                            break; 
                        case 1:
                            header("Location: view/manager/index.php");
                            break;
                        case 2:
                            header("Location: view/employee/index.php");
                            break;
                        default:
                            echo "<script>alert('Role không được nhận diện.');</script>";
                            header("Location: login.php");
                            break;
                    }
                    exit(); // Dừng thực thi sau khi điều hướng
                } else {
                    echo "<script>alert('Tên người dùng hoặc mật khẩu không đúng.');</script>";
                    return null; // Đăng nhập thất bại
                }
            } else {
                echo "<script>alert('Tên người dùng hoặc mật khẩu không đúng.');</script>";
                return null; // Đăng nhập thất bại
            }
        } catch (PDOException $e) {
            error_log("Lỗi: " . $e->getMessage());
            return null; // Xử lý lỗi cơ sở dữ liệu
        }
    }

   

    public function logout() {
        session_start(); // Bắt đầu phiên
        if (isset($_SESSION['user_id'])) { // Kiểm tra phiên có tồn tại
            session_unset(); // Xóa tất cả các biến phiên
            session_destroy(); // Hủy phiên
        }
        header('location:../../index.php'); // Chuyển hướng về trang đăng nhập
    }
}


?>