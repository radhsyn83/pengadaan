<?php
session_start();

if (isset($_SESSION["id"])) {
    header('Location: index.php');
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
    <link rel="stylesheet" href="plugins/noty/noty.min.css">
    <!-- CSS plugins goes here -->

    <!-- Main Style -->
    <link rel="stylesheet" href="css/style.min.css" id="main-css">
    <link rel="stylesheet" href="#" id="sidebar-css">

    <title>Mimity Admin</title>
</head>

<body class="login-page">

<div class="container pt-5">
    <div class="row justify-content-center">
        <div class="col-md-auto d-flex justify-content-center">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <!-- LOG IN FORM -->
                    <h4 class="card-title text-center mb-0">LOG IN</h4>
                    <div class="text-center text-muted font-italic">to your account</div>
                    <hr>
                    <form id="formLogin">
                        <div class="form-group">
                <span class="input-icon">
                  <i class="material-icons">person_outline</i>
                  <input type="text" class="form-control" name="username" placeholder="Username">
                </span>
                        </div>
                        <div class="form-group">
                <span class="input-icon">
                  <i class="material-icons">lock_open</i>
                  <input type="password" class="form-control" name="password" placeholder="Password">
                </span>
                        </div>
                        <!--              <div class="form-group d-flex justify-content-between align-items-center">-->
                        <!--                <div class="custom-control custom-checkbox">-->
                        <!--                  <input type="checkbox" class="custom-control-input" id="remember">-->
                        <!--                  <label class="custom-control-label" for="remember">Remember me</label>-->
                        <!--                </div>-->
                        <!--                <a href="#" class="text-primary text-decoration-underline small">Forgot password ?</a>-->
                        <!--              </div>-->
                        <button type="submit" name="login" class="btn btn-primary btn-block">LOG IN</button>
                    </form>
                    <!--            <hr>-->
                    <!--            <div class="small">-->
                    <!--              Don't have an account ?-->
                    <!--              <a href="#" class="text-decoration-underline">Register</a>-->
                    <!--            </div>-->
                    <!-- /LOG IN FORM -->

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Scripts -->
<script src="js/script.js"></script>

<!-- Plugins -->
<script src="plugins/noty/noty.min.js"></script>
<!-- JS plugins goes here -->
<script src="js/jquery-3.2.1.min.js"></script>
<script>
    $("#formLogin").on("submit", function (e) {
        e.preventDefault();
        $.ajax({
            url: "model/auth.php?login",
            data: $(this).serialize(),
            dataType: "JSON",
            method: "POST",
            success: function (data) {
                showNotif(data);

                if (data["error"] === 0) {
                    setTimeout(function () {
                        window.location.href = "index.php";
                    }, 1000);
                }
            }
        });
    });

    function showNotif(d) {
        if (d["error"] === 0) {
            new Noty({
                type: 'success',
                text: '<h5>' + d["msg"] + '</h5>Mohon menunggu.',
                timeout: 1000
            }).show()
        } else {
            new Noty({
                type: 'error',
                text: '<h5>' + d["msg"] + '</h5>Username dan Password tidak cocok.',
                timeout: 2000
            }).show();
        }
    }

</script>

</body>

</html>