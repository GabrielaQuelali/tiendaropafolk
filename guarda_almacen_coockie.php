<?php
$variable=$_POST['almacen'];

require("conexionmysqli2.inc");
$sql = "select cod_ciudad from almacenes where cod_almacen=$variable";
$resp = mysqli_query($enlaceCon,$sql );
$dat = mysqli_fetch_array( $resp );
$cod_ciudad = $dat[0];

setcookie("global_almacen",$variable);
setcookie("global_agencia", $cod_ciudad);
echo "<script language='Javascript'>
			alert('El valor fue cambiado con éxito.');
			parent.location='indexAlmacenReg.php';
			</script>";
?>