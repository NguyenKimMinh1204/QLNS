<?php
include '../layout_employee/auth.php';
include('../../controller/class/class_chamcong_e.php');
$a = new chamconge();
$user_id = $_SESSION['user_id'];

// Lấy địa chỉ IP Wi-Fi
// $wifi_address = $a->getWiFiIPv4(); // Bỏ dòng này đi
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lumino - Dashboard</title>

    <link href="../../assets/employee/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../assets/employee/css/datepicker3.css" rel="stylesheet">
    <link href="../../assets/employee/css/styles.css" rel="stylesheet">

    <!--Icons-->
    <script src="../../assets/employee/js/lumino.glyphs.js"></script>

    <!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->

</head>

<body>
    <?php
    include ('../layout_employee/header.php');
    ?>
    <?php
    include ('../layout_employee/sidebar.php');
    ?>
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="#"><svg class="glyph stroked home">
                            <use xlink:href="#stroked-home"></use>
                        </svg></a></li>
                <li class="active">Chấm công</li>
            </ol>
        </div>
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Chấm Công</h3>
                    </div>
                    <div class="panel-body text-center">
                        <div class="clock-display">
                            <h2 id="current-time">00:00:00</h2>
                            <p id="current-date"></p>
                        </div>
                        <div class="attendance-buttons">
                            <button id="clockInButton" class="btn btn-success btn-lg">
                                <i class="glyphicon glyphicon-log-in"></i> Chấm Công Vào
                            </button>
                            <button id="clockOutButton" class="btn btn-danger btn-lg">
                                <i class="glyphicon glyphicon-log-out"></i> Chấm Công Ra
                            </button>
                        </div>
                        <div id="attendance-status" class="alert" style="display: none; margin-top: 15px;"></div>
                        <div class="attendance-times" style="margin-top: 20px;">
                            <div id="clock-in-time"></div>
                            <div id="clock-out-time"></div>
                        </div>
                        <div id="ipDisplay"></div> <!-- Thêm div để hiển thị IP -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Lấy địa chỉ IP cục bộ
    async function getLocalIP() {
        const pc = new RTCPeerConnection();
        pc.createDataChannel('');
        pc.createOffer().then((offer) => pc.setLocalDescription(offer));
        pc.onicecandidate = (event) => {
            if (event && event.candidate && event.candidate.candidate) {
                const candidate = event.candidate.candidate;
                const ipRegex = /([0-9]{1,3}(\.[0-9]{1,3}){3})/;
                const ipMatch = candidate.match(ipRegex);
                if (ipMatch) {
                    document.getElementById('ipDisplay').innerText = 'Local IP: ' + ipMatch[1];
                    pc.close();
                }
            }
        };
    }
    getLocalIP();

    // Update clock
    function updateClock() {
        const now = new Date();
        document.getElementById('current-time').textContent = now.toLocaleTimeString();
        document.getElementById('current-date').textContent = now.toLocaleDateString();
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Handle attendance status display
    function showStatus(message, isSuccess) {
        const statusDiv = document.getElementById('attendance-status');
        statusDiv.className = 'alert ' + (isSuccess ? 'alert-success' : 'alert-danger');
        statusDiv.textContent = message;
        statusDiv.style.display = 'block';
        setTimeout(() => statusDiv.style.display = 'none', 3000);
    }

    // Function to update attendance status display
    function updateAttendanceDisplay() {
        fetch("controller_chamcong_e.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=get_status'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const clockInTime = new Date(data.data.clock_in_time);
                    document.getElementById('clock-in-time').innerHTML =
                        `<strong>Giờ vào:</strong> ${clockInTime.toLocaleTimeString()}`;

                    if (data.data.clock_out_time) {
                        const clockOutTime = new Date(data.data.clock_out_time);
                        document.getElementById('clock-out-time').innerHTML =
                            `<strong>Giờ ra:</strong> ${clockOutTime.toLocaleTimeString()}`;
                        document.getElementById("clockInButton").disabled = true;
                        document.getElementById("clockOutButton").disabled = true;
                    } else {
                        document.getElementById('clock-out-time').innerHTML = '';
                        document.getElementById("clockInButton").disabled = true;
                        document.getElementById("clockOutButton").disabled = false;
                    }
                } else {
                    document.getElementById('clock-in-time').innerHTML = '';
                    document.getElementById('clock-out-time').innerHTML = '';
                    document.getElementById("clockInButton").disabled = false;
                    document.getElementById("clockOutButton").disabled = true;
                }
            });
    }

    // Call updateAttendanceDisplay initially and after each action
    updateAttendanceDisplay();

    // Clock In handler
    document.getElementById("clockInButton").addEventListener("click", function() {
        const wifiAddress = document.getElementById('ipDisplay').innerText.split(': ')[1]; // Lấy địa chỉ IP từ div
        fetch("controller_chamcong_e.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=clock_in&wifi_address=${wifiAddress}` // Sử dụng địa chỉ IP Wi-Fi đã lấy
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatus("Chấm công vào thành công!", true);
                    updateAttendanceDisplay();
                } else {
                    showStatus(data.message || "Chấm công vào thất bại!", false);
                }
            });
    });

    // Clock Out handler
    document.getElementById("clockOutButton").addEventListener("click", function() {
        const wifiAddress = document.getElementById('ipDisplay').innerText.split(': ')[1]; // Lấy địa chỉ IP từ div
        fetch("controller_chamcong_e.php", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=clock_out&wifi_address=${wifiAddress}` // Bao gồm địa chỉ Wi-Fi trong yêu cầu
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStatus("Chấm công ra thành công!", true);
                    updateAttendanceDisplay();
                } else {
                    showStatus(data.message || "Chấm công ra thất bại!", false);
                }
            });
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
</body>

</html>