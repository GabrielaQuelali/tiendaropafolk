<?php
require("conexionmysqli.php");
require('estilos.inc');
require('funciones.php');
?>
<script language='JavaScript'>

	

function repetirValor(f,grupoPrecio)
{	
  var nuevoPrecio=document.getElementById('precioGral'+grupoPrecio).value;
 	//alert('holaa='+document.getElementById('precioGral'+grupoPrecio).value);

 	var inputs = $('form input[name^="precio'+grupoPrecio+'"]');
		inputs.each(function() {
  		var value = $(this).val();
  		
  		
  			document.getElementById($(this).attr('id')).value=nuevoPrecio;
  			
  		

    	});

//alert('holaa='+grupoPrecio);

}
</script>
<?php


$rpt_territorio=$_GET['rpt_territorio'];
$rpt_marca=$_GET['rpt_marca'];
$rpt_modelo=$_GET['rpt_modelo'];
$rpt_grupo=$_GET['rpt_grupo'];
$rpt_subgrupo=$_GET['rpt_subgrupo'];
$rpt_genero=$_GET['rpt_genero'];
$rpt_talla=$_GET['rpt_talla'];
$rpt_material=$_GET['rpt_material'];
$rpt_color=$_GET['rpt_color'];
$mensaje=$_GET['mensaje'];


/*echo "rpt_territorio=".$rpt_territorio."<br/>";
echo "rpt_marca=".$rpt_marca."<br/>";
echo "rpt_modelo=".$rpt_modelo."<br/>";
echo "rpt_grupo=".$rpt_grupo."<br/>";
echo "rpt_subgrupo=".$rpt_subgrupo."<br/>";
echo "rpt_genero=".$rpt_genero."<br/>";
echo "rpt_talla=".$rpt_talla."<br/>";
echo "rpt_material=".$rpt_material."<br/>";
echo "rpt_color=".$rpt_color."<br/>";*/


$sqlSucursal="select descripcion from ciudades where cod_ciudad=".$rpt_territorio;
$respSucursal=mysqli_query($enlaceCon,$sqlSucursal);		
while($datSucursal=mysqli_fetch_array($respSucursal)){
	$sucursal=$datSucursal['descripcion'];
}

$sqlMarca="select nombre from marcas where codigo=".$rpt_marca;
$respMarca=mysqli_query($enlaceCon,$sqlMarca);		
while($datMarca=mysqli_fetch_array($respMarca)){
	$marca=$datMarca['nombre'];
}

echo "<form  action='guardarEdicionPreciosGral.php' method='post' name='form1'>";
echo "<input type='hidden' name='rpt_territorio' id='rpt_territorio' value='".$rpt_territorio."'>";
echo "<input type='hidden' name='rpt_marca' id='rpt_marca' value='".$rpt_marca."'>";
echo "<input type='hidden' name='rpt_modelo' id='rpt_modelo' value='".$rpt_modelo."'>";
echo "<input type='hidden' name='rpt_grupo' id='rpt_grupo' value='".$rpt_grupo."'>";
echo "<input type='hidden' name='rpt_subgrupo' id='rpt_subgrupo' value='".$rpt_subgrupo."'>";
echo "<input type='hidden' name='rpt_genero' id='rpt_genero' value='".$rpt_genero."'>";
echo "<input type='hidden' name='rpt_talla' id='rpt_talla' value='".$rpt_talla."'>";
echo "<input type='hidden' name='rpt_material' id='rpt_material' value='".$rpt_material."'>";
echo "<input type='hidden' name='rpt_color' id='rpt_color' value='".$rpt_color."'>";

echo "<h1>Actualizacion de Precios Sucursal $sucursal <br/> $marca</h1>";

echo "<center>$mensaje</center>";


if($rpt_grupo!="-1"){
	$grupo="";
	$sqlTitulo="select nombre from grupos where codigo in (".$rpt_grupo.")";
	$respTitulo=mysqli_query($enlaceCon,$sqlTitulo);		
	while($datTitulo=mysqli_fetch_array($respTitulo)){
		$grupo.=$datTitulo['nombre'];
	}
}else{
	$grupo="TODOS";
}
if($rpt_subgrupo!="-1"){
	$subgrupo="";
	$sqlTitulo="select nombre from subgrupos where codigo in (".$rpt_subgrupo.")";
	$respTitulo=mysqli_query($enlaceCon,$sqlTitulo);		
	while($datTitulo=mysqli_fetch_array($respTitulo)){
		$subgrupo.=$datTitulo['nombre'];
	}
}else{
	$subgrupo="TODOS";
}
if($rpt_modelo!="-1"){
	$modelo="";
	$sqlTitulo="select nombre from modelos where codigo in (".$rpt_modelo.")";
	$respTitulo=mysqli_query($enlaceCon,$sqlTitulo);		
	while($datTitulo=mysqli_fetch_array($respTitulo)){
		$modelo.=$datTitulo['nombre'];
	}
}else{
	$modelo="TODOS";
}
if($rpt_genero!="-1"){
	$genero="";
	$sqlTitulo="select nombre from generos where codigo in(".$rpt_genero.")";
	$respTitulo=mysqli_query($enlaceCon,$sqlTitulo);		
	while($datTitulo=mysqli_fetch_array($respTitulo)){
		$genero.=$datTitulo['nombre'];
	}
}else{
	$genero="TODOS";
}
if($rpt_material!="-1"){
	$material="";
	$sqlTitulo="select nombre from materiales where codigo in (".$rpt_material.")";
	$respTitulo=mysqli_query($enlaceCon,$sqlTitulo);		
	while($datTitulo=mysqli_fetch_array($respTitulo)){
		$material.=$datTitulo['nombre'];
	}
}else{
	$material="TODOS";
}

