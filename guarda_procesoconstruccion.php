<?php
require("conexionmysqli2.inc");
require("estilos.inc");
require("funciones.php");

error_reporting(E_ALL);
 ini_set('display_errors', '1');


//recogemos variables
$globalAgencia=$_COOKIE['global_agencia'];


$nombre_proceso_const=$_POST['nombre_proceso_const'];
$nombre_proceso_const = strtoupper($nombre_proceso_const);


$descripcion_proceso_const=$_POST['descripcion_proceso_const'];
$descripcion_proceso_const = strtoupper($descripcion_proceso_const);


$fechaCreacion=date("Y-m-d-H-i-s");



$sql="select IFNULL((max(cod_proceso_const)+1),1) as codigo from  procesos_construccion ";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$codigo=$dat[0];
$usuario=$_COOKIE['global_usuario'];

$sql_inserta="insert into procesos_construccion(cod_proceso_const,nombre_proceso_const,descripcion_proceso_const,cod_estado,
created_by,created_date) values ($codigo,'$nombre_proceso_const','$descripcion_proceso_const','1','$usuario','$fechaCreacion')";

//echo $sql_inserta;

$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);


if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_procesosConstruccion.php?estado=-1';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}

?>