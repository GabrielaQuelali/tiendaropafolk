<?php

require("conexionmysqli.inc");
require("estilos_almacenes.inc");
require("funciones.php");

$globalAlmacen=$_COOKIE['global_almacen'];
$globalAgencia=$_COOKIE['global_agencia'];

if($fecha=="")
{   $fecha=date("d/m/Y");
}
//echo "holaaa";
	
	$codIngresoEditar=$_GET["codIngreso"];
	$sql=" select count(*) from preingreso_detalle_almacenes where cod_ingreso_almacen=".$codIngresoEditar;	
	//echo $sql;
	$num_materiales=0;
	$resp= mysqli_query($enlaceCon,$sql);				
	while($dat=mysqli_fetch_array($resp)){	
		$num_materiales=$dat[0];
	}
	//echo "num materiales".$num_materiales;
?>
<html>
    <head>
        
<script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="dlcalendar.js"></script>
<script type='text/javascript' language='javascript'>

num=<?php echo $num_materiales;?>;
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
	var codTipo=f.itemTipoMaterial.value;
	var nombreItem=f.itemNombreMaterial.value;
	contenedor = document.getElementById('divListaMateriales');
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxListaMaterialesIngreso.php?codTipo="+codTipo+"&nombreItem="+nombreItem,true);
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
function setMateriales(f, cod, nombreMat, cantidadPresentacion,costoItem){
	var numRegistro=f.materialActivo.value;
	
	document.getElementById('material'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat;
	document.getElementById('ultimoCosto'+numRegistro).value=costoItem;
	document.getElementById('divUltimoCosto'+numRegistro).innerHTML="["+costoItem+"]";
	
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
	console.log(cantidad+" "+ultimoCosto);
	var calculoCosto=parseFloat(cantidad)*parseFloat(ultimoCosto);
	var calculoPrecioTotal=parseFloat(cantidad)*parseFloat(precioFila);	
	if(calculoCosto=="NaN"){
		calculoCosto.value=0;
	}
	if(document.getElementById('swCambiarPrecioVenta').value==1){
	  	document.getElementById('precioVenta'+fila).value=precioFila; 
	}
	document.getElementById('divUltimoCosto'+fila).innerHTML="["+ultimoCosto+"]["+calculoCosto+"]";
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
	//alert("menos");
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

<form action='guarda_editarpreingresomateriales.php' method='post' name='form1'>
<input type="hidden" name="codIngreso" value="<?php echo $codIngresoEditar;?>" id="codIngreso">
<center><table border='0' class='textotit' align='center'><tr><th>Editar Pre Ingreso</th></tr></table></center><br>

<?php

$sqlIngreso="select i.`nro_correlativo`, i.`fecha`, i.`cod_tipoingreso`, i.`nota_entrega`, i.`nro_factura_proveedor`, 
		i.`observaciones`,i.`cod_proveedor` , p.nombre_proveedor
		from `preingreso_almacenes` i 
		left join proveedores p on(i.cod_proveedor=p.cod_proveedor)
		where i.`cod_ingreso_almacen` = $codIngresoEditar" ;
$respIngreso=mysqli_query($enlaceCon,$sqlIngreso);
while($datIngreso=mysqli_fetch_array($respIngreso)){
	$nroCorrelativo=$datIngreso[0];
	$fechaIngreso=$datIngreso[1];
	$codTipoIngreso=$datIngreso[2];
	$notaEntrega=$datIngreso[3];
	$nroFacturaProv=$datIngreso[4];
	$obsIngreso=$datIngreso[5];
	$codProveedor=$datIngreso[6];
	$nombreProveedor=$datIngreso[7];
	
}

?>
<table border='0' class='texto' cellspacing='0' align='center' width='90%' style='border:#ccc 1px solid;'>
<tr><th>Numero de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Factura</th></tr>
<tr>
	<td align='center'><?php echo $nroCorrelativo?></td>
	<td align='center'>
	<input type="text" disabled="true" class="texto" value="<?php echo $fechaIngreso;?>" id="fecha" size="10" name="fecha">
	<img id='imagenFecha' src='imagenes/fecha.bmp'>
	</td>
	
<?php
$sql1="select cod_tipoingreso, nombre_tipoingreso from tipos_ingreso order by nombre_tipoingreso";
$resp1=mysqli_query($enlaceCon,$sql1);
?>

<td align='center'><select name='tipo_ingreso' id='tipo_ingreso' class='texto'>

<?php

while($dat1=mysqli_fetch_array($resp1))
{   $cod_tipoingreso=$dat1[0];
    $nombre_tipoingreso=$dat1[1];
?>
    <option value="<?php echo $cod_tipoingreso; ?>" <?php if($cod_tipoingreso==$codTipoIngreso){echo "selected";}?>"><?php echo $nombre_tipoingreso;?></option>
<?php
}
?>
</select></td>
<td align="center"><input type="text" class="texto" name="nro_factura" value="<?php echo $nroFacturaProv; ?>" id="nro_factura"></td></tr>
<tr><th >Proveedor</th><th colspan="3">Observaciones</th></tr>
<tr>
<td align='center'><?php echo $nombreProveedor; ?></td>
<td colspan="4" align="center"><input type="text" class="texto" name="observaciones" value="<?php echo $obsIngreso; ?>" size="100"></td></tr>
</table><br>

		<fieldset id="fiel" style="width:98%;border:0;" >
			<table align="center"class="text" cellSpacing="1" cellPadding="2" width="100%" border="0" id="data0" style="border:#ccc 1px solid;">
				<tr>
					<td align="center" colspan="6">
						<input class="boton" type="button" value="Nuevo Item (+)" onclick="mas(this)" />
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
					<td width="10%" align="center">P. Compra[u]</td>
					<td width="10%" align="center">P. Venta[u]</td>
					<td width="10%" align="center">PrecioTotal</td>
					<td width="10%" align="center">&nbsp;</td>
				</tr>
			</table>
			
			<?php
			$sqlDetalle="select id.`cod_material`, m.`descripcion_material`, id.`cantidad_unitaria`, id.`precio_bruto`, id.`precio_neto`, 
				lote, fecha_vencimiento,m.codigo2, m.color,m.talla, mar.nombre as nombreMarca, id.precio_venta
				from `preingreso_detalle_almacenes` id, 
				`material_apoyo` m
				left join marcas mar on (m.cod_marca= mar.codigo)
				where
				id.`cod_material`=m.`codigo_material` and id.`cod_ingreso_almacen`='$codIngresoEditar' order by 2";
			$respDetalle=mysqli_query($enlaceCon,$sqlDetalle);
			$indiceMaterial=1;
			while($datDetalle=mysqli_fetch_array($respDetalle)){
				$codMaterial=$datDetalle[0];
				$nombreMaterial=$datDetalle[1];
				$cantidadMaterial=$datDetalle[2];
				$precioBruto=$datDetalle[3];
				$precioNeto=$datDetalle[4];
				$loteMaterial=$datDetalle[5];
				$fechaVencimiento=$datDetalle[6];
				$codigo2=$datDetalle[7];
				$color=$datDetalle[8];
				$talla=$datDetalle[9];
				$nombreMarca=$datDetalle[10];
				$precioVenta=$datDetalle[11];
				$num=$indiceMaterial;
				/// SACAMOS PRECIO DE VENTA
				
					$sqlPrecio="select p.`precio` from `precios` p where p.`cod_precio`=1 and p.`codigo_material`=$codMaterial and p.cod_ciudad='".$global_agencia."'";
					//echo $sqlPrecio;
					$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
					$numFilasPrecio=mysqli_num_rows($respPrecio);
					if($numFilasPrecio==1){
						$datPrecio=mysqli_fetch_array($respPrecio);
						$precio0=$datPrecio[0];
						
						$precio0=redondear2($precio0);
					}else{
						$precio0=0;
						$precio0=redondear2($precio0);
					}
			///////////////
				//SACAMOS EL PRECIO
				$sqlUltimoCosto=" select id.precio_bruto
				from preingreso_almacenes i, preingreso_detalle_almacenes id
				where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and 
				id.cod_material='$codMaterial' and i.cod_almacen='$globalAlmacen' 
				ORDER BY i.cod_ingreso_almacen desc limit 0,1";
				$respUltimoCosto=mysqli_query($enlaceCon,$sqlUltimoCosto);
				$numFilas=mysqli_num_rows($respUltimoCosto);
				$costoItem=0;
				if($numFilas>0){
					$datUltimoCosto=mysqli_fetch_array($respUltimoCosto);
					$costoItem=$datUltimoCosto[0];
					//$costoItem=mysql_result($respUltimoCosto,0,0);
				}else{
					//SACAMOS EL COSTO REGISTRADO EN LA TABLA DE PRECIOS
					$sqlCosto="select p.`precio` from precios p where p.`codigo_material`='$codMaterial' and p.`cod_precio`='0' 
					and cod_ciudad='$globalAgencia'";
					$respCosto=mysqli_query($enlaceCon,$sqlCosto);
					$numFilas2=mysqli_num_rows($respCosto);
					if($numFilas2>0){
						$datCosto=mysqli_fetch_array($respCosto);
					    $costoItem=$datCosto[0];
						//$costoItem=mysql_result($respCosto,0,0);
					}
				}
			?>

<div id="div<?php echo $num?>">

<table border="0" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="5%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)" accesskey="B"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>

<td width="35%" align="left">
<input type="hidden" name="material<?php echo $num;?>" id="material<?php echo $num;?>" value="<?=$codMaterial;?>">
<div id="cod_material<?php echo $num;?>" class='textoform'><strong><?=$codigo2;?></strong> <?=$nombreMaterial;?> <?=$color;?> <?=$talla;?><strong><? echo " (".$nombreMarca.")";?></strong></div>
</td>

<td align="center" width="10%">
<input type="number" class="inputnumber" min="1" max="1000000" id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" size="5"  value="<?=$cantidadMaterial;?>" step="0.01" onchange='cambiaCosto(this.form,<?php echo $num;?>)' onkeyup='cambiaCosto(this.form,<?php echo $num;?>)' required>
</td>

<td align="center" width="10%">
<input type="text" class="textoform" id="lote<?php echo $num;?>" name="lote<?php echo $num;?>" size="10" value="<?php echo $loteMaterial;?>" required>
</td>

<td align="center" width="10%">
<input type="number" class="inputnumber" value="<?=$precioBruto;?>" id="precio<?=$num;?>" name="precio<?=$num;?>" size="5" min="0" step="0.01"  
onchange='cambiaCosto(this.form,<?=$num;?>)' onkeyup='cambiaCosto(this.form,<?=$num;?>)' required><br>
<input type="hidden" id='ultimoCosto<?php echo $num;?>' name='ultimoCosto<?php echo $num;?>' value='<?=$costoItem;?>'>
<div id='divUltimoCosto<?php echo $num;?>'><?=$precioBruto*$cantidadMaterial;?></div>
</td>
<td align="center" width="10%">
<input type="number" class="inputnumber" value="<?=$precioVenta;?>" id="precioVenta<?php echo $num;?>" name="precioVenta<?php echo $num;?>" size="5" min="0.1" step="0.01"  
 <?php if (obtenerValorConfiguracion($enlaceCon,7)==0){  echo "readonly";}?>  required><br>
<div id='divPVenta<?php echo $num;?>'><?=$precio0?></div>
</td>
<td align="center" width="10%">
<div id='divPrecioTotal<?php echo $num;?>'><?=$precioBruto*$cantidadMaterial;?></div>
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


<?php

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form);'></center>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_preingreso.php\"'></center>
</div>";
?>


<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 500px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divboton" style="position: absolute; top:20px; left:920px;visibility:hidden; text-align:center; z-index:3">
	<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
</div>


<div id="divProfileData" style="background-color:#FFF; width:750px; height:450px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center; height:445px; overflow-y: scroll;">
		<table align='center' class="texto">
			<tr><th>Linea</th><th>Material</th><th>&nbsp;</th></tr>
			<tr>
			<td><select name='itemTipoMaterial' id="itemTipoMaterial" class="textogranderojo" style="width:300px">
			<?php
			$sqlTipo="select g.cod_grupo, g.nombre_grupo from grupos g
			where g.estado=1 order by 2;";
			$respTipo=mysqli_query($enlaceCon,$sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysqli_fetch_array($respTipo)){
				$codTipoMat=$datTipo[0];
				$nombreTipoMat=$datTipo[1];
				echo "<option value=$codTipoMat>$nombreTipoMat</option>";
			}
			?>
			</select>
			</td>
			<td>
				<input type='text' name='itemNombreMaterial' id="itemNombreMaterial" class="textogranderojo" onkeypress="return pressEnter(event, this.form);">
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