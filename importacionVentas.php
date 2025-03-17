<?php

set_time_limit(0);
require('conexion.inc');
require('funcionesImportacion.php');
require('funciones.php');
require('funciones_inventarios.php');

$codTipoSalida=1001;
$codTipoDoc=2;
$tiendaCargar="Principal";

$anioCarga=$_GET["anio"];

$sqlCab="select v.BranchName, DATE_FORMAT(v.BillDate, '%Y-%m-%d') from ventas_anterior v where v.BranchName='$tiendaCargar' and YEAR(v.BillDate)=$anioCarga 
GROUP BY year(v.BillDate), month(v.BillDate), day(v.BillDate), v.BranchName";
$respCab=mysql_query($sqlCab);
while($datCab=mysql_fetch_array($respCab)){
	$tienda=$datCab[0];
	$fecha=$datCab[1];
	
	if($tienda=="Principal"){
		$codAlmacen=1000;
	}
	if($tienda=="Sucursal 1"){
		$codAlmacen=1002;
	}
	if($tienda=="Sucursal Multicine"){
		$codAlmacen=1003;
	}
	if($tienda=="Sucursal San Miguel"){
		$codAlmacen=1004;
	}
	
	$codSalida=codigoSalida($codAlmacen);
	
	//echo $fecha.$codSalida."<br>";
	$sqlInsertCab="insert into salida_almacenes (cod_salida_almacenes, cod_almacen, cod_tiposalida, cod_tipo_doc, fecha, hora_salida, observaciones, estado_salida, nro_correlativo, salida_anulada) values ('$codSalida','$codAlmacen','$codTipoSalida','$codTipoDoc','$fecha','00:00:00','HISTORICO $tienda',
	'1','0','0')";
	//echo $sqlInsertCab."<br>";
	$respInsertCab=mysql_query($sqlInsertCab);
	
	$sqlDetalle="select ProductId, ProductQuantity, ProductPrice, DesctoTarjeta  from ventas_anterior v where v.BillDate BETWEEN '$fecha 00:00:00' and '$fecha 23:59:59' and v.BranchName='$tiendaCargar' GROUP BY ProductId;";
	$respDetalle=mysql_query($sqlDetalle);
	
	$indice=1;
	while($datDetalle=mysql_fetch_array($respDetalle)){
		$idProducto=$datDetalle[0];
		$idProductoNuevo=obtieneIdProducto($idProducto);
		
		$cantidadProducto=$datDetalle[1];
		$precioProducto=$datDetalle[2];
		$descuentoProducto=$datDetalle[3];
		
		$totalSinDescuento=$cantidadProducto*$precioProducto;
		$descuentoProducto=$totalSinDescuento-$descuentoProducto;
		
		$montoProducto=$totalSinDescuento-$descuentoProducto;
		
		$respuesta=insertar_detalleSalidaVenta($codSalida,$codAlmacen,$idProductoNuevo,$cantidadProducto,$precioProducto,$descuentoProducto,$montoProducto,0, $indice);
		
		echo $respuesta."<br>";
		$indice++;
	}
	
}

echo "terminado $anioCarga";

?>