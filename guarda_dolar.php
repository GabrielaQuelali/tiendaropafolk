<?php
require("conexionmysqli.php");
require("estilos.inc");

$valor=$_POST['monto_dolar'];

$sql_inserta=mysqli_query("UPDATE cotizaciondolar set valor=$valor");
echo "<script language='Javascript'>
			alert('El valor fue cambiado con éxito.');
			location.href='registrar_cotizacion_dolar.php';
			</script>";
?>