<?php
require("conexionmysqli.inc");
require('function_formatofecha.php');
require("estilos_almacenes.inc");
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="lib/externos/jquery/jquery-ui/completo/jquery-ui-1.8.9.custom.css" rel="stylesheet" type="text/css"/>
        <link href="lib/css/paneles.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.core.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.widget.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.button.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.mouse.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.draggable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.position.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.resizable.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.dialog.min.js"></script>
        <script type="text/javascript" src="lib/externos/jquery/jquery-ui/minimo/jquery.ui.datepicker.min.js"></script>
        <script type="text/javascript" src="lib/js/xlibPrototipo-v0.1.js"></script>
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

function ShowBuscarVenta(idRec){
	document.getElementById('divRecuadroExt2').style.visibility='visible';
	document.getElementById('divProfileData2').style.visibility='visible';
	document.getElementById('divProfileDetail2').style.visibility='visible';
	document.getElementById('divboton').style.visibility='visible';
	
	var contenedor;
	document.getElementById('reciboActivo').value=idRec;
	var fechaIniBusqueda2=document.getElementById('fechaIniBusqueda2').value;
	var fechaFinBusqueda2=document.getElementById('fechaFinBusqueda2').value;
	var nroCorrelativoBusqueda=document.getElementById('nroCorrelativoBusqueda').value;
	var vendedorBusqueda=document.getElementById('vendedorBusqueda').value;
	var tipoPagoBusqueda=document.getElementById('tipoPagoBusqueda').value;
	
	contenedor = document.getElementById('divListaMateriales');		
	ajax=nuevoAjax();	
	ajax.open("GET", "ajaxListaVentas.php?fechaIniBusqueda2="+fechaIniBusqueda2+"&fechaFinBusqueda2="+fechaFinBusqueda2+"&nroCorrelativoBusqueda="+nroCorrelativoBusqueda+"&vendedorBusqueda="+vendedorBusqueda+"&tipoPagoBusqueda="+tipoPagoBusqueda+"&recibo="+document.getElementById('reciboActivo').value,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}

function setVenta(idRec,codigo,nombreTipoDoc,nro_correlativo,fecha,monto,vendedor){
	
	ajax=nuevoAjax();	
	ajax.open("GET", "guardaRelacionaVentaRecibo.php?recibo="+idRec+"&codSalidaAlmacen="+codigo,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);

	document.getElementById('divRecuadroExt2').style.visibility='hidden';
	document.getElementById('divProfileData2').style.visibility='hidden';
	document.getElementById('divProfileDetail2').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';
	document.getElementById('divVenta'+idRec).innerHTML=nombreTipoDoc+" "+nro_correlativo+" "+fecha+" <strong>"+monto+"</strong><br/><a  onclick='quitarVenta("+idRec+")' >Quitar Venta</a>";
	
}

function quitarVenta(idRec){

	if (confirm("Desea eliminar la Relacion  del Recibo Nro."+idRec+" - Venta")){
		ajax=nuevoAjax();	
		ajax.open("GET", "eliminaRelacionVentaRecibo.php?recibo="+idRec,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
		}
		ajax.send(null);
		document.getElementById('divVenta'+idRec).innerHTML="<a  onclick='ShowBuscarVenta("+idRec+")' >Enlazar a Venta</a>";
	}	
}
function cerrarRecibo(idRec){

	if (confirm("Esta seguro de Cerrar el Recibo Nro."+idRec+" el proceso no podra ser revertido.")){
		ajax=nuevoAjax();	
		ajax.open("GET", "cerrarRecibo.php?recibo="+idRec,true);
		ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
		}
		ajax.send(null);
		document.getElementById('divEstadoRecibo'+idRec).innerHTML="CERRADO";
		document.getElementById('divCheckRecibo'+idRec).innerHTML="";
	}	
}



function Hidden(){
	document.getElementById('divRecuadroExt2').style.visibility='hidden';
	document.getElementById('divProfileData2').style.visibility='hidden';
	document.getElementById('divProfileDetail2').style.visibility='hidden';
	document.getElementById('divboton').style.visibility='hidden';

}

