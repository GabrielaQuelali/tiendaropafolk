<?php
	require("../conexionmysqli.php");
	require("../estilos2.inc");
	require("configModule.php");

	$tipo=$_GET['tipo'];
	$codMaestro=$_GET['codMaestro'];
	$datos=$_GET['datos'];
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="update $tableDetalle set estado=2 where codigo=$vector[$i]";
		$resp=mysqli_query($enlaceCon,$sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='listDetalle.php?tipo=".$tipo."&codMaestro=".$codMaestro."';
			</script>";

?>