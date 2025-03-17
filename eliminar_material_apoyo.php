<?php

	require("conexionmysqli.php");
	require("estilos.inc");
	//require('estilos_inicio_adm.inc');
	$datos=$_GET['datos'];
	$tipo=$_GET['tipo'];
	$estado=$_GET['estado'];
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="update material_apoyo set estado=2 where codigo_material=$vector[$i]";
		$resp=mysqli_query($enlaceCon,$sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_material.php?tipo=".$tipo."&estado=".$estado."'
			</script>";


?>