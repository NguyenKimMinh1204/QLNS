<?php
include '../../db/connetion.php';


class kpi_dep extends Database{
//danh sách kpi phòng ban
    public function loadKPIDepartment()
{
    $sql = 'SELECT 
    kd.id AS kpi_id, 
    kd.name, 
    kd.description, 
    d.id AS department_id, 
    d.department_name, 
    d.maphong
FROM 
    kpi_department kd
JOIN 
    departments d 
ON 
    kd.department_id = d.id ORDER BY kd.id ASC';
    $link = $this->getConnection();
    
    // Check connection
    if ($link) {
        // Execute query
        $stmt = $link->prepare($sql);
        $stmt->execute();

        // Fetch all data
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = count($result);
        
        if ($count > 0) {
            echo '<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Mã KPI</th>
                            <th style="width: 30px;">Mã phòng ban</th>
                            <th style="width: 150px;">Tên KPI</th>
                            <th style="width: 650px;">Mô tả</th>
                            <th style="width: 150px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>';
            
            foreach ($result as $row) {
                $id = $row['kpi_id'];
                $name = $row['name'];
                $description = $row['description'];
                $maphong = $row['maphong'];
                
                echo '                
                    <tr>
                        <td>' . htmlspecialchars($id) . '</td>
                        <td>' . htmlspecialchars($maphong) . '</td>
                        <td>' . htmlspecialchars($name) . '</td>
                        <td>' . htmlspecialchars($description) . '</td>                       
                       
                   
                       
                            <td>
                            <button class="btn btn-primary" data-toggle="modal" data-target="#editKPIDepartmentModal" data-id="' . htmlspecialchars($id) . '" data-name="' . htmlspecialchars($name) . '" data-description="' . htmlspecialchars($description) . '" >Edit</button>
                            <button class="btn btn-danger delete-kpi-btn" data-id="' . htmlspecialchars($id) . '">Delete</button>
                        </td>
                    </tr>';
            }

            echo '</tbody>
                </table>';
        } else {
            echo 'Không có dữ liệu';
        }
    } else {
        echo 'Không thể kết nối đến cơ sở dữ liệu';
    }
}
//danh sách kpi của phòng ban mà trưởng phòng nhận được
public function loadKPIDepartment_m($department_id) {
    $sql = 'SELECT 
        kd.id AS kpi_id, 
        kd.name, 
        kd.description, 
        d.id AS department_id, 
        d.department_name, 
        d.maphong
    FROM 
        kpi_department kd
    JOIN 
        departments d 
    ON 
        kd.department_id = d.id 
    WHERE 
        kd.department_id = :department_id 
    ORDER BY 
        kd.id ASC';
    
    $link = $this->getConnection();

    if ($link) {
        $stmt = $link->prepare($sql);
        $stmt->execute(['department_id' => $department_id]); // Bind the parameter
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!empty($result)) {
            echo '<table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Mã KPI</th>
                            <th style="width: 30px;">Mã phòng ban</th>
                            <th style="width: 150px;">Tên KPI</th>
                            <th style="width: 650px;">Mô tả</th>
                            <th style="width: 150px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>';

            foreach ($result as $row) {
                $id = htmlspecialchars($row['kpi_id']);
                $department_id = htmlspecialchars($row['department_id']);
                $name = htmlspecialchars($row['name']);
                $description = htmlspecialchars($row['description']);
                
                $maphong = htmlspecialchars($row['maphong']);
                
                echo '<tr>
                        <td>'.$id.'</td>
                        <td>'.$maphong.'</td>
                        <td>'.$name.'</td>
                        <td>'.$description.'</td>
                      
                        <td>
                        <a href="giaokpi.php?kpi_id='.$id.'&department_id='.$department_id.'">
                        <button class="btn btn-primary">Giao KPI</button>
                        </a>


</td>
</tr>';
}

echo '</tbody>
</table>';
} else {
echo 'Không có dữ liệu'; // No data found
}
} else {
echo 'Không thể kết nối đến cơ sở dữ liệu'; // Connection error
}
}


