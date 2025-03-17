<?php
	require("conexionmysqli2.inc");
	require('estilos.inc');
	require("funciones.php");
	$ciudad=$_COOKIE['global_agencia'];

	$sqlCiuAux="";

?>
<script language='Javascript'>
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}


function ajaxGuardarCantProductoInv(codigo){

	//alert("coidgo"+codigo+" sw="+sw);
	var frm = document.getElementById("form1");
	var cant=document.getElementById('cant'+codigo).value;
	
	var contenedor;
	contenedor = document.getElementById('divCantidadProducto'+codigo);
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxGuardarCantProductoInv.php?codigo='+codigo+'&cantidad='+cant,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
</script>
<h3 align='center'>Listado de Productos</h3><br>
<center><a href='stockProductos.php'  target="_blank" >Reporte Stock</a></center>
<form method='post' action='' name="form1" id="form1">
	
	<center><table class='texto'>
	<tr>
		<th>Nro</th>
		<th>Producto</th>
		<th>Grupo</th>
<th>Modelo</th>
		<th>Genero</th>		
		<th>Subgrupo</th>
		
		<th>Material</th>
		<th>Color</th>
		<th>Talla</th>
		<th>Cant</th>
		<th>&nbsp;</th>


		</tr>
	<?php 
	$sql="select m.codigo_material, m.descripcion_material, m.estado, 
		m.cod_grupo,gru.nombre as nombreGrupo,
		 m.cod_subgrupo,sgru.nombre as nombreSubgrupo,
		m.cod_marca, mar.nombre as nombreMarca,
		m.observaciones, imagen,
		 m.color, col.nombre as nombreColor,col.abreviatura as abrevColor,
		 m.talla, tal.nombre as nombreTalla,
		 m.codigo_barras, m.codigo2, m.fecha_creacion,
		m.cod_modelo,mo.nombre as nombreModelo, mo.abreviatura as abrevModelo, 
		m.cod_material, mat.nombre as nombreMaterial, mat.abreviatura abrevMaterial, 
		m.cod_genero, gen.nombre as nombreGenero, es.nombre_estado,
		m.cod_coleccion,cole.nombre as nombreColeccion,
		m.costo_si_no
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
		where m.cod_tipo=1 and m.estado=1
		order by nombreGrupo asc,abrevModelo asc,nombreSubgrupo asc,nombreColor asc, talla asc ";		
		
	$indice_tabla=1;
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp)){

		$codigo=$dat['codigo_material'];
		$nombreProd=$dat['descripcion_material'];
		$estado=$dat['estado'];
		$grupo=$dat['nombreGrupo'];
		$subgrupo=$dat['nombreSubgrupo'];
		$marca=$dat['nombreMarca'];
		//$tipoMaterial=$dat[4];

		$observaciones=$dat['observaciones'];
		$imagen=$dat['imagen'];
		$color=$dat['color'];
		$nombreColor=$dat['nombreColor'];
		$abrevColor=$dat['abrevColor'];
		$talla=$dat['talla'];
		$nombreTalla=$dat['nombreTalla'];
		$codigoBarras=$dat['codigo_barras'];
		$codigo2=$dat['codigo2'];
		$fechaCreacion=$dat['fecha_creacion'];
		$nombreModelo=$dat['nombreModelo'];
		$abrevModelo=$dat['abrevModelo'];
		$nombreMaterial=$dat['nombreMaterial'];
		$abrevMaterial=$dat['abrevMaterial'];
		$nombreGenero=$dat['nombreGenero'];
		$nombre_estado=$dat['nombre_estado'];
		$cod_coleccion=$dat['cod_coleccion'];
		$nombreColeccion=$dat['nombreColeccion'];	
		$costo_si_no=$dat['costo_si_no'];

		$cantidad=0;
		$fecha="";
		$sqlAux="select ii.cantidad, ii.fecha, ii.cod_ciudad, c.descripcion as descCiudad,
		ii.codigo_funcionario, concat (f.nombres,f.paterno,f.materno) as funcionario
		from inventarioinicial ii 
		left join ciudades c on(ii.cod_ciudad=c.cod_ciudad)
		left join funcionarios f on (ii.codigo_funcionario=f.codigo_funcionario)
		where ii.codigo_material=".$codigo."
		 and ii.cod_ciudad=".$ciudad;
		 //echo $sqlAux;
$respAux=mysqli_query($enlaceCon,$sqlAux);
while($datAux=mysqli_fetch_array($respAux)){
	$cantidad=$datAux['cantidad'];
	$fecha=$datAux['fecha'];
	$descCiudad=$datAux['descCiudad'];
	$funcionario=$datAux['funcionario'];
}

	?>

<tr>
		<td align="center"><?=$indice_tabla;?></td>
		<td><?=$nombreProd;?></td>
		<td><?=$grupo;?></td>
				<td><?=$nombreModelo?><br/><strong><?=$abrevModelo;?></strong></td>
		<td><?=$nombreGenero;?></td>
		<td><?=$subgrupo;?></td>

		<td><?=$nombreMaterial?><br/><strong><?=$abrevMaterial;?></strong></td>
		<td><strong><?=$nombreColor;?></strong><br/><?=$abrevColor;?></td>
		<td><strong><?=$nombreTalla;?></strong></td>
		<td><strong><input type='number' class='inputnumber'  id='cant<?=$codigo?>' name='cant<?=$codigo;?>' size='6'  value='<?=$cantidad;?>'></strong>
		</td>
		<td><center><div id="divCantidadProducto<?=$codigo;?>">
		<?php if($cantidad>0){ ?>
			<strong><?=$cantidad;?></strong><br/>(<?=$fecha;?>)<br/><?=$descCiudad?><br/><?=$funcionario?>
		<?php } ?>
<?php
	
			echo "<br/><a href='#'   onClick='ajaxGuardarCantProductoInv($codigo);'>Guardar</a>";
	
?>

</div></center></td>




		
		

 </tr>
	<?php	$indice_tabla++;
}
	?>
</table></center><br>
	


</form>