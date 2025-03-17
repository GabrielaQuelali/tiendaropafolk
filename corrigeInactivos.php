<?php
set_time_limit(0);
require('conexionmysqli.inc');
require("funciones.php");

//where m.estado=1

$sql="select m.codigo_barras, count(*) from material_apoyo m 
group by m.codigo_barras having count(*)>1 and m.codigo_barras not in ('','000134887000','F')";
$resp=mysqli_query($enlaceCon, $sql);
while($dat=mysqli_fetch_array($resp)){
	$codigoBarras=$dat[0];
	$cantidadRepetida=$dat[1];

	//echo $codigoBarras." ".$cantidadRepetida."<br>";

	$codigo1=0;
	$codigo2=0;

	$sqlProd="select codigo_material, estado from material_apoyo m where m.codigo_barras='$codigoBarras'";
	$respProd=mysqli_query($enlaceCon, $sqlProd);
	while($datProd=mysqli_fetch_array($respProd)){
		$codigoProd=$datProd[0];
		$estado=$datProd[1];

		$cantidadIngreso=0;
		$cantidadSalida=0;

		if($estado!=1){
			$sqlIngresos="select count(*) from ingreso_almacenes i, ingreso_detalle_almacenes id
				where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and 
				id.cod_material='$codigoProd'";
			$respIngreso=mysqli_query($enlaceCon, $sqlIngresos);
			if($datIngresos=mysqli_fetch_array($respIngreso)){
				$cantidadIngreso=$datIngresos[0];
			}

			$sqlSalidas="select count(*) from salida_almacenes s, salida_detalle_almacenes sd
				where s.cod_salida_almacenes=sd.cod_salida_almacen and s.salida_anulada=0 and 
				sd.cod_material='$codigoProd'";
			$respSalida=mysqli_query($enlaceCon, $sqlSalidas);
			if($datSalidas=mysqli_fetch_array($respSalida)){
				$cantidadSalida=$datSalidas[0];
			}
		}
		echo "PROD: ".$codigoProd." ".$cantidadIngreso." ".$cantidadSalida." <br>";
	}

}

echo "OK MATERIAL.....";
?>