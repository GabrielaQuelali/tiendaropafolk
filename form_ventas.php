<?php
echo "global_tipo_almacen".$_COOKIE['global_tipo_almacen'];
$_COOKIE['global_tipo_almacen']=1;
  if($_COOKIE['global_tipo_almacen']!=1){
  	require "conexionmysqli.inc";
  	?>
<!--link href="assets/style.css" rel="stylesheet" />
     		<div class="row">         
        <div class="col-lg-12 mb-5 mb-lg-0">
         <div  class="text-white after-loop-item card border-0 card-secundario shadow-lg" style="border-radius: 0% !important;">
            <div class="card-header d-flex align-items-end flex-column text-right bg-danger">
               <h4 class="font-weight-bold">SUMINISTROS</h4>
            </div>
            <div class="card-body bg-white text-muted">
               <p>Estimado Usuario, el modulo de suministros está activado.</p>
               <p class="w-85">Si requiere realizar ventas, por favor cambie EL <b>TIPO</b> a <b>MEDICAMENTOS</b></p>
								<a href="archivos-respaldo/guia_suministros.pdf" target="_blank">¿Como cambiar el tipo de Sucursal? click aquí</a>               
            </div>
         </div>
        </div>        
      </div-->
  	<?php
  }else{

?>
<html>
    <head>
        <title>VENTA</title>
        <link  rel="icon"   href="imagenes/card.png" type="image/png" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="lib/js/xlibPrototipoSimple-v0.1.js"></script>
		<script type="text/javascript" src="functionsGeneral.js"></script>
		
        <link rel="stylesheet" type="text/css" href="dist/bootstrap/bootstrap.css"/>
        <link rel="stylesheet" type="text/css" href="dist/bootstrap/dataTables.bootstrap4.min.css"/>
        <script type="text/javascript" src="dist/bootstrap/jquery-3.5.1.js"></script>
        <script type="text/javascript" src="dist/bootstrap/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="dist/bootstrap/dataTables.bootstrap4.min.js"></script>
        <!--<script type="text/javascript" src="lib/js/xlibPrototipo-v0.1.js"></script>-->
        <link rel="stylesheet" href="dist/selectpicker/dist/css/bootstrap-select.css">
        <link rel="stylesheet" type="text/css" href="dist/css/micss.css"/>
        <link rel="stylesheet" type="text/css" href="dist/demo.css"/>
        <link rel="stylesheet" type="text/css" href="assets/css/demo.css"/>
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.css" />
        <style type="text/css">
        	body{
              zoom: 86%;
            }
            img.bw {
	            filter: grayscale(0);
            }

            img.bw.grey {
            	filter: brightness(0.8) invert(0.4);
            	transition-property: filter;
            	transition-duration: 1s;	
            } 
            .btn-info{
            	background:#00ac99 !important;
            }
            .btn-info:hover{
            	background:#e6992b !important;
            }
            .btn-warning{
            	background:#e6992b !important;
            }
            .btn-warning:hover{
            	background:#1d2a76 !important;
            }


            .check_box:not(:checked),
.check_box:checked {
  position : absolute;
  left     : -9999px;
}

.check_box:not(:checked) + label,
.check_box:checked + label {
  position     : relative;
  padding-left : 30px;
  cursor       : pointer;
}

.check_box:not(:checked) + label:before,
.check_box:checked + label:before {
  content    : '';
  position   : absolute;
  left       : 0px;
  top        : 0px;
  width      : 20px;
  height     : 20px;
  border     : 1px solid #aaa;
  background : #f8f8f8;
}

.check_box:not(:checked) + label:after,
.check_box:checked + label:after {
  font-family             : 'Material Icons';
  content                 : 'check';
  text-rendering          : optimizeLegibility;
  font-feature-settings   : "liga" 1;
  font-style              : normal;
  text-transform          : none;
  line-height             : 22px;
  font-size               : 21px;
  width                   : 22px;
  height                  : 22px;
  text-align              : center;
  position                : absolute;
  top                     : 0px;
  left                    : 0px;
  display                 : inline-block;
  overflow                : hidden;
  -webkit-font-smoothing  : antialiased;
  -moz-osx-font-smoothing : grayscale;
  color                   : #09ad7e;
  transition              : all .2s;
}

.check_box:not(:checked) + label:after {
  opacity   : 0;
  transform : scale(0);
}

.check_box:checked + label:after {
  opacity   : 1;
  transform : scale(1);
}

.check_box:disabled:not(:checked) + label:before,
.check_box:disabled:checked + label:before {
  &, &:hover {
    border-color     : #bbb !important;
    background-color : #ddd;
  }
}

.check_box:disabled:checked + label:after {
  color : #999;
}

.check_box:disabled + label {
  color : #aaa;
}

.check_box:checked:focus + label:before,
.check_box:not(:checked):focus + label:before {
  border : 1px dotted #09ad7e;
}

label:hover:before {
  border : 1px solid #09ad7e !important;
}
    td a:focus {
          color: #febd00 !important;
          /*font-size: 20px !important;*/
          background:#1d2a76 !important;
		}
		td a:hover {
          color: #febd00 !important;
          /*font-size: 20px !important;*/
          background:#1d2a76 !important;
		}       



.sidenav {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #48C3C1;
  overflow-x: hidden;
  transition: 0.1s;
  padding-top: 60px;
  color: #fff;
}

.sidenav a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.sidenav a:hover {
  color: #f1f1f1;
}

.sidenav .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

@media screen and (max-height: 450px) {
  .sidenav {padding-top: 15px;}
  .sidenav a {font-size: 18px;}
}





/*.circle::before, .circle::after {
  content:"";
  position:absolute;
  top: 50%;
  left: 50%;
  transform:translate(-50%, -50%);
  border: 10px solid gray;
  border-radius:100%;
  animation: latido linear 3s infinite;
}

.circle::after {
  animation-delay: -1.5s;
}

@keyframes latido {
  0% { width:60px; height:40px; border:7px solid gray; }
  100% { width:120px; height:120px; border:10px solid transparent; }
}

*/

        </style>
	
<script type='text/javascript' language='javascript'>
	$(document).ready(function() {
    setTimeout(function() {
        $("#button_nuevo_cliente").removeClass("circle");
    },3000);
});

	$(document).ready(function() {		
        Mousetrap.bind('alt+q', function(){ if(num>0){var numeroFila=num;menos(numeroFila);} return false;});
        Mousetrap.bind('alt+r', function(){ guardarRecetaVenta(); return false;});
        //Mousetrap.bind('alt+t', function(){ $("#tipo_comprobante").focus(); return false; });
        $('[data-toggle="tooltip"]').tooltip({
              animated: 'swing', //swing expand
              placement: 'right',
              html: true,
              trigger : 'hover'
          });
        /*var txtPersonalizado = document.getElementById("razonSocial")
        txtPersonalizado.addEventListener("input", function (event) {
            validarTextoEntrada(this, "[0-9 a-z A-Z Ññ.]")
        });*/

    });

function guardarVentaGeneral(){
	var tipo_doc=$("#tipo_documento").val();
	var error_nit=$("#siat_error_valor").val();

	var tituloBoton="Facturar";
	if(error_nit==0&&tipo_doc==5){
		var tituloBoton="Facturar con Codigo de Excepción";
	}

	//NIT INEXISTENTE
   Swal.fire({
        title: '¿Esta seguro de Facturar?',
        text: "Se procederá con el guardado del documento",
         type: 'info',
        showCancelButton: true,
        confirmButtonClass: 'btn btn-info',
        cancelButtonClass: 'btn btn-default',
        confirmButtonText: tituloBoton,
        cancelButtonText: 'Cancelar',
        buttonsStyling: false
       }).then((result) => {
          if (result.value) {
          	document.getElementById("confirmacion_guardado").value=1;
          	$("#confirmacion_guardado").val(1);
            $('#guardarSalidaVenta').submit();  
            //return(false);                 
          } else if (result.dismiss === Swal.DismissReason.cancel) {
            return(false);
          }
    });
    //$(".swal2-modal").css('background-color', '#000');//Optional changes the color of the sweetalert 
    //$(".swal2-container").css('background-color', 'rgba(43, 165, 137, 1)');//changes the color of the overlay
    return false;
}

function mueveReloj(){
    momentoActual = new Date()
    hora = momentoActual.getHours()
    minuto = momentoActual.getMinutes()
    segundo = momentoActual.getSeconds()

    horaImprimible = hora + " : " + minuto
    $("#hora_sistema").html(horaImprimible);
    setTimeout("mueveReloj()",1000)
}
function funcionInicio(){
	//document.getElementById('nitCliente').focus();
}

function number_format(amount, decimals) {
    amount += ''; // por si pasan un numero en vez de un string
    amount = parseFloat(amount.replace(/[^0-9\.-]/g, '')); // elimino cualquier cosa que no sea numero o punto
    decimals = decimals || 0; // por si la variable no fue fue pasada
    // si no es un numero o es igual a cero retorno el mismo cero
    if (isNaN(amount) || amount === 0) 
        return parseFloat(0).toFixed(decimals);
    // si es mayor o menor que cero retorno el valor formateado como numero
    amount = '' + amount.toFixed(decimals);
    var amount_parts = amount.split('.'),
        regexp = /(\d+)(\d{3})/;
    while (regexp.test(amount_parts[0]))
        amount_parts[0] = amount_parts[0].replace(regexp, '$1' + ',' + '$2');
    return amount_parts.join('.');
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
	var stock=0;
	if($("#solo_stock").is(":checked")){
		stock=1;
	}
	var contenedor;
	var codTipo=f.itemTipoMaterial.value;
	var codForma=f.itemFormaMaterial.value;
	var codAccion=f.itemAccionMaterial.value;
	var codPrincipio=f.itemPrincipioMaterial.value;
	var nombreItem=f.itemNombreMaterial.value;
	var tipoSalida=(f.tipoSalida.value);
	var codigoMat=(f.itemCodigoMaterial.value);

	var nomAccion=f.itemAccionMaterialNom.value;
	var nomPrincipio=f.itemPrincipioMaterialNom.value;

   if(nomAccion==""&&nomPrincipio==""&&codigoMat==""&&nombreItem==""){
     alert("Debe ingresar un criterio de busqueda");
   }else{
	contenedor = document.getElementById('divListaMateriales');
    contenedor.innerHTML="<br><br><br><br><br><br><p class='text-muted'style='font-size:50px'>Buscando Producto(s)...</p>";
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
	ajax.open("GET", "ajaxListaMateriales.php?codigoMat="+codigoMat+"&codTipo="+codTipo+"&nombreItem="+nombreItem+"&arrayItemsUtilizados="+arrayItemsUtilizados+"&tipoSalida="+tipoSalida+"&codForma="+codForma+"&codAccion="+codAccion+"&codPrincipio="+codPrincipio+"&codProv="+codTipo+"&stock="+stock+"&nomAccion="+nomAccion+"&nomPrincipio="+nomPrincipio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {			
			contenedor.innerHTML = ajax.responseText;
			var oRows = document.getElementById('listaMaterialesTabla').getElementsByTagName('tr');
            var nFilas = oRows.length;					
			if(parseInt(nFilas)==2){
				if(ajax.responseText!=""){
				  document.getElementsByClassName('enlace_ref')[0].click();	
				}				
				//$(".enlace_ref").click();
			}
			//
			document.getElementById('itemCodigoMaterial').focus();				
		}		
	}
	ajax.send(null)
   }


}

function limpiarFormularioBusqueda(){
	$("#itemTipoMaterial").val("0");
	$("#itemFormaMaterial").val("0");
	$("#itemAccionMaterial").val("0");
	$("#itemPrincipioMaterial").val("0");
	$("#itemAccionMaterialNom").val("");
	$("#itemPrincipioMaterialNom").val("");
	$("#itemNombreMaterial").val("");
	//$("#tipoSalida").val("0");
	$("#itemCodigoMaterial").val("");
	$(".selectpicker").selectpicker("refresh");
	$("#solo_stock").prop( "checked", true );
	$("#divListaMateriales").html("");

	
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
    if(codmat>0){
      console.log("CodMat:"+codmat+" Indice:"+indice+" Alma:"+codalm)
	  ajax=nuevoAjax();
	  ajax.open("GET", "ajaxStockSalidaMateriales.php?codmat="+codmat+"&codalm="+codalm+"&indice="+indice,true);
	  ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//console.log(ajax.responseText);
			//alert(ajax.responseText);
			$("#idstock"+indice).html(ajax.responseText);
			//contenedor.innerHTML = ajax.responseText;
			if($("#stock"+indice).val()<=0){
				$("#div"+indice).attr("style","background:#F5B7B1");
				$("#stock"+indice).attr("style","font-size:18px;background: #A2A29F;height: 30px;");
			}

			ajaxCargarSelectDescuentos(indice);
		 }
	  }
	  ajax.send(null);
	  //verificarReceta(codmat,indice);
	  totales();	
    }
}
function actStockSinBucle(indice){
	var contenedor;
	contenedor=document.getElementById("idstock"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
    var codalm=document.getElementById("global_almacen").value;
    if(codmat>0){
      console.log("CodMat:"+codmat+" Indice:"+indice+" Alma:"+codalm)
	  ajax=nuevoAjax();
	  ajax.open("GET", "ajaxStockSalidaMateriales.php?codmat="+codmat+"&codalm="+codalm+"&indice="+indice,true);
	  ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			//console.log(ajax.responseText);
			//alert(ajax.responseText);
			$("#idstock"+indice).html(ajax.responseText);
		}
	  }
	  ajax.send(null);	
    }
}
function ajaxCargarSelectDescuentos(indice){
	var contenedor;
	contenedor=document.getElementById("idprecio"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
	var tipoPrecio=document.getElementById("tipoPrecio"+indice).value;
	var cantidadUnitaria=document.getElementById("cantidad_unitaria"+indice).value;
	var fecha=document.getElementById("fecha").value;	
	var ajaxNue=nuevoAjax();
	ajaxNue.open("GET", "ajaxLoadDescuento.php?codmat="+codmat+"&indice="+indice+"&tipoPrecio="+tipoPrecio+"&fecha="+fecha,true);
	ajaxNue.onreadystatechange=function() {
		if (ajaxNue.readyState==4) {
			var respuesta=ajaxNue.responseText.split("#####");
			document.getElementById("tipoPrecio"+indice).innerHTML=respuesta[2];
            //$("#tipoPrecio"+indice).html(respuesta[2]);
			ajaxPrecioItem(indice);		
		}
	}
	ajaxNue.send(null);	
}

/*function ajaxPrecioItem(indice){
	var contenedor;
	contenedor=document.getElementById("idprecio"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
	var tipoPrecio=document.getElementById("tipoPrecio").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxPrecioItem.php?codmat="+codmat+"&indice="+indice+"&tipoPrecio="+tipoPrecio,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			calculaMontoMaterial(indice);
		}
	}
	ajax.send(null);
}*/

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
			ajaxClienteBuscar();
			ajaxVerificarNitCliente();			
		}
	}
	ajax.send(null);
}
function ajaxVerificarNitCliente(){
	$("#siat_error").attr("style","");
	$("#siat_error_valor").val(0);
	$("#siat_error").html("Verificando existencia del NIT...");
	var parametros={"nit":$("#nitCliente").val()};
	$.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajaxVerificarNitSiatCliente.php",
        data: parametros,
        success:  function (resp) {          
           var r=resp.split("#####");
           $("#siat_error").html(r[1]);  

           if(r[2]=="1"){
           		$("#siat_error").attr("style","background:white;color:green;padding:10px;border-radius:10px;font-weight:bold;margin-left:220px;height:40px;font-size:30px;");
           		$("#tipo_documento").val(5);
           		$("#siat_error_valor").val(1);
           }else{
           	
           		$("#siat_error").attr("style","background:white;color:#5E5E5E;padding:10px;border-radius:10px;font-weight:bold;margin-left:220px;height:40px;font-size:30px;");
           		// if ($("#tipo_documento").val()!=2){
           		// 	$("#tipo_documento").val(1);           		           		
           		// }
           		
           }
           mostrarComplemento();
           $("#tipo_documento").selectpicker("refresh");                        	   
        }
    });	
}

