<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");

error_reporting(E_ALL);
 ini_set('display_errors', '1');

$tipo=$_POST['tipo'];
$fechaNotaRemision=$_POST['fechaNotaRemision'];

//echo "fechaNotaRemision".$fechaNotaRemision;
$global_agencia=$_COOKIE['global_agencia'];
$global_almacen=$_COOKIE['global_almacen'];
$cantidad_material=$_POST['cantidad_material'];

$sql = "select IFNULL(MAX(cod_ingreso_almacen)+1,1) from ingreso_almacenes order by cod_ingreso_almacen desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$codigo=$dat[0];


$sql = "select IFNULL(MAX(nro_correlativo)+1,1) from ingreso_almacenes where cod_almacen='$global_almacen' and cod_tipo=".$tipo." order by cod_ingreso_almacen desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$nro_correlativo=$dat[0];


$hora_sistema = date("H:i:s");

$tipo_ingreso=$_POST['tipo_ingreso'];
$nota_entrega=0;
$nro_factura=$_POST['nro_factura'];
$observaciones=$_POST['observaciones'];
$proveedor=$_POST['proveedor'];

$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d H:i:s");

//$fecha_real=date("Y-m-d");

if($tipo_ingreso==1002){
	$codSalida=$_POST['cod_salida'];
	$estadoSalida=4;//recepcionado
	$sqlCambiaEstado="update salida_almacenes set estado_salida='$estadoSalida' where cod_salida_almacenes=$codSalida";
	$respCambiaEstado=mysqli_query($enlaceCon,$sqlCambiaEstado);
}

//echo "paso 0 query cabecera";

