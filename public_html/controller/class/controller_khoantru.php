<?php
include ('class_khoantru.php');
$khoantru = new AttendanceReport();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
     // Default user_id for demonstration
    

    if ($action === 'getDeductionDetails') {
        $user_idd = $_POST['user_idd'] ; // Default user_id for demonstration
        $month = $_POST['month'] ?? date('m');
        $year = $_POST['year'] ?? date('Y');
        $leaveDaysWithPermission = $khoantru->getLeaveDaysWithPermission($user_idd, $month, $year);
        $unapprovedLeaveDays = $khoantru->getUnapprovedLeaveDays($user_idd, $month, $year);
        $attendanceDeductions = $khoantru->getAttendanceDeductionsArray($user_idd, $month, $year);

        $response = [
            'leaveDaysWithPermission' => $leaveDaysWithPermission,
            'unapprovedLeaveDays' => $unapprovedLeaveDays,
            'attendanceDeductions' => $attendanceDeductions
        ];

        echo json_encode($response);
        exit;
    }

    if ($action === 'addTransaction') {
        $user_idd = $_POST['user_idd'] ;
        $amount = $_POST['amount'] ?? 0;
        $transaction_date = $_POST['transaction_date'] ?? date('Y-m-d');
        

        $result = $khoantru->addTransaction($user_idd, $amount, $transaction_date);

        echo $result;
        exit;
    }
    // ... existing code ...
}
// $user_id=59;
// $amount=100000;     
// $transaction_date="2024-12-05";
//  $result = $khoantru->addTransaction($user_id, $amount, $transaction_date);
//    echo $result;
// $khoantru = new AttendanceReport();
// $a=20;
// echo $khoantru->calculatePenalty($a);
// echo"<br>";
// $user_id=59;
// $month=10;
// $year=2024;
// print_r($khoantru->getLeaveDaysWithPermission($user_id,$month,$year));//nghỉ có phép
// echo"<br>";
// print_r($khoantru->getUnapprovedLeaveDays($user_id,$month,$year));//nghỉ không phép
// echo"<br>";
// echo "<pre>";
// print_r($khoantru->getAttendanceDeductionsArray($user_id,$month,$year));//trừ đi trễ
// echo "<pre>";
// echo $khoantru->getTotalDeductions($user_id,$month,$year);
//  $transactions = $khoantru->getTransactions();
//  $date="2024-10-05";
 
//   foreach ($transactions as $transaction) {
//                         if ($transaction['user_id'] == 50 && 
//                             $transaction['category_id'] == 6 && // Assuming category_id is fixed as 6
//                             $transaction['transaction_date'] == $date) {
//                            $khoantrunew=$transaction['amount'];
//                         }else{
//                             $khoantrunew=0;
//                         }
//                     }
//                     echo $khoantrunew;
?>