function ajaxRazonSocialCliente(f){
	var contenedor;
	contenedor=document.getElementById("divRazonSocial");
	var cliente=document.getElementById("cliente").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxRazonSocialCliente.php?cliente="+cliente,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			if(cliente!=146){
				contenedor.innerHTML = ajax.responseText;
			}			
			document.getElementById('razonSocial').focus();
			if($("#cliente").val()==146){
				$("#razonSocial").attr("readonly",false);								
			}else{
				$("#razonSocial").attr("readonly",true);	
			}
			
		}
	}
	ajax.send(null);
}



function ajaxNitCliente(f){
	var contenedor;
	var nitCliente=document.getElementById("nitCliente").value;
	//if(nitCliente>0){

	//}else{
	 /*contenedor=document.getElementById("divNIT");
	 var rsCliente=document.getElementById("razonSocial").value;
	 ajaxNit=nuevoAjax();
	 ajaxNit.open("GET", "ajaxNitCliente.php?rsCliente="+rsCliente,true);
	 ajaxNit.onreadystatechange=function() {
		if (ajaxNit.readyState==4) {
			contenedor.innerHTML = ajaxNit.responseText;
			//document.getElementById('razonSocial').focus();
			ajaxClienteBuscar();
		}
	 }
	 ajaxNit.send(null);*/		
	//}
	//ajaxClienteBuscar();
}

function ajaxClienteBuscar(f){
	var contenedor;
	contenedor=document.getElementById("divCliente");
	var nitCliente=document.getElementById("nitCliente").value;
	ajax=nuevoAjax();
	ajax.open("GET", "ajaxClienteLista.php?nitCliente="+nitCliente,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			var datos_resp=ajax.responseText.split("####");
			//alert(datos_resp[1])
			//$("#cliente").val(datos_resp[1]);			
			$("#cliente").html(datos_resp[1]);				
			ajaxRazonSocialCliente(document.getElementById('form1'));
			$("#cliente").selectpicker('refresh');
		}
	}
	ajax.send(null);
}

function calculaMontoMaterial(indice){

	var cantidadUnitaria=document.getElementById("cantidad_unitaria"+indice).value;
	var precioUnitario=document.getElementById("precio_unitario"+indice).value;
	var descuentoUnitario=document.getElementById("descuentoProducto"+indice).value;
	
	/*var montoUnitario=(parseFloat(cantidadUnitaria)*parseFloat(precioUnitario)) -(parseFloat(cantidadUnitaria)*parseFloat(descuentoUnitario));*/
	var montoUnitario=(parseFloat(cantidadUnitaria)*parseFloat(precioUnitario))-(descuentoUnitario);
	montoUnitario=Math.round(montoUnitario*100)/100
		
	document.getElementById("montoMaterial"+indice).value=montoUnitario;
	if(!$("#stock"+indice).val()>0){
		actStockSinBucle(indice);
	}
	totales();
}


// Conclusión
(function() {
  /**
   * Ajuste decimal de un número.
   *
   * @param {String}  tipo  El tipo de ajuste.
   * @param {Number}  valor El numero.
   * @param {Integer} exp   El exponente (el logaritmo 10 del ajuste base).
   * @returns {Number} El valor ajustado.
   */
  function decimalAdjust(type, value, exp) {
    // Si el exp no está definido o es cero...
    if (typeof exp === 'undefined' || +exp === 0) {
      return Math[type](value);
    }
    value = +value;
    exp = +exp;
    // Si el valor no es un número o el exp no es un entero...
    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
      return NaN;
    }
    // Shift
    value = value.toString().split('e');
    value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
  }

  // Decimal round
  if (!Math.round10) {
    Math.round10 = function(value, exp) {
      return decimalAdjust('round', value, exp);
    };
  }
  // Decimal floor
  if (!Math.floor10) {
    Math.floor10 = function(value, exp) {
      return decimalAdjust('floor', value, exp);
    };
  }
  // Decimal ceil
  if (!Math.ceil10) {
    Math.ceil10 = function(value, exp) {
      return decimalAdjust('ceil', value, exp);
    };
  }
})();

function totales(){	
	

var errores=0;
// for(var ii=1;ii<=num;ii++){

// 	if(document.getElementById("materiales"+ii)){
// 		var cod_material = document.getElementById("materiales"+ii).value;
// 		$("#cod_material"+ii).attr("class","textomedianonegro");	
// 	}else{
// 		var cod_material = null;
// 	}	
// 	if( isNaN(cod_material)||cod_material == null || cod_material.length == 0) {
//   	errores++;
//   	$("#cod_material"+ii).attr("class","textomedianonegro btn-danger");
// 	}

// 	if(document.getElementById("cantidad_unitaria"+ii)){
// 		var cantidad = document.getElementById("cantidad_unitaria"+ii).value;	
// 	}else{
// 		var cantidad = null;
// 	}	
// 	if( isNaN(cantidad)||cantidad == null || cantidad.length == 0) {
//   	errores++;
//   	$("#cod_material"+ii).attr("class","textomedianonegro btn-warning");
// 	}

// 	if(document.getElementById("stock"+ii)){
// 		var stock = document.getElementById("stock"+ii).value;	
// 	}else{
// 		var stock = null;
// 	}		
// 	if( isNaN(stock)||stock == null || stock.length == 0) {
//   	errores++;
//   	$("#cod_material"+ii).attr("class","textomedianonegro btn-warning");
// 	}

// 	if(document.getElementById("precio_unitario"+ii)){
// 		var precio = document.getElementById("precio_unitario"+ii).value;	
// 	}else{
// 		var precio = null;
// 	}		
// 	if( isNaN(precio)||precio == null || precio.length == 0 ) {
//   	errores++;
//   	$("#cod_material"+ii).attr("class","textomedianonegro btn-warning");
// 	}

// 	if(document.getElementById("descuentoProducto"+ii)){
// 		var descuento = document.getElementById("descuentoProducto"+ii).value;
// 	}else{
// 		var descuento = null;
// 	}	
// 	if( isNaN(descuento)||descuento == null || descuento.length == 0) {
//   	errores++;
//   	$("#cod_material"+ii).attr("class","textomedianonegro btn-warning");
// 	}

// 	if(document.getElementById("montoMaterial"+ii)){
// 		var monto = document.getElementById("montoMaterial"+ii).value;
// 	}else{
// 		var monto = null;
// 	}	
// 	if( isNaN(monto)||monto == null || monto.length == 0) {
//   	errores++;
//   	$("#cod_material"+ii).attr("class","textomedianonegro btn-warning");
// 	}
// }

if(errores>0){
	$("#btsubmit").removeAttr("type");
	$("#btsubmit").attr("class","btn btn-danger");
	$("#btsubmit").html("ERROR(ES) EN PRODUCTOS");
	$("#btsubmit").attr("onclick","Swal.fire('Alerta de Seguridad!!!','<b>ERROR(ES) EN PRODUCTO(S) <BR><h2 class=\"bg-warning\">VERIFIQUE LOS ESTADOS AMARILLOS: STOCK, CANTIDAD, PRECIO, DESC., MONTO </h2><b><hr><br> <small class=\"text-muted\">Si no encuentra los errores por favor actualize el formulario con <br>(CTRL + F5)</small>','error'); return false;");
	document.getElementById("totalVenta").value=0;
	document.getElementById("totalFinal").value=0;
}else{
	$("#btsubmit").attr("type","submit");
	$("#btsubmit").attr("class","btn btn-warning");
	$("#btsubmit").html("Guardar Venta");
	$("#btsubmit").attr("onclick","return validar(this.form, 1010,0)");
		var subtotal=0;
    for(var ii=1;ii<=num;ii++){
	 	if(document.getElementById('materiales'+ii)!=null){
			var monto=document.getElementById("montoMaterial"+ii).value;
			subtotal=subtotal+parseFloat(monto);
		}
    }
    var subtotalPrecio=0;
    for(var ii=1;ii<=num;ii++){
	 	if(document.getElementById('materiales'+ii)!=null){
			var precio=document.getElementById("precio_unitario"+ii).value;
			var cantidad=document.getElementById("cantidad_unitaria"+ii).value;
			subtotalPrecio=subtotalPrecio+parseFloat(precio*cantidad);
		}
    }

    document.getElementById("total_precio_sin_descuento").innerHTML=subtotalPrecio;

    subtotalPrecio=Math.round(subtotalPrecio*100)/100;
    subtotal=subtotal.toFixed(2); //para que redonde bien si cae en 5
    //alert("Subtotal"+subtotal);
	subtotal=Math.round(subtotal*10)/10;
	
	subtotal=subtotal.toFixed(2); 
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
	cargarDescuentoTotalVenta(subtotal);


}
   
	
}
function cargarDescuentoTotalVenta(total){
	var parametros={"monto_total":total};
	$.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajaxDescuentoGeneralVenta.php",
        data: parametros,
        success:  function (resp) { 
           var r=resp.split("#####");
           $("#codigoDescuentoGeneral").val(r[1]); 
           $("#descuentoVenta").val(r[2]); 
           $("#porcentajeDescuentoRealNombre").html(r[3]); 
           $("#porcentajeDescuentoRealNombre2").html("<small>"+r[3]+"</small> %"); 
           aplicarDescuento();       	   
        }
    });	
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
  aplicarCambioEfectivo(f);
}

function verCambio(f){
	var totalFinal=document.getElementById("totalFinal").value;
	var totalEfectivo=document.getElementById("totalEfectivo").value;
	var totalCambio=totalEfectivo-totalFinal;
	totalCambio=number_format(totalCambio,2);
	
	document.getElementById("totalCambio").value=totalCambio;
	
}
function buscarMaterial(f, numMaterial){
	f.materialActivo.value=numMaterial;
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
	document.getElementById('divboton').style.visibility='visible';
	
	document.getElementById('divListaMateriales').innerHTML='';
	document.getElementById('itemNombreMaterial').value='';	
	//document.getElementById('itemNombreMaterial').focus();
	document.getElementById('itemCodigoMaterial').value='';	
	document.getElementById('itemCodigoMaterial').focus();		
	
}
function openNav() {
  document.getElementById("mySidenav").style.width = "550px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}
</script>
<?php 
$rpt_territorio=$_COOKIE['global_agencia'];
$rpt_almacen=$_COOKIE['global_almacen'];
$fecha_inicio="01/".date("m/Y");
$fecha_actual=date("d/m/Y");
 echo "#rpt_territorio".$rpt_territorio;
 echo "#rpt_almacen".$rpt_almacen;
 echo "#fecha_inicio".$fecha_inicio;
 echo "#fecha_actual".$fecha_actual;
?>
<script>
function buscarKardexProducto(f, numMaterial){
	window.open('rpt_inv_kardex.php?rpt_territorio=<?=$rpt_territorio?>&rpt_almacen=<?=$rpt_almacen?>&fecha_ini=<?=$fecha_inicio?>&fecha_fin=<?=$fecha_actual?>&tipo_item=2&rpt_item='+$("#materiales"+numMaterial).val()+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');		
}

var tablaBuscadorSucursales=null;
function encontrarMaterial(numMaterial){
	var cod_material = $("#materiales"+numMaterial).val();
	var parametros={"cod_material":cod_material};
	$.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajax_encontrar_productos.php",
        data: parametros,
        success:  function (resp) { 
           // alert(resp);           
        	$("#modalProductosCercanos").modal("show");
        	$("#tabla_datos").html(resp);   
        }
    });	
}

function similaresMaterial(numMaterial){
	$("#materialActivo").val(numMaterial);
	var cod_material = $("#materiales"+numMaterial).val();
	var parametros={"cod_material":cod_material};
	$.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajax_encontrar_productos_similares.php",
        data: parametros,
        success:  function (resp) {          
        	$("#modalProductosSimilares").modal("show");
        	$("#tabla_datos_similares").html(resp);    	   
        }
    });	
}

function Hidden(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';

}
function setMaterialesSimilar(f, cod, nombreMat,cantPre='1',divi='1'){	
	var numRegistro=f.materialActivo.value;
	$("#cantidad_presentacionboton"+numRegistro).css("color","#EC341B");
	if(divi==1){
      $("#cantidad_presentacionboton"+numRegistro).css("color","#969393");
	}

	var imagen='<img src="imagenes/card.png" width="40">';

	document.getElementById('materiales'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat+" ("+cod+")";
	document.getElementById('cantidad_presentacion'+numRegistro).value=cantPre;
	document.getElementById('divi'+numRegistro).value=divi;
	document.getElementById('cantidad_presentacionboton'+numRegistro).innerHTML=cantPre;
	document.getElementById("cantidad_unitaria"+numRegistro).focus();
	document.getElementById("cantidad_unitaria"+numRegistro).select();
    $("#modalProductosSimilares").modal("hide");
    $(".boton2peque").removeAttr("accessKey");
    document.getElementById("removeFila"+numRegistro).accessKey= "q";
    actStock(numRegistro);
    obtenerLineaMaterial(cod,numRegistro);
}

function obtenerLineaMaterial(cod,numRegistro){
	var parametros={"codigo":cod};
    $.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajaxLineaProducto.php",
        data: parametros,
        success:  function (resp) { 
            $("#cod_material"+numRegistro).attr("title",resp);                             	   
            $("#cod_material"+numRegistro).append("<br><small class='text-primary'>"+resp+"</small>");                             	   
        }
    });	
}

