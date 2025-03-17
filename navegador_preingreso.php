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
{  //alert("editar ingreso funOk");
	$.get("programas/ingresos/frmConfirmarCodigoPreIngreso.php","codigo="+codReg, function(inf1) {
        dlgAC("#pnldlgAC","Codigo de confirmacion",inf1,function(){
            var cad1=$("input#idtxtcodigo").val();
            var cad2=$("input#idtxtclave").val();
            if(cad1!="" && cad2!="") {
                dlgEsp.setVisible(true);
                $.get("programas/ingresos/validacionCodigoConfirmar.php","codigo="+cad1+"&clave="+cad2, function(inf2) {
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

function funModif(codReg,funOkConfirm)
{   $.get("programas/ingresos/frmModificarPreIngreso.php","codigo="+codReg, function(inf1) {
        dlgAC("#pnldlgAC","Modificar Proveedor",inf1,function(){
            var cad1=$("select[id=combotipoingreso]").val();
            var cad2=$("select[id=comboproveedor]").val();
            if(cad1!="" && cad2!="") {
                dlgEsp.setVisible(true);
				//alert ("combotipoingreso="+cad1+"&comboproveedor="+cad2+"&codigo="+codReg);
                $.get("programas/ingresos/guardarModifTipoProvePreIngreso.php","combotipoingreso="+cad1+"&comboproveedor="+cad2+"&codigo="+codReg, function(inf2) {
                    dlgEsp.setVisible(false);
                    funOkConfirm();
                });
            } else {
                dlgA("#pnldlgA3","Informe","<div class='pnlalertar'>Debe seleccionar datos.</div>",function(){},function(){});
            }
        },function(){});
    });
}


function ajaxBuscarPreIngresos(f){
	//alert ("Ingreso Busqueda");
	var fechaIniBusqueda, fechaFinBusqueda, notaIngreso, verBusqueda, global_almacen, provBusqueda;
	fechaIniBusqueda=document.getElementById("fechaIniBusqueda").value;
	//alert ("1");
	fechaFinBusqueda=document.getElementById("fechaFinBusqueda").value;

	//alert ("3");
	//alert (document.getElementById("global_almacen").value);
	global_almacen=document.getElementById("global_almacen").value;
	//alert ("4");
	provBusqueda=document.getElementById("provBusqueda").value;
	//alert ("5");
	var contenedor;
	contenedor = document.getElementById('divCuerpo');
	ajax=nuevoAjax();

	ajax.open("GET", "ajaxNavPreIngresos.php?fechaIniBusqueda="+fechaIniBusqueda+"&fechaFinBusqueda="+fechaFinBusqueda+"&global_almacen="+global_almacen+"&provBusqueda="+provBusqueda,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText;
			HiddenBuscar();
		}
	}
	ajax.send(null)
}



function enviar_nav(){   
	location.href='registrar_preingreso.php';
}

function editarPreIngresoTipoProv(codigoIngreso)
{   funModif(codigoIngreso,function(){
		alert("Se modicaron los Datos!");
		location.href='navegador_preingreso.php';
	});
}

function ingresarProductosAlmacen(codigo,corre){
	
if (confirm("Esta Seguro de Generar el Ingreso del Preingreso Nro. " +corre) == true) {

	location.href='guardaIngresoPreingreso.php?codigoPreingreso='+codigo+'';
}
}

function editar_preingreso(f)
{  
	//alert("editar ingreso");
	var i;
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
    {   alert('Debe seleccionar solamente un registro para anularlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para anularlo.');
        }
        else
        {      //location.href='editar_ingresomateriales.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=1&valor_inicial=1';
                funOk(j_cod_registro,function(){
					//alert("editar ingreso2");
                    location.href='editar_preingreso.php?codIngreso='+j_cod_registro+'';
                });
        }
    }
}
function anular_preingreso(f)
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
    {   alert('Debe seleccionar solamente un registro para anularlo.');
    }
    else
    {   if(j==0)
        {   alert('Debe seleccionar un registro para anularlo.');
        }
        else
        {   //window.open('anular_ingreso.php?codigo_registro='+j_cod_registro+'&grupo_ingreso=2','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=280,height=150');
                funOk(j_cod_registro,function(){
                    location.href='anular_preingreso.php?codigo_registro='+j_cod_registro+'';
                });
        }
    }
}
        </script>
    </head>
    <body>

<?php
 $global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];

echo "<form method='post' action='navegador_preingreso.php'>";
echo "<input type='hidden' name='fecha_sistema' id='fecha_sistema' value='$fecha_sistema'>";
echo "<input type='hidden' name='global_almacen' id='global_almacen' value='$global_almacen'>";
$sqlFuncProv="select * from funcionarios_proveedores where codigo_funcionario=$global_usuario";
$respFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);
$cantFuncProv=mysqli_num_rows($respFuncProv);
$consulta = "
    SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado,
	(select p.nombre_proveedor from proveedores p where p.cod_proveedor=i.cod_proveedor) as proveedor, i.nro_factura_proveedor,
	i.created_by,i.created_date, i.modified_by, i.modified_date
    FROM preingreso_almacenes i, tipos_ingreso ti
    WHERE i.cod_tipoingreso=ti.cod_tipoingreso";
	if($globalTipoFuncionario==2){
	if($cantFuncProv>0){
		$consulta= $consulta." and i.cod_proveedor in( select cod_proveedor from funcionarios_proveedores where codigo_funcionario=$global_usuario)";
	}
	}
    $consulta = $consulta." AND i.cod_almacen='$global_almacen'";
   $consulta = $consulta."ORDER BY i.nro_correlativo DESC limit 0, 50 ";
   
//echo "MAT:$sql";
$resp = mysqli_query($enlaceCon,$consulta);
echo "<h1>Pre-Ingreso de Productos</h1>";

echo "<br/><table border='1' cellspacing='0' class='textomini'>
<tr><th>Leyenda:</th><th>PREINGRESOS ANULADOS</th><td bgcolor='#ff8080' width='10%'></td><th>PREINGRESOS SIN ANULAR</th><td bgcolor='' width='10%'>&nbsp;</td></tr></table><br>";

echo "<div class='divBotones'><input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav()'>
<input type='button' value='Editar' class='boton' onclick='editar_preingreso(this.form)'>
<input type='button' value='Anular' name='anular' class='boton2' onclick='anular_preingreso(this.form)'>
<td><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div><br>";

echo "<div id='divCuerpo'>";
echo "<br><center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Nro. Pre Ingreso</th><th>Nro.Factura</th><th>Fecha</th><th>Tipo de Ingreso</th>
<th>Proveedor</th>
<th>Observaciones</th>
<th>Registro</th>
<th>Ult. Edicion</th>
<th>&nbsp;</th><th>&nbsp;</th><th>Nro Ingreso</th></tr>";
while ($dat = mysqli_fetch_array($resp)) {
    $codigo = $dat[0];
    $fecha_ingreso = $dat[1];
    $fecha_ingreso_mostrar = "$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
    $hora_ingreso = $dat[2];
    $nombre_tipoingreso = $dat[3];
    $obs_ingreso = $dat[4];
    $nota_entrega = $dat[5];
    $nro_correlativo = $dat[6];
    $anulado = $dat[7];
	$proveedor=$dat[8];
	$nroFacturaProveedor=$dat[9];
	
	$created_by=$dat[10];
	$sqlRegUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$created_by;
	$respRegUsu=mysqli_query($enlaceCon,$sqlRegUsu);
	$usuReg =" ";
	while($datRegUsu=mysqli_fetch_array($respRegUsu)){
		$usuReg =$datRegUsu['nombres'][0].$datRegUsu['paterno'];		
	}
	$created_date=$dat[11];
	$modified_by=$dat[12];
	$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
	$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
	$usuMod ="";
	while($datModUsu=mysqli_fetch_array($respModUsu)){
		$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
	}
	$modified_date=$dat[13];
	

    echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";

    if ($anulado == 1) {
        $color_fondo = "#ff8080";
        $chkbox = "";
    }
    if (  $anulado == 0) {
        $color_fondo = "";
        $chkbox = "<input type='checkbox' name='codigo' value='$codigo'>";
    }
	$sqlAux=" select IFNULL(codigo_ingreso,0) from  preingreso_almacenes  where cod_ingreso_almacen=$codigo";
	$respAux= mysqli_query($enlaceCon,$sqlAux);
	$datAux=mysqli_fetch_array($respAux);
	if($datAux[0]<>0){
		$chkbox = "";
	}
		
    echo "<tr bgcolor='$color_fondo'><td align='center'>$chkbox</td><td align='center'>$nro_correlativo</td><td align='center'>$nroFacturaProveedor</td>
	<td align='center'>$fecha_ingreso_mostrar $hora_ingreso</td><td>$nombre_tipoingreso</td>
	<td>&nbsp;$proveedor</td>
	<td>&nbsp;$obs_ingreso</td>
	<td>&nbsp;$usuReg<br>$created_date</td>";
	if(empty($usuMod)){
	echo "<td>&nbsp</td>";
	}else{
		echo "<td>&nbsp;$usuMod<br>$modified_date</td>";
    }				
	 
	 echo "<td align='center'><a target='_BLANK' href='navegador_predetalleingresomateriales.php?codigo_ingreso=$codigo'>
	<img src='imagenes/detalles.png' border='0' width='30' heigth='30' title='Ver Detalles del Ingreso'></a>
	</td>";
	if($anulado==0){
	echo "<td align='center'>
		<a href='#' onclick='javascript:editarPreIngresoTipoProv($codigo)' > 
		<img src='imagenes/edit.png' border='0' width='30' heigth='30' title='Editar Tipo & Proveedor'>
		</a>
	</td>";
	}else{
		echo "<td align='center'>

	</td>";
	}	

	if($datAux[0]==0){
		if($globalTipoFuncionario==1){
			if($anulado==0){
				echo "<td align='center'>
				<a href='#' onclick='javascript:ingresarProductosAlmacen($codigo,$nro_correlativo);' > 
				<img src='imagenes/ingreso2.png' border='0' width='40' heigth='40' title='Ingresar Productos a Almacen'>
				</a>
				</td>";
			}else{ 
				echo "<td align='center'></td>";
			}
		}else{ 
			echo "<td align='center'></td>";
		} 	
	}else{ 
		$sqlAux2=" select i.nro_correlativo,i.cod_almacen , a.nombre_almacen
		from  ingreso_almacenes i inner join almacenes a on( i.cod_almacen =a.cod_almacen )
		where i.cod_ingreso_almacen=".$datAux[0];
		//echo  $sqlAux2;
	$respAux2= mysqli_query($enlaceCon,$sqlAux2);
	$datAux2=mysqli_fetch_array($respAux2);
			echo "<td align='center'>$datAux2[0]<br/>$datAux2[2]</td>";
	} 
	echo "</tr>";
}
echo "</table></center><br>";
echo "</div>";

echo "
<div class='divBotones'>
<input type='button' value='Registrar' name='adicionar' class='boton' onclick='enviar_nav()'>
<input type='button' value='Editar' class='boton' onclick='editar_preingreso(this.form)'>
<input type='button' value='Anular' name='adicionar' class='boton2' onclick='anular_preingreso(this.form)'>
<td><input type='button' value='Buscar' class='boton' onclick='ShowBuscar()'></div>";
echo "</form>";
?>

<div id="divRecuadroExt" style="background-color:#666; position:absolute; width:800px; height: 400px; top:30px; left:150px; visibility: hidden; opacity: .70; -moz-opacity: .70; filter:alpha(opacity=70); -webkit-border-radius: 20px; -moz-border-radius: 20px; z-index:2;">
</div>

<div id="divProfileData" style="background-color:#FFF; width:750px; height:350px; position:absolute; top:50px; left:170px; -webkit-border-radius: 20px; 	-moz-border-radius: 20px; visibility: hidden; z-index:2;">
  	<div id="divProfileDetail" style="visibility:hidden; text-align:center">
		<h2 align='center' class='texto'>Buscar Pre Ingresos</h2>
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
				<td>Proveedor:</td>
				<td>
					<select name="ProvBusqueda" class="texto" id="provBusqueda">
						<option value="0">Todos</option>
					<?php
						$sqlProv="select cod_proveedor, nombre_proveedor from proveedores ";
						if($globalTipoFuncionario==2){
							if($cantFuncProv>0){
								$sqlProv=$sqlProv." where cod_proveedor in
								( select cod_proveedor from funcionarios_proveedores where codigo_funcionario=$global_usuario) ";
							}
						}
						$sqlProv=$sqlProv." order by 2";
						$respProv=mysqli_query($enlaceCon,$sqlProv);
						while($datProv=mysqli_fetch_array($respProv)){
							$codProvBus=$datProv[0];
							$nombreProvBus=$datProv[1];
					?>
							<option value="<?php echo $codProvBus;?>"><?php echo $nombreProvBus;?></option>
					<?php
						}
					?>
					</select>
				
				</td>
			</tr>			
		</table>	
		<center><br>
			<input type='button' value='Buscar' class='boton' onClick="ajaxBuscarPreIngresos(this.form)">
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
