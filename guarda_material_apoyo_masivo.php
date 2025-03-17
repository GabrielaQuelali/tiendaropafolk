<?php
require("conexionmysqli2.inc");
require("estilos.inc");
require("funciones.php");

/*error_reporting(E_ALL);
 ini_set('display_errors', '1');*/


//recogemos variables
$globalAgencia=$_COOKIE['global_agencia'];

$tipo=$_POST['tipo'];
$codTipo=$_POST['cod_tipo'];
$codGrupo=$_POST['cod_grupo'];

$observaciones='';
$codUnidad=$_POST['cod_unidad'];


//$codColor=$_POST['cod_color'];

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


$sqlColores="select codigo, nombre,abreviatura from colores where estado=1 order by nombre asc";
	$respColores=mysqli_query($enlaceCon,$sqlColores);

	while($datColores=mysqli_fetch_array($respColores)){
		$codColor=$datColores[0];

		if($_POST['color'.$codColor]){

/////////////////////////////
$sql="select codigo, nombre, abreviatura, estado from tallas where estado=1 order by 1";
	$resp=mysqli_query($enlaceCon,$sql);

	
	while($dat=mysqli_fetch_array($resp)){
		$codTalla=$dat[0];

		if($_POST['talla'.$codTalla]){
			

			$sql2="select IFNULL((max(codigo_material)+1),1) as codigo from material_apoyo m";
			$resp2=mysqli_query($enlaceCon,$sql2);
			$dat2=mysqli_fetch_array($resp2);

			$codigo=$dat2[0];
			$nombreProducto=$codigo;
			

			$sql_inserta="insert into material_apoyo(codigo_material, descripcion_material, estado, cod_linea_proveedor, cod_grupo, cod_tipomaterial,
cantidad_presentacion, observaciones, imagen, cod_unidad, codigo_barras, cod_subgrupo, cod_marca, color, talla,codigo2, fecha_creacion,cod_modelo,cod_material,cod_genero,cod_coleccion,cod_tipo,costo_si_no) values ($codigo,'$nombreProducto','1','1','$codGrupo','$codTipo','1','$observaciones','$archivoName','$codUnidad','$codigoBarras',
'$codSubGrupo','$codMarca','$codColor','$codTalla','','$fechaCreacion','$codModelo','$codMaterial',
'$codGenero','$codColeccion','$tipo',1)";


			$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);

			actualizaNombreProducto2($enlaceCon,$codigo);
	
			//////////////////

			$ciudad=$_COOKIE['global_agencia'];

		$sqlCiu="select cod_ciudad,descripcion as desc_ciudad,nombre_ciudad 
		from ciudades where cod_ciudad='".$ciudad."' order by cod_ciudad asc";
	
		$respCiu=mysqli_query($enlaceCon,$sqlCiu);
		
		while($datCiu=mysqli_fetch_array($respCiu)){

			$cod_ciudad=$datCiu['cod_ciudad'];
		

		$sqlGrupoPrecio="select codigo,nombre from grupos_precio where  estado=1 order by codigo asc";
		//echo $sqlGrupoPrecio;
		$respGrupoPrecio=mysqli_query($enlaceCon,$sqlGrupoPrecio);
		
			while($datGrupoPrecio=mysqli_fetch_array($respGrupoPrecio)){

				$codGrupoPrecio=$datGrupoPrecio['codigo'];

					$precio=0;
					$cant_inicio=0;
					$cant_final=0;
					
					if($_POST['precio'.$cod_ciudad.$codGrupoPrecio]){

						$precio=$_POST['precio'.$cod_ciudad.$codGrupoPrecio];

					}
						if($_POST['cant_ini'.$cod_ciudad.$codGrupoPrecio]){

						$cant_inicio=$_POST['cant_ini'.$cod_ciudad.$codGrupoPrecio];

					}
						if($_POST['cant_fin'.$cod_ciudad.$codGrupoPrecio]){

						$cant_final=$_POST['cant_fin'.$cod_ciudad.$codGrupoPrecio];

					}
			
					$fechaCreacion=date("Y-m-d-H-i-s");
					$sqlInsert="insert into precios (codigo_material,cod_precio,precio,cod_ciudad,cant_inicio,cant_final,
					created_by,created_date)values('".$codigo."','".$codGrupoPrecio."',
					'".$precio."','".$cod_ciudad."','".$cant_inicio."','".$cant_final."','".$_COOKIE['global_usuario']."','".$fechaCreacion."')";
					mysqli_query($enlaceCon,$sqlInsert);
													
			}
		}			
	
			////////////////////


		}
	}// fin while tallas

		
}
//////////////////////////////////
} // fin while colores
	if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_material.php?estado=-1&tipo=".$tipo."';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}

?>