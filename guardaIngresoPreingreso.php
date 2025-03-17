<?php

require("conexionmysqli.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");

//echo $_GET['codigoPreingreso'];

$global_agencia=$_COOKIE['global_agencia'];

$sql = "select IFNULL(MAX(cod_ingreso_almacen)+1,1) from ingreso_almacenes order by cod_ingreso_almacen desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$codigo=$dat[0];


$sql = "select IFNULL(MAX(nro_correlativo)+1,1) from ingreso_almacenes where cod_almacen='$global_almacen' order by cod_ingreso_almacen desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$nro_correlativo=$dat[0];

$hora_sistema = date("H:i:s");

$nota_entrega=0;

$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d H:i:s");

$fecha_real=date("Y-m-d");

	$sqlAux=" select IFNULL(codigo_ingreso,0) from  preingreso_almacenes  where cod_ingreso_almacen=".$_GET['codigoPreingreso'];
	$respAux= mysqli_query($enlaceCon,$sqlAux);
	$datAux=mysqli_fetch_array($respAux);
	if($datAux[0]==0){

$consulta="insert into ingreso_almacenes (cod_ingreso_almacen,cod_almacen,cod_tipoingreso,fecha,hora_ingreso,observaciones,cod_salida_almacen,
nota_entrega,nro_correlativo,ingreso_anulado,cod_tipo_compra,cod_orden_compra,nro_factura_proveedor,factura_proveedor,estado_liquidacion,
cod_proveedor,created_by,modified_by,created_date,modified_date) 
select $codigo,$global_almacen,cod_tipoingreso,'$fecha_real','$hora_sistema',observaciones,
cod_salida_almacen,nota_entrega,$nro_correlativo,ingreso_anulado,0,0,nro_factura_proveedor,0,0,cod_proveedor,'$createdBy','0','$createdDate',''
from preingreso_almacenes where  cod_ingreso_almacen=".$_GET['codigoPreingreso'];


$resp_inserta = mysqli_query($enlaceCon,$consulta);


if($resp_inserta==1){
	
	$sql1=" update  preingreso_almacenes set codigo_ingreso=$codigo where cod_ingreso_almacen=".$_GET['codigoPreingreso'];
	mysqli_query($enlaceCon,$sql1);
	
		$sql2="select cod_material, cantidad_unitaria, cantidad_restante, lote, fecha_vencimiento,precio_bruto, costo_almacen, costo_actualizado,
		costo_actualizado_final, costo_promedio, precio_neto,precio_venta from preingreso_detalle_almacenes where cod_ingreso_almacen=".$_GET['codigoPreingreso'];
		$resp2 = mysqli_query($enlaceCon,$sql2);
		while ($dat2 = mysqli_fetch_array($resp2)) {
			
			$cod_material= $dat2['cod_material'];
			$cantidad_unitaria=$dat2['cantidad_unitaria'];
			$cantidad_restante= $dat2['cantidad_restante'];
			$lote=$dat2['cantidad_restante'];
			$fecha_vencimiento=$dat2['fecha_vencimiento'];
			$precio_bruto=$dat2['precio_bruto'];
			$costo_almacen= $dat2['costo_almacen'];
			$costo_actualizado=$dat2['costo_actualizado'];
			$costo_actualizado_final=$dat2['costo_actualizado_final'];
			$costo_promedio= $dat2['costo_promedio'];
			$precio_neto=$dat2['precio_neto'];
			$precioVenta=$dat2['precio_venta'];
			
			$sql3="insert into ingreso_detalle_almacenes(cod_ingreso_almacen, cod_material, cantidad_unitaria, cantidad_restante, 
		lote, fecha_vencimiento,precio_bruto, costo_almacen, costo_actualizado, costo_actualizado_final, costo_promedio, precio_neto) values('$codigo',
		'$cod_material','$cantidad_unitaria', '$cantidad_restante', '$lote', '$fecha_vencimiento','$precio_bruto', '$costo_almacen', '$costo_actualizado',
		'$costo_actualizado_final', '$costo_promedio', '$precio_neto')";
		mysqli_query($enlaceCon,$sql3);
		
					
	  //echo $sql3;
			
			$sqlMargen="select p.margen_precio from material_apoyo m, proveedores_lineas p
				where m.cod_linea_proveedor=p.cod_linea_proveedor and m.codigo_material='$cod_material'";
			$respMargen=mysqli_query($enlaceCon,$sqlMargen);
			$numFilasMargen=mysqli_num_rows($respMargen);
			$porcentajeMargen=0;
			if($numFilasMargen>0){
				$datMargen=mysqli_fetch_array($respMargen);
				$porcentajeMargen=$datMargen[0];
				
			}		
			$precioItem=$costo+($costo*($porcentajeMargen/100));
			$aa=recalculaCostos($enlaceCon,$cod_material,$global_almacen);
		//Preguntamos si se modificara el Precio de Venta
			//echo "valor de configuracion=".obtenerValorConfiguracion($enlaceCon,7);
			if($precioVenta!=""){
				//echo "Ingresa modificar";
			 if(obtenerValorConfiguracion($enlaceCon,7)==1){
				 				 
				//SACAMOS EL ULTIMO PRECIO REGISTRADO
				$sqlPrecioActual="select precio from precios where codigo_material='$cod_material' and cod_precio=1 and cod_ciudad='".$global_agencia."'";
						
				//echo $sqlPrecioActual;
				$respPrecioActual=mysqli_query($enlaceCon,$sqlPrecioActual);
				$numFilasPrecios=mysqli_num_rows($respPrecioActual);
				$precioActual=0;
				//echo "numFilasPrecios=".$numFilasPrecios."<br>";
				if($numFilasPrecios>0){
					$datPrecioActual=mysqli_fetch_array($respPrecioActual);
					$precioActual=$datPrecioActual[0];
					//$precioActual=mysql_result($enlaceCon,$respPrecioActual,0,0);
				}
			
					//echo "precio +margen: ".$precioItem." precio actual: ".$precioActual;
					//SI NO EXISTE EL PRECIO LO INSERTA CASO CONTRARIO VERIFICA QUE EL PRECIO DEL INGRESO SEA MAYOR AL ACTUAL PARA HACER EL UPDATE
				if($numFilasPrecios==0){
					$sqlPrecios="insert into precios (codigo_material, cod_precio, precio,cod_ciudad) values('$cod_material','1','$precioVenta','".$global_agencia."')";
					//echo $sqlPrecios;
					$respPrecios=mysqli_query($enlaceCon,$sqlPrecios);
				}else{
					//if($precioItem>$precioActual){
						$sqlPrecios="update precios set precio='$precioVenta' where codigo_material='$cod_material' and cod_precio=1 and cod_ciudad='".$global_agencia."'";
						//echo $sqlPrecios;
						$respPrecios=mysqli_query($enlaceCon,$sqlPrecios);
					//}
				}			
				 
			} 
			} 
			
		
		}


		echo "<script language='Javascript'>
			Swal.fire('Los datos fueron insertados correctamente.')
		    .then(() => {
				location.href='navegador_ingresomateriales.php';
		    });
		</script>";

    	
}else{
			echo "<script language='Javascript'>
			Swal.fire('EXISTIO UN ERROR EN LA TRANSACCION, POR FAVOR CONTACTE CON EL ADMINISTRADOR.')
		    .then(() => {
				location.href='navegador_preingreso.php';
		    });
		</script>";

}
}else{
		echo "<script language='Javascript'>
			Swal.fire('YA SE GENERO EL INGRESO.')
		    .then(() => {
				location.href='navegador_preingreso.php';
		    });
		</script>";

}
?>