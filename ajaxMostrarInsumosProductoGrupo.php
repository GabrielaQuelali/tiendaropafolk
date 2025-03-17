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

echo "<table border='1' width='100%'>
        <tr>
            <th>Nombre Producto</th>
            <th>MontoVenta</th>
            <th>Cant.Venta</th>
            <th>Detalle Insumos</th>
        </tr>";

// Iterar sobre los datos y generar filas de tabla
$totalReporte=0;

foreach ($data as $item) {
    $montoVentaProductoX=$item['montoVentaProducto'];
    $montoVentaProductoXF=formatonumeroDec($montoVentaProductoX);
    $cantidadVentaProductoX=$item['cantidadProducto'];
    $cantidadVentaProductoXF=formatonumero($cantidadVentaProductoX);
    echo "<tr>
            <td>{$item['nombreProducto']}</td>
            <td align='center'>$montoVentaProductoXF</td>
            <td align='center'>$cantidadVentaProductoXF</td>
            <td>";    
    // Generar una tabla interna para los detalles de procesos
    echo "<table border='1' width='100%'>
            <tr>
                <th>Insumo</th>
                <th>Unidad</th>
                <th>Cantidad</th>
                <th>Precio</th>
                <th>Monto</th>
                <th>-</th>
            </tr>";
    
    // Iterar sobre los detalles de procesos    
    
    $dataDetalleDecode=$item['detalleInsumos'];
    // $dataDetalleDecode=json_decode($item['detalleInsumos'],true);
    // // Verificamos si el detalle de insumos se decodificó correctamente
    // if ($dataDetalleDecode === null) {
    //     echo "Error al decodificar el detalle de insumos";
    //     continue; // Pasar a la siguiente iteración del bucle
    // }

    $montoDetalleProcesos=0;
    $montoTotalProductoX=0;
    foreach ($dataDetalleDecode as $detalle) {
        $montoInsumo=$detalle['cantidadInsumo']*$detalle['precioInsumo'];
        echo "<tr>
                <td>{$detalle['nombreInsumo']}</td>
                <td>{$detalle['unidadMedida']}</td>
                <td align='right'>{$detalle['cantidadInsumo']}</td>
                <td align='right'>{$detalle['precioInsumo']}</td>
                <td align='right'>$montoInsumo</td>
                <td align='right'>-</td>
              </tr>";
        $montoDetalleProcesos += $montoInsumo;
    }
    $montoTotalProductoX=$montoDetalleProcesos*$cantidadVentaProductoX;
    $montoTotalProductoXF=formatonumeroDec($montoTotalProductoX);
    $totalReporte += $montoTotalProductoX;

        echo "<tr>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td align='right'><span style='color:red;'><b>$montoDetalleProcesos</b></span></td>
            <td align='right'><span style='color:blue;'><b>$montoTotalProductoXF</b></span></td>
            </tr>";     
        echo "</table></td></tr>";
}
    $totalReporteF=formatonumeroDec($totalReporte);
    echo "<tr>
    <td>-</td>
    <td>-</td>
    <td>-</td>
    <td align='right'><span style='color:red;font-size:18px'><b>$totalReporteF</b></span></td>
    </tr>";
echo "</table>";
?>
