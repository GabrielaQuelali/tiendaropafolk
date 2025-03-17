<?php
require("conexionmysqli2.inc");
require("estilos_almacenes.inc");
require("funciones.php");
require("funciones_inventarios.php");


 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$flagSuccessIngresos=0;
$flagSuccessSalidas=0;
$codigoVenta=$_POST["codVenta"];

$sql = "select nro_correlativo from salida_almacenes where cod_salida_almacenes='$codigoVenta'";
$resp = mysqli_query($enlaceCon, $sql);
$numeroFacturaAnteriorVenta=0;
if($datX=mysqli_fetch_array($resp)){
	$numeroFacturaAnteriorVenta=$datX[0];	
}


$cantidad_material=0;
$observacionesCambio="POR CAMBIO DE ITEM F/NR :".$numeroFacturaAnteriorVenta;
//datos documento				
$sqlDatosVenta="select concat((DATE_FORMAT(s.fecha, '%d/%m/%Y')),' ',s.hora_salida) as fecha, t.`abreviatura`, 
			(select cl.nombre_cliente from clientes cl where s.cod_cliente=cl.cod_cliente) as nombre_cliente,
			(select cl.telf1_cliente from clientes cl where s.cod_cliente=cl.cod_cliente) as telefonoCli, 			
			s.`nro_correlativo`, s.razon_social, s.nit, s.observaciones,
			(select concat(f.paterno, ' ', f.nombres) from funcionarios f where codigo_funcionario=s.cod_chofer) as chofer,
			(select celular from funcionarios f where codigo_funcionario=s.cod_chofer) as celular,
			(select v.placa from vehiculos v where v.codigo=s.cod_vehiculo) as placa,
			s.cod_cliente
			from `salida_almacenes` s, `tipos_docs` t
				where s.`cod_salida_almacenes`='$codigoVenta' and s.`cod_tipo_doc`=t.`codigo`";
$respDatosVenta=mysqli_query($enlaceCon, $sqlDatosVenta);
while($datDatosVenta=mysqli_fetch_array($respDatosVenta)){
			$fechaVenta=$datDatosVenta[0];
			$nombreTipoDoc=$datDatosVenta[1];
			$nombreCliente=$datDatosVenta[2];
			$telfCliente=$datDatosVenta[3];
			$nroDocVenta=$datDatosVenta[4];
			$razonSocial=$datDatosVenta[5];
			$nitVenta=$datDatosVenta[6];
			$obsNota=$datDatosVenta[7];
			$nombreChofer=$datDatosVenta[8];
			$celularChofer=$datDatosVenta[9];
			$placa=$datDatosVenta[10];
			$codClienteVenta=$datDatosVenta[11];
}

$sql = "select valor_configuracion from configuraciones where id_configuracion=-1;";
$resp = mysqli_query($enlaceCon, $sql);
$numeroFacturaVenta=mysqli_result($resp,0,0);

$sql = "select valor_configuracion from configuraciones where id_configuracion=-2;";
$resp = mysqli_query($enlaceCon, $sql);
$proveedorVenta=mysqli_result($resp,0,0);

if(!empty($_POST['codigo_producto'])) {
	$cantidad_material=count($_POST['codigo_producto']);
}

if(!isset($_POST['no_venta'])){
   $almacenDestino=2;
   $almacenOrigen=$global_almacen;
}else{
   $almacenDestino=$_POST['almacen'];
   $almacenOrigen=$global_almacen;
}

$cod_tipopreciogeneral=0;
$cod_tipoVenta2=1;
$cod_tipodelivery=0;
$monto_bs=0;
$monto_usd=0;
$tipo_cambio=0;
$created_at=date("Y-m-d H:i:s");



/////////////////////////REGISTRAR INGRESOS
$sql = "select IFNULL(MAX(cod_ingreso_almacen)+1,1) from ingreso_almacenes order by cod_ingreso_almacen desc";
$resp = mysqli_query($enlaceCon, $sql);
$codigo=mysqli_result($resp,0,0);
$codigoIngresoGeneral=$codigo;
$sql = "select IFNULL(MAX(nro_correlativo)+1,1) from ingreso_almacenes where cod_almacen='$global_almacen' order by cod_ingreso_almacen desc";
$resp = mysqli_query($enlaceCon, $sql);
$nro_correlativo=mysqli_result($resp,0,0);
$codSalida=0;
$hora_sistema = date("H:i:s");

$tipo_ingreso=1001; //POR CAMBIO DE ITEM
$nota_entrega=0;
$nro_factura=$numeroFacturaVenta;  //
$observaciones=$observacionesCambio;
$proveedor=$proveedorVenta;  //

