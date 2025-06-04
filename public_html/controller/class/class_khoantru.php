<?php
include '../../db/connetion.php';

class AttendanceReport extends Database {
    public function getAttendanceDeductions($month = null, $year = null, $department_id = null) {
        if ($month === null) $month = date('m');
        if ($year === null) $year = date('Y');

        $start_date = "$year-$month-04";
        $nextMonth = $month == 12 ? 1 : $month + 1;
        $nextYear = $month == 12 ? $year + 1 : $year;
        $end_date = "$nextYear-$nextMonth-04";

        // $sql = "SELECT a.user_id, a.lateLeaveTime, u.full_name, u.manv
        //         , s.work_time, s.shift_name, t.amount, t.is_active
        //         FROM attendance a
        //         INNER JOIN users u ON u.id = a.user_id
        //         INNER JOIN shifts s ON s.id = a.shift_id
        //         INNER JOIN transactions t ON t.user_id = a.user_id
        //         WHERE a.clock_in_time BETWEEN :start_date AND :end_date";
        $sql="SELECT u.full_name, u.manv, a.user_id FROM users u INNER JOIN ( SELECT user_id, MIN(clock_in_time) AS first_clock_in 
        FROM attendance a
        WHERE clock_in_time BETWEEN :start_date AND :end_date GROUP BY user_id ) a ON u.id = a.user_id;";
        // Thêm điều kiện lọc theo phòng ban nếu có
        if ($department_id) {
            $sql .= " AND u.department_id = :department_id";
        }
        
        // $sql .= " GROUP BY u.id LIMIT 1";

        try {
            $link = $this->getConnection();
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            
            if ($department_id) {
                $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo '<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã NV</th>
                            <th>Tên nhân viên</th>
                            <th>tháng năm</th>
                            <th>tổng trừ tiền</th>
                            <th>Tính các khoản trừ</th>
                        </tr>
                    </thead>
                    <tbody>';

            $stt = 1;
            $khoantrunew=0;
            $day = '05';
            $date = "$year-$month-$day";
             foreach ($result as $row) {
                // Define the date variable outside the loop
                $khoantrunew = 0; // Khởi tạo khoantrunew cho mỗi hàng
                // $date="2024-10-05";
                
                $transactions = $this->getTransactions();
                foreach ($transactions as $transaction) {
                    if ($transaction['user_id'] == $row['user_id'] && 
                        $transaction['category_id'] == 6 && // Assuming category_id is fixed as 6
                        $transaction['transaction_date'] == $date) {
                        $khoantrunew = $transaction['amount']; // Cập nhật khoantrunew nếu khớp
                    }
                }
                
                $deduction = $this->getTotalDeductions($row['user_id'],$month,$year);
                $totalDeduction = $deduction; // Assuming total deduction is the same as deduction for simplicity

                echo '<tr>
                        <td>' . htmlspecialchars($stt) . '</td>
                        <td>' . htmlspecialchars($row['manv']) . '</td>
                        <td>' . htmlspecialchars($row['full_name']) . '</td>
                        <td>' . $date. '</td>
                        <td>' . htmlspecialchars($khoantrunew)  . '</td>
                        <td>
                            <button type="button" class="btn btn-primary" 
                                    data-toggle="modal" 
                                    data-target="#deductionModal" data-date="' . $date . '"
                                    data-user-id="' . htmlspecialchars($row['user_id']) . '" 
                                    data-total-deduction="' . htmlspecialchars($totalDeduction) . '"
                                    onclick="setUserId(' . htmlspecialchars($row['user_id']) . ')">
                                Xem các khoản trừ
                            </button>
                       </td>
                      </tr>';  
           
                $stt++;
            }

            echo '</tbody>
                </table>';
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
        }
    }
 
public function loadAttendanceDays($user_id, $month = null, $year = null) {
    // Set default to current month and year if not provided
    if ($month === null) $month = date('m');
    if ($year === null) $year = date('Y');

    // Calculate the start and end dates
    $startDate = date('Y-m-d', strtotime("$year-$month-04"));

    // Handle the month/year rollover for the end date
    $nextMonth = $month + 1;
    $nextYear = $year;
    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear += 1;
    }
    $endDate = date('Y-m-d', strtotime("$nextYear-$nextMonth-03"));

