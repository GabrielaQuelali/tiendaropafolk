<?php

require("conexionmysqli.php");
require("estilos_almacenes.inc");
require("funciones.php");


?>
<html>
    <head>
        <title>Busqueda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="lib/js/xlibPrototipoSimple-v0.1.js"></script>
		<script type="text/javascript" src="functionsGeneral.js"></script>
<script type='text/javascript' language='javascript'>
function funcionInicio(){
	//document.getElementById('nitCliente').focus();
}


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
	var contenedor;
	var codTipo=f.itemTipoMaterial.value;
	var nombreItem=f.itemNombreMaterial.value;
	contenedor = document.getElementById('divListaMateriales');

	var arrayItemsUtilizados=new Array();	
	var i=0;
	for(var j=1; j<=num; j++){
		if(document.getElementById('materiales'+j)!=null){
			console.log("codmaterial: "+document.getElementById('materiales'+j).value);
			arrayItemsUtilizados[i]=document.getElementById('materiales'+j).value;
			i++;
		}
	}
	
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxListaMateriales.php?codTipo="+codTipo+"&nombreItem="+nombreItem+"&arrayItemsUtilizados="+arrayItemsUtilizados,true);
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


function ajaxNroDoc(f){
	var contenedor;
	contenedor=document.getElementById("divNroDoc");
	ajax=nuevoAjax();
	var codTipoDoc=(f.tipoDoc.value);
	ajax.open("GET", "ajaxNroDoc.php?codTipoDoc="+codTipoDoc,true);
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
			ajaxPrecioItem(indice);
		}
	}
	totales();
	ajax.send(null);
}

function ajaxPrecioItem(indice){
	var contenedor;
	contenedor=document.getElementById("idprecio"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
	var tipoPrecio=document.getElementById("tipoPrecio"+indice).value;
	var cantidadUnitaria=document.getElementById("cantidad_unitaria"+indice).value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxPrecioItem.php?codmat="+codmat+"&indice="+indice+"&tipoPrecio="+tipoPrecio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			var respuesta=ajax.responseText.split("#####");
			contenedor.innerHTML = respuesta[0];
            document.getElementById("descuentoProducto"+indice).value=(respuesta[1]*parseFloat(cantidadUnitaria)); 
			calculaMontoMaterial(indice);
		}
	}
	ajax.send(null);
}

function ajaxRazonSocial(f){
	var contenedor;
	contenedor=document.getElementById("divRazonSocial");
	var nitCliente=document.getElementById("nitCliente").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxRazonSocial.php?nitCliente="+nitCliente,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			document.getElementById('razonSocial').focus();
		}
	}
	ajax.send(null);
}


function calculaMontoMaterial(indice){

	var cantidadUnitaria=document.getElementById("cantidad_unitaria"+indice).value;
	var precioUnitario=document.getElementById("precio_unitario"+indice).value;
	var descuentoUnitario=document.getElementById("descuentoProducto"+indice).value;
	
	var montoUnitario=(parseFloat(cantidadUnitaria)*parseFloat(precioUnitario)) - (parseFloat(descuentoUnitario));
	montoUnitario=Math.round(montoUnitario*100)/100
		
	document.getElementById("montoMaterial"+indice).value=montoUnitario;
	
	totales();
}

function totales(){
	var subtotal=0;
    for(var ii=1;ii<=num;ii++){
	 	if(document.getElementById('materiales'+ii)!=null&&document.getElementById('materiales'+ii).value!=-100){
			var monto=document.getElementById("montoMaterial"+ii).value;
			subtotal=subtotal+parseFloat(monto);
		}
    }
    var subtotalPrecio=0;
    for(var ii=1;ii<=num;ii++){
	 	if(document.getElementById('materiales'+ii)!=null&&document.getElementById('materiales'+ii).value!=-100){
			var precio=document.getElementById("precio_unitario"+ii).value;
			var cantidad=document.getElementById("cantidad_unitaria"+ii).value;
			subtotalPrecio=subtotalPrecio+parseFloat(precio*cantidad);
		}
    }
    document.getElementById("total_precio_sin_descuento").innerHTML=subtotalPrecio;

    subtotalPrecio=Math.round(subtotalPrecio*100)/100;

	subtotal=Math.round(subtotal*100)/100;
	
	var tipo_cambio=$("#tipo_cambio_dolar").val();

    document.getElementById("totalVenta").value=subtotal;
	document.getElementById("totalFinal").value=subtotal;

	document.getElementById("totalVentaUSD").value=Math.round((subtotal/tipo_cambio)*100)/100;
	document.getElementById("totalFinalUSD").value=Math.round((subtotal/tipo_cambio)*100)/100;

    //setear descuento o aplicar la suma total final con el descuento
	document.getElementById("descuentoVenta").value=0;
	document.getElementById("descuentoVentaUSD").value=0;
	aplicarCambioEfectivo();
	minimoEfectivo();
	//calcularMontoCambiar();
}

