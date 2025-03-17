<html>
<body>

<?php
require('conexionmysqli.inc');
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');

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
$rptProveedor=$_GET["rpt_proveedor"];
$rptTipoPago=$_GET["rpt_tipoPago"];

//echo "tipoPago".$rptTipoPago."<br>";
$cadenaProveedor="TODOS";	
if($rptProveedor=="-1"){
	 $rptProveedor=""; $swProveedor=0;	 
	$sqlProveedor="select cod_proveedor from proveedores where estado= 1";

	$respProveedor=mysqli_query($enlaceCon,$sqlProveedor);
	while($datProveedor=mysqli_fetch_array($respProveedor))
	{
		if($swProveedor==0){
			$rptProveedor=$datProveedor[0];
			$swProveedor=1;
		}else{
			$rptProveedor=$rptProveedor.",";
			$rptProveedor=$rptProveedor.$datProveedor[0];
		}
	}

}else{
	$swCadenaProveedor=0;	
	$sqlCadenaProveedor="select cod_proveedor, nombre_proveedor from proveedores where estado=1 and cod_proveedor in(".$rptProveedor.")	order by nombre_proveedor asc";
	//echo  $sqlCadenaProveedor;
	$respCadenaProveedor=mysqli_query($enlaceCon,$sqlCadenaProveedor);
	while($datCadenaProveedor=mysqli_fetch_array($respCadenaProveedor)){	
		if($swCadenaProveedor==0){
			$cadenaProveedor=$datCadenaProveedor[1];
			$swCadenaProveedor=1;
		}else{
			$cadenaProveedor=$cadenaProveedor.";";
			$cadenaProveedor=$cadenaProveedor.$datCadenaProveedor[1];
		}
		
	}
	
}
$cadenaTipoPagos="TODOS";	
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
	echo "rptTipoPago".$rptTipoPago."<br>";
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
//echo "marcaAA".$rptMarca."<br>";
	//echo "rptTipoPago".$rptTipoPago."<br>";

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorio($enlaceCon,$rpt_territorio);
?>
<table align="center"  >
<tr class="textotit" align='center' ><th  colspan="2"  >Reporte de Ajuste de Ventas por Proveedor</th></tr>
	<tr ><th>Territorio:</th><td><?php echo $nombre_territorio;?></td> </tr>
	<tr><th>De: </th> <td><?php  echo $fecha_ini;?> A: <?php  echo $fecha_fin;?></td></tr>
	<tr><th>Proveedores: </th><td><?php  echo $cadenaProveedor;?></td></tr>
	<tr><th>Tipos de Pago: </th><td><?php  echo $cadenaTipoPagos;?></td></tr>
	<tr><th>Fecha Reporte:</th> <td><?php  echo$fecha_reporte;?></td></tr>	
	</table>
<?php

$sqlProvMarcas="select p.cod_proveedor, p.nombre_proveedor, pm.codigo, m.nombre
from proveedores p 
left join  proveedores_marcas pm on (p.cod_proveedor=pm.cod_proveedor)
left join  marcas m on (pm.codigo=m.codigo)
where p.estado=1 ";
if(!empty($rptProveedor)){
	$sqlProvMarcas.=" and p.cod_proveedor in( $rptProveedor)";
}
$sqlProvMarcas.=" order by p.nombre_proveedor";
//echo $sqlProvMarcas;
$respProvMarcas=mysqli_query($enlaceCon,$sqlProvMarcas);
?>
<table align='center'  width='85%' border="1">
<?php
	$totalVentaProd=0;
	$totalVentaDesc=0;
	$totalVentaCobrado=0;
