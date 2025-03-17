<?php
require("conexion.inc");
$codTerritorio=$_GET['codTerritorio'];

$sql="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$codTerritorio'";
$resp=mysql_query($sql);

echo "<select name='rpt_almacen' class='texto' id='rpt_almacen' required>";
while($dat=mysql_fetch_array($resp)){
	$codigo=$dat[0];
	$nombre=$dat[1];
	echo "<option value='$codigo'>$nombre</option>";
}
echo "</select>";

?>
