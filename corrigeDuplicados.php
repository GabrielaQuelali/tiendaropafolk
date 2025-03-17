<?php
set_time_limit(0);
require('conexionmysqli.inc');
require("funciones.php");


$sql="select m.codigo_barras, count(*) from material_apoyo m 
where m.estado=1
group by m.codigo_barras having count(*)>1 and m.codigo_barras not in ('','000134887000','F')";
$resp=mysqli_query($enlaceCon, $sql);
while($dat=mysqli_fetch_array($resp)){
	$codigoBarras=$dat[0];
	$cantidadRepetida=$dat[1];

	//echo $codigoBarras." ".$cantidadRepetida."<br>";

	$codigo1=0;
	$codigo2=0;

	$sqlProds="select codigo_material, estado from material_apoyo m where m.codigo_barras='$codigoBarras'";
	$respProds=mysqli_query($enlaceCon, $sqlProds);
	if($datProds=mysqli_fetch_array($respProds)){
		$codigo1=$datProds[0];
	}
	if($datProds=mysqli_fetch_array($respProds)){
		$codigo2=$datProds[0];
	}
	$precioVenta1=precioVenta($enlaceCon,$codigo1,1);
	$precioVenta2=precioVenta($enlaceCon,$codigo2,1);

	$codigoACambiar=0;
	$codigoOficial=0;

	if($precioVenta1>$precioVenta2){
		$codigoOficial=$codigo1;
		$codigoACambiar=$codigo2;
	}else{
		$codigoOficial=$codigo2;
		$codigoACambiar=$codigo1;
	}
	//echo "codigo 1; ".$codigo1."PV. ".$precioVenta1." codigo2:".$codigo2." pv2:".$precioVenta2."<br>";

	$upd1="update ingreso_detalle_almacenes set cod_material='$codigoOficial' where cod_material='$codigoACambiar';";

	$upd2="update salida_detalle_almacenes set cod_material='$codigoOficial' where cod_material='$codigoACambiar';";

	$upd3="update material_apoyo set estado=0 where codigo_material='$codigoACambiar';";
	$upd4="update material_apoyo set estado=1 where codigo_material='$codigoOficial';";

	echo $upd1."<br>";
	echo $upd2."<br>";
	echo $upd3."<br>";
	echo $upd4."<br>";


}

echo "OK MATERIAL.....";
?>