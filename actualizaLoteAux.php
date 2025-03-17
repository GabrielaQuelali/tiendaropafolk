<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");


 error_reporting(E_ALL);
 ini_set('display_errors', '1');
 


$sql = "select cod_ingreso_almacen,cod_lote from ingreso_almacenes where cod_lote>0
order by cod_lote asc";
$resp=mysqli_query($enlaceCon,$sql);
echo $sql;
while($dat=mysqli_fetch_array($resp)){
		$cod_ingreso_almacen=$dat['cod_ingreso_almacen'];
		$cod_lote=$dat['cod_lote'];

		$sql2="update ingreso_detalle_almacenes  set lote='".$cod_lote."'
		 where cod_ingreso_almacen=".$cod_ingreso_almacen;
		 echo $sql2."<br>";
		 $resp2=mysqli_query($enlaceCon,$sql2);
		 echo " resp=".$resp2;
}

?>