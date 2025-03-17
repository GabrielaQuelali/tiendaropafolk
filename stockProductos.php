<?php
	require("conexionmysqli2.inc");
	require('estilos.inc');
	require("funciones.php");
	$ciudad=$_COOKIE['global_agencia'];

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
<h3 align='center'>Stock de Productos</h3>
<form method='post' action='' name="form1" id="form1">
<center>
	<table border="1">

	<tr>
		<td align="center"><strong>Nro</strong></td>
		<td align="center"><strong>GRUPO</strong></td>
		<td align="center"><strong>MODELO</strong></td>
		<td align="center"><strong>PRODUCTO</strong></td>
<?php

	$sqlTallas="select codigo,nombre,abreviatura,estado,orden from tallas order by orden asc";
	$respTallas=mysqli_query($enlaceCon,$sqlTallas);
	while($datTallas=mysqli_fetch_array($respTallas)){
		$cod_talla=$datTallas['codigo'];
		$nombre_talla=$datTallas['abreviatura'];
?>
	<td align="center"><strong><?=$nombre_talla;?></strong></td>
<?php		
	}

?>	
<td align="center"><strong>Total</strong></td>
<td align="center"><strong>Existencia</strong></td>
	</tr>

<?php

	$contadorProducto=0;
	$nro=0;


	$sql2="select nt.cod_marca,mar.nombre as nomMarca,
