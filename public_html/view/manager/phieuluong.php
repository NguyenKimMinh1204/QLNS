<?php
include '../layout_manager/auth.php';
include '../../controller/class/class_phieuluong.php';

$user_id = $_SESSION['user_id'];
$a = new phieuluong();

// Retrieve filter values from GET or set defaults
$month = isset($_GET['month']) ? (int)$_GET['month'] : date('n'); // Default to current month
$year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y'); // Default to current year

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Phiếu lương</title>

    <link href="../../assets/employee/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/employee/css/datepicker3.css" rel="stylesheet">
    <link href="../../assets/employee/css/styles.css" rel="stylesheet">
    <link href="../../assets/css/employee/info.css" rel="stylesheet">

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

    .table th {
        background-color: #6a329f;
        color: #fff;
        text-align: center;
    }

    .highlight {
        background-color: #c6f3f7;
    }

    .total {
        background-color: #6a329f;
        color: #fff;
        font-weight: bold;
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .header img {
        width: 100px;
        margin-bottom: 10px;
    }

    .header h1 {
        font-size: 24px;
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
    <?php
    //include ('../layout_employee/main.php');
    ?>
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="index.php"><svg class="glyph stroked home">
                            <use xlink:href="#stroked-home"></use>
                        </svg></a></li>
                <li class="active">Phiếu Lương</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-md-12">
                <form method="GET" action="" class="form-inline">
                    <div class="form-group">
                        <label for="month">Tháng:</label>
                        <select name="month" id="month" class="form-control">
                            <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?php echo $m; ?>" <?php echo ($m == $month) ? 'selected' : ''; ?>>
                                <?php echo $m; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="year">Năm:</label>
                        <select name="year" id="year" class="form-control">
                            <?php for ($y = 2020; $y <= date("Y"); $y++): ?>
                            <option value="<?php echo $y; ?>" <?php echo ($y == $year) ? 'selected' : ''; ?>>
                                <?php echo $y; ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Lọc</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-9">
                <div>
                    <div>
                        <div class="header">
                            <img src="../../assets/img/adsagency-logo-2.svg" alt="AdsAgency Logo">
                            <h1>PHIẾU LƯƠNG THÁNG
                                <?php echo str_pad($month, 2, '0', STR_PAD_LEFT); ?>/<?php echo $year; ?></h1>
                        </div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Nội dung</th>
                                    <th>Giá trị</th>
                                    <th>Ghi chú</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                        $a->loadphieuluong($user_id, $month, $year);
                    ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- <div class="header">
                        <img src="../../assets/img/adsagency-logo-2.svg" alt="AdsAgency Logo">
                        <h1>PHIẾU LƯƠNG THÁNG 09/2024</h1>
                        </div>
                        <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th>STT</th>
                            <th>Nội dung</th>
                            <th>Giá trị</th>
                            <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <td>1</td>
                            <td>Mã nhân viên</td>
                            <td>TTS 060</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>2</td>
                            <td>Họ và tên nhân viên</td>
                            <td>Huỳnh Phúc Hảo</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>3</td>
                            <td>Email</td>
                            <td>huynhphuchao782002@gmail.com</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>4</td>
                            <td>Chức danh công việc</td>
                            <td>TTS IT</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>5</td>
                            <td>Phòng</td>
                            <td>DEV</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>6</td>
                            <td>Lương chính thức</td>
                            <td>1.000.000</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>7</td>
                            <td>Ngày công tiêu chuẩn</td>
                            <td>21.0</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>8</td>
                            <td>Ngày công thực tế</td>
                            <td>14.0</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>9</td>
                            <td>Lương theo ngày công LVTT</td>
                            <td>666.667</td>
                            <td></td>
                            </tr>
                            <tr class="highlight">
                            <td>10</td>
                            <td>Lương theo KPIs</td>
                            <td>666.667</td>
                            <td>[1]</td>
                            </tr>
                            <tr>
                            <td>11</td>
                            <td>Hoa hồng</td>
                            <td>800.000</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>12</td>
                            <td>Thưởng</td>
                            <td></td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>13</td>
                            <td>Tiền gửi xe</td>
                            <td>96.000</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>14</td>
                            <td>Thu nhập khác</td>
                            <td></td>
                            <td></td>
                            </tr>
                            <tr class="highlight">
                            <td>15</td>
                            <td>Tổng phụ cấp + hoa hồng</td>
                            <td>896.000</td>
                            <td>[2]</td>
                            </tr>
                            <tr class="highlight">
                            <td>16</td>
                            <td>Tổng thu nhập</td>
                            <td>1.562.667</td>
                            <td>[3] = [1] + [2]</td>
                            </tr>
                            <tr>
                            <td>17</td>
                            <td>Tạm ứng</td>
                            <td>200.000</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>18</td>
                            <td>Phạt vi phạm nội quy</td>
                            <td></td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>19</td>
                            <td>Các khoản khác</td>
                            <td></td>
                            <td></td>
                            </tr>
                            <tr>
                            <td>20</td>
                            <td>Thuế TNCN</td>
                            <td>200.000</td>
                            <td>[4]</td>
                            </tr>
                            <tr class="highlight">
                            <td>21</td>
                            <td>Tổng các khoản bị trừ</td>
                            <td></td>
                            <td></td>
                            </tr>
                            <tr class="total">
                            <td>22</td>
                            <td>Thu nhập thực lãnh</td>
                            <td>1.362.667</td>
                            <td>[5] = [3] + [4]</td>
                            </tr>
                        </tbody>
                        </table>
                    </div> -->
            </div>
        </div>

    </div>

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
</body>

</html>