<?php
require("../conexionmysqli.php");
require("../estilos2.inc");
require("configModule.php");

$codigo=$_POST['codigo'];
$nombre=$_POST['nombre'];
$abreviatura=$_POST['abreviatura'];
$codMaestro=$_POST['codMaestro'];
$tipo=$_POST['tipo'];

$sql_upd=mysqli_query($enlaceCon,"update $tableDetalle set nombre='$nombre', abreviatura='$abreviatura' where codigo='$codigo'");

echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='$urlListDetalle2?codMaestro=".$codMaestro."&tipo=".tipo."';
			</script>";
?>