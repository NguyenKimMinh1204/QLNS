<?php
include '../layout/auth.php';

include '../../controller/class/class_User.php';
$a=new Users();

// Kiểm tra xem có department_id được gửi từ yêu cầu POST không
$department_id = isset($_POST['department_id_ds']) ? $_POST['department_id_ds'] : null;

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quản lý tài khoản</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="../../assets/lib/bootstrap/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="../../assets/lib/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Theme style -->

    <link rel="stylesheet" href="../../assets/lib/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel=" stylesheet" href="../../assets/lib/dist/css/skins/_all-skins.min.css">
    <script src="../../assets/lib/plugins/jQuery/jquery-3.7.1.min.js"></script>
    <script src="../../assets/lib/bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="../../../plugins/datatables/jquery.dataTables.min.js"></script>

    <!-- link_table -->
    <!-- Bootstrap 3.3.6 -->
    <!-- <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css"> -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../../plugins/datatables/dataTables.bootstrap.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../../assets/lib/dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../../assets/lib/dist/css/AdminLTE.css">
    <!-- hết -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
    </script>
    <link rel="stylesheet" href="../../assets/css/employee/main.css">
    <script>
    $(document).ready(function() {
        $('.table').DataTable(); // Khởi tạo DataTable cho bảng

        // Xử lý sự kiện khi chọn phòng ban
        $('#department_id').change(function() {
            var departmentId = $(this).val(); // Lấy ID phòng ban đã chọn
            $.ajax({
                url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_user.php', // Đường dẫn đến file xử lý
                method: 'POST',
                data: {
                    department_id: departmentId
                },
                success: function(response) {
                    $('.container.mt-5.hight1.width1').html(
                        response); // Cập nhật danh sách người dùng
                },
                error: function() {
                    alert('Error fetching users.'); // Thông báo lỗi
                }
            });
        });
    });
    </script>
    <style>
    .hight1 {
        min-height: 900px;

    }

    .width1 {
        min-width: 1350px;
    }

    .add_css {
        margin-top: 20px;
        margin: 20px;
        margin-left: 0px;
    }

    .inline-text-elements {
        text-decoration: none;
    }

    .mt-3 {
        margin-left: 30px;
    }

    .mt-5 {
        margin: 5px;

    }

    .dieuhuong {
        position: relative;

    }
    </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php 
            
                include ("../layout/header.php");
            
            ?>
        <!-- Left side column. contains the logo and sidebar -->
        <?php 
                include ("../layout/sidebar.php");
            ?>
        <div class="content-wrapper">
            <div class="container-fluid  ">
                <div class="row dieuhuong mt-5">
                    <a href="index.php"><i class="fa fa-dashboard"></i> Home</a><i
                        class="fa fa-fw fa-chevron-right"></i>
                    <a href="#">Quản lý tài khoản</a>    

                </div>
                <div class="row ">
                    <div class="col-sm-3">
                        <h2 class="inline-text-elements">Quản lý tài khoản</h2>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-1"><button class="btn btn-success mt-3 add_css" data-toggle="modal"
                            data-target="#addUserModal">Thêm tài khoản
                        </button></div>


                    <div class="col-sm-3">
                        <form method="post" action="" id="departmentForm" class="form-timkiem"
                            style="position:relative ;left:100px">
                            <div class="form-group">
                                <!-- <label for="department_id_ds">Chọn phòng ban</label> -->
                                <select class="form-control add_css" id="department_id_ds" name="department_id_ds"
                                    required onchange="this.form.submit()">
                                    <option value="0"
                                        <?php echo (!isset($_POST['department_id_ds']) || $_POST['department_id_ds'] == 0) ? 'selected' : ''; ?>>
                                        Tất cả tài khoản
                                    </option>
                                    <?php
                                        $departments = $a->getDepartments(); // Fetch departments
                                        foreach ($departments as $department) {
                                            $selected = (isset($_POST['department_id_ds']) && $_POST['department_id_ds'] == $department['id']) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($department['id']) . '" ' . $selected . '>' . htmlspecialchars($department['department_name']) . '</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </form>
                    </div>


                </div>
                <div class="row">
                    <div class="col-sm-12"><?php 
              
           
               // Nếu không có dữ liệu POST, hiển thị tất cả người dùng
                if (!isset($_POST['department_id_ds'])) {
                    // Hiển thị tất cả người dùng khi mới mở trang
                    $a->loaddulieu_user();
                } else {
                    // Xử lý khi có dữ liệu POST
                    $department_id_ds = !empty($_POST['department_id_ds']) ? $_POST['department_id_ds'] : 0;

                    // Kiểm tra nếu $department_id_ds là 0 hoặc rỗng
                    if ($department_id_ds == 0) {
                        // Hiển thị tất cả người dùng
                        $a->loaddulieu_user();
                    } else {
                        // Hiển thị người dùng theo phòng ban
                        $a->loaddulieu_user1($department_id_ds);
                    }
                }

                    ?></div>
                </div>



            </div>
            <div class="container mt-5 hight1 width1">


                <!-- Modal Add User -->

                <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog"
                    aria-labelledby="addUserModalLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="addUserModalLabel">Thêm tài khoản</h4>
                            </div>
                            <div class="modal-body">
                                <form id="addUserForm">
                                    <div class="form-group">
                                        <label for="username">Tên tài khoản</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Mật khẩu</label>
                                        <input type="password" class="form-control" id="password" name="password"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="role_id">phân quyền</label>
                                        <select class="form-control" id="role_id" name="role_id" required>
                                            <option value="0">Giám đốc</option>
                                            <option value="1">Trưởng phòng</option>
                                            <option value="2">Nhân viên</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="full_name">Họ và tên</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="department_id1">Phòng ban</label>
                                        <select class="form-control" id="department_id1" name="department_id1" required>
                                            <?php
                                                $departments = $a->getDepartments(); // Fetch departments
                                                foreach ($departments as $department) {
                                                    echo '<option value="' . htmlspecialchars($department['id']) . '">' . htmlspecialchars($department['department_name']) . '</option>';
                                                }
                                                ?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                <button type="button" class="btn btn-primary" id="addUser">Thêm tài khoản</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- edit modal user -->
                <div class="modal fade" id="editUserModal" tabindex="-1" role="dialog"
                    aria-labelledby="editUserModalLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="editUserModalLabel">Sửa tài khoản</h4>
                            </div>
                            <div class="modal-body">
                                <form id="editUserForm">
                                    <input type="hidden" id="edit_user_id" name="user_id">
                                    <div class="form-group">
                                        <label for="edit_username">Tên tài khoản</label>
                                        <input type="text" class="form-control" id="edit_username" name="username"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_email">Email</label>
                                        <input type="email" class="form-control" id="edit_email" name="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_role_id">Phân quyền</label>
                                        <select class="form-control" id="edit_role_id" name="role_id" required>
                                            <option value="0">Giám đốc</option>
                                            <option value="1">Trưởng phòng</option>
                                            <option value="2">Nhân viên</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_full_name">Họ và tên</label>
                                        <input type="text" class="form-control" id="edit_full_name" name="full_name"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_department_id">Phòng ban</label>
                                        <select class="form-control" id="edit_department_id" name="department_id"
                                            required>
                                            <?php
                                            $departments = $a->getDepartments(); // Fetch departments
                                            foreach ($departments as $department) {
                                                echo '<option value="' . htmlspecialchars($department['id']) . '">' . htmlspecialchars($department['department_name']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                <button type="button" class="btn btn-primary" id="saveUserChanges">Lưu thay đổi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Change Password -->
                <div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog"
                    aria-labelledby="changePasswordModalLabel">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                                <h4 class="modal-title" id="changePasswordModalLabel">Change Password</h4>
                            </div>
                            <div class="modal-body">
                                <form id="changePasswordForm">
                                    <input type="hidden" id="change_user_id" name="user_id">
                                    <div class="form-group">
                                        <label for="new_password">New Password</label>
                                        <input type="password" class="form-control" id="new_password"
                                            name="new_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="confirm_password">Confirm Password</label>
                                        <input type="password" class="form-control" id="confirm_password"
                                            name="confirm_password" required>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="changePassword">Change
                                    Password</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bootstrap và jQuery -->
            <!-- Replace this slim version -->
            <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>

            <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <!-- With the full version -->
            <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->

            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

            <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
            <script type="text/javascript" charset="utf8"
                src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
            </script>
            <script>
            $('#editUserModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var userId = button.data('id');
                var username = button.data('username');
                var email = button.data('email');
                var fullName = button.data('full_name');
                var roleId = button.data('role_id');
                var departmentId = button.data('department_id');

                // Xuất thông tin để kiểm tra
                console.log('Edit User Info:', {
                    userId: userId,
                    username: username,
                    email: email,
                    fullName: fullName,
                    roleId: roleId,
                    departmentId: departmentId
                });

                var modal = $(this);
                modal.find('#edit_user_id').val(userId);
                modal.find('#edit_username').val(username);
                modal.find('#edit_email').val(email);
                modal.find('#edit_full_name').val(fullName);
                modal.find('#edit_role_id').val(roleId);
                modal.find('#edit_department_id').val(departmentId);
            });

            // Handle save changes
            $(document).on('click', '#saveUserChanges', function() {
                var userId = $('#edit_user_id').val();
                var username = $('#edit_username').val();
                var email = $('#edit_email').val();
                var roleId = $('#edit_role_id').val();
                var fullName = $('#edit_full_name').val();
                var departmentId = $('#edit_department_id').val();


                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_user.php', // Path to your update handler
                    method: 'POST',
                    data: {
                        action: 'update_user',
                        id: userId,
                        username: username,
                        email: email,
                        role_id: roleId,
                        full_name: fullName,
                        department_id: departmentId

                    },
                    success: function(response) {
                        if (response == 1) {

                            alert('Cập nhật tài khoản thành công!'); // Success message
                            location.reload(); // Reload the page to see the updated user
                        } else {
                            alert('Lỗi khi cập nhật user: ' +
                                response); // Show detailed error message
                            location.reload();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        alert('AJAX error: ' + textStatus + ' : ' +
                            errorThrown); // Show AJAX error
                    }
                });
            });

            // Handle delete user (if still needed)
            // $(document).on('click', '.delete-btn', function() {
            //     var deptId = $(this).data('id');
            //     if (confirm('Are you sure you want to delete this department?')) {
            //         $.ajax({
            //             url: 'http://localhost/KLTN/controller/class/controller_user.php', // Path to your delete handler
            //             method: 'POST',
            //             data: {
            //                 action: 'delete',
            //                 id: deptId
            //             },
            //             success: function(response) {
            //                 if (response == 1) {
            //                     alert('User deleted successfully!');
            //                     location.reload(); // Reload to see changes
            //                 } else {
            //                     alert('Error deleting User.');
            //                 }
            //             }
            //         });
            //     }
            // });

            // $(document).on('click', '.edit-btn', function() {
            //     var userId = $(this).data('id');
            //     var username = $(this).data('username');
            //     var email = $(this).data('email');
            //     var fullName = $(this).data('full_name');
            //     var roleId = $(this).data('role_id');
            //     var departmentId = $(this).data('department_id');

            //     var modal = $('#editUserModal');
            //     modal.find('#edit_user_id').val(userId);
            //     modal.find('#edit_username').val(username);
            //     modal.find('#edit_email').val(email);
            //     modal.find('#edit_full_name').val(fullName);
            //     modal.find('#edit_role_id').val(roleId);
            //     modal.find('#edit_department_id').val(departmentId);

            //     modal.modal('show'); // Hiển thị modal
            // });
            </script>

            <script>
            $(document).on('click', '#addUser', function() {
                var username = $('#username').val();
                var password = $('#password').val();
                var email = $('#email').val();
                var roleId = $('#role_id').val();
                var fullName = $('#full_name').val();
                var departmentId = $('#department_id1').val();

                // Xuất thông tin để kiểm tra
                console.log('Add User Info:', {
                    username: username,
                    password: password,
                    email: email,
                    roleId: roleId,
                    fullName: fullName,
                    departmentId: departmentId
                });

                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_user.php', // Path to your insert handler
                    method: 'POST',
                    data: {
                        action: 'add_user',
                        username: username,
                        password: password,
                        email: email,
                        role_id: roleId,
                        full_name: fullName,
                        department_id: departmentId
                    },
                    success: function(response) {
                        if (response == 1) {

                            alert('Thêm tài khoản thành công!'); // Success message
                            location.reload(); // Reload the page to see the new user
                        } else {
                            alert('Lỗi khi thên tài khoản: ' +
                                response); // Show detailed error message
                            // Error message
                        }
                    },
                    error: function() {
                        alert('Đã xảy ra lỗi trong quá trình xử lý.'); // AJAX error handling
                    }
                });
            });


            // Handle change password
            $(document).on('click', '.change-password-btn', function() {
                var userId = $(this).data('id'); // Get user ID
                $('#change_user_id').val(userId); // Set the user ID in the modal
            });

            // Handle save changes for password
            $(document).on('click', '#changePassword', function() {
                var userId = $('#change_user_id').val();
                var newPassword = $('#new_password').val();
                var confirmPassword = $('#confirm_password').val();

                // Check if passwords match
                if (newPassword !== confirmPassword) {
                    alert('Mật khẩu không trùng khớp!');
                    return;
                }

                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_user.php', // Path to your change password handler
                    method: 'POST',
                    data: {
                        action: 'change_password',
                        id: userId,
                        password: newPassword // Send the new password
                    },
                    success: function(response) {
                        if (response == 1) {
                            alert('Thay đổi mật khẩu thành công!'); // Success message
                            location.reload(); // Reload the page to see the changes
                        } else {
                            alert('Thay đổi mật khẩu thất bạn.'); // Error message
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('Đã xảy ra lỗi trong quá trình xử lý.'); // AJAX error handling
                    }
                });
            });
            </script>

            <script>
            $(document).on('click', '.toggle-status-btn', function() {
                var userId = $(this).data('id');
                var isActive = $(this).data('is-active');


                var action = isActive === 1 ? 'dừng hoạt động' : 'mở hoạt động';
                var confirmationMessage = 'Bạn có chắc chắn muốn ' + action + ' tài khoản này không?';

                // Show confirmation dialog
                if (confirm(confirmationMessage)) {
                    $.ajax({
                        url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_user.php', // Path to your toggle handler
                        method: 'POST',
                        data: {
                            action: 'toggle_account_status',
                            id: userId,
                            is_active: isActive
                        },
                        success: function(response) {
                            if (response == 1) {
                                alert(
                                    'Tình trạng tài khoản đã được cập nhật thành công!'
                                ); // Success message
                                location.reload(); // Reload the page to see the updated status
                            } else {
                                alert('Lỗi khi cập nhật tình trạng tài khoản.'); // Error message
                            }
                        },
                        error: function() {
                            alert('Đã xảy ra lỗi trong quá trình xử lý.'); // AJAX error handling
                        }
                    });
                }
            });
            </script>




        </div>
        <?php 
            // include ("../../layout/main.php");
            include ("../layout/minisidebar.php");
            include ("../layout/footer.php");
        ?>



    </div>

    <!--Add the sidebar 's background. This div must be placed
                immediately after the control sidebar-- >
                <
                div class = "control-sidebar-bg" > < /div>

                    <
                    /div>  /
                wrapper-- >

                <
                !--jQuery 2.2 .0-- >

                <
                !--Bootstrap 3.3 .6-- >


                <
                !--FastClick-- >
                <
                script src = "../../assets/lib/plugins/fastclick/fastclick.js" >
                </script>
                 AdminLTE App -->
    <script src="../../assets/lib/dist/js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="../../assets/lib/plugins/sparkline/jquery.sparkline.min.js">
    </script>
    <!-- jvectormap -->
    <script src="../../assets/lib/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js">
    </script>
    <script src="../../assets/lib/plugins/jvectormap/jquery-jvectormap-world-mill-en.js">
    </script>
    <!-- SlimScroll 1.3.0 -->
    <script src="../../assets/lib/plugins/slimScroll/jquery.slimscroll.min.js">
    </script>
    <!-- ChartJS 1.0.1 -->
    <script src="../../assets/lib/plugins/chartjs/Chart.min.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="../../assets/lib/dist/js/pages/dashboard2.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../assets/lib/dist/js/demo.js"></script>
    <!--avgvwrbvrbw555555555555555555555555555-->
    <script src="../../plugins/jQuery/jQuery-2.2.0.min.js"></script>
    <!-- Bootstrap 3.3.6 -->

    <!-- DataTables -->
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="../../plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="../../assets/lib/dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../../assets/lib/dist/js/demo.js"></script>
    <!-- page script -->
    <script>
    $(function() {
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
    </script>
</body>






</html>




</html>



</html>


</html>


</html>