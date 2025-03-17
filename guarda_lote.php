<?php
require("conexionmysqli2.inc");
require("estilos.inc");
require("funciones.php");

error_reporting(E_ALL);
 ini_set('display_errors', '1');


//recogemos variables
$globalAgencia=$_COOKIE['global_agencia'];
$cod_ciudad=$_COOKIE['global_agencia'];

$nombre_lote=$_POST['nombre_lote'];
$nombre_lote = strtoupper($nombre_lote);
$cod_producto=$_POST['cod_producto'];
$obs_lote=$_POST['obs_lote'];
$cant_lote=$_POST['cant_lote'];
$fecha=$_POST['fecha'];
$usuario=$_COOKIE['global_usuario'];
$fechaCreacion = date("Y-m-d H:i:s");
$obligacionxpagar=$_POST['obligacionxpagar'];


$sql="select IFNULL((max(cod_lote)+1),1) as codigo from lotes_produccion ";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$cod_lote=$dat[0];


$sql2="select IFNULL((max(nro_lote)+1),1) as codigo from lotes_produccion  where cod_ciudad=".$cod_ciudad;
$resp2=mysqli_query($enlaceCon,$sql2);
$dat2=mysqli_fetch_array($resp2);
$nro_lote=$dat2[0];


$sql_inserta="insert into lotes_produccion(cod_lote,nro_lote,fecha_lote,nombre_lote,obs_lote,codigo_material,cant_lote,cod_estado_lote,created_by,
created_date,obligacionxpagar_si_no,cod_estado_pago,cod_ciudad) values ($cod_lote,$nro_lote,'$fecha','$nombre_lote','$obs_lote','$cod_producto',$cant_lote,'1',
'$usuario','$fechaCreacion',$obligacionxpagar,1,$cod_ciudad)";
//echo $sql_inserta;

$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);

if($obligacionxpagar!=1){
	$sqlUpdate="update lotes_produccion set cod_estado_pago=3 where cod_lote=".$cod_lote;
	mysqli_query($enlaceCon,$sqlUpdate);
}


if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_lotes.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>