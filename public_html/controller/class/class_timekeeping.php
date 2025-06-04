<?php
include '../../db/connetion.php';
require '../../assets/vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class timekeeping extends Database{
public function getDepartments() {
        $link = $this->getConnection();
        $stmt = $link->prepare("SELECT * FROM departments");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
public function getSum_user($department_id = null) {
    // Nếu không có department_id, lấy tất cả người dùng
    $sql = 'SELECT * FROM users' . ($department_id ? ' WHERE department_id = :department_id' : '') . ' ORDER BY id ASC';
    $link = $this->getConnection();
    
    // Check if the connection is successful
    if ($link) {
        // Prepare the SQL statement
        $stmt = $link->prepare($sql);

        // Bind the department_id parameter only if it is provided
        if ($department_id) {
            $stmt->bindValue(':department_id', $department_id, PDO::PARAM_INT);
        }

        // Execute the statement
        $stmt->execute();

        // Fetch all results
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $userCount = count($result); // Get the total number of users
        
        if ($userCount > 0) {
            return $userCount; // Return the number of users in the department or total users
        } else {
            echo 'No data found';
        }

    } else {
        echo 'Unable to connect to the database';
    }
}

public function getEvaluationIdByDepKpiId($dep_kpi_id) {
    // SQL query to get the id based on dep_kpi_id
    $sql = 'SELECT id FROM director_evaluation WHERE dep_kpi_id = :dep_kpi_id';

    // Get the database connection
    $link = $this->getConnection();

    if ($link) {
        // Prepare the statement
        $stmt = $link->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(':dep_kpi_id', $dep_kpi_id, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return 0 if no id is found, otherwise return the id
        return $result ? $result['id'] : 0;
    } else {
        // If no connection, return 0
        return 0;
    }
}

public function cap_nhat_ca_lam_viec($id, $shift_id) {
    $dbh = $this->getConnection();
    $stmt = $dbh->prepare("UPDATE attendance SET shift_id = :shift_id WHERE id = :id");
    $stmt->bindParam(':shift_id', $shift_id);  // Cập nhật shift_id
    $stmt->bindParam(':id', $id);  // Cập nhật theo id của bản ghi attendance
    return $stmt->execute() ? 1 : 0;
}

function getWeekdaysCountFromRange($monthYear = null) {
    // Kiểm tra nếu người dùng không nhập, mặc định lấy tháng và năm hiện tại
    if (!$monthYear) {
        $month = date('m'); // Tháng hiện tại
        $year = date('Y'); // Năm hiện tại
    } else {
        // Tách tháng và năm từ chuỗi POST (định dạng: YYYY-MM)
        [$year, $month] = explode('-', $monthYear);
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

public function loadTimekeeping() {
    $sql = "SELECT u.id AS user_id, u.full_name, u.manv, u.department_id, a.clock_in_time, a.clock_out_time, 
                   a.wifi_address, a.wifi_address_out, s.shift_name, s.work_time
            FROM attendance a
            INNER JOIN users u ON u.id = a.user_id
            INNER JOIN shifts s ON s.id = a.shift_id
            WHERE DATE(a.clock_in_time) BETWEEN :start_date AND :end_date";

    // Get the current month and year
    $monthYear = date('Y-m');
    list($year, $month) = explode('-', $monthYear);

    // Set date range for the current month
    $start_date = "$year-$month-04";
    $nextMonth = $month == 12 ? 1 : $month + 1;
    $nextYear = $month == 12 ? $year + 1 : $year;
    $end_date = "$nextYear-$nextMonth-03";

    try {
        $link = $this->getConnection();

        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':start_date', $start_date);
            $stmt->bindParam(':end_date', $end_date);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Process and display the results
            if ($result && count($result) > 0) {
                // Initialize table
                echo '<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Employee Name</th>
                                <th>Employee Code</th>
                                <th>Working Days</th>
                                <th>Details</th>';

                // Display days from the 4th of this month to the 3rd of the next month
                $current_date = strtotime($start_date);
                while ($current_date < strtotime($end_date)) {
                    echo '<th>' . date('D d', $current_date) . '</th>';
                    $current_date = strtotime('+1 day', $current_date);
                }

                echo '</tr>
                        </thead>
                        <tbody>';

                // Process data
                $attendance = [];
                foreach ($result as $row) {
                    $day = date('Y-m-d', strtotime($row['clock_in_time']));
                    if (!isset($attendance[$row['user_id']])) {
                        $attendance[$row['user_id']] = [
                            'name' => $row['full_name'],
                            'code' => $row['manv'],
                            'days' => [],
                            'worktime' => 0
                        ];
                    }
                    // Accumulate worktime and store information by day
                    $attendance[$row['user_id']]['worktime'] += $row['work_time'];
                    $attendance[$row['user_id']]['days'][$day] = $row;
                }

                // Iterate over employees
                foreach ($attendance as $user_id => $data) {
                    $working_days = count(array_filter($data['days'], function ($day) {
                        $day_of_week = date('N', strtotime($day['clock_in_time']));
                        return $day_of_week < 6; // Exclude Saturday and Sunday
                    }));

                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($data['name']) . '</td>';
                    echo '<td>' . htmlspecialchars($data['code']) . '</td>';
                    echo '<td>' . $working_days . '</td>';
                    echo '<td class="worktime">' . $data['worktime'] . ' công </td>';

                    // Display each day
                    $current_date = strtotime($start_date);
                    while ($current_date < strtotime($end_date)) {
                        $current_day = date('Y-m-d', $current_date);
                        if (isset($data['days'][$current_day])) {
                            $day_data = $data['days'][$current_day];
                            echo '<td class="has-data" data-toggle="modal" data-target="#attendanceModal"
                                       data-user-id="' . htmlspecialchars($user_id) . '"
                                       data-clock-in="' . htmlspecialchars($day_data['clock_in_time']) . '"
                                       data-clock-out="' . htmlspecialchars($day_data['clock_out_time']) . '"
                                       data-wifi-in="' . htmlspecialchars($day_data['wifi_address']) . '"
                                       data-wifi-out="' . htmlspecialchars($day_data['wifi_address_out']) . '"
                                       data-work-time="' . htmlspecialchars($day_data['work_time']) . '">
                                       ' . htmlspecialchars($day_data['work_time']) . '
                                  </td>';
                        } else {
                            echo '<td></td>';
                        }
                        $current_date = strtotime('+1 day', $current_date);
                    }

                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="35" class="text-center">No data available</td></tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<div class="alert alert-danger">Could not connect to the database</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}

public function loadTimekeepingByFilters1($department_id = null, $monthYear = null) {
    $sql = "SELECT u.id AS user_id, u.full_name, u.manv, u.department_id, a.clock_in_time, a.clock_out_time, 
                   a.wifi_address, a.wifi_address_out, s.shift_name, s.work_time
            FROM attendance a
            INNER JOIN users u ON u.id = a.user_id
            INNER JOIN shifts s ON s.id = a.shift_id
            WHERE 1=1"; // Base condition

    $params = [];

    // Nếu không có tháng, lấy tháng hiện tại
    if (!$monthYear) {
        $monthYear = date('Y-m');
    }

    // Tách tháng và năm
    list($year, $month) = explode('-', $monthYear);

    // Điều kiện lọc theo phòng ban
    if ($department_id) {
        $sql .= ' AND u.department_id = :department_id';
        $params[':department_id'] = $department_id;
    }

    // Điều kiện lọc theo tháng trước và tháng hiện tại
    $previousMonth = $month == 1 ? 12 : $month - 1;
    $previousYear = $month == 1 ? $year - 1 : $year;

    $start_date = "$previousYear-$previousMonth-16";
    $end_date = "$year-$month-15";

    $sql .= ' AND a.clock_in_time BETWEEN :start_date AND :end_date';
    $params[':start_date'] = $start_date;
    $params[':end_date'] = $end_date;

    try {
        $link = $this->getConnection();

        if ($link) {
            $stmt = $link->prepare($sql);

            // Bind parameters
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Tạo bảng
            echo '<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Họ tên</th>
                            <th>Mã nhân viên</th>
                            <th>Ngày làm việc</th>
                            <th>Ngày công thực tế</th>';

            // Hiển thị các ngày từ 16 tháng trước đến 15 tháng hiện tại
            $current_date = strtotime($start_date);
            $end_date = strtotime($end_date);

            while ($current_date <= $end_date) {
                echo '<th>' . date('D d', $current_date) . '</th>';
                $current_date = strtotime('+1 day', $current_date);
            }

            echo '</tr>
                    </thead>
                    <tbody>';

            if ($result && count($result) > 0) {
                // Xử lý dữ liệu
                $attendance = [];
                foreach ($result as $row) {
                    $day = date('Y-m-d', strtotime($row['clock_in_time']));
                    if (!isset($attendance[$row['user_id']])) {
                        $attendance[$row['user_id']] = [
                            'name' => $row['full_name'],
                            'code' => $row['manv'],
                            'days' => [],
                            'worktime' => 0
                        ];
                    }
                    $attendance[$row['user_id']]['worktime'] += $row['work_time'];
                    $attendance[$row['user_id']]['days'][$day] = $row;
                }

                // Lặp qua nhân viên
                foreach ($attendance as $user_id => $data) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($data['name']) . '</td>';
                    echo '<td>' . htmlspecialchars($data['code']) . '</td>';
                    echo '<td>' . count($data['days']) . '</td>';
                    echo '<td>' . $data['worktime'] . ' công</td>';

                    // Hiển thị từng ngày từ 16 tháng trước đến 15 tháng hiện tại
                    $current_date = strtotime($start_date);
                    while ($current_date <= $end_date) {
                        $current_day = date('Y-m-d', $current_date);
                        $day_of_week = date('N', $current_date);

                        if (isset($data['days'][$current_day])) {
                            $day_data = $data['days'][$current_day];
                            echo '<td class="has-data" data-toggle="modal" data-target="#attendanceModal"
                                     data-user-id="' . htmlspecialchars($user_id) . '"
                                     data-clock-in="' . htmlspecialchars($day_data['clock_in_time']) . '"
                                     data-clock-out="' . htmlspecialchars($day_data['clock_out_time']) . '"
                                     data-wifi-in="' . htmlspecialchars($day_data['wifi_address']) . '"
                                     data-wifi-out="' . htmlspecialchars($day_data['wifi_address_out']) . '"
                                     data-work-time="' . htmlspecialchars($day_data['work_time']) . '">
                                     ' . htmlspecialchars($day_data['work_time']) . '
                                 </td>';
                        } else {
                            if ($day_of_week >= 6) {
                                echo '<td>w</td>'; // Cuối tuần
                            } else {
                                echo '<td></td>'; // Ngày không có dữ liệu
                            }
                        }
                        $current_date = strtotime('+1 day', $current_date);
                    }
                    echo '</tr>';
                }
            } else {
                echo '<tr><td colspan="35" class="text-center">No data available</td></tr>';
            }

            echo '</tbody></table>';
        } else {
            echo '<div class="alert alert-danger">Could not connect to the database</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}
public function loadTimekeepingByFilters($department_id = null, $monthYear = null) {
    $sql = "SELECT u.id AS user_id, u.full_name, u.manv, u.department_id, a.clock_in_time, a.clock_out_time, 
               a.wifi_address, a.wifi_address_out, s.shift_name, s.work_time
        FROM attendance a
        INNER JOIN users u ON u.id = a.user_id
        INNER JOIN shifts s ON s.id = a.shift_id
        WHERE 1=1";

$params = [];

if (!$monthYear) {
    $monthYear = date('Y-m');
}

list($year, $month) = explode('-', $monthYear);

if ($department_id) {
    $sql .= ' AND u.department_id = :department_id';
    $params[':department_id'] = $department_id;
}

$start_date = "$year-$month-04";
$nextMonth = $month == 12 ? 1 : $month + 1;
$nextYear = $month == 12 ? $year + 1 : $year;
$end_date = "$nextYear-$nextMonth-03";

// Sửa câu lệnh SQL để chỉ so sánh ngày (không tính thời gian)
$sql .= ' AND DATE(a.clock_in_time) BETWEEN :start_date AND :end_date';
$params[':start_date'] = $start_date;
$params[':end_date'] = $end_date;

try {
    $link = $this->getConnection();

    if ($link) {
        $stmt = $link->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo '<table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Họ tên</th>
                        <th>Mã nhân viên</th>
                        <th>Ngày làm việc</th>
                        <th>Ngày công thực tế</th>';

        // Hiển thị các ngày từ 4 tháng này đến 3 tháng sau
        $current_date = strtotime($start_date);
        $end_date = strtotime($end_date . ' 23:59:59'); // Đảm bảo bao gồm ngày cuối cùng (3 tháng 12)

        while ($current_date <= $end_date) {
            echo '<th>' . date('D d', $current_date) . '</th>';
            $current_date = strtotime('+1 day', $current_date);
        }

        echo '</tr>
            </thead>
            <tbody>';

        if ($result && count($result) > 0) {
            $attendance = [];
            foreach ($result as $row) {
                $day = date('Y-m-d', strtotime($row['clock_in_time']));
                if (!isset($attendance[$row['user_id']])) {
                    $attendance[$row['user_id']] = [
                        'name' => $row['full_name'],
                        'code' => $row['manv'],
                        'days' => [],
                        'worktime' => 0
                    ];
                }
                $attendance[$row['user_id']]['worktime'] += $row['work_time'];
                $attendance[$row['user_id']]['days'][$day] = $row;
            }

            foreach ($attendance as $user_id => $data) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($data['name']) . '</td>';
                echo '<td>' . htmlspecialchars($data['code']) . '</td>';
                echo '<td>' . count($data['days']) . '</td>';
                echo '<td>' . $data['worktime'] . ' công</td>';

                $current_date = strtotime($start_date);
                while ($current_date <= $end_date) {
                    $current_day = date('Y-m-d', $current_date);
                    $day_of_week = date('N', $current_date);

                    if (isset($data['days'][$current_day])) {
                        $day_data = $data['days'][$current_day];
                        echo '<td class="has-data" data-toggle="modal" data-target="#attendanceModal"
                                 data-user-id="' . htmlspecialchars($user_id) . '"
                                 data-clock-in="' . htmlspecialchars($day_data['clock_in_time']) . '"
                                 data-clock-out="' . htmlspecialchars($day_data['clock_out_time']) . '"
                                 data-wifi-in="' . htmlspecialchars($day_data['wifi_address']) . '"
                                 data-wifi-out="' . htmlspecialchars($day_data['wifi_address_out']) . '"
                                 data-work-time="' . htmlspecialchars($day_data['work_time']) . '">
                                 ' . htmlspecialchars($day_data['work_time']) . '
                             </td>';
                    } else {
                        if ($day_of_week >= 6) {
                            echo '<td>w</td>'; // Cuối tuần
                        } else {
                            echo '<td></td>'; // Ngày không có dữ liệu
                        }
                    }
                    $current_date = strtotime('+1 day', $current_date);
                }
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="35" class="text-center">No data available</td></tr>';
        }

        echo '</tbody></table>';
    } else {
        echo '<div class="alert alert-danger">Could not connect to the database</div>';
    }
} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
}

}
public function generateUserOptions() {
    try {
        // Kết nối cơ sở dữ liệu
        $link = $this->getConnection();

        if ($link) {
            // Truy vấn dữ liệu từ bảng `users`
            $sql = "SELECT u.id, u.full_name FROM users u";
            $stmt = $link->prepare($sql);
            $stmt->execute();

            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Xuất các thẻ <option>
            if ($users && count($users) > 0) {
                foreach ($users as $user) {
                    echo '<option value="' . htmlspecialchars($user['id']) . '">' . htmlspecialchars($user['full_name']) . '</option>';
                }
            } else {
                echo '<option value="">No users found</option>';
            }
        } else {
            echo '<option value="">Database connection failed</option>';
        }
    } catch (PDOException $e) {
        echo '<option value="">Error: ' . htmlspecialchars($e->getMessage()) . '</option>';
    }
}


public function getTimekeepingData($user_id, $monthYear = null) {
    try {
        $sql = "SELECT u.full_name, u.manv, a.clock_in_time, a.clock_out_time, s.work_time
                FROM attendance a
                INNER JOIN users u ON u.id = a.user_id
                INNER JOIN shifts s ON s.id = a.shift_id
                WHERE u.id = ?"; // Single user ID

        $params = [$user_id];

        // Set the date range (from 4th day of the month to the 3rd of next month)
        if (!$monthYear) {
            $monthYear = date('Y-m');
        }
        list($year, $month) = explode('-', $monthYear);
        $start_date = "$year-$month-04";
        $nextMonth = $month == 12 ? 1 : $month + 1;
        $nextYear = $month == 12 ? $year + 1 : $year;
        $end_date = "$nextYear-$nextMonth-03";

        // Add the date condition to the SQL query
        $sql .= ' AND DATE(a.clock_in_time) BETWEEN ? AND ?';
        $params[] = $start_date;
        $params[] = $end_date;

        $link = $this->getConnection();
        if ($link) {
            $stmt = $link->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key + 1, $value);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Process the data into a structured array
            $data = [];
            foreach ($result as $row) {
                $day = date('d', strtotime($row['clock_in_time']));
                if (!isset($data[$row['manv']])) {
                    $data[$row['manv']] = [
                        'full_name' => $row['full_name'],
                        'work_days' => []
                    ];
                }
                $data[$row['manv']]['work_days'][$day] = [
                    'clock_in_time' => $row['clock_in_time'],
                    'clock_out_time' => $row['clock_out_time'],
                    'work_time' => $row['work_time']
                ];
            }

            return $data; // Return the structured data array
        } else {
            echo '<div class="alert alert-danger">Could not connect to the database</div>';
            return [];
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
        return [];
    }
}
public function exportTimekeeping($user_id, $monthYear = null) {
    // Include the PHPExcel library
    require_once __DIR__ . '/../../assets/upload/Classes/PHPExcel.php';

    try {
        $sql = "SELECT u.full_name, u.manv, a.clock_in_time, s.work_time
                FROM attendance a
                INNER JOIN users u ON u.id = a.user_id
                INNER JOIN shifts s ON s.id = a.shift_id
                WHERE u.id = ?"; // Single user ID

        $params = [$user_id];

        // Set the date range (from 4th day of the month to the 3rd of next month)
        if (!$monthYear) {
            $monthYear = date('Y-m');
        }
        list($year, $month) = explode('-', $monthYear);
        $start_date = "$year-$month-04";
        $nextMonth = $month == 12 ? 1 : $month + 1;
        $nextYear = $month == 12 ? $year + 1 : $year;
        $end_date = "$nextYear-$nextMonth-03";

        // Add the date condition to the SQL query
        $sql .= ' AND DATE(a.clock_in_time) BETWEEN ? AND ?';
        $params[] = $start_date;
        $params[] = $end_date;

        $link = $this->getConnection();
        if ($link) {
            $stmt = $link->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key + 1, $value);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Initialize PHPExcel object
            $spreadsheet = new PHPExcel();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $sheet->setCellValue('A1', 'Full Name');
            $sheet->setCellValue('B1', 'Clock In');
            $sheet->setCellValue('C1', 'Clock Out');
            $sheet->setCellValue('D1', 'Work Time');

            $row = 2; // Start from row 2 for data
            foreach ($result as $rowData) {
                $sheet->setCellValue('A' . $row, $rowData['full_name']);
                $sheet->setCellValue('B' . $row, $rowData['clock_in_time']);
                $sheet->setCellValue('C' . $row, $rowData['clock_out_time']);
                $sheet->setCellValue('D' . $row, $rowData['work_time']);
                $row++;
            }

            // Specify the directory path
            $directoryPath = __DIR__ . '/../../assets/upload/';
            $filename = $directoryPath . 'attendance_report_' . $monthYear . '.xlsx';

            // Write to file
            $writer = PHPExcel_IOFactory::createWriter($spreadsheet, 'Excel2007');
            $writer->save($filename);
            return $filename;
        } else {
            echo '<div class="alert alert-danger">Could not connect to the database</div>';
        }
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
}



public function exportTimekeepingDataToExcel($data, $monthYear) {
    // Initialize PhpSpreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers
    $sheet->setCellValue('A1', 'Full Name');
    $sheet->setCellValue('B1', 'Employee Code');
    $sheet->setCellValue('C1', 'Clock In');
    $sheet->setCellValue('D1', 'Clock Out');
    $sheet->setCellValue('E1', 'Work Time');

    $row = 2; // Start from row 2 for data
    foreach ($data as $employeeCode => $employeeData) {
        foreach ($employeeData['work_days'] as $day => $dayData) {
            $sheet->setCellValue('A' . $row, $employeeData['full_name']);
            $sheet->setCellValue('B' . $row, $employeeCode);
            $sheet->setCellValue('C' . $row, $dayData['clock_in_time']);
            $sheet->setCellValue('D' . $row, $dayData['clock_out_time']);
            $sheet->setCellValue('E' . $row, $dayData['work_time']);
            $row++;
        }
    }

    // Output to browser
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="attendance_report_' . $monthYear . '.xlsx"');
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}

public function importTimekeepingData($filePath) {
    try {
        // Load the spreadsheet file
        $spreadsheet = IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();

        // Initialize an array to store the data
        $data = [];

        // Iterate over each row in the spreadsheet
        foreach ($sheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Assuming the first column is 'manv' and the rest are workdays
            $manv = $rowData[0];
            $workdays = array_slice($rowData, 1);

            // Store the data in the array
            $data[$manv] = $workdays;
        }

        // Process the data as needed
        foreach ($data as $manv => $workdays) {
            // Example: Insert or update workdays in the database
            $this->updateWorkdaysForManv($manv, $workdays);
        }

        return 1;
    } catch (Exception $e) {
        error_log("Lỗi khi nhập dữ liệu: " . $e->getMessage());
        return 0;
    }
}

public function getUserIdFromEmployeeCode($employeeCode) {
    $link = $this->getConnection();
    if ($link) {
        $sql = "SELECT id FROM users WHERE manv = :employee_code";
        $stmt = $link->prepare($sql);
        $stmt->bindValue(':employee_code', $employeeCode, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
    return null;
}

private function updateAttendanceRecord($entry) {
    $link = $this->getConnection();
    if ($link) {
        $sql = "INSERT INTO attendance ( user_id, clock_in_time, clock_out_time, shift_id)
                VALUES ( :user_id, :clock_in, :clock_out, :shift_id)
                ON DUPLICATE KEY UPDATE
                clock_in_time = :clock_in, clock_out_time = :clock_out, work_time = :work_time";
        $stmt = $link->prepare($sql);
        $stmt->bindValue(':full_name', $entry['full_name'], PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $entry['user_id'], PDO::PARAM_STR);
        $stmt->bindValue(':clock_in', $entry['clock_in'], PDO::PARAM_STR);
        $stmt->bindValue(':clock_out', $entry['clock_out'], PDO::PARAM_STR);
        $stmt->bindValue(':shift_id', $entry['shift_id'], PDO::PARAM_STR);
        $stmt->execute();
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

public function clockInTest($user_id, $check_in_time = null) {
    // If no check-in time is provided, use the current time
    $clock_in_time = $check_in_time ? $check_in_time : date("Y-m-d H:i:s");

    // Call check_in to determine `check_in` and `shift_id`
    $check_in_result = $this->check_in($clock_in_time, $user_id);
    $check_in_status = $check_in_result['check_in'];
    $lateLeaveTime = $check_in_result['thoi_gian_tre']; // Get late time

    // Insert the attendance record
    $sql = '
        INSERT INTO attendance (user_id, clock_in_time, check_in, lateLeaveTime) 
        VALUES (:user_id, :clock_in_time, :check_in, :lateLeaveTime)
    ';
    
    $link = $this->getConnection();
    if ($link) {
        $stmt = $link->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':clock_in_time', $clock_in_time);
        $stmt->bindParam(':check_in', $check_in_status, PDO::PARAM_INT);
        $stmt->bindParam(':lateLeaveTime', $lateLeaveTime, PDO::PARAM_INT);

        // Execute the query
        if ($stmt->execute()) {
            // Return the last inserted ID
            return $link->lastInsertId();
        } else {
            throw new Exception("Clock-in failed. Please try again.");
        }
    } else {
        throw new Exception("Could not connect to the database.");
    }
}

public function clockOutTest($attendance_id, $check_out_time = null) {
    $link = $this->getConnection();

    if ($link) {
        // Retrieve clock_in_time and check_in from attendance
        $sql = 'SELECT clock_in_time, check_in FROM attendance WHERE id = :id';
        $stmt = $link->prepare($sql);
        $stmt->bindParam(':id', $attendance_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            // If no check-out time is provided, use the current time
            $clock_out_time = $check_out_time ? $check_out_time : date("Y-m-d H:i:s");

            // Call check_out to calculate shift_id, work_time, and early leave time
            $check_in = $result['check_in'];
            $check_out_result = $this->check_out($clock_out_time, $check_in, $attendance_id);

            // Check the result from check_out
            if ($check_out_result) {
                // Update the attendance record
                $update_sql = '
                    UPDATE attendance 
                    SET clock_out_time = :clock_out_time, shift_id = :shift_id, earlyTime = :earlyTime
                    WHERE id = :id
                ';
                $stmt_update = $link->prepare($update_sql);
                $stmt_update->bindParam(':clock_out_time', $clock_out_time);
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

public function importTimekeepingDataFromExcel($filePath) {
    try {
        // Load the spreadsheet
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
        $sheet = $spreadsheet->getActiveSheet();
        $highestRow = $sheet->getHighestRow();

        $link = $this->getConnection();

        if ($link) {
            for ($row = 2; $row <= $highestRow; $row++) {
                $employeeCode = $sheet->getCell('B' . $row)->getValue();
                $clockIn = $sheet->getCell('C' . $row)->getValue();
                $clockOut = $sheet->getCell('D' . $row)->getValue();

                // Get user_id from employee code
                $userId = $this->getUserIdFromEmployeeCode($employeeCode);
                if (!$userId) {
                    echo "User ID not found for employee code: $employeeCode\n";
                    continue;
                }

                // Clock in
                $attendanceId = $this->clockInTest($userId, $clockIn);
                if (!$attendanceId) {
                    echo "Clock-in failed for user ID: $userId\n";
                    continue;
                }

                // Clock out
                $clockOutResult = $this->clockOutTest($attendanceId, $clockOut);
                if (!$clockOutResult['success']) {
                    echo "Clock-out failed for attendance ID: $attendanceId\n";
                }
            }
            return 1;
        } else {
            echo 'Không thể kết nối database';
        }
    } catch (Exception $e) {
        echo 'Lỗi khi nhập dữ liệu: ' . $e->getMessage();
    }
}
}
?>