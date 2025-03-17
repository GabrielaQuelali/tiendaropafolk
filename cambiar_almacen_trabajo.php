<?php
require("conexionmysqli.php");

require("estilos.inc");
error_reporting(E_ALL);
 ini_set('display_errors', '1');
    $sqlCambioUsd="select valor from cotizaciondolar order by 1 desc limit 1";
	$respUsd=mysqli_query($enlaceCon,$sqlCambioUsd);
	$tipoCambio=1;
	while($filaUSD=mysqli_fetch_array($respUsd)){
		$tipoCambio=$filaUSD[0];	
	}

echo "<form action='guarda_almacen_coockie.php' method='post'>";

echo "<h1>Cambiar Almacen Trabajo</h1>";

echo "<center><table class='texto'>";
echo "<tr><th align='left'>Almacen</th>";
echo "<td align='center'>";

$sql1="select cod_almacen, nombre_almacen from almacenes where cod_estado=1 order by nombre_almacen";
$resp1=mysqli_query($enlaceCon,$sql1);
echo "<td align='center'><select name='almacen' id='almacen' class='texto'>";
while($dat1=mysqli_fetch_array($resp1))
{   
	$cod_almacen=$dat1[0];
    $nombre_almacen=$dat1[1];
    if($cod_almacen==$_COOKIE['global_almacen']){
      echo "<option value='$cod_almacen' selected>$nombre_almacen</option>";
    }else{
      echo "<option value='$cod_almacen'>$nombre_almacen</option>";	
    }    
}
echo "</select>";

echo	"</td></tr>";
echo "</table></center>";

echo "<div class='divBotones'>
<input type='submit' class='boton' value='Guardar'>
";
echo "</form>";
?>