<?php
include '../../db/connetion.php';

class namekpi_emp extends Database {
    
    public function loadKpiData($department_id = null)
{
    $sql = "
       SELECT kp.id,kp.kpi_lib_id,kp.kpi_name,kp.kpi_description,kp.is_counted,kl.kpi_name AS kpi_name_dep,kl.kpi_description AS kpi_description_dep,kl.department_id FROM kpi_personal kp
INNER JOIN kpi_library kl ON kl.kpi_lib_id=kp.kpi_lib_id
    ";
    
    if ($department_id !== null) {
        $sql .= " WHERE kl.department_id = :department_id";
    }
    
    $link = $this->getConnection();
    
    // Kiểm tra kết nối
    if ($link) {
        // Thực hiện truy vấn
        $stmt = $link->prepare($sql);
        
        if ($department_id !== null) {
            $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
        }
         
        $stmt->execute();
        // Lấy tất cả dữ liệu
        $ketqua = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $i = count($ketqua);
        
        if ($i > 0) {
            echo '<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 100px;">STT</th>
                            <th style="width: 250px;">Tên KPI phòng</th>
                            <th style="width: 250px;">Tên KPI</th>
                            <th style="width: 350px;">Mô tả KPI</th>
                            <th style="width: 250px;">tính giá trị kpi</th>
                            <th style="width: 150px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>';
            $dem=1;
            foreach ($ketqua as $row) {
                $kpiId = $row['id'];
                $kpi_name_dep = $row['kpi_name_dep'];
                $kpiName = $row['kpi_name'];
                $kpiDescription = $row['kpi_description'];
                $is_counted = $row['is_counted']? 'có':'không';
                
                echo '                
                    <tr>
                        <td>' . $dem. '</td>
                        <td>' . htmlspecialchars($kpi_name_dep) . '</td>
                        <td>' . htmlspecialchars($kpiName) . '</td>
                        <td>' . htmlspecialchars($kpiDescription) . '</td>
                        <td>' . htmlspecialchars($is_counted) . '</td>
                        <td>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#editModal" 
                                    data-id="' . htmlspecialchars($kpiId) . '" 
                                    data-name="' . htmlspecialchars($kpiName) . '" 
                                    data-description="' . htmlspecialchars($kpiDescription) . '" 
                                    data-iscounted="' . htmlspecialchars($is_counted) . '">Sửa</button>
                            <button class="btn btn-danger delete-btn" 
                                    data-id="' . htmlspecialchars($kpiId) . '">Xóa</button>
                        </td>
                    </tr>';
                    $dem++;
            }

            echo '</tbody>
                </table>';
        } else {
            echo 'Không có dữ liệu';
        }

        // Đóng kết nối không cần thiết vì PDO tự động đóng khi hết phạm vi
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
        public function getnameKPIDep() {
        $link = $this->getConnection();
        $stmt = $link->prepare("SELECT kpi_lib_id,kpi_name FROM kpi_library");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        // Method to add a new KPI
    public function addKpi($kpi_lib_id, $kpiName, $kpiDescription) {
        try {
            $link = $this->getConnection();
            $sql = "INSERT INTO kpi_personal (kpi_lib_id, kpi_name, kpi_description, is_counted) VALUES (:kpi_lib_id, :kpi_name, :kpi_description)";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':kpi_lib_id', $kpi_lib_id);
            $stmt->bindParam(':kpi_name', $kpiName);
            $stmt->bindParam(':kpi_description', $kpiDescription);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("PDO Exception: " . $e->getMessage());
            return false;
        }
    }

    // Method to update an existing KPI
    public function updateKpi($kpiId, $kpiName, $kpiDescription,$is_counted) {
        try {
            $link = $this->getConnection();
            $sql = "UPDATE kpi_personal SET kpi_name = :kpi_name, kpi_description = :kpi_description,is_counted = :is_counted WHERE id = :kpi_id";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':kpi_id', $kpiId);
            $stmt->bindParam(':kpi_name', $kpiName);
            $stmt->bindParam(':kpi_description', $kpiDescription);
             $stmt->bindParam(':is_counted', $is_counted);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("PDO Exception: " . $e->getMessage());
            return false;
        }
    }

    // Method to delete a KPI
    public function deleteKpi($kpiId) {
        try {
            $link = $this->getConnection();
            $sql = "DELETE FROM kpi_personal WHERE id = :kpi_id";
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':kpi_id', $kpiId);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("PDO Exception: " . $e->getMessage());
            return false;
        }
    }

