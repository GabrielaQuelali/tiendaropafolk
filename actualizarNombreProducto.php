<?php
require("conexionmysqli2.inc");
require("estilos.inc");
require("funciones.php");

error_reporting(E_ALL);
 ini_set('display_errors', '1');

//and codigo_material=1  

$sql3="select codigo_material from material_apoyo order by codigo_material asc";
	$resp3=mysqli_query($enlaceCon,$sql3);

	while($dat3=mysqli_fetch_array($resp3)){
		$codigo_material=$dat3[0];
	
		actualizaNombreProducto($enlaceCon,$codigo_material);
}
		

?>