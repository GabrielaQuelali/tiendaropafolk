<?php

require("../conexionmysqli.php");
require("../estilos2.inc");
require("configModule.php");
require_once("../funcion_nombres.php");

	$codMaestro=$_GET['codMaestro'];
	$tipo=$_GET['tipo'];
	$nameMaestro=obtenerNombreMaestro($enlaceCon,$table,$codMaestro);
	
echo "<form action='$urlSaveDet' method='post'>";
echo "<input type='hidden' name='tipo' id='tipo' value='".$tipo."'>";
echo "<input type='hidden' name='codMaestro' id='codMaestro' value='".$codMaestro."'>";

echo "<h1>Registrar $moduleDetNameSingular</h1>";
echo "<h1>$moduleNameSingular $nameMaestro</h1>";

echo "<center><table class='texto' width='60%'>";


echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='nombre' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();' required>
</td>";
echo "<tr><th align='left'>Abreviatura</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='abreviatura' size='30' required>
</td>";
echo "</tr>";
echo "</table></center>";




echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"listDetalle.php?codMaestro=".$codMaestro."&tipo=".$tipo."\"'>
";

echo "</form>";
?>