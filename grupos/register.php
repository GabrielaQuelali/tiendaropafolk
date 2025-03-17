<?php

require("../conexionmysqli.php");
require("../estilos2.inc");
require("configModule.php");

echo "<form action='save.php' method='post'>";

$tipo=$_GET['tipo'];

echo "<input type='hidden' name='tipo' id='tipo' value='".$tipo."'>";
echo "<h1>Registrar $moduleNameSingular</h1>";

echo "<center><table class='texto' width='60%'>";

echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='nombre' id='nombre' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();' required>
</td>";
echo "<tr><th align='left'>Abreviatura</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='abreviatura' id='abreviatura' size='30' required>
</td></tr>";


echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"$urlList2?tipo=$tipo\"'>
";

echo "</form>";
?>