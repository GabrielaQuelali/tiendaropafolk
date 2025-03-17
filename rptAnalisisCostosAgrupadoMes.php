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

$diaPrimerMes=explode("-",$fecha_iniconsulta)[2];
$diaUltimoMes=explode("-",$fecha_finconsulta)[2];

setlocale(LC_ALL, 'es_ES');
$tiempoInicio = strtotime($fecha_iniconsulta);//obtener tiempo de inicio
$tiempoFin = strtotime(date("Y-m-t", strtotime($fecha_finconsulta)).""); //obtener el tiempo final pero al ultimo día, para que muestre todos los meses



$rpt_territorio = $_POST['rpt_territorio'];
$rptTerritorioString = implode(",", $rpt_territorio);

$fecha_reporte = date("d/m/Y");

$nombre_territorio = nombreTerritorioAgrupado($enlaceCon, $rptTerritorioString);
//echo $montoVentasTotales;


$arrayGastos="";
if($rpt_ver==1){
	$arrayGastos = obtenerDetalleGastosMensuales($rptTerritorioString, $fecha_iniconsulta, $fecha_finconsulta);	
}else{
	$arrayGastos = obtenerDetalleGastos($rptTerritorioString, $fecha_iniconsulta, $fecha_finconsulta);	
}
list($gastosTotales, $jsonGastos) = $arrayGastos;
$gastosTotalesF = formatonumeroDec($gastosTotales);


echo "<table align='center' class='textotit' width='100%'><tr><td align='center'>Analisis de Costos Agrupado x Mes
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

echo "<table align='center' class='texto' width='100%'>
<tr>
<th>Codigo</th>
<th>Producto</th>";

while($tiempoInicio <= $tiempoFin){
	$fechaActual = date("Y-m-d", $tiempoInicio);
	$date = new DateTime($fechaActual);
	$date->modify('last day of this month');
	$fechaUltimoDiaMes = $date->format('Y-m-d');
	
	$arrayGastos="";
	if($rpt_ver==1){
		$arrayGastos = obtenerDetalleGastosMensuales($rptTerritorioString, $fechaActual, $fechaUltimoDiaMes);	
	}else{
		$arrayGastos = obtenerDetalleGastos($rptTerritorioString, $fechaActual, $fechaUltimoDiaMes);	
	}
	list($gastosTotales, $jsonGastos) = $arrayGastos;
	$gastosTotalesF = formatonumeroDec($gastosTotales);
	?>
	<th colspan="10"><b><small><?=strftime('%b %Y', strtotime($fechaActual))?><br>Gastos: <?=$gastosTotalesF;?></small></b></th>
	<?php
	$tiempoInicio += strtotime("+1 month","$fechaActual");
	$cantidadMes++;
}

echo "</tr>";


echo "<tr>
<th>-</th>
<th>-</th>";

$tiempoInicio = strtotime($fecha_iniconsulta);//obtener tiempo de inicio
$tiempoFin = strtotime(date("Y-m-t", strtotime($fecha_finconsulta)).""); //obtener el tiempo final pero al ultimo día, para que muestre 
$cantidadMes=0;
while($tiempoInicio <= $tiempoFin){
	$fechaActual = date("Y-m-d", $tiempoInicio);
	?>
	<th><b><small><small>Cant.</small></small></b></th>
	<th><b><small><small>Monto(Bs.)</small></small></b></th>
	<th><b><small><small>PrecioP</small></small></b></th>
	<th><b><small><small>Part.</small></small></b></th>
	<th><b><small><small>Insumos</small></small></b></th>
	<th><b><small><small>Procesos</small></small></b></th>
	<th><b><small><small>Gasto</small></small></b></th>
	<th><b><small><small>C.U</small></small></b></th>
	<th><b><small><small>C.Total</small></small></b></th>
	<th><b><small><small>Margen</small></small></b></th>
	<?php
	$tiempoInicio += strtotime("+1 month","$fechaActual");
	$cantidadMes++;
}

