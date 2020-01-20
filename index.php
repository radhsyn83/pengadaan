<?php
session_start();

if (!isset($_SESSION["id"])) {
    header('Location: login.php');
}
?>


<!doctype html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Font & Icon -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i&display=swap"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:400,700" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">

    <!-- Plugins -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
    <link rel="stylesheet" href="plugins/noty/noty.min.css">
    <link rel="stylesheet" href="plugins/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="plugins/flatpickr/plugins/monthSelect/style.css">
    <link rel="stylesheet" href="plugins/clockpicker/bootstrap-clockpicker.min.css">
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="plugins/datatables/responsive.bootstrap4.min.css">

    <!-- Main Style -->
    <link rel="stylesheet" href="css/style.min.css" id="main-css">
    <link rel="stylesheet" href="#" id="sidebar-css">

    <title>Aplikasi Pengadaan Barang</title>
</head>

<body>

<!-- Sidebar -->
<div class="sidebar">

    <!-- Sidebar header -->
    <div class="sidebar-header">
        <a href="index.html" class="logo">
            <img src="img/logo.svg" alt="Logo" id="main-logo">
            <?= $_SESSION["jabatan"] ?>
        </a>
        <a href="#" class="nav-link nav-icon rounded-circle ml-auto" data-toggle="sidebar">
            <i class="material-icons">close</i>
        </a>
    </div>
    <!-- /Sidebar header -->

    <!-- Sidebar body -->
    <div class="sidebar-body">
        <ul class="nav nav-sub mt-3" id="menu">
            <li class="nav-item">
                <a class="nav-link has-icon active" href="javascript:void(0)"><i class="fa fa-home"></i>Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link has-icon" onclick="logout()" href="javascript:void(0)"><i class="material-icons">exit_to_app</i> Logout</a>
            </li>
        </ul>
    </div>
    <!-- /Sidebar body -->

</div>
<!-- /Sidebar -->

<!-- Main -->
<div class="main">

    <!-- Main header -->
    <div class="main-header">
        <a class="nav-link nav-icon rounded-circle" href="#" data-toggle="sidebar"><i
                    class="material-icons">menu</i></a>
        <ul class="nav nav-circle nav-gap-x-1 ml-auto"></ul>
        <ul class="nav nav-pills">
            <li class="nav-link-divider mx-2"></li>
            <li class="nav-item dropdown">
                <a class="nav-link has-img px-2" href="#">
                    <img src="img/user.svg" alt="Admin" class="rounded-circle mr-2">
                    <span class="d-none d-sm-block"><?= $_SESSION["nama"] ?></span>
                </a>
            </li>
        </ul>
    </div>
    <!-- /Main header -->

    <!-- Main body -->
    <div id="content" class="main-body"></div>
    <!-- /Main body -->

</div>
<!-- /Main -->

<!-- Backdrop for expanded sidebar -->
<div class="sidebar-backdrop" id="sidebarBackdrop" data-toggle="sidebar"></div>

<!-- Main Scripts -->
<script src="js/script.min.js"></script>
<script src="js/app.min.js"></script>

<!-- Plugins -->
<script src="plugins/jquery-sparkline/jquery.sparkline.min.js"></script>
<script src="plugins/chart.js/Chart.min.js"></script>
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<script src="plugins/noty/noty.min.js"></script>
<script src="plugins/bootbox/bootbox.min.js"></script>
<script src="plugins/flatpickr/flatpickr.min.js"></script>
<script src="plugins/flatpickr/plugins/monthSelect/index.js"></script>
<script src="plugins/clockpicker/bootstrap-clockpicker.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.bootstrap4.responsive.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.bootstrap4.responsive.min.js"></script>
<script src="plugins/dateformat.min.js"></script>

<script>
    let jabatanId = "<?= $_SESSION['id_jabatan'] ?>";

    $("#loadingBox").on("click", function (e) {

        let dialog = bootbox.dialog({
            message: `<div class="d-flex align-items-center">
                      <div class="spinner-border spinner-border-sm mr-2"></div> Mohon menunggu...
                    </div>`
        });
        setTimeout(() => {
            dialog.modal('hide')
        }, 2000)
    });

    $(document).ready(function () {
        if (jabatanId === "2") {
            loadPage("kepala.php");
        } else if (jabatanId === "3") {
            loadPage("staff.php");
        } else if (jabatanId === "1") {
            loadPage("manager.php");
        }
    });

    function loadPage(url) {
        $.ajax({
            url: url,
            success: function (msg) {
                $("#content").html(msg);
            }
        });
    }

    function menuOpen(identity, menu) {
        let url = "page/"+menu+".php";
        $.ajax({
            url: url,
            success: function (page) {
                resetMenuStaff();
                $("#staff-content").html(page);
                identity.addClass("active");
            }
        });
    }

    function logout() {
        $.ajax({
            url: "model/auth.php?logout",
            dataType: "JSON",
            method: "POST",
            success: function () {
                window.location.href = "login.php";
            }
        });
    }

</script>
</body>

</html>