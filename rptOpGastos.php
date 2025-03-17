<?php

require("conexionmysqli.inc");
require("estilos_almacenes.inc");

$fecha_inirptdefault=date("Y-m-01");
$fecha_rptdefault=date("Y-m-d");

$globalCiudad=$_COOKIE['global_agencia'];

echo "<h1>Reporte Detallado de Gastos</h1>";

echo"<form method='post' action='rptGastos.php' target='_blank'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	
	echo "<tr><th align='left'>Territorio</th>
	<td><select name='rpt_territorio[]' id='rpt_territorio[]' class='selectpicker' data-style='btn btn-success' multiple>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";			
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Fecha Inicio:</th>";
			echo" <TD bgcolor='#ffffff'>
				<INPUT  type='date' class='texto' value='$fecha_inirptdefault' id='fecha_ini' size='10' name='fecha_ini' required>";
    		echo"  </TD>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha Final:</th>";
			echo" <TD bgcolor='#ffffff'>
				<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='fecha_fin' size='10' name='fecha_fin' required>";
    		echo"  </TD>";
	echo "</tr>";

	echo"\n </table><br>";
	echo "<center><input type='submit' name='reporte' value='Ver Reporte' class='boton'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>