function aplicarDescuento(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var total=document.getElementById("totalVenta").value;
	var descuento=document.getElementById("descuentoVenta").value;
	
	descuento=Math.round(descuento*100)/100;
	
	document.getElementById("totalFinal").value=Math.round((parseFloat(total)-parseFloat(descuento))*100)/100;
	var descuentoUSD=(parseFloat(total)-parseFloat(descuento))/tipo_cambio;
	document.getElementById("descuentoVentaUSD").value=Math.round((descuento/tipo_cambio)*100)/100;
	document.getElementById("totalFinalUSD").value=Math.round((descuentoUSD)*100)/100;

	document.getElementById("descuentoVentaPorcentaje").value=Math.round((parseFloat(descuento)*100)/(parseFloat(total)));
	document.getElementById("descuentoVentaUSDPorcentaje").value=Math.round((parseFloat(descuento)*100)/(parseFloat(total)));
	aplicarCambioEfectivo();
	minimoEfectivo();
	//totales();
	
}
function aplicarDescuentoUSD(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var total=document.getElementById("totalVentaUSD").value;
	var descuento=document.getElementById("descuentoVentaUSD").value;
	
	descuento=Math.round(descuento*100)/100;
	
	document.getElementById("totalFinalUSD").value=Math.round((parseFloat(total)-parseFloat(descuento))*100)/100;
	var descuentoBOB=(parseFloat(total)-parseFloat(descuento))*tipo_cambio;
	document.getElementById("descuentoVenta").value=Math.round((descuento*tipo_cambio)*100)/100;
	document.getElementById("totalFinal").value=Math.round((descuentoBOB)*100)/100;
	document.getElementById("descuentoVentaPorcentaje").value=Math.round((parseFloat(descuento)*100)/(parseFloat(total)));
	document.getElementById("descuentoVentaUSDPorcentaje").value=Math.round((parseFloat(descuento)*100)/(parseFloat(total)));
	aplicarCambioEfectivoUSD();
	minimoEfectivo();
	//totales();
}

