<?php

	require("conexionmysqli.php");
	require("estilos.inc");
	//require('estilos_inicio_adm.inc');
	$datos=$_GET['datos'];
	$estado=$_GET['estado'];
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="update procesos_construccion set cod_estado=2 where cod_proceso_const=$vector[$i]";
		
		$resp=mysqli_query($enlaceCon,$sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_procesosConstruccion.php?estado=$estado';
			</script>";

?>