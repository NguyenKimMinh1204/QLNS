<?php
include '../../db/connetion.php';


class department extends Database{
    public function loaddulieu()
{
    $sql='SELECT *FROM departments ORDER by id ASC';
    $link = $this->getConnection();
    
    // Kiểm tra kết nối
    if ($link) {
        // Thực hiện truy vấn
        $stmt = $link->prepare($sql);
        $stmt->execute();

        // Lấy tất cả dữ liệu
        $ketqua = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $i = count($ketqua);
        
        if ($i > 0) {
            echo '<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 100px;">Mã phòng</th>
                            <th style="width: 550px;">Tên Phòng</th>                            
                            <th style="width: 250px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            foreach ($ketqua as $row) {
                $id = $row['id'];
                $name = $row['department_name'];
                $maphong = $row['maphong']; // Lấy mã phòng từ dữ liệu
                
                echo '                
                    <tr>
                        <td>' . htmlspecialchars($maphong) . '</td>
                        <td>' . htmlspecialchars($name) . '</td>
                        <td>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#editModal" data-id="' . htmlspecialchars($id) . '" data-name="' . htmlspecialchars($name) . '" data-maphong="' . htmlspecialchars($maphong) . '">Sửa phòng ban</button>
                             <button class="btn btn-danger delete-btn" data-id="' . htmlspecialchars($id) . '">Xóa phòng ban</button>
                        </td>
                        
                    </tr>';
            }

            echo '</tbody>
                </table>';
        } else {
            echo 'Không có dữ liệu';
        }

        // Đóng kết nối không cần thiết vì PDO tự động đóng khi hết phạm vi
    } else {
        echo 'Không thể kết nối đến cơ sở dữ liệu';
    }
}
    public function them_phong_ban($dept_name, $maphong) {
    $dbh = $this->getConnection();
    $stmt = $dbh->prepare("INSERT INTO departments (department_name, maphong) VALUES (:dept_name, :maphong)");
    $stmt->bindParam(':dept_name', $dept_name);
    $stmt->bindParam(':maphong', $maphong); // Thêm mã phòng
    
    return $stmt->execute(); // Trả về true nếu thành công, false nếu thất bại
}
    public function cap_nhat_phong_ban($id, $giatri, $maphong) {
    $dbh = $this->getConnection();
    $stmt = $dbh->prepare("UPDATE departments SET department_name = :value, maphong = :maphong WHERE id = :id");
    $stmt->bindParam(':value', $giatri);
    $stmt->bindParam(':maphong', $maphong); // Cập nhật mã phòng
    $stmt->bindParam(':id', $id);
    return $stmt->execute() ? 1 : 0;
    }
    public function xoabypara($tenbang, $id) {
    $dbh = $this->getConnection();
    $stmt = $dbh->prepare("DELETE FROM $tenbang WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $id);
    return $stmt->execute() ? 1 : 0;
}

}


?>