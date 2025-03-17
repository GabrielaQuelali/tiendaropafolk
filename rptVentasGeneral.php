<?php


require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli.inc');
require('funcion_nombres.php');
require('funciones.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
$global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$globalAlmacen=$_COOKIE['global_almacen'];

$sqlFuncProv="select * from funcionarios_proveedores where codigo_funcionario=$global_usuario";
$respFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);
$cantFuncProv=mysqli_num_rows($respFuncProv);
$global_agencia=$_COOKIE['global_agencia'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=($fecha_ini);
$fecha_finconsulta=($fecha_fin);

$rptTerritorio=$_GET['rpt_territorio'];
//echo "rpt_territorio=".$rptTerritorio;
$rptMarca=$_GET["rpt_marca"];
$rptTipoPago=$_GET["rpt_tipoPago"];

$cadenaTerritorio="TODOS";	
if($rptTerritorio=="-1"){
	$cadenaTerritorio="TODOS";
	$rptTerritorio=""; $swTerritorio=0;	 
	$sqlTerritorio="select cod_ciudad, descripcion from ciudades order by descripcion asc";
	$respTerritorio=mysqli_query($enlaceCon,$sqlTerritorio);
	while($datTerritorio=mysqli_fetch_array($respTerritorio))
	{	$codTerritorio=$datTerritorio[0];
		if($swTerritorio==0){
			$rptTerritorio=$datTerritorio[0];
			$swTerritorio=1;
		}else{
			$rptTerritorio=$rptTerritorio.",";
			$rptTerritorio=$rptTerritorio.$datTerritorio[0];
		}
	}
	//echo "rptTerritorio".$rptTerritorio."<br>";;
}else{
	$swCadenaTerritorio=0;	
	$sqlTerritorio="select cod_ciudad, descripcion from ciudades where cod_ciudad in(".$rptTerritorio.")	order by descripcion asc";
	//echo $sqlTerritorio;
	$respTerritorio=mysqli_query($enlaceCon,$sqlTerritorio);
	while($datTerritorio=mysqli_fetch_array($respTerritorio)){	
		if($swCadenaTerritorio==0){
			$cadenaTerritorio=$datTerritorio[1];
			$swCadenaTerritorio=1;
		}else{
			$cadenaTerritorio=$cadenaTerritorio.";";
			$cadenaTerritorio=$cadenaTerritorio.$datTerritorio[1];
		}
		
	}

	
}
//echo "holaaaaaaa";
$global_agencia=$rptTerritorio;

$cadenaTipoPagos="TODOS";	
if($rptTipoPago=="-1"){
	$cadenaTipoPagos="TODOS";
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
	//echo "rptTipoPago".$rptTipoPago."<br>";;
}else{
	$swCadenaTipoPago=0;	
	$sqlTipoPago="select cod_tipopago, nombre_tipopago from tipos_pago where estado=1 and cod_tipopago in(".$rptTipoPago.")	order by cod_tipopago asc";
	$respTipoPago=mysqli_query($enlaceCon,$sqlTipoPago);
	while($datTipoPago=mysqli_fetch_array($respTipoPago)){	
		if($swCadenaTipoPago==0){
			$cadenaTipoPagos=$datTipoPago[1];
			$swCadenaTipoPago=1;
		}else{
			$cadenaTipoPagos=$cadenaTipoPagos.";";
			$cadenaTipoPagos=$cadenaTipoPagos.$datTipoPago[1];
		}
		
	}

	
}


$fecha_reporte=date("d/m/Y");


echo "<table align='center'  >
<tr class='textotit' align='center' ><th  colspan='2'  >REPORTE DE VENTAS X DOCUMENTO Y PRODUCTO</th></tr>
	<tr ><th>Territorio:</th><td> $cadenaTerritorio </td> </tr>
	<tr><th>De:</th> <td> $fecha_ini A:$fecha_fin</td></tr>
	<tr><th>Tipos de Pago: </th><td>$cadenaTipoPagos</td></tr>
	<tr><th>Fecha Reporte:</th> <td>$fecha_reporte</td></tr>	
	</table>";

$sql="select concat(s.fecha,' ',s.hora_salida)as fecha,
c.nombre_cliente,
s.razon_social, s.observaciones,
t.abreviatura,
s.nro_correlativo,
s.monto_final, s.cod_salida_almacenes,
tp.nombre_tipopago,s.cod_chofer,

(select count(*) from recibos r where r.cod_salida_almacen=s.cod_salida_almacenes 
and s.cod_almacen in (select cod_almacen from almacenes where cod_ciudad in (".$rptTerritorio."))) as num_recibos,
tp.contabiliza, al.nombre_almacen
from salida_almacenes s 
left join tipos_pago tp on (tp.cod_tipopago=s.cod_tipopago)
left join clientes c  on (c.`cod_cliente`=s.cod_cliente)
left join tipos_docs t on(t.codigo=s.cod_tipo_doc)
left join almacenes al on(s.cod_almacen=al.cod_almacen)
where s.cod_tiposalida=1001 and s.salida_anulada=1 
and	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.cod_ciudad in (".$rptTerritorio."))";	
$sql=$sql." and s.`fecha` BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."' ";

	
if(!empty($rptTipoPago)){
	$sql=$sql." and s.cod_tipopago  in( $rptTipoPago) ";
	}
