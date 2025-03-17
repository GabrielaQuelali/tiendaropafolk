<?php
require("conexionmysqlipdf.inc");

$fecha_producto=$_POST['fecha_ini'];

$sql="select m.codigo_material, m.descripcion_material
from material_apoyo m where estado=1 and m.fecha_creacion>='$fecha_producto' order by 2";
$resp=mysqli_query($enlaceCon,$sql);

echo "<select class='texto' name='rpt_productos[]' id='rpt_productos' size='10' multiple>";
while($dat=mysqli_fetch_array($resp)){
	$codigo=$dat[0];
	$nombre=$dat[1];
	echo "<option value='$codigo'>$nombre</option>";
}	
echo "</select>";

?>