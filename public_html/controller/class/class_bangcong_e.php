<?php
include '../../db/connetion.php';


class bangcong extends Database{

// public function loadAttendanceData($user_id, $month = null, $year = null) {
//         // Set default to current month and year if not provided
//         if ($month === null) $month = date('m');
//         if ($year === null) $year = date('Y');

//         // Calculate the start and end dates
//         $startDate = date('Y-m-d', strtotime("$year-$month-04"));

//         // Handle the month/year rollover for the end date
//         $nextMonth = $month + 1;
//         $nextYear = $year;
//         if ($nextMonth > 12) {
//             $nextMonth = 1;
//             $nextYear += 1;
//         }
//         $endDate = date('Y-m-d', strtotime("$nextYear-$nextMonth-03"));

//         $sql = 'SELECT 
//                     a.id, a.user_id, a.clock_in_time, a.clock_out_time, a.wifi_address,
//                     a.shift_id, s.shift_name, s.start_time, s.end_time, s.work_time, 
//                     a.earlyTime, a.lateLeaveTime
//                 FROM attendance a
//                 INNER JOIN shifts s ON s.id = a.shift_id
//                 WHERE a.user_id = :user_id
//                   AND a.clock_in_time BETWEEN :start_date AND :end_date
//                 ORDER BY a.clock_in_time';

//         $link = $this->getConnection();
//         if ($link) {
//             $stmt = $link->prepare($sql);
//             $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
//             $stmt->bindParam(':start_date', $startDate, PDO::PARAM_STR);
//             $stmt->bindParam(':end_date', $endDate, PDO::PARAM_STR);
//             $stmt->execute();
//             $attendanceData = $stmt->fetchAll(PDO::FETCH_ASSOC);

//             if (count($attendanceData) > 0) {
//                 $calendarData = [];
//                 foreach ($attendanceData as $record) {
//                     $date = date('Y-m-d', strtotime($record['clock_in_time']));
//                     $calendarData[$date] = [
//                         'clock_in' => date('H:i', strtotime($record['clock_in_time'])),
//                         'clock_out' => !empty($record['clock_out_time']) ? date('H:i', strtotime($record['clock_out_time'])) : '',
//                         'work_time' => $record['work_time'],
//                         'lateLeaveTime' => $record['lateLeaveTime'], // Thời gian đi trễ
//                         'earlyTime' => $record['earlyTime'] // Thời gian về sớm
//                     ];
//                 }

//                 // Dữ liệu nghỉ phép
//                 $leaveData = $this->loadLeaveData($user_id, $month, $year);

//                 // Xử lý hiển thị lịch
//                 $firstDay = date('w', strtotime("$year-$month-04"));
//                 $daysInMonth = date('t', strtotime("$year-$month-01")); // Days in the current month

//                 echo '<div class="col-lg-12">
//                         <table class="calendar table table-bordered">
//                             <thead>
//                                 <tr>
//                                     <th>CN</th>
//                                     <th>T2</th>
//                                     <th>T3</th>
//                                     <th>T4</th>
//                                     <th>T5</th>
//                                     <th>T6</th>
//                                     <th>T7</th>
//                                 </tr>
//                             </thead>
//                             <tbody>';

//                 echo '<tr style="height: 50px;">';
//                 for ($i = 0; $i < $firstDay; $i++) {
//                     echo '<td style="height: 50px;"></td>';
//                 }

//                 $currentDay = 4; // Start from 4th day
//                 $currentDayOfWeek = $firstDay;

//                 // Loop through each day from 4th of this month to 3rd of next month
//                 while ($currentDay <= $daysInMonth || ($currentDay <= 3 && $month != $nextMonth)) {
//                     if ($currentDayOfWeek == 7) {
//                         echo '</tr><tr style="height: 100px;">';
//                         $currentDayOfWeek = 0;
//                     }

//                     $currentDate = sprintf("%s-%02d-%02d", $currentDay <= $daysInMonth ? $year : $nextYear, $currentDay <= $daysInMonth ? $month : $nextMonth, $currentDay <= $daysInMonth ? $currentDay : $currentDay - $daysInMonth);
//                     echo '<td style="height: 100px;" class="text-center ';

//                     // Kiểm tra trạng thái màu sắc
//                     $dayOfWeek = date('w', strtotime($currentDate));
//                     $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);

//                     if (isset($calendarData[$currentDate])) {
//                         $data = $calendarData[$currentDate];
//                         $work_time = $data['work_time'];
//                         if ($work_time == 0.5) {
//                             echo 'bg-vang';
//                         } elseif ($work_time == 1) {
//                             echo 'bg-xanhla';
//                         } elseif ($work_time === '') {
//                             if (!$isWeekend && !isset($leaveData[$currentDate])) {
//                                 echo '';
//                             }
//                         }
//                     } else {
//                         if (isset($leaveData[$currentDate])) {
//                             echo 'bg-xanhduong';
//                         } else {
//                             if (!$isWeekend) {
//                                 echo 'bg-do';
//                             }
//                         }
//                     }

//                     echo '">';

//                     echo '<div class="date">' . ($currentDay <= $daysInMonth ? $currentDay : $currentDay - $daysInMonth) . '</div>';

//                     if (isset($calendarData[$currentDate])) {
//                         $data = $calendarData[$currentDate];
//                         echo '<div class="attendance-info">';

//                         // Hiển thị thời gian đi trễ
//                         if (!is_null($data['lateLeaveTime']) && $data['lateLeaveTime'] > 0) {
//                             echo '<div class="delay-info">Trễ: ' . $data['lateLeaveTime'] . ' phút</div>';
//                         }

//                         // Hiển thị thời gian về sớm
//                         if (!is_null($data['earlyTime']) && $data['earlyTime'] > 0) {
//                             echo '<div class="early-info">Về sớm: ' . $data['earlyTime'] . ' phút</div>';
//                         }

//                         echo '</div>';
//                     }

//                     if (isset($leaveData[$currentDate])) {
//                         echo '<div class="leave-info" title="Lý do: ' . htmlspecialchars($leaveData[$currentDate]['reason']) . '">Nghỉ phép</div>';
//                     }

//                     echo '</td>';
//                     $currentDay++;
//                     $currentDayOfWeek++;
//                 }

//                 while ($currentDayOfWeek < 7) {
//                     echo '<td style="height: 50px;"></td>';
//                     $currentDayOfWeek++;
//                 }

//                 echo '</tr></tbody></table></div>';
//             } else {
//                 echo '<div class="alert alert-info">Không có dữ liệu chấm công trong tháng này</div>';
//             }
//         } else {
//             echo '<div class="alert alert-danger">Không thể kết nối đến cơ sở dữ liệu</div>';
//         }
//     }
    public function loadAttendanceData($user_id, $month = null, $year = null) {
    // Set default to current month and year if not provided
    if ($month === null) $month = date('m');
    if ($year === null) $year = date('Y');

    // Calculate the start and end dates
    $startDate = date('Y-m-d', strtotime("$year-$month-04"));
    $nextMonth = $month + 1;
    $nextYear = $year;

    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear += 1;
    }

