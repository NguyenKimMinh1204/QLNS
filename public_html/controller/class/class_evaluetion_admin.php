<?php
include '../../db/connetion.php';


class evaluetion extends Database{

 public function getDepartments() {
        $link = $this->getConnection();
        $stmt = $link->prepare("SELECT * FROM departments");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                    $evaluationId = $this->getEvaluationIdByDepKpiId($row['dept_kpi_id']);
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
                            <td>';
                    // Kiểm tra và hiển thị modal phù hợp
                if ($evaluationId > 0) {
                    $comment=$this->getCommentsById($evaluationId);
                    echo '<button class="btn btn-primary" data-toggle="modal" data-target="#editEvaluationModal" 
                            data-evaluation-id="' . $evaluationId . '"data-commment="'.$comment.'"  data-dept-kpi-id="' . htmlspecialchars($row['dept_kpi_id']) . '">Sửa đánh giá</button>';
                } else {
                    echo '<button class="btn btn-success" data-toggle="modal" data-target="#evaluationModal" 
                            data-dept-kpi-id="' . htmlspecialchars($row['dept_kpi_id']) . '">Thêm đánh giá</button>';
                }

                echo '</td>
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
// Thêm bản ghi vào bảng director_evaluation
public function them_danh_gia($user_id, $dep_kpi_id, $comments) {
    $dbh = $this->getConnection();
    $dbh->beginTransaction();
    try {
        // Thêm đánh giá vào bảng director_evaluation
        $stmt = $dbh->prepare("
            INSERT INTO director_evaluation 
            (user_id, dep_kpi_id, comments, evaluation_date) 
            VALUES (:user_id, :dep_kpi_id, :comments, NOW())
        ");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':dep_kpi_id', $dep_kpi_id);
        $stmt->bindParam(':comments', $comments);
        $stmt->execute();
        
        // Lấy ID của đánh giá vừa thêm
        $evaluation_id = $dbh->lastInsertId();

        // Cập nhật bảng department_kpis
        $stmt = $dbh->prepare("
            UPDATE department_kpis 
            SET id_evaluation = :evaluation_id 
            WHERE dept_kpi_id = :dep_kpi_id
        ");
        $stmt->bindParam(':evaluation_id', $evaluation_id);
        $stmt->bindParam(':dep_kpi_id', $dep_kpi_id);
        $stmt->execute();

        $dbh->commit();
        return true;
    } catch (Exception $e) {
        $dbh->rollBack();
        return false;
    }
}

// Cập nhật bản ghi trong bảng director_evaluation
public function cap_nhat_danh_gia($id, $user_id, $comments) {
    $dbh = $this->getConnection();
    $stmt = $dbh->prepare("
        UPDATE director_evaluation 
        SET 
            user_id = :user_id, 
            comments = :comments, 
            evaluation_date = NOW()
        WHERE id = :id
    ");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':comments', $comments);
    return $stmt->execute() ? 1 : 0; // Trả về 1 nếu thành công, 0 nếu thất bại
}

// Xóa bản ghi trong bảng director_evaluation
public function xoa_danh_gia($id) {
    $dbh = $this->getConnection();
    $dbh->beginTransaction();
    try {
        // Xóa đánh giá khỏi bảng director_evaluation
        $stmt = $dbh->prepare("DELETE FROM director_evaluation WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Cập nhật bảng department_kpis, xóa liên kết đánh giá
        $stmt = $dbh->prepare("
            UPDATE department_kpis 
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
public function getAllEvaluations() {
    // SQL query to get all evaluations
    $sql = 'SELECT id, user_id, dep_kpi_id, comments, evaluation_date FROM director_evaluation';

    // Get the database connection
    $link = $this->getConnection();

    if ($link) {
        // Prepare the statement
        $stmt = $link->prepare($sql);

        // Execute the query
        $stmt->execute();

        // Fetch all results
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Return the results
        return $result;
    } else {
        // If no connection, return an empty array
        return [];
    }
}
public function getCommentsById($id) {
    // SQL query to get comments by id
    $sql = 'SELECT comments 
            FROM director_evaluation 
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





}
?>