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
	ajax.open('GET', 'ajaxSubGrupoInsumo.php?cod_grupo='+cod_grupo+'',true);
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

$estado=$_GET['estado'];
	$tipo=$_GET['tipo'];
	$grupo=$_GET['grupo'];

echo "<form  action='guarda_insumo.php' method='post' name='form1'>";
echo "<input type='hidden' value='$tipo' name='tipo' id='tipo'>";

echo "<h2>Adicionar Insumo</h2>";


echo "<center><table class='texto'>";

echo "<tr><th>Grupo</th>";
$sql1="select g.codigo, g.nombre  from grupos g
where g.estado=1 and g.cod_tipo=2 order by 2;";
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
echo "<tr><th align='left'>Nombre Insumo</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='descripcion_material' id='descripcion_material' size='40' style='text-transform:uppercase;' >
	</td> <th align='left'>Codigo Insumo</th>
	<td align='left'><input type='text' class='texto' name='codigo2' id='codigo2' size='20' style='text-transform:uppercase;'> </td>
	</tr>";


echo "<tr><th align='left'>Descripcion</th>";
echo "<td align='left' colspan='3'>
	<input type='text' class='texto' name='observaciones' id='observaciones' size='120' style='text-transform:uppercase;'>
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
echo "</tr>";
?>	
	
<?php
echo "</table></center>";
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
					echo "<td><input type='number' class='inputnumber' step='0.01' id='precio".$cod_ciudad.$codGrupoPrecio."' name='precio".$cod_ciudad.$codGrupoPrecio."' size='6'  value='0'></td>";
					echo "<td><input class='inputnumber' type='number'  id='cant_ini".$cod_ciudad.$codGrupoPrecio."'  name='cant_ini".$cod_ciudad.$codGrupoPrecio."' value='".$cant_inicio."' ></td>";
					echo " <td><input class='inputnumber' type='number'  id='cant_fin".$cod_ciudad.$codGrupoPrecio."'  name='cant_fin".$cod_ciudad.$codGrupoPrecio."' value='".$cant_final."' ></td>";
					echo "</tr>";
			
			}
		}			
	
echo"</table></center>";
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_insumos.php?tipo=".$tipo."&estado=".$estado."&grupo=".$grupo."\"'>
</div>";
echo "</form>";
?>

