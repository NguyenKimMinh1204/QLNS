<?php
include '../../db/connetion.php';
class tinhluong_nv extends Database{     
// public function tinhluong_nv($department_id,$month = null,$year = null){
//     // Set default to current month and year only if not provided in the function call
//     if ($month === null) $month = date('m');
//     if ($year === null) $year = date('Y');

//     // Tính toán ngày bắt đầu và kết thúc
//     $start_date = "$year-$month-04";
//     $nextMonth = $month == 12 ? 1 : $month + 1;
//     $nextYear = $month == 12 ? $year + 1 : $year;
//     $end_date = "$nextYear-$nextMonth-03";
//     $sql = 'SELECT u.manv, u.full_name, u.email, r.role_name, d.department_name, d.salary_coefficient, r.rank_salary, 
//             SUM(s.work_time) as sum_work_time, d.maphong,COUNT(s.work_time) AS count_work_time
//             FROM users u 
//             INNER JOIN user_info ui ON u.info_id = ui.id
//             INNER JOIN roles r ON u.role_id = r.id
//             INNER JOIN departments d ON u.department_id = d.id
//             INNER JOIN attendance a ON u.id = a.user_id
//             INNER JOIN shifts s ON s.id = a.shift_id

//             WHERE u.department_id = :department_id
//             AND MONTH(a.clock_in_time) = :month
//             AND YEAR(a.clock_in_time) = :year;';
//   $link = $this->getConnection();
//     $link = $this->getConnection();
//     if ($link) {
       
//         $stmt = $link->prepare($sql);
        
//         // Gán giá trị cho placeholder :id
//         $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
//         $stmt->bindParam(':month', $month, PDO::PARAM_INT);
//         $stmt->bindParam(':year', $year, PDO::PARAM_INT);
//         $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
//         $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
       
//         // Thực thi truy vấn
//         $stmt->execute();

//         // Lấy kết quả
//         $ketqua = $stmt->fetchAll(PDO::FETCH_ASSOC);
//         $i = count($ketqua);

//         // Kiểm tra nếu có kết quả
//         if ($i > 0) {
//             $thuong=0;
//             $phucap=0;
//             $phat=0;
//             $thunhapkhac=0;
//              echo '<table class="table table-bordered table-hover">
//                     <thead>
//                         <tr>
//                          <th>STT</th>
//                         <th>Mã nhân viên</th>
//                             <th>Tên nhân viên</th>
                       
                          
//                            <th>Tháng tính lương</th>
//                             <th>Giá trị Lương</th> 
                            
                       
//                             <th>Hành động</th>
//                         </tr>
//                     </thead>
//                     <tbody>';
//             $dem=1;
//               if ($result && count($result) > 0) {
//                 foreach($ketqua as $row){
//                         $user = $ketqua[0];
//                         $luongchinh = $user['rank_salary'] * $user['salary_coefficient'];
//                         $ngaycongtieuchuan = $this->getWeekdaysCountFromRange($month, $year);
//                         $luongngaycong = round(($luongchinh / $ngaycongtieuchuan) * $user['sum_work_time'],3);
//                         $allowances = $this->loadphucap($user['user_id'], $month, $year);
//                         foreach ($allowances as $allowance) {
//                             switch ($allowance['category_id']) {
//                                 case 2:
//                                     $thuong = $allowance['amount'];
//                                     break;
//                                 case 3:
//                                     $phucap = $allowance['amount'];
//                                     break;
//                                 case 4:
//                                     $phat = $allowance['amount'];
//                                     break;
//                                 case 6:
//                                     $thunhapkhac = $allowance['amount'];
//                                     break;
//                             }
//                         }
//                         $tongphucaphh=$thuong+$phucap+$thunhapkhac;
//                         $tongthunhap=$luongngaycong+$tongphucaphh;
                        
//                         $baohiem=$luongngaycong*0.105;
                        
//                         $tongcackhoantru=$phat+$baohiem;
//                         $thunhaptruocthue=$tongthunhap-$tongcackhoantru;
//                         if($thunhaptruocthue<=5000000){
//                             $thuethunhapcanhan=$thunhaptruocthue*0.05;
//                         }elseif($thunhaptruocthue>5000000 && $thunhaptruocthue<=10000000){
//                             $thuethunhapcanhan=$thunhaptruocthue*0.1-250000;
//                         }elseif($thunhaptruocthue>10000000 && $thunhaptruocthue<=18000000){
//                             $thuethunhapcanhan=$thunhaptruocthue*0.15-750000;   
//                         }elseif($thunhaptruocthue>18000000&&$thunhaptruocthue<=32000000){
//                             $thuethunhapcanhan=$thunhaptruocthue*0.2-1650000;
//                         }elseif($thunhaptruocthue>32000000 && $thunhaptruocthue<=52000000){
//                             $thuethunhapcanhan=$thunhaptruocthue*0.25-3250000;
//                         }elseif($thunhaptruocthue>52000000 && $thunhaptruocthue<=80000000){
//                             $thuethunhapcanhan=$thunhaptruocthue*0.3-5850000;
//                         }elseif($thunhaptruocthue>80000000){
//                             $thuethunhapcanhan=$thunhaptruocthue*0.35-9850000;
//                         }
//                         $thunhapsauthue=$thunhaptruocthue-$thuethunhapcanhan;
//                         $date=date('Y-m-05',strtotime($start_date));
//                         echo '<tr>
//                                         <td>' . htmlspecialchars($dem) . '</td>
//                                         <td>' . htmlspecialchars($row['manv']) . '</td>
//                                         <td>' . htmlspecialchars($row['full_name']) . '</td>
//                                     <td>' . htmlspecialchars($date) . '</td>
                                    
                                    
//                                         <td></td>
                                    
//                                         <td>buttun</td>
                                        
//                                         </tr>';
//                         $dem++;
                    
//                             }
          
//             }else {    
//                     echo '<tr><td colspan="10" class="text-center">Không có dữ liệu</td></tr>';
//                 }   
//              echo '</tbody>
//                 </table>';
//             }
                
//     } else {
//         echo 'Không thể kết nối đến cơ sở dữ liệu';
//     }
    
// }
    public function tinhluong_nv($department_id = null, $month = null, $year = null)
{
    // Set default to current month and year only if not provided in the function call
    if ($month === null) $month = date('m');
    if ($year === null) $year = date('Y');

    // Tính toán ngày bắt đầu và kết thúc
    $start_date = "$year-$month-04";
    $nextMonth = $month == 12 ? 1 : $month + 1;
    $nextYear = $month == 12 ? $year + 1 : $year;
    $end_date = "$nextYear-$nextMonth-03";

    // SQL query to get the necessary data
    $sql = 'SELECT a.user_id,u.manv, u.full_name, u.email, r.role_name, d.department_name, d.salary_coefficient, r.rank_salary, 
             SUM(s.work_time) as sum_work_time, d.maphong, COUNT(s.work_time) AS count_work_time
             FROM users u 
             INNER JOIN user_info ui ON u.info_id = ui.id
             INNER JOIN roles r ON u.role_id = r.id
             INNER JOIN departments d ON u.department_id = d.id
             INNER JOIN attendance a ON u.id = a.user_id
             INNER JOIN shifts s ON s.id = a.shift_id
             WHERE MONTH(a.clock_in_time) = :month
             AND YEAR(a.clock_in_time) = :year';

    // Add department condition if department_id is provided
    if ($department_id) {
        $sql .= ' AND u.department_id = :department_id';
    }
   
    

    // Get the database connection
    $link = $this->getConnection();
    
    if ($link) {
        $stmt = $link->prepare($sql);

        // Bind parameters
        if ($department_id) {
            $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
        }
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Fetch results
        $ketqua = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // If there are results
        if (count($ketqua) > 0) {
            echo '<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã nhân viên</th>
                            <th>Tên nhân viên</th>
                            <th>Tháng tính lương</th>
                            <th>Giá trị Lương</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>';
            $dem = 1;

            // Process each result row
            foreach ($ketqua as $row) {
                $luongchinh = $row['rank_salary'] * $row['salary_coefficient'];
                $bangluong= $this->getSalariesByUserAndDate($row['user_id'], $year, $month);
                $ngaycongthucte=$this->loadAttendanceDays($row['user_id'], $month, $year);
                // Assuming you have a function to calculate weekdays
                $ngaycongtieuchuan = $this->getWeekdaysCountFromRange($month, $year);
                $luongngaycong = round(($luongchinh / $ngaycongtieuchuan) * $ngaycongthucte, 3);

                // Load allowances (assuming the method is correct)
                $allowances = $this->loadphucap($row['user_id'], $month, $year);
                
                // Initialize variables for allowances
                $thuong = 0;
                $phucap = 0;
                $phat = 0;
                $thunhapkhac = 0;

                // Process allowances
                foreach ($allowances as $allowance) {
                    switch ($allowance['category_id']) {
                        case 2: $thuong = $allowance['amount']; break;
                        case 6: $phat = $allowance['amount']; break;
                        case 4: $thunhapkhac = $allowance['amount']; break;
                    }
                }
                $phucap = 500000;
                // Total allowances
                
                $tongphucaphh = $thuong + $phucap + $thunhapkhac;
                $tongthunhap = $luongngaycong + $tongphucaphh;

                // Calculate insurance and other deductions
                $baohiem = $luongngaycong * 0.105;
                
                
                    $thunhaptruocthue = $tongthunhap;
                
                if($tongthunhap>11000000){
                    $thunhapchiuthue=0;
                    $thunhapchiuthue = max(0, ($tongthunhap - 11000000 - $baohiem));
                
                // Calculate tax based on income
                if ($thunhapchiuthue <= 5000000) {
                    $thuethunhapcanhan = $thunhapchiuthue * 0.05;
                } elseif ($thunhapchiuthue <= 10000000) {
                    $thuethunhapcanhan = $thunhapchiuthue * 0.1 - 250000;
                } elseif ($thunhapchiuthue <= 18000000) {
                    $thuethunhapcanhan = $thunhapchiuthue * 0.15 - 750000;
                } elseif ($thunhapchiuthue <= 32000000) {
                    $thuethunhapcanhan = $thunhapchiuthue * 0.2 - 1650000;
                } elseif ($thunhapchiuthue <= 52000000) {
                    $thuethunhapcanhan = $thunhapchiuthue * 0.25 - 3250000;
                } elseif ($thunhapchiuthue <= 80000000) {
                    $thuethunhapcanhan = $thunhapchiuthue * 0.3 - 5850000;
                } else {
                    $thuethunhapcanhan = $thunhapchiuthue * 0.35 - 9850000;
                }
                }else{
                    $thuethunhapcanhan=0;
                }
           
                $tongcackhoantru = $phat + $baohiem+$thuethunhapcanhan;
                $thunhapsauthue=$tongthunhap-$tongcackhoantru;
                $rs=$this->getSalariesByUserAndDate($row['user_id'], $year, $month);
                // $luongdatinh = $rs[0]['net_salary'];
               if ($ngaycongthucte > 10) {
                    if (!empty($rs[0]['net_salary']) && $rs[0]['net_salary'] !== '') {
                        $luongdatinh = $rs[0]['net_salary'];
                    } else {
                        $luongdatinh = 0;
                    }
                } else {
                    $luongdatinh = 0;
                }

                
                // Format the salary data and display it
                $date = date('Y-m-05', strtotime($start_date));

                $luongchinh = round((float) str_replace(',', '.', $luongchinh ?? '0'), 1);
             
                $luongngaycong = round((float) str_replace(',', '.', $luongngaycong ?? '0'), 1);
                $thuong = round((float) str_replace(',', '.', $thuong ?? '0'), 1);
                $phucap = round((float) str_replace(',', '.', $phucap ?? '0'), 1);
                $phat = round((float) str_replace(',', '.', $phat ?? '0'), 1);
                $thunhapkhac = round((float) str_replace(',', '.', $thunhapkhac ?? '0'), 1);
                $tongphucaphh = round((float) str_replace(',', '.', $tongphucaphh ?? '0'), 1);
                $tongthunhap = round((float) str_replace(',', '.', $tongthunhap ?? '0'), 1);
                $baohiem = round((float) str_replace(',', '.', $baohiem ?? '0'), 1);
                $tongcackhoantru = round((float) str_replace(',', '.', $tongcackhoantru ?? '0'), 1);
                $thunhaptruocthue = round((float) str_replace(',', '.', $thunhaptruocthue ?? '0'), 1);
                $thuethunhapcanhan = round((float) str_replace(',', '.', $thuethunhapcanhan ?? '0'), 1);
        $thunhapsauthue = round((float) str_replace(',', '.', $thunhapsauthue ?? '0'), 1);
                echo '<tr>
                        <td>' . htmlspecialchars($dem) . '</td>
                        <td>' . htmlspecialchars($row['manv']) . '</td>
                        <td>' . htmlspecialchars($row['full_name']) . '</td>
                        <td>' . htmlspecialchars($date) . '</td>
                        <td>' . htmlspecialchars($luongdatinh) . ''.$thuong.'</td>
                        <td><button type="button" class="btn btn-primary" data-toggle="modal"
                            data-target="#salarySlipModal" id="xemluong"
                            data-date="' . ($date ?? '') . '"
                            data-user_id="' . ($row['user_id'] ?? 0) . '"
                            data-fullname="' . ($row['full_name'] ?? '') . '"
                            data-manv="' . ($row['manv'] ?? '') . '"
                            data-email="' . ($row['email'] ?? '') . '"
                            data-rolename="' . ($row['role_name'] ?? '') . '"
                            data-department_name="' . ($row['department_name'] ?? '') . '"
                            data-luongchinh="' . ($luongchinh ?? 0) . '"
                            data-ngaycongtieuchuan="' . ($ngaycongtieuchuan ?? 0) . '"
                            data-ngaycongthucte="' . ($ngaycongthucte ?? 0) . '"
                            data-luongngaycong="' . ($luongngaycong ?? 0) . '"
                            data-thuong="' . ($thuong ?? 0) . '"
                            data-phucap="' . ($phucap ?? 0) . '"
                            data-phat="' . ($phat ?? 0) . '"
                            data-thunhapkhac="' . ($thunhapkhac ?? 0) . '"
                            data-tongphucaphh="' . ($tongphucaphh ?? 0) . '"
                            data-tongthunhap="' . ($tongthunhap ?? 0) . '"
                            data-baohiem="' . ($baohiem ?? 0) . '"
                            data-tongcackhoantru="' . ($tongcackhoantru ?? 0) . '"
                            
                            data-thuethunhapcanhan="' . ($thuethunhapcanhan ?? 0) . '"
                            data-thunhapsauthue="' . ($thunhapsauthue ?? 0) . '"
                            
                            >Xem chi tiết</button></td>
                    </tr>';
                $dem++;
            }

            echo '</tbody>
                </table>';
        } else {
            echo '<tr><td colspan="6" class="text-center">Không có dữ liệu</td></tr>';
        }
    } else {
        echo 'Không thể kết nối đến cơ sở dữ liệu';
    }
}
 public function getDepartments() {
        $link = $this->getConnection();
        $stmt = $link->prepare("SELECT * FROM departments");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
public function getSalaries() {
    try {
        $link = $this->getConnection(); // Kết nối cơ sở dữ liệu
        $stmt = $link->prepare("SELECT * FROM salary");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Trả về danh sách dưới dạng mảng
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
        return [];
    }
}

public function addSalary($user_id, $net_salary, $day_salary) {
    // Lấy danh sách lương hiện tại
    $salaries = $this->getSalaries(); // Phương thức getSalaries() cần được định nghĩa để lấy dữ liệu từ bảng salary

    // Kiểm tra xem đã tồn tại lương với user_id và day_salary chưa
    foreach ($salaries as $salary) {
        if ($salary['user_id'] == $user_id && $salary['day_salary'] == $day_salary) {
            return 5; // Nếu trùng lặp, trả về 2
        }
    }

    // Nếu không trùng lặp, thêm bản ghi mới
    $sql = "INSERT INTO salary (user_id, net_salary, day_salary, is_active)
            VALUES (:user_id, :net_salary, :day_salary, 0)";

    try {
        $link = $this->getConnection(); // Kết nối cơ sở dữ liệu
        $stmt = $link->prepare($sql);

        // Gắn giá trị cho các tham số
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':net_salary', $net_salary);
        $stmt->bindParam(':day_salary', $day_salary);

        // Thực thi câu lệnh
        if ($stmt->execute()) {
            return 1; // Thành công
        } else {
            return 0; // Thất bại
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
        return 0; // Lỗi xảy ra
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

    // Lấy kết quả trả về và kiểm tra
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return !empty($results) ? $results : [];
}

}
?>