function aplicarDescuentoPorcentaje(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var total=document.getElementById("totalVenta").value;
    
    var descuentoPorcentaje=document.getElementById("descuentoVentaPorcentaje").value;
    document.getElementById("descuentoVentaUSDPorcentaje").value=descuentoPorcentaje;

	var descuento=document.getElementById("descuentoVenta").value;
	
	descuento=Math.round(parseFloat(descuentoPorcentaje)*parseFloat(total)/100);
	
	document.getElementById("totalFinal").value=Math.round((parseFloat(total)-parseFloat(descuento))*100)/100;
	var descuentoUSD=(parseFloat(total)-parseFloat(descuento))/tipo_cambio;
	document.getElementById("descuentoVenta").value=Math.round((descuento)*100)/100;
	document.getElementById("descuentoVentaUSD").value=Math.round((descuento/tipo_cambio)*100)/100;
	document.getElementById("totalFinalUSD").value=Math.round((descuentoUSD)*100)/100;
	
	aplicarCambioEfectivo();
	minimoEfectivo();
	//totales();
}
function aplicarDescuentoUSDPorcentaje(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var total=document.getElementById("totalVenta").value;
    
    var descuentoPorcentaje=document.getElementById("descuentoVentaUSDPorcentaje").value;
    document.getElementById("descuentoVentaPorcentaje").value=descuentoPorcentaje;

	var descuento=document.getElementById("descuentoVenta").value;
	
	descuento=Math.round(parseFloat(descuentoPorcentaje)*parseFloat(total))/100;
	
	document.getElementById("totalFinal").value=Math.round((parseFloat(total)-parseFloat(descuento))*100)/100;
	var descuentoUSD=(parseFloat(total)-parseFloat(descuento))/tipo_cambio;
	document.getElementById("descuentoVenta").value=Math.round((descuento)*100)/100;
	document.getElementById("descuentoVentaUSD").value=Math.round((descuento/tipo_cambio)*100)/100;
	document.getElementById("totalFinalUSD").value=Math.round((descuentoUSD)*100)/100;
	
	aplicarCambioEfectivo();
	minimoEfectivo();
	//totales();
}
function minimoEfectivo(){
  //obtener el minimo a pagar
	var minimoEfectivo=$("#totalFinal").val();
	var minimoEfectivoUSD=$("#totalFinalUSD").val();
	//asignar el minimo al atributo min
	//$("#efectivoRecibidoUnido").attr("min",minimoEfectivo);
	//$("#efectivoRecibidoUnidoUSD").attr("min",minimoEfectivoUSD);	
	calcularMontoCambiar();	
}
function aplicarCambioEfectivo(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var recibido=document.getElementById("efectivoRecibido").value;
	var total=document.getElementById("totalFinal").value;

	var cambio=Math.round((parseFloat(recibido)-parseFloat(total))*100)/100;
	document.getElementById("cambioEfectivo").value=parseFloat(cambio);
	document.getElementById("efectivoRecibidoUSD").value=Math.round((recibido/tipo_cambio)*100)/100;
	document.getElementById("cambioEfectivoUSD").value=Math.round((cambio/tipo_cambio)*100)/100;	
	minimoEfectivo();
}
function aplicarCambioEfectivoUSD(f){
	var tipo_cambio=$("#tipo_cambio_dolar").val();
	var recibido=document.getElementById("efectivoRecibidoUSD").value;
	var total=document.getElementById("totalFinalUSD").value;

	var cambio=Math.round((parseFloat(recibido)-parseFloat(total))*100)/100;
	document.getElementById("cambioEfectivoUSD").value=parseFloat(cambio);
	document.getElementById("efectivoRecibido").value=Math.round((recibido*tipo_cambio)*100)/100;
	document.getElementById("cambioEfectivo").value=Math.round((cambio*tipo_cambio)*100)/100;	
	minimoEfectivo();
}
function aplicarMontoCombinadoEfectivo(f){
	alert("paso1");
   var efectivo=$("#efectivoRecibidoUnido").val();	
   var efectivoUSD=$("#efectivoRecibidoUnidoUSD").val();	
  if(efectivo==""){
   efectivo=0;
  }
  if(efectivoUSD==""){
   efectivoUSD=0;
  }	

  var tipo_cambio=$("#tipo_cambio_dolar").val();
  var monto_dolares_bolivianos=parseFloat(efectivoUSD)*parseFloat(tipo_cambio);
  var monto_total_bolivianos=monto_dolares_bolivianos+parseFloat(efectivo);
  document.getElementById("efectivoRecibido").value=Math.round((monto_total_bolivianos)*100)/100;
  document.getElementById("efectivoRecibidoUSD").value=Math.round((monto_total_bolivianos/tipo_cambio)*100)/100;
  alert("paso1"+document.getElementById("efectivoRecibido").value);
  alert("paso1"+document.getElementById("efectivoRecibidoUSD").value);
  aplicarCambioEfectivo(f);
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
function setMateriales(f, cod, nombreMat){
	var numRegistro=f.materialActivo.value;
	
	document.getElementById('materiales'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat;
	
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';
	
	document.getElementById("cantidad_unitaria"+numRegistro).focus();

	actStock(numRegistro);
}
		
function precioNeto(fila){

	var precioCompra=document.getElementById('precio'+fila).value;
		
	var importeNeto=parseFloat(precioCompra)- (parseFloat(precioCompra)*0.13);

	if(importeNeto=="NaN"){
		importeNeto.value=0;
	}
	document.getElementById('neto'+fila).value=importeNeto;
}
function fun13(cadIdOrg,cadIdDes)
{   var num=document.getElementById(cadIdOrg).value;
    num=(100-13)*num/100;
    document.getElementById(cadIdDes).value=num;
}

num=0;
cantidad_items=0;

function mas(obj) {
	if(num>=15){
		alert("No puede registrar mas de 15 items en una nota.");
	}else{
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
			var cod_precio=document.getElementById("tipoPrecio").value;			
			ajax.open("GET","ajaxMaterialVentas.php?codigo="+num+"&cod_precio="+cod_precio,true);
			ajax.onreadystatechange=function(){
				if (ajax.readyState==4) {
					div_material.innerHTML=ajax.responseText;
					buscarMaterial(form1, num);
				}
			}		
			ajax.send(null);
		}

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
	totales();
}

function pressEnter(e, f){
	tecla = (document.all) ? e.keyCode : e.which;
	if (tecla==13){
		document.getElementById('itemNombreMaterial').focus();
		listaMateriales(f);
		return false;
	}
}

function validar(f, ventaDebajoCosto){
	
	//alert(ventaDebajoCosto);
	f.cantidad_material.value=num;
	var cantidadItems=num;
	console.log("numero de items: "+cantidadItems);
	if(cantidadItems>0){
	  if(cuentaSeleccionados()>0){
	  	var itemAumentoValidacion=0;
	  	var montoAumentoEfectivoItem=parseFloat($("#total_aumento_efectivo").html());
		 if(montoAumentoEfectivoItem>0){
           if(buscarItemAumento()==0){
           	 alert("Debe registrar en la venta el item AUMENTO DE EFECTIVO POR CAMBIO DE PRODUCTO, "+montoAumentoEfectivoItem+" Bs.");
           	 itemAumentoValidacion=1;
		     return(false);
           }else{
           	if(buscarItemAumentoMonto()!=montoAumentoEfectivoItem){
           		alert("Los Montos de Aumento no igualan ");
           	    itemAumentoValidacion=1;
		        return(false);
           	}
           }
		 }else{
		 	if(buscarItemAumento()>0){
             alert("No debe existir en la venta el item AUMENTO DE EFECTIVO POR CAMBIO DE PRODUCTO");
             itemAumentoValidacion=1;
		     return(false);
		 	}
		} 
		if(itemAumentoValidacion==0){	
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
				
				//VALIDACION DE VARIABLE DE STOCK QUE NO SE VALIDA
				stock=document.getElementById("stock"+i).value;
				if(stock=="-"){
					stock=10000;
				}else{
					stock=parseFloat(document.getElementById("stock"+i).value);
				}
				
				descuento=parseFloat(document.getElementById("descuentoProducto"+i).value);
				precioUnit=parseFloat(document.getElementById("precio_unitario"+i).value);				
				var costoUnit=parseFloat(document.getElementById("costoUnit"+i).value);
		
				console.log("materiales"+i+" valor: "+item);
				console.log("stock: "+stock+" cantidad: "+cantidad+ "precio: "+precioUnit);

				if(item==0){
					alert("Debe escoger un item en la fila "+i);
					return(false);
				}
				//alert(costoUnit+" "+precioUnit);
				if(costoUnit>precioUnit && ventaDebajoCosto==0){
					alert('No puede registrar una venta a perdida!!!!');
					return(false);
				}
				if(stock<cantidad){
					alert("No puede sacar cantidades mayores a las existencias. Fila "+i);
					return(false);
				}		
				if((cantidad<=0 || precioUnit<=0) || (Number.isNaN(cantidad)) || Number.isNaN(precioUnit)){
					alert("La cantidad y/o Precio no pueden estar vacios o ser <= 0.");
					return(false);
				}
			}
	       }
	     }//fin de validacion item aumento
		}else{
		  alert("Debe cambiar al menos 1 item.");
		  return(false);	
		}
	}else{
		alert("El ingreso debe tener al menos 1 item.");
		return(false);
	}
}