echo "</tr>";



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

	echo "<tr>
		<td>$codProductoFinal</td>
        <td>$nombreItem</td>";


  	$tiempoInicio2 = strtotime($fecha_iniconsulta);
  	$sw_meses=0;
  	$cantidadMes2=0;
  	while($tiempoInicio2 <= $tiempoFin){

  		$titleProductoDetalle="";

    	$cantidadMes2++;
	  	$dateInicio = date("Y-m", $tiempoInicio2)."-01";
	  	$dateFin = date("Y-m-t", $tiempoInicio2);
	  	if($cantidadMes2==1){
	      $sw_meses=1;
	  		$dateInicio=date('Y-m', strtotime($fecha_iniconsulta))."-".$diaPrimerMes;
	  	}
	    if($cantidadMes2==$cantidadMes){
	      $dateFin=date('Y-m', strtotime($fecha_finconsulta))."-".$diaUltimoMes;
	    }
	    
	    ///////INICIO DEL CALCULO GENERAL
	    $sqlDetalle = "";
	    if($tipoAgrupacion == 'GRUPO'){
			$sqlDetalle = "SELECT pc.cod_producto_costo, pc.nombre_producto_costo,
			    (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total, 'GRUPO' as tipoagrupacion, pc.costo_si_no
			    from salida_almacenes s
			    INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
			    INNER JOIN producto_costo_detalle pcd ON pcd.cod_producto=sd.cod_material
			    INNER JOIN producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
			    where s.`fecha` BETWEEN '$dateInicio' and '$dateFin'
			    and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and  
			    s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($rptTerritorioString) ) 
			    and pc.cod_producto_costo = '$codProductoFinal' "; 
	    }else{
	    	$sqlDetalle="SELECT m.codigo_material, m.descripcion_material,
			    (sum(sd.monto_unitario)-sum(sd.descuento_unitario))montoVenta, sum(sd.cantidad_unitaria), s.descuento, s.monto_total,'PRODUCTO'  as tipoagrupacion, m.costo_si_no
			    from salida_almacenes s
			    INNER JOIN salida_detalle_almacenes sd ON s.cod_salida_almacenes=sd.cod_salida_almacen
			    INNER JOIN material_apoyo m ON sd.cod_material=m.codigo_material
			    LEFT JOIN producto_costo_detalle pcd ON pcd.cod_producto=m.codigo_material
			    LEFT JOIN  producto_costo pc ON pc.cod_producto_costo=pcd.cod_producto_costo
			    where s.`fecha` BETWEEN '$dateInicio' and '$dateFin'
			    and s.`salida_anulada`= 1 and s.`cod_tiposalida`=1001 and  
			    s.`cod_almacen` in (select a.`cod_almacen` from `almacenes` a where a.`cod_ciudad` in ($rptTerritorioString) ) and 
			    pcd.cod_producto is null and m.codigo_material = '$codProductoFinal' "; 
	    }
	    $respDetalle=mysqli_query($enlaceCon, $sqlDetalle);
	    $montoVentaProductoDet = 0;
	    $cantidadDet = 0;
	    $descuentoVentaDet = 0;
	    $montoNota = 0;
	    $nombreProductoDetalle="";
	    if($datDetalle = mysqli_fetch_array($respDetalle)){
	    	$nombreProductoDetalle=$datDetalle[1];
	    	$montoVentaProductoDet = $datDetalle[2];
			$cantidadDet = $datDetalle[3];
			$descuentoVenta = $datDetalle[4];
			$montoNota = $datDetalle[5];
	    }

		$montoVentaProductoDetF = number_format($montoVentaProductoDet, 2, ".", ",");
		$cantidadDetF = number_format($cantidadDet, 0, ".", ",");
		$precioPromedio = $montoVentaProductoDet / $cantidadDet;
		$precioPromedioF = formatonumeroDec($precioPromedio);


		$arrayGastos="";
		$montoVentasTotales=0;
		if($rpt_ver==1){
			$arrayGastos = obtenerDetalleGastosMensuales($rptTerritorioString, $dateInicio, $dateFin);
			$montoVentasTotales = montoVentasSucursalExcepciones($rptTerritorioString, $dateInicio, $dateFin);
		}else{
			$arrayGastos = obtenerDetalleGastos($rptTerritorioString, $dateInicio, $dateFin);
			$montoVentasTotales = montoVentasSucursal($rptTerritorioString, $dateInicio, $dateFin);
		}

		$participacionVentaProducto = ($montoVentaProductoDet / $montoVentasTotales) * 100;
		$participacionVentaProductoF = formatonumeroDec($participacionVentaProducto);
		$totalVenta = $totalVenta + $montoVentaProductoDet;


		//************************************
		//*** ESTA PARTE ES DE LOS INSUMOS****
		//************************************
		if ($tipoAgrupacion == 'GRUPO') {
			$arrayCostoInsumos = obtenerCostoInsumosGrupo($codProductoFinal, $dateInicio, $dateFin, $rptTerritorioString);
			list($costoInsumosTotal, $jsonInsumos, $banderaObsInsumos) = $arrayCostoInsumos;
			$costoInsumosTotalF = formatonumeroDec($costoInsumosTotal);
			$costoInsumos = $costoInsumosTotal / $cantidadDet;
			$costoInsumosF = formatonumeroDec($costoInsumos);
		} else {
			$arrayCostoInsumos = obtenerCostoInsumosProducto($codProductoFinal, $dateInicio, $dateFin, $rptTerritorioString);
			list($costoInsumos, $jsonInsumos, $banderaObsInsumos) = $arrayCostoInsumos;
			$costoInsumosF = formatonumeroDec($costoInsumos);
			$costoInsumosTotal = $costoInsumos * $cantidadDet;
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

		//************************************
		//**** FIN  INSUMOS***
		//************************************

		//***********************************************
		//*** ESTA PARTE SACA LOS COSTOS DE PROCESOS*****
		//***********************************************
		if ($tipoAgrupacion == 'GRUPO') {
			$arrayCostoProcesos = obtenerCostoProcesosGrupo($codProductoFinal, $dateInicio, $dateFin, $rptTerritorioString);
			list($costoProcesos, $jsonProcesos, $banderaObsProcesos) = $arrayCostoProcesos;
		} else {
			$arrayCostoProcesos = obtenerCostoProcesos($codProductoFinal, $dateInicio, $dateFin);
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
		$costoProcesoPromedio = $costoProcesosTotal / $cantidadDet;
		$costoProcesosPromedioF = formatonumeroDec($costoProcesoPromedio);
		$costoProcesosTotalF = formatonumeroDec($costoProcesosTotal);

		$totalSumaProcesos = $totalSumaProcesos + $costoProcesosTotal;

		$jsonProcesosEncode = json_encode($jsonProcesos);

		//*********************
		//**** FIN PROCESOS ***
		//*********************
		
		//$arrayGastos = obtenerDetalleGastos($rptTerritorioString, $dateInicio, $dateFin);
		list($gastosTotales, $jsonGastos) = $arrayGastos;
		$gastosTotalesF = formatonumeroDec($gastosTotales);

		$montoGastoDistribuido = $gastosTotales * ($participacionVentaProducto / 100);
		$montoGastoDistribuidoF = formatonumeroDec($montoGastoDistribuido);

		$totalSumaGastos = $totalSumaGastos + $montoGastoDistribuido;

		$costoTotalProducto = $costoInsumosTotal + $costoProcesosTotal + $montoGastoDistribuido;
		$costoTotalProductoF = formatonumeroDec($costoTotalProducto);

		$costoUnitarioProducto=0;
		if($cantidadDet>0){
			$costoUnitarioProducto = $costoTotalProducto / $cantidadDet;
		}
		$costoUnitarioProductoF = formatonumeroDec($costoUnitarioProducto);

		$margenProducto=0;
		$margenProductoF="-";
		if($montoVentaProductoDet>0){
			$margenProducto = (($montoVentaProductoDet - $costoTotalProducto) / $montoVentaProductoDet) * 100;
			$margenProductoF = formatonumeroDec($margenProducto)." %";
		}else{
			$cantidadDetF="-";
			$montoVentaProductoDetF="-";
			$participacionVentaProductoF="-";
			$costoInsumosTotalF="-";
			$costoProcesosTotalF="-";
			$montoGastoDistribuidoF="-";
			$costoTotalProductoF="-";
			$costoUnitarioProductoF="-";
		}

		/*echo "<tr>
	        <td>$indice</td>
			<td>$codProductoFinal</td>
	        <td>$nombreItem</td>
			<td align='center'>$cantidadFormat</td>
			<td align='right'>$montoVentaProductoF</td>	
			<td align='right'>$precioPromedioF</td>	
			<td align='right'>$participacionVentaProductoF %</td>	
			<td align='right' style='border: red 2px solid; background-color: LightPink;'><a href='#' onclick='mostrarInsumosProductos($codProductoFinal,\"$tipoAgrupacion\",$jsonInsumosEncode);'>$costoInsumosF</a></td>	
			<td align='right' style='border: red 2px solid; border: red 2px solid; background-color: LightPink;'>$costoInsumosTotalF</td>	
			<td align='right' style='border: red 2px solid; background-color: PaleGreen'><a href='#' onclick='mostrarProcesosProductos($codProductoFinal,\"$tipoAgrupacion\",$jsonProcesosEncode)'>$costoProcesosPromedioF</td>	
			<td align='right' style='border: red 2px solid; background-color: PaleGreen'>$costoProcesosTotalF</td>	
			<td align='right' style='border: red 2px solid; background-color: Khaki'>$montoGastoDistribuidoF</td>	
			<td align='right' style='border: red 2px solid;'>$costoTotalProductoF</td>	
			<td align='right' style='border: red 2px solid;'>$margenProductoF %</td>	
		</tr>";
		$indice++;*/
	    /////FIN DEL CALCULO GENERAL

		// $titleProductoDetalle.="Cantidad: ".$cantidadFormat."\n";
		// $titleProductoDetalle.="Venta: ".$montoVentaProductoF."\n";
		// $titleProductoDetalle.="Precio: ".$precioPromedioF."\n";
		// $titleProductoDetalle.="Participacion: ".$participacionVentaProductoF."\n";
		// $titleProductoDetalle.="Insumos: ".$costoInsumosF."\n";
		// $titleProductoDetalle.="Total Insumos: ".$costoInsumosTotalF."\n";
		// $titleProductoDetalle.="Costo Procesos: ".$costoProcesosPromedioF."\n";
		// $titleProductoDetalle.="Costo Procesos Total: ".$costoProcesosTotalF."\n";
		// $titleProductoDetalle.="Total Gasto: ".$gastosTotalesF."\n";
		// $titleProductoDetalle.="Gasto Distribuido: ".$montoGastoDistribuidoF."\n";

		$titleProductoDetalle=$nombreProductoDetalle."\n";
		$titleProductoDetalle.=" Total Venta Mes: ".$montoVentasTotales."\n";
		$titleProductoDetalle.=" Gasto Total Mes: ".$gastosTotalesF."\n";

	    echo "
	    <td align='right' style='border: black 2px solid; background-color: LightOrange;' title='$titleProductoDetalle'>
	    	<small>$cantidadDetF</small></td>	    
    	<td align='right' style='border: black 2px solid; background-color: LightOrange;' title='$titleProductoDetalle'>
	    	<small>$montoVentaProductoDetF</small></td>
    	<td align='right' style='border: black 2px solid; background-color: LightOrange;' title='$titleProductoDetalle'>
	    	<small>$precioPromedioF</small></td>
	    <td align='right' style='border: black 2px solid; background-color: LightOrange;' title='$titleProductoDetalle'>
	    	<small>$participacionVentaProductoF</small></td>
	    <td align='right' style='border: black 2px solid; background-color: LightPink;' title='$titleProductoDetalle'>
	    	<small>$costoInsumosTotalF</small></td>
	    <td align='right' style='border: black 2px solid; background-color: PaleGreen' title='$titleProductoDetalle'>
	    	<small>$costoProcesosTotalF</small></td>
	    <td align='right' style='border: black 2px solid; background-color: Khaki' title='$titleProductoDetalle'>
	    	<small>$montoGastoDistribuidoF</small></td>
	    <td align='right' style='border: black 2px solid; background-color: PaleGreen' title='$titleProductoDetalle'>
	    	<small>$costoUnitarioProductoF</small></td>	    
    	<td align='right' style='border: black 2px solid; background-color: PaleGreen' title='$titleProductoDetalle'>
	    	<small>$costoTotalProductoF</small></td>
	    <td align='right' style='border: red 2px solid;' title='$titleProductoDetalle'><b>$margenProductoF</b></td>"; 


	    // para sumar mes
	  	$fechaActual = date("Y-m-d", $tiempoInicio2);  	
	  	$tiempoInicio2 += strtotime("+1 month","$fechaActual");
  	}


}
$totalPtr = number_format($totalVenta, 2, ".", ",");
$totalSumaInsumosF = formatonumeroDec($totalSumaInsumos);
$totalSumaProcesosF = formatonumeroDec($totalSumaProcesos);
$totalSumaGastosF = formatonumeroDec($totalSumaGastos);

$totalSumaCosto = $totalSumaInsumos + $totalSumaProcesos + $totalSumaGastos;
$totalSumaCostoF = formatonumeroDec($totalSumaCosto);

$totalMargenProductoF = formatonumeroDec((($totalVenta - $totalSumaCosto) / $totalVenta) * 100);

/*echo "<tr>
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
	<td align='right'><b>$totalSumaCostoF</b></td>
	<td align='right'><b>$totalMargenProductoF %</b></td>
	<td></td>
<tr>";
echo "</table>";
*/

?>