if($rpt_color!="-1"){
	$color="";
	$sqlTitulo="select nombre from colores where codigo in(".$rpt_color.")";
	$respTitulo=mysqli_query($enlaceCon,$sqlTitulo);		
	while($datTitulo=mysqli_fetch_array($respTitulo)){
		$color.=$datTitulo['nombre'];
	}
}else{
	$color="TODOS";
}
if($rpt_talla!="-1"){
	$talla="";
	$sqlTitulo="select nombre from tallas where codigo in(".$rpt_talla.")";
	$respTitulo=mysqli_query($enlaceCon,$sqlTitulo);		
	while($datTitulo=mysqli_fetch_array($respTitulo)){
		$talla.=$datTitulo['nombre'];
	}
}else{
	$talla="TODOS";
}
echo "<center><table>";
echo "<tr>";
	echo "<th>Grupo</th><td>$grupo</td>";
	echo "<th>SubGrupo</th><td colspan='3'>$subgrupo</td>";
	echo "<th>Modelo</th><td>$modelo</td>";
	
echo "<tr/>";
echo "<tr>";
	echo "<th>Genero</th><td>$genero</td>";
	echo "<th>Material</th><td>$material</td>";
	echo "<th>Color</th><td>$color</td>";
	echo "<th>Talla</th><td>$talla</td>";
	
echo "<tr/>";
echo "</table></center>";

echo "<center>
<a onClick='location.href=\"filtroDefinicionPrecios.php\"' class='texto'>Volver a Filtros<img src='imagenes/back.png' width='20'></a>


</center>";
echo "<center><table class='texto'>";
	echo "<tr>
	<th>Nro</th><th>Modelo</th><th>Grupo</th><th>SubGrupo</th>		
	<th>Material</th><th>Genero</th><th>Color</th><th>Talla</th><th>Material</th>";
 
	$sqlGrupoPrecio="select codigo,nombre from grupos_precio where  estado=1 order by codigo asc";

	$respGrupoPrecio=mysqli_query($enlaceCon,$sqlGrupoPrecio);
		
	while($datGrupoPrecio=mysqli_fetch_array($respGrupoPrecio)){

			$codGrupoPrecio=$datGrupoPrecio['codigo'];
			$nomGrupoPrecio=$datGrupoPrecio['nombre'];
	echo" <th>".$nomGrupoPrecio."<br/>
	<table><tr><td><input type='button' value='R' onClick='repetirValor(this.form,".$codGrupoPrecio.")' ></td><td>
<input type='number' class='inputnumber'  id='precioGral".$codGrupoPrecio."' name='precioGral".$codGrupoPrecio."' 
 size='3' min='0' step='0.01'  value='0'></td></tr></table>
	</th>";
}
echo "</tr>";

$sql="select mp.codigo_material,mp.descripcion_material,mp.estado,mp.cod_grupo, g.nombre as nombreGrupo,
mp.cod_tipomaterial,mp.cantidad_presentacion,mp.observaciones,mp.imagen,
mp.cod_unidad, um.nombre as nombreUnidad,mp.peso, mp.cod_subgrupo,
sg.nombre as nombreSubgrupo, 
mp.cod_marca, mar.nombre as nombreMarca, mp.talla,tal.nombre as nombreTalla, mp.color, col.nombre as nombreColor,
mp.codigo_anterior,
mp.codigo2,mp.fecha_creacion,mp.cod_modelo, mo.nombre as nombreModelo,
mp.cod_material, ma.nombre as nombreMaterial,
mp.cod_genero, ge.nombre as nombreGenero
from material_apoyo mp
left join subgrupos sg on (mp.cod_subgrupo=sg.codigo)
left join grupos g on (sg.cod_grupo=g.codigo)
left join unidades_medida um on (mp.cod_unidad=um.codigo)
left join marcas mar on (mp.cod_marca=mar.codigo)
left join tallas tal on (mp.talla=tal.codigo)
left join colores col on (mp.color=col.codigo)
left join modelos mo on (mp.cod_modelo=mo.codigo)
left join materiales ma on (mp.cod_material=ma.codigo)
left join generos ge on (mp.cod_genero=ge.codigo)
where mp.estado=1";
$sql.=" and mp.cod_marca in(".$rpt_marca.")";
// Filtro Grupo
if($rpt_grupo!="-1"){
	$sql.=" and sg.cod_grupo in(".$rpt_grupo.")";
}
// Fin Filtro Grupo
// Filtro SubGrupo
if($rpt_subgrupo!="-1"){
	$sql.=" and mp.cod_subgrupo in(".$rpt_subgrupo.")";
}
// Fin Filtro SubGrupo
// Filtro Modelo
if($rpt_modelo!="-1"){
	$sql.=" and mp.cod_modelo in(".$rpt_modelo.")";
}
// Fin Filtro Modelo
// Filtro Modelo
if($rpt_modelo!="-1"){
	$sql.=" and mp.cod_modelo in(".$rpt_modelo.")";
}
// Fin Filtro Modelo
// Filtro Genero
if($rpt_genero!="-1"){
	$sql.=" and mp.cod_genero in(".$rpt_genero.")";
}
// Fin Filtro Genero
// Filtro Talla
if($rpt_talla!="-1"){
	$sql.=" and mp.talla in(".$rpt_talla.")";
}
// Fin Filtro Talla
// Filtro Color
if($rpt_color!="-1"){
	$sql.=" and mp.color in(".$rpt_color.")";
}
// Fin Filtro Color
// Filtro Material
if($rpt_material!="-1"){
	$sql.=" and mp.cod_material in(".$rpt_material.")";
}
// Fin Filtro Material


