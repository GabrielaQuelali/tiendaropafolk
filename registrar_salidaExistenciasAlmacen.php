<?php
$indexGerencia=1;
require_once 'conexionmysqli.inc';
require_once 'estilos_almacenes.inc';


$global_almacen=$_COOKIE['global_almacen'];
$global_ciudad=$_COOKIE['global_agencia'];

?>
<html>
    <head>
        <title>Salidas de Productos</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <!--script type="text/javascript" src="lib/js/xlibPrototipoSimple-v0.1.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script-->
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



function mostrarItems(f){
    //alert('holaaaa');
	var contenedor;

	var tipo=f.tipo.value;
	var global_almacen=f.global_almacen.value;
	var fecha=f.fecha.value;
	 var global_ciudad=f.global_ciudad.value;


	contenedor = document.getElementById('divProductos');
	
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxListaProductosIngreso.php?tipo="+tipo+"&global_almacen="+global_almacen+"&fechaNotaRemision="+fecha+"&global_ciudad="+global_ciudad,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}


function ajaxNroDoc(f){
	var contenedor;
	contenedor=document.getElementById("divNroDoc");
		var tipo=f.tipo.value;
	ajax=nuevoAjax();
	var codTipoDoc=(f.tipoDoc.value);
	ajax.open("GET", "ajaxNroDoc.php?codTipoDoc="+codTipoDoc+"&tipo="+tipo,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null);
}






function calculaMontoMaterial(){
	console.log('enter calcula monto');
}

num=0;
cantidad_items=0;

function calculaTotal(f,indice){

document.getElementById("total"+indice).value=document.getElementById("cantidad_venta"+indice).value*document.getElementById("precio_venta"+indice).value;
 calculaTotalGeneral(f);
}

function calculaTotalGeneral(f){

	var cantidad=0;
	var precio=0;
	var i=1;
	var cantidadItems=(f.cantidad_material.value)-1;
	var isChecked;
	var precioTotal=0;

	if(cantidadItems>0){		
		for( i=1; i<=cantidadItems; i++){
	
         	isChecked = document.getElementById('codigoMaterial'+i).checked;
         	if(isChecked) {			
				cantidad=parseFloat(document.getElementById("cantidad_venta"+i).value);
				precio=parseFloat(document.getElementById("precio_venta"+i).value);
				precioTotal=precioTotal+parseFloat(cantidad*precio);	
				//alert ("precioTotal"+precioTotal)					;
			}
	}	

	}
		
	document.getElementById('totalGeneral').value=precioTotal;
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

if(isset($_GET['fecha'])){

	$fecha=$_GET['fecha'];

}else{

	$fecha=date("Y-m-d");
}

echo "fecha".$fecha."<br/>";


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
<form action='guardarSalidaExistenciasAlmacen.php' method='POST' name='form1'>
<input type='hidden' id='global_almacen' name='global_almacen'  value='<?php echo $global_almacen?>'>
<input type='hidden' id='global_ciudad' name='global_ciudad'  value='<?php echo $global_ciudad?>'>	
<input type='hidden' id='tipo' name='tipo'  value='<?php echo $tipo?>'>

<h1>Registrar Salida de Productos de las Existencias de Almacen</h1>

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

<input type="date" class="texto"  id="fecha" name="fecha"  value="<?=$fecha;?>" onChange="mostrarItems(this.form)" >
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
<fieldset id="fiel" style="width:100%;border:0;">
	<table align="center" class="texto" width="80%" border="0" id="data0" style="border:#ccc 1px solid;">
	<tr align="center">
		<th width="5%">-</th>
		<th width="35%">Producto</th>
		<th width="12%">Stock</th>
		<th width="12%">Cantidad </th>
		<th width="12%">Precio</th>
		<th width="12%">Precio Destino</th>
		<th width="12%">Total</th>
	</tr>
	</table>
<div id="divProductos">
<table  border="0" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;">
<?php	
	$sqlIng=" select id.cod_material,sum(id.cantidad_unitaria)  as  cantIngreso ";
	$sqlIng.=" from ingreso_almacenes i ";
	$sqlIng.=" left join  ingreso_detalle_almacenes id on (i.cod_ingreso_almacen=id.cod_ingreso_almacen) ";
	$sqlIng.=" where i.fecha between '2020-11-20 00:00:00' and '".$fecha." 23:59:59' ";
	$sqlIng.=" and i.cod_almacen='".$global_almacen."' and i.ingreso_anulado=1 ";
	$sqlIng.=" and i.cod_tipo='".$tipo."'";
	$sqlIng.=" group by id.cod_material asc";

	//echo $sqlIng;
	$indiceMaterial=1;
	$respIng=mysqli_query($enlaceCon,$sqlIng);
	while($datIng=mysqli_fetch_array($respIng)){

		$codigo_material=$datIng['cod_material'];
		$cantIngreso=$datIng['cantIngreso'];
		$sqlProducto="select  descripcion_material from material_apoyo where codigo_material=".$codigo_material;
		$respProducto=mysqli_query($enlaceCon,$sqlProducto);
		while($datProducto=mysqli_fetch_array($respProducto)){
			$descripcion_material=$datProducto['descripcion_material'];
		}
		$sql_salidas="select sum(sd.cantidad_unitaria) as cantSalida from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen 
			and s.fecha between '2020-11-20 00:00:00' and '".$fecha."' and s.cod_almacen='".$global_almacen."'
			and sd.cod_material='".$codigo_material."' and s.salida_anulada=1";
			
			//echo $sql_salidas;
			
			$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
			$dat_salidas=mysqli_fetch_array($resp_salidas);
			$cantSalida=$dat_salidas['cantSalida'];
			$stockProducto=$cantIngreso-$cantSalida;

			$consulta="select p.`precio` from precios p where p.`codigo_material`='".$codigo_material."' and p.`cod_precio`='1' and cod_ciudad='".$global_ciudad."'";
			//echo $consulta;
			$precio=0;
			$rs=mysqli_query($enlaceCon,$consulta);
			$registro=mysqli_fetch_array($rs);
			if(mysqli_num_rows($rs)>0){
					$precio=$registro[0];
			}

		$num=$indiceMaterial;	
	if($stockProducto>0){
	?>

<tr bgcolor="#FFFFFF">
<td width="5%" align="center">
<?=$num;?>
	<input type='checkbox' id='codigoMaterial<?php echo $num;?>' name='codigoMaterial<?php echo $num;?>' value='<?=$codigo_material;?>' onChange='calculaTotalGeneral(this.form);' >
</td>
<td width="35%" align="center"><?=$descripcion_material?></td>
<td width="12%" align="center">
		<input type='text' id='stock<?php echo $num;?>' name='stock<?php echo $num;?>' value='<?=$stockProducto;?>' readonly size='4'>
</td>
<td align="center" width="12%">
	<input class="inputnumber" type="number" value="0"  id="cantidad_venta<?=$num;?>" name="cantidad_venta<?=$num;?>" onChange='calculaTotal(this.form,<?=$num;?>);' onKeyUp='calculaTotal(this.form,<?=$num;?>);'  required > 
</td>
<td align="center" width="12%">
	<input class="inputnumber" type="number" value="<?=$precio;?>" min="0.01" id="precio<?=$num;?>" 
	name="precio<?=$num;?>" step="0.01" readonly> 
</td>

<td align="center" width="12%">
	<input class="inputnumber" type="number" value="<?=$precio;?>"  id="precio_venta<?=$num;?>" 
	name="precio_venta<?=$num;?>" onChange='calculaTotal(this.form,<?=$num;?>);' onKeyUp='calculaTotal(this.form,<?=$num;?>);'  required> 
</td>
<td align="center" width="12%">
	<input class="inputnumber" type="number" value="0" id="total<?=$num;?>" 
	name="total<?=$num;?>"  readonly> 
</td>

</tr>

<?php
	$indiceMaterial++;
	}
}
?>
<tr>
	<td colspan="6" align="right">TOTAL</td><td align="center" width="12%">
	<input class="inputnumber" type="number" value="0" id="totalGeneral" 
	name="totalGeneral"  readonly> 
</td></tr>
</table>
<input type="hidden" name="cantidad_material" id="cantidad_material" value="<?=$indiceMaterial;?>">
</div>

</fieldset>



<?php

echo "<div class='divBotones'>
	<input type='submit' class='boton' value='Guardar' onClick='return validar(this.form);'>
	<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_salidamateriales.php\"'>
</div>";

echo "</div>";
echo "<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>





<input type='hidden' id='totalmat' value='<?=$cantidad_material;?>'>

<input type='hidden' name='cantidad_material' value="0">



</form>
</body>