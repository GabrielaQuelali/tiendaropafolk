<?php

require("conexionmysqli.php");
require('funciones.php');
require('function_formatofecha.php');
require("estilos_almacenes.inc");

?>
<html>
    <head>
        <title>Busqueda</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <link href="lib/css/paneles.css" rel="stylesheet" type="text/css"/>
        <!-- <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.button.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.mouse.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.draggable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.resizable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.dialog.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript" src="lib/js/xlibPrototipo-v0.1.js"></script> -->
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
	
function ShowBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='visible';
	document.getElementById('divProfileData').style.visibility='visible';
	document.getElementById('divProfileDetail').style.visibility='visible';
}

function HiddenBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
}
	
function ShowFacturar(codVenta,numCorrelativo){
	document.getElementById("cod_venta").value=codVenta;
	document.getElementById("nro_correlativo").value=numCorrelativo;
	
	document.getElementById('divRecuadroExt2').style.visibility='visible';
	document.getElementById('divProfileData2').style.visibility='visible';
	document.getElementById('divProfileDetail2').style.visibility='visible';
}

function HiddenFacturar(){
	document.getElementById('divRecuadroExt2').style.visibility='hidden';
	document.getElementById('divProfileData2').style.visibility='hidden';
	document.getElementById('divProfileDetail2').style.visibility='hidden';
}

function funOk(codReg,funOkConfirm)
{   $.get("programas/salidas/frmConfirmarCodigoSalida.php","codigo="+codReg, function(inf1) {
        dlgAC("#pnldlgAC","Codigo de confirmacion",inf1,function(){
            var cad1=$("input#idtxtcodigo").val();
            var cad2=$("input#idtxtclave").val();
            if(cad1!="" && cad2!="") {
                dlgEsp.setVisible(true);
                $.get("programas/salidas/validacionCodigoConfirmar.php","codigo="+cad1+"&clave="+cad2, function(inf2) {
                    inf2=xtrim(inf2);
                    dlgEsp.setVisible(false);
                    if(inf2=="" || inf2=="OK") {
                        /**/funOkConfirm();/**/
                    } else {
                        dlgA("#pnldlgA2","Informe","<div class='pnlalertar'>El codigo ingresado es incorrecto.</div>",function(){},function(){});
                    }
                });
            } else {
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Introduzca el codigo de confirmacion.</div>",function(){},function(){});
            }
        },function(){});
    });
}

function ajaxBuscarVentas(f){
	var fechaIniBusqueda, fechaFinBusqueda, nroCorrelativoBusqueda, verBusqueda, global_almacen, clienteBusqueda,vendedorBusqueda,tipoVentaBusqueda;
	fechaIniBusqueda=document.getElementById("fechaIniBusqueda").value;
	fechaFinBusqueda=document.getElementById("fechaFinBusqueda").value;
	nroCorrelativoBusqueda=document.getElementById("nroCorrelativoBusqueda").value;
	verBusqueda=document.getElementById("verBusqueda").value;
	global_almacen=document.getElementById("global_almacen").value;
	clienteBusqueda=document.getElementById("clienteBusqueda").value;
    vendedorBusqueda=document.getElementById("vendedorBusqueda").value;
    tipoVentaBusqueda=document.getElementById("tipoVentaBusqueda").value;

	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxSalidaVentas.php?fechaIniBusqueda="+fechaIniBusqueda+"&fechaFinBusqueda="+fechaFinBusqueda+"&nroCorrelativoBusqueda="+nroCorrelativoBusqueda+"&verBusqueda="+verBusqueda+"&global_almacen="+global_almacen+"&clienteBusqueda="+clienteBusqueda+"&vendedorBusqueda="+vendedorBusqueda+"&tipoVentaBusqueda="+tipoVentaBusqueda,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null)
}
function convertirNR(codFactura){
	if(confirm('Esta seguro de Anular la Factura y Convertir en NR.')){
		location.href='convertirNRAnularFac.php?codigo_registro='+codFactura;
	}else{
		return(false);
	}
}
function enviar_nav()
{   location.href='registrar_salidaventas.php';
}
function editar_salida(f)
{   var i;
    var j=0;
    var j_cod_registro, estado_preparado;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-2].value;
                estado_preparado=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro para anularlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para anularlo.');
        }
        else
        {   if(f.fecha_sistema.value==fecha_registro)
            {
                {   
                        location.href='editarVentas.php?codigo_registro='+j_cod_registro;
                }
            }
            else
            {   funOk(j_cod_registro,function(){
                        location.href='editarVentas.php?codigo_registro='+j_cod_registro;
                    });
            }
        }
    }
}
function anular_salida(f)
{   var i;
    var j=0;
    var j_cod_registro, estado_preparado;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-2].value;
                estado_preparado=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro para anularlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para anularlo.');
        }
        else
        {   funOk(j_cod_registro,function() {
                        location.href='anular_venta.php?codigo_registro='+j_cod_registro;
            });
        }
    }
}