    // SQL query to fetch attendance records
    $sql = 'SELECT a.id, a.user_id, a.clock_in_time, a.clock_out_time, a.wifi_address, a.shift_id,
            s.shift_name, s.start_time, s.end_time, s.work_time
            FROM attendance a
            INNER JOIN shifts s ON s.id = a.shift_id
            WHERE a.user_id = :user_id
              AND DATE(a.clock_in_time) BETWEEN :start_date AND :end_date
            ORDER BY a.clock_in_time';

    $link = $this->getConnection();
    if ($link) {
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':start_date', $startDate, PDO::PARAM_STR);
        $stmt->bindParam(':end_date', $endDate, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            // Initialize the total days worked
            $daysWorked = 0;

            // Loop through the attendance data to calculate the total work time
            foreach ($result as $record) {
                // Ensure work_time is a valid number before adding
                $workTime = isset($record['work_time']) ? floatval($record['work_time']) : 0;
                if ($workTime > 0) {
                    $daysWorked += $workTime; // Sum the work time for each day
                }
            }

            // Return the total work days
            return $daysWorked;
        } else {
            return 0;
        }
    } else {
        throw new Exception('Cannot connect to the database');
    }
}
   

public function getWeekdaysCountFromRange($month = null, $year = null) {
    // Kiểm tra nếu người dùng không nhập, mặc định lấy tháng và năm hiện tại
    if (!$month) {
        $month = date('m'); // Tháng hiện tại
    }
    if (!$year) {
        $year = date('Y'); // Năm hiện tại
    }

    // Xác định ngày bắt đầu: 4 tháng này
    $start_date = new DateTime("$year-$month-04");

    // Xác định ngày kết thúc: 3 tháng sau
    $nextMonth = $month + 1;
    $nextYear = $year;
    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear += 1;
    }
    $end_date = new DateTime("$nextYear-$nextMonth-03");

    $weekday_count = 0;

    // Lặp qua từng ngày trong khoảng thời gian
    while ($start_date <= $end_date) {
        $day_of_week = $start_date->format('w'); // 0: Chủ nhật, 6: Thứ bảy
        if ($day_of_week != 0 && $day_of_week != 6) {
            $weekday_count++; // Tăng số ngày làm việc nếu không phải thứ 7 hoặc chủ nhật
        }
        $start_date->modify('+1 day'); // Tăng 1 ngày
    }

    return $weekday_count;
}

public function loadphucap( $user_id, $month, $year) {
    $sql = 'SELECT  tr.id, tr.user_id, tr.category_id, tr.amount, tr.transaction_date, ca.name
            FROM   
            transactions tr
            INNER JOIN categories ca ON ca.id = tr.category_id 
            WHERE tr.user_id = :user_id
            AND MONTH(tr.transaction_date) = :month
            AND YEAR(tr.transaction_date) = :year
            AND tr.category_id IN (2, 3, 4, 6,7,8);';
    
    $link = $this->getConnection();
    if ($link) {
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return $result; // Return the array of allowances
    } else {
        throw new Exception('Cannot connect to the database');
    }
}