$totalaPagar=0;
while ($datProvMarcas = mysqli_fetch_array($respProvMarcas)) {
	
	$codProveedor=$datProvMarcas['cod_proveedor'];
	$nombreProveedor=$datProvMarcas['nombre_proveedor'];
	$codMarca=$datProvMarcas['codigo'];
	$nombreMarca=$datProvMarcas['nombre'];
	
	$totalIngresoProveeedor=0;
?>
<tr><td colspan="15"  ><strong><?=$nombreProveedor;?> MARCA:<?=$nombreMarca;?><strong></td></tr>
<?php

	$sql=" select mar.nombre, concat(s.fecha,' ',s.hora_salida)as fecha,c.nombre_cliente,
	s.razon_social, s.observaciones,t.abreviatura,
	s.nro_correlativo, tp.nombre_tipopago, s.descuento,s.monto_final, s.cod_salida_almacenes,s.cod_chofer,
	m.codigo_barras, m.descripcion_material, 
	sd.monto_unitario,sd.descuento_unitario, 
	sd.cantidad_unitaria, m.color, m.talla, m.cod_marca,m.codigo2 
	from salida_detalle_almacenes sd
	left join salida_almacenes s on (sd.cod_salida_almacen=s.cod_salida_almacenes) 
	left join material_apoyo m on (sd.cod_material=m.codigo_material)
	left join marcas  mar on (m.cod_marca=mar.codigo)
	left join clientes c on(s.cod_cliente=c.cod_cliente)
	left join tipos_docs t on (t.codigo=s.cod_tipo_doc)
	left join tipos_pago tp on (tp.cod_tipopago=s.cod_tipopago)
	where  s.salida_anulada=0 " ;
	$sql.=" and s.fecha BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta' ";
	if(!empty($rptTipoPago)){
		$sql.=" and s.cod_tipopago  in( $rptTipoPago) ";
	}
	/*if(!empty($rptMarca)){
		$sql.=" and m.cod_marca in( $rptMarca)";
	}*/
	$sql.=" and m.cod_marca=".$codMarca;
	$sql.=" order by mar.nombre asc , s.fecha asc ";
	//echo  "<br/>".$sql;
	$resp=mysqli_query($enlaceCon,$sql);
    $numVentasProv=mysqli_num_rows($resp);
	//echo "numVentasProv=".$numVentasProv;
    if($numVentasProv==0){
			$totalMarcaProd=0;
	$totalMarcaDescProd=0;
	$totalMarcaVentaProd=0;	
	  
	  
	}else{
	?>

	<tr>
	<th>Fecha</th>
	<th>Cliente</th>
	<th>Razon Social</th>
	<th>Documento</th>
	<th>Forma Pago</th>
	<th>Monto Factura</th>
	<th>Descuento Factura</th>
	<th>Responsable</th>
	<th>Cod Producto</th>
	<th>Producto</th>
	<th>Color/Talla</th>
	<th>Cantidad</th>
	<th>Precio Producto</th>
	<th>Descuento</th>
	<th>Precio Venta Producto</th>
	</tr>
<?php
	$totalMarcaProd=0;
	$totalMarcaDescProd=0;
	$totalMarcaVentaProd=0;		
	while($datos=mysqli_fetch_array($resp)){	
		$fechaVenta=$datos['fecha'];
		$nombreCliente=$datos['nombre_cliente'];
		$razonSocial=$datos['razon_social'];
		$obsVenta=$datos['observaciones'];
		$datosDoc= $datos['abreviatura']."-".$datos['nro_correlativo'];
		$nombreTipopago= $datos['nombre_tipopago'];
		$descuentoVenta= $datos['descuento'];
		$montoVenta= $datos['monto_final'];
		$codSalida=$datos['cod_salida_almacenes'];
		$cod_funcionario=$datos['cod_chofer'];
		$codigoBarras=$datos['codigo_barras'];
		$descripcionMaterial=$datos['descripcion_material'];
		$montoUnitario=$datos['monto_unitario'];
		$descuentoUnitario=$datos['descuento_unitario'];
		$cantidadUnitaria=$datos['cantidad_unitaria'];
		$colorProducto=$datos['color'];
		$tallaProducto=$datos['talla'];		
		$codigo2=$datos['codigo2'];
		$montoUnitarioProdVenta=0;
		$montoUnitarioProdVentaFormato=0;
		$montoUnitarioDesc=0;
		$montoUnitarioDescFormato=0;

		// Porcentaje de descuento
		$porcentajeDescVenta=0;
		if($descuentoVenta>0){
			$porcentajeDescVenta=($descuentoVenta*100)/($montoVenta+$descuentoVenta);
			$montoUnitarioDesc=($porcentajeDescVenta*$montoUnitario)/100;		
			$montoUnitarioProdVenta=$montoUnitario-$montoUnitarioDesc;			
		}else{
			$montoUnitarioProdVenta=$montoUnitario;
		}
	
		$sqlResponsable="select CONCAT(SUBSTRING_INDEX(nombres,' ', 1),' ',SUBSTR(paterno, 1,1),'.') from funcionarios where codigo_funcionario='".$cod_funcionario."'";
		$respResponsable=mysqli_query($enlaceCon,$sqlResponsable);
		$datResponsable=mysqli_fetch_array($respResponsable);
		$nombreResponsable=$datResponsable[0];
		
		$totalVentaProd=$totalVentaProd+$montoUnitario;
		$totalVentaDesc=$totalVentaDesc+$montoUnitarioDesc;
		$totalVentaCobrado=$totalVentaCobrado+$montoUnitarioProdVenta;

		$totalMarcaProd=$totalMarcaProd+$montoUnitario;
		$totalMarcaDescProd=$totalMarcaDescProd+$montoUnitarioDesc;
		$totalMarcaVentaProd=$totalMarcaVentaProd+$montoUnitarioProdVenta;
					

		
		$montoVentaFormat=number_format($montoVenta,2,".",",");
		$montoUnitarioFormato=number_format($montoUnitario,2,".",",");
		$cantidadFormat=number_format($cantidadUnitaria,0,".",",");
		$montoUnitarioDescFormato=number_format($montoUnitarioDesc,2,".",",");
		$montoUnitarioProdVentaFormato=number_format($montoUnitarioProdVenta,2,".",",");

?>	
		<tr>
		<td><?php echo $fechaVenta;?></td>
		<td><?php echo $nombreCliente;?></td>
		<td><?php echo $razonSocial;?></td>
		<td><?php echo $datosDoc;?></td>
		<td><?php echo $nombreTipopago;?></td>
		<td align="right"><?php echo $montoVentaFormat;?></td>
		<td align="right"><?php echo number_format($descuentoVenta,2,".",",");?></td>	
		<td><?php echo $nombreResponsable;?></td>
		<td><?php echo $codigoBarras." ".$codigo2;?></td>
		<td><?php echo $descripcionMaterial;?></td>
		<td><?php echo $colorProducto."/".$tallaProducto;?></td>
		<td><?php echo $cantidadFormat;?></td>
		<td align="right"><?php echo $montoUnitarioFormato;?></td>		
		<td align="right"><?php echo $montoUnitarioDescFormato;?></td>		
		<td align="right"><?php echo $montoUnitarioProdVentaFormato;?></td>		
		</tr>		
<?php 
	}
	

	}
	$totalIngresoProveeedor=$totalIngresoProveeedor+$totalMarcaVentaProd;
	?>
	<tr>
		<td align="right" colspan="12"><strong>Ingreso por Ventas <?php echo $nombreProveedor;?></strong></td>
		<td align="right"><strong><?php echo $totalMarcaProd;?></strong></td>		
		<td align="right"><strong><?php echo $totalMarcaDescProd;?></strong></td>		
		<td align="right"><strong><?php echo $totalMarcaVentaProd;?></strong></td>		
</tr>
	<?php

 ////Recibos Externos del Proveedor
	$totalRecProveedor=0;
	$sqlRecMar = " select r.id_recibo,r.fecha_recibo,r.cod_ciudad,ciu.descripcion,
	r.nombre_recibo,r.desc_recibo,r.monto_recibo,
	r.created_by,r.modified_by,r.created_date,r.modified_date, r.cel_recibo,r.recibo_anulado,r.cod_tipopago, tp.nombre_tipopago,
	r.cod_tiporecibo, tr.nombre_tiporecibo, r.cod_proveedor, p.nombre_proveedor, r.cod_salida_almacen,
	r.cod_estadorecibo, er.nombre_estado
	from recibos r 
	inner join ciudades ciu on (r.cod_ciudad=ciu.cod_ciudad)
	inner join tipos_pago tp on(r.cod_tipopago=tp.cod_tipopago)
	inner join tipos_recibo tr on(r.cod_tiporecibo=tr.cod_tiporecibo)
	left  join proveedores p on (r.cod_proveedor=p.cod_proveedor)
	left  join estados_recibo er on (r.cod_estadorecibo=er.cod_estado)
	where r.cod_ciudad=".$global_agencia." and r.recibo_anulado=0 ";
	$sqlRecMar = $sqlRecMar." AND r.fecha_recibo BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'";
	$sqlRecMar = $sqlRecMar." and (r.cod_tiporecibo=2 or (r.cod_tiporecibo=1 and r.resta_ventas_proveedor=0)) and r.cod_proveedor=".$codProveedor;
	$sqlRecMar=$sqlRecMar." order by r.id_recibo asc,r.cod_ciudad desc ";
	// echo $sqlRecMar;
	$respRecMar = mysqli_query($enlaceCon,$sqlRecMar);
	$numRec=mysqli_num_rows($respRecMar);
	$totalRecibo=0;
	if( $numRec==0){
		$totalRecProveedor=0;
	}else{
		?>
		<tr>

	<th>Fecha</th>
	<th>Cliente</th>
	<th>Detalle</th>
	<th>Documento</th>
	<th>Forma de pago</th>
	<th>Monto Recibo</th>
	<th>Responsable</th>
	<th >&nbsp;</th>
	<th>Tipo de Recibo</th>
	<th colspan="5">&nbsp;</th>
	<th>Monto Final</th>
	</tr>
<?php		
		
	while ($datRecMar = mysqli_fetch_array($respRecMar)) {
	
		$id_recibo= $datRecMar['id_recibo'];
		$fecha_recibo= $datRecMar['fecha_recibo'];
		$vector_fecha_recibo=explode("-",$fecha_recibo);
		$fecha_recibo_mostrar=$vector_fecha_recibo[2]."/".$vector_fecha_recibo[1]."/".$vector_fecha_recibo[0];
		$cod_ciudad= $datRecMar['cod_ciudad'];
		$descripcion= $datRecMar['descripcion'];
		$nombre_recibo= $datRecMar['nombre_recibo'];
		$desc_recibo= $datRecMar['desc_recibo'];
		$monto_recibo= $datRecMar['monto_recibo'];
		$created_by= $datRecMar['created_by'];
		$modified_by= $datRecMar['modified_by'];
		$created_date= $datRecMar['created_date'];
		$modified_date= $datRecMar['modified_date'];
		$cel_recibo = $datRecMar['cel_recibo'];
		$recibo_anulado= $datRecMar['recibo_anulado'];
		$cod_tipopago= $datRecMar['cod_tipopago'];
		$nombre_tipopago= $datRecMar['nombre_tipopago'];
		$cod_tiporecibo= $datRecMar['cod_tiporecibo'];
		$nombre_tiporecibo= $datRecMar['nombre_tiporecibo'];
		$cod_proveedor= $datRecMar['cod_proveedor'];
		$nombre_proveedor= $datRecMar['nombre_proveedor'];
		$cod_salida_almacen= $datRecMar['cod_salida_almacen'];
		$cod_estadorecibo= $datRecMar['cod_estadorecibo'];
		$nombre_estadorecibo= $datRecMar['nombre_estado'];
		$created_date_mostrar="";
		$totalRecProveedor=$totalRecProveedor+$monto_recibo;

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
	<tr>
			
			<td><?=$fecha_recibo_mostrar;?></td>
			<td><?=$nombre_recibo;?></td>
			<td><?=$desc_recibo;?></td>
			<td>REC-<?=$id_recibo;?></td>
			<td><?=$nombre_tipopago;?></td>
			<td align='right'><?=$monto_recibo;?></td>			
			<td><?=$usuReg;?><br><?=$created_date_mostrar;?></td>
			<td><?=$usuMod;?><br><?=$modified_date_mostrar;?></td>	
			<td><?=$nombre_tiporecibo;?></td>
			<td colspan="5">&nbsp;</td>
			<td align='right'><?=$monto_recibo;?></td>
	</tr>
	<?php	
	}	
	}
	$totalIngresoProveeedor=$totalIngresoProveeedor+$totalRecProveedor;
	?>
		<tr>
			<td align='right' colspan="12"><STRONG>Ingreso por Recibos <?php echo $nombreProveedor;?> </strong></td>
			<td align='right'>&nbsp;</td>
			<td align='right'>&nbsp;</td>
			<td align='right'><STRONG><?=$totalRecProveedor;?></strong></td>
		</tr>
		<tr bgcolor='#d3ffce'  >
			<td align='right' colspan="12" ><STRONG>Total Ingresos <?php echo $nombreProveedor;?> </strong></td>
			<td align='right'>&nbsp;</td>
			<td align='right'>&nbsp;</td>
			<td align='right'><STRONG><?=$totalIngresoProveeedor;?></strong></td>
		</tr>	

<?php
$totalEgresoProveeedor=0;

 ////Recibos Egreso del Proveedor
	$totalRecEgresoProveedor=0;
	$sqlRecMar = " select r.id_recibo,r.fecha_recibo,r.cod_ciudad,ciu.descripcion,
	r.nombre_recibo,r.desc_recibo,r.monto_recibo,
	r.created_by,r.modified_by,r.created_date,r.modified_date, r.cel_recibo,r.recibo_anulado,r.cod_tipopago, tp.nombre_tipopago,
	r.cod_tiporecibo, tr.nombre_tiporecibo, r.cod_proveedor, p.nombre_proveedor, r.cod_salida_almacen,
	r.cod_estadorecibo, er.nombre_estado
	from recibos r 
	inner join ciudades ciu on (r.cod_ciudad=ciu.cod_ciudad)
	inner join tipos_pago tp on(r.cod_tipopago=tp.cod_tipopago)
	inner join tipos_recibo tr on(r.cod_tiporecibo=tr.cod_tiporecibo)
	left  join proveedores p on (r.cod_proveedor=p.cod_proveedor)
	left  join estados_recibo er on (r.cod_estadorecibo=er.cod_estado)
	where r.cod_ciudad=".$global_agencia." and r.recibo_anulado=0 ";
	$sqlRecMar = $sqlRecMar." AND r.fecha_recibo BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'";
	$sqlRecMar = $sqlRecMar." and (r.cod_tiporecibo=1 and r.resta_ventas_proveedor=1) and r.cod_proveedor=".$codProveedor;
	$sqlRecMar=$sqlRecMar." order by r.id_recibo asc,r.cod_ciudad desc ";
	//echo $sqlRecMar;
	$respRecMar = mysqli_query($enlaceCon,$sqlRecMar);
	$numRec=mysqli_num_rows($respRecMar);
	$totalReciboEgreso=0;
	if( $numRec==0){
		$totalRecEgresoProveedor=0;
	}else{
		?>
		<tr>

	<th>Fecha</th>
	<th>Cliente</th>
	<th>Detalle</th>
	<th>Documento</th>
	<th>Forma de pago</th>
	<th>Monto Recibo</th>
	<th>Responsable</th>
	<th >&nbsp;</th>
	<th>Tipo de Recibo</th>
	<th colspan="5">&nbsp;</th>
	<th>Monto Final</th>
	</tr>
<?php		
		
	while ($datRecMar = mysqli_fetch_array($respRecMar)) {
	
		$id_recibo= $datRecMar['id_recibo'];
		$fecha_recibo= $datRecMar['fecha_recibo'];
		$vector_fecha_recibo=explode("-",$fecha_recibo);
		$fecha_recibo_mostrar=$vector_fecha_recibo[2]."/".$vector_fecha_recibo[1]."/".$vector_fecha_recibo[0];
		$cod_ciudad= $datRecMar['cod_ciudad'];
		$descripcion= $datRecMar['descripcion'];
		$nombre_recibo= $datRecMar['nombre_recibo'];
		$desc_recibo= $datRecMar['desc_recibo'];
		$monto_recibo= $datRecMar['monto_recibo'];
		$created_by= $datRecMar['created_by'];
		$modified_by= $datRecMar['modified_by'];
		$created_date= $datRecMar['created_date'];
		$modified_date= $datRecMar['modified_date'];
		$cel_recibo = $datRecMar['cel_recibo'];
		$recibo_anulado= $datRecMar['recibo_anulado'];
		$cod_tipopago= $datRecMar['cod_tipopago'];
		$nombre_tipopago= $datRecMar['nombre_tipopago'];
		$cod_tiporecibo= $datRecMar['cod_tiporecibo'];
		$nombre_tiporecibo= $datRecMar['nombre_tiporecibo'];
		$cod_proveedor= $datRecMar['cod_proveedor'];
		$nombre_proveedor= $datRecMar['nombre_proveedor'];
		$cod_salida_almacen= $datRecMar['cod_salida_almacen'];
		$cod_estadorecibo= $datRecMar['cod_estadorecibo'];
		$nombre_estadorecibo= $datRecMar['nombre_estado'];
		$created_date_mostrar="";
		$totalRecEgresoProveedor=$totalRecEgresoProveedor+$monto_recibo;

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
	<tr>
			
			<td><?=$fecha_recibo_mostrar;?></td>
			<td><?=$nombre_recibo;?></td>
			<td><?=$desc_recibo;?></td>
			<td>REC-<?=$id_recibo;?></td>
			<td><?=$nombre_tipopago;?></td>
			<td align='right'><?=$monto_recibo;?></td>			
			<td><?=$usuReg;?><br><?=$created_date_mostrar;?></td>
			<td><?=$usuMod;?><br><?=$modified_date_mostrar;?></td>	
			<td><?=$nombre_tiporecibo;?></td>
			<td colspan="5">&nbsp;</td>
			<td align='right'><?=$monto_recibo;?></td>
	</tr>
	<?php	
	}	
	}
	$totalEgresoProveeedor=$totalEgresoProveeedor+$totalRecEgresoProveedor;
	?>
		<tr >
			<td align='right' colspan="12"><STRONG>Egreso por Recibos <?php echo $nombreProveedor;?> </strong></td>
			<td align='right'>&nbsp;</td>
			<td align='right'>&nbsp;</td>
			<td align='right'><STRONG><?=$totalRecEgresoProveedor;?></strong></td>
		</tr>

<?php

$totalGastoProveedor=0;		
		$consultaGasto="select g.cod_gasto,g.descripcion_gasto,g.cod_tipogasto,tg.nombre_tipogasto,g.fecha_gasto,g.monto,g.cod_ciudad,
g.created_by,g.modified_by,g.created_date,g.modified_date,g.gasto_anulado,g.cod_proveedor, p.nombre_proveedor,g.cod_grupogasto, gg.nombre_grupogasto,
g.cod_tipopago, tp.nombre_tipopago
from gastos g
inner join tipos_gasto tg on (g.cod_tipogasto=tg.cod_tipogasto)
inner join grupos_gasto gg on (g.cod_grupogasto=gg.cod_grupogasto)
inner join tipos_pago tp on (g.cod_tipopago=tp.cod_tipopago)
left  join proveedores p on (g.cod_proveedor=p.cod_proveedor)
where g.cod_ciudad=".$global_agencia." and g.cod_proveedor=".$codProveedor." and gasto_anulado=0 order by g.cod_gasto desc";
//and g.cod_tipogasto=2 

	$respGasto = mysqli_query($enlaceCon,$consultaGasto);
	$numGasto=mysqli_num_rows($respGasto);
	if($numGasto==0){
		?>
		<tr>
			<td align='right' colspan="12"><STRONG>Egreso por Gastos <?php echo $nombreProveedor;?> </strong></td>
			<td align='right'>&nbsp;</td>
			<td align='right'>&nbsp;</td>
			<td align='right'><STRONG><?=$totalGastoProveedor;?></strong></td>
		</tr>
		<?php
		
	}else{
?>
<tr>

<th>Fecha</th>
<th>Grupo Gasto</th>
<th>Detalle</th>
<th>Documento</th>
<th>Forma Pago</th>
<th>Monto</th>
<th>Responsable</th>
<th>&nbsp;</th>
<th>Tipo Gasto</th>
	<th colspan="5">&nbsp;</th>
	<th>Monto Final</th>

</tr>
<?php		
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
	////////////
	$totalGastoProveedor=$totalGastoProveedor+$monto;
	?>
	<tr >
	<td><?=$fecha_gasto_mostrar;?></td>
	<td><?=$nombre_grupogasto;?></td>
	<td><?=$descripcion_gasto;?></td>
	<td><?="GAS-".$cod_gasto;?></td>
	<td><?=$nombre_tipopago;?></td>
	<td><?=$monto;?></td>
	<td><?=$usuReg;?><br><?=$created_date_mostrar;?></td>
	<td><?=$usuMod;?><br><?=$modified_date_mostrar;?></td>	
	<td><?= $nombre_tipogasto;?></td>
	<td colspan="5">&nbsp;</td>
	<td align='right'><?=$monto;?></td>
	

	</tr>
<?php
}
$totalEgresoProveeedor=$totalEgresoProveeedor+$totalGastoProveedor;
?>
		<tr>
			<td align='right' colspan="12"><STRONG>TOTAL GASTOS <?php echo $nombreProveedor;?> </strong></td>
			<td align='right'>&nbsp;</td>
			<td align='right'>&nbsp;</td>
			<td align='right'><STRONG><?=$totalGastoProveedor;?></strong></td>
		</tr>
<?php
	}
