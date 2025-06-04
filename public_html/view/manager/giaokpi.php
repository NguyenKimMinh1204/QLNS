<?php
include '../layout_manager/auth.php';
include '../../controller/class/class_kpi_m.php';
$a=new kpi_m();

$dept_kpi_id=$_REQUEST['dept_kpi_id'];
$department_id= $a->getDepartmentBydept_kpi_id($dept_kpi_id);
$kpi_lib_id= $a->getKpiLibIdByDeptKpiId($dept_kpi_id);
// $users = $a->getUsersByDepartmentId($department_id);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Giao KPI</title>

    <link href="../../assets/employee/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/employee/css/datepicker3.css" rel="stylesheet">
    <link href="../../assets/employee/css/styles.css" rel="stylesheet">

    <!--Icons-->
    <script src="../../assets/employee/js/lumino.glyphs.js"></script>
    <!-- Tải CSS của Bootstrap -->
    <script src="../../assets/employee/js/jquery-1.11.1.min.js"></script>
    <script src="../../assets/employee/js/bootstrap.min.js"></script>
    <script src="../../assets/employee/js/chart.min.js"></script>
    <script src="../../assets/employee/js/chart-data.js"></script>
    <script src="../../assets/employee/js/easypiechart.js"></script>
    <script src="../../assets/employee/js/easypiechart-data.js"></script>
    <script src="../../assets/employee/js/bootstrap-datepicker.js"></script>

    <!--[if lt IE 9]><![endif]-->
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>

    <style>
    .breadcrumb {
        border-radius: 0;
        padding: 27px 10px;
        background: #e9ecf2;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        margin: 0;
    }

    .my-2 {
        margin: 5px;
    }

    .mt-5 {
        margin-top: 20px;
        margin-bottom: 20px;
    }
    </style>
</head>

