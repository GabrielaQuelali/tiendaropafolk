<?php

require("conexionmysqli2.inc");
require("estilos_almacenes.inc");
require("funcionRecalculoCostos.php");
require("funciones.php");


 error_reporting(E_ALL);
 ini_set('display_errors', '1');


$global_agencia=$_COOKIE['global_agencia'];
$global_almacen=$_COOKIE['global_almacen'];

$sql = "select IFNULL(MAX(cod_cotizacion)+1,1) from cotizaciones order by cod_cotizacion desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$codigo=$dat[0];
//echo $sql;

$sql = "select IFNULL(MAX(nro_cotizacion)+1,1) from cotizaciones where cod_almacen='$global_almacen'  order by nro_cotizacion desc";
$resp = mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$nro_correlativo=$dat[0];



$cantidad_material=$_POST['cantidad_material'];
$desc_cotizacion=$_POST['desc_cotizacion'];
//$fecha=$_POST['fecha'];
//echo $fecha."<br/>";
$fecha=date("Y-m-d");
//echo $fecha."<br/>";


$createdBy=$_COOKIE['global_usuario'];
$createdDate=date("Y-m-d H:i:s");



//echo "paso 0 query cabecera";
$consulta="insert into cotizaciones (cod_cotizacion,nro_cotizacion,fecha_cotizacion,desc_cotizacion,cod_estado,cod_almacen,created_by,created_date)values($codigo,$nro_correlativo,'$fecha','$desc_cotizacion',1,$global_almacen,
'$createdBy','$createdDate')";
//echo $consulta."<br/>";
$sql_inserta = mysqli_query($enlaceCon,$consulta);

if($sql_inserta==1){
		$orden=1;
      for ($i = 1; $i <= $cantidad_material; $i++) {
				$cod_material = $_POST["material$i"];
		
				if($cod_material!=0){
					$cantidad=$_POST["cantidad_unitaria$i"];					
					$consulta="insert into cotizaciones_detalle(cod_cotizacion,cod_producto,cantidad,orden) 
					values('$codigo','$cod_material','$cantidad',$orden)";
					//echo $consulta;
					$respuestaConsulta = mysqli_query($enlaceCon,$consulta);

					////Insumos de productos
					$sqlInsumosProductos="select cod_insumo,cod_unidad_medida,cant 
					from insumos_productos where cod_producto=".$cod_material;
					$respInsumosProductos=mysqli_query($enlaceCon,$sqlInsumosProductos);
					while($datInsumosProductos=mysqli_fetch_array($respInsumosProductos)){

						$cod_insumo=$datInsumosProductos['cod_insumo'];
						$cod_unidad_medida=$datInsumosProductos['cod_unidad_medida'];
						$cantInsProdUnit=$datInsumosProductos['cant'];

						/// Costo de Insumo 
						$sqlCostoInsumo="select precio from precios  p
						where codigo_material=".$cod_insumo." and cod_precio=0 and cod_ciudad=".$global_agencia;
						$respCostoInsumo=mysqli_query($enlaceCon,$sqlCostoInsumo);
						$costoInsumoProd=0;
						while($datCostoInsumo=mysqli_fetch_array($respCostoInsumo)){
							$costoInsumoProd=$datCostoInsumo['precio'];

						}
						///// Insertando Cotizacion detalle insumos

						$sqlInsert=" insert into cotizacion_detalle_insumos (cod_cotizacion,cod_producto,cod_insumo,cant_insumoProdUnid,costo_insumoProdUnid)values('$codigo','$cod_material',$cod_insumo,$cantInsProdUnit,$costoInsumoProd)";
						//echo $sqlInsert;
						mysqli_query($enlaceCon,$sqlInsert);

						/////////
					}

					$sqlManoObra="select max( lpc.cod_lote) as cod_lote
									from lotes_produccion lp
								left join lote_procesoconst lpc on (lp.cod_lote=lpc.cod_lote)
								where codigo_material=".$cod_material;
					//echo $sqlManoObra;
					$respManoObra=mysqli_query($enlaceCon,$sqlManoObra);
					while($datManoObra=mysqli_fetch_array($respManoObra)){
						$cod_lote=$datManoObra['cod_lote'];
						if($cod_lote!=null){
						$sqlManoObra2="select lpc.cod_proceso_const,lpc.cod_proveedor,lpc.precio 
						from lote_procesoconst lpc
						left join procesos_construccion pc on (lpc.cod_proceso_const=pc.cod_proceso_const)
						left join proveedores p on (lpc.cod_proveedor=p.cod_proveedor)
						where lpc.cod_lote=".$cod_lote;
						//echo $sqlManoObra2;
						$respManoObra2=mysqli_query($enlaceCon,$sqlManoObra2);
						while($datManoObra2=mysqli_fetch_array($respManoObra2)){
							$cod_proceso_const=$datManoObra2['cod_proceso_const'];
							$cod_proveedor=$datManoObra2['cod_proveedor'];
							$precio=$datManoObra2['precio'];

							$sqlInsertManoObra="insert into cotizaciones_detalle_manoobra (cod_cotizacion,cod_producto,cod_proceso_const,cod_proveedor,precio_proceso_const)
							 values ('$codigo','$cod_material',$cod_proceso_const,$cod_proveedor,$precio)";
							 mysqli_query($enlaceCon,$sqlInsertManoObra);
							 //echo $sqlInsertManoObra;
						}
					}


					}	


					$orden++;
			
	 		  }
	  	}
	
	echo "<script language='Javascript'>
		alert('Los datos fueron insertados correctamente.');
		location.href='navegador_cotizaciones.php?&estado=-1'
		</script>";	
	}else{

	echo "<script language='Javascript'>
		alert('EXISTIO UN ERROR EN LA TRANSACCION, POR FAVOR CONTACTE CON EL ADMINISTRADOR.');
		location.href='navegador_cotizaciones.php?estado=-1'
		</script>";
}

?>