    // Method to fetch all KPIs
    public function loadAllKPIs() {
        $sql = 'SELECT dk.kpi_lib_id, dk.department_id, d.department_name, dk.assigned_value, dk.assigned_date, dk.due_date, dk.status_id, st.name_status_kpi, dk.progress
                FROM department_kpis dk
                INNER JOIN departments d ON dk.department_id = d.id
                INNER JOIN status_kpi st ON dk.status_id = st.id';
        
        $link = $this->getConnection();
        
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($result) > 0) {
                echo '<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>KPI ID</th>
                                <th>Department ID</th>
                                <th>Department Name</th>
                                <th>Assigned Value</th>
                                <th>Assigned Date</th>
                                <th>Due Date</th>
                                <th>Status ID</th>
                                <th>Status Name</th>
                                <th>Progress</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                foreach ($result as $row) {
                    $kpiId = htmlspecialchars($row['kpi_lib_id']);
                    $departmentId = htmlspecialchars($row['department_id']);
                    $departmentName = htmlspecialchars($row['department_name']);
                    $assignedValue = htmlspecialchars($row['assigned_value']);
                    $assignedDate = htmlspecialchars($row['assigned_date']);
                    $dueDate = htmlspecialchars($row['due_date']);
                    $statusId = htmlspecialchars($row['status_id']);
                    $statusName = htmlspecialchars($row['name_status_kpi']);
                    $progress = htmlspecialchars($row['progress']);

                    echo '<tr>
                            <td>' . $kpiId . '</td>
                            <td>' . $departmentId . '</td>
                            <td>' . $departmentName . '</td>
                            <td>' . $assignedValue . '</td>
                            <td>' . $assignedDate . '</td>
                            <td>' . $dueDate . '</td>
                            <td>' . $statusId . '</td>
                            <td>' . $statusName . '</td>
                            <td>' . $progress . '</td>
                            <td>
                                <button class="btn btn-primary edit-btn" 
                                        data-id="' . $kpiId . '" 
                                        data-name="' . htmlspecialchars($row['kpi_name']) . '" 
                                        data-description="' . htmlspecialchars($row['kpi_description']) . '" 
                                        data-assigned-value="' . $assignedValue . '" 
                                        data-assigned-date="' . $assignedDate . '" 
                                        data-due-date="' . $dueDate . '" 
                                        data-last-update="' . htmlspecialchars($row['last_update']) . '">Edit</button>
                                <button class="btn btn-danger delete-btn" data-id="' . $kpiId . '">Delete</button>
                              <a href=""></a>  
                            </td>
                          </tr>';
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
    public function loadKPIsByDepartment($departmentId) {
        $sql = 'SELECT dk.kpi_lib_id, dk.department_id, d.department_name, dk.assigned_value, dk.assigned_date, dk.due_date, dk.status_id, st.name_status_kpi, dk.progress
                FROM department_kpis dk
                INNER JOIN departments d ON dk.department_id = d.id
                INNER JOIN status_kpi st ON dk.status_id = st.id
                WHERE dk.department_id = :department_id';
        
        $link = $this->getConnection();
        
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':department_id', $departmentId, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($result) > 0) {
                echo '<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>KPI ID</th>
                                <th>Department ID</th>
                                <th>Department Name</th>
                                <th>Assigned Value</th>
                                <th>Assigned Date</th>
                                <th>Due Date</th>
                                <th>Status ID</th>
                                <th>Status Name</th>
                                <th>Progress</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                foreach ($result as $row) {
                    $kpiId = htmlspecialchars($row['kpi_lib_id']);
                    $departmentId = htmlspecialchars($row['department_id']);
                    $departmentName = htmlspecialchars($row['department_name']);
                    $assignedValue = htmlspecialchars($row['assigned_value']);
                    $assignedDate = htmlspecialchars($row['assigned_date']);
                    $dueDate = htmlspecialchars($row['due_date']);
                    $statusId = htmlspecialchars($row['status_id']);
                    $statusName = htmlspecialchars($row['name_status_kpi']);
                    $progress = htmlspecialchars($row['progress']);

                    echo '<tr>
                            <td>' . $kpiId . '</td>
                            <td>' . $departmentId . '</td>
                            <td>' . $departmentName . '</td>
                            <td>' . $assignedValue . '</td>
                            <td>' . $assignedDate . '</td>
                            <td>' . $dueDate . '</td>
                            <td>' . $statusId . '</td>
                            <td>' . $statusName . '</td>
                            <td>' . $progress . '</td>
                            <td>
                                <button class="btn btn-primary edit-btn" 
                                        data-id="' . $kpiId . '" 
                                        data-name="' . htmlspecialchars($row['kpi_name']) . '" 
                                        data-description="' . htmlspecialchars($row['kpi_description']) . '" 
                                        data-assigned-value="' . $assignedValue . '" 
                                        data-assigned-date="' . $assignedDate . '" 
                                        data-due-date="' . $dueDate . '" 
                                        data-last-update="' . htmlspecialchars($row['last_update']) . '">Edit</button>
                                <button class="btn btn-danger delete-btn" data-id="' . $kpiId . '">Delete</button>
                                <button class="btn btn-info progress-btn" data-id="' . $kpiId . '">View Progress</button>
                            </td>
                          </tr>';
                }

                echo '</tbody></table>';
            } else {
                echo 'No data available for this department';
            }
        } else {
            echo 'Cannot connect to the database';
        }
    }
}