$sql.=" order by s.fecha, s.hora_salida, s.nro_correlativo";
//echo $sql;

$resp=mysqli_query($enlaceCon,$sql);
?>
<br><table align='center' width='70%' border="1">
<tr>
<th>Fecha</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Documento</th>
<th>Forma Pago</th>
<th>Monto</th>
<th>Responsable</th>
<th>
	<table width='100%' >
	<tr>
		<th >Codigo</th>
		<th>Producto</th>
		<th>Color/Talla</th>
		<th>Cantidad</th>
		<th>Monto</th>
	</tr>
	</table>
</th>
<th>&nbsp;</th>
</tr>
<?php
$totalVenta=0;
$totalVentaRecibo=0;
$totalVentaNoContabiliza=0;
$cadenaRecibo="";
$fondoRecibos="#ffffff";
while($datos=mysqli_fetch_array($resp)){	
	$fechaVenta=$datos[0];
	$nombreCliente=$datos[1];
	$razonSocial=$datos[2];
	$obsVenta=$datos[3];
	$datosDoc=$datos[4]."-".$datos[5];
	$montoVenta=$datos[6];
	$codSalida=$datos[7];
	$nombreTipoPago=$datos[8];
	$cod_funcionario=$datos[9];
	$numRecibos=$datos[10];
	$contabiliza=$datos[11];
	$nombre_almacen=$datos[12];
	
	
	$sqlResponsable="select CONCAT(SUBSTRING_INDEX(nombres,' ', 1),' ',SUBSTR(paterno, 1,1),'.') from funcionarios where codigo_funcionario='".$cod_funcionario."'";
	$respResponsable=mysqli_query($enlaceCon,$sqlResponsable);
	$datResponsable=mysqli_fetch_array($respResponsable);
	$nombreFuncionario=$datResponsable[0];
	$cadenaRecibo="";
	if ($numRecibos>0){
		$sqlRecVenta=" select r.id_recibo,r.fecha_recibo,r.nombre_recibo,r.desc_recibo,r.monto_recibo, r.created_by,r.cod_tipopago, ";
		$sqlRecVenta.=" r.cod_tiporecibo,r.cod_proveedor ";
		$sqlRecVenta.=" from recibos r ";
		$sqlRecVenta.=" left join  salida_almacenes sa on(r.cod_salida_almacen=sa.cod_salida_almacenes and sa.cod_almacen in (select cod_almacen from almacenes where cod_ciudad in ".$rptTerritorio."))";
		$sqlRecVenta.=" where r.cod_ciudad in (".$rptTerritorio.")";
		$sqlRecVenta.=" and r.recibo_anulado=1 ";
		$sqlRecVenta.=" and r.cod_salida_almacen=".$codSalida;
		$sqlRecVenta.=" order by r.fecha_recibo";
		$respRecVenta=mysqli_query($enlaceCon,$sqlRecVenta);
		while($datRecVenta=mysqli_fetch_array($respRecVenta)){
		
			$vector_fecha_recibo=explode("-",$datRecVenta['fecha_recibo']);
			$fecha_recibo_mostrar=$vector_fecha_recibo[2]."/".$vector_fecha_recibo[1]."/".$vector_fecha_recibo[0];
			
			$cadenaRecibo.="REC-".$datRecVenta['id_recibo']." ".$fecha_recibo_mostrar." <strong>".$datRecVenta['monto_recibo']." Bs</strong><br>";
		}
	}

	$montoVentaFormat=number_format($montoVenta,2,".",",");
	if($numRecibos>0){
		$fondoRecibos="#c4d4e9";
		$totalVentaRecibo=$totalVentaRecibo+$montoVenta;
		
	}else{
	
		$fondoRecibos="#ffffff";
		
	}
	
	if($contabiliza==0){
		$totalVentaNoContabiliza=$totalVentaNoContabiliza+$montoVenta;
	}
		$totalVenta=$totalVenta+$montoVenta;
	
	$sqlX="select m.codigo_barras, m.`descripcion_material`, 
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total, m.color, m.talla, 
	mar.nombre,m.cod_marca,m.codigo2
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m, marcas mar
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` 
	and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`=1 
	and sd.`cod_material`=m.`codigo_material` 
	and m.cod_marca=mar.codigo
	and	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.cod_ciudad in (".$rptTerritorio.")) 
	and	s.cod_salida_almacenes='$codSalida' ";

	$sqlX=$sqlX." group by m.`codigo_material` order by 2 desc;";
	//echo $sqlX;
	
	$respX=mysqli_query($enlaceCon,$sqlX);

	$tablaDetalle="<table  border='1'>";
	
	$totalVentaX=0;
	
	while($datosX=mysqli_fetch_array($respX)){	
		$codItem=$datosX[0];
		$nombreItem=$datosX[1];
		$montoVentaProd=$datosX[2];
		$cantidad=$datosX[3];
		
		$descuentoVenta=$datosX[4];
		$montoNota=$datosX[5];
		
		$colorItem=$datosX[6];
		$tallaItem=$datosX[7];
		$nombreMarca=$datosX[8];
		$codMarca=$datosX[9];
		$codigo2=$datosX[10];
		
		if($descuentoVenta>0){
			$porcentajeVentaProd=($montoVentaProd/$montoNota);
			$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
			$montoVentaProd=$montoVentaProd-$descuentoAdiProducto;
		}
		
		$montoPtr=number_format($montoVentaProd,2,".",",");
		$cantidadFormat=number_format($cantidad,0,".",",");
		
		$totalVentaX=$totalVentaX+$montoVentaProd;
				
		$tablaDetalle.="<tr>
		<td>$codItem $codigo2</td>
		<td>$nombreMarca $nombreItem</td>
		<td>$colorItem/$tallaItem</td>
		<td>$cantidadFormat</td>
		<td align='right'>$montoPtr</td>		
		</tr>";
	}
	$totalPtr=number_format($totalVentaX,2,".",",");
	if(($montoVenta-$totalVentaX)>0 || ($montoVenta-$totalVentaX)<0){
		$colorObs="#ff0000";
	}else{
		$colorObs="#ffffff";
	}
	$tablaDetalle.="<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td><strong>Total:</strong></td>
		<td bgcolor='$colorObs' align='right'><strong>$totalPtr</strong></td>
	<tr>
	</table>";