function checkSubmit() {
    document.getElementById("btsubmit").value = "Enviando...";
    document.getElementById("btsubmit").disabled = true;
    return true;
}	

$(document).ready(function() {
  $("#guardarSalidaVenta").submit(function(e) {
      var mensaje="";
      if(parseFloat($("#efectivoRecibido").val())<parseFloat($("#totalFinal").val())){
        mensaje+="<p></p>";
        alert("El monto en efectivo NO debe ser menor al monto total");
        return false;
      }else{
      	document.getElementById("btsubmit").value = "Enviando...";
        document.getElementById("btsubmit").disabled = true;
      }     
    });
});

function calcularMontoCambiar() {
	var montoCambiar=0;
	$(".codigo_producto").each(function (index) {  
       if($(this).is(':checked')){
       	montoCambiar+=parseFloat($("#monto_producto"+$(this).val()).val());
       }
    });
  montoCambiar=Math.round((montoCambiar)*100)/100;  
  var montoAumentoEfectivo=parseFloat(document.getElementById("totalFinal").value)-montoCambiar;  
  montoAumentoEfectivo=Math.round((montoAumentoEfectivo)*100)/100;  
  if(montoAumentoEfectivo<0){
  	montoAumentoEfectivo=0;
  }
  document.getElementById("total_precio_sin_descuento_vendido").innerHTML="<b>"+montoCambiar+"</b>";
  document.getElementById("total_aumento_efectivo").innerHTML=""+montoAumentoEfectivo+"";
}
function cuentaSeleccionados(){
	var cantidadSel=0;
	$(".codigo_producto").each(function (index) {  
       if($(this).is(':checked')){
       	cantidadSel++;
       }
    });
    return cantidadSel;
}

function buscarItemAumento(){
	var itemAumento=0;
    for(var ii=1;ii<=num;ii++){
	 	if(document.getElementById('materiales'+ii)!=null&&document.getElementById('materiales'+ii).value==-100){
			itemAumento++;
		}
    }
    return itemAumento;
}
function buscarItemAumentoMonto(){
	var subtotalPrecio=0;
    for(var ii=1;ii<=num;ii++){
	 	if(document.getElementById('materiales'+ii)!=null&&document.getElementById('materiales'+ii).value==-100){
			var precio=document.getElementById("precio_unitario"+ii).value;
			var cantidad=document.getElementById("cantidad_unitaria"+ii).value;
			subtotalPrecio=parseFloat(precio*cantidad);
		}
    }
    return subtotalPrecio;
}
</script>

		
<?php
echo "</head><body onLoad='funcionInicio();'>";

$banderaErrorFacturacion=0;

if(isset($fecha)==""){   
	$fecha=date("Y-m-d");
}

	$sqlCambioUsd="select valor from cotizaciondolar order by 1 desc limit 1";
	$respUsd=mysqli_query($enlaceCon,$sqlCambioUsd);
	$tipoCambio=1;
	while($filaUSD=mysqli_fetch_array($respUsd)){
		$tipoCambio=$filaUSD[0];	
	}
?><input type="hidden" id="tipo_cambio_dolar" value="<?=$tipoCambio?>"><?php
$usuarioVentas=$_COOKIE['global_usuario'];
$globalAgencia=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE['global_almacen'];