function setMateriales(f, cod, nombreMat,cantPre='1',divi='1'){
	var numRegistro=f.materialActivo.value;
	$("#cantidad_presentacionboton"+numRegistro).css("color","#EC341B");
	if(divi==1){
      $("#cantidad_presentacionboton"+numRegistro).css("color","#969393");
	}		
  var imagen='<img src="imagenes/card.png" width="40">';
	$(".boton2peque").removeAttr("accessKey");
	document.getElementById('materiales'+numRegistro).value=cod;
	document.getElementById('cod_material'+numRegistro).innerHTML=nombreMat+" ("+cod+")";
	document.getElementById('cantidad_presentacion'+numRegistro).value=cantPre;
	document.getElementById('divi'+numRegistro).value=divi;
	
	document.getElementById('cantidad_presentacionboton'+numRegistro).innerHTML=cantPre;
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';
	
	document.getElementById("cantidad_unitaria"+numRegistro).focus();
	document.getElementById("cantidad_unitaria"+numRegistro).select();
	document.getElementById("removeFila"+numRegistro).accessKey= "q";
    actStock(numRegistro);
    obtenerLineaMaterial(cod,numRegistro);
}
function verificarReceta(cod,numRegistro){
	ajax=nuevoAjax();
	ajax.open("GET","ajaxMaterialReceta.php?fila="+numRegistro+"&codigo="+cod,true);

	ajax.onreadystatechange=function(){
	   if (ajax.readyState==4) {
	   //	alert(ajax.responseText);
	   	if(parseInt(ajax.responseText)==0){
          if(!$("#receta_boton"+numRegistro).hasClass("d-none")){
            $("#receta_boton"+numRegistro).addClass("d-none");
          }
	   	}else{
	   	   if($("#receta_boton"+numRegistro).hasClass("d-none")){
            $("#receta_boton"+numRegistro).removeClass("d-none");
          }	
	   	}	   	
	   }
   }		
   ajax.send(null);
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
function ajaxPrecioItem(indice){
	var contenedor;
	contenedor=document.getElementById("idprecio"+indice);
	var codmat=document.getElementById("materiales"+indice).value;
	var tipoPrecio=document.getElementById("tipoPrecio"+indice).value;
	var cantidadUnitaria=document.getElementById("cantidad_unitaria"+indice).value;
	var fecha=document.getElementById("fecha").value;	
	var ajaxe=nuevoAjax();
	ajaxe.open("GET", "ajaxPrecioItem.php?codmat="+codmat+"&indice="+indice+"&tipoPrecio="+tipoPrecio+"&fecha="+fecha+"&cantidad_unitaria="+cantidadUnitaria,true);
	ajaxe.onreadystatechange=function() {
		if (ajaxe.readyState==4) {
			var respuesta=ajaxe.responseText.split("#####");
			contenedor.innerHTML = respuesta[0];
            document.getElementById("descuentoProducto"+indice).value=respuesta[1];
            if($("#descuentoProducto"+indice).val()>0){
              $("#tipoPrecio"+indice).css("background","#C0392B");
            }else{
              $("#tipoPrecio"+indice).css("background","#85929E");
            }       
            $('[data-toggle="tooltip"]').tooltip({
              animated: 'swing', //swing expand
              placement: 'top',
              html: true,
              trigger : 'hover'
            });      
			calculaMontoMaterial(indice);			
		}
	}
	ajaxe.send(null);
}
function mas(obj) {
	if(num>=1000){
		alert("No puede registrar mas de 15 items en una nota.");
	}else{
		var fila_actual=0;
		//aca validamos que el item este seleccionado antes de adicionar nueva fila de datos
		var banderaItems0=0;
		for(var j=1; j<=num; j++){
			if(document.getElementById('materiales'+j)!=null){
				if(document.getElementById('materiales'+j).value==0){
					banderaItems0=1;
					fila_actual=j;
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
			contenedor = document.createElement('tr');
			contenedor.id = 'div'+num;  
			fi.type="style";
			fi.appendChild(contenedor);
			var div_material;
			div_material=document.getElementById("div"+num);	
			var cod_precio=document.getElementById("tipoPrecio").value;
			var fecha=document.getElementById("fecha").value;				
			var ajaxFila=nuevoAjax();
			ajaxFila.open("GET","ajaxMaterialVentas.php?codigo="+num+"&cod_precio="+cod_precio+"&fecha="+fecha,true);

			ajaxFila.onreadystatechange=function(){
				if (ajaxFila.readyState==4) {
					div_material.innerHTML=ajaxFila.responseText;
					$('.selectpicker').selectpicker('refresh');
					buscarMaterial(form1, num);
				}
			}		
			ajaxFila.send(null);
		}else{
			buscarMaterial(obj.form,fila_actual);
			
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
	    listaMateriales(f);	
	    //$("#enviar_busqueda").click();
	    //$("#enviar_busqueda").click();//Para mejorar la funcion	
	    return false;    	   	    	
		//listaMateriales(f);			
	}
}


	
	
function checkSubmit() {
    document.getElementById("btsubmit").value = "Enviando...";
    document.getElementById("btsubmit").disabled = true;
    document.getElementById("btsubmitPedido").value = "Enviando...";
    document.getElementById("btsubmitPedido").disabled = true;
    return true;
}

$(document).ready(function() {
  $("#guardarSalidaVenta").submit(function(e) {
      var mensaje="";
      if(parseFloat($("#efectivoRecibido").val())<parseFloat($("#totalFinal").val())){
        mensaje+="<p></p>";
        alert("El monto recibido NO debe ser menor al monto total");
        return false;
      }else{

      	//var confirmacionRealizada=$("#confirmacion_guardado").val();
      	var confirmacionRealizada=document.getElementById("confirmacion_guardado").value;
      	//alert(confirmacionRealizada);
      	console.log("Datos confirm:"+confirmacionRealizada+"")
      	if(parseInt(confirmacionRealizada)==1){
      		document.getElementById("btsubmit").innerHTML = "Enviando...";
            document.getElementById("btsubmit").disabled = true;
      		return true;      		
      	}else{
      		return guardarVentaGeneral();
      	}
      }     
    });
});	

$("body").on("submit", "form", function() {
    $(this).submit(function() {
        return false;
    });
    return true;
});

function alterna_modo_de_pantalla() {
  if ((document.fullScreenElement && document.fullScreenElement !== null) ||    // metodo alternativo
      (!document.mozFullScreen && !document.webkitIsFullScreen)) {               // metodos actuales
    if (document.documentElement.requestFullScreen) {
      document.documentElement.requestFullScreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullScreen) {
      document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
    }
  } else {
    if (document.cancelFullScreen) {
      document.cancelFullScreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitCancelFullScreen) {
      document.webkitCancelFullScreen();
    }
  }
}

</script>
<?php
echo "</head><body onLoad='funcionInicio();'>";
require("conexionmysqli.inc");
require("estilos_almacenes.inc");
require("funciones.php");
?>
<script>
function validarSesionSucursalVacio(){
	var dato=0;
	var parametros={"n":1};
    $.ajax({
        type: "GET",
        dataType: 'html',
        url: "validarSesionCookieSucursal.php",
        data: parametros,
        async:false,
        success:  function (resp) {
          dato=resp;     
        }
    });
    return dato;
}
function validarSuminstrosSesion(){
	var dato=0;
	var parametros={"n":1};
    $.ajax({
        type: "GET",
        dataType: 'html',
        url: "validarSesionCookieSucursalSuministros.php",
        data: parametros,
        async:false,
        success:  function (resp) {
          dato=resp;     
        }
    });
    return dato;
}	
function validar(f, ventaDebajoCosto,pedido){

	if(validarSesionSucursalVacio()==0){
			errores++;
			Swal.fire("Error", "Cierre de sesión detectado!!. Vuelva a Iniciar Sesión y seleccione la Sucursal.", "error");
			$("#pedido_realizado").val(0);
			return(false);
	}
	if(validarSuminstrosSesion()==0){
			errores++;
			Swal.fire("Error", "No se puede realizar Ventas si está en el MODULO DE SUMINISTROS!", "error");
			$("#pedido_realizado").val(0);
			return(false);
	}

	if(parseFloat($("#totalFinal").val())>1000&&$("#nitCliente").val()=="123"){
			errores++;
			Swal.fire("Error", "El monto es superior a 1,000 se requiere un numero de NIT / CI / CEX válido", "error");
			$("#pedido_realizado").val(0);
			return(false);
	}

	if($("#nitCliente").val()=="0"){
			errores++;
			Swal.fire("Nit!", "Se requiere un numero de NIT / CI / CEX válido", "warning");
			$("#pedido_realizado").val(0);
			return(false);
	}

	///////////////////////////////////////////// 
	if($("#nro_tarjeta").val().length!=16&&$("#nro_tarjeta").val()!=""){
			errores++;
			Swal.fire("Tarjeta!", "El número de Tarjeta debe tener 16 digitos<br><br><b>Ej: 1234********1234</b>", "info");
			$("#pedido_realizado").val(0);
			return(false);
	}

  if(pedido==1){
  	$("#pedido_realizado").val(pedido);
  }	
  var pedidoFormu=parseInt($("#pedido_realizado").val());
	//alert(ventaDebajoCosto);
	f.cantidad_material.value=num;
	var cantidadItems=num;
	var cantidadItemsReal=0;
	var itemsStock0=0;
	for(var i=1; i<=cantidadItems; i++){
			if(document.getElementById("materiales"+i)!=null){
				if(document.getElementById("stock"+i)){
					if(document.getElementById("stock"+i).value==0){
				  	itemsStock0++;
				  }
				}else{
					itemsStock0++;
				}
				cantidadItemsReal++;	
			}
    }
    if(pedidoFormu==2){ 
    	//VALIDA SI SOLO SE AGREGAN STOCK EN 0 PARA YA NO FACTURAR (OPCION GUARDAR Y PEDIR)
    	cantidadItems=cantidadItems-itemsStock0;
    }
	console.log("numero de items: "+cantidadItems);
	var errores=0;
	if(cantidadItemsReal>0){
		var validacionClientes=0;
		if(parseInt($("#validacion_clientes").val())!=0){
          if($("#cliente").val()==146||$("#cliente").val()==""){  //146 clientes varios
            validacionClientes=1;
          }
		}	
		if(validacionClientes==0){
      var item="";
		  var cantidad="";
		  var cantidadPres="";
		  var divi=0;
		  var stock="";
		  var descuento="";					
		 for(var i=1; i<=cantidadItems; i++){
			console.log("valor i: "+i);
			console.log("objeto materiales: "+document.getElementById("materiales"+i));
			if(document.getElementById("materiales"+i)!=null){
				item=parseFloat(document.getElementById("materiales"+i).value);
				cantidad=parseFloat(document.getElementById("cantidad_unitaria"+i).value);
				cantidadPres=parseFloat(document.getElementById("cantidad_presentacion"+i).value);
				divi=parseInt(document.getElementById("divi"+i).value);
				
				//VALIDACION DE VARIABLE DE STOCK QUE NO SE VALIDA
				if(document.getElementById("stock"+i)){
					stock=document.getElementById("stock"+i).value;					
				}else{
					stock=0;
				}

				if(stock=="-"){
					stock=10000;
				}else{ //validacion
					if(document.getElementById("stock"+i)){
					  stock=parseFloat(document.getElementById("stock"+i).value);			
				  }else{
					  stock=0;
				  }
				  if(stock>0){
				  	//no hacer nada
				  }else{
				  	stock=0;
				  }					
				}
				console.log("Stock_prod:"+stock+":FIn");
				// alert(stock+"");
				
				descuento=parseFloat(document.getElementById("descuentoProducto"+i).value);		

				if(document.getElementById("precio_unitario"+i)){
					precioUnit=parseFloat(document.getElementById("precio_unitario"+i).value);					
				}else{
					precioUnit=0;
				}
								
				if(document.getElementById("costoUnit"+i)){
					var costoUnit=parseFloat(document.getElementById("costoUnit"+i).value);					
				}else{
					var costoUnit=0;
				}

				
		
				console.log("materiales"+i+" valor: "+item);
				console.log("stock: "+stock+" cantidad: "+cantidad+ "precio: "+precioUnit);
                
        if(item==0){
					errores++;
					alert("Debe escoger un item en la fila "+i);
					$("#pedido_realizado").val(0);
					return(false);
				}

				if(($("#efectivoRecibidoUnido").val()==0||$("#efectivoRecibidoUnido").val()=="")&&$("#nitCliente").val()!=""&&$("#razonSocial").val()!=""&&($("#efectivoRecibidoUnidoUSD").val()==0||$("#efectivoRecibidoUnidoUSD").val()=="")){
					errores++;
					document.getElementById("efectivoRecibidoUnido").focus();
					document.getElementById("efectivoRecibidoUnido").select();
					alert("Debe registrar el monto recibido");

					$("#pedido_realizado").val(0);
					return(false);
				}
				//alert(costoUnit+" "+precioUnit);
				if(costoUnit>precioUnit && ventaDebajoCosto==0){
					errores++;
					alert('No puede registrar una venta a perdida!!!!');
					$("#pedido_realizado").val(0);
					return(false);
				}
				if(stock<cantidad&&pedidoFormu==0){
					errores++;
					document.getElementById("cantidad_unitaria"+i).focus();
					document.getElementById("cantidad_unitaria"+i).select();
					alert("No hay STOCK suficiente "+$("#cod_material"+i).html());					
					$("#pedido_realizado").val(0);
					return(false);
				}		
				if((cantidad<=0 || precioUnit<=0) || (Number.isNaN(cantidad)) || Number.isNaN(precioUnit)){
					errores++;
					alert("La cantidad y/o Precio no pueden estar vacios o ser <= 0.");
					$("#pedido_realizado").val(0);
					return(false);
				}

				if(divi==0&&cantidadPres>0&&(cantidad%cantidadPres)!=0){
					errores++;
					document.getElementById("cantidad_unitaria"+i).focus();
					document.getElementById("cantidad_unitaria"+i).select();
					alert("El item de la fila "+i+" no es divisible!, la cantidad unitaria debe ser multiple a la cantidad de presentación");					
					$("#pedido_realizado").val(0);
					return(false);
				}
			}
		  }
		  if(errores==0&&pedidoFormu==1){
		  	  guardarPedido(1);
              //guardarPedidoDesdeFacturacion(1);
              return false;
		  }else{
		  	var errores2=0;
		  	if($("#tipoVenta").val()==2){
		  	  if($("#nro_tarjeta").val()!=""){
                if(!($("#monto_tarjeta").val()>0)){
		  	       errores2++;
                   alert("Debe Ingresar el monto de la Tarjeta");
					$("#pedido_realizado").val(0);
				   return(false);
                }
		  	  }else{
		  	  	errores2++;
		  	  	alert("Debe Registrar los datos de la tarjeta");
					$("#pedido_realizado").val(0);
				   return(false);
		  	  }// fin nro de tarjeta		  	
		  	}else{
		  	  if($("#nro_tarjeta").val()!=""){
                errores2++;
                alert("Debe cambiar el TIPO DE PAGO a PAGO CON TARJETA");
				$("#pedido_realizado").val(0);
				return(false);
		  	  }	
		  	}
		  	//CONFIRMACION
		  	if(errores2==0){
		  		//return confirm('Quieres guardar la venta');
		  	}
		  }
		}else{
		  alert("Debe registrar el Cliente.");
		  $("#pedido_realizado").val(0);
		  return(false);
		}		
	}else{
		if(pedidoFormu==2){
			location.href='navegadorPedidos.php';
		    return(false);
		}else{
			alert("El ingreso debe tener al menos 1 producto.");
		}		
		$("#pedido_realizado").val(0);
		return(false);
	}
}
/*$(document).ready(function(){
    $('#guardarSalidaVenta').on("submit",function(){
        guardarSalidaVenta();
    });
});*/

var tipoVentaGlobal=1;
function cambiarTipoVenta2(){
	if(tipoVentaGlobal==1){
      $("#tipo_venta2").val(2);
      $("#boton_tipoventa2").html("<i class='material-icons' style='background: #652BE9;color:#fff;'>corporate_fare</i> TIPO DE VENTA INSTITUCIONAL");
      tipoVentaGlobal=2;
      $("#boton_tipoventa2").attr("style","background:#58E8F1");
	}else{
      $("#tipo_venta2").val(1);
      tipoVentaGlobal=1;
      $("#boton_tipoventa2").html("<i class='material-icons' style='background: #652BE9;color:#fff;'>point_of_sale</i> TIPO DE VENTA CORRIENTE");
      $("#boton_tipoventa2").attr("style","background:#fff;color:#000;");
	}
  
}

function cambiarDelivery(tipo){
	$("#tipo_ventadelivery").val(tipo);	
	if(tipo==1){
			$("#boton_tipoventadelivery").html("<img src='imagenes/yaigo.png' class='bw' width='30px'>   Yaigo");
      $("#boton_tipoventadelivery2").html("<img src='imagenes/py_icono.png' class='bw grey' width='30px'>   Pedidos Ya!");
      $("#boton_tipoventadelivery3").html("<img src='imagenes/sucursal.png' class='bw grey' width='30px'>   Sucursal Virtual");
      $("#venta_detalle").attr("style","color:#fff;background:#544DBF !important; font-size: 16px;");
      $("#mensaje_venta").html("<img src='imagenes/yaigo.png' width='50' height='50'> <b style='font-size:14px;color:#544DBF;'>YAIGO</b>");
 }else if(tipo==2){
 			$("#boton_tipoventadelivery2").html("<img src='imagenes/py_icono.png' class='bw' width='30px'>   Pedidos Ya!");
      $("#boton_tipoventadelivery").html("<img src='imagenes/yaigo.png' class='bw grey' width='30px'>   Yaigo");
      $("#boton_tipoventadelivery3").html("<img src='imagenes/sucursal.png' class='bw grey' width='30px'>   Sucursal Virtual");
      $("#venta_detalle").attr("style","color:#fff;background:#E10659 !important; font-size: 16px;");
      $("#mensaje_venta").html("<img src='imagenes/py_icono.png' width='50' height='50'> <b style='font-size:14px;color:#E10659;'>PEDIDOS YA!</b>");
 }else if(tipo==3){
 			$("#boton_tipoventadelivery3").html("<img src='imagenes/sucursal.png' class='bw' width='30px'>   Sucursal Virtual");
 			$("#boton_tipoventadelivery2").html("<img src='imagenes/py_icono.png' class='bw grey' width='30px'>   Pedidos Ya!");
      $("#boton_tipoventadelivery").html("<img src='imagenes/yaigo.png' class='bw grey' width='30px'>   Yaigo");
      $("#venta_detalle").attr("style","color:#fff;background:#E66608 !important; font-size: 16px;");
      $("#mensaje_venta").html("<img src='imagenes/sucursal.png' width='50' height='50'> <b style='font-size:14px;color:#E66608;'>SUCURSAL VIRTUAL</b>");
 }else{
 			$("#boton_tipoventadelivery").html("<img src='imagenes/yaigo.png' class='bw grey' width='30px'>   Yaigo");
      $("#boton_tipoventadelivery2").html("<img src='imagenes/py_icono.png' class='bw grey' width='30px'>   Pedidos Ya!");
      $("#boton_tipoventadelivery3").html("<img src='imagenes/sucursal.png' class='bw grey' width='30px'>   Sucursal Virtual");
      $("#venta_detalle").attr("style","color:#fff;background:#00ccb6 !important; font-size: 16px;");
      $("#mensaje_venta").html("");
 }
}

function registrarNuevoCliente(){
	$("#nomcli").val("");
  $("#apcli").val("");
  $("#ci").val("");
  $("#nit").val("");
  $("#dir").val("");
  $("#tel1").val("");
  $("#mail").val("");
  $("#fact").val("");
	if($("#nitCliente").val()!=""){
		$("#nit").val($("#nitCliente").val());
		//$("#nomcli").val($("#razonSocial").val());
		$("#fact").val($("#razonSocial").val());
		$("#boton_guardado_cliente").attr("onclick","adicionarCliente()");		
		$("#titulo_cliente").html("NUEVO CLIENTE");
		$("#modalNuevoCliente").modal("show");
	}else{
		alert("Ingrese el NIT para registrar el cliente!");
	}	
}
function editarDatosClienteRegistro(){
	if($("#cliente").val()!=146){
		var parametros={"cliente":$("#cliente").val()};
		$.ajax({
	        type: "GET",
	        dataType: 'html',
	        url: "ajaxClienteEncontrar.php",
	        data: parametros,
	        success:  function (resp) { 
	        	// alert(resp)
	        	var r=resp.split("#####");
            $("#nomcli").val(r[1]);
				    $("#apcli").val(r[2]);
				    $("#ci").val(r[3]);
				    $("#nit").val(r[4]);
				    $("#dir").val(r[5]);
				    $("#tel1").val(r[6]);
				    $("#mail").val(r[7]);
				    $("#area").val(r[8]);
				    $("#fact").val(r[9]);
				    $("#edad").val(r[10]);
				    $("#genero").val(r[11]);       
	        	$("#boton_guardado_cliente").attr("onclick","editarDatosCliente()");
						$("#titulo_cliente").html("EDITAR CLIENTE");
						$("#edad").selectpicker("refresh");
				    $("#genero").selectpicker("refresh");   
						$("#modalNuevoCliente").modal("show");   	   
	        }
	    });
		
	}else{
		alert("Seleccione un cliente para editar");
	}	
}


function validarCorreoUnicoCliente(cliente,nit,correo){
	var dato=0;
	var parametros={"cliente":cliente,"nit":nit,"correo":correo};
    $.ajax({
        type: "GET",
        dataType: 'html',
        url: "validarCorreoUnicoCliente.php",
        data: parametros,
        async:false,
        success:  function (resp) {
          dato=resp;     
        }
    });
    return dato;
}



function adicionarCliente() {	
    var nomcli = $("#nomcli").val();
    var apcli = $("#apcli").val();
    var ci = $("#ci").val();
    var nit = $("#nit").val();
    var dir = $("#dir").val();
    var tel1 = $("#tel1").val();
    var mail = $("#mail").val();
    var area = $("#area").val();
    var fact = $("#fact").val();
    var edad = $("#edad").val();
    var genero = $("#genero").val();

  if(nomcli==""||nit==""||mail==""||tel1==""){
    Swal.fire("Informativo!", "Debe llenar los campos obligatorios", "warning");
  }else{
  	if(validarCorreoUnicoCliente(0,nit,mail)==0){
  			Swal.fire("Error!", "El cliente con correo: "+mail+", ya se encuentra registrado!", "error");
  	}else{
		    var parametros={"nomcli":nomcli,"nit":nit,"ci":ci,"dir":dir,"tel1":tel1,"mail":mail,"area":area,"fact":fact,"edad":edad,"apcli":apcli,"genero":genero,"dv":1};
		    $.ajax({
		        type: "GET",
		        dataType: 'html',
		        url: "programas/clientes/prgClienteAdicionar.php",
		        data: parametros,
		        success:  function (resp) {         	
		           var r=resp.split("#####");
		           if(parseInt(r[1])>0){           	
		           	  refrescarComboCliente(r[1]);   
		           	  $("#nomcli").val("");
							    $("#apcli").val("");
							    $("#ci").val("");
							    $("#nit").val("");
							    $("#dir").val("");
							    $("#tel1").val("");
							    $("#mail").val("");
							    $("#area").val("");
							    $("#fact").val("");
							    // $("#edad").val(0);
							    // $("#genero").val(0);

		           }else{
		           	//alert(resp);
		           	  $("#modalNuevoCliente").modal("hide"); 
		           	  Swal.fire("Error!", "Error al crear cliente", "error");
		           }            
		                            	   
		        }
		    });	
  	}
  }
}

function editarDatosCliente() {
	var cod_cliente = $("#cliente").val();
    var nomcli = $("#nomcli").val();
    var apcli = $("#apcli").val();
    var ci = $("#ci").val();
    var nit = $("#nit").val();
    var dir = $("#dir").val();
    var tel1 = $("#tel1").val();
    var mail = $("#mail").val();
    var area = $("#area").val();
    var fact = $("#fact").val();
    var edad = $("#edad").val();
    var genero = $("#genero").val();

  if(nomcli==""||nit==""||mail==""||tel1==""){
    Swal.fire("Informativo!", "Debe llenar los campos obligatorios", "warning");
  }else{
  	if(validarCorreoUnicoCliente(cod_cliente,nit,mail)==0){
  			Swal.fire("Error!", "El cliente con correo: "+mail+" y nit: "+nit+" ya se encuentra registrado!", "error");
  	}else{
		    var parametros={"nomcli":nomcli,"nit":nit,"ci":ci,"dir":dir,"tel1":tel1,"mail":mail,"area":area,"fact":fact,"edad":edad,"apcli":apcli,"genero":genero,"dv":1,"cod_cliente":cod_cliente};
		    $.ajax({
		        type: "GET",
		        dataType: 'html',
		        url: "programas/clientes/prgClienteEditar.php",
		        data: parametros,
		        success:  function (resp) { 
		           var r=resp.split("#####");
		           if(parseInt(r[1])>0){
		           		//alert(r[1]);
		           	  refrescarComboCliente(r[1]);   
		           	  $("#nomcli").val("");
							    $("#apcli").val("");
							    $("#ci").val("");
							    $("#nit").val("");
							    $("#dir").val("");
							    $("#tel1").val("");
							    $("#mail").val("");
							    $("#area").val("");
							    $("#fact").val("");
							    // $("#edad").val("");
							    // $("#genero").val("");

		           }else{
		           	  $("#modalNuevoCliente").modal("hide"); 
		           	  Swal.fire("Error!", "Error al editar cliente", "error");
		           }            
		                            	   
		        }
		    });	
  	}
  }
}

function guardarRecetaVenta(){
	$("#nom_doctor").val("");
	$("#ape_doctor").val("");
	$("#dir_doctor").val("");
	$("#mat_doctor").val("");
	$("#n_ins_doctor").val("");
	$("#nomcli").val($("#razonSocial").val());
	actualizarTablaMedicos("apellidos");
	$("#modalRecetaVenta").modal("show");
}

function nuevaInstitucion(){
  var institucion=$("#ins_doctor").val();
  if(institucion==-2){
  	if($("#div_ins_doctor").hasClass("d-none")){
  		$("#div_ins_doctor").removeClass("d-none")
  	}
  }else{
  	if(!$("#div_ins_doctor").hasClass("d-none")){
  		$("#div_ins_doctor").addClass("d-none")
  	}
  }
}

function guardarMedicoReceta(){
	var nom_doctor=$("#nom_doctor").val();
	var ape_doctor=$("#ape_doctor").val();
	var dir_doctor=$("#dir_doctor").val();
	var mat_doctor=$("#mat_doctor").val();
	var n_ins_doctor=$("#n_ins_doctor").val();
	var ins_doctor=$("#ins_doctor").val();
	var esp_doctor=$("#esp_doctor").val();
	var esp_doctor2=$("#esp_doctor2").val();
	if(nom_doctor==""||ape_doctor==""){
       //alerta
	}else{
		if(ins_doctor==-2&&n_ins_doctor==""){
          //alerta
		}else{
			guardarMedicoRecetaAjax(nom_doctor,ape_doctor,dir_doctor,mat_doctor,n_ins_doctor,ins_doctor,esp_doctor,esp_doctor2);
		}
	}
}

function guardarMedicoRecetaAjax(nom_doctor,ape_doctor,dir_doctor,mat_doctor,n_ins_doctor,ins_doctor,esp_doctor,esp_doctor2){
	var parametros={nom_doctor:nom_doctor,ape_doctor:ape_doctor,dir_doctor:dir_doctor,mat_doctor:mat_doctor,n_ins_doctor:n_ins_doctor,ins_doctor:ins_doctor,esp_doctor:esp_doctor,esp_doctor2:esp_doctor2};
	$.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajaxNuevoMedico.php",
        data: parametros,
        success:  function (resp) {
        	$("#nom_doctor").val("");
	        $("#ape_doctor").val("");
	        $("#dir_doctor").val("");
	        $("#mat_doctor").val("");
	        $("#n_ins_doctor").val("");
        	if(parseInt(resp)==1){
               Swal.fire("Correcto!", "Se guardó el médico con éxito", "success");   
               actualizarTablaMedicos("codigo");                 	   
        	}else{
               Swal.fire("Error!", "Contactar con el administrador", "error");   
        	}            
        }
    });	
}

function actualizarTablaMedicos(orden){
	var codigo=$("#cod_medico").val();
   var parametros={order_by:orden,cod_medico:codigo};
   $.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajaxListaMedicos.php",
        data: parametros,
        success:  function (resp) {
        	actualizarListaInstitucion();
        	$("#datos_medicos").html(resp);                 	   
        }
    });	
}


