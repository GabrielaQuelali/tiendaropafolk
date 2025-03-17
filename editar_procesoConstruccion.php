<script>
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

</script>

<head>

</head>
<?php
require("conexionmysqli.inc");
require('estilos.inc');
require('funciones.php');

$codProcesoConst=$_GET['codigo'];
$estado=$_GET['estado'];
$globalAgencia=$_COOKIE['global_agencia'];

$sqlEdit="SELECT nombre_proceso_const,descripcion_proceso_const,cod_estado,created_by,
created_date
FROM procesos_construccion pc
 where cod_proceso_const='".$codProcesoConst."'";

$respEdit=mysqli_query($enlaceCon,$sqlEdit);
while($datEdit=mysqli_fetch_array($respEdit)){

	$nombre_proceso_constX=$datEdit['nombre_proceso_const'];
	$descripcion_proceso_constX=$datEdit['descripcion_proceso_const'];
	$cod_estadoX=$datEdit['cod_estado'];
	$created_byX=$datEdit['created_by'];



}


echo "<form action='guarda_editarProcesoConstruccion.php' method='post' name='form1'>";

echo "<h1>Editar Proceso</h1>";


echo "<input type='hidden' name='codProcesoConst' id='codProcesoConst' value='".$codProcesoConst."'>";
echo "<input type='hidden' name='estado' id='estado' value='".$estado."'>";

echo "<center><table class='texto'>";

echo "<tr>";
echo "<th align='left'>Proceso</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='nombre_proceso_const' id='nombre_proceso_const' value='$nombre_proceso_constX' size='40' style='text-transform:uppercase;' >
	</td> ";
echo "</tr>";

echo "<tr><th align='left'>Descripcion</th>";
echo "<td align='left' >
	<input type='text' class='texto' name='descripcion_proceso_const' id='descripcion_proceso_const' value='$descripcion_proceso_constX' size='100' style='text-transform:uppercase;'>
	</td>";
echo "</tr>";


echo "</table></center>";
echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_procesosConstruccion.php?estado=".$estado."\"'>
</div>";
echo "</form>";
?>
