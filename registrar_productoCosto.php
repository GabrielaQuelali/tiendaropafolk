<?php
	require("conexionmysqli2.inc");
	require('estilos.inc');
	require("funciones.php");
?>
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
function validar(f){
	 
	
	var cantidadItems=f.indice_tabla.value;
	
	if(cantidadItems>0){

		
		for(var i=1; i<=cantidadItems; i++){
			item=parseFloat(document.getElementById("codigoMaterial"+i).value);			
			if(item==0){
				alert("Debe escoger un item en la fila "+i);
				return(false);
			}
			return(true);
		}
		
	}else{
		alert("El ingreso debe tener al menos 1 item.");
		return(false);
	}
}

function limpiarBuscarProductos(f){
	var cod_marca;
	var nombreProducto;
	var nombreProductoCosto;
		var rpt_modelo=new Array();
		var rpt_grupo=new Array();
		var rpt_subgrupo=new Array();
		var rpt_genero=new Array();
		var rpt_talla=new Array();
		var rpt_material=new Array();
		var rpt_color=new Array();		
		var rpt_coleccion=new Array();
		cod_marca=f.cod_marca.value;
		nombreProducto="";
		nombreProductoCosto=f.nombreProductoCosto.value;
		location.href='registrar_productoCosto.php?cod_marca='+cod_marca+'&rpt_grupo='+rpt_grupo+'&rpt_subgrupo='+rpt_subgrupo+'&rpt_modelo='+rpt_modelo+'&rpt_material='+rpt_material+'&rpt_coleccion='+rpt_coleccion+'&rpt_talla='+rpt_talla+'&rpt_color='+rpt_color+'&rpt_genero='+rpt_genero+'&nombreProducto='+nombreProducto+'&nombreProductoCosto='+nombreProductoCosto;

}


 function buscarProductos(f){
		
		var cod_marca;
		var nombreProducto;
		var nombreProductoCosto;
		var rpt_modelo=new Array();
		var rpt_grupo=new Array();
		var rpt_subgrupo=new Array();
		var rpt_genero=new Array();
		var rpt_talla=new Array();
		var rpt_material=new Array();
		var rpt_color=new Array();		
		var rpt_coleccion=new Array();
		cod_marca=f.cod_marca.value;
		nombreProducto=f.nombreProducto.value;
		nombreProductoCosto=f.nombreProductoCosto.value;

		var a=0;
			for(b=0;b<=f.rpt_modelo.options.length-1;b++)
			{	if(f.rpt_modelo.options[b].selected)
				{	rpt_modelo[a]=f.rpt_modelo.options[b].value;
					a++;
				}
			}

	var e=0;
			for(g=0;g<=f.rpt_grupo.options.length-1;g++)
			{	if(f.rpt_grupo.options[g].selected)
				{	rpt_grupo[e]=f.rpt_grupo.options[g].value;
					e++;
				}
			}
	

	var i=0;
			for(j=0;j<=f.rpt_subgrupo.options.length-1;j++)
			{	if(f.rpt_subgrupo.options[j].selected)
				{	rpt_subgrupo[i]=f.rpt_subgrupo.options[j].value;
					i++;
				}
			}
	//alert("rpt_subgrupo="+rpt_subgrupo);

	var c=0;
			for(d=0;d<=f.rpt_genero.options.length-1;d++)
			{	if(f.rpt_genero.options[d].selected)
				{	rpt_genero[c]=f.rpt_genero.options[d].value;
					c++;
				}
			}
	//alert("rpt_genero="+rpt_genero);

	var n=0;
			for(m=0;m<=f.rpt_talla.options.length-1;m++)
			{	if(f.rpt_talla.options[m].selected)
				{	rpt_talla[n]=f.rpt_talla.options[m].value;
					n++;
				}
			}
	//alert("rpt_talla="+rpt_talla);

	var p=0;
			for(q=0;q<=f.rpt_material.options.length-1;q++)
			{	if(f.rpt_material.options[q].selected)
				{	rpt_material[p]=f.rpt_material.options[q].value;
					p++;
				}
			}			
	//alert("rpt_material="+rpt_material);

	var y=0;
			for(z=0;z<=f.rpt_color.options.length-1;z++)
			{	if(f.rpt_color.options[z].selected)
				{	rpt_color[y]=f.rpt_color.options[z].value;
					y++;
				}
			}			
	//alert("rpt_color="+rpt_color);

	var v=0;
			for(w=0;w<=f.rpt_coleccion.options.length-1;w++)
			{	if(f.rpt_coleccion.options[w].selected)
				{	rpt_coleccion[v]=f.rpt_coleccion.options[w].value;
					v++;
				}
			}			

	location.href='registrar_productoCosto.php?cod_marca='+cod_marca+'&rpt_grupo='+rpt_grupo+'&rpt_subgrupo='+rpt_subgrupo+'&rpt_modelo='+rpt_modelo+'&rpt_material='+rpt_material+'&rpt_coleccion='+rpt_coleccion+'&rpt_talla='+rpt_talla+'&rpt_color='+rpt_color+'&rpt_genero='+rpt_genero+'&nombreProducto='+nombreProducto+'&nombreProductoCosto='+nombreProductoCosto;
}