function buscarMedicoTest(){
   var codigo=$("#cod_medico").val();
   var nom=$("#buscar_nom_doctor").val();
   var app=$("#buscar_app_doctor").val();
   var parametros={order_by:"codigo",cod_medico:codigo,nom_medico:nom,app_medico:app};
   $.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajaxListaMedicos.php",
        data: parametros,
        success:  function (resp) {
        	actualizarListaInstitucion();
        	$("#datos_medicos").html(resp);                 	   
        }
    });	
}
function actualizarListaInstitucion(){
   var parametros={cod:""};
   $.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajaxListaInstitucion.php",
        data: parametros,
        success:  function (resp) {
        	$("#ins_doctor").html(resp);   
        	$("#ins_doctor").selecpicker("refresh");                 	   
        }
    });	
}

function asignarMedicoVenta(codigo){
   $("#cod_medico").val(codigo);
   if(codigo>0){
  	 $("#boton_receta").attr("style","background:#e6992b");
  	 $("#mensaje_receta").html("<img src='imagenes/doc.jpg' width='50' height='50'> <b style='font-size:14px;color:#4E836C;'>Dr. "+$("#medico_lista"+codigo).html()+"</b>");

  	 /*if($(".receta_detalle").hasClass("d-none")){
      $(".receta_detalle").removeClass("d-none");
   	 }*/
   }else{
   	 $("#boton_receta").attr("style","background:#652BE9 !important;");
   	 $("#mensaje_receta").html("");

   	 /*if(!$(".receta_detalle").hasClass("d-none")){
      $(".receta_detalle").addClass("d-none");
   	 }*/
   }

   $("#modalRecetaVenta").modal("hide");
}
function refrescarComboCliente(cliente){
	var parametros={"cliente":cliente,"nit":$("#nitCliente").val()};
	$.ajax({
        type: "GET",
        dataType: 'html',
        url: "listaClientesActual.php",
        data: parametros,
        success:  function (resp) {
        	Swal.fire("Correcto!", "Se guardó el cliente con éxito", "success");   
           $("#cliente").html(resp);  
           ajaxRazonSocialCliente(document.getElementById('form1'));
           $("#cliente").selectpicker("refresh");          
           $("#modalNuevoCliente").modal("hide");                  	   
        }
    });	
}

