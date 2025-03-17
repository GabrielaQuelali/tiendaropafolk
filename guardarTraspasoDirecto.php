<?php
$start_time = microtime(true);
require("conexionmysqli2.inc");
require("estilos.inc");

require("funciones.php");
require("funciones_inventarios.php");


 error_reporting(E_ALL);
 ini_set('display_errors', '1');

$tipo=$_POST['tipo'];

$banderaEditPreciosTraspaso=0;
$banderaEditPreciosTraspaso=obtenerValorConfiguracion($enlaceCon, 20);

$usuarioVendedor=$_COOKIE['global_usuario'];
$globalSucursal=$_COOKIE['global_agencia'];

$cod_ingreso_almacen=$_POST['cod_ingreso_almacen'];



$errorProducto="";
$totalFacturaMonto=0;

$tipoSalida=$_POST['tipoSalida'];


$tipoDoc=$_POST['tipoDoc'];
//echo "tipoDoc=".$tipoDoc."<br/>";


   $almacenDestino=$_POST['almacen'];
   $global_almacen=$_COOKIE['global_almacen'];

   $almacenOrigen=$global_almacen;



if(isset($_POST['tipoVenta'])){	$tipoVenta=$_POST['tipoVenta']; }else{ $tipoVenta=0;	}
	$observaciones=$_POST['observaciones'];

$fecha=$_POST["fecha"];


$cantidad_material=$_POST["cantidad_material"];
//echo "cantidad_material=".$cantidad_material;




$fecha=date("Y-m-d");
$hora=date("H:i:s");



$sqlConf="select valor_configuracion from configuraciones where id_configuracion=4";
$respConf=mysqli_query($enlaceCon,$sqlConf);
$datConf=mysqli_fetch_array($respConf);
$banderaValidacionStock=$datConf[0];

$created_by=$usuarioVendedor;

	$anio=date("Y");

	$created_at=date("Y-m-d H:i:s");
	$sql="SELECT IFNULL(max(cod_salida_almacenes)+1,1) FROM salida_almacenes";
	$resp=mysqli_query($enlaceCon,$sql);

	$datCodSalida=mysqli_fetch_array($resp);
	$codigo=$datCodSalida[0];


  //CUANDO ES NR O TRASPASOS U OTROS TIPOS DE DOCS
	$vectorNroCorrelativo=numeroCorrelativo($enlaceCon,$tipoDoc,$tipo);
	$nro_correlativo=$vectorNroCorrelativo[0];
	$cod_dosificacion=0;

	$sql_inserta="insert into salida_almacenes(cod_salida_almacenes, cod_almacen, cod_tiposalida, 
 	cod_tipo_doc, fecha, hora_salida, territorio_destino, almacen_destino, observaciones, estado_salida, nro_correlativo, salida_anulada,  cod_chofer, cod_tipo)
 		values ('$codigo', '$almacenOrigen', '$tipoSalida', '$tipoDoc', '$fecha', '$hora', '0', '$almacenDestino', 
 		'$observaciones', '1', '$nro_correlativo','1',  '$usuarioVendedor','$tipo')";

 $resp_inserta=mysqli_query($enlaceCon,$sql_inserta);
	//echo $sql_inserta;


//$sql_inserta=1;
if($resp_inserta==1){
	
	for($i=1;$i<=$cantidad_material;$i++)
	{   	
		$codMaterial=$_POST["materiales".$i];
		
		if($codMaterial!=0){

			$cantidadUnitaria=$_POST["cantidad_unitaria".$i];			
			$precioUnitario=0;				
			$descuentoProducto=0;				
			/****************** Gestionamos los precios desde los traspasos  **************/
			$precio_normal=0;
			$precio_mayor=0;
		
				$precio_normal=$_POST['precio_normal'.$i];
				$precio_mayor=$_POST['precio_mayor'.$i];

			/******* Cuando es Traspaso y los precios no son Editables ******/
			if($tipoSalida==1000 && $banderaEditPreciosTraspaso==0){
				//echo "saca precios de la base de datos del almacen origen";
				$consulta="select p.`precio` from precios p where p.`codigo_material`='$codMaterial' and p.`cod_precio`='1' and cod_ciudad='$globalSucursal'";
				$rs=mysqli_query($enlaceCon,$consulta);
				$registro=mysqli_fetch_array($rs);
				if(mysqli_num_rows($rs)>0){
					$precio_normal=$registro[0];
				}
				$consulta="select p.`precio` from precios p where p.`codigo_material`='$codMaterial' and p.`cod_precio`='2' and cod_ciudad='$globalSucursal'";
				$rs=mysqli_query($enlaceCon,$consulta);
				$registro=mysqli_fetch_array($rs);
				if(mysqli_num_rows($rs)>0){
					$precio_mayor=$registro[0];
				}
			}

			//SE DEBE CALCULAR EL MONTO DEL MATERIAL POR CADA UNO PRECIO*CANTIDAD - EL DESCUENTO ES UN DATO ADICIONAL
			$montoMaterial=$precioUnitario*$cantidadUnitaria;
			$montoMaterialConDescuento=($precioUnitario*$cantidadUnitaria)-$descuentoProducto;
			
			
			
			if($banderaValidacionStock==1){

				$respuesta=descontar_inventariosIngreso($enlaceCon,$codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$i,$precio_normal,$precio_mayor,$cod_ingreso_almacen);
				//echo "descontar_inventarios=".$respuesta."<br>";
			}else{
				$respuesta=insertar_detalleSalidaVenta($enlaceCon,$codigo, $almacenOrigen,$codMaterial,$cantidadUnitaria,$precioUnitario,$descuentoProducto,$montoMaterial,$banderaValidacionStock,$i,$precio_normal,$precio_mayor);
				
			}
	
			if($respuesta!=1){
				echo "<script>
					alert('Existio un error en el detalle. Contacte con el administrador del sistema.');
				</script>";
			}
		}			
	}
	
		echo "<script type='text/javascript' language='javascript'>
		alert('El trasposo se efectuo correctamente.');
			location.href='navegador_ingresomateriales.php?tipo=$tipo&estado=-1';
		</script>";

}else{
		echo "<script type='text/javascript' language='javascript'>
			alert('Ocurrio un error en la transaccion. Contacte con el administrador del sistema.');
			location.href='navegador_ingresomateriales.php?tipo=$tipo&estado=-1';
		</script>";
}

?>



