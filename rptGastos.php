<?php
require('function_formatofecha.php');
require('conexionmysqli.inc');
require('funcion_nombres.php');
require('funciones.php');
require('estilos_almacenes.inc');


$fecha_ini=$_POST['fecha_ini'];
$fecha_fin=$_POST['fecha_fin'];
$rpt_territorio=$_POST['rpt_territorio'];
$rpt_territorio=implode(',', $rpt_territorio);


//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;

$fecha_reporte=date("d/m/Y");

echo "<h1>Reporte Detallado de Gastos</h1>
	<h2>Fecha Inicio: $fecha_ini Fecha Final: $fecha_fin &nbsp;&nbsp;&nbsp; Fecha Reporte: $fecha_reporte</h2>";

echo "<br><center><table class='textomediano'>";
echo "<tr>
<th>Sucursal</th>
<th>Fecha</th>
<th>Tipo</th>
<th>Descripcion</th>
<th>Distribucion</th>
<th>Monto [Bs]</th></tr>";

$consulta = "SELECT g.cod_gasto, g.descripcion_gasto, 
(select nombre_tipogasto from tipos_gasto where cod_tipogasto=g.cod_tipogasto)tipogasto, DATE_FORMAT(g.fecha_gasto, '%d/%m/%Y'), 
g.monto, g.gasto_anulado, c.descripcion, gg.nombre_grupogasto, tdc.desc_tipo_distribucion_costo
from gastos g 
inner join ciudades c ON c.cod_ciudad=g.cod_ciudad
LEFT JOIN grupos_gasto gg ON gg.cod_grupogasto=g.cod_grupogasto
LEFT JOIN tipos_distribucion_costo tdc ON tdc.cod_tipo_distribucion_costo=g.cod_tipo_distribucion_costo
where 
g.fecha_gasto between '$fecha_iniconsulta' and '$fecha_finconsulta' and g.gasto_anulado=1 and 
c.cod_ciudad in ($rpt_territorio) order by g.fecha_gasto;";

//echo $consulta;

$resp = mysqli_query($enlaceCon,$consulta);
$totalGastos=0;
while ($dat = mysqli_fetch_array($resp)) {
	$codGasto = $dat[0];
	$descripcionGasto= $dat[1];
	$tipoGasto=$dat[2];
	$fechaGasto = $dat[3];
	$montoGasto = $dat[4];
	$totalGastos=$totalGastos+$montoGasto;
	$codEstado=$dat[5];	
	$montoGastoF=formatonumeroDec($montoGasto);
	$nombreSucursal=$dat[6];
	$grupoGasto=$dat[7];
	$tipoDistribucionCosto=$dat[8];

	echo "<tr>
	<td align='center'>$nombreSucursal</td>
	<td align='center'>$fechaGasto</td>
	<td align='center'>$grupoGasto</td>
	<td align='left'>$descripcionGasto</td>
	<td align='left'>$tipoDistribucionCosto</td>
	<td align='right'>$montoGastoF</td>
	</tr>";
}
$totalGastosF=formatonumeroDec($totalGastos);
echo "<tr>
<td align='center'>-</td>
<td align='center'>-</td>
<td align='center'>-</td>
<td align='center'>-</td>
<th>Total Gastos</th>
<td align='right'><b>$totalGastosF</b></td>
</tr>";
echo "</table></center><br>";


include("imprimirInc.php");
?>