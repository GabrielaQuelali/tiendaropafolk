<?php
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli.inc');
require('funcion_nombres.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$sqlFuncProv="select * from funcionarios_proveedores where codigo_funcionario=$global_usuario";
$respFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);
$cantFuncProv=mysqli_num_rows($respFuncProv);
//desde esta parte viene el reporte en si
$fecha_iniconsulta=($fecha_ini);
$fecha_finconsulta=($fecha_fin);

$rpt_territorio=$_GET['rpt_territorio'];
$rptMarca=$_GET["rpt_marca"];
$rptTipoPago=$_GET["rpt_tipoPago"];
echo "marcaAA".$rptMarca;
echo "tipoPago".$rptTipoPago."<br>";;
if($rptMarca=="-1"){
	 $rptMarca=""; $swMarca=0;	 
	$sqlMarca="select codigo from marcas where estado= 1";
		if($globalTipoFuncionario==2){
		if($cantFuncProv>0){
			$sqlMarca= $sqlMarca." and codigo in( select codigo from proveedores_marcas where cod_proveedor in
			( select cod_proveedor from funcionarios_proveedores where codigo_funcionario=$global_usuario))";
		}
	}
	echo $sqlMarca ;
	$respMarca=mysqli_query($enlaceCon,$sqlMarca);
	while($datMarca=mysqli_fetch_array($respMarca))
	{
		if($swMarca==0){
			$rptMarca=$datMarca[0];
			$swMarca=1;
		}else{
			$rptMarca=$rptMarca.",";
			$rptMarca=$rptMarca.$datMarca[0];
		}
	}
	echo "marcaAA".$rptMarca."<br>";
}	
if($rptTipoPago=="-1"){
	$rptTipoPago=""; $swTipoPago=0;	 
	$sqlTipoPago="select cod_tipopago, nombre_tipopago from tipos_pago where estado=1  order by cod_tipopago asc";
	$respTipoPago=mysqli_query($enlaceCon,$sqlTipoPago);
	while($datTipoPago=mysqli_fetch_array($respTipoPago))
	{	$codTipopago=$datTipoPago[0];
		if($swTipoPago==0){
			$rptTipoPago=$datTipoPago[0];
			$swTipoPago=1;
		}else{
			$rptTipoPago=$rptTipoPago.",";
			$rptTipoPago=$rptTipoPago.$datTipoPago[0];
		}
	}
	echo "rptTipoPago".$rptTipoPago."<br>";;
}
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$sqlFuncProv="select * from funcionarios_proveedores where codigo_funcionario=$global_usuario";
$respFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);
$cantFuncProv=mysqli_num_rows($respFuncProv);

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($enlaceCon,$rpt_territorio);

echo "<table align='center' class='textotit' width='70%'><tr><td align='center'>Reporte Ventas x Documento
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";

$sql="select concat(s.`fecha`,' ',s.hora_salida)as fecha,  
	(select c.nombre_cliente from clientes c where c.`cod_cliente`=s.cod_cliente) as cliente, s.`razon_social`, s.`observaciones`, 
	(select t.`abreviatura` from `tipos_docs` t where t.`codigo`=s.cod_tipo_doc),s.`nro_correlativo`, s.`monto_final`, s.cod_salida_almacenes
	from `salida_almacenes` s 
	where s.`cod_tiposalida`=1001 
	and s.salida_anulada=0 and	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio')";	
	$sql=$sql." and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' ";
	if(!empty($rptMarca)){
	$sql=$sql." and s.cod_salida_almacenes in(SELECT sda.cod_salida_almacen FROM `salida_detalle_almacenes` sda 
	inner join material_apoyo ma on (sda.cod_material=ma.codigo_material and ma.cod_marca in( $rptMarca))) ";
	}
if(!empty($rptTipoPago)){
	$sql=$sql." and s.cod_tipopago  in( $rptTipoPago) ";
	}
$sql.=" order by s.fecha, s.hora_salida, s.nro_correlativo";
 echo $sql;