?>

	<tr bgcolor='<?php if($contabiliza==0){echo "#fddeda"; }else{ echo $fondoRecibos;}?>'>
	<td><?=$fechaVenta."-".$contabiliza;?></td>
	<td><?=$nombreCliente;?></td>
	<td><?=$razonSocial;?></td>
	<td><?=$datosDoc;?></td>
	<td><?=$nombreTipoPago;?></td>	
	<td align='right'><strong><?=$montoVentaFormat;?></strong><br><?=$cadenaRecibo;?></td>
	<td><?=$nombreFuncionario;?><br><?=$nombre_almacen;?></td> 
	<td><?=$tablaDetalle;?></td>
	<th>&nbsp;</th>
	</tr>
<?php
}
?>
<tr >
	<td colspan="4">&nbsp;</td>
	<td align='center'><strong>Forma de Pago</strong></td>	
	<td align='center'><strong>Ventas</strong></td>
	<td align='center'><strong>Ventas Pagadas con Recibo</strong></td>
	<td align='center'><strong>Ventas que no<br> Generan Ingreso Monetario</strong></td>
	<td align='center'><strong>Total Ingreso<br> por Ventas</strong></td>
	
</tr>
<?php
$sql2="select cod_tipopago, sum(s.`monto_final`)
	from `salida_almacenes` s 
	where s.`cod_tiposalida`=1001 
	and s.salida_anulada=1 
	and	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.cod_ciudad in (".$rptTerritorio."))";		
	$sql2=$sql2." and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' ";
	
