<?php
include '../../db/connetion.php';

class chamconge extends Database{
   
    public function getClientIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // IP từ proxy
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // IP từ proxy có forwarding
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        // IP thực tế của thiết bị
        return $_SERVER['REMOTE_ADDR'];
    }
}

    public function getLocalIPv4() {
        // Use `ipconfig` to get network info on Windows
        $output = shell_exec('ipconfig');
    
        // Match the IPv4 address
        preg_match('/IPv4 Address[.\s]+:\s*([\d.]+)/', $output, $matches);
    
        // Return the found IP or an empty string if not found
        return $matches[1] ?? 'IP not found';
    }
    //lấy ip wwifi labtop win
    // public function getWiFiIPv4() {
    //     // Run the `ipconfig` command to get network information
    //     $output = shell_exec('ipconfig');
    
    //     // Find the Wi-Fi adapter block and match the IPv4 address
    //     if (preg_match('/Wireless LAN adapter Wi-Fi:(?:.*\R)*?.*IPv4 Address[.\s]+:\s*([\d.]+)/', $output, $matches)) {
    //         return $matches[1]; // Return the found Wi-Fi IPv4 address
    //     }
    
    //     return 'Wi-Fi IP not found'; // Return a default message if not found
    // }
    public function getWiFiIPv4() {
    if (!function_exists('shell_exec')) {
        return 'shell_exec is disabled on this server.';
    }

    $output = shell_exec('ipconfig');
    if (empty($output)) {
        return 'Unable to execute ipconfig or no output received.';
    }

    if (preg_match('/Wireless LAN adapter Wi-Fi:(?:.*\R)*?.*IPv4 Address[.\s]+:\s*([\d.]+)/i', $output, $matches)) {
        return $matches[1];
    }

    return 'Wi-Fi IPv4 Address not found';
}


    public function check_ip_address($ip_wf){
        $sql='SELECT * FROM wifi_addresses';
        $link = $this->getConnection();
        if ($link) {
             $stmt = $link->prepare($sql);
            $stmt->execute();
    
            // Lấy tất cả dữ liệu
            $ketqua = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $i = count($ketqua);
            
            if ($i > 0) {
                $dem='';
                foreach ($ketqua as $row) {
                    $ip = $row['ip_address'];
                    if($ip==$ip_wf){
                            $dem=$ip;
                    }
                }
                    return $dem;       
            }
        }else {
                throw new Exception("Could not connect to the database");
            }
    
    }
    public function check_in($clock_in,$user_id) {
        $sql = 'SELECT s.id, s.shift_name, s.start_time, s.end_time, s.work_time FROM shifts s';
        $link = $this->getConnection();
    
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->execute();
    
            // Lấy tất cả dữ liệu ca làm
            $shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if (count($shifts) > 0) {
                $check_in = 0;
                $thoi_gian_tre_minutes = 0; // Để lưu thời gian trễ (nếu cần)
    
                foreach ($shifts as $row) {
                    $start_time = $row['start_time']; // Lấy thời gian bắt đầu ca từ DB
    
                    // Chuyển đổi `clock_in` và `start_time` thành chỉ giờ và phút
                    $clock_in_time = date('H:i', strtotime($clock_in));
                    $start_time_formatted = date('H:i', strtotime($start_time));
    
                    // Xác định giới hạn trước và sau
                    $early_bound = date('H:i', strtotime("$start_time_formatted -1 hour"));
                    $late_bound = date('H:i', strtotime("$start_time_formatted +2 hour"));
    
                    // Kiểm tra thời gian `clock_in` trong khoảng hợp lệ
                    if ($clock_in_time >= $early_bound && $clock_in_time <= $late_bound) {
                        // Tính thời gian trễ (chỉ tính nếu lớn hơn 0)
                         // Chuyển giây thành phút
    
                        if ($start_time_formatted == "08:00") {
                            $thoi_gian_tre = max(0, strtotime($clock_in_time) -strtotime($start_time_formatted));
                            $thoi_gian_tre_minutes = round($thoi_gian_tre / 60);
                            $check_in = 1; // Ca sáng
                        } elseif ($start_time_formatted == "13:00") {
                            $thoi_gian_tre = max(0, strtotime($clock_in_time) -strtotime($start_time_formatted));
                            $thoi_gian_tre_minutes = round($thoi_gian_tre / 60);
                            $check_in = 2; // Ca chiều
                        } else {
                            $check_in = 0; // Không hợp lệ
                            $thoi_gian_tre = max(0, strtotime($clock_in_time) -strtotime($start_time_formatted));
                            $thoi_gian_tre_minutes = round($thoi_gian_tre / 60);
                        }
    
                        break; // Thoát khỏi vòng lặp sau khi xác định được ca làm
                    }
                }
    
               return [
                        'check_in' => $check_in,
                        'thoi_gian_tre' => $thoi_gian_tre_minutes, // Thời gian trễ được trả về
                        
                    ];
            }
        } else {
            throw new Exception("Could not connect to the database");
        }
    }
    public function check_out($clock_out, $check_in, $attendance_id) {
    $sql = 'SELECT s.id, s.shift_name, s.start_time, s.end_time, s.work_time FROM shifts s';
    $link = $this->getConnection();

    if ($link) {
        $stmt = $link->prepare($sql);
        $stmt->execute();

        // Lấy tất cả dữ liệu ca làm
        $shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Khởi tạo biến kết quả
        $shift_id = 0;
        $thoi_gian_ve_som = 0;

        // Chuyển clock_out về định dạng hh:mm
        $clock_out_time = date('H:i', strtotime($clock_out));

        foreach ($shifts as $row) {
            $start_time = $row['start_time'];
            $end_time = $row['end_time'];

            // Chuyển đổi giờ bắt đầu và giờ kết thúc
            $start_time_formatted = date('H:i', strtotime($start_time));
            $end_time_formatted = date('H:i', strtotime($end_time));

            // Xác định giới hạn thời gian kết thúc
            $early_bound = date('H:i', strtotime("$end_time_formatted -1 hour"));
            $late_bound = date('H:i', strtotime("$end_time_formatted +2 hour"));

            // Kiểm tra ca làm
            if ($check_in == 1 && $start_time_formatted == "08:00" && $end_time_formatted == "12:00") {
                if ($clock_out_time >= $early_bound && $clock_out_time <= $late_bound) {
                    $shift_id = 1; // Ca sáng
                    $thoi_gian_ve_som = max(0, strtotime($end_time_formatted) - strtotime($clock_out_time)) / 60; // Tính phút
                    break;
                }
            } elseif ($check_in == 1 && $start_time_formatted == "08:00" && $end_time_formatted == "17:00") {
                if ($clock_out_time >= $early_bound && $clock_out_time <= $late_bound) {
                    $shift_id = 3; // Ca nguyên ngày
                    $thoi_gian_ve_som = max(0, strtotime($end_time_formatted) - strtotime($clock_out_time)) / 60; // Tính phút
                    break;
                }
            } elseif ($check_in == 2 && $start_time_formatted == "13:00" && $end_time_formatted == "17:00") {
                if ($clock_out_time >= $early_bound && $clock_out_time <= $late_bound) {
                    $shift_id = 2; // Ca chiều
                    $thoi_gian_ve_som = max(0, strtotime($end_time_formatted) - strtotime($clock_out_time)) / 60; // Tính phút
                    break;
                }
            }
        }


        return ['shift_id' => $shift_id, 'thoi_gian_ve_som' => $thoi_gian_ve_som, 'clock_out_time' => $clock_out_time, 'check_in' => $check_in];
    } else {
        throw new Exception("Could not connect to the database");
    }
}