public function loadKPI_Personal() {
// $dbh = $this->getConnection();
$sql = '
SELECT u.full_name ,k.user_id,k.id,k.kpi_dep_id, k.kpi_name, k.description, k.date_start, k.date_end, k.target,
k.actual, k.priority, s.name_status_kpi FROM kpi_personal AS k JOIN users AS u ON k.user_id = u.id JOIN status_kpi AS s
ON k.status = s.id ORDER BY k.id ASC;
';

$link = $this->getConnection();

if ($link) {
$stmt = $link->prepare($sql);
$stmt->execute(); // Bind the parameter
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($result)) {
echo '<div class="bootstrap-table">
    <div class="fixed-table-toolbar"><button class="btn btn-success" data-toggle="modal"
            data-target="#assignKPIModal">Assign KPI</button>
        <div class="columns btn-group pull-right">

            <button class="btn btn-default" type="button" name="refresh" title="Refresh"><i
                    class="glyphicon glyphicon-refresh icon-refresh"></i></button><button class="btn btn-default"
                type="button" name="toggle" title="Toggle"><i
                    class="glyphicon glyphicon glyphicon-list-alt icon-list-alt"></i></button>
            <div class="keep-open btn-group" title="Columns">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-th icon-th"></i> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><label><input type="checkbox" data-field="name_user" value="1" checked="checked"> name
                            user</label></li>
                    <li><label><input type="checkbox" data-field="name_kpi_nv" value="2" checked="checked"> name
                            KPI</label></li>
                    <li><label><input type="checkbox" data-field="description" value="3" checked="checked">
                            description</label></li>
                    <li><label><input type="checkbox" data-field="start_date" value="4" checked="checked"> start
                            date</label></li>
                    <li><label><input type="checkbox" data-field="end_date" value="5" checked="checked"> end date
                        </label></li>
                    <li><label><input type="checkbox" data-field="target" value="6" checked="checked"> target </label>
                    </li>
                    <li><label><input type="checkbox" data-field="actual" value="7" checked="checked"> actual </label>
                    </li>
                    <li><label><input type="checkbox" data-field="priority" value="8" checked="checked"> priority
                        </label></li>
                    <li><label><input type="checkbox" data-field="status" value="9" checked="checked"> status </label>
                    </li>
                </ul>
            </div>
        </div>
        <div class="pull-right search"><input class="form-control" type="text" placeholder="Search">
        </div>
    </div>
    <div class="fixed-table-container">
        <div class="fixed-table-header">
            <table></table>
        </div>
        <div class="fixed-table-body">
            <div class="fixed-table-loading" style="top: 37px; display: none;">Loading, please wait…
            </div>
            <table data-toggle="table" data-url="tables/data1.json" data-show-refresh="true" data-show-toggle="true"
                data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true"
                data-sort-name="name" data-sort-order="desc" class="table table-hover">
                <thead>
                    <tr>
                        <th class="bs-checkbox " style="width: 36px; ">
                            <div class="th-inner "><input name="btSelectAll" type="checkbox"></div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">name user<span class="order"><span class="caret"
                                        style="margin: 10px 5px;"></span></span></div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">name kpi<span class="order"><span class="caret"
                                        style="margin: 10px 5px;"></span></span></div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">description</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">time start</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">time end</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">target</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">actual</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">priority</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">status</div>
                            <div class="fht-cell"></div>
                        </th>
                    </tr>
                </thead>
                <tbody>';

                    foreach ($result as $row) {
                    $id = htmlspecialchars($row['id']);
                    $user_id = htmlspecialchars($row['user_id']);
                    $full_name = htmlspecialchars($row['full_name']);
                    $kpi_name = htmlspecialchars($row['kpi_name']);
                    $description = htmlspecialchars($row['description']);
                    $date_start = htmlspecialchars($row['date_start']);
                    $date_end = htmlspecialchars($row['date_end']);
                    $target = htmlspecialchars($row['target']);
                    $actual = htmlspecialchars($row['actual']);
                    $priority = htmlspecialchars($row['priority']);
                    $status_name = htmlspecialchars($row['name_status_kpi']);

                    echo '<tr data-index="0">
                        <td class="bs-checkbox"><input data-index="0" name="toolbar1" type="checkbox"></td>
                        <td style="">'.$full_name.'</td>
                        <td style="">'.$kpi_name.'</td>
                        <td style="">'.$description.'</td>
                        <td style="">'.$date_start.'</td>
                        <td style="">'.$date_end.'</td>
                        <td style="">'.$target.'</td>
                        <td style="">'.$actual.'</td>
                        <td style="">'.$priority.'</td>
                        <td style="">'.$status_name.'</td>
                        <td>
                            <!-- Edit Button with Additional Data Attributes -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#editKPIPersonalModal"
                                data-id="' . $id . '" data-user_id="' . $user_id . '" data-kpi_name="' . $kpi_name . '"
                                data-description="' . $description . '" data-target="' . $target . '"
                                data-priority="' . $priority . '">Edit</button>

                            <!-- Delete Button -->
                            <button class="btn btn-danger delete-btn" data-id="' . $id . '">Delete</button>
                        </td>
                    </tr>
                    <tr>


                    </tr>';
                    }

                    echo '
                </tbody>
            </table>
        </div>
        <div class="fixed-table-pagination">
            <div class="pull-left pagination-detail"><span class="pagination-info">Showing 1 to 10
                    of 21 rows</span><span class="page-list"><span class="btn-group dropup"><button type="button"
                            class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span
                                class="page-size">10</span> <span class="caret"></span></button>
                        <ul class="dropdown-menu" role="menu">
                            <li class="active"><a href="javascript:void(0)">10</a></li>
                            <li><a href="javascript:void(0)">25</a></li>
                            <li><a href="javascript:void(0)">50</a></li>
                            <li><a href="javascript:void(0)">100</a></li>
                        </ul>
                    </span> records per page</span></div>
            <div class="pull-right pagination">
                <ul class="pagination">
                    <li class="page-first disabled"><a href="javascript:void(0)">&lt;&lt;</a></li>
                    <li class="page-pre disabled"><a href="javascript:void(0)">&lt;</a></li>
                    <li class="page-number active disabled"><a href="javascript:void(0)">1</a></li>
                    <li class="page-number"><a href="javascript:void(0)">2</a></li>
                    <li class="page-number"><a href="javascript:void(0)">3</a></li>
                    <li class="page-next"><a href="javascript:void(0)">&gt;</a></li>
                    <li class="page-last"><a href="javascript:void(0)">&gt;&gt;</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>';
}else{
echo 'KPI chưa có người làm';
}
} else {
echo 'Không thể thực thi truy vấn';
}
}

