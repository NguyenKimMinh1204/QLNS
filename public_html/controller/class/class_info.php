<?php
include ('../../db/connetion.php');
class info extends Database{
public function loaddulieu_user($id) {
    // Sửa câu truy vấn để lấy dữ liệu dựa trên ID của người dùng
    $sql = 'SELECT users.id, users.manv, users.username, users.email, users.full_name, users.avatar,
            roles.role_name, users.role_id, users.department_id, departments.department_name 
            FROM users 
            JOIN roles ON users.role_id = roles.id 
            JOIN departments ON users.department_id = departments.id 
            WHERE users.id = :id'; // Sử dụng placeholder :id để lấy user theo ID
    
    // Kết nối tới cơ sở dữ liệu
    $link = $this->getConnection();
    if ($link) {
        // Chuẩn bị câu truy vấn
        $stmt = $link->prepare($sql);
        
        // Gán giá trị cho placeholder :id
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Thực thi truy vấn
        $stmt->execute();

        // Lấy kết quả
        $ketqua = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $i = count($ketqua);

        // Kiểm tra nếu có kết quả
        if ($i > 0) {
            // Giả sử dữ liệu người dùng nằm ở dòng đầu tiên
            $user = $ketqua[0];

            // Hiển thị thông tin người dùng (đã lấy từ kết quả truy vấn)
            echo '<div class="employee-info">
                <div class="avatar">
                    <img src="../../assets/img/' . htmlspecialchars($user['avatar']) . '" alt="Avatar" />
              
                </div>
                <div class="details">
                    <h2>' . htmlspecialchars($user['full_name']) . '</h2>
                    <p>Mã Nhân Viên: ' . htmlspecialchars($user['manv']) . '</p>
                    <p>Phòng Ban: ' . htmlspecialchars($user['department_name']) . '</p>
                </div>
            </div>';
        } else {
            echo 'Không có dữ liệu';
        }
    } else {
        echo 'Không thể kết nối đến cơ sở dữ liệu';
    }
}
public function updateUserInfo($userId, $email, $cccd, $address, $phone, $birthdate) {
    $link = $this->getConnection();
    
    try {
        $link->beginTransaction();
        
        // Cập nhật email trong bảng users
        $sqlUsers = "UPDATE users SET email = :email WHERE id = :id";
        $stmtUsers = $link->prepare($sqlUsers);
        $stmtUsers->execute([
            ':email' => $email,
            ':id' => $userId
        ]);
        
        // Cập nhật thông tin trong bảng user_info
        $sqlInfo = "UPDATE user_info SET cccd = :cccd, address = :address, phone_number = :phone, birthdate = :birthdate 
                   WHERE id = (SELECT info_id FROM users WHERE id = :userId)";
        $stmtInfo = $link->prepare($sqlInfo);
        $stmtInfo->execute([
            ':cccd' =>$cccd,
            ':address' => $address,
            ':phone' => $phone,
            ':birthdate' => $birthdate,
            ':userId' => $userId
        ]);
        
        $link->commit();
        return "Cập nhật thông tin thành công";
    } catch (PDOException $e) {
        $link->rollBack();
        return "Lỗi khi cập nhật thông tin: " . $e->getMessage();
    }
}
public function infoUser1($id) {
    // Xử lý upload avatar nếu có
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"]) && isset($_POST["action"]) && $_POST["action"] == "upload_avatar") {
        $result = $this->uploadAvatar($id, $_FILES["avatar"]);
        echo "<script>alert('" . $result . "');</script>";
        // Refresh trang sau khi upload
        echo "<script>window.location.href = window.location.href;</script>";
        return;
    }

    // Sửa câu truy vấn để lấy dữ liệu dựa trên ID của người dùng
    $sql = 'SELECT users.id, users.manv, users.username, users.email, users.full_name, users.avatar,
            users.role_id, roles.role_name,
            users.department_id, departments.department_name,
            users.info_id, user_info.cccd, user_info.address, user_info.phone_number, user_info.birthdate
        FROM users 
        JOIN roles ON users.role_id = roles.id 
        JOIN departments ON users.department_id = departments.id 
        JOIN user_info ON users.info_id = user_info.id
        WHERE users.id = :id';
 // Sử dụng placeholder :id để lấy user theo ID

