<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli.inc');
require('funcion_nombres.php');
require('funciones.php');

$fecha_ini=$_GET['fecha_ini'];
$rpt_territorio=$_GET['rpt_territorio'];

$variableAdmin=$_GET["variableAdmin"];
if($variableAdmin!=1){
	$variableAdmin=0;
}
$global_agencia=$_COOKIE['global_agencia'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;

$fecha_reporte=date("d/m/Y");

echo "<h1>Reporte Arqueo Diario de Caja</h1>
	<h2>Fecha: $fecha_ini &nbsp;&nbsp;&nbsp; Fecha Reporte: $fecha_reporte</h2>";

	

echo "<center><table class='textomediano'>";
echo "<tr><th colspan='2'>Saldo Inicial Caja Chica</th></tr>
<tr><th>Fecha</th><th>Monto Apertura de Caja Chica [Bs]</th></tr>";
$consulta = "select DATE_FORMAT(c.fecha_cajachica, '%d/%m/%Y'), c.monto, c.fecha_cajachica from cajachica_inicio c where 
c.fecha_cajachica='$fecha_iniconsulta'";
//echo $consulta;
$resp = mysqli_query($enlaceCon,$consulta);
while ($dat = mysqli_fetch_array($resp)) {
	$fechaCajaChica = $dat[0];
	$montoCajaChica = $dat[1];
	$montoCajaChicaF=number_format($montoCajaChica,2,".",",");
	echo "<tr>
	<td align='center'>$fechaCajaChica</td>
	<td align='right'>$montoCajaChicaF</td>
	</tr>";
}
echo "</table></center><br>";


	
$sql="select s.`fecha`,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, 
	s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),
	s.`nro_correlativo`, s.`monto_final`, s.cod_tipopago, (select tp.nombre_tipopago from tipos_pago tp where tp.cod_tipopago=s.cod_tipopago), 
	s.hora_salida
	from `salida_almacenes` s 
	where s.`cod_tiposalida`=1001 and s.salida_anulada=0 and
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')
	and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_iniconsulta'";
 
if($variableAdmin==1){
	$sql.=" and s.cod_tipo_doc in (1,2,3)";
}else{
	$sql.=" and s.cod_tipo_doc in (1)";
}
$sql.=" order by s.fecha, s.hora_salida";
	
$resp=mysqli_query($enlaceCon,$sql);
?>
<br><table align='center' class='textomediano' width='70%'>
<tr><th colspan='7'>Detalle de Ventas</th></tr>
<tr>
<th>Fecha</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Observaciones</th>
<th>Documento</th>
<th>Forma Pago</th>
<th>Monto [Bs]</th>
</tr>
<?php
$totalVenta=0;

while($datos=mysqli_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	$totalVenta=$totalVenta+$montoVenta;
	$codTipoPago=$datos[7];
	$nombreTipoPago=$datos[8];
	$horaVenta=$datos[9];
	
	$montoVentaFormat=number_format($montoVenta,2,".",",");
	
?>
	<tr>
	<td><?=$fechaVenta." ".$horaVenta;?></td>
	<td><?=$nombreCliente;?></td>
	<td><?=$razonSocial;?></td>
	<td><?=$obsVenta;?></td>
	<td><?=$datosDoc;?></td>
	<td><?=$nombreTipoPago;?></td>	
	<td align='right'><?=$montoVentaFormat;?></td>
	</tr>
<?php	
}


$totalVentaFormat=number_format($totalVenta,2,".",",");

$sql2=" select  s.cod_tipopago, sum(s.monto_final) from salida_almacenes s ";
$sql2.=" where s.cod_tiposalida=1001 and s.salida_anulada=0  ";
$sql2.=" and s.cod_almacen in (select a.cod_almacen from almacenes a where a.cod_ciudad='".$rpt_territorio."')";
$sql2.=" and s.fecha BETWEEN '".$fecha_iniconsulta."' and '".$fecha_iniconsulta."'";
if($variableAdmin==1){
	$sql2.=" and s.cod_tipo_doc in (1,2,3)";
}else{
	$sql2.=" and s.cod_tipo_doc in (1)";
}
$sql2.=" group by s.cod_tipopago order by s.cod_tipopago asc";

