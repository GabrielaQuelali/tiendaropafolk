<?php
//header("Content-type: application/vnd.ms-excel");
//header("Content-Disposition: attachment; filename=archivo.xls");
require('estilos_reportes_almacencentral.php');
require('function_formatofecha.php');
require('conexionmysqli.php');
require("funciones.php");

$rpt_territorio=$_POST["rpt_territorio"];
$rpt_almacen=$_POST["rpt_almacen"];

$rptGrupo=$_POST["rpt_grupo"];
$rptMarca=$_POST["rpt_marca"];

$rptFechaInicio=$_POST["rpt_ini"];
$rptFechaFinal=$_POST["rpt_fin"];

$fechaInicioPivot = $rptFechaInicio;
//restamos un dia
$fechaInicioPivot=date("Y-m-d",strtotime($fechaInicioPivot."- 1 days")); 
//echo "inicio pivot: ".$fechaInicioPivot;


$rptGrupoS="";
if($rptGrupo!=""){
	$rptGrupoS=implode(",",$rptGrupo);
}

$rptMarcaS="";
if($rptMarca!=""){
	$rptMarcaS=implode(",",$rptMarca);
}

$itemTallaBusqueda=$_POST["itemTallaBusqueda"];
$itemColorBusqueda=$_POST["itemColorBusqueda"];
$nombreProducto=$_POST["nombre_producto"];


