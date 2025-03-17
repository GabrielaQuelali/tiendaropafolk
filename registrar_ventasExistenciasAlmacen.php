<?php
$indexGerencia=1;
require("conexionmysqli.php");
require("estilos_almacenes.inc");
require("funciones.php");

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


function mostrarItems(f){
    //alert('holaaaa');
	var contenedor;

	var tipo=f.tipo.value;
	var global_almacen=f.global_almacen.value
	var fechaNotaRemision=f.fechaNotaRemision.value
	 var global_ciudad=f.global_ciudad.value

	contenedor = document.getElementById('divProductos');

	ajax=nuevoAjax();
	ajax.open("GET", "ajaxListaProductosIngreso.php?tipo="+tipo+"&global_almacen="+global_almacen+"&fechaNotaRemision="+fechaNotaRemision+"&global_ciudad="+global_ciudad,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
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
	var cantidad=0;
		var stock=0;
		var sw=0;
		var i=1;
	var cantidadItems=f.cantidad_material.value;
	cantidadItems=cantidadItems-1;
var isChecked;


		if(cantidadItems>0){		
		for( i=1; i<=cantidadItems; i++){
	
         	isChecked = document.getElementById('codigoMaterial'+i).checked;
         	if(isChecked) {
				sw=1;			
				cantidad=parseFloat(document.getElementById("cantidad_venta"+i).value);
				stock=parseFloat(document.getElementById("stock"+i).value);
				if(cantidad==0){
					alert("La cantidad de Venta de la Fila "+i+" es 0. ");
					return(false);
				}
				if(stock<cantidad){
					alert("No puede sacar cantidades mayores a las existencias. revisar Fila "+i);
					return(false);
				}						
			}
			

		}
		if(sw==0){
			alert("Debe seleccionar al menos 1 item.");
		return(false);
		}
	}else{
		alert("La Venta debe tener al menos 1 Producto.="+cantidadItems);
		return(false);
	}
}
	
	
</script>
<body>
		
<?php

$tipo=1; // Salida de Productos 

$global_almacen=$_COOKIE['global_almacen'];
$global_ciudad=$_COOKIE['global_agencia'];

$sql="select ifnull(max(nro_correlativo)+1,1) from salida_almacenes 
where cod_tipo_doc=2 and cod_almacen='".$global_almacen."' and cod_tipo='".$tipo."'";
//echo $sql;
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$nroCorrelativo=$dat[0];
$fechaNotaRemision=$fecha=date("Y-m-d");


?>
<form action='guardarVentaExistenciasAlmacen.php' method='POST' name='form1'>
<input type='hidden' id='tipo' name='tipo'  value='<?php echo $tipo?>'>
<input type='hidden' id='global_almacen' name='global_almacen'  value='<?php echo $global_almacen?>'>
<input type='hidden' id='global_ciudad' name='global_ciudad'  value='<?php echo $global_ciudad?>'>
<input type='hidden' id='tipoDoc' name='tipoDoc'  value='2'>
<input type='hidden' id='tipoSalida' name='tipoSalida'  value='1001'>


<h1>Registrar Ventas de las Existencias de Almacen</h1>

<table class='texto' align='center' width='90%'>
<tr><th>Tipo de Salida</th><th>Tipo de Documento</th><th>Nro. Salida</th><th>Fecha</th><th>Tipo de Pago</th></tr>
<tr>
<td align='center'>
	
		
<?php
	$sqlTipo="select cod_tiposalida, nombre_tiposalida from tipos_salida where cod_tiposalida=1001 order by 2";
	$respTipo=mysqli_query($enlaceCon,$sqlTipo);
	while($datTipo=mysqli_fetch_array($respTipo)){
		$codigoTipoSalida=$datTipo[0];
		$nombreTipoSalida=$datTipo[1];
	}

		
?>
		
<?=$nombreTipoSalida;?>

</td>
<td align='center'>
	<?php

$sql="select codigo, nombre, abreviatura from tipos_docs where codigo=2 ";
$resp=mysqli_query($enlaceCon,$sql);

while($dat=mysqli_fetch_array($resp)){
	$codigoTipoDoc=$dat[0];
	$nombreTipoDoc=$dat[1];
}
	?>
	<?=$nombreTipoDoc;?>
</td>
<td align='center'>
	 <?=$nroCorrelativo;?>
</td>

<td align="center">
	<input type="date" class="texto"  id="fechaNotaRemision" name="fechaNotaRemision"  value="<?=$fechaNotaRemision;?>"
	onChange="mostrarItems(this.form)">
</td>

<td align='center'>
	<select name="tipoPago" id="tipoPago" class="selectpicker" data-style="btn btn-warning" >
<?php	
	$sqlTipoPago="select cod_tipopago, nombre_tipopago from tipos_pago where estado=1  order by cod_tipopago asc";
	$respTipoPago=mysqli_query($enlaceCon,$sqlTipoPago);
	while($datTipoPago=mysqli_fetch_array($respTipoPago))
	{	
?>
<?php	$codTipopago=$datTipoPago[0];
		$nombreTipopago=$datTipoPago[1];
	?>
		<option value="<?=$codTipopago;?>" ><?=$nombreTipopago;?></option>
		
<?php	}?>
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

<fieldset id="fiel" style="width:100%;border:0;">
	<table align="center" class="texto" width="80%" border="0" id="data0" style="border:#ccc 1px solid;">
	<tr align="center">
		<th width="5%">-</th>
		<th width="35%">Producto</th>
		<th width="12%">Stock</th>
		<th width="12%">Cantidad Venta</th>
		<th width="12%">Precio</th>
		<th width="12%">Precio Venta</th>
		<th width="12%">Total</th>
	</tr>
	</table>
<div id="divProductos">
<table  border="0" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;">
<?php	
	$sqlIng=" select id.cod_material,sum(id.cantidad_unitaria)  as  cantIngreso ";
	$sqlIng.=" from ingreso_almacenes i ";
	$sqlIng.=" left join  ingreso_detalle_almacenes id on (i.cod_ingreso_almacen=id.cod_ingreso_almacen) ";
	$sqlIng.=" where i.fecha between '2020-11-20 00:00:00' and '".$fechaNotaRemision." 23:59:59' ";
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
			and s.fecha between '2020-11-20 00:00:00' and '".$fechaNotaRemision."' and s.cod_almacen='".$global_almacen."'
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


	<div class="divBotones">
		<input type="submit" class="boton" value="Guardar" onClick="return validar(this.form);">
		<input type="button" class="boton2" value="Cancelar"
		 onClick="location.href='navegadorVentas2.php'">
	</div>



</form>
</body>
</html>