<?php
include '../layout/auth.php';

include '../../controller/class/class_viewProgress_a.php';
$a=new tientrinh_a();


$user_id=$_SESSION['user_id'];
$dept_kpi_id = $_GET['dept_kpi_id'];

?>
<!DOCTYPE html>
<html>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tiến trình</title>
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
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>

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

    <script>
    $(document).ready(function() {
        $('.table').DataTable(); // Khởi tạo DataTable cho bảng

        // Xử lý sự kiện khi chọn phòng ban
        $('#department_id_ds').change(function() {
            var departmentId = $(this).val(); // Lấy ID phòng ban đã chọn
            $.ajax({
                url: 'https://www.quanlynhansuads.io.vn/view/admin/KPI_dep.php', // Adjust the URL as needed
                method: 'POST',
                data: {
                    department_id_ds: departmentId
                },
                success: function(response) {
                    $('.container.mt-5.hight1.width1').html(
                        response); // Cập nhật danh sách người dùng
                },
                error: function() {
                    alert('Error fetching KPIs.'); // Thông báo lỗi
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

    .back {
        position: absolute;

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
                <div class="row">
                    <div class="back"><a href="quanlykpi_phong.php"><i
                                class="fa fa-fw fa-arrow-circle-left"></i>back</a>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-3" style="margin-left:20px">
                        <h2>Xem tiến trình</h2>
                    </div>

                </div>
                <div class="row">





                </div>




            </div>
            <div class="container mt-5 hight1 width1">


                <?php 
                
                $a->loadekpi($dept_kpi_id);

                

                    ?>







            </div>

            <!-- Modal để xem tiến độ KPI -->
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

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            // $('#selectProgressModal').on('show.bs.modal', function(event) {
            //     var button = $(event.relatedTarget);
            //     var empKpiId = button.data('emp-kpi-id');

            //     // Gửi Ajax request
            //     $.ajax({
            //         url: 'http://localhost:8080/KLTN/controller/class/controller_r_kpi.php',
            //         method: 'POST',
            //         data: {
            //             action: 'get_single_progress',
            //             emp_kpi_id: empKpiId
            //         },
            //         success: function(response) {
            //             var data = JSON.parse(response);
            //             if (data) {
            //                 $('#modal_progress_update').text(data.progress_update ||
            //                     'Không có dữ liệu');
            //                 $('#modal_result_detail').text(data.result_detail ||
            //                     'Không có dữ liệu');
            //                 $('#modal_updated_at').text(data.updated_at || 'Không có dữ liệu');
            //             } else {
            //                 $('#modal_progress_update').text('Không có dữ liệu');
            //                 $('#modal_result_detail').text('Không có dữ liệu');
            //                 $('#modal_updated_at').text('Không có dữ liệu');
            //             }
            //         },
            //         error: function() {
            //             alert('Có lỗi xảy ra khi tải dữ liệu');
            //         }
            //     });
            // });
            </script>
            <script>
            // Khi mở modal để xem tiến độ KPI
            $('#selectProgressModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Lấy nút đã nhấn
                var empKpiId = button.data('emp-kpi-id'); // Lấy emp_kpi_id từ data-attribute

                // Gọi hàm để lấy tiến độ KPI
                $.ajax({
                    url: 'https://www.quanlynhansuads.io.vn/controller/class/controller_viewProgress_a.php', // Đảm bảo URL chính xác
                    method: 'POST',
                    data: {
                        action: 'get_single_progress',
                        emp_kpi_id: empKpiId
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        console.log(data); // Kiểm tra dữ liệu trả về
                        var tbody = $('#progressTableBody');
                        tbody.empty(); // Xóa dữ liệu cũ

                        if (data.length > 0) {
                            data.forEach(function(item) {
                                tbody.append('<tr>' +
                                    '<td>' + item.updated_at + '</td>' +
                                    '<td>' + item.progress_update + '</td>' +
                                    '<td>' + item.result_detail + '</td>' +
                                    '</tr>');
                            });
                        } else {
                            tbody.append(
                                '<tr><td colspan="3" class="text-center">Không có dữ liệu</td></tr>'
                            );
                        }
                    },
                    error: function() {
                        alert('Có lỗi xảy ra khi tải dữ liệu');
                    }
                });
            });
            </script>





        </div>

    </div>

    <?php 
            // include ("../../layout/main.php");
            include ("../layout/minisidebar.php");
            include ("../layout/footer.php");
        ?>


    <!--Add the sidebar 's background. This div must be placed
                immediately after the control sidebar-- >
                <
                div class = "control-sidebar-bg" > < /div>

                    <
                    /div> . /
                wrapper-- >

                <
                !--jQuery 2.2 .0-- >

                <
                !--Bootstrap 3.3 .6-- >


                <
                !--FastClick-- > -->
    <script src="../../assets/lib/plugins/fastclick/fastclick.js">
    </script>
    <!-- AdminLTE App -->
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