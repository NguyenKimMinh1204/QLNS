<?php

include ('class_viewProgress_a.php'); // Bao gồm class chứa các phương thức

// Khởi tạo đối tượng
$a = new tientrinh_a();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra xem action có phải là 'get_single_progress' không
    if (isset($_POST['action']) && $_POST['action'] === 'get_single_progress') {
        // Lấy emp_kpi_id từ yêu cầu
        $emp_kpi_id = isset($_POST['emp_kpi_id']) ? intval($_POST['emp_kpi_id']) : 0;

        // Gọi phương thức get_single_progress
        $progress_logs = $a->get_single_progress($emp_kpi_id);

        // Trả về dữ liệu dưới dạng JSON
        echo json_encode($progress_logs);
        exit; // Dừng thực thi script
    }
}
?>