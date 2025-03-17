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


function buscarMaterial(f){
	
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
	document.getElementById('divboton').style.visibility='visible';
	document.getElementById('divListaMateriales').innerHTML='';
	document.getElementById('itemNombreMaterial').value='';	
	document.getElementById('itemNombreMaterial').focus();		
}



function Hidden(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';

}

function listaMateriales(f){
	var contenedor;
	var codGrupo=f.codGrupo.value;
	var tipo=1; 
	var nombreItem=f.itemNombreMaterial.value;
		var codMarca=f.itemMarca.value;
	var codBarraCod2=f.itemCodBarraCod2.value;
	
	contenedor = document.getElementById('divListaMateriales');
	ajax=nuevoAjax();
	
	ajax.open("GET", "ajaxListaProductosLote.php?tipo="+tipo+"&codGrupo="+codGrupo+"&codMarca="+codMarca+"&codBarraCod2="+codBarraCod2+"&nombreItem="+nombreItem,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null)
}

function setMateriales(f, cod, nombreMat, costoItem,precioVenta,precioVentaMayor){
	

	
	document.getElementById('cod_producto').value=cod;
	document.getElementById('producto').innerHTML=nombreMat;
	
	
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';
	
	
}
	


</script>

<head>

</head>
<?php
require("conexionmysqli.inc");
require('estilos.inc');
require('funciones.php');
$tipo=1;
$codLote=$_GET['cod_lote'];
$globalAgencia=$_COOKIE['global_agencia'];

$sqlEdit="select lp.nro_lote,lp.fecha_lote,lp.nombre_lote,lp.obs_lote,lp.codigo_material,
m.descripcion_material,
lp.cant_lote,lp.cod_estado_lote,lp.created_by,
lp.created_date,lp.fecha_inicio_lote,lp.fecha_fin_lote,lp.obligacionxpagar_si_no
from lotes_produccion lp
left join material_apoyo m on(lp.codigo_material=m.codigo_material)
 where lp.cod_lote='$codLote'";

$respEdit=mysqli_query($enlaceCon,$sqlEdit);
while($datEdit=mysqli_fetch_array($respEdit)){

	$nro_loteX=$datEdit['nro_lote'];
	$fecha_loteX=$datEdit['fecha_lote'];
	$nombre_loteX=$datEdit['nombre_lote'];
	$obs_loteX=$datEdit['obs_lote'];
	$codigo_materialX=$datEdit['codigo_material'];
	$descripcion_materialX=$datEdit['descripcion_material'];
	$cant_loteX=$datEdit['cant_lote'];
	$cod_estado_loteX=$datEdit['cod_estado_lote'];
	$obligacionxpagar_si_noX=$datEdit['obligacionxpagar_si_no'];


}
?>

<form action='guarda_editarlote.php' method='post' name='form1'>

<h1>Editar Lote</h1>


<input type='hidden' name='codLote' id='codLote' value='<?=$codLote;?>'>

<center><table class='texto'>
	<tr>
<th align='left'>Nro Lote</th>
<td align='left'><?=$nro_loteX;?></td>
</tr>
<tr>

<th align="left">Fecha</th>
<td align='left'>
<input type="date" class="texto"  id="fecha" name="fecha"  value="<?=$fecha_loteX;?>" >
</td>

</tr>
<tr>
<th align='left'>Nombre Lote</th>
<td align='left'>
	<input type='text' class='texto' name='nombre_lote' id='nombre_lote' value='<?=$nombre_loteX;?>' size='40' style='text-transform:uppercase;' >
	</td> 
</tr>
<tr>
 <th>Producto</th>
 <td>
<input type="hidden" name="cod_producto" id="cod_producto" value="<?=$codigo_materialX;?>" >
<div id="producto" class='textograndenegro'><?=$descripcion_materialX;?></div>
			<a href="javascript:buscarMaterial(form1)" accesskey="B"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>
</tr>
<tr><th align="left">Observacion</th>
<td align="left" >
	<input type="text" class="texto" name="obs_lote" id="obs_lote" value="<?=$obs_loteX;?>" size="100" style="text-transform:uppercase;">
	</td>
</tr>
<tr><th>Cantidad de Produccion</th>
<td>
	<input type="number" class="inputnumber"  id="cant_lote" name="cant_lote" value="$cant_loteX" size="6"  value="0"></td>
</tr>
<tr><th>Obligacion x Pagar</th>
<td>
<select  id="obligacionxpagar" name="obligacionxpagar"class="texto">
    <option value="1" <?php if($obligacionxpagar_si_noX==1){ echo "selected";} ?> >SI</option>
    <option value="0" <?php if($obligacionxpagar_si_noX==0){ echo "selected";} ?> >NO</option> 
</select>
</td>
</tr>

</table></center>
<div class="divBotones">
<input type="submit" class="boton" value="Guardar">
<input type="button" class="boton2" value="Cancelar" onClick="location.href='navegador_lotes.php'">
</div>
<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 500px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divboton" style="position: absolute; top:20px; left:920px;visibility:hidden; text-align:center; z-index:3">
	<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:450px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center; height:445px; overflow-y: scroll;">
		<table align='center' class="texto">
			<tr><th>Grupo</th><th>Marca</th><th>Cod.Barra/Cod.Prov</th><th>Material</th><th>&nbsp;</th></tr>
			<tr>
			<td><select name='codGrupo' id="codGrupo" class="texto" style="width:120px">
			<?php
			$sqlTipo="select g.codigo, g.nombre from grupos g
			where g.estado=1 and  g.cod_tipo=".$tipo." order by 2";
			$respTipo=mysqli_query($enlaceCon,$sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysqli_fetch_array($respTipo)){
				$codigoGrupo=$datTipo[0];
				$nombreGrupo=$datTipo[1];
				echo "<option value=$codigoGrupo>$nombreGrupo</option>";
			}
			?>
			</select>
			</td>
			<td><select class="texto" name='itemMarca' style="width:120px">
			<?php
			$sqlMarca="select m.codigo, m.nombre from marcas m
			where m.estado=1 order by 2;";
			echo $sqlMarca;
			$respMarca=mysqli_query($enlaceCon,$sqlMarca);
			echo "<option value='0'>--</option>";
			while($datMarca=mysqli_fetch_array($respMarca)){
				$codMarca=$datMarca[0];
				$nombreMarca=$datMarca[1];
				echo "<option value=$codMarca> $codMarca - $nombreMarca</option>";
			}
			?>

			</select>
			</td>
			<td>
				<input type='text' name='itemCodBarraCod2' id='itemCodBarraCod2' style="width:120px" class="texto" onkeypress="return pressEnter(event, this.form);">
			</td>		
			<td>
				<input type='text' name='itemNombreMaterial' id="itemNombreMaterial" class="texto"  onkeypress="return pressEnter(event, this.form);">
			</td>
			<td>
				<input type='button' class='boton' value='Buscar' onClick="listaMateriales(this.form)">
			</td>
			</tr>
			
		</table>
		<div id="divListaMateriales">
		</div>
	
	</div>
</div>
</form>
