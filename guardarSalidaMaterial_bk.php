<?php
require("conexionmysqli.php");
require("estilos_almacenes.inc");
require("funciones.php");
require("funciones_inventarios.php");


$usuarioVendedor=$_POST['cod_vendedor'];
$globalSucursal=$_COOKIE['global_agencia'];

$tipoSalida=$_POST['tipoSalida'];
$tipoDoc=$_POST['tipoDoc'];
$almacenDestino=$_POST['almacen'];
$codCliente=$_POST['cliente'];

$tipoPrecio=$_POST['tipoPrecio'];
$tipoVenta=$_POST['tipoVenta'];
$razonSocial=$_POST['razonSocial'];
$nitCliente=$_POST['nitCliente'];

$observaciones=$_POST["observaciones"];
$almacenOrigen=$global_almacen;

$totalVenta=$_POST["totalVenta"];
$descuentoVenta=$_POST["descuentoVenta"];
$totalFinal=$_POST["totalFinal"];

$totalFinalRedondeado=round($totalFinal);

$fecha=$_POST["fecha"];
$cantidad_material=$_POST["cantidad_material"];

if($descuentoVenta=="" || $descuentoVenta==0){
	$descuentoVenta=0;
}

$vehiculo="";

//$fecha=date("Y-m-d");
$hora=date("H:i:s");

//SACAMOS LA CONFIGURACION PARA EL DOCUMENTO POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=1";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$tipoDocDefault=$datConf[0];
//$tipoDocDefault=mysql_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA EL CLIENTE POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=2";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$clienteDefault=$datConf[0];
//$clienteDefault=mysql_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=3";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$facturacionActivada=$datConf[0];
//$facturacionActivada=mysql_result($respConf,0,0);

$sqlConf="select valor_configuracion from configuraciones where id_configuracion=4";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$banderaValidacionStock=$datConf[0];
//$banderaValidacionStock=mysql_result($respConf,0,0);


$sql="SELECT IFNULL(max(cod_salida_almacenes)+1,1) FROM salida_almacenes";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$codigo=$dat[0];
//$codigo=mysql_result($resp,0,0);


$vectorNroCorrelativo=numeroCorrelativo($enlaceCon,$tipoDoc);
$nro_correlativo=$vectorNroCorrelativo[0];
$cod_dosificacion=$vectorNroCorrelativo[2];

if($facturacionActivada==1 && $tipoDoc==1){
		//SACAMOS DATOS DE LA DOSIFICACION PARA INSERTAR EN LAS FACTURAS EMITIDAS
	$sqlDatosDosif="select d.nro_autorizacion, d.llave_dosificacion 
		from dosificaciones d where d.cod_dosificacion='$cod_dosificacion'";
	$respDatosDosif=mysqli_query($enlaceCon,$sqlDatosDosif);
	$datDatosDosif=mysqli_fetch_array($respDatosDosif);
	$nroAutorizacion=$datDatosDosif[0];
	$llaveDosificacion=$datDatosDosif[1];
	
	//$nroAutorizacion=mysql_result($respDatosDosif,0,0);
	//$llaveDosificacion=mysql_result($respDatosDosif,0,1);
	include 'controlcode/sin/ControlCode.php';
	$controlCode = new ControlCode();
	$code = $controlCode->generate($nroAutorizacion,//Numero de autorizacion
								   $nro_correlativo,//Numero de factura
								   $nitCliente,//Número de Identificación Tributaria o Carnet de Identidad
								   str_replace('-','',$fecha),//fecha de transaccion de la forma AAAAMMDD
								   $totalFinalRedondeado,//Monto de la transacción
								   $llaveDosificacion//Llave de dosificación
								   );
	//FIN DATOS FACTURA
}


