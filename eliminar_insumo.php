<?php

	require("conexionmysqli.php");
	require("estilos.inc");
	//require('estilos_inicio_adm.inc');

	$estado=$_GET['estado'];
	$tipo=$_GET['tipo'];
	$grupo=$_GET['grupo'];

	$datos=$_GET['datos'];

	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="update material_apoyo set estado=2 where codigo_material=$vector[$i]";
		$resp=mysqli_query($enlaceCon,$sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_insumos.php?tipo=".$tipo."&estado=".$estado."&grupo=".$grupo."';
			</script>";


?>