function listaVentas(){

	var contenedor;
	
	var fechaIniBusqueda2=document.getElementById('fechaIniBusqueda2').value;
	var fechaFinBusqueda2=document.getElementById('fechaFinBusqueda2').value;
	var nroCorrelativoBusqueda=document.getElementById('nroCorrelativoBusqueda').value;
	var vendedorBusqueda=document.getElementById('vendedorBusqueda').value;
	var tipoPagoBusqueda=document.getElementById('tipoPagoBusqueda').value;
	
	contenedor = document.getElementById('divListaMateriales');		
	ajax=nuevoAjax();	
	ajax.open("GET", "ajaxListaVentas.php?fechaIniBusqueda2="+fechaIniBusqueda2+"&fechaFinBusqueda2="+fechaFinBusqueda2+"&nroCorrelativoBusqueda="+nroCorrelativoBusqueda+"&vendedorBusqueda="+vendedorBusqueda+"&tipoPagoBusqueda="+tipoPagoBusqueda+"&recibo="+document.getElementById('reciboActivo').value,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
		}
	}
	ajax.send(null);
}




function HiddenBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
}
		
function funOk(codReg,funOkConfirm)
{   
	$.get("programas/recibos/frmConfirmarCodigoRecibo.php","codigo="+codReg, function(inf1) {
        dlgAC("#pnldlgAC","Codigo de confirmacion",inf1,function(){
            var cad1=$("input#idtxtcodigo").val();
            var cad2=$("input#idtxtclave").val();
            if(cad1!="" && cad2!="") {
                dlgEsp.setVisible(true);
                $.get("programas/recibos/validacionCodigoConfirmar.php","codigo="+cad1+"&clave="+cad2, function(inf2) {
                    inf2=xtrim(inf2);
                    dlgEsp.setVisible(false);
                    if(inf2=="" || inf2=="OK") {
                        /**/funOkConfirm();/**/
                    } else {
                        dlgA("#pnldlgA2","Informe","<div class='pnlalertar'>El codigo ingresado es incorrecto.</div>",function(){},function(){});
                    }
                });
            } else {
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Introducir el codigo de confirmacion.</div>",function(){},function(){});
            }
        },function(){});
    });
}



function ajaxBuscarRecibos(f){
	
	var fechaIniBusqueda, fechaFinBusqueda, cliente,tipoRecibo, proveedor, detalle, global_almacen;
	
	fechaIniBusqueda=document.getElementById("fechaIniBusqueda").value;
	
	fechaFinBusqueda=document.getElementById("fechaFinBusqueda").value;
	
	cliente=document.getElementById("cliente").value;
	tipoRecibo=document.getElementById("tipoRecibo").value;
	proveedor=document.getElementById("proveedor").value;
	detalle=document.getElementById("detalle").value;

	
	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxBuscarRecibos.php?fechaIniBusqueda="+fechaIniBusqueda+"&fechaFinBusqueda="+fechaFinBusqueda+"&cliente="+cliente+"&tipoRecibo="+tipoRecibo+"&proveedor="+proveedor+"&detalle="+detalle,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null);
}



function registrar_recibo()
{   location.href='registrar_recibo.php';
}



function editar_recibo(f)
{   var i;
    var j=0;
    var j_cod_registro;
    //var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
               /// fecha_registro=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro para editalo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para editarlo.');
        }
        else
        {     
                funOk(j_cod_registro,function(){
                    location.href='editar_recibo.php?idRecibo='+j_cod_registro+'';
                });
        }
    }
}
function anular_recibo(f)
{   var i;
    var j=0;
    var j_cod_registro;
    //var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
               // fecha_registro=f.elements[i-1].value;
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
        {   //window.open('anular_ingreso.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=2','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=280,height=150');
                funOk(j_cod_registro,function(){
                    location.href='anular_recibo.php?idRecibo='+j_cod_registro+'';
                });
        }
    }
}
        </script>
    </head>
    <body>

<?php

$globalAlmacen=$_COOKIE['global_almacen'];
  $global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$global_agencia=$_COOKIE['global_agencia'];