$resp2 = mysqli_query($enlaceCon,$sql2);
while ($dat2 = mysqli_fetch_array($resp2)) {
	$tipopago= $dat2[0];
	$totMontoTipopago= $dat2[1];
	$sql3=" select nombre_tipopago from tipos_pago where cod_tipopago=".$tipopago;
	
	$resp3 = mysqli_query($enlaceCon,$sql3);	
	while ($dat3 = mysqli_fetch_array($resp3)) {
		$descTipopago=$dat3[0];
	}
	$totMontoTipopagoF=number_format($totMontoTipopago,2,".",",");
?>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align="right"><strong><?=$descTipopago;?></strong></td>
	<td align="right"><strong><?=$totMontoTipopagoF;?></strong></td>
<tr>

<?php
}
?>
<?php
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align='right'><strong>Total Ventas</strong></td>
	<td align='right'><strong>$totalVentaFormat</strong></td>
<tr>";
echo "</table></br>";
?>
<br><table align='center' class='textomediano' width='70%'>
<tr><th colspan='7'>Detalle de Recibos</th></tr>
<tr>
<th>Tipo</th>
<th>Nro</th>
<th>Fecha</th>
<th>Cliente</th>
<th>Detalle</th>
<th>&nbsp;</th>
<th>Forma Pago</th>
<th>Monto [Bs]</th>
</tr>
<?php
$consulta = " select r.id_recibo,r.fecha_recibo,r.cod_ciudad,ciu.descripcion,
r.nombre_recibo,r.desc_recibo,r.monto_recibo,
r.created_by,r.modified_by,r.created_date,r.modified_date, r.cel_recibo,r.recibo_anulado,r.cod_tipopago, tp.nombre_tipopago,
r.cod_tiporecibo, tr.nombre_tiporecibo, r.cod_proveedor, p.nombre_proveedor
from recibos r inner join ciudades ciu on (r.cod_ciudad=ciu.cod_ciudad)
inner join tipos_pago tp on(r.cod_tipopago=tp.cod_tipopago)
inner join tipos_recibo tr on(r.cod_tiporecibo=tr.cod_tiporecibo)
left  join proveedores p on (r.cod_proveedor=p.cod_proveedor)
where r.cod_ciudad=".$global_agencia." and r.recibo_anulado=0";
$consulta = $consulta." AND r.fecha_recibo BETWEEN '".$fecha_iniconsulta."' and '".$fecha_iniconsulta."'";
$consulta=$consulta." order by r.id_recibo DESC,r.cod_ciudad desc ";

$resp = mysqli_query($enlaceCon,$consulta);
$totalRecibo=0;
while ($dat = mysqli_fetch_array($resp)) {
	$id_recibo= $dat['id_recibo'];
	$fecha_recibo= $dat['fecha_recibo'];
	$vector_fecha_recibo=explode("-",$fecha_recibo);
	$fecha_recibo_mostrar=$vector_fecha_recibo[2]."/".$vector_fecha_recibo[1]."/".$vector_fecha_recibo[0];
	$cod_ciudad= $dat['cod_ciudad'];
	$descripcion= $dat['descripcion'];
	$nombre_recibo= $dat['nombre_recibo'];
	$desc_recibo= $dat['desc_recibo'];
	$monto_recibo= $dat['monto_recibo'];
	$created_by= $dat['created_by'];
	$modified_by= $dat['modified_by'];
	$created_date= $dat['created_date'];
	$modified_date= $dat['modified_date'];
	$cel_recibo = $dat['cel_recibo'];
	$recibo_anulado= $dat['recibo_anulado'];
	$cod_tipopago= $dat['cod_tipopago'];
	$nombre_tipopago= $dat['nombre_tipopago'];
	$cod_tiporecibo= $dat['cod_tiporecibo'];
	$nombre_tiporecibo= $dat['nombre_tiporecibo'];
	$cod_proveedor= $dat['cod_proveedor'];
	$nombre_proveedor= $dat['nombre_proveedor'];
	$created_date_mostrar="";
	$totalRecibo=$totalRecibo+$monto_recibo;
	// formatoFechaHora
	if(!empty($created_date)){
		$vector_created_date = explode(" ",$created_date);
		$fechaReg=explode("-",$vector_created_date[0]);
		$created_date_mostrar = $fechaReg[2]."/".$fechaReg[1]."/".$fechaReg[0]." ".$vector_created_date[1];
	}
	// fin formatoFechaHora
	$modified_date= $dat['modified_date'];
	$cel_recibo = $dat['cel_recibo'];
	$modified_date_mostrar="";
	// formatoFechaHora
	if(!empty($modified_date)){
		$vector_modified_date = explode(" ",$modified_date);
		$fechaEdit=explode("-",$vector_modified_date[0]);
		$modified_date_mostrar = $fechaEdit[2]."/".$fechaEdit[1]."/".$fechaEdit[0]." ".$vector_modified_date[1];
	}
	// fin formatoFechaHora
	
	/////	
		$sqlRegUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$created_by;
		$respRegUsu=mysqli_query($enlaceCon,$sqlRegUsu);
		$usuReg =" ";
		while($datRegUsu=mysqli_fetch_array($respRegUsu)){
			$usuReg =$datRegUsu['nombres'][0].$datRegUsu['paterno'];		
		}
	//////
		$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
		$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
		$usuMod ="";
		while($datModUsu=mysqli_fetch_array($respModUsu)){
			$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
		}
	?>
	<td><?=$nombre_tiporecibo;?></td>
	<td>REC-<?=$id_recibo;?></td>
	<td><?=$fecha_recibo_mostrar;?></td>
	
	
	<td><?=$nombre_recibo;?></td>
	<td><?=$desc_recibo;?></td>
	<td><?=$nombre_proveedor;?></td>
	<td><?=$nombre_tipopago;?></td>	
	<td align='right'><?=$monto_recibo;?></td>
	</tr>
<?php
}

