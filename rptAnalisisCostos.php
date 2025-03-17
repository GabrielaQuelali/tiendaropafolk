<script>	
	function nuevoAjax(){	
		var xmlhttp=false;
		try {
				xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
		} catch (e) {
		try {
			xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
		} catch (E) {
			xmlhttp = false;
		}
		}
		if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
		}
		return xmlhttp;
	}


	function mostrarInsumosProductos(codProducto,jsonProducto){
		//$("#codigo_salida_cambio").val(codigo);
		$("#modalInsumosProductos").modal("show");   
		var contenedor;
		contenedor=document.getElementById("divTablaInsumos");
		ajax=nuevoAjax();
		ajax.open("GET", "ajaxMostrarInsumosProducto.php?codProducto="+codProducto+"&jsonProducto="+jsonProducto,true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				$('#divTablaInsumos').html(ajax.responseText);
				//contenedor.innerHTML = 
			}
		}
		ajax.send(null);
	}

	function mostrarProcesosProductos(codProducto,jsonProducto){
		//$("#codigo_salida_cambio").val(codigo);
		$("#modalProcesosProductos").modal("show");   
		var contenedor;
		contenedor=document.getElementById("divTablaProcesos");
		ajax=nuevoAjax();
		ajax.open("GET", "ajaxMostrarProcesosProducto.php?codProducto="+codProducto+"&jsonProducto="+jsonProducto,true);
		ajax.onreadystatechange=function() {
			if (ajax.readyState==4) {
				$('#divTablaProcesos').html(ajax.responseText);
				//contenedor.innerHTML = 
			}
		}
		ajax.send(null);
	}
</script>

