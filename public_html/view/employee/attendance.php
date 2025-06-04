<?php
session_start();
date_default_timezone_set("Asia/Ho_Chi_Minh");
include('../../controller/class/class_chamcong_e.php');
$employee = new chamconge();


header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = ['success' => false, 'message' => ''];
    
    if (!isset($_SESSION['user_id'])) {
        $response['message'] = 'User not logged in';
        echo json_encode($response);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    if ($_POST['action'] === 'clock_in') {
        $wifi_address = $_POST['wifi_address'] ?? '';
        
        // Kiểm tra đã chấm công vào chưa
        $status = $employee->getAttendanceStatus($user_id);
        if ($status && $status['clock_in_time']) {
            $response['message'] = 'Bạn đã chấm công vào hôm nay';
            echo json_encode($response);
            exit;
        }

        // Thực hiện chấm công vào
        try {
            $employee->clockIn($user_id, $wifi_address);
            $response['success'] = true;
            $response['message'] = 'Chấm công vào thành công';
        } catch (Exception $e) {
            $response['message'] = 'Lỗi khi chấm công vào: ' . $e->getMessage();
        }
    }
    elseif ($_POST['action'] === 'clock_out') {
        $wifi_address = $_POST['wifi_address'] ?? '';
        // Kiểm tra trạng thái chấm công
        $status = $employee->getAttendanceStatus($user_id);
        if (!$status || !$status['clock_in_time']) {
            $response['message'] = 'Bạn chưa chấm công vào hôm nay';
            echo json_encode($response);
            exit;
        }
        if ($status['clock_out_time']) {
            $response['message'] = 'Bạn đã chấm công ra hôm nay';
            echo json_encode($response);
            exit;
        }

        // Lấy ID của bản ghi chấm công hiện tại
        $attendance_id = $employee->getLatestAttendanceId($user_id);
        if ($attendance_id) {
            try {
                $employee->clockOut($attendance_id, $wifi_address);
                $response['success'] = true;
                $response['message'] = 'Chấm công ra thành công';
            } catch (Exception $e) {
                $response['message'] = 'Lỗi khi chấm công ra: ' . $e->getMessage();
            }
        } else {
            $response['message'] = 'Không tìm thấy bản ghi chấm công';
        }
    }
    elseif ($_POST['action'] === 'get_status') {
        $status = $employee->getAttendanceStatus($user_id);
        $response['success'] = true;
        $response['data'] = $status;
    }

    echo json_encode($response);
    exit;
}