nt.cod_grupo, g.nombre as nomGrupo, 
nt.cod_modelo, mo.nombre as nomModelo, mo.abreviatura as abrevModelo,
nt.cod_genero, gen.nombre as nomGenero, gen.abreviatura as abrevGenero,
nt.cod_subgrupo,subg.nombre as nomSubgrupo, subg.abreviatura as abrevSubgrupo,
nt.cod_material, mat.nombre as nomMaterial, mat.abreviatura as abrevMaterial,
nt.cod_color, col.nombre as nomColor, col.abreviatura as abrevColor
from (select m.cod_marca,sg.cod_grupo,m.cod_modelo,m.cod_genero,m.cod_subgrupo,m.cod_material,m.color as cod_color
from material_apoyo m left join subgrupos sg on (m.cod_subgrupo=sg.codigo)
group by m.cod_marca,sg.cod_grupo, m.cod_modelo,m.cod_genero,m.cod_subgrupo,m.cod_material,m.color) nt
left join grupos g on (nt.cod_grupo=g.codigo)
left join subgrupos subg on (nt.cod_subgrupo=subg.codigo)
left join modelos mo on (nt.cod_modelo=mo.codigo)
left join generos gen on (nt.cod_genero= gen.codigo)
left join materiales mat on (nt.cod_material=mat.codigo)
left join colores col on (nt.cod_color=col.codigo)
left join marcas mar on (nt.cod_marca=mar.codigo)
order by nomMarca asc, nomGrupo asc, abrevModelo asc, nomGenero asc, nomSubgrupo asc, 
nomMaterial asc, nomColor asc";
//echo $sql2;
	$cantTotalProductos=0;
	$resp2=mysqli_query($enlaceCon,$sql2);
	while($dat2=mysqli_fetch_array($resp2)){
		$nro++;
		$cod_marca=$dat2['cod_marca'];
		$nomMarca=$dat2['nomMarca'];
		$cod_grupo=$dat2['cod_grupo'];
		$nomGrupo=$dat2['nomGrupo'];
		$cod_modelo=$dat2['cod_modelo'];
		$nomModelo=$dat2['nomModelo'];
		$abrevModelo=$dat2['abrevModelo'];
		$cod_genero=$dat2['cod_genero'];
		$nomGenero=$dat2['nomGenero'];
		$abrevGenero=$dat2['abrevGenero'];
		$cod_subgrupo=$dat2['cod_subgrupo'];
		$nomSubgrupo=$dat2['nomSubgrupo'];
		$abrevSubgrupo=$dat2['abrevSubgrupo'];
		$cod_material=$dat2['cod_material'];
		$nomMaterial=$dat2['nomMaterial'];
		$abrevMaterial=$dat2['abrevMaterial'];
		$cod_color=$dat2['cod_color'];
		$nomColor=$dat2['nomColor'];
		$abrevColor=$dat2['abrevColor'];
		$descModelo="";
		if($nomModelo==$abrevModelo){
			$descModelo=$abrevModelo;
		}else{
			$descModelo=$abrevModelo." ".$nomModelo;
		}
?>
<tr>
	<td align="right"><strong><?=$nro;?></strong></td>
		<td><?=$nomGrupo;?></td>
		<td><?=$nomModelo."<br/>Abrev:".$abrevModelo;?></td>

	</td>
	<td><?=$nomGrupo;?> <?=$descModelo;?> <?=$nomGenero;?> <?=$nomSubgrupo;?> <?=$abrevMaterial;?>
		<?=$nomColor;?> <?=$abrevColor;?>

	</td>
<?php

			$cantTalla=0;
			$sqlTalla="select codigo,nombre,abreviatura,estado,orden from tallas order by orden asc";
			$respTalla=mysqli_query($enlaceCon,$sqlTalla);
			while($datTalla=mysqli_fetch_array($respTalla)){
				$codTalla=$datTalla['codigo'];
				$nombreTalla=$datTalla['nombre'];

				$sqlProducto="select ma.codigo_material,ma.descripcion_material,ma.estado,
				subg.cod_grupo,ma.cod_subgrupo,ma.cod_marca,ma.talla,ma.color,ma.cod_modelo,
				ma.cod_material,ma.cod_genero,ma.cod_coleccion,ma.estado,es.nombre_estado
				from material_apoyo ma
				left join subgrupos subg on (ma.cod_subgrupo=subg.codigo)
				left join estados es on (ma.estado=es.cod_estado)
				where ma.cod_tipo=1
				and ma.cod_marca=".$cod_marca."
				and subg.cod_grupo=".$cod_grupo."
				and ma.cod_modelo=".$cod_modelo."
				and ma.cod_genero=".$cod_genero."
				and ma.cod_subgrupo=".$cod_subgrupo."
				and ma.cod_material=".$cod_material."
				and ma.color=".$cod_color."
				and ma.talla=".$codTalla;
				$respProducto=mysqli_query($enlaceCon,$sqlProducto);
				$contProd=0;
				$codProducto=0;
			
?>
			<td align="center" title="<?="Talla:".$nombreTalla?>">

<?php
				while($datProducto=mysqli_fetch_array($respProducto)){
					$contProd++;
					$contadorProducto++;
					////////////////////////
					$codProducto=$datProducto['codigo_material'];
					$estado=$datProducto['estado'];
					$nombre_estado=$datProducto['nombre_estado'];
					
					////////////////////////
					$cantidadProducto=0;
					$sqlStock="select cantidad,fecha,codigo_funcionario from inventarioinicial where codigo_material=".$codProducto." and cod_ciudad=".$ciudad;
					$respStock=mysqli_query($enlaceCon,$sqlStock);
					while($datStock=mysqli_fetch_array($respStock)){
						$cantidadProducto=$datStock['cantidad'];
						$cantTalla=$cantTalla+$cantidadProducto;
					}

					if($estado==1){
?>					
					<?="Cant:<strong>".$cantidadProducto."</strong> Prod:".$codProducto;?><br/>			
<?php				
					}else{
?>					
					<?="<strong style='color:RED' >Cant:".$cantidadProducto." Prod:".$codProducto." ANULADO</strong>";?>
					<br/>			
<?php				
					}						
				}
				if($contProd>0){
					if( $contProd==1){
						//echo $contProd;
					}else{
						echo "<strong style='color:BLUE'>Repetido:</strong>". $contProd;
					}
					
				}else{
					echo "-";
				}
?>				
				
				</td>
				
<?php
				
			}
		////
?>

<?php
if($cantTalla>0){
?>
	<td align="right" bgcolor="#CDFAFC" ><strong><?=number_format($cantTalla,2,'.',',');?></strong></td>
	<td align="right" bgcolor="#CDFAFC"><strong>EXISTENTE</strong></td>
		<?php
}else{
?>
	<td align="right">&nbsp;</td>
	<td align="right">&nbsp;</td>
<?php	
}
?>

	
</tr>
<?php

	$cantTotalProductos=$cantTotalProductos+$cantTalla;
	}


?>
<tr align="right"> 
	<td colspan="19"><strong>TOTAL PRODUCTOS</strong></td>
	<td><strong><?=number_format($cantTotalProductos,2,'.',',');?></strong></td>
	<td>&nbsp;</td>
</tr>
</table>
<?="TOTAL PRODUCTOS:".number_format($contadorProducto,2,'.',',');?>
</center>
	<br>
	


</form>