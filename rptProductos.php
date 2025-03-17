<?php

	require("conexionmysqli.php");
	require('estilos.inc');
	require("funciones.php");
	require("funcion_nombres.php");
	
	$globalAgencia=$_GET['rpt_territorio'];
	//echo "globalAgencia=".$globalAgencia;
	$rpt_grupo=$_GET['rpt_grupo'];
	$nombreAgencia=nombreTerritorio($enlaceCon,$globalAgencia);
	//echo "rpt_grupo=".$rpt_grupo;
	echo "<h1>Reporte de Productos</h1>";
	echo "<h2>Agencia: $nombreAgencia</h2>";

	$sql="select ida.cod_material,ma.descripcion_material,g.nombre
from ingreso_almacenes ia 
left join ingreso_detalle_almacenes ida  on (ia.cod_ingreso_almacen=ida.cod_ingreso_almacen)
left join material_apoyo ma on (ida.cod_material=ma.codigo_material)
left join subgrupos sg on (ma.cod_subgrupo=sg.codigo)
left join grupos g on (sg.cod_grupo=g.codigo)
where  ia.cod_tipo=1 
and ia.ingreso_anulado=1 
and ia.cod_almacen in (select cod_almacen from almacenes where cod_ciudad =".$globalAgencia.")
and g.codigo in (".$rpt_grupo.") 
group by ida.cod_material,ma.descripcion_material,g.nombre
order by  ma.descripcion_material asc ";



	
	$resp=mysqli_query($enlaceCon,$sql);
			
	echo "<center><table class='texto'>";
	echo "<tr><th>Indice</th><th>Grupo</th><th>Nombre Producto</th><th>Unidad</th>
		<th>PrecioVenta[Bs]</th></tr>";
		
	
	$indice_tabla=1;
	while($dat=mysqli_fetch_array($resp))
	{
		$codProducto=$dat['cod_material'];
		$producto=$dat['descripcion_material'];
		$grupo=$dat['nombre'];

		$precioVenta=precioVenta($enlaceCon,$codProducto,$globalAgencia);
		$sqlUM="select um.abreviatura,ma.imagen from material_apoyo ma 
				left join unidades_medida um on (ma.cod_unidad=um.codigo)
				where ma.codigo_material=".$codProducto;
		$respUM=mysqli_query($enlaceCon,$sqlUM);
		while($datUM=mysqli_fetch_array($respUM)){
			$unidadMedida=$datUM['abreviatura'];
			$imagen=$datUM['imagen'];

			if($imagen==""){
				$imagen="default.png";
			}
		
			if($imagen=='default.png'){
				$tamanioImagen=50;
			}else{
				$tamanioImagen=50;
			}
		}		

?>
		<tr>
		<td align='center'><?=$indice_tabla;?></td>
		<td><?=$grupo;?></td>
		<td><?=$producto;?></td>
		<td><?=$unidadMedida;?></td>
		<td><?=$precioVenta;?></td>
		<td><img src="imagenesprod/<?=$imagen;?>" width="<?=$tamanioImagen;?>"></td>
		

		</tr>
<?php
		$indice_tabla++;
	}
	
?>
</table></center>
<br/>

<br/>