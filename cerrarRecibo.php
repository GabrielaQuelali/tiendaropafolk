<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");

$global_agencia=$_COOKIE["global_agencia"];
$recibo=$_GET["recibo"];






$consulta="update recibos  set ";
$consulta.=" cod_estadorecibo=2";
$consulta.=" where id_recibo='".$recibo."' and cod_ciudad='".$global_agencia."'";

//echo $consulta;
mysqli_query($enlaceCon,$consulta);

?>


    	


