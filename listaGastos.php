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

function HiddenBuscar(){
	document.getElementById('divRecuadroExt').style.visibility='hidden';
	document.getElementById('divProfileData').style.visibility='hidden';
	document.getElementById('divProfileDetail').style.visibility='hidden';
}
		
function funOk(codReg,funOkConfirm)
{   
	$.get("programas/gastos/frmConfirmarCodigoGasto.php","codigo="+codReg, function(inf1) {
        dlgAC("#pnldlgAC","Codigo de confirmacion",inf1,function(){
            var cad1=$("input#idtxtcodigo").val();
            var cad2=$("input#idtxtclave").val();
            if(cad1!="" && cad2!="") {
                dlgEsp.setVisible(true);
                $.get("programas/gastos/validacionCodigoConfirmar.php","codigo="+cad1+"&clave="+cad2, function(inf2) {
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



function ajaxBuscarGastos(f){
	
	var fechaIniBusqueda, fechaFinBusqueda, tipoGasto, grupoGasto,tipoPago, proveedor, detalle, global_almacen;
	
	fechaIniBusqueda=document.getElementById("fechaIniBusqueda").value;
	
	fechaFinBusqueda=document.getElementById("fechaFinBusqueda").value;
	
	tipoGasto=document.getElementById("tipoGasto").value;
	grupoGasto=document.getElementById("grupoGasto").value;
	tipoPago=document.getElementById("tipoPago").value;
	proveedor=document.getElementById("proveedor").value;
	
	detalle=document.getElementById("detalle").value;

	
	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxBuscarGastos.php?fechaIniBusqueda="+fechaIniBusqueda+"&fechaFinBusqueda="+fechaFinBusqueda+"&tipoGasto="+tipoGasto+"&grupoGasto="+grupoGasto+"&tipoPago="+tipoPago+"&proveedor="+proveedor+"&detalle="+detalle,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null)
}



function registrar_gasto()
{   location.href='registrar_gasto.php';
}



function editar_gasto(f)
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
                    location.href='editar_gasto.php?idGasto='+j_cod_registro+'';
                });
        }
    }
}
function anular_gasto(f)
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
                    location.href='anular_gasto.php?idGasto='+j_cod_registro+'';
                });
        }
    }
}
        </script>
    </head>
    <body>
<form method='post' action='listaGastos.php'>

<?php

$global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$global_agencia=$_COOKIE['global_agencia'];

?>
<h1>Gastos</h1>

<table border='1' cellspacing='0' class='textomini'>
<tr><th>LEYENDA:</th><th>Gastos Anulados</th><td bgcolor='#ff8080' width='10%'>>&nbsp;</td>

<th>Gastos Registrados</th><td bgcolor='' width='10%'>&nbsp;</td></tr>
</table><br>

<div class='divBotones'>
<input type='button' value='Registrar' name='adicionar' class='boton' onclick='registrar_gasto()'>
<input type='button' value='Editar' class='boton' onclick='editar_gasto(this.form)'>
<input type='button' value='Anular' name='adicionar' class='boton2' onclick='anular_gasto(this.form)'>
<td><input type='button' value='Buscar' class='boton-verde' onclick='ShowBuscar()'>
</div>

