<?php
require("conexionmysqli.inc");
$global_agencia=$_COOKIE["global_agencia"];
$idReciboAnular=$_GET["idRecibo"];

$sql="update recibos set recibo_anulado=1 ";
$sql.=" where id_recibo='".$idReciboAnular."' and cod_ciudad='".$global_agencia."'";
$resp=mysqli_query($enlaceCon,$sql);

		echo "<script language='Javascript'>
			Swal.fire('Recibo Anulado.')
		    .then(() => {
				location.href='listaRecibos.php';	
		    });
		</script>";
		



?>