<?php
include '../../db/connetion.php';


class kpi_depp extends Database{

    public function getDepartments() {
        $link = $this->getConnection();
        $stmt = $link->prepare("SELECT * FROM departments");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    public function getKPIsByDepartment($departmentId) {
    $link = $this->getConnection();
    $stmt = $link->prepare("SELECT kpi_lib_id, department_id, kpi_name, kpi_description FROM kpi_library WHERE department_id = :department_id");
    $stmt->bindParam(':department_id', $departmentId, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
  // Method to fetch all KPIs
    public function loadAllKPIs() {
        $sql = 'SELECT dk.dept_kpi_id,dk.kpi_lib_id,kl.kpi_lib_id,kl.kpi_name,kl.kpi_description, dk.department_id, d.department_name,d.maphong, dk.assigned_value, dk.assigned_date, dk.due_date, dk.status_id, st.name_status_kpi, dk.progress 
        FROM department_kpis dk 
        INNER JOIN departments d ON dk.department_id = d.id 
        INNER JOIN status_kpi st ON dk.status_id = st.id 
        INNER JOIN kpi_library kl ON kl.kpi_lib_id=dk.kpi_lib_id 
';
                $link = $this->getConnection();
        
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($result) > 0) {
                echo '<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>STT</th>
                                <th>Tên phòng ban</th>
                                <th>Tên KPI</th>
                                <th>Mô tả KPI</th>
                                <th>giá trị được giao</th>
                                <th>giá trị thực tế</th>                 
                                <th>Tiến độ (%)</th>
                                <th>ngày được giao</th>
                                <th>ngày kết thúc</th>
                                <th>trạng thái</th>
                                
                                <th>hành động</th>
                            </tr>
                        </thead>
                        <tbody>';
                $dem = 1;
                foreach ($result as $row) {
                    $progressPercentage = ($row['assigned_value'] > 0) ? ($row['progress'] / $row['assigned_value']) * 100 : 0;

                    echo '<tr>
                            <td>' . $dem . '</td>
                            <td>' . htmlspecialchars($row['department_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_description']) . '</td>
                            <td>' . htmlspecialchars($row['assigned_value']) . '</td>
                            <td>' . htmlspecialchars($row['progress']) . '</td>
                           
                            <td>' . number_format($progressPercentage, 2) . '%</td>
                            <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                            <td>' . htmlspecialchars($row['due_date']) . '</td>
                            <td>' . htmlspecialchars($row['name_status_kpi']) . '</td>
                            
                            <td>
                                <button class="btn btn-primary edit-btn my-2" data-toggle="modal" data-target="#editKPIDepartmentModal"
         
                            data-assigned-value="' . htmlspecialchars($row['assigned_value']) . '"  
                            data-due-date="' . htmlspecialchars($row['due_date']) . '" 
                            data-dept-kpi-id="' . htmlspecialchars($row['dept_kpi_id']) . '">Sửa</button>
        
                                <button class="btn btn-danger delete-btn my-2" data-id="' . htmlspecialchars($row['dept_kpi_id']) . '">Xóa</button>
                               <a href="viewProgress.php?dept_kpi_id='. htmlspecialchars($row['dept_kpi_id']).'"><button class="btn btn-info progress-btn my-2" data-id="' . htmlspecialchars($row['dept_kpi_id']) . '">Xem tiến trình</button></a> 
                            </td>
                          </tr>';
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
 public function loadKPIsByFilters($department_id = null, $month = null, $year = null) {
        $sql = 'SELECT dk.dept_kpi_id, dk.kpi_lib_id, kl.kpi_name, kl.kpi_description, dk.department_id, d.department_name, dk.assigned_value, dk.assigned_date, dk.due_date, dk.status_id, st.name_status_kpi, dk.progress 
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

                    echo '<tr>
                            <td>' . $dem . '</td>
                            <td>' . htmlspecialchars($row['department_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_description']) . '</td>
                            <td>' . htmlspecialchars($row['assigned_value']) . '</td>
                            <td>' . htmlspecialchars($row['progress']) . '</td>
                            <td>' . number_format($progressPercentage, 2) . '%</td>
                            <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                            <td>' . htmlspecialchars($row['due_date']) . '</td>
                            <td>' . htmlspecialchars($row['name_status_kpi']) . '</td>
                            <td>
                                <button class="btn btn-primary edit-btn my-2" data-toggle="modal" data-target="#editKPIDepartmentModal"
                                data-assigned-value="' . htmlspecialchars($row['assigned_value']) . '"  
                                data-due-date="' . htmlspecialchars($row['due_date']) . '" 
                                data-dept-kpi-id="' . htmlspecialchars($row['dept_kpi_id']) . '">Sửa</button>
                                <button class="btn btn-danger delete-btn my-2" data-id="' . htmlspecialchars($row['dept_kpi_id']) . '">Xóa</button>
                                <a href="viewProgress.php?dept_kpi_id='. htmlspecialchars($row['dept_kpi_id']).'"><button class="btn btn-info progress-btn my-2" data-id="' . htmlspecialchars($row['dept_kpi_id']) . '">Xem tiến trình</button></a> 
                            </td>
                          </tr>';
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
    // Method to fetch KPIs by department
   
   public function them_kpi($kpi_lib_id, $department_id, $assigned_value, $assigned_date, $due_date) {
    $dbh = $this->getConnection();
    $stmt = $dbh->prepare("INSERT INTO department_kpis (kpi_lib_id, department_id, assigned_value, assigned_date, due_date, status_id, progress, last_update) 
                           VALUES ( :kpi_lib_id, :department_id, :assigned_value,:assigned_date, :due_date, 1, 0,NOW())");

    // Bind parameters to the statement
    $stmt->bindParam(':kpi_lib_id', $kpi_lib_id);
    $stmt->bindParam(':department_id', $department_id);
    $stmt->bindParam(':assigned_date', $assigned_date);
    $stmt->bindParam(':assigned_value', $assigned_value);
    $stmt->bindParam(':due_date', $due_date);
   

    // Execute the statement and return the result
    return $stmt->execute();
}
public function them_kpiIT($kpi_lib_id, $department_id, $assigned_value, $assigned_date, $due_date, $goalBasedIncentive = null) {
        $dbh = $this->getConnection();
        $sql = "INSERT INTO department_kpis (kpi_lib_id, department_id, assigned_value, assigned_date, due_date, status_id, progress, last_update, goalBasedIncentive) 
                VALUES (:kpi_lib_id, :department_id, :assigned_value, :assigned_date, :due_date, 1, 0, NOW(), :goal_based_incentive)";

        $stmt = $dbh->prepare($sql);

        // Bind parameters to the statement
        $stmt->bindParam(':kpi_lib_id', $kpi_lib_id);
        $stmt->bindParam(':department_id', $department_id);
        $stmt->bindParam(':assigned_value', $assigned_value);
        $stmt->bindParam(':assigned_date', $assigned_date);
        $stmt->bindParam(':due_date', $due_date);
        $stmt->bindParam(':goal_based_incentive', $goalBasedIncentive);

        // Execute the statement and return the result
        return $stmt->execute();
    }
public function sua_kpi($dept_kpi_id, $assigned_value, $due_date) {
    $dbh = $this->getConnection();
    $stmt = $dbh->prepare("UPDATE department_kpis 
                           SET assigned_value = :assigned_value,               
                               due_date = :due_date, 
                               last_update = now() 
                           WHERE dept_kpi_id = :dept_kpi_id");

    // Bind parameters to the statement
    $stmt->bindParam(':dept_kpi_id', $dept_kpi_id);
    $stmt->bindParam(':assigned_value', $assigned_value);
    $stmt->bindParam(':due_date', $due_date);
    $stmt->bindParam(':last_update', $last_update);

    // Execute the statement and return the result
    return $stmt->execute();
}

    
    public function xoa_kpi($dept_kpi_id) {
    $dbh = $this->getConnection();
    $stmt = $dbh->prepare("DELETE FROM department_kpis WHERE dept_kpi_id = :dept_kpi_id");

    // Bind the KPI ID parameter to the statement
    $stmt->bindParam(':dept_kpi_id', $dept_kpi_id);

    // Execute the statement and return the result
    return $stmt->execute();
}

    public function getKPIProgressLog($emp_kpi_id) {
        $link = $this->getConnection();
        $stmt = $link->prepare("SELECT * FROM kpi_progress_log WHERE emp_kpi_id = :emp_kpi_id ORDER BY log_date DESC");
        $stmt->bindParam(':emp_kpi_id', $emp_kpi_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   

}
?>