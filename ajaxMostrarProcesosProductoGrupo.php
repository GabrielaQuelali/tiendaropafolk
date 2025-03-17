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
$totalReporte=0;
foreach ($data as $item) {
    $cantidadVendidaProducto=$item['cantidadProducto'];
    $cantidadVendidaProductoF=formatonumero($cantidadVendidaProducto);
    echo "<tr>
            <td>{$item['nombreAlmacen']}</td>
            <td>{$item['fecha']}</td>
            <td>{$item['codigoProducto']}</td>
            <td><small>{$item['nombreProducto']}</small></td>
            <td align='center'>$cantidadVendidaProductoF</td>
            <td align='center'>{$item['loteProducto']}</td>
            <td>";
    
    // Generar una tabla interna para los detalles de procesos
    echo "<table border='1'>
            <tr>
                <th>Cod Lote</th>
                <th>Nro Lote</th>
                <th>Cod Proceso Const</th>
                <th>Nombre Proceso Const</th>
                <th>Precio</th>
                <th>Monto</th>
            </tr>";
    
    // Iterar sobre los detalles de procesos
    $dataDetalleDecode=json_decode($item['detalleProcesos'],true);
    $montoDetalleProcesos=0;
    $montoTotalProceso=0;
    foreach ($dataDetalleDecode as $detalle) {
        $cantidadVendida=$detalle['cantidad'];
        $cantidadVendidaF=formatonumero($cantidadVendida);
        $precioProceso=$detalle['precio'];
        $precioProcesoF=formatonumeroDec($precioProceso);
        $montoProceso=$cantidadVendida*$precioProceso;
        $montoProcesoF=formatonumeroDec($montoProceso);
        echo "<tr>
                <td>{$detalle['cod_lote']}</td>
                <td>{$detalle['nro_lote']}</td>
                <td>{$detalle['cod_proceso_const']}</td>
                <td>{$detalle['nombre_proceso_const']}</td>
                <td align='right'>$precioProcesoF</td>
                <td align='right'>$montoProcesoF</td>
              </tr>";
        $montoDetalleProcesos += $detalle["precio"];
        $montoTotalProceso += $montoProceso;
    }
        $montoTotalProceso=$montoTotalProceso * $cantidadVendidaProducto;
        $montoTotalProcesoF=formatonumeroDec($montoTotalProceso);

        $totalReporte += $montoTotalProceso;
        echo "<tr>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td align='right'><span style='color:red;'><b>$montoDetalleProcesos</b></span></td>
            <td align='right'><span style='color:blue;'><b>$montoTotalProcesoF</b></span></td>
            </tr>";    
        echo "</table></td></tr>";
}
$totalReporteF = formatonumeroDec($totalReporte);
echo "<tr>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td>-</td>
<td align='right'><span style='color:red;font-size:18px'><b>$totalReporteF</b></span></td>
</tr>";    
echo "</table>";


?>