//SACAMOS LA CONFIGURACION PARA EL DOCUMENTO POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=1";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$tipoDocDefault=$datConf[0];
//$tipoDocDefault=mysql_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA EL CLIENTE POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=2";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$clienteDefault=$datConf[0];
//$clienteDefault=mysql_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=3";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$facturacionActivada=$datConf[0];
//$facturacionActivada=mysql_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA CONOCER SI PERMITIMOS VENDER POR DEBAJO DEL COSTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=5";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$ventaDebajoCosto=$datConf[0];
//$ventaDebajoCosto=mysql_result($respConf,0,0);



//datos de la venta
$codigoVenta=$_GET['codVenta'];
		$sqlEmp="select cod_empresa, nombre, nit, direccion, ciudad from datos_empresa";
		$respEmp=mysqli_query($enlaceCon,$sqlEmp);
		$datEmp=mysqli_fetch_array($respEmp);
		
		$nombreEmpresa=$datEmp[1];//mysql_result($respEmp,0,1);
		$nitEmpresa=$datEmp[2];//mysql_result($respEmp,0,2);
		$direccionEmpresa=$datEmp[3];//mysql_result($respEmp,0,3);
		$ciudadEmpresa=$datEmp[4];//mysql_result($respEmp,0,4);
		
		
		/*$nombreEmpresa=mysql_result($respEmp,0,1);
		$nitEmpresa=mysql_result($respEmp,0,2);
		$direccionEmpresa=mysql_result($respEmp,0,3);
		$ciudadEmpresa=mysql_result($respEmp,0,4);*/
	
		//datos documento				
		$sqlDatosVenta="select concat((DATE_FORMAT(s.fecha, '%d/%m/%Y')),' ',s.hora_salida) as fecha, t.`abreviatura`, 
			(select cl.nombre_cliente from clientes cl where s.cod_cliente=cl.cod_cliente) as nombre_cliente,
			(select cl.telf1_cliente from clientes cl where s.cod_cliente=cl.cod_cliente) as telefonoCli, 			
			s.`nro_correlativo`, s.razon_social, s.nit, s.observaciones, 
			(select concat(f.paterno, ' ', f.nombres) from funcionarios f where codigo_funcionario=s.cod_chofer) as chofer,
			(select celular from funcionarios f where codigo_funcionario=s.cod_chofer) as celular,
			(select v.placa from vehiculos v where v.codigo=s.cod_vehiculo) as placa
			from `salida_almacenes` s, `tipos_docs` t
				where s.`cod_salida_almacenes`='$codigoVenta' and s.`cod_tipo_doc`=t.`codigo`";
		$respDatosVenta=mysqli_query($enlaceCon,$sqlDatosVenta);
		while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
			$fechaVenta=$datDatosVenta[0];
			$nombreTipoDoc=$datDatosVenta[1];
			$nombreCliente=$datDatosVenta[2];
			$telfCliente=$datDatosVenta[3];
			$nroDocVenta=$datDatosVenta[4];
			$razonSocial=$datDatosVenta[5];
			$nitVenta=$datDatosVenta[6];
			$obsNota=$datDatosVenta[7];
			$nombreChofer=$datDatosVenta[8];
			$celularChofer=$datDatosVenta[9];
			$placa=$datDatosVenta[10];
		}
?>


<h1>Cambio de Productos</h1>
<form action='guardarCambioDeProducto.php' method='POST' name='form1' id="guardarSalidaVenta" ><!--onsubmit='return checkSubmit();'-->
<table class='texto' align='center' width='100%'>
<tr>
<th>Cliente</th>
<th>Nro.Factura</th>
<th>Fecha Venta</th>
<th>NIT</th>
<th>Nombre/RazonSocial</th>
<th>Vendedor</th>
</tr>
<tr>
<input type="hidden" name="tipoSalida" id="tipoSalida" value="1002">

<input type="hidden" id="global_almacen" value="<?=$globalAlmacen?>">

<td align='left'>
	<input type="hidden" value="<?=$tipoDocDefault;?>" id="tipoDoc" name="tipoDoc" onChange='ajaxNroDoc(form1)'>
	<input type="text" style="background:#BFBFBD;"  value="<?=$nombreCliente;?>" id="nombreCliente" name="nombreCliente" size='40' readonly>
</td>
<td align='left'>
	<div id='divNroDoc'>
		<input type='text' class='texto' style="background:#BFBFBD;" value='<?php echo $nroDocVenta?>' id='nro_factura' size='20' name='nro_factura' readonly>
	</div>
</td>

<td align='left'>
	<input type='text' class='texto' style="background:#BFBFBD;" value='<?php echo $fechaVenta?>' id='fecha_antigua' size='20' name='fecha_antigua' readonly>
