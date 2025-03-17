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

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Analisis de Costos Agrupado
	<br>Territorio: $nombre_territorio <br> De: $fecha_ini A: $fecha_fin
	<br>Fecha Reporte: $fecha_reporte</tr></table>";


echo "<br><table align='center' class='texto' width='100%' style='border: red 3px solid;'>
<tr>
	<td>Gastos a Distribuir</td>
	<td style='background-color:yellow;'>$gastosTotalesF</td>
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

echo "<br><table align='center' class='texto' width='100%'>
<tr>
<th>-</th>
<th>Codigo</th>
<th>Producto</th>
<th>Cantidad</th>
<th>Monto Venta</th>
<th>Precio <br> Promedio</th>
<th>Participacion</th>
<th>Costo Insumos <br> Unitario</th>
<th>Costo Insumos <br> Total</th>
<th>Costo Insumos <br> Json</th>
<th>Costo Proceso <br> Unitario</th>
<th>Costo Proceso <br> Total</th>
<th>Json <br> Total</th>
<th>Gasto <br> Distribuido</th>
<th>Costo <br> Unitario</th>
<th>Costo <br> Total</th>
<th>Margen <br> (Costo Vs. Venta)</th>
</tr>";

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

	$tablaInsumos = generarTablaGenerica($jsonInsumos);

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


	$tablaProcesos=generarTablaGenerica2($jsonProcesos);
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
		<td align='center'>$cantidadFormat</td>
		<td align='right'>$montoVentaProductoF</td>	
		<td align='right'>$precioPromedioF</td>	
		<td align='right'>$participacionVentaProductoF %</td>	

		<td align='right' style='border: red 2px solid; background-color: LightPink;'><a href='#' onclick='mostrarInsumosProductos($codProductoFinal,\"$tipoAgrupacion\",$jsonInsumosEncode);'>$costoInsumosF</a></td>	
		<td align='right' style='border: red 2px solid; border: red 2px solid; background-color: LightPink;'>$costoInsumosTotalF</td>

		<td align='right' style='border: red 2px solid; border: red 2px solid; background-color: LightPink;'>$tablaInsumos</td>	
		<td align='right' style='border: red 2px solid; background-color: PaleGreen'><a href='#' onclick='mostrarProcesosProductos($codProductoFinal,\"$tipoAgrupacion\",$jsonProcesosEncode)'>$costoProcesosPromedioF</td>	
		<td align='right' style='border: red 2px solid; background-color: PaleGreen'>$costoProcesosTotalF</td>	

		<td align='right' style='border: red 2px solid; background-color: PaleGreen'>$tablaProcesos</td>	

		<td align='right' style='border: red 2px solid; background-color: Khaki'>$montoGastoDistribuidoF</td>	
		<td align='right' style='border: red 2px solid;'>$costoUnitarioProductoF</td>	
		<td align='right' style='border: red 2px solid;'>$costoTotalProductoF</td>	
		<td align='right' style='border: red 2px solid;'>$margenProductoF %</td>	
	</tr>";
	$indice++;
}
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
	<td align='right'><b>$totalPtr</b></td>
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