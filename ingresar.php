<?php
session_start();
require("./connect.php");
if (isset($_POST['placa'])) {
    $id = trim($_POST['placa']);
    $id = strip_tags($id);
    $id = htmlspecialchars($id);
    $sqlvehiculo = $conn->query("SELECT * FROM vehiculos WHERE PLACA='$id';");
    $countvehiculo = mysqli_num_rows($sqlvehiculo);
    if ($countvehiculo == 1) {
        $rowvehiculo = mysqli_fetch_assoc($sqlvehiculo);
        if ($rowvehiculo['TECNICO'] != "ACTIVO" || $rowvehiculo['SOAT'] != "ACTIVO") {
            $response[] = array(
                "error" => true,
                "status" => "alert-warning",
                "message" => "<strong>La placa \"$id\" presenta problemas.</strong><hr><p>Esto puede deberse por la no vigencia del seguro o la tecnicomecánica del vehículo.</strong></p>"
            );
        } else {
            $sqlantecedentes = $conn->query("SELECT * FROM antecedentes WHERE vehiculos_PLACA='$id' ORDER BY IDANTECEDENTE DESC LIMIT 1;");
            $countantecedentes = mysqli_num_rows($sqlantecedentes);
            if ($countantecedentes > 0) {
                $rowantecedente = mysqli_fetch_assoc($sqlantecedentes);
                $response[] = array(
                    "error" => true,
                    "status" => "alert-warning",
                    "message" => "<strong>La placa \"$id\" presenta antecedentes.</strong><hr><p>Antecedente más reciente: <strong>\"$rowantecedente[DESCANTECENTE]\".</strong></p>"
                );
            } else {
                $response[] = array(
                    "error" => false,
                    "status" => "alert-success",
                    "message" => "<strong>La placa \"$id\" es válida.</strong><hr><p>El vehículo es apto para el ingreso, ¿desea confirmar el ingreso o salida?</p>",
                );
            }
        }
        //$estadoPago = $rowPago['ESTADOPAGO'];
    } else {
        $response[] = array(
            "error" => true,
            "status" => "alert-danger",
            "message" => "<strong>La placa \"$id\" NO existe.</strong><hr><p>Por favor, verifique la placa del vehículo...</p>"
        );
    }
} else if ($_POST['placa'] == "" || $_POST['placa'] == null) {
    $response[] = array(
        "error" => true,
        "status" => "alert-danger",
        "message" => "<strong>No se ha ingresado ninguna placa.</strong><hr><p>Por favor, ingrese una placa de vehículo válida...</p>"
    );
}
echo json_encode($response);