$totMontoTipopago=0;
$sql2="select r.cod_tipopago,sum(r.monto_recibo)  from recibos r where r.cod_ciudad=".$global_agencia." and r.recibo_anulado=0 ";
$sql2 = $sql2." AND r.fecha_recibo BETWEEN '".$fecha_iniconsulta."' and '".$fecha_iniconsulta."'";
$sql2 = $sql2."group by r.cod_tipopago order by r.cod_tipopago asc";

$resp2 = mysqli_query($enlaceCon,$sql2);
while ($dat2 = mysqli_fetch_array($resp2)) {
	$tipopago= $dat2[0];
	$totMontoTipopago= $dat2[1];
	$sql3=" select nombre_tipopago from tipos_pago where cod_tipopago=".$tipopago;
	
	$resp3 = mysqli_query($enlaceCon,$sql3);	
	while ($dat3 = mysqli_fetch_array($resp3)) {
		$descTipopago=$dat3[0];
	}
	$totMontoTipopagoF=number_format($totMontoTipopago,2,".",",");
?>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>	
	<td align="right"><strong><?=$descTipopago;?></strong></td>
	<td align="right"><strong><?=$totMontoTipopagoF;?></strong></td>
<tr>

<?php
}
$totalReciboF=number_format($totalRecibo,2,".",",");
?>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
		<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align="right"><strong>Total Recibos:</strong></td>
	<td align="right"><strong><?=$totalReciboF;?></strong></td>
<tr>
</table>


<br><center><table class='textomediano'>
<tr><th colspan='8'>Detalle de Gastos</th></tr>
<tr>
<th>Tipo</th>
<th>Nro</th>
<th>Fecha</th>
<th>&nbsp;</th>
<th>Grupo</th>
<th>Detalle</th>
<th>Forma Pago</th>
<th>Monto [Bs]</th>
</tr>
	
<?php



$sqlGasto="select g.cod_gasto,g.descripcion_gasto,g.cod_tipogasto,tg.nombre_tipogasto,g.fecha_gasto,g.monto,g.cod_ciudad,
g.created_by,g.modified_by,g.created_date,g.modified_date,g.gasto_anulado,g.cod_proveedor, p.nombre_proveedor,g.cod_grupogasto, gg.nombre_grupogasto,
g.cod_tipopago, tp.nombre_tipopago
from gastos g
inner join tipos_gasto tg on (g.cod_tipogasto=tg.cod_tipogasto)
inner join grupos_gasto gg on (g.cod_grupogasto=gg.cod_grupogasto)
inner join tipos_pago tp on (g.cod_tipopago=tp.cod_tipopago)
left  join proveedores p on (g.cod_proveedor=p.cod_proveedor)
where    g.cod_ciudad=".$global_agencia." 
and g.fecha_gasto BETWEEN '".$fecha_iniconsulta."' and '".$fecha_iniconsulta."'
order by g.cod_gasto asc ";

//echo $sqlGasto;
$totalGastos=0;	
$resp = mysqli_query($enlaceCon,$sqlGasto);

