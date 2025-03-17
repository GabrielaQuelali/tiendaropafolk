<?php

	require("conexionmysqli.php");
	require("estilos.inc");
	//require('estilos_inicio_adm.inc');
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="delete from producto_costo where cod_producto_costo=$vector[$i]";		
		$resp=mysqli_query($enlaceCon,$sql);
		$sql2="delete from producto_costo_detalle where cod_producto_costo=$vector[$i]";		
		$resp2=mysqli_query($enlaceCon,$sql2);
		
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_productoCosto.php?estado=-1';
			</script>";

?>