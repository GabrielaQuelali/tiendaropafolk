<?php
require("conexionmysqli.inc");
require("estilos_almacenes.inc");
require("funciones.php");

$globalAlmacen=$_COOKIE['global_almacen'];
$globalAgencia=$_COOKIE['global_agencia'];

$cod_lote=$_GET['cod_lote'];
$tipo=$_GET['tipo'];

if($fecha=="")
{   $fecha=date("Y-m-d");
}


$num_materiales=1;
	
?>
<html>
    <head>
        
<script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="dlcalendar.js"></script>
<script type='text/javascript' language='javascript'>

num=<?= $num_materiales;?>;
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
function ajaxNroSalida(){
	var contenedor;
	var nroSalida = parseInt(document.getElementById('nro_salida').value);
	if(isNaN(nroSalida)){
		nroSalida=0;
	}
	contenedor = document.getElementById('divNroSalida');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxNroSalida.php?nroSalida="+nroSalida,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null)
}

function listaMateriales(f){
	var contenedor;
	var codGrupo=f.codGrupo.value;
	var tipo=f.tipo.value;
	var nombreItem=f.itemNombreMaterial.value;
			var codMarca=f.itemMarca.value;
	var codBarraCod2=f.itemCodBarraCod2.value;
	contenedor = document.getElementById('divListaMateriales');
	ajax=nuevoAjax();
	
	ajax.open("GET", "ajaxListaMaterialesIngreso.php?tipo="+tipo+"&codGrupo="+codGrupo+"&codMarca="+codMarca+"&codBarraCod2="+codBarraCod2+"&nombreItem="+nombreItem,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null)
}
function buscarMaterial(f, numMaterial){
	f.materialActivo.value=numMaterial;
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
function setMateriales(f, cod, nombreMat, costoItem, precioVenta,precioVentaMayor){
	var numRegistro=f.materialActivo.value;
	
	document.getElementById('material'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat;
	document.getElementById('ultimoCosto'+numRegistro).value=costoItem;

	document.getElementById('precio'+numRegistro).value=costoItem;
	document.getElementById('precioVenta'+numRegistro).value=precioVenta;
	document.getElementById('precioVentaMayor'+numRegistro).value=precioVentaMayor;
	document.getElementById('divUltimoCosto'+numRegistro).innerHTML="["+costoItem+"]";
	document.getElementById('divPVenta'+numRegistro).innerHTML="["+precioVenta+"]";
	document.getElementById('divPVentaMayor'+numRegistro).innerHTML="["+precioVentaMayor+"]";

	
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';
	

	document.getElementById("cantidad_unitaria"+numRegistro).focus();
	
}
function cambiaCosto(f, fila){
	var cantidad=document.getElementById('cantidad_unitaria'+fila).value;
	var precioFila=document.getElementById('precio'+fila).value;
	var ultimoCosto=document.getElementById('ultimoCosto'+fila).value;
	
	console.log(cantidad+" "+precioFila);
	var calculoCosto=parseFloat(cantidad)*parseFloat(ultimoCosto);
	var calculoPrecioTotal=parseFloat(cantidad)*parseFloat(precioFila);	
	if(calculoCosto=="NaN"){
		calculoCosto.value=0;
	}	

	document.getElementById('divUltimoCosto'+fila).innerHTML="["+ultimoCosto+"]";
	document.getElementById('divPrecioTotal'+fila).innerHTML=calculoPrecioTotal;
	
}

function enviar_form(f)
{   f.submit();
}
	//num=0;

function mas(obj) {

		num++;
		fi = document.getElementById('fiel');
		contenedor = document.createElement('div');
		contenedor.id = 'div'+num;  
		fi.type="style";
		fi.appendChild(contenedor);
		var div_material;
		div_material=document.getElementById("div"+num);			
		ajax=nuevoAjax();
		ajax.open("GET","ajaxMaterial.php?codigo="+num,true);
		ajax.onreadystatechange=function(){
			if (ajax.readyState==4) {
				div_material.innerHTML=ajax.responseText;
				buscarMaterial(form1, num);
			}
		}		
		ajax.send(null);
	
}	
		
function menos(numero) {
	if(numero==num){
		num=parseInt(num)-1;
	}
	//num=parseInt(num)-1;
	fi = document.getElementById('fiel');
	fi.removeChild(document.getElementById('div'+numero));		
}

function pressEnter(e, f){
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla==13){
		document.getElementById('itemNombreMaterial').focus();
		listaMateriales(f);
		return false;
	}
}
function validar(f){   
	f.cantidad_material.value=num;
	var cantidadItems=num;
	
	if(cantidadItems>0){
		var item="";
		var cantidad="";
		var precioBruto="";
		var precioNeto="";
		
		for(var i=1; i<=cantidadItems; i++){
			item=parseFloat(document.getElementById("material"+i).value);			
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


	</script>

<form action='guardaIngresoAlmProductoLote.php' method='post' name='form1'>

<?php
	$sql="select nro_correlativo from ingreso_almacenes where cod_almacen='".$global_almacen."' and cod_tipo=".$tipo." order by cod_ingreso_almacen desc";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$num_filas=mysqli_num_rows($resp);
	if($num_filas==0)
	{   $nro_correlativo=1;
	}
	else
	{   $nro_correlativo=$dat[0];
    	$nro_correlativo++;
	}


	if($fecha==""){   
		$fecha=date("d/m/Y");
	}
	$sql2="select lp.nro_lote,lp.nombre_lote,lp.codigo_material,ma.descripcion_material,um.abreviatura,lp.cant_lote 
	from lotes_produccion lp
	left join material_apoyo ma on(lp.codigo_material=ma.codigo_material)
	left join unidades_medida um on(ma.cod_unidad=um.codigo)
	where lp.cod_lote=".$cod_lote;
	$resp2=mysqli_query($enlaceCon,$sql2);
	while($dat2=mysqli_fetch_array($resp2)){
		$nro_lote=$dat2['nro_lote'];
		$nombre_lote=$dat2['nombre_lote'];
		$codigo_material=$dat2['codigo_material'];
		$descripcion_material=$dat2['descripcion_material'];
		$abreviatura=$dat2['abreviatura'];
		$cant_lote=$dat2['cant_lote'];

	}
?>	


<input type="hidden" name="tipo" value="<?php echo $tipo;?>" id="tipo">

<input type="hidden" name="cod_lote" value="<?php echo $cod_lote;?>" id="cod_lote">
<table border='0' class='textotit' align='center'><tr><th> Ingreso de Producto a Almacen</th></tr></table><br>


<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>
<tr><th>Numero de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Factura</th></tr>
<tr>
	<td align='center'><?=$nro_correlativo;?></td>
	<td align='center'>
		<input type="date" class="texto"  id="fecha" name="fecha"  value="<?=$fecha;?>" >

	</td>
	
<?php
$sql1="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso order by cod_tipoingreso ";
$resp1=mysqli_query($enlaceCon,$sql1);
?>

<td align='center'><select name='tipo_ingreso' id='tipo_ingreso' class='texto'>

<?php

while($dat1=mysqli_fetch_array($resp1))
{   $cod_tipoingreso=$dat1[0];
    $nombre_tipoingreso=$dat1[1];
?>
    <option value="<?=$cod_tipoingreso; ?>"><?php echo $nombre_tipoingreso;?></option>
<?php
}
?>
</select></td>
<td align="center"><input type="text" class="texto" name="nro_factura" value="0" id="nro_factura"></td></tr>
<tr><th>Proveedor</th><th colspan="3">Observaciones</th></tr>
<tr>
<td  align="center">
<select name='proveedor' id='proveedor' class='texto'>";
<?php	$sql1="select cod_proveedor, nombre_proveedor from proveedores where  cod_tipo=".$tipo." order by 2";
$resp1=mysqli_query($enlaceCon,$sql1); 

while($dat1=mysqli_fetch_array($resp1))
{   $codigo=$dat1[0];
    $nombre=$dat1[1];
?>
  <option value="<?=$codigo?>"><?=$nombre;?></option>
<?php  
}
?>
</select>


</td>
<td colspan="4" align="center"><input type="text" class="texto" name="observaciones"  size="100"></td>
</tr>
<tr><th colspan="5">Lote</th></tr>
<tr><td colspan="5"><?="NRO:".$nro_lote." ".$nombre_lote." CANT:".$cant_lote;?>
<br/><?=$descripcion_material." Unidad Medida:".$abreviatura;?></td></tr>
</table><br>

		<fieldset id="fiel" style="width:98%;border:0;" >
			<table align="center"class="text" cellSpacing="1" cellPadding="2" width="100%" border="0" id="data0" style="border:#ccc 1px solid;">
				<tr>
					<td align="center" colspan="6">
						<input class="boton" type="button" value="Buscar Producto(+)" onclick="mas(this)" />
					</td>
				</tr>
				<tr>
					<td align="center" colspan="6">
					<div style="width:100%;" align="center"><b>DETALLE</b></div>
					</td>				
				</tr>				
				<tr class="titulo_tabla" align="center">
					<td width="5%" align="center">&nbsp;</td>
					<td width="35%" align="center">Producto</td>
					<td width="10%" align="center">Cantidad</td>
					<td width="10%" align="center">Lote</td>
					<td width="10%" align="center">Costo [u]</td>
					<td width="10%" align="center">P. Normal[u]</td>
					<td width="10%" align="center">P. x Mayor[u]</td>
					<td width="10%" align="center">Precio Total</td>
					<td width="10%" align="center">&nbsp;</td>
				</tr>
			</table>
			
			<?php
				$sqlDetalle="select lp.codigo_material,ma.descripcion_material,um.abreviatura,
				lp.cant_lote  from lotes_produccion lp
				left join material_apoyo ma on(lp.codigo_material=ma.codigo_material)
				left join unidades_medida um on(ma.cod_unidad=um.codigo)
				where lp.cod_lote=".$cod_lote;
				
			$respDetalle=mysqli_query($enlaceCon,$sqlDetalle);
			$indiceMaterial=1;
			while($datDetalle=mysqli_fetch_array($respDetalle)){
				$codMaterial=$datDetalle['codigo_material'];
				$nombreMaterial=$datDetalle['descripcion_material'];
				$cantidadMaterial=$datDetalle['cant_lote'];
				
				$num=$indiceMaterial;
				
				//obtener costo
				$sqlPrecio0="select precio from precios where codigo_material='".$codMaterial."' and cod_precio=0 
					and cod_ciudad='".$globalAgencia."'";
				$respPrecio0=mysqli_query($enlaceCon,$sqlPrecio0);
				$numFilasPrecio0=mysqli_num_rows($respPrecio0);
				$precio0=0;				
				if($numFilasPrecio0>0){
						$datPrecio0=mysqli_fetch_array($respPrecio0);
						$precio0=$datPrecio0[0];
				}
				// fin obtener costo
				
				//obtener precio normal
				$sqlPrecio1="select precio from precios where codigo_material='".$codMaterial."' and cod_precio=1 
					and cod_ciudad='".$globalAgencia."'";
				$respPrecio1=mysqli_query($enlaceCon,$sqlPrecio1);
				$numFilasPrecio1=mysqli_num_rows($respPrecio1);
				$precio1=0;			
				if($numFilasPrecio1>0){
						$datPrecio1=mysqli_fetch_array($respPrecio1);
						$precio1=$datPrecio1[0];
				}
				// fin obtener precio normal
				
				//obtener precio x mayor
				$sqlPrecio2="select precio from precios where codigo_material='".$codMaterial."' and cod_precio=2 
					and cod_ciudad='".$globalAgencia."'";
				$respPrecio2=mysqli_query($enlaceCon,$sqlPrecio2);
				$numFilasPrecio2=mysqli_num_rows($respPrecio2);
				$precio2=0;			
				if($numFilasPrecio2>0){
						$datPrecio2=mysqli_fetch_array($respPrecio2);
						$precio2=$datPrecio2[0];
				}
				// fin obtener precio x mayor
			
			?>

<div id="div<?php echo $num?>">

<table border="0" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="5%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)" accesskey="B"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>

<td width="35%" align="left">
<input type="hidden" name="material<?php echo $num;?>" id="material<?php echo $num;?>" value="<?=$codMaterial;?>">
<div id="cod_material<?php echo $num;?>" class='textoform'><strong><?=$codigo2;?><?=$codBarra;?></strong> <?=$nombreMaterial;?><strong><? echo " (".$nombreMarca.")";?></strong></div>
</td>
<td align="center" width="10%">
<input type="number" class="inputnumber" min="1" max="1000000" id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" size="5"  value="<?=$cantidadMaterial;?>" step="0.01" onchange='cambiaCosto(this.form,<?php echo $num;?>)' onkeyup='cambiaCosto(this.form,<?php echo $num;?>)' required>
</td>

<td align="center" width="10%">
<input type="text" class="textoform" id="lote<?php echo $num;?>" name="lote<?php echo $num;?>" size="10" value="<?=$cod_lote;?>" readonly>
</td>

<td align="center" width="10%">
<input type="number" class="inputnumber"  id="precio<?=$num;?>" name="precio<?=$num;?>" 
value="<?=$precio0;?>" size="5" min="0" step="0.01"  
onchange='cambiaCosto(this.form,<?=$num;?>)' onkeyup='cambiaCosto(this.form,<?=$num;?>)'
 <?php if (obtenerValorConfiguracion($enlaceCon,7)==0){  echo "readonly";}?>  required><br>
<input type="hidden" id='ultimoCosto<?php echo $num;?>' name='ultimoCosto<?php echo $num;?>' value="">
<div id='divUltimoCosto<?php echo $num;?>'><?=$precio0;?></div>
</td>
<td align="center" width="10%">
<input type="number" class="inputnumber" value="<?=$precio1;?>" id="precioVenta<?php echo $num;?>" name="precioVenta<?php echo $num;?>" size="5" min="0.1" step="0.01"  
 <?php if (obtenerValorConfiguracion($enlaceCon,7)==0){  echo "readonly";}?>  required><br>
<div id='divPVenta<?php echo $num;?>'><?=$precio1;?></div>
</td>
<td align="center" width="10%">
<input type="number" class="inputnumber" value="<?=$precio2;?>" id="precioVentaMayor<?php echo $num;?>" name="precioVentaMayor<?php echo $num;?>" size="5" min="0.1" step="0.01"  
 <?php if (obtenerValorConfiguracion($enlaceCon,7)==0){  echo "readonly";}?>  required><br>
<div id='divPVentaMayor<?php echo $num;?>'><?=$precio2;?></div>
</td>
<td align="center" width="10%">
<div id='divPrecioTotal<?php echo $num;?>'><?=$precio0*$cantidadMaterial;?></div>
</td>

<td align="center"  width="10%" ><input class="boton1" type="button" value="(-)" onclick="menos(<?php echo $num;?>)" size="5"/></td>

</tr>
</table>

</div>
			
			<?php
				$indiceMaterial++;
			}
			?>
			
		</fieldset>


<div class='divBotones'>
<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form);'></center>
<input type='button' class='boton2' value='Cancelar' 
onClick="location.href='navegador_lotes.php'"></center>
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
			where g.estado=1 and cod_tipo=".$tipo." order by 2;";
			$respTipo=mysqli_query($enlaceCon,$sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysqli_fetch_array($respTipo)){
				$codGrupo=$datTipo[0];
				$nombreGrupo=$datTipo[1];
				echo "<option value=$codGrupo>$nombreGrupo</option>";
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
				<input type='text' name='itemNombreMaterial' id="itemNombreMaterial" class="texto" onkeypress="return pressEnter(event, this.form);">
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
<input type='hidden' name='materialActivo' value="0">
<input type='hidden' name='cantidad_material' value="0">
<input type='hidden' id='swCambiarPrecioVenta' name='swCambiarPrecioVenta' value="<?php echo obtenerValorConfiguracion($enlaceCon,7);?>">

</form>
</body>