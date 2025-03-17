<?php
require("conexionmysqli2.inc");
require("estilos.inc");
require("funciones.php");

error_reporting(E_ALL);
 ini_set('display_errors', '1');


//recogemos variables
$globalAgencia=$_COOKIE['global_agencia']; // Ciudad
//echo $globalAgencia;
$fecha=$_POST['fecha'];
//echo "fecha=".$fecha;
$codProveedor=$_POST['codProveedor'];
//echo "codProveedor=".$codProveedor;
$observaciones=$_POST['observaciones'];
//echo "observaciones=".$observaciones."<br>";
$fechaCreacion=date("Y-m-d-H-i-s");
$createdBy=$_COOKIE['global_usuario'];

$sql="select IFNULL((max(cod_pago)+1),1) as codigo from pagos_proveedor_cab ";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$cod_pago=$dat[0];
//echo "cod_pago".$cod_pago;

$sql2="select IFNULL((max(nro_pago)+1),1) as codigo from pagos_proveedor_cab where cod_ciudad=".$globalAgencia;
$resp2=mysqli_query($enlaceCon,$sql2);
$dat2=mysqli_fetch_array($resp2);
$nro_pago=$dat2[0];
//echo "nro_pago".$nro_pago;

$sqlInstert="insert into pagos_proveedor_cab (cod_pago, fecha, monto_pago, observaciones,
cod_proveedor,cod_estado,nro_pago,created_by,created_date,cod_ciudad) 
values(".$cod_pago.",'".$fecha."',0,'".$observaciones."',".$codProveedor.",1,".$nro_pago.",".$createdBy.",'".$fechaCreacion."',".$globalAgencia.")";
//echo $sqlInstert."<br>";
$resp_inserta=mysqli_query($enlaceCon,$sqlInstert);

