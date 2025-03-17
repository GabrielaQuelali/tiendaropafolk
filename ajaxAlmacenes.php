<?php
require("conexionmysqli.php");
$codTerritorio=$_GET['codTerritorio'];

$sql="select cod_almacen, nombre_almacen from almacenes where cod_ciudad='$codTerritorio'";
$resp=mysqli_query($enlaceCon,$sql);

echo "<select name='rpt_almacen' class='texto' id='rpt_almacen' required>";
while($dat=mysqli_fetch_array($resp)){
	$codigo=$dat[0];
	$nombre=$dat[1];
	echo "<option value='$codigo'>$nombre</option>";
}
echo "</select>";

?>