function anular_salida_siat(f)
{   var i;
    var j=0;
    var j_cod_registro, estado_preparado;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-2].value;
                estado_preparado=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro para anularlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para anularlo.');
        }
        else
        {   
            // funOk(j_cod_registro,function() {
            //             location.href='anular_venta_siat.php?codigo_registro='+j_cod_registro;
            // });

            funVerifi(j_cod_registro);


        }
    }
}
function funVerifi(codReg){   
    // var cod_sucursal=$("#cod_sucursal").val();

var parametros={"codigo":codReg};
 $.ajax({
        type: "GET",
        dataType: 'html',
        url: "programas/salidas/frmConfirmarCodigoSalida_siat.php",
        data: parametros,
        success:  function (resp) { 
            $("#datos_anular").html(resp);
            $("#codigo_salida").val(codReg);
            $("#contrasena_admin").val("");
            $("#modalAnularFactura").modal("show");           
      }
 }); 
}

function confirmarCodigo(){ 
    document.getElementById('boton_anular').style.visibility='hidden';
   // var cod_sucursal=document.getElementById("cod_sucursal").value;  
   // var cod_personal=document.getElementById("cod_personal").value;  
   
  var cad1=$("input#idtxtcodigo").val();
  var cad2=$("input#idtxtclave").val(); 
  var per=$("#rpt_personal").val(); 

  // var rpt_tipoanulacion=$("#rpt_tipoanulacion").val(); 
  // var glosa_anulacion=$("input#glosa_anulacion").val(); 

  var enviar_correo=$("input#enviar_correo").val();
  var correo_destino=$("input#correo_destino").val();

  var parametros={"codigo":cad1,"clave":cad2,"per":per};
  $.ajax({
        type: "GET",
        dataType: 'html',
        url: "programas/salidas/validacionCodigoConfirmar_siat.php",
        data: parametros,
        success:  function (resp) { 
            if(resp==1) {
                location.href='anular_venta_siat.php?codigo_registro='+$("#codigo_salida").val()+'&id_caja='+per+'&enviar_correo='+enviar_correo+'&correo_destino='+correo_destino;
            }else{
               Swal.fire("Error!","El codigo que ingreso es incorrecto","error");
               $("#modalAnularFactura").modal("hide");    
            }
      }
 }); 
}

function cambiarCancelado(f)
{   var i;
    var j=0;
    var j_cod_registro, estado_preparado;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-2].value;
                estado_preparado=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro.');
        }
        else
		{      
			funOk(j_cod_registro,function() {
				location.href='cambiarEstadoCancelado.php?codigo_registro='+j_cod_registro+'';
			});            
        }
    }
}

function cambiarNoEntregado(f)
{   var i;
    var j=0;
    var j_cod_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente una Salida.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar una Salida.');
        }
        else
        {   location.href='cambiarEstadoNoEntregado.php?codigo_registro='+j_cod_registro+'';
        }
    }
}
function cambiarNoCancelado(f)
{   var i;
    var j=0;
    var j_cod_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente una Salida.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar una Salida.');
        }
        else
        {   location.href='cambiarEstadoNoCancelado.php?codigo_registro='+j_cod_registro+'';
        }
    }
}

