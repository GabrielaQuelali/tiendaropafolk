<?php
require("conexionmysqli.php");
require("estilos.inc");
require('funciones.php');

//recogemos variables

$codProducto=$_GET['cod_material'];
$tipo=$_GET['tipo'];
$estado=$_GET['estado'];
$globalAgencia=$_COOKIE['global_agencia'];

$sqlEdit="select codigo_material,descripcion_material,estado,cod_linea_proveedor,
cod_grupo,cod_tipomaterial,cantidad_presentacion,observaciones,imagen,cod_unidad,
peso,cod_subgrupo, cod_marca,codigo_barras,talla,color,codigo_anterior,codigo2,fecha_creacion,cod_modelo,
cod_material,cod_genero,cod_coleccion from material_apoyo
 where codigo_material='$codProducto'";
$respEdit=mysqli_query($enlaceCon,$sqlEdit);
while($datEdit=mysqli_fetch_array($respEdit)){
	$descripcion_material=$datEdit['descripcion_material'];
	$estado=$datEdit['estado'];
	$cod_linea_proveedor=$datEdit['cod_linea_proveedor'];
	$cod_grupo=$datEdit['cod_grupo'];
	$cod_tipomaterial=$datEdit['cod_tipomaterial'];
	$cantidad_presentacion=$datEdit['cantidad_presentacion'];
	$observaciones=$datEdit['observaciones'];
	$cod_unidad=$datEdit['cod_unidad'];
	$cod_subgrupo=$datEdit['cod_subgrupo'];
	$cod_marca=$datEdit['cod_marca'];
	//codigo_barras=$datEdit['codigo_barras'];
	$talla=$datEdit['talla'];
	$color=$datEdit['color'];
	//codigo_anterior=$datEdit['codigo_anterior'];
	//codigo2=$datEdit['codigo2'];
	//fecha_creacion=$datEdit['fecha_creacion'];
	$cod_modelo=$datEdit['cod_modelo'];
	$cod_material=$datEdit['cod_material'];
	$cod_genero=$datEdit['cod_genero'];
	$cod_coleccion=$datEdit['cod_coleccion'];

}
$fechaCreacion=date("Y-m-d-H-i-s");

$sql="select IFNULL((max(codigo_material)+1),1) as codigo from material_apoyo m";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$codigo=$dat[0];
//$codigo=mysql_result($resp,0,0);
$txtCopia="COPIA ";

$sql_inserta="insert into material_apoyo(codigo_material, descripcion_material, estado, cod_linea_proveedor, cod_grupo, cod_tipomaterial,
cantidad_presentacion, observaciones, cod_unidad, cod_subgrupo, cod_marca, color, talla,fecha_creacion,cod_modelo,cod_material,cod_genero,cod_coleccion,cod_tipo) values ($codigo,'$txtCopia.$codigo.$descripcion_material','1','1','$cod_grupo','$cod_tipomaterial','1','$observaciones','$cod_unidad',
'$cod_subgrupo','$cod_marca','$color','$talla','$fechaCreacion','$cod_modelo','$cod_material',
'$cod_genero','$cod_coleccion','$tipo')";



$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);

$sqlPrecio="select cod_precio,precio,cod_ciudad,cant_inicio, 
cant_final,created_by,created_date from precios  where codigo_material='".$codProducto."'
 and cod_ciudad='".$globalAgencia."'";

	$respPrecio=mysqli_query($enlaceCon,$sqlPrecio);
		
		while($daPrecio=mysqli_fetch_array($respPrecio)){

			$cod_precio=$daPrecio['cod_precio'];
			$precio=$daPrecio['precio'];
			$cod_ciudad=$daPrecio['cod_ciudad'];
			$cant_inicio=$daPrecio['cant_inicio'];
			$cant_final=$daPrecio['cant_final'];
			$fechaCreacion=date("Y-m-d-H-i-s");

	
	
				$sqlInsert="insert into precios (codigo_material,cod_precio,precio,cod_ciudad,cant_inicio,cant_final,
					created_by,created_date)values('".$codigo."','".$cod_precio."',
					'".$precio."','".$cod_ciudad."','".$cant_inicio."','".$cant_final."','".$_COOKIE['global_usuario']."','".$fechaCreacion."')";
				
					mysqli_query($enlaceCon,$sqlInsert);
			
		}

if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_material.php?tipo=".$tipo."&estado=".$estado."'
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}	

?>