<?php

require("conexion.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");


$codigo_ingreso=$_POST['codigoIngreso'];
$cantidad_items=$_POST['numeroItems'];
$cod_almacen=$_COOKIE['global_almacen'];


for ($i = 1; $i <= $cantidad_items-1; $i++) {
	$cod_material = $_POST["material$i"];
    $precioBruto=$_POST["precioBruto$i"];
	
    $consulta="update ingreso_detalle_almacenes set precio_bruto='$precioBruto', precio_neto='$precioBruto',
		costo_almacen='$precioBruto', costo_actualizado='$precioBruto', costo_actualizado_final='$precioBruto',
		costo_promedio='$precioBruto'
		where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$cod_material'";
	$sql_inserta2 = mysql_query($consulta);
	
	recalculaCostos($cod_material, $cod_almacen);
}
echo "<script language='Javascript'>
    alert('Los datos fueron guardados correctamente.');
	location.href='navegadorLiquidacionIngresos.php';
    </script>";
?>