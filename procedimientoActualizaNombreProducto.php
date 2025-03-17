<?php
	require("conexionmysqli2.inc");
	require('estilos.inc');
	require("funciones.php");
?>
<script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="functionsGeneral.js">


</script>


    

	<h3 align='center'>Listado de Productos</h3>

	<form method='post' action=''>

	
<?php
		$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		m.cod_grupo,gru.nombre as nombreGrupo, m.cod_subgrupo,sgru.nombre as nombreSubgrupo,
		m.cod_marca, mar.nombre as nombreMarca,
		m.observaciones, imagen,
		 m.color, col.nombre as nombreColor,
		 m.talla, tal.nombre as nombreTalla,
		 m.codigo_barras, m.codigo2, m.fecha_creacion,
		m.cod_modelo,mo.nombre as nombreModelo, 
		m.cod_material, mat.nombre as nombreMaterial, 
		m.cod_genero, gen.nombre as nombreGenero, es.nombre_estado,
		m.cod_coleccion,cole.nombre as nombreColeccion
		from material_apoyo m
		left join estados es on (m.estado=es.cod_estado)
		left join grupos gru on ( gru.codigo=m.cod_grupo)
		left join subgrupos sgru on ( sgru.codigo=m.cod_subgrupo)
		left join marcas mar on ( mar.codigo=m.cod_marca)
		left join modelos mo on ( mo.codigo=m.cod_modelo)
		left join materiales mat on ( mat.codigo=m.cod_material)
		left join generos gen on ( gen.codigo=m.cod_genero)
		left join colores col on ( col.codigo=m.color)
		left join tallas tal on ( tal.codigo=m.talla)
		left join colecciones cole  on ( cole.codigo=m.cod_coleccion)
		where m.cod_tipo=1  and m.estado=1";
	
	$sql=$sql." order by m.codigo_material asc";		
?>
<table border="1">
	<tr><td>codigo</td>
		<td>producto</td>
		<td>producto2</td>
		<td>Modelo</td>
	<td>Genero</td>
	<td>Material</td>
	<td>Color</td>
	<td>Talla</td>
	<td>Coleccion</td>
	<td>Grupo</td>
	<td>Suggrupo</td>
	</tr>
<?php
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp)){
		$codigo_material=$dat['codigo_material'];
		$descripcion_material=$dat['descripcion_material'];
?>
<tr><td><?=$codigo_material;?></td><td><?=$descripcion_material;?></td>
<?php
		actualizaNombreProducto2($enlaceCon,$codigo_material);
			$sql2="select  m.descripcion_material, m.estado, 
		m.cod_grupo,gru.nombre as nombreGrupo, m.cod_subgrupo,sgru.nombre as nombreSubgrupo,
		m.cod_marca, mar.nombre as nombreMarca,
		m.observaciones, imagen,
		 m.color, col.nombre as nombreColor,
		 m.talla, tal.nombre as nombreTalla,
		 m.codigo_barras, m.codigo2, m.fecha_creacion,
		m.cod_modelo,mo.nombre as nombreModelo, 
		m.cod_material, mat.nombre as nombreMaterial, 
		m.cod_genero, gen.nombre as nombreGenero, es.nombre_estado,
		m.cod_coleccion,cole.nombre as nombreColeccion
		from material_apoyo m
		left join estados es on (m.estado=es.cod_estado)
		left join grupos gru on ( gru.codigo=m.cod_grupo)
		left join subgrupos sgru on ( sgru.codigo=m.cod_subgrupo)
		left join marcas mar on ( mar.codigo=m.cod_marca)
		left join modelos mo on ( mo.codigo=m.cod_modelo)
		left join materiales mat on ( mat.codigo=m.cod_material)
		left join generos gen on ( gen.codigo=m.cod_genero)
		left join colores col on ( col.codigo=m.color)
		left join tallas tal on ( tal.codigo=m.talla)
		left join colecciones cole  on ( cole.codigo=m.cod_coleccion)
		where m.codigo_material=".$codigo_material;
		$resp2=mysqli_query($enlaceCon,$sql2);
	while($dat2=mysqli_fetch_array($resp2)){
		$descripcion_material2=$dat2['descripcion_material'];
		$nombreGrupo=$dat2['nombreGrupo'];
		$nombreSubgrupo=$dat2['nombreSubgrupo'];
		$nombreMarca=$dat2['nombreMarca'];
		$nombreColor=$dat2['nombreColor'];
		$nombreTalla=$dat2['nombreTalla'];
		$nombreModelo=$dat2['nombreModelo'];
		$nombreMaterial=$dat2['nombreMaterial'];
		$nombreGenero=$dat2['nombreGenero'];
		$nombreColeccion=$dat2['nombreColeccion'];
	?><td><?=$descripcion_material2;?></td>
	<td><?=$nombreModelo;?></td>
	<td><?=$nombreGenero;?></td>
	<td><?=$nombreMaterial;?></td>
	<td><?=$nombreColor;?></td>
	<td><?=$nombreTalla;?></td>
	<td><?=$nombreColeccion;?></td>
	<td><?=$nombreGrupo;?></td>
	<td><?=$nombreSubgrupo;?></td>
</tr>
	<?php
	}
	

}
	?>
</table>



</form>