public function loadKPI_Personal_task_kpi_dep($kpi_dep_id) {
// $dbh = $this->getConnection();
$sql = '
SELECT u.full_name ,k.user_id,k.id,k.kpi_dep_id, k.kpi_name, k.description, k.date_start, k.date_end, k.target,
k.actual, k.priority, s.name_status_kpi FROM kpi_personal AS k JOIN users AS u ON k.user_id = u.id JOIN status_kpi AS s
ON k.status = s.id WHERE k.kpi_dep_id=:kpi_dep_id ORDER BY k.id ASC;
';

$link = $this->getConnection();

if ($link) {
$stmt = $link->prepare($sql);
$stmt->execute(['kpi_dep_id' => $kpi_dep_id]); // Bind the parameter
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($result)) {
echo '<div class="bootstrap-table">
    <div class="fixed-table-toolbar"><button class="btn btn-success" data-toggle="modal"
            data-target="#assignKPIModal">Assign KPI</button>
        <div class="columns btn-group pull-right">

            <button class="btn btn-default" type="button" name="refresh" title="Refresh"><i
                    class="glyphicon glyphicon-refresh icon-refresh"></i></button><button class="btn btn-default"
                type="button" name="toggle" title="Toggle"><i
                    class="glyphicon glyphicon glyphicon-list-alt icon-list-alt"></i></button>
            <div class="keep-open btn-group" title="Columns">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-th icon-th"></i> <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><label><input type="checkbox" data-field="name_user" value="1" checked="checked"> name
                            user</label></li>
                    <li><label><input type="checkbox" data-field="name_kpi_nv" value="2" checked="checked"> name
                            KPI</label></li>
                    <li><label><input type="checkbox" data-field="description" value="3" checked="checked">
                            description</label></li>
                    <li><label><input type="checkbox" data-field="start_date" value="4" checked="checked"> start
                            date</label></li>
                    <li><label><input type="checkbox" data-field="end_date" value="5" checked="checked"> end date
                        </label></li>
                    <li><label><input type="checkbox" data-field="target" value="6" checked="checked"> target </label>
                    </li>
                    <li><label><input type="checkbox" data-field="actual" value="7" checked="checked"> actual </label>
                    </li>
                    <li><label><input type="checkbox" data-field="priority" value="8" checked="checked"> priority
                        </label></li>
                    <li><label><input type="checkbox" data-field="status" value="9" checked="checked"> status </label>
                    </li>
                </ul>
            </div>
        </div>
        <div class="pull-right search"><input class="form-control" type="text" placeholder="Search">
        </div>
    </div>
    <div class="fixed-table-container">
        <div class="fixed-table-header">
            <table></table>
        </div>
        <div class="fixed-table-body">
            <div class="fixed-table-loading" style="top: 37px; display: none;">Loading, please wait…
            </div>
            <table data-toggle="table" data-url="tables/data1.json" data-show-refresh="true" data-show-toggle="true"
                data-show-columns="true" data-search="true" data-select-item-name="toolbar1" data-pagination="true"
                data-sort-name="name" data-sort-order="desc" class="table table-hover">
                <thead>
                    <tr>
                        <th class="bs-checkbox " style="width: 36px; ">
                            <div class="th-inner "><input name="btSelectAll" type="checkbox"></div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">name user<span class="order"><span class="caret"
                                        style="margin: 10px 5px;"></span></span></div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">name kpi<span class="order"><span class="caret"
                                        style="margin: 10px 5px;"></span></span></div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">description</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">time start</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">time end</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">target</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">actual</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">priority</div>
                            <div class="fht-cell"></div>
                        </th>
                        <th style="">
                            <div class="th-inner sortable">status</div>
                            <div class="fht-cell"></div>
                        </th>
                    </tr>
                </thead>
                <tbody>';

                    foreach ($result as $row) {
                    $id = htmlspecialchars($row['id']);
                    $user_id = htmlspecialchars($row['user_id']);
                    $full_name = htmlspecialchars($row['full_name']);
                    $kpi_name = htmlspecialchars($row['kpi_name']);
                    $description = htmlspecialchars($row['description']);
                    $date_start = htmlspecialchars($row['date_start']);
                    $date_end = htmlspecialchars($row['date_end']);
                    $target = htmlspecialchars($row['target']);
                    $actual = htmlspecialchars($row['actual']);
                    $priority = htmlspecialchars($row['priority']);
                    $status_name = htmlspecialchars($row['name_status_kpi']);

                    echo '<tr data-index="0">
                        <td class="bs-checkbox"><input data-index="0" name="toolbar1" type="checkbox"></td>
                        <td style="">'.$full_name.'</td>
                        <td style="">'.$kpi_name.'</td>
                        <td style="">'.$description.'</td>
                        <td style="">'.$date_start.'</td>
                        <td style="">'.$date_end.'</td>
                        <td style="">'.$target.'</td>
                        <td style="">'.$actual.'</td>
                        <td style="">'.$priority.'</td>
                        <td style="">'.$status_name.'</td>
                        <td>
                            <!-- Edit Button with Additional Data Attributes -->
                            <button class="btn btn-primary" data-toggle="modal" data-target="#editKPIPersonalModal"
                                data-id="' . $id . '" data-user_id="' . $user_id . '" data-kpi_name="' . $kpi_name . '"
                                data-description="' . $description . '" data-target="' . $target . '"
                                data-priority="' . $priority . '">Edit</button>

                            <!-- Delete Button -->
                            <button class="btn btn-danger delete-btn" data-id="' . $id . '">Delete</button>
                        </td>
                    </tr>
                    <tr>


                    </tr>';
                    }

                    echo '
                </tbody>
            </table>
        </div>
        <div class="fixed-table-pagination">
            <div class="pull-left pagination-detail"><span class="pagination-info">Showing 1 to 10
                    of 21 rows</span><span class="page-list"><span class="btn-group dropup"><button type="button"
                            class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span
                                class="page-size">10</span> <span class="caret"></span></button>
                        <ul class="dropdown-menu" role="menu">
                            <li class="active"><a href="javascript:void(0)">10</a></li>
                            <li><a href="javascript:void(0)">25</a></li>
                            <li><a href="javascript:void(0)">50</a></li>
                            <li><a href="javascript:void(0)">100</a></li>
                        </ul>
                    </span> records per page</span></div>
            <div class="pull-right pagination">
                <ul class="pagination">
                    <li class="page-first disabled"><a href="javascript:void(0)">&lt;&lt;</a></li>
                    <li class="page-pre disabled"><a href="javascript:void(0)">&lt;</a></li>
                    <li class="page-number active disabled"><a href="javascript:void(0)">1</a></li>
                    <li class="page-number"><a href="javascript:void(0)">2</a></li>
                    <li class="page-number"><a href="javascript:void(0)">3</a></li>
                    <li class="page-next"><a href="javascript:void(0)">&gt;</a></li>
                    <li class="page-last"><a href="javascript:void(0)">&gt;&gt;</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>';
}else{
echo 'KPI chưa có người làm';
}
} else {
echo 'Không thể thực thi truy vấn';
}
}


