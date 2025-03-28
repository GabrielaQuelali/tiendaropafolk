<?php

require('conexionmysqli.php');


function insertaMarca($marca){
	$marca=ucwords($marca);
	
	$sql="select count(*), codigo from marcas where nombre='$marca' ";
	$resp=mysqli_query($enlaceCon,$sql);
	$contador=mysqli_num_rows($resp);
	
	//$contador=mysql_result($resp,0,0);
	$codigoDevolver=0;
	if($contador>0){
		$dat=mysqli_fetch_array($resp);
		$codigoDevolver=$dat[1];
		//$codigoDevolver=mysql_result($resp,0,1);
	}
	if($contador==0){
		$sqlInserta="insert into marcas (nombre, abreviatura, estado) values ('$marca','$marca','1')";
		//echo $sqlInserta;
		$respInserta=mysqli_query($enlaceCon,$sqlInserta);
		$codigoDevolver=mysqli_insert_id();
	}
	return ($codigoDevolver);
}

function insertaGrupo($grupo){
	$grupo=ucwords($grupo);
	
	if($grupo=="CONJUNTO"){$grupo="CONJUNTOS";}
	if($grupo=="MEDIA"){$grupo="MEDIAS";}
	if($grupo=="CALZA"){$grupo="CALZAS";}
	if($grupo=="CALZONCILLO"){$grupo="CALZONCILLO";}
	
	
	$sql="select count(*), codigo from grupos where nombre='$grupo' ";
	$resp=mysqli_query($enlaceCon,$sql);
	$contador=mysqli_num_rows($resp);
	//$contador=mysql_result($resp,0,0);
	$codigoDevolver=0;
	if($contador>0){
		$dat=mysqli_fetch_array($resp);
		$codigoDevolver=$dat[1];
		//$codigoDevolver=mysql_result($resp,0,1);
	}
	if($contador==0){
		$sqlInserta="insert into grupos (nombre, abreviatura, estado) values ('$grupo','$grupo','1')";
		//echo $sqlInserta;
		$respInserta=mysqli_query($enlaceCon,$sqlInserta);
		$codigoDevolver=mysqli_insert_id();
	}
	return ($codigoDevolver);
}

function insertaSubGrupo($codigoGrupo, $subgrupo){
	$subgrupo=ucwords($subgrupo);
	
	$sql="select count(*), codigo from subgrupos where nombre='$subgrupo' and cod_grupo='$codigoGrupo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$contador=mysqli_num_rows($resp);
	//$contador=mysql_result($resp,0,0);
	$codigoDevolver=0;
	if($contador>0){
		$dat=mysqli_fetch_array($resp);
		$codigoDevolver=$dat[1];
		//$codigoDevolver=mysql_result($resp,0,1);
	}
	if($contador==0){
		$sqlInserta="insert into subgrupos (nombre, abreviatura, estado, cod_grupo) values ('$subgrupo','$subgrupo','1','$codigoGrupo')";
		$respInserta=mysqli_query($enlaceCon,$sqlInserta);
		$codigoDevolver=mysqli_insert_id();
	}
	return ($codigoDevolver);
}

function devuelveIdGrupo($enlaceCon,$idSubGrupo){
	$sql="select count(*), s.cod_grupo from subgrupos s where s.codigo='$idSubGrupo'";
	$resp=mysqli_query($enlaceCon,$sql);
	$contador=mysqli_num_rows($resp);
	//$contador=mysql_result($resp,0,0);
	$codigoDevolver=0;
	if($contador>0){
		$dat=mysqli_fetch_array($resp);
		$codigoDevolver=$dat[1];
		//$codigoDevolver=mysql_result($resp,0,1);
	}
	return($codigoDevolver);
}

