<?php

	require("conexionmysqli.php");
	require("estilos.inc");
	//require('estilos_inicio_adm.inc');
	$codLote=$_GET["codLote"];
		$fecha=date("Y-m-d-H-i-s");

		$sql="update lotes_produccion set cod_estado_lote=2, fecha_inicio_lote='$fecha' where cod_lote=$codLote";
	
		$resp=mysqli_query($enlaceCon,$sql);
	
	echo "<script language='Javascript'>
			
			location.href='navegador_lotes.php';
			</script>";

?>