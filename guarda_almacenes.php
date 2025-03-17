<?php
require("conexionmysqli.php");
require("estilos.inc");

$cod_ciudad=$_GET['cod_ciudad'];
$cod_funcionario=$_GET['cod_funcionario'];
$nombre_almacen=$_GET['nombre_almacen'];
$sql="select cod_almacen from almacenes order by cod_almacen desc";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{	$codigo=1000;
}
else
{	$codigo=$dat[0];
	$codigo++;
}

$sql="insert into almacenes (cod_almacen,cod_ciudad,nombre_almacen,responsable_almacen,cod_estado) values($codigo,$cod_ciudad,'$nombre_almacen',$cod_funcionario,'1')";
$resp_inserta=mysqli_query($enlaceCon,$sql);
// echo $sql;
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_almacenes.php';
			</script>";
?>