<?php
include 'class_namekpi_emp.php';

$namekpiEmp = new namekpi_emp();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
            $kpi_lib_id = $_POST['kpi_lib_id'];
            $kpiName = $_POST['kpi_name'];
            $kpiDescription = $_POST['kpi_description'];
            $result = $namekpiEmp->addKpi($kpi_lib_id, $kpiName, $kpiDescription);
            echo $result ? 1 : 0;
            break;

        case 'update':
            $kpiId = $_POST['kpi_id'];
            $kpiName = $_POST['kpi_name'];
            $kpiDescription = $_POST['kpi_description'];
            $is_counted = $_POST['is_counted']; // Assuming this is passed in the request
            $result = $namekpiEmp->updateKpi($kpiId, $kpiName, $kpiDescription, $is_counted);
            echo $result ? 1 : 0;
            break;

        case 'delete':
            $kpiId = $_POST['kpi_id'];
            $result = $namekpiEmp->deleteKpi($kpiId);
            echo $result ? 1 : 0;
            break;
    }
}