</script>
<script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="functionsGeneral.js">
</script>

<h3 align='center'>Registrar  Producto para Costo</h3>
<form method="POST" action="guardar_productoCosto.php" name="form1">

<?php

	$estado=1;
	$globalAgencia=$_COOKIE['global_agencia'];
	$global_almacen=$_COOKIE['global_almacen'];
	$tipo=1;
	if(isset($_GET['nombreProducto'])){
		$nombreProducto=$_GET['nombreProducto'];	
	}
	if(isset($_GET['nombreProductoCosto'])){
		$nombreProductoCosto=$_GET['nombreProductoCosto'];	
	}
	
	if(isset($_GET['cod_marca'])){
		$cod_marca=$_GET['cod_marca'];	
	}
	if(isset($_GET['rpt_grupo'])){
		$rpt_grupo=$_GET['rpt_grupo'];
		$rpt_grupo_cadena=$_GET['rpt_grupo'];
		//echo "rpt_grupo=".$rpt_grupo."<br/>";
		$rpt_grupo=explode(",",$rpt_grupo);
		
	}
	if(isset($_GET['rpt_subgrupo'])){
		$rpt_subgrupo=$_GET['rpt_subgrupo'];
		$rpt_subgrupo_cadena=$_GET['rpt_subgrupo'];
		//echo "rpt_subgrupo=".$rpt_subgrupo;
		$rpt_subgrupo=explode(",",$rpt_subgrupo);
	}
	if(isset($_GET['rpt_genero'])){
		$rpt_genero=$_GET['rpt_genero'];
		$rpt_genero_cadena=$_GET['rpt_genero'];
		//echo "rpt_genero=".$rpt_genero;
		$rpt_genero=explode(",",$rpt_genero);
	}
	if(isset($_GET['rpt_modelo'])){
		$rpt_modelo=$_GET['rpt_modelo'];
		$rpt_modelo_cadena=$_GET['rpt_modelo'];
		//echo "rpt_modelo=".$rpt_modelo;
		$rpt_modelo=explode(",",$rpt_modelo);
	}
	if(isset($_GET['rpt_color'])){
		$rpt_color=$_GET['rpt_color'];
		$rpt_color_cadena=$_GET['rpt_color'];
		//echo "rpt_color=".$rpt_color;
		$rpt_color=explode(",",$rpt_color);
	}

	if(isset($_GET['rpt_talla'])){
		$rpt_talla=$_GET['rpt_talla'];
		$rpt_talla_cadena=$_GET['rpt_talla'];
		//echo "rpt_talla=".$rpt_talla;
		$rpt_talla=explode(",",$rpt_talla);
	}
	if(isset($_GET['rpt_material'])){
		$rpt_material=$_GET['rpt_material'];
		//echo "rpt_material=".$rpt_material;
		$rpt_material_cadena=$_GET['rpt_material'];
		$rpt_material=explode(",",$rpt_material);
	}
	if(isset($_GET['rpt_coleccion'])){
		$rpt_coleccion=$_GET['rpt_coleccion'];
		//echo "rpt_coleccion=".$rpt_coleccion;
		$rpt_coleccion_cadena=$_GET['rpt_coleccion'];
		$rpt_coleccion=explode(",",$rpt_coleccion);
	}
