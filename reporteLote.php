<?php
	require("conexionmysqli.php");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");
	error_reporting(E_ALL);
 ini_set('display_errors', '1');
 ?>
<html>
<body>
<?php
	$codCargo=$_COOKIE['global_cargo'];

	$codLote=$_GET['codLote'];
	$codPago=$_GET['codPago'];

	$sqlPago="select nro_pago from pagos_proveedor_cab where cod_pago=".$codPago;
	$respPago=mysqli_query($enlaceCon,$sqlPago);
	while($datPago=mysqli_fetch_array($respPago)){
		$nroPago=$datPago[0];
	}
	
	$sql="select lp.cod_lote,lp.nro_lote,lp.nombre_lote,lp.obs_lote,
lp.codigo_material,ma.descripcion_material,lp.cant_lote,lp.cod_estado_lote,
el.nombre_estado, lp.created_by, concat(fc.paterno,fc.materno,fc.nombres) usuReg ,
 lp.created_date,lp.fecha_inicio_lote,lp.fecha_fin_lote,lp.fecha_lote, 
 lp.obligacionxpagar_si_no,lp.cod_estado_pago, ep.nombre_estado_pago
from lotes_produccion lp
left join estados_lote el on(lp.cod_estado_lote=el.cod_estado)
left join material_apoyo ma on(lp.codigo_material=ma.codigo_material)
left join estados_pago ep on(lp.cod_estado_pago=ep.cod_estado_pago)
left join funcionarios fc on (lp.created_by=fc.codigo_funcionario)
where lp.cod_lote=".$codLote;
	
	//echo $sql;
	
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp)){
		
		$nro_lote=$dat['nro_lote'];
		$nombre_lote=$dat['nombre_lote'];
		$obs_lote=$dat['obs_lote'];
		$codigo_material=$dat['codigo_material'];
		$descripcion_material=$dat['descripcion_material'];
		$cant_lote=$dat['cant_lote'];
		$cod_estado_lote=$dat['cod_estado_lote'];
		$nombre_estado=$dat['nombre_estado'];
		$created_by=$dat['created_by'];
		$created_date=$dat['created_date'];
		$fecha_registro= explode(' ',$created_date);
		$fecha_reg=$fecha_registro[0];
    $fecha_reg_mostrar = "$fecha_reg[8]$fecha_reg[9]-$fecha_reg[5]$fecha_reg[6]-$fecha_reg[0]$fecha_reg[1]$fecha_reg[2]$fecha_reg[3] $fecha_registro[1]";	

		$usuReg=$dat['usuReg'];
 		

 		$fecha_inicio_lote= explode(' ',$dat['fecha_inicio_lote']);
		$fechaIniLote=$fecha_inicio_lote[0];
    $fecha_iniLote_mostrar = "$fechaIniLote[8]$fechaIniLote[9]/$fechaIniLote[5]$fechaIniLote[6]/$fechaIniLote[0]$fechaIniLote[1]$fechaIniLote[2]$fechaIniLote[3] $fecha_inicio_lote[1]";	
 		
 		$fecha_fin_lote= explode(' ',$dat['fecha_fin_lote']);
		$fechaFinLote=$fecha_fin_lote[0];
    	$fecha_finLote_mostrar = "$fechaFinLote[8]$fechaFinLote[9]/$fechaFinLote[5]$fechaFinLote[6]/$fechaFinLote[0]$fechaFinLote[1]$fechaFinLote[2]$fechaFinLote[3] $fecha_fin_lote[1]";	

 		$fecha_lote=$dat['fecha_lote'];
 		$fecha_lote= explode('-',$fecha_lote);
		$fecha_lote_mostrar=$fecha_lote[2]."/".$fecha_lote[1]."/".$fecha_lote[0];

 		$obligacionxpagar_si_no=$dat['obligacionxpagar_si_no'];
 		$cod_estado_pago=$dat['cod_estado_pago'];
 		$nombre_estado_pago=$dat['nombre_estado_pago'];

	}
?>

		<br>
	
	<table border="1" cellpadding="1" cellspacing="0" align="center">
	<tr align="center" class="textotit">
		<td colspan="5"><strong>Nro. de Lote:</strong> <?=$nro_lote?> <strong>Fecha:</strong><?=$fecha_lote_mostrar;?></td>

		
	</tr>

	<tr>

		
		<th>Nombre</th>
		<th>Producto</th>
			<th>Cantidad</th>	
		<th>Fecha Inicio</th>
		<th>Fecha Fin</th>
		
	</tr>
	<tr>

		<td align="center"><?=$nombre_lote;?></td>
		<td align="center"><?=$descripcion_material;?></td>
			<td align="center"><?=$cant_lote;?></td>	
		<td align="center"><?=$fecha_iniLote_mostrar;?></td>
		<td align="center"><?=$fecha_finLote_mostrar;?></td>
		
		
	</tr>
	<tr>	

		<th colspan="3">Observaciones</th>
		<th>Estado Lote</th>
		<th>Fecha Registro</th>	
		
	</tr>
	<tr>

		<td  colspan="3" ><?=$obs_lote;?></td>
		<td ><?=$nombre_estado;?></td>	
		<td ><?=$fecha_reg_mostrar;?></td>
					
	</tr>
	<tr>		
		
		<th>Estado Pago</th>
		<th colspan="4">Obligacion x Pagar</th>
	</tr>
	<tr>
		
		<td ><?=$nombre_estado_pago;?></td>
		<td  colspan="4">
			<?php
				if($obligacionxpagar_si_no==1){
					echo "SI";
				}else{
					echo "NO";
				}
			?>
				
			</td>
	</tr>
	</table>
	<table border="1" cellpadding="1" cellspacing="0" align="center">
		<tr  >
		<td align="center" colspan="12"><strong>DETALLE</strong></td>
	</tr>
	<tr >
		<th>&nbsp;</th>
		<th>Proceso</th>
		<th>Fecha</th>
		<th>Proveedor</th>
		<th>Cantidad</th>
		<th>Precio</th>
		<th>Total</th>
		<th>a Cuenta</th>
		<th>Saldo</th>		
		<th>Obligacion X Pagar</th>
		<th>Estado</th>
		<th>Monto Pagado en <br>Pago Nro <?=$nroPago;?></th>	
	</tr>
	
	
