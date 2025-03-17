<?php
require("conexionmysqlipdf.inc");
require("funciones.php");
require("funcion_nombres.php");
require("estilos_almacenesAjax.php");

$codProducto=$_POST["codProducto"];
$jsonProducto=$_POST["jsonProducto"];

$data = json_decode($jsonProducto, true);

//echo $jsonProducto;

// Comprobar si el JSON es válido
if ($data === null) {
    echo "Error al decodificar el JSON";
    exit;
}

echo "<center><table border='1' width='100%' align='center'>
        <tr>
            <th>Insumo</th>
            <th>Unidad</th>
            <th>Cantidad</th>
            <th>Precio</th>
            <th>Total</th>
        </tr>";

// Iterar sobre los datos y generar filas de tabla
$totalReporte=0;

foreach ($data['insumos'] as $item) {
    echo "<tr>
            <td>".$item['nombre']."</td>
            <td>".$item['unidad']."</td>
            <td>".formatonumero($item['cantidad'])."</td>
            <td>".formatonumeroDec($item['precio'])."</td>
            <td>".formatonumeroDec($item['subtotal'])."</td>
        </tr>";
    $totalReporte+=$item['subtotal'];
}
    echo "<tr>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>".formatonumeroDec($totalReporte)."</td>
        </tr>";
echo "</table></center>";
?>