public function clockIn($user_id, $wifi_address, $check_in_time = null) {
    // Nếu không nhập thời gian check_in, sử dụng thời gian hiện tại
    $clock_in_time = $check_in_time ? $check_in_time : date("Y-m-d H:i:s");

    // Gọi hàm check_in để xác định `check_in` và `shift_id`
    $check_in_result = $this->check_in($clock_in_time, $user_id);
    $check_in_status = $check_in_result['check_in'];
    $lateLeaveTime = $check_in_result['thoi_gian_tre']; // Lấy thời gian trễ

    // Thực hiện câu lệnh INSERT
    $sql = '
        INSERT INTO attendance (user_id, clock_in_time, wifi_address, check_in, lateLeaveTime) 
        VALUES (:user_id, :clock_in_time, :wifi_address, :check_in, :lateLeaveTime)
    ';
    
    $link = $this->getConnection();
    if ($link) {
        $stmt = $link->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':clock_in_time', $clock_in_time);
        $stmt->bindParam(':wifi_address', $wifi_address); // Vẫn giữ lại để lưu địa chỉ IP
        $stmt->bindParam(':check_in', $check_in_status, PDO::PARAM_INT);
        $stmt->bindParam(':lateLeaveTime', $lateLeaveTime, PDO::PARAM_INT); // Bind lateLeaveTime

        // Execute the query
        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Clock-in successful',
                'check_in_status' => $check_in_status
            ];
        } else {
            throw new Exception("Clock-in failed. Please try again.");
        }
    } else {
        throw new Exception("Could not connect to the database.");
    }
}
public function clockOut($attendance_id, $wifi_address, $check_out_time = null) {
    $link = $this->getConnection();

    if ($link) {
        // Lấy thông tin clock_in_time và check_in từ attendance
        $sql = 'SELECT clock_in_time, check_in FROM attendance WHERE id = :id';
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':id', $attendance_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // Nếu không nhập thời gian check_out, sử dụng thời gian hiện tại
            $clock_out_time = $check_out_time ? $check_out_time : date("Y-m-d H:i:s");

            // Gọi hàm check_out để tính toán shift_id, work_time và thoi_gian_ve_som
            $check_in = $result['check_in'];
            $check_out_result = $this->check_out($clock_out_time, $check_in, $attendance_id);

            // Kiểm tra kết quả trả về từ hàm check_out
            if ($check_out_result) {
                // Cập nhật thông tin clock_out_time, wifi_address_out, shift_id và earlyTime vào bảng attendance
                $update_sql = '
                    UPDATE attendance 
                    SET clock_out_time = :clock_out_time, wifi_address_out = :wifi_address_out, shift_id = :shift_id, earlyTime = :earlyTime
                    WHERE id = :id
                ';
                $stmt_update = $link->prepare($update_sql);
                $stmt_update->bindParam(':clock_out_time', $clock_out_time);
                $stmt_update->bindParam(':wifi_address_out', $wifi_address); // Giữ lại để lưu địa chỉ IP
                $stmt_update->bindParam(':shift_id', $check_out_result['shift_id']);
                $stmt_update->bindParam(':id', $attendance_id, PDO::PARAM_INT);
                $stmt_update->bindParam(':earlyTime', $check_out_result['thoi_gian_ve_som']); // Bind earlyTime

                if ($stmt_update->execute()) {
                    return [
                        'success' => true,
                        'message' => 'Clock-out successful',
                        'shift_id' => $check_out_result['shift_id']
                    ];
                } else {
                    throw new Exception("Clock-out failed. Please try again.");
                }
            } else {
                throw new Exception("Error calculating clock-out details.");
            }
        } else {
            throw new Exception("No matching attendance record found for clock-out.");
        }
    } else {
        throw new Exception("Could not connect to the database.");
    }
}
    public function getLatestAttendanceId($user_id) {
        $sql = 'SELECT id FROM attendance 
                WHERE user_id = :user_id 
                AND DATE(clock_in_time) = CURDATE() 
                AND clock_out_time IS NULL 
                ORDER BY clock_in_time DESC 
                LIMIT 1';
        
        $link = $this->getConnection();
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['id'] : null;
        }
        return null;
    }
    public function getAttendanceStatus($user_id) {
        $sql = "SELECT clock_in_time, clock_out_time 
                FROM attendance 
                WHERE user_id = :user_id 
                AND DATE(clock_in_time) = CURDATE()
                ORDER BY clock_in_time DESC 
                LIMIT 1";
        
        $link = $this->getConnection();
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }
   

    public function loadLeaveData1($user_id, $month = null, $year = null) {
        if ($month === null) $month = date('m');
        if ($year === null) $year = date('Y');
    
        $sql = 'SELECT * FROM leave_requests 
                WHERE user_id = :user_id 
                AND (
                    (MONTH(start_date) = :month AND YEAR(start_date) = :year)
                    OR 
                    (MONTH(end_date) = :month AND YEAR(end_date) = :year)
                )
                AND status = "approved"
                ORDER BY start_date';
        
        $link = $this->getConnection();
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            $leaveData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Tạo mảng để lưu trữ dữ liệu theo ngày
            $calendarData = [];
            foreach ($leaveData as $record) {
                $start = new DateTime($record['start_date']);
                $end = new DateTime($record['end_date']);
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($start, $interval, $end);
    
                foreach ($period as $date) {
                    $dateStr = $date->format('Y-m-d');
                    $calendarData[$dateStr] = [
                        'type' => $record['type'],
                        'reason' => $record['reason']
                    ];
                }
            }
    
            // Hiển thị lịch
            echo '<div class="container mt-5">';
            echo '<h3>' . date('F Y', strtotime("$year-$month-01")) . '</h3>';
            
            
            echo '<table class="calendar table table-bordered">
                    <thead>
                        <tr>
                            <th>CN</th>
                            <th>T2</th>
                            <th>T3</th>
                            <th>T4</th>
                            <th>T5</th>
                            <th>T6</th>
                            <th>T7</th>
                        </tr>
                    </thead>
                    <tbody>';
    
            $firstDay = date('w', strtotime("$year-$month-01"));
            $daysInMonth = date('t', strtotime("$year-$month-01"));
    
            // Tạo các ô trống cho những ngày đầu tháng
            echo '<tr>';
            for ($i = 0; $i < $firstDay; $i++) {
                echo '<td></td>';
            }
    
            $currentDay = 1;
            $currentDayOfWeek = $firstDay;
    
            while ($currentDay <= $daysInMonth) {
                if ($currentDayOfWeek == 7) {
                    echo '</tr><tr>';
                    $currentDayOfWeek = 0;
                }
    
                $currentDate = sprintf("%s-%02d-%02d", $year, $month, $currentDay);
                
                // Xác định class và text hiển thị dựa trên type
                $cellClass = '';
                $typeText = '';
                
                if (isset($calendarData[$currentDate])) {
                    $data = $calendarData[$currentDate];
                    switch ($data['type']) {
                        case 'di_tre':
                            $cellClass = 'di-tre';
                            $typeText = 'Đi trễ';
                            break;
                        case 'nghi_phep':
                            $cellClass = 'nghi-phep';
                            $typeText = 'Nghỉ phép';
                            break;
                        default:
                            $cellClass = $data['type'];
                            $typeText = $data['type'];
                            break;
                    }
                }
    
                echo '<td class="text-center ' . $cellClass . '">';
                echo '<div class="date">' . $currentDay . '</div>';
                if ($typeText) {
                    echo '<div class="leave-info" title="Lý do: ' . htmlspecialchars($data['reason']) . '">' 
                         . $typeText . '</div>';
                }
                echo '</td>';
    
                $currentDay++;
                $currentDayOfWeek++;
            }
    
            // Điền các ô trống cuối tháng
            while ($currentDayOfWeek < 7) {
                echo '<td></td>';
                $currentDayOfWeek++;
            }
    
            echo '</tr></tbody></table></div>';
        } else {
            echo '<div class="alert alert-danger">Không thể kết nối đến cơ sở dữ liệu</div>';
        }
    }
    public function getLeaveStatistics($user_id, $month, $year) {
        $sql = "SELECT 
                    type,
                    COUNT(*) as count,
                    SUM(DATEDIFF(end_date, start_date) + 1) as total_days
                FROM leave_requests
                WHERE user_id = :user_id 
                AND status = 'approved'
                AND (
                    (MONTH(start_date) = :month AND YEAR(start_date) = :year)
                    OR 
                    (MONTH(end_date) = :month AND YEAR(end_date) = :year)
                )
                GROUP BY type";
       
        $link = $this->getConnection();
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            $statistics = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            echo '<div class="leave-statistics">';
            echo '<h4>Thống kê nghỉ phép tháng ' . $month . '/' . $year . '</h4>';
            echo '<table class="table table-bordered">';
            echo '<thead>';
            echo '<tr>';
            echo '<th>Loại nghỉ phép</th>';
            echo '<th>Số lần</th>';
            echo '<th>Tổng số ngày</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';
    
            $totalRequests = 0;
            $totalDays = 0;
    
            foreach ($statistics as $stat) {
                $typeText = $stat['type'] == 'di_tre' ? 'Đi trễ' : 
                          ($stat['type'] == 'nghi_phep' ? 'Nghỉ phép' : $stat['type']);
                
                echo '<tr>';
                echo '<td>' . htmlspecialchars($typeText) . '</td>';
                echo '<td>' . $stat['count'] . '</td>';
                echo '<td>' . $stat['total_days'] . '</td>';
                echo '</tr>';
    
                $totalRequests += $stat['count'];
                $totalDays += $stat['total_days'];
            }
    
            echo '<tr class="total">';
            echo '<td><strong>Tổng cộng</strong></td>';
            echo '<td><strong>' . $totalRequests . '</strong></td>';
            echo '<td><strong>' . $totalDays . '</strong></td>';
            echo '</tr>';
    
            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        }
    }
    public function loadAttendanceData($user_id, $month = null, $year = null) {
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

        $sql = 'SELECT 
                    a.id, a.user_id, a.clock_in_time, a.clock_out_time, a.wifi_address,
                    a.shift_id, s.shift_name, s.start_time, s.end_time, s.work_time, 
                    a.earlyTime, a.lateLeaveTime
                FROM attendance a
                INNER JOIN shifts s ON s.id = a.shift_id
                WHERE a.user_id = :user_id
                  AND a.clock_in_time BETWEEN :start_date AND :end_date
                ORDER BY a.clock_in_time';

        $link = $this->getConnection();
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':start_date', $startDate, PDO::PARAM_STR);
            $stmt->bindParam(':end_date', $endDate, PDO::PARAM_STR);
            $stmt->execute();
            $attendanceData = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($attendanceData) > 0) {
                $calendarData = [];
                foreach ($attendanceData as $record) {
                    $date = date('Y-m-d', strtotime($record['clock_in_time']));
                    $calendarData[$date] = [
                        'clock_in' => date('H:i', strtotime($record['clock_in_time'])),
                        'clock_out' => !empty($record['clock_out_time']) ? date('H:i', strtotime($record['clock_out_time'])) : '',
                        'work_time' => $record['work_time'],
                        'lateLeaveTime' => $record['lateLeaveTime'], // Thời gian đi trễ
                        'earlyTime' => $record['earlyTime'] // Thời gian về sớm
                    ];
                }

                // Dữ liệu nghỉ phép
                $leaveData = $this->loadLeaveData($user_id, $month, $year);

                // Xử lý hiển thị lịch
                $firstDay = date('w', strtotime("$year-$month-04"));
                $daysInMonth = date('t', strtotime("$year-$month-01")); // Days in the current month

                echo '<div class="col-lg-12">
                        <table class="calendar table table-bordered">
                            <thead>
                                <tr>
                                    <th>CN</th>
                                    <th>T2</th>
                                    <th>T3</th>
                                    <th>T4</th>
                                    <th>T5</th>
                                    <th>T6</th>
                                    <th>T7</th>
                                </tr>
                            </thead>
                            <tbody>';

                echo '<tr style="height: 50px;">';
                for ($i = 0; $i < $firstDay; $i++) {
                    echo '<td style="height: 50px;"></td>';
                }

                $currentDay = 4; // Start from 4th day
                $currentDayOfWeek = $firstDay;

                // Loop through each day from 4th of this month to 3rd of next month
                while ($currentDay <= $daysInMonth || ($currentDay <= 3 && $month != $nextMonth)) {
                    if ($currentDayOfWeek == 7) {
                        echo '</tr><tr style="height: 100px;">';
                        $currentDayOfWeek = 0;
                    }

                    $currentDate = sprintf("%s-%02d-%02d", $currentDay <= $daysInMonth ? $year : $nextYear, $currentDay <= $daysInMonth ? $month : $nextMonth, $currentDay <= $daysInMonth ? $currentDay : $currentDay - $daysInMonth);
                    echo '<td style="height: 100px;" class="text-center ';

                    // Kiểm tra trạng thái màu sắc
                    $dayOfWeek = date('w', strtotime($currentDate));
                    $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);

                    if (isset($calendarData[$currentDate])) {
                        $data = $calendarData[$currentDate];
                        $work_time = $data['work_time'];
                        if ($work_time == 0.5) {
                            echo 'bg-vang';
                        } elseif ($work_time == 1) {
                            echo 'bg-xanhla';
                        } elseif ($work_time === '') {
                            if (!$isWeekend && !isset($leaveData[$currentDate])) {
                                echo '';
                            }
                        }
                    } else {
                        if (isset($leaveData[$currentDate])) {
                            echo 'bg-xanhduong';
                        } else {
                            if (!$isWeekend) {
                                echo 'bg-do';
                            }
                        }
                    }

                    echo '">';

                    echo '<div class="date">' . ($currentDay <= $daysInMonth ? $currentDay : $currentDay - $daysInMonth) . '</div>';

                    if (isset($calendarData[$currentDate])) {
                        $data = $calendarData[$currentDate];
                        echo '<div class="attendance-info">';

                        // Hiển thị thời gian đi trễ
                        if (!is_null($data['lateLeaveTime']) && $data['lateLeaveTime'] > 0) {
                            echo '<div class="delay-info">Trễ: ' . $data['lateLeaveTime'] . ' phút</div>';
                        }

                        // Hiển thị thời gian về sớm
                        if (!is_null($data['earlyTime']) && $data['earlyTime'] > 0) {
                            echo '<div class="early-info">Về sớm: ' . $data['earlyTime'] . ' phút</div>';
                        }

                        echo '</div>';
                    }

                    if (isset($leaveData[$currentDate])) {
                        echo '<div class="leave-info" title="Lý do: ' . htmlspecialchars($leaveData[$currentDate]['reason']) . '">Nghỉ phép</div>';
                    }

                    echo '</td>';
                    $currentDay++;
                    $currentDayOfWeek++;
                }

                while ($currentDayOfWeek < 7) {
                    echo '<td style="height: 50px;"></td>';
                    $currentDayOfWeek++;
                }

                echo '</tr></tbody></table></div>';
            } else {
                echo '<div class="alert alert-info">Không có dữ liệu chấm công trong tháng này</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Không thể kết nối đến cơ sở dữ liệu</div>';
        }
    }
    // Update loadLeaveData to return data instead of echoing
    public function loadLeaveData($user_id, $month = null, $year = null) {
        if ($month === null) $month = date('m');
        if ($year === null) $year = date('Y');
    
        $sql = 'SELECT * FROM leave_requests 
                WHERE user_id = :user_id 
                AND (
                    (MONTH(start_date) = :month AND YEAR(start_date) = :year)
                    OR 
                    (MONTH(end_date) = :month AND YEAR(end_date) = :year)
                )
                AND status = "approved"
                ORDER BY start_date';
        
        $link = $this->getConnection();
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            $leaveData = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Create an array to store leave data by date
            $calendarData = [];
            foreach ($leaveData as $record) {
                $start = new DateTime($record['start_date']);
                $end = new DateTime($record['end_date']);
                $interval = DateInterval::createFromDateString('1 day');
                $period = new DatePeriod($start, $interval, $end);
    
                foreach ($period as $date) {
                    $dateStr = $date->format('Y-m-d');
                    $calendarData[$dateStr] = [
                        'type' => $record['type'],
                        'reason' => $record['reason']
                    ];
                }
            }
    
            return $calendarData; // Return leave data instead of echoing
        } else {
            echo '<div class="alert alert-danger">Không thể kết nối đến cơ sở dữ liệu</div>';
            return [];
        }
    }
    
   // ... existing code ...
