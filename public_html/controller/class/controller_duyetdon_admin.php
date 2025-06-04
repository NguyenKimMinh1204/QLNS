<?php
include 'class_duyetdon_admin.php';
$duyetdon = new duyetdon();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'approve_request') {
        $request_id = $_POST['request_id'];

        if (empty($request_id)) {
            echo json_encode(['status' => 0, 'message' => "Yêu cầu không hợp lệ!"]);
            exit;
        }

        $result = $duyetdon->updateLeaveStatus($request_id, 'approved');

        if ($result) {
            echo json_encode(['status' => 1, 'message' => "Đơn đã được duyệt thành công!"]);
        } else {
            echo json_encode(['status' => 0, 'message' => "Có lỗi xảy ra khi duyệt đơn!"]);
        }
    }

    if ($action === 'reject_request') {
        $request_id = $_POST['request_id'];
        $reason_reject = $_POST['reason_reject'];

        if (empty($request_id) || empty($reason_reject)) {
            echo json_encode(['status' => 0, 'message' => "Yêu cầu không hợp lệ!"]);
            exit;
        }

        $result = $duyetdon->updateLeaveStatusWithCheck($request_id, 'rejected', $reason_reject);

        if ($result) {
            echo json_encode(['status' => 1, 'message' => "Đơn đã bị từ chối thành công!"]);
        } else {
            echo json_encode(['status' => 0, 'message' => "Có lỗi xảy ra khi từ chối đơn!"]);
        }
    }
} else {
    echo json_encode(['status' => 0, 'message' => "Yêu cầu không hợp lệ!"]);
}