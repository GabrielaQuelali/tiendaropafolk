<?php
$start_time = microtime(true);
require("conexionmysqli2.inc");
require("estilos.inc");

require("funciones.php");
require("funciones_inventarios.php");


 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$tipo=$_POST['tipo'];
$global_almacen=$_POST['global_almacen'];
$global_ciudad=$_POST['global_ciudad'];
$tipoSalida=$_POST['tipoSalida'];
$tipoDoc=$_POST['tipoDoc'];
$tipoPago=$_POST['tipoPago'];
$observaciones=$_POST['observaciones'];
$fechaNotaRemision=$_POST["fechaNotaRemision"];
$cantidad_material=$_POST["cantidad_material"];



$fecha=$_POST['fechaNotaRemision'];//date("Y-m-d");
$hora=date("H:i:s");
$usuarioVendedor=$_COOKIE['global_usuario'];
$created_by=$usuarioVendedor;
$anio=date("Y");

$created_at=date("Y-m-d H:i:s");


$sqlConf="select valor_configuracion from configuraciones where id_configuracion=4";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$banderaValidacionStock=$datConf[0];


//SACAMOS LA CONFIGURACION PARA EL CLIENTE POR DEFECTO
$sqlConf="select valor_configuracion from configuraciones where id_configuracion=2";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$clienteDefault=$datConf[0];



$sql="SELECT IFNULL(max(cod_salida_almacenes)+1,1) FROM salida_almacenes";
$resp=mysqli_query($enlaceCon,$sql);
$datCodSalida=mysqli_fetch_array($resp);
$codigo=$datCodSalida[0];


$sql="select ifnull(max(nro_correlativo)+1,1) from salida_almacenes 
where cod_tipo_doc=2 and cod_almacen='".$global_almacen."' and cod_tipo='".$tipo."'";
//echo $sql;
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$nroCorrelativo=$dat[0];


	$cod_dosificacion=0;

	$sql_inserta="insert into salida_almacenes(cod_salida_almacenes, cod_almacen, cod_tiposalida, 
 	cod_tipo_doc, fecha, hora_salida,  observaciones, estado_salida, nro_correlativo, salida_anulada, 
 	cod_cliente, razon_social, nit, cod_chofer, cod_dosificacion,cod_tipopago,  cod_tipo)
 		values ('$codigo', '$global_almacen', '$tipoSalida', '$tipoDoc', '$fechaNotaRemision', '$hora', 
 		'$observaciones', '1', '$nroCorrelativo','1', '$clienteDefault','SN','123', '$usuarioVendedor','$cod_dosificacion','$tipoPago','$tipo')";

 $resp_inserta=mysqli_query($enlaceCon,$sql_inserta);
	//echo $sql_inserta;
	$resp_inserta=1;
	if($resp_inserta==1){
	$orden=0;
	$montoTotal=0;
	for($i=1;$i<=($cantidad_material-1);$i++){  

		if(isset($_POST['codigoMaterial'.$i])){
				$orden=$orden+1;
				$codMaterial=$_POST['codigoMaterial'.$i];
				$stock=$_POST['stock'.$i];
				$cantidad_venta=$_POST['cantidad_venta'.$i];
				$precio=$_POST['precio'.$i];
				$precio_venta=$_POST['precio_venta'.$i];

				echo "codMaterial=".$codMaterial."<br>";
					
			$precioUnitario=0;				
			$descuentoProducto=0;	
			$precio_normal=0;
			$precio_mayor=0;			
			/****************** Gestionamos los precios desde los traspasos  **************/
			//SE DEBE CALCULAR EL MONTO DEL MATERIAL POR CADA UNO PRECIO*CANTIDAD - EL DESCUENTO ES UN DATO ADICIONAL
			$montoMaterial=$precio_venta*$cantidad_venta;
			$montoTotal=$montoTotal+$montoMaterial;
						
			$respuesta=descontar_inventarios($enlaceCon,$codigo, $global_almacen,$codMaterial,$cantidad_venta,$precio_venta,$descuentoProducto,$montoMaterial,$orden,$precio_normal,$precio_mayor);

			if($respuesta!=1){
				echo "<script>
					alert('Existio un error en el detalle. Contacte con el administrador del sistema.');
				</script>";
			}
					
		}
	}
	$sqlUpdMonto="update salida_almacenes set monto_total=$montoTotal, descuento=0,monto_final=$montoTotal 
				where cod_salida_almacenes=$codigo";
				// echo $sqlUpdMonto;
	$respUpdMonto=mysqli_query($enlaceCon,$sqlUpdMonto);

		echo "<script type='text/javascript' language='javascript'>
		alert('La Venta se efectuo correctamente.');
			location.href='navegadorVentas2.php';
		</script>";


}else{
		echo "<script type='text/javascript' language='javascript'>
			alert('Ocurrio un error en la transaccion. Contacte con el administrador del sistema.');
			location.href='navegadorVentas2.php';
		</script>";
}


?>



