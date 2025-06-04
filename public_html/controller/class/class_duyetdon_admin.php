<?php
include '../../db/connetion.php';

class duyetdon extends Database {
  // Cập nhật trạng thái đơn xin nghỉ phép
    public function updateLeaveStatus($request_id, $status) {
        $sql = "UPDATE leave_requests 
                SET status = :status 
                WHERE id = :request_id";
        
        $link = $this->getConnection();
        
        try {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':request_id', $request_id);
            return $stmt->execute();
        } catch(PDOException $e) {
            return false;
        }
    }

    // Cập nhật trạng thái đơn xin nghỉ phép với kiểm tra pending
    public function updateLeaveStatusWithCheck($request_id, $status, $reason_reject = '') {
        $sql = "UPDATE leave_requests 
                SET status = :status, 
                    updated_at = NOW(), 
                    reason_reject = :reason_reject
                WHERE id = :request_id 
                AND status = 'pending'";
        
        $link = $this->getConnection();
        
        try {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':reason_reject', $reason_reject);
            $stmt->bindParam(':request_id', $request_id);
            $stmt->execute();
            
            // Kiểm tra số lượng bản ghi bị ảnh hưởng
            $affectedRows = $stmt->rowCount();
            error_log("Affected Rows: " . $affectedRows); // Ghi lại số lượng bản ghi bị ảnh hưởng
            return $affectedRows; // Trả về số lượng bản ghi bị ảnh hưởng
        } catch(PDOException $e) {
            error_log("Error: " . $e->getMessage()); // Ghi lại lỗi
            return false;
        }
    }

    // Hiển thị danh sách đơn xin nghỉ phép
    public function showLeaveRequests() {
        $sql = "SELECT lr.*, u.full_name, d.department_name as department_name, lr.reason_reject	
                FROM leave_requests lr 
                JOIN users u ON lr.user_id = u.id 
                JOIN departments d ON lr.department_id = d.id 
                
                WHERE u.role_id=1 ";
        
        $link = $this->getConnection();
        
        try {
            $stmt = $link->prepare($sql);
            
            $stmt->execute();
            $leave_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $stt = 1;
            foreach ($leave_requests as $request) {
                $id=$request['id'];
                echo "<tr>";
                echo "<td>" . $stt++ . "</td>";
                echo "<td>" . $request['full_name'] . "</td>";
                echo "<td>" . $request['department_name'] . "</td>";
                echo "<td>" . ($request['type'] == 'nghi_phep' ? 'Nghỉ phép' : 'Đi trễ') . "</td>";
                echo "<td>" . $request['reason'] . "</td>";
                echo "<td>" . date('d/m/Y H:i', strtotime($request['start_date'])) . "</td>";
                echo "<td>" . date('d/m/Y H:i', strtotime($request['end_date'])) . "</td>";
                echo "<td>";
                switch($request['status']) {
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
                echo "</td>";
                echo "<td>" . $request['reason_reject'] . "</td>";
                echo "<td>";
                if($request['status'] == 'pending') {
                    echo "<button class='btn btn-xs btn-success approve-btn' data-id='" . $request['id'] . "'><i class='glyphicon glyphicon-ok'></i> Duyệt</button> ";
                    echo '<button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#rejectReasonModal" data-idu="'. $id . '"><i class="glyphicon glyphicon-remove"></i> Từ chối</button>';
                }
                echo "</td>";
                echo "</tr>";
            }
        } catch(PDOException $e) {
            echo "<tr><td colspan='9'>Có lỗi xảy ra khi tải dữ liệu: " . $e->getMessage() . "</td></tr>";
        }
    }

    // Hiển thị danh sách đơn xin nghỉ phép theo department_id, month, year
    public function showLeaveRequestsByMonthYear( $month, $year) {
        $sql = "SELECT lr.*, u.full_name, d.department_name as department_name, lr.reason_reject	
                FROM leave_requests lr 
                JOIN users u ON lr.user_id = u.id 
                JOIN departments d ON lr.department_id = d.id 
                WHERE  u.role_id=1 
                AND MONTH(lr.start_date) = :month 
                AND YEAR(lr.start_date) = :year";
        
        $link = $this->getConnection();
        
        try {
            $stmt = $link->prepare($sql);
           
            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':year', $year);
            $stmt->execute();
            $leave_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $stt = 1;
            foreach ($leave_requests as $request) {
                $id=$request['id'];
                echo "<tr>";
                echo "<td>" . $stt++ . " </td>";
                echo "<td>" . $request['full_name'] . "</td>";
                echo "<td>" . $request['department_name'] . "</td>";
                echo "<td>" . ($request['type'] == 'nghi_phep' ? 'Nghỉ phép' : 'Đi trễ') . "</td>";
                echo "<td>" . $request['reason'] . "</td>";
                echo "<td>" . date('d/m/Y H:i', strtotime($request['start_date'])) . "</td>";
                echo "<td>" . date('d/m/Y H:i', strtotime($request['end_date'])) . "</td>";
                echo "<td>";
                switch($request['status']) {
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
                echo "</td>";
                echo "<td>" . $request['reason_reject'] . "</td>";
                echo "<td>";
                if($request['status'] == 'pending') {
                    echo "<button class='btn btn-xs btn-success approve-btn' data-id='" . $request['id'] . "'><i class='glyphicon glyphicon-ok'></i> Duyệt</button> ";
                    
                    echo '<button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#rejectReasonModal" data-idu="'. $id . '"><i class="glyphicon glyphicon-remove"></i> Từ chối</button>';

                    
                }
                echo "</td>";
                echo "</tr>";
            }
        } catch(PDOException $e) {
            echo "<tr><td colspan='9'>Có lỗi xảy ra khi tải dữ liệu: " . $e->getMessage() . "</td></tr>";
        }
    }
    
}