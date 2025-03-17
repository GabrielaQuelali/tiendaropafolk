<?php
require("conexionmysqli.php");
require("estilos.inc");
require("funciones.php");

//recogemos variables
$globalAgencia=$_COOKIE['global_agencia'];

$codProcesoConst=$_POST['codProcesoConst'];
$estado=$_POST['estado'];
$nombre_proceso_const=$_POST['nombre_proceso_const'];

$descripcion_proceso_const=$_POST['descripcion_proceso_const'];


$sql_inserta="update procesos_construccion set 
 nombre_proceso_const='$nombre_proceso_const', 
 descripcion_proceso_const='$descripcion_proceso_const'

where cod_proceso_const='$codProcesoConst'";
$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);



if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron guardados correctamente.');
			location.href='navegador_procesosConstruccion.php?estado=$estado';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>