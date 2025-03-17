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
<?php
require("conexionmysqli2.inc");
require('estilos.inc');

$estado=$_GET['estado'];

echo "<form  action='guarda_procesoconstruccion.php' method='post' name='form1'>";

echo "<h2>Adicionar Proceso</h2>";

echo "<center><table class='texto'>";


echo "<tr><th align='left'>Nombre Proceso</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='nombre_proceso_const' id='nombre_proceso_const' size='40' style='text-transform:uppercase;' >
	</td> 
	</tr>";


echo "<tr><th align='left'>Descripcion</th>";
echo "<td align='left' >
	<input type='text' class='texto' name='descripcion_proceso_const' id='descripcion_proceso_const' size='120' style='text-transform:uppercase;'>
	</td>";
echo "<tr>"; ?>
<?php
echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"navegador_procesosConstruccion.php?estado=".$estado."\"'>
</div>";
echo "</form>";
?>

