<?php
require("conexion.inc");
require("funciones.php");

$fechaIniBusqueda=$_GET['fechaIniBusqueda'];
$fechaFinBusqueda=$_GET['fechaFinBusqueda'];
$nroCorrelativoBusqueda=$_GET['nroCorrelativoBusqueda'];
$global_almacen=$_GET['global_almacen'];
$itemBusqueda=$_GET['itemBusqueda'];

//$fechaIniBusqueda=formateaFechaVista($fechaIniBusqueda);
//$fechaFinBusqueda=formateaFechaVista($fechaFinBusqueda);

$consulta = "
    SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.estado_liquidacion
    FROM ingreso_almacenes i, tipos_ingreso ti
    WHERE i.cod_tipoingreso=ti.cod_tipoingreso and i.ingreso_anulado=0
    AND i.cod_almacen='$global_almacen' and i.cod_tipoingreso in (1000,1002) ";
	
if($nroCorrelativoBusqueda!="")
   {$consulta = $consulta."AND i.nro_correlativo='$nroCorrelativoBusqueda' ";
   }
if($fechaIniBusqueda!="" && $fechaFinBusqueda!="")
   {$consulta = $consulta."AND i.fecha between '$fechaIniBusqueda' and '$fechaFinBusqueda' ";
   }
if($itemBusqueda!=""){
   $consulta = $consulta."and i.cod_ingreso_almacen in 
	(select id.cod_ingreso_almacen from ingreso_detalle_almacenes id where id.cod_material in ($itemBusqueda)) ";
}
   $consulta = $consulta."ORDER BY i.nro_correlativo";

//echo "MAT: $consulta";

$resp = mysql_query($consulta);
echo "<br><center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Numero Ingreso</th><th>Nota de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th><th>&nbsp;</th></tr>";
while ($dat = mysql_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_ingreso = $dat[1];
    $fecha_ingreso_mostrar = "$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
    $hora_ingreso = $dat[2];
    $nombre_tipoingreso = $dat[3];
    $obs_ingreso = $dat[4];
    $nota_entrega = $dat[5];
    $nro_correlativo = $dat[6];
    $estadoLiquidacion = $dat[7];
    echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
    $sql_verifica_movimiento = "select s.cod_salida_almacenes from salida_almacenes s, salida_detalle_ingreso sdi
                where s.cod_salida_almacenes=sdi.cod_salida_almacen and s.salida_anulada=0 and sdi.cod_ingreso_almacen='$codigo'";
    $resp_verifica_movimiento = mysql_query($sql_verifica_movimiento);
    $num_filas_movimiento = mysql_num_rows($resp_verifica_movimiento);
   
    if ($estadoLiquidacion == 1) {
        $color_fondo = "#ffff99";
        $chkbox = "";
    }
    if ($estadoLiquidacion == 0) {
        $color_fondo = "";
        $chkbox = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
    
	echo "<tr><td align='center'>$chkbox</td><td align='center'>$nro_correlativo</td><td align='center'>&nbsp;$nota_entrega</td>
	<td align='center'>$fecha_ingreso_mostrar $hora_ingreso</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td align='center' bgcolor='$color_fondo'>
	<a target='_BLANK' href='navegador_detalleingresomateriales.php?codigo_ingreso=$codigo'><img src='imagenes/icon_detail.png' width='30' border='0' title='Ver Detalles del Ingreso'></a></td></tr>";
}
echo "</table></center><br>";


?>