$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d H:i:s");

$fecha_real=date("Y-m-d");

$consulta="insert into ingreso_almacenes (cod_ingreso_almacen,cod_almacen,cod_tipoingreso,fecha,hora_ingreso,observaciones,cod_salida_almacen,
nota_entrega,nro_correlativo,ingreso_anulado,cod_tipo_compra,cod_orden_compra,nro_factura_proveedor,factura_proveedor,estado_liquidacion,
cod_proveedor,created_by,modified_by,created_date,modified_date) 
values($codigo,$global_almacen,$tipo_ingreso,'$fecha_real','$hora_sistema','$observaciones','$codSalida','$nota_entrega','$nro_correlativo',0,0,0,$nro_factura,0,0,'$proveedor','$createdBy','0','$createdDate','')";

$sql_inserta = mysqli_query($enlaceCon, $consulta);

$montoTotalCambio=0;
if($sql_inserta==1){
   $valorExcel="1";
    if($valorExcel=="1"){
      foreach($_POST['codigo_producto'] as $check) {
      	$cod_material=$check;

      	$sql_detalle="select s.cod_material, m.descripcion_material, s.lote, s.fecha_vencimiento,sum(s.cantidad_unitaria), s.precio_unitario, sum(s.`descuento_unitario`), sum(s.`monto_unitario`), sum(ss.`descuento`) from salida_detalle_almacenes s, material_apoyo m, `salida_almacenes` ss where s.cod_salida_almacen='$codigoVenta' and s.cod_material=m.codigo_material and ss.`cod_salida_almacenes`=s.`cod_salida_almacen` and m.codigo_material=$cod_material group by s.cod_material order by s.orden_detalle";
	
        $resp_detalle=mysqli_query($enlaceCon, $sql_detalle);
        $montoTotal=0;
        $pesoTotal=0;
        $pesoTotalqq=0;
        $montoUnitarioTotal=0;
        while($dat_detalle=mysqli_fetch_array($resp_detalle))
        {	$cod_material=$dat_detalle[0];
            $cantidad=$dat_detalle[4];
			$precioBruto=$dat_detalle[5];
			$montoTotalCambio+=($dat_detalle[4]*$dat_detalle[5]);
			$lote="";
        }
		
		if($cod_material!=0){			
			$fechaVencimiento='1900-01-01';		
			$precioUnitario=$precioBruto;			
			$costo=$precioUnitario;			
			$sql_costos="SELECT precio FROM precios where cod_precio=0 and codigo_material=$cod_material limit 1";
            $resp_costos=mysqli_query($enlaceCon, $sql_costos);
            while($dat_costos=mysqli_fetch_array($resp_costos)){	
            	$costo=$dat_costos[0];
            }	
			$consulta="insert into ingreso_detalle_almacenes(cod_ingreso_almacen, cod_material, cantidad_unitaria, cantidad_restante, lote, fecha_vencimiento, 
			precio_bruto, costo_almacen, costo_actualizado, costo_actualizado_final, costo_promedio, precio_neto) 
			values('$codigo','$cod_material','$cantidad','$cantidad','$lote','$fechaVencimiento','$precioUnitario','$precioUnitario','$costo','$costo','$costo','$costo')";
			
			//echo $consulta."<br>";

			$respConsulta=mysqli_query($enlaceCon, $consulta);
		}
	  }
	  //fin de if

		$flagSuccessIngresos=1;
    }
    	
}




////////////////////////REGISTRAR SALIDAS

$usuarioVendedor=$_POST['cod_vendedor'];
$globalSucursal=$_COOKIE['global_agencia'];

$tipoSalida=$_POST['tipoSalida'];
$tipoDoc=3;//$_POST['tipoDoc'];
$almacenDestino=0;
$codCliente=$codClienteVenta;

$tipoVenta=$_POST['tipoVenta'];
$razonSocial=$_POST['razonSocial'];
$nitCliente=$_POST['nitCliente'];

$observaciones=$_POST["observaciones"]." ".$observacionesCambio;
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
$respConf=mysqli_query($enlaceCon, $sqlConf);
$tipoDocDefault=mysqli_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA EL CLIENTE POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=2";
$respConf=mysqli_query($enlaceCon, $sqlConf);
$clienteDefault=mysqli_result($respConf,0,0);

//SACAMOS LA CONFIGURACION PARA CONOCER SI LA FACTURACION ESTA ACTIVADA
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=3";
$respConf=mysqli_query($enlaceCon, $sqlConf);
$facturacionActivada=mysqli_result($respConf,0,0);

