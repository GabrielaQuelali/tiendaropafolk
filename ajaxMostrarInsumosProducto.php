<?php
require("conexionmysqlipdf.inc");
require("funciones.php");
require("funcion_nombres.php");
require("estilos_almacenesAjax.php");

$codProducto=$_POST["codProducto"];
$jsonProducto=$_POST["jsonProducto"];

$nombreProductoX=nombreProducto($enlaceCon, $codProducto);

//$data = json_decode($jsonProducto);

$data = json_decode($jsonProducto, true);

// Comprobar si el JSON es válido
if ($data === null) {
    echo "Error al decodificar el JSON";
    exit;
}

// Crear la tabla HTML

echo "<table border='1' width='100%'>
        <tr>
        <th>Producto</th>
        <th>Monto Venta</th>
        <th>Cantidad Venta</th>
        <th>Detalle de Insumos</th>
        </tr>";

// Iterar sobre los datos y generar filas de tabla
$costoTotal=0;
    echo "<tr>
            <td>$nombreProductoX</td>
            <td><table>
                <tr><th>Insumo</th>
                <th>Unidad</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Monto</th></tr>";
foreach ($data as $item) {
    echo "<tr><td>{$item['nombreInsumo']}</td>
            <td>{$item['unidadMedida']}</td>
            <td><small>{$item['cantidadInsumo']}</small></td>
            <td>{$item['precioInsumo']}</td>
            <td>{$item['costoInsumo']}</td>";    
            $costoTotal += $item['costoInsumo'];
    echo "</tr>";
}
    echo "<tr>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>$costoTotal</td>
            </tr>";
    echo "</table></td></tr>";
echo "</table>";

?>