echo "<form method='post' action='listaRecibos.php'>";



$consulta = " select r.id_recibo,r.fecha_recibo,r.cod_ciudad,ciu.descripcion,
r.nombre_recibo,r.desc_recibo,r.monto_recibo,
r.created_by,r.modified_by,r.created_date,r.modified_date, r.cel_recibo,r.recibo_anulado,r.cod_tipopago, tp.nombre_tipopago,
r.cod_tiporecibo, tr.nombre_tiporecibo, r.cod_proveedor, p.nombre_proveedor, r.cod_salida_almacen,
r.cod_estadorecibo, er.nombre_estado, r.resta_ventas_proveedor,gr.nombre_gruporecibo
from recibos r 
inner join ciudades ciu on (r.cod_ciudad=ciu.cod_ciudad)
inner join tipos_pago tp on(r.cod_tipopago=tp.cod_tipopago)
inner join tipos_recibo tr on(r.cod_tiporecibo=tr.cod_tiporecibo)
left  join proveedores p on (r.cod_proveedor=p.cod_proveedor)
left  join estados_recibo er on (r.cod_estadorecibo=er.cod_estado)
left join grupos_recibo gr on (r.cod_gruporecibo=gr.cod_gruporecibo) 
where r.cod_ciudad=".$global_agencia." order by r.id_recibo DESC,r.cod_ciudad desc";
//echo "consulta=".$consulta;
$resp = mysqli_query($enlaceCon,$consulta);
?>

<h1>RECIBOS</h1>
<table border='1' cellspacing='0' class='textomini'><tr><th>LEYENDA:</th><th>Recibos Anulados</th><td bgcolor='#ff8080' width='10%'></td><th>RECIBOS UTILIZADOS</th><td bgcolor='#ffff99' width='10%'></td><th>RECIBOS UTILIZADOS</th><td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";

<div class='divBotones'><input type='button' value='Registrar' name='adicionar' class='boton' onclick='registrar_recibo()'>
<input type='button' value='Editar' class='boton' onclick='editar_recibo(this.form)'>
<input type='button' value='Anular' name='adicionar' class='boton2' onclick='anular_recibo(this.form)'>
<input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>