$resp=mysqli_query($enlaceCon,$sql);

echo "<br><table align='center' class='texto' width='70%'>
<tr>
<th>Fecha</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Documento</th>
<th>Monto</th>
<th>
	<table width='100%'>
	<tr>
		<th width='50%'>Codigo</th>
		<th width='50%'>Producto</th>
		<th width='50%'>Color/Talla</th>
		<th width='25%'>Cantidad</th>
		<th width='25%'>Monto</th>
	</tr>
	</table>
</th>
</tr>";

$totalVenta=0;
$totalFactVentaX=0;
while($datos=mysqli_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	$codSalida=$datos[7];
	
	$montoVentaFormat=number_format($montoVenta,2,".",",");
	
	$totalVenta=$totalVenta+$montoVenta;
	
	$sqlX="select m.codigo_barras, m.`descripcion_material`, 
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total, m.color, m.talla, 
	mar.nombre,m.cod_marca,m.codigo2
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m, marcas mar
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` 
	and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`=0 
	and sd.`cod_material`=m.`codigo_material` 
	and m.cod_marca=mar.codigo
	and	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rpt_territorio') 
	and	s.cod_salida_almacenes='$codSalida' ";
	if(!empty($rptMarca)){
		$sqlX=$sqlX." and m.cod_marca in ($rptMarca)";						
	}
	
	$sqlX=$sqlX." group by m.`codigo_material` order by 2 desc;";
	//echo $sqlX;
	
	$respX=mysqli_query($enlaceCon,$sqlX);

	$tablaDetalle="<table width='100%'>";
	
	$totalVentaX=0;
	
	while($datosX=mysqli_fetch_array($respX)){	
		$codItem=$datosX[0];
		$nombreItem=$datosX[1];
		$montoVenta=$datosX[2];
		$cantidad=$datosX[3];
		
		$descuentoVenta=$datosX[4];
		$montoNota=$datosX[5];
		
		$colorItem=$datosX[6];
		$tallaItem=$datosX[7];
		$nombreMarca=$datosX[8];
		$codMarca=$datosX[9];
		$codigo2=$datosX[10];
		
		if($descuentoVenta>0){
			$porcentajeVentaProd=($montoVenta/$montoNota);
			$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
			$montoVenta=$montoVenta-$descuentoAdiProducto;
		}
		
		$montoPtr=number_format($montoVenta,2,".",",");
		$cantidadFormat=number_format($cantidad,0,".",",");
		
		$totalVentaX=$totalVentaX+$montoVenta;
		$totalFactVentaX=$totalFactVentaX+$montoVenta;
		
		$tablaDetalle.="<tr>
		<td>$codItem $codigo2</td>
		<td>$nombreMarca $nombreItem</td>
		<td>$colorItem/$tallaItem</td>
		<td>$cantidadFormat</td>
		<td>$montoPtr</td>		
		</tr>";
	}
	$totalPtr=number_format($totalVentaX,2,".",",");
	if($montoVenta-$totalVentaX>0 || $montoVenta-$totalVentaX<0){
		$colorObs="#ff0000";
	}else{
		$colorObs="#ffffff";
	}
	$tablaDetalle.="<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<th>Total:</th>
		<th bgcolor='$colorObs'>$totalPtr</th>
	<tr></table>";

	
	echo "<tr>
	<td>$fechaVenta</td>
	<td>$nombreCliente</td>
	<td>$razonSocial</td>
	<td>$datosDoc</td>
	<td>$montoVentaFormat</td>
	<td>$tablaDetalle</td>
	</tr>";
}
$totalVentaFormat=number_format($totalVenta,2,".",",");
$totalFactVentaXFormato=number_format($totalFactVentaX,2,".",",");
echo "<tr>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<th>Total Marca(s)</th>
	<th align='right'>$totalFactVentaXFormato</th>
</tr>";
echo "<tr>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<th>Total Monto Venta(s)</th>
	<th align='right'>$totalVentaFormat</th>
</tr>";
echo "</table></br>";


include("imprimirInc.php");
?>