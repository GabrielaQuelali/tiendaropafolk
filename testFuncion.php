<?php

require("conexion.inc");
require("estilos_almacenes.inc");
require("funciones.php");
require("funciones_inventarios.php");


$codSalida=3038;

$montoDoc=montoVentaDocumento($codSalida);

echo $montoDoc;

?>