<?php
require("conexionmysqli.php");

ob_clean();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $sql = "UPDATE analisis_costos_nuevos SET estado = 0 WHERE id = $id";
    $resp = mysqli_query($enlaceCon, $sql);

    if ($resp) {
        echo json_encode(['status' => true, 'message' => 'Estado actualizado correctamente']);
    } else {
        echo json_encode(['status' => false, 'message' => 'Error al actualizar el estado']);
    }
} else {
    echo json_encode(['status' => false, 'message' => 'Solicitud inválida']);
}