$sql_inserta="INSERT INTO `salida_almacenes`(`cod_salida_almacenes`, `cod_almacen`,`cod_tiposalida`, 
		`cod_tipo_doc`, `fecha`, `hora_salida`, `territorio_destino`, 
		`almacen_destino`, `observaciones`, `estado_salida`, `nro_correlativo`, `salida_anulada`, 
		`cod_cliente`, `monto_total`, `descuento`, `monto_final`, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado, cod_dosificacion,cod_tipopago)
		values ('$codigo', '$almacenOrigen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
		'$observaciones', '1', '$nro_correlativo', 0, '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial', 
		'$nitCliente', '$usuarioVendedor', '$vehiculo',0,'$cod_dosificacion','$tipoVenta')";
		echo $sql_inserta;
$sql_inserta=mysqli_query($enlaceCon,$sql_inserta);

if($sql_inserta==1){
	
	if($facturacionActivada==1){
		//insertamos la factura
		$sqlInsertFactura="insert into facturas_venta (cod_dosificacion, cod_sucursal, nro_factura, cod_estado, razon_social, nit, fecha, importe, 
		codigo_control, cod_venta) values ('$cod_dosificacion','$globalSucursal','$nro_correlativo','1','$razonSocial','$nitCliente','$fecha','$totalFinal',
		'$code','$codigo')";
		//echo $sqlInsertFactura;
		$respInsertFactura=mysqli_query($enlaceCon,$sqlInsertFactura);	
	}

	$montoTotalVentaDetalle=0;
	for($i=1;$i<=$cantidad_material;$i++)
	{   	
		$codMaterial=$_POST["materiales$i"];
		if($codMaterial!=0){
			$cantidadUnitaria=$_POST["cantidad_unitaria$i"];
			$precioUnitario=$_POST["precio_unitario$i"];
			$descuentoProducto=$_POST["descuentoProducto$i"];
			
			//SE DEBE CALCULAR EL MONTO DEL MATERIAL POR CADA UNO PRECIO*CANTIDAD - EL DESCUENTO ES UN DATO ADICIONAL
			$montoMaterial=$precioUnitario*$cantidadUnitaria;
			$montoMaterialConDescuento=($precioUnitario*$cantidadUnitaria)-$descuentoProducto;
			
			
			$montoTotalVentaDetalle=$montoTotalVentaDetalle+$montoMaterialConDescuento;
			if($banderaValidacionStock==1){
				//echo "descontando aca";
				$respuesta=descontar_inventarios($enlaceCon,$codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$i);
			}else{
				$respuesta=insertar_detalleSalidaVenta($enlaceCon,$codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$banderaValidacionStock, $i);
			}
	
			if($respuesta!=1){
				echo "<script>
					alert('Existio un error en el detalle. Contacte con el administrador del sistema.');
				</script>";
			}
		}			
	}
	
	$montoTotalConDescuento=$montoTotalVentaDetalle-$descuentoVenta;
	//ACTUALIZAMOS EL PRECIO CON EL DETALLE
	$sqlUpdMonto="update salida_almacenes set monto_total='$montoTotalVentaDetalle', monto_final='$montoTotalConDescuento' 
				where cod_salida_almacenes='$codigo'";
	$respUpdMonto=mysqli_query($enlaceCon,$sqlUpdMonto);
	if($facturacionActivada==1){
		$sqlUpdMonto="update facturas_venta set importe='$montoTotalConDescuento' 
					where cod_venta='$codigo'";
		$respUpdMonto=mysqli_query($enlaceCon,$sqlUpdMonto);
	}
	//echo "tipoSalida=".$tipoSalida;
	//echo "tipoDoc=".$tipoDoc;
	if($tipoSalida==1001){
		if($tipoDoc==1){
			echo "<script type='text/javascript' language='javascript'>
			location.href='formatoFactura2.php?codVenta=$codigo';
			</script>";
			
		}else{
			echo "<script type='text/javascript' language='javascript'>
			location.href='formatoNotaRemision2.php?codVenta=$codigo';
			</script>";

		}
	}else{	
		echo "<script type='text/javascript' language='javascript'>
			location.href='navegador_salidamateriales.php';
		</script>";
	}

	
}else{
		echo "<script type='text/javascript' language='javascript'>
			alert('Ocurrio un error en la transaccion. Contacte con el administrador del sistema.');
			location.href='navegador_salidamateriales.php';
		</script>";
}

?>



