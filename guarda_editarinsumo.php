<?php
require("conexionmysqli.php");
require("estilos.inc");
require("funciones.php");

//recogemos variables
$globalAgencia=$_COOKIE['global_agencia'];

$codigo_material=$_POST['codigo_material'];
$descripcion_material=$_POST['descripcion_material'];
$descripcion_material = strtoupper($descripcion_material);
$codigo2=$_POST['codigo2'];


$cod_grupo=$_POST['cod_grupo'];
$cod_subgrupo=$_POST['cod_subgrupo'];
$observaciones=$_POST['observaciones'];

$codUnidad=$_POST['cod_unidad'];

$tipo=$_POST['tipo'];


$sql_inserta="update material_apoyo set 
codigo2='$codigo2',
 descripcion_material='$descripcion_material', 
 observaciones='$observaciones',
cod_subgrupo='$cod_subgrupo', 
cod_unidad='$codUnidad'
where codigo_material='$codigo_material'";

$resp_inserta=mysqli_query($enlaceCon,$sql_inserta);

$sqlDelete="delete from precios 
where codigo_material='".$codigo_material."' and cod_ciudad='".$globalAgencia."' ";
mysqli_query($enlaceCon,$sqlDelete);
////////////////////////

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
////////////////////////


if($resp_inserta){
		echo "<script language='Javascript'>
			alert('Los datos fueron guardados correctamente.');
			location.href='navegador_insumos.php?tipo=".$tipo."&estado=-1&grupo=".$cod_grupo."';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>