    $endDate = date('Y-m-d', strtotime("$nextYear-$nextMonth-03"));

    // SQL query to fetch attendance data
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
                    'lateLeaveTime' => $record['lateLeaveTime'],
                    'earlyTime' => $record['earlyTime']
                ];
            }

            $leaveData = $this->loadLeaveData($user_id, $month, $year);

            $daysInCurrentMonth = date('t', strtotime("$year-$month-01"));
            $daysInNextMonth = date('t', strtotime("$nextYear-$nextMonth-01"));

            $startDay = 4; // Start from 4th day of this month
            $endDay = $daysInNextMonth + 2;

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

            $currentDayOfWeek = date('w', strtotime("$year-$month-04"));
            echo '<tr style="height: 50px;">';
            for ($i = 0; $i < $currentDayOfWeek; $i++) {
                echo '<td style="height: 50px;"></td>';
            }

            for ($currentDay = $startDay; $currentDay <= $endDay; $currentDay++) {
                $currentDate = ($currentDay <= $daysInCurrentMonth)
                    ? sprintf("%s-%02d-%02d", $year, $month, $currentDay)
                    : sprintf("%s-%02d-%02d", $nextYear, $nextMonth, $currentDay - $daysInCurrentMonth);

                if ($currentDayOfWeek == 7) {
                    echo '</tr><tr style="height: 100px;">';
                    $currentDayOfWeek = 0;
                }

                echo '<td class="text-center ';

                $dayOfWeek = date('w', strtotime($currentDate));
                $isWeekend = ($dayOfWeek == 0 || $dayOfWeek == 6);

                if (isset($calendarData[$currentDate])) {
                    $work_time = $calendarData[$currentDate]['work_time'];
                    echo $work_time == 1 ? 'bg-xanhla' : ($work_time == 0.5 ? 'bg-vang' : 'bg-do');
                } else {
                    echo $isWeekend ? '' : 'bg-do';
                }

                echo '">';
                echo '<div class="date">' . date('d', strtotime($currentDate)) . '</div>';

                if (isset($calendarData[$currentDate])) {
                    $data = $calendarData[$currentDate];
                    if ($data['lateLeaveTime'] > 0) echo '<div>Trễ: ' . $data['lateLeaveTime'] . '</div>';
                    if ($data['earlyTime'] > 0) echo '<div>Sớm: ' . $data['earlyTime'] . '</div>';
                }

                echo '</td>';
                $currentDayOfWeek++;
            }

            while ($currentDayOfWeek < 7) {
                echo '<td></td>';
                $currentDayOfWeek++;
            }

            echo '</tr></tbody></table></div>';
        } else {
            echo '<div class="alert alert-info">Không có dữ liệu chấm công trong tháng này.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Không thể kết nối cơ sở dữ liệu.</div>';
    }
}

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
// public function loadLeaveData($user_id, $month = null, $year = null) {
//     if ($month === null) $month = date('m');
//     if ($year === null) $year = date('Y');