if(!empty($rptTipoPago)){
	$sql2=$sql2." and s.cod_tipopago  in( $rptTipoPago) ";
	}
$sql2.=" group by cod_tipopago order by cod_tipopago asc";
 //echo $sql2;

$resp2=mysqli_query($enlaceCon,$sql2);
while($datos2=mysqli_fetch_array($resp2)){	
	$tipoPago=$datos2[0];
	$sqlTipoPago2="select cod_tipopago, nombre_tipopago,contabiliza from tipos_pago where cod_tipopago=".$tipoPago;
	$respTipoPago2=mysqli_query($enlaceCon,$sqlTipoPago2);
	while($datTipoPago2=mysqli_fetch_array($respTipoPago2)){	
		$nombreTipoPago2=$datTipoPago2[1];
		$contabiliza2=$datTipoPago2[2];
	}
	$montoTipoPago=$datos2[1];
	$montoTipoPagoFormat=number_format($montoTipoPago,2,".",",");
	//////////////////////
	$sql4="select cod_tipopago, sum(s.`monto_final`)
	from `salida_almacenes` s 
	where s.`cod_tiposalida`=1001 
	and s.salida_anulada=1 and	s.`cod_almacen` in 
	(select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in (".$rptTerritorio."))";	
	$sql4.=" and s.cod_salida_almacenes  in (select r.cod_salida_almacen  from recibos r where  r.cod_ciudad in (".$rptTerritorio.") and r.cod_salida_almacen is not null)";
	$sql4.=" and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' ";	
	$sql4.=" and s.cod_tipopago  in( $tipoPago) ";
	$sql4.=" group by cod_tipopago order by cod_tipopago asc";
	$resp4=mysqli_query($enlaceCon,$sql4);
	$montoPagadoRec=0;
	while($dat4=mysqli_fetch_array($resp4)){
		$montoPagadoRec=$dat4[1];
	}
	$montoPagadoRecFormat=number_format($montoPagadoRec,2,".",",");
	////////////////////////
	$montoNOContabiliza=0;
	if($contabiliza2==0){
		$montoNOContabiliza=$montoTipoPago;
	}
	$montoRealTipoPago= $montoTipoPago-$montoPagadoRec-$montoNOContabiliza;
	$montoRealTipoPagoFormat=number_format($montoRealTipoPago,2,".",",");
?>
	<tr >
	<td>-</td>
	<td>-</td>	
	<td>-</td>
	<td>-</td>
	<td><?=$nombreTipoPago2;?></td>
	<td align='right'><?=$montoTipoPagoFormat;?></td>
	<td align='right'><?=$montoPagadoRecFormat;?></td>
	<td align='right'><?=$montoNOContabiliza;?></td>
	<td align='right'><?=$montoRealTipoPagoFormat;?></td>
</tr>
<?php
}