    // Kết nối tới cơ sở dữ liệu
    $link = $this->getConnection();
    if ($link) {
        // Chuẩn bị câu truy vấn
        $stmt = $link->prepare($sql);
        
        // Gán giá trị cho placeholder :id
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Thực thi truy vấn
        $stmt->execute();

        // Lấy kết quả
        $ketqua = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $i = count($ketqua);

        // Kiểm tra nếu có kết quả
        if ($i > 0) {
            // Giả sử dữ liệu người dùng nằm ở dòng đầu tiên
            $user = $ketqua[0];

            // Hiển thị thông tin người dùng (đã lấy từ kết quả truy vấn)
            echo '
                <div class="info_user">
                    <div class="anhdaidien">
                        <div class="avatar">
                            <img src="../../assets/img/' . htmlspecialchars($user['avatar']) . '" alt="Avatar" >
                        </div>
                       
                        <form method="post" enctype="multipart/form-data">
                            <input type="file" name="avatar" id="avatar" style="display: none;" accept="image/*">
                            <input type="hidden" name="action" value="upload_avatar">
                            <button type="button" class="change" onclick="document.getElementById(\'avatar\').click()">Change Photo</button>
                        </form>
                        <script>
                            document.getElementById("avatar").onchange = function() {
                                this.form.submit();
                            };
                        </script>
                        <div class="thongtin">
                            <div class="name">
                                <p><b>Họ và Tên:</b>'.htmlspecialchars($user['full_name']).'</p>
                            </div>
                            <div class="manv">
                                    <p><b>Mã Nhân Viên:</b>'.htmlspecialchars($user['manv']).'</p>
                            </div>
                            <div class="phongban">
                                    <p><b>Phòng Ban:</b>'.htmlspecialchars($user['department_name']).'</p>
                            </div>
                            <div class="cancuoc">
                                    <p><b>CCCD/CMND:</b>'.htmlspecialchars($user['cccd']).'</p>
                            </div>
                            <div class="capbac">
                                    <p><b>Cấp Bậc:</b>'.htmlspecialchars($user['role_name']).'</p>
                            </div>
                            <div class="email">
                                    <p><b>Email:</b>'.htmlspecialchars($user['email']).'</p>
                            </div>
                            <div class="">
                                    <p><b>Địa Chỉ:</b>'.htmlspecialchars($user['address']).'</p>
                            </div>
                            <div class="phone">
                                    <p><b>Số Điện Thoại:</b>'.htmlspecialchars($user['phone_number']).'</p>
                            </div>
                            <div class="ngaysinh">
                                    <p><b>Ngày Sinh:</b>'.date('d/m/Y', strtotime($user['birthdate'])).'</p>
                            </div>
                            <div class="edit-button">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editUserModal">
                                    Chỉnh sửa thông tin
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Modal -->
                <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="editUserModalLabel">Chỉnh sửa thông tin</h4>
                            </div>
                            <div class="modal-body">
                                <form id="editUserForm" method="post">
                                    <input type="hidden" name="action" value="update_info">
                                    <div class="form-group">
                                        <label for="cccd">CCCD/CMND:</label>
                                        <input type="text" class="form-control" id="cccd" name="cccd" value="'.htmlspecialchars($user['cccd']).'">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email" value="'.htmlspecialchars($user['email']).'">
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Địa chỉ:</label>
                                        <input type="text" class="form-control" id="address" name="address" value="'.htmlspecialchars($user['address']).'">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Số điện thoại:</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="'.htmlspecialchars($user['phone_number']).'">
                                    </div>
                                    <div class="form-group">
                                        <label for="birthdate">Ngày sinh:</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="'.date('Y-m-d', strtotime($user['birthdate'])).'">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>';
        } else {
            echo 'Không có dữ liệu';
        }
    } else {
        echo 'Không thể kết nối đến cơ sở dữ liệu';
    }
}