public function getAttendanceStatustest($user_id,$time) {
        $sql = "SELECT clock_in_time, clock_out_time 
                FROM attendance 
                WHERE user_id = :user_id 
                AND DATE(clock_in_time) = :time
                ORDER BY clock_in_time DESC 
                LIMIT 1";
        
        $link = $this->getConnection();
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':time', $time, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return null;
    }
//okee
public function clockInTest($user_id, $wifi_address, $check_in_time = null) {
    // Kiểm tra địa chỉ IP Wi-Fi
    $valid_ip = $this->check_ip_address($wifi_address); // Hàm kiểm tra địa chỉ IP Wi-Fi

    if ($valid_ip) { // Chỉ tiếp tục nếu địa chỉ IP Wi-Fi hợp lệ
        // Nếu không nhập thời gian check_in, sử dụng thời gian hiện tại
        $clock_in_time = $check_in_time ? $check_in_time : date("Y-m-d H:i:s");

        // Gọi hàm check_in để xác định `check_in` và `shift_id`
        $check_in_result = $this->check_in($clock_in_time, $user_id);
        $check_in_status = $check_in_result['check_in'];
        $lateLeaveTime = $check_in_result['thoi_gian_tre']; // Lấy thời gian trễ

        // Thực hiện câu lệnh INSERT
        $sql = '
            INSERT INTO attendance (user_id, clock_in_time, wifi_address, check_in, lateLeaveTime) 
            VALUES (:user_id, :clock_in_time, :wifi_address, :check_in, :lateLeaveTime)
        ';
        
        $link = $this->getConnection();
        if ($link) {
            $stmt = $link->prepare($sql);

            // Bind parameters
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindParam(':clock_in_time', $clock_in_time);
            $stmt->bindParam(':wifi_address', $wifi_address);
            $stmt->bindParam(':check_in', $check_in_status, PDO::PARAM_INT);
            $stmt->bindParam(':lateLeaveTime', $lateLeaveTime, PDO::PARAM_INT); // Bind lateLeaveTime

            // Execute the query
            if ($stmt->execute()) {
                return [
                    'success' => true,
                    'message' => 'Clock-in successful',
                    'check_in_status' => $check_in_status
                ];
            } else {
                throw new Exception("Clock-in failed. Please try again.");
            }
        } else {
            throw new Exception("Could not connect to the database.");
        }
    } else {
        throw new Exception("Unauthorized Wi-Fi IP address. Clock-in not allowed.");
    }
}//okee

