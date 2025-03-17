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

function validar(){

	//alert ("La suma de Documentos que se desea pagar es distinto al Monto de Pago.");
	var montoTotalPago=0;
	var montoTotalaPagarDeuda=0;
	var frm = document.getElementById("form1");
	
	var montoTotalPago=0;
	var montoTotalaPagarDeuda=0;

	if(document.getElementById('montoTotalPago')){
		montoTotalPago=document.getElementById('montoTotalPago').innerHTML;
		montoTotalaPagarDeuda=document.getElementById('divMontoTotalaPagarDeuda').innerHTML;
		montoTotalPago=parseFloat(montoTotalPago);
		montoTotalPago=montoTotalPago.toFixed(2);

		montoTotalaPagarDeuda=parseFloat(montoTotalaPagarDeuda);
		montoTotalaPagarDeuda=montoTotalaPagarDeuda.toFixed(2);
	
		if(montoTotalPago!=montoTotalaPagarDeuda){
			alert ("La suma de Documentos que se desea pagar es distinto al Monto de Pago.");
			return (false);	
		}else{
		
	 		return (true);	
		}

	}else{

		alert ("No existe Documentos para realizar el Pago, selecccione otro Proveedor");
		return (false);		
	}	

}

function ajaxObligacionesxPagar(combo){
	var cod_proveedor=combo.value;
	
	var contenedor;
	contenedor = document.getElementById('div_obligacionesxpagar');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxObligacionesxPagar.php?cod_proveedor='+cod_proveedor+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function calcularTotalPago(){
	//alert("holaaa");
	var frm = document.getElementById("form1");
	var montoTotalPago=0;
	var i;
	for (i=0;i<frm.elements.length;i++){
		if((frm.elements[i].name).indexOf('montoTipoPago')!=-1){	
			montoTotalPago=montoTotalPago+parseFloat(frm.elements[i].value);
			montoTotalPago=montoTotalPago.toFixed(2);
			
		}
	}
		
	document.getElementById('montoTotalPago').innerHTML=montoTotalPago;
}

function sumarPagoDeudaLote(){
	//alert("holaaa");
	var j;
	var totalAuxiliar=0;
	var frm = document.getElementById("form1");

	for (j=0;j<frm.elements.length;j++){
		if((frm.elements[j].name).indexOf('docPagar')!=-1){
			if(frm.elements[j].checked){
				frm.elements[j+2].disabled=false;
				if(frm.elements[j+2].value){
					totalAuxiliar=totalAuxiliar+parseFloat(frm.elements[j+2].value);
					totalAuxiliar=totalAuxiliar.toFixed(2);
				}
			}else{
				frm.elements[j+2].disabled=true;
				frm.elements[j+2].value=0;
				

			}						
		}
	}
	document.getElementById('divMontoTotalaPagarDeuda').innerHTML=totalAuxiliar;
}

function checkearRegistros(){

	var frm = document.getElementById("form1");
	var j;
	var totalAuxiliar=0;
	if(frm.selectReg.checked){
		for (j=0;j<frm.elements.length;j++){
			if((frm.elements[j].name).indexOf('docPagar')!=-1){
				frm.elements[j].checked=true;
				frm.elements[j+2].value=frm.elements[j+1].value;
				totalAuxiliar=totalAuxiliar+parseFloat(frm.elements[j+1].value);
				totalAuxiliar=totalAuxiliar.toFixed(2);
			}
		}
		document.getElementById('divMontoTotalaPagarDeuda').innerHTML=totalAuxiliar;
	}else{

		for (j=0;j<frm.elements.length;j++){
			if((frm.elements[j].name).indexOf('docPagar')!=-1){
				frm.elements[j].checked=false;
				frm.elements[j+2].value=0;
				frm.elements[j+2].disabled=true;
			}
		}
		document.getElementById('divMontoTotalaPagarDeuda').innerHTML=0;

	}
	

}

