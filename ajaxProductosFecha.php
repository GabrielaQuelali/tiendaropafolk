<?php
require("conexionmysqlipdf.inc");

// $fecha_producto = $_POST['fecha_ini'];
$grupo          = $_POST['cod_grupo'] ?? '';
$modelo         = $_POST['cod_modelo'] ?? '';
$nombre         = trim($_POST['nombre_producto'] ?? '');


$grupo = implode(",", $grupo);
$modelo = implode(",", $modelo);

// $sql="select m.codigo_material, m.descripcion_material
// from material_apoyo m where estado=1 and m.fecha_creacion>='$fecha_producto' order by 2";

$sql = "SELECT m.codigo_material, m.descripcion_material
        FROM material_apoyo m
        WHERE m.estado = 1";
$params = [$fecha_producto];
if (!empty($grupo)) {
    $sql .= " AND m.cod_grupo in ($grupo) ";
}
if (!empty($modelo)) {
    $sql .= " AND m.cod_modelo in ($modelo)";
}
if (!empty($nombre)) {
    $sql .= " AND m.descripcion_material LIKE '%$nombre%'";
}
$sql .= " ORDER BY m.descripcion_material";

//echo $sql; 

$resp=mysqli_query($enlaceCon,$sql);

echo "<select class='texto' name='rpt_productos[]' id='rpt_productos' size='10' multiple>";
while($dat=mysqli_fetch_array($resp)){
	$codigo=$dat[0];
	$nombre=$dat[1];
	echo "<option value='$codigo'>$nombre</option>";
}	
echo "</select>";

?>