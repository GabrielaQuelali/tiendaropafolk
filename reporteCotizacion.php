<?php
	require("conexionmysqli.php");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");
	error_reporting(E_ALL);
 ini_set('display_errors', '1');

	$codCargo=$_COOKIE['global_cargo'];
	$cod_cotizacion=$_GET['cod_cotizacion'];

$sql="select c.nro_cotizacion, c.fecha_cotizacion,c.desc_cotizacion,c.cod_estado,
c.cod_almacen, c.created_by,c.created_date
 from cotizaciones c where c.cod_cotizacion=".$cod_cotizacion."";

$resp=mysqli_query($enlaceCon,$sql);
while($dat=mysqli_fetch_array($resp)){
	$nro_cotizacion=$dat['nro_cotizacion'];
	$fecha_cotizacion=$dat['fecha_cotizacion'];
	$fecha= explode('-',$fecha_cotizacion);	
    $fecha_cotizacion_mostrar = $fecha[2]."/".$fecha[1]."/".$fecha[0];

	$desc_cotizacion=$dat['desc_cotizacion'];
}
?>

<form method='post' action=''>

<table border='0'  align='center'>
	<tr><th colspan="2">COTIZACION</th></tr>
<tr><td>Nro. de Cotizacion</td><td><?=$nro_cotizacion;?></td></tr>
<tr><td>Fecha</td><td><?= $fecha_cotizacion_mostrar;?></td></tr>
<tr><td>De:</td><td><?=$desc_cotizacion;?></td></tr>
<tr><td colspan="2" align="center"><strong>DETALLE<strong></td></tr>
<tr>
	<td colspan="2">
<table border="1">

<?php
	$sqlDet=" select cd.cod_producto,ma.descripcion_material,cd.cantidad
					from cotizaciones_detalle cd
					left join material_apoyo ma on( cd.cod_producto=ma.codigo_material) 
					where cd.cod_cotizacion=".$cod_cotizacion." order by orden asc";
			$respDet=mysqli_query($enlaceCon,$sqlDet);
			$totalInsumoCotizacion=0;
			$totalProcesoCotizacion=0;
			$correlativoProducto=1;
			while($datDet=mysqli_fetch_array($respDet)){
				$cod_producto=$datDet['cod_producto'];
				$descripcion_material=$datDet['descripcion_material'];
				$cantidad=$datDet['cantidad'];
			?>
			<tr><td><strong><?=$correlativoProducto;?></strong></td>
				<td colspan="6" bgcolor="#EBF5FB"><strong><?=$descripcion_material;?></strong></td></tr>
			<tr>
				<td>&nbsp;</td>
				<td><strong>Insumos</strong></td>
				<td><strong>Cant </strong></td>
				<td><strong>Cant Insumo Unit</strong></td>
				<td><strong>Total Cant Insumo Unit</strong></td>
				<td><strong>Costo Insumo Unit</strong></td>
				
				<td><strong>Total Costo Insumo Unit</strong></td>
			</tr>
			<?php
				$sqlInsumoProd="select cdi.cod_insumo, ma.descripcion_material as insumo,cdi.cant_insumoProdUnid,cdi.costo_insumoProdUnid
 					from cotizacion_detalle_insumos cdi
 					left join material_apoyo ma on (cdi.cod_insumo=ma.codigo_material)
 					where cdi.cod_cotizacion=".$cod_cotizacion." and cdi.cod_producto=".$cod_producto;
 				$respInsumoProd=mysqli_query($enlaceCon,$sqlInsumoProd);
 				$totalCostoInsumoProducto=0;
				while($datInsumoProd=mysqli_fetch_array($respInsumoProd)){
					$cod_insumo=$datInsumoProd['cod_insumo'];
					$insumo=$datInsumoProd['insumo'];
					$cant_insumoProdUnid=$datInsumoProd['cant_insumoProdUnid'];
					$costo_insumoProdUnid=$datInsumoProd['costo_insumoProdUnid'];
					$totalCantinsumoProdUnid=$cant_insumoProdUnid*$cantidad;
					$totalCostoinsumoProdUnid=$costo_insumoProdUnid*($cant_insumoProdUnid*$cantidad);
					$totalCostoInsumoProducto=$totalCostoInsumoProducto+$totalCostoinsumoProdUnid;
				?>

					<tr><td>&nbsp;</td>
						<td><?=$insumo;?></td>
						<td align="right"><?=$cantidad;?></td>
					<td align="right"><?=$cant_insumoProdUnid;?></td>
					<td align="right"><?=$totalCantinsumoProdUnid;?></td>
					<td align="right"><?=$costo_insumoProdUnid*$cant_insumoProdUnid;?></td>
					
					<td align="right"><?=$totalCostoinsumoProdUnid;?></td>

			</tr>
				<?php

				}


			?>
			<tr bgcolor="#FEF9E7"><td  colspan="6" align="right" ><strong>Total Insumo:</strong></td>
					<td align="right"><?=$totalCostoInsumoProducto;?></td>
			</tr>
			<tr><td>&nbsp;</td><td colspan="6"><strong>MANO DE OBRA</strong></td></tr>
			<tr><td>&nbsp;</td><td><strong>Proceso</strong></td>
				<td align="right"><strong>Cant </strong></td>
				<td><strong>Proveedor</strong></td>
				<td align="right"><strong>Precio</strong></td>
				<td colspan="2" align="right"><strong>Total Precio Mano Obra</strong></td>
			</tr>
		<?php
		///listar mano de obra//

		$sqlManoObra="select cdm.cod_proceso_const, 		pc.nombre_proceso_const,cdm.cod_proveedor,p.nombre_proveedor,
			cdm.precio_proceso_const
 from cotizaciones_detalle_manoobra  cdm
 left join procesos_construccion pc on (cdm.cod_proceso_const=pc.cod_proceso_const)
 left join proveedores p on (p.cod_proveedor=cdm.cod_proveedor)
 where cdm.cod_cotizacion=".$cod_cotizacion." and cdm.cod_producto=".$cod_producto;
 
 $respManoObra=mysqli_query($enlaceCon,$sqlManoObra);
 				$totalCostoProcesoProducto=0;
				while($datManoObra=mysqli_fetch_array($respManoObra)){
					$nombre_proceso_const=$datManoObra['nombre_proceso_const'];
					$nombre_proveedor=$datManoObra['nombre_proveedor'];
					$precio_proceso_const=$datManoObra['precio_proceso_const'];
					$precioProductoManoObra=$precio_proceso_const*$cantidad;
					$totalCostoProcesoProducto=$totalCostoProcesoProducto+$precioProductoManoObra;

				?>
				<tr><td>&nbsp;</td><td><?=$nombre_proceso_const;?></td>
				<td align="right"><?=$cantidad;?></td>
				<td><?=$nombre_proveedor;?></td>
				<td align="right"><?=$precio_proceso_const;?></td>
				<td colspan="2" align="right"><?=$precioProductoManoObra;?></td>
			</tr>

				<?php

				}
?>
<tr bgcolor="#F5EEF8"><td>&nbsp;</td><td  colspan="5" align="right"><strong>Total Proceso :</strong></td>
					<td align="right"><?=$totalCostoProcesoProducto;?></td>
			</tr>

<tr bgcolor="#D6EAF8"><td>&nbsp;</td><td  colspan="5" align="right" ><strong>TOTAL COSTO INSUMO Y PROCESO DE PRODUCTO:</strong></td>
					<td align="right"><?=$totalCostoProcesoProducto+$totalCostoInsumoProducto;?></td>
			</tr>

<?php
		$totalInsumoCotizacion=$totalInsumoCotizacion+$totalCostoInsumoProducto;
		$totalProcesoCotizacion=$totalProcesoCotizacion+$totalCostoProcesoProducto;
		$correlativoProducto++;
			}

		?>
		<tr bgcolor="#FCF3CF"><td>&nbsp;</td><td  colspan="5" align="right"><strong>TOTAL INSUMOS COTIZACION:</strong></td>
					<td align="right"><?=$totalInsumoCotizacion;?></td>
			</tr>
		<tr bgcolor="#EBDEF0"><td>&nbsp;</td><td  colspan="5" align="right"><strong>TTOTAL PROCESOS COTIZACION:</strong></td>
					<td align="right"><?=$totalProcesoCotizacion;?></td>
			</tr>
				<tr bgcolor="#ABEBC6">
					<td>&nbsp;</td>
					<td  colspan="5" align="right"><strong>TOTAL COTIZACION:</strong></td>
					<td align="right"><strong><?=$totalInsumoCotizacion+$totalProcesoCotizacion;?></strong></td>
			</tr>

