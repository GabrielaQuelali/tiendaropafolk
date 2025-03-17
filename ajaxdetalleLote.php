<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">


<?php 

require_once 'conexionmysqli2.inc';
require_once 'funciones.php';

$global_almacen=$_COOKIE['global_almacen'];
$globalAgencia=$_COOKIE["global_agencia"];
	$banderaEditPrecios=0;
	$banderaEditPrecios=obtenerValorConfiguracion($enlaceCon, 20);


	$cod_lote=$_GET['lote'];

	$sqlLote="select lp.codigo_material, ma.descripcion_material,lp.cant_lote
from lotes_produccion lp
left join material_apoyo ma on (lp.codigo_material=ma.codigo_material) 
	where lp.cod_lote='".$cod_lote."'";
	//echo $sqlLote;

	$respLote=mysqli_query($enlaceCon,$sqlLote);
	while($datLote=mysqli_fetch_array($respLote)){

		$descripcion_material=$datLote['descripcion_material'];
		$cant_lote=$datLote['cant_lote'];
		
echo $descripcion_material;
	
 		
	}


?>



</head>
</html>