$totalVentaFormat=number_format($totalVenta,2,".",",");
$totalVentaReciboFormat=number_format($totalVentaRecibo,2,".",",");
$totalVentaNoContabilizaFormat=number_format($totalVentaNoContabiliza,2,".",",");
$montoRealVenta=($totalVenta-$totalVentaRecibo-$totalVentaNoContabiliza);
$montoRealVentaFormat=number_format($montoRealVenta,2,".",",");
?>
<tr>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<td>-</td>


	<td><strong>TOTALES</strong></td>
	<td align='right' title="Total Ventas"><strong><?=$totalVentaFormat;?></strong></td>
	<td align='right' title="Total Ventas Pagadas con Recibo"><strong><?=$totalVentaReciboFormat;?></strong></td>
	<td align='right' title="Total Ventas Pagadas con Recibo"><strong><?=$totalVentaNoContabilizaFormat;?></strong></td>
	<td align='right'title="Monto Ventas" bgcolor="#BDFBB7" ><strong><?=$montoRealVentaFormat;?></strong></td>
	
</tr>
</table></br>

<br><table align='center' border="1" width='70%'>
<tr><th colspan='12'>DETALLES RECIBO</th></tr>
<tr>
<th>Tipo Recibo</th>
<th>Documento</th>
<th>Factura</th>
<th>Fecha</th>
<th>Cliente</th>
<th>Detalle</th>
<th>Proveedor</th>
<th>Resta Venta</th>
<th>Responsable</th>
<th>FormaPago</th>
<th>Monto Ingresado [Bs]</th>
<th>Monto que <br>Debe ser Restado de Ventas </th>

</tr>
<?php

$consulta = " select r.id_recibo,r.fecha_recibo,r.cod_ciudad,ciu.descripcion,
r.nombre_recibo,r.desc_recibo,r.monto_recibo,
r.created_by,r.modified_by,r.created_date,r.modified_date, r.cel_recibo,r.recibo_anulado,r.cod_tipopago, tp.nombre_tipopago,
r.cod_tiporecibo, tr.nombre_tiporecibo, r.cod_proveedor, p.nombre_proveedor, r.cod_salida_almacen,
r.cod_estadorecibo, er.nombre_estado, r.resta_ventas_proveedor
from recibos r 
inner join ciudades ciu on (r.cod_ciudad=ciu.cod_ciudad)
inner join tipos_pago tp on(r.cod_tipopago=tp.cod_tipopago)
inner join tipos_recibo tr on(r.cod_tiporecibo=tr.cod_tiporecibo)
left  join proveedores p on (r.cod_proveedor=p.cod_proveedor)
left  join estados_recibo er on (r.cod_estadorecibo=er.cod_estado)
where r.cod_ciudad in (".$rptTerritorio.") and r.recibo_anulado=1 ";
$consulta = $consulta." AND r.fecha_recibo BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'";
if(!empty($rptTipoPago)){
	$consulta=$consulta." and r.cod_tipopago  in( $rptTipoPago) ";
	}
$consulta=$consulta." order by r.id_recibo asc,r.cod_ciudad desc ";

//echo $consulta;