<br><div id='divCuerpo'>
<br><center><table class='texto'>
<tr>
<th>&nbsp;</th>
<th>Tipo Recibo</th>
<th>Recibo</th>
<th>Fecha</th>
<th>Forma Pago</th>
<th>Monto</th>
<th>Contacto</th>
<th>Grupo de Recibo</th>
<th>Descripcion</th>
<th>Proveedor</th>
<th>Resta Venta<br>Proveedor</th>
<th>Registrado Por</th>
<th>Modificado Por</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>Estado</th>
</tr>
<?php
while ($dat = mysqli_fetch_array($resp)) {
	$id_recibo= $dat['id_recibo'];
	$fecha_recibo= $dat['fecha_recibo'];
	$vector_fecha_recibo=explode("-",$fecha_recibo);
	$fecha_recibo_mostrar=$vector_fecha_recibo[2]."/".$vector_fecha_recibo[1]."/".$vector_fecha_recibo[0];
	$cod_ciudad= $dat['cod_ciudad'];
	$descripcion= $dat['descripcion'];
	$nombre_recibo= $dat['nombre_recibo'];
	$desc_recibo= $dat['desc_recibo'];
	$monto_recibo= $dat['monto_recibo'];
	$created_by= $dat['created_by'];
	$modified_by= $dat['modified_by'];
	$created_date= $dat['created_date'];
	$modified_date= $dat['modified_date'];
	$cel_recibo = $dat['cel_recibo'];
	$recibo_anulado= $dat['recibo_anulado'];
	$cod_tipopago= $dat['cod_tipopago'];
	$nombre_tipopago= $dat['nombre_tipopago'];
	$cod_tiporecibo= $dat['cod_tiporecibo'];
	$nombre_tiporecibo= $dat['nombre_tiporecibo'];
	$cod_proveedor= $dat['cod_proveedor'];
	$nombre_proveedor= $dat['nombre_proveedor'];
	$cod_salida_almacen= $dat['cod_salida_almacen'];
	$cod_estadorecibo= $dat['cod_estadorecibo'];
	$nombre_estadorecibo= $dat['nombre_estado'];
	$resta_ventas_proveedor= $dat['resta_ventas_proveedor'];
	$nombre_gruporecibo= $dat['nombre_gruporecibo'];
	//Datos de la Venta///
	
	$sqlVenta = " SELECT s.fecha, s.hora_salida, s.nro_correlativo, s.cod_tipo_doc, td.abreviatura, razon_social, nit,
    s.monto_total,s.monto_final,concat(f.paterno,' ',f.materno,' ',f.nombres) as vendedor
    FROM salida_almacenes s
	left join tipos_salida ts  on (s.cod_tiposalida = ts.cod_tiposalida)
	left join tipos_docs td   on (s.cod_tipo_doc = td.codigo)
	left join funcionarios f   on (s.cod_chofer = f.codigo_funcionario)
    WHERE  s.cod_almacen = '".$globalAlmacen."' and s.cod_tiposalida=1001 and s.cod_salida_almacenes=".$cod_salida_almacen;		
	$respVenta = mysqli_query($enlaceCon,$sqlVenta);
	/////////
		$fecha_salida = ""; $fecha_salida_mostrar = "";		$hora_salida = "";
		$nro_correlativo= ""; $cod_tipo_doc= "";$abreviatura_tipodoc= "";
		$razon_social= "";$nit="";$monto_total="";$monto_final= "";	$vendedor= "";
	/////////
	while ($datVenta = mysqli_fetch_array($respVenta)) {	
		$fecha_salida = $datVenta['fecha'];
		$fecha_salida_mostrar = $fecha_salida[8].$fecha_salida[9]."-".$fecha_salida[5].$fecha_salida[6]."-".$fecha_salida[0].$fecha_salida[1].$fecha_salida[2].$fecha_salida[3];
		$hora_salida = $datVenta['hora_salida'];
		$nro_correlativo= $datVenta['nro_correlativo'];
		$cod_tipo_doc= $datVenta['cod_tipo_doc'];
		$abreviatura_tipodoc= $datVenta['abreviatura'];
		$razon_social= $datVenta['razon_social'];
		$nit= $datVenta['nit'];
		$monto_total= $datVenta['monto_total'];
		$monto_final= $datVenta['monto_final'];		
		$vendedor= $datVenta['vendedor'];
	}
	
	
	$created_date_mostrar="";
	// formatoFechaHora
	if(!empty($created_date)){
		$vector_created_date = explode(" ",$created_date);
		$fechaReg=explode("-",$vector_created_date[0]);
		$created_date_mostrar = $fechaReg[2]."/".$fechaReg[1]."/".$fechaReg[0]." ".$vector_created_date[1];
	}
	// fin formatoFechaHora
	$modified_date= $dat['modified_date'];
	$cel_recibo = $dat['cel_recibo'];
	$modified_date_mostrar="";
	// formatoFechaHora
	if(!empty($modified_date)){
		$vector_modified_date = explode(" ",$modified_date);
		$fechaEdit=explode("-",$vector_modified_date[0]);
		$modified_date_mostrar = $fechaEdit[2]."/".$fechaEdit[1]."/".$fechaEdit[0]." ".$vector_modified_date[1];
	}
	// fin formatoFechaHora
	
	/////	
		$sqlRegUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$created_by;
		$respRegUsu=mysqli_query($enlaceCon,$sqlRegUsu);
		$usuReg =" ";
		while($datRegUsu=mysqli_fetch_array($respRegUsu)){
			$usuReg =$datRegUsu['nombres'][0].$datRegUsu['paterno'];		
		}
	//////
		$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
		$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
		$usuMod ="";
		while($datModUsu=mysqli_fetch_array($respModUsu)){
			$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
		}
	////////////
	  $color_fondo = "";
	if ($recibo_anulado == 1) {
        $color_fondo = "#ff8080";
        
    }

?>	
   <tr style="background-color: <?=$color_fondo;?>;">
	<td><?php 
	if ($recibo_anulado == 0) {
		
		if($cod_estadorecibo==1){
	?>	
		<div id="divCheckRecibo<?php echo $id_recibo;?>"><input type="checkbox" name="id_recibo" id="id_recibo" value="<?=$id_recibo;?>"></div>
	<?php 
		}
	}
	?>	
	</td>
	<td><?=$nombre_tiporecibo;?></td>	
	<td><?=$id_recibo;?></td>
	<td><?=$fecha_recibo_mostrar;?></td>
	<td><?=$nombre_tipopago;?></td>
	<td><?=$monto_recibo;?></td>
	<td><?=$nombre_recibo;?></td>
	<td><?=$nombre_gruporecibo;?></td>
	<td><?=$desc_recibo;?></td>
	<td><?=$nombre_proveedor;?></td>		
	<td>
	<?php    

		if($resta_ventas_proveedor=="0"){
			echo "NO";
		}
		if($resta_ventas_proveedor=="1"){
			echo "SI";
		}
		
		?>
	</td>
	<td><?=$usuReg;?><br><?=$created_date_mostrar;?></td>
	<td><?=$usuMod;?><br><?=$modified_date_mostrar;?></td>		
	<td><a href="formatoRecibo.php?idRecibo=<?=$id_recibo;?>" target="_BLANK">Ver Recibo</a></td>

	<td>
	
	<div id="divVenta<?php echo $id_recibo;?>">
	<?=$abreviatura_tipodoc." ".$nro_correlativo."<br>".$fecha_salida_mostrar." ".$hora_salida." <strong>".$monto_final."</strong>";?>
	<?php 
	
	if ($recibo_anulado == 0) {
	  if (empty($cod_salida_almacen)){?>
		<a  onclick='ShowBuscarVenta(<?=$id_recibo;?>)' >Enlazar a Venta</a>
	<?php  }else{?>
		<a  onclick='quitarVenta(<?=$id_recibo;?>)' >Quitar Venta</a>
	<?php  }
		}
	?>
	</div>
		
	</td>
		<td>
		<?php 	if ($recibo_anulado == 0) { ?>
		<div id="divEstadoRecibo<?php echo $id_recibo;?>"><?=$nombre_estadorecibo;?>
		<?php if ($cod_estadorecibo == 1) {?>
		<a  onclick='cerrarRecibo(<?=$id_recibo;?>)' >Cerrar Recibo</a>
	
	<?php  }

	?>
		</div>
		<?php 	} ?>
		</td>
		
	</tr>
<?php	
}
?>
</table></center><br>
</div>

