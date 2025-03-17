<html>
    <head>
        <title>Liquidacion Ingresos</title>
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

function ajaxBuscar(f){
	var fechaIniBusqueda, fechaFinBusqueda, nroCorrelativoBusqueda, global_almacen, itemBusqueda;
	fechaIniBusqueda=document.getElementById("fechaIniBusqueda").value;
	fechaFinBusqueda=document.getElementById("fechaFinBusqueda").value;
	nroCorrelativoBusqueda=document.getElementById("nroCorrelativoBusqueda").value;
	global_almacen=document.getElementById("global_almacen").value;
	itemBusqueda=document.getElementById("itemBusqueda").value;
	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxBuscarIngresos.php?fechaIniBusqueda="+fechaIniBusqueda+"&fechaFinBusqueda="+fechaFinBusqueda+"&nroCorrelativoBusqueda="+nroCorrelativoBusqueda+"&global_almacen="+global_almacen+"&itemBusqueda="+itemBusqueda,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null)
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
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Introdusca el codigo de confirmacion.</div>",function(){},function(){});
            }
        },function(){});
    });
}

function liquidarIngreso(f)
{   var i;
    var j=0;
    var j_cod_registro;
    var fecha_registro;
    for(i=0;i<=f.length-1;i++)
    {   if(f.elements[i].type=='checkbox')
        {   if(f.elements[i].checked==true)
            {   j_cod_registro=f.elements[i].value;
                fecha_registro=f.elements[i-1].value;
                j=j+1;
            }
        }
    }
    if(j>1)
    {   alert('Debe seleccionar solamente un registro para liquidarlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para liquidarlo.');
        }
        else
        {   funOk(j_cod_registro,function(){
				location.href='liquidarIngreso.php?codigo_registro='+j_cod_registro;          
			});
        }
    }
}
        </script>
    </head>
    <body>
<?php

require("conexion.inc");
require('function_formatofecha.php');

$txtnroingreso = $_GET["txtnroingreso"];
$fecha1 = $_GET["fecha1"];
$fecha2 = $_GET["fecha2"];

$txtnroingreso = str_replace("'", "''", $txtnroingreso);
$fecha1 = str_replace("'", "''", $fecha1);
$fecha2 = str_replace("'", "''", $fecha2);

    require("estilos_almacenes.inc");

echo "<form method='post' action='navegador_ingresomateriales.php'>";
echo "<input type='hidden' name='fecha_sistema' value='$fecha_sistema'>";

$consulta = "
    SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.estado_liquidacion
    FROM ingreso_almacenes i, tipos_ingreso ti
    WHERE i.cod_tipoingreso=ti.cod_tipoingreso and i.ingreso_anulado=0
    AND i.cod_almacen='$global_almacen' and i.cod_tipoingreso in (1000,1002) ";
	
if($txtnroingreso!="")
   {$consulta = $consulta."AND i.nro_correlativo='$txtnroingreso' ";
   }
if($fecha1!="" && $fecha2!="")
   {$consulta = $consulta."AND '$fecha1'<=i.fecha AND i.fecha<='$fecha2' ";
   }

   $consulta = $consulta."ORDER BY i.nro_correlativo DESC limit 0, 150 ";
//echo "MAT:$sql";
$resp = mysql_query($consulta);

echo "<h1>Liquidacion de Ingresos</h1>";
echo "<table border='1' cellspacing='0' class='textomini'><tr><th>Leyenda:</th><th>Ingresos Liquidados</th><td bgcolor='#ffff99' width='10%'></td><th>Ingresos No Liquidados</th><td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";
require('home_almacen.php');

echo "<div class='divBotones'>
<input type='button' value='Liquidar Ingreso' name='adicionar' class='boton' onclick='liquidarIngreso(this.form)'></td>
<input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'>
</div>";

echo "<div id='divCuerpo'>";

echo "<br><center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Numero Ingreso</th><th>Nota de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th><th>&nbsp;</th></tr>";
while ($dat = mysql_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_ingreso = $dat[1];
    $fecha_ingreso_mostrar = "$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
    $hora_ingreso = $dat[2];
    $nombre_tipoingreso = $dat[3];
    $obs_ingreso = $dat[4];
    $nota_entrega = $dat[5];
    $nro_correlativo = $dat[6];
    $estadoLiquidacion = $dat[7];
    echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
    $sql_verifica_movimiento = "select s.cod_salida_almacenes from salida_almacenes s, salida_detalle_ingreso sdi
                where s.cod_salida_almacenes=sdi.cod_salida_almacen and s.salida_anulada=0 and sdi.cod_ingreso_almacen='$codigo'";
    $resp_verifica_movimiento = mysql_query($sql_verifica_movimiento);
    $num_filas_movimiento = mysql_num_rows($resp_verifica_movimiento);
   
    if ($estadoLiquidacion == 1) {
        $color_fondo = "#ffff99";
        $chkbox = "";
    }
    if ($estadoLiquidacion == 0) {
        $color_fondo = "";
        $chkbox = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
    
	echo "<tr><td align='center'>$chkbox</td><td align='center'>$nro_correlativo</td><td align='center'>&nbsp;$nota_entrega</td>
	<td align='center'>$fecha_ingreso_mostrar $hora_ingreso</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td align='center' bgcolor='$color_fondo'>
	<a target='_BLANK' href='navegador_detalleingresomateriales.php?codigo_ingreso=$codigo'><img src='imagenes/icon_detail.png' width='30' border='0' title='Ver Detalles del Ingreso'></a></td></tr>";
}
echo "</table></center><br>";
echo "</div >";


echo "<div class='divBotones'>
<input type='button' value='Liquidar Ingreso' name='adicionar' class='boton' onclick='liquidarIngreso(this.form)'>
<input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'>
</div>";

echo "</form>";
?>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>
<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Buscar Ingresos</h2>
		<table align='center' class='texto'>
			<tr>
				<td>Fecha Ini(dd/mm/aaaa)</td>
				<td>
				<input type='date' name='fechaIniBusqueda' id="fechaIniBusqueda" class='texto'>
				</td>
			</tr>
			<tr>
				<td>Fecha Fin(dd/mm/aaaa)</td>
				<td>
				<input type='date' name='fechaFinBusqueda' id="fechaFinBusqueda" class='texto'>
				</td>
			</tr>
			<tr>
				<td>Nro. de Ingreso</td>
				<td>
				<input type='text' name='nroCorrelativoBusqueda' id="nroCorrelativoBusqueda" class='texto'>
				</td>
			</tr>			
			<tr>
				<td>Item:</td>
				<td>
					<select name="itemBusqueda" class="texto" id="itemBusqueda">
						<option value="">-</option>
					<?php
						$sqlMat="select m.codigo_material, m.descripcion_material, (select g.nombre_grupo from grupos g where g.cod_grupo=m.cod_grupo)as grupo from material_apoyo m where m.estado=1";
						$respMat=mysql_query($sqlMat);
						while($datMat=mysql_fetch_array($respMat)){
							$codMaterialBusqueda=$datMat[0];
							$nombreMaterialBusqueda=$datMat[1];
							$grupo=$datMat[2];
					?>
							<option value="<?php echo $codMaterialBusqueda;?>"><?php echo $nombreMaterialBusqueda;?></option>
					<?php
						}
					?>
					</select>
				
				</td>
			</tr>			

		</table>	
		<center>
			<input type='button' class="boton" value='Buscar' onClick="ajaxBuscar(this.form)">
			<input type='button' class="boton" value='Cancelar' onClick="HiddenBuscar()">
			
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
