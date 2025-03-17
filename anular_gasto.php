<?php
require("conexionmysqli.inc");
$global_agencia=$_COOKIE["global_agencia"];
$idGastoAnular=$_GET["idGasto"];

$sql="update gastos set gasto_anulado=2 ";
$sql.=" where cod_gasto='".$idGastoAnular."' and cod_ciudad='".$global_agencia."'";
$resp=mysqli_query($enlaceCon,$sql);

		echo "<script language='Javascript'>
			Swal.fire('Recibo Anulado.')
		    .then(() => {
				location.href='listaGastos.php';	
		    });
		</script>";
		



?>