function mostrarClientesActualesCombo(){
	var parametros={"0":0};
	$.ajax({
        type: "GET",
        dataType: 'html',
        url: "listaClientesActual.php",
        data: parametros,
        success:  function (resp) {
           $("#cliente_campana").html(resp);  
           $("#cliente_campana").val($("#cliente").val());
           $("#cliente_campana").selectpicker("refresh");                         	   
        }
    });	
}


function mostrarRegistroConTarjeta(){
	$("#titulo_tarjeta").html("");
	if($("#nro_tarjeta").val()!=""){
      $("#titulo_tarjeta").html("(REGISTRADO)");
	}
	if($("#monto_tarjeta").val()==""){	  
      $("#monto_tarjeta").val($("#totalFinal").val());
      $("#efectivoRecibidoUnido").val($("#totalFinal").val());
      $("#tipoVenta").val(2);
      $(".selectpicker").selectpicker("refresh");
      aplicarMontoCombinadoEfectivo(form1);
      document.getElementById("nro_tarjeta").focus();
	}
	$("#modalPagoTarjeta").modal("show");	
	//$("#nro_tarjeta").focus();	
}
function verificarPagoTargeta(){	
  var nro_tarjeta=$("#nro_tarjeta").val();
  if(nro_tarjeta!=""){
  	$("#boton_tarjeta").attr("style","background:green");
  }else{
  	$("#boton_tarjeta").attr("style","background:#96079D");
  }
}
function mostrarListadoNits(){
	var rs=$("#razonSocial").val();
	//$("#nitCliente").val("");
	var parametros={"rs":rs};
	$.ajax({
        type: "GET",
        dataType: 'html',
        url: "listaRazonActual.php",
        data: parametros,
        success:  function (resp) { 
        	var re=resp.split("#####");
           $("#lista_nits").html(re[0]);  
           if(parseInt(re[1])==1){
              asignarNit(re[2]);
           }else{
             $("#modalAsignarNit").modal("show");                  	   	
           }                 
        }
    });	
}
function asignarNit(nit){
   $("#nitCliente").val(nit); 
   $("#modalAsignarNit").modal("hide");      
}

function validarTextoEntrada(input, patron) {
    var texto = input.value
    var letras = texto.split("")
    for (var x in letras) {
        var letra = letras[x]
        if (!(new RegExp(patron, "i")).test(letra)) {
            letras[x] = ""
        }
    }
    input.value = letras.join("")
}


function modalPuntosCampana(){
	mostrarClientesActualesCombo();
	$("#modalPuntosCampana").modal("show");     
}
function buscarClientePuntos(){
	$("#tabla_puntos").html("");
	var cliente = $("#cliente_campana").val();
	var parametros={"cliente":cliente};
	$.ajax({
        type: "GET",
        dataType: 'html',
        url: "ajaxPuntosCampana.php",
        data: parametros,
        success:  function (resp) { 
        	$("#tabla_puntos").html(resp);        	                
        }
    });	
}

$(document).ready(function(){
 $("#busqueda_sucursal").keyup(function(){
 _this = this;
 // Show only matching TR, hide rest of them
 $.each($("#tabla_sucursal tbody tr"), function() {
 if($(this).text().toLowerCase().indexOf($(_this).val().toLowerCase()) === -1)
 $(this).hide();
 else
 $(this).show();
 });
 });
});

function mostrarComplemento(){
	var tipo=$("#tipo_documento").val();
	//$("#nitCliente").attr("type","number");
	if(tipo==1){
		//if($("#nitCliente").val()!=""){
			//$("#nitCliente").val($("#nitCliente").val().replace(/ [A-Za-z-] + / g, ''));
		//}		
		$("#complemento").attr("type","text");
		$("#nitCliente").attr("placeholder","INGRESE EL CARNET");
	}else{
		$("#complemento").attr("type","hidden");
		if(tipo==5){
			$("#nitCliente").attr("placeholder","INGRESE EL NIT");	
		}else{
			$("#nitCliente").attr("placeholder","INGRESE EL DOCUMENTO");
		}
		
	}
}

function check(e) {

    tecla = (document.all) ? e.keyCode : e.which;

    //Tecla de retroceso para borrar, siempre la permite
    if (tecla == 8||tecla==13) {
        return true;
    }

    // Patron de entrada, en este caso solo acepta numeros y letras
    if($("#tipo_documento").val()!=1){
    	patron = /[A-Za-z0-9-]/;
    }else{
    	patron = /[0-9]/;
    }
    
    tecla_final = String.fromCharCode(tecla);
    return patron.test(tecla_final);
}

$(document).ready(function (){
		mostrarComplemento();
});


function isNumeric(char) {
  return !isNaN(char - parseInt(char));
}

function maskIt(pattern, value) {
  let position = 0;
  let currentChar = 0;
  let masked = '';
  while(position < pattern.length && currentChar < value.length) {
    if(pattern[position] === '0') {
      masked += value[currentChar];
      currentChar++;
    } else {
      masked += pattern[position];
    }
    position++;
  }
  return masked;
}
function numberCharactersPattern(pattern) {
  let numberChars = 0;
  for(let i = 0; i < pattern.length; i++) {
    if(pattern[i] === '0') {
      numberChars ++;
    }
  }
  return numberChars;
}
function applyInputMask(elementId, mask) {
  let inputElement = document.getElementById(elementId);
  let content = '';
  let maxChars = numberCharactersPattern(mask);
  
  inputElement.addEventListener('keydown', function(e) {
    e.preventDefault();
    if (isNumeric(e.key) && content.length < maxChars) {
      content += e.key;
    }
    if(e.keyCode == 8) {
      if(content.length > 0) {
        content = content.substr(0, content.length - 1);
      }
    }
    inputElement.value = maskIt('0000********0000', content);
  })
}

$( document ).ready(function() {
  applyInputMask('nro_tarjeta', '0000********0000');
});
</script>
<?php
if(!isset($fecha)||$fecha==""){   
	$fecha=date("d/m/Y");
}
$sqlCambioUsd="select valor from cotizaciondolar order by 1 desc limit 1";
$respUsd=mysqli_query($enlaceCon,$sqlCambioUsd);
$tipoCambio=1;
while($filaUSD=mysqli_fetch_array($respUsd)){
		$tipoCambio=$filaUSD[0];	
}

$usuarioVentas=$_COOKIE['global_usuario'];
$globalAgencia=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE['global_almacen'];
$cuidadDefecto=$globalAgencia;
//SACAMOS LA CONFIGURACION PARA EL DOCUMENTO POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=1";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$tipoDocDefault=$datConf[0];//$tipoDocDefault=mysqli_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA EL CLIENTE POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=2";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$clienteDefault=$datConf[0];//$clienteDefault=mysqli_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=3";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$facturacionActivada=$datConf[0];//$facturacionActivada=mysqli_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA LA ANULACION
$anulacionCodigo=1;
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=6";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$anulacionCodigo=$datConf[0];//$anulacionCodigo=mysqli_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA CONOCER SI PERMITIMOS VENDER POR DEBAJO DEL COSTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=5";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$ventaDebajoCosto=$datConf[0];//$ventaDebajoCosto=mysqli_result($respConf,0,0);
$ciudad=$_COOKIE['global_agencia'];
$codigoDescuentoGeneral=0;
$porcentajeDescuentoReal=0;
$porcentajeDescuentoRealNombre="Descuento";

include("datosUsuario.php");

$cadComboBancos = "";
$consulta="SELECT c.codigo, c.nombre FROM bancos AS c WHERE estado = 1 ORDER BY c.nombre ASC";
$rs=mysqli_query($enlaceCon,$consulta);
while($reg=mysqli_fetch_array($rs))
   {$codBanco = $reg["codigo"];
    $nomBanco = $reg["nombre"];
    $cadComboBancos=$cadComboBancos."<option value='$codBanco'>$nomBanco</option>";
   }



$cadComboCiudad = "";
$consulta="SELECT c.cod_ciudad, c.descripcion FROM ciudades AS c WHERE 1 = 1 ORDER BY c.descripcion ASC";
$rs=mysqli_query($enlaceCon,$consulta);
while($reg=mysqli_fetch_array($rs))
   {$codCiudad = $reg["cod_ciudad"];
    $nomCiudad = $reg["descripcion"];
    $cadComboCiudad=$cadComboCiudad."<option value='$codCiudad'>$nomCiudad</option>";
   }

   $cadComboEdad = "";
$consultaEdad="SELECT c.codigo,c.nombre, c.abreviatura FROM tipos_edades AS c WHERE c.estado = 1 ORDER BY 1";
$rs=mysqli_query($enlaceCon,$consultaEdad);
while($reg=mysqli_fetch_array($rs))
   {$codigoEdad = $reg["codigo"];
    $nomEdad = $reg["abreviatura"];
    $desEdad = $reg["nombre"];
    $cadComboEdad=$cadComboEdad."<option value='$codigoEdad'>$nomEdad ($desEdad)</option>";
   }

$cadTipoPrecio="";
$consulta1="select t.`codigo`, t.`nombre` from `tipos_precio` t";
$rs1=mysqli_query($enlaceCon,$consulta1);
while($reg1=mysqli_fetch_array($rs1))
   {$codTipo = $reg1["codigo"];
    $nomTipo = $reg1["nombre"];
    $cadTipoPrecio=$cadTipoPrecio."<option value='$codTipo'>$nomTipo</option>";
   }

$cadComboGenero="";
$consult="select t.`cod_genero`, t.`descripcion` from `generos` t where cod_estadoreferencial=1";
$rs1=mysqli_query($enlaceCon,$consult);
while($reg1=mysqli_fetch_array($rs1))
   {$codTipo = $reg1["cod_genero"];
    $nomTipo = $reg1["descripcion"];
    $cadComboGenero=$cadComboGenero."<option value='$codTipo'>$nomTipo</option>";
   }


$cadComboInstitucion = "";
$consulta="SELECT c.codigo, c.nombre FROM instituciones c WHERE estado = 1 ORDER BY c.codigo ASC";
$rs=mysqli_query($enlaceCon,$consulta);
while($reg=mysqli_fetch_array($rs))
   {$codInstitucion = $reg["codigo"];
    $nomInstitucion = $reg["nombre"];
    $cadComboInstitucion=$cadComboInstitucion."<option value='$codInstitucion'>$nomInstitucion</option>";
   }
$cadComboEspecialidades = "";
$consulta="SELECT c.codigo, c.nombre FROM especialidades c WHERE estado = 1 ORDER BY c.codigo ASC";
$rs=mysqli_query($enlaceCon,$consulta);
while($reg=mysqli_fetch_array($rs))
   {$codEsp = $reg["codigo"];
    $nomEsp = $reg["nombre"];
    $cadComboEspecialidades=$cadComboEspecialidades."<option value='$codEsp'>$nomEsp</option>";
   }
$iconVentas2="point_of_sale";
echo "hola gabrielaaa";
?>

<nav class="mb-4 navbar navbar-expand-lg" style='background:#00ccb6 !important;color:white !important;'>
                <a class="navbar-brand font-bold" href="#">KIDPLACE VENTAS [<?php echo $fechaSistemaSesion?>][<b id="hora_sistema"><?php echo $horaSistemaSesion;?></b>] [<?php echo $nombreAlmacenSesion;?>]</a>
                <div id="siat_error"></div>
                
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-4" aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent-4">
                    <ul class="navbar-nav ml-auto">
                    	  <li class="nav-item active">
                            <a class="nav-link" href="#" onclick="alterna_modo_de_pantalla();" title="PANTALLA COMPLETA"><i class="material-icons">fullscreen</i></a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="#"><i class="fa fa-user"></i> <?php echo $nombreUsuarioSesion?> <span class="sr-only">(current)</span></a>
                        </li>                        
                        <li class="nav-item active">
                            <a class="btn btn-success btn-fab" href="#" style="background: #652BE9 !important;color:#fff;" onclick="guardarRecetaVenta()" title="REGISTRAR RECETA"  data-toggle='tooltip' id="boton_receta"><i class="material-icons" >medical_services</i></a>
                        </li>
                        <li class="nav-item active">
                            <a href="#" onclick="guardarPedido(0)" class="btn btn-danger btn-fab float-right" style="background:#900C3F;color:#fff;" title="GUARDAR VENTA PERDIDA" data-toggle='tooltip'><i class="material-icons">search_off</i></a>
                        </li>
                        <li class="nav-item dropdown active">
                            <a class="btn btn-danger btn-fab float-right dropdown-toggle" id="navbarDropdownMenuLink-del" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: #1d2a76;color:#fff;"><i class="material-icons">delivery_dining</i></a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-cyan" aria-labelledby="navbarDropdownMenuLink-del">
                            		<a class="dropdown-item"  href="#" onclick="cambiarDelivery(0)">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Ninguno</b></a>
                                <a class="dropdown-item" id="boton_tipoventadelivery" href="#" onclick="cambiarDelivery(1)"><img src="imagenes/yaigo.png" class='bw grey' width="30px"> Yaigo</a>
                                <a class="dropdown-item" id="boton_tipoventadelivery2" href="#" onclick="cambiarDelivery(2)"><img src="imagenes/py_icono.png" class='bw grey' width="30px"> Pedidos Ya!</a>
                                <a class="dropdown-item" id="boton_tipoventadelivery3" href="#" onclick="cambiarDelivery(3)"><img src="imagenes/sucursal.png" class='bw grey' width="30px"> Sucursal Virtual</a>
                            </div>
                        </li>                       

                        <li class="nav-item dropdown active">
                            <a class="btn btn-danger btn-fab float-right dropdown-toggle" id="navbarDropdownMenuLink-4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="background: #1d2a76;color:#fff;"><i class="fa fa-gear"></i></a>
                            <div class="dropdown-menu dropdown-menu-right dropdown-cyan" aria-labelledby="navbarDropdownMenuLink-4">                            	
                            	<a class="dropdown-item" href="#" onclick="cambiarTipoVenta2()" id="boton_tipoventa2"><i class="material-icons" style="background: #652BE9;color:#fff;"><?=$iconVentas2?></i> TIPO DE VENTA CORRIENTE</a>
                              <a class="dropdown-item" href="#" onclick="modalPuntosCampana()" id="boton_campana"><i class="material-icons" style="background:#6DC3D5;color:#fff;">control_point</i> PUNTOS POR CAMPA&Ntilde;A
                              </a>                               
                            </div>
                        </li>
                        <li class="nav-item">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
                    </ul>
                </div>
            </nav>



