<?php
require("conexionmysqli.php");
require("estilos.inc");
require("funciones.php");

//recogemos variables
$globalAgencia=$_COOKIE['global_agencia'];
$codProducto=$_POST['codProducto'];
$nombreProducto=$_POST['material'];
$nombreProducto = strtoupper($nombreProducto);

//$codLinea=$_POST['codLinea'];
$codGrupo=$_POST['cod_grupo'];
$codTipo=$_POST['cod_tipo'];
$observaciones=$_POST['observaciones'];
$codUnidad=$_POST['cod_unidad'];
$precioProducto=$_POST['precio_producto'];
$costoProducto=$_POST['costo_producto'];

$codigoBarras=$_POST['codigo_barras'];
$color=$_POST['cod_color'];
$talla=$_POST['cod_talla'];
$codMarca=$_POST['cod_marca'];
$codSubGrupo=$_POST['cod_subgrupo'];
$codigo2=$_POST['codigo2'];
$codModelo=$_POST['cod_modelo'];
$codGenero=$_POST['cod_genero'];
$codMaterial=$_POST['cod_material'];
$codColeccion=$_POST['cod_coleccion'];

$sql_inserta="update material_apoyo set descripcion_material='$nombreProducto', 

cod_grupo='$codGrupo', observaciones='$observaciones', cod_unidad='$codUnidad', codigo_barras='$codigoBarras', color='$color',
talla='$talla', cod_marca='$codMarca', cod_subgrupo='$codSubGrupo',codigo2='$codigo2',
cod_modelo='$codModelo',cod_genero='$codGenero',cod_material='$codMaterial'
,cod_coleccion='$codColeccion'

where codigo_material='$codProducto'";
//echo $sql_inserta;
$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);
actualizaNombreProducto2($enlaceCon,$codProducto);


if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron guardados correctamente.');
			location.href='navegador_material.php?tipo=$tipo&estado=$estado';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>