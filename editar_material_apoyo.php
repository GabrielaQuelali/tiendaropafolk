<script>
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

function ajaxSubGrupo(combo){
	var cod_grupo=combo.value;
	var contenedor;
	contenedor = document.getElementById('divSubGrupo');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxSubGrupo.php?cod_grupo='+cod_grupo+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
function ajaxMarca(dato){
	//var cod_grupo=combo.value;
	var lineaproveedor=dato.value;
	var contenedor;
	contenedor = document.getElementById('divMarca');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxMarca.php?lineaproveedor='+lineaproveedor+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
</script>

<head>

</head>
<?php
require("conexionmysqli.inc");
require('estilos.inc');
require('funciones.php');

$codProducto=$_GET['cod_material'];
$tipo=$_GET['tipo'];
$estado=$_GET['estado'];
$globalAgencia=$_COOKIE['global_agencia'];

$sqlEdit="select m.codigo_material, m.descripcion_material, m.estado, m.cod_linea_proveedor, m.cod_grupo, m.cod_tipomaterial, 
	m.observaciones, m.cod_unidad, m.codigo_barras, m.color, m.talla, m.cod_marca, m.cod_subgrupo,
	m.codigo2, m.cod_material,m.cod_modelo,  m.cod_genero,m.cod_coleccion
	from material_apoyo m where m.codigo_material='$codProducto'";
$respEdit=mysqli_query($enlaceCon,$sqlEdit);
while($datEdit=mysqli_fetch_array($respEdit)){
	$nombreProductoX=$datEdit[1];
	$estadoMaterialX=$datEdit[2];
	$codLineaX=$datEdit[3];
	/// obteniendo Codigo Proveedor
	$sqlAux="select cod_proveedor from proveedores_lineas where cod_linea_proveedor=$codLineaX";
	$respAux=mysqli_query($enlaceCon,$sqlAux);
	$datAux=mysqli_fetch_array($respAux);
	$proveedor=$datAux[0];
	/// Fin obteniendo Codigo Proveedor
	
	$codGrupoX=$datEdit[4];
	$codTipoX=$datEdit[5];
	$observacionesX=$datEdit[6];
	$codUnidadX=$datEdit[7];
	$codigoBarrasX=$datEdit[8];
	$colorX=$datEdit[9];
	$tallaX=$datEdit[10];
	$codMarcaX=$datEdit[11];
	$codSubGrupoX=$datEdit[12];
	$codigo2X=$datEdit[13];	
	$codMaterialX=$datEdit[14];
	$codModeloX=$datEdit[15];
	$codGeneroX=$datEdit[16];
	$codColeccionX=$datEdit['cod_coleccion'];
}


echo "<form action='guarda_editarproducto.php' method='post' name='form1'>";

echo "<h1>Editar Producto</h1>";


echo "<input type='hidden' name='codProducto' id='codProducto' value='$codProducto'>";
echo "<input type='hidden' name='tipo' id='tipo' value='$tipo'>";
echo "<input type='hidden' name='estado' id='estado' value='$estado'>";

echo "<center><table class='texto'>";

echo "<tr><th align='left'>Nombre de Producto</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='material' size='40' style='text-transform:uppercase;' value='$nombreProductoX' >
	</td>
	<th align='left'>Codigo Externo</th>
	<td align='left'>
	<input type='text' class='texto' name='codigo2' size='40' style='text-transform:uppercase;' value='$codigo2X' >
	</td>
	</tr>";

echo "<tr><th align='left'>Código de Barras</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='codigo_barras' size='40' value='$codigoBarrasX' >
	</td>";	
echo "<td></td><td></td>";
echo "</tr>";

echo "<tr><th>Tipo</th>";
$sql1="select f.cod_tipomaterial, f.nombre_tipomaterial from tipos_material f where f.cod_tipomaterial in (1,2) order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='cod_tipo' id='cod_tipo' required>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codTipo=$dat1[0];
				$nombreTipo=$dat1[1];
				echo "<option value='$codTipo'>$nombreTipo</option>";
			}
			echo "</select>
</td>";
//echo "</tr>";

echo "<th>Marca</th>";
$sqlMarca="select codigo, nombre from marcas where estado=1  order by nombre asc";
 $respMarca=mysqli_query($enlaceCon,$sqlMarca);


echo "<td><div id='divMarca'>
			<select name='cod_marca' id='cod_marca' required>
			<option value=''></option>";
			while($datMarca=mysqli_fetch_array($respMarca))
			{	$codigoX=$datMarca[0];
				$nombreX=$datMarca[1];
				if($codMarcaX==$codigoX){
					echo "<option value='$codigoX' selected>$nombreX</option>";					
				}else{
					echo "<option value='$codigoX'>$nombreX</option>";
				}
			}
			echo "</select>
</div></td>";
echo "</tr>";

echo "<tr><th>Grupo</th>";
$sql1="select f.codigo, f.nombre from grupos f where f.estado=1 and cod_tipo='".$tipo."' order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='cod_grupo' id='cod_grupo' required onChange='ajaxSubGrupo(this);'>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codGrupo=$dat1[0];
				$nombreGrupo=$dat1[1];
				if($codGrupoX==$codGrupo){
					echo "<option value='$codGrupo' selected>$nombreGrupo</option>";					
				}else{
					echo "<option value='$codGrupo'>$nombreGrupo</option>";					
				}
			}
			echo "</select>
