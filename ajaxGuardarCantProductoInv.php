<?php
require("conexionmysqli.php");
$codigo=$_GET['codigo'];
$cantidad=$_GET['cantidad'];
$ciudad=$_COOKIE['global_agencia'];
$usuario=$_COOKIE['global_usuario'];
echo "ciudad".$ciudad."<br/>";
echo "usuario".$usuario."<br/>";
$fechaCreacion=date("Y-m-d-H-i-s");
$sql="delete from  inventarioinicial where codigo_material=".$codigo." and cod_ciudad=".$ciudad;
mysqli_query($enlaceCon,$sql);

$sqlInsert="insert into inventarioinicial
 (codigo_material,cantidad,fecha,codigo_funcionario,cod_ciudad)values(".$codigo.",".$cantidad.",'".$fechaCreacion."',".$usuario.",".$ciudad.")";
mysqli_query($enlaceCon,$sqlInsert);

$sqlAux="select ii.cantidad, ii.fecha, ii.cod_ciudad, c.descripcion as descCiudad,
		ii.codigo_funcionario, concat (f.nombres,f.paterno,f.materno) as funcionario
		from inventarioinicial ii 
		left join ciudades c on(ii.cod_ciudad=c.cod_ciudad)
		left join funcionarios f on (ii.codigo_funcionario=f.codigo_funcionario)
		where ii.codigo_material=".$codigo."
		 and ii.cod_ciudad=".$ciudad;
		 //echo $sqlAux;
$respAux=mysqli_query($enlaceCon,$sqlAux);
while($datAux=mysqli_fetch_array($respAux)){
	$cantidad=$datAux['cantidad'];
	$fecha=$datAux['fecha'];
	$descCiudad=$datAux['descCiudad'];
	$funcionario=$datAux['funcionario'];
}
?>

<strong><?=$cantidad;?></strong><br/>(<?=$fecha?>)<br/><?=$descCiudad?><br/><?=$funcionario?>
<?php
echo "<br/><a href='#'   onClick='ajaxGuardarCantProductoInv($codigo);'>Guardar</a>";

?>
