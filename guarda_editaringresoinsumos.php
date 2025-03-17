<?php

require("conexionmysqli.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");


$codIngreso=$_POST["codIngreso"];
$tipo=$_POST["tipo"];
$estado=$_POST["estado"];
$tipo_ingreso=$_POST['tipo_ingreso'];
$nota_entrega=$_POST['nota_entrega'];
$nro_factura=$_POST['nro_factura'];
$observaciones=$_POST['observaciones'];
$codSalida=$_POST['codSalida'];
$fechaNotaRemision=$_POST['fechaNotaRemision'];

$global_agencia=$_COOKIE['global_agencia'];

$consulta="update ingreso_almacenes set cod_tipoingreso='$tipo_ingreso', 
		nro_factura_proveedor='$nro_factura', 
		observaciones='$observaciones',
		fecha='$fechaNotaRemision' 
		where cod_ingreso_almacen=$codIngreso";
$sql_inserta = mysqli_query($enlaceCon,$consulta);

//echo "aaaa:$consulta";

$sqlDel="delete from ingreso_detalle_almacenes where cod_ingreso_almacen=$codIngreso";
$respDel=mysqli_query($enlaceCon,$sqlDel);

for ($i = 1; $i <= $cantidad_material; $i++) {
	$cod_material = $_POST["material$i"];
    if($cod_material!=0){
		$cantidad=$_POST["cantidad_unitaria$i"];
		$lote=$_POST["lote$i"];
		//$fechaVenc=$_POST["fechaVenc$i"];
		$precioBruto=$_POST["precio$i"];
		
		$precioVenta=$_POST["precioVenta$i"];
		$precioVentaMayor=$_POST["precioVentaMayor$i"];
		
		$fechaVenc=UltimoDiaMes($fechaVenc);
		
		$consulta="insert into ingreso_detalle_almacenes (cod_ingreso_almacen, cod_material, cantidad_unitaria, cantidad_restante, lote, precio_bruto, costo_almacen, costo_actualizado, costo_actualizado_final, costo_promedio, precio_neto,precio_venta,precio_venta2) 
		values($codIngreso,'$cod_material',$cantidad,$cantidad,'$lote','$precioBruto','$precioBruto','$precioBruto','$precioBruto','$precioBruto','$precioBruto','$precioVenta','$precioVentaMayor')";
		
		//echo "bbb:$consulta";
		$sql_inserta2 = mysqli_query($enlaceCon,$consulta);
		//echo "valor de configuracion=".obtenerValorConfiguracion($enlaceCon,7);
			 if(obtenerValorConfiguracion($enlaceCon,7)==1){
				 				 
				//SACAMOS EL ULTIMO COSTO
							$sqlPrecioActual="select precio from precios where codigo_material='".$cod_material."' and cod_precio=0 
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
								$respPrecios=mysqli_query($enlaceCon,$sqlPrecios);
							}else{
									$sqlPrecios="update precios set precio='".$precioBruto."', modified_by='".$_COOKIE['global_usuario']."', modified_date='".$fechaHoy."'
									 where codigo_material='".$cod_material."' and cod_precio=0 and cod_ciudad='".$global_agencia."'";		
									 $respPrecios=mysqli_query($enlaceCon,$sqlPrecios);					
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
					  		$respPrecios=mysqli_query($enlaceCon,$sqlPrecios);
							}else{					
									$sqlPrecios="update precios set precio='$precioVenta',
									cant_inicio='".$cantIniPN."', cant_final='".$cantFinPN."', modified_by='".$_COOKIE['global_usuario']."', modified_date='".$fechaHoy."'
						 			where codigo_material='".$cod_material."' and cod_precio=1 and cod_ciudad='".$global_agencia."'";			
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
									$respPrecios=mysqli_query($enlaceCon,$sqlPrecios);
							}else{				
									$sqlPrecios="update precios set precio='".$precioVentaMayor."',
									cant_inicio='".$cantIniPM."', cant_final='".$cantFinPM."', modified_by='".$_COOKIE['global_usuario']."', modified_date='".$fechaHoy."' where codigo_material='".$cod_material."' and cod_precio=2 and cod_ciudad='".$global_agencia."'";
									$respPrecios=mysqli_query($enlaceCon,$sqlPrecios);					
							}
							// FIN PRECIO x MAYOR
				 
			} 
	}

}

	echo "<script language='Javascript'>
			Swal.fire('Los datos fueron modificados correctamente.')
		    .then(() => {
				location.href='navegador_ingresoinsumos.php?tipo=".$tipo."&estado=".$estado."';
		    });
		</script>";
			

?>