<?php
include 'class_thuongkpi_nv_a.php';
$thuongkpi_nv_a = new thuongkpi_nv_a();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    if($action==='addTransaction'){
    $user_id = $_POST['user_id'];
    $amount = $_POST['amount'];
    $transaction_date = $_POST['transaction_date'];

    $result = $thuongkpi_nv_a->addTransaction($user_id, $amount, $transaction_date);

    if ($result === 1) {
        echo 'Thêm thưởng thành công.';
    } elseif ($result === 2) {
        echo 'Thưởng đã được thêm';
    } else {
        echo 'thêm thưởng thất bại';
    }
    }
    if($action==='loadKPIByuser'){
        $user_id=$_POST['user_id'];
        $month=$_POST['month'];     
        $year=$_POST['year'];
        
        $result=$thuongkpi_nv_a->loadKPIByMonthYearUserbyuser($user_id,$month,$year);
        if($result){
            echo json_encode($result);
        }else{
            echo 0;
        }

    }
}

// $u=59;
// $m=12;
// $y=2024;
// print_r($thuongkpi_nv_a->loadKPIByMonthYearUserbyuser($u,$m,$y));
?>