function imprimirNotas(f)
{   var i;
    var j=0;
    datos=new Array();
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   datos[j]=f.elements[i].value;
                j=j+1;
            }
        }
    }
    if(j==0)
    {   alert('Debe seleccionar al menos una salida para imprimir la Nota.');
    }
    else
    {   window.open('navegador_detallesalidamaterialResumen.php?codigo_salida='+datos+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
    }
}
function preparar_despacho(f)
{   var i;
    var j=0;
    datos=new Array();
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   datos[j]=f.elements[i].value;
                j=j+1;
            }
        }
    }
    if(j==0)
    {   alert('Debe seleccionar al menos una salida para proceder a su preparado.');
    }
    else
    {   location.href='preparar_despacho.php?datos='+datos+'&tipo_material=1&grupo_salida=2';
    }
}
function enviar_datosdespacho(f)
{   var i;
    var j=0;
    datos=new Array();
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   datos[j]=f.elements[i].value;
                j=j+1;
            }
        }
    }
    if(j==0)
    {   alert('Debe seleccionar al menos una salida para proceder al registro del despacho.');
    }
    else
    {   location.href='registrar_datosdespacho.php?datos='+datos+'&tipo_material=1&grupo_salida=2';
    }
}
function llamar_preparado(f, estado_preparado, codigo_salida)
{   window.open('navegador_detallesalidamateriales.php?codigo_salida='+codigo_salida,'popup','');
}
        </script>
    </head>
    <body>
<?php


$txtnroingreso="";
$fecha1="";
$fecha2="";
$view="";
$fecha_sistema="";
$estado_preparado="";

if(!isset($txtnroingreso)){
    $txtnroingreso = $_GET["txtnroingreso"];    
}
if(!isset($fecha1)){
    $fecha1 = $_GET["fecha1"];
}
if(!isset($fecha2)){
    $fecha2 = $_GET["fecha2"];
}
if(!isset($view)){
    $view=$_GET["view"];
}
if(!isset($fecha_sistema)){

}

echo "<form method='post' action=''>";
echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";
//$global_admin_cargo
echo "<h1>Listado de Ventas</h1>";
echo "<table class='texto' cellspacing='0' width='90%'>
<tr><th>Leyenda:</th>
<th>Ventas Registradas</th><td bgcolor='#f9e79f' width='5%'></td>
<th>Ventas Entregadas</th><td bgcolor='#1abc9c' width='5%'></td>
<th>Ventas Anuladas</th><td bgcolor='#e74c3c' width='5%'></td>
<td bgcolor='' width='10%'>&nbsp;</td></tr></table>";
//
echo "<div class='divBotones'>
		<input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></td>		
		<!--input type='button' value='Anular' class='boton2' onclick='anular_salida(this.form)'-->
        <input type='button' value='Anular Con SIAT' class='boton2' onclick='anular_salida_siat(this.form)'>
    </div>";

echo "<br>";

echo "<div id='divCuerpo'><center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Nro. Doc</th><th>Fecha/hora<br>Registro Salida</th><th>Tipo de Salida</th><th>TipoPago</th><th>Razon Social</th><th>NIT</th><th>Observaciones</th><th>Factura</th><th>-</th>";
if($global_admin_cargo==1){
    echo "<th>-</th><th>-</th>";
}
    echo "</tr>";
	
echo "<input type='hidden' name='global_almacen' value='$global_almacen' id='global_almacen'>";

$consulta = "
	SELECT s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, 
	(select a.nombre_almacen from almacenes a where a.`cod_almacen`=s.almacen_destino), s.observaciones, 
	s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino, 
	(select c.nombre_cliente from clientes c where c.cod_cliente = s.cod_cliente), s.cod_tipo_doc, razon_social, nit,
	(select t.nombre_tipopago from tipos_pago t where t.cod_tipopago=s.cod_tipopago)as tipopago,siat_estado_facturacion
	FROM salida_almacenes s, tipos_salida ts 
	WHERE s.cod_tiposalida = ts.cod_tiposalida AND s.cod_almacen = '$global_almacen' and s.cod_tiposalida=1001 ";

if($txtnroingreso!="")
   {$consulta = $consulta."AND s.nro_correlativo='$txtnroingreso' ";
   }
if($fecha1!="" && $fecha2!="")
{	$consulta = $consulta."AND '$fecha1'<=s.fecha AND s.fecha<='$fecha2' ";
}
if($view==1){
	$consulta = $consulta." and s.cod_tipo_doc=1 ";
}   
$consulta = $consulta."ORDER BY s.fecha desc, s.hora_salida desc limit 0, 70 ";