function distribuirMontoTotalPago(){

	var frm = document.getElementById("form1");
	//alert(frm.name);
	
	var montoaDistribuir=0;
	var i;
	for (i=0;i<frm.elements.length;i++){
		if((frm.elements[i].name).indexOf('montoTipoPago')!=-1){	
			montoaDistribuir=montoaDistribuir+parseFloat(frm.elements[i].value);			
		}
	}
	document.getElementById('montoTotalPago').innerHTML=montoaDistribuir;
	

	var j;
	//var cadena="";
	var auxiliarMonto=0;
	for (j=0;j<frm.elements.length;j++){
		if((frm.elements[j].name).indexOf('docPagar')!=-1){
			if(frm.elements[j].checked){
				if(montoaDistribuir>=frm.elements[j+1].value){
					frm.elements[j+2].value=frm.elements[j+1].value;
					montoaDistribuir=montoaDistribuir-frm.elements[j+1].value;
					montoaDistribuir=montoaDistribuir.toFixed(2);

				}else{
					frm.elements[j+2].value=montoaDistribuir;
					montoaDistribuir=0;	
				}
				auxiliarMonto=auxiliarMonto+parseFloat(frm.elements[j+2].value);
				auxiliarMonto=auxiliarMonto.toFixed(2);
				
			}else{

				frm.elements[j+2].value=0;
			}						
		}
	}
	document.getElementById('divMontoTotalaPagarDeuda').innerHTML=auxiliarMonto;
	
}


</script>
<?php
require("conexionmysqli2.inc");
require('estilos.inc');
$fecha=date("Y-m-d");

?>
<form  action="guarda_pagoProv.php" method="post" name="form1" id="form1">

<h2>Pago a Proveedor</h2>
<center>

	<table class='texto'>
<tr>

<th align="left">Fecha</th>
<td align='left'>
<input type="date" class="texto"  id="fecha" name="fecha"  value="<?=$fecha;?>" >
</td>

</tr>
<tr>
<th>Proveedor</th>
<?php

$sql1="select p.cod_proveedor,p.nombre_proveedor,p.estado,p.cod_tipo 
        from proveedores p where p.estado=1 
 and cod_proveedor in(select distinct(cod_proveedor)
from lote_procesoconst lpc
left join lotes_produccion lp on ( lp.cod_lote=lpc.cod_lote)
left join procesos_construccion pc on ( lpc.cod_proceso_const=pc.cod_proceso_const)
left join material_apoyo ma on (lp.codigo_material=ma.codigo_material)
left join estados_lote el on (lp.cod_estado_lote=el.cod_estado)
where  lp.cod_estado_lote<>4
and lpc.obligacionxpagar_si_no=1
and  lpc.cod_estado_pago<>3

order by lpc.obligacionxpagar_fecha asc)
        order by p.nombre_proveedor asc";
		$resp1=mysqli_query($enlaceCon,$sql1);
?>
<td>
			<select name="codProveedor" id="codProveedor" required  onChange='ajaxObligacionesxPagar(this);'>
			<option value="0">Seleecione un Proveedor </option>
<?php		while($dat1=mysqli_fetch_array($resp1)){
				$cod_proveedor=$dat1['cod_proveedor'];
				$nombre_proveedor=$dat1['nombre_proveedor'];
?>
				<option value="<?=$cod_proveedor;?>"><?=$nombre_proveedor;?></option>
<?php			
		}
?>
			</select>
</td>

</tr>
<tr>
	<th align="left">Observacion</th>
<td align="left" >
	<input type="text" class="texto" name="observaciones" id="observaciones" size="100" 
	style="text-transform:uppercase;">
	</td>
</tr>
</table></center>
<div align="center" id="div_obligacionesxpagar" name="div_obligacionesxpagar">

</div>
<div class="divBotones">
<input type="submit" class="boton" value="Guardar" onClick=" return validar();">
<input type="button" class="boton2" value="Cancelar" 
onClick="location.href='listadoPagosProveedor.php'" >
</div>
</form>


