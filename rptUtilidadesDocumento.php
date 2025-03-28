<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli.php');
require('funcion_nombres.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$rpt_ver=$_GET['rpt_ver'];
$codTipoDoc=$_GET['codTipoDoc'];


//desde esta parte viene el reporte en si
$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
$fecha_finconsulta=cambia_formatofecha($fecha_fin);


$rpt_territorio=$_GET['rpt_territorio'];

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($enlaceCon,$rpt_territorio);

echo "<h1>Reporte Utilidades x Documento</h1>
	<h2>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</h2>";

$sql="select s.`fecha`,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, 
	s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),
	s.`nro_correlativo`, s.`monto_final`,
	(select sum(sd.costo_almacen*sd.cantidad_unitaria) from salida_detalle_almacenes sd where sd.cod_salida_almacen=s.cod_salida_almacenes)costo
	from `salida_almacenes` s where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' and 
	s.cod_tipo_doc in ($codTipoDoc)";

if($rpt_ver==1){
	$sql.=" and s.estado_salida=4 ";
}

$sql.=" order by s.fecha, s.nro_correlativo";
	
$resp=mysqli_query($enlaceCon,$sql);

echo "<center><table class='texto'>
<tr>
<th>Fecha</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Observaciones</th>
<th>Documento</th>
<th>Monto</th>
<th>Costo</th>
<th>Utilidad</th>
</tr>";

$totalVenta=0;
$totalCosto=0;
while($datos=mysqli_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	$totalVenta=$totalVenta+$montoVenta;
	$montoVentaFormat=number_format($montoVenta,2,".",",");

	
	$montoCosto=$datos[7];
	$totalCosto=$totalCosto+$montoCosto;
	$montoCostoFormat=number_format($montoCosto,2,".",",");
	
	$utilidad=$montoVenta-$montoCosto;
	$utilidadFormat=number_format($utilidad,2,".",",");
	
	echo "<tr>
	<td>$fechaVenta</td>
	<td>$nombreCliente</td>
	<td>$razonSocial</td>
	<td>$obsVenta</td>
	<td>$datosDoc</td>
	<td>$montoVentaFormat</td>
	<td>$montoCostoFormat</td>
	<td>$utilidadFormat</td>
	</tr>";
}
$totalVentaFormat=number_format($totalVenta,2,".",",");
$totalCostoFormat=number_format($totalCosto,2,".",",");
$totalUtilidad=$totalVenta-$totalCosto;
$totalUtilidadFormat=number_format($totalUtilidad,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td>$totalVentaFormat</td>
	<td>$totalCostoFormat</td>
	<td>$totalUtilidadFormat</td>
<tr>";

echo "</table></br>";
include("imprimirInc.php");
?>