<?php
	require("conexionmysqli2.inc");
	require('estilos.inc');
	require("funciones.php");

?>
<h3 align='center'>Resumen de Productos para Costo</h3>
<form method='post' action=''>
	
	<center><table class='texto'>
	<tr>
		<th>Nro</th>
		<th>Grupo Costeo</th>
		<th>Producto</th>
		<th>Costeo SI/NO</th>

		</tr>
	<?php 
	$sql="SELECT pc.nombre_producto_costo,ma.descripcion_material,ma.costo_si_no,
IF(ma.costo_si_no IS NULL OR ma.costo_si_no = 0, 'NO', 'SI') costo_si_no_desc
FROM material_apoyo ma
left join producto_costo_detalle pcd on (ma.codigo_material=pcd.cod_producto)
left join producto_costo pc on (pcd.cod_producto_costo=pc.cod_producto_costo)
where ma.cod_tipo=1 and ma.estado=1
order by pc.nombre_producto_costo asc,ma.descripcion_material  asc ";		
		
	$indice_tabla=1;
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp)){
		$nombre_producto_costo=$dat['nombre_producto_costo'];
		$descripcion_material=$dat['descripcion_material'];
		$costo_si_no=$dat['costo_si_no'];
		$costo_si_no_desc=$dat['costo_si_no_desc'];
	?>

<tr>
		<td align="center"><?=$indice_tabla;?></td>
		<td><?=$nombre_producto_costo;?></td>
		<td><?=$descripcion_material;?></td>
		<td><?=$costo_si_no_desc;?></td>

		
		

 </tr>
	<?php	$indice_tabla++;
}
	?>
</table></center><br>
	


</form>