<div class='divBotones'><input type='button' value='Registrar' name='adicionar' class='boton' onclick='registrar_recibo()'>
<input type='button' value='Editar' class='boton' onclick='editar_recibo(this.form)'>
<input type='button' value='Anular' name='adicionar' class='boton2' onclick='anular_recibo(this.form)'>
<td><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";
<input type='hidden' name='reciboActivo' id='reciboActivo' >
</form>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 450px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:400px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Buscar Recibos</h2>
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
				<td>Tipo Recibo</td>
				<td>
					<select name="tipoRecibo" id="tipoRecibo" class="texto"  >
					<option value="" >--</option>
					<?php	
						$sqlTipoRecibo="select cod_tiporecibo, nombre_tiporecibo from tipos_recibo where estado=1  order by cod_tiporecibo asc";
						$respTipoRecibo=mysqli_query($enlaceCon,$sqlTipoRecibo);
						while($datTipoRecibo=mysqli_fetch_array($respTipoRecibo)){	
					?>
					<?php	$codTiporecibo=$datTipoRecibo[0];
							$nombreTiporecibo=$datTipoRecibo[1];
					?>
					<option value="<?=$codTiporecibo;?>" ><?=$nombreTiporecibo;?></option>
		
				<?php	}?>
					</select>
			</td>
			</tr>	
