<?php

	require("conexionmysqli2.inc");
	require('estilos.inc');
	require("funciones.php");

	$cod_pago=$_GET['cod_pago'];



		$sql="update pagos_proveedor_cab set cod_estado=2 where cod_pago=".$cod_pago;
		//echo $sql."<br/>";
		$resp=mysqli_query($enlaceCon,$sql);
		if($resp){
			$sqlDetalle="select ppc.cod_proveedor, ppd.cod_tipo_doc_obligxpagar,ppd.codigo_doc,
						ppd.cod_proceso_const,
						ppd.monto_pago 
						from pagos_proveedor_detalle ppd 
						left join pagos_proveedor_cab ppc on (ppd.cod_pago=ppc.cod_pago)
						where ppd.cod_pago=".$cod_pago;
			//echo $sqlDetalle."<br>";
			$respDetalle=mysqli_query($enlaceCon,$sqlDetalle);
			while($datDetalle=mysqli_fetch_array($respDetalle)){
				$cod_proveedor=$datDetalle['cod_proveedor'];
				$cod_tipo_doc_obligxpagar=$datDetalle['cod_tipo_doc_obligxpagar'];
				$codigo_doc=$datDetalle['codigo_doc'];
				$cod_proceso_const=$datDetalle['cod_proceso_const'];
				$monto_pago=$datDetalle['monto_pago'];
				// lotes
				if($cod_tipo_doc_obligxpagar==2){

						$sqlPagoProvDet="select sum(ppd.monto_pago) 
						from pagos_proveedor_detalle ppd 
						left join pagos_proveedor_cab ppc on (ppd.cod_pago=ppc.cod_pago)
						where ppd.codigo_doc=".$codigo_doc."
						and ppd.cod_proceso_const=".$cod_proceso_const."
						and ppc.cod_proveedor=".$cod_proveedor."
						and ppc.cod_estado=1";
						//echo $sqlPagoProvDet."<br/>";
						$monto_pagoLote=0;
						$respPagoProvDet=mysqli_query($enlaceCon,$sqlPagoProvDet);
						while($datPagoProvDet=mysqli_fetch_array($respPagoProvDet)){
							$monto_pagoLote=$datPagoProvDet[0];
							if($monto_pagoLote==null){
								$monto_pagoLote=0;
							}
						}

						//// Obtiene el monto que se debe Pagar del Proceso de Construccion
						$sqlDocAux="select (lpc.precio * lpc.cantidad ) 
							from lote_procesoconst lpc
							where lpc.cod_lote=".$codigo_doc."
							and lpc.cod_proceso_const=".$cod_proceso_const."
							and lpc.cod_proveedor=".$cod_proveedor;
							$respDocAux=mysqli_query($enlaceCon,$sqlDocAux);
							$montoaPagarLote=0;
							while($datDocAux=mysqli_fetch_array($respDocAux)){
								$montoaPagarLote=$datDocAux[0];
								if($montoaPagarLote==null){
 									$montoaPagarLote=0;
 								}
							}	
					
 							$sqlUpdateEstado="update lote_procesoconst set ";
 							
 							if($monto_pagoLote==0){
								$sqlUpdateEstado=$sqlUpdateEstado."cod_estado_pago=1";
							}else{
								if($monto_pagoLote<$montoaPagarLote){
									$sqlUpdateEstado=$sqlUpdateEstado."cod_estado_pago=2";
								}else{
									$sqlUpdateEstado=$sqlUpdateEstado."cod_estado_pago=3";	
								}
							}
							$sqlUpdateEstado=$sqlUpdateEstado." where cod_lote=".$codigo_doc."
							and cod_proceso_const=".$cod_proceso_const."
							and cod_proveedor=".$cod_proveedor;
							//echo $sqlUpdateEstado."<br>";
							mysqli_query($enlaceCon,$sqlUpdateEstado);
 
						//// FIN DE Obtiener el monto que se debe Pagar del Proceso de Construccion

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

		}
	}
	
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
				location.href='listadoPagosProveedor.php';
			</script>";

?>