//echo $consulta;
//
$resp = mysqli_query($enlaceCon,$consulta);
	
	
while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_salida = $dat[1];
    $fecha_salida_mostrar = "$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
    $hora_salida = $dat[2];
    $nombre_tiposalida = $dat[3];
    $nombre_almacen = $dat[4];
    $obs_salida = $dat[5];
    $estado_almacen = $dat[6];
    $nro_correlativo = $dat[7];
    $salida_anulada = $dat[8];
    $cod_almacen_destino = $dat[9];
	$nombreCliente=$dat[10];
	$codTipoDoc=$dat[11];
	$nombreTipoDoc=nombreTipoDoc($enlaceCon,$codTipoDoc);
	$razonSocial=$dat[12];
	$razonSocial=strtoupper($razonSocial);
	$nitCli=$dat[13];
	$tipoPago=$dat[14];
	
    echo "<input type='hidden' name='fecha_salida$nro_correlativo' value='$fecha_salida_mostrar'>";
	
	$sqlEstadoColor="select color from estados_salida where cod_estado='$estado_almacen'";
	$respEstadoColor=mysqli_query($enlaceCon,$sqlEstadoColor);
	$numFilasEstado=mysqli_num_rows($respEstadoColor);
	if($numFilasEstado>0){
		$datEstadoColor = mysqli_fetch_array($respEstadoColor);
		$color_fondo = $datEstadoColor[0];
		//$color_fondo=mysql_result($respEstadoColor,0,0);
		
	}else{
		$color_fondo="#ffffff";
	}
	$chk = "<input type='checkbox' name='codigo' value='$codigo'>";


    $urlDetalle="dFacturaElectronica.php";
    $siat_estado_facturacion=$dat['siat_estado_facturacion'];
    // if($codTipoDoc==4){
    //     $nro_correlativo="<i class=\"text-danger\">M-$nro_correlativo</i>";
    //     if($siat_estado_facturacion!=1){
    //          //$urlDetalle="dFactura.php";
    //     }
    // }else{
    //     $nro_correlativo="F-$nro_correlativo";
    // }
    $datosAnulacion="";
    $stikea="";
    $stikec="";
    if($salida_anulada==1){
        $stikea="<strike class='text-danger'>";        
        $stikec=" (ANULADO)</strike>";
        // $datosAnulacion="title='<small><b class=\"text-primary\">$nro_correlativo ANULADA<br>Caja:</b> ".nombreVisitador($dat['cod_chofer_anulacion'])."<br><b class=\"text-primary\">F:</b> ".date("d/m/Y H:i",strtotime($dat['fecha_anulacion']))."</small>' data-toggle='tooltip'";
        $chk="";
    }

	
    echo "<input type='hidden' name='estado_preparado' value='$estado_preparado'>";
    //echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$nombre_ciudad</td><td>$nombre_almacen</td><td>$nombre_funcionario</td><td>&nbsp;$obs_salida</td><td>$txt_detalle</td></tr>";
    echo "<tr>";
    echo "<td align='center'>&nbsp;$chk</td>";
    echo "<td align='center'>$stikea$nombreTipoDoc-$nro_correlativo $stikec</td>";
    echo "<td align='center'>$stikea$fecha_salida_mostrar $hora_salida$stikec</td>";
    echo "<td>$stikea $nombre_tiposalida $stikec</td>";
    echo "<td>$stikea $tipoPago $stikec</td><td>$stikea &nbsp;$razonSocial $stikec</td><td>$stikea&nbsp;$nitCli $stikec</td><td>$stikea &nbsp;$obs_salida $stikec</td>";
    $url_notaremision = "navegador_detallesalidamuestras.php?codigo_salida=$codigo";    
	
	$urlConversionFactura="convertNRToFactura.php?codVenta=$codigo";    
    
	$NRparaMostrar=$nombreTipoDoc."-".$nro_correlativo;
	$fechaParaMostrar=$fecha_salida_mostrar;
	
	/*echo "<td bgcolor='$color_fondo'><a href='javascript:llamar_preparado(this.form, $estado_preparado, $codigo)'>
		<img src='imagenes/icon_detail.png' width='30' border='0' title='Detalle'></a></td>";
	*/
    switch ($siat_estado_facturacion) {
        case 1:$color_fondo="#99E80A";break;
        case 2:$color_fondo="#FF2E09";break;
        case 3:$color_fondo="#12A4DF";break;  
        default:$color_fondo="#12A4DF";break;      
    }
	if($codTipoDoc==1){
		echo "<td  bgcolor='$color_fondo'><a href='formatoFacturaOnLine.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Formato Pequeño'></a></td>";
	}else{
		echo "<td  bgcolor='$color_fondo'><a href='formatoNotaRemision2.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/factura1.jpg' width='30' border='0' title='Factura Formato Pequeño'></a></td>";
	}
	
    $codigoVentaCambio=0;
    $sqlCambio="select c.cod_cambio from salida_almacenes c where c.cod_cambio=$codigo";
    $respCambio=mysqli_query($enlaceCon,$sqlCambio);
    if($global_admin_cargo==1){
     while($datCambio=mysqli_fetch_array($respCambio)){
        $codigoVentaCambio=$datCambio[0];        
     }
     if($codigoVentaCambio==0 ){
        echo "<td  bgcolor='$color_fondo'><a href='cambiarProductoVenta.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/change.png' width='30' border='0' title='Cambio de Producto'></a></td>";
     }else{
        echo "<td  bgcolor='$color_fondo'><a href='notaSalidaCambio.php?codVenta=$codigo' target='_BLANK'><img src='imagenes/icon_detail.png' width='30' border='0' title='Ver Detalle del Cambio'></a></td>";
     }

	 if($codTipoDoc==2 && $salida_anulada==0){
		echo "<td bgcolor='$color_fondo'>
		<a href='#' onClick='ShowFacturar($codigo,$nro_correlativo);'>
		<img src='imagenes/icon_detail.png' width='30' border='0' title='Convertir en Factura'></a></td>";	
	 }elseif($codTipoDoc==1 && $salida_anulada==0){
		echo "<td align='center' bgcolor='$color_fondo'>
		<a href='#' onClick='convertirNR($codigo);'>
		<img src='imagenes/restaurar2.png' width='20' border='0' title='Convertir en NR y Anular Factura'></a>
		</td>";
	 }else{
        echo "<td align='center' bgcolor='$color_fondo'> </td>";
     }
     // if($codTipoDoc!=1 && $codTipoDoc!=2){
     //    echo "<td  bgcolor='$color_fondo'> ";
     //    echo "</td>";   
     // }
     echo "<td  bgcolor='$color_fondo'> <a href='$urlDetalle?codigo_salida=$codigo' target='_BLANK' title='DOCUMENTO FACTURA'  class='text-dark'><i class='material-icons'>description</i></a>";
        echo "</td>";
    }

	echo "</tr>";
}
echo "</table></center><br>";
echo "</div>";

