<?php
require("conexionmysqli2.inc");
require("estilos.inc");
require("funciones.php");

error_reporting(E_ALL);
 ini_set('display_errors', '1');


//recogemos variables
$globalAgencia=$_COOKIE['global_agencia'];

$descripcion_material=$_POST['descripcion_material'];
$descripcion_material = strtoupper($descripcion_material);
$codigo2=$_POST['codigo2'];


$cod_grupo=$_POST['cod_grupo'];
$cod_subgrupo=$_POST['cod_subgrupo'];
$observaciones=$_POST['observaciones'];


$codUnidad=$_POST['cod_unidad'];

$fechaCreacion=date("Y-m-d-H-i-s");

$tipo=$_POST['tipo'];



$sql="select IFNULL((max(codigo_material)+1),1) as codigo from material_apoyo m";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$codigo_material=$dat[0];
$usuario=$_COOKIE['global_usuario'];

$sql_inserta="insert into material_apoyo(codigo_material,codigo2,descripcion_material,observaciones,cod_unidad,cod_subgrupo,creado_por,fecha_creacion,estado,cod_tipo) values ($codigo_material,'$codigo2','$descripcion_material','$observaciones','$codUnidad',$cod_subgrupo,'$usuario','$fechaCreacion','1','".$tipo."')";

$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);

	
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
				if($precio<>0){
					$sqlInsert="insert into precios (codigo_material,cod_precio,precio,cod_ciudad,cant_inicio,cant_final,
					created_by,created_date)values('".$codigo_material."','".$codGrupoPrecio."',
					'".$precio."','".$cod_ciudad."','".$cant_inicio."','".$cant_final."','".$_COOKIE['global_usuario']."','".$fechaCreacion."')";
					mysqli_query($enlaceCon,$sqlInsert);
					
				}	
			
			}
		}			
	
			////////////////////


if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_insumos.php?tipo=".$tipo."&estado=-1&grupo=".$cod_grupo."';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>