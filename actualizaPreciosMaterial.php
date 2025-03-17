<?php
require("conexionmysqli.php");
require("estilos.inc");

//recogemos variables
$codProducto=$_POST['codProducto'];
$nombreProducto=$_POST['nombreProducto'];

$tipo=$_POST['tipo'];
$estado=$_POST['estado'];



$sqlCiu="select cod_ciudad,descripcion as desc_ciudad,nombre_ciudad 
		from ciudades order by cod_ciudad asc";

		$respCiu=mysqli_query($enlaceCon,$sqlCiu);
		
		while($datCiu=mysqli_fetch_array($respCiu)){

			$cod_ciudad=$datCiu['cod_ciudad'];
			$desc_ciudad=$datCiu['desc_ciudad'];
			$nombre_ciudad=$datCiu['nombre_ciudad'];		

		$sqlGrupoPrecio="select codigo,nombre from grupos_precio where estado=1 order by codigo asc";

		$respGrupoPrecio=mysqli_query($enlaceCon,$sqlGrupoPrecio);
		
			while($datGrupoPrecio=mysqli_fetch_array($respGrupoPrecio)){

				$codGrupoPrecio=$datGrupoPrecio['codigo'];

				$precio=$_POST['precio'.$cod_ciudad.$codGrupoPrecio];
				$cant_ini=$_POST['cant_ini'.$cod_ciudad.$codGrupoPrecio];
				$cant_fin=$_POST['cant_fin'.$cod_ciudad.$codGrupoPrecio];

				if($precio>0){

					$sqlDelete="delete from precios 
					where codigo_material='".$codProducto."' and cod_precio='".$codGrupoPrecio."'  
					and cod_ciudad='".$cod_ciudad."' ";
					mysqli_query($enlaceCon,$sqlDelete);

					$fechaCreacion=date("Y-m-d-H-i-s");
					$sqlInsert="insert into precios (codigo_material,cod_precio,precio,cod_ciudad,cant_inicio,cant_final,
					created_by,created_date)values('".$codProducto."','".$codGrupoPrecio."',
					'".$precio."','".$cod_ciudad."','".$cant_ini."','".$cant_fin."','".$_COOKIE['global_usuario']."','".$fechaCreacion."')";
					mysqli_query($enlaceCon,$sqlInsert);

					//echo $sqlInsert."<br/>";
				}


			}
		}




/*if($respUpd){
		echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_material.php';
			</script>";
}else{*/
	echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='listaPrecios.php?codigo=".$codProducto."&nombre=".$nombreProducto."&tipo=".$tipo."&estado=".$estado."'";
			
			echo"</script>";
//}
	

?>