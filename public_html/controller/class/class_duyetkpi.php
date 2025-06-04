<?php
include ('../../db/connetion.php');
class duyetkpim extends Database{
    public function cap_nhat_department_kpi_progress($dept_kpi_id, $progress) {
        $dbh = $this->getConnection();
        
        // Lấy giá trị progress hiện tại
        $stmt = $dbh->prepare("SELECT progress FROM department_kpis WHERE dept_kpi_id = :dept_kpi_id");
        $stmt->bindParam(':dept_kpi_id', $dept_kpi_id);
        $stmt->execute();
        $current_progress = $stmt->fetchColumn();
        
        // Tính toán progress mới
        $new_progress = $current_progress + $progress;
        
        // Cập nhật progress mới
        $stmt = $dbh->prepare("UPDATE department_kpis SET progress = :progress WHERE dept_kpi_id = :dept_kpi_id");
        $stmt->bindParam(':dept_kpi_id', $dept_kpi_id);
        $stmt->bindParam(':progress', $new_progress);
        
        return $stmt->execute() ? 1 : 0;
    }
      public function cap_nhat_employee_kpi_progress($emp_kpi_id, $progress) {
        $dbh = $this->getConnection();
        
        // Lấy giá trị progress hiện tại
        $stmt = $dbh->prepare("SELECT progress FROM employee_kpis WHERE emp_kpi_id = :emp_kpi_id");
        $stmt->bindParam(':emp_kpi_id', $emp_kpi_id);
        $stmt->execute();
        $current_progress = $stmt->fetchColumn();
        
        // Tính toán progress mới
        $new_progress = $current_progress + $progress;
        
        // Cập nhật progress mới và trạng thái
        $stmt = $dbh->prepare("UPDATE employee_kpis SET progress = :progress WHERE emp_kpi_id = :emp_kpi_id");
        $stmt->bindParam(':emp_kpi_id', $emp_kpi_id);
        $stmt->bindParam(':progress', $new_progress);
        $stmt->execute();
        
        // Cập nhật trạng thái trong bảng kpi_progress_logs
        return $stmt->execute() ? 1 : 0;
    }

   
    public function exportKPIWithActions($department_id, $month, $year) {
        $sql = "SELECT ek.emp_kpi_id, ek.kpi_personal_id, ek.user_id, ek.progress, u.full_name, ek.dept_kpi_id,
        ek.link_web, u.manv, kl.log_id, kl.progress_update, kl.result_detail, kl.updated_at, kl.status, kp.kpi_name, kl.reason
        FROM employee_kpis ek 
        INNER JOIN users u ON u.id = ek.user_id 
        INNER JOIN kpi_personal kp ON kp.id = ek.kpi_personal_id 
        INNER JOIN kpi_progress_logs kl ON kl.emp_kpi_id=ek.emp_kpi_id 
        WHERE u.department_id = :department_id 
        AND MONTH(kl.updated_at) = :month 
        AND YEAR(kl.updated_at) = :year;";

        try {
            $link = $this->getConnection();
            
            if ($link) {
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
                $stmt->bindParam(':month', $month, PDO::PARAM_INT);
                $stmt->bindParam(':year', $year, PDO::PARAM_INT);
                $stmt->execute();
                
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo '
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã NV</th>
                                <th>Tên</th>
                                <th>Tên công việc</th>
                                <th>Cập nhật tiến độ</th>
                                <th>Tiến độ tổng</th>
                                <th>Thời gian gửi</th>
                                <th>Lý do từ chối</th>
                                <th>Ghi chú</th>
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
                                <td>' . htmlspecialchars($row['updated_at']) . '</td>
                                <td>' . htmlspecialchars($row['reason']) . '</td>
                                <td>' . htmlspecialchars($row['result_detail']) . '</td>
                                <td>';
                                
                                if ($row['status'] === 'pending') {
                                    echo '<button id="approve-btn-' . htmlspecialchars($row['log_id']) . '" name="approve" class="btn btn-success" data-dep-kpi-id="' . htmlspecialchars($row['dept_kpi_id']) . '" data-log-id="' . htmlspecialchars($row['log_id']) . '" data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '" 
                                    data-progress="' . htmlspecialchars($row['progress_update']) . '">Duyệt</button>
                                    <button id="reject-btn-' . htmlspecialchars($row['log_id']) . '" name="reject" class="btn btn-danger" data-log-id="' . htmlspecialchars($row['log_id']) . '" data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '" >Từ chối</button>';
                                } elseif ($row['status'] === 'approved') {
                                    echo '<span style="color: green;">Đã duyệt</span>';
                                } elseif ($row['status'] === 'rejected') {
                                    echo '<span style="color: red;">Đã từ chối</span>';
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

    public function update_kpi_progress_log_status($log_id, $status) {
        $dbh = $this->getConnection();
        
        // Cập nhật trạng thái trong bảng kpi_progress_logs
        $stmt = $dbh->prepare("UPDATE kpi_progress_logs SET status = :status WHERE log_id = :log_id");
        $stmt->bindParam(':log_id', $log_id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        
        return $stmt->execute() ? 1 : 0;
    }
public function tu_choi_employee_kpi_progress($log_id, $reason_reject) {
        try {
            // Update the status in the kpi_progress_logs table
           
        $dbh = $this->getConnection();
        
        // Cập nhật trạng thái trong bảng kpi_progress_logs
        $stmt = $dbh->prepare("UPDATE kpi_progress_logs SET status = 'rejected', reason = :reason WHERE log_id = :log_id");
        $stmt->bindParam(':log_id', $log_id, PDO::PARAM_INT);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':reason', $reason_reject, PDO::PARAM_STR);
        
            // Return 1 if the update is successful, otherwise return 0
          return $stmt->execute() ? 1 : 0;
        } catch (Exception $e) {
            // Log the exception or handle it as needed
            return 0;
        }
    }

    public function duyet_employee_kpi_progress($emp_kpi_id, $progress, $log_id, $dept_kpi_id) {
        try {
            // Update employee KPI progress
            $empUpdateSuccess = $this->cap_nhat_employee_kpi_progress($emp_kpi_id, $progress);
            if (!$empUpdateSuccess) {
                return 2; // Return -2 if employee KPI update fails
            }

            // Update department KPI progress
            $deptUpdateSuccess = $this->cap_nhat_department_kpi_progress($dept_kpi_id, $progress);
            if (!$deptUpdateSuccess) {
                return 3; // Return -3 if department KPI update fails
            }

            // Update KPI progress log status
            $approveSuccess = $this->update_kpi_progress_log_status($log_id, 'approved');
            if (!$approveSuccess) {
                return 4; // Return -4 if log status update fails
            }

            // Return 1 if all updates are successful
            return 1;
        } catch (Exception $e) {
            // Log the exception or handle it as needed
            return 0; // Return 0 if an exception occurs
        }
    }
     public function duyet_employee_it_kpi_progress($emp_kpi_id, $progress, $log_id, $dept_kpi_id) {
        try {
            // Update employee KPI progress
            $empUpdateSuccess = $this->cap_nhat_employee_kpi_progress($emp_kpi_id, $progress);
            if (!$empUpdateSuccess) {
                return 2; // Return -2 if employee KPI update fails
            }

            // Update department KPI progress
            $deptUpdateSuccess = $this->cap_nhat_department_kpi_progress($dept_kpi_id, $progress);
            if (!$deptUpdateSuccess) {
                return 3; // Return -3 if department KPI update fails
            }

            // Update KPI progress log status
            $approveSuccess = $this->update_kpi_progress_log_status($log_id, 'approved');
            if (!$approveSuccess) {
                return 4; // Return -4 if log status update fails
            }

            // Return 1 if all updates are successful
            return 1;
        } catch (Exception $e) {
            // Log the exception or handle it as needed
            return 0; // Return 0 if an exception occurs
        }
    }

    // Add a method to get department KPI ID from employee KPI ID if needed
    private function getDepartmentKpiIdByEmpKpiId($emp_kpi_id) {
        // Implement logic to retrieve department KPI ID
    }

}
?>