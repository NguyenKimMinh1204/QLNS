<?php
include '../../db/connetion.php';

class phieuluong extends Database{
    function getWorkingDays($month, $year) {
        // Tổng số ngày trong tháng
        $totalDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    
        $workingDays = 0; // Biến đếm số ngày làm việc
    
        // Lặp qua từng ngày trong tháng
        for ($day = 1; $day <= $totalDays; $day++) {
            // Lấy thông tin thứ của ngày
            $dayOfWeek = date('N', strtotime("$year-$month-$day"));
    
            // Chỉ tính ngày làm việc (thứ 2 đến thứ 6)
            if ($dayOfWeek >= 1 && $dayOfWeek <= 5) {
                $workingDays++;
            }
        }
    
        return $workingDays;
    }
public function loadphieuluong( $user_id, $month, $year) {
    // Kiểm tra xem đã có bảng lương chưa
    $check_salary_sql = "SELECT * FROM salary s
                        WHERE s.user_id = :user_id
                        AND MONTH(s.day_salary) = :month
                        AND YEAR(s.day_salary) = :year";
    
    $link = $this->getConnection();
    if ($link) {
        // Kiểm tra bảng lương
        $check_stmt = $link->prepare($check_salary_sql);
        $check_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $check_stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $check_stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $check_stmt->execute();
        $salary_exists = $check_stmt->fetch(PDO::FETCH_ASSOC);

        if (!$salary_exists) {
            echo 'Chưa được tính lương';
            return;
        }

        // Sửa câu truy vấn để lấy dữ liệu dựa trên ID của người dùng
        $sql = 'SELECT u.manv, u.full_name, u.email, r.role_name, d.department_name, d.salary_coefficient, r.rank_salary, 
                SUM(s.work_time) as sum_work_time, d.maphong,COUNT(s.work_time) AS count_work_time
                FROM users u 
                INNER JOIN user_info ui ON u.info_id = ui.id
                INNER JOIN roles r ON u.role_id = r.id
                INNER JOIN departments d ON u.department_id = d.id
                INNER JOIN attendance a ON u.id = a.user_id
                INNER JOIN shifts s ON s.id = a.shift_id

                WHERE u.id = :user_id
                AND MONTH(a.clock_in_time) = :month
                AND YEAR(a.clock_in_time) = :year;'; 
        
        // Thực thi truy vấn
        $stmt = $link->prepare($sql);
        
        // Gán giá trị cho placeholder :id
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
       
        // Thực thi truy vấn
        $stmt->execute();

        // Lấy kết quả
        $ketqua = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $i = count($ketqua);

        // Kiểm tra nếu có kết quả
        if ($i > 0) {
           
            $user = $ketqua[0];
            $luongchinh = $user['rank_salary'] * $user['salary_coefficient'];
            // $ngaycongtieuchuan = $this->getWorkingDays($month, $year);
            $ngaycong=$this->loadAttendanceDays($user_id,$month, $year);
            $ngaycongtieuchuan=$ngaycong['standard_days'];
            $ngaycongthucte=$ngaycong['actual_days'];
            $luongngaycong = round(($luongchinh / $ngaycongtieuchuan) * $user['sum_work_time'],3);
            $luongkpi=$luongngaycong;
            echo '
                    
                            <tr>
                            <td>1</td>
                            <td>Mã nhân viên</td>
                            <td>'.$user['manv'].'</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>2</td>
                            <td>Họ và tên nhân viên</td>
                            <td>'.$user['full_name'].'</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>3</td>
                            <td>Email</td>
                            <td>'.$user['email'].'</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>4</td>
                            <td>Chức danh công việc</td>
                            <td>'.$user['role_name'].' '.$user['maphong'].'</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>5</td>
                            <td>Phòng</td>
                            <td>'.$user['department_name'].'</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>6</td>
                            <td>Lương chính thức</td>
                            <td>'.number_format($luongchinh, 1, ',', '.').'</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>7</td>
                            <td>Ngày công tiêu chuẩn</td>
                            <td>'.$ngaycongtieuchuan.'</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>8</td>
                            <td>Ngày công thực tế</td>
                            <td>'.$ngaycongthucte.'</td>
                            <td></td>
                            </tr>
                            <tr class="highlight">
                            <td>9</td>
                            <td>Lương theo ngày công LVTT</td>
                            <td>'.number_format($luongngaycong, 1,',', '.').'</td>
                            <td>[1]</td>
                            </tr>
                            
                            ';
                            $this->loadphucap($user_id, $month, $year, $luongkpi,$ngaycongthucte);
                            
        } else {
            // Log the parameters for debugging
            error_log("No data found for user_id: $user_id, month: $month, year: $year");
            echo 'Không có dữ liệu';
        }
    } else {
        echo 'Không thể kết nối đến cơ sở dữ liệu';
    }
}
public function loadphucap( $user_id, $month, $year,$luongkpi,$ngaycongthucte) {
    $sql = 'SELECT  tr.id, tr.user_id, tr.category_id, tr.amount, tr.transaction_date, ca.name
            FROM   
            transactions tr
            INNER JOIN categories ca ON ca.id = tr.category_id 
                        
            WHERE tr.user_id = :user_id
            AND MONTH(tr.transaction_date) = :month
            AND YEAR(tr.transaction_date) = :year;';
            $link = $this->getConnection();
    
    if ($link) {
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':month', $month, PDO::PARAM_INT);
        $stmt->bindParam(':year', $year, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (count($result) > 0) {
            $dem=10;
            $pc_hh=0;
            foreach ($result as $row) {
                if($row['category_id']==2 || $row['category_id']==3 ||  $row['category_id']==4){
                     echo '<tr>
                            <td>'.$dem.'</td>
                            <td>'.$row['name'].'</td>
                            <td>'.number_format($row['amount'], 3, ',', '.').'</td>
                            <td></td>
                        </tr>
                           ';
                           $pc_hh+=$row['amount'];
                } 
                
                
                $dem++;
               
            }
            $dem++;
            echo '<tr class="highlight">
                    <td>'.$dem.'</td>
                    <td>Tổng Phụ cấp + Thưởng</td>
                    <td>'.number_format($pc_hh, 3, ',', '.').'</td>
                    <td>[2]</td>
                </tr>
                ';
                $dem++;
                $sum_amount=$pc_hh+$luongkpi;
            echo '<tr class="highlight">
                    <td>'.$dem.'</td>
                    <td>Tổng thu nhập</td>
                    <td>'.number_format($sum_amount, 3, ',', '.').'</td>
                    <td>[3] =[1]+[2]</td>
                </tr>
                ';
                $sumtru=0;
            foreach ($result as $row) {
                if($row['category_id']==5 || $row['category_id']==6 || $row['category_id']==7 || $row['category_id']==8){
                     echo '<tr>
                            <td>'.$dem.'</td>
                            <td>'.$row['name'].'</td>
                            <td>'.number_format($row['amount'], 3, ',', '.').'</td>
                            <td></td>
                        </tr>
                           ';
                           $sumtru+=$row['amount'];
                } 
                
               
                $dem++;
               
            }
            echo '<tr class="highlight">
                            <td>'.$dem.'</td>
                            <td>Tổng các khoản trừ</td>
                            <td>'.number_format($sumtru, 3, ',', '.').'</td>
                            <td>[4]</td>
                        </tr>
                           ';
          $bangluong= $this->getSalariesByUserAndDate($row['user_id'], $year, $month);
                $luongdatinh=$bangluong[0]['net_salary'];
            echo '<tr class="highlight">
                    <td>'.$dem.'</td>
                    <td>Tổng thu nhập</td>
                    <td>'.number_format($luongdatinh, 3, ',', '.').'</td>
                    <td>[5] =[4]+[3]</td>
                </tr>
                ';

        } else {
            echo 'No data available';
        }
    } else {
        echo 'Cannot connect to the database';
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
    $sql = 'SELECT SUM(s.work_time) as total_work_time
            FROM attendance a
            INNER JOIN shifts s ON s.id = a.shift_id
            WHERE a.user_id = :user_id
              AND DATE(a.clock_in_time) BETWEEN :start_date AND :end_date';

    $link = $this->getConnection();
    if ($link) {
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':start_date', $startDate, PDO::PARAM_STR);
        $stmt->bindParam(':end_date', $endDate, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Get standard working days using getWeekdaysCountFromRange
        $standardDays = $this->getWeekdaysCountFromRange($month, $year);
        $actualDays = $result['total_work_time'] ?? 0;

        return [
            'standard_days' => $standardDays,
            'actual_days' => $actualDays
        ];
    }
    
    return [
        'standard_days' => 0,
        'actual_days' => 0
    ];
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


}
?>