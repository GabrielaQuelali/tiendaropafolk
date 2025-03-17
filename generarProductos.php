<?php
require("conexionmysqli.php");
require('estilos.inc');
require('funciones.php');

$codProducto=$_GET['codigo'];
$nombreProducto=$_GET['nombre'];

echo "<form  action='actualizaPreciosMaterial.php' method='post' name='form1'>";

		$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		m.cod_grupo,gru.nombre as nombreGrupo, m.cod_subgrupo,sgru.nombre as nombreSubgrupo,
		m.cod_marca, mar.nombre as nombreMarca,
		m.observaciones, imagen,
		 m.color, col.nombre as nombreColor,
		 m.talla, tal.nombre as nombreTalla,
		 m.codigo_barras, m.codigo2, m.fecha_creacion,
		m.cod_modelo,mo.nombre as nombreModelo, 
		m.cod_material, mat.nombre as nombreMaterial, 
		m.cod_genero, gen.nombre as nombreGenero
		from material_apoyo m
		left join grupos gru on ( gru.codigo=m.cod_grupo)
		left join subgrupos sgru on ( sgru.codigo=m.cod_subgrupo)
		left join marcas mar on ( mar.codigo=m.cod_marca)
		left join modelos mo on ( mo.codigo=m.cod_modelo)
		left join materiales mat on ( mat.codigo=m.cod_material)
		left join generos gen on ( gen.codigo=m.cod_genero)
		left join colores col on ( col.codigo=m.color)
		left join tallas tal on ( tal.codigo=m.talla)
		where m.codigo_material='".$codProducto."'";

		$resp=mysqli_query($enlaceCon,$sql);
		while($dat=mysqli_fetch_array($resp)){

		$nombreProd=$dat['descripcion_material'];
		$estado=$dat['estado'];
		$grupo=$dat['nombreGrupo'];
		$subgrupo=$dat['nombreSubgrupo'];
		$marca=$dat['nombreMarca'];
	
		$observaciones=$dat['observaciones'];
		$imagen=$dat['imagen'];
		$color=$dat['color'];
		$nombreColor=$dat['nombreColor'];
		$talla=$dat['talla'];
		$nombreTalla=$dat['nombreTalla'];
		$codigoBarras=$dat['codigo_barras'];
		$codigo2=$dat['codigo2'];
		$fechaCreacion=$dat['fecha_creacion'];
		$nombreModelo=$dat['nombreModelo'];
		$nombreMaterial=$dat['nombreMaterial'];
		$nombreGenero=$dat['nombreGenero'];
	}


echo "<h1>GENERAR PRODUCTOS EN BASE AL SIGUIENTE PRODUCTO</h1><h2>$nombreProd</h2>";
echo "<center><table class='texto'><tr><td>Marca</td><td>$marca</td></tr>";
echo "<tr><td>Grupo</td><td>$grupo</td></tr>";
echo "<tr><td>Subgrupo</td><td>$subgrupo</td></tr>";
echo "<tr><td>Modelo</td><td>$nombreModelo</td></tr>";
echo "<tr><td>Genero</td><td>$nombreGenero</td></tr>";
echo "<tr><td>Material</td><td>$nombreMaterial</td></tr></table></center>";






echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php\"'>
</div>";
echo "</form>";

?>
