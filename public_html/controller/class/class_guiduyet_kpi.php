<?php
include ('../../db/connetion.php');
class duyetkpi extends Database{
    public function getDepartmentIdByUserId($user_id) {
    $link = $this->getConnection();
    $sql = 'SELECT department_id FROM users WHERE id = :user_id';

    try {
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        $department_id = $stmt->fetchColumn();
        return $department_id ? $department_id : null;
    } catch (PDOException $e) {
        // Handle database error
        return 'Error: ' . $e->getMessage();
    }
}
public function loadITTasksByMonthYearUser($user_id, $month, $year) {
    $sql = "SELECT ek.emp_kpi_id, ek.kpi_personal_id, ek.user_id, ek.progress, u.full_name, 
            ek.link_web, u.manv, kl.log_id, kl.progress_update, kl.result_detail, kl.updated_at, kl.status, kp.kpi_name, kl.reason
            FROM employee_kpis ek 
            INNER JOIN users u ON u.id = ek.user_id 
            INNER JOIN kpi_personal kp ON kp.id = ek.kpi_personal_id 
            INNER JOIN kpi_progress_logs kl ON kl.emp_kpi_id = ek.emp_kpi_id 
            WHERE u.id = :user_id AND MONTH(kl.updated_at) = :month AND YEAR(kl.updated_at) = :year";

    try {
        $link = $this->getConnection();
        
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo '<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã NV</th>
                            <th>Tên</th>
                            <th>Tên công việc</th>
                            <th>Cập nhật tiến độ</th>
                            <th>Tiến độ tổng</th>
                            <th>Chi tiết kết quả</th>
                            <th>Lý do từ chối</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            if ($result && count($result) > 0) {
                $stt = 1;
                foreach ($result as $row) {
                    $pr = $row['progress'] + $row['progress_update'];
                    echo '<tr>
                            <td>' . $stt++ . '</td>
                            <td>' . htmlspecialchars($row['manv']) . '</td>
                            <td>' . htmlspecialchars($row['full_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                            <td>' . htmlspecialchars($row['progress_update']) . '</td>
                            <td>' . htmlspecialchars($pr) . '</td>
                            <td>' . htmlspecialchars($row['reason']) . '</td>
                            <td>' . htmlspecialchars($row['result_detail']) . '</td>
                            <td>';
                    
                    switch($row['status']) {
                        case 'pending':
                            echo '<span class="label label-warning">Đang chờ</span>';
                            break;
                        case 'approved':
                            echo '<span class="label label-success">Đã duyệt</span>';
                            break;
                        case 'rejected':
                            echo '<span class="label label-danger">Từ chối</span>';
                            break;
                    }
                    
                    echo '</td></tr>';
                }
            } else {
                echo '<tr><td colspan="9" class="text-center">Không có dữ liệu</td></tr>';
            }
            
            echo '</tbody>
                </table>';
        } else {
            echo '<div class="alert alert-danger">Không thể kết nối đến cơ sở dữ liệu</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
    }
}
public function loadKPIByMonthYearUser($user_id, $month, $year) {
    $sql = "SELECT ek.emp_kpi_id, ek.kpi_personal_id, ek.user_id, ek.progress, u.full_name, 
            ek.link_web, u.manv, kl.log_id, kl.progress_update, kl.result_detail, kl.updated_at, kl.status, kp.kpi_name, kl.reason
            FROM employee_kpis ek 
            INNER JOIN users u ON u.id = ek.user_id 
            INNER JOIN kpi_personal kp ON kp.id = ek.kpi_personal_id 
            INNER JOIN kpi_progress_logs kl ON kl.emp_kpi_id = ek.emp_kpi_id 
            WHERE u.id = :user_id AND MONTH(kl.updated_at) = :month AND YEAR(kl.updated_at) = :year";

    try {
        $link = $this->getConnection();
        
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo '<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã NV</th>
                            <th>Tên</th>
                            <th>Tên công việc</th>
                            <th>Cập nhật tiến độ</th>
                            <th>Tiến độ tổng</th>
                            <th>Chi tiết kết quả</th>
                            <th>Lý do từ chối</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            if ($result && count($result) > 0) {
                $stt = 1;
                foreach ($result as $row) {
                    $pr = $row['progress'] + $row['progress_update'];
                    echo '<tr>
                            <td>' . $stt++ . '</td>
                            <td>' . htmlspecialchars($row['manv']) . '</td>
                            <td>' . htmlspecialchars($row['full_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                            <td>' . htmlspecialchars($row['progress_update']) . '</td>
                            <td>' . htmlspecialchars($pr) . '</td>
                            <td>' . htmlspecialchars($row['reason']) . '</td>
                            <td>' . htmlspecialchars($row['result_detail']) . '</td>
                            <td>';
                    
                    switch($row['status']) {
                        case 'pending':
                            echo '<span class="label label-warning">Đang chờ</span>';
                            break;
                        case 'approved':
                            echo '<span class="label label-success">Đã duyệt</span>';
                            break;
                        case 'rejected':
                            echo '<span class="label label-danger">Từ chối</span>';
                            break;
                    }
                    
                    echo '</td></tr>';
                }
            } else {
                echo '<tr><td colspan="9" class="text-center">Không có dữ liệu</td></tr>';
            }
            
            echo '</tbody>
                </table>';
        } else {
            echo '<div class="alert alert-danger">Không thể kết nối đến cơ sở dữ liệu</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
    }
}

}


?>