$sqlConf="select valor_configuracion from configuraciones where id_configuracion=4";
$respConf=mysqli_query($enlaceCon, $sqlConf);
$banderaValidacionStock=mysqli_result($respConf,0,0);


$sql="SELECT IFNULL(max(cod_salida_almacenes)+1,1) FROM salida_almacenes";
$resp=mysqli_query($enlaceCon, $sql);
$codigo=mysqli_result($resp,0,0);
$codigoSalidaGeneral=$codigo;

$vectorNroCorrelativo=numeroCorrelativo($enlaceCon, $tipoDoc);
$nro_correlativo=$vectorNroCorrelativo[0];
$cod_dosificacion=$vectorNroCorrelativo[2];

if($facturacionActivada==1 && $tipoDoc==1){
		//SACAMOS DATOS DE LA DOSIFICACION PARA INSERTAR EN LAS FACTURAS EMITIDAS
	$sqlDatosDosif="select d.nro_autorizacion, d.llave_dosificacion 
		from dosificaciones d where d.cod_dosificacion='$cod_dosificacion'";
	$respDatosDosif=mysqli_query($enlaceCon, $sqlDatosDosif);
	$nroAutorizacion=mysqli_result($respDatosDosif,0,0);
	$llaveDosificacion=mysqli_result($respDatosDosif,0,1);
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
		`cod_cliente`, `monto_total`, `descuento`, `monto_final`, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado, cod_dosificacion,cod_tipopago,created_by,created_at,cod_tipopreciogeneral,cod_tipoventa2,monto_cancelado_bs,monto_cancelado_usd,tipo_cambio,cod_delivery,
			siat_cuis,siat_cuf,siat_codigotipodocumentoidentidad,siat_complemento,siat_codigoPuntoVenta,siat_excepcion,siat_codigocufd,siat_cod_leyenda)
		values ('$codigo', '$almacenOrigen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
		'$observaciones', '1', '$nro_correlativo', 0, '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial', 
		'$nitCliente', '$usuarioVendedor', '$vehiculo',0,'$cod_dosificacion','$tipoVenta','$usuarioVendedor','$created_at','0','0','0','0','0','0','0','0','0','','0','0','0','0')";

//echo $sql_inserta."<br>";

$sql_inserta=mysqli_query($enlaceCon, $sql_inserta);

