<?php
include 'class_chamcong_e.php';

$chamconge = new chamconge();

// // Test Clock In
// $check_in=$chamconge->check_in('2024-12-02 8:10:00',61);echo json_encode($check_in);
// $check_out=$chamconge->check_out('2024-12-02 11:55:00',1,98);echo json_encode($check_out);

// echo json_encode($clockInResult);
// $clock_out='2024-12-02 17:30:00'; $check_in=1;$attendance_id=78;

// $result = $chamconge->check_out('2024-12-02 11:55:00', 1, 98);

// // Kiểm tra kết quả trả về
// var_dump($result);

// echo "Shift ID: " . $result['shift_id'] . "<br>";
// echo "Thời gian về sớm: " . $result['thoi_gian_ve_som'] . " phút";

// $clockInResult = $chamconge->clockInTest(61, '192.168.1.6', '2024-12-02 07:50:00');

// echo json_encode($clockInResult);
//Assuming attendance_id is retrieved from the previous clock-in
// $attendance_id = 99; // Get the latest attendance ID for the user

// // // Test Clock Out

//     $clockOutResult = $chamconge->clockOutTest($attendance_id, '192.168.1.6', '2024-12-02 16:55:00');
//     echo json_encode($clockOutResult);

?>
<!-- INSERT INTO `attendance` ( `user_id`, `clock_in_time`, `clock_out_time`, `wifi_address`, `wifi_address_out`,
`check_in`, `shift_id`, `earlyTime`, `lateLeaveTime`)
VALUES
( '84', '2024-12-04 07:51:00', '2024-12-04 16:45:00', NULL, NULL, '1', '3', '15', '0'),
( '84', '2024-12-05 08:14:00', '2024-12-05 17:11:00', NULL, NULL, '1', '3', '0', '14'),
( '84', '2024-12-06 07:50:00', '2024-12-06 17:06:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2024-12-09 07:50:00', '2024-12-09 17:06:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2024-12-10 07:33:00', '2024-12-10 17:01:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2024-12-11 07:43:00', '2024-12-11 17:03:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2024-12-12 07:59:00', '2024-12-12 17:16:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2024-12-13 07:55:00', '2024-12-13 17:08:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2024-12-16 08:30:00', '2024-12-16 17:13:00', NULL, NULL, '1', '3', '0', '30'),
( '84', '2024-12-17 07:53:00', '2024-12-17 16:12:00', NULL, NULL, '1', '3', '48', '0'),
( '84', '2024-12-18 07:52:00', '2024-12-18 17:15:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2024-12-19 09:05:00', '2024-12-19 17:18:00', NULL, NULL, '1', '3', '0', '65'),
( '84', '2024-12-20 07:47:00', '2024-12-20 17:20:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2024-12-23 08:00:00', '2024-12-23 17:30:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2024-12-24 07:47:00', '2024-12-24 17:25:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2024-12-25 08:00:00', '2024-12-25 17:12:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2024-12-26 07:47:00', '2024-12-26 17:02:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2024-12-27 07:55:00', '2024-12-27 17:01:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2024-12-30 07:50:00', '2024-12-30 17:06:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2024-12-31 07:50:00', '2024-12-31 17:06:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2025-01-01 07:50:00', '2025-01-01 17:06:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2025-01-02 07:50:00', '2025-01-02 17:06:00', NULL, NULL, '1', '3', '0', '0'),
( '84', '2025-01-03 07:50:00', '2025-01-03 17:06:00', NULL, NULL, '1', '3', '0', '0'); -->