$sql.=" order by mo.nombre asc ,  sg.nombre asc, ge.nombre asc, col.nombre asc,mp.codigo_material asc";

//echo $sql."<br/>";
$resp=mysqli_query($enlaceCon,$sql);
$contador=1;
while($dat=mysqli_fetch_array($resp)){

	$codigo_material=$dat['codigo_material'];
	$descripcion_material=$dat['descripcion_material'];
	$estado=$dat['estado'];
	$cod_grupo=$dat['cod_grupo'];
	$nombreGrupo=$dat['nombreGrupo'];
	$cod_tipomaterial=$dat['cod_tipomaterial'];
	$cantidad_presentacion=$dat['cantidad_presentacion'];
	$observaciones=$dat['observaciones'];
	$imagen=$dat['imagen'];
	$cod_unidad=$dat['cod_unidad'];
	$nombreUnidad=$dat['nombreUnidad'];
	$peso=$dat['peso'];
	$cod_subgrupo=$dat['cod_subgrupo'];
	$nombreSubgrupo=$dat['nombreSubgrupo'];
	$cod_marca=$dat['cod_marca'];
	$nombreMarca=$dat['nombreMarca'];
	$talla=$dat['talla'];
	$nombreTalla=$dat['nombreTalla'];
	$color=$dat['color'];
	$nombreColor=$dat['nombreColor'];
	$codigo_anterior=$dat['codigo_anterior'];
	$codigo2=$dat['codigo2'];
	$fecha_creacion=$dat['fecha_creacion'];
	$cod_modelo=$dat['cod_modelo'];
	$nombreModelo=$dat['nombreModelo'];
	$cod_material=$dat['cod_material'];
	$nombreMaterial=$dat['nombreMaterial'];
	$cod_genero=$dat['cod_genero'];
 	$nombreGenero=$dat['nombreGenero'];

 	echo "<tr>";
					echo "<td>".$contador."</td>";
										
					echo "<td>".$nombreModelo."</td>";
					echo "<td>".$nombreGrupo."</td>";
					echo "<td>".$nombreSubgrupo."</td>";
					echo "<td>".$nombreMaterial."</td>";
					echo "<td>".$nombreGenero."</td>";		
					echo "<td>".$nombreColor."</td>";		
					echo "<td>".$nombreTalla."</td>";
					echo "<td>".$codigo_material."-".$descripcion_material."</td>";

					$sqlGrupoPrecio="select codigo,nombre from grupos_precio where  estado=1 order by codigo asc";

					$respGrupoPrecio=mysqli_query($enlaceCon,$sqlGrupoPrecio);
		
					while($datGrupoPrecio=mysqli_fetch_array($respGrupoPrecio)){

						$codGrupoPrecio=$datGrupoPrecio['codigo'];
						$nomGrupoPrecio=$datGrupoPrecio['nombre'];	

						$sqlPrecio="select p.precio, p.cant_inicio,p.cant_final from precios p		
						where p.codigo_material='".$codigo_material."' 
						and p.cod_ciudad='".$rpt_territorio."' and p.cod_precio='".$codGrupoPrecio."'";

						$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
						$cantSqlPrecio=mysqli_num_rows($respPrecio);
						$precio=0;

						if($cantSqlPrecio>0){

							while($datPrecio=mysqli_fetch_array($respPrecio)){
								$precio=$datPrecio['precio'];
								$cant_inicio=$datPrecio['cant_inicio'];
								$cant_final=$datPrecio['cant_final'];

							}
						}

							echo "<td><center><input type='number' class='inputnumber'  id='precio".$codGrupoPrecio.$codigo_material."' name='precio".$codGrupoPrecio.$codigo_material."' size='6' min='0' step='0.01'  value='".($precio)."' ></center></td>";
							
					}
					
					echo "</tr>";
				$contador++;

}

		
		
	
echo"</table></center>";


echo "<center>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Volver a Filtros' onClick='location.href=\"filtroDefinicionPrecios.php\"'>
</center>";
echo "</form>";

?>