<?php
require('conexionmysqli.php');
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('funcion_nombres.php');
require('funciones.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

$sqlUTF=mysqli_query($enlaceCon,"SET NAMES utf8");

$fecha_ini=$_POST['fecha_ini'];
$fecha_fin=$_POST['fecha_fin'];


//desde esta parte viene el reporte en si
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;


$rpt_territorio=$_POST['rpt_territorio'];
$rptTerritorioString=implode(",",$rpt_territorio);

$fecha_reporte=date("d/m/Y");

$nombre_territorio=nombreTerritorioAgrupado($enlaceCon, $rptTerritorioString);
$montoVentasTotales=montoVentasSucursal($rptTerritorioString, $fecha_iniconsulta, $fecha_finconsulta);
//echo $montoVentasTotales;

$arrayGastos=obtenerDetalleGastos($rptTerritorioString, $fecha_iniconsulta, $fecha_finconsulta);
list($gastosTotales, $jsonGastos) = $arrayGastos;
$gastosTotalesF=formatonumeroDec($gastosTotales);

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Analisis de Costos
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";

	
echo "<br><table align='center' class='texto' width='100%' style='border: red 3px solid;'>
<tr>
	<td>Gastos a Distribuir</td>
	<td style='background-color:yellow;'>$gastosTotalesF</td>
</tr></table>";

$sql="select m.`codigo_material`, m.`descripcion_material`, 
	(sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total
	from `salida_almacenes` s, `salida_detalle_almacenes` sd, `material_apoyo` m 
	where s.`cod_salida_almacenes`=sd.`cod_salida_almacen` and s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
	and s.`salida_anulada`= 1 and sd.`cod_material`=m.`codigo_material` and s.`cod_tiposalida`=1001 and  
	s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($rptTerritorioString) )
	group by m.`codigo_material` order by montoVenta desc;";
	
$resp=mysqli_query($enlaceCon,$sql);

echo "<br><table align='center' class='texto' width='100%'>
<tr>
<th>Codigo</th>
<th>Producto</th>
<th>Cantidad</th>
<th>Monto Venta</th>
<th>Precio <br> Promedio</th>
<th>Participacion</th>
<th>Costo Insumos <br> Unitario</th>
<th>Costo Insumos <br> Total</th>
<th>Costo Proceso <br> Unitario</th>
<th>Costo Proceso <br> Total</th>
<th>Gasto <br> Distribuido</th>
<th>Costo <br> Total</th>
<th>Margen <br> (Costo Vs. Venta)</th>
</tr>";

$totalVenta=0;
while($datos=mysqli_fetch_array($resp)){	
	$codProductoFinal=$datos[0];
	$nombreItem=$datos[1];
	
	$montoVentaProducto=$datos[2];
	$cantidad=$datos[3];

	$descuentoVenta=$datos[4];
	$montoNota=$datos[5];
	
	if($descuentoVenta>0){
		$porcentajeVentaProd=($montoVentaProducto/$montoNota);
		$descuentoAdiProducto=($descuentoVenta*$porcentajeVentaProd);
		$montoVentaProducto=$montoVentaProducto-$descuentoAdiProducto;
	}
	$montoVentaProductoF=number_format($montoVentaProducto,2,".",",");
	$cantidadFormat=number_format($cantidad,0,".",",");
	$precioPromedio=$montoVentaProducto/$cantidad;
	$precioPromedioF=formatonumeroDec($precioPromedio);
	$participacionVentaProducto=($montoVentaProducto/$montoVentasTotales)*100;
	$participacionVentaProductoF=formatonumeroDec($participacionVentaProducto); 

	$totalVenta=$totalVenta+$montoVentaProducto;

	/*************************************/
	/**** ESTA PARTE ES DE LOS INSUMOS****/
	/*************************************/
	$arrayCostoInsumos=obtenerCostoInsumosProducto($codProductoFinal);
	//var_dump($arrayCostoInsumos);
	list($costoInsumos, $jsonInsumos, $banderaObsInsumos) = $arrayCostoInsumos;
	
	$costoInsumosF=formatonumeroDec($costoInsumos);
	$costoInsumosTotal=$costoInsumos*$cantidad;
	$costoInsumosTotalF=formatonumeroDec($costoInsumosTotal);
	//echo $costoInsumos." ".$jsonInsumos." ".$banderaObsInsumos."<br>";

	$estiloCostoInsumos="";
	if($banderaObsInsumos==1){
		$estiloCostoInsumos="style='background-color:salmon'";
	}else{
		$estiloCostoInsumos= "";
	}
	$jsonInsumosDecode=json_encode($jsonInsumos);
	//$tablaHTMLInsumos=generarTablaHTML($jsonInsumos);
	/*************************************/
	/**** FIN  INSUMOS****/
	/*************************************/

	/***********************************************/
	/**** ESTA PARTE SACA LOS COSTOS DE PROCESOS****/
	/***********************************************/
	$arrayCostoProcesos=obtenerCostoProcesos($codProductoFinal, $fecha_iniconsulta, $fecha_finconsulta);
	list($costoProcesos, $jsonProcesos, $banderaObsProcesos) = $arrayCostoProcesos;
	
	$jsonProcesos=json_encode($jsonProcesos);

	$tablaHTMLProcesos=generarTablaProcesos($jsonProcesos);
	//$jsonProcesos=json_decode($jsonProcesos);

	$estiloCostoProcesos="";
	if($banderaObsProcesos==1){
		$estiloCostoProcesos="style='background-color:salmon; border: red 2px solid;'";
	}
	if($banderaObsProcesos==2){
		$estiloCostoProcesos="style='background-color:LightCyan'; border: red 2px solid;";
	}
	$costoProcesosTotal=$costoProcesos;
	$costoProcesoPromedio=$costoProcesosTotal/$cantidad;
	$costoProcesosPromedioF=formatonumeroDec($costoProcesoPromedio);
	$costoProcesosTotalF=formatonumeroDec($costoProcesosTotal);
	/**********************/
	/**** FIN PROCESOS ****/
	/**********************/

	$montoGastoDistribuido=$gastosTotales*($participacionVentaProducto/100);
	$montoGastoDistribuidoF=formatonumeroDec($montoGastoDistribuido);
	
	$costoTotalProducto=$costoInsumosTotal+$costoProcesosTotal+$montoGastoDistribuido;
	$costoTotalProductoF=formatonumeroDec($costoTotalProducto);

	$margenProducto=(($montoVentaProducto-$costoTotalProducto)/$montoVentaProducto)*100;
	$margenProductoF=formatonumeroDec($margenProducto);

	echo "<tr>
		<td>$codProductoFinal</td>
		<td>$nombreItem</td>
		<td align='center'>$cantidadFormat</td>
		<td align='right'>$montoVentaProductoF</td>	
		<td align='right'>$precioPromedioF</td>	
		<td align='right'>$participacionVentaProductoF %</td>	
		<td align='right' $estiloCostoInsumos style='border: red 2px solid;'><a href='#' onclick='mostrarInsumosProductos($codProductoFinal,$jsonInsumosDecode);'>$costoInsumosF</a></td>	
		<td align='right' $estiloCostoInsumos style='border: red 2px solid;'>$costoInsumosTotalF</td>	
		<td align='right' $estiloCostoProcesos style='border: red 2px solid;'><a href='#' onclick='mostrarProcesosProductos($codProductoFinal,$jsonProcesos)'>$costoProcesosPromedioF</td>	
		<td align='right' $estiloCostoProcesos style='border: red 2px solid;'>$costoProcesosTotalF</td>	
		<td align='right' style='border: red 2px solid;'>$montoGastoDistribuidoF</td>	
		<td align='right' style='border: red 2px solid;'>$costoTotalProductoF</td>	
		<td align='right' style='border: red 2px solid;'>$margenProductoF</td>	
	</tr>";
}
$totalPtr=number_format($totalVenta,2,".",",");
echo "<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>Total:</td>
	<td>$totalPtr</td>
<tr>";
echo "</table>";

?>


<!-- small modal -->
<div class="modal fade modal-primary" id="modalInsumosProductos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content card">
			<div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon" style="background: #96079D;color:#fff;">
                    <i class="material-icons">checklist</i>
                  </div>
                  <h4 class="card-title text-dark font-weight-bold">Detalle de Insumos <small id="titulo_tarjeta"></small></h4>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
			<div class="card-body">
			<div class="row">
				<div class="col-sm-12">
					<div class="row">
						<div id='divTablaInsumos'>
						</div>
					</div>                
				</div>
			</div>             	   
	</div>
	</div>  
    </div>
</div>
<!--    end small modal -->


<!-- small modal -->
<div class="modal fade modal-primary" id="modalProcesosProductos" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content card">
			<div class="card-header card-header-primary card-header-icon">
                  <div class="card-icon" style="background: #96079D;color:#fff;">
                    <i class="material-icons">checklist</i>
                  </div>
                  <h4 class="card-title text-dark font-weight-bold">Detalle de Procesos <small id="titulo_tarjeta"></small></h4>
                  <button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal" aria-hidden="true" style="position:absolute;top:0px;right:0;">
                    <i class="material-icons">close</i>
                  </button>
                </div>
			<div class="card-body">
			<div class="row">
				<div class="col-sm-12">
					<div class="row">
						<div id='divTablaProcesos'>
						</div>
					</div>                
				</div>
			</div>             	   
	</div>
	</div>  
    </div>
</div>
<!--    end small modal -->