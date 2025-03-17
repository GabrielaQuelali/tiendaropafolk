<?php
require("conexionmysqli.php");
$codigo=$_GET['codigo'];
$sw=$_GET['sw'];
//echo "holaaa";
$sql="update material_apoyo set costo_si_no=".$sw." where codigo_material=".$codigo;
$resp=mysqli_query($enlaceCon,$sql);

$sqlAux="select costo_si_no from material_apoyo where codigo_material=".$codigo;
$respAux=mysqli_query($enlaceCon,$sqlAux);
while($datAux=mysqli_fetch_array($respAux)){
	$costo_si_no=$datAux['costo_si_no'];
}

if($costo_si_no==1){
	$costo_si_no_desc="SI";
}else{
	$costo_si_no_desc="NO";
}
	echo $costo_si_no_desc;
	if($costo_si_no==1){
			echo "<br/><a href='#' onClick='ajaxCambioCosteo($codigo,0);'  >Deshabilitar</a>";
	}else{
			echo "<br/><a href='#' onClick='ajaxCambioCosteo($codigo,1);'>Habilitar</a>";
	}


?>
