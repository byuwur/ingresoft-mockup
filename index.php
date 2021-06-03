<?php
session_start();
require("./connect.php");
?>
<!doctype html>
<html lang="es">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Material Design for Bootstrap fonts and icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons">
    <!-- Material Design for Bootstrap CSS -->
    <link rel="stylesheet" href="./css/bootstrap-material-design.css">
    <link rel="stylesheet" href="./css/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="./css/main.css">
    <link rel="icon" href="./img/escudo-policia.png">
    <title>Ingresoft General Santander</title>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="./js/jquery-3.5.1.min.js"></script>
    <script src="./js/popper.js"></script>
    <script src="./js/bootstrap-material-design.js"></script>
    <!--script>$(document).ready(function() { $('body').bootstrapMaterialDesign(); });</script-->
</head>
<?php
require("./functions.php");
?>
<?php
if (isset($_SESSION['admin'])) {
    header("Location: admin.php");
    echo '<script type="text/javascript"> window.location = "admin.php"; </script>';
}
if (isset($_POST['btn-login'])) {
    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);
    $pass = trim($_POST['password']);
    $pass = strip_tags($pass);
    $pass = htmlspecialchars($pass);
    if (empty($email)) {
        messageModal("ERROR", "Ingrese su usuario.", "", "danger");
        exit;
    }
    if (empty($pass)) {
        messageModal("ERROR", "Ingrese su contraseña.", "", "danger");
        exit;
    }
    $res = $conn->query("SELECT * FROM usuarios WHERE IDUSUARIO = '$email'; ");
    $count = mysqli_num_rows($res);
    if ($count == 1) {
        $row = mysqli_fetch_assoc($res);
        if ($pass == $row['PASSUSUARIO']) {
            $_SESSION['admin'] = $row['IDUSUARIO'];
            header("Location: admin.php");
            echo '<script type="text/javascript"> window.location = "admin.php"; </script>';
        } else {
            messageModal("ERROR", "Credenciales incorrectas. Intente de nuevo.", "", "danger");
            exit;
        }
    } else {
        messageModal("ERROR", "No hay coincidencias. Revise sus credenciales.", "", "danger");
        exit;
    }
}
?>

<body>
    <!-- Top Nav -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
        <a href="#home" class="navbar-brand"><img src="./img/escudo-policia.png" width="32px" height="32px" /><strong class="navbar-toggler">INGRESOFT</strong></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="topNavbar">
            <ul class="navbar-nav mr-auto mt-1">
                <li class="nav-item"><a class="btn btn-dark" href="#home"><i class="fas fa-home"></i>Inicio</a></li>
            </ul>
        </div>
    </nav>
    <!-- Top Nav -->
    <!-- Bottom Nav -->
    <nav class="navbar fixed-bottom navbar-expand navbar-dark bg-dark text-white m-0">
        <small class="text-right">&copy; MOCKUP <?= date("Y"); ?> <a href="http://somosmnm.000webhostapp.com/mateus/" style="color:#AAA">Mateus [byUwUr]</a> &middot; <small>Para ESCUELA GENERAL SANTANDER &middot;</small> <a href="./privacy_policy" style="color:#AAA">Política de Privacidad</a></small>
    </nav>
    <!-- Bottom Nav -->
    <!-- Content Here -->
    <div class="main">
        <div class="container text-center">
            <div class="content">
                <form class="form-padding" action="" method="POST">
                    <hr><img src="./img/ingresoft.png" width="512" height="192" /><hr>
                    <h1><i class="fas fa-sign-in-alt"></i>INGRESAR</h1>
                    <div class="row form-padding">
                        <div class="col-12 col-sm-4"></div>
                        <div class="col-12 col-sm-4">
                            <div class="group"><input type="text" id="email" name="email" required /><span class="highlight"></span><span class="bar"></span><label>Usuario</label></div>
                        </div>
                        <div class="col-12 col-sm-4"></div>
                    </div>
                    <div class="row form-padding">
                        <div class="col-12 col-sm-4"></div>
                        <div class="col-12 col-sm-4">
                            <div class="group"><input type="password" id="password" name="password" required /><span class="highlight"></span><span class="bar"></span><label>Contraseña</label></div>
                        </div>
                        <div class="col-12 col-sm-4"></div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-4"></div>
                        <div class="col-12 col-sm-4">
                            <input class="btn-block btn-material" type="submit" name="btn-login" value="INICIAR SESIÓN" />
                        </div>
                        <div class="col-12 col-sm-4"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>