</td>
<td>
		<div id='divNIT'>
			<input type='number' style="background:#BFBFBD;" value='<?php echo $nitVenta; ?>' name='nitCliente' id='nitCliente' readonly>
		</div>
	</td>
	
	<td>
		<div id='divRazonSocial'>
			<input type='text' style="background:#BFBFBD;" name='razonSocial' id='razonSocial' value='<?php echo $razonSocial;?>' readonly>
		</div>
	</td>

	<td>
		<div id='divVendedor'>
			<input type='text' style="background:#BFBFBD;" name='vendedor' id='vendedor' value='<?php echo $nombreChofer;?>' readonly>
		</div>
	</td>


</tr>

<?php
if($tipoDocDefault==2){
	$razonSocialDefault="-";
	$nitDefault="0";
}else{
	$razonSocialDefault="";
	$nitDefault="";
}
?>
<!--tr>
	<th style="background:#2874A6;">Fecha </th>
    <th style="background:#2874A6;">Tipo Precio</th>
    <th style="background:#2874A6;">Tipo Pago</th>
	<th style="background:#2874A6;">Vendedor Actual</th>
	<th colspan="2" style="background:#2874A6;">Observaciones</th>
</tr-->
<tr>	
	<td align='left'>
	<input type='text' class='texto' value='<?php echo $fecha?>' id='fecha' size='20' name='fecha' readonly>
</td>
<td>
	<div id='divTipoPrecio'>
		<?php
			$sql1="select codigo, nombre, abreviatura from tipos_precio where estado=1 order by 3";
			$resp1=mysqli_query($enlaceCon,$sql1);
			echo "<select name='tipoPrecio' class='texto' id='tipoPrecio'>";
			while($dat=mysqli_fetch_array($resp1)){
				$codigo=$dat[0];
				$nombre=$dat[1];
				$abreviatura=$dat[2];
				echo "<option value='$codigo'>$nombre ($abreviatura %)</option>";
			}
			echo "</select>";
			?>

	</div>
</td>
<td>
	<div id='divTipoVenta'>
		<?php
			$sql1="select cod_tipopago, nombre_tipopago from tipos_pago order by 1";
			$resp1=mysqli_query($enlaceCon,$sql1);
			echo "<select name='tipoVenta' class='texto' id='tipoVenta'>";
			while($dat=mysqli_fetch_array($resp1)){
				$codigo=$dat[0];
				$nombre=$dat[1];
				echo "<option value='$codigo'>$nombre</option>";
			}
			echo "</select>";
			?>

	</div>
</td>
    <td>
		<select name='cod_vendedor' class='texto' id='cod_vendedor' required>
			<option value=''>----</option>
			<?php
			$sql2="select f.`codigo_funcionario`,
				concat(f.`paterno`,' ', f.`nombres`) as nombre from `funcionarios` f where 
				f.`cod_ciudad`='$globalAgencia' and estado=1 order by 2";
			$resp2=mysqli_query($enlaceCon,$sql2);

			while($dat2=mysqli_fetch_array($resp2)){
				$codVendedor=$dat2[0];
				$nombreVendedor=$dat2[1];
			?>		
			<option value='<?php echo $codVendedor?>' <?=($codVendedor==$global_usuario)?"selected":"";?> ><?php echo $nombreVendedor?></option>
			<?php    
			}
			?>
		</select>
	</td>
	<th align='center' colspan="3">
		<input type='text' class='texto' name='observaciones' value='' size='40' rows="3">
	</th>
</tr>

</table>
	<input type="hidden" name="codVenta" value="<?=$codigoVenta?>">
	<h2>Productos a Cambiar</h2>
<div id='divCuerpo'><center>
	<table class='texto'>
       <tr>
       	 <th style="background:#248CE7;color:white;">&nbsp;</th>
       	 <th style="background:#248CE7;color:white;">Codigo</th>
       	 <th style="background:#248CE7;color:white;">Producto</th>
       	 <th style="background:#248CE7;color:white;">Color/Talla</th>
	     <th style="background:#248CE7;color:white;">Cantidad</th>
	     <th style="background:#248CE7;color:white;">Monto</th>
      </tr>

<?php
//AQUI EMPEZAMOS CON EL DETALLE
$codigoVenta=$_GET['codVenta'];