while ($dat = mysqli_fetch_array($resp)) {
	$cod_gasto= $dat['cod_gasto'];
	$descripcion_gasto= $dat['descripcion_gasto'];
	$cod_tipogasto= $dat['cod_tipogasto'];
	$nombre_tipogasto= $dat['nombre_tipogasto'];
	$fecha_gasto= $dat['fecha_gasto'];	
	$vector_fecha_gasto=explode("-",$fecha_gasto);
	$fecha_gasto_mostrar=$vector_fecha_gasto[2]."/".$vector_fecha_gasto[1]."/".$vector_fecha_gasto[0];
	$monto= $dat['monto'];
	$cod_ciudad= $dat['cod_ciudad'];
	$created_by= $dat['created_by'];
	$modified_by= $dat['modified_by'];
	$created_date= $dat['created_date'];
	$modified_date= $dat['modified_date'];
	$gasto_anulado= $dat['gasto_anulado'];
	$cod_proveedor= $dat['cod_proveedor'];
	$nombre_proveedor= $dat['nombre_proveedor'];
	$cod_grupogasto= $dat['cod_grupogasto'];
	$nombre_grupogasto= $dat['nombre_grupogasto'];
	$cod_tipopago= $dat['cod_tipopago'];
	$nombre_tipopago= $dat['nombre_tipopago'];

	$created_date_mostrar="";
	// formatoFechaHora
	if(!empty($created_date)){
		$vector_created_date = explode(" ",$created_date);
		$fechaReg=explode("-",$vector_created_date[0]);
		$created_date_mostrar = $fechaReg[2]."/".$fechaReg[1]."/".$fechaReg[0]." ".$vector_created_date[1];
	}
	// fin formatoFechaHora

	$modified_date_mostrar="";
	// formatoFechaHora
	if(!empty($modified_date)){
		$vector_modified_date = explode(" ",$modified_date);
		$fechaEdit=explode("-",$vector_modified_date[0]);
		$modified_date_mostrar = $fechaEdit[2]."/".$fechaEdit[1]."/".$fechaEdit[0]." ".$vector_modified_date[1];
	}
	// fin formatoFechaHora
	
	/////	
		$sqlRegUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$created_by;
		$respRegUsu=mysqli_query($enlaceCon,$sqlRegUsu);
		$usuReg =" ";
		while($datRegUsu=mysqli_fetch_array($respRegUsu)){
			$usuReg =$datRegUsu['nombres'][0].$datRegUsu['paterno'];		
		}
	//////
	$usuMod ="";
	 if(!empty($modified_by)){
		$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
		$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
		$usuMod ="";
		while($datModUsu=mysqli_fetch_array($respModUsu)){
			$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
		}
	}
	
	$totalGastos=$totalGastos+$monto;

	$monto=redondear2($monto);
?>

	<tr>
	<td align='center'><?=$nombre_tipogasto;?></td>
	<td align='center'><?=$cod_gasto;?></td>
	<td align='center'><?=$fecha_gasto_mostrar;?></td>
	<td align='right'><?=$nombre_proveedor;?></td>
	<td align='right'><?=$nombre_grupogasto;?></td>
	<td align='right'><?=$descripcion_gasto;?></td>
	<td align='right'><?=$nombre_tipopago;?></td>
	<td align='right'><?=$monto;?></td>
	</tr>
<?php
}
$totalGastos=redondear2($totalGastos);
$totMontoGastoTipopago=0;
$sql2="select g.cod_tipopago,sum(g.monto)  from gastos g where g.cod_ciudad=".$global_agencia." and g.gasto_anulado=0 ";
$sql2 = $sql2." AND g.fecha_gasto BETWEEN '".$fecha_iniconsulta."' and '".$fecha_iniconsulta."'";
$sql2 = $sql2."group by g.cod_tipopago order by g.cod_tipopago asc";
//echo $sql2 ;
$resp2 = mysqli_query($enlaceCon,$sql2);
while ($dat2 = mysqli_fetch_array($resp2)) {
	$tipopago= $dat2[0];
	$totMontoTipopago= $dat2[1];
	$sql3=" select nombre_tipopago from tipos_pago where cod_tipopago=".$tipopago;
	
	$resp3 = mysqli_query($enlaceCon,$sql3);	
	while ($dat3 = mysqli_fetch_array($resp3)) {
		$descTipopago=$dat3[0];
	}
	$totMontoTipopagoF=number_format($totMontoTipopago,2,".",",");
?>
<tr>
	<td colspan="6">&nbsp;</td>
	<td align="right"><strong><?=$descTipopago;?></strong></td>
	<td align="right"><strong><?=$totMontoTipopagoF;?></strong></td>
</tr>
<?php
}
?>
<tr>
<td colspan="6">&nbsp;</td>
<td align="right"><strong>TOTAL GASTOS</strong></td>
<td align="right"><strong><?=$totalGastos;?></strong></td>
</tr>
</table></center><br>
<center>
<table class='textomediano'>
<tr><th colspan='5'>TOTALES POR TIPO DE PAGO</th></tr>
<tr>
<th>&nbsp;</th>
<th>Ventas </th>
<th>Recibos </th>
<th>Gastos </th>
<th>Saldo </th>
</tr>
<?php
$totVTA=0;
$totREC=0;
$totGTO=0;

	$sqlTP="select cod_tipopago, nombre_tipopago  from tipos_pago  where estado=1 order by cod_tipopago asc";
	$respTP = mysqli_query($enlaceCon,$sqlTP);
	while ($datTP = mysqli_fetch_array($respTP)) {
		$codTP=$datTP['cod_tipopago'];
		$nombreTP=$datTP['nombre_tipopago'];
		/////////////////VENTAS/////////
		$totalVTATP=0;
		$sqlVTA="select cod_tipopago, sum(s.`monto_final`)
		from `salida_almacenes` s 
		where s.`cod_tiposalida`=1001 
		and s.salida_anulada=0 and	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')";	
		$sqlVTA=$sqlVTA." and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_iniconsulta' ";
		$sqlVTA=$sqlVTA." and s.cod_tipopago  in( ".$codTP.") ";
		$sqlVTA.=" group by cod_tipopago ";		
		$respVTA=mysqli_query($enlaceCon,$sqlVTA);		
		while($datosVTA=mysqli_fetch_array($respVTA)){	
			$totalVTATP=$datosVTA[1];
		}
		////////////////////////////////////////
		////////////RECIBOS//////////
		$totalRECTP=0;
		$sqlREC="select r.cod_tipopago,sum(r.monto_recibo)  from recibos r where r.cod_ciudad=".$global_agencia." and r.recibo_anulado=0";
		$sqlREC =$sqlREC." AND r.fecha_recibo BETWEEN '".$fecha_iniconsulta."' and '".$fecha_iniconsulta."'";
		$sqlREC=$sqlREC." and r.cod_tipopago  in(".$codTP.") ";	
		$sqlREC = $sqlREC."group by r.cod_tipopago ";

		$respREC = mysqli_query($enlaceCon,$sqlREC);
		while ($datREC = mysqli_fetch_array($respREC)) {	
			$totalRECTP= $datREC[1];
		}
		//////////////////////////
		//////////////GASTOS/////////////
		$totalGTOTP=0;
		$sqlGTO=" select g.cod_tipopago,sum(g.monto)  from gastos g where g.cod_ciudad=".$global_agencia." and g.gasto_anulado=0 ";
		$sqlGTO = $sqlGTO." AND g.fecha_gasto BETWEEN '".$fecha_iniconsulta."' and '".$fecha_iniconsulta."'";
		$sqlGTO = $sqlGTO." and g.cod_tipopago  in(".$codTP.") ";	
		$sqlGTO = $sqlGTO."group by g.cod_tipopago order by g.cod_tipopago asc";		
		$respGTO = mysqli_query($enlaceCon,$sqlGTO);
		while ($datGTO = mysqli_fetch_array($respGTO)){		
			$totalGTOTP= $datGTO[1];
		}
		/////////////////////////////////
		if($codTP<>4){
		$totVTA=$totVTA+$totalVTATP;
		$totREC=$totREC+$totalRECTP;
		$totGTO=$totGTO+$totalGTOTP;
		}
?>
<tr>
<td align="right"><?=$nombreTP;?></td>
<td align="right"><?=$totalVTATP;?></td>
<td align="right"><?=$totalRECTP;?></td>
<td align="right"><?=$totalGTOTP;?></td>
<td align="right"><?=($totalVTATP+$totalRECTP-$totalGTOTP);?></td>
</tr>
<?php
}
?>
<tr>
<td align="right">&nbsp;</td>
<td align="right"><strong><?=$totVTA;?></strong></td>
<td align="right"><strong><?=$totREC;?></strong></td>
<td align="right"><strong><?=$$totGTO;?></strong></td>
<td align="right"><strong><?=($totVTA+$totREC-$totGTO);?></strong></td>
</tr>

</table>
</center>
<?php





include("imprimirInc.php");
?>