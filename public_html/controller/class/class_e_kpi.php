<?php
include ('../../db/connetion.php');
class ekpi extends Database{
  
    public function loadekpi($user_id){
        $sql = "SELECT ek.emp_kpi_id, ek.dept_kpi_id, ek.kpi_personal_id, ek.user_id, ek.assigned_value,
         ek.assigned_date, ek.due_date, ek.status_id, sk.name_status_kpi, ek.progress, u.full_name, 
         kp.kpi_lib_id, kp.kpi_name, kp.kpi_description 
         FROM employee_kpis ek 
         INNER JOIN department_kpis dk ON dk.dept_kpi_id = ek.dept_kpi_id 
         INNER JOIN users u ON u.id = ek.user_id 
         INNER JOIN kpi_personal kp ON kp.id = ek.kpi_personal_id 
         INNER JOIN status_kpi sk ON sk.id = ek.status_id 
        WHERE 
            u.id = :user_id";

        try {
            $link = $this->getConnection();
            
            if ($link) {
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();
                
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo '<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Tên nhân viên</th>
                                <th>Tên KPI</th>
                                <th>Mô tả</th>
                                <th>Giá trị được giao</th>
                                <th>Giá trị thực tế</th>
                                <th>Tỷ lệ đạt</th>
                                <th>Ngày giao</th>
                                <th>Ngày hết hạn</th>
                                <th>Trạng thái</th>
                                
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                if ($result && count($result) > 0) {
                    foreach ($result as $row) {
                        // Tính tỷ lệ phần trăm
                        $completion_rate = ($row['assigned_value'] > 0) ? 
                            round(($row['progress'] / $row['assigned_value']) * 100, 2) : 0;
                        
                        // Get the KPI status
                        $status = $this->getKPIStatus($completion_rate, $row['due_date']);
                        
                        echo '<tr>
                                <td>' . htmlspecialchars($row['full_name']) . '</td>
                                <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                                <td>' . htmlspecialchars($row['kpi_description']) . '</td>
                                <td>' . htmlspecialchars($row['assigned_value']) . '</td>
                                <td>' . htmlspecialchars($row['progress']) . '</td>
                                <td>' . $completion_rate . '%</td>
                                <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                                <td>' . htmlspecialchars($row['due_date']) . '</td>
                                <td>' . htmlspecialchars($status) . '</td>';
                                if (new DateTime() > new DateTime($row['due_date'])) {
                        // Chỉ hiển thị nút Xem tiến độ
                                echo '<td>
                                        <button type="button" 
                                                class="btn btn-primary btn-sm my-2" 
                                                data-toggle="modal"
                                                data-target="#selectProgressModal"
                                                data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '">
                                            Xem tiến độ
                                        </button>
                                    </td>';
                                    } else {
                                        // Hiển thị cả Cập nhật tiến độ và Xem tiến độ
                                        echo '<td>
                                                <button type="button" 
                                                        class="btn btn-primary btn-sm my-2" 
                                                        data-toggle="modal"
                                                        data-target="#updateProgressModal"
                                                        data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '"
                                                        data-dept-kpi-id="' . htmlspecialchars($row['dept_kpi_id']) . '">
                                                    Cập nhật tiến độ
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-primary btn-sm my-2" 
                                                        data-toggle="modal"
                                                        data-target="#selectProgressModal"
                                                        data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '">
                                                    Xem tiến độ
                                                </button>
                                            </td>';
                                    }

                                    echo '</tr>';
                                }
                              
                            
                } else {
                    echo '<tr><td colspan="8" class="text-center">Không có dữ liệu</td></tr>';
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
    $sql = "SELECT ek.emp_kpi_id, ek.dept_kpi_id, ek.kpi_personal_id, ek.user_id, ek.assigned_value,
            ek.assigned_date, ek.due_date, ek.status_id, sk.name_status_kpi, ek.progress, u.full_name, 
            kp.kpi_lib_id, kp.kpi_name, kp.kpi_description 
            FROM employee_kpis ek 
            INNER JOIN department_kpis dk ON dk.dept_kpi_id = ek.dept_kpi_id 
            INNER JOIN users u ON u.id = ek.user_id 
            INNER JOIN kpi_personal kp ON kp.id = ek.kpi_personal_id 
            INNER JOIN status_kpi sk ON sk.id = ek.status_id 
            WHERE u.id = :user_id AND MONTH(ek.assigned_date) = :month AND YEAR(ek.assigned_date) = :year";

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
                            <th>Tên nhân viên</th>
                            <th>Tên KPI</th>
                            <th>Mô tả</th>
                            <th>Giá trị được giao</th>
                            <th>Giá trị thực tế</th>
                            <th>Tỷ lệ đạt</th>
                            <th>Ngày giao</th>
                            <th>Ngày hết hạn</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            if ($result && count($result) > 0) {
                foreach ($result as $row) {
                    $dep=$this->getDepartmentIdByUserId($row['user_id']);
                    $completion_rate = ($row['assigned_value'] > 0) ? 
                        round(($row['progress'] / $row['assigned_value']) * 100, 2) : 0;
                        
                    $status = $this->getKPIStatus($completion_rate, $row['due_date']);
                        
                    echo '<tr>
                            <td>' . htmlspecialchars($row['full_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_description']) . '</td>
                            <td>' . htmlspecialchars($row['assigned_value']) . '</td>
                            <td>' . htmlspecialchars($row['progress']) . '</td>
                            <td>' . $completion_rate . '%</td>
                            <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                            <td>' . htmlspecialchars($row['due_date']) . '</td>
                            <td>' . htmlspecialchars($status) . '</td>';
                            
                    if (new DateTime() > new DateTime($row['due_date'])) {
                        echo '<td>
                                <button type="button" 
                                        class="btn btn-primary btn-sm my-2" 
                                        data-toggle="modal"
                                        data-target="#selectProgressModal"
                                        data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '">
                                    Xem tiến độ
                                </button>
                              </td>';
                    } else {
                       
                            echo '<td>
                                <button type="button" 
                                        class="btn btn-primary btn-sm my-2" 
                                        data-toggle="modal"
                                        data-target="#updateProgressModal"
                                        data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '"
                                        data-dept-kpi-id="' . htmlspecialchars($row['dept_kpi_id']) . '">
                                    Cập nhật tiến độ
                                </button>
                                <button type="button" 
                                        class="btn btn-primary btn-sm my-2" 
                                        data-toggle="modal"
                                        data-target="#selectProgressModal"
                                        data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '">
                                    Xem tiến độ
                                </button>
                              </td>';
                            
                        
                    }

                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="10" class="text-center">Không có dữ liệu</td></tr>';
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
public function loadITTasksByMonthYearUser($user_id, $month = null, $year = null) {
    $sql = "SELECT DISTINCT ek.emp_kpi_id, ek.dept_kpi_id, ek.kpi_personal_id, ek.user_id, ek.assigned_value, 
            ek.assigned_date, ek.due_date, ek.status_id, sk.name_status_kpi, ek.progress, u.full_name, 
            kp.kpi_lib_id, kp.kpi_name, kp.kpi_description, ek.link_web, u.manv
            FROM employee_kpis ek 
            INNER JOIN department_kpis dk ON dk.dept_kpi_id = ek.dept_kpi_id 
            INNER JOIN users u ON u.id = ek.user_id 
            INNER JOIN kpi_personal kp ON kp.id = ek.kpi_personal_id 
            INNER JOIN status_kpi sk ON sk.id = ek.status_id 
            WHERE u.id = :user_id";

    // Add conditions for month and year if they are provided
    if ($month !== null) {
        $sql .= " AND MONTH(ek.assigned_date) = :month";
    }
    if ($year !== null) {
        $sql .= " AND YEAR(ek.assigned_date) = :year";
    }

    try {
        $link = $this->getConnection();
        
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            
            // Bind month and year parameters if they are provided
            if ($month !== null) {
                $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            }
            if ($year !== null) {
                $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
           
            
            echo '
            <table class="table table-bordered table-hover">
                    <thead>
                        <tr> 
                        <th><input type="checkbox" id="selectAll"></th>
                            <th>Tên</th>
                            <th>Mã NV</th>
                            <th>Link</th>
                            <th>Tên công việc</th>
                            <th>Mô tả công việc</th>
                            <th>Ngày giao</th>
                            <th>Ngày hết hạn</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            if ($result && count($result) > 0) {
                foreach ($result as $row) {
                    echo '<tr>
                            <td><input type="checkbox" name="task_ids[]" value="' . htmlspecialchars($row['emp_kpi_id']) . '"></td>
                            <td>' . htmlspecialchars($row['full_name']) . '</td>
                            <td>' . htmlspecialchars($row['manv']) . '</td>
                            <td style="word-wrap: break-word; max-width: 200px;"><a href="' . htmlspecialchars($row['link_web']) . '" target="_blank">' . htmlspecialchars($row['link_web']) . '</a></td>
                            <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_description']) . '</td>
                            <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                            <td>' . htmlspecialchars($row['due_date']) . '</td>
                            <td>' . htmlspecialchars($row['name_status_kpi']) . '</td>
                            <td>
                                <button type="button" 
                                                class="btn btn-primary btn-sm my-2" 
                                                data-toggle="modal"
                                                data-target="#updateITTaskProgressModal"
                                                data-assigned-value-it="' . htmlspecialchars($row['assigned_value']) . '"
                                                data-emp-kpi-id-it="' . htmlspecialchars($row['emp_kpi_id']) . '">
                                            Cập nhật tiến độ
                                        </button>

                                <button type="button" 
                                        class="btn btn-primary btn-sm my-2" 
                                        data-toggle="modal"
                                        data-target="#viewITTaskProgressModal"
                                        data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '">
                                    Xem tiến độ
                                </button>
                            </td>
                          </tr>';
                }
            } else {
                echo '<tr><td colspan="10" class="text-center">Không có dữ liệu</td></tr>';
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

private function updateTaskStatus($link, &$row) {
    $currentDate = new DateTime();
    $dueDateThreshold = new DateTime('2023-12-03'); // Assuming the threshold date is December 3rd

    $dueDate = new DateTime($row['due_date']);
    
    // Update status_id and due_date based on conditions
    if ($currentDate > $dueDateThreshold) {
        if ($row['progress'] >= $row['assigned_value']) {
            $row['status_id'] = 4; // hoàn thành
        } else {
            $row['status_id'] = 3; // chưa hoàn thành
        }
    } else {
        $row['status_id'] = 2; // đang làm
        $row['due_date'] = $dueDateThreshold->format('Y-m-d');
    }

    // Update the database with new status_id and due_date
    $updateStmt = $link->prepare("UPDATE employee_kpis SET status_id = :status_id, due_date = :due_date WHERE emp_kpi_id = :emp_kpi_id");
    $updateStmt->bindParam(':status_id', $row['status_id'], PDO::PARAM_INT);
    $updateStmt->bindParam(':due_date', $row['due_date']);
    $updateStmt->bindParam(':emp_kpi_id', $row['emp_kpi_id'], PDO::PARAM_INT);
    $updateStmt->execute();
}

    function getKPIStatus($percent, $due_date) {
    // Chuyển due_date thành timestamp
    $due_date_timestamp = strtotime($due_date);
    $current_date_timestamp = time(); // Lấy thời gian hiện tại

    // Xác định trạng thái
    if ($percent == 0) {
        return "chưa làm";
    } elseif ($percent < 100) {
        if ($due_date_timestamp < $current_date_timestamp) {
            return "chưa hoàn thành"; // Hết hạn mà chưa đạt 100%
        }
        return "đang làm";
    } elseif ($percent >= 100) {
        return "hoàn thành";
    }
}
private function getEmpKpiIdByManv($manv) {
    // Fetch the emp_kpi_id using manv (employee code)
    $dbh = $this->getConnection();
    $stmt = $dbh->prepare("SELECT emp_kpi_id FROM employee_kpis WHERE manv = :manv");
    $stmt->bindParam(':manv', $manv);
    $stmt->execute();
    
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['emp_kpi_id'] : null; // Return emp_kpi_id or null if not found
}

    public function them_kpi_progress_log($emp_kpi_id, $progress_update, $result_detail) {
        $dbh = $this->getConnection();
        $stmt = $dbh->prepare("INSERT INTO kpi_progress_logs (emp_kpi_id, progress_update, result_detail, updated_at,status) 
                               VALUES (:emp_kpi_id, :progress_update, :result_detail, NOW(),'pending')");
        $stmt->bindParam(':emp_kpi_id', $emp_kpi_id);
        $stmt->bindParam(':progress_update', $progress_update);
        $stmt->bindParam(':result_detail', $result_detail);
       
        
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
        
        // Cập nhật progress mới
        $stmt = $dbh->prepare("UPDATE employee_kpis SET progress = :progress WHERE emp_kpi_id = :emp_kpi_id");
        $stmt->bindParam(':emp_kpi_id', $emp_kpi_id);
        $stmt->bindParam(':progress', $new_progress);
        
        return $stmt->execute() ? 1 : 0;
    }
    public function them_kpi_result($dept_kpi_id, $result_value, $note) {
        $dbh = $this->getConnection();
        $stmt = $dbh->prepare("INSERT INTO kpi_result (dept_kpi_id, result_value, result_date, note) 
                               VALUES (:dept_kpi_id, :result_value, NOW(), :note)");
        $stmt->bindParam(':dept_kpi_id', $dept_kpi_id);
        $stmt->bindParam(':result_value', $result_value);
       
        $stmt->bindParam(':note', $note);
        
        return $stmt->execute() ? true : false; // Return true if successful, false if failed
    }
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
    public function capnhat_tiendo($emp_kpi_id, $dept_kpi_id, $result, $mota) {
        try {
            $dbh = $this->getConnection();
            $dbh->beginTransaction();

            // Add detailed error logging for each step
            if (!$this->them_kpi_progress_log($emp_kpi_id, $result, $mota)) {
                error_log("Failed at them_kpi_progress_log");
                throw new Exception("Failed to add progress log");
            }

            if (!$this->cap_nhat_employee_kpi_progress($emp_kpi_id, $result)) {
                error_log("Failed at cap_nhat_employee_kpi_progress");
                throw new Exception("Failed to update employee KPI progress");
            }

            if (!$this->them_kpi_result($dept_kpi_id, $result, $mota)) {
                error_log("Failed at them_kpi_result");
                throw new Exception("Failed to add KPI result");
            }

            if (!$this->cap_nhat_department_kpi_progress($dept_kpi_id, $result)) {
                error_log("Failed at cap_nhat_department_kpi_progress");
                throw new Exception("Failed to update department KPI progress");
            }

            $dbh->commit();
            return 1;
        } catch (Exception $e) {
            error_log("Error in capnhat_tiendo: " . $e->getMessage());
            $dbh->rollBack();
            return 0;
        }
    }
// public function addTransactionToKPI($transactionData) {
//     try {
//         // Step 1: Extract details from transaction description
//         $transaction_description = $transactionData['transaction_description']; // e.g., "24642428-REQ-92283031"
        
//         // Extract manv (employee code) and transaction code
//         $parts = explode('-', $transaction_description);
//         $manv = $parts[0];  // Employee code: "24642428"
//         $transaction_code = $parts[1]; // Transaction type: "REQ"
//         $transaction_id = $parts[2];  // Transaction ID: "92283031"
        
//         // Step 2: Fetch emp_kpi_id (Employee KPI ID) based on the manv (Employee Code)
//         $emp_kpi_id = $this->getEmpKpiIdByManv($manv);
//         if (!$emp_kpi_id) {
//             return 'Invalid employee code (manv).';
//         }
        
//         // Step 3: Prepare the data for inserting into kpi_progress_logs
//         $progress_update = $transactionData['transaction_amount']; // Transaction amount: "15500000.00"
//         $result_detail = "Transaction ID: $transaction_id, Description: $transaction_description"; // Result details
//         $updated_at = $transactionData['transaction_date']; // Transaction date: "2020-02-05 10:13:01"
        
//         // Step 4: Begin Transaction to ensure atomicity
//         $dbh = $this->getConnection();
//         $dbh->beginTransaction();
        
//         // Step 5: Insert into kpi_progress_logs
//         if (!$this->them_kpi_progress_log($emp_kpi_id, $progress_update, $result_detail)) {
//             throw new Exception("Failed to add progress log.");
//         }

//         // Step 6: Update employee KPI progress
//         if (!$this->cap_nhat_employee_kpi_progress($emp_kpi_id, $progress_update)) {
//             throw new Exception("Failed to update employee KPI progress.");
//         }

//         // Step 7: Update department KPI progress (Assuming we have dept_kpi_id)
//         $dept_kpi_id = $this->getDeptKpiIdByEmpKpiId($emp_kpi_id);
//         if (!$this->cap_nhat_department_kpi_progress($dept_kpi_id, $progress_update)) {
//             throw new Exception("Failed to update department KPI progress.");
//         }
        
//         // Step 8: Commit the transaction
//         $dbh->commit();
//         return 1; // Success
        
//     } catch (Exception $e) {
//         $dbh->rollBack();
//         error_log("Error in addTransactionToKPI: " . $e->getMessage());
//         return 0; // Failure
//     }
// }
    public function get_kpi_progress_logs($emp_kpi_id,$user_id) {
        $sql = "SELECT kl.emp_kpi_id, kl.progress_update, kl.result_detail, kl.updated_at,kl.status
                FROM kpi_progress_logs kl 
                INNER JOIN employee_kpis ek ON ek.emp_kpi_id = kl.emp_kpi_id 
                WHERE ek.user_id = :user_id AND status='approved'
                AND kl.emp_kpi_id=:emp_kpi_id
                ORDER BY kl.updated_at DESC";

        try {
            $link = $this->getConnection();
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':emp_kpi_id', $emp_kpi_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return [];
        }
    }
    
    public function xoa_kpi_progress_log($log_id) {
    try {
        $dbh = $this->getConnection();
        $dbh->beginTransaction();

        // Lấy emp_kpi_id và dept_kpi_id từ bảng employee_kpis và kpi_progress_logs
        $stmt = $dbh->prepare("SELECT e.emp_kpi_id, e.dept_kpi_id 
                               FROM employee_kpis e
                               INNER JOIN kpi_progress_logs kp ON e.emp_kpi_id = kp.emp_kpi_id
                               WHERE kp.log_id = :log_id
                               LIMIT 1");
        $stmt->bindParam(':log_id', $log_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result) {
            $emp_kpi_id = $result['emp_kpi_id'];
            $dept_kpi_id = $result['dept_kpi_id'];

            // Xóa progress log của employee
            $stmt = $dbh->prepare("DELETE FROM kpi_progress_logs WHERE log_id = :log_id");
            $stmt->bindParam(':log_id', $log_id);
            if (!$stmt->execute()) {
                error_log("Failed to delete KPI progress log with log_id: $log_id");
                throw new Exception("Failed to delete KPI progress log");
            }

            // Xóa kết quả KPI cho phòng ban
            $stmt = $dbh->prepare("DELETE FROM kpi_result WHERE dept_kpi_id = :dept_kpi_id");
            $stmt->bindParam(':dept_kpi_id', $dept_kpi_id);
            if (!$stmt->execute()) {
                error_log("Failed to delete KPI result for dept_kpi_id: $dept_kpi_id");
                throw new Exception("Failed to delete KPI result");
            }

            // Cập nhật lại progress cho employee (giảm giá trị nếu cần thiết)
            $stmt = $dbh->prepare("UPDATE employee_kpis SET progress = 0 WHERE emp_kpi_id = :emp_kpi_id");
            $stmt->bindParam(':emp_kpi_id', $emp_kpi_id);
            if (!$stmt->execute()) {
                error_log("Failed to update employee KPI progress for emp_kpi_id: $emp_kpi_id");
                throw new Exception("Failed to update employee KPI progress");
            }

            // Cập nhật lại progress cho department (giảm giá trị nếu cần thiết)
            $stmt = $dbh->prepare("UPDATE department_kpis SET progress = 0 WHERE dept_kpi_id = :dept_kpi_id");
            $stmt->bindParam(':dept_kpi_id', $dept_kpi_id);
            if (!$stmt->execute()) {
                error_log("Failed to update department KPI progress for dept_kpi_id: $dept_kpi_id");
                throw new Exception("Failed to update department KPI progress");
            }

            $dbh->commit();
            return 1;
        } else {
            throw new Exception("No KPI log found with log_id: $log_id");
        }
    } catch (Exception $e) {
        error_log("Error in xoa_kpi_progress_log: " . $e->getMessage());
        $dbh->rollBack();
        return 0;
    }
}

public function generateProductCode($typeId, $userId) {
    // Kết nối cơ sở dữ liệu và lấy tên loại mã sản phẩm từ bảng dựa trên typeId
    $link = $this->getConnection(); // Kết nối cơ sở dữ liệu
    $sql = 'SELECT name_type FROM code_type WHERE id = :typeId';
    
    try {
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':typeId', $typeId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Kiểm tra xem có kết quả trả về không
        $type = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($type) {
            // Lấy tên loại sản phẩm từ cơ sở dữ liệu
            $prefix = $type['name_type'] === 'REQ' ? "REQ" : ($type['name_type'] === 'TPL' ? "TPL" : "INVALID");
            
            // Lấy mã nhân viên từ bảng users dựa trên user_id
            $manv = $this->getEmployeeCodeByUserId($userId);
            
            // Sinh mã số sản phẩm ngẫu nhiên
            $uniqueNumber = str_pad(rand(0, 99999999), 8, '0', STR_PAD_LEFT);
            
            // Trả về mã sản phẩm với mã nhân viên ở trước
            return $prefix === "INVALID" ? "Invalid product type." : "{$manv}-{$prefix}-{$uniqueNumber}";
        } else {
            return "Invalid product type.";
        }
    } catch (PDOException $e) {
        // Xử lý lỗi cơ sở dữ liệu
        return 'Error: ' . $e->getMessage();
    }
}

private function getEmployeeCodeByUserId($userId) {
    $link = $this->getConnection();
    $sql = 'SELECT manv FROM users WHERE id = :userId';
    
    try {
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Lấy mã nhân viên (manv) từ cơ sở dữ liệu
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            return $user['manv'];
        } else {
            return 'Unknown';  // Trả về 'Unknown' nếu không tìm thấy mã nhân viên
        }
    } catch (PDOException $e) {
        // Xử lý lỗi cơ sở dữ liệu
        return 'Error: ' . $e->getMessage();
    }
}

public function getEmployeeIdByManv($manv) {
    $link = $this->getConnection(); // Kết nối cơ sở dữ liệu

    // Truy vấn để lấy ID nhân viên dựa trên manv
    $sql = "SELECT id FROM users WHERE manv = :manv";

    try {
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':manv', $manv, PDO::PARAM_STR);
        $stmt->execute();
        
        // Lấy id nếu tồn tại
        $id = $stmt->fetchColumn();
        
        return $id ? $id : "Mã nhân viên không tồn tại.";
    } catch (PDOException $e) {
        // Xử lý lỗi cơ sở dữ liệu
        return 'Error: ' . $e->getMessage();
    }
}
public function getKpiProgressLogs() {
    $link = $this->getConnection(); // Kết nối cơ sở dữ liệu

    // Truy vấn để lấy thông tin KPI progress logs
    $sql = "SELECT kp.result_detail, kp.emp_kpi_id, e.user_id 
            FROM kpi_progress_logs kp 
            INNER JOIN employee_kpis e 
            ON e.emp_kpi_id = kp.emp_kpi_id";

    try {
        $stmt = $link->prepare($sql);
        $stmt->execute();
        
        // Lấy toàn bộ kết quả truy vấn
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Kiểm tra nếu có dữ liệu
        if (!empty($result)) {
            return $result;
        } else {
            return "Không có dữ liệu KPI progress logs.";
        }
    } catch (PDOException $e) {
        // Xử lý lỗi cơ sở dữ liệu
        return 'Error: ' . $e->getMessage();
    }
}



public function getCkApiWithId() {
    $link = $this->getConnection(); // Kết nối cơ sở dữ liệu

    // Truy vấn dữ liệu từ bảng ck_api
    $sql = "SELECT * FROM ck_api";

    try {
        $stmt = $link->prepare($sql);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC); // Lấy toàn bộ dữ liệu từ bảng ck_api

        foreach ($results as &$row) { // Duyệt qua từng dòng dữ liệu
            $transactionDescription = $row['transaction_description']; // Lấy chuỗi transaction_description

            // Tách mã nhân viên từ transaction_description
            $manv = explode('-', $transactionDescription)[0];

            // Gọi hàm lấy id dựa trên mã nhân viên (manv)
            $row['id_nv'] = $this->getEmployeeIdByManv($manv);
        }

        return $results; // Trả về mảng kết quả đã có thêm cột id
    } catch (PDOException $e) {
        // Xử lý lỗi cơ sở dữ liệu
        return 'Error: ' . $e->getMessage();
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

public function updateITTaskProgress($emp_kpi_id) {
    $dbh = $this->getConnection();
    
    // Fetch the assigned value for the task
    $stmt = $dbh->prepare("SELECT assigned_value FROM employee_kpis WHERE emp_kpi_id = :emp_kpi_id");
    $stmt->bindParam(':emp_kpi_id', $emp_kpi_id, PDO::PARAM_INT);
    $stmt->execute();
    $assigned_value = $stmt->fetchColumn();
    
    if ($assigned_value !== false) {
        // Update the progress with the assigned value
        $stmt = $dbh->prepare("UPDATE employee_kpis SET progress = :progress WHERE emp_kpi_id = :emp_kpi_id");
        $stmt->bindParam(':emp_kpi_id', $emp_kpi_id, PDO::PARAM_INT);
        $stmt->bindParam(':progress', $assigned_value, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            // Log the progress update
            $result_detail = "Updated progress to assigned value for IT task.";
            return $this->them_kpi_progress_log($emp_kpi_id, $assigned_value, $result_detail);
        }
    }
    return false;
}

public function updateMultipleITTasks($emp_kpi_ids) {
    $dbh = $this->getConnection();
    $dbh->beginTransaction();
    
    try {
        foreach ($emp_kpi_ids as $emp_kpi_id) {
            if (!$this->updateITTaskProgress($emp_kpi_id)) {
                throw new Exception("Failed to update task with ID: $emp_kpi_id");
            }
        }
        $dbh->commit();
        return true;
    } catch (Exception $e) {
        $dbh->rollBack();
        error_log("Error in updateMultipleITTasks: " . $e->getMessage());
        return false;
    }
}

public function updateKpiStatus($emp_kpi_id, $dept_kpi_id) {
    $link = $this->getConnection();

    // Lấy dữ liệu từ bảng employee_kpis cho emp_kpi_id
    $stmt = $link->prepare("SELECT progress, assigned_value, due_date FROM employee_kpis WHERE emp_kpi_id = :emp_kpi_id");
    $stmt->bindParam(':emp_kpi_id', $emp_kpi_id, PDO::PARAM_INT);
    $stmt->execute();
    $employeeKpi = $stmt->fetch(PDO::FETCH_ASSOC);

    // Lấy dữ liệu từ bảng department_kpis cho dept_kpi_id
    $stmt = $link->prepare("SELECT progress, assigned_value, due_date FROM department_kpis WHERE dept_kpi_id = :dept_kpi_id");
    $stmt->bindParam(':dept_kpi_id', $dept_kpi_id, PDO::PARAM_INT);
    $stmt->execute();
    $departmentKpi = $stmt->fetch(PDO::FETCH_ASSOC);

    // Cập nhật trạng thái cho employee_kpis
    if ($employeeKpi) {
        $status_id = 1; // Mặc định là trạng thái 1
        $currentDate = new DateTime();

        // Kiểm tra điều kiện
        if ($employeeKpi['progress'] > 0) {
            $status_id = 2; // Nếu progress > 0 thì status_id = 2
        }

        if ($currentDate > new DateTime($employeeKpi['due_date'])) {
            $status_id = 5; // Nếu ngày hiện tại lớn hơn due_date thì trạng thái khác (ví dụ: 5)
        }

        // Cập nhật trạng thái
        $updateStmt = $link->prepare("UPDATE employee_kpis SET status_id = :status_id WHERE emp_kpi_id = :emp_kpi_id");
        $updateStmt->bindParam(':status_id', $status_id);
        $updateStmt->bindParam(':emp_kpi_id', $emp_kpi_id);
        $updateStmt->execute();
    }

    // Cập nhật trạng thái cho department_kpis
    if ($departmentKpi) {
        $status_id = 1; // Mặc định là trạng thái 1
        $currentDate = new DateTime();

        // Kiểm tra điều kiện
        if ($departmentKpi['progress'] / $departmentKpi['assigned_value'] >= 1) {
            $status_id = 4; // Nếu dk.progress/dk.assigned_value >= 1 thì status_id = 4
        } elseif ($departmentKpi['progress'] / $departmentKpi['assigned_value'] < 1) {
            $status_id = 3; // Nếu < 1 thì status_id = 3
        }

        if ($currentDate > new DateTime($departmentKpi['due_date'])) {
            $status_id = 5; // Nếu ngày hiện tại lớn hơn due_date thì trạng thái khác (ví dụ: 5)
        }

        // Cập nhật trạng thái
        $updateStmt = $link->prepare("UPDATE department_kpis SET status_id = :status_id WHERE dept_kpi_id = :dept_kpi_id");
        $updateStmt->bindParam(':status_id', $status_id);
        $updateStmt->bindParam(':dept_kpi_id', $dept_kpi_id);
        $updateStmt->execute();
    }
}

}
?>