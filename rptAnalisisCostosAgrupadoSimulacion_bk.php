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

$rpt_ver = $_POST['rpt_ver'];


//desde esta parte viene el reporte en si
$fecha_iniconsulta = $fecha_ini;
$fecha_finconsulta = $fecha_fin;


$rpt_territorio = $_POST['rpt_territorio'];
$rptTerritorioString = implode(",", $rpt_territorio);

$fecha_reporte = date("d/m/Y");

$nombre_territorio = nombreTerritorioAgrupado($enlaceCon, $rptTerritorioString);


$arrayGastos="";

$montoVentasTotales=0;
if($rpt_ver==1){
	$arrayGastos = obtenerDetalleGastosMensuales($rptTerritorioString, $fecha_iniconsulta, $fecha_finconsulta);
	$montoVentasTotales = montoVentasSucursalExcepciones($rptTerritorioString, $fecha_iniconsulta, $fecha_finconsulta);
}else{
	$arrayGastos = obtenerDetalleGastos($rptTerritorioString, $fecha_iniconsulta, $fecha_finconsulta);
	$montoVentasTotales = montoVentasSucursal($rptTerritorioString, $fecha_iniconsulta, $fecha_finconsulta);
}


list($gastosTotales, $jsonGastos) = $arrayGastos;
$gastosTotalesF = formatonumeroDec($gastosTotales);

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Analisis de Costos Agrupado - Simulación
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";


echo "<br><table align='center' class='texto' width='100%' style='border: red 3px solid;'>
<tr>
	<td>Gastos a Distribuir</td>
	<td style='background-color:yellow;' id='totalGastoDistribuido'>$gastosTotalesF</td>
</tr></table>";

$sql = "SELECT pc.cod_producto_costo, pc.nombre_producto_costo,
    (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total, 'GRUPO' as tipoagrupacion, pc.costo_si_no
    from salida_almacenes s
    INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
    INNER JOIN producto_costo_detalle pcd ON pcd.cod_producto=sd.cod_material
    INNER JOIN producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
    where s.`fecha` BETWEEN '$fecha_iniconsulta' and '$fecha_finconsulta'
    and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and  
    s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($rptTerritorioString) )";
	if($rpt_ver==1){
		$sql.=" and pc.costo_si_no = 1 ";	
	} 
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
    if($rpt_ver==1){
		$sql.=" and m.costo_si_no=1 ";	
	} 
    $sql.=" GROUP BY m.codigo_material
    order by montoVenta desc";

$resp = mysqli_query($enlaceCon, $sql);

echo "<br>
<table align='center' class='texto' width='100%'>
	<td style='padding:0px;'>
		<label>Margen General:</label>
		<input type='number' class='margen-gral-input' value='' style='width: 10ch;'>
		<button class='btn btn-primary btn-xs pb-1 pt-4 btn_margen_gral' title='Cargar nuevo margen'>
        	<i class='fa fa-check-circle'></i>
		</button>
		<button class='btn btn-warning btn-xs pb-1 pt-4 btn_margen_limpiar' title='Volver a margen anterior'>
			<i class='fa fa-undo'></i>
		</button>
	</td>
</table>
<table align='center' class='texto' width='100%'>
<tr>
<th>-</th>
<th>Codigo</th>
<th>Producto</th>
<th>Cantidad</th>
<th>Monto Venta</th>
<th>Precio <br> Promedio Ant.</th>
<th>Precio <br> Promedio</th>
<th>Participacion</th>
<th>Costo Insumos <br> Unitario</th>
<th>Costo Insumos <br> Total</th>
<th>Costo Proceso <br> Unitario</th>
<th>Costo Proceso <br> Total</th>
<th>Gasto <br> Distribuido</th>
<th>Costo <br> Unitario</th>
<th>Costo <br> Total</th>
<th>Ant.Margen <br> (Costo Vs. Venta) %</th>
<th>Margen <br> (Costo Vs. Venta) %</th>
</tr>

<tbody id='detalleCosto'>";