?>	
<center>
	<table class='texto'>
<tr><th colspan="4">PARAMETROS DE BUSQUEDA</th><tr>
<tr>
	<th>Producto</th>
<td align='left'>
	<input type='text' class='texto' name='nombreProducto' id='nombreProducto' 
	size='50' style='text-transform:uppercase;' 
	value="<?=$nombreProducto;?>" 
	>
	<input type="button" value="Buscar"  class="boton" onclick="buscarProductos(this.form)">
	</td>
	
<th>Marca</th>
<td>
<?php
$sqlMarca="select codigo, nombre from marcas where estado=1  order by nombre asc";
 $respMarca=mysqli_query($enlaceCon,$sqlMarca);
 if(mysqli_num_rows($respMarca)<=0){
	 $sqlMarca="select codigo, nombre from marcas where estado=1 order by nombre asc";
	$respMarca=mysqli_query($enlaceCon,$sqlMarca);
}
?>

<select name='cod_marca' id='cod_marca' class='selectpicker' data-width='300px' data-live-search='true' data-style='btn btn-success' data-actions-box='true' >

<?php
while($datMarca=mysqli_fetch_array($respMarca)){
	$codigoX=$datMarca[0];
	$nombreX=$datMarca[1];
?>
	<option value="<?=$codigoX;?>" <?php if($cod_marca==$codigoX){ echo "selected";}?> >
		<?=$nombreX." ".$codigoX;?>
	</option>
<?php
}
?>
</select>
</td>
</tr>
<tr><th>Grupo</th>
<?php
$sql1="select f.codigo, f.nombre from grupos f 
where f.estado=1 and cod_tipo=".$tipo." order by 2;";
$resp1=mysqli_query($enlaceCon,$sql1);
?>
<td>
	<select name='rpt_grupo' id='rpt_grupo' class='selectpicker' data-width='300px' data-live-search='true' data-style='btn btn-success' data-actions-box='true'  onChange='buscarProductos(this.form);' multiple>
	
<?php
			while($dat1=mysqli_fetch_array($resp1))
			{	$codGrupo=$dat1[0];
				$nombreGrupo=$dat1[1];
				

				$swGrupo=0;
				 foreach ($rpt_grupo as $grupo) {
    				if($codGrupo==$grupo){
    					$swGrupo=1;
    				}
				}
?>
		<option value='<?=$codGrupo;?>' <?php if($swGrupo==1){ echo "selected";} ?> ><?=$nombreGrupo;?></option>
<?php	}

 ?>
	</select>
</td>
<th>Sub-Grupo</th>
<td>
<div id='divSubGrupo'>

<?php
	$sqlSubgrupo="select sub.codigo, sub.nombre, sub.cod_grupo, g.nombre as nombre_grupo
	from subgrupos sub left join grupos g on(sub.cod_grupo=g.codigo)
	where sub.estado=1  ";
	if(!empty($rpt_grupo_cadena)){
	$sqlSubgrupo=$sqlSubgrupo." and sub.cod_grupo in (".$rpt_grupo_cadena.")";
}
	$sqlSubgrupo=$sqlSubgrupo."  order by nombre_grupo asc, sub.nombre asc";
	//echo $sqlSubgrupo;
?>
<select name='rpt_subgrupo' id='rpt_subgrupo' class='selectpicker' data-width='300px' data-live-search='true' data-style='btn btn-success' data-actions-box='true'   multiple 
onChange='buscarProductos(this.form);' >  
<?php
	$respSubgrupo=mysqli_query($enlaceCon,$sqlSubgrupo);
	while($datSubgrupo=mysqli_fetch_array($respSubgrupo))
	{	$codSubgrupo=$datSubgrupo['codigo'];
		$nombreSubgrupo=$datSubgrupo['nombre'];
		$codGrupo=$datSubgrupo['cod_grupo'];
		$nombreGrupo=$datSubgrupo['nombre_grupo'];
						$swSubGrupo=0;
				 foreach ($rpt_subgrupo as $subgrupo) {
    				if($codSubgrupo==$subgrupo){
    					$swSubGrupo=1;
    				}
				}

?>
		<option value='<?=$codSubgrupo;?>'<?php if($swSubGrupo==1){ echo "selected";} ?>><?=$nombreGrupo."".$nombreSubgrupo;?> </option>
<?php	} ?>
	</select>