function devuelveIdProducto($enlaceCon,$barCode, $nombreItem, $codMarca, $codSubGrupo, $color, $talla, $descripcionItem, $precioItem, $imagen, $precio,$codigoExterno){
	$contador=0;
	$codigoDevolver=0;
		//echo $barCode."codigo externo".$codigoExterno;
	if(!empty($$barCode)){
		//$sql="select count(*), m.codigo_material from material_apoyo m where m.codigo_barras='$barCode'";
		$sql="select  m.codigo_material from material_apoyo m where m.codigo_barras='$barCode'";
		//echo $sql;
		$resp=mysqli_query($enlaceCon,$sql);
		$contador=mysqli_num_rows($resp);
		//$contador=mysql_result($resp,0,0);
		$codigoDevolver=0;
		if($contador>0){
			$dat=mysqli_fetch_array($resp);
			$codigoDevolver=$dat[0];
			//$codigoDevolver=mysql_result($resp,0,1);
		}
	}
	if(!empty($codigoExterno)){
		//$sql="select count(*), m.codigo_material from material_apoyo m where m.codigo2='$codigoExterno'";
		$sql="select m.codigo_material from material_apoyo m where m.codigo2='$codigoExterno'";
		//echo $sql."<br>";
		$resp=mysqli_query($enlaceCon,$sql);
		$contador=mysqli_num_rows($resp);
		//$contador=mysql_result($resp,0,0);
		$codigoDevolver=0;
			if($contador>0){
				$dat=mysqli_fetch_array($resp);
				$codigoDevolver=$dat[0];
				//$codigoDevolver=mysql_result($resp,0,1);
			}
	}
 
	if($contador==0){
		$codGrupo=devuelveIdGrupo($enlaceCon,$codSubGrupo);
		$codigoDevolver=crearProducto($enlaceCon,0, $barCode, $nombreItem, $codMarca, $codGrupo, $codSubGrupo, $talla, $color, $descripcionItem, 0, $precioItem, $imagen, $precio,$codigoExterno);
	}
	return ($codigoDevolver);
}

function crearProducto($enlaceCon,$idNuevo, $barCode, $nombreItem, $codMarca, $codGrupo, $codSubGrupo, $tallaItem, $colorItem, $descripcionItem, $idAnterior, $precioItem, $imagen, $precio,$codigoExterno){
	$estadoItem=1;
	$lineaProveedorItem=1;
	$codTipoMaterial=1;
	$cantidadPresentacion=1;
	$codUnidad=1;
	
	$nombreItem=strtoupper($nombreItem);
	$colorItem=ucwords($colorItem);
	$tallaItem=ucwords($tallaItem);
	
	if($idNuevo==0){
		$sqlAuxMax="select count(*) from material_apoyo";
		$respAuxMax=mysqli_query($enlaceCon,$sqlAuxMax);
		$datAuxMax=mysqli_fetch_array($respAuxMax);
		if($datAuxMax[0]==0){
			$idNuevo=1;
		}else {
			$sqlMax="select max(codigo_material)+1 from material_apoyo";
			//echo $sqlMax;
			$respMax=mysqli_query($enlaceCon,$sqlMax);
			$datMax=mysqli_fetch_array($respMax);
			$idNuevo=$datMax[0];
		}
		//$idNuevo=mysql_result($respMax,0,0);
	}
	
	if($idAnterior==0){
		$sqlAuxMax="select count(*) from material_apoyo";
		$respAuxMax=mysqli_query($enlaceCon,$sqlAuxMax);
		$datAuxMax=mysqli_fetch_array($respAuxMax);
		if($datAuxMax[0]==0){
			$idAnterior=1;
		}else {
			$sqlMax="select max(codigo_anterior)+1 from material_apoyo";
			//echo $sqlMax;
			$respMax=mysqli_query($enlaceCon, $sqlMax);
			$datMax=mysqli_fetch_array($respMax);
			$idAnterior=$datMax[0];
		}
		//$idAnterior=mysql_result($respMax,0,0);
	}
	
	$sqlInsertItem="insert into material_apoyo (codigo_material, descripcion_material, estado, cod_linea_proveedor, cod_grupo, cod_tipomaterial,
	cantidad_presentacion, observaciones, imagen, cod_unidad, cod_subgrupo, cod_marca, codigo_barras, talla,
	color, codigo_anterior,codigo2) values 
	('$idNuevo','$nombreItem','$estadoItem','$lineaProveedorItem','$codGrupo','$codTipoMaterial','$cantidadPresentacion','$descripcionItem','$imagen','$codUnidad','$codSubGrupo','$codMarca','$barCode','$tallaItem','$colorItem',
	'$idAnterior','$codigoExterno')";
	//echo $sqlInsertItem."<br>";
	$respInsertItem=mysqli_query($enlaceCon, $sqlInsertItem);
	
	
	$sqlDelPrecio="delete from precios where codigo_material='$idNuevo'";
	$respDelPrecio=mysqli_query($enlaceCon, $sqlDelPrecio);
	
	$sqlInsertPrecio="insert into precios (codigo_material, cod_precio, precio, cod_ciudad) values ('$idNuevo','0','$precioItem','1')";
	$respInsertPrecio=mysqli_query($enlaceCon, $sqlInsertPrecio);
	
	$sqlInsertPrecio="insert into precios (codigo_material, cod_precio, precio, cod_ciudad) values ('$idNuevo','1','$precio','1')";
	$respInsertPrecio=mysqli_query($enlaceCon, $sqlInsertPrecio);

	return($idNuevo);
	
}

?>