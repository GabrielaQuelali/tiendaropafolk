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

<head>

</head>
<?php
require("conexionmysqli.inc");
require('estilos.inc');
require('funciones.php');

$codigo_material=$_GET['codigo'];
$globalAgencia=$_COOKIE['global_agencia'];

$estado=$_GET['estado'];
	$tipo=$_GET['tipo'];
	$grupo=$_GET['grupo'];

$sqlEdit="select codigo2,descripcion_material,observaciones,cod_unidad,cod_subgrupo,
 creado_por,fecha_creacion,estado from material_apoyo where codigo_material='$codigo_material'";
 //echo $sqlEdit;

$respEdit=mysqli_query($enlaceCon,$sqlEdit);
while($datEdit=mysqli_fetch_array($respEdit)){

	$codigo2X=$datEdit['codigo2'];
	$descripcion_materialX=$datEdit['descripcion_material'];
	$observacionesX=$datEdit['observaciones'];
	$cod_unidadX=$datEdit['cod_unidad'];
	$costo_insumoX=0;
	$precio_insumoX=0;
	$cod_subgrupoX=$datEdit['cod_subgrupo'];
	//////////////
	$sqlAux="select cod_grupo from subgrupos where codigo='".$cod_subgrupoX."'";
	//echo $sqlAux;
	
	$respAux=mysqli_query($enlaceCon,$sqlAux);
	while($datAux=mysqli_fetch_array($respAux))
			{	$cod_grupoX=$datAux[0];				
			}
	/////////////
	$created_byX=$datEdit['creado_por'];
	$created_dateX=$datEdit['fecha_creacion'];
	$cod_estadoX=$datEdit['estado'];
}


echo "<form action='guarda_editarinsumo.php' method='post' name='form1'>";
echo "<input type='hidden' value='$tipo' name='tipo' id='tipo'>";
echo "<h1>Editar Insumo</h1>";


echo "<input type='hidden' name='codigo_material' id='codigo_material' value='$codigo_material'>";

echo "<center><table class='texto'>";
echo "<tr><th>Grupo</th>";
$sql1="select g.codigo, g.nombre  from grupos g where g.estado=1 and cod_tipo=2 order by nombre asc;";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td>
			<select name='cod_grupo' id='cod_grupo' required onChange='ajaxSubGrupo(this);'>";
			while($dat1=mysqli_fetch_array($resp1))
			{	$codGrupo=$dat1[0];
				$nombreGrupo=$dat1[1];
				if($cod_grupoX==$codGrupo){
					echo "<option value='$codGrupo' selected>$nombreGrupo</option>";					
				}else{
					echo "<option value='$codGrupo'>$nombreGrupo</option>";
				}
				
			}
			echo "</select>
</td>";
echo "<th>Sub-Grupo</th>";
echo "<td>
<div id='divSubGrupo'>";
$sql="select codigo, nombre from subgrupos where cod_grupo=$cod_grupoX order  by nombre asc";
$resp=mysqli_query($enlaceCon,$sql);

echo "<select name='cod_subgrupo' class='texto' id='cod_subgrupo' required>";
echo "<option value=''>---</option>";
while($dat=mysqli_fetch_array($resp)){
	$codigo=$dat[0];
	$nombre=$dat[1];

	if($cod_subgrupoX==$codigo){
					echo "<option value='$codigo' selected>$nombre</option>";					
				}else{
					echo "<option value='$codigo'>$nombre</option>";
				}
}
echo "</select>";

echo"</div>
</td>";
echo "</tr>";

echo "<tr><th align='left'>Nombre Insumo</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='descripcion_material' id='descripcion_material' size='40' value='$descripcion_materialX' style='text-transform:uppercase;' >
	</td> <th align='left'>Codigo Insumo</th>
	<td align='left'><input type='text' class='texto' name='codigo2' id='codigo2' value='$codigo2X' size='20' style='text-transform:uppercase;'> </td>
	</tr>";


echo "<tr><th align='left'>Descripcion</th>";
echo "<td align='left' colspan='3'>
	<input type='text' class='texto' name='observaciones' id='observaciones' value='$observacionesX' size='120' style='text-transform:uppercase;'>
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
				if($cod_unidadX==$codUnidad){
					echo "<option value='$codUnidad' selected>$nombreUnidad $abreviatura</option>";					
				}else{
					echo "<option value='$codUnidad'>$nombreUnidad $abreviatura</option>";					
				}
			}
			echo "</select>
</td>";
echo "</tr>";
echo "</table></center>";

$ciudad=$_COOKIE['global_agencia'];
	echo "<center><table class='texto'>";
	echo "<tr>
	<th>Nro</th><th>Sucursal</th><th>&nbsp;</th><th>Precio</th><th>Cantidad Inicio</th><th>Cantidad Final</th>		
		</tr>";

		$sqlCiu="select cod_ciudad,descripcion as desc_ciudad,nombre_ciudad 
		from ciudades where cod_ciudad='".$ciudad."' order by cod_ciudad asc";
		
		$respCiu=mysqli_query($enlaceCon,$sqlCiu);
		
		while($datCiu=mysqli_fetch_array($respCiu)){

			$cod_ciudad=$datCiu['cod_ciudad'];
			$desc_ciudad=$datCiu['desc_ciudad'];
			$nombre_ciudad=$datCiu['nombre_ciudad'];		

		$sqlGrupoPrecio="select codigo,nombre,cant_inicio,cant_final from grupos_precio where  estado=1 order by codigo asc";
		
		$respGrupoPrecio=mysqli_query($enlaceCon,$sqlGrupoPrecio);
		
			while($datGrupoPrecio=mysqli_fetch_array($respGrupoPrecio)){

				$codGrupoPrecio=$datGrupoPrecio['codigo'];
				$nomGrupoPrecio=$datGrupoPrecio['nombre'];			

					$cant_inicio=$datGrupoPrecio['cant_inicio'];
					$cant_final=$datGrupoPrecio['cant_final'];
					///////////////////////////////////////
					$precio=0;
					$sqlListPrecios="select p.codigo_material,p.cod_precio,gp.nombre ,gp.abreviatura ,p.precio,p.cod_ciudad,c.nombre_ciudad,
					p.cant_inicio,p.cant_final, p.created_by, 
					concat(f.nombres,' ',f.paterno,' ',f.materno) as creado_por, p.created_date		
					from precios p
					left join grupos_precio gp on (p.cod_precio=gp.codigo)
					left join ciudades c on (p.cod_ciudad=c.cod_ciudad)
					left join funcionarios f on (p.created_by=f.codigo_funcionario)
					where p.codigo_material='".$codigo_material."'and p.cod_ciudad='".$cod_ciudad."'
					and cod_precio=".$codGrupoPrecio." order by p.cod_precio asc";

					$respListPrecios=mysqli_query($enlaceCon,$sqlListPrecios);

					while($datListPrecios=mysqli_fetch_array($respListPrecios)){
			
						$precio=$datListPrecios['precio'];
						$cant_inicio=$datListPrecios['cant_inicio'];
						$cant_final=$datListPrecios['cant_final'];
			
					}		
					//////////////////////////////////////
					echo "<tr>";
					echo "<td>&nbsp;</td>";
					echo "<td>".$desc_ciudad."</td>";
					echo "<td>".$nomGrupoPrecio."</td>";
					echo "<td><input type='number' class='inputnumber'  id='precio".$cod_ciudad.$codGrupoPrecio."' name='precio".$cod_ciudad.$codGrupoPrecio."' size='6' step='0.01' value='".$precio."'></td>";
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