</div>
</td>
</tr>
<tr><th>Modelo</th>
<td>
<?php
$sqlModelo="select codigo, nombre,abreviatura from modelos where estado=1  order by nombre asc";
 $respModelo=mysqli_query($enlaceCon,$sqlModelo);
?>
<select name='rpt_modelo' id='rpt_modelo' class='selectpicker' data-width='300px' data-live-search='true' data-style='btn btn-success' data-actions-box='true'  onChange='buscarProductos(this.form);' multiple  >
	
<?php
while($datModelo=mysqli_fetch_array($respModelo)){
	$codModelo=$datModelo[0];
	$nombreModelo=$datModelo[1];
	$abrevModelo=$datModelo[2];
	$swModelo=0;
	foreach ($rpt_modelo as $modelo) {
    	if($codModelo==$modelo){
    		$swModelo=1;
    	}
	}
?>
	<option value="<?=$codModelo;?>" <?php if($swModelo==1){ echo "selected";}?>><?=$nombreModelo." (".$abrevModelo.")";?> </option>
<?php
	}
?>
</select>
</td>
<th>Genero</th>
<td>

<?php

$sqlGenero="select codigo, nombre,abreviatura from generos where estado=1  order by nombre asc";
 $respGenero=mysqli_query($enlaceCon,$sqlGenero);
?>
<select name='rpt_genero' id='rpt_genero' class='selectpicker' data-width='300px' data-live-search='true' data-style='btn btn-success' data-actions-box='true' 
onChange='buscarProductos(this.form);'  multiple >

<?php

while($datGenero=mysqli_fetch_array($respGenero)){
	$codGenero=$datGenero[0];
	$nombreGenero=$datGenero[1];
	$abrevGenero=$datGenero[2];
	$swGenero=0;
	foreach ($rpt_genero as $genero) {
    	if($codGenero==$genero){
    		$swGenero=1;
    	}
	}
?>
	<option value="<?=$codGenero;?>"<?php if($swGenero==1){ echo "selected";}?>><?=$nombreGenero." (".$abrevGenero.")";?></option>
<?php
}
?>
</select></td>
</tr>
<tr>
<th align='left'>Material</th>
<td>
<?php	
$sqlMaterial="select codigo, nombre,abreviatura from materiales where estado=1  order by nombre asc";
 $respMaterial=mysqli_query($enlaceCon,$sqlMaterial);
?>
<select name='rpt_material' id='rpt_material' class='selectpicker' data-width='300px' data-live-search='true' data-style='btn btn-success' data-actions-box='true' onChange='buscarProductos(this.form);' multiple >

<?php
while($datMaterial=mysqli_fetch_array($respMaterial)){
	$codMaterial=$datMaterial[0];
	$nombreMaterial=$datMaterial[1];
	$abrevMaterial=$datMaterial[2];
	$swMaterial=0;
	foreach ($rpt_material as $material) {
    	if($codMaterial==$material){
    		$swMaterial=1;
    	}
	}
?>
	<option value='<?=$codMaterial;?>'<?php if($swMaterial==1){ echo "selected";}?> ><?=$nombreMaterial." (".$abrevMaterial.")";?></option>
<?php
}
?>
</select></td>
<th align='left'>Coleccion</th>
<td>
<?php
$sqlColeccion="select codigo, nombre,abreviatura from colecciones where estado=1  order by nombre asc";
 $respColeccion=mysqli_query($enlaceCon,$sqlColeccion);
?>
<select name='rpt_coleccion' id='rpt_coleccion' class='selectpicker' data-width='300px' data-live-search='true' data-style='btn btn-success' data-actions-box='true' 
onChange='buscarProductos(this.form);'  multiple>
	
<?php
while($datColeccion=mysqli_fetch_array($respColeccion)){
	$codColeccion=$datColeccion[0];
	$nombreColeccion=$datColeccion[1];
	$abrevColeccion=$datColeccion[2];
	$swColeccion=0;
	foreach ($rpt_coleccion as $coleccion) {
    	if($codColeccion==$coleccion){
    		$swColeccion=1;
    	}
	}
?>
	<option value="<?=$codColeccion;?>" <?php if($swColeccion==1){ echo "selected";}?> ><?=$nombreColeccion." (".$abrevColeccion.")";?> </option>
<?php
}
?>
</select>
</td>
</tr>

