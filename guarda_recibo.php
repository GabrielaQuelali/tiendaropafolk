<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");

$global_agencia=$_COOKIE["global_agencia"];

$sql = "select IFNULL(MAX(id_recibo)+1,1) from recibos where cod_ciudad='".$global_agencia."' order by id_recibo desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$id_recibo=$dat[0];
//$nro_correlativo=mysql_result($resp,0,0);



$monto=$_POST['monto'];
$tipoRecibo=$_POST['tipoRecibo'];
$tipoPago=$_POST['tipoPago'];
$nombre=$_POST['nombre'];
$nro_contacto=$_POST['nro_contacto'];
$desc_recibo=$_POST['desc_recibo'];
$proveedor=$_POST['proveedor'];
$restarVentaProv=$_POST['restarVentaProv'];
$grupoRecibo=$_POST['grupoRecibo'];
if(empty($proveedor)){
	$proveedor=NULL;
}

$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d H:i:s");

$fecha=$_POST['fecha'];
$vector_fecha_recibo=explode("/",$fecha);
$fecha_recibo=$vector_fecha_recibo[2]."-".$vector_fecha_recibo[1]."-".$vector_fecha_recibo[0];
/*echo $fecha."<br>";
echo $fecha_recibo;*/
//$fecha=date("Y-m-d");




$consulta="insert into recibos (id_recibo,fecha_recibo,cod_ciudad,nombre_recibo,desc_recibo,
monto_recibo,created_by,created_date,cel_recibo,recibo_anulado,cod_tipopago, cod_tiporecibo, cod_proveedor,cod_estadorecibo,resta_ventas_proveedor,cod_gruporecibo) 
values(".$id_recibo.",'".$fecha_recibo."',".$global_agencia.",'".$nombre."','".$desc_recibo."',".$monto.",".$createdBy.",
'".$createdDate."','".$nro_contacto."',0,".$tipoPago.",'".$tipoRecibo."','".$proveedor."',1 ,".$restarVentaProv.",'".$grupoRecibo."')";

mysqli_query($enlaceCon,$consulta);

?>


	<script language='Javascript'>
		location.href="listaRecibos.php";
	</script>	
    

    	


