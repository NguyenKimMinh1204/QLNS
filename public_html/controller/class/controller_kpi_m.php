<?php
include './class_kpi_m.php';
$a = new kpi_m();

if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
    $action = $_POST[ 'action' ] ?? '';

    if ( $action === 'add' ) {
        $dept_kpi_id = $_POST[ 'dept_kpi_id' ];
        $kpi_personal_id = $_POST[ 'kpi_personal_id' ];
        $user_id = $_POST[ 'user_id' ];
        $due_date = $_POST[ 'due_date' ];
        $assigned_value = $_POST[ 'assigned_value' ];
        $assigned_date = $_POST[ 'assigned_date' ];
        $kpi_link = $_POST['kpi_link'] ?? null;
        if($kpi_link!=null){
         $result = $a->addEmployeeKPI( $dept_kpi_id, $kpi_personal_id, $user_id, $due_date, $assigned_value,$assigned_date,$kpi_link);   
        }else{
            $result = $a->addEmployeeKPI1( $dept_kpi_id, $kpi_personal_id, $user_id, $due_date, $assigned_value,$assigned_date);
            
        }
        // Thực hiện thêm KPI và xuất ra 1 hoặc 0
        
        echo $result ? 1 : 0;

    } elseif ( $action === 'update' ) {
        $emp_kpi_id = $_POST[ 'emp_kpi_id' ];
        $assigned_value = $_POST[ 'assigned_value' ];
        $due_date = $_POST[ 'due_date' ];

        // Perform the update and return 1 or 0
        $result = $a->updateEmployeeKPI( $emp_kpi_id, $assigned_value, $due_date );
        echo $result ? 1 : 0;

    } elseif ( $action === 'delete' ) {
        $emp_kpi_id = $_POST[ 'emp_kpi_id' ];
        $result = $a->deleteEmployeeKPI( $emp_kpi_id );
        if ( $result === 0 ) {
            echo 'Không thể xóa KPI: Tiến độ lớn hơn 0 hoặc KPI không tồn tại.';
        } else {
            echo $result ? 1 : 0;
            // Return 1 if deletion was successful, otherwise 0
        }

    } elseif ( $action === 'filter' ) {
        $user_id = $_POST[ 'user_id' ] ?? null;
        $month = $_POST[ 'month' ] ?? null;
        $year = $_POST[ 'year' ] ?? null;

        $department_id = $a->getDepartmentByUserId( $_SESSION[ 'user_id' ] );
        // Get department_id based on the logged-in user

        // Determine which filters are set and call the appropriate method
        if ( $user_id && $month && $year ) {
            // Case 1: User ID, Month, and Year
            $a->loadKPIByEmployeeAndTime( $department_id, $user_id, $month, $year );
        } elseif ( $user_id ) {
            // Case 2: User ID only
            $a->loadKPIByEmployeeAndTime( $department_id, $user_id, null, null );
        } elseif ( $month && $year ) {
            // Case 3: Month and Year only
            $a->loadKPIsByDepartmentbytime( $department_id, $month, $year );
        } elseif ( $year ) {
            // Case 4: Year only
            $a->loadKPIsByDepartmentbytime( $department_id, null, $year );
        } elseif ( $user_id && $year ) {
            // Case 5: User ID and Year
            $a->loadKPIByEmployeeAndTime( $department_id, $user_id, null, $year );
        } else {
            // If no filters are applied, load all KPIs
            $a->loadeallkpi( $department_id );
        }
    } elseif ( $_POST[ 'action' ] == 'get_progress_logs' ) {
        $emp_kpi_id = $_POST[ 'emp_kpi_id' ];
        $progressLogs = $a->get_kpi_progress_logs( $emp_kpi_id );
        echo json_encode( $progressLogs );
        // Return the logs as JSON
        exit;
    }
}

?>