<body>
    <?php
    include ('../layout_manager/header.php');
    ?>
    <?php
    include ('../layout_manager/sidebar.php');
    ?>

    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="index.php"><svg class="glyph stroked home">
                            <use xlink:href="#stroked-home"></use>
                        </svg></a></li>

                <li class="active"><a href="ds_kpi_dep.php">Danh sách KPI phòng ban</a></li>
                <li class="active"><a href="giaokpi.php">Giao KPI cho nhân viên</a></li>

            </ol>
        </div>

        <!--/.row-->


        <?php
       
            $a->renderKPIDetails($dept_kpi_id);
            
            
            ?>

        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Advanced Table</div>
                <button type="button" class="btn btn-primary btn-sm edit-btn my-2" data-toggle="modal"
                    data-target="#GiaoModal">
                    Giao KPI cho nhân viên
                </button>
                <div class="panel-body">

                    <?php 
                        $a->loadekpi($dept_kpi_id);
                    ?>
                    <!-- <div class="clearfix"></div> -->

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-11">


            </div>
        </div>
        <!--/.row-->
        <!-- Edit KPI Personal Modal -->
        <div class="modal fade" id="Suakpi" tabindex="-1" role="dialog" aria-labelledby="editKPIPersonalModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editKPIPersonalModalLabel">Sửa KPI</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editKPIForm">
                        <div class="modal-body">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="emp_kpi_id" id="edit-kpi-id">
                            <div class="form-group">
                                <label for="edit-assigned_value">Giá trị được giao</label>
                                <input type="number" class="form-control" id="edit-assigned_value" name="assigned_value"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="edit-due_date">Ngày hết hạn</label>
                                <input type="datetime-local" class="form-control" id="edit-due_date" name="due_date"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Assign KPI Modal -->
        <div class="modal fade" id="GiaoModal" tabindex="-1" role="dialog" aria-labelledby="assignKPIModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignKPIModalLabel">Giao KPI cho Nhân viên</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="assignKPIForm">
                        <div class="modal-body">
                            <input type="hidden" name="action" value="add">
                            <input type="hidden" name="dept_kpi_id" id="dept_kpi_id"
                                value="<?php echo $dept_kpi_id; ?>">
                            <div class="form-group">
                                <label for="user_id1">Tên Nhân viên</label>
                                <select class="form-control" id="user_id1" name="user_id1" required>
                                    <?php
                                    // Kiểm tra nếu $users có dữ liệu
                                    $users = $a->getUserBydepartment($department_id);
                                    if ($users) {
                                        foreach ($users as $user) {
                                            // Kiểm tra xem mỗi phần tử có id và full_name
                                            echo '<option value="' . htmlspecialchars($user['id']) . '">' . htmlspecialchars($user['full_name']) . '</option>';
                                        }
                                    } else {
                                        echo '<option value="">Không có nhân viên</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="kpi_personal_id">Tên KPI</label>
                                <select class="form-control" id="kpi_personal_id" name="kpi_personal_id" required>
                                    <?php
                                    // Kiểm tra và lấy danh sách KPI
                                    $namekpis = $a->getnamekpiEByDepartmentAndLib($department_id, $kpi_lib_id);
                                    
                                    // Kiểm tra nếu có dữ liệu
                                    if (count($namekpis) > 0) {
                                        foreach ($namekpis as $namekpi) {
                                            // Hiển thị danh sách các KPI
                                            echo '<option value="' . htmlspecialchars($namekpi['id']) . '">' . htmlspecialchars($namekpi['kpi_name']) . '</option>';
                                        }
                                    } else {
                                        // Nếu không có KPI nào, hiển thị thông báo
                                        echo '<option value="">Không có KPI</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="assigned_value">Giá trị được giao</label>
                                <input type="number" class="form-control" id="assigned_value" name="assigned_value"
                                    required>
                            </div>
                            <!-- Điều kiện kiểm tra department_id -->
                            <?php if ($department_id == 5): ?>
                            <div class="form-group">
                                <label for="kpi_link">Liên kết KPI</label>
                                <input type="url" class="form-control" id="kpi_link" name="kpi_link"
                                    placeholder="Nhập liên kết KPI">
                            </div>
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="assigned_date">Ngày giao</label>
                                <input type="datetime-local" class="form-control" id="assigned_date"
                                    name="assigned_date" required>
                            </div>
                            <div class="form-group">
                                <label for="due_date">Ngày hết hạn</label>
                                <input type="datetime-local" class="form-control" id="due_date" name="due_date"
                                    required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                            <button type="submit" class="btn btn-success" id="Giao">Giao KPI</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <div class="modal fade" id="selectProgressModal" tabindex="-1" role="dialog"
        aria-labelledby="viewProgressModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewProgressModalLabel">Tiến độ KPI</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Giá trị tiến độ</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody id="progressTableBody">
                            <!-- Dữ liệu sẽ được tải qua AJAX -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
    // Khi mở modal để xem tiến độ KPI
    $('#selectProgressModal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Lấy nút đã nhấn
        var empKpiId = button.data('empkpiid'); // Lấy emp_kpi_id từ data-attribute

        // Gọi AJAX để lấy tiến độ KPI
        $.ajax({
            url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_kpi_m.php', // Đường dẫn đến file PHP xử lý
            method: 'POST',
            data: {
                action: 'get_progress_logs', // Hành động để lấy tiến độ
                emp_kpi_id: empKpiId // Gửi emp_kpi_id
            },
            success: function(response) {
                var progressLogs = JSON.parse(response); // Chuyển đổi JSON thành đối tượng
                var tbody = $('#progressTableBody');
                tbody.empty(); // Xóa dữ liệu cũ

                if (progressLogs.length > 0) {
                    progressLogs.forEach(function(log) {
                        tbody.append('<tr>' +
                            '<td>' + log.updated_at + '</td>' +
                            '<td>' + log.progress_update + '</td>' +
                            '<td>' + log.result_detail + '</td>' +
                            '</tr>');
                    });
                } else {
                    tbody.append(
                        '<tr><td colspan="3" class="text-center">Không có dữ liệu</td></tr>');
                }
            },
            error: function() {
                alert('Có lỗi xảy ra trong quá trình xử lý.');
            }
        });
    });
    </script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- <script>
    $(document).on('click', '#Giao', function() {
        var dept_kpi_id = $('#dept_kpi_id').val();
        var user_id = $('#user_id1').val();
        var kpi_personal_id = $('#kpi_personal_id').val();
        var assigned_value = $('#assigned_value').val();
        var due_date = $('#due_date').val();
        console.log(dept_kpi_id);
        console.log(user_id);
        console.log(kpi_personal);
        console.log(assigned_value);
        console.log(due_date);

        // Kiểm tra xem deptName và maphong có rỗng không
        if (assigned_value.trim() === '' || due_date.trim() === '') {
            alert('Giá trị mục tiêu và ngày hết hạn không được để trống.');
            return; // Dừng lại nếu rỗng
        }

        $.ajax({
            url: 'http://localhost/KLTN/controller/class/controller_kpi_m.php', // Đường dẫn đến file PHP xử lý
            method: 'POST',
            data: {
                action: 'add',
                dept_kpi_id: dept_kpi_id,
                kpi_personal_id: kpi_personal_id, // Gửi mã phòng
                user_id: user_id,
                due_date: due_date,
                assigned_value: assigned_value,

            },
            success: function(response) {
                console.log(response);
                if (response == 1) {

                    alert('Giao KPI cho nhân viên thành công!');
                    location.reload(); // Tải lại trang để xem sự thay đổi
                } else {
                    alert('Lỗi khi Giao KPI. Response: ' + response);
                    location.reload();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Có lỗi xảy ra trong quá trình xử lý: ' + textStatus + ' - ' +
                    errorThrown);
            }
        });
    });
    $(document).on('click', '.edit-btn', function() {
        var empKpiId = $(this).data('emp-kpi-id');
        var assignedValue = $(this).data('assigned-value');
        var dueDate = $(this).data('due-date');

        $('#edit-kpi-id').val(empKpiId);
        $('#edit-assigned_value').val(assignedValue);
        $('#edit-due_date').val(dueDate);
    });

    // Handle the form submission for updating KPI
    $('#editKPIForm').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission
        $.ajax({
            type: 'POST',
            url: 'http://localhost/KLTN/controller/class/controller_kpi_m.php',
            data: $(this).serialize(), // Serialize the form data
            success: function(response) {
                console.log("Server response:", response); // Log the response
                if (response == 1) {
                    alert('Cập nhật KPI thành công!');
                    location.reload(); // Reload the page to see the changes
                } else {
                    alert('Cập nhật KPI thất bại!');
                }
            },
            error: function() {
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            }
        });
    });
    </script> -->
    <script>
    // // sửa 
    // $(document).ready(function() {
    //     $('#editKPIForm').on('submit', function(e) {
    //         e.preventDefault(); // Ngăn chặn hành động mặc định của form
    //         $.ajax({
    //             type: 'POST',
    //             url: 'http://localhost/KLTN/controller/class/controller_kpi_m.php', // Đường dẫn đến controller
    //             data: $(this).serialize(), // Dữ liệu từ form
    //             success: function(response) {
    //                 if (response == 1) {
    //                     alert('Cập nhật KPI thành công!');
    //                     location.reload(); // Tải lại trang để cập nhật dữ liệu
    //                 } else {
    //                     alert('Cập nhật KPI thất bại!');
    //                 }
    //             },
    //             error: function() {
    //                 alert('Có lỗi xảy ra. Vui lòng thử lại.');
    //             }
    //         });
    //     });
    // });
    //xóa
    $(document).on('click', '.delete-btn', function() {
        var empKpiId = $(this).data('emp-kpi-id');

        if (confirm('Bạn có chắc chắn muốn xóa KPI này không?')) {
            $.ajax({
                type: 'POST',
                url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_kpi_m.php',
                data: {
                    action: 'delete',
                    emp_kpi_id: empKpiId
                },
                success: function(response) {
                    if (response ===
                        'Cannot delete KPI: Progress is greater than 0 or KPI does not exist.') {
                        alert(response); // Show the error message
                    } else if (response == 1) {
                        alert('Xóa KPI thành công!');
                        location.reload(); // Reload the page to see the changes
                    } else {
                        alert('Xóa KPI thất bại!');
                    }
                },
                error: function() {
                    alert('Có lỗi xảy ra trong quá trình xử lý.');
                }
            });
        }
    });
    $(document).on('click', '#Giao', function() {
        var dept_kpi_id = $('#dept_kpi_id').val();
        var kpi_personal_id = $('#kpi_personal_id').val();
        var user_id = $('#user_id1').val();
        var assigned_value = $('#assigned_value').val();
        var assigned_date = $('#assigned_date').val();
        var due_date = $('#due_date').val();
        var kpi_link = $('#kpi_link').val();

        console.log("Dept KPI ID:", dept_kpi_id);
        console.log("Personal KPI ID:", kpi_personal_id);
        console.log("User ID:", user_id);
        console.log("Assigned Value:", assigned_value);
        console.log("Assigned Date:", assigned_date);
        console.log("Due Date:", due_date);
        console.log("kpi_link:", kpi_link);
        // Validate inputs
        if (!assigned_value.trim() || !due_date.trim() || !assigned_date.trim()) {
            alert('Giá trị mục tiêu và ngày hết hạn không được để trống.');
            return;
        }
        if (dept_kpi_id == 5 && (!kpi_link || !kpi_link.trim())) {
            alert('Liên kết KPI không được để trống.');
            return;
        }


        $.ajax({
            url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_kpi_m.php',
            method: 'POST',
            data: {
                action: 'add',
                dept_kpi_id: dept_kpi_id,
                kpi_personal_id: kpi_personal_id,
                user_id: user_id,
                assigned_value: assigned_value,
                assigned_date: assigned_date,
                due_date: due_date,
                kpi_link: kpi_link

            },
            success: function(response) {
                console.log("Server Response:", response);

                if (response == 1) {
                    alert('Giao KPI cho nhân viên thành công!');
                    location.reload();
                } else {
                    alert('Lỗi khi Giao KPI. Response: ' + response);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Có lỗi xảy ra trong quá trình xử lý: ' + textStatus + ' - ' + errorThrown);
            }
        });
    });

    // Edit KPI functionality remains unchanged.
    $(document).on('click', '.edit-btn', function() {
        var empKpiId = $(this).data('emp-kpi-id');
        var assignedValue = $(this).data('assigned-value');
        var dueDate = $(this).data('due-date');

        $('#edit-kpi-id').val(empKpiId);
        $('#edit-assigned_value').val(assignedValue);
        $('#edit-due_date').val(dueDate);
    });

    // Handle form submission for updating KPI
    $('#editKPIForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_kpi_m.php',
            data: $(this).serialize(),
            success: function(response) {
                console.log("Server Response:", response);
                if (response == 1) {
                    alert('Cập nhật KPI thành công!');
                    location.reload();
                } else {
                    alert('Cập nhật KPI thất bại!');
                }
            },
            error: function() {
                alert('Có lỗi xảy ra. Vui lòng thử lại.');
            }
        });
    });
    </script>
</body>

</html>

</html>

</html>

</html>

</html>

</html>

</html>