<?php

	require("conexionmysqli.php");
	require("estilos.inc");
	//require('estilos_inicio_adm.inc');
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="delete from cotizaciones where cod_cotizacion=$vector[$i]";		
		$resp=mysqli_query($enlaceCon,$sql);
		$sql2="delete from cotizaciones_detalle where cod_cotizacion=$vector[$i]";		
		$resp2=mysqli_query($enlaceCon,$sql2);
		$sql3="delete from cotizaciones_detalle_manoobra where cod_cotizacion=$vector[$i]";		
		$resp3=mysqli_query($enlaceCon,$sql3);
		$sql4="delete from cotizacion_detalle_insumos where cod_cotizacion=$vector[$i]";		
		$resp4=mysqli_query($enlaceCon,$sql4);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_cotizaciones.php?estado=-1';
			</script>";

?>