</td>";
//echo "</tr>";

echo "<th>Sub-Grupo</th>";
echo "<td>
<div id='divSubGrupo'>";
$sql="select codigo, nombre from subgrupos where cod_grupo in ($codGrupoX) order by nombre asc";
$resp=mysqli_query($enlaceCon,$sql);

echo "<select name='cod_subgrupo' class='texto' id='cod_subgrupo' required>";
echo "<option value=''>---</option>";
while($dat=mysqli_fetch_array($resp)){
	$codigo=$dat[0];
	$nombre=$dat[1];
	if($codSubGrupoX==$codigo){
		echo "<option value='$codigo' selected>$nombre</option>";
	}else{	
		echo "<option value='$codigo'>$nombre</option>";		
	}
}
echo "</select>";

echo "</div>";
echo "</td>";
echo "</tr>";

echo "<tr><th>Modelo</th>";
$sqlMod="select codigo, nombre, abreviatura from modelos  where estado=1 order by 2;";
$respMod=mysqli_query($enlaceCon,$sqlMod);
echo "<td>
			<select name='cod_modelo' id='cod_modelo' required >
			<option value=''></option>";
			while($datMod=mysqli_fetch_array($respMod))
			{	$codModelo=$datMod[0];
				$nombreModelo=$datMod[1];
				$abreviaturaModelo=$datMod[2];
				if($codModeloX==$codModelo){
					echo "<option value='$codModelo' selected>$nombreModelo - $abreviaturaModelo</option>";					
				}else{
					echo "<option value='$codModelo'>$nombreModelo - $abreviaturaModelo</option>";					
				}
			}
			echo "</select>
</td>";

echo "<th>Genero</th>";

$sqlGen="select codigo, nombre, abreviatura from generos  where estado=1 order by 2;";
$respGen=mysqli_query($enlaceCon,$sqlGen);
echo "<td>
			<select name='cod_genero' id='cod_genero' required >
			<option value=''></option>";
			while($datGen=mysqli_fetch_array($respGen))
			{	$codGenero=$datGen[0];
				$nombreGenero=$datGen[1];
				$abreviaturaGenero=$datGen[2];
				if($codGeneroX==$codGenero){
					echo "<option value='$codGenero' selected>$nombreGenero - $abreviaturaGenero</option>";					
				}else{
					echo "<option value='$codGenero'>$nombreGenero-$abreviaturaGenero</option>";					
				}
			}
			echo "</select>
</td>";

echo "</tr>";
echo "<tr>";
echo "<th>Material</th>";

