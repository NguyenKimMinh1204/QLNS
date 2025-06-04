<?php
include '../../db/connetion.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

class tinhluong extends Database{
public function getDepartments() {
$link = $this->getConnection();
$stmt = $link->prepare("SELECT * FROM departments");
$stmt->execute();
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function loadKPIsByFilters($department_id = null, $month = null, $year = null) {
        $sql = 'SELECT dk.dept_kpi_id, dk.kpi_lib_id, kl.kpi_name, kl.kpi_description, dk.department_id, d.department_name, dk.assigned_value, dk.assigned_date, dk.due_date, dk.status_id, st.name_status_kpi, dk.progress, dk.is_active,dk.goalBasedIncentive
                FROM department_kpis dk 
                INNER JOIN departments d ON dk.department_id = d.id 
                INNER JOIN status_kpi st ON dk.status_id = st.id 
                INNER JOIN kpi_library kl ON kl.kpi_lib_id = dk.kpi_lib_id 
                WHERE 1=1'; // Start with a base condition

        // Prepare an array to hold the parameters
        $params = [];

        // Add conditions based on the provided parameters
        if ($department_id) {
            $sql .= ' AND dk.department_id = :department_id';
            $params[':department_id'] = $department_id;
        }

        if ($month) {
            $sql .= ' AND MONTH(dk.assigned_date) = :month';
            $params[':month'] = $month;
        }

        if ($year) {
            $sql .= ' AND YEAR(dk.assigned_date) = :year';
            $params[':year'] = $year;
        }

        $link = $this->getConnection();

        if ($link) {
            $stmt = $link->prepare($sql);

            // Bind parameters dynamically
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($result) > 0) {
                // Output the results in a table format
                echo '<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên phòng ban</th>
                                <th>Tên KPI</th>
                                <th>Mô tả KPI</th>
                                <th>Giá trị được giao</th>
                                <th>Giá trị thực tế</th>
                                <th>Tiến độ (%)</th>
                                <th>Ngày được giao</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>';
                $dem = 1;
                foreach ($result as $row) {
                    $progressPercentage = ($row['assigned_value'] > 0) ? ($row['progress'] / $row['assigned_value']) * 100 : 0;
                    $bonus = $this->calculateKPIBonus($row['progress'], $row['assigned_value']);
                    $isActive = $row['is_active'];
                    $currentDate = date('Y-m-d');
                    $dueDate = $row['due_date'];
                      if($row['department_id']==5){
                    $bonusIT=$row['goalBasedIncentive'];
                    $phantram=$row['progress']/$row['assigned_value'];
                    if($phantram<0.8){
                        $bonus=0;
                    }else{
                        $bonus=$bonusIT*$row['progress']/$row['assigned_value'];
                    }
                     echo '<tr>
                            <td>' . $dem . '</td>
                            <td>' . htmlspecialchars($row['department_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_description']) . '</td>
                            <td>' . htmlspecialchars($row['assigned_value']) . '</td>
                            <td>' . htmlspecialchars($row['progress']) . '</td>
                            <td>' . number_format($progressPercentage, 2) . '%</td>
                            <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                            <td>' . htmlspecialchars($dueDate) . '</td>
                            <td>' . htmlspecialchars($row['name_status_kpi']) . '</td>
                            <td>'; 
                    }else{
                         echo '<tr>
                            <td>' . $dem . '</td>
                            <td>' . htmlspecialchars($row['department_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_description']) . '</td>
                            <td>' . htmlspecialchars($row['assigned_value']) . '</td>
                            <td>' . htmlspecialchars($row['progress']) . '</td>
                            <td>' . number_format($progressPercentage, 2) . '%</td>
                            <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                            <td>' . htmlspecialchars($dueDate) . '</td>
                            <td>' . htmlspecialchars($row['name_status_kpi']) . '</td>
                            <td>'; 
                    }
                   
                    
                    if ($dueDate > $currentDate) {
                        echo '<button class="btn btn-warning my-2" disabled>Chưa tới hạn tính thưởng</button>';
                    } else {
                        if ($isActive == 0) {
                            echo '<button class="btn btn-success calculate-bonus-btn my-2" data-id="' . htmlspecialchars($row['dept_kpi_id']) . '" data-bonus="' . number_format($bonus, 2) . '" data-toggle="modal" data-target="#bonusModal">Tính Thưởng</button>';
                        } else {
                            echo '<span> Thưởng: ' . $row['goalBasedIncentive'] . '</span>';
                        }
                    }

                    echo '</td></tr>';
                    $dem++;
                }

                echo '</tbody></table>';
            } else {
                echo 'No data available';
            }
        } else {
            echo 'Cannot connect to the database';
        }
    }
public function updateDepartmentKpis($dept_kpi_id, $goalBasedIncentive) {
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
        $sql = "UPDATE department_kpis SET goalBasedIncentive = :goalBasedIncentive, is_active = 1 WHERE dept_kpi_id = :dept_kpi_id";
        $stmt = $link->prepare($sql);

        // Bind the parameters
        $stmt->bindValue(':goalBasedIncentive', $goalBasedIncentive, PDO::PARAM_STR);
        $stmt->bindValue(':dept_kpi_id', $dept_kpi_id, PDO::PARAM_INT);

        // Execute the statement and return the result
        return $stmt->execute() ? true : false;
    } else {
        // If no connection, return false
        return false;
    }
}

public function calculateKPIBonus($progress, $assigned_value) {
    $progressPercentage = ($assigned_value > 0) ? ($progress / $assigned_value) * 100 : 0;
 return 0.1 * $progress;
    // if ($progressPercentage < 80) {
    //     return 0;
    // } elseif ($progressPercentage < 90) {
    //     return 0.02 * $progress;
    // } elseif ($progressPercentage < 100) {
    //     return 0.03 * $progress;
    // } elseif ($progressPercentage == 100) {
    //     return 0.04 * $progress;
    // } elseif ($progressPercentage <= 110) {
    //     return 0.05 * $progress;
    // } elseif ($progressPercentage < 120) {
    //     return 0.06 * $progress;
    // } elseif ($progressPercentage < 150) {
    //     return 0.07 * $progress;
    // } else {
    //     return 0.08 * $progress;
    // }
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

        return true;
    } catch (Exception $e) {
        error_log("Error importing timekeeping data: " . $e->getMessage());
        return false;
    }
}

private function updateWorkdaysForManv($manv, $workdays) {
    // Implement the logic to update workdays for the given manv
    // This could involve inserting or updating records in the database
    // Example:
    $link = $this->getConnection();
    if ($link) {
        foreach ($workdays as $day => $workTime) {
            $sql = "INSERT INTO workdays (manv, day, work_time) VALUES (:manv, :day, :work_time)
                    ON DUPLICATE KEY UPDATE work_time = :work_time";
            $stmt = $link->prepare($sql);
            $stmt->bindValue(':manv', $manv, PDO::PARAM_STR);
            $stmt->bindValue(':day', $day, PDO::PARAM_STR);
            $stmt->bindValue(':work_time', $workTime, PDO::PARAM_STR);
            $stmt->execute();
        }
    }
}
}
?>