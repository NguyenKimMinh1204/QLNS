<?php
include '../../db/connetion.php';


class evaluetion_manager extends Database{
      public function getDepartmentByUserId($user_id) {

    $link = $this->getConnection();

    // Sửa lại câu SQL để loại bỏ dấu "=" thừa
    $stmt = $link->prepare("SELECT u.department_id FROM users u WHERE u.id = :user_id");

    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

   
    return $row ? $row['department_id'] : null;
}
public function getEvaluationIdByEmpKpiId($emp_kpi_id) {
    // SQL query to get the id based on dep_kpi_id
    $sql = 'SELECT id FROM manager_evaluation WHERE emp_kpi_id = :emp_kpi_id';

    // Get the database connection
    $link = $this->getConnection();

    if ($link) {
        // Prepare the statement
        $stmt = $link->prepare($sql);

        // Bind the parameter
        $stmt->bindValue(':emp_kpi_id', $emp_kpi_id, PDO::PARAM_INT);

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
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>';
                
                if ($result && count($result) > 0) {
                    foreach ($result as $row) {
                        $evaluationId = $this->getEvaluationIdByEmpKpiId($row['emp_kpi_id']);
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
                                <td>';
                             if ($evaluationId > 0) {
                                echo '<button class="btn btn-primary" data-toggle="modal" data-target="#editEvaluationModal" 
                                        data-evaluation-id="' . $evaluationId . '" data-dept-kpi-id="' . htmlspecialchars($row['dept_kpi_id']) . '">Sửa đánh giá</button>';
                            } else {
                                echo '<button class="btn btn-success" data-toggle="modal" data-target="#evaluationModal" 
                                        data-dept-kpi-id="' . htmlspecialchars($row['dept_kpi_id']) . '">Thêm đánh giá</button>';
                            }
                            echo '</td>
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
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            if ($result && count($result) > 0) {
                foreach ($result as $row) {
                    $evaluationId = $this->getEvaluationIdByEmpKpiId($row['emp_kpi_id']);
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
                            <td>';
                             if ($evaluationId > 0) {
                                $comment=$this->getCommentsById($row['emp_kpi_id']);
                                echo '<button class="btn btn-primary" data-toggle="modal" data-target="#editEvaluationModal1" 
                                         data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '" data-comment="'.$comment.'" 
                                         
                                         data-user-id="'.htmlspecialchars($row['user_id']) .'">Sửa đánh giá</button>';
                            } else {
                                echo '<button class="btn btn-success" data-toggle="modal" data-target="#evaluationModal1" 
                                        data-emp-kpi-id="' . htmlspecialchars($row['emp_kpi_id']) . '" data-user-id="'.htmlspecialchars($row['user_id']) .'">Thêm đánh giá</button>';
                            }
                            echo '</td>
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
public function getCommentsById($id) {
    // SQL query to get comments by id
    $sql = 'SELECT comments 
            FROM manager_evaluation 
            WHERE id = :id';

    // Get the database connection
    $link = $this->getConnection();

    if ($link) {
        // Prepare the statement
        $stmt = $link->prepare($sql);

        // Bind the ID parameter
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Execute the query
        $stmt->execute();

        // Fetch the result
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Return the comments field
        return $result ? $result['comments'] : null;
    } else {
        // If no connection, return null
        return null;
    }
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
public function them_danh_gia_quan_ly($user_id, $manager_id, $emp_kpi_id, $comments) {
    $dbh = $this->getConnection();
    $dbh->beginTransaction();
    try {
        // Thêm đánh giá vào bảng manager_evaluation
        $stmt = $dbh->prepare("
            INSERT INTO manager_evaluation 
            (user_id, manager_id, emp_kpi_id, comments, evaluation_date)
            VALUES (:user_id, :manager_id, :emp_kpi_id, :comments, NOW())
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':manager_id', $manager_id);
        $stmt->bindParam(':emp_kpi_id', $emp_kpi_id);
        $stmt->bindParam(':comments', $comments);
        $stmt->execute();

        // Lấy ID của đánh giá vừa thêm
        $evaluation_id = $dbh->lastInsertId();

        // Cập nhật bảng employee_kpis
        $stmt = $dbh->prepare("
            UPDATE employee_kpis 
            SET id_evaluation = :evaluation_id 
            WHERE emp_kpi_id = :emp_kpi_id
        ");
        $stmt->bindParam(':evaluation_id', $evaluation_id);
        $stmt->bindParam(':emp_kpi_id', $emp_kpi_id);
        $stmt->execute();

        $dbh->commit();
        return true;
    } catch (Exception $e) {
        $dbh->rollBack();
        return false;
    }
}
public function cap_nhat_danh_gia_quan_ly($id, $comments) {
    $dbh = $this->getConnection();
    $stmt = $dbh->prepare("
        UPDATE manager_evaluation 
        SET comments = :comments, evaluation_date = NOW() 
        WHERE id = :id
    ");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':comments', $comments);
    return $stmt->execute() ? 1 : 0; // Trả về 1 nếu thành công, 0 nếu thất bại
}
public function xoa_danh_gia_quan_ly($id) {
    $dbh = $this->getConnection();
    $dbh->beginTransaction();
    try {
        // Xóa đánh giá khỏi bảng manager_evaluation
        $stmt = $dbh->prepare("DELETE FROM manager_evaluation WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Cập nhật bảng employee_kpis, xóa liên kết đánh giá
        $stmt = $dbh->prepare("
            UPDATE employee_kpis 
            SET id_evaluation = NULL 
            WHERE id_evaluation = :id
        ");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $dbh->commit();
        return 1;
    } catch (Exception $e) {
        $dbh->rollBack();
        return 0;
    }
}

}
?>