<tr>
				<td>Proveedor</td>
				<td>
					<select name="proveedor" id="proveedor" class="texto"  >
						<option value="" >--</option>
					<?php	
						$sql3="select cod_proveedor, nombre_proveedor from proveedores where estado=1  order by nombre_proveedor asc";
						$resp3=mysqli_query($enlaceCon,$sql3);
						while($dat3=mysqli_fetch_array($resp3)){	
					?>
					<?php	$codProveedor=$dat3[0];
							$nombreProveedor=$dat3[1];
					?>
					<option value="<?=$codProveedor;?>" ><?=$nombreProveedor;?></option>
		
				<?php	}?>
				</select>
				</td>
			</tr>					
			<tr>
				<td>Nombre Cliente</td>
				<td>
				<input type='text' name='cliente' id="cliente" class='texto'>
				</td>
			</tr>			
						<tr>
				<td>Detalle</td>
				<td>
				<input type='text' name='detalle' id="detalle" class='texto'>
				</td>
			</tr>		
		</table>	
		<center><br>
			<input type='button' value='Buscar' class='boton' onClick="ajaxBuscarRecibos(this.form)">
			<input type='button' value='Cancelar' class='boton2' onClick="HiddenBuscar();">
			
		</center>
	</div>
</div>

<div id="divboton" style="position: absolute; top:20px; left:920px;visibility:hidden; text-align:center; z-index:3">
	<a href="javascript:Hidden();"><img src="imagenes/cerrar4.png" height="45px" width="45px"></a>
</div>
<div id="divRecuadroExt2" style="background-color:#666; position:absolute; width:800px; height: 500px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData2" style="background-color:#FFF; width:750px; height:450px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail2" style="visibility:hidden; text-align:center; height:445px; overflow-y: scroll;">
		<table align='center' class="texto">
			<tr><th>Fecha Ini(dd/mm/aaaa)</th><th>Fecha Final</th><th>Nro de Doc</th><th>Vendedor</th><th>Forma Pago</th><th>&nbsp;</th></tr>
			 <tr>
                
                <td><input type='text' name='fechaIniBusqueda2' id="fechaIniBusqueda2" size="12" class='texto'></td>

                <td><input type='text' name='fechaFinBusqueda2' id="fechaFinBusqueda2" size="12" class='texto'></td>

                <td><input type='text' name='nroCorrelativoBusqueda' id="nroCorrelativoBusqueda" size="10" class='texto'></td>
                <td>
                    <select name="vendedorBusqueda" class="texto" id="vendedorBusqueda">
                        <option value="">--</option>
                    <?php
                        $sqlVendedor="SELECT DISTINCT c.codigo_funcionario,CONCAT(c.paterno,' ',c.materno,' ',c.nombres) as personal from salida_almacenes s join funcionarios c on c.codigo_funcionario=s.cod_chofer order by 2;";
                        $respVendedor=mysqli_query($enlaceCon,$sqlVendedor);
                        while($datVendedor=mysqli_fetch_array($respVendedor)){
                            $codFuncionarioBusqueda=$datVendedor[0];
                            $nombreFuncionarioBusqueda=$datVendedor[1];
                    ?>
                            <option value="<?php echo $codFuncionarioBusqueda;?>"><?php echo $nombreFuncionarioBusqueda;?></option>
                    <?php
                        }
                    ?>
                    </select>
                
                </td>
                <td>
                    <select name="tipoPagoBusqueda" class="texto" id="tipoPagoBusqueda">
                        <option value="">--</option>
                    <?php
                        $sql7="select cod_tipopago, nombre_tipopago from tipos_pago order by 2";						
                        $resp7=mysqli_query($enlaceCon,$sql7);
                        while($dat7=mysqli_fetch_array($resp7)){
                            $codTipoPagoBusqueda=$dat7[0];
                            $nombreTipoPagoBusqueda=$dat7[1];
                    ?>
                            <option value="<?php echo $codTipoPagoBusqueda;?>"><?php echo $nombreTipoPagoBusqueda;?></option>
                    <?php
                        }
                    ?>
                    </select>
                
                </td>
            </tr>
			<tr>			
			<td>
				<input type='button' class='boton' value='Buscar' onClick="listaVentas()">
				
			</td>
						
			</tr>			
		</table>
		<div id="divListaMateriales">
		</div>
	
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
