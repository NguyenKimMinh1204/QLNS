<?php
include ('../../db/connetion.php');
class thuong_kpi_m extends Database{
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
public function loadKPIByMonthYearUser($department_id, $month, $year) {
    $sql = "SELECT ek.emp_kpi_id, ek.dept_kpi_id, ek.kpi_personal_id, ek.user_id, ek.assigned_value,
            ek.assigned_date, ek.due_date, ek.status_id, sk.name_status_kpi, ek.progress, u.full_name, 
            kp.kpi_lib_id, kp.kpi_name, kp.kpi_description,u.manv,ek.goalBasedIncentive,ek.is_active,u.department_id,dk.goalBasedIncentive as goalBasedIncentiveIT
            
            FROM employee_kpis ek 
            INNER JOIN department_kpis dk ON dk.dept_kpi_id = ek.dept_kpi_id 
            INNER JOIN users u ON u.id = ek.user_id 
            INNER JOIN kpi_personal kp ON kp.id = ek.kpi_personal_id 
            INNER JOIN status_kpi sk ON sk.id = ek.status_id 
            WHERE u.department_id = :department_id AND MONTH(ek.assigned_date) = :month AND YEAR(ek.assigned_date) = :year";

    try {
        $link = $this->getConnection();
        
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo '<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                         <th>STT</th>
                        <th>Mã nhân viên</th>
                            <th>Tên nhân viên</th>
                            <th>Tên KPI</th>
                            <th>Mô tả</th>
                            <th>Giá trị được giao</th>
                            <th>Giá trị thực tế</th>
                           
                            <th>Tỷ lệ đạt</th>
                             <th>Giá trị thưởng</th>
                            <th>Ngày giao</th>
                            <th>Ngày hết hạn</th>
                            <th>Trạng thái</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>';
            $dem=1;
            if ($result && count($result) > 0) {
                foreach ($result as $row) {
                    $completion_rate = ($row['assigned_value'] > 0) ? 
                        round(($row['progress'] / $row['assigned_value']) * 100, 2) : 0;
                        
                    $status = $this->getKPIStatus($completion_rate, $row['due_date']);
                    $isActive=$row['is_active'];
                    $bonus=$this->calculateIncentivePercentage($completion_rate)*$row['progress'];
                  
                   if($row['department_id']==5){
                    $phantram=$row['progress']/$row['assigned_value'];
                    if($phantram<0.8 ){
                        $bonus=0;   
                    }else{
                        $bonusIT=$row['goalBasedIncentiveIT']*90/100;
                        $bonus=$bonusIT*$row['progress']/100; 
                    }
                     echo '<tr>
                            <td>' . htmlspecialchars($dem) . '</td>
                            <td>' . htmlspecialchars($row['manv']) . '</td>
                            <td>' . htmlspecialchars($row['full_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_description']) . '</td>
                            <td>' . htmlspecialchars($row['assigned_value']) . '</td>
                            <td>' . htmlspecialchars($row['progress']) . '</td>
                            <td>' . $completion_rate . '%</td>
                            <td>' . htmlspecialchars($bonus) . '</td>
                            <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                            <td>' . htmlspecialchars($row['due_date']) . '</td>
                            <td>' . htmlspecialchars($status) . '</td>
                            <td>';
                   }else{
                    echo '<tr>
                            <td>' . htmlspecialchars($dem) . '</td>
                            <td>' . htmlspecialchars($row['manv']) . '</td>
                            <td>' . htmlspecialchars($row['full_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_description']) . '</td>
                            <td>' . htmlspecialchars($row['assigned_value']) . '</td>
                            <td>' . htmlspecialchars($row['progress']) . '</td>
                            <td>' . $completion_rate . '%</td>
                            <td>' . htmlspecialchars($bonus) . '</td>
                            <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                            <td>' . htmlspecialchars($row['due_date']) . '</td>
                            <td>' . htmlspecialchars($status) . '</td>
                            <td>';}
                    
                    
                    $dueDate = new DateTime($row['due_date']);
                    $currentDate = new DateTime();

                    if ($dueDate > $currentDate) {
                        echo '<button class="btn btn-warning my-2" disabled>Chưa tới hạn tính thưởng</button>';
                    } else {
                        if ($isActive == 0) {
                            echo '<button class="btn btn-success calculate-bonus-btn my-2" data-id="' . htmlspecialchars($row['emp_kpi_id']) . '" data-bonus="' . number_format($bonus, 2) . '" data-toggle="modal" data-target="#bonusModal">Tính Thưởng</button>';
                        } else {
                            echo '<span> Thưởng: ' . $row['goalBasedIncentive'] . '</span>';
                        }
                    }

                    echo '</td></tr>';$dem++;
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
public function loadITTasksByMonthYearUser($user_id, $month, $year) {
    $sql = "SELECT ek.emp_kpi_id, ek.dept_kpi_id, ek.kpi_personal_id, ek.user_id, ek.assigned_value, ek.assigned_date,
     ek.due_date, ek.status_id, sk.name_status_kpi, ek.progress, u.full_name, kp.kpi_lib_id, kp.kpi_name, 
     kp.kpi_description, ek.link_web, u.manv, dk.is_active, dk.goalBasedIncentive
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
            
            echo '<form id="itTasksForm"><table class="table table-bordered table-hover">
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
                $currentDate = new DateTime();
                foreach ($result as $row) {
                    $dueDate = new DateTime($row['due_date']);
                    echo '<tr>
                            <td><input type="checkbox" name="task_ids[]" value="' . htmlspecialchars($row['emp_kpi_id']) . '"></td>
                            <td>' . htmlspecialchars($row['full_name']) . '</td>
                            <td>' . htmlspecialchars($row['manv']) . '</td>
                            <td><a href="' . htmlspecialchars($row['link_web']) . '" target="_blank">Link</a></td>
                            <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_description']) . '</td>
                            <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                            <td>' . htmlspecialchars($row['due_date']) . '</td>
                            <td>' . htmlspecialchars($row['name_status_kpi']) . '</td>
                            <td>';
                    
                    if ($currentDate < $dueDate) {
                        echo '<button class="btn btn-warning my-2" disabled>Chưa tới hạn tính thưởng</button>';
                    } else {
                        if ($row['is_active'] == 0) {
                            echo '<button class="btn btn-danger my-2" disabled>Chưa tính thưởng</button>';
                        } else {
                            echo '<span> Thưởng: ' . htmlspecialchars($row['goalBasedIncentive']) . '</span>';
                        }
                    }

                    echo '</td></tr>';
                }
            } else {
                echo '<tr><td colspan="10" class="text-center">Không có dữ liệu</td></tr>';
            }
            
            echo '</tbody>
                </table>
                <button type="button" class="btn btn-success" onclick="completeTasks()">Hoàn thành</button>
                </form>';
        } else {
            echo '<div class="alert alert-danger">Không thể kết nối đến cơ sở dữ liệu</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
    }
}

 function getKPIStatus($percent, $due_date) {
    // Chuyển due_date thành timestamp
    $due_date_timestamp = strtotime($due_date);
    $current_date_timestamp = time(); // Lấy thời gian hiện tại

    // Xác định trạng thái
    if ($percent == 0) {
        return "Chưa làm";
    } elseif ($percent < 100) {
        if ($due_date_timestamp < $current_date_timestamp) {
            return "Chưa hoàn thành"; // Hết hạn mà chưa đạt 100%
        }
        return "Đang làm";
    } elseif ($percent >= 100) {
        return "Hoàn thành";
    }
}

public function calculateIncentivePercentage($completionRate) {
    if ($completionRate < 80) {
        return 0;
    } elseif ($completionRate >= 80 && $completionRate < 90) {
        return 3/100;
    } elseif ($completionRate >= 90 && $completionRate < 100) {
        return 4/100;
    } elseif ($completionRate > 100 && $completionRate <= 110) {
        return 5/100;
    } elseif ($completionRate > 110 && $completionRate < 120) {
        return 6/100;
    } elseif ($completionRate >= 120 && $completionRate < 150) {
        return 7/100;
    } else { // $completionRate >= 150
        return 8/100;
    }
}
public function updateEmployeeKpis($emp_kpi_id, $goalBasedIncentive) {
    // Validate goalBasedIncentive
    if ($goalBasedIncentive === null || $goalBasedIncentive === '') {
        return 0; // Return 0 if goalBasedIncentive is empty or null
    }

    // Format goalBasedIncentive
    $goalBasedIncentive = number_format((float)$goalBasedIncentive, 2, '.', '');

    // Get the database connection
    $link = $this->getConnection();

    if ($link) {
        // Prepare the SQL statement
        $sql = "UPDATE employee_kpis SET goalBasedIncentive = :goalBasedIncentive, is_active = 1 WHERE emp_kpi_id = :emp_kpi_id";
        $stmt = $link->prepare($sql);

        // Bind the parameters
        $stmt->bindValue(':goalBasedIncentive', $goalBasedIncentive, PDO::PARAM_STR);
        $stmt->bindValue(':emp_kpi_id', $emp_kpi_id, PDO::PARAM_INT);

        // Execute the statement and return the result
        return $stmt->execute() ? 1 : 0;
    } else {
        // If no connection, return false
        return 0;
    }
}

}



?>