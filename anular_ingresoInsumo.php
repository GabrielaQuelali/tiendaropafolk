<?php
require("conexionmysqli.inc");


$codigo_registro=$_GET['codigo_registro'];
$tipo=$_GET['tipo'];
$estado=$_GET['estado'];

$sql="update ingreso_almacenes set ingreso_anulado=2 where cod_ingreso_almacen='$codigo_registro'";
$resp=mysqli_query($enlaceCon,$sql);

		echo "<script language='Javascript'>
			Swal.fire('El registro fue anulado.')
		    .then(() => {
				location.href='navegador_ingresoinsumos.php?tipo=".$tipo."&estado=".$estado."';	
		    });
		</script>";
		

/*echo "<script language='Javascript'>
			alert('El registro fue anulado.');
			location.href='navegador_ingresomateriales.php';			
			</script>";*/

?>