//     // Tính khoảng thời gian từ 4 tháng trước đến 3 tháng sau
//     $startDate = new DateTime("$year-$month-01");
//     $startDate->modify('-4 months'); // 4 tháng trước
//     $endDate = new DateTime("$year-$month-01");
//     $endDate->modify('+3 months'); // 3 tháng sau
//     $endDate->modify('last day of this month'); // Đặt ngày cuối của tháng cuối cùng

//     $sql = 'SELECT * FROM leave_requests 
//             WHERE user_id = :user_id 
//             AND (
//                 (start_date BETWEEN :start_date AND :end_date)
//                 OR 
//                 (end_date BETWEEN :start_date AND :end_date)
//             )
//             AND status = "approved"
//             ORDER BY start_date';

//     $link = $this->getConnection();
//     if ($link) {
//         $stmt = $link->prepare($sql);
//         $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
//         $stmt->bindValue(':start_date', $startDate->format('Y-m-d'), PDO::PARAM_STR);
//         $stmt->bindValue(':end_date', $endDate->format('Y-m-d'), PDO::PARAM_STR);
//         $stmt->execute();
//         $leaveData = $stmt->fetchAll(PDO::FETCH_ASSOC);

//         // Tạo mảng để lưu dữ liệu lịch nghỉ
//         $calendarData = [];
//         foreach ($leaveData as $record) {
//             $start = new DateTime($record['start_date']);
//             $end = new DateTime($record['end_date']);
//             $interval = DateInterval::createFromDateString('1 day');
//             $period = new DatePeriod($start, $interval, $end->modify('+1 day'));

//             foreach ($period as $date) {
//                 $dateStr = $date->format('Y-m-d');
//                 $calendarData[$dateStr] = [
//                     'type' => $record['type'],
//                     'reason' => $record['reason']
//                 ];
//             }
//         }

//         // Hiển thị lịch
//         echo '<div class="container mt-5">';
//         echo '<h3>Lịch nghỉ phép từ ' . $startDate->format('F Y') . ' đến ' . $endDate->format('F Y') . '</h3>';

//         // Hiển thị chú thích
//         echo '<div class="leave-type-legend">
//                 <span class="legend-item"><span class="color-box di-tre"></span> Đi trễ</span>
//                 <span class="legend-item"><span class="color-box nghi-phep"></span> Nghỉ phép</span>
//               </div>';

//         // Hiển thị từng tháng
//         $current = clone $startDate;
//         while ($current <= $endDate) {
//             $monthYear = $current->format('F Y');
//             $daysInMonth = $current->format('t');
//             $firstDayOfWeek = $current->format('w');

//             echo '<h4>' . $monthYear . '</h4>';
//             echo '<table class="calendar table table-bordered">
//                     <thead>
//                         <tr>
//                             <th>CN</th>
//                             <th>T2</th>
//                             <th>T3</th>
//                             <th>T4</th>
//                             <th>T5</th>
//                             <th>T6</th>
//                             <th>T7</th>
//                         </tr>
//                     </thead>
//                     <tbody>';

//             // Tạo ô trống trước ngày đầu tiên
//             echo '<tr>';
//             for ($i = 0; $i < $firstDayOfWeek; $i++) {
//                 echo '<td></td>';
//             }

//             $currentDay = 1;
//             $currentDayOfWeek = $firstDayOfWeek;

//             while ($currentDay <= $daysInMonth) {
//                 if ($currentDayOfWeek == 7) {
//                     echo '</tr><tr>';
//                     $currentDayOfWeek = 0;
//                 }

//                 $currentDate = sprintf("%s-%02d-%02d", $current->format('Y'), $current->format('m'), $currentDay);

//                 // Xác định class và text hiển thị
//                 $cellClass = '';
//                 $typeText = '';
//                 $reason = '';

//                 if (isset($calendarData[$currentDate])) {
//                     $data = $calendarData[$currentDate];
//                     $reason = htmlspecialchars($data['reason']);

//                     switch ($data['type']) {
//                         case 'di_tre':
//                             $cellClass = 'di-tre';
//                             $typeText = 'Đi trễ';
//                             break;
//                         case 'nghi_phep':
//                             $cellClass = 'nghi-phep';
//                             $typeText = 'Nghỉ phép';
//                             break;
//                         default:
//                             $cellClass = 'unknown-type';
//                             $typeText = 'Không rõ';
//                             break;
//                     }
//                 }

//                 echo '<td class="text-center ' . $cellClass . '">';
//                 echo '<div class="date">' . $currentDay . '</div>';
//                 if ($typeText) {
//                     echo '<div class="leave-info" title="Lý do: ' . $reason . '">' . $typeText . '</div>';
//                 }
//                 echo '</td>';

//                 $currentDay++;
//                 $currentDayOfWeek++;
//             }

//             // Điền các ô trống cuối tháng
//             while ($currentDayOfWeek < 7) {
//                 echo '<td></td>';
//                 $currentDayOfWeek++;
//             }

//             echo '</tr></tbody></table>';

//             // Chuyển sang tháng kế tiếp
//             $current->modify('first day of next month');
//         }

//         echo '</div>';
//     } else {
//         echo '<div class="alert alert-danger">Không thể kết nối đến cơ sở dữ liệu</div>';
//     }
// }

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

}
?>