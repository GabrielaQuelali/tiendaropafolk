<?php
require("../conexionmysqli.php");
require("../estilos2.inc");
require("configModule.php");

$nombre=$_POST['nombre'];
$abreviatura=$_POST['abreviatura'];
$tipo=$_POST['tipo'];

$sql="insert into $table (nombre, abreviatura, estado,cod_tipo) values('$nombre','$abreviatura','1','$tipo')";
//echo $sql;
$sql_inserta=mysqli_query($enlaceCon,$sql);

echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='list.php?tipo='+$tipo;
			</script>";

?>