<?php

require("../conexionmysqli.php");
require("../estilos2.inc");
require("configModule.php");
require_once("../funcion_nombres.php");

	$codMaestro=$_GET['codMaestro'];
	$codigo_registro=$_GET['codigo_registro'];
	$tipo=$_GET['tipo'];
	$nameMaestro=obtenerNombreMaestro($enlaceCon,$table,$codMaestro);
	
$sql=mysqli_query($enlaceCon,"select nombre, abreviatura from $tableDetalle where codigo=$codigo_registro");
$dat=mysqli_fetch_array($sql);

$nombre=$dat[0];
$abreviatura=$dat[1];

	
echo "<form action='$urlSaveEditDet' method='post'>";

echo "<h1>Editar $moduleDetNameSingular</h1>";
echo "<h1>$moduleNameSingular $nameMaestro</h1>";

echo "<center><table class='texto' width='60%'>";

echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<input type='hidden' name='tipo' id='tipo' value='$tipo'>";
echo "<input type='hidden' name='codMaestro' id='codMaestro' value='$codMaestro'>";

echo "<tr><th align='left'>Nombre</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='nombre' size='40' onKeyUp='javascript:this.value=this.value.toUpperCase();' value='$nombre' required>
</td>";
echo "<tr><th align='left'>Abreviatura</th>";
echo "<td align='left'>
	<input type='text' class='texto' name='abreviatura' size='30' value='$abreviatura' required>
</td>";
echo "</tr>";
echo "</table></center>";




echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"listDetalle.php?tipo=".$tipo."&codMaestro=".$codMaestro."\"'>
";

echo "</form>";
?>