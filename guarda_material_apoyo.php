<?php
require("conexionmysqli2.inc");
require("estilos.inc");
require("funciones.php");

error_reporting(E_ALL);
 ini_set('display_errors', '1');


//recogemos variables
$globalAgencia=$_COOKIE['global_agencia'];
$tipo=$_POST['tipo'];
$nombreProducto=$_POST['material'];
$codigo2=$_POST['codigo2'];
$nombreProducto = strtoupper($nombreProducto);
//$codLinea=$_POST['codLinea'];
$codGrupo=$_POST['cod_grupo'];
$codTipo=$_POST['cod_tipo'];
$observaciones=$_POST['observaciones'];
$codUnidad=$_POST['cod_unidad'];
/*$precioProducto=$_POST['precio_producto'];
$costoProducto=$_POST['costo_producto'];*/

$codigoBarras=$_POST['codigo_barras'];
$codColor=$_POST['cod_color'];
$codTalla=$_POST['cod_talla'];
$codModelo=$_POST['cod_modelo'];
$codMaterial=$_POST['cod_material'];
$codGenero=$_POST['cod_genero'];
$codMarca=$_POST['cod_marca'];
$codSubGrupo=$_POST['cod_subgrupo'];
$codColeccion=$_POST['cod_coleccion'];

$fechaCreacion=date("Y-m-d-H-i-s");

$fechahora=date("dmy.Hi");
$archivoName=$fechahora.$_FILES['archivo']['name'];
if ($_FILES['archivo']["error"] > 0){
	echo "Error: " . $_FILES['archivo']['error'] . "<br>";
	$archivoName='default.png';
}else{
	move_uploaded_file($_FILES['archivo']['tmp_name'], "imagenesprod/".$archivoName);		
}


$sql="select IFNULL((max(codigo_material)+1),1) as codigo from material_apoyo m";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
//echo $codigo; 
$codigo=$dat[0];
//$codigo=mysql_result($resp,0,0);

$sql_inserta="insert into material_apoyo(codigo_material, descripcion_material, estado, cod_linea_proveedor, cod_grupo, cod_tipomaterial,
cantidad_presentacion, observaciones, imagen, cod_unidad, codigo_barras, cod_subgrupo, cod_marca, color, talla,codigo2, fecha_creacion,cod_modelo,cod_material,cod_genero,cod_coleccion,cod_tipo,costo_si_no) values ($codigo,'$nombreProducto','1','$codLinea','$codGrupo','$codTipo','1','$observaciones','$archivoName','$codUnidad','$codigoBarras',
'$codSubGrupo','$codMarca','$codColor','$codTalla','$codigo2','$fechaCreacion','$codModelo','$codMaterial',
'$codGenero','$codColeccion','$tipo',1)";
//echo $sql_inserta;

$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);

actualizaNombreProducto2($enlaceCon,$codigo);
	
//echo "si actualizo nombre";
if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_material.php?estado=-1&tipo=".$tipo."'
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>