public function clockOutTest($attendance_id, $wifi_address, $check_out_time = null) {
    // Kiểm tra địa chỉ IP Wi-Fi
    $valid_ip = $this->check_ip_address($wifi_address); // Hàm kiểm tra địa chỉ IP Wi-Fi

    if ($valid_ip) {
        $link = $this->getConnection();

        if ($link) {
            // Lấy thông tin clock_in_time và check_in từ attendance
            $sql = 'SELECT clock_in_time, check_in FROM attendance WHERE id = :id';
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':id', $attendance_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result) {
                // Nếu không nhập thời gian check_out, sử dụng thời gian hiện tại
                $clock_out_time = $check_out_time ? $check_out_time : date("Y-m-d H:i:s");

                // Gọi hàm check_out để tính toán shift_id, work_time và thoi_gian_ve_som
                $check_in = $result['check_in'];
                $check_out_result = $this->check_out($clock_out_time, $check_in, $attendance_id);

                // Kiểm tra kết quả trả về từ hàm check_out
                if ($check_out_result) {
                    // Cập nhật thông tin clock_out_time, wifi_address_out, shift_id và earlyTime vào bảng attendance
                    $update_sql = '
                        UPDATE attendance 
                        SET clock_out_time = :clock_out_time, wifi_address_out = :wifi_address_out, shift_id = :shift_id, earlyTime = :earlyTime
                        WHERE id = :id
                    ';
                    $stmt_update = $link->prepare($update_sql);
                    $stmt_update->bindParam(':clock_out_time', $clock_out_time);
                    $stmt_update->bindParam(':wifi_address_out', $wifi_address); // Assuming you want to store the same wifi address
                    $stmt_update->bindParam(':shift_id', $check_out_result['shift_id']);
                    $stmt_update->bindParam(':id', $attendance_id, PDO::PARAM_INT);
                    $stmt_update->bindParam(':earlyTime', $check_out_result['thoi_gian_ve_som']); // Bind earlyTime

                    if ($stmt_update->execute()) {
                        return [
                            'success' => true,
                            'message' => 'Clock-out successful',
                            'shift_id' => $check_out_result['shift_id']
                        ];
                    } else {
                        throw new Exception("Clock-out failed. Please try again.");
                    }
                } else {
                    throw new Exception("Error calculating clock-out details.");
                }
            } else {
                throw new Exception("No matching attendance record found for clock-out.");
            }
        } else {
            throw new Exception("Could not connect to the database.");
        }
    } else {
        throw new Exception("Unauthorized Wi-Fi IP address. Clock-out not allowed.");
    }
}

// ... existing code ...
    
    // Helper methods to set clock in and clock out times
    private function setClockInTime($time) {
        // Logic to set the clock in time for testing
        // This could involve manipulating the database or the class state
    }
    
    private function setClockOutTime($time) {
        // Logic to set the clock out time for testing
        // This could involve manipulating the database or the class state
    }





}


?>