<th align='left'>Talla</th>
<td>
<?php
$sqlTalla="select codigo, nombre,abreviatura from tallas where estado=1  order by nombre asc";
 $respTalla=mysqli_query($enlaceCon,$sqlTalla);
?>
<select name='rpt_talla' id='rpt_talla' class='selectpicker' data-width='300px' data-live-search='true' data-style='btn btn-success' data-actions-box='true' 
onChange='buscarProductos(this.form);' multiple >

<?php
while($datTalla=mysqli_fetch_array($respTalla)){
	$codTalla=$datTalla[0];
	$nombreTalla=$datTalla[1];
	$abrevTalla=$datTalla[2];
	$swTalla=0;
		foreach ($rpt_talla as $talla) {
    	if($codTalla==$talla){
    		$swTalla=1;
    	}
	}
?>
	<option value='<?=$codTalla;?>'<?php if($swTalla==1){ echo "selected";}?> ><?=$nombreTalla." ".$abrevTalla;?></option>
<?php
}
?>
</select></td>
<th align='left'>Color</th>
<td>
<?php
$sqlColores="select codigo, nombre,abreviatura from colores where estado=1  order by nombre asc";
 $respColores=mysqli_query($enlaceCon,$sqlColores);
?>
<select name='rpt_color' id='rpt_color'  class='selectpicker' data-width='300px' data-live-search='true' data-style='btn btn-success' data-actions-box='true' onChange='buscarProductos(this.form);'
 multiple >

<?php
while($datColores=mysqli_fetch_array($respColores)){
	$codColor=$datColores[0];
	$nombreColor=$datColores[1];
	$abrevColor=$datColores[2];
	$swColor=0;
	foreach ($rpt_color as $color) {
    	if($codColor==$color){
    		$swColor=1;
    	}
	}
?>
	<option value='<?=$codColor;?>'<?php if($swColor==1){ echo "selected";}?> ><?=$nombreColor." ".$abrevColor;?> </option>
<?php
}
?>
</select><br/><input type="button" value="Limpiar Busqueda" name="limpiar" class="boton" onclick="limpiarBuscarProductos(this.form)">
</td>


</tr>
	</table></center>
	<br/>
<center>
<table class='texto'>
<tr><th colspan="2">DATOS DE REGISTRO DE PRODUCTO DE COSTO</th></tr>
<tr>
	<th>Nombre de Producto Costo</th>
	<td align='left' colspan="3">
	<input type='text' class='texto' name='nombreProductoCosto' id='nombreProductoCosto' 
	size='100' style='text-transform:uppercase;' 
	value="<?=$nombreProductoCosto;?>" 
	required='true' >

	</td>
</tr>	
	</table></center>
	<center><table class='texto'>
	<tr>
		<th>&nbsp;</th>
		<th>Nro</th>
		<th>Producto</th>
		<th>Grupo</th>
		<th>Subgrupo</th>
		<th>Modelo</th>
		<th>Genero</th>
		<th>Material</th>
		<th>Coleccion</th>
		<th>Talla</th>
		<th>Color</th>	
	</tr>
	<?php 
	$sql="select ma.codigo_material,ma.descripcion_material,