if($sql_inserta==1){

	$montoTotalVentaDetalle=0;
	for($i=1;$i<=$cantidad_material;$i++)
	{   	
		$codMaterial=$_POST["materiales$i"];
		if($codMaterial!=0&&$codMaterial!=-100){
			$cantidadUnitaria=$_POST["cantidad_unitaria$i"];
			$precioUnitario=$_POST["precio_unitario$i"];
			$descuentoProducto=$_POST["descuentoProducto$i"];
			
			//SE DEBE CALCULAR EL MONTO DEL MATERIAL POR CADA UNO PRECIO*CANTIDAD - EL DESCUENTO ES UN DATO ADICIONAL
			$montoMaterial=$precioUnitario*$cantidadUnitaria;
			$montoMaterialConDescuento=($precioUnitario*$cantidadUnitaria)-$descuentoProducto;
			
			
			$montoTotalVentaDetalle=$montoTotalVentaDetalle+$montoMaterialConDescuento;
			if($banderaValidacionStock==1){
				//echo "descontando aca";
				$respuesta=descontar_inventarios($enlaceCon, $codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$i);
			}else{
				$respuesta=insertar_detalleSalidaVenta($enlaceCon, $codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$banderaValidacionStock, $i);
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
	$respUpdMonto=mysqli_query($enlaceCon, $sqlUpdMonto);
	if($facturacionActivada==1){
		$sqlUpdMonto="update facturas_venta set importe='$montoTotalConDescuento' 
					where cod_venta='$codigo'";
		$respUpdMonto=mysqli_query($enlaceCon, $sqlUpdMonto);
	}
	
if($montoTotalConDescuento>$montoTotalCambio){	   
	//insertar NOTA DE REMISION
	$tipoSalida=1001; //VENTA
    $tipoDoc=2;//$_POST['tipoDoc'];
    $totalVenta=$montoTotalConDescuento-$montoTotalCambio;       	
$sql="SELECT IFNULL(max(cod_salida_almacenes)+1,1) FROM salida_almacenes";
$resp=mysqli_query($enlaceCon, $sql);
$codigo=mysqli_result($resp,0,0);
$codigoSalidaGeneralNota=$codigo;

$vectorNroCorrelativo=numeroCorrelativo($enlaceCon, $tipoDoc);
$nro_correlativo=$vectorNroCorrelativo[0];
$cod_dosificacion=$vectorNroCorrelativo[2];

$observaciones="POR CAMBIO DE ITEM F/NR :".$numeroFacturaAnteriorVenta;
$sql_inserta="INSERT INTO `salida_almacenes`(cod_salida_almacenes, cod_almacen, cod_tiposalida, 
		cod_tipo_doc, fecha, hora_salida, territorio_destino, 
		almacen_destino, observaciones, estado_salida, nro_correlativo, salida_anulada, 
		cod_cliente, monto_total, descuento, monto_final, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado, cod_dosificacion,cod_tipopago,created_by,created_at,cod_tipopreciogeneral,cod_tipoventa2,monto_cancelado_bs,monto_cancelado_usd,tipo_cambio,cod_delivery,
			siat_cuis,siat_cuf,siat_codigotipodocumentoidentidad,siat_complemento,siat_codigoPuntoVenta,siat_excepcion,siat_codigocufd,siat_cod_leyenda)
		values ('$codigo', '$almacenOrigen', '$tipoSalida', '2', '$fecha', '$hora', '0', '$almacenDestino', 
		'$observaciones', '1', '$nro_correlativo', 0, '$codCliente', '$totalVenta', '0', '$totalVenta', '$razonSocial', 
		'$nitCliente','$usuarioVendedor', '$vehiculo',0,'$cod_dosificacion','1','$usuarioVendedor','$created_at','0','0','0','0','0','0','0','0','0','','0','0','0','0')";

//echo $sql_inserta;

$sql_inserta=mysqli_query($enlaceCon, $sql_inserta);

  if($sql_inserta==1){
  	for($i=1;$i<=$cantidad_material;$i++)
	{   	
		$codMaterial=$_POST["materiales$i"];
		if($codMaterial!=0&&$codMaterial==-100){
			$cantidadUnitaria=$_POST["cantidad_unitaria$i"];
			$precioUnitario=$_POST["precio_unitario$i"];
			$descuentoProducto=$_POST["descuentoProducto$i"];
			
			//SE DEBE CALCULAR EL MONTO DEL MATERIAL POR CADA UNO PRECIO*CANTIDAD - EL DESCUENTO ES UN DATO ADICIONAL
			$montoMaterial=$precioUnitario*$cantidadUnitaria;
			$montoMaterialConDescuento=($precioUnitario*$cantidadUnitaria)-$descuentoProducto;
			
			
			$montoTotalVentaDetalle=$montoTotalVentaDetalle+$montoMaterialConDescuento;
			if($banderaValidacionStock==1){
				//echo "descontando aca";
				$respuesta=descontar_inventarios($enlaceCon, $codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$i);
			}else{
				$respuesta=insertar_detalleSalidaVenta($enlaceCon, $codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$banderaValidacionStock, $i);
			}
	
			if($respuesta!=1){
				echo "<script>
					alert('Existio un error en el detalle. Contacte con el administrador del sistema.');
				</script>";
			}
		}			
	  }
			
	 }			
	 $consulta="UPDATE salida_almacenes SET cod_cambio=$codigoVenta where cod_salida_almacenes=$codigoSalidaGeneralNota";
     $sql_inserta = mysqli_query($enlaceCon, $consulta);
    }//Fin Nota de Remisión

	$flagSuccessSalidas=1;	
}

$mensajeAlert="";
if($flagSuccessIngresos==0){
  $mensajeAlert.="Ocurrio un error al guardar los datos de Ingreso, ";
}else{	
  $consulta="UPDATE salida_almacenes SET cod_cambio=$codigoVenta where cod_salida_almacenes=$codigoSalidaGeneral";
  $sql_inserta = mysqli_query($enlaceCon, $consulta);
}
if($flagSuccessSalidas==0){
  $mensajeAlert.="Ocurrio un error al guardar los datos de la Venta, "; 
}else{
  $consulta2="UPDATE ingreso_almacenes SET cod_cambio=$codigoVenta where cod_ingreso_almacen=$codigoIngresoGeneral";
  $sql_inserta2 = mysqli_query($enlaceCon, $consulta2);	
}

if($mensajeAlert==""){
	$mensajeAlert.="Se guardaron los datos Correctamente";
}

echo "<script type='text/javascript' language='javascript'>
			alert('".$mensajeAlert."');
			location.href='navegadorVentas.php';
		</script>";
?>



