<?php
require("conexionmysqlipdf.inc");
require("funciones.php");
require("estilos_almacenesAjax.php");

$codProducto=$_GET["codProducto"];
$jsonProducto=$_GET["jsonProducto"];

//$data = json_decode($jsonProducto);

$data = json_decode($jsonProducto, true);

// Comprobar si el JSON es válido
if ($data === null) {
    echo "Error al decodificar el JSON";
    exit;
}

// Crear la tabla HTML

echo "<table border='1'>
        <tr>
            <th>Nombre Almacen</th>
            <th>Fecha</th>
            <th>Código</th>
            <th>Nombre Producto</th>
            <th>Cant. Vendida</th>
            <th>Lote</th>
            <th>Detalle Procesos</th>
        </tr>";

// Iterar sobre los datos y generar filas de tabla
foreach ($data as $item) {
    echo "<tr>
            <td>{$item['nombreAlmacen']}</td>
            <td>{$item['fecha']}</td>
            <td>{$item['codigoProducto']}</td>
            <td><small>{$item['nombreProducto']}</small></td>
            <td>{$item['cantidadProducto']}</td>
            <td>{$item['loteProducto']}</td>
            <td>";
    
    // Generar una tabla interna para los detalles de procesos
    echo "<table border='1'>
            <tr>
                <th>Cod Lote</th>
                <th>Nro Lote</th>
                <th>Cod Proceso Const</th>
                <th>Nombre Proceso Const</th>
                <th>Cantidad</th>
                <th>Monto</th>
            </tr>";
    
    // Iterar sobre los detalles de procesos
    $dataDetalleDecode=json_decode($item['detalleProcesos'],true);
    $montoDetalleProcesos=0;
    foreach ($dataDetalleDecode as $detalle) {
        echo "<tr>
                <td>{$detalle['cod_lote']}</td>
                <td>{$detalle['nro_lote']}</td>
                <td>{$detalle['cod_proceso_const']}</td>
                <td>{$detalle['nombre_proceso_const']}</td>
                <td align='right'>{$detalle['cantidad']}</td>
                <td align='right'>{$detalle['precio']}</td>
              </tr>";
        $montoDetalleProcesos += $detalle["precio"];
    }
        echo "<tr>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td align='right'><span style='color:red;'><b>$montoDetalleProcesos</b></span></td>
            </tr>";    
    
        echo "</table></td></tr>";
}

echo "</table>";

?>