$resp = mysqli_query($enlaceCon,$consulta);
$totalRecibo=0;
$totalReciboRestaVentas=0;
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
	$cod_salida_almacen= $dat['cod_salida_almacen'];
	$cod_estadorecibo= $dat['cod_estadorecibo'];
	$nombre_estadorecibo= $dat['nombre_estado'];
	$resta_ventas_proveedor= $dat['resta_ventas_proveedor'];
	if($resta_ventas_proveedor=="1"){
		$fondo2="#ffffbf";
			$totalReciboRestaVentas=$totalReciboRestaVentas+$monto_recibo;
	
	}else{
		$fondo2="#ffffff";
		$totalRecibo=$totalRecibo+$monto_recibo;
		
	}
	
	//////////////////
		$sqlVenta = " SELECT s.fecha, s.hora_salida, s.nro_correlativo, s.cod_tipo_doc, td.abreviatura, razon_social, nit,
    s.monto_total,s.monto_final,concat(f.paterno,' ',f.materno,' ',f.nombres) as vendedor
    FROM salida_almacenes s
	left join tipos_salida ts  on (s.cod_tiposalida = ts.cod_tiposalida)
	left join tipos_docs td   on (s.cod_tipo_doc = td.codigo)
	left join funcionarios f   on (s.cod_chofer = f.codigo_funcionario)
    WHERE  s.cod_almacen = '".$globalAlmacen."' and s.cod_tiposalida=1001 and s.cod_salida_almacenes=".$cod_salida_almacen;		
	//echo $sqlVenta;
	$respVenta = mysqli_query($enlaceCon,$sqlVenta);
	/////////
		$fecha_salida = ""; $fecha_salida_mostrar = "";		$hora_salida = "";
		$nro_correlativo= ""; $cod_tipo_doc= "";$abreviatura_tipodoc= "";
		$razon_social= "";$nit="";$monto_total="";$monto_final= "";	$vendedor= "";
	/////////
	while ($datVenta = mysqli_fetch_array($respVenta)) {	
		$fecha_salida = $datVenta['fecha'];
		$fecha_salida_mostrar = $fecha_salida[8].$fecha_salida[9]."-".$fecha_salida[5].$fecha_salida[6]."-".$fecha_salida[0].$fecha_salida[1].$fecha_salida[2].$fecha_salida[3];
		$hora_salida = $datVenta['hora_salida'];
		$nro_correlativo= $datVenta['nro_correlativo'];
		$cod_tipo_doc= $datVenta['cod_tipo_doc'];
		$abreviatura_tipodoc= $datVenta['abreviatura'];
		$razon_social= $datVenta['razon_social'];
		$nit= $datVenta['nit'];
		$monto_total= $datVenta['monto_total'];
		$monto_final= $datVenta['monto_final'];		
		$vendedor= $datVenta['vendedor'];
	}
	
	//////////////////

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
		$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
		$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
		$usuMod ="";
		while($datModUsu=mysqli_fetch_array($respModUsu)){
			$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
		}
	?>
	<tr bgcolor="<?php if(empty($cod_salida_almacen)){echo $fondo2;}else{ echo "#c4d4e9";}?>">
	<td><?=$nombre_tiporecibo;?></td>
	<td>REC-<?=$id_recibo;?></td>
	<td><?=$abreviatura_tipodoc." ".$nro_correlativo."<br>".$fecha_salida_mostrar." ".$hora_salida." <strong>".$monto_final."</strong>";?></td>
	<td><?=$fecha_recibo_mostrar;?></td>
	
	
	<td><?=$nombre_recibo;?></td>
	<td><?=$desc_recibo;?></td>
	<td><?=$nombre_proveedor;?></td>
		<td>
	<?php    

		if($resta_ventas_proveedor=="0"){
			echo "NO";
		}
		if($resta_ventas_proveedor=="1"){
			echo "SI";
		}
		
		?>
	</td>
	<td><?=$usuReg ;?></td>
	<td><?=$nombre_tipopago;?></td>
			
	<td align='right'><?php if($resta_ventas_proveedor=="0"){echo $monto_recibo;}else{echo "<center>-</center>";}?></td>
	<td align='right'><?php if($resta_ventas_proveedor=="1"){echo $monto_recibo;}else{echo "<center>-</center>";}?></td>
	
	</tr>
<?php
}

$totMontoTipopago=0;
$sql2="select r.cod_tipopago,sum(r.monto_recibo)  from recibos r where r.cod_ciudad in (".$rptTerritorio.") and r.recibo_anulado=1 and resta_ventas_proveedor=0";
$sql2 = $sql2." AND r.fecha_recibo BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'";
if(!empty($rptTipoPago)){
	$sql2=$sql2." and r.cod_tipopago  in( $rptTipoPago) ";
	}
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
	<td>&nbsp;</td>		
	<td>&nbsp;</td>	
	<td>&nbsp;</td>		
	<td align="right"><strong><?=$descTipopago;?></strong></td>
	<td align="right"><strong><?=$totMontoTipopagoF;?></strong></td>

<tr>

