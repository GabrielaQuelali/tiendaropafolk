<?php
require("conexionmysqlipdf.inc");
require("funciones.php");
require("funcion_nombres.php");
require("estilos_almacenesAjax.php");

$codProducto=$_POST["codProducto"];
$jsonProducto=$_POST["jsonProducto"];


$procesos = json_decode($jsonProducto, true);

// Verificar si la decodificación fue exitosa
if (json_last_error() !== JSON_ERROR_NONE) {
    echo "Error al decodificar el JSON: " . json_last_error_msg();
    exit;
}

// Paso 3: Generar la tabla HTML
echo "<center><table border='1' width='100%' align='center'>
        <tr>
            <th>Proceso</th>
            <th>Costo</th>
        </tr>";

$totalCosto = 0;

// Iterar sobre los datos y generar filas de tabla
foreach ($procesos as $proceso) {
    echo "<tr>
            <td>{$proceso['nombre_proceso']}</td>
            <td>" . number_format($proceso['costo_proceso'], 2) . "</td>
        </tr>";
    $totalCosto += $proceso['costo_proceso'];
}

// Mostrar el total de costos
echo "<tr>
        <td><strong>Total</strong></td>
        <td><strong>" . number_format($totalCosto, 2) . "</strong></td>
    </tr>";

echo "</table></center>";
?>