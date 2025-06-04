<?php
include '../../db/connetion.php';


class kpi_m extends Database{

   public function getDepartmentByUserId($user_id) {

    $link = $this->getConnection();

    // Sửa lại câu SQL để loại bỏ dấu "=" thừa
    $stmt = $link->prepare("SELECT u.department_id FROM users u WHERE u.id = :user_id");

    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

   
    return $row ? $row['department_id'] : null;
}

public function getDepartmentBydept_kpi_id($dept_kpi_id) {
    // Kết nối tới cơ sở dữ liệu
    $link = $this->getConnection();
    
    // Chuẩn bị câu truy vấn SQL với tham số `dept_kpi_id`
    $stmt = $link->prepare("SELECT d.department_id FROM department_kpis d WHERE d.dept_kpi_id = :dept_kpi_id");
    
    // Gắn giá trị của `dept_kpi_id` vào tham số truy vấn
    $stmt->bindParam(':dept_kpi_id', $dept_kpi_id, PDO::PARAM_INT);
    
    // Thực thi truy vấn
    $stmt->execute();
    
    // Lấy kết quả trả về và trả về trực tiếp `department_id`
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['department_id'] : null; // Returns department_id or null if not found
}


public function getUserBydepartment($department_id) {
    // Kết nối tới cơ sở dữ liệu
    $link = $this->getConnection();
    
    // Chuẩn bị câu truy vấn SQL với tham số `user_id`
    $stmt = $link->prepare("SELECT u.id, u.full_name FROM users u 
    WHERE u.department_id=:department_id");
    
    // Gắn giá trị của `user_id` vào tham số truy vấn
    $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
    
    // Thực thi truy vấn
    $stmt->execute();
    
    // Lấy kết quả trả về (chỉ một dòng)
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
public function getKpiLibIdByDeptKpiId($dept_kpi_id) {
    // Kết nối tới cơ sở dữ liệu
    $link = $this->getConnection();
    
    // Chuẩn bị câu truy vấn SQL
    $stmt = $link->prepare("
        SELECT kpi_lib_id 
        FROM department_kpis 
        WHERE dept_kpi_id = :dept_kpi_id
        LIMIT 1
    ");
    
    // Gắn giá trị của `dept_kpi_id` vào tham số truy vấn
    $stmt->bindParam(':dept_kpi_id', $dept_kpi_id, PDO::PARAM_INT);
    
    // Thực thi truy vấn
    $stmt->execute();
    
    // Lấy kết quả đầu tiên (hoặc trả về `null` nếu không có kết quả)
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['kpi_lib_id'] : null;
}


public function getnamekpiEByDepartmentAndLib($department_id, $kpi_lib_id) {
    // Kết nối tới cơ sở dữ liệu
    $link = $this->getConnection();
    
    // Chuẩn bị câu truy vấn SQL
    $stmt = $link->prepare("
        SELECT kp.id, kp.kpi_lib_id, kp.kpi_name, kp.kpi_description, kp.is_counted, kl.department_id
        FROM kpi_personal kp
        INNER JOIN kpi_library kl ON kl.kpi_lib_id = kp.kpi_lib_id
        WHERE kl.department_id = :department_id AND kp.kpi_lib_id = :kpi_lib_id
    ");
    
    // Gắn giá trị của `department_id` và `kpi_lib_id` vào tham số truy vấn
    $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
    $stmt->bindParam(':kpi_lib_id', $kpi_lib_id, PDO::PARAM_INT);
    
    // Thực thi truy vấn
    $stmt->execute();
    
    // Lấy tất cả kết quả trả về
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function loadKPIsByDepartment($departmentId) {
        $sql = 'SELECT dk.kpi_lib_id,kl.kpi_name, dk.dept_kpi_id, dk.department_id, d.department_name, dk.assigned_value, dk.assigned_date, dk.due_date, dk.status_id, st.name_status_kpi, dk.progress
                FROM department_kpis dk
                INNER JOIN kpi_library kl ON kl.kpi_lib_id = dk.kpi_lib_id
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
                                <th>STT</th>
                                <th>Tên kpi</th>
                                <th>Giá trị được giao</th>
                                <th>Giá trị thực tế</th>
                                <th>Tiến độ(%)</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
                                <th>hành động</th>
                            </tr>
                        </thead>
                        <tbody>';
                $dem=1;
                $tiendo=0;
                foreach ($result as $row) {
                    
                    $tiendo=round($row['progress']/$row['assigned_value'],2);
                     echo '<tr>
                            <td>' . $dem. '</td>
                            <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                            <td>' . htmlspecialchars($row['assigned_value']) . '</td>
                            <td>' . htmlspecialchars($row['progress']) . '</td>
                            <td>' . $tiendo . '</td>
                            <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                            <td>' . htmlspecialchars($row['due_date']) . '</td>
                            <td>' . htmlspecialchars($row['name_status_kpi']) . '</td>
                            
                            <td>
                            <a href="giaokpi.php?dept_kpi_id='. htmlspecialchars($row['dept_kpi_id']) . '&department_id='. htmlspecialchars($row['department_id']) . '">
                            <button class="btn btn-info progress-btn mt-5" data-id="' . htmlspecialchars($row['kpi_lib_id']) . '">Xem tiến trình</button></a>
                                
                            </td>
                          </tr>';
                          $dem++;
                }

                echo '</tbody></table>';
            } else {
                echo 'No data available for this department';
            }
        } else {
            echo 'Cannot connect to the database';
        }
    }
     public function loadKPIsByDepartmentbytime($departmentId, $month, $year) {
        $sql = 'SELECT dk.kpi_lib_id,kl.kpi_name, dk.dept_kpi_id, dk.department_id, d.department_name, dk.assigned_value, dk.assigned_date, dk.due_date, dk.status_id, st.name_status_kpi, dk.progress
                FROM department_kpis dk
                INNER JOIN kpi_library kl ON kl.kpi_lib_id = dk.kpi_lib_id
                INNER JOIN departments d ON dk.department_id = d.id
                INNER JOIN status_kpi st ON dk.status_id = st.id
                WHERE dk.department_id = :department_id
                AND MONTH(dk.assigned_date) = :month
                AND YEAR(dk.assigned_date) = :year';
        
        $link = $this->getConnection();
        
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':department_id', $departmentId, PDO::PARAM_INT);
            $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (count($result) > 0) {
                echo '<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                               <th>STT</th>
                                <th>Tên kpi</th>
                                <th>Giá trị được giao</th>
                                <th>Giá trị thực tế</th>
                                <th>Tiến độ(%)</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
                                <th>hành động</th>
                            </tr>
                        </thead>
                        <tbody>';
                $dem = 1;
                foreach ($result as $row) {
                    $tiendo=round($row['progress']/$row['assigned_value']*100,2);
                     echo '<tr>
                            <td>' . $dem. '</td>
                            <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                            <td>' . htmlspecialchars($row['assigned_value']) . '</td>
                            <td>' . htmlspecialchars($row['progress']) . '</td>
                            <td>' . $tiendo . '</td>
                            <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                            <td>' . htmlspecialchars($row['due_date']) . '</td>
                            <td>' . htmlspecialchars($row['name_status_kpi']) . '</td>
                            
                            <td>
                            <a href="giaokpi.php?dept_kpi_id='. htmlspecialchars($row['dept_kpi_id']) . '&department_id='. htmlspecialchars($row['department_id']) . '">
                            <button class="btn btn-info progress-btn mt-5" data-id="' . htmlspecialchars($row['kpi_lib_id']) . '">Xem tiến trình</button></a>
                                
                            </td>
                          </tr>';
                          $dem++;
                }

                echo '</tbody></table>';
            } else {
                echo 'No data available for this department';
            }
        } else {
            echo 'Cannot connect to the database';
        }
    }

    public function renderKPIDetails($dept_kpi_id) {
// Fetch KPI details
$sql = 'SELECT dk.dept_kpi_id,dk.kpi_lib_id,kl.kpi_lib_id,kl.kpi_name,kl.kpi_description, dk.department_id, d.department_name,d.maphong, dk.assigned_value, dk.assigned_date, dk.due_date, dk.status_id, st.name_status_kpi, dk.progress 
FROM department_kpis dk 
INNER JOIN departments d ON dk.department_id = d.id 
INNER JOIN status_kpi st ON dk.status_id = st.id 
INNER JOIN kpi_library kl ON kl.kpi_lib_id=dk.kpi_lib_id 
WHERE dk.dept_kpi_id = :dept_kpi_id;';

$link = $this->getConnection();

if ($link) {
$stmt = $link->prepare($sql);
// Bind the parameter
$stmt->execute(['dept_kpi_id' => $dept_kpi_id]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($result)) {
$firstRow = $result[0];
echo '<div class="row container">
    <div class="col-lg-12">
        <h1 class="page-header">Quản lý KPI nhân viên '.htmlspecialchars($firstRow['department_name']).'</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">

        <h3>Thông tin KPI</h3>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 60px;">STT</th>
                    <th style="width: 30px;">Mã phòng ban</th>
                    <th style="width: 150px;">Tên KPI</th>
                    <th style="width: 650px;">Mô tả</th>
                    <th style="width: 60px;">Giá trị mục tiêu</th>
                    <th style="width: 60px;">Giá trị thực tế</th>
                    <th style="width: 60px;">Tỷ lệ đạt(%)</th>
                    <th style="width: 150px;">Thời gian bắt đầu</th>
                    <th style="width: 150px;">Thời gian kết thúc</th>
                    <th style="width: 60px;">Trạng thái</th>
                    

                </tr>
            </thead>
            <tbody>';
$dem=1;
                foreach ($result as $row) {
                $dept_kpi_id = htmlspecialchars($row['dept_kpi_id']); 
                $maphong = htmlspecialchars($row['maphong']);// Corrected the index to 'id'
                $name = htmlspecialchars($row['kpi_name']);
                $description = htmlspecialchars($row['kpi_description']);
                $assigned_value = htmlspecialchars($row['assigned_value']);
                $assigned_date = htmlspecialchars($row['assigned_date']);
                $due_date = htmlspecialchars($row['due_date']);
                $name_status_kpi = htmlspecialchars($row['name_status_kpi']); // Ensure the alias matches
                $progress = htmlspecialchars($row['progress']);
                    $tiendo = round(($progress / $assigned_value) * 100, 2);

                echo '<tr>
                    <td>'.$dem.'</td>
                    <td>'.$maphong.'</td>
                    <td>'.$name.'</td>
                    <td>'.$description.'</td>
                    <td>'.$assigned_value.'</td>
                    <td>'.$progress.'</td>
                    <td>'.$tiendo.'%</td>
                    <td>'.$assigned_date.'</td>
                    <td>'.$due_date.'</td>
                    <td>'.$name_status_kpi.'</td>
                    
                </tr>';$dem++;
                }

                echo '</tbody>
        </table>
    </div>
</div>';
} else {
echo 'Không có dữ liệu';
}
} else {
echo 'Không thể kết nối đến cơ sở dữ liệu';
}
}
    public function loadekpi($dept_kpi_id) {
        $sql = "SELECT ek.emp_kpi_id, ek.dept_kpi_id, ek.kpi_personal_id, ek.user_id, ek.assigned_value,
         ek.assigned_date, ek.due_date, ek.status_id, sk.name_status_kpi, ek.progress, u.full_name, 
         kp.kpi_lib_id, kp.kpi_name, kp.kpi_description,ek.link_web
         FROM employee_kpis ek 
         INNER JOIN department_kpis dk ON dk.dept_kpi_id = ek.dept_kpi_id 
         INNER JOIN users u ON u.id = ek.user_id 
         INNER JOIN kpi_personal kp ON kp.id = ek.kpi_personal_id 
         INNER JOIN status_kpi sk ON sk.id = ek.status_id 
         WHERE ek.dept_kpi_id = :dept_kpi_id";

        try {
            $link = $this->getConnection();
            
            if ($link) {
                $stmt = $link->prepare($sql);
                $stmt->execute(['dept_kpi_id' => $dept_kpi_id]); 
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo '<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Tên nhân viên</th>
                                <th>link</th>
                                <th>Tên KPI</th>
                                <th>Mô tả</th>
                                <th>Giá trị được giao</th>
                                <th>Ngày giao</th>
                                <th>Ngày hết hạn</th>
                                <th>Trạng thái</th>
                                <th>Giá trị thực tế</th>
                                <th>Tiến độ</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                if ($result && count($result) > 0) {
                    foreach ($result as $row) {
                        // Tính tỷ lệ phần trăm
                        $completion_rate = ($row['assigned_value'] > 0) ? 
                            round(($row['progress'] / $row['assigned_value']) * 100, 2) : 0;
                        
                        echo '<tr>
                                <td>' . htmlspecialchars($row['full_name']) . '</td>
                                <td>' . htmlspecialchars($row['link_web']) . '</td>
                                <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                                <td>' . htmlspecialchars($row['kpi_description']) . '</td>
                                <td>' . htmlspecialchars($row['assigned_value']) . '</td>
                                <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                                <td>' . htmlspecialchars($row['due_date']) . '</td>
                                <td>' . htmlspecialchars($row['name_status_kpi']) . '</td>
                                <td>' . htmlspecialchars($row['progress']) . '</td>
                                <td>' . $completion_rate . '%</td>
                               
                                <td> 
                                 <button type="button" 
                                        class="btn btn-primary btn-sm edit-btn my-2" data-toggle="modal"
                    data-target="#Suakpi"
                                        data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '"
                                        data-assigned-value="' . htmlspecialchars($row['assigned_value']) . '"
                                        data-due-date="' . htmlspecialchars($row['due_date']) . '">
                                    Sửa KPI nhân viên
                                </button>
                                <button type="button" 
                                        class="btn btn-danger btn-sm delete-btn my-2" 
                                        data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '">
                                    Xóa KPI nhân viên
                                </button>
                                
                                <button type="button" 
                                            class="btn btn-primary btn-sm my-2" 
                                            data-toggle="modal"
                                            data-target="#selectProgressModal"
                                            data-empkpiid="' . htmlspecialchars($row['emp_kpi_id']) . '"
                                           >
                                        Xem tiến độ
                                    </button></td>
                            </tr>';
                    }
                } else {
                    echo '<tr><td colspan="10" class="text-center">Không có dữ liệu</td></tr>';
                }
                
                echo '</tbody>
                    </table>';
            } else {
                echo '<div class="alert alert-danger">Không thể kết nối đến cơ sở dữ liệu</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
        }
    }
    public function loadeallkpi($department_id) {
        $sql = "SELECT ek.emp_kpi_id, ek.dept_kpi_id, ek.kpi_personal_id, ek.user_id, ek.assigned_value,
                 ek.assigned_date, ek.due_date, ek.status_id, sk.name_status_kpi, ek.progress, u.full_name, 
                 kp.kpi_lib_id, kp.kpi_name, kp.kpi_description 
                 FROM employee_kpis ek 
                 INNER JOIN department_kpis dk ON dk.dept_kpi_id = ek.dept_kpi_id 
                 INNER JOIN users u ON u.id = ek.user_id 
                 INNER JOIN kpi_personal kp ON kp.id = ek.kpi_personal_id 
                 INNER JOIN status_kpi sk ON sk.id = ek.status_id 
                 WHERE dk.department_id = :department_id";

        try {
            $link = $this->getConnection();
            
            if ($link) {
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT);
                $stmt->execute();
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                echo '<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Tên nhân viên</th>
                                <th>Tên KPI</th>
                                <th>Mô tả</th>
                                <th>Giá trị được giao</th>
                                <th>Ngày giao</th>
                                <th>Ngày hết hạn</th>
                                <th>Trạng thái</th>
                                <th>Giá trị thực tế</th>
                                <th>Tiến độ</th>
                                <th style="width:250px;">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                if ($result && count($result) > 0) {
                    foreach ($result as $row) {
                        // Tính tỷ lệ phần trăm
                        $completion_rate = ($row['assigned_value'] > 0) ? 
                            round(($row['progress'] / $row['assigned_value']) * 100, 2) : 0;
                        
                        echo '<tr>
                                <td>' . htmlspecialchars($row['full_name']) . '</td>
                                <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                                <td>' . htmlspecialchars($row['kpi_description']) . '</td>
                                <td>' . htmlspecialchars($row['assigned_value']) . '</td>
                                <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                                <td>' . htmlspecialchars($row['due_date']) . '</td>
                                <td>' . htmlspecialchars($row['name_status_kpi']) . '</td>
                                <td>' . htmlspecialchars($row['progress']) . '</td>
                                <td>' . $completion_rate . '%</td>
                                <td> 
                                    <button type="button" 
                                            class="btn btn-primary btn-sm edit-btn mt-5" data-toggle="modal"
                                            data-target="#Suakpi"
                                            data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '"
                                            data-assigned-value="' . htmlspecialchars($row['assigned_value']) . '"
                                            data-due-date="' . htmlspecialchars($row['due_date']) . '">
                                        Sửa
                                    </button>
                                    <button type="button" 
                                            class="btn btn-danger btn-sm delete-btn mt-5" 
                                            data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '">
                                        Xóa
                                    </button>
                                    
                                    <button type="button" 
                                            class="btn btn-primary btn-sm mt-5" 
                                            data-toggle="modal"
                                            data-target="#selectProgressModal"
                                            data-empkpiid="' . htmlspecialchars($row['emp_kpi_id']) . '">
                                        Xem tiến độ
                                    </button>
                                </td>
                            </tr>';
                    }
                } else {
                    echo '<tr><td colspan="10" class="text-center">Không có dữ liệu</td></tr>';
                }
                
                echo '</tbody>
                    </table>';
            } else {
                echo '<div class="alert alert-danger">Không thể kết nối đến cơ sở dữ liệu</div>';
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
        }
    }
public function loadKPIByEmployeeAndTime($department_id, $user_id, $month, $year) {
    $sql = "SELECT ek.emp_kpi_id, ek.dept_kpi_id, ek.kpi_personal_id, ek.user_id, ek.assigned_value,
             ek.assigned_date, ek.due_date, ek.status_id, sk.name_status_kpi, ek.progress, u.full_name, 
             kp.kpi_lib_id, kp.kpi_name, kp.kpi_description 
             FROM employee_kpis ek 
             INNER JOIN department_kpis dk ON dk.dept_kpi_id = ek.dept_kpi_id 
             INNER JOIN users u ON u.id = ek.user_id 
             INNER JOIN kpi_personal kp ON kp.id = ek.kpi_personal_id 
             INNER JOIN status_kpi sk ON sk.id = ek.status_id 
             WHERE dk.department_id = :department_id";

    // Add filters based on the provided parameters
    if ($user_id) {
        $sql .= " AND ek.user_id = :user_id";
    }
    if ($month) {
        $sql .= " AND MONTH(ek.assigned_date) = :month";
    }
    if ($year) {
        $sql .= " AND YEAR(ek.assigned_date) = :year";
    }

    try {
        $link = $this->getConnection();
        
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':department_id', $department_id, PDO::PARAM_INT); // Bind department_id
            
            // Bind user_id, month, and year if they are set
            if ($user_id) {
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            }
            if ($month) {
                $stmt->bindParam(':month', $month, PDO::PARAM_INT);
            }
            if ($year) {
                $stmt->bindParam(':year', $year, PDO::PARAM_INT);
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Output the results in a table
            echo '<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Tên nhân viên</th>
                            <th>Tên KPI</th>
                            <th>Mô tả</th>
                            <th>Giá trị được giao</th>
                            <th>Ngày giao</th>
                            <th>Ngày hết hạn</th>
                            <th>Trạng thái</th>
                            <th>Giá trị thực tế</th>
                            <th>Tiến độ</th>
                            <th style="width:250px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            if ($result && count($result) > 0) {
                foreach ($result as $row) {
                    // Calculate completion rate
                    $completion_rate = ($row['assigned_value'] > 0) ? 
                        round(($row['progress'] / $row['assigned_value']) * 100, 2) : 0;
                    
                    echo '<tr>
                            <td>' . htmlspecialchars($row['full_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_name']) . '</td>
                            <td>' . htmlspecialchars($row['kpi_description']) . '</td>
                            <td>' . htmlspecialchars($row['assigned_value']) . '</td>
                            <td>' . htmlspecialchars($row['assigned_date']) . '</td>
                            <td>' . htmlspecialchars($row['due_date']) . '</td>
                            <td>' . htmlspecialchars($row['name_status_kpi']) . '</td>
                            <td>' . htmlspecialchars($row['progress']) . '</td>
                            <td>' . $completion_rate . '%</td>
                            <td> 
                                <button type="button" 
                                        class="btn btn-primary btn-sm edit-btn mt-5" data-toggle="modal"
                                        data-target="#Suakpi"
                                        data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '"
                                        data-assigned-value="' . htmlspecialchars($row['assigned_value']) . '"
                                        data-due-date="' . htmlspecialchars($row['due_date']) . '">
                                    Sửa
                                </button>
                                <button type="button" 
                                        class="btn btn-danger btn-sm delete-btn mt-5" 
                                        data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '">
                                    Xóa
                                </button>
                                   <button type="button" 
                                            class="btn btn-primary btn-sm mt-5" 
                                            data-toggle="modal"
                                            data-target="#selectProgressModal"
                                            data-empkpiid="' . htmlspecialchars($row['emp_kpi_id']) . '">
                                        Xem tiến độ
                                    </button>
                            </td>
                        </tr>';
                }
            } else {
                echo '<tr><td colspan="10" class="text-center">Không có dữ liệu</td></tr>';
            }
            
            echo '</tbody>
                </table>';
        } else {
            echo '<div class="alert alert-danger">Không thể kết nối đến cơ sở dữ liệu</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
    }
}
public function addEmployeeKPI1($dept_kpi_id, $kpi_personal_id, $user_id,$due_date, $assigned_value,$assigned_date) {
        $sql = "INSERT INTO employee_kpis (dept_kpi_id, kpi_personal_id, user_id, assigned_value, assigned_date, due_date, status_id, progress, last_update
) 
                VALUES (:dept_kpi_id, :kpi_personal_id, :user_id, :assigned_value,  :assigned_date, :due_date, 1, 0, NOW())";

        try {
            $link = $this->getConnection();
            
            if ($link) {
                $stmt = $link->prepare($sql);
                
                // Set the due date as needed, for example, 30 days from now
                

                // Bind parameters
                $stmt->bindParam(':dept_kpi_id', $dept_kpi_id, PDO::PARAM_INT);
                $stmt->bindParam(':kpi_personal_id', $kpi_personal_id, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':assigned_value', $assigned_value, PDO::PARAM_STR);
                $stmt->bindParam(':assigned_date', $assigned_date, PDO::PARAM_STR);
                $stmt->bindParam(':due_date', $due_date, PDO::PARAM_STR);
             
                // Execute the statement
                $stmt->execute();
                return $link->lastInsertId(); // Return the ID of the newly inserted KPI
            } else {
                throw new Exception('Cannot connect to the database');
            }
       
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
        }
    }
public function addEmployeeKPI($dept_kpi_id, $kpi_personal_id, $user_id, $due_date, $assigned_value, $assigned_date, $kpi_link) {
    $sql = "INSERT INTO employee_kpis (dept_kpi_id, kpi_personal_id, user_id, assigned_value, assigned_date, due_date, status_id, progress, last_update,link_web) 
                VALUES (:dept_kpi_id, :kpi_personal_id, :user_id, :assigned_value, :assigned_date, :due_date, 1, 0, NOW(),:link)";

        try {
            $link = $this->getConnection();
            
            if ($link) {
                $stmt = $link->prepare($sql);
                
                // Set the due date as needed, for example, 30 days from now
                

                // Bind parameters
                $stmt->bindParam(':dept_kpi_id', $dept_kpi_id, PDO::PARAM_INT);
                $stmt->bindParam(':kpi_personal_id', $kpi_personal_id, PDO::PARAM_INT);
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->bindParam(':assigned_value', $assigned_value, PDO::PARAM_STR);
                $stmt->bindParam(':assigned_date', $assigned_date, PDO::PARAM_STR);
                $stmt->bindParam(':due_date', $due_date, PDO::PARAM_STR);
                $stmt->bindParam(':link', $kpi_link, PDO::PARAM_STR);
                // Execute the statement
                $stmt->execute();
                return $link->lastInsertId(); // Return the ID of the newly inserted KPI
            } else {
                throw new Exception('Cannot connect to the database');
            }
       
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
        }
}


