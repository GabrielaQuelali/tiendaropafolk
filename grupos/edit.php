<?php

require("../conexionmysqli.php");
require("../estilos2.inc");
require("configModule.php");

$tipo=$_GET['tipo'];
$codigo_registro=$_GET['codigo_registro'];

$sql=mysqli_query($enlaceCon,"select nombre, abreviatura,cod_tipo from $table where codigo=$codigo_registro");
$dat=mysqli_fetch_array($sql);

$nombreX=$dat[0];
$abreviaturaX=$dat[1];


echo "<form action='$urlSaveEdit' method='post'>";
echo "<input type='hidden' name='tipo' id='tipo' value='".$tipo."'>";
echo "<h1>Editar $moduleNameSingular</h1>";

echo "<center>
<table class='texto'>";

echo "<tr><th align='left'>Nombre</th>";
echo "<input type='hidden' name='codigo' value='$codigo_registro'>";
echo "<td align='left'><input type='text' class='texto' name='nombre' value='$nombreX' size='30' onKeyUp='javascript:this.value=this.value.toUpperCase();' required></td></tr>";

echo "<tr><th align='left'>Abreviatura</th>";
echo "<td align='left'><input type='text' class='texto' name='abreviatura' value='$abreviaturaX' size='20' required></td></tr>";

echo "</table>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
<input type='button' class='boton2' value='Cancelar' onClick='location.href=\"$urlList2?tipo=".$tipo."\"'>

</div>";

echo "</form>";
?>