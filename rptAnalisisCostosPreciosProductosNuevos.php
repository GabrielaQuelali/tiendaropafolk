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


	function mostrarInsumosProductos(codProducto, jsonProducto) {
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

		url = "ajaxMostrarInsumosBase.php";

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


	function mostrarProcesosProductos(codProducto, jsonProducto) {
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

		var url = "ajaxMostrarProcesosBase.php";

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

//$fecha_ini = $_POST['fecha_ini'];


$rpt_territorio = $_POST['rpt_territorio'];
$rptTerritorioString = implode(",", $rpt_territorio);


$rpt_productos = $_POST['rpt_productos'];
$rptProductosString = implode(",", $rpt_productos);


$fecha_reporte = date("d/m/Y");

$nombre_territorio = nombreTerritorioAgrupado($enlaceCon, $rptTerritorioString);

$montoVentasTotales=0;
$cantidadVentasTotales=0;

$costoProductosTotalesReporte = obtenerCostosTotalesUnitariosProducto($rptProductosString, $rptTerritorioString);
//echo "costos totales directos: ".$costoProductosTotalesReporte;

//<br> De: $fecha_ini A: $fecha_fin

echo "<form method='POST' action='guardar_analisisnuevos.php'>";

echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Analisis de Costos y Precios
	<br>Territorio: $nombre_territorio 
	<br>Fecha Reporte: $fecha_reporte
	<br>Glosa: <input type='text' class='texto' name='glosa' id='glosa' size='50'>
	</tr></table>";

$sql = "SELECT m.codigo_material, m.descripcion_material, (0)montoVenta, (0)cantidad_unitaria, 0 as descuento, 0 as monto_total, 'PRODUCTO' as tipoagrupacion, pc.costo_si_no 
from material_apoyo m 
LEFT JOIN producto_costo_detalle pcd ON pcd.cod_producto=m.codigo_material 
LEFT JOIN producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo 
where m.codigo_material in ($rptProductosString) and pcd.cod_producto is null 
GROUP BY m.codigo_material";

//echo $sql;

$resp = mysqli_query($enlaceCon, $sql);


echo "<br>
<table align='center' class='texto' width='100%'>
<tr>
<th>-</th>
<th>Codigo</th>
<th>Producto</th>
<th>Costo Directo <br> Insumos</th>
<th>Costo Directo <br> Procesos</th>
<th>Total Costo Directo</th>

<th>Cantidad a Producir</th>
<th>Horas</th>

<th>% Costo Directo</th>
<th>% Cantidad Producir</th>

<th>Costo Indirecto Distribuido</th>
<th>Costo Indirecto Unitario</th>
<th>Costo Total [u]</th>

<th><small>Precio Consignacion s/f</small></th>
<th><small>Margen</small></th>

<th><small>Precio VentaDirecta s/f</small></th>
<th><small>Margen</small></th>

<th><small>Precio Sugerido s/f</small></th>
<th><small>Margen</small></th>

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


	$costoInsumosArray = obtenerCostoInsumosProductoUnitario($codProductoFinal, $rptTerritorioString);
	
	//var_dump($costoInsumosArray);

	$costoInsumos = $costoInsumosArray[0];
	$jsonInsumos = $costoInsumosArray[1];

	$jsonInsumosEncode = json_encode($jsonInsumos);

	$costoInsumosF = formatonumeroDec($costoInsumos);

	//$totalSumaInsumos = $totalSumaInsumos + $costoInsumosTotal;
	//$costoProcesos = obtenerCostoProcesosUnitario($codProductoFinal);


	$costoProcesos = obtenerCostoProcesosUnitarioBase($codProductoFinal);
	$jsonProcesosEncode = json_encode(obtenerCostoProcesosUnitarioBaseJson($codProductoFinal));

	//var_dump($jsonProcesosEncode)."<br>";

	$costoProcesosF = formatonumeroDec($costoProcesos);

	$totalCostoDirectoProducto=$costoProcesos + $costoInsumos;
	$totalCostoDirectoProductoF=formatonumeroDec($totalCostoDirectoProducto);

	$porcentajeCostoProducto = ($totalCostoDirectoProducto / $costoProductosTotalesReporte)*100;
	$porcentajeCostoProductoF = formatonumeroDec($porcentajeCostoProducto);



	$porcentajeProductoPromedio= (($porcentajeCantidadProducto + $porcentajeVentaProducto + $porcentajeCostoProducto) / 3);
	$porcentajeProductoPromedioF = formatonumero($porcentajeProductoPromedio*100);




	echo "<tr>
        <td><input type='checkbox' name='guardar[]' value='$codProductoFinal' checked></td>
		<td>
			<input type='hidden' name='codigo[]' value='$codProductoFinal'>
			$codProductoFinal
		</td>

        <td>
        	<input type='hidden' name='nombre_producto[]' value='$nombreItem'>
        	$nombreItem
    	</td>
		
		<td align='right' class='costo-insumo-total'>
			<input type='hidden' name='costo_insumos[]' value='$costoInsumosF'>
			<a href='#' onclick='mostrarInsumosProductos($codProductoFinal,$jsonInsumosEncode);'>$costoInsumosF</a>
		</td>	
		
		<td align='right' class='costo-insumo-total'>
			<input type='hidden' name='costo_procesos[]' value='$costoProcesosF'>
			<a href='#' onclick='mostrarProcesosProductos($codProductoFinal,$jsonProcesosEncode);'>$costoProcesosF</a>
		</td>	
		
		<td align='right' class='costo-directo-producto'>
			<input type='hidden' name='total_costo_directo[]' value='$totalCostoDirectoProductoF'>
			$totalCostoDirectoProductoF
		</td>

		<td align='center' style='border: gray 1px solid; background-color: LightSalmon'>
			<input type='number' class='cantidad-producir' name='cantidad-producir[]'  value='0' style='width: 10ch;'>
		</td>

		<td align='center' style='border: gray 1px solid; background-color: LightSalmon'>
			<input type='number' class='horas-produccion' name='horas-produccion[]' value='0' style='width: 10ch;'>
		</td>

		<td align='right' class='porcentaje-costo-unitario'>
			<input type='hidden' name='porcentaje-costo-unitario[]' value='$porcentajeCostoProductoF'>
			$porcentajeCostoProductoF
		</td>

		<td align='right' class='porcentaje-cantidad-producir'>-</td>

		<td style='display:none;'><input type='hidden' class='costo_indirecto_distribuido' name='costo_indirecto_distribuido[]' value='0' style='width: 10ch;'></td>
		<td align='right' class='costo-indirecto-distribuido'>-</td>
		
		<td style='display:none;'><input type='hidden' class='costo_indirecto_unitario' name='costo_indirecto_unitario[]' value='0' style='width: 10ch;'></td>
		<td align='right' class='costo-indirecto-unitario'>-</td>
		
		<td style='display:none;'><input type='hidden' class='costo_total_unitario' name='costo_total_unitario[]' value='0' style='width: 10ch;'></td>
		<td align='right' class='total-costo-unitario'>-</td>

		
		<td align='center' style='border: gray 1px solid; background-color: LightBlue'>
			<input type='number' class='precio-consignacion-sf' name='precio-consignacion-sf[]' value='0' style='width: 10ch;' step='0.01'>
		</td>
		
		<td align='center' style='border: gray 1px solid; background-color: LightBlue' class='margen-consignacion-sf'>-</td>

		<td align='center' style='border: gray 1px solid; background-color: LightBlue'>
			<input type='number' class='precio-ventadirecta-sf' name='precio-ventadirecta-sf[]' value='0' style='width: 10ch;' step='0.01'>
		</td>
		
		<td align='center' style='border: gray 1px solid; background-color: LightBlue' class='margen-ventadirecta-sf'>-</td>

		<td align='center' style='border: gray 1px solid; background-color: LightBlue'>
			<input type='number' class='precio-sugerido-sf' name='precio-sugerido-sf[]' value='0' style='width: 10ch;'  step='0.01'>
		</td>
		
		<td align='center' style='border: gray 1px solid; background-color: LightBlue' class='margen-sugerido-sf'>-</td>
		
	</tr>";
	$indice++;
}
echo "</tbody>";
$totalCostoDirectoProductoF = number_format($costoProductosTotalesReporte, 2, ".", ",");

echo "<tr>
    <td>&nbsp;</td>
	<td>&nbsp;</td>
    <td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td align='right'><b id='total-costo-producto'>$totalCostoDirectoProductoF</b></td>
	<td align='right'><b id='total-cantidad-producir'>-</b></td>
	<td align='right'><b id='total-horas-producir'>-</b></td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	<tr>";
echo "</table>";


echo "<center><input type='submit' class='boton2' value='Guardar Analisis'></center>";

?>




<!-- small modal -->
<div class="modal fade modal-primary" id="modalInsumosProductos" tabindex="-1" role="dialog"
	aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-sm">
		<div class="modal-content card shadow">
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
	<div class="modal-dialog modal-sm">
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
		
		$('form').submit(function(e) {
		if($('input[name="guardar[]"]:checked').length === 0) {
		    alert('Por favor seleccione al menos un item para guardar');
		    e.preventDefault();
		}
		});

		function formatNumber(num) {
			return num.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
		}

		function parseFormattedNumber(numStr) {
			return parseFloat(numStr.replace(/,/g, ''));
		}

		function calcularCostosIndirectos(){
			let totalCantidad = parseFloat($("#total-cantidad-producir").text());
			let totalHoras = parseFloat($("#total-horas-producir").text());
			console.log("totales: "+totalCantidad+" "+totalHoras);

			$('.porcentaje-cantidad-producir').each(function() {
				var $row = $(this).closest('tr');
				var cantidad = $row.find('.cantidad-producir').val() || 0;
				var horas = $row.find('.horas-produccion').val() || 0;
				var porcentajeCostoUnitario = parseFormattedNumber($row.find('.porcentaje-costo-unitario').text().trim());
				var porcentajeCantidad = formatNumber((cantidad / totalCantidad)*100);
				
				$row.find('.porcentaje-cantidad-producir').text(porcentajeCantidad);
				
				var costoIndirectoDistribuidoOriginal = ((( parseFloat(porcentajeCostoUnitario) + parseFloat(porcentajeCantidad) ) / 2) /100) * parseFloat(totalHoras*17);
				var costoIndirectoDistribuido = formatNumber(( ((parseFloat(porcentajeCostoUnitario) + parseFloat(porcentajeCantidad))/2)/100) * parseFloat(totalHoras*17));
				$row.find('.costo-indirecto-distribuido').text(costoIndirectoDistribuido);
				$row.find('.costo_indirecto_distribuido').val(( ((parseFloat(porcentajeCostoUnitario) + parseFloat(porcentajeCantidad))/2)/100) * parseFloat(totalHoras*17));
				
				
				var costoIndirectoUnitario = formatNumber(parseFloat(costoIndirectoDistribuidoOriginal) / parseFloat(cantidad));
				$row.find('.costo-indirecto-unitario').text(costoIndirectoUnitario);
				$row.find('.costo_indirecto_unitario').val(parseFloat(costoIndirectoDistribuidoOriginal) / parseFloat(cantidad));
				
				var costoDirectoProducto = parseFormattedNumber($row.find('.costo-directo-producto').text().trim());
				var costoTotalProducto = formatNumber(parseFloat(costoDirectoProducto) + parseFloat(costoIndirectoUnitario));

				$row.find('.total-costo-unitario').text(costoTotalProducto);
				$row.find('.costo_total_unitario').val(parseFloat(costoDirectoProducto) + parseFloat(costoIndirectoUnitario));
				
				
				//console.log('porcentajeCostoUnitario: '+porcentajeCostoUnitario+' porcentajeCantidad: '+porcentajeCantidad+ 'horas: '+totalHoras+' montocalculado: '+costoIndirectoDistribuido );
			});

		}


		/*ESTA PARTE SUMA LAS CANTIDADES*/
		$(".cantidad-producir").on("input", function() {
	        let total = 0;
	        $(".cantidad-producir").each(function() {
	            total += parseFloat($(this).val()) || 0;
	        });
	        $("#total-cantidad-producir").text(total);
	    	calcularCostosIndirectos();
	    });
	    /*FIN SUMAR CANTIDADES*/

	    /*ESTA PARTE SUMA LAS HORAS*/
		$(".horas-produccion").on("input", function() {
	        let total = 0;
	        $(".horas-produccion").each(function() {
	            total += parseFloat($(this).val()) || 0;
	        });
	        $("#total-horas-producir").text(total);
	    	calcularCostosIndirectos();
	    });
	    /*FIN SUMAR HORAS*/




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