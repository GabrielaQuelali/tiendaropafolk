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


</script>
<?php
require("conexionmysqli2.inc");
require('estilos.inc');

$tipo=$_GET['tipo'];
$estado=$_GET['estado'];

echo "<form enctype='multipart/form-data' action='guarda_material_apoyo_masivo.php' method='post' name='form1'>";
echo "<input type='hidden' name='tipo' id='tipo' value='$tipo'>";

echo "<center><table class='texto'>";
echo "<tr><th colspan='4'><center>AÑADIR PRODUCTOS EN DIFERENTES TALLAS</center></th></tr>";

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
where f.estado=1 and f.cod_tipo=".$tipo." order by 2;";
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
 if(mysqli_num_rows($respModelo)<=0){
	 $sqlModelo="select codigo, nombre,abreviatura from modelos where estado=1 order by nombre asc";
	$respModelo=mysqli_query($enlaceCon,$sqlModelo);
}


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
 if(mysqli_num_rows($respGenero)<=0){
	 $sqlGenero="select codigo, nombre,abreviatura from generos where estado=1 order by nombre asc";
	$respGenero=mysqli_query($enlaceCon,$sqlGenero);
}


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
 if(mysqli_num_rows($respTalla)<=0){
	 $sqlMaterial="select codigo, nombre,abreviatura from materiales where estado=1 order by nombre asc";
	$respMaterial=mysqli_query($enlaceCon,$sqlMaterial);
}
echo "<select name='cod_material' id='cod_material' class='texto' required>";
echo "<option value=''>---</option>";
while($datMaterial=mysqli_fetch_array($respMaterial)){
	$codMaterial=$datMaterial[0];
	$nombreMaterial=$datMaterial[1];
	$abrevMaterial=$datMaterial[2];
	echo "<option value='$codMaterial'>$nombreMaterial - $abrevMaterial </option>";
}
echo "</select></td> ";

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

echo "<tr>";
echo "<th>Unidad</th>";
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
echo "<td > <input name='archivo' id='archivo' type='file' class='boton2'> </td>";

echo "</tr>";
$sqlColores="select codigo, nombre,abreviatura from colores where estado=1 order by nombre asc";
	$respColores=mysqli_query($enlaceCon,$sqlColores);

	echo "<tr><th colspan='4'><center>SELECCIONAR COLORES</center></th></tr>";
	while($datColores=mysqli_fetch_array($respColores))
	{
		$codigo=$datColores[0];
		$nombre=$datColores[1];
		$abreviatura=$datColores[2];
		echo "<tr>
		<td><input type='checkbox' name='color$codigo' id='color$codigo' value='$codigo' ></td>
		
		<td colspan='3'>$nombre ( $abreviatura )</td>
		
		</tr>";
	}

	$sql="select codigo, nombre, abreviatura, estado from tallas where estado=1 order by 1";
	$resp=mysqli_query($enlaceCon,$sql);

	echo "<tr><th colspan='4'><center>SELECCIONAR TALLAS</center></th></tr>";
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$nombre=$dat[1];
		$abreviatura=$dat[2];
		echo "<tr>
		<td><input type='checkbox' name='talla$codigo' id='talla$codigo' value='$codigo' ></td>
		
		<td colspan='3'>$nombre ( $abreviatura )</td>
		
		</tr>";
	}
	echo "</table></center><br>";
$ciudad=$_COOKIE['global_agencia'];
	echo "<center><table class='texto'>";
	echo "<tr>
	<th>Nro</th><th>Sucursal</th><th>&nbsp;</th><th>Precio</th><th>Cantidad Inicio</th><th>Cantidad Final</th>		
		</tr>";

		$sqlCiu="select cod_ciudad,descripcion as desc_ciudad,nombre_ciudad 
		from ciudades where cod_ciudad='".$ciudad."' order by cod_ciudad asc";
		//echo $sqlCiu;

		$respCiu=mysqli_query($enlaceCon,$sqlCiu);
		
		while($datCiu=mysqli_fetch_array($respCiu)){

			$cod_ciudad=$datCiu['cod_ciudad'];
			$desc_ciudad=$datCiu['desc_ciudad'];
			$nombre_ciudad=$datCiu['nombre_ciudad'];		

		$sqlGrupoPrecio="select codigo,nombre,cant_inicio,cant_final from grupos_precio where  estado=1 order by codigo asc";
		//echo $sqlGrupoPrecio;
		$respGrupoPrecio=mysqli_query($enlaceCon,$sqlGrupoPrecio);
		
			while($datGrupoPrecio=mysqli_fetch_array($respGrupoPrecio)){

				$codGrupoPrecio=$datGrupoPrecio['codigo'];
				$nomGrupoPrecio=$datGrupoPrecio['nombre'];			

					$cant_inicio=$datGrupoPrecio['cant_inicio'];
					$cant_final=$datGrupoPrecio['cant_final'];
					echo "<tr>";
					echo "<td>&nbsp;</td>";
					echo "<td>".$desc_ciudad."</td>";
					echo "<td>".$nomGrupoPrecio."</td>";
					echo "<td><input type='number' class='inputnumber'  id='precio".$cod_ciudad.$codGrupoPrecio."' name='precio".$cod_ciudad.$codGrupoPrecio."' size='6'  value='0'></td>";
					echo "<td><input class='inputnumber' type='number'  id='cant_ini".$cod_ciudad.$codGrupoPrecio."'  name='cant_ini".$cod_ciudad.$codGrupoPrecio."' value='".$cant_inicio."' ></td>";
					echo " <td><input class='inputnumber' type='number'  id='cant_fin".$cod_ciudad.$codGrupoPrecio."'  name='cant_fin".$cod_ciudad.$codGrupoPrecio."' value='".$cant_final."' ></td>";
					echo "</tr>";
			
			}
		}			
	
echo"</table></center>";


echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar'  onClick='location.href=\"navegador_material.php?estado=".$estado."&tipo=".$tipo."\"'>
</div>";
echo "</form>";
?>

