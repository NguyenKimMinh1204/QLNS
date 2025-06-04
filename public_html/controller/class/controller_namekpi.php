<?php
include 'class_namekpi.php';

$namekpi = new namekpi();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'add':
            $departmentId = $_POST['department_id'];
            $kpiName = $_POST['kpi_name'];
            $kpiDescription = $_POST['kpi_description'];
            $result = $namekpi->addKpi($departmentId, $kpiName, $kpiDescription);
            echo $result ? 1 : 0;
            break;

        case 'update':
            $kpiId = $_POST['kpi_id'];
            $kpiName = $_POST['kpi_name'];
            $kpiDescription = $_POST['kpi_description'];
            $result = $namekpi->updateKpi($kpiId, $kpiName, $kpiDescription);
            echo $result ? 1 : 0;
            break;

        case 'delete':
            $kpiId = $_POST['kpi_id'];
            $result = $namekpi->deleteKpi($kpiId);
            echo $result ? 1 : 0;
            break;
    }
}