</table>
</td>
</tr>
<tr>
<td colspan="2" align="center"><strong>RESUMEN INSUMOS</strong></td>
<tr><td colspan="2">
<table border="1">
	<tr><td>&nbsp;</td><td><strong>Insumo</strong></td><td><strong>Cantidad Total</strong></td><td><strong>Costo Total</strong></td></tr>
	<?php
	$totalResumenInsumos=0;
	$sqlResInsumos=" select ri.cod_insumo,ma.descripcion_material, sum(ri.tot_cant_insumo) as totCantInsumo, sum(ri.tot_costo_insumo) as totCostoInsumo
from (select cdi.cod_producto, cdi.cod_insumo,
sum(cd.cantidad*cdi.cant_insumoProdUnid) as tot_cant_insumo,
sum(cd.cantidad*cdi.costo_insumoProdUnid*cdi.cant_insumoProdUnid) as tot_costo_insumo
from cotizacion_detalle_insumos cdi
left join cotizaciones_detalle cd on (cd.cod_producto=cdi.cod_producto and cd.cod_cotizacion=cdi.cod_cotizacion) 
where cdi.cod_cotizacion=".$cod_cotizacion."
group by cdi.cod_producto, cdi.cod_insumo) ri
left join material_apoyo ma on(ri.cod_insumo=ma.codigo_material)
group by ri.cod_insumo, ma.descripcion_material
order by totCostoInsumo desc";
//echo $sqlResInsumos;
 	//	echo $sqlResInsumos;
 		$respResInsumos=mysqli_query($enlaceCon,$sqlResInsumos);
 		$totalResumen=0;
 		$nro=1;
 		$totInsumo=0;
		$totCosto=0;
		while($datInsumoProd=mysqli_fetch_array($respResInsumos)){
			$descripcion_insumo=$datInsumoProd['descripcion_material'];
			$totInsumo=$datInsumoProd['totCantInsumo'];
			$totCosto=$datInsumoProd['totCostoInsumo'];
			$totalResumenInsumos=$totalResumenInsumos+$totCosto;

	?>
	<tr><td><?=$nro;?></td><td><?=$descripcion_insumo;?></td>
		<td align="right"><?=$totInsumo;?></td><td align="right"><?=$totCosto;?></td></tr>
	<?php
		$nro++;
	}
	?>
		<tr bgcolor="#FCF3CF"><td  colspan="3" align="right"><strong>Total Resumen Insumos Cotizacion:</strong></td>
					<td align="right"><?=$totalResumenInsumos;?></td>
			</tr>

	</table>
</td>
</tr>
</table>
	</center>
	
<center><a href='javascript:window.print();'><IMG border='no' src='imagenes/print.jpg' width='40'></a></center>
	