$sqlMat="select codigo, nombre, abreviatura from materiales  where estado=1 order by 2;";
$respMat=mysqli_query($enlaceCon,$sqlMat);
echo "<td>
			<select name='cod_material' id='cod_material' required >
			<option value=''></option>";
			while($datMat=mysqli_fetch_array($respMat))
			{	$codMaterial=$datMat[0];
				$nombreMaterial=$datMat[1];
				$abreviaturaMaterial=$datMat[2];
				if($codMaterialX==$codMaterial){
					echo "<option value='$codMaterial' selected>$nombreMaterial - $abreviaturaMaterial</option>";					
				}else{
					echo "<option value='$codMaterial'>$nombreMaterial - $abreviaturaMaterial</option>";					
				}
			}
			echo "</select>
</td>";
echo "<th>Coleccion</th>";

$sqlColeccion="select codigo, nombre, abreviatura from colecciones  where estado=1 order by 2;";
$respColeccion=mysqli_query($enlaceCon,$sqlColeccion);
echo "<td>
			<select name='cod_coleccion' id='cod_coleccion' required >
			<option value=''></option>";
			while($datColeccion=mysqli_fetch_array($respColeccion))
			{	$codColeccion=$datColeccion[0];
				$nombreColeccion=$datColeccion[1];
				$abreviaturaColeccion=$datColeccion[2];
				if($codColeccionX==$codColeccion){
					echo "<option value='$codColeccion' selected>$nombreColeccion - $abreviaturaColeccion</option>";					
				}else{
					echo "<option value='$codColeccion'>$nombreColeccion - $abreviaturaColeccion</option>";					
				}
			}
			echo "</select>
</td>";
echo "</tr>";

echo "<tr><th align='left'>Talla</th>";
$sqlTalla="select codigo, nombre, abreviatura from tallas  where estado=1 order by 2;";
$respTalla=mysqli_query($enlaceCon,$sqlTalla);
echo "<td>
			<select name='cod_talla' id='cod_talla' required >
			<option value=''></option>";
			while($datTalla=mysqli_fetch_array($respTalla))
			{	$codTalla=$datTalla[0];
				$nombreTalla=$datTalla[1];
				$abreviaturaTalla=$datTalla[2];
				if($tallaX==$codTalla){
					echo "<option value='$codTalla' selected>$nombreTalla - $abreviaturaTalla</option>";					
				}else{
					echo "<option value='$codTalla'>$nombreTalla - $abreviaturaTalla</option>";					
				}
			}
			echo "</select>
</td>";


echo "<th align='left'>Color</th>";
$sqlColor="select codigo, nombre, abreviatura from colores  where estado=1 order by 2;";
$respColor=mysqli_query($enlaceCon,$sqlColor);
echo "<td>
			<select name='cod_color' id='cod_color' required >
			<option value=''></option>";
			while($datColor=mysqli_fetch_array($respColor))
			{	$codColor=$datColor[0];
				$nombreColor=$datColor[1];
				$abreviaturaColor=$datColor[2];
				if($colorX==$codColor){
					echo "<option value='$codColor' selected>$nombreColor - $abreviaturaColor</option>";					
				}else{
					echo "<option value='$codColor'>$nombreColor - $abreviaturaColor</option>";					
				}
			}
			echo "</select>
</td>";
echo "</tr>";


echo "<tr><th align='left'>Descripcion</th>";
echo "<td align='left' colspan='3'>
	<input type='text' class='texto' name='observaciones' id='observaciones' value='$observacionesX' size='100' style='text-transform:uppercase;'>
	</td>";

echo "<tr><th>Unidad de Manejo</th>";
$sql1="select u.codigo, u.nombre, u.abreviatura from unidades_medida u order by 1;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='cod_unidad' id='cod_unidad' required>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codUnidad=$dat1[0];
				$nombreUnidad=$dat1[1];
				$abreviatura=$dat1[2];
				if($codUnidadX==$codUnidad){
					echo "<option value='$codUnidad' selected>$nombreUnidad $abreviatura</option>";					
				}else{
					echo "<option value='$codUnidad'>$nombreUnidad $abreviatura</option>";					
				}
			}
			echo "</select>
</td>";
//echo "</tr>";


echo "<th>Imagen</th>";
echo "<td> <input name='archivo' id='archivo' type='file' class='boton2' disabled/> </td>";
echo "</tr>";




echo "</table></center>";
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php?estado=".$estado."&tipo=".$tipo."\"'>
</div>";
echo "</form>";
?>
