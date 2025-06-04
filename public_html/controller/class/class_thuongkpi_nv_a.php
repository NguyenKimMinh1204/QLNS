<?php
include '../../db/connetion.php';

class thuongkpi_nv_a extends Database {
public function getDepartments() {
        $link = $this->getConnection();
        $stmt = $link->prepare("SELECT * FROM departments");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
 public function getTransactions() {
    // SQL query to select data from 'transactions' table
    $sql = "SELECT t.id, t.user_id, t.category_id, t.amount, t.transaction_date, t.calculation_date, t.is_active FROM `transactions` t";
    
    try {
        // Assume $this->getConnection() gives a valid PDO connection
        $link = $this->getConnection();
        
        // Prepare the SQL statement
        $stmt = $link->prepare($sql);
        
        // Execute the statement
        $stmt->execute();
        
        // Fetch the results as an associative array
        $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return the result
        return $transactions;
    } catch (PDOException $e) {
        // Handle any exceptions, such as connection errors or query issues
        echo "Error: " . $e->getMessage();
        return 0;
    }
}
    
public function addTransaction($user_id, $amount, $transaction_date) {
    // Retrieve existing transactions
    $transactions = $this->getTransactions();

    // Check for existing transaction with the same user_id, category_id, and transaction_date
    foreach ($transactions as $transaction) {
        if ($transaction['user_id'] == $user_id && 
            $transaction['category_id'] == 2 && // Assuming category_id is fixed as 6
            $transaction['transaction_date'] == $transaction_date) {
            return 2; // Return 2 if a matching transaction is found
        }
    }

    // If no matching transaction is found, proceed to insert a new one
    $sql = "INSERT INTO transactions (user_id, category_id, amount, transaction_date, is_active, calculation_date)
            VALUES (:user_id, 2, :amount, :transaction_date, 1, CURDATE())";

    try {
        $link = $this->getConnection(); // Connect to the database
        $stmt = $link->prepare($sql);

        // Bind parameters to the SQL statement
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':transaction_date', $transaction_date);

        // Execute the statement
        if ($stmt->execute()) {
            return 1; // Success
        } else {
            return 0; // Failure
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
        return 0; // Error occurred
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
// ... existing code ...

public function getTotalGoalBasedIncentive($user_id, $month, $year) {
    $sql = "SELECT 
                SUM(ek.goalBasedIncentive) AS total_goalBasedIncentive
            FROM 
                employee_kpis ek 
            INNER JOIN 
                department_kpis dk ON dk.dept_kpi_id = ek.dept_kpi_id 
            INNER JOIN 
                users u ON u.id = ek.user_id 
            INNER JOIN 
                kpi_personal kp ON kp.id = ek.kpi_personal_id 
            INNER JOIN 
                status_kpi sk ON sk.id = ek.status_id 
            WHERE 
                u.id = :user_id AND
                MONTH(ek.assigned_date) = :month AND 
                YEAR(ek.assigned_date) = :year";

    try {
        $link = $this->getConnection(); // Connect to the database
        $stmt = $link->prepare($sql);

        // Bind parameters to the SQL statement
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);

        // Execute the statement
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the total_goalBasedIncentive or 0 if no result
        return $result['total_goalBasedIncentive'] ?? 0;
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
        return 0; // Error occurred
    }
}

// ... existing code ...
public function calculateIncentivePercentage($completionRate) {
    if ($completionRate < 80) {
        return 0;
    } elseif ($completionRate >= 80 && $completionRate < 90) {
        return 2/100;
    } elseif ($completionRate >= 90 && $completionRate < 100) {
        return 3/100;
    } elseif ($completionRate == 100) {
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
public function loadKPIByMonthYearUser($department_id=null, $month, $year) {
    $sql = "  SELECT 
    ek.emp_kpi_id, 
    ek.user_id, 
    ek.assigned_value,
    ek.assigned_date,
    ek.due_date,
    u.full_name, 
    u.manv, 
    ek.goalBasedIncentive, 
    ek.is_active
FROM 
    users u
INNER JOIN 
    (
        SELECT 
            ek.user_id, 
            MIN(ek.assigned_date) AS first_assigned_date
        FROM 
            employee_kpis ek
        INNER JOIN 
            department_kpis dk ON dk.dept_kpi_id = ek.dept_kpi_id
        INNER JOIN 
            kpi_personal kp ON kp.id = ek.kpi_personal_id
        INNER JOIN 
            status_kpi sk ON sk.id = ek.status_id
        WHERE 
             MONTH(ek.assigned_date) = :month 
             AND YEAR(ek.assigned_date) = :year
        GROUP BY 
            ek.user_id
    ) subquery ON subquery.user_id = u.id
INNER JOIN 
    employee_kpis ek ON ek.user_id = u.id AND ek.assigned_date = subquery.first_assigned_date
INNER JOIN 
    kpi_personal kp ON kp.id = ek.kpi_personal_id
WHERE 
   1=1;";

    // Add department_id condition if it is provided
    if ($department_id !== null) {
        $sql .= " AND u.department_id = :department_id";
    }
   
    try {
        $link = $this->getConnection();
        
        if ($link) {
            $stmt = $link->prepare($sql);

            // Bind parameters
            if ($department_id !== null) {
                $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
            }
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);

            // Execute the statement
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            echo '<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                         <th>STT</th>
                        <th>Mã nhân viên</th>
                            <th>Tên nhân viên</th>
                       
                          
                           <th>Tháng tính lương thưởng</th>
                            <th>Giá trị thưởng</th> 
                            
                       
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>';
            $dem=1;
            $date=date($year.'-'.$month.'-05');
            if ($result && count($result) > 0) {
                foreach ($result as $row) {
                    $isActive=$row['is_active'];
                   $total_bonus=$this->getTotalGoalBasedIncentive($row['user_id'],$month,$year) ;
                    
                   
                     echo '<tr>
                            <td>' . htmlspecialchars($dem) . '</td>
                            <td>' . htmlspecialchars($row['manv']) . '</td>
                            <td>' . htmlspecialchars($row['full_name']) . '</td>
                           <td>' . htmlspecialchars($date) . '</td>
                          
                           
                            <td>' . htmlspecialchars($total_bonus) . '</td>
                           
                            <td>';
                   
                    
                    $dueDate = new DateTime($row['due_date']);
                    $currentDate = new DateTime();

                    if ($dueDate > $currentDate) {
                        echo '<button class="btn btn-warning my-2" disabled>Chưa tới hạn tính thưởng</button>';
                    } else {
                        if ($isActive == 0) {
                            echo '<button class="btn btn-warning calculate-bonus-btn my-2" >chờ tính Thưởng</button>';
                        } elseif ($isActive == 1) {
                            echo '<button class="btn btn-success calculate-bonus-btn my-2" 
                                    data-manv="' . htmlspecialchars($row['manv']) . '"
                                    data-user_id="' . htmlspecialchars($row['user_id']) . '"
                                    data-totalbonus="' . htmlspecialchars($total_bonus) . '"
                                    data-date="' . htmlspecialchars($date) . '"
                                    data-fullname="' . htmlspecialchars($row['full_name']) . '"
                                    
                                  
                                    data-toggle="modal" data-target="#addTransactionModal">Thêm thưởng</button>';
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
 public function loadKPIByMonthYearUserbyuser($user_id, $month, $year, $department_id = null) {
        $sql = "SELECT ek.emp_kpi_id, ek.dept_kpi_id, ek.kpi_personal_id, ek.user_id, ek.assigned_value,
                ek.assigned_date, ek.due_date, ek.status_id, sk.name_status_kpi, ek.progress, u.full_name, 
                kp.kpi_lib_id, kp.kpi_name, kp.kpi_description, u.manv, ek.goalBasedIncentive, ek.is_active, 
                u.department_id, dk.goalBasedIncentive as goalBasedIncentiveIT
                FROM employee_kpis ek 
                INNER JOIN department_kpis dk ON dk.dept_kpi_id = ek.dept_kpi_id 
                INNER JOIN users u ON u.id = ek.user_id 
                INNER JOIN kpi_personal kp ON kp.id = ek.kpi_personal_id 
                INNER JOIN status_kpi sk ON sk.id = ek.status_id 
                WHERE u.id = :user_id 
                AND MONTH(ek.assigned_date) = :month 
                AND YEAR(ek.assigned_date) = :year";

        // Add department_id condition if it is provided
        if ($department_id !== null) {
            $sql .= " AND u.department_id = :department_id";
        }

        try {
            $link = $this->getConnection();
            $stmt = $link->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            if ($department_id !== null) {
                $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
            }

            // Execute the statement
            $stmt->execute();

            // Fetch the results as an associative array
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Return the result
            return $result;
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
            return [];
        }
    }


    
}

?>