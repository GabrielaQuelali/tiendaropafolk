<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");

$global_agencia=$_COOKIE["global_agencia"];

$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d H:i:s");

$fecha=date("Y-m-d");
//Efectivo
$tipoPago=1;
//Interno
$tipoRecibo=1;
// Restar de Ventas
$restarVentaProv=1;



$datosProvRec=$_POST['datosProvRec'];

echo $datosProvRec;
$arrayRecibos = explode(",", $datosProvRec);
for ($x=0;$x<count($arrayRecibos); $x++) { 

	echo $arrayRecibos[$x];
	$arrayProvGrupRec =explode("/", $arrayRecibos[$x]);
	$proveedor=$arrayProvGrupRec[0];
	$grupoRecibo=$arrayProvGrupRec[1];
	$montoRecibo=$_POST['monto'.$arrayRecibos[$x]];
	/////////////////////////
		$sql3="select  nombre_proveedor from proveedores where cod_proveedor=".$proveedor;
		$resp3=mysqli_query($enlaceCon,$sql3);
		while($dat3=mysqli_fetch_array($resp3)){	
			$nombre_proveedor=$dat3[0];
		}
		
		$sql4="select  nombre_gruporecibo from grupos_recibo where cod_gruporecibo=".$grupoRecibo;
		$resp3=mysqli_query($enlaceCon,$sql4);
		while($dat4=mysqli_fetch_array($resp4)){	
			$nombre_gruporecibo=$dat4[0];
		}
	//////////////////////////
	
	
	/*echo "Proveedor".$arrayProvGrupRec[0];
	echo "GrupoRecibo".$arrayProvGrupRec[1]."<br/>";
	echo "monto Recibo=".$_POST['monto'.$arrayRecibos[$x]];*/
	$sql = "select IFNULL(MAX(id_recibo)+1,1) from recibos where cod_ciudad='".$global_agencia."' order by id_recibo desc";
	$resp = mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$id_recibo=$dat[0];
	/////////////////////////////////////
	$consulta="insert into recibos (id_recibo,fecha_recibo,cod_ciudad,nombre_recibo,desc_recibo,
	monto_recibo,created_by,created_date,cel_recibo,recibo_anulado,cod_tipopago, cod_tiporecibo, cod_proveedor,cod_estadorecibo,resta_ventas_proveedor,cod_gruporecibo) 
	values(".$id_recibo.",'".$fecha."',".$global_agencia.",'".$nombre_proveedor."','".$nombre_gruporecibo."',".$montoRecibo.",".$createdBy.",
'".$createdDate."','".$nro_contacto."',0,".$tipoPago.",'".$tipoRecibo."','".$proveedor."',1 ,".$restarVentaProv.",'".$grupoRecibo."')";

mysqli_query($enlaceCon,$consulta);
	///////////////////////////////////////

}


?>
	<script language='Javascript'>
		location.href="listaRecibos.php";
	</script>	



    	