    public function updateEmployeeKPI($emp_kpi_id, $assigned_value, $due_date) {
        $sql = "UPDATE employee_kpis 
                SET assigned_value = :assigned_value, due_date = :due_date, last_update = NOW() 
                WHERE emp_kpi_id = :emp_kpi_id";

        try {
            $link = $this->getConnection();
            
            if ($link) {
                $stmt = $link->prepare($sql);
                $stmt->bindParam(':emp_kpi_id', $emp_kpi_id, PDO::PARAM_INT);
                $stmt->bindParam(':assigned_value', $assigned_value, PDO::PARAM_STR);
                $stmt->bindParam(':due_date', $due_date, PDO::PARAM_STR);
                
                $stmt->execute();
                return $stmt->rowCount() > 0 ? 1 : 0; // Return 1 if update was successful, otherwise 0
            } else {
                throw new Exception('Cannot connect to the database');
            }
        } catch (PDOException $e) {
            echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
            return 0; // Indicate an error occurred
        } catch (Exception $e) {
            echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
            return 0; // Indicate an error occurred
        }
    }

   public function deleteEmployeeKPI($emp_kpi_id) {
    // First, check the progress of the KPI
    $sql = "SELECT progress FROM employee_kpis WHERE emp_kpi_id = :emp_kpi_id";
    
    try {
        $link = $this->getConnection();
        
        if ($link) {
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':emp_kpi_id', $emp_kpi_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Check if the KPI exists and its progress
            if ($row) {
                $progress = $row['progress'];
                
                if ($progress > 0) {
                    // If progress is greater than 0, return an error message
                    return 0; // Indicate that deletion is not allowed
                } else {
                    // Proceed to delete the KPI
                    $deleteSql = "DELETE FROM employee_kpis WHERE emp_kpi_id = :emp_kpi_id";
                    $deleteStmt = $link->prepare($deleteSql);
                    $deleteStmt->bindParam(':emp_kpi_id', $emp_kpi_id, PDO::PARAM_INT);
                    $deleteStmt->execute();
                    return $deleteStmt->rowCount(); // Return the number of affected rows
                }
            } else {
                // KPI not found
                return 0; // Indicate that the KPI does not exist
            }
        } else {
            throw new Exception('Cannot connect to the database');
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
        return 0; // Indicate an error occurred
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">Lỗi: ' . $e->getMessage() . '</div>';
        return 0; // Indicate an error occurred
    }
}
public function get_kpi_progress_logs($emp_kpi_id) {
        $sql = "SELECT kl.emp_kpi_id, kl.progress_update, kl.result_detail, kl.updated_at 
                FROM kpi_progress_logs kl 
                INNER JOIN employee_kpis ek ON ek.emp_kpi_id = kl.emp_kpi_id 
                WHERE
                kl.emp_kpi_id=:emp_kpi_id
                ORDER BY kl.updated_at DESC";

        try {
            $link = $this->getConnection();
            $stmt = $link->prepare($sql);
            $stmt->bindParam(':emp_kpi_id', $emp_kpi_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            echo "Lỗi: " . $e->getMessage();
            return [];
        }
    }
}
    ?>