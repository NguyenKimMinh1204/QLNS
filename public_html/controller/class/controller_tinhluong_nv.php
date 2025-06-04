<?php
include 'class_tinhluong_nv.php';
$a = new tinhluong_nv();

// Cif the action is set in the request
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    $user_id = $_POST['user_id'] ?? 0;
    $transaction_date = $_POST['transaction_date'] ?? date('Y-m-d');

    // Initialize amounts
    $amount_pc = $_POST['amount_pc'] ?? 0;
    $amount_thue = $_POST['amount_thue'] ?? 0;
    $amount_bh = $_POST['amount_bh'] ?? 0;
    $salary = $_POST['salary'] ?? 0;

    // Handle different actions
    switch ($action) {
        case 'addAllTransactions':
            // Add transaction for Phụ Cấp
            $result_pc = $a->addTransactionpc($user_id, $amount_pc, $transaction_date);
           

            // Add transaction for Thuế
            $result_thue = $a->addTransactionthue($user_id, $amount_thue, $transaction_date);
           

            // Add transaction for Bảo Hiểm
            $result_bh = $a->addTransactionBH($user_id, $amount_bh, $transaction_date);
           

            
            $result_salary = $a->addSalary($user_id, $salary, $transaction_date);
            if ($result_salary == 5) {
                echo 5;
                break;
            }
            // Check results and return a response
            if ($result_pc == 1 && $result_thue == 1 && $result_bh == 1 && $result_salary == 1) {
                echo 1;
            } else {
                echo 0;
            }
            break;

        default:
            echo 'Hành động không hợp lệ';
            break;
    }
} else {
    echo 'không có hành động nào được chỉ định';
}
    $user_id =59;
    $transaction_date = '2024-10-05';
    // // Initialize amounts
    // $amount_pc = 500000;
    // $amount_thue = 200000;
    // $amount_bh = 600000;
    //$salary=6500000;
    
    // $result_pc=$a->addTransactionpc($user_id,$amount_pc,$transaction_date);
    // $result_bh=$a->addTransactionBH($user_id,$amount_bh,$transaction_date);
    // $result_thue=$a->addTransactionthue($user_id,$amount_thue,$transaction_date);
    //$result_salary=$a->addSalary($user_id,$salary,$transaction_date);
    // if($result_pc==1){ 
    //     echo 'Thêm phụ cấp thành công';
    // }elseif($result_pc==2){
    //     echo 'Phụ cấp đã được thêm';
    // }else{
    //     echo 'Lỗi khi thêm phụ cấp';
    // }
    // echo '<br>';
    // if($result_bh==1){
    //     echo 'Thêm bảo hiểm thành công';
    // } elseif($result_bh==4){
    //     echo 'Bảo hiểm đã được thêm';
    // }else{
    //     echo 'Lỗi khi thêm bảo hiểm';
    // }
    //  echo '<br>';
    // if($result_thue==1){
    //     echo 'Thêm thuế thành công';
    // }elseif($result_thue==3){
    //     echo 'Thuế đã được thêm';       
    // }else{
    //     echo 'Lỗi khi thêm thuế';
    // }
    //  echo '<br>';
    // if($result_salary==1){
    //     echo 'Thêm lương thành công';
    // }elseif($result_salary==5){
    //     echo 'Lương đã được thêm';
    // }else{
    //     echo 'Lỗi khi thêm lương';
    // }
// ?>