$sql_detalle="select m.codigo_barras, m.`descripcion_material`, 
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total, m.color, m.talla,m.codigo_material
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` 
	and s.`salida_anulada`=0 and sd.`cod_material`=m.`codigo_material` and 
	s.cod_salida_almacenes='$codigoVenta'
	group by m.`codigo_material` order by 2 desc;";
	
$resp_detalle=mysqli_query($enlaceCon,$sql_detalle);
$montoTotal=0;
$pesoTotal=0;
$pesoTotalqq=0;
$montoUnitarioTotal=0;
while($datosX=mysqli_fetch_array($resp_detalle))
{	$codItem=$datosX[0];
		$nombreItem=$datosX[1];
		$montoVenta=$datosX[2];
		$cantidad=$datosX[3];
		
		$descuentoVenta=$datosX[4];
		$montoNota=$datosX[5];
		
		$colorItem=$datosX[6];
		$tallaItem=$datosX[7];
		$cod_material=$datosX[8];
		if($descuentoVenta>0){
			$porcentajeVentaProd=($montoVenta/$montoNota);
			$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
			$montoVenta=$montoVenta-$descuentoAdiProducto;
		}
		
		$montoPtr=number_format($montoVenta,2,".",",");
		$cantidadFormat=number_format($cantidad,0,".",",");
		
	$montoTotal=$montoTotal+$montoVenta;
	$montoTotal=redondear2($montoTotal);

	$chk = "<input type='checkbox' checked class='codigo_producto' name='codigo_producto[]' value='$cod_material' onclick='calcularMontoCambiar();'><input type='hidden' value='".$montoPtr."' id='monto_producto".$cod_material."'>";
	?>
     <tr>
       	 <td>&nbsp;<?=$chk?></td>
       	 <td><?=$codItem?></td>
       	 <td><?=$nombreItem?></td>
       	 <td><?=$colorItem."/".$tallaItem?></td>
	     <td><?=$cantidadFormat?></td>
	     <td><?=$montoPtr?></td>
      </tr> 
	<?php
	$montoUnitarioTotal+=$montoPtr;
}
?>
      <tr>
       	 <td colspan="5"><b>Total Vendido</b></td>
	     <td><b><?=number_format($montoUnitarioTotal,2,'.','')?></b></td>
      </tr>
</table></center>
</div>


<input type="hidden" id="ventas_codigo"><!--para validar la funcion mas desde ventas-->
<div class="codigo-barras div-center">
               <input type="text" class="form-codigo-barras" id="input_codigo_barras" placeholder="Ingrese el código de barras." autofocus autocomplete="off">
</div>

<fieldset id="fiel" style="width:100%;border:0;">
	<table align="center" class="texto" width="100%" id="data0">
	<tr>
		<td align="center" colspan="9">
			<b>Detalle del Cambio</b><input class="boton" type="button" value="Adicionar Item (+)" onclick="mas(this)" accesskey="a"/>
		</td>
	</tr>

	<tr align="center">
		<td width="5%">&nbsp;</td>
		<td width="30%">Material</td>
		<td width="10%">Stock</td>
		<td width="10%">Cantidad</td>
		<td width="10%">Precio </td>
		<td width="15%">Desc.</td>
		<td width="10%">Monto</td>
		<td width="10%">&nbsp;</td>
	</tr>
	</table>
</fieldset>


<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2; overflow: auto;">
</div>

<div id="divboton" style="position: absolute; top:20px; left:920px;visibility:hidden; text-align:center; z-index:3">
	<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2; overflow: auto;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<table align='center'>
			<tr><th>Grupo</th><th>Material</th><th>&nbsp;</th></tr>
			<tr>
			<td><select class="textogranderojo" name='itemTipoMaterial' style="width:300px">
			<?php
			$sqlTipo="select g.codigo, g.nombre from grupos g
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
				<input type='text' name='itemNombreMaterial' id='itemNombreMaterial' class="textogranderojo" onkeypress="return pressEnter(event, this.form);">
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
<div style="height:250px;"></div>

<div class="pie-div" style="background:#F2E191;">
	
	<table class="pie-montos">
      <tr>
        <td>
	<table id='' width='100%' border="0">
		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;"></td><td align='center'><b style="font-size:35px;color:#0691CD;">Bs.</b></td>
		</tr>

		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;">Monto Nota</td><td><input type='number' name='totalVenta' id='totalVenta' readonly style="background:#B0B4B3"></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Descuento</td><td><input type='number' name='descuentoVenta' id='descuentoVenta' onChange='aplicarDescuento(form1);' style="height:27px;font-size:22px;width:100%;color:red;" onkeyup='aplicarDescuento(form1);' onkeydown='aplicarDescuento(form1);' value="0" step='0.01' required></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Descuento %</td><td><input type='number' name='descuentoVentaPorcentaje' id='descuentoVentaPorcentaje' style="height:27px;font-size:22px;width:100%;color:red;" onChange='aplicarDescuentoPorcentaje(form1);' onkeyup='aplicarDescuentoPorcentaje(form1);' onkeydown='aplicarDescuentoPorcentaje(form1);' value="0" step='0.01'></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;font-size:12px;color:red;">Monto Final</td><td><input type='number' name='totalFinal' id='totalFinal' readonly style="background:#0691CD;height:27px;font-size:22px;width:100%;color:#fff;"></td>
		</tr>
		<tr style="display:none">
			<td align='right' width='90%' style="color:#777B77;font-size:12px;">Efectivo Recibido</td><td><input type='number' style="background:#B0B4B3" name='efectivoRecibido' id='efectivoRecibido' readonly step="any" onChange='aplicarCambioEfectivo(form1);' onkeyup='aplicarCambioEfectivo(form1);' onkeydown='aplicarCambioEfectivo(form1);'></td>
		</tr>
		<tr style="display:none">
			<td align='right' width='90%' style="color:#777B77;font-size:12px;">Cambio</td><td><input type='number' name='cambioEfectivo' id='cambioEfectivo' readonly style="background:#7BCDF0;height:25px;font-size:18px;width:100%;"></td>
		</tr>
	</table>
      
        </td>
        <td>
	<table id='' width='100%' border="0">
		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;"></td><td align='center'><b style="font-size:35px;color:#189B22;">$ USD</b></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;">Monto Nota</td><td><input type='number' name='totalVentaUSD' id='totalVentaUSD' readonly style="background:#B0B4B3"></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Descuento</td><td><input type='number' name='descuentoVentaUSD' id='descuentoVentaUSD' style="height:27px;font-size:22px;width:100%;color:red;" onChange='aplicarDescuentoUSD(form1);' onkeyup='aplicarDescuentoUSD(form1);' onkeydown='aplicarDescuentoUSD(form1);' value="0" step='0.01' required></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Descuento %</td><td><input type='number' name='descuentoVentaUSDPorcentaje' id='descuentoVentaUSDPorcentaje' style="height:27px;font-size:22px;width:100%;color:red;" onChange='aplicarDescuentoUSDPorcentaje(form1);' onkeyup='aplicarDescuentoUSDPorcentaje(form1);' onkeydown='aplicarDescuentoUSDPorcentaje(form1);' value="0" step='0.01'></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Monto Final</td><td><input type='number' name='totalFinalUSD' id='totalFinalUSD' readonly style="background:#189B22;height:27px;font-size:22px;width:100%;color:#fff;"> </td>
		</tr>
		<tr style="display:none">
			<td align='right' width='90%' style="color:#777B77;font-size:12px;">Efectivo Recibido</td><td><input type='number' name='efectivoRecibidoUSD' id='efectivoRecibidoUSD' style="background:#B0B4B3" step="any" readonly onChange='aplicarCambioEfectivoUSD(form1);' onkeyup='aplicarCambioEfectivoUSD(form1);' onkeydown='aplicarCambioEfectivoUSD(form1);'></td>
		</tr>
		<tr style="display:none">
			<td align='right' width='90%' style="color:#777B77;font-size:12px;">Cambio</td><td><input type='number' name='cambioEfectivoUSD' id='cambioEfectivoUSD' readonly style="background:#4EC156;height:25px;font-size:18px;width:100%;"></td>
		</tr>
	</table>
        </td>
      </tr>
	</table>


<?php

if($banderaErrorFacturacion==0){
	echo "<div class='divBotones'>
	        <input type='submit' class='boton' value='Cambiar Productos' id='btsubmit' name='btsubmit' onClick='return validar(this.form, $ventaDebajoCosto)'>
			<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_ingresomateriales.php\"';>
            <label style='font-size:11px;color:#9EA09E;'>TIPO DE CAMBIO $ : <b style=''> ".$tipoCambio." Bs.</b></label>
            
            <table style='width:400px;padding:0 !important;margin:0 !important;bottom:10px;position:fixed;left:100px;'>
            <tr>
               <td style='font-size:12px;color:#262C2E;font-weight:bold;' colspan='2'>Total devolución:<label id='total_precio_sin_descuento_vendido'>".number_format($montoUnitarioTotal,2,'.','')."</label> Bs., Aum. Efectivo : (<label style='color:red' id='total_aumento_efectivo'>".number_format($montoUnitarioTotal-0,2,'.','')."</label>) Bs. </td>
             </tr>
            <tr>
               <td style='font-size:12px;color:#456860;' colspan='2'>Total precio sin descuento = <label id='total_precio_sin_descuento'>0.00</label> Bs.</td>
             </tr>
             <tr>
               <td style='font-size:12px;color:#0691CD;' colspan='2'></td>
             </tr>
            <tr>
               <td style='font-size:12px;color:#0691CD; font-weight:bold;'>EFECTIVO Bs.</td>
               <td style='font-size:12px;color:#189B22; font-weight:bold;'>EFECTIVO $ USD</td>
             </tr>
             <tr>
               <td><input type='number' name='efectivoRecibidoUnido' onChange='aplicarMontoCombinadoEfectivo(form1);' onkeyup='aplicarMontoCombinadoEfectivo(form1);' onkeydown='aplicarMontoCombinadoEfectivo(form1);' id='efectivoRecibidoUnido' style='height:25px;font-size:18px;width:100%;' step='any'></td>
               <td><input type='number' name='efectivoRecibidoUnidoUSD' onChange='aplicarMontoCombinadoEfectivo(form1);' onkeyup='aplicarMontoCombinadoEfectivo(form1);' onkeydown='aplicarMontoCombinadoEfectivo(form1);' id='efectivoRecibidoUnidoUSD' style='height:25px;font-size:18px;width:100%;' step='any'></td>
             </tr>
            </table>

			";
	echo "</div>";	
}else{
	echo "";
}


?>

</div>

<input type='hidden' name='materialActivo' value="0">
<input type='hidden' name='cantidad_material' value="0">

</form>
</body>
</html>