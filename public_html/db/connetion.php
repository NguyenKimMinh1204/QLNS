<?php
class Database {
    private $host = 'localhost';       // Tên server, thường là localhost
    private $db_name = 'quanlynh_hrdb';  // Tên cơ sở dữ liệu
    private $username = 'quanlynh_user_name';        // Tên tài khoản MySQL
    private $password = 'Kimkim123@@@';            // Mật khẩu tài khoản MySQL
    public $conn;

    // Hàm kết nối đến cơ sở dữ liệu
    public function getConnection() {
        $this->conn = null;

        try {
            // Tạo kết nối mới bằng PDO
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            
            // Thiết lập chế độ lỗi để báo cáo ngoại lệ
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        } catch(PDOException $exception) {
            // Bắt lỗi nếu có vấn đề trong quá trình kết nối
            echo "Lỗi kết nối: " . $exception->getMessage();
        }

        return $this->conn;
    }
    public function xuatUsernameTheoId($user_id) {
    // Câu truy vấn SQL để lấy username của người dùng dựa trên id
    $sql = 'SELECT full_name  FROM users WHERE id = :user_id';

    $link = $this->getConnection();
    if ($link) {
        $stmt = $link->prepare($sql);
        
        // Ràng buộc tham số `user_id`
        $stmt->bindParam(':user_id', $user_id);
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Lấy username của người dùng

        // Kiểm tra nếu có kết quả và trả về username
        if ($result) {
            return $result['full_name'];
        } else {
            return 'Guest'; // Trả về 'Guest' nếu không có username nào
        }
    } else {
        return 'Không thể kết nối cơ sở dữ liệu';
    }
}
public function xuatavatarTheoId($user_id) {
    // Câu truy vấn SQL để lấy username của người dùng dựa trên id
    $sql = 'SELECT avatar  FROM users WHERE id = :user_id';

    $link = $this->getConnection();
    if ($link) {
        $stmt = $link->prepare($sql);
        
        // Ràng buộc tham số `user_id`
        $stmt->bindParam(':user_id', $user_id);
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Lấy username của người dùng

        // Kiểm tra nếu có kết quả và trả về username
        if ($result) {
            return $result['avatar'];
        } else {
            return 'Guest'; // Trả về 'Guest' nếu không có username nào
        }
    } else {
        return 'Không thể kết nối cơ sở dữ liệu';
    }
}
public function xuatPhongbanTheoId($user_id) {
    // Câu truy vấn SQL để lấy username của người dùng dựa trên id
    $sql = 'SELECT d.department_name  
            FROM users u 
            INNER JOIN departments d ON d.id=u.department_id 
            WHERE u.id = :user_id';

    $link = $this->getConnection();
    if ($link) {
        $stmt = $link->prepare($sql);
        
        // Ràng buộc tham số `user_id`
        $stmt->bindParam(':user_id', $user_id);
        
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC); // Lấy username của người dùng

        // Kiểm tra nếu có kết quả và trả về username
        if ($result) {
            return $result['department_name'];
        } else {
            return 'Guest'; // Trả về 'Guest' nếu không có username nào
        }
    } else {
        return 'Không thể kết nối cơ sở dữ liệu';
    }
}
}

?>