<?php	
	$sql_detalle="select lpc.cod_proceso_const, pc.nombre_proceso_const,
	lpc.cod_proveedor, p.nombre_proveedor,lpc.cantidad,lpc.precio, lpc.obligacionxpagar_si_no,
	lpc.obligacionxpagar_fecha,	lpc.cod_estado_pago, ep.nombre_estado_pago
 	from lote_procesoconst lpc
  	left join proveedores p on (lpc.cod_proveedor=p.cod_proveedor)
	left join procesos_construccion pc on (lpc.cod_proceso_const=pc.cod_proceso_const)
	left join estados_pago ep on(lpc.cod_estado_pago=ep.cod_estado_pago)
 where lpc.cod_lote=".$codLote."  order by pc.nombre_proceso_const asc	";
	$resp_detalle=mysqli_query($enlaceCon,$sql_detalle);

	$indice=1;
	$totalCantProd=0;
	while($dat_detalle=mysqli_fetch_array($resp_detalle)){	

		$cod_proceso_const=$dat_detalle['cod_proceso_const'];
		$nombre_proceso_const=$dat_detalle['nombre_proceso_const'];
		$cod_proveedor=$dat_detalle['cod_proveedor']; 
		$nombre_proveedor=$dat_detalle['nombre_proveedor'];
		$cantidadProcCons=$dat_detalle['cantidad'];
		$precioProcCons=$dat_detalle['precio'];
		$obligacionxpagar_si_noProcCons=$dat_detalle['obligacionxpagar_si_no'];
		$fechaProcCons=$dat_detalle['obligacionxpagar_fecha'];
		$fechaProcCons_mostrar="";
		if($fechaProcCons!=null){
 			$fechaProcCons= explode('-',$fechaProcCons);
			$fechaProcCons_mostrar=$fechaProcCons[2]."/".$fechaProcCons[1]."/".$fechaProcCons[0];
		}

		$cod_estado_pagoProcCons=$dat_detalle['cod_estado_pago'];
		$nombre_estado_pagoProcCons=$dat_detalle['nombre_estado_pago'];

		$sqlPagoProvDetGral="select sum(ppd.monto_pago) from pagos_proveedor_detalle ppd
			left join pagos_proveedor_cab ppc on (ppd.cod_pago=ppc.cod_pago)
			 where  ppd.codigo_doc=".$codLote."
			 and ppd.cod_proceso_const=".$cod_proceso_const."
			 and ppc.cod_proveedor=".$cod_proveedor;
		//echo $sqlPagoProvDetGral."<br>";
		$monto_pagoGral=0;
		$respPagoProvDetGral=mysqli_query($enlaceCon,$sqlPagoProvDetGral);		
		while($datPagoProvDetGral=mysqli_fetch_array($respPagoProvDetGral)){
			$monto_pagoGral=$datPagoProvDetGral[0];
			if($monto_pagoGral==null){
				$monto_pagoGral=0;
			}

		}
		$sqlPagoProvDet="select ppd.monto_pago from pagos_proveedor_detalle ppd
			left join pagos_proveedor_cab ppc on (ppd.cod_pago=ppc.cod_pago)
			where  ppd.cod_pago=".$codPago." and  ppd.codigo_doc=".$codLote."
			and ppd.cod_proceso_const=".$cod_proceso_const."
			and ppc.cod_proveedor=".$cod_proveedor;
		//echo $sqlPagoProvDet."<br>";
		$monto_pago=0;
		$respPagoProvDet=mysqli_query($enlaceCon,$sqlPagoProvDet);		
		while($datPagoProvDet=mysqli_fetch_array($respPagoProvDet)){
			$monto_pago=$datPagoProvDet['monto_pago'];
				if($monto_pago==null){
				$monto_pago=0;
			}


		}

		//#d3ffce
	?>	
	<?php if($monto_pago>0){ ?>
		<tr bgcolor="#d3ffce" >
	<?php }else{ ?>
		<tr>
	<?php } ?>
		<td><?=$indice;?></td>	
		<td><?=$nombre_proceso_const;?></td>
		<td><?=$fechaProcCons_mostrar;?></td>	
		<td><?=$nombre_proveedor;?></td>	
		<td><?=$cantidadProcCons;?></td>	
		<td><?=$precioProcCons;?></td>	
		<td><?=($cantidadProcCons*$precioProcCons);?></td>	
		<td><?=$monto_pagoGral;?></td>	
		<td><?=(($cantidadProcCons*$precioProcCons)-$monto_pagoGral)?></td>	
				
		<td>
		<?php
			if($obligacionxpagar_si_noProcCons==1){
				echo "SI";
			}else{
				echo "NO";
			}
		?>			
		</td>
		<td><?=$nombre_estado_pagoProcCons;?></td>
		<td><?=$monto_pago;?></td>	
		</tr>
<?php
		$indice++;
	}
?>
</table>
	
</center>
</tbody>
</html>
