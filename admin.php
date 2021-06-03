<?php
if (isset($_GET['logout'])) {
    session_start();
    unset($_SESSION['admin']);
    session_unset();
    session_destroy();
    header("Location: ./");
    exit;
}
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
    <script src="./js/tesseract.min.js"></script>
    <script src="./js/bootstrap-material-design.js"></script>
    <!--script>$(document).ready(function() { $('body').bootstrapMaterialDesign(); });</script-->
</head>
<?php
require("./functions.php");
?>
<?php
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    echo '<script type="text/javascript"> window.location = "index.php"; </script>';
}
if (isset($_POST['btn-ingreso-in'])) {
    $placa = trim($_POST['placa']);
    $placa = strip_tags($placa);
    $placa = htmlspecialchars($placa);
    $ingreso = trim($_POST['btn-ingreso-in']);
    $ingreso = strip_tags($ingreso);
    $ingreso = htmlspecialchars($ingreso);
    $currentDate = date("Y-m-d H:i:s");
    $sql = $conn->query(" INSERT INTO ingresos (TIPO, FECHA, DESCRIPCION, vehiculos_PLACA) VALUES ('$ingreso', '$currentDate', '$ingreso CON ÉXITO.', '$placa'); ");
    if ($sql) {
        messageModal("REGISTRAR", "Registro de \"$ingreso\" realizado correctamente.", "", "success");
    } else {
        messageModal("ERROR", "Algo salió mal. Intente más tarde.<br><small>Error $conn->error</small>", "", "danger");
    }
}
if (isset($_POST['btn-ingreso-out'])) {
    $placa = trim($_POST['placa']);
    $placa = strip_tags($placa);
    $placa = htmlspecialchars($placa);
    $ingreso = trim($_POST['btn-ingreso-out']);
    $ingreso = strip_tags($ingreso);
    $ingreso = htmlspecialchars($ingreso);
    $currentDate = date("Y-m-d H:i:s");
    $sql = $conn->query(" INSERT INTO ingresos (TIPO, FECHA, DESCRIPCION, vehiculos_PLACA) VALUES ('$ingreso', '$currentDate', '$ingreso CON ÉXITO.', '$placa'); ");
    if ($sql) {
        messageModal("REGISTRAR", "Registro de \"$ingreso\" realizado correctamente.", "", "success");
    } else {
        messageModal("ERROR", "Algo salió mal. Intente más tarde.<br><small>Error $conn->error</small>", "", "danger");
    }
}
#---Tabla 'usuarios'---#
$sqlusuario = $conn->query(" SELECT * FROM usuarios WHERE IDUSUARIO='$_SESSION[admin]'; ");
$datosusuario = mysqli_fetch_assoc($sqlusuario);
$countusuario = mysqli_num_rows($sqlusuario);
#---Tabla 'ingresos'---#
$sqlingreso = $conn->query(" SELECT * FROM ingresos ORDER BY IDINGRESO DESC; ");
#---Tabla 'vehiculos'---#
$sqlvehiculo = $conn->query(" SELECT * FROM vehiculos; ");
#---Tabla 'antecedentes'---#
$sqlantecedente = $conn->query(" SELECT * FROM antecedentes ORDER BY IDANTECEDENTE DESC; ");

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
                <li class="nav-item"><a class="btn btn-dark" href="#inout"><i class="fas fa-sign-in-alt"></i>Ingresos & Salidas</a></li>
                <li class="nav-item"><a class="btn btn-dark" href="#vehicles"><i class="fas fa-bus"></i>Vehículos</a></li>
                <li class="nav-item"><a class="btn btn-dark" href="#history"><i class="fas fa-times-circle"></i>Antecedentes</a></li>
            </ul>
            <ul class="navbar-nav mt-1 text-right">
                <li class="nav-item"><a class="btn btn-dark" href="?logout"><i class="fas fa-sign-out-alt"></i>Cerrar sesión (<?= $datosusuario['IDUSUARIO']; ?> - "<?= $datosusuario['NOMBREUSUARIO']; ?>")</a></li>
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
                <div id="home"></div>
                <hr class="">
                <h1><i class="fas fa-home"></i>BIENVENIDO, "<?= $datosusuario['NOMBREUSUARIO']; ?>".</h1>
                <form id="verif-form" name="verif-form" class="form-padding" action="" method="POST">
                    <div class="row form-padding">
                        <div class="col-12 col-md-5 col-lg-4 col-xl-3 mb-3">
                            <canvas id="canvasWebcam" width="1280px" height="720px" style="display:none;opacity:0;"></canvas>
                            <video autoplay="true" id="videoWebcam" style="width: 100%; height: 100%;">El navegador no soporta el componente de vídeo HTML5</video>
                        </div>
                        <div class="row col-12 col-md-7 col-lg-8 col-xl-9">
                            <div class="col-12"><a class="btn-block btn-material" href="javascript:analyzeTextFromImage();">ANALIZAR</a></div>
                            <div class="col-12">
                                <div class="group"><input type="text" id="placa" name="placa" required /><span class="highlight"></span><span class="bar"></span><label>Ingrese la placa del vehículo</label></div>
                            </div>
                            <div id="verif-response" name="verif-response" class="col-12 alert"></div>
                            <div id="btn-ingreso-in" class="col-6" style="display: none; opacity: 0;"><input class="btn-block btn-material" type="submit" name="btn-ingreso-in" value="ENTRADA" /></div>
                            <div id="btn-ingreso-out" class="col-6" style="display: none; opacity: 0;"><input class="btn-block btn-material" type="submit" name="btn-ingreso-out" value="SALIDA" /></div>
                        </div>
                    </div>
                </form>
                <hr class="">
                <div id="inout"></div>
                <hr class="">
                <h1><i class="fas fa-sign-in-alt"></i>INGRESOS Y SALIDAS</h1>
                <?php
                while ($datosingreso = mysqli_fetch_assoc($sqlingreso)) {
                ?>
                    <div class="row form-padding form-top-border">
                        <div class="col-6 col-md-2 col-lg-2">
                            <small>ID:</small><br><b><?= $datosingreso['IDINGRESO']; ?></b>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2 mb-2">
                            <small>Placa del vehículo:</small><br><b><?= $datosingreso['vehiculos_PLACA'] ?></b>
                        </div>
                        <div class="col-6 col-md-2 col-lg-2">
                            <small>Tipo:</small><br><b><?= $datosingreso['TIPO']; ?></b>
                        </div>
                        <div class="col-6 col-md-4 col-lg-2 mb-2">
                            <small>Fecha <small>(aaaa-mm-dd hh:mm:ss):</small></small><br><b><?= $datosingreso['FECHA'] ?></b>
                        </div>
                        <div class="col-12 col-md-12 col-lg-4 mb-2">
                            <small>Descripción:</small><br><b><?= $datosingreso['DESCRIPCION'] ?></b>
                        </div>
                    </div>
                <?php
                }
                ?>
                <hr class="">
                <div id="vehicles"></div>
                <hr class="">
                <h1><i class="fas fa-bus"></i>VEHÍCULOS</h1>
                <?php
                while ($datosvehiculo = mysqli_fetch_assoc($sqlvehiculo)) {
                ?>
                    <div class="row form-padding form-top-border">
                        <div class="col-6 col-md-2">
                            <small>Placa:</small><br><b><?= $datosvehiculo['PLACA']; ?></b>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <small>Tipo de servicio:</small><br><b><?= $datosvehiculo['TIPO_SERVICIO']; ?></b>
                        </div>
                        <div class="col-6 col-md-2">
                            <small>Estado:</small><br><b><?= $datosvehiculo['ESTADO']; ?></b>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <small>Marca:</small><br><b><?= $datosvehiculo['MARCA']; ?></b>
                        </div>
                        <div class="col-6 col-md-2">
                            <small>Línea:</small><br><b><?= $datosvehiculo['LINEA']; ?></b>
                        </div>
                        <div class="col-6 col-md-2 mb-2">
                            <small>Modelo:</small><br><b><?= $datosvehiculo['MODELO']; ?></b>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <small>Cilindraje:</small><br><b><?= $datosvehiculo['CILINDRAJE'] ?></b>
                        </div>
                        <div class="col-6 col-md-2 mb-2">
                            <small>Color:</small><br><b><?= $datosvehiculo['COLOR'] ?></b>
                        </div>
                        <div class="col-6 col-md-3 mb-2">
                            <small>Tecnicomecánica:</small><br><b><?= $datosvehiculo['TECNICO'] ?></b>
                        </div>
                        <div class="col-6 col-md-2 mb-2">
                            <small>SOAT - seguro:</small><br><b><?= $datosvehiculo['SOAT'] ?></b>
                        </div>
                    </div>
                <?php
                }
                ?>
                <hr class="">
                <div id="history"></div>
                <hr class="">
                <h1><i class="fas fa-times-circle"></i>ANTECEDENTES</h1>
                <?php
                while ($datosantecedente = mysqli_fetch_assoc($sqlantecedente)) {
                ?>
                    <div class="row form-padding form-top-border">
                        <div class="col-6 col-md-2">
                            <small>ID:</small><br><b><?= $datosantecedente['IDANTECEDENTE']; ?></b>
                        </div>
                        <div class="col-6 col-md-4 mb-2">
                            <small>Placa del vehículo:</small><br><b><?= $datosantecedente['vehiculos_PLACA'] ?></b>
                        </div>
                        <div class="col-12 col-md-6">
                            <small>Descripción del antecedente:</small><br><b><?= $datosantecedente['DESCANTECENTE']; ?></b>
                        </div>
                    </div>
                <?php
                }
                ?>
                <hr class="">
                <hr class="">
                <h1><i class="fas fa-sign-out-alt"></i>CERRAR SESIÓN (<?= $_SESSION['admin']; ?> - "<?= $datosusuario['NOMBREUSUARIO']; ?>")</h1>
                <div class="row form-padding">
                    <div class="col-12"><a class="btn-block btn-material" href="?logout"><i class="fas fa-sign-out-alt"></i>Cerrar sesión (<?= $datosusuario['IDUSUARIO']; ?> - "<?= $datosusuario['NOMBREUSUARIO']; ?>")</a></li>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="./ingresar.js"></script>
</body>

</html>