$consulta="insert into ingreso_almacenes (cod_ingreso_almacen,cod_almacen,cod_tipoingreso,fecha,hora_ingreso,observaciones,
nota_entrega,nro_correlativo,ingreso_anulado,cod_tipo_compra,cod_orden_compra,nro_factura_proveedor,factura_proveedor,estado_liquidacion,
cod_proveedor,created_by,modified_by,created_date,modified_date,cod_tipo) 
values($codigo,$global_almacen,$tipo_ingreso,'$fechaNotaRemision','$hora_sistema','$observaciones','$nota_entrega','$nro_correlativo',1,0,0,$nro_factura,0,0,'$proveedor','$createdBy','0','$createdDate','',$tipo)";
$sql_inserta = mysqli_query($enlaceCon,$consulta);
//echo "paso 1 sql_inserta".$sql_inserta;
if($sql_inserta==1){
   $valorExcel=0;
		if(isset($_POST["tipo_submit"])){
			$valorExcel=$_POST["tipo_submit"];
		} 
		if($valorExcel=="1"){
      include "subirDatosExcel.php";

			echo "<script language='Javascript'>
				alert('".$mensaje."');
				location.href='navegador_ingresomateriales.php';
			</script>";	
 		}else{
      for ($i = 1; $i <= $cantidad_material; $i++) {
				$cod_material = $_POST["material$i"];
		
				if($cod_material!=0){
						$cantidad=$_POST["cantidad_unitaria$i"];
						$precioBruto=$_POST["precio$i"];
						$precioVenta=$_POST["precioVenta$i"];
						$precioVentaMayor=$_POST["precioVentaMayor$i"];
						if (isset($_POST["lote$i"])){
						$lote=$_POST["lote$i"];	
						}		
						$fechaVencimiento='1900-01-01';
						$precioUnitario=$precioBruto;			
						$costo=$precioUnitario;			
						$consulta="insert into ingreso_detalle_almacenes(cod_ingreso_almacen, cod_material, cantidad_unitaria, cantidad_restante, lote, fecha_vencimiento, 
						precio_bruto, costo_almacen, costo_actualizado, costo_actualizado_final, costo_promedio, precio_neto,precio_venta,precio_venta2,orden_detalle) 
					values('$codigo','$cod_material','$cantidad','$cantidad','$lote','$fechaVencimiento','$precioUnitario','$precioUnitario','$costo','$costo','$costo','$costo','$precioVenta','$precioVentaMayor',$i)";
				
						$respuestaConsulta = mysqli_query($enlaceCon,$consulta);
						$fechaHoy=$fechaNotaRemision." ".$hora_sistema;
			 			if(obtenerValorConfiguracion($enlaceCon,7)==1){				 				 				 		
				 			//SACAMOS EL ULTIMO COSTO
							$sqlPrecioActual="select precio from precios where codigo_material='$cod_material' and cod_precio=0 
							and cod_ciudad='".$global_agencia."'";
							$respPrecioActual=mysqli_query($enlaceCon,$sqlPrecioActual);
							$numFilasPrecios=mysqli_num_rows($respPrecioActual);
							$precioActual=0;				
							if($numFilasPrecios>0){
								$datPrecioActual=mysqli_fetch_array($respPrecioActual);
								$precioActual=$datPrecioActual[0];
							}
							if($numFilasPrecios==0){
									$sqlPrecios="insert into precios (codigo_material, cod_precio, precio,cod_ciudad,created_by,created_date) values('".$cod_material."','0','".$precioBruto."','".$global_agencia."','".$_COOKIE['global_usuario']."','".$fechaHoy."')";
									echo $sqlPrecios;					
								$respPrecios=mysqli_query($enlaceCon,$sqlPrecios);
							}else{
									$sqlPrecios="update precios set precio='".$precioBruto."', modified_by='".$_COOKIE['global_usuario']."', modified_date='".$fechaHoy."'
									 where codigo_material='".$cod_material."' and cod_precio=0 and cod_ciudad='".$global_agencia."'";		
									 $respPrecios=mysqli_query($enlaceCon,$sqlPrecios);
									 echo $sqlPrecios;					
							}			
							// FIN COSTO
							$cantIniPN=1;$cantFinPN=5;		 
							//SACAMOS EL ULTIMO PRECIO REGISTRADO PRECIO NORMAL
							$sqlPrecioActual="select precio from precios where codigo_material='$cod_material' and cod_precio=1 
							and cod_ciudad='".$global_agencia."'";
							$respPrecioActual=mysqli_query($enlaceCon,$sqlPrecioActual);
							$numFilasPrecios=mysqli_num_rows($respPrecioActual);
							$precioActual=0;				
							if($numFilasPrecios>0){
									$datPrecioActual=mysqli_fetch_array($respPrecioActual);
									$precioActual=$datPrecioActual[0];
							}
							if($numFilasPrecios==0){
								$sqlPrecios="insert into precios (codigo_material, cod_precio, precio,cod_ciudad,cant_inicio,cant_final,created_by,created_date) values('".$cod_material."','1','".$precioVenta."','".$global_agencia."','".$cantIniPN."','".$cantFinPN."','".$_COOKIE['global_usuario']."','".$fechaHoy."')";
								echo $sqlPrecios;
					  		$respPrecios=mysqli_query($enlaceCon,$sqlPrecios);
							}else{					
									$sqlPrecios="update precios set precio='$precioVenta',
									cant_inicio='".$cantIniPN."', cant_final='".$cantFinPN."', modified_by='".$_COOKIE['global_usuario']."', modified_date='".$fechaHoy."'
						 			where codigo_material='".$cod_material."' and cod_precio=1 and cod_ciudad='".$global_agencia."'";
						 			echo $sqlPrecios;			
						 			$respPrecios=mysqli_query($enlaceCon,$sqlPrecios);					
							}			
							// FIN PRECIO NORMAL
							//SACAMOS EL ULTIMO PRECIO REGISTRADO PRECIO X MAYOR
							$cantIniPM=6;
			 				$cantFinPM=1000;
							$sqlPrecioActual="select precio from precios where codigo_material='$cod_material' and cod_precio=2 and cod_ciudad='".$global_agencia."'";
							$respPrecioActual=mysqli_query($enlaceCon,$sqlPrecioActual);
							$numFilasPrecios=mysqli_num_rows($respPrecioActual);
							$precioActual=0;
							if($numFilasPrecios>0){
								$datPrecioActual=mysqli_fetch_array($respPrecioActual);
								$precioActual=$datPrecioActual[0];
							}
							if($numFilasPrecios==0){
								$sqlPrecios="insert into precios (codigo_material, cod_precio, precio,cod_ciudad,cant_inicio,cant_final,created_by,created_date) values('".$cod_material."','2','".$precioVentaMayor."','".$global_agencia."','".$cantIniPM."','".$cantFinPM."','".$_COOKIE['global_usuario']."','".$fechaHoy."')";
								echo $sqlPrecios;
									$respPrecios=mysqli_query($enlaceCon,$sqlPrecios);
							}else{				
									$sqlPrecios="update precios set precio='".$precioVentaMayor."',
									cant_inicio='".$cantIniPM."', cant_final='".$cantFinPM."', modified_by='".$_COOKIE['global_usuario']."', modified_date='".$fechaHoy."' where codigo_material='".$cod_material."' and cod_precio=2 and cod_ciudad='".$global_agencia."'";
									echo $sqlPrecios;
									$respPrecios=mysqli_query($enlaceCon,$sqlPrecios);					
							}
							// FIN PRECIO x MAYOR
						} 			
	 		  }//IF MATERIAL
	  	}// FIN FOR
	  	//echo "inserto todos los materiales";
		}

	//echo "Los datos fueron insertados correctamente";
echo "<script language='Javascript'>
		alert('Los datos fueron insertados correctamente.');
		location.href='navegador_ingresoinsumos.php?tipo=".$tipo."&estado=-1' </script>";
}else{

	//	echo "EXISTIO UN ERROR EN LA TRANSACCION, POR FAVOR CONTACTE CON EL ADMINISTRADOR.";


	echo "<script language='Javascript'>
		alert('EXISTIO UN ERROR EN LA TRANSACCION, POR FAVOR CONTACTE CON EL ADMINISTRADOR.');
		location.href='navegador_ingresoinsumos.php?tipo=".$tipo."&estado=-1' </script>";
}

?>