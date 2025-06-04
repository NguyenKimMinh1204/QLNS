<?php
include '../../db/connetion.php';




class Users extends Database{



    public function loaddulieu_user() {
        $sql='SELECT users.id,users.is_account_active,users.manv,users.username, users.password, users.email, users.full_name, roles.role_name,users.role_id,users.department_id , departments.department_name AS department_name FROM users JOIN roles ON users.role_id = roles.id JOIN departments ON users.department_id = departments.id;';
        $link = $this->getConnection();
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->execute();
            $ketqua = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $i = count($ketqua);
            
            if ($i > 0) {
                echo '<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Mã NV</th>
                                <th>Tài khoản</th>
                                <th>Họ và tên</th>
                                <th>Email</th>
                                <th>Chức vụ</th>
                                <th>Phòng ban</th>
                                <th>Tình trạng</th>
                                <th style="width:230px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                foreach ($ketqua as $row) {
                    
                    echo '<tr>
                            <td>' . htmlspecialchars($row['manv']) . '</td>
                            <td>' . htmlspecialchars($row['username']) . '</td>
                            <td>' . htmlspecialchars($row['full_name']) . '</td>
                            <td>' . htmlspecialchars($row['email']) . '</td>     
                            <td>' . htmlspecialchars($row['role_name']) . '</td>
                            <td>' . htmlspecialchars($row['department_name']) . '</td>  
                            <td>' . ($row['is_account_active'] == 1 ? 'Hoạt động' : 'Bị vô hiệu hóa') . '</td>                           
                            <td>
                                <button class="btn btn-primary mt-5" data-toggle="modal" data-target="#editUserModal"  data-id="' . htmlspecialchars($row['id']) . '" 
                                        data-username="' . htmlspecialchars($row['username']) . '" 
                                        data-email="' . htmlspecialchars($row['email']) . '" 
                                        data-full_name="' . htmlspecialchars($row['full_name']) . '" 
                                        data-role_id="' . htmlspecialchars($row['role_id']) . '" 
                                        data-department_id="' . htmlspecialchars($row['department_id']) . '">Sửa</button>
                               
                                 <button class="btn btn-warning change-password-btn mt-5" data-toggle="modal" data-target="#changePasswordModal" 
            data-id="' . htmlspecialchars($row['id']) . '" 
            data-username="' . htmlspecialchars($row['username']) . '">đổi mật khẩu</button>
             <button class="mt-5 btn ' . ($row['is_account_active'] == '1' ? 'btn-danger' : 'btn-success') . ' toggle-status-btn" 
                        data-id="' . htmlspecialchars($row['id']) . '" 
                        data-is-active="' . htmlspecialchars($row['is_account_active']) . '">' . ($row['is_account_active'] == '1' ? 'Đóng' : 'Mở') . '</button>                    
            </td>
                          </tr>';
                }

                echo '</tbody></table>';
            } else {
                echo 'Không có dữ liệu';
            }
        } else {
            echo 'Không thể kết nối đến cơ sở dữ liệu';
        }
    }

    public function loaddulieu_user1($department_id = null) {
    $sql = 'SELECT users.id,users.is_account_active, users.manv, users.username, users.password, users.email, users.full_name, roles.role_name, users.role_id, users.department_id, departments.department_name AS department_name FROM users JOIN roles ON users.role_id = roles.id JOIN departments ON users.department_id = departments.id';
    
    // Nếu department_id được cung cấp, thêm điều kiện WHERE
    if ($department_id !== null) {
        $sql .= ' WHERE users.department_id = :department_id';
    }

    $link = $this->getConnection();
    if ($link) {
        $stmt = $link->prepare($sql);
        
        // Nếu department_id được cung cấp, ràng buộc tham số
        if ($department_id !== null) {
            $stmt->bindParam(':department_id', $department_id);
        }

        $stmt->execute();
        $ketqua = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $i = count($ketqua);
        
        if ($i > 0) {
            echo '<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Mã NV</th>
                                <th>Tài khoản</th>
                                <th>Họ và tên</th>
                                <th>Email</th>
                                <th>Chức vụ</th>
                                <th>Phòng ban</th>
                                <th>Tình trạng</th>
                                <th style="width:230px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            foreach ($ketqua as $row) {
                echo '<tr>
                        <td>' . htmlspecialchars($row['manv']) . '</td>
                        <td>' . htmlspecialchars($row['username']) . '</td>
                        <td>' . htmlspecialchars($row['full_name']) . '</td>
                        <td>' . htmlspecialchars($row['email']) . '</td>     
                        <td>' . htmlspecialchars($row['role_name']) . '</td>
                        <td>' . htmlspecialchars($row['department_name']) . '</td>
                        <td>' . ($row['is_account_active'] == 1 ? 'Hoạt động' : 'Bị vô hiệu hóa') . '</td>                              
                        <td>
                                <button class="btn btn-primary mt-5" data-toggle="modal" data-target="#editUserModal"  data-id="' . htmlspecialchars($row['id']) . '" 
                                        data-username="' . htmlspecialchars($row['username']) . '" 
                                        data-email="' . htmlspecialchars($row['email']) . '" 
                                        data-full_name="' . htmlspecialchars($row['full_name']) . '" 
                                        data-role_id="' . htmlspecialchars($row['role_id']) . '" 
                                        data-department_id="' . htmlspecialchars($row['department_id']) . '">Sửa</button>
                                
                                 <button class="btn btn-warning change-password-btn mt-5" data-toggle="modal" data-target="#changePasswordModal" 
            data-id="' . htmlspecialchars($row['id']) . '" 
            data-username="' . htmlspecialchars($row['username']) . '">đổi mật khẩu</button>
             <button class="mt-5 btn ' . ($row['is_account_active'] == '1' ? 'btn-danger' : 'btn-success') . ' toggle-status-btn" 
                        data-id="' . htmlspecialchars($row['id']) . '" 
                        data-is-active="' . htmlspecialchars($row['is_account_active']) . '">' . ($row['is_account_active'] == '1' ? 'Đóng' : 'Mở') . '</button>                    
            </td>
                      </tr>';
            }

            echo '</tbody></table>';
        } else {
            echo 'Không có dữ liệu';
        }
    } else {
        echo 'Không thể kết nối đến cơ sở dữ liệu';
    }
}
public function them_user($username,$password, $email, $role_id, $full_name, $department_id) {
        $dbh = $this->getConnection();
        $hashedPassword = $this->hashPassword($password); // Mã hóa mật khẩu ở đây

        // Tạo mã nhân viên
        $manv = $this->generateEmployeeCode();

        $stmt = $dbh->prepare("INSERT INTO users (manv, username, password, email, role_id, full_name, department_id, created_at) VALUES (:manv, :username, :password, :email, :role_id, :full_name, :department_id, NOW())");
        $stmt->bindParam(':manv', $manv);
        $stmt->bindParam(':username', $username);
         $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':department_id', $department_id);
        
        return $stmt->execute() ? 1 : 0; // Trả về 1 nếu thành công, 0 nếu thất bại
    }

   public function cap_nhat_user($id, $username, $email, $role_id, $full_name, $department_id) {
    $dbh = $this->getConnection();
    $sql = "UPDATE users SET updated_at = NOW()";

    // Kiểm tra các tham số không null và xây dựng câu lệnh SQL
    if ($username !== null) {
        $sql .= ", username = :username";
    }

    if ($email !== null) {
        $sql .= ", email = :email";
    }

    if ($role_id !== null) { // Thêm điều kiện cho role_id
        $sql .= ", role_id = :role_id";
    }

    if ($full_name !== null) {
        $sql .= ", full_name = :full_name";
    }

    if ($department_id !== null) {
        $sql .= ", department_id = :department_id";
    }

    // Thêm điều kiện WHERE
    $sql .= " WHERE id = :id";

    // Chuẩn bị câu lệnh SQL
    $stmt = $dbh->prepare($sql);

    // Ràng buộc tham số cho từng giá trị
    if ($username !== null) {
        $stmt->bindParam(':username', $username);
    }

    if ($email !== null) {
        $stmt->bindParam(':email', $email);
    }

    if ($role_id !== null) {
        $stmt->bindParam(':role_id', $role_id);
    }

    if ($full_name !== null) {
        $stmt->bindParam(':full_name', $full_name);
    }

    if ($department_id !== null) {
        $stmt->bindParam(':department_id', $department_id);
    }

    // Ràng buộc id để xác định bản ghi cần cập nhật
    $stmt->bindParam(':id', $id);

    // Thực thi câu lệnh SQL và trả về kết quả
    return $stmt->execute() ? 1 : 0; // Trả về 1 nếu thành công, 0 nếu thất bại
}


    public function xoabypara_user($id) {
        $dbh = $this->getConnection();
        $stmt = $dbh->prepare("DELETE FROM users WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        return $stmt->execute() ? 1 : 0;
    }
    public function getDepartments() {
    $link = $this->getConnection();
    $stmt = $link->prepare("SELECT * FROM departments");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Thêm phương thức mã hóa mật khẩu
    public function hashPassword($password) {
        return md5($password); // Mã hóa mật khẩu bằng MD5
    }
    public function changePassword($id, $newPassword) {
        $dbh = $this->getConnection();
        $hashedPassword = $this->hashPassword($newPassword); // Mã hóa mật khẩu mới

        $stmt = $dbh->prepare("UPDATE users SET password = :password, updated_at = NOW() WHERE id = :id");
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute() ? 1 : 0; // Trả về 1 nếu thành công, 0 nếu thất bại
    }

    // Thêm phương thức để sinh mã nhân viên
    // private function generateEmployeeCode($department_id, $role_id) {
    //     // Lấy mã phòng từ cơ sở dữ liệu
    //     $stmt = $this->getConnection()->prepare("SELECT maphong FROM departments WHERE id = :id");
    //     $stmt->bindParam(':id', $department_id);
    //     $stmt->execute();
    //     $maphong = $stmt->fetchColumn();

    //     // Tạo prefix dựa trên role_id
    //     $prefix = '';
    //     if ($role_id == 0) {
    //         $prefix = 'AD'; // Nếu là admin
    //     } else {
    //         // Sử dụng maphong làm prefix
    //         if ($maphong) {
    //             $prefix = strtoupper($maphong); // Chuyển đổi thành chữ hoa
    //         } else {
    //             $prefix = 'X'; // Mặc định nếu không tìm thấy
    //         }
    //     }

    //     do {
    //         $randomNumber = str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT); // Tạo số ngẫu nhiên 3 chữ số
    //         $manv = $prefix . $randomNumber;

    //         // Kiểm tra mã nhân viên có tồn tại không
    //         $stmt = $this->getConnection()->prepare("SELECT COUNT(*) FROM users WHERE manv = :manv");
    //         $stmt->bindParam(':manv', $manv);
    //         $stmt->execute();
    //         $exists = $stmt->fetchColumn();
    //     } while ($exists > 0); // Nếu mã đã tồn tại, sinh lại

    //     return $manv;
    // }
    // Thêm phương thức để lấy department_id hiện tại của người dùng
    private function getCurrentDepartmentId($id) {
        $dbh = $this->getConnection();
        $stmt = $dbh->prepare("SELECT department_id FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
private function generateEmployeeCode() {
    // Lấy 2 số cuối của năm hiện tại
    $yearSuffix = date('y'); // VD: Năm 2024 -> '24'

    do {
        // Tạo số ngẫu nhiên 6 chữ số
        $randomNumber = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT); // Số từ 000000 đến 999999
        $manv = $yearSuffix . $randomNumber; // Kết hợp 2 số cuối năm + 6 số ngẫu nhiên

        // Kiểm tra mã nhân viên có tồn tại không
        $stmt = $this->getConnection()->prepare("SELECT COUNT(*) FROM users WHERE manv = :manv");
        $stmt->bindParam(':manv', $manv);
        $stmt->execute();
        $exists = $stmt->fetchColumn();
    } while ($exists > 0); // Nếu mã đã tồn tại, sinh lại

    return $manv;
}

    public function toggleAccountStatus($id, $isActive) {
        $dbh = $this->getConnection();
        $stmt = $dbh->prepare("UPDATE users SET is_account_active = :is_account_active WHERE id = :id");
        $stmt->bindParam(':is_account_active', $isActive);
        $stmt->bindParam(':id', $id);
        return $stmt->execute() ? 1 : 0; // Return 1 if successful, 0 if failed
    }
}