<form action='form_ventasSave.php' method='POST' name='form1' id="guardarSalidaVenta">
<?php echo "hola gabrielaaa2"; ?>
	<input type="hidden" id="siat_error_valor" name="siat_error_valor">
	<input type="hidden" id="confirmacion_guardado" value="0"><input type="hidden" id="tipo_cambio_dolar" name="tipo_cambio_dolar"value="<?=$tipoCambio?>">
	<input type="hidden" id="pedido_realizado" value="0">
	<input type="hidden" id="cod_medico" name="cod_medico" value="0">
	<input type="hidden" id="global_almacen" value="<?=$globalAlmacen?>">
	<input type="hidden" id="validacion_clientes" name="validacion_clientes" value="<?=obtenerValorConfiguracion(11)?>">
<table class='' width='100%' style='width:100%;margin-top:-24px !important;'>
<?php echo "hola gabrielaaa3"; ?>
<tr class="bg-info text-white" align='center' id='venta_detalle' style="color:#fff;background:#00ccb6 !important; font-size: 16px;">
<th>Tipo de Doc.</th>
<th>Nro.Factura</th>
<th>Fecha</th>
<th class='d-none'>Precio</th>
<th>Tipo Pago</th>
<th width="20%">NIT/CI/CEX</th>
<th colspan="2" width="25%">Nombre/RazonSocial</th>
 <!-- Observaciones <th></th> -->
<th colspan='2' width="15%">Cliente</th>
</tr>
<tr>
<input type="hidden" name="tipoSalida" id="tipoSalida" value="1001">
<td>
	<?php
		
		if($facturacionActivada==1){
			$sql="select codigo, nombre, abreviatura from tipos_docs where codigo in (1,2) order by 2 desc";
		}else{
			$sql="select codigo, nombre, abreviatura from tipos_docs where codigo in (2) order by 2 desc";
		}
		$resp=mysqli_query($enlaceCon,$sql);

		echo "<select class='selectpicker form-control' data-style='btn-info' name='tipoDoc' id='tipoDoc' onChange='ajaxNroDoc(form1)' required>";
		echo "<option value=''>-</option>";
		while($dat=mysqli_fetch_array($resp)){
			$codigo=$dat[0];
			$nombre=$dat[1];
			if($codigo==$tipoDocDefault){
				echo "<option value='$codigo' selected>$nombre</option>";
			}else{
				echo "<option value='$codigo'>$nombre</option>";
			}
		}
		echo "</select>";
		?>
</td>
<td align='center'>
	<div id='divNroDoc'>
		<?php
		
		// $vectorNroCorrelativo=numeroCorrelativo($tipoDocDefault);
		// $nroCorrelativo=$vectorNroCorrelativo[0];
		// $banderaErrorFacturacion=$vectorNroCorrelativo[1];

		$vectorNroCorrelativo=numeroCorrelativoCUFD($tipoDocDefault);
		$nroCorrelativo=$vectorNroCorrelativo[0];
		$banderaErrorFacturacion=$vectorNroCorrelativo[1];

		echo "<span class='textogranderojo'>$nroCorrelativo</span>";
	  if($nroCorrelativo=="CUFD INCORRECTO / VENCIDO"){
			?><script>$(document).ready(function (){
				$("#dosificar_factura_sucursal").removeClass("d-none");
			})</script><?php
	  }
		?>	
	</div>
</td>

<td align='center'>
	<input type='text' class='form-control' value='<?php echo $fecha?>' id='fecha' size='10' name='fecha' readonly>	
</td>


<td class='d-none'>
	<div id='divTipoPrecio'>	
<?php
			$sql1="select codigo, nombre from tipos_precio where estado=1 order by 1";
			$resp1=mysqli_query($enlaceCon,$sql1);
			echo "<select name='tipoPrecio' class='selectpicker form-control' data-style='btn-info' id='tipoPrecio'>";
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
	<div id='divTipoVenta'>
		<?php
			$sql1="select cod_tipopago, nombre_tipopago from tipos_pago order by 1";
			$resp1=mysqli_query($enlaceCon,$sql1);
			echo "<select name='tipoVenta' class='selectpicker form-control' id='tipoVenta' data-style='btn-info'>";
			while($dat=mysqli_fetch_array($resp1)){
				$codigo=$dat[0];
				$nombre=$dat[1];
				echo "<option value='$codigo'>$nombre</option>";
			}
			echo "</select>";
			?>

	</div>
</td>


<?php
if($tipoDocDefault==2){
	$razonSocialDefault="-";
	$nitDefault="0";
}else{
	$razonSocialDefault="";
	$nitDefault="";
}

$tipoVentas2=1;
$tipoVentasdelivery=0;
//$iconVentas2="corporate_fare";
$iconVentas2="point_of_sale";
?>

	
	<td>
		<div class="row">
			<div class="col-sm-3" style="padding-right: 5px;">
		<select name='tipo_documento' class='selectpicker form-control' data-live-search="true" id='tipo_documento' onChange='mostrarComplemento(form1);' required data-style="btn btn-rose">
<?php
$sql2="SELECT codigoClasificador,descripcion FROM siat_sincronizarparametricatipodocumentoidentidad;";
$resp2=mysqli_query($enlaceCon,$sql2);

while($dat2=mysqli_fetch_array($resp2)){
   $codCliente=$dat2[0];
	$nombreCliente=$dat2[1]." ".$dat2[2];
?><option value='<?php echo $codCliente?>'><?php echo $nombreCliente?></option><?php
}
?>
	</select>
	</div>
		<div id='divNIT' class="col-sm-9" style="padding: 0;">
			<input type='text' value='<?php echo $nitDefault; ?>' name='nitCliente' id='nitCliente'  onChange='ajaxRazonSocial(this.form);' onkeypress="return check(event)" onkeyup="javascript:this.value=this.value.toUpperCase();" class="form-control" required placeholder="INGRESE EL NIT" autocomplete="off">
		</div>
		<!-- style="font-size: 20px;color:#9D09BB"-->
		
	</div>
	<input type='hidden' name='complemento' id='complemento' value='' class="form-control" placeholder="COMPLEMENTO" style="text-transform:uppercase;position:absolute;width:160px !important;background:#D2FFE8;" onkeyup="javascript:this.value=this.value.toUpperCase();" > 
	</td>
	
	<td colspan="2">
		<div id='divRazonSocial'>
          <input type='text' name='razonSocial' id='razonSocial' value='<?php echo $razonSocialDefault; ?>' class="form-control" required placeholder="Ingrese la razon social" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"  onchange='ajaxNitCliente(this.form);' pattern='[A-Z a-z 0-9 Ññ.-&]+'>          
        </div>
        <span class="input-group-btn" style="position:absolute;width:10px !important;">
            <a href="#" onclick="ajaxVerificarNitCliente(); return false;" class="btn btn-info btn-sm" style="position:absolute;right: 100%;"><i class="material-icons">refresh</i> Verificar Nit</a>
            <a href="#" class="btn btn-primary btn-sm" onclick="mostrarListadoNits();return false;"><span class="material-icons">person_search</span> Encontrar NIT
            </a>
        </span>
           <span class="input-group-btn" style="position:absolute;left:5px !important;">
            
          </span>
	</td>

	<!-- <td align='center'> -->
		<input type='hidden' class="form-control" id='observaciones' readonly name='observaciones' value='-' placeholder="Ingrese una observación">
	<!-- </td> -->
	<td align='center' id='divCliente' width="20%">			
	<select name='cliente' class='selectpicker form-control' data-live-search="true" id='cliente' onChange='ajaxRazonSocialCliente(this.form);' required data-style="btn btn-rose">
		<!-- <option value=''>----</option> -->
		<option value='146'>NO REGISTRADO</option>
<?php
// $sql2="select c.`cod_cliente`, c.nombre_cliente,c.paterno from clientes c order by 2";
// $resp2=mysqli_query($enlaceCon,$sql2);

// while($dat2=mysqli_fetch_array($resp2)){
//    $codCliente=$dat2[0];
// 	$nombreCliente=$dat2[1]." ".$dat2[2];
// 	if($codCliente==$clienteDefault){
?>		
	<!-- <option value='<?php echo $codCliente?>' selected><?php echo $nombreCliente?></option> -->
<?php			
	// }else{
?>		
	<!-- <option value='<?php echo $codCliente?>'><?php echo $nombreCliente?></option> -->
<?php			
// 	}

// }
?>
	</select>
	</td><td>	
		<a href="#" title="Editar Cliente" data-toggle='tooltip' onclick="editarDatosClienteRegistro(); return false;" class="btn btn-primary btn-round btn-sm text-white btn-fab"><i class="material-icons">edit</i></a>
	<a href="#" title="Registrar Nuevo Cliente" data-toggle='tooltip' onclick="registrarNuevoCliente(); return false;" class="btn btn-success btn-round btn-sm text-white circle" id="button_nuevo_cliente">+</a>

	</td>
	<!--<input type="hidden" name="tipoPrecio" value="1">-->

</tr>

</table>
<br>
<input type="hidden" id="tipo_venta2" name="tipo_venta2" value="<?=$tipoVentas2?>">
<input type="hidden" id="tipo_ventadelivery" name="tipo_ventadelivery" value="<?=$tipoVentasdelivery?>">
<input type="hidden" id="ventas_codigo"><!--para validar la funcion mas desde ventas-->

<fieldset  style="width:100%;border:0; margin-top: -28px;">
	<table id="data0" class='table table-sm table-condensed' width='100%' style='width:100%'>
		<thead>
	<tr>
		<td align="left" colspan="8" class="text-success" style="text-align: left !important;padding:10px !important;">
			<b style='font-size:20px;color:#1d2a76;'>Detalle de la Venta    </b>
			<input class="btn btn-info" style="margin-top: 0px;" type="button" value="Adicionar Item (+)" onclick="mas(this)" accesskey="a"/>
			<div id='mensaje_receta' class='float-right'></div>
			<div id='mensaje_venta' class='float-right'></div>
		</td>
	</tr>
    <tr align="center" class="bg-info text-white" style='background:#00ac99 !important;'>
		<td width="10%" align="left" style="padding:10px !important;">Cant. Pres / Opciones</td>
		<td width="30%" style="padding:10px !important;">Material</td>
		<td width="12%" align="center" style="padding:10px !important;">Stock</td>
		<td width="8%" align="left" style="padding:10px !important;">Cantidad</td>
		<td width="10%" align="left" style="padding:10px !important;">Precio </td>
		<td width="15%" align="left" style="padding:10px !important;">Desc.</td>
		<td width="10%" align="left" style="padding:10px !important;">Monto</td>
		<td width="10%" colspan="2" style="padding:10px !important;">&nbsp;</td>
	</tr>
	</thead>
	<tbody id="fiel"></tbody>
	</table>

</fieldset>



<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:1200px; height: 600px; top:30px; left:50px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2; overflow: auto;">
</div>

<div id="divboton" style="position: absolute; top:20px; left:1210px;visibility:hidden; text-align:center; z-index:3">
	<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
</div>

<div id="divProfileData" style="background-color:#FFF; width:1150px; height:550px; position:absolute; top:50px; left:70px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2; overflow: auto;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<table align='center'>
			<tr><th>Proveedor</th><th>Acci&oacute;n Terap&eacute;utica</th><th>Principio Activo</th></tr>
			<tr>
			<td width="30%"><select class="selectpicker col-sm-12" name='itemTipoMaterial' id='itemTipoMaterial' data-live-search='true' data-size='6' data-style='btn btn-default btn-lg ' style="width:300px"> <!-- data-live-search='true' data-size='6' data-style='btn btn-default btn-lg '-->
			<?php
			if($_COOKIE["global_tipo_almacen"]==1){
                 $sqlTipo="select p.cod_proveedor,p.nombre_proveedor from proveedores p
			where p.estado_activo=1 and p.cod_proveedor>0 order by 2;";
			}else{
	             $sqlTipo="select p.cod_proveedor,p.nombre_proveedor from proveedores p
			where p.estado_activo=1 and p.cod_proveedor<0 order by 2;"; 
			}

			

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
			<td width="40%"><select class="selectpicker col-sm-12 d-none" data-live-search='true' data-size='6' data-style='btn btn-default btn-lg ' name='itemFormaMaterial' id='itemFormaMaterial' style="width:300px">
			<?php
			$sqlTipo="select pl.cod_forma_far,pl.nombre_forma_far from formas_farmaceuticas pl 
			where pl.estado=1 order by 2;";
			$respTipo=mysqli_query($enlaceCon,$sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysqli_fetch_array($respTipo)){
				$codTipoMat=$datTipo[0];
				$nombreTipoMat=$datTipo[1];
				echo "<option value=$codTipoMat>$nombreTipoMat</option>";
			}
			?>

			</select>
			<select class="selectpicker col-sm-12 d-none" data-live-search='true' data-size='6' data-style='btn btn-default btn-lg ' name='itemAccionMaterial' id='itemAccionMaterial' style="width:300px">
			<?php
			/*$sqlTipo="select pl.cod_accionterapeutica,pl.nombre_accionterapeutica from acciones_terapeuticas pl 
			where pl.estado=1 order by 2;";
			$respTipo=mysqli_query($enlaceCon,$sqlTipo);*/
			echo "<option value='0'>--</option>";
			/*while($datTipo=mysqli_fetch_array($respTipo)){
				$codTipoMat=$datTipo[0];
				$nombreTipoMat=$datTipo[1];
				echo "<option value=$codTipoMat>$nombreTipoMat</option>";
			}*/
			?>

			</select>
			<input type='text' placeholder='Accion Terapeutica' name='itemAccionMaterialNom' id='itemAccionMaterialNom' class="textogranderojo" onkeypress="return pressEnter(event, this.form);">
			</td>
			<td width="30%">
				<select class="selectpicker col-sm-12 d-none" data-live-search='true' data-size='6' data-style='btn btn-default btn-lg ' name='itemPrincipioMaterial' id='itemPrincipioMaterial' style="width:300px">
			<?php
			echo "<option value='0'>--</option>";
			/*$sqlTipo="select pl.codigo,pl.nombre from principios_activos pl 
			where pl.estado=1 order by 2;";
			$respTipo=mysqli_query($enlaceCon,$sqlTipo);
			echo "<option value='0'>--</option>";
			while($datTipo=mysqli_fetch_array($respTipo)){
				$codTipoMat=$datTipo[0];
				$nombreTipoMat=$datTipo[1];
				echo "<option value=$codTipoMat>$nombreTipoMat</option>";
			}*/
			?>                 
			</select>
			<input type='text' placeholder='Principio Activo' name='itemPrincipioMaterialNom' id='itemPrincipioMaterialNom' class="textogranderojo" onkeypress="return pressEnter(event, this.form);">
			</td>
			<tr><th>&nbsp;</th><th>Codigo / Producto</th><th>&nbsp;</th></tr>
	     <tr>
	     	<td>
				<div class="custom-control custom-checkbox small float-left">
                    <input type="checkbox" class="" id="solo_stock" checked="">
                    <label class="text-dark font-weight-bold" for="solo_stock">&nbsp;&nbsp;&nbsp;Solo Productos con Stock</label>
         </div>
			</td>
			<td>
				<div class="row">
					<div class="col-sm-3"><input type='number' placeholder='--' name='itemCodigoMaterial' id='itemCodigoMaterial' class="textogranderojo" onkeypress="return pressEnter(event, this.form);" onkeyup="return pressEnter(event, this.form);"></div>
					<div class="col-sm-9"><input type='text' placeholder='Descripción' name='itemNombreMaterial' id='itemNombreMaterial' class="textogranderojo" onkeypress="return pressEnter(event, this.form);"></div>				   
				</div>
				
			</td>	
					
			<td align="center">				
				<input type='button' id="enviar_busqueda" class='btn btn-info' value='Buscar' onClick="listaMateriales(this.form)">	
				<a href="#" class="btn btn-warning btn-fab float-right" title="Limpiar Formulario de Busqueda" data-toggle='tooltip' onclick="limpiarFormularioBusqueda();return false;"><i class="material-icons">cleaning_services</i></a>			
			</td>
 			</tr>
			
		</table>		
		<div id="divListaMateriales">
		</div>
	
	</div>