<br><div id='divCuerpo'>
<br><center>
<table class='texto'>
<tr>
<th>&nbsp;</th>
<th>Tipo</th>
<th>Nro Gasto</th>
<th>Fecha</th>
<th>Forma Pago</th>
<th>Monto</th>
<th>Grupo Gasto</th>
<th>Detalle</th>
<th>&nbsp;</th>
<th>Tipo<br/>Distribucion</th>
<th>Registrado Por</th>
<th>Modificado Por</th>
<th></th>
</tr>
<?php
$consulta="select g.cod_gasto,g.descripcion_gasto,g.cod_tipogasto,tg.nombre_tipogasto,g.fecha_gasto,g.monto,g.cod_ciudad,
g.created_by,g.modified_by,g.created_date,g.modified_date,g.gasto_anulado,g.cod_proveedor, p.nombre_proveedor,g.cod_grupogasto, gg.nombre_grupogasto,
g.cod_tipopago, tp.nombre_tipopago, g.cod_tipo_distribucion_costo,
tdc.desc_tipo_distribucion_costo
from gastos g
inner join tipos_gasto tg on (g.cod_tipogasto=tg.cod_tipogasto)
inner join grupos_gasto gg on (g.cod_grupogasto=gg.cod_grupogasto)
inner join tipos_pago tp on (g.cod_tipopago=tp.cod_tipopago)
left  join proveedores p on (g.cod_proveedor=p.cod_proveedor)
left  join tipos_distribucion_costo tdc on (g.cod_tipo_distribucion_costo=tdc.cod_tipo_distribucion_costo)
where g.cod_ciudad=".$global_agencia." order by g.fecha_gasto desc,g.cod_gasto desc";
//echo "consulta=".$consulta;
$resp = mysqli_query($enlaceCon,$consulta);
while ($dat = mysqli_fetch_array($resp)) {
	$cod_gasto= $dat['cod_gasto'];
	$descripcion_gasto= $dat['descripcion_gasto'];
	$cod_tipogasto= $dat['cod_tipogasto'];
	$nombre_tipogasto= $dat['nombre_tipogasto'];
	$fecha_gasto= $dat['fecha_gasto'];	
	$vector_fecha_gasto=explode("-",$fecha_gasto);
	$fecha_gasto_mostrar=$vector_fecha_gasto[2]."/".$vector_fecha_gasto[1]."/".$vector_fecha_gasto[0];
	$monto= $dat['monto'];
	$cod_ciudad= $dat['cod_ciudad'];
	$created_by= $dat['created_by'];
	$modified_by= $dat['modified_by'];
	$created_date= $dat['created_date'];
	$modified_date= $dat['modified_date'];
	$gasto_anulado= $dat['gasto_anulado'];
	$cod_proveedor= $dat['cod_proveedor'];
	$nombre_proveedor= $dat['nombre_proveedor'];
	$cod_grupogasto= $dat['cod_grupogasto'];
	$nombre_grupogasto= $dat['nombre_grupogasto'];
	$cod_tipopago= $dat['cod_tipopago'];
	$nombre_tipopago= $dat['nombre_tipopago'];
	$cod_tipo_distribucion_costo= $dat['cod_tipo_distribucion_costo'];
	$desc_tipo_distribucion_costo= $dat['desc_tipo_distribucion_costo'];

	$created_date_mostrar="";
	// formatoFechaHora
	if(!empty($created_date)){
		$vector_created_date = explode(" ",$created_date);
		$fechaReg=explode("-",$vector_created_date[0]);
		$created_date_mostrar = $fechaReg[2]."/".$fechaReg[1]."/".$fechaReg[0]." ".$vector_created_date[1];
	}
	// fin formatoFechaHora

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
	$usuMod ="";
	 if(!empty($modified_by)){
		$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
		$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
		$usuMod ="";
		while($datModUsu=mysqli_fetch_array($respModUsu)){
			$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
		}
	}
	////////////
	  $color_fondo = "";
	   //echo $gasto_anulado ;
	if ($gasto_anulado == 2) {
        $color_fondo = "#ff8080";
        
    }

?>	
   <tr style="background-color: <?=$color_fondo;?>;">
	<td><?php 
	if ($gasto_anulado == 1) {
   
	?>	
		<input type="checkbox" name="cod_gasto" id="cod_gasto" value="<?=$cod_gasto;?>">
	<?php 
	}
	?>	
	</td>
	<td><?=$nombre_tipogasto;?></td>
	<td><?=$cod_gasto;?></td>
	<td><?=$fecha_gasto_mostrar;?></td>
	<td><?=$nombre_tipopago;?></td>
	<td><?=$monto;?></td>
	<td><?=$nombre_grupogasto;?></td>
	<td><?=$descripcion_gasto;?></td>	
	<td>&nbsp;<?=$nombre_proveedor;?></td>
	<td><?=$desc_tipo_distribucion_costo;?></td>
	<td><?=$usuReg;?><br><?=$created_date_mostrar;?></td>
	<td><?=$usuMod;?><br><?=$modified_date_mostrar;?></td>	
	
	<td><a href="formatoGasto.php?idGasto=<?=$cod_gasto;?>" target="_BLANK">Ver Gasto</a></td>
	</tr>
<?php	
}


?>
</table></center><br>
</div>

<div class='divBotones'>
<input type='button' value='Registrar' name='adicionar' class='boton' onclick='registrar_gasto()'>
<input type='button' value='Editar' class='boton' onclick='editar_gasto(this.form)'>
<input type='button' value='Anular' name='adicionar' class='boton2' onclick='anular_gasto(this.form)'>
<td><input type='button' value='Buscar' class='boton-verde' onclick='ShowBuscar()'>
</div>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 500px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:450px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Buscar Gastos</h2>
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
				<td>Tipo Gasto</td>
				<td>
					<select name="tipoGasto" id="tipoGasto" class="texto"  >
					<option value="" >--</option>
					<?php	
						$sqlTipoGasto="select cod_tipogasto, nombre_tipogasto from tipos_gasto where estado=1  order by cod_tipogasto asc";
						$respTipoGasto=mysqli_query($enlaceCon,$sqlTipoGasto);
						while($datTipoGasto=mysqli_fetch_array($respTipoGasto)){	
					?>
					<?php	$codTipogasto=$datTipoGasto[0];
							$nombreTipogasto=$datTipoGasto[1];
					?>
					<option value="<?=$codTipogasto;?>" ><?=$nombreTipogasto;?></option>
		
				<?php	}?>
					</select>
			</td>
			</tr>		
						<tr>
				<td>Grupo Gasto</td>
				<td>
				<select name="grupoGasto" id="grupoGasto" class="texto"  >
				<option value="" >--</option>
				<?php	
					$sqlGrupoGasto="select cod_grupogasto, nombre_grupogasto from grupos_gasto where estado=1  order by cod_grupogasto asc";
					$respGrupoGasto=mysqli_query($enlaceCon,$sqlGrupoGasto);
					while($datGrupoGasto=mysqli_fetch_array($respGrupoGasto)){	
				?>
				<?php	$codGrupoGasto=$datGrupoGasto[0];
						$nombreGrupoGasto=$datGrupoGasto[1];
				?>
						<option value="<?=$codGrupoGasto;?>" ><?=$nombreGrupoGasto;?></option>
		
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
				<td>Forma Pago</td>
				<td>
					
					<select name="tipoPago" id="tipoPago" class="texto"  >
					<option value="" >--</option>
					<?php	
						$sqlTipoPago="select cod_tipopago, nombre_tipopago from tipos_pago where estado=1  order by cod_tipopago asc";
						$respTipoPago=mysqli_query($enlaceCon,$sqlTipoPago);
						while($datTipoPago=mysqli_fetch_array($respTipoPago)){	
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
				<td>Detalle</td>
				<td>
				<input type='text' name='detalle' id="detalle" class='texto'>
				</td>
			</tr>		
		</table>	
		<center><br>
			<input type='button' value='Buscar' class='boton' onClick="ajaxBuscarGastos(this.form)">
			<input type='button' value='Cancelar' class='boton2' onClick="HiddenBuscar();">
			
		</center>
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
