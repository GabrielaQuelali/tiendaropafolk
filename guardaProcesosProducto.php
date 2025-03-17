<?php
require("conexionmysqli.php");
require("estilos.inc");

//recogemos variables
$codProducto=$_POST['codProducto'];
$nombreProducto=$_POST['nombreProducto'];

$tipo=$_POST['tipo'];
$estado=$_POST['estado'];

$sqlDelete="delete from procesos_construccion_producto where cod_producto='".$codProducto."'";
//echo $sqlDelete;
mysqli_query($enlaceCon,$sqlDelete);

$sql="select cod_proceso_const,nombre_proceso_const, descripcion_proceso_const,cod_estado,created_by,created_date
 from procesos_construccion where cod_estado=1
 order by nombre_proceso_const asc ";
		//echo $sql;
		$resp=mysqli_query($enlaceCon,$sql);
		while($dat=mysqli_fetch_array($resp)){
			$cod_proceso_const=$dat['cod_proceso_const'];
			
			//echo "codigoInsumo=".$codigoInsumo."<br/>";

			if(isset($_POST['cod_proceso_const'.$cod_proceso_const])){


					$sqlInsert="insert into procesos_construccion_producto (cod_producto,cod_proceso_const,costo_proceso_const) values('".$codProducto."','".$cod_proceso_const."',".$_POST['costo'.$cod_proceso_const].")";
					//echo $sqlInsert;
					mysqli_query($enlaceCon,$sqlInsert);

				}

			}
		


	echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='registroProcesosProducto.php?codigo=".$codProducto."&nombre=".$nombreProducto."&tipo=".$tipo."&estado=".$estado."'";
			
			echo"</script>";

	

?>