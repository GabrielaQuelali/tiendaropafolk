<script language='JavaScript'>
function envia_formulario(f)
{	var rpt_territorio, rpt_almacen, tipo_item, rpt_ver, rpt_fecha;
	rpt_territorio=f.rpt_territorio.value;
	rpt_almacen=f.rpt_almacen.value;
	rpt_ver=f.rpt_ver.value;
	rpt_fecha=f.rpt_fecha.value;
	var rpt_grupo=new Array();	
			
	var j=0;
	for(i=0;i<=f.rpt_grupo.options.length-1;i++)
	{	if(f.rpt_grupo.options[i].selected)
		{	rpt_grupo[j]=f.rpt_grupo.options[i].value;
			j++;
		}
	}	
	
	var forms = f;
    if(forms.checkValidity()){
		window.open('rptExistenciasCostos.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&rpt_ver='+rpt_ver+'&rpt_fecha='+rpt_fecha+'&rpt_grupo='+rpt_grupo,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
		return(true);    
	} else{
        alert("Debe seleccionar todos los campos del reporte.")
    }
	

}
function activa_tipomaterial(f){
	if(f.tipo_item.value==1)
	{	f.rpt_tipomaterial.disabled=true;
	}
	else
	{	f.rpt_tipomaterial.disabled=false;
	}
}
function envia_select(form){
	form.submit();
	return(true);
}
</script>
<?php

require("conexionmysqli.php");
require("estilos_almacenes.inc");

$fecha_rptdefault=date("d/m/Y");
echo "<h1>Reporte Existencias con Costo</h1>";

echo"<form method='post' action=''>";
	echo"<center><table class='texto'>";
	echo "<tr><th align='left'>Territorio</th><td>
	<select name='rpt_territorio' class='texto' onChange='envia_select(this.form)' required>";
	
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<option value=''>------</option>";
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

		echo "<tr><th align='left'>Grupo</th><td><select name='rpt_grupo' class='texto' size='10' multiple required>";
	$sql="select codigo, nombre from grupos where estado=1 order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";

	
	echo "<tr><th align='left'>Ver:</th>";
	echo "<td><select name='rpt_ver' class='texto' required>";
	echo "<option value='1'>Todo</option>";
	echo "<option value='2'>Con Existencia</option>";
	echo "<option value='3'>Sin existencia</option>";
	echo "</tr>";
	$fecha_rptdefault=date("d/m/Y");
	echo "<tr><th align='left'>Existencias a fecha:</th>";
			echo" <TD bgcolor='#ffffff'>
			<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='rpt_fecha' size='10' name='rpt_fecha' required>";
    		echo" </TD>";
	echo "</tr>";

	echo"\n </table><br>";
	require('home_almacen.php');
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>