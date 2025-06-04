<?php
include '../layout/auth.php';
?>
<?php
include '../../controller/class/class_chamcong_admin.php';
$a=new wifi();

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Quản lý chấm công</title>
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- With the full version -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
    </script>
    <!-- Bootstrap và jQuery -->
    <!-- Replace this slim version -->
    <script src="https://code.jquery.com/jquery-3.7.1.slim.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- With the full version -->
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> -->

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js">
    </script>
    <link rel="stylesheet" href="../../assets/css/employee/main.css">
    <style>
    .dieuhuong {
        position: relative;

    }

    .mt-5 {
        margin: 10px;

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
            <div class="container-fluid">
                <div class="row dieuhuong mt-5">
                    <a href="index.php"><i class="fa fa-dashboard"></i> Home</a><i
                        class="fa fa-fw fa-chevron-right"></i>
                    <a href="#">Quản lý địa chỉ wifi</a>

                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <h2>Quản lý địa chỉ wifi</h2>
                    </div>

                </div>
                <div class="row">

                    <div class="col-sm-1"><button class="btn btn-success mt-3 add_css" data-toggle="modal"
                            data-target="#addWifiModal">Thêm địa chỉ wifi
                        </button></div>
                </div>
                <div class="row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-8"> <?php $a->loaddulieu(); ?></div>
                </div>



                <!-- Modal for Editing Wi-Fi Address -->
                <!-- Edit WiFi Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit WiFi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="editWiFiForm">
                                    <input type="hidden" id="edit-id" name="id">
                                    <div class="form-group">
                                        <label for="edit-ssid">SSID</label>
                                        <input type="text" class="form-control" id="edit-ssid" name="ssid" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="ip_address">IP Address</label>
                                        <input type="text" class="form-control" id="ip_address" name="ip_address"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <input type="text" class="form-control" id="description" name="description">
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="saveChanges">Save changes</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Modal for Adding Wi-Fi Address -->
                <!-- Add WiFi Modal -->
                <div class="modal fade" id="addWifiModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addModalLabel">Add New WiFi</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="addWiFiForm">
                                    <div class="form-group">
                                        <label for="add-ssid">SSID</label>
                                        <input type="text" class="form-control" id="add-ssid" name="ssid" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="add-ip_address">IP Address</label>
                                        <input type="text" class="form-control" id="add-ip_address" name="ip_address"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="add-description">Description</label>
                                        <input type="text" class="form-control" id="add-description" name="description">
                                    </div>
                                    <button type="submit" class="btn btn-primary" id="addwifi">Add WiFi</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <script>
            $(document).ready(function() {
                $('.table').DataTable();
            });
            </script>
            <script>
            // $(document).ready(function() {
            // Handle the "Edit WiFi" modal
            $('#editModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data('id');
                var ssid = button.data('ssid');
                var ip = button.data('ip');
                var description = button.data('description');

                // Populate the modal with data from the button
                var modal = $(this);
                modal.find('#edit-id').val(id);
                modal.find('#edit-ssid').val(ssid);
                modal.find('#ip_address').val(ip);
                modal.find('#description').val(description);
            });

            $(document).on('click', '#saveChanges', function() {
                var Id = $('#edit-id').val();
                var ssid = $('#edit-ssid').val();
                var ip_address = $('#ip_address').val();
                var description = $('#description').val();

                console.log({
                    Id,
                    ssid,
                    ip_address,
                    description
                }); // Kiểm tra dữ liệu gửi đi

                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_chamcong_admin.php',
                    method: 'POST',
                    data: {
                        action: 'updatewf',
                        Id: Id,
                        ssid: ssid,
                        ip_address: ip_address,
                        description: description
                    },
                    success: function(response) {
                        console.log(response); // Kiểm tra phản hồi
                        if (response === '1') {
                            alert('Cập nhật thông tin wifi thành công!');
                            location.reload();
                        } else {
                            alert('Lỗi khi cập nhật thông tin wifi.');
                            location.reload();

                        }
                    }
                });
            })

            // Submit the "Add WiFi" form via AJAX
            $(document).on('click', '#addwifi', function(event) {
                event.preventDefault(); // Ngăn chặn hành vi mặc định của nút submit
                var ssid = $('#add-ssid').val(); // Sửa ID để lấy giá trị từ trường đúng
                var ip_address = $('#add-ip_address').val(); // Sửa ID để lấy giá trị từ trường đúng
                var description = $('#add-description').val(); // Sửa ID để lấy giá trị từ trường đúng

                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_chamcong_admin.php',
                    method: 'POST',
                    data: {
                        action: 'addwf',
                        ssid: ssid,
                        ip_address: ip_address,
                        description: description,
                    },
                    success: function(response) {

                        if (response === '2') {
                            alert('vui lòng nhập đầy đủ thông tin');
                            // Tải lại trang để xem sự thay đổi
                        }
                        if (response === '1') {
                            alert('Thêm thông tin wifi thành công!');
                            location.reload(); // Tải lại trang để xem sự thay đổi
                        } else {
                            alert('Lỗi khi thêm thông tin wifi.');
                            location.reload();

                        }
                    }

                });
            });

            // Handle WiFi delete action via AJAX
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id'); // Get WiFi ID from the button data attribute

                if (confirm('Are you sure you want to delete this WiFi address?')) {
                    $.ajax({
                        url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_chamcong_admin.php', // Replace with your actual controller path
                        type: 'POST',
                        data: {
                            action: 'delete',
                            id: id
                        },
                        success: function(response) {
                            if (response === '1') {
                                alert('WiFi deleted successfully');
                                location
                                    .reload(); // Reload the page to show updated data
                            } else {
                                alert('Failed to delete WiFi');
                            }
                        }
                    });
                }
            });
            // });
            </script>
        </div>





    </div>

    <?php 
            // include ("../../layout/main.php");
            include ("../layout/minisidebar.php");
            include ("../layout/footer.php");
        ?>

    <!-- Content Wrapper. Contains page content -->

    <!-- /.content-wrapper -->


    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>

    </div>
    <!-- ./wrapper -->

    <!-- jQuery 2.2.0 -->

    <script src="../../assets/lib/plugins/fastclick/fastclick.js"></script>
    <!-- AdminLTE App -->
    <script src="../../assets/lib/dist/js/app.min.js"></script>
    <!-- Sparkline -->
    <script src="../../assets/lib/plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="../../assets/lib/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="../../assets/lib/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- SlimScroll 1.3.0 -->
    <script src="../../assets/lib/plugins/slimScroll/jquery.slimscroll.min.js"></script>
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