</div>
<div style="height:200px;"></div>

<div class="pie-div">
	<table class="pie-montos">
      <tr>
        <td>
         
         <table id='' width='100%' border="0" style='float:right;margin-top: 30px;'>
          <tr>
          	<td style="font-weight:bold;font-size:12px;">MONTO FINAL Bs.</td>
          	<td style="font-weight:bold;color:#777B77;font-size:12px;">TOTAL RECIBIDO</td>
          	<td style="font-weight:bold;color:#777B77;font-size:12px;color:#057793;">TOTA CAMBIO Bs.</td>
          </tr>
          <tr>
          	<td><input type='number' name='totalFinal' id='totalFinal' class='form-control' readonly style='height:40px;font-size:35px;width:80%;background:#383A3E !important; margin-top:4px; color:#39ff14;' value='0.00'></td>
          	<td><input type='number' class='form-control' style='height:40px;font-size:35px;width:80%;background:#383A3E !important; margin-top:4px; color:#08FAEF;' name='efectivoRecibido' id='efectivoRecibido' readonly step="any" onChange='aplicarCambioEfectivo(form1);' onkeyup='aplicarCambioEfectivo(form1);' onkeydown='aplicarCambioEfectivo(form1);' value='0.00'></td>
          	<td><input type='number' class='form-control' name='cambioEfectivo' id='cambioEfectivo' readonly style='height:40px;font-size:35px;width:80%;background:#383A3E !important; margin-top:4px; color:#ff8000;' value='0.00'></td>
          </tr>
	     </table>

        </td>
        <td>
        	<table id='' width='100%' border="0">
		<tr>
			<td align='left' width='90%' style="color:#777B77;font-size:12px;"></td><td align='LEFT'><b style="font-size:30px;color:#0691CD;">D</b><label style="color:#0691CD;">escuento <b style="font-size:30px;color:#0691CD;">F</b>inal</label></td>
		</tr>

		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;">Monto Venta</td><td><input type='number' name='totalVenta' id='totalVenta' readonly style="background:#B0B4B3"></td>
		</tr>
		<tr>
			<td align='right' width='90%' id='porcentajeDescuentoRealNombre' style="font-weight:bold;color:red;font-size:12px;"><?=$porcentajeDescuentoRealNombre?></td><td><input type='number' name='descuentoVenta' id='descuentoVenta' onChange='aplicarDescuento(form1);' style="height:27px;font-size:22px;width:100%;color:red;" onkeyup='aplicarDescuento(form1);' onkeydown='aplicarDescuento(form1);' value="0" readonly step='any' required></td>
		</tr>
		<tr>
			<td align='right' width='90%' id='porcentajeDescuentoRealNombre2' style="font-weight:bold;color:red;font-size:12px;"><?=$porcentajeDescuentoRealNombre?> %</td><td><input type='number' name='descuentoVentaPorcentaje' id='descuentoVentaPorcentaje' style="height:27px;font-size:22px;width:100%;color:red;" onChange='aplicarDescuentoPorcentaje(form1);' onkeyup='aplicarDescuentoPorcentaje(form1);' onkeydown='aplicarDescuentoPorcentaje(form1);' value="<?=$porcentajeDescuentoReal?>" readonly step='any'></td>
		</tr>

	</table>
       
	<table id='' width='100%' border="0" style="display:none">
		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;"></td><td align='center'><b style="font-size:35px;color:#189B22;">$ USD</b></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;">Monto Venta</td><td><input type='number' name='totalVentaUSD' id='totalVentaUSD' readonly style="background:#B0B4B3"></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Descuento</td><td><input type='number' name='descuentoVentaUSD' id='descuentoVentaUSD' style="height:27px;font-size:22px;width:100%;color:red;" onChange='aplicarDescuentoUSD(form1);' onkeyup='aplicarDescuentoUSD(form1);' onkeydown='aplicarDescuentoUSD(form1);' value="0" step='any' required></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Descuento %</td><td><input type='number' name='descuentoVentaUSDPorcentaje' id='descuentoVentaUSDPorcentaje' style="height:27px;font-size:22px;width:100%;color:red;" onChange='aplicarDescuentoUSDPorcentaje(form1);' onkeyup='aplicarDescuentoUSDPorcentaje(form1);' onkeydown='aplicarDescuentoUSDPorcentaje(form1);' value="0" step='any'></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="font-weight:bold;color:red;font-size:12px;">Monto Final</td><td><input type='number' name='totalFinalUSD' id='totalFinalUSD' readonly style="background:#189B22;height:27px;font-size:22px;width:100%;color:#fff;"> </td>
		</tr>
		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;">Monto Recibido</td><td><input type='number' name='efectivoRecibidoUSD' id='efectivoRecibidoUSD' style="background:#B0B4B3" step="any" readonly onChange='aplicarCambioEfectivoUSD(form1);' onkeyup='aplicarCambioEfectivoUSD(form1);' onkeydown='aplicarCambioEfectivoUSD(form1);'></td>
		</tr>
		<tr>
			<td align='right' width='90%' style="color:#777B77;font-size:12px;">Cambio</td><td><input type='number' name='cambioEfectivoUSD' id='cambioEfectivoUSD' readonly style="background:#4EC156;height:25px;font-size:18px;width:100%;"></td>
		</tr>
	</table>
        </td>
      </tr>
	</table>


<?php
//<button type='submit' onClick='return validar(this.form, $ventaDebajoCosto,1)' id='btsubmitPedido' name='btsubmitPedido' class='btn btn-default float-right'><i class='material-icons'>save</i> Guardar Venta y Pedido</button><button type='button' class='btn btn-danger' onClick='location.href=\"navegador_ingresomateriales.php\"';>Cancelar</button>
if($banderaErrorFacturacion==0){
	echo "<div class=''>
	        <div class='btn-group' role='group' aria-label='Grupo Venta' style='position:fixed;margin-top:0 !important;'>
               <button type='submit' class='btn btn-warning' id='btsubmit' name='btsubmit' onClick='return validar(this.form, $ventaDebajoCosto,0)'>Guardar Venta</button>
			   
			   
            </div>	       
            <h2 style='font-size:11px;color:#9EA09E;float:right;'>TIPO DE CAMBIO $ : <b style='color:#189B22;'> ".$tipoCambio." Bs.</b></h2>
            
            <table style='width:450px;padding:0 !important;margin:0 !important;bottom:25px;position:fixed;left:100px;'>
            <tr>
               <td style='display:none;font-size:12px;color:#456860;' colspan='2'>Total precio sin descuento = <label id='total_precio_sin_descuento'>0.00</label> Bs.</td>
             </tr>
             <tr>
               <td style='display:none;font-size:12px;color:#0691CD;' colspan='2'><p>&nbsp;</p></td>
             </tr>
            <tr>
               <td style='font-size:12px;color:#0691CD; font-weight:bold;'>MONTO RECIBIDO Bs.</td>
               <td style='font-size:12px;color:#189B22; font-weight:bold;'>EFECTIVO $ USD</td>
             </tr>
             <tr>
               <td width='50%'><input type='number' name='efectivoRecibidoUnido' onChange='aplicarMontoCombinadoEfectivo(form1);' onkeyup='aplicarMontoCombinadoEfectivo(form1);' onkeydown='aplicarMontoCombinadoEfectivo(form1);' id='efectivoRecibidoUnido' style='height:35px;font-size:30px;width:100%;background:#C4BDBD !important;color:#4574B9;'  class='form-control' step='any' value='0' required></td>
               <td><a href='#' class='btn btn-default btn-sm btn-fab' style='background:#96079D' onclick='mostrarRegistroConTarjeta(); return false;' id='boton_tarjeta' title='AGREGAR TARJETA DE CREDITO' data-toggle='tooltip'><i class='material-icons'>credit_card</i></a><input type='number' name='efectivoRecibidoUnidoUSD' onChange='aplicarMontoCombinadoEfectivo(form1);' onkeyup='aplicarMontoCombinadoEfectivo(form1);' onkeydown='aplicarMontoCombinadoEfectivo(form1);' id='efectivoRecibidoUnidoUSD' style='height:35px;font-size:30px;width:80%;background:#A5F9EA !important; float:left; margin-top:4px; color:#059336;'step='any' class='form-control' value='0'></td>
             </tr>
            </table>

			";
	echo "</div>";	
}else{
	echo "";
}


?>

</div>

<input type='hidden' name='materialActivo' id='materialActivo' value="0">
<input type='hidden' id="cantidad_material" name='cantidad_material' value="0">
<input type='hidden' name='codigoDescuentoGeneral' id="codigoDescuentoGeneral" value="<?=$codigoDescuentoGeneral?>">




<!-- small modal -->
<div class="modal fade modal-primary" id="modalPagoTarjeta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content card">
               <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon" style="background: #96079D;color:#fff;">
                    <i class="material-icons">credit_card</i>
                  </div>
                  <h4 class="card-title text-dark font-weight-bold">Pago con Tarjeta <small id="titulo_tarjeta"></small></h4>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">
<div class="row">
	<div class="col-sm-12">
		         <div class="row d-none">
                  <label class="col-sm-3 col-form-label">Banco</label>
                  <div class="col-sm-9">
                    <div class="form-group">
                      <select class="selectpicker form-control" name="banco_tarjeta" id="banco_tarjeta" data-style="btn btn-success" data-live-search="true">                      	
                          <?php echo "$cadComboBancos"; ?>
                          <option value="0" selected>Otro</option>
                       </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label">Numero <br>Tarjeta</label>
                  <div class="col-sm-9">
                    <div class="form-group">
                      <input class="form-control" type="text" style='height:40px;font-size:25px;width:80%;background:#D7B3D8 !important; float:left; margin-top:4px; color:#4C079A;' id="nro_tarjeta" name="nro_tarjeta" value="" onkeydown="verificarPagoTargeta()" onkeyup="verificarPagoTargeta()" onkeypress="verificarPagoTargeta()" autocomplete="off" />
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-3 col-form-label">Monto <br>Tarjeta</label>
                  <div class="col-sm-9">
                    <div class="form-group">
                      <input class="form-control" type="number" id="monto_tarjeta" name="monto_tarjeta" style='height:40px;font-size:35px;width:80%;background:#A5F9EA !important; float:left; margin-top:4px; color:#057793;' step="any" value=""/>
                    </div>
                  </div>
                </div> 
                <br>
                <a href="#" data-dismiss="modal" aria-hidden="true" class="btn btn-info btn-sm">GUARDAR</a>               
                <br><br>
       </div>
</div>                  

                </div>
      </div>  
    </div>
  </div>
<!--    end small modal -->





</form>

<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
<center>
	<h3>Comunicados</h3>
	<p>Estimadas Sucursales, en este menú se mostrarán los comunicados gestionados desde el departamento de Marketing</p>
	<p>Depto. Sistemas Cobofar</p>
</center>
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" style="z-index: 9999;">
  <ol class="carousel-indicators">
    <?php
  $sqlComunicado="select codigo from comunicados_imagen where estado=1";
  $respComunicado=mysqli_query($enlaceCon,$sqlComunicado);
  $index=0;
  $classActive="active";
  while($datComunicado=mysqli_fetch_array($respComunicado)){    
    if($index>0){
      $classActive="";
    }
  ?><li data-target="#carouselExampleIndicators" data-slide-to="<?=$index?>" class="<?=$classActive?>"></li><?php
    $index++;
  }
  $classActive="";
  if($index==0){
    $classActive="active";
  }
    ?>
    <li data-target="#carouselExampleIndicators" data-slide-to="<?=$index?>" class="<?=$classActive?>"></li>
  </ol>
  <div class="carousel-inner">
    <?php
  $sqlComunicado="select codigo,imagen,titulo,parrafo from comunicados_imagen where estado=1 order by fecha_creacion desc";
  $respComunicado=mysqli_query($enlaceCon,$sqlComunicado);
  $index=0;
  $classActive="active";
  while($datComunicado=mysqli_fetch_array($respComunicado)){
    $imagenDir=$datComunicado['imagen'];
    $tituloCom=$datComunicado['titulo'];
    $parrafoCom=$datComunicado['parrafo'];    
    if($index>0){
      $classActive="";
    }
  ?><div class="carousel-item <?=$classActive?>">
      <img class="d-block w-100" src="<?=$imagenDir?>" alt="img">
      <div class="carousel-caption d-none d-md-block">
        <label class="text-white"><b><?=$tituloCom?></b></label>
        <p><small><?=$parrafoCom?></small></p>
      </div>
    </div><?php
    $index++;
  }
  $classActive="";
  if($index==0){
    $classActive="active";
  }
  ?><div class="carousel-item <?=$classActive?>">
      <img class="d-block w-100" src="imagenes/recetas.jpg" alt="img">
      <div class="carousel-caption d-none d-md-block">
        <label class="text-white"><b></b></label>
        <p><small></small></p>
      </div>
    </div><?php
    ?>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" style="color:red" href="#carouselExampleIndicators" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

</div>
<!-- <span style="font-size:30px;cursor:pointer;position: fixed;right: 0;bottom:10px;" onclick="openNav()"><img src="imagenes/mailbox.png" width="40"></span> -->





<!-- small modal -->
<div class="modal fade modal-primary" id="modalObservacionPedido" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content card">
               <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon" style="background:#900C3F;color:#fff;">
                    <i class="material-icons">search_off</i>
                  </div>
                  <h4 class="card-title text-dark font-weight-bold">Registrar Como Venta Perdida</h4>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">
                	<input type="hidden" name="modo_pedido" id="modo_pedido" value="0">
                      <div class="row">
                          <label class="col-sm-2 col-form-label">Motivo</label>
                           <div class="col-sm-10">                     
                             <div class="form-group">
                               <select class="selectpicker form-control" name="modal_motivo" id="modal_motivo" data-style="btn btn-warning">
                                    <option selected="selected" value="0">OBSERVACIÓN ESPECÍFICA</option>
                                    <?php 
                                     $sqlObs="SELECT codigo,descripcion FROM observaciones_clase where cod_objeto=1 and cod_estadoreferencial=1 order by 1 desc";
                                     $resp=mysqli_query($enlaceCon,$sqlObs);
                                     while($filaObs=mysqli_fetch_array($resp)){
                                     		$codigo=$filaObs[0];
                                     		$nombre=$filaObs[1];	
                                     		if($codigo==4){//no existe prod en el inventario
                                     		 ?><option value="<?=$codigo;?>" selected><?=$nombre?></option><?php 
                                     		}else{
 											  ?><option value="<?=$codigo;?>"><?=$nombre?></option><?php				
                                     		}
                                     }
                                    ?>
                                  </select>
                             </div>
                           </div>        
                      </div>
                      <div class="row">
                          <label class="col-sm-2 col-form-label">Observacion</label>
                           <div class="col-sm-10">                     
                             <div class="form-group">
                               <textarea class="form-control" id="modal_observacion" name="modal_observacion"></textarea>
                             </div>
                           </div>        
                      </div>
                      <br><br>
                      <div class="float-right">
                        <button class="btn btn-default" onclick="alerts.showSwal('mensaje-guardar-pedido','')">Guardar Como Venta Perdida</button>
                      </div> 
                </div>
      </div>  
    </div>
  </div>
