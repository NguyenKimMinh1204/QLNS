<?php
include '../layout_manager/auth.php';
include '../../controller/class/class_KPI.php';
$a=new kpi_dep();
$user_id=$_SESSION['user_id'];
$department_id=$a->getUserDepartmentId($user_id);



?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Danh sách KPI</title>

    <link href="../../assets/employee/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/employee/css/datepicker3.css" rel="stylesheet">
    <link href="../../assets/employee/css/styles.css" rel="stylesheet">

    <!--Icons-->
    <script src="../../assets/employee/js/lumino.glyphs.js"></script>

    <!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->
    <style>
    .breadcrumb {
        border-radius: 0;
        padding: 27px 10px;
        background: #e9ecf2;
        box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
        margin: 0;
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
                <li><a href="#"><svg class="glyph stroked home">
                            <use xlink:href="#stroked-home"></use>
                        </svg></a></li>
                <li class="active">Quản lý KPI nhân viên</li>
            </ol>
        </div>

        <!--/.row-->

        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">Advanced Table</div>
                <div class="panel-body">

                    <?php
                            echo $user_id;
            
                            echo $department_id;
                            $user_id_ds=$a->getUsersByDepartmentId($department_id);
                                    
                    ?>
                    <form method="post" action="" id="userForm">
                        <div class="form-group">
                            <select class="form-control add_css" id="user_id_ds" name="user_id_ds" required
                                onchange="this.form.submit()">
                                <option value="0"
                                    <?php echo (!isset($_POST['user_id_ds']) || $_POST['user_id_ds'] == 0) ? 'selected' : ''; ?>>
                                    Chọn nhân viên
                                </option>
                                <?php
                                $users = $a->getUsersByDepartmentId($department_id); // Lấy danh sách nhân viên theo phòng ban
                                foreach ($users as $user) {
                                    $selected = (isset($_POST['user_id_ds']) && $_POST['user_id_ds'] == $user['id']) ? 'selected' : '';
                                    echo '<option value="' . htmlspecialchars($user['id']) . '" ' . $selected . '>' . htmlspecialchars($user['full_name']) . '</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </form>

                    <?php
                            // Nếu đã chọn nhân viên, hiển thị KPI cá nhân của nhân viên đó
                            if (isset($_POST['user_id_ds']) && $_POST['user_id_ds'] != 0) {
                                $user_id_ds = $_POST['user_id_ds'];

                                // Lấy thông tin nhân viên từ danh sách
                                $selected_user = array_filter($users, function ($u) use ($user_id_ds) {
                                    return $u['id'] == $user_id_ds;
                                });
                                $selected_user = reset($selected_user); // Lấy phần tử đầu tiên của mảng kết quả

                                echo '<h3>KPI Cá Nhân của ' . htmlspecialchars($selected_user['full_name']) . '</h3>';
                                $a->loadKPIByUserId($user_id_ds); // Hiển thị KPI cá nhân của nhân viên đã chọn
                            }
                            ?>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-11">


                </div>
            </div>
            <!--/.row-->
            <!-- Edit KPI Personal Modal -->
            <div class="modal fade" id="editKPIPersonalModal" tabindex="-1" role="dialog"
                aria-labelledby="editKPIPersonalModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editKPIPersonalModalLabel">Edit KPI</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="editKPIForm">
                            <div class="modal-body">
                                <input type="hidden" name="kpi_id" id="edit-kpi-id">
                                <div class="form-group">
                                    <label for="edit_user_id">Name</label>
                                    <select class="form-control" id="edit_user_id" name="edit_user_id" required>
                                        <?php
                                                $users = $a->getUsersByDepartmentId($department_id); // Fetch departments
                                                foreach ($users  as $user) {
                                                    echo '<option value="' . htmlspecialchars($user['id']) . '">' . htmlspecialchars(string: $user['full_name']) . '</option>';
                                                }
                                                ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit-kpi-name">KPI Name</label>
                                    <input type="text" class="form-control" id="edit-kpi-name" name="kpi_name">
                                </div>
                                <div class="form-group">
                                    <label for="edit-description">Description</label>
                                    <textarea class="form-control" id="edit-description" name="description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="edit-target">Target</label>
                                    <input type="number" class="form-control" id="edit-target" name="target">
                                </div>
                                <div class="form-group">
                                    <label for="edit-priority">Priority</label>
                                    <input type="text" class="form-control" id="edit-priority" name="priority">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Assign KPI Modal -->
            <!-- <div class="modal fade" id="assignKPIModal" tabindex="-1" role="dialog" aria-labelledby="assignKPIModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="assignKPIModalLabel">Assign KPI to User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="assignKPIForm">
                        <div class="modal-body">

                            <div class="form-group">
                                <label for="user_id">Name</label>
                                <select class="form-control" id="user_id" name="user_id" required>
                                    <?php
                                                // $users = $a->getUsersByDepartmentId($department_id); // Fetch departments
                                                // foreach ($users  as $user) {
                                                //     echo '<option value="' . htmlspecialchars($user['id']) . '">' . htmlspecialchars(string: $user['full_name']) . '</option>';
                                                // }
                                                ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="assign-kpi-name">KPI Name</label>
                                <input type="text" class="form-control" id="assign-kpi-name" name="kpi_name">
                            </div>
                            <div class="form-group">
                                <label for="assign-description">Description</label>
                                <textarea class="form-control" id="assign-description" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="assign-target">Target</label>
                                <input type="number" class="form-control" id="assign-target" name="target">
                            </div>
                            <div class="form-group">
                                <label for="assign-priority">Priority</label>
                                <input type="text" class="form-control" id="assign-priority" name="priority">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-success">Assign KPI</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> -->
            <div class="modal fade" id="assignKPIModal" tabindex="-1" role="dialog"
                aria-labelledby="assignKPIModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="assignKPIModalLabel">Assign KPI to User</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form id="assignKPIForm">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="user_id">Name</label>
                                    <select class="form-control" id="user_id" name="user_id" required>
                                        <?php
                                $users = $a->getUsersByDepartmentId($department_id); // Fetch users by department
                                foreach ($users as $user) {
                                    echo '<option value="' . htmlspecialchars($user['id']) . '">' . htmlspecialchars($user['full_name']) . '</option>';
                                }
                            ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="assign-kpi-name">KPI Name</label>
                                    <input type="text" class="form-control" id="assign-kpi-name" name="kpi_name"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="assign-description">Description</label>
                                    <textarea class="form-control" id="assign-description"
                                        name="description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="assign-target">Target</label>
                                    <input type="number" class="form-control" id="assign-target" name="target" required>
                                </div>
                                <div class="form-group">
                                    <label for="assign-value-target">Value Target</label>
                                    <input type="number" class="form-control" id="assign-value-target"
                                        name="value_target" required>
                                </div>
                                <div class="form-group">
                                    <label for="assign-priority">Priority</label>
                                    <input type="text" class="form-control" id="assign-priority" name="priority">
                                </div>
                                <div class="form-group">
                                    <label for="assign-end-date">End Date</label>
                                    <input type="date" class="form-control" id="assign-end-date" name="end_date"
                                        required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success">Assign KPI</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
        <script>
        // Populate Edit KPI Modal
        $('#editKPIPersonalModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var user_id = button.data('user_id');
            var kpi_name = button.data('kpi_name');
            var description = button.data('description');
            var target = button.data('target');
            var priority = button.data('priority');

            var modal = $(this);
            modal.find('#edit-kpi-id').val(id);
            modal.find('#edit-user-id').val(user_id);
            modal.find('#edit-kpi-name').val(kpi_name);
            modal.find('#edit-description').val(description);
            modal.find('#edit-target').val(target);
            modal.find('#edit-priority').val(priority);
        });
        </script>
        <script src="../../assets/employee/js/jquery-1.11.1.min.js"></script>
        <script src="../../assets/employee/js/bootstrap.min.js"></script>
        <script src="../../assets/employee/js/chart.min.js"></script>
        <script src="../../assets/employee/js/chart-data.js"></script>
        <script src="../../assets/employee/js/easypiechart.js"></script>
        <script src="../../assets/employee/js/easypiechart-data.js"></script>
        <script src="../../assets/employee/js/bootstrap-datepicker.js"></script>
        <script>
        $('#calendar').datepicker({});

        ! function($) {
            $(document).on("click", "ul.nav li.parent > a > span.icon", function() {
                $(this).find('em:first').toggleClass("glyphicon-minus");
            });
            $(".sidebar span.icon").find('em:first').addClass("glyphicon-plus");
        }(window.jQuery);

        $(window).on('resize', function() {
            if ($(window).width() > 768) $('#sidebar-collapse').collapse('show')
        })
        $(window).on('resize', function() {
            if ($(window).width() <= 767) $('#sidebar-collapse').collapse('hide')
        })
        </script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        $(document).ready(function() {
            // When a checkbox is clicked
            $('.dropdown-menu input[type="checkbox"]').on('change', function() {
                var field = $(this).data('field'); // Get the data-field value
                var isChecked = $(this).is(':checked'); // Check if it's checked

                // Toggle column visibility based on checkbox state
                $('table th, table td').each(function() {
                    if ($(this).attr('data-field') === field) {
                        $(this).toggle(isChecked);
                    }
                });
            });
        });
        </script>
</body>

</html>