public function addTransactionpc($user_id, $amount, $transaction_date) {
    // Retrieve existing transactions
    $transactions = $this->getTransactions();

    // Check for existing transaction with the same user_id, category_id, and transaction_date
    foreach ($transactions as $transaction) {
        if ($transaction['user_id'] == $user_id && 
            $transaction['category_id'] == 3 && // Assuming category_id is fixed as 6
            $transaction['transaction_date'] == $transaction_date) {
            return 2; // Return 2 if a matching transaction is found
        }
    }

    // If no matching transaction is found, proceed to insert a new one
    $sql = "INSERT INTO transactions (user_id, category_id, amount, transaction_date, is_active, calculation_date)
            VALUES (:user_id, 3, :amount, :transaction_date, 1, CURDATE())";

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
public function addTransactionthue($user_id, $amount, $transaction_date) {
    // Retrieve existing transactions
    $transactions = $this->getTransactions();

    // Check for existing transaction with the same user_id, category_id, and transaction_date
    foreach ($transactions as $transaction) {
        if ($transaction['user_id'] == $user_id && 
            $transaction['category_id'] == 7 && // Assuming category_id is fixed as 6
            $transaction['transaction_date'] == $transaction_date) {
            return 3; // Return 2 if a matching transaction is found
        }
    }

    // If no matching transaction is found, proceed to insert a new one
    $sql = "INSERT INTO transactions (user_id, category_id, amount, transaction_date, is_active, calculation_date)
            VALUES (:user_id, 7, :amount, :transaction_date, 1, CURDATE())";

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
public function addTransactionBH($user_id, $amount, $transaction_date) {
    // Retrieve existing transactions
    $transactions = $this->getTransactions();

    // Check for existing transaction with the same user_id, category_id, and transaction_date
    foreach ($transactions as $transaction) {
        if ($transaction['user_id'] == $user_id && 
            $transaction['category_id'] == 8 && // Assuming category_id is fixed as 6
            $transaction['transaction_date'] == $transaction_date) {
            return 4; // Return 2 if a matching transaction is found
        }
    }

    // If no matching transaction is found, proceed to insert a new one
    $sql = "INSERT INTO transactions (user_id, category_id, amount, transaction_date, is_active, calculation_date)
            VALUES (:user_id, 8, :amount, :transaction_date, 1, CURDATE())";

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
public function getSalariesByUserAndDate($user_id, $year, $month) {
    // Kết nối đến cơ sở dữ liệu
    $link = $this->getConnection();

    // Chuẩn bị câu lệnh truy vấn
    $stmt = $link->prepare("
        SELECT id, user_id, net_salary, day_salary, payment_date, is_active
        FROM salary
        WHERE user_id = :user_id
        AND YEAR(day_salary) = :year
        AND MONTH(day_salary) = :month
    ");

    // Gắn giá trị cho các tham số
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindParam(':year', $year, PDO::PARAM_INT);
    $stmt->bindParam(':month', $month, PDO::PARAM_INT);

    // Thực thi câu lệnh
    $stmt->execute();

    // Trả về tất cả kết quả dưới dạng mảng kết hợp
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function calculatePenalty($minutesLate) {
        $sql = "SELECT id, money FROM status";
        
        try {
            $link = $this->getConnection();
            $stmt = $link->prepare($sql);
            $stmt->execute();
            $statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($statuses as $status) {
                switch ($status['id']) {
                    case 14:
                        if ($minutesLate >= 5 && $minutesLate < 60) {
                            return $minutesLate * $status['money'];
                        }
                        break;
                    case 2:
                        if ($minutesLate >= 1 && $minutesLate < 5) {
                            return $status['money'];
                        }
                        break;
                    case 6:
                        if ($minutesLate >= 60 && $minutesLate < 120) {
                            return $status['money'];
                        }
                        break;
                    case 13:
                        if ($minutesLate > 120) {
                            return $status['money'];
                        }
                        break;
                    default:
                        // Handle other cases if needed
                        break;
                }
            }

            return 0; // No penalty if no conditions match
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
            return 0;
        }
    }

    public function getLeaveDaysWithPermission($user_id, $month = null, $year = null) {
    if ($month === null) $month = date('m');
    if ($year === null) $year = date('Y');

    // Tính toán khoảng thời gian bắt đầu và kết thúc
    $start_date = "$year-$month-04";
    $nextMonth = $month == 12 ? 1 : $month + 1;
    $nextYear = $month == 12 ? $year + 1 : $year;
    $end_date = "$nextYear-$nextMonth-03";

    $sql = "SELECT start_date, end_date 
            FROM leave_requests 
            WHERE user_id = :user_id 
            AND status = 'approved'
            AND (
                (start_date BETWEEN :start_date AND :end_date)
                OR 
                (end_date BETWEEN :start_date AND :end_date)
            )";

    try {
        $link = $this->getConnection();
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->execute();
        $leaveData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $leaveDays = [];

        foreach ($leaveData as $record) {
            $start = new DateTime($record['start_date']);
            $end = new DateTime($record['end_date']);

            // Đảm bảo $end bao gồm cả ngày cuối cùng
            $end->setTime(23, 59, 59);

            // Kiểm tra nếu ngày bắt đầu và ngày kết thúc là cùng một ngày
            if ($start->format('Y-m-d') === $end->format('Y-m-d')) {
                $leaveDays[] = $start->format('Y-m-d');
            } else {
                // Nếu không, lấy tất cả các ngày từ start đến end
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($start, $interval, $end);

                foreach ($period as $date) {
                    $leaveDays[] = $date->format('Y-m-d');
                }
                // Thêm ngày cuối cùng (chỉ khi cần)
                if (!in_array($end->format('Y-m-d'), $leaveDays)) {
                    $leaveDays[] = $end->format('Y-m-d');
                }
            }
        }

        return $leaveDays;
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
        return [];
    }
}

    public function getUnapprovedLeaveDays($user_id, $month = null, $year = null) {
        if ($month === null) $month = date('m');
        if ($year === null) $year = date('Y');

        // Calculate the start and end dates for the range
        $start_date = "$year-$month-04";
        $nextMonth = $month == 12 ? 1 : $month + 1;
        $nextYear = $month == 12 ? $year + 1 : $year;
        $end_date = "$nextYear-$nextMonth-03";

        // Get all approved leave days
        $approvedLeaveDays = $this->getLeaveDaysWithPermission($user_id, $month, $year);

        // Get all attendance records for the user in the specified range
        $sql = "SELECT DATE(clock_in_time) as clock_in_date
                FROM attendance
                WHERE user_id = :user_id
                AND clock_in_time BETWEEN :start_date AND :end_date";

        try {
            $link = $this->getConnection();
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->execute();
            $attendanceData = $stmt->fetchAll(PDO::FETCH_COLUMN);

            $unapprovedLeaveDays = [];

            // Iterate over each day in the range
            $start = new DateTime($start_date);
            $end = new DateTime($end_date);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($start, $interval, $end);

            foreach ($period as $date) {
                $dayOfWeek = $date->format('N'); // 1 (for Monday) through 7 (for Sunday)
                $dateStr = $date->format('Y-m-d');

                // Check if it's a weekday and not in attendance or approved leave
                if ($dayOfWeek >= 1 && $dayOfWeek <= 5 && 
                    !in_array($dateStr, $attendanceData) && 
                    !in_array($dateStr, $approvedLeaveDays)) {
                    $unapprovedLeaveDays[] = $dateStr;
                }
            }

            return $unapprovedLeaveDays;
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
            return [];
        }
    }

    public function getAttendanceDeductionsArray($user_id = null, $month = null, $year = null) {
        if ($month === null) $month = date('m');
        if ($year === null) $year = date('Y');

        // Calculate the start and end dates for the query
        $start_date = "$year-$month-04";
        $nextMonth = $month == 12 ? 1 : $month + 1;
        $nextYear = $month == 12 ? $year + 1 : $year;
        $end_date = "$nextYear-$nextMonth-03";

        $sql = "SELECT 
    a.user_id, 
    a.lateLeaveTime, 
    u.full_name, 
    u.manv, 
    a.clock_in_time, 
    a.clock_out_time
FROM 
    attendance a
INNER JOIN 
    users u 
ON 
    u.id = a.user_id
WHERE 
    a.user_id =:user_id AND a.clock_in_time BETWEEN :start_date AND :end_date";

        // Add user_id filter if provided
       

        try {
            $link = $this->getConnection();
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            if ($user_id !== null) {
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            }
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $deductionsArray = [];

            foreach ($result as $row) {
                if($row['lateLeaveTime']>0){
                $deduction = $this->calculatePenalty($row['lateLeaveTime']);

                $deductionsArray[] = [
                    'user_id' => $row['user_id'],
                    'manv' => $row['manv'],
                    'full_name' => $row['full_name'],
                    'clock_in_time' => $row['clock_in_time'],
                    'clock_out_time' => $row['clock_out_time'],
                    'lateLeaveTime' => $row['lateLeaveTime'],
                    'totalDeduction' => $deduction
                ];
            }
            }

            return $deductionsArray;
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
            return [];
        }
    }

public function getDepartments() {
        $link = $this->getConnection();
        $stmt = $link->prepare("SELECT * FROM departments");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    public function getTotalDeductions($user_id, $month = null, $year = null) {
        if ($month === null) $month = date('m');
        if ($year === null) $year = date('Y');

        // Get leave days with permission and calculate deductions
        $leaveDaysWithPermission = $this->getLeaveDaysWithPermission($user_id, $month, $year);
        $deductionForLeaveDays = 0;
        if (count($leaveDaysWithPermission) > 4) {
            $deductionForLeaveDays = (count($leaveDaysWithPermission) - 4) * 200000;
        }

        // Get unapproved leave days and calculate deductions
        $unapprovedLeaveDays = $this->getUnapprovedLeaveDays($user_id, $month, $year);
        $deductionForUnapprovedLeaveDays = count($unapprovedLeaveDays) * 200000;

        // Get attendance deductions
        $attendanceDeductionsArray = $this->getAttendanceDeductionsArray($user_id, $month, $year);
        $deductionForAttendance = 0;
        foreach ($attendanceDeductionsArray as $deduction) {
            $deductionForAttendance += $deduction['totalDeduction'];
        }

        // Calculate total deductions
        $totalDeductions = $deductionForLeaveDays + $deductionForUnapprovedLeaveDays + $deductionForAttendance;

        return $totalDeductions;
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

// public function addTransaction($user_id, $amount, $transaction_date) {
//     $sql = "INSERT INTO transactions (user_id, category_id, amount, transaction_date, is_active,calculation_date)
//             VALUES (:user_id, 6, :amount, :transaction_date, 1,CURDATE())";

//     try {
//         $link = $this->getConnection(); // Kết nối tới cơ sở dữ liệu
//         $stmt = $link->prepare($sql);

//         // Cố định category_id là 6
        

//         // Gán các tham số vào câu lệnh SQL
//         $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
       
//         $stmt->bindParam(':amount', $amount);
//         $stmt->bindParam(':transaction_date', $transaction_date);
        

//         // Thực thi câu lệnh
//         if ($stmt->execute()) {
//             return 1; // Thành công
//         } else {
//             return 0; // Thất bại
//         }
//     } catch (PDOException $e) {
//         echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
//         return 0; // Lỗi xảy ra
//     }
// }
public function addTransaction($user_id, $amount, $transaction_date) {
    // Retrieve existing transactions
    $transactions = $this->getTransactions();

    // Check for existing transaction with the same user_id, category_id, and transaction_date
    foreach ($transactions as $transaction) {
        if ($transaction['user_id'] == $user_id && 
            $transaction['category_id'] == 6 && // Assuming category_id is fixed as 6
            $transaction['transaction_date'] == $transaction_date) {
            return 2; // Return 2 if a matching transaction is found
        }
    }

    // If no matching transaction is found, proceed to insert a new one
    $sql = "INSERT INTO transactions (user_id, category_id, amount, transaction_date, is_active, calculation_date)
            VALUES (:user_id, 6, :amount, :transaction_date, 1, CURDATE())";

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
}
?>