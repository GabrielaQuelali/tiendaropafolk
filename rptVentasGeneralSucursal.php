<?php

require('estilos_reportes_almacencentral.php');
	//require('estilos_almacenes_central_sincab.php');
require('function_formatofecha.php');
require('conexionmysqli.inc');
require('funcion_nombres.php');
require('funciones.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
//echo "fecha inicio=".$fecha_ini;
//echo "fecha fin=".$fecha_fin;
$fecha_ini=$fecha_ini."-01";
$fecha_fin=date("Y-m-t", strtotime($fecha_fin));
//echo "fecha inicio=".$fecha_ini;
//echo "fecha fin=".$fecha_fin;


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
//echo  "territorio".$rpt_territorio;
//$rptTipoPago=$_GET["rpt_tipoPago"];
//echo  "rptTipoPago".$rptTipoPago;

$cadenaTerritorio="TODOS";	
if($rptTerritorio=="-1"){
	$cadenaTerritorio="TODOS";
	$rptTerritorio=""; $swTerritorio=0;	 
	$sqlCiudades="select cod_ciudad, descripcion from ciudades  order by descripcion asc";
	$respCiudades=mysqli_query($enlaceCon,$sqlCiudades);
	while($datCiudades=mysqli_fetch_array($respCiudades))
	{	$codCiudad=$datCiudades[0];
		if($swTerritorio==0){
			$rptTerritorio=$datCiudades[0];
			$swTerritorio=1;
		}else{
			$rptTerritorio=$rptTerritorio.",";
			$rptTerritorio=$rptTerritorio.$datCiudades[0];
		}
	}
	//echo "rptTerritorio holaaaa".$rptTerritorio."<br>";
}else{
	$swCadenaTerritorio=0;	
	$sqlCiudades="select cod_ciudad, descripcion from ciudades where  cod_ciudad in(".$rptTerritorio.")	order by descripcion asc";
	$respCiudades=mysqli_query($enlaceCon,$sqlCiudades);
	while($datCiudades=mysqli_fetch_array($respCiudades)){	
		if($swCadenaTerritorio==0){
			$cadenaTerritorio=$datCiudades[1];
			$swCadenaTerritorio=1;
		}else{
			$cadenaTerritorio=$cadenaTerritorio.";";
			$cadenaTerritorio=$cadenaTerritorio.$datCiudades[1];
		}
		
	}

	
}

$fecha_reporte=date("d/m/Y");

?>
<html>
<body>
	
<table align='center'  >
<tr class='textotit' align='center' ><th  colspan='2'>VENTAS X SUCURSAL DOCUMENTO Y PRODUCTO</th></tr>
	<tr ><th>Territorio:</th><td><?=$cadenaTerritorio;?></td> </tr>
	<tr><th>De:</th> <td> <?=$fecha_ini." A:".$fecha_fin;?></td></tr>
	<!--tr><th>Tipos de Pago: </th><td><?=$cadenaTipoPagos;?></td></tr-->
	<tr><th>Fecha Reporte:</th> <td><?=$fecha_reporte;?></td></tr>	
	</table>
<br/>

<?php

setlocale(LC_ALL, 'es_ES');
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;

$fechaInicioTime = strtotime($fecha_ini);
$mesInicio=date("n",$fechaInicioTime);
$anioInicio=date("Y",$fechaInicioTime);
//echo "mes=".$mesInicio." anio=".$anioInicio;

$fechaFinTime = strtotime($fecha_fin);
$mesFin=date("n",$fechaFinTime);
$anioFin=date("Y",$fechaFinTime);
?>

<table align='center'  >
<tr class='textotit' align='center' ><th  colspan='2'> RESUMEN  DE VENTAS X SUCURSAL</th></tr>
</table>
<br/>
<center>
<table border="1">
<?php
?>
	<tr bgcolor="#CDFAFC">
		<td><strong>Nro</strong></td>
		<td><strong>Sucursal</strong></td>
<?php	
while($anioInicio <= $anioFin){

	if($anioFin>$anioInicio){
	
		while($mesInicio <= 12){
?>
			<td><strong><?=$mesInicio."/".$anioInicio;?></strong></td>
<?php			
			$mesInicio++;
		}
?>
			<td><small><strong>TOTAL</strong></small></td>
<?php		
		$mesInicio=1;			
	}

	if($anioFin==$anioInicio){
		while($mesInicio <= $mesFin){
?>
	<td align="center"><strong><?=$mesInicio."/".$anioInicio;?></strong></td>
<?php
			$mesInicio++;
		}
?>
<td align="center"><strong>Total</strong></td>
	</tr>
<?php		
	}

 	$anioInicio++;
}
?>

<?php

$sqlSucursales2="select c.cod_ciudad,c.descripcion, c.nombre_ciudad 
		from ciudades c
		where c.cod_ciudad in(".$rptTerritorio.")
		order by c.descripcion asc";
		$respSucursales2=mysqli_query($enlaceCon,$sqlSucursales2);
		$nro=0;
		while($datosSucursales2=mysqli_fetch_array($respSucursales2)){
			$codCiudad=$datosSucursales2['cod_ciudad'];
			$descripcionCiudad=$datosSucursales2['descripcion'];
			$nro++;		
			///// inicializar variable para cada sucursal
			$mesInicio=date("n",$fechaInicioTime);
			$anioInicio=date("Y",$fechaInicioTime);

			$mesFin=date("n",$fechaFinTime);
			$anioFin=date("Y",$fechaFinTime);
			///// FIN inicializar variable para cada sucursal
?>
		<tr>
			<td><?=$nro;?></td><td><?=$descripcionCiudad;?></td>
<?php
		$totalAnioSucursal=0;
		while($anioInicio <= $anioFin){
			$totalAnioSucursal=0;
			if($anioFin>$anioInicio){		
				while($mesInicio <= 12){	
				$dateInicio=$anioInicio."-".$mesInicio."-01";
				$dateFin=date("Y-m-t", strtotime($dateInicio));
					///////Aqui sacamos el total de ventas en la sucursal mes y anio
						$sql="select sum(s.monto_final)
						from salida_almacenes s 
						left join almacenes alm on (s.cod_almacen=alm.cod_almacen)
						left join tipos_docs td on (s.cod_tipo_doc=td.codigo)
						where s.salida_anulada=1
						and  alm.cod_ciudad =".$codCiudad."
						and s.cod_tiposalida=1001
						and s.fecha BETWEEN '".$dateInicio."' and '".$dateFin."'";
						//echo $sql;
						$resp=mysqli_query($enlaceCon,$sql);
						$montoVenta=0;
						while($dat=mysqli_fetch_array($resp)){
							$montoVenta=$dat[0];
						}
						if($montoVenta==null){
							$montoVenta=0;
						}
						$totalAnioSucursal=$totalAnioSucursal+$montoVenta;
?>

							<td align="right"><?=number_format($montoVenta,2,'.',',')?></td>
<?php  ///////FIN Aqui sacamos el total de ventas en la sucursal mes y anio
					$mesInicio++;
				}
	?>
			<td align="right"><?=number_format($totalAnioSucursal,2,'.',',')?></td>
<?php	
				$mesInicio=1;			
			}

			if($anioFin==$anioInicio){
				while($mesInicio <= $mesFin){
				///////Aqui sacamos el total de ventas en la sucursal mes y anio
					$dateInicio=$anioInicio."-".$mesInicio."-01";
				$dateFin=date("Y-m-t", strtotime($dateInicio));
						$sql="select sum(s.monto_final)
						from salida_almacenes s 
						left join almacenes alm on (s.cod_almacen=alm.cod_almacen)
						left join tipos_docs td on (s.cod_tipo_doc=td.codigo)
						where s.salida_anulada=1
						and  alm.cod_ciudad =".$codCiudad."
						and s.cod_tiposalida=1001
						and s.fecha BETWEEN '".$dateInicio."' and '".$dateFin."'";
						//echo $sql;
						$resp=mysqli_query($enlaceCon,$sql);
						$montoVenta=0;
						while($dat=mysqli_fetch_array($resp)){
							$montoVenta=$dat[0];
						}
						if($montoVenta==null){
							$montoVenta=0;
						}
						$totalAnioSucursal=$totalAnioSucursal+$montoVenta;
?>
						<td align="right"><?=number_format($montoVenta,2,'.',',');?></td>
<?php
					///////FIN Aqui sacamos el total de ventas en la sucursal mes y anio					
					$mesInicio++;
				}
?>
				<td align="right"><?=number_format($totalAnioSucursal,2,'.',',');?></td>
<?php
			}
 			$anioInicio++;
		}
?>
		</tr>
<?php
}
?>	
<tr bgcolor="#CDFAFC">
		<td colspan="2" align="right" ><strong>TOTALES:</strong></td>
<?php
///// inicializar variable para cada sucursal
			$mesInicio=date("n",$fechaInicioTime);
			$anioInicio=date("Y",$fechaInicioTime);

			$mesFin=date("n",$fechaFinTime);
			$anioFin=date("Y",$fechaFinTime);
			///// FIN inicializar variable para cada sucursal
while($anioInicio <= $anioFin){
	$totalAnio=0;
	if($anioFin>$anioInicio){

		while($mesInicio <= 12){
			$dateInicio=$anioInicio."-".$mesInicio."-01";
			$dateFin=date("Y-m-t", strtotime($dateInicio));
			$sql="select sum(s.monto_final)
						from salida_almacenes s 
						left join almacenes alm on (s.cod_almacen=alm.cod_almacen)
						left join tipos_docs td on (s.cod_tipo_doc=td.codigo)
						where s.salida_anulada=1
						and  alm.cod_ciudad in (".$rptTerritorio.")
						and s.cod_tiposalida=1001
						and s.fecha BETWEEN '".$dateInicio."' and '".$dateFin."'";
						//echo $sql;
						$resp=mysqli_query($enlaceCon,$sql);
						$montoVentaMes=0;
						while($dat=mysqli_fetch_array($resp)){
							$montoVentaMes=$dat[0];
						}
						if($montoVentaMes==null){
							$montoVentaMes=0;
						}
						$totalAnio=$totalAnio+$montoVentaMes;

		?>
		<td align="right"><?=number_format($montoVentaMes,2,'.',',');?></td>
		
	<?php
			$mesInicio++;
		}
			?>
			<td align="right"><?=number_format($totalAnio,2,'.',',');?></td>
<?php
		$mesInicio=1;			
	}

	if($anioFin==$anioInicio){
		while($mesInicio <= $mesFin){
$dateInicio=$anioInicio."-".$mesInicio."-01";
			$dateFin=date("Y-m-t", strtotime($dateInicio));
			$sql="select sum(s.monto_final)
						from salida_almacenes s 
						left join almacenes alm on (s.cod_almacen=alm.cod_almacen)
						left join tipos_docs td on (s.cod_tipo_doc=td.codigo)
						where s.salida_anulada=1
						and  alm.cod_ciudad in (".$rptTerritorio.")
						and s.cod_tiposalida=1001
						and s.fecha BETWEEN '".$dateInicio."' and '".$dateFin."'";
						//echo $sql;
						$resp=mysqli_query($enlaceCon,$sql);
						$montoVentaMes=0;
						while($dat=mysqli_fetch_array($resp)){
							$montoVentaMes=$dat[0];
						}
						if($montoVentaMes==null){
							$montoVentaMes=0;
						}
						$totalAnio=$totalAnio+$montoVentaMes;
		?>
		<td align="right"><strong><?=number_format($montoVentaMes,2,'.',',');?></strong></td>
	<?php
			$mesInicio++;
		}
		?>
		<td align="right"><strong><?=number_format($totalAnio,2,'.',',');?></strong></td>
		
	<?php		
	}

 	$anioInicio++;
?>
	
<?php
}
?>
</tr>
</table>
</center>


<br/>
<center>
		<table border="1">
<?php		

$sqlSucursales="select c.cod_ciudad,c.descripcion, c.nombre_ciudad 
		from ciudades c
		where c.cod_ciudad in(".$rptTerritorio.")
		order by c.descripcion asc";
		$respSucursales=mysqli_query($enlaceCon,$sqlSucursales);
		$totalVentaSucursal=0;
		$totalVentaTodasSucursal=0;
		while($datosSucursales=mysqli_fetch_array($respSucursales)){
			$codCiudad=$datosSucursales['cod_ciudad'];
			$descripcionCiudad=$datosSucursales['descripcion'];
			$nombreCiudad=$datosSucursales['nombre_ciudad'];
			$totalVentaSucursal=0;
?>
		<tr bgcolor="#E6F8E0"><th>Sucursal</th><th >Periodo</th>
			<th >Nro</th>
			<th >Fecha</th>
			<th >Nit/Razon Social</th>			
			<th >Productos</th>
			<th >Precio</th>
			<th >Cantidad</th>
			<th ><center>Descuento o <br/>Incremento</center></th>
			<th >Subtotal Producto</th>
			<th >Total</th>
		</tr>
<?php			
	/// Aqui se obtiene los meses/anio de los que se debe sacar la informacion
	$sqlMesAnio="select  mg.aniopivote, mg.mespivote from 
	(select month(s.fecha) as mespivote,YEAR(s.fecha) as aniopivote 
	from salida_almacenes s 
	left join almacenes alm on (s.cod_almacen=alm.cod_almacen)
	where s.salida_anulada=1 and  alm.cod_ciudad in(".$codCiudad.")
	and s.cod_tiposalida=1001 
	and s.fecha BETWEEN  '".$fecha_iniconsulta."' and '".$fecha_finconsulta."') mg
	group by  mg.aniopivote asc, mg.mespivote asc";
	//echo $sqlMesAnio;
	$respMesAnio=mysqli_query($enlaceCon,$sqlMesAnio);
	$totalSucursalMesAnio=0;
	while($datosMesAnio=mysqli_fetch_array($respMesAnio)){
		$aniopivote=$datosMesAnio['aniopivote'];
		$mespivote=$datosMesAnio['mespivote'];
		$totalSucursalMesAnio=0;
	///////Ventas por Mes/anio y sucursal
			$sqlVentas="select sal.mespivote,sal.aniopivote, sal.cod_salida_almacenes,sal.cod_almacen,sal.nombre_almacen,sal.cod_tipo_doc,
				sal.tipoDoc,sal.fecha,sal.hora_salida,sal.observaciones,sal.estado_salida,
				sal.nro_correlativo,sal.monto_total,sal.descuento,sal.monto_final,
				sal.razon_social,sal.nit,sal.cod_chofer,sal.monto_cancelado
				from (select MONTH(s.fecha) as mespivote,YEAR(s.fecha) as aniopivote,s.cod_salida_almacenes,s.cod_almacen,alm.nombre_almacen,s.cod_tipo_doc,
					td.nombre as tipoDoc,s.fecha,s.hora_salida,s.observaciones,s.estado_salida,
					s.nro_correlativo,s.monto_total,s.descuento,s.monto_final,
					s.razon_social,s.nit,s.cod_chofer,s.monto_cancelado
					from salida_almacenes s 
					left join almacenes alm on (s.cod_almacen=alm.cod_almacen)
					left join tipos_docs td on (s.cod_tipo_doc=td.codigo)
					where s.salida_anulada=1
					and  alm.cod_ciudad =".$codCiudad."
					and s.cod_tiposalida=1001
					and s.fecha BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."'
					order by s.fecha asc,s.nro_correlativo asc) sal
					where sal. mespivote=".$mespivote." and aniopivote=".$aniopivote."
					order by sal.nro_correlativo asc,sal.fecha asc";
					
			$respVentas=mysqli_query($enlaceCon,$sqlVentas);
			$swInicio=0;
			while($datosVentas=mysqli_fetch_array($respVentas)){
		
				$cod_salida_almacenes=$datosVentas['cod_salida_almacenes'];
				$cod_almacen=$datosVentas['cod_almacen'];
				$nombre_almacen=$datosVentas['nombre_almacen'];
				$cod_tipo_doc=$datosVentas['cod_tipo_doc'];
				$tipoDoc=$datosVentas['tipoDoc'];
				$fecha=$datosVentas['fecha'];
				$hora_salida=$datosVentas['hora_salida'];
				$observaciones=$datosVentas['observaciones'];
				$estado_salida=$datosVentas['estado_salida'];
				$nro_correlativo=$datosVentas['nro_correlativo'];
				$monto_total=$datosVentas['monto_total'];
				$descuento=$datosVentas['descuento'];
				$monto_final=$datosVentas['monto_final'];
				$razon_social=$datosVentas['razon_social'];
				$nit=$datosVentas['nit'];
				$cod_chofer=$datosVentas['cod_chofer'];
				$monto_cancelado=$datosVentas['monto_cancelado'];
				$totalSucursalMesAnio=$totalSucursalMesAnio+$monto_final;	
				$cod_salida_almacenesPivote=$cod_salida_almacenes;		

				////Detalle Ventas
				$sqlVentaDetalle="select sda.cod_material,ma.descripcion_material,sda.cantidad_unitaria,
				sda.lote,sda.precio_unitario,sda.descuento_unitario,sda.monto_unitario, sda.cod_ingreso_almacen, sda.orden_detalle
				from salida_detalle_almacenes sda
				left join material_apoyo ma on (sda.cod_material=ma.codigo_material)
 				where sda.cod_salida_almacen=".$cod_salida_almacenes." order by sda.orden_detalle asc";
 				$respVentaDetalle=mysqli_query($enlaceCon,$sqlVentaDetalle);
				while($datosVentaDetalle=mysqli_fetch_array($respVentaDetalle)){

					$cod_material=$datosVentaDetalle['cod_material'];
					$descripcion_material=$datosVentaDetalle['descripcion_material'];
					$cantidad_unitaria=$datosVentaDetalle['cantidad_unitaria'];
					$lote=$datosVentaDetalle['lote'];
					$precio_unitario=$datosVentaDetalle['precio_unitario'];
					$descuento_unitario=$datosVentaDetalle['descuento_unitario'];
					$monto_unitario=$datosVentaDetalle['monto_unitario'];
					$monto_unitario=$monto_unitario-($descuento_unitario);

					$cod_ingreso_almacen=$datosVentaDetalle['cod_ingreso_almacen'];
					$orden_detalle=$datosVentaDetalle['orden_detalle'];

					if($swInicio==0){
						$codSalidaAlmacenesPivote=$cod_salida_almacenes;
						$codSalidaAlmacenesPivoteAnterior=$codSalidaAlmacenesPivote;
						
					}else{
						$codSalidaAlmacenesPivote=$cod_salida_almacenes;
					}
?>
		<tr>
			<?php if($swInicio==0  or ($codSalidaAlmacenesPivote!=$codSalidaAlmacenesPivoteAnterior) ){  
				
			?>
				<th><?=$descripcionCiudad;?></th><th ><?=$mespivote."/".$aniopivote;?></th>
				<td><?=$nro_correlativo;?></td>			
				<td><?=$fecha;?></td>
				<td ><?=$nit."<br/>".$razon_social;?></td>

			<?php }else{ ?>
				<td colspan="5">&nbsp;</td>
			<?php } ?>
			<td><?=$descripcion_material;?></td>
			<td align="right"><?=$precio_unitario;?></td>	
			<td align="right"><?=$cantidad_unitaria;?></td>
			<td align="right"><?=-($descuento_unitario);?></td>
			<td align="right"><?=number_format($monto_unitario,2,'.',',');?></td>	
			<?php if($swInicio==0  or ($codSalidaAlmacenesPivote!=$codSalidaAlmacenesPivoteAnterior) ){  
				$swInicio=1;
			?>

				<td align="right"><?=number_format($monto_final,2,'.',',');?></td>	
			<?php }else{ ?>
				<td >&nbsp;</td>
			<?php } ?>
				
		</tr>
<?php
		$codSalidaAlmacenesPivoteAnterior=$codSalidaAlmacenesPivote;
				}
 				////FIN Detalle Ventas

					}
			/////// FIN Ventas por Mes/anio y sucursal

 	?>
		<tr bgcolor="#D8F6CE"><td colspan="10" align="right"><strong><?="Total ".$mespivote."/".$aniopivote.":";?></strong></td><td align="right"><?=number_format($totalSucursalMesAnio,2,'.',',');?></td></tr>
<?php
			$totalVentaSucursal=$totalVentaSucursal+$totalSucursalMesAnio;

		}
		///////fin ciudades
		 	?>
		<tr bgcolor="#CDFAFC" ><td colspan="10"  align="right"><strong><?="Total ".$descripcionCiudad.":";?></td>
			<td align="right"><?=number_format($totalVentaSucursal,2,'.',',');?></strong></td>
		</tr>
<?php
	$totalVentaTodasSucursal=$totalVentaTodasSucursal+$totalVentaSucursal;
	}
?>
		<tr bgcolor="#D8F6CE"><td colspan="10"  align="right"><strong>TOTAL TODAS LAS SUCURSALES:</strong></td>
			<td align="right"><strong><?=number_format($totalVentaTodasSucursal,2,'.',',');?></strong></td>
		</tr>


</table>
</center>
<br/>

<table align='center'  >
<tr class='textotit' align='center' ><th  colspan='2'> RESUMEN  DE PRODUCTOS VENDIDOS EN EL PERIODO SELECCIONADO</th></tr>
</table>
<br/>
<center>
<table border="1">
	<tr align="center"><td rowspan="2"><strong>Nro</strong></td><td rowspan="2"><strong>Producto</strong></td>
<?php
$sqlSucursales3="select c.cod_ciudad,c.descripcion, c.nombre_ciudad 
		from ciudades c		
		where c.cod_ciudad in(".$rptTerritorio.")
		order by c.descripcion asc";
		$respSucursales3=mysqli_query($enlaceCon,$sqlSucursales3);
		while($datosSucursales3=mysqli_fetch_array($respSucursales3)){
			$codCiudad=$datosSucursales3['cod_ciudad'];
			$descripcionCiudad=$datosSucursales3['descripcion'];
?>
	<td colspan="3" ><strong><?=$descripcionCiudad;?></strong></td>
<?php			
		}

?>
<th rowspan="2">Cantidad</th>
<th rowspan="2">Total Precio</th>
</tr>

	<tr align="center">
<?php
//Variable aux
$cantSucursales=0;
$sqlSucursales3="select c.cod_ciudad,c.descripcion, c.nombre_ciudad 
		from ciudades c		
		where c.cod_ciudad in(".$rptTerritorio.")
		order by c.descripcion asc";
		$respSucursales3=mysqli_query($enlaceCon,$sqlSucursales3);
		while($datosSucursales3=mysqli_fetch_array($respSucursales3)){
			$codCiudad=$datosSucursales3['cod_ciudad'];
			$descripcionCiudad=$datosSucursales3['descripcion'];
			$cantSucursales++;
?>
<td ><strong>Cant</strong></td><td ><strong>Precio Unit</strong></td><td ><strong>Precio Total</strong></td>
<?php			
		}

?>

</tr>

<?php
	$sqlProductos="select sda.cod_material, ma.descripcion_material,sum(sda.cantidad_unitaria) as cantVenta
	from salida_almacenes s 
	left join almacenes alm on (s.cod_almacen=alm.cod_almacen)	
	left join salida_detalle_almacenes sda on(s.cod_salida_almacenes=sda.cod_salida_almacen)
	left join material_apoyo ma on (sda.cod_material=ma.codigo_material)
	where s.cod_tiposalida=1001
	and s.salida_anulada=1 
	and  alm.cod_ciudad in (".$rptTerritorio.")	
	and s.fecha BETWEEN '".$fecha_ini."' and '".$fecha_fin."'
	group by sda.cod_material, ma.descripcion_material
	order by cantVenta desc";
	//echo $sqlProductos;
	$nroProd=0;
	$respProductos=mysqli_query($enlaceCon,$sqlProductos);
	$totalFinal=0;
	$totalProducto=0;
	while($datosProductos=mysqli_fetch_array($respProductos)){
	$nroProd++;
	$codProducto=$datosProductos['cod_material'];
	$producto=$datosProductos['descripcion_material'];
	$cantProdVenta=$datosProductos['cantVenta'];

?>
	<tr>
		<td><?=$nroProd;?></td>
		<td><?=$producto;?></td>
<?php
	
		$sqlSucursales4="select c.cod_ciudad from ciudades c		
		where c.cod_ciudad in(".$rptTerritorio.") order by c.descripcion asc";
		$respSucursales4=mysqli_query($enlaceCon,$sqlSucursales4);
		$totalProducto=0;
		while($datosSucursales4=mysqli_fetch_array($respSucursales4)){
			$totalPrecioProdxCant=0;
			$codCiudad=$datosSucursales4['cod_ciudad'];

			$sqlProdSuc="select sda.cod_material, sda.cantidad_unitaria cantProdSuc,
			sda.monto_unitario precioPromProdSuc,sda.descuento_unitario descProdSuc
			from salida_almacenes s 
			left join almacenes alm on (s.cod_almacen=alm.cod_almacen) 
			left join salida_detalle_almacenes sda on(s.cod_salida_almacenes=sda.cod_salida_almacen) 
			where s.cod_tiposalida=1001 
			and s.salida_anulada=1 
			and alm.cod_ciudad =".$codCiudad."
			and sda.cod_material=".$codProducto."
 			and s.fecha BETWEEN '".$fecha_ini."' and '".$fecha_fin."'
 			";
 			//echo $sqlProdSuc."<br/>";
 			$respProdSuc=mysqli_query($enlaceCon,$sqlProdSuc);
 			$cantProdSuc=0;
			$precioProdSuc=0;
			$descProdSuc=0;
			$totcantProdSuc=0;
			$totprecioProdSuc=0;

			$totalPrecioProdxCant=0;
			$cadenaCantPrecioAux="";
			while($datosProdSuc=mysqli_fetch_array($respProdSuc)){
				$cantProdSuc=$datosProdSuc['cantProdSuc'];
				$precioProdSuc=$datosProdSuc['precioPromProdSuc'];
				$descProdSuc=$datosProdSuc['descProdSuc'];
				if($descProdSuc==null){
					$descProdSuc=0;
				}
				$precioProdSuc=$precioProdSuc-($descProdSuc);
				$cadenaCantPrecioAux=$cadenaCantPrecioAux."".number_format($precioProdSuc,2,'.',',')."(".
				number_format($cantProdSuc,0,'.',',').")<br/>";
				$totcantProdSuc=$totcantProdSuc+$cantProdSuc;
				$totalPrecioProdxCant=$totalPrecioProdxCant+($cantProdSuc*$precioProdSuc);
		
			}
			/// Validacion Auxiliar
			$swDifPrecio=0;
			if( ($precioProdSuc*$totcantProdSuc) <>$totalPrecioProdxCant){
				$swDifPrecio=1;
			}
			//////		
			$totalProducto=$totalProducto+$totalPrecioProdxCant;
			?>
			
				<td align="right" bgcolor="#EFF5FB">
					<?php if ($totcantProdSuc>0){ echo "<strong>";}?>
					<?=number_format($totcantProdSuc,2,'.',',');?>
					<?php if ($totcantProdSuc>0){ echo "</strong>";}?>
					</td>
				
					<?php if($swDifPrecio==0){?>
					<td align="right">
					<?=number_format($precioProdSuc,2,'.',',');?>
					</td>
					<?php }else{ ?>
					<td align="right" bgcolor="#FBFCCD">
						 <?=$cadenaCantPrecioAux;?>
					</td>
					<?php } 
					?>

								
				<td align="right" bgcolor="#F8E0F1">
						<?php if ($totalPrecioProdxCant>0){ echo "<strong>";}?>
					<?=number_format($totalPrecioProdxCant,2,'.',',');?>
					<?php if ($totalPrecioProdxCant>0){ echo "</strong>";}?>	
				</td>
					
<?php				
		

		}
		$totalFinal=$totalFinal+$totalProducto;	
?>
		
		<td bgcolor="#EFF5FB" align="right"><?=number_format($cantProdVenta,2,'.',',');?></td>
		<td bgcolor="#EFF5FB" align="right"><?=number_format($totalProducto,2,'.',',');?></td>
	</tr>
<?php
	
	}
	///Aux donde sumo el numero de sucur
	$cantSucursales=($cantSucursales*3)+3;
?>
<tr bgcolor="#DBFCCD"><td colspan="<?=$cantSucursales;?>" align="right"><strong>TOTAL</strong></td>
	<td align="right"  ><strong><?=number_format($totalFinal,2,'.',',');?></strong></td>
</tr>
</table>
</center>
</body>
</html>

<?php 

include("imprimirInc.php");
?>