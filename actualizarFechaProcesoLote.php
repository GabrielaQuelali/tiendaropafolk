<?php
require("conexionmysqli2.inc");
require("estilos.inc");
require("funciones.php");

error_reporting(E_ALL);
 ini_set('display_errors', '1');

//and codigo_material=1  

$sql3="select cod_lote,fecha_lote,obligacionxpagar_si_no,cod_estado_pago  from lotes_produccion where cod_estado_lote<>4;";
	$resp3=mysqli_query($enlaceCon,$sql3);

	while($dat3=mysqli_fetch_array($resp3)){
		$cod_lote=$dat3['cod_lote'];
		$fecha_lote=$dat3['fecha_lote'];
		$obligacionxpagar_si_no=$dat3['obligacionxpagar_si_no'];
		$cod_estado_pago=$dat3['cod_estado_pago'];

		
			$sqlUpdate="update lote_procesoconst set
				obligacionxpagar_si_no=".$obligacionxpagar_si_no.",
				obligacionxpagar_fecha='".$fecha_lote."',
				cod_estado_pago=".$cod_estado_pago."
				where cod_lote=".$cod_lote;
		$sqlUpdate."<br>";
		mysqli_query($enlaceCon,$sqlUpdate);
		
	
		
}
		

?>