$montoPagoTotal=0;
if($resp_inserta){
/// Recorrer el detalle de los Documentos pendientes a Pagar
$cod_tipo_doc_obligxpagar=2;
$sqlObligxPagar="select lpc.cod_lote,lpc.cod_proceso_const, pc.nombre_proceso_const, lpc.cod_proveedor, lpc.cantidad, lpc.precio,
lpc.obligacionxpagar_si_no, lpc.obligacionxpagar_fecha, lpc.cod_estado_pago,lp.nro_lote,
lp.nombre_lote,lp.codigo_material, ma.descripcion_material,lp.cod_estado_lote, el.nombre_estado as nombre_estado_lote,lp.cant_lote
from lote_procesoconst lpc
left join lotes_produccion lp on ( lp.cod_lote=lpc.cod_lote)
left join procesos_construccion pc on ( lpc.cod_proceso_const=pc.cod_proceso_const)
left join material_apoyo ma on (lp.codigo_material=ma.codigo_material)
left join estados_lote el on (lp.cod_estado_lote=el.cod_estado)
where  lp.cod_estado_lote<>4
and lpc.obligacionxpagar_si_no=1
and  lpc.cod_estado_pago<>3
and lpc.cod_proveedor=".$codProveedor."
order by lpc.obligacionxpagar_fecha asc,lp.nro_lote asc";

//echo $sqlObligxPagar."<br>";
$respObligxPagar=mysqli_query($enlaceCon,$sqlObligxPagar);
$orden=0;
while($datObligxPagar=mysqli_fetch_array($respObligxPagar)){
	$cod_lote=$datObligxPagar['cod_lote'];
	$cod_proceso_const=$datObligxPagar['cod_proceso_const'];
	$cod_proveedor=$datObligxPagar['cod_proveedor'];



	$docPagar=$_POST['docPagar'.$cod_lote.$cod_proceso_const.$cod_proveedor];
	
	if($docPagar){
		

		$montoPagoLote=$_POST['montoPagoDeudaLote'.$cod_lote.$cod_proceso_const.$cod_proveedor];
	
	//echo "montoPagoLote=".$montoPagoLote."<br>";
		if($montoPagoLote>0){

			$orden++;
			$montoPagoTotal=$montoPagoTotal+$montoPagoLote;
			$sqlInsertPagoProvDet="insert into pagos_proveedor_detalle(cod_pago,orden,cod_tipo_doc_obligxpagar,codigo_doc,monto_pago,cod_proceso_const) 
				values (".$cod_pago.",".$orden.",".$cod_tipo_doc_obligxpagar.",".$cod_lote.",".$montoPagoLote.",".$cod_proceso_const.")";
				echo $orden."<br>";
			mysqli_query($enlaceCon,$sqlInsertPagoProvDet);
			//echo "se realizara el registro <br/>";

			//// verificar si el Documento se pago en su totalidad
			$sqlDocAux="select (lpc.precio * lpc.cantidad ) 
					from lote_procesoconst lpc
					where lpc.cod_lote=".$cod_lote."
					and lpc.cod_proceso_const=".$cod_proceso_const."
					and lpc.cod_proveedor=".$cod_proveedor;
			$respDocAux=mysqli_query($enlaceCon,$sqlDocAux);
			$montoDoc=0;
			while($datDocAux=mysqli_fetch_array($respDocAux)){
				$montoDoc=$datDocAux[0];
					if($montoDoc==null){
 						$montoDoc=0;
 					}
			}	
			//echo "montoDoc=".$montoDoc."<br>";
			////////////
			 $sqlAuxi2=" select sum(ppd.monto_pago)
			from pagos_proveedor_detalle ppd
			left join pagos_proveedor_cab ppc on(ppd.cod_pago=ppc.cod_pago)
			where ppc.cod_estado=1
			and  ppc.cod_proveedor=".$cod_proveedor."
			and ppd.codigo_doc=".$cod_lote."
			and ppd.cod_proceso_const=".$cod_proceso_const."
			and ppc.cod_estado=1";
			//echo $sqlAux2."<br>";
			$respAuxi2=mysqli_query($enlaceCon,$sqlAuxi2);
			$acuentaDoc=0;
 			while($datAuxi2=mysqli_fetch_array($respAuxi2)){ 		
 				$acuentaDoc=$datAuxi2[0];
 					if($acuentaDoc==null){
 						$acuentaDoc=0;
 					}
 			}
			////////////
 			//echo "acuentaDoc=".$acuentaDoc."<br>";
 			if(($acuentaDoc)>=$montoDoc){
 				$sqlUpdateEstado="update lote_procesoconst set
					cod_estado_pago=3
					where cod_lote=".$cod_lote."
					and cod_proceso_const=".$cod_proceso_const."
					and cod_proveedor=".$cod_proveedor;
					//echo $sqlUpdateEstado."<br>";
				mysqli_query($enlaceCon,$sqlUpdateEstado);

 			}else{
 				$sqlUpdateEstado="update lote_procesoconst set
					cod_estado_pago=2
					where cod_lote=".$cod_lote."
					and cod_proceso_const=".$cod_proceso_const."
					and cod_proveedor=".$cod_proveedor;
					//echo $sqlUpdateEstado."<br>";
				mysqli_query($enlaceCon,$sqlUpdateEstado);

 			}
			////fin  veri+ficar si el Documento se pago en su totalidad


		}
	
	}
}

$sqlUpdateInsert="update pagos_proveedor_cab set monto_pago=".$montoPagoTotal." where
 cod_pago=".$cod_pago;
mysqli_query($enlaceCon,$sqlUpdateInsert);
/// registro de forma de Pago

	$sqlTipoPago="select cod_tipopago, nombre_tipopago,estado,contabiliza,banco,nro_cta,nombre_cta
			from tipos_pago where estado=1 order by cod_tipopago asc";
		
		$respTipoPago=mysqli_query($enlaceCon,$sqlTipoPago);
 		while($datTipoPago=mysqli_fetch_array($respTipoPago)){
 			$cod_tipopago=$datTipoPago['cod_tipopago'];
 			$banco=$datTipoPago['banco'];
 			$nro_cta=$datTipoPago['nro_cta'];
 			$nombre_cta=$datTipoPago['nombre_cta'];

 			if ($_POST['montoTipoPago'.$cod_tipopago]){

 				$montoxTipoPago=$_POST['montoTipoPago'.$cod_tipopago];
 				if($montoxTipoPago>0){


 					$sqlInsertTipoPago="insert into pagos_proveedor_detalle_pago (cod_pago,cod_tipo_pago,cod_moneda,monto_pago,";
 					if($banco==1){
 						$sqlInsertTipoPago=$sqlInsertTipoPago." cod_banco,";
 					}
 					if($nro_cta==1){
 						$sqlInsertTipoPago=$sqlInsertTipoPago." nro_cuenta,";
 					}
 					if($nombre_cta==1){
 						$sqlInsertTipoPago=$sqlInsertTipoPago." nombre_cuenta,";
 					}
 					$sqlInsertTipoPago=$sqlInsertTipoPago." inf_adicional) values(".$cod_pago.",".$cod_tipopago.",1,".$montoxTipoPago.",";
 					if($banco==1){
 					$sqlInsertTipoPago=$sqlInsertTipoPago.$_POST['cod_banco'.$cod_tipopago].",";
 					}
 					if($nro_cta==1){
 					$sqlInsertTipoPago=$sqlInsertTipoPago."'".$_POST['nro_cta'.$cod_tipopago]."',";
 					}
 					if($nombre_cta==1){
 					$sqlInsertTipoPago=$sqlInsertTipoPago."'".$_POST['nombre_cta'.$cod_tipopago]."',";
 					}
 					$sqlInsertTipoPago=$sqlInsertTipoPago."'".$_POST['infAdicional'.$cod_tipopago]."')";
 				
 				mysqli_query($enlaceCon,$sqlInsertTipoPago);
 				//echo $sqlInsertTipoPago."<br>";


 				}

 			}
 		}
// fin registro forma de pago

//
	$sqlRevisionLote="select distinct(codigo_doc) from pagos_proveedor_detalle where cod_pago=".$cod_pago;
	$respRevisionLote=mysqli_query($enlaceCon,$sqlRevisionLote);
	while($datRevisionLote=mysqli_fetch_array($respRevisionLote)){ 
		$codigo_doc=$datRevisionLote[0];
		// ACTUALIZAR ESTADO DE PAGO DEL LOTE
							$sqlLote="select obligacionxpagar_si_no from lotes_produccion 
							where cod_lote=".$codigo_doc;
							$respLote=mysqli_query($enlaceCon,$sqlLote);							
							while($datLote=mysqli_fetch_array($respLote)){
								$obligacionxpagar_si_no=$datLote['obligacionxpagar_si_no'];
							}	
							if($obligacionxpagar_si_no==1){
								// aqui recorreremos el detalle del lote
								$sqlLoteDetalle="select obligacionxpagar_si_no,cod_proceso_const, cod_proveedor,cantidad, precio
 									from lote_procesoconst where cod_lote=".$codigo_doc;
 								$respLoteDetalle=mysqli_query($enlaceCon,$sqlLoteDetalle);	
 								$contador=0;	
 								$contadorEstado=0;					
								while($datLoteDetalle=mysqli_fetch_array($respLoteDetalle)){
									$contador++;
									$obligacionxpagarSiNoD=$datLoteDetalle['obligacionxpagar_si_no'];
									$codProcesoConstD=$datLoteDetalle['cod_proceso_const'];
									$codProveedorD=$datLoteDetalle['cod_proveedor'];
									$cantidadD=$datLoteDetalle['cantidad'];
									$precioD=$datLoteDetalle['precio'];
									$montoApagarLoteD=$cantidadD*$precioD;

									if($obligacionxpagarSiNoD==1){
										////////////////////////////
										$sqlPagoProvDetLote="select sum(ppd.monto_pago) 
										from pagos_proveedor_detalle ppd 
										left join pagos_proveedor_cab ppc on (ppd.cod_pago=ppc.cod_pago)
										where ppd.codigo_doc=".$codigo_doc."
										and ppd.cod_proceso_const=".$codProcesoConstD."
										and ppc.cod_proveedor=".$codProveedorD."
										and ppc.cod_estado=1";
										$montoPagoLoteD=0;
										$respPagoProvDetLote=mysqli_query($enlaceCon,$sqlPagoProvDetLote);
										while($datPagoProvDetLote=mysqli_fetch_array($respPagoProvDetLote)){	
											$montoPagoLoteD=$datPagoProvDetLote[0];
											if($montoPagoLoteD==null){
												$montoPagoLoteD=0;
											}
										}

										$sqlUpdateLPC="update lote_procesoconst set ";
 							
 										if($montoPagoLoteD==0){
											$sqlUpdateLPC=$sqlUpdateLPC."cod_estado_pago=1";
										}else{
											if($montoPagoLoteD<$montoApagarLoteD){
												$sqlUpdateLPC=$sqlUpdateLPC."cod_estado_pago=2";
												$contadorEstado=$contadorEstado+(0.5);
											}else{
												$sqlUpdateLPC=$sqlUpdateLPC."cod_estado_pago=3";	
												$contadorEstado=$contadorEstado+1;
											}
										}

										$sqlUpdateLPC=$sqlUpdateLPC." where cod_lote=".$codigo_doc."
										and cod_proceso_const=".$codProcesoConstD."
										and cod_proveedor=".$codProveedorD;	
										//echo 	$sqlUpdateLPC."<br>";					
										mysqli_query($enlaceCon,$sqlUpdateLPC);
									//////////
									}else{
										$contadorEstado=$contadorEstado+1;
									}
									
								}

								// fin recorrido de Lote
								$sqlUpdateLote="update lotes_produccion set ";
								if($contadorEstado==0){
									$sqlUpdateLote=$sqlUpdateLote." cod_estado_pago=1";
								}else{
									if($contadorEstado<$contador){
										$sqlUpdateLote=$sqlUpdateLote." cod_estado_pago=2";
									}else{
										$sqlUpdateLote=$sqlUpdateLote." cod_estado_pago=3";
									}
								}
								 

								$sqlUpdateLote=$sqlUpdateLote." where cod_lote=".$codigo_doc;
								//echo 	$sqlUpdateLote."<br>";	
								mysqli_query($enlaceCon,$sqlUpdateLote);							

							}else{
								$sqlUpdateLote="update lotes_produccion set 
								cod_estado_pago=3 where cod_lote=".$codigo_doc;
								//echo 	$sqlUpdateLote."<br>";
								mysqli_query($enlaceCon,$sqlUpdateLote);
							}
						// FIN DE ACTUALIZAR ESTADO DE PAGO DEL LOTE
 	}

//
		echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='listadoPagosProveedor.php';
			</script>";
}else{
	echo "<script language='Javascript'>
			alert('ERROR EN LA TRANSACCION. COMUNIQUESE CON EL ADMIN.');
			history.back();
			</script>";
}
	

?>