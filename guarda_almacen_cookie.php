<?php
$variable=$_POST['almacen'];

require("conexion.inc");
$sql = "select cod_ciudad from almacenes where cod_almacen=$variable";
$resp = mysql_query( $sql );
$dat = mysql_fetch_array( $resp );
$cod_ciudad = $dat[0];

setcookie("global_almacen",$variable);
setcookie("global_agencia", $cod_ciudad);
echo "<script language='Javascript'>
			alert('El valor fue cambiado con éxito.');
			parent.location='indexAlmacenReg.php';
			</script>";
?>