ma.cod_subgrupo,sg.nombre as nombreSubgrupo,
sg.codigo,g.nombre as nombreGrupo,
ma.cod_modelo,mo.nombre as nombreModelo,
ma.cod_genero,ge.nombre as nombreGenero,ge.abreviatura as abrevGenero,
ma.cod_material, mat.nombre as nombreMaterial,
ma.cod_coleccion,co.nombre as nombreColeccion,
ma.talla,ta.nombre as nombreTalla,ta.abreviatura as abrevTalla,
ma.color,col.nombre as nombreColor
from material_apoyo ma
left join subgrupos sg on (ma.cod_subgrupo=sg.codigo)
left join grupos g on (sg.cod_grupo=g.codigo)
left join modelos mo on (ma.cod_modelo=mo.codigo)
left join generos ge on (ma.cod_genero=ge.codigo)
left join materiales mat on (ma.cod_material=mat.codigo)
left join colecciones co on (ma.cod_coleccion=co.codigo)
left join tallas ta on (ma.talla=ta.codigo)
left join colores col on (ma.color=col.codigo)
where ma.cod_tipo=1
and ma.estado=1 and ma.cod_marca=".$cod_marca;
if(!empty($rpt_grupo_cadena)){
$sql=$sql." and g.codigo in (".$rpt_grupo_cadena.")"; /* grupo*/
}
if(!empty($rpt_subgrupo_cadena)){
$sql=$sql." and sg.codigo in(".$rpt_subgrupo_cadena.")"; /* subgrupo*/
}
if(!empty($rpt_modelo_cadena)){
$sql=$sql." and mo.codigo in(".$rpt_modelo_cadena.")"; /* modelo*/
}
if(!empty($rpt_genero_cadena)){
$sql=$sql." and ge.codigo in(".$rpt_genero_cadena.")"; /* genero*/
}
if(!empty($rpt_material_cadena)){
$sql=$sql." and mat.codigo in(".$rpt_material_cadena.")"; /* materiales*/
}
if(!empty($rpt_coleccion_cadena)){
$sql=$sql." and co.codigo in(".$rpt_coleccion_cadena.") "; /* colecciones*/
}
if(!empty($rpt_talla_cadena)){
$sql=$sql." and ta.codigo in(".$rpt_talla_cadena.") "; /* tallas*/
}
if(!empty($rpt_color_cadena)){
$sql=$sql." and col.codigo in(".$rpt_color_cadena.") "; /* colres*/
}
if(!empty($nombreProducto)){
	$sql=$sql." and ma.descripcion_material like '%".$nombreProducto."%' "; /* colres*/
}
$sql=$sql." and ma.codigo_material not in( select cod_producto from producto_costo_detalle) ";

$sql=$sql." order by nombreGrupo asc,nombreSubgrupo asc, ma.descripcion_material asc";

//echo "sql=".$sql;
	
	$indice_tabla=0;
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp)){
		$indice_tabla++;
		$codigo_material=$dat['codigo_material'];
		$descripcion_material=$dat['descripcion_material'];
		$cod_subgrupo=$dat['cod_subgrupo'];
		$nombreSubgrupo=$dat['nombreSubgrupo'];
		$cod_grupo=$dat['cod_grupo'];
		$nombreGrupo=$dat['nombreGrupo'];
		$cod_modelo=$dat['cod_modelo'];
		$nombreModelo=$dat['nombreModelo'];
		$cod_genero=$dat['cod_genero'];
		$nombreGenero=$dat['nombreGenero'];
		$abrevGenero=$dat['abrevGenero'];
		$cod_material=$dat['cod_material'];
 		$nombreMaterial=$dat['nombreMaterial'];
		$cod_coleccion=$dat['cod_coleccion'];
		$nombreColeccion=$dat['nombreColeccion'];
		$talla=$dat['talla'];
		$nombreTalla=$dat['nombreTalla'];
		$abrevTalla=$dat['abrevTalla'];
		$color=$dat['color'];
		$nombreColor=$dat['nombreColor'];

	?>

<tr>
	<td align="center"><input type="checkbox" id="codigoMaterial<?=$indice_tabla;?>"
		value="<?=$codigo_material;?>"
		name="codigoMaterial<?=$indice_tabla;?>" checked></td>
		<td align="center"><?=$indice_tabla;?></td>
		<td><?=$descripcion_material;?></td>
		<td><?=$nombreGrupo;?></td>
		<td><?=$nombreSubgrupo;?></td>
		<td><?=$nombreModelo;?></td>
		<td><?=$nombreGenero;?></td>
		<td><?=$nombreMaterial;?></td>
		<td><?=$nombreColeccion;?></td>
		<td><?=$nombreTalla;?></td>
		<td><?=$nombreColor;?></td>		
 </tr>
	<?php	
}
	?>

</table></center><br>
<input type="hidden" name="indice_tabla" id="indice_tabla" value="<?=$indice_tabla;?>">
<div class="divBotones" align="center">
	<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form);'>
	<input type="button" class="boton2" value="Cancelar" 
onClick="location.href='navegador_productoCosto.php?estado=<?=$estado?>'"></center>
	
	
</div>

</form>