$fecha_reporte=date("Y-m-d");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";


	$sql_nombre_territorio="select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
	$resp_nombre_territorio=mysqli_query($enlaceCon,$sql_nombre_territorio);
	$datos_nombre_territorio=mysqli_fetch_array($resp_nombre_territorio);
	$nombre_territorio=$datos_nombre_territorio[0];
	$sql_nombre_almacen="select nombre_almacen from almacenes where cod_almacen='$rpt_almacen'";
	$resp_nombre_almacen=mysqli_query($enlaceCon,$sql_nombre_almacen);
	$datos_nombre_almacen=mysqli_fetch_array($resp_nombre_almacen);
	$nombre_almacen=$datos_nombre_almacen[0];
	
	echo "<table align='center' class='textotit' width='70%'><tr><td align='center'>Reporte Movimiento de Productos
	<br>Territorio: $nombre_territorio <br>Nombre Almacen: <strong>$nombre_almacen</strong>
	<br>Periodo: <strong>$rptFechaInicio  a  $rptFechaFinal</strong><br>$txt_reporte</td></tr></table>";
	
		//desde esta parte viene el reporte en si
		
		
		$sql_item="select ma.codigo_material, ma.descripcion_material, ma.cantidad_presentacion,
		(select g.nombre from grupos g where g.codigo=ma.cod_grupo)as nombregrupo, ma.peso, ma.color, ma.talla, ma.codigo_barras,
		(select mar.nombre from marcas mar where mar.codigo=ma.cod_marca)as marca,ma.codigo2
		from material_apoyo ma
		where ma.codigo_material<>0 and ma.estado='1' 
		and ma.cod_grupo in ($rptGrupoS) ";
		$sql_item= $sql_item." and ma.cod_marca in ($rptMarcaS) ";
		if($itemTallaBusqueda!=""){
			$sql_item.=" and ma.talla like '%$itemTallaBusqueda%'";
		}	
		if($itemColorBusqueda!=""){
			$sql_item.=" and ma.color like '%$itemColorBusqueda%'";
		}
		if($nombreProducto!=""){
			$sql_item.=" and ma.descripcion_material like '%$nombreProducto%'";
		}
		$sql_item.=" order by 9,4,2";
		
		//echo $sql_item;

		$resp_item=mysqli_query($enlaceCon,$sql_item);
		
		echo "<br><table border=0 align='center' class='textomediano' width='70%'>
			<thead>
				<tr><th>&nbsp;</th><th>COD INT</th><th>COD BARRA /COD PROV</th><th>Marca</th>><th>Grupo</th><th>Producto</th><th>Precio Actual</th>
				<th>Stock Anterior</th>
				<th>Ingresos</th>
				<th>Salidas</th>
				<th>Saldo Final</th>
				</tr>
			</thead>";				
	
		$indice=1;
		$totalStock=0;
		while($datos_item=mysqli_fetch_array($resp_item)){	
			$codigo_item=$datos_item[0];
			$nombre_item=$datos_item[1];
			$cantidadPresentacion=$datos_item[2];
			$nombreGrupo=$datos_item[3];
			$pesoItem=$datos_item[4];
			$colorItem=$datos_item[5];
			$tallaItem=$datos_item[6];
			$barCode=$datos_item[7];
			$nombreMarca=$datos_item[8];
			$codigo2=$datos_item[9];
			
			$precio0=precioVenta($enlaceCon,$codigo_item,$rpt_territorio);

			$cadena_mostrar="";

			$cadena_mostrar.="<tr><td>$indice</td><td>$codigo_item</td><td>$barCode $codigo2</td><td>$nombreMarca</td><td>$nombreGrupo</td><td>$nombre_item - $colorItem $tallaItem</td><td align='center'>$precio0</td>";

			$stockAnterior=stockProductoAFecha($enlaceCon, $rpt_almacen, $codigo_item, $fechaInicioPivot);

			//echo $stock2;
			$cantidadIngresosPeriodo=ingresosItemPeriodo($enlaceCon, $rpt_almacen, $codigo_item, $rptFechaInicio, $rptFechaFinal);
			$cantidadSalidasPeriodo=salidasItemPeriodo($enlaceCon, $rpt_almacen, $codigo_item, $rptFechaInicio, $rptFechaFinal);
			
			$saldoFinalItem=0;
			$saldoFinalItem=$stockAnterior+$cantidadIngresosPeriodo-$cantidadSalidasPeriodo;

			if($stockAnterior<=0){	
				$cadena_mostrar.="<td align='center'>$stockAnterior</td>";
			}
			elseif($stockAnterior>0){	
				$cadena_mostrar.="<td align='center'><span class='textomedianorojo'><b>$stockAnterior</b></span></td>";
			}			

			$cantidadIngresosPeriodoF=formatonumero($cantidadIngresosPeriodo);
			$cantidadSalidasPeriodoF=formatonumero($cantidadSalidasPeriodo);
			$saldoFinalItemF=formatonumero($saldoFinalItem);
			
			if($cantidadIngresosPeriodo>0){
				$cantidadIngresosPeriodoF="<span class='textomedianorojo'><b>$cantidadIngresosPeriodoF</b></span>";
			}else{
				$cantidadIngresosPeriodoF="$cantidadIngresosPeriodoF";
			}

			if($cantidadSalidasPeriodo>0){
				$cantidadSalidasPeriodoF="<span class='textomedianorojo'><b>$cantidadSalidasPeriodoF</b></span>";
			}else{
				$cantidadSalidasPeriodoF="$cantidadSalidasPeriodoF";
			}

			if($saldoFinalItem>0){
				$saldoFinalItemF="<span class='textomedianorojo'><b>$saldoFinalItem</b></span>";
			}else{
				$saldoFinalItemF="$saldoFinalItem";
			}

			$cadena_mostrar.="<td align='center'>$cantidadIngresosPeriodoF</td>
			<td align='center'>$cantidadSalidasPeriodoF</td>
			<td align='center'>$saldoFinalItemF</td>
			</tr>";
			
			$sql_linea="select * from material_apoyo where codigo_material='$codigo_item'";
			$resp_linea=mysqli_query($enlaceCon,$sql_linea);			
			$num_filas=mysqli_num_rows($resp_linea);
			
			echo $cadena_mostrar;
			$indice++;
		}

		echo "</table>";
		
		include("imprimirInc.php");

?>