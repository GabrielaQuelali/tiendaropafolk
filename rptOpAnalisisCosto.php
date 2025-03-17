<script>
	function enviarFormDetallado(f, tiporeporte){
		if(tiporeporte==1){
			f.action='rptAnalisisCostos.php';
		}
		if(tiporeporte==2){
			f.action='rptAnalisisCostosAgrupado.php';
		}
		if(tiporeporte==3){
			f.action='rptAnalisisCostosAgrupadoMes.php';
		}
		if(tiporeporte==4){
			f.action='rptAnalisisCostosAgrupadoSimulacion.php';
		}
		f.submit();
	}
</script>
<?php
require('conexionmysqli.php');
require("estilos_almacenes.inc");


$fecha_rptdefault=date("Y-m-d");

echo "<h1>Analisis de Costos</h1><br>";

echo"<form method='post' action='' target='_blank'>";

	echo"\n<table class='texto' align='center' cellSpacing='0' width='50%'>\n";
	echo "<tr><th align='left'>Territorio</th>
		<td><select name='rpt_territorio[]' class='selectpicker' data-style='btn-success' data-live-search='true' multiple required>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysqli_query($enlaceCon, $sql);
	echo "<option value=''></option>";
	while($dat=mysqli_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Fecha inicio:</th>";
			echo" <td>
			<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='fecha_ini' size='10' name='fecha_ini' required>";
    		echo" </td>";
	echo "</tr>";
	echo "<tr><th align='left'>Fecha final:</th>";
			echo" <td>
			<INPUT  type='date' class='texto' value='$fecha_rptdefault' id='fecha_fin' size='10' name='fecha_fin' required>";
    		echo" </td>";
	echo "</tr>";
	
	echo "<tr><th align='left'>Ver</th>
		<td><select name='rpt_ver' id='rpt_ver' class='selectpicker' data-style='btn-success' data-live-search='true' required>";
	echo "<option value='0'>Ver Todo</option>";
	echo "<option value='1' selected>Omitir Productos y Gastos No Mensuales</option>";
	echo "</select></td></tr>";

	echo"\n </table><br>";

	echo "<center>
	<input type='button' name='reporte' value='Ver Reporte Detallado' onclick='enviarFormDetallado(this.form, 1);' class='boton'>
	<input type='button' name='reporte' value='Ver Reporte Agrupado' onclick='enviarFormDetallado(this.form, 2);' class='boton-azul'>
	<input type='button' name='reporte' value='Ver Reporte Agrupado x Mes' onclick='enviarFormDetallado(this.form, 3);' class='boton-rojo'>
	<input type='button' name='reporte' value='Ver Reporte Agrupado Simulación' onclick='enviarFormDetallado(this.form, 4);' class='boton-verde'>
	</center><br>";
	echo"</form>";
	echo "</div>";

?>