<!--    end small modal -->


<!-- small modal -->
<div class="modal fade modal-primary" id="modalProductosCercanos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content card">
                <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">place</i>
                  </div>
                  <h4 class="card-title text-primary font-weight-bold">Stock de Productos en Sucursales</h4>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">
                	<div class="form-group">
												 <input type="text" class="form-control pull-right" style="width:20%" id="busqueda_sucursal" placeholder="Buscar Sucursal">
									</div>
									<br>
                  <table class="table table-sm table-bordered" id='tabla_sucursal'>
                    <thead>
                      <tr style='background: #ADADAD;color:#000;'>
                      <th>#</th>
                      <th>Producto</th>
                      <th>Sucursal</th>
                      <th width="40%">Dirección</th>
                      <th>Stock</th>
                      <th>Precio</th>
                      </tr>
                    </thead>
                    <tbody id="tabla_datos">
                      
                    </tbody>
                  </table>
                  <br><br>
                </div>
      </div>  
    </div>
  </div>
<!--    end small modal -->

<!-- small modal -->
<div class="modal fade modal-primary" id="modalProductosSimilares" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content card">
                <div class="card-header card-header-success card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">device_hub</i>
                  </div>
                  <h4 class="card-title text-success font-weight-bold">Productos Similares</h4>
                   <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">
                  <table class="table table-sm table-bordered">
                    <thead>
                      <tr style='background: #ADADAD;color:#000;'>
                      <th>#</th>
                      <th>Proveedor</th>
                      <th>Linea</th>
                      <th width="45%">Producto</th>
                      <th>Principio Activo</th>
                      <th>Stock</th>
                      <th>Precio</th>
                      <th>&nbsp;</th>
                      </tr>
                    </thead>
                    <tbody id="tabla_datos_similares">
                      
                    </tbody>
                  </table>
                  <br><br>
                </div>
      </div>  
    </div>
  </div>
<!--    end small modal -->


<!-- small modal -->
<div class="modal fade modal-primary" id="modalNuevoCliente" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content card" style="background:#1F2E84 !important;color:#fff;">
                <div class="card-header card-header-warning card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">add</i>
                  </div>
                  <h4 class="card-title text-white font-weight-bold" id="titulo_cliente">Nuevo Cliente</h4>
                   <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">                	
                <div class="row">
                  <label class="col-sm-2 col-form-label text-white">Nombre (*)</label>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;text-transform:uppercase;" type="text" id="nomcli" required value="<?php echo "$nomCliente"; ?>" placeholder="Nombre del Cliente" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                    </div>
                  </div>
                  <label class="col-sm-1 col-form-label text-white">Apellidos</label>
                  <div class="col-sm-5">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;text-transform:uppercase;" type="text" id="apcli" value="<?php echo "$apCliente"; ?>" required placeholder="Apellido(s) del Cliente" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                    </div>
                  </div>
                </div>
                <div class="row">                  
                  <label class="col-sm-2 col-form-label text-white">Teléfono (*)</label>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;" type="text" id="tel1" value="<?php echo "$telefono1"; ?>" required placeholder="Telefono/Celular"/>
                    </div>
                  </div>
                  <label class="col-sm-1 col-form-label text-white">Email (*)</label>
                  <div class="col-sm-5">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;" type="email" id="mail" value="<?php echo "$email"; ?>" required placeholder="cliente@correo.com"/>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label text-white">CI</label>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;" type="text" id="ci" value="<?php echo "$ciCliente"; ?>"required/>
                    </div>
                  </div>
                  <label class="col-sm-1 col-form-label text-white">NIT(*)</label>
                  <div class="col-sm-5">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;" type="text" id="nit" value="<?php echo "$nitCliente"; ?>" readonly/>
                    </div>
                  </div>                  
                </div>
                <div class="row">
                	<label class="col-sm-2 col-form-label text-white">Razon Social ó Nombre Factura</label>
                  <div class="col-sm-10">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;" type="text" id="fact" value="<?php echo "$nomFactura"; ?>" required/>
                    </div>
                  </div>
                </div>
                <hr style="background: #FFD116;color:#FFD116;">
                <div class="row">
                  <label class="col-sm-2 col-form-label text-white">Dirección</label>
                  <div class="col-sm-10">
                    <div class="form-group">
                      <input class="form-control" style="color:black;background: #fff;" type="text" id="dir" value="<?php echo "$dirCliente"; ?>" required placeholder="Zona / Avenida-Calle / Puerta"/>
                    </div>
                  </div>
                </div>
                

                <div class="row">
                  <label class="col-sm-2 col-form-label text-white">Género</label>
                  <div class="col-sm-10">
                    <div class="form-group">                    	
                      <select class="selectpicker form-control" name="genero"id="genero" data-style="btn btn-primary" data-live-search="true" required>
                      	<option value="0" selected>--SELECCIONE--</option>
                           <?php echo "$cadComboGenero"; ?>
                       </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label text-white">Edad</label>
                  <div class="col-sm-10">
                    <div class="form-group">
                      <select class="selectpicker form-control" name="edad"id="edad" data-style="btn btn-primary" data-live-search="true" required>
                      	<option value="0" selected>--SELECCIONE--</option>
                          <?php echo "$cadComboEdad"; ?>
                       </select>
                    </div>
                  </div>
                </div>
                <input type="hidden" name="area" id="area" value="<?=$cuidadDefecto?>">           

                </div>
                <div  class="card-footer">
                   <div class="">
                      <input class="btn btn-warning" id="boton_guardado_cliente" type="button" value="Guardar" onclick="javascript:adicionarCliente();" />
                       <input class="btn btn-danger" type="button" value="Cancelar" data-dismiss="modal" aria-hidden="true" />
                   </div>
                 </div> 
    </div>
  </div>
</div>  
<!--    end small modal -->
<!-- small modal -->
<div class="modal fade modal-primary" id="modalRecetaVenta" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content card">
               <div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon" style="background: #652BE9;color:#fff;">
                    <i class="material-icons">medical_services</i>
                  </div>
                  <h4 class="card-title text-dark font-weight-bold">Datos del Médico</h4>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">
<div class="row">
	<div class="col-sm-6">
                <div class="row">
                  <label class="col-sm-2 col-form-label">Nombre (*)</label>
                  <div class="col-sm-10">
                    <div class="form-group">
                      <input class="form-control" type="text" style="background: #A5F9EA;" id="nom_doctor" required value="" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label">Apellidos (*)</label>
                  <div class="col-sm-10">
                    <div class="form-group">
                      <input class="form-control" type="text" style="background: #A5F9EA;" id="ape_doctor" value="" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label">Dirección</label>
                  <div class="col-sm-10">
                    <div class="form-group">
                      <input class="form-control" type="text" style="background: #A5F9EA;" id="dir_doctor" value="" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label">Matricula</label>
                  <div class="col-sm-10">
                    <div class="form-group">
                      <input class="form-control" type="text" style="background: #A5F9EA;" id="mat_doctor" value="" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                    </div>
                  </div>
                </div>
                <div class="row d-none" id="div_ins_doctor">
                  <label class="col-sm-2 col-form-label">Institución (*)</label>
                  <div class="col-sm-10">
                    <div class="form-group">
                      <input class="form-control" type="text" style="background: #A5F9EA;" id="n_ins_doctor" id="n_ins_doctor" value="" required style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                    </div><label style='font-size:10px;color:red;'>Ej: CLINICA PRIVADA, CENTRO DE SALUD</label><br>                    
                  </div>
                </div>
                <div class="row d-none">
                  <label class="col-sm-2 col-form-label">Institución</label>
                  <div class="col-sm-10">
                    <div class="form-group">
                      <select class="selectpicker form-control" name="ins_doctor" id="ins_doctor" data-style="btn btn-primary" data-live-search="true" data-size='6' onchange="nuevaInstitucion();" required>
                           <?php echo "$cadComboInstitucion"; ?>
                       </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label">Especialidad</label>
                  <div class="col-sm-10">
                    <div class="form-group">
                      <select class="selectpicker form-control" name="esp_doctor"id="esp_doctor" data-style="btn btn-info" data-live-search="true" data-size='6' required >
                           <?php echo "$cadComboEspecialidades"; ?>
                       </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <label class="col-sm-2 col-form-label">2da Esp.</label>
                  <div class="col-sm-10">
                    <div class="form-group">
                      <select class="selectpicker form-control" name="esp_doctor2"id="esp_doctor2" data-style="btn btn-info" data-live-search="true" data-size='6' required>
                      	<option value="0">Ninguna</option>
                          <?php echo "$cadComboEspecialidades"; ?>
                       </select>
                    </div>
                  </div>
                </div>
                <br>
                <div class="float-left">
                        <button class="btn btn-default" onclick="guardarMedicoReceta();">Guardar Nuevo</button>
                </div>                 
                <br><br>
       </div>
	   <div class="col-sm-6">    
	            <div class="row">
                  <!-- <label class="col-sm-2 col-form-label">Nombres</label>
                  <div class="col-sm-4">
                    <div class="form-group">
                      <input class="form-control" type="text" style="background: #A5F9EA;" id="buscar_nom_doctor" value="" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                    </div>
                  </div> -->
                  <label class="col-sm-3 col-form-label">Nombres y Apellidos</label>
                  <div class="col-sm-8">
                    <div class="form-group">
                      <input class="form-control" type="text" style="background: #A5F9EA;" id="buscar_app_doctor" value="" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();"/>
                    </div>
                  </div>
                  <a href="#" class='btn btn-success btn-sm btn-fab float-right' onclick='buscarMedicoTest()'><i class='material-icons'>search</i></a>
                </div>
                <br>

                   <table class="table table-bordered table-condensed">
                   	  <thead>
                   	  	<!-- <tr class="" style="background: #652BE9;color:#fff;"><th width="60%">Nombre</th><th>Matricula</th><th>-</th></tr> -->
                   	  	<tr class="" style="background: #652BE9;color:#fff;"><th width="60%">Nombre</th><th>Especialidad</th><th>-</th></tr>
                   	  </thead>
                   	  <tbody id="datos_medicos">                   	  	
                   	  </tbody>
                   </table>                      
       </div>
</div>                      
                </div>
      </div>  
    </div>
  </div>
<!--    end small modal -->


<!-- small modal -->
<div class="modal fade modal-primary" id="modalAsignarNit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content card">
               <div class="card-header card-header-rose card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">person_search</i>
                  </div>
                  <h4 class="card-title text-rose font-weight-bold">Nit Encontrados</h4>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">
                	<table class="table table-sm table-condensed table-bordered"><thead><tr><th class="bg-info">Razón Social</th><th class="bg-info">NIT</th><th class="bg-info">-</th></tr></thead>
                	<tbody id="lista_nits"></tbody></table>   
                	<p>Seleccione el NIT correspondiente</p>                  
                </div>
      </div>  
    </div>
  </div>
<!--    end small modal -->


<!-- small modal -->
<div class="modal fade modal-primary" id="modalPuntosCampana" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content card">
                <div class="card-header card-header-info card-header-icon">
                  <div class="card-icon">
                    <i class="material-icons">control_point</i>
                  </div>
                  <h4 class="card-title text-dark font-weight-bold">Puntos por Campaña</h4>
                   <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
                <div class="card-body">

                <div class="row">
                  <label class="col-sm-2 col-form-label">Cliente</label>
                  <div class="col-sm-6">
                    <div class="form-group">
                    	<select class="selectpicker col-sm-12" id="cliente_campana" name="cliente_campana" data-live-search="true" data-size="6">                    		
                    	</select>
                    </div>
                  </div>
                  <div class="col-sm-2">
                				<a href="#" class="btn btn-success btn-sm" onclick="javascript:buscarClientePuntos();return false;">Buscar Puntos</a>
                	</div> 
                </div>      
                              
                <div id="tabla_puntos">
                	
                </div>
                </div>
                <div  class="card-footer">
                 </div> 
    </div>
  </div>
</div>  
<!--    end small modal -->


<!--<script src="dist/selectpicker/dist/js/bootstrap-select.js"></script>-->
 <script type="text/javascript" src="dist/js/functionsGeneral.js"></script>
 <script type="text/javascript">nuevaInstitucion();</script>

 <div id="dosificar_factura_sucursal" style="position: fixed;width:100%;height:100%;background: rgba(0, 0, 0,0.7);top:0;z-index: 9999999;color:#FFC300;" class="d-none"> 	
 	<h1 style="color:#FFC300;font-size:25px;">CUFD VENCIDO!</h1>
 	<center><img src="imagenes/facturacion.jpg" width="500"></center>
 	<br>
 	<?php 
 	
	 $codSucursal=$_COOKIE['global_agencia'];
	 $anio=date("Y");
	 $sqlCuis="select cuis FROM siat_cuis where cod_ciudad='$codSucursal' and estado=1 and cod_gestion='$anio'";
  $respCuis=mysqli_query($enlaceCon,$sqlCuis);
  $cuis=mysqli_result($respCuis,0,0);
  if($cuis!=""){

  }else{
  	$cuis="NO GENERADO PARA LA GESTIÓN ".$anio;
  }


 ?><center><p>Obtenga el CUFD para las ventas del día de HOY en la sucursal.</p>
 		<table class="table table-bordered table-success" style="width: 60%;">
 			<tr><td><b>SUCURSAL</b></td><td align="left"><?=$nombreAlmacenSesion?></td></tr>
 			<tr><td><b>CUIS</b></td><td align="left"><?=$cuis?></td></tr>
 			<tr><td><b>FECHA CUFD IMPUESTOS</b></td><td align="left"><?=date("d/m/Y")?></td></tr>
 		</table>
 	</center><?php 			
					?><center><a href="siat_folder/siat_cuis_cufd/generar_cufd.php?cod_ciudad=<?=$_COOKIE['global_agencia']?>&l=1" class="btn btn-warning" style="height: 60px;font-size: 20px;">Obtener CUFD <br> <img src="imagenes/actua.gif" width="80" height="80" style="position: absolute;top:0;right:0;"></a></center><?php			

 	?>  
 </div>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/61b377b680b2296cfdd119d2/1fmign8ns';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body>
</html>
<?php 
mysqli_close($enlaceCon);
}