public function getUserDepartmentId($user_id) {
    $sql = 'SELECT u.department_id
    FROM users u
    WHERE u.id = :user_id';

    $stmt = $this->getConnection()->prepare($sql);
    $stmt->execute(['user_id' => $user_id]);

    // Fetch the result as an associative array
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if a result was found and return department_id or null
    return $result ? $result['department_id'] : null;
}
public function getUsersByDepartmentId($department_id) {
$sql = 'SELECT u.id, u.full_name
FROM users u
WHERE u.department_id = :department_id';

$stmt = $this->getConnection()->prepare($sql);
$stmt->execute(['department_id' => $department_id]);

// Fetch all results as an associative array
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the list of users or an empty array if none found
return $result? $result['department_id'] : null;
}

public function renderUserSelect($department_id) {
// Get users by department ID

$users = $this->getUsersByDepartmentId($department_id);

// Start the select element
$html = '<div class="form-group">
    <label for="user-select">User</label>
    <select class="form-control" id="user-select" name="user_id">';

        // Check if users exist and populate the options
        if (!empty($users)) {
        foreach ($users as $user) {
        $html .= '<option value="' . htmlspecialchars($user['id']) . '">' . htmlspecialchars($user['full_name']) . '
        </option>';
        }
        } else {
        // Option when no users are found
        $html .= '<option value="">No users available</option>';
        }

        // Close the select element
        $html .= ' </select>
</div>';

// Return the complete HTML
return $html;
}
public function getKPIDetails($kpi_id) {
$sql = 'SELECT kd.id, kd.name, kd.description, kd.target, kd.actual,kd.time_create, kd.time_end, d.department_name
FROM kpi_department kd
JOIN departments d ON kd.department_id = d.id
WHERE kd.id = :kpi_id';

$stmt = $this->getConnection()->prepare($sql);
$stmt->execute(['kpi_id' => $kpi_id]);
return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function renderKPIDetails($kpi_id) {
// Fetch KPI details
$sql = 'SELECT kd.id, kd.name, kd.description, kd.target, kd.actual,kd.time_create, kd.time_end, d.id AS
department_id,d.maphong,d.department_name
FROM kpi_department kd
JOIN departments d ON kd.department_id = d.id
WHERE kd.id = :kpi_id';

$link = $this->getConnection();

if ($link) {
$stmt = $link->prepare($sql);
// Bind the parameter
$stmt->execute(['kpi_id' => $kpi_id]);
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!empty($result)) {
$firstRow = $result[0];
echo '<div class="row">
    <div class="col-lg-12">
        <h1 class="page-header">Quản lý KPI nhân viên '.htmlspecialchars($firstRow['department_name']).'</h1>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">

        <h3>thông tin KPI</h3>
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th style="width: 60px;">Mã KPI</th>
                    <th style="width: 30px;">Mã phòng ban</th>
                    <th style="width: 150px;">Tên KPI</th>
                    <th style="width: 650px;">Mô tả</th>
                    <th style="width: 150px;">Thời gian bắt đầu</th>
                    <th style="width: 150px;">Thời gian kết thúc</th>
                    <th style="width: 60px;">Mục tiêu</th>
                    <th style="width: 60px;">Thực tế</th>

                </tr>
            </thead>
            <tbody>';

                foreach ($result as $row) {
                $id = htmlspecialchars($row['id']); // Corrected the index to 'id'
                $name = htmlspecialchars($row['name']);
                $description = htmlspecialchars($row['description']);
                $target1 = htmlspecialchars($row['target']);
                $time_create = htmlspecialchars($row['time_create']);
                $time_end = htmlspecialchars($row['time_end']);
                $maphong = htmlspecialchars($row['maphong']); // Ensure the alias matches
                $actual = htmlspecialchars($row['actual']);

                echo '<tr>
                    <td>'.$id.'</td>
                    <td>'.$maphong.'</td>
                    <td>'.$name.'</td>
                    <td>'.$description.'</td>
                    <td>'.$time_create.'</td>
                    <td>'.$time_end.'</td>
                    <td>'.$target1.'</td>
                    <td>'.$actual.'</td>
                </tr>';
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



// Thêm KPI phòng ban
public function them_kpi_phong_ban($department_id, $name, $description) {
    $dbh = $this->getConnection();
    $stmt = $dbh->prepare("INSERT INTO kpi_department (department_id, name, description) VALUES (:department_id, :name, :description)");
    $stmt->bindParam(':department_id', $department_id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);

    if ($stmt->execute()) {
        return true;
    } else {
        // Debugging: Output error information
        error_log("Error adding KPI: " . implode(", ", $stmt->errorInfo()));
        return false;
    }
}

// Cập nhật KPI phòng ban
public function cap_nhat_kpi_phong_ban($id, $name, $description) {
    $dbh = $this->getConnection();
    $stmt = $dbh->prepare("UPDATE kpi_department SET name = :name, description = :description WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);

    if ($stmt->execute()) {
        return true;
    } else {
        // Debugging: Output error information
        print_r($stmt->errorInfo());
        return false;
    }
}

// Xóa KPI phòng ban
public function xoa_kpi_phong_ban($id) {
    $dbh = $this->getConnection();
    $stmt = $dbh->prepare("DELETE FROM kpi_department WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        return true;
    } else {
        // Debugging: Output error information
        print_r($stmt->errorInfo());
        return false;
    }
}

public function getDepartments() {
$link = $this->getConnection();
$stmt = $link->prepare("SELECT * FROM departments");
$stmt->execute();
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


// Thêm KPI nhân viên
public function them_kpi_nhan_vien($user_id, $kpi_name, $evaluated_by, $date_start, $date_end, $target, $department_id,
$id_dep_task, $priority)
{
$dbh = $this->getConnection();
$stmt = $dbh->prepare("INSERT INTO kpi_personal (user_id, kpi_name, evaluated_by, date_start, date_end, target,
department_id, id_dep_task, priority)
VALUES (:user_id, :kpi_name, :evaluated_by, :date_start, :date_end, :target, :department_id, :id_dep_task, :priority)");
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':kpi_name', $kpi_name);
$stmt->bindParam(':evaluated_by', $evaluated_by);
$stmt->bindParam(':date_start', $date_start);
$stmt->bindParam(':date_end', $date_end);
$stmt->bindParam(':target', $target);
$stmt->bindParam(':department_id', $department_id);
$stmt->bindParam(':id_dep_task', $id_dep_task);
$stmt->bindParam(':priority', $priority);

return $stmt->execute(); // Trả về true nếu thành công, false nếu thất bại
}

// Cập nhật KPI nhân viên
public function cap_nhat_kpi_nhan_vien($id, $kpi_name, $target, $priority)
{
$dbh = $this->getConnection();
$stmt = $dbh->prepare("UPDATE kpi_personal SET kpi_name = :kpi_name, target = :target, priority = :priority WHERE id =
:id");
$stmt->bindParam(':kpi_name', $kpi_name);
$stmt->bindParam(':target', $target);
$stmt->bindParam(':priority', $priority);
$stmt->bindParam(':id', $id);

return $stmt->execute() ? 1 : 0;
}
// Xóa KPI nhân viên
public function xoa_kpi_nhan_vien($id)
{
$dbh = $this->getConnection();
$stmt = $dbh->prepare("DELETE FROM kpi_personal WHERE id = :id LIMIT 1");
$stmt->bindParam(':id', $id);

return $stmt->execute() ? 1 : 0;
}
}


?>