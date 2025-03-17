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
         <script type="text/javascript" src="functionsGeneral.js"></script>

        <script language='javascript'>
		function envia_formulario(f)
		{	var rpt_territorio, rpt_almacen,tipo_ingreso,fecha_ini, fecha_fin, tipo_item, rpt_item;
			rpt_territorio=f.rpt_territorio.value;
			rpt_almacen=f.rpt_almacen.value;
			fecha_ini=f.exafinicial.value;
			fecha_fin=f.exaffinal.value;
			rpt_item=f.rpt_item.value;
			
			var forms = f;
			if(forms.checkValidity()){										
			window.open('rpt_inv_kardex.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&fecha_ini='+fecha_ini+'&fecha_fin='+fecha_fin+'&rpt_item='+rpt_item+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
				return(true);    
			} else{
				alert('Debe seleccionar todos los campos del reporte.');
			}	
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

		function ajaxReporteItems(f){
			var contenedor;
			contenedor=document.getElementById('divItemReporte');
			ajax=nuevoAjax();
			var codGrupo=(f.rpt_grupo.value);
			ajax.open('GET', 'ajaxReporteItems.php?codGrupo='+codGrupo,true);
			ajax.onreadystatechange=function() {
				if (ajax.readyState==4) {
					contenedor.innerHTML = ajax.responseText
				}
			}
			ajax.send(null);
		}
		
		function envia_select(form){
			form.submit();
			return(true);
		}
		</script>
       </head><body>
	   <?php


$fecha_rptdefault=date("d/m/Y");
echo "<table align='center' class='textotit'><tr><th>Reporte Kardex de Existencia Fisica</th></tr></table><br>";
echo"<form method='post' action='rpt_op_inv_kardex.php'>";
	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)' required>";
	
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($rpt_territorio==$codigo_ciudad)
		{	echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
		}
		else
		{	echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
		}
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Almacen</th><td><select name='rpt_almacen' class='texto' required>";
	$sql="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$rpt_territorio'";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_almacen=$dat[0];
		$nombre_almacen=$dat[1];
		if($rpt_almacen==$codigo_almacen)
		{	echo "<option value='$codigo_almacen' selected>$nombre_almacen</option>";
		}
		else
		{	echo "<option value='$codigo_almacen'>$nombre_almacen</option>";
		}
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Grupo</th><td><select name='rpt_grupo' class='texto' size='5' onChange='ajaxReporteItems(this.form);' required>
	<option value='-1'>TODOS</option>";
	$sql="select codigo, nombre from grupos where estado=1 order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo'>$nombre</option>";
	}
	echo "</select></td></tr>";
	echo "</tr>";
	
	echo "<tr><th align='left'>Material</th><td>
	<div id='divItemReporte'>
	<select name='rpt_item' class='texto' required>";
	
	$sql_item="select codigo_material, descripcion_material, codigo_barras from material_apoyo where codigo_material<>0 order by descripcion_material";
	
	$resp=mysqli_query($enlaceCon,$sql_item);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_item=$dat[0];
		$nombre_item=$dat[1];
		$barCode=$dat[2];
		?><option value='<?=$codigo_item?>'><?=$barCode?>-<?=$nombre_item?></option><?php
	}
	echo "</select></td>
	</div>
	</tr>";
     ?>
   <tr>
   	<th align='left'>Codigo de Barras</th>
   	   <td>
        <div class="codigo-barras-sm">
               <input type="text" class="form-codigo-barras-sm" id="input_codigo_barras" placeholder="Ingrese el código de barras." autofocus autocomplete="off">
         </div>
       </td>
    </tr>
     <?php
	
	$fechaIniDefault=fechaInicioSistema($enlaceCon);
	$fechaFinalDefault=date("Y-m-d");
	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <td bgcolor='#ffffff'>
			<input  type='date' class='texto' value='$fechaIniDefault' id='exafinicial' size='10' name='exafinicial' min='$fechaIniDefault' required>";
    		echo" </td>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <td bgcolor='#ffffff'>
			<input  type='date' class='texto' value='$fechaFinalDefault' id='exaffinal' size='10' name='exaffinal' min='$fechaIniDefault' required>";
    		echo" </td>";
	echo "</tr>";
	
	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "";

?>
</body>
</html>