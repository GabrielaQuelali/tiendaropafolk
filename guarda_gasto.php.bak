<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");

$global_agencia=$_COOKIE["global_agencia"];

$sql = "select IFNULL(MAX(cod_gasto)+1,1) from gastos where cod_ciudad='".$global_agencia."' order by cod_gasto desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$cod_gasto=$dat[0];
//$nro_correlativo=mysql_result($resp,0,0);



$monto=$_POST['monto'];
$tipoPago=$_POST['tipoPago'];
$tipoGasto=$_POST['tipoGasto'];
$grupoGasto=$_POST['grupoGasto'];
$proveedor=$_POST['proveedor'];
//echo "proveedor=".$proveedor;
if(empty($proveedor)){
	$proveedor=NULL;
	//echo "entro if proveedor=".$proveedor;
}

$descripcion_gasto=$_POST['descripcion_gasto'];

$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d H:i:s");

$fecha=date("Y-m-d");



$consulta="insert into gastos (cod_gasto,descripcion_gasto,cod_tipogasto,fecha_gasto,monto,cod_ciudad,
created_by,created_date,
gasto_anulado,cod_proveedor,cod_grupogasto,cod_tipopago) 
values(".$cod_gasto.",'".$descripcion_gasto."',".$tipoGasto.",'".$fecha."',".$monto.",'".$global_agencia."',".$createdBy.",'".$createdDate."',0,'".$proveedor."','".$grupoGasto."','".$tipoPago."')";

mysqli_query($enlaceCon,$consulta);

?>


	<script language='Javascript'>
		location.href="listaGastos.php";
	</script>	
    

    	


