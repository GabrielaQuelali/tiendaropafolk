<?php
$indexGerencia=1;
require("conexionmysqli.php");
require("estilos_almacenes.inc");
require("funciones.php");

$tipo=$_GET['tipo'];
$estado=$_GET['estado'];
$cod_ingreso_almacen=$_GET['cod_ingreso_almacen'];
$global_almacen=$_COOKIE['global_almacen'];


$sql=" select count(*) from ingreso_detalle_almacenes where cod_ingreso_almacen=".$cod_ingreso_almacen;	
	//echo $sql;
$num_materiales=0;
$resp= mysqli_query($enlaceCon,$sql);				
while($dat=mysqli_fetch_array($resp)){	
		$num_materiales=$dat[0];
}
//echo "num_materiales=".$num_materiales;
?>
<html>
    <head>
        <title>MinkaSoftware</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="functionsGeneral.js"></script>				
		<script type='text/javascript' language='javascript'>


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

function listaMateriales(f){
    alert('num='+num);
	var contenedor;
	var codGrupo=f.codGrupo.value;
	var nombreItem=f.itemNombreMaterial.value;
	var tipo=f.tipo.value;
	var codMarca=f.itemMarca.value;
	var codBarraCod2=f.itemCodBarraCod2.value;
	contenedor = document.getElementById('divListaMateriales');

	var arrayItemsUtilizados=new Array();	
	
	var i=0;
	for(var j=1; j<=num; j++){
		if(document.getElementById('materiales'+j)!=null){
			//alert ('Hola1='+document.getElementById('materiales'+j).value);
			//console.log("codmaterial: "+document.getElementById('materiales'+j).value);
			arrayItemsUtilizados[i]=document.getElementById('materiales'+j).value;
			i++;
		}
	}
	//alert (arrayItemsUtilizados);
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxListaMateriales.php?codGrupo="+codGrupo+"&tipo="+tipo+"&codMarca="+codMarca+"&codBarraCod2="+codBarraCod2+"&nombreItem="+nombreItem+"&arrayItemsUtilizados="+arrayItemsUtilizados,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function ajaxTipoDoc(f){
	var contenedor;
	contenedor=document.getElementById("divTipoDoc");
	ajax=nuevoAjax();
	var codTipoSalida=(f.tipoSalida.value);
	ajax.open("GET", "ajaxTipoDoc.php?codTipoSalida="+codTipoSalida,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}


function ajaxPesoMaximo(codVehiculo){
	var contenedor;
	contenedor=document.getElementById("divPesoMax");
	ajax=nuevoAjax();
	var codVehiculo=codVehiculo;
	ajax.open("GET", "ajaxPesoMaximo.php?codVehiculo="+codVehiculo,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}

function ajaxNroDoc(f){
	var contenedor;
	contenedor=document.getElementById("divNroDoc");
	ajax=nuevoAjax();
	var codTipoDoc=(f.tipoDoc.value);
	var tipo=f.tipo.value;
	ajax.open("GET", "ajaxNroDoc.php?codTipoDoc="+codTipoDoc+"&tipo="+tipo,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}

function actStock(indice){
	var contenedor;
	contenedor=document.getElementById("idstock"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
    var codalm=document.getElementById("global_almacen").value;
	
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxStockSalidaMateriales.php?codmat="+codmat+"&codalm="+codalm+"&indice="+indice,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
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
function setMateriales(f, cod, nombreMat, stock_producto, precio_producto, precio_productomayor){
	var numRegistro=f.materialActivo.value;
	
	console.log("numRegistro: "+numRegistro);
	console.log("codigoproducto: "+cod);

	document.getElementById('materiales'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat;
	
	document.getElementById('precio_normal'+numRegistro).value=precio_producto;
	document.getElementById('precio_mayor'+numRegistro).value=precio_productomayor;
	

	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';
	
	document.getElementById("cantidad_unitaria"+numRegistro).focus();
	
	actStock(numRegistro);
}
function calculaMontoMaterial(){
	console.log('enter calcula monto');
}


function mas(obj) {


		//aca validamos que el item este seleccionado antes de adicionar nueva fila de datos
		var banderaItems0=0;
		for(var j=1; j<=num; j++){
			if(document.getElementById('materiales'+j)!=null){
				if(document.getElementById('materiales'+j).value==0){
					banderaItems0=1;
				}
			}
		}
		//fin validacion
		console.log("bandera: "+banderaItems0);
  
		if(banderaItems0==0){
			num++;
			cantidad_items++;
			console.log("num: "+num);
			console.log("cantidadItems: "+cantidad_items);
			fi = document.getElementById('fiel');
			contenedor = document.createElement('div');
			contenedor.id = 'div'+num;  
			fi.type="style";
			fi.appendChild(contenedor);
			var div_material;
			div_material=document.getElementById("div"+num);			
			ajax=nuevoAjax();
			ajax.open("GET","ajaxMaterialSalida.php?codigo="+num,true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4) {
					div_material.innerHTML=ajax.responseText;
					buscarMaterial(form1, num);
				}
			}		
			ajax.send(null);


		}


	
}
		
function menos(numero) {
	cantidad_items--;
	console.log("TOTAL ITEMS: "+num);
	console.log("NUMERO A DISMINUIR: "+numero);
	if(numero==num){
		num=parseInt(num)-1;
 	}
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
	console.log("numero de items: "+cantidadItems);
	var tipoSalida=document.getElementById("tipoSalida").value;
	var almacenSalida=document.getElementById("almacen").value;
	//alert(tipoSalida+"  almacen: "+almacenSalida);
	if(tipoSalida==1000){
		if(almacenSalida==0 || almacenSalida==""){
			alert("Debe seleccionar un almacen de destino.");
			return(false);
		}
	}

	if(cantidadItems>0){	
		var item="";
		var cantidad="";
		var stock="";
		var descuento="";
						
		for(var i=1; i<=cantidadItems; i++){
			console.log("valor i: "+i);
			console.log("objeto materiales: "+document.getElementById("materiales"+i));
			if(document.getElementById("materiales"+i)!=null){
				item=parseFloat(document.getElementById("materiales"+i).value);
				cantidad=parseFloat(document.getElementById("cantidad_unitaria"+i).value);
				stock=parseFloat(document.getElementById("stock"+i).value);
		
				console.log("materiales"+i+" valor: "+item);
				console.log("stock: "+stock+" cantidad: "+cantidad);

				if(item==0){
					alert("Debe escoger un item en la fila "+i);
					return(false);
				}		
				if(stock<cantidad){
					alert("No puede sacar cantidades mayores a las existencias. Fila "+i);
					return(false);
				}						
			}
		}
	}else{
		alert("El ingreso debe tener al menos 1 item.");
		return(false);
	}
}
	
	
</script>

		
<?php
echo "<body>";


$tipo=$_GET['tipo'];

if(isset($fecha)){
	$fecha=$fecha;
}else{
	$fecha="";
}


if($fecha=="")
{   $fecha=date("Y-m-d");
}
$fechaIni=date('Y-m-d',strtotime($fecha.'-5 days'));

$sql="select nro_correlativo from salida_almacenes where cod_almacen='$global_almacen' and cod_tipo=$tipo order by cod_salida_almacenes desc";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{   $codigo=1;
}
else
{   $codigo=$dat[0];
    $codigo++;
}

?>
<form action='guardarTraspasoDirecto.php' method='POST' name='form1'>
<input type='hidden' id='tipo' name='tipo'  value='<?php echo $tipo?>'>


<h1>Registrar Salida de Almacen</h1>

<table class='texto' align='center' width='90%'>
<tr><th>Tipo de Salida</th><th>Tipo de Documento</th><th>Nro. Salida</th><th>Fecha</th><th>Almacen Destino</th></tr>
<tr>
<td align='center'>
	<select name='tipoSalida' id='tipoSalida' onChange='ajaxTipoDoc(form1)' class="texto" required>
		<option value="">--------</option>
<?php
	$sqlTipo="select cod_tiposalida, nombre_tiposalida from tipos_salida where cod_tiposalida<>1001 order by 2";
	$respTipo=mysqli_query($enlaceCon,$sqlTipo);
	while($datTipo=mysqli_fetch_array($respTipo)){
		$codigo=$datTipo[0];
		$nombre=$datTipo[1];
?>
		<option value='<?php echo $codigo?>'><?php echo $nombre?></option>
<?php		
	}
?>
	</select>
</td>
<td align='center'>
	<div id='divTipoDoc'>
		<select name='tipoDoc' id='tipoDoc'><option value="0"></select>
	</div>
</td>
<td align='center'>
	<div id='divNroDoc' class='textogranderojo'>
	</div>
</td>

<td align='center'>
	<input type='date' class='texto' value='<?=$fecha;?>' id='fecha' name='fecha' min='<?=$fechaIni;?>' max='<?=$fecha;?>'>
</td>

<td align='center'>
	<select name='almacen' id='almacen' class='texto'>
		<option value=''>-----</option>
<?php
	$sql3="select cod_almacen, nombre_almacen from almacenes where cod_almacen not in ($global_almacen) order by nombre_almacen";
	$resp3=mysqli_query($enlaceCon,$sql3);
	while($dat3=mysqli_fetch_array($resp3)){
		$cod_almacen=$dat3[0];
		$nombre_almacen="$dat3[1] $dat3[2] $dat3[3]";
?>
		<option value="<?php echo $cod_almacen?>"><?php echo $nombre_almacen?></option>
<?php		
	}
?>
	</select>
</td>
</tr>

<tr>
	<th>Observaciones</th>
	<th align='center' colspan="4">
		<input type='text' class='texto' name='observaciones' value='' size='100' rows="2">
	</th>
</tr>
</table>

<br>
<div class="codigo-barras div-center">
               <input type="text" class="form-codigo-barras" id="input_codigo_barras" placeholder="Ingrese el código de barras." autofocus autocomplete="off">
</div>

<fieldset id="fiel" style="width:100%;border:0;">
	<table align="center" class="texto" width="80%" border="0" id="data0" style="border:#ccc 1px solid;">
	<tr>
		<td align="center" colspan="9">
			<b>Detalle de la Transaccion   </b><input class="boton" type="button" value="Agregar (+)" onclick="mas(this)" />
		</td>
	</tr>
	<tr align="center">
		<th width="10%">-</th>
		<th width="40%">Producto</th>
		<th width="10%">Stock</th>
		<th width="10%">Cantidad</th>
		<th width="10%">Precio</th>
		<th width="10%">Precio por Mayor</th>
		<th width="10%">&nbsp;</th>
	</tr>
	</table>

<?php

	$sqlIng=" select ida.cod_material,ma.descripcion_material, ida.cantidad_unitaria,ida.cantidad_restante,ida.lote, ";
	$sqlIng=$sqlIng." ida.precio_bruto,ida.costo_almacen, ida.costo_actualizado,ida.costo_actualizado_final,ida.costo_promedio,";
	$sqlIng=$sqlIng." ida.fecha_vencimiento,ida.precio_neto,ida.precio_venta,ida.precio_venta2,ida.orden_detalle";
	$sqlIng=$sqlIng." FROM ingreso_detalle_almacenes ida ";
	$sqlIng=$sqlIng." left join material_apoyo ma on (ida.cod_material=ma.codigo_material) ";
	$sqlIng=$sqlIng." where ida.cod_ingreso_almacen='".$cod_ingreso_almacen."' order by ida.orden_detalle asc";

	//echo $sqlIng;

	$indiceMaterial=1;
	$respIng=mysqli_query($enlaceCon,$sqlIng);
	while($datIng=mysqli_fetch_array($respIng)){
		$cod_material=$datIng['cod_material'];
		$descripcion_material=$datIng['descripcion_material'];
		$cantidad_unitaria=$datIng['cantidad_unitaria'];
		$cantidad_restante=$datIng['cantidad_restante'];
		$lote=$datIng['lote'];
		$precio_bruto=$datIng['precio_bruto'];
		$costo_almacen=$datIng['costo_almacen'];
		$costo_actualizado=$datIng['costo_actualizado'];
		$costo_actualizado_final=$datIng['costo_actualizado_final'];
		$costo_promedio=$datIng['costo_promedio'];
		$fecha_vencimiento=$datIng['fecha_vencimiento'];
		$precio_neto=$datIng['precio_neto'];
		$precio_venta=$datIng['precio_venta'];
		$precio_venta2=$datIng['precio_venta2'];
		$orden_detalle=$datIng['orden_detalle'];
		$stockProducto=stockProductoIngreso($enlaceCon,$global_almacen,$cod_material,$cod_ingreso_almacen);
		$num=$indiceMaterial;
	
	?>

<div id="div<?=$num;?>">

<table  border="0" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;" id="data<?=$num;?>" >
<tr bgcolor="#FFFFFF">
<td width="10%" align="center">
	<?=$num;?>
	<a href="javascript:buscarMaterial(form1, <?=$num;?>)"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>
<td width="40%" align="center">

	<input type="hidden" name="materiales<?=$num;?>" id="materiales<?=$num;?>" value="<?=$cod_material;?>">
	<div id="cod_material<?=$num;?>" class='textomedianonegro'><?=$descripcion_material;?></div>
</td>

<td width="10%" align="center">
	<div id="idstock<?php echo $num;?>">
		
		<input type='text' id='stock<?php echo $num;?>' name='stock<?php echo $num;?>' value='<?=$stockProducto;?>' readonly size='4'>
		
	</div>
</td>

<td align="center" width="10%">
	<input class="inputnumber" type="number" value="<?=$cantidad_unitaria;?>" min="0.01" id="cantidad_unitaria<?=$num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' name="cantidad_unitaria<?=$num;?>" onChange='calculaMontoMaterial(<?=$num;?>);' step="0.01" required> 
</td>

<td align="center" width="10%">
	<input class="inputnumber" type="number" value="<?=$precio_venta?>" min="0.01" id="precio_normal<?=$num;?>" 
	name="precio_normal<?=$num;?>" step="0.01" required> 
</td>

<td align="center" width="10%">
	<?=$precio_venta2;?>
	<input class="inputnumber" type="number" value="<?=$precio_venta2?>" min="0.01" id="precio_mayor<?=$num;?>" 
	name="precio_mayor<?=$num;?>" step="0.01" required> 
</td>

<td align="center"  width="10%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" /></td>

</tr>
</table>

</div>
<?php
	$indiceMaterial++;
	
	}
?>
</fieldset>

	<script>
		num=<?=$num_materiales;?>;
		cantidad_items=<?=$num_materiales;?>;
		console.log('valores num='+num)
	</script>

	<div class="divBotones">
		<input type="submit" class="boton" value="Guardar" onClick="return validar(this.form);">
		<input type="button" class="boton2" value="Cancelar"
		 onClick="location.href='navegador_ingresomateriales.php?tipo=<?=$tipo;?>&estado=<?=$estado;?>'">
	</div>


<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:1100px; height: 500px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2; overflow: auto;">
</div>

<div id="divboton" style="position: absolute; top:20px; left:1220px;visibility:hidden; text-align:center; z-index:3">
	<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
</div>

<div id="divProfileData" style="background-color:#FFF; width:1050px; height:450px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2; overflow: auto;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<table align='center'>
			<tr><th>Grupo</th><th>Marca</th><th>Cod.Barra/Cod.Prov</th><th>Material</th><th>&nbsp;</th></tr>
			<tr>
			<td><select class="texto" name='codGrupo' id='codGrupo' style="width:120px">
			<?php
			$sqlTipo="select g.codigo, g.nombre from grupos g
			where g.estado=1 order by 2;";
			echo $sqlTipo;
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
				<input type='text' name='itemNombreMaterial' id='itemNombreMaterial' style="width:180px" class="texto" onkeypress="return pressEnter(event, this.form);">
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

<input type='hidden' id='totalmat' value='<?=$cantidad_items;?>'>
<input type='hidden' id='codalmacen' value='<?=$global_almacen;?>'>
<input type='hidden' id='global_almacen' value='<?=$global_almacen;?>'>

<input type='hidden' name='materialActivo' value="0">
<input type='hidden' name='cantidad_material' value="0">
<input type='hidden' id='cod_ingreso_almacen' name='cod_ingreso_almacen' value='<?=$cod_ingreso_almacen;?>'>


<input type='hidden' name='no_venta' value="1">

</form>
</body>
</html>