$totalVenta = 0;
$indice = 1;

$totalSumaInsumos = 0;
$totalSumaProcesos = 0;
$totalSumaGastos = 0;

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

	/*************************************/
	/**** ESTA PARTE ES DE LOS INSUMOS****/
	/*************************************/
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

	$estiloCostoInsumos = "";
	if ($banderaObsInsumos == 1) {
		$estiloCostoInsumos = "style='background-color:red'";
	} else {
		$estiloCostoInsumos = "";
	}

	$totalSumaInsumos = $totalSumaInsumos + $costoInsumosTotal;

	/*************************************/
	/**** FIN  INSUMOS****/
	/*************************************/

	/***********************************************/
	/**** ESTA PARTE SACA LOS COSTOS DE PROCESOS****/
	/***********************************************/
	if ($tipoAgrupacion == 'GRUPO') {
		$arrayCostoProcesos = obtenerCostoProcesosGrupo($codProductoFinal, $fecha_iniconsulta, $fecha_finconsulta, $rptTerritorioString);
		list($costoProcesos, $jsonProcesos, $banderaObsProcesos) = $arrayCostoProcesos;
	} else {
		$arrayCostoProcesos = obtenerCostoProcesos($codProductoFinal, $fecha_iniconsulta, $fecha_finconsulta);
		list($costoProcesos, $jsonProcesos, $banderaObsProcesos) = $arrayCostoProcesos;
	}
	//$jsonProcesos=json_decode($jsonProcesos);
	$estiloCostoProcesos = "";
	if ($banderaObsProcesos == 1) {
		$estiloCostoProcesos = "style='background-color:salmon; border: red 2px solid;'";
	}
	if ($banderaObsProcesos == 2) {
		$estiloCostoProcesos = "style='background-color:LightCyan'; border: red 2px solid;";
	}
	$costoProcesosTotal = $costoProcesos;
	$costoProcesoPromedio = $costoProcesosTotal / $cantidad;
	$costoProcesosPromedioF = formatonumeroDec($costoProcesoPromedio);
	$costoProcesosTotalF = formatonumeroDec($costoProcesosTotal);

	$totalSumaProcesos = $totalSumaProcesos + $costoProcesosTotal;

	$jsonProcesosEncode = json_encode($jsonProcesos);
	/**********************/
	/**** FIN PROCESOS ****/
	/**********************/

	$montoGastoDistribuido = $gastosTotales * ($participacionVentaProducto / 100);
	$montoGastoDistribuidoF = formatonumeroDec($montoGastoDistribuido);

	$totalSumaGastos = $totalSumaGastos + $montoGastoDistribuido;

	$costoTotalProducto = $costoInsumosTotal + $costoProcesosTotal + $montoGastoDistribuido;
	$costoTotalProductoF = formatonumeroDec($costoTotalProducto);

	$costoUnitarioProducto = $costoTotalProducto / $cantidad ;
	$costoUnitarioProductoF = formatonumeroDec($costoUnitarioProducto);

	$margenProducto = (($montoVentaProducto - $costoTotalProducto) / $montoVentaProducto) * 100;
	$margenProductoF = formatonumeroDec($margenProducto);

	echo "<tr>
        <td>$indice</td>
		<td>$codProductoFinal</td>
        <td>$nombreItem</td>
		
		<td align='center'><input type='number' class='cantidad-input' value='$cantidadFormat' style='width: 10ch;'></td>
		<td align='right' class='monto-venta'>$montoVentaProductoF</td>
		<td align='right'>$precioPromedioF</td>
		<td align='right'>
			<input type='number' class='precio-promedio-input' value='$precioPromedioF' style='width: 10ch;'>
		</td>

		<td align='right' class='participacion'>$participacionVentaProductoF %</td>	
		<td align='right' class='costo-insumo-unitario' style='border: red 2px solid; background-color: LightPink;'><a href='#' onclick='mostrarInsumosProductos($codProductoFinal,\"$tipoAgrupacion\",$jsonInsumosEncode);'>$costoInsumosF</a></td>	
		<td align='right' class='costo-insumo-total' style='border: red 2px solid; border: red 2px solid; background-color: LightPink;'>$costoInsumosTotalF</td>	
		<td align='right' class='costo-proceso-unitario' style='border: red 2px solid; background-color: PaleGreen'><a href='#' onclick='mostrarProcesosProductos($codProductoFinal,\"$tipoAgrupacion\",$jsonProcesosEncode)'>$costoProcesosPromedioF</td>	
		<td align='right' class='costo-proceso-total' style='border: red 2px solid; background-color: PaleGreen'>$costoProcesosTotalF</td>	
		<td align='right' class='gasto-distribuido' style='border: red 2px solid; background-color: Khaki'>$montoGastoDistribuidoF</td>	
		<td align='right' class='costo-unitario' style='border: red 2px solid;'>$costoUnitarioProductoF</td>	
		<td align='right' class='costo-total' style='border: red 2px solid;'>$costoTotalProductoF</td>	
		<td align='right' style='border: red 2px solid;'>$margenProductoF</td>	
		<td align='right' style='border: red 2px solid;'>
			<input type='hidden' class='margen-input-ant' value='$margenProductoF' style='width: 10ch;'>
			<input type='number' class='margen-input' value='$margenProductoF' style='width: 10ch;'>
		</td>	
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
	<td align='right'><b>$totalSumaCostoF</b></td>
	<td align='right'><b>$totalMargenProductoF %</b></td>
	<td></td>
