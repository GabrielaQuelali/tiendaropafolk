<?php
require("conexionmysqli.php");
$codGrupo=$_GET['cod_grupo'];

$sql="select codigo, nombre from subgrupos where cod_grupo in ($codGrupo)  order  by nombre asc";
$resp=mysqli_query($enlaceCon,$sql);

echo "<select name='cod_subgrupo' class='texto' id='cod_subgrupo' required>";
echo "<option value=''>---</option>";
while($dat=mysqli_fetch_array($resp)){
	$codigo=$dat[0];
	$nombre=$dat[1];

	echo "<option value='$codigo'>$nombre</option>";
}
echo "</select>";

?>
