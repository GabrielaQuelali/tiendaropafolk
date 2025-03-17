<?php
echo "<script language='JavaScript'>
		function envia_formulario(f)
		{	var rpt_territorio;
			rpt_territorio=f.rpt_territorio.value;
			var rpt_grupo=new Array();	
			var j=0;
			for(i=0;i<=f.rpt_grupo.options.length-1;i++)
			{	if(f.rpt_grupo.options[i].selected)
				{	rpt_grupo[j]=f.rpt_grupo.options[i].value;
					j++;
				}
			}			window.open('rptPrecios.php?rpt_territorio='+rpt_territorio+'&rpt_grupo='+rpt_grupo,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_select(form){
			form.submit();
			return(true);
		}
		</script>";
require("conexionmysqli.php");
require("estilos_almacenes.inc");
$fecha_rptdefault=date("d/m/Y");
echo "<table align='center' class='textotit'><tr><th>Reporte Precios</th></tr></table><br>";
echo"<form method='post' action=''>";
	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto'>";
	if($global_tipoalmacen==1)
	{	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	}
	else
	{	$sql="select cod_ciudad, descripcion from ciudades where cod_ciudad='$global_agencia' order by descripcion";
	}
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
	
	echo "<tr><th align='left'>Grupo</th><td><select name='rpt_grupo' class='texto' size='10' multiple>";
	$sql="select codigo, nombre from grupos where estado=1 order by 2";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		echo "<option value='$codigo' selected>$nombre</option>";
	}
	echo "</select></td></tr>";
	
	echo"\n </table><br>";

	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";
	echo"<script type='text/javascript' language='javascript'  src='dlcalendar.js'></script>";

?>