<?php
include ('../../db/connetion.php');
class tientrinh_a extends Database {
     public function loadekpi($dept_kpi_id){
        $sql = "SELECT ek.emp_kpi_id, ek.dept_kpi_id,ek.kpi_personal_id, ek.user_id, ek.assigned_value, ek.assigned_date,
        ek.due_date, ek.status_id, sk.name_status_kpi, ek.progress, u.full_name, kp.kpi_lib_id, kp.kpi_name, kp.kpi_description 
        FROM employee_kpis ek 
        INNER JOIN department_kpis dk ON dk.dept_kpi_id = ek.dept_kpi_id 
        INNER JOIN users u ON u.id = ek.user_id 
        INNER JOIN kpi_personal kp ON kp.id = ek.kpi_personal_id 
        INNER JOIN status_kpi sk ON sk.id = ek.status_id 
        where ek.dept_kpi_id=:dept_kpi_id
        ";

        try {
            $link = $this->getConnection();
            
            if ($link) {
                $stmt = $link->prepare($sql);
               $stmt->bindParam(':dept_kpi_id', $dept_kpi_id, PDO::PARAM_INT);
                $stmt->execute();
                
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo '<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên nhân viên</th>
                                <th>Tên KPI</th>
                                <th>Mô tả</th>
                                <th>Giá trị được giao</th>
                                <th>Giá trị thực tế</th>
                                <th>Tỷ lệ đạt được</th>
                                <th>Ngày giao</th>
                                <th>Ngày hết hạn</th>
                                <th>Trạng thái</th>
                                
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>';
                $sum_target=0;
                $sum_actual=0;
                $sum_tiendo=0;
                $dem=1;
                if ($result && count($result) > 0) {
                    foreach ($result as $row) {
                        // Tính tỷ lệ phần trăm
                        $completion_rate = ($row['assigned_value'] > 0) ? 
                            round(($row['progress'] / $row['assigned_value']) * 100, 2) : 0;
                        
                        $assigned_value=htmlspecialchars($row['assigned_value']);
                        $progress=htmlspecialchars($row['progress']);
                        $sum_target+= $assigned_value;
                        $sum_actual+=$progress;
                        $sum_tiendo+=$completion_rate;
                        echo '<tr>
                                <td>'.$dem.'</td>
                                <td>' . htmlspecialchars($row['full_name']) . '</td>
                                <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                                <td>' . htmlspecialchars($row['kpi_description']) . '</td>
                                <td>' . htmlspecialchars($row['assigned_value']) . '</td>
                                <td>' . htmlspecialchars($row['progress']) . '</td>
                                <td>' . $completion_rate . '%</td>
                                <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                                <td>' . htmlspecialchars($row['due_date']) . '</td>
                                <td>' . htmlspecialchars($row['name_status_kpi']) . '</td>
                                
                                
                                <td> <button type="button" 
                                            class="btn btn-primary btn-sm" 
                                            data-toggle="modal"
                                            data-target="#selectProgressModal"
                                            data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '"
                                           >
                                        Xem tiến độ
                                    </button></td>
                            </tr>';
                            $dem++;
                            
                    }
                    $sum_tiendo1=$sum_tiendo/($dem-1);
                    echo'<tr>
                            <td>'.$dem.'</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>'.$sum_target.'</td>
                            <td>'.$sum_actual.'</td>
                            <td>'.$sum_tiendo1.'%</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>';
                } else {
                    echo '<tr><td colspan="8" class="text-center">Không có dữ liệu</td></tr>';
                }
                
                echo '</tbody>
                    </table>';
            } else {
                echo '<div class="alert alert-danger">Không thể kết nối đến cơ sở dữ liệu</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger"> ' . $e->getMessage() . '</div>';
        }
    }
    public function get_kpi_progress_logs() {
        $sql = "SELECT kl.emp_kpi_id, kl.progress_update, kl.result_detail, kl.updated_at 
                FROM kpi_progress_logs kl 
                INNER JOIN employee_kpis ek ON ek.emp_kpi_id = kl.emp_kpi_id 
                WHERE ek.user_id = :user_id 
                ORDER BY kl.updated_at DESC";

        try {
            $link = $this->getConnection();
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmt->execute();
            
            $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            // Debugging: Check if logs are returned
            if (empty($logs)) {
                echo '<div class="alert alert-warning">Không có dữ liệu tiến độ KPI.</div>';
            }
            return $logs;
            
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return [];
        }
    }
    public function get_single_progress($emp_kpi_id) {
        $sql = "SELECT kl.progress_update, kl.result_detail, kl.updated_at 
        FROM kpi_progress_logs kl 
        WHERE kl.emp_kpi_id = :emp_kpi_id 
        ORDER BY kl.updated_at DESC;";

        try {
            $link = $this->getConnection();
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':emp_kpi_id', $emp_kpi_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $logs; // Trả về dữ liệu logs
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return [];
        }
    }
}


?>