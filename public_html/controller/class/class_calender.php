<?php
include '../../db/connetion.php';
class class_calender extends Database{
public function loadAttendanceData1($user_id, $month = null, $year = null) {
    // Set default to current month and year only if not provided in the function call
    if ($month === null) $month = date('m');
    if ($year === null) $year = date('Y');

    // Tính toán ngày bắt đầu và kết thúc
    $start_date = "$year-$month-04";
    $nextMonth = $month == 12 ? 1 : $month + 1;
    $nextYear = $month == 12 ? $year + 1 : $year;
    $end_date = "$nextYear-$nextMonth-03";

    // Truy vấn dữ liệu chấm công
    $sql = 'SELECT a.id, a.user_id, a.clock_in_time, a.clock_out_time, a.wifi_address, a.shift_id, s.shift_name,
                   s.start_time, s.end_time, s.work_time
            FROM attendance a
            INNER JOIN shifts s ON s.id = a.shift_id
            WHERE a.user_id = :user_id
            AND a.clock_in_time BETWEEN :start_date AND :end_date
            ORDER BY a.clock_in_time';

    $link = $this->getConnection();
    if ($link) {
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
        $stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
        $stmt->execute();
        $attendanceData = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (count($attendanceData) > 0) {
            // Sắp xếp dữ liệu theo ngày
            $calendarData = [];
            foreach ($attendanceData as $record) {
                $date = date('Y-m-d', strtotime($record['clock_in_time']));
                $calendarData[$date] = [
                    'clock_in' => date('H:i', strtotime($record['clock_in_time'])),
                    'clock_out' => !empty($record['clock_out_time']) ? date('H:i', strtotime($record['clock_out_time'])) : '',
                    'work_time' => $record['work_time']
                ];
            }

            // Hiển thị lịch
            $currentDay = 4;
            $currentMonth = $month;
            $currentYear = $year;
            $daysInMonth = date('t', strtotime("$currentYear-$currentMonth-01"));
            $currentDayOfWeek = date('w', strtotime("$currentYear-$currentMonth-04"));

            echo '<div class="container mt-5">
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

            // Tạo các ô trống trước ngày 4
            echo '<tr>';
            for ($i = 0; $i < $currentDayOfWeek; $i++) {
                echo '<td></td>';
            }

            // Hiển thị các ngày từ 4 của tháng hiện tại đến 3 của tháng kế tiếp
            while (!($currentDay == 4 && $currentMonth == $nextMonth && $currentYear == $nextYear)) {
                if ($currentDayOfWeek == 7) {
                    echo '</tr><tr>';
                    $currentDayOfWeek = 0;
                }

                $currentDate = sprintf("%s-%02d-%02d", $currentYear, $currentMonth, $currentDay);

                echo '<td class="text-center">';
                echo '<div class="date">' . $currentDay . '</div>';

                if (isset($calendarData[$currentDate])) {
                    $data = $calendarData[$currentDate];
                    echo '<div class="attendance-info">';
                    echo '<div class="cong">' . $data['work_time'] . '</div>';
                    echo '<div class="time-in"><i class="fa fa-fw fa-sign-in"></i><p>' . $data['clock_in'] . '</p></div>';
                    if (!empty($data['clock_out'])) {
                        echo '<div class="time-out"><i class="fa fa-fw fa-sign-out"></i><p>' . $data['clock_out'] . '</p></div>';
                    }
                    echo '</div>';
                }

                echo '</td>';

                $currentDay++;
                $currentDayOfWeek++;

                // Nếu vượt qua số ngày trong tháng, chuyển sang tháng kế tiếp
                if ($currentDay > $daysInMonth) {
                    $currentDay = 1;
                    $currentMonth = $nextMonth;
                    $currentYear = $nextYear;
                    $daysInMonth = date('t', strtotime("$currentYear-$currentMonth-01"));
                }
            }

            // Hoàn thành hàng cuối bằng các ô trống
            while ($currentDayOfWeek < 7) {
                echo '<td></td>';
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

            // Calculate standard working days using the helper function
            $ngaycongtc = $this->getWeekdaysCountFromRange($month, $year);   

            // Output HTML for displaying the total work days
            echo '<div class="container mt-5">';
            echo '<h3>' . date('F Y', strtotime("$year-$month-01")) . '</h3>';
            echo '<p>Tổng ngày công: <strong>' . $daysWorked . ' ngày công</strong></p>';
            echo '<p>Ngày công tiêu chuẩn: <strong>' . $ngaycongtc . ' ngày công</strong></p>';
            echo '</div>';
        } else {
            echo '<div class="alert alert-info">Không có dữ liệu chấm công trong khoảng thời gian này.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">Không thể kết nối đến cơ sở dữ liệu.</div>';
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
    
}
?>