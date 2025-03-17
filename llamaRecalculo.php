<?php

require("conexion.inc");
require("funcionRecalculoCostos.php");

$item=$_GET["item"];
$almacen=1000;

$bandera=recalculaCostos($item,$almacen);

echo "procesado: ".$bandera;

?>