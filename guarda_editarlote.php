<?php
require("conexionmysqli.php");
require("estilos.inc");
require("funciones.php");

//recogemos variables
$globalAgencia=$_COOKIE['global_agencia'];

$codLote=$_POST['codLote'];
$nombre_lote=$_POST['nombre_lote'];
$fecha=$_POST['fecha'];

$cod_producto=$_POST['cod_producto'];
$obs_lote=$_POST['obs_lote'];
$cant_lote=$_POST['cant_lote'];
$obligacionxpagar=$_POST['obligacionxpagar'];

$sql_inserta="update lotes_produccion set 
 fecha_lote='".$fecha."', 
 nombre_lote='".$nombre_lote."', 
 obs_lote='".$obs_lote."',
codigo_material=".$cod_producto.",  
cant_lote=".$cant_lote.",
obligacionxpagar_si_no=".$obligacionxpagar."
where cod_lote=".$codLote;
echo $sql_inserta;
$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);

if($obligacionxpagar!=1){
	$sqlUpdate="update lotes_produccion set cod_estado_pago=3 where cod_lote=".$codLote;
	mysqli_query($enlaceCon,$sqlUpdate);
}

if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron guardados correctamente.');
			location.href='navegador_lotes.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}


?>