echo "<div class='divBotones'>
		<input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></td>		
		<!--input type='button' value='Anular' class='boton2' onclick='anular_salida(this.form)'-->
        <input type='button' value='Anular Con SIAT' class='boton2' onclick='anular_salida_siat(this.form)'>
    </div>";
	
echo "</form>";

?>
<!-- small modal -->
<div class="modal fade modal-primary" id="modalAnularFactura" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content card">
        <div class="card-header card-header-danger card-header-icon">
          <div class="card-icon">
            <i class="material-icons">delete</i>
          </div>
          <h4 class="card-title text-danger font-weight-bold">Anulación de Facturas</h4>
          <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
            <i class="material-icons">close</i>
          </button>
        </div>
        <input type="hidden" name="codigo_salida" id="codigo_salida" value="0">
        <div class="card-body" id="datos_anular">
           
        </div>
        <div class="card-footer" >
           <button id="boton_anular" name="boton_anular" class="btn btn-default" onclick="confirmarCodigo()">ANULAR</button>
        </div>
    </div>  
    </div>
</div>
<!--    end small modal -->


<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 450px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:400px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Buscar Ventas</h2>
		<table align='center' class='texto'>
			<tr>
				<td>Fecha Ini(dd/mm/aaaa)</td>
				<td>
				<input type='text' name='fechaIniBusqueda' id="fechaIniBusqueda" class='texto'>
				</td>
			</tr>
			<tr>
				<td>Fecha Fin(dd/mm/aaaa)</td>
				<td>
				<input type='text' name='fechaFinBusqueda' id="fechaFinBusqueda" class='texto'>
				</td>
			</tr>
			<tr>
				<td>Nro. de Documento</td>
				<td>
				<input type='text' name='nroCorrelativoBusqueda' id="nroCorrelativoBusqueda" class='texto'>
				</td>
			</tr>			
			<tr>
				<td>Cliente:</td>
				<td>
					<select name="clienteBusqueda" class="texto" id="clienteBusqueda">
						<option value="0">Todos</option>
					<?php
						$sqlClientes="select c.`cod_cliente`, c.`nombre_cliente` from clientes c order by 2";
						$respClientes=mysqli_query($enlaceCon,$sqlClientes);
						while($datClientes=mysqli_fetch_array($respClientes)){
							$codCliBusqueda=$datClientes[0];
							$nombreCliBusqueda=$datClientes[1];
					?>
							<option value="<?php echo $codCliBusqueda;?>"><?php echo $nombreCliBusqueda;?></option>
					<?php
						}
					?>
					</select>
				
				</td>
			</tr>
            <tr>
                <td>Vendedor:</td>
                <td>
                    <select name="vendedorBusqueda" class="texto" id="vendedorBusqueda">
                        <option value="0">Todos</option>
                    <?php
                        $sqlClientes="SELECT DISTINCT c.codigo_funcionario,CONCAT(c.paterno,' ',c.materno,' ',c.nombres) as personal from salida_almacenes s join funcionarios c on c.codigo_funcionario=s.cod_chofer order by 2;";
                        $respClientes=mysqli_query($enlaceCon,$sqlClientes);
                        while($datClientes=mysqli_fetch_array($respClientes)){
                            $codCliBusqueda=$datClientes[0];
                            $nombreCliBusqueda=$datClientes[1];
                    ?>
                            <option value="<?php echo $codCliBusqueda;?>"><?php echo $nombreCliBusqueda;?></option>
                    <?php
                        }
                    ?>
                    </select>
                
                </td>
            </tr>			
            <tr>
                <td>Tipo Pago:</td>
                <td>
                    <select name="tipoVentaBusqueda" class="texto" id="tipoVentaBusqueda">
                        <option value="0">Todos</option>
                    <?php
                        $sqlClientes="select c.`cod_tipopago`, c.`nombre_tipopago` from tipos_pago c order by 2";
                        $respClientes=mysqli_query($enlaceCon,$sqlClientes);
                        while($datClientes=mysql_fetch_array($respClientes)){
                            $codCliBusqueda=$datClientes[0];
                            $nombreCliBusqueda=$datClientes[1];
                    ?>
                            <option value="<?php echo $codCliBusqueda;?>"><?php echo $nombreCliBusqueda;?></option>
                    <?php
                        }
                    ?>
                    </select>
                
                </td>
            </tr>
			<tr>
				<td>Ver:</td>
				<td>
				<select name='verBusqueda' id='verBusqueda' class='texto' >
					<option value='0'>Todo</option>
					<option value='1'>No Cancelados</option>
                    <option value='2'>Anulados</option>
				</select>
				</td>
			</tr>			
		</table>	
		<center>
			<input type='button' value='Buscar' onClick="ajaxBuscarVentas(this.form)">
			<input type='button' value='Cancelar' onClick="HiddenBuscar()">
			
		</center>
	</div>
