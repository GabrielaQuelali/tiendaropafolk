<?php
require("conexionmysqli.php");

$sql="update ingreso_almacenes set ingreso_anulado=1 where cod_ingreso_almacen='$codigo_registro'";
$resp=mysqli_query($enlaceCon,$sql);

echo "<script language='Javascript'>
			alert('El registro fue anulado.');
			location.href='navegador_ingresomateriales.php';			
			</script>";

?>