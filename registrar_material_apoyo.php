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
<?php
require("conexionmysqli2.inc");
require('estilos.inc');
$tipo=$_GET['tipo'];
$estado=$_GET['estado'];
//echo "tipo="+$tipo;
//echo "estado="+$estado;

echo "<form enctype='multipart/form-data' action='guarda_material_apoyo.php' method='post' name='form1'>";
	echo "<input type='hidden' name='tipo' id='tipo' value='$tipo'>";

echo "<h2>Adicionar Producto</h2>";


echo "<center><table class='texto'>";
echo "<tr><th align='left'>Genera Nombre Automatico</th>
<td align='left'><input type='checkbox' name='nombreGenerado' id='nombreGenerado' 

checked='true'></td>";
echo "</tr>";

echo "<tr><th align='left'>Nombre Producto</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='material' id='material' size='40' style='text-transform:uppercase;' >
	</td> <th align='left'>Codigo Proveedor</th>
	<td align='left'><input type='text' class='texto' name='codigo2' id='codigo2' size='20' style='text-transform:uppercase;'> </td>
	</tr>";

echo "<tr><th align='left'>Código de Barras</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='codigo_barras' size='40' >
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


echo "<th>Marca</th>";

echo "<td>";



$sqlMarca="select codigo, nombre from marcas where estado=1  order by nombre asc";
 $respMarca=mysqli_query($enlaceCon,$sqlMarca);
 if(mysqli_num_rows($respMarca)<=0){
	 $sqlMarca="select codigo, nombre from marcas where estado=1 order by nombre asc";
	$respMarca=mysqli_query($enlaceCon,$sqlMarca);
}


echo "<select name='cod_marca' id='cod_marca' class='texto' required>";
echo "<option value=''>---</option>";
while($datMarca=mysqli_fetch_array($respMarca)){
	$codigoX=$datMarca[0];
	$nombreX=$datMarca[1];
	echo "<option value='$codigoX'>$nombreX $codigoX</option>";
}
echo "</select></td>";

echo "</tr>";

echo "<tr><th>Grupo</th>";
$sql1="select f.codigo, f.nombre from grupos f 
where f.estado=1 and cod_tipo=".$tipo." order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='cod_grupo' id='cod_grupo' required onChange='ajaxSubGrupo(this);'>
			<option value=''></option>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codGrupo=$dat1[0];
				$nombreGrupo=$dat1[1];
				echo "<option value='$codGrupo'>$nombreGrupo</option>";
			}
			echo "</select>
</td>";


echo "<th>Sub-Grupo</th>";
echo "<td>
<div id='divSubGrupo'></div>
</td>";
echo "</tr>";

echo "<tr><th>Modelo</th>";
echo "<td>";
$sqlModelo="select codigo, nombre,abreviatura from modelos where estado=1  order by nombre asc";
 $respModelo=mysqli_query($enlaceCon,$sqlModelo);


echo "<select name='cod_modelo' id='cod_modelo' class='texto' required>";
echo "<option value=''>---</option>";
while($datModelo=mysqli_fetch_array($respModelo)){
	$codModelo=$datModelo[0];
	$nombreModelo=$datModelo[1];
	$abrevModelo=$datModelo[2];
	echo "<option value='$codModelo'>$nombreModelo - $abrevModelo </option>";
}
echo "</select></td>";

echo "<th>Genero</th>";

echo "<td>";



$sqlGenero="select codigo, nombre,abreviatura from generos where estado=1  order by nombre asc";
 $respGenero=mysqli_query($enlaceCon,$sqlGenero);

echo "<select name='cod_genero' id='cod_genero' class='texto' required>";
echo "<option value=''>---</option>";
while($datGenero=mysqli_fetch_array($respGenero)){
	$codGenero=$datGenero[0];
	$nombreGenero=$datGenero[1];
	$abrevGenero=$datGenero[2];
	echo "<option value='$codGenero'>$nombreGenero - $abrevGenero</option>";
}
echo "</select></td>";

echo "</tr>";
echo "<tr>";
echo "<th align='left'>Material</th>";
echo "<td>";
$sqlMaterial="select codigo, nombre,abreviatura from materiales where estado=1  order by nombre asc";
 $respMaterial=mysqli_query($enlaceCon,$sqlMaterial);

echo "<select name='cod_material' id='cod_material' class='texto' required>";
echo "<option value=''>---</option>";
while($datMaterial=mysqli_fetch_array($respMaterial)){
	$codMaterial=$datMaterial[0];
	$nombreMaterial=$datMaterial[1];
	$abrevMaterial=$datMaterial[2];
	echo "<option value='$codMaterial'>$nombreMaterial - $abrevMaterial </option>";
}
echo "</select></td>";
echo "<th align='left'>Coleccion</th>";
echo"<td>";
$sqlColeccion="select codigo, nombre,abreviatura from colecciones where estado=1  order by nombre asc";
 $respColeccion=mysqli_query($enlaceCon,$sqlColeccion);
echo "<select name='cod_coleccion' id='cod_coleccion' class='texto' required>";
echo "<option value=''>---</option>";
while($datColeccion=mysqli_fetch_array($respColeccion)){
	$codColeccion=$datColeccion[0];
	$nombreColeccion=$datColeccion[1];
	$abrevColeccion=$datColeccion[2];
	echo "<option value='$codColeccion'>$nombreColeccion - $abrevColeccion </option>";
}
echo "</select>";
echo "</td>";
echo "</tr>";


echo "<th align='left'>Talla</th>";
echo "<td>";
$sqlTalla="select codigo, nombre,abreviatura from tallas where estado=1  order by nombre asc";
 $respTalla=mysqli_query($enlaceCon,$sqlTalla);

echo "<select name='cod_talla' id='cod_talla' class='texto' required>";
echo "<option value=''>---</option>";
while($datTalla=mysqli_fetch_array($respTalla)){
	$codTalla=$datTalla[0];
	$nombreTalla=$datTalla[1];
	$abrevTalla=$datTalla[2];
	echo "<option value='$codTalla'>$nombreTalla - $abrevTalla </option>";
}
echo "</select></td>";

echo "<th align='left'>Color</th>";
echo "<td>";
$sqlColores="select codigo, nombre,abreviatura from colores where estado=1  order by nombre asc";
 $respColores=mysqli_query($enlaceCon,$sqlColores);

echo "<select name='cod_color' id='cod_color' class='texto' required>";
echo "<option value=''>---</option>";
while($datTalla=mysqli_fetch_array($respColores)){
	$codColor=$datTalla[0];
	$nombreColor=$datTalla[1];
	$abrevColor=$datTalla[2];
	echo "<option value='$codColor'>$nombreColor - $abrevColor </option>";
}
echo "</select></td>";
echo "</tr>";

echo "<tr><th align='left'>Descripcion</th>";
echo "<td align='left' colspan='3'>
	<input type='text' class='texto' name='observaciones' id='observaciones' size='100' style='text-transform:uppercase;'>
	</td>";
echo "<tr>";

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
				echo "<option value='$codUnidad'>$nombreUnidad $abreviatura</option>";
			}
			echo "</select>
</td>";



echo "<th>Imagen</th>";
echo "<td> <input name='archivo' id='archivo' type='file' class='boton2'/> </td>";
echo "</tr>";



?>	

	
<?php
echo "</table></center>";
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_material.php?estado=".$estado."&tipo=".$tipo."\"'>
</div>";
echo "</form>";
?>