<tr>";
echo "</table>";

?>


<!-- small modal -->
<div class="modal fade modal-primary" id="modalInsumosProductos" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
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
	<div class="modal-dialog modal-lg">
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
		 * ? Modificación de Cantidad / Precio Promedio
		 ***********************************************/
		$('#detalleCosto').on('keyup', '.cantidad-input, .precio-promedio-input, .margen-input', function() {
			// Obtén la fila actual
			var $row = $(this).closest('tr');
			
			// Obtiene Datos
			var cantidad   			 = $row.find('.cantidad-input').val() || 0;
			var montoVenta 			 = parseFormattedNumber($row.find('.monto-venta').text().trim());
			var precioPromedio  	 = $row.find('.precio-promedio-input').val() || 0;
			var costoInsumoUnitario  = parseFormattedNumber($row.find('.costo-insumo-unitario').text().trim());
			var costoProcesoUnitario = parseFormattedNumber($row.find('.costo-proceso-unitario').text().trim());
			var costoUnitario 		 = parseFormattedNumber($row.find('.costo-unitario').text().trim());
			var margen  	 		 = $row.find('.margen-input').val() || 0;
			
			// * Calcula el Costo Insumos Total
			var v_costoInsumoTotal = formatNumber(cantidad * costoInsumoUnitario);
			$row.find('.costo-insumo-total').text(v_costoInsumoTotal);
			// * Calcula el Costo Proceso Total
			var v_costoProcesoTotal = formatNumber(cantidad * costoProcesoUnitario);
			$row.find('.costo-proceso-total').text(v_costoProcesoTotal);
			// * Calcula el Costo Total
			var v_costoTotal = formatNumber(cantidad * costoUnitario);
			$row.find('.costo-total').text(v_costoTotal);

			// ! Si se modifica el Margen
			if ($(this).hasClass('margen-input')) {
				// Validación para evitar margen del 100%
				if (margen >= 100) {
					alert('El margen no puede ser 100% o mayor.');
					$row.find('.margen-input').val(99.99);
					margen = 99.99;
				}
				// Modificación de Precio Promedio en base al margen
				precioPromedio = (costoUnitario / (1 - (margen / 100))).toFixed(2);
				$row.find('.precio-promedio-input').val(precioPromedio);
			}else{
				// * Calcula el Margen
				var v_margen = formatNumber(((precioPromedio - costoUnitario) / precioPromedio) * 100);
				$row.find('.margen-input').val(v_margen);
			}
			// * Calcula el Monto Venta
			var v_montoVenta = formatNumber(cantidad * precioPromedio);
			$row.find('.monto-venta').text(v_montoVenta);

			// Calcula totales
			obtieneTotalesFinal();
		});
		/**
		 * Funciòn que calcula el @TotalMontoVenta y @Participación
		 * finalmene se obtiene el Gasto @Distribuido
		 */
		function obtieneTotalesFinal(){
			// Sumar los valores de la columna monto-venta
			var totalMontoVenta = 0;
			$('#detalleCosto .monto-venta').each(function() {
				var montoVenta = parseFormattedNumber($(this).text());
				totalMontoVenta += montoVenta;
			});
			$('#total-monto-venta').text(formatNumber(totalMontoVenta));

			var totalGastoDistribuido = parseFormattedNumber($('#totalGastoDistribuido').text()) || 0;
			// Calcular y actualizar el campo de participación
			$('#detalleCosto .monto-venta').each(function() {
				// Participación
				var montoVenta 	  = parseFormattedNumber($(this).text());
				var participacion = (montoVenta / totalMontoVenta) * 100;
				$(this).closest('tr').find('.participacion').text(formatNumber(participacion) + ' %');
				// Gasto Distribuido
				var gastoDistribuido = totalGastoDistribuido * (participacion / 100);
				$(this).closest('tr').find('.gasto-distribuido').text(formatNumber(gastoDistribuido));
			});
		}
		/**
		 * Modifica Margen General
		 */
		$('body').on('click', '.btn_margen_gral, .btn_margen_limpiar', function(){
			let $this_btn = $(this);
			
			// Todos los Margenes en "Lista"
			if ($this_btn.hasClass('btn_margen_gral')) {
				let margen = $('.margen-gral-input').val();
				$('.margen-input').val(margen);
			}

			$('#detalleCosto tr').each(function() {
				var $row = $(this);

				// Captura Margen Anterior
				if ($this_btn.hasClass('btn_margen_limpiar')) {
					let marge_anterior = $row.find('.margen-input-ant').val();
					$row.find('.margen-input').val(marge_anterior);
				}
        
				// Obtiene Datos
				var cantidad   			 = $row.find('.cantidad-input').val() || 0;
				var montoVenta 			 = parseFormattedNumber($row.find('.monto-venta').text().trim());
				var precioPromedio  	 = $row.find('.precio-promedio-input').val() || 0;
				var costoInsumoUnitario  = parseFormattedNumber($row.find('.costo-insumo-unitario').text().trim());
				var costoProcesoUnitario = parseFormattedNumber($row.find('.costo-proceso-unitario').text().trim());
				var costoUnitario 		 = parseFormattedNumber($row.find('.costo-unitario').text().trim());
				var margen  	 		 = $row.find('.margen-input').val() || 0;
				
				// * Calcula el Costo Insumos Total
				var v_costoInsumoTotal = formatNumber(cantidad * costoInsumoUnitario);
				$row.find('.costo-insumo-total').text(v_costoInsumoTotal);
				// * Calcula el Costo Proceso Total
				var v_costoProcesoTotal = formatNumber(cantidad * costoProcesoUnitario);
				$row.find('.costo-proceso-total').text(v_costoProcesoTotal);
				// * Calcula el Costo Total
				var v_costoTotal = formatNumber(cantidad * costoUnitario);
				$row.find('.costo-total').text(v_costoTotal);

				// Validación para evitar margen del 100%
				if (margen >= 100) {
					alert('El margen no puede ser 100% o mayor.');
					$row.find('.margen-input').val(99.99);
					margen = 99.99;
				}
				// Modificación de Precio Promedio en base al margen
				precioPromedio = (costoUnitario / (1 - (margen / 100))).toFixed(2);
				$row.find('.precio-promedio-input').val(precioPromedio);

				// * Calcula el Monto Venta
				var v_montoVenta = formatNumber(cantidad * precioPromedio);
				$row.find('.monto-venta').text(v_montoVenta);
			});
			// Calcula totales
			obtieneTotalesFinal();
		});
	});
</script>