</div>


<div id="divRecuadroExt2" style="background-color:#666; position:absolute; width:800px; height: 350px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>
<div id="divProfileData2" style="background-color:#FFF; width:750px; height:300px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail2" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Convertir a Factura</h2>
		<form name="form1" id="form1" action="convertNRToFactura.php" method="POST">
		<table align='center' class='texto'>
			<tr>
				<input type="hidden" name="cod_venta" id="cod_venta" value="0">
				<td>Nro.</td>
				<td>
				<input type='text' name='nro_correlativo' id="nro_correlativo" class='texto' disabled>
				</td>
			</tr>
			<tr>
				<td>Razon Social</td>
				<td>
				<input type='text' name='razon_social_convertir' id="razon_social_convertir" class='texto' required>
				</td>
			</tr>
			<tr>
				<td>NIT</td>
				<td>
				<input type='number' name='nit_convertir' id="nit_convertir" class='texto' required>
				</td>
			</tr>
		</table>	
		<center>
			<input type='submit' value='Convertir' class='boton' >
			<input type='button' value='Cancelar' class='boton2' onClick="HiddenFacturar()">
			
		</center>
		</form>
	</div>
</div>





        <script type='text/javascript' language='javascript'>
        </script>
        <div id="pnldlgfrm"></div>
        <div id="pnldlgSN"></div>
        <div id="pnldlgAC"></div>
        <div id="pnldlgA1"></div>
        <div id="pnldlgA2"></div>
        <div id="pnldlgA3"></div>
        <div id="pnldlgArespSvr"></div>
        <div id="pnldlggeneral"></div>
        <div id="pnldlgenespera"></div>
    </body>
</html>


