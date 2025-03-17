<?php

set_time_limit(0);
require('conexion.inc');
require('funcionesImportacion.php');

$sqlDel="delete from marcas";
$respDel=mysql_query($sqlDel);

$sqlDel="delete from subgrupos";
$respDel=mysql_query($sqlDel);

$sqlDel="delete from grupos";
$respDel=mysql_query($sqlDel);

$sqlDel="delete from precios";
$respDel=mysql_query($sqlDel);

$sqlDel="delete from material_apoyo";
$respDel=mysql_query($sqlDel);

$sqlRef="select distinct(i.marca) from importacion_productos i";
$respRef=mysql_query($sqlRef);
while($datRef=mysql_fetch_array($respRef)){
	$nombre=$datRef[0];
	$codigoMarca=insertaMarca($nombre);
}

$sqlRef="select distinct(i.grupo) from importacion_productos i";
$respRef=mysql_query($sqlRef);
while($datRef=mysql_fetch_array($respRef)){
	$nombre=$datRef[0];
	//echo $nombre."<br>";
	list($subgrupo,$grupo) = explode("-",$nombre);
	$subgrupo=strtoupper($subgrupo);
	$grupo=strtoupper($grupo);
	//echo $subgrupo." ".$grupo."<br>"; 
	if($grupo==""){$grupo=$subgrupo;}
	
	$codigoGrupo=insertaGrupo($grupo);
	$codigoSubGrupo=insertaSubGrupo($codigoGrupo, $subgrupo);

}


$sql="select id, id_anterior, barcode, nombre, color, talla, marca, precio, costo, grupo from importacion_productos";
$resp=mysql_query($sql);

$indice=1;
while($dat=mysql_fetch_array($resp)){
	$idNuevo=$dat[0];
	$idAnterior=$dat[1];
	$barCode=$dat[2];
	$nombreItem=$dat[3];
	$colorItem=$dat[4];
	$tallaItem=$dat[5];
	$marcaItem=$dat[6];
	$precioItem=$dat[7];
	$costoItem=$dat[8];
	$grupoItem=$dat[9];
	$descripcionItem=$nombreItem;
	
	$codigoMarca=insertaMarca($marcaItem);
	list($codSubgrupoItem,$codGrupoItem) = explode("-",$grupoItem);
	$codigoGrupo=insertaGrupo($codGrupoItem);
	$codigoSubGrupo=insertaSubGrupo($codigoGrupo,$codSubgrupoItem);
	
	
	$idCreacionItem=crearProducto($idNuevo, $barCode, $nombreItem, $codigoMarca, $codigoGrupo, $codigoSubGrupo, $tallaItem, $colorItem, $descripcionItem, $idAnterior,$precioItem);	
	
	echo $idCreacionItem." ".$indice."<br>";
	$indice++;
}

?>