?>	

		<tr bgcolor="F6D2CA">
			<td align='right' colspan="12"><STRONG>Total Egresos <?php echo $nombreProveedor;?> </strong></td>
			<td align='right'>&nbsp;</td>
			<td align='right'>&nbsp;</td>
			<td align='right'><STRONG><?=$totalEgresoProveeedor;?></strong></td>
		</tr>	
				<tr bgcolor="#F8FBB7">
			<td align='right' colspan="12"><STRONG>Monto a Pagar a <?php echo $nombreProveedor;?> </strong></td>
			<td align='right'>&nbsp;</td>
			<td align='right'>&nbsp;</td>
			<td align='right'><STRONG><?=($totalIngresoProveeedor-$totalEgresoProveeedor);?></strong></td>
		</tr>
	
<?php
$totalaPagar=$totalaPagar+($totalIngresoProveeedor-$totalEgresoProveeedor);

	}

		$totalVentaProdFormato=number_format($totalVentaProd,2,".",",");
		$totalVentaDescFormato=number_format($totalVentaDesc,2,".",",");
		$totalVentaCobradoFormato=number_format($totalVentaCobrado,2,".",",");
		$totalaPagarF=number_format($totalaPagar,2,".",",");
?>
<tr>
	
		<td colspan="12" align="right"><strong>TOTAL VENTAS </strong></td>
		<td align="right"><strong><?php echo $totalVentaProdFormato;?></strong></td>		
		<td align="right"><strong><?php echo $totalVentaDescFormato;?></strong></td>		
		<td align="right"><strong><?php echo $totalVentaCobradoFormato;?></strong></td>		
		</tr>	
<tr>
	
		<td colspan="12" align="right"><strong>Total a Pagar </strong></td>
		<td align="right">&nbsp;</td>		
		<td align="right">&nbsp;</td>		
		<td align="right"><strong><?php echo $totalaPagarF;?></strong></td>		
		</tr>			
</table>		
</body>
</html>


