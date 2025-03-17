<?php
require ('conexionmysqli.php');
require ('estilos_reportes_almacencentral.php');
require ('function_formatofecha.php');
require ('funcion_nombres.php');
require ('funciones.php');

error_reporting(E_ALL);
ini_set('display_errors', '1');

?>
<script>
	function nuevoAjax() {
		var xmlhttp = false;
		try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
		} catch (e) {
			try {
				xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
			} catch (E) {
				xmlhttp = false;
			}
		}
		if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
			xmlhttp = new XMLHttpRequest();
		}
		return xmlhttp;
	}


	function mostrarInsumosProductos(codProducto, tipoagrupacion, jsonProducto) {
		//$("#codigo_salida_cambio").val(codigo);
		$("#modalInsumosProductos").modal("show");
		var contenedor;
		contenedor = document.getElementById("divTablaInsumos");
		ajax = nuevoAjax();

		var datos = {
			codProducto: codProducto,
			jsonProducto: jsonProducto
		};
		var datosCodificados = Object.keys(datos).map(function (key) {
			return encodeURIComponent(key) + '=' + encodeURIComponent(datos[key]);
		}).join('&');

		var url = "";

		url = "ajaxMostrarInsumosProductoGrupo.php";

		ajax.open("POST", url, true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				$('#divTablaInsumos').html(ajax.responseText);
				//contenedor.innerHTML = 
			}
		}
		ajax.send(datosCodificados);
	}


	function mostrarProcesosProductos(codProducto, tipoagrupacion, jsonProducto) {
		//$("#codigo_salida_cambio").val(codigo);
		$("#modalProcesosProductos").modal("show");
		var contenedor;
		contenedor = document.getElementById("divTablaProcesos");
		ajax = nuevoAjax();

		// Construir los datos que deseas enviar
		var datos = {
			codProducto: codProducto,
			jsonProducto: jsonProducto
		};
		var datosCodificados = Object.keys(datos).map(function (key) {
			return encodeURIComponent(key) + '=' + encodeURIComponent(datos[key]);
		}).join('&');

		var url = "ajaxMostrarProcesosProductoGrupo.php";

		ajax.open("POST", url, true);
		ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

		ajax.onreadystatechange = function () {
			if (ajax.readyState == 4) {
				$('#divTablaProcesos').html(ajax.responseText);
				//contenedor.innerHTML = 
			}
		}
		ajax.send(datosCodificados);
	}
</script>

<?php
$sqlUTF = mysqli_query($enlaceCon, "SET NAMES utf8");

$fecha_ini = $_POST['fecha_ini'];
$fecha_fin = $_POST['fecha_fin'];


//desde esta parte viene el reporte en si
$fecha_iniconsulta = $fecha_ini;
$fecha_finconsulta = $fecha_fin;


$rpt_territorio = $_POST['rpt_territorio'];
$rptTerritorioString = implode(",", $rpt_territorio);

$fecha_reporte = date("d/m/Y");

$nombre_territorio = nombreTerritorioAgrupado($enlaceCon, $rptTerritorioString);

$montoVentasTotales=0;
$cantidadVentasTotales=0;

$arrayVentaCantidadTotales = montoVentasSucursal($rptTerritorioString, $fecha_iniconsulta, $fecha_finconsulta); 
list($montoVentasTotales,$cantidadVentasTotales) = $arrayVentaCantidadTotales; 

$arrayMontoCostosTotales = montoCostosInsumosProcesosTotal($rptTerritorioString,$fecha_iniconsulta,$fecha_finconsulta);

list($montoCostoInsumosTotales, $montoCostoProcesosTotales) =  $arrayMontoCostosTotales;

$montoCostoDirectoTotales = $montoCostoInsumosTotales + $montoCostoProcesosTotales;

// echo "costos totales directos: ".$montoCostoDirectoTotales;

$arrayGastosPorTienda="";
$arrayGastosPorTienda = obtenerDetalleGastos($rptTerritorioString, $fecha_iniconsulta, $fecha_finconsulta);
list($gastosTotalesTiendas, $jsonGastos) = $arrayGastosPorTienda;
$gastosTotalesTiendasF = formatonumeroDec($gastosTotalesTiendas);

$arrayGastosTodasLasTiendas="";
$arrayGastosTodasLasTiendas = obtenerDetalleGastos(0, $fecha_iniconsulta, $fecha_finconsulta);
list($gastosTotalesTodasLasTiendas, $jsonGastos) = $arrayGastosTodasLasTiendas;
$gastosTotalesTodasLasTiendasF = formatonumeroDec($gastosTotalesTodasLasTiendas);

