<?php
require("conexionmysqli.php");
require("estilos.inc");

//recogemos variables

$cod_lote=$_POST['cod_lote'];


$sqlDelete="delete from lote_procesoconst where cod_lote='".$cod_lote."'";
mysqli_query($enlaceCon,$sqlDelete);

$sql="select cod_proceso_const,nombre_proceso_const, descripcion_proceso_const,cod_estado,created_by,created_date
 from procesos_construccion where cod_estado=1
 order by nombre_proceso_const asc ";
		//echo $sql;
		$resp=mysqli_query($enlaceCon,$sql);
		while($dat=mysqli_fetch_array($resp)){
			$cod_proceso_const=$dat['cod_proceso_const'];
					
			if(isset($_POST['procesConst'.$cod_proceso_const])){
				$codProcesoConst=$_POST['procesConst'.$cod_proceso_const];
				$cantProcesoConst=$_POST['cant'.$cod_proceso_const];
				$precioProcesoConst=$_POST['precio'.$cod_proceso_const];
				$proveedor=$_POST['proveedor'.$cod_proceso_const];
				$obligacionxpagar=$_POST['obligacionxpagar_sino'.$cod_proceso_const];
				$fechaobligacionxpagar=$_POST['fechaobligacionxpagar'.$cod_proceso_const];

				if($cantProcesoConst>0 and $precioProcesoConst>0 ){
					$sqlInsert="insert into lote_procesoconst (cod_lote,cod_proceso_const,cod_proveedor,cantidad,
					precio,obligacionxpagar_si_no,obligacionxpagar_fecha,cod_estado_pago) values(".$cod_lote.",".$cod_proceso_const.",".$proveedor.",".$cantProcesoConst.",".$precioProcesoConst.",".$obligacionxpagar.",'".$fechaobligacionxpagar."',1)";
					//echo $sqlInsert;
					mysqli_query($enlaceCon,$sqlInsert);
				}

			}
	}
		
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_lotes.php'";
			
			echo"</script>";

	

?>