<?php
}
$totalReciboF=number_format($totalRecibo,2,".",",");
$totalReciboRestaVentasF=number_format($totalReciboRestaVentas,2,".",",");
?>
<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>	
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>		
		<td>&nbsp;</td>		
	<td align="right"><strong>TOTAL RECIBOS:</strong></td>
	<td align="right" bgcolor="#BDFBB7"><strong><?=$totalReciboF;?></strong></td>
	<td align="right"><strong><?=$totalReciboRestaVentasF;?></strong></td>

<tr>
</table>
<br><center><table border="1">
<tr><th colspan='8'>Gastos</th></tr>
<tr>
<th>Tipo</th>
<th>Nro</th>
<th>Fecha</th>
<th>Proveedor</th>
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
where    g.cod_ciudad in (".$rptTerritorio.") and g.gasto_anulado=1
and g.fecha_gasto BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'";
if(!empty($rptTipoPago)){
	$sqlGasto=$sqlGasto." and g.cod_tipopago  in( $rptTipoPago) ";
	}
$sqlGasto=$sqlGasto." order by g.cod_gasto asc ";

//echo $sqlGasto;
$totalGastos=0;	
$respGasto= mysqli_query($enlaceCon,$sqlGasto);

while ($datGasto = mysqli_fetch_array($respGasto)) {
	
	$cod_gasto= $datGasto['cod_gasto'];
	$descripcion_gasto= $datGasto['descripcion_gasto'];
	$cod_tipogasto= $datGasto['cod_tipogasto'];
	$nombre_tipogasto= $datGasto['nombre_tipogasto'];
	$fecha_gasto= $datGasto['fecha_gasto'];	
	$vector_fecha_gasto=explode("-",$fecha_gasto);
	$fecha_gasto_mostrar=$vector_fecha_gasto[2]."/".$vector_fecha_gasto[1]."/".$vector_fecha_gasto[0];
	$monto= $datGasto['monto'];
	$cod_ciudad= $datGasto['cod_ciudad'];
	$created_by= $datGasto['created_by'];
	$modified_by= $datGasto['modified_by'];
	$created_date= $datGasto['created_date'];
	$modified_date= $datGasto['modified_date'];
	$gasto_anulado= $datGasto['gasto_anulado'];
	$cod_proveedor= $datGasto['cod_proveedor'];
	$nombre_proveedor= $datGasto['nombre_proveedor'];
	$cod_grupogasto= $datGasto['cod_grupogasto'];
	$nombre_grupogasto= $datGasto['nombre_grupogasto'];
	$cod_tipopago= $datGasto['cod_tipopago'];
	$nombre_tipopago= $datGasto['nombre_tipopago'];

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

$sql2="select g.cod_tipopago,sum(g.monto)  from gastos g where g.cod_ciudad in (".$rptTerritorio.")and g.gasto_anulado=1 ";
$sql2 = $sql2." AND g.fecha_gasto BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'";
if(!empty($rptTipoPago)){
	$sql2=$sql2." and g.cod_tipopago  in( $rptTipoPago) ";
}
$sql2 = $sql2."group by g.cod_tipopago order by g.cod_tipopago asc";

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
<td align="right" bgcolor="#BDFBB7"><strong><?=$totalGastos;?></strong></td>
</tr>
</table></center><br>
<center>
<table border="1">
<tr><th colspan='7'>TOTALES POR TIPO DE PAGO</th></tr>
<tr>
<th>&nbsp;</th>
<th>Ventas </th>
<th>Recibos </th>
<th>Total Ingresos</th>
<th>Gastos </th>
<th>Total Egresos </th>
<th>Saldo </th>
</tr>
<?php
$totVTA=0;
$totREC=0;
$totGTO=0;

	$sqlTP="select cod_tipopago, nombre_tipopago,contabiliza  from tipos_pago  where estado=1 order by cod_tipopago asc";
	$respTP = mysqli_query($enlaceCon,$sqlTP);
	while ($datTP = mysqli_fetch_array($respTP)) {
		$codTP=$datTP['cod_tipopago'];
		$nombreTP=$datTP['nombre_tipopago'];
		$contabilizaTP=$datTP['contabiliza'];
		/////////////////VENTAS/////////
		$totalVTATP=0;
		$sqlVTA="select cod_tipopago, sum(s.`monto_final`)
		from `salida_almacenes` s 
		where s.`cod_tiposalida`=1001 
		and s.salida_anulada=1 and	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad`='$rptTerritorio')";	
		$sqlVTA=$sqlVTA." and s.cod_salida_almacenes not in (select r.cod_salida_almacen  from recibos r where  r.cod_ciudad=".$rptTerritorio." and r.cod_salida_almacen is not null)";
		$sqlVTA=$sqlVTA." and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' ";
		$sqlVTA=$sqlVTA." and s.cod_tipopago  in( ".$codTP.") ";
		$sqlVTA.=" group by cod_tipopago ";		
		$respVTA=mysqli_query($enlaceCon,$sqlVTA);		
		while($datosVTA=mysqli_fetch_array($respVTA)){	
			$totalVTATP=$datosVTA[1];
		}
		////////////////////////////////////////
		////////////RECIBOS//////////
		$totalRECTP=0;
		$sqlREC="select r.cod_tipopago,sum(r.monto_recibo)  from recibos r where r.cod_ciudad in (".$rptTerritorio.") and r.recibo_anulado=1 and r.resta_ventas_proveedor=0";
		$sqlREC =$sqlREC." AND r.fecha_recibo BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'";
		$sqlREC=$sqlREC." and r.cod_tipopago  in(".$codTP.") ";	
		$sqlREC = $sqlREC."group by r.cod_tipopago ";

		$respREC = mysqli_query($enlaceCon,$sqlREC);
		while ($datREC = mysqli_fetch_array($respREC)) {	
			$totalRECTP= $datREC[1];
		}
		//////////////////////////
		//////////////GASTOS/////////////
		$totalGTOTP=0;
		$sqlGTO=" select g.cod_tipopago,sum(g.monto)  from gastos g where g.cod_ciudad in (".$rptTerritorio.") and g.gasto_anulado=1 ";
		$sqlGTO = $sqlGTO." AND g.fecha_gasto BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'";
		$sqlGTO = $sqlGTO." and g.cod_tipopago  in(".$codTP.") ";	
		$sqlGTO = $sqlGTO."group by g.cod_tipopago order by g.cod_tipopago asc";		
		$respGTO = mysqli_query($enlaceCon,$sqlGTO);
		while ($datGTO = mysqli_fetch_array($respGTO)){		
			$totalGTOTP= $datGTO[1];
		}
		/////////////////////////////////
		
		if($contabilizaTP==1){
			$totVTA=$totVTA+$totalVTATP;
			$totREC=$totREC+$totalRECTP;
			$totGTO=$totGTO+$totalGTOTP;
		}
?>
<tr>
<td align="right"><?=$nombreTP;?></td>
<td align="right"><?=$totalVTATP;?></td>
<td align="right"><?=$totalRECTP;?></td>
<td align="right" bgcolor="#DEFBB7"><?php if($contabilizaTP==1){ echo($totalVTATP+$totalRECTP);}else {echo "0";}?></td>
<td align="right"><?=$totalGTOTP;?></td>
<td align="right" bgcolor="#DEFBB7"><?=$totalGTOTP;?></td>
<td align="right" ><?php if($contabilizaTP==1){ echo($totalVTATP+$totalRECTP-$totalGTOTP);}else{ echo "0";}?></td>
</tr>
<?php
}
?>
<tr>
<td align="right">&nbsp;</td>
<td align="right"><strong><?=$totVTA;?></strong></td>
<td align="right"><strong><?=$totREC;?></strong></td>
<td align="right" bgcolor="#DEFBB7"><strong><?=($totVTA+$totREC);?></strong></td>
<td align="right"><strong><?=$totGTO;?></strong></td>
<td align="right"bgcolor="#DEFBB7"><strong><?=$totGTO;?></strong></td>
<td align="right" bgcolor="#BDFBB7"><strong><?=($totVTA+$totREC-$totGTO);?></strong></td>
</tr>

</table>
</center>
<br>
<?php 


//include("imprimirInc.php");
?>