//echo "GASTOS TOTALES: ".$gastosTotalesTiendasF." ".$gastosTotalesTodasLasTiendasF;


echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Analisis de Costos y Precios
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";

$sql = "SELECT pc.cod_producto_costo, pc.nombre_producto_costo,
    (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total, 'GRUPO' as tipoagrupacion, pc.costo_si_no
    from salida_almacenes s
    INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
    INNER JOIN producto_costo_detalle pcd ON pcd.cod_producto=sd.cod_material
    INNER JOIN producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
    where s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
    and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and  
    s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($rptTerritorioString) )";
$sql.=" GROUP BY pc.cod_producto_costo
    UNION 
    SELECT m.codigo_material, m.descripcion_material,
    (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total,'PRODUCTO'  as tipoagrupacion, m.costo_si_no
    from salida_almacenes s
    INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
    INNER JOIN material_apoyo m ON sd.cod_material=m.codigo_material
    LEFT JOIN producto_costo_detalle pcd ON pcd.cod_producto=m.codigo_material
    LEFT JOIN  producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
    where s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
    and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and  
    s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($rptTerritorioString) ) and 
    pcd.cod_producto is null "; 
    $sql.=" GROUP BY m.codigo_material
    order by montoVenta desc";

//echo $sql;
$resp = mysqli_query($enlaceCon, $sql);

echo "<br>
<table align='center' class='texto' width='100%'>
<tr>
<th>-</th>
<th>Codigo</th>
<th>Producto</th>
<th>Cantidad</th>
<th>Monto Venta</th>
<th>Costo Directo <br> Insumos</th>
<th>Costo Directo <br> Procesos</th>
<th>Total Costo Directo</th>
<th>Comision</th>
<th>% Cantidad</th>
<th>% Costo</th>
<th>% Venta</th>
<th>% Promedio</th>
<th>Otros GO</th>
<th>CostoDirecto[u]</th>
<th>CostoIndirecto[u]</th>
<th>CostoUnitario</th>
<th>Precio Venta</th>
<th>Ganancia</th>
<th>Margen</th>

<th><small>Precio Consignacion s/f</small></th>
<th><small>Margen</small></th>

<th><small>Precio VentaDirecta s/f</small></th>
<th><small>Margen</small></th>

<th><small>Precio Sugerido s/f</small></th>
<th><small>Margen</small></th>

<th style='display:none;'><small>P. Consignacion c/f</small></th>
<th style='display:none;'><small>P. VentaDirecta c/f</small></th>
<th style='display:none;'><small>P. Sugerido c/f</small></th>

</tr>

<tbody id='detalleCosto'>";

$totalVenta = 0;
$indice = 1;

$totalSumaInsumos = 0;
$totalSumaProcesos = 0;
$totalSumaGastos = 0;
$totalSumaGanancias = 0;

while ($datos = mysqli_fetch_array($resp)) {
	$codProductoFinal = $datos[0];
	$nombreItem = $datos[1];
	$montoVentaProducto = $datos[2];
	$cantidad = $datos[3];
	$descuentoVenta = $datos[4];
	$montoNota = $datos[5];
	$tipoAgrupacion = $datos[6];
	$banderaAnalisisCosto=$datos[7];

	if ($tipoAgrupacion == 'GRUPO') {
		$nombreItem = "<span class='textomedianorojo'>$nombreItem</span>";
	} else {
		$nombreItem = "<b>$nombreItem</b>";
	}

	$montoVentaProductoF = number_format($montoVentaProducto, 2, ".", ",");
	$cantidadFormat = number_format($cantidad, 0, ".", ",");
	$precioPromedio = $montoVentaProducto / $cantidad;
	$precioPromedioF = formatonumeroDec($precioPromedio);
	$participacionVentaProducto = ($montoVentaProducto / $montoVentasTotales) * 100;
	$participacionVentaProductoF = formatonumeroDec($participacionVentaProducto);

	$totalVenta = $totalVenta + $montoVentaProducto;

	/*ESTA PARTE SACA LOS PRECIOS DISTINTOS QUE HAY EN LAS VENTAS*/
	$titlePreciosDetalle = preciosVentaDistintos($rptTerritorioString, $codProductoFinal, $fecha_iniconsulta, $fecha_finconsulta);
	/*FIN PRECIOS*/

	/**** ESTA PARTE ES DE LOS INSUMOS****/
	if ($tipoAgrupacion == 'GRUPO') {
		$arrayCostoInsumos = obtenerCostoInsumosGrupo($codProductoFinal, $fecha_iniconsulta, $fecha_finconsulta, $rptTerritorioString);
		list($costoInsumosTotal, $jsonInsumos, $banderaObsInsumos) = $arrayCostoInsumos;
		$costoInsumosTotalF = formatonumeroDec($costoInsumosTotal);
		$costoInsumos = $costoInsumosTotal / $cantidad;
		$costoInsumosF = formatonumeroDec($costoInsumos);
	} else {
		$arrayCostoInsumos = obtenerCostoInsumosProducto($codProductoFinal, $fecha_iniconsulta, $fecha_finconsulta, $rptTerritorioString);
		list($costoInsumos, $jsonInsumos, $banderaObsInsumos) = $arrayCostoInsumos;
		$costoInsumosF = formatonumeroDec($costoInsumos);
		$costoInsumosTotal = $costoInsumos * $cantidad;
		$costoInsumosTotalF = formatonumeroDec($costoInsumosTotal);
	}

	$jsonInsumosEncode = json_encode($jsonInsumos);

	$totalSumaInsumos = $totalSumaInsumos + $costoInsumosTotal;
	/**** ESTA PARTE SACA LOS COSTOS DE PROCESOS****/
	if ($tipoAgrupacion == 'GRUPO') {
		$arrayCostoProcesos = obtenerCostoProcesosGrupo($codProductoFinal, $fecha_iniconsulta, $fecha_finconsulta, $rptTerritorioString);
		list($costoProcesos, $jsonProcesos, $banderaObsProcesos) = $arrayCostoProcesos;
	} else {
		$arrayCostoProcesos = obtenerCostoProcesos($codProductoFinal, $fecha_iniconsulta, $fecha_finconsulta);
		list($costoProcesos, $jsonProcesos, $banderaObsProcesos) = $arrayCostoProcesos;
	}	
	$costoProcesosTotalProducto = $costoProcesos * $cantidad;
	$costoProcesosTotalProductoF = formatonumeroDec($costoProcesosTotalProducto);


	$jsonProcesosEncode = json_encode($jsonProcesos);


	$totalCostoDirectoProducto=$costoProcesosTotalProducto + $costoInsumosTotal;
	$totalCostoDirectoProductoF=formatonumeroDec($totalCostoDirectoProducto);

	$porcentajeCantidadProducto = $cantidad / $cantidadVentasTotales;
	$porcentajeVentaProducto = $montoVentaProducto / $montoVentasTotales;
	$porcentajeCostoProducto = $totalCostoDirectoProducto / $montoCostoDirectoTotales;

	$porcentajeCantidadProductoF = formatonumero($porcentajeCantidadProducto*100);
	$porcentajeVentaProductoF = formatonumero($porcentajeVentaProducto*100);
	$porcentajeCostoProductoF = formatonumero($porcentajeCostoProducto*100);

	$porcentajeProductoPromedio= (($porcentajeCantidadProducto + $porcentajeVentaProducto + $porcentajeCostoProducto) / 3);
	$porcentajeProductoPromedioF = formatonumero($porcentajeProductoPromedio*100);


	$montoComisionTiendaProducto = $gastosTotalesTiendas * $porcentajeProductoPromedio;
	$montoComisionTiendaProductoF = formatonumeroDec($montoComisionTiendaProducto);

	$montoOtrosGOProducto = $gastosTotalesTodasLasTiendas * $porcentajeProductoPromedio;
	$montoOtrosGOProductoF = formatonumeroDec($montoOtrosGOProducto);

	$totalCostoDirectoProducto = $totalCostoDirectoProducto / $cantidad;
	$totalCostoDirectoProductoF = formatonumeroDec($totalCostoDirectoProducto);
	
	$totalCostoIndirectoProducto = $montoComisionTiendaProducto / $cantidad;
	$totalCostoIndirectoProductoF = formatonumeroDec($totalCostoIndirectoProducto);

	$totalCostoProductoUnitario = $totalCostoDirectoProducto + $totalCostoIndirectoProducto;
	$totalCostoProductoUnitarioF = formatonumeroDec($totalCostoProductoUnitario);

	$precioMayorProducto=preciosMayorProducto($rptTerritorioString, $codProductoFinal, $fecha_iniconsulta, $fecha_finconsulta,$tipoAgrupacion);
	$precioMayorProductoF=formatonumeroDec($precioMayorProducto);

	$gananciaProducto = $precioMayorProducto - $totalCostoProductoUnitario;
	$gananciaProductoF = formatonumeroDec($gananciaProducto);

	$margenGananciaProducto = 1 - ($totalCostoProductoUnitario/$precioMayorProducto);
	$margenGananciaProductoF = formatonumeroDec($margenGananciaProducto*100);




	echo "<tr>
        <td>$indice</td>
		<td>$codProductoFinal</td>
        <td>$nombreItem</td>
		
		<td align='center'>$cantidadFormat</td>
		<td align='right'>$montoVentaProductoF</td>

		<td align='right' class='costo-insumo-total'>
			<a href='#' onclick='mostrarInsumosProductos($codProductoFinal,\"$tipoAgrupacion\",$jsonInsumosEncode);'>$costoInsumosTotalF</a>
		</td>

		<td align='right' class='costo-insumo-total'>
			<a href='#' onclick='mostrarProcesosProductos($codProductoFinal,\"$tipoAgrupacion\",$jsonProcesosEncode)'>$costoProcesosTotalProductoF</a>
		</td>	
		
		<td align='right'>$totalCostoDirectoProductoF</td>

		<td align='right'>$montoComisionTiendaProductoF</td>

		<td align='right'>$porcentajeCantidadProductoF %</td>
		<td align='right'>$porcentajeCostoProductoF %</td>
		<td align='right'>$porcentajeVentaProductoF %</td>
		<td align='right'>$porcentajeProductoPromedioF %</td>
		<td align='right'>$montoOtrosGOProductoF</td>
		<td align='right'>$totalCostoDirectoProductoF</td>
		<td align='right'>$totalCostoIndirectoProductoF</td>
		<td align='right' class='total-costo-unitario'>$totalCostoProductoUnitarioF</td>
		<td align='right'>$precioMayorProductoF</td>
		<td align='right'>$gananciaProductoF</td>
		<td align='right'>$margenGananciaProductoF %</td>

		<td align='center' style='border: gray 1px solid; background-color: LightBlue'><input type='number' class='precio-consignacion-sf' value='0' style='width: 10ch;'></td>
		<td align='center' style='border: gray 1px solid; background-color: LightBlue' class='margen-consignacion-sf'>-</td>

		<td align='center' style='border: gray 1px solid; background-color: LightBlue'><input type='number' class='precio-ventadirecta-sf' value='0' style='width: 10ch;'></td>
		<td align='center' style='border: gray 1px solid; background-color: LightBlue' class='margen-ventadirecta-sf'>-</td>

		<td align='center' style='border: gray 1px solid; background-color: LightBlue'><input type='number' class='precio-sugerido-sf' value='0' style='width: 10ch;'></td>
		<td align='center' style='border: gray 1px solid; background-color: LightBlue' class='margen-sugerido-sf'>-</td>

		<td align='center' style='display:none; border: gray 1px solid; background-color: LightBlue' class='precio-consignacion-cf'>-</td>
		<td align='center' style='display:none; border: gray 1px solid; background-color: LightBlue' class='precio-ventadirecta-cf'>-</td>
		<td align='center' style='display:none; border: gray 1px solid; background-color: LightBlue' class='precio-sugerido-cf'>-</td>
		
	</tr>";
	$indice++;
}
echo "</tbody>";
$totalPtr = number_format($totalVenta, 2, ".", ",");
$totalSumaInsumosF = formatonumeroDec($totalSumaInsumos);
$totalSumaProcesosF = formatonumeroDec($totalSumaProcesos);
$totalSumaGastosF = formatonumeroDec($totalSumaGastos);

$totalSumaCosto = $totalSumaInsumos + $totalSumaProcesos + $totalSumaGastos;
$totalSumaCostoF = formatonumeroDec($totalSumaCosto);

$totalSumaGananciasF = formatonumeroDec($totalSumaGanancias);

$totalMargenProductoF = formatonumeroDec((($totalVenta - $totalSumaCosto) / $totalVenta) * 100);


echo "<tr>
    <td>&nbsp;</td>
	<td>&nbsp;</td>
    <td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align='right'><b id='total-monto-venta'>$totalPtr</b></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align='right'><b>$totalSumaInsumosF</b></td>
	<td>&nbsp;</td>
	<td align='right'><b>$totalSumaProcesosF</b></td>
	<td align='right'><b>$totalSumaGastosF</b></td>
	<td>&nbsp;</td>
	<td align='right'><b id='total-suma-costo'>$totalSumaCostoF</b></td>
	<td>&nbsp;</td>
	<td><b id='ganancia-total-final'>$totalSumaGananciasF</b></td>
	<td align='right'><b>$totalMargenProductoF %</b></td>
	<tr>";
echo "</table>";


echo "<center><input type='button' class='boton2' value='Guardar Analisis'></center>";

?>


<!-- small modal -->
<div class="modal fade modal-primary" id="modalInsumosProductos" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content card">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon" style="background: #96079D;color:#fff;">
					<i class="material-icons">checklist</i>
				</div>
				<h4 class="card-title text-dark font-weight-bold">Detalle de Insumos <small id="titulo_tarjeta"></small>
				</h4>
				<button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal"
					aria-hidden="true" style="position:absolute;top:0px;right:0;">
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
<div class="modal fade modal-primary" id="modalProcesosProductos" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content card">
			<div class="card-header card-header-primary card-header-icon">
				<div class="card-icon" style="background: #96079D;color:#fff;">
					<i class="material-icons">checklist</i>
				</div>
				<h4 class="card-title text-dark font-weight-bold">Detalle de Procesos <small
						id="titulo_tarjeta"></small></h4>
				<button type="button" class="btn btn-danger btn-sm btn-fab float-right" data-dismiss="modal"
					aria-hidden="true" style="position:absolute;top:0px;right:0;">
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

<script>
	$(document).ready(function() {
		function formatNumber(num) {
			return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
		}

		function parseFormattedNumber(numStr) {
			return parseFloat(numStr.replace(/,/g, ''));
		}

		/***********************************************
		 * ? Calculo de Precios Adicionales
		 ***********************************************/
		// 
		$('#detalleCosto').on('keyup', '.precio-consignacion-sf', function() {
			// Obtén la fila actual
			var $row = $(this).closest('tr');
			
			// Obtiene Datos
			var precioConsignacionSF = $row.find('.precio-consignacion-sf').val() || 0;
			var totalCostoUnitario = parseFormattedNumber($row.find('.total-costo-unitario').text().trim());

			var v_margenConsignacionSF = formatNumber((1-(totalCostoUnitario/precioConsignacionSF))*100);
			console.log('calculando: '+precioConsignacionSF+' '+totalCostoUnitario);
			$row.find('.margen-consignacion-sf').text(v_margenConsignacionSF+' %');

			var v_precioConsignacionCF = formatNumber(precioConsignacionSF / 0.84)
			$row.find('.precio-consignacion-cf').text(v_precioConsignacionCF);
		});

		$('#detalleCosto').on('keyup', '.precio-ventadirecta-sf', function() {
			// Obtén la fila actual
			var $row = $(this).closest('tr');
			
			// Obtiene Datos
			var precioSF = $row.find('.precio-ventadirecta-sf').val() || 0;
			var totalCostoUnitario = parseFormattedNumber($row.find('.total-costo-unitario').text().trim());

			var v_margenSF = formatNumber((1-(totalCostoUnitario/precioSF))*100);
			console.log('calculando: '+precioSF+' '+totalCostoUnitario);
			$row.find('.margen-ventadirecta-sf').text(v_margenSF+' %');

			var v_precioCF = formatNumber(precioSF / 0.84)
			$row.find('.precio-ventadirecta-cf').text(v_precioCF);
		});

		$('#detalleCosto').on('keyup', '.precio-sugerido-sf', function() {
			// Obtén la fila actual
			var $row = $(this).closest('tr');
			
			// Obtiene Datos
			var precioSF = $row.find('.precio-sugerido-sf').val() || 0;
			var totalCostoUnitario = parseFormattedNumber($row.find('.total-costo-unitario').text().trim());

			var v_margenSF = formatNumber((1-(totalCostoUnitario/precioSF))*100);
			console.log('calculando: '+precioSF+' '+totalCostoUnitario);
			$row.find('.margen-sugerido-sf').text(v_margenSF+' %');

			var v_precioCF = formatNumber(precioSF / 0.84)
			$row.find('.precio-sugerido-cf').text(v_precioCF);
		});
	});
</script>