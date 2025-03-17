<?php
$start_time = microtime(true);
require("conexionmysqli.php");
require("estilos_almacenes.inc");
require("funciones.php");
require("funciones_inventarios.php");
require("enviar_correo/php/send-email_anulacion.php");


 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$tipo=$_POST['tipo'];
echo "tipo=".$tipo;

$banderaEditPreciosTraspaso=0;
$banderaEditPreciosTraspaso=obtenerValorConfiguracion($enlaceCon, 20);

$usuarioVendedor=$_COOKIE['global_usuario'];
$globalSucursal=$_COOKIE['global_agencia'];

 echo "Usuario:".$usuarioVendedor."<br/>";
 echo "Sucursal:".$globalSucursal."<br/>";
$errorProducto="";
$totalFacturaMonto=0;

$tipoSalida=$_POST['tipoSalida'];
echo "Tipo Salida:".$tipoSalida."<br/>";

$tipoDoc=$_POST['tipoDoc'];

echo "Tipo Documento:".$tipoDoc."<br/>";


   $almacenDestino=$_POST['almacen'];
   echo "Almacen Destino:".$almacenDestino."<br/>";
   $almacenOrigen=$global_almacen;
   echo "Almacen Origen:".$almacenOrigen."<br/>";




if(isset($_POST['observaciones'])){	$observaciones=$_POST['observaciones']; }else{ $observaciones="";	}
  echo "observaciones:".$observaciones."<br/>";

if(isset($_POST['totalVenta'])){	$totalVenta=$_POST['totalVenta']; }else{ $totalVenta=0;	}
  echo "totalVenta:".$totalVenta."<br/>";

$cantidad_material=$_POST["cantidad_material"];
  echo "cantidad_material:".$cantidad_material."<br/>";

$fecha=$_POST['fecha'];

$hora=date("H:i:s");






$sqlConf="select valor_configuracion from configuraciones where id_configuracion=4";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$banderaValidacionStock=$datConf[0];
//$banderaValidacionStock=mysql_result($respConf,0,0);



$created_by=$usuarioVendedor;

$contador = 0;
  //CUANDO ES NR O TRASPASOS U OTROS TIPOS DE DOCS
		$vectorNroCorrelativo=numeroCorrelativo($enlaceCon,$tipoDoc,$tipo);
		$nro_correlativo=$vectorNroCorrelativo[0];
		$cod_dosificacion=0;

		$sql_inserta="insert into salida_almacenes(cod_salida_almacenes, cod_almacen, cod_tiposalida, 
 		cod_tipo_doc, fecha, hora_salida, territorio_destino, almacen_destino, observaciones, estado_salida, nro_correlativo, salida_anulada, 
 		cod_cliente, monto_total, descuento, monto_final, razon_social, nit, cod_chofer, cod_vehiculo, monto_cancelado, cod_dosificacion,cod_tipopago, monto_efectivo, monto_cambio,cod_tipo)
 		values ('$codigo', '$almacenOrigen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
 		'$observaciones', '1', '$nro_correlativo', 1, '$codCliente', '$totalVenta', '$descuentoVenta', '$totalFinal', '$razonSocial', '$nitCliente', '$usuarioVendedor', '$vehiculo',0,'$cod_dosificacion','$tipoVenta','$totalEfectivo','$totalCambio',$tipo)";

 		echo $sql_inserta."<br/>";
 		$sql_inserta=mysqli_query($enlaceCon,$sql_inserta);



if($sql_inserta==1){


	$montoTotalVentaDetalle=0;
	for($i=1;$i<=$cantidad_material;$i++){   	

		$codMaterial=$_POST["codigoMaterial$i"];
		if($codMaterial!=0){

			$cantidadUnitaria=$_POST["cantidad_venta$i"];

			if(isset($_POST["precio$i"])){
				$precioUnitario=$_POST["precio$i"];
			}else{

			$precioUnitario=0;				
			}

				$descuentoProducto=0;
			
			

			/****************** Gestionamos los precios desde los traspasos  **************/
			$precioTraspaso=0;
			$precioTraspaso2=0;
			if(isset($_POST["precio$i"])){
				$precioTraspaso=$_POST["precio$i"];
				$precioTraspaso2=$_POST["precio_venta$i"];
			}
			/******* Cuando es Traspaso y los precios no son Editables ******/
			if($tipoSalida==1000 && $banderaEditPreciosTraspaso==0){
				$consulta="select p.`precio` from precios p where p.`codigo_material`='$codMaterial' and p.`cod_precio`='1' and cod_ciudad='$globalSucursal'";
				$rs=mysqli_query($enlaceCon,$consulta);
				$registro=mysqli_fetch_array($rs);
				if(mysqli_num_rows($rs)>0){
					$precioTraspaso=$registro[0];
				}
				$consulta="select p.`precio` from precios p where p.`codigo_material`='$codMaterial' and p.`cod_precio`='2' and cod_ciudad='$globalSucursal'";
				$rs=mysqli_query($enlaceCon,$consulta);
				$registro=mysqli_fetch_array($rs);
				if(mysqli_num_rows($rs)>0){
					$precioTraspaso2=$registro[0];
				}
			}

			//SE DEBE CALCULAR EL MONTO DEL MATERIAL POR CADA UNO PRECIO*CANTIDAD - EL DESCUENTO ES UN DATO ADICIONAL
			$montoMaterial=$precioUnitario*$cantidadUnitaria;
			$montoMaterialConDescuento=($precioUnitario*$cantidadUnitaria)-$descuentoProducto;
			
			$montoTotalVentaDetalle=$montoTotalVentaDetalle+$montoMaterialConDescuento;
			

			if($banderaValidacionStock==1){


				$respuesta=descontar_inventarios($enlaceCon,$codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$i,$precioTraspaso,$precioTraspaso2);
				//echo "descontar_inventarios=".$respuesta."<br>";
			}else{
				$respuesta=insertar_detalleSalidaVenta($enlaceCon,$codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$banderaValidacionStock,$i,$precioTraspaso,$precioTraspaso2);
			//	echo "insertar_detalleSalidaVenta=".$respuesta."<br>";
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
	$sqlUpdMonto="update salida_almacenes set monto_total=$montoTotalVentaDetalle, monto_final=$montoTotalConDescuento 
				where cod_salida_almacenes=$codigo";
				// echo $sqlUpdMonto;
	$respUpdMonto=mysqli_query($enlaceCon,$sqlUpdMonto);



		/*echo "<script type='text/javascript' language='javascript'>
			location.href='navegador_salidamateriales.php?tipo=$tipo';
		</script>";*/
	
}else{
		/*echo "<script type='text/javascript' language='javascript'>
			alert('Ocurrio un error en la transaccion. Contacte con el administrador del sistema.');
			location.href='navegador_salidamateriales.phptipo=$tipo';
		</script>";*/
}

?>