public function infoUser($id) {
    // Xử lý upload avatar nếu có
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["avatar"]) && isset($_POST["action"]) && $_POST["action"] == "upload_avatar") {
        $result = $this->uploadAvatar($id, $_FILES["avatar"]);
        echo "<script>alert('" . $result . "');</script>";
        echo "<script>window.location.href = window.location.href;</script>";
        return;
    }

    // Câu truy vấn lấy thông tin người dùng
    $sql = 'SELECT users.id, users.manv, users.username, users.email, users.full_name, users.avatar,
            users.role_id, roles.role_name,
            users.department_id, departments.department_name,
            users.info_id, user_info.cccd, user_info.address, user_info.phone_number, user_info.birthdate
        FROM users 
        JOIN roles ON users.role_id = roles.id 
        JOIN departments ON users.department_id = departments.id 
        JOIN user_info ON users.info_id = user_info.id
        WHERE users.id = :id';

    $link = $this->getConnection();
    if ($link) {
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $ketqua = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($ketqua) > 0) {
            $user = $ketqua[0];
            $avatar=$user['avatar'];
            $full_name=$user['full_name'];
            $manv=$user['manv'];
            $department_name=$user['department_name'];
            $cccd=$user['cccd'];
            $role_name=$user['role_name'];
            $email=$user['email'];
            $address=$user['address'];
            $phone_number=$user['phone_number'];
            $avatar=$user['avatar'];
            // Hiển thị thông tin theo dạng bảng
            echo '
                <div class="info_user">
                    <div class="avatar-container text-center">
                        <img src="../../assets/img/' . htmlspecialchars($user['avatar']) . '" alt="Avatar" class="avatar">
                        <form method="post" enctype="multipart/form-data">
                            <input type="file" name="avatar" id="avatar" style="display: none;" accept="image/*">
                            <input type="hidden" name="action" value="upload_avatar">
                            <button type="button" class="btn btn-link change-photo" onclick="document.getElementById(\'avatar\').click()">Đổi ảnh đại diện</button>
                        </form>
                    </div>

                    <table class="table info-table">
                        <tbody>
                            <tr>
                                <td class="strong"><strong>Họ và Tên:</strong></td>
                                <td>' . htmlspecialchars($user['full_name']) . '</td>
                            </tr>
                            <tr>
                                <td class="strong"><strong>Mã Nhân Viên:</strong></td>
                                <td>' . htmlspecialchars($user['manv']) . '</td>
                            </tr>
                            <tr>
                                <td><strong>Phòng Ban:</strong></td>
                                <td>' . htmlspecialchars($user['department_name']) . '</td>
                            </tr>
                            <tr>
                                <td class="strong"><strong>CCCD/CMND:</strong></td>
                                <td>' . htmlspecialchars($user['cccd']) . '</td>
                            </tr>
                            <tr>
                                <td><strong>Cấp Bậc:</strong></td>
                                <td>' . htmlspecialchars($user['role_name']) . '</td>
                            </tr>
                            <tr>
                                <td class="strong"><strong>Email:</strong></td>
                                <td>' . htmlspecialchars($user['email']) . '</td>
                            </tr>
                            <tr>
                                <td class="strong"><strong>Địa Chỉ:</strong></td>
                                <td>' . htmlspecialchars($user['address']) . '</td>
                            </tr>
                            <tr>
                                <td class="strong"><strong>Số Điện Thoại:</strong></td>
                                <td>' . htmlspecialchars($user['phone_number']) . '</td>
                            </tr>
                            <tr>
                                <td class="strong"><strong>Ngày Sinh:</strong></td>
                                <td>' . date('d/m/Y', strtotime($user['birthdate'])) . '</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="text-center">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editUserModal">
                            Chỉnh sửa thông tin
                        </button>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4 class="modal-title" id="editUserModalLabel">Chỉnh sửa thông tin</h4>
                            </div>
                            <div class="modal-body">
                                <form id="editUserForm" method="post">
                                    <input type="hidden" name="action" value="update_info">
                                    <div class="form-group">
                                        <label for="cccd">CCCD/CMND:</label>
                                        <input type="text" class="form-control" id="cccd" name="cccd" value="' . htmlspecialchars($user['cccd']) . '">
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" class="form-control" id="email" name="email" value="' . htmlspecialchars($user['email']) . '">
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Địa chỉ:</label>
                                        <input type="text" class="form-control" id="address" name="address" value="' . htmlspecialchars($user['address']) . '">
                                    </div>
                                    <div class="form-group">
                                        <label for="phone">Số điện thoại:</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="' . htmlspecialchars($user['phone_number']) . '">
                                    </div>
                                    <div class="form-group">
                                        <label for="birthdate">Ngày sinh:</label>
                                        <input type="date" class="form-control" id="birthdate" name="birthdate" value="' . date('Y-m-d', strtotime($user['birthdate'])) . '">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>';
        } else {
            echo 'Không có dữ liệu';
        }
    } else {
        echo 'Không thể kết nối đến cơ sở dữ liệu';
    }
}


public function uploadAvatar($userId, $file) {
$targetDir = "../../assets/img/";
$targetFile = $targetDir . basename($file["name"]);
$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

// Kiểm tra xem tệp có phải là hình ảnh không
$check = getimagesize($file["tmp_name"]);
if ($check === false) {
return "Tệp không phải là hình ảnh.";
}

// Kiểm tra kích thước tệp
if ($file["size"] > 500000) {
return "Kích thước tệp quá lớn.";
}

// Chỉ cho phép các định dạng hình ảnh nhất định
if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
return "Chỉ cho phép các định dạng JPG, JPEG, PNG & GIF.";
}

// Di chuyển tệp đến thư mục đích
if (move_uploaded_file($file["tmp_name"], $targetFile)) {
// Cập nhật đường dẫn hình ảnh trong cơ sở dữ liệu
$sql = "UPDATE users SET avatar = :avatar WHERE id = :id";
$link = $this->getConnection();
$stmt = $link->prepare($sql);
$stmt->bindParam(':avatar', $file["name"]);
$stmt->bindParam(':id', $userId);
if ($stmt->execute()) {
return "Hình ảnh đã được tải lên thành công.";
} else {
return "Có lỗi xảy ra khi cập nhật cơ sở dữ liệu.";
}
} else {
return "Có lỗi xảy ra khi tải lên hình ảnh.";
}
}

}

?>