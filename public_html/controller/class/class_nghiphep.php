<?php
include '../../db/connetion.php';

class nghiphep extends Database {
    // Thêm đơn xin nghỉ phép mới
    public function addLeaveRequest($user_id, $department_id, $type, $reason, $start_date, $end_date) {
        $sql = "INSERT INTO leave_requests (user_id, department_id, type, reason, start_date, end_date, status) 
                VALUES (:user_id, :department_id, :type, :reason, :start_date, :end_date, 'pending')";
        
        $link = $this->getConnection();
        
        try {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':department_id', $department_id);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':reason', $reason);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            
            if($stmt->execute()) {
                return "Gửi đơn thành công!";
            } else {
                return "Có lỗi xảy ra khi gửi đơn!";
            }
        } catch(PDOException $e) {
            return "Lỗi: " . $e->getMessage();
        }
    }

    // Lấy lịch sử đơn xin phép của một nhân viên
    public function getLeaveHistory($user_id) {
        $sql = "SELECT * FROM leave_requests 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC";
        
        $link = $this->getConnection();
        
        try {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return [];
        }
    }

    // Thêm function mới để lấy department_id
    public function getDepartmentId($user_id) {
        $sql = "SELECT department_id FROM users 
                WHERE id = :user_id 
                LIMIT 1";
        
        $link = $this->getConnection();
        
        try {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result ? $result['department_id'] : null;
        } catch(PDOException $e) {
            return null;
        }
    }

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
    public function showLeaveRequests($department_id) {
        $sql = "SELECT lr.*, u.full_name, d.department_name as department_name, lr.reason_reject	
                FROM leave_requests lr 
                JOIN users u ON lr.user_id = u.id 
                JOIN departments d ON lr.department_id = d.id 
                WHERE d.id = :department_id
                AND u.role_id=2";
        
        $link = $this->getConnection();
        
        try {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':department_id', $department_id);
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
    public function showLeaveRequestsByMonthYear($department_id, $month, $year) {
        $sql = "SELECT lr.*, u.full_name, d.department_name as department_name, lr.reason_reject	
                FROM leave_requests lr 
                JOIN users u ON lr.user_id = u.id 
                JOIN departments d ON lr.department_id = d.id 
                WHERE d.id = :department_id 
                AND u.role_id=2
                AND MONTH(lr.start_date) = :month 
                AND YEAR(lr.start_date) = :year";
        
        $link = $this->getConnection();
        
        try {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':department_id', $department_id);
            $stmt->bindParam(':month', $month);
            $stmt->bindParam(':year', $year);
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
}