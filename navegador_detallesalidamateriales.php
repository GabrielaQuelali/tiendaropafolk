<?php

	require("conexionmysqli.php");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");
	require("funcion_nombres.php");

	$sqlEmpresa="select nombre, nit, direccion from datos_empresa";
	$respEmpresa=mysqli_query($enlaceCon,$sqlEmpresa);
	$datEmpresa=mysqli_fetch_array($respEmpresa);
	$global_almacen=$dat_almacen[0];
	
	$nombreEmpresa=$datEmpresa[0];
	$nitEmpresa=$datEmpresa[1];
	$direccionEmpresa=$datEmpresa[2];

	/*$nombreEmpresa=mysql_result($respEmpresa,0,0);
	$nitEmpresa=mysql_result($respEmpresa,0,1);
	$direccionEmpresa=mysql_result($respEmpresa,0,2);*/
	
	
	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, s.observaciones,
	s.nro_correlativo, s.territorio_destino, s.almacen_destino, (select c.nombre_cliente from clientes c where c.cod_cliente=s.cod_cliente),
	(select c.dir_cliente from clientes c where c.cod_cliente=s.cod_cliente),
	s.monto_total, s.descuento, s.monto_final,lp.nro_lote,lp.nombre_lote, lp.cant_lote, lp.obs_lote,mp.descripcion_material
	FROM salida_almacenes s
	left join tipos_salida ts on (s.cod_tiposalida = ts.cod_tiposalida )
	left join lotes_produccion lp on (s.cod_lote=lp.cod_lote)
	left join material_apoyo mp on (lp.codigo_material=mp.codigo_material)
	where s.cod_almacen='$global_almacen' and s.cod_salida_almacenes='$codigo_salida'";
	$resp=mysqli_query($enlaceCon,$sql);
	$dat=mysqli_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_salida=$dat[1];
	$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
	$nombre_tiposalida=$dat[2];
	$obs_salida=$dat[3];
	$nro_correlativo=$dat[4];
	$territorio_destino=$dat[5];
	$almacen_destino=$dat[6];
	$nombreAlmacenDestino=nombreAlmacen($enlaceCon,$almacen_destino);

	$nombreCliente=$dat[7];
	$direccionCliente=$dat[8];
	$montoNota=$dat[9];
	$montoNota=redondear2($montoNota);
	$descuentoNota=$dat[10];
	$descuentoNota=redondear2($descuentoNota);
	$montoFinal=$dat[11];
	$montoFinal=redondear2($montoFinal);

	$nro_lote=$dat['nro_lote'];
	$nombre_lote=$dat['nombre_lote'];
	$cant_lote=$dat['cant_lote'];
	$obs_lote=$dat['obs_lote'];
	$descripcion_material=$dat['descripcion_material'];

		
	echo "<table class='texto' align='center'>";
	echo "<tr><th colspan='3' align='center'>Nota de Remision</th></tr>";
	echo "<tr><th align='left' width='30%'>$nombreEmpresa</th>
	<th align='center' width='30%'>Nro. $nro_correlativo</th>
	<th align='right' width='30%'>Fecha: $fecha_salida_mostrar</th>
	</tr>";
	
	echo "<tr><th align='left' class='bordeNegroTdMod'>Tipo de Salida: $nombre_tiposalida</th>
	<th align='center' class='bordeNegroTdMod'>Almacen Destino: $nombreAlmacenDestino</th><th align='right'>Observaciones: $obs_salida</th></tr>";
		if($nro_lote<>null){
		echo "<tr>
		<th align='left' class='bordeNegroTdMod' colspan='3'>LOTE: $nro_lote $nombre_lote $cant_lote <br/>$descripcion_material / $obs_lote</th></tr>";
			}
	echo "</table><br>";

	echo "<table border='0' class='texto' cellspacing='0' width='90%' align='center'>";

	echo "<tr><th>Nro</th><th>Grupo/Subgrupo</th><th>Marca</th><th>COD</th><th>Producto</th><th>Cantidad</th><th>CostoUnitario</th><th>CostoItem</th><th>Precio Normal</th><th>Precio x Mayor</th></tr>";
	
	echo "<form method='post' action=''>";
	
	$sql_detalle="select s.cod_material, m.descripcion_material, s.lote, s.fecha_vencimiento, 
		sum(s.cantidad_unitaria), avg(s.costo_almacen),s.precio_traspaso, s.precio_traspaso2
		from salida_detalle_almacenes s
		left join material_apoyo m on(s.cod_material=m.codigo_material)
		
		where s.cod_salida_almacen='$codigo'  
		group by s.cod_material, m.descripcion_material 
		order by s.orden_detalle ";
	
	$resp_detalle=mysqli_query($enlaceCon,$sql_detalle);
	$indice=0;
	$montoTotal=0;
	$pesoTotal=0;
	
	$costoTotal=0;
	$montoUnitario=0;
	$nroCorr=0;
	while($dat_detalle=mysqli_fetch_array($resp_detalle))
	{	
		$nroCorr++;
		$cod_material=$dat_detalle[0];
		$nombre_material=$dat_detalle[1];
		$loteProducto=$dat_detalle[2];
		$fechaVencimiento=$dat_detalle[3];
		$cantidad_unitaria=$dat_detalle[4];
		$costoUnitario=$dat_detalle[5];
		$costoItem=$cantidad_unitaria*$costoUnitario;
		$precio_traspaso=$dat_detalle['precio_traspaso'];
		$precio_traspaso2=$dat_detalle['precio_traspaso2'];		
		
			$sql_nombre_material="select s.nombre,g.nombre,m.nombre,ma.codigo2, ma.codigo_barras,ma.talla, ma.color
		from material_apoyo ma
		left join subgrupos s on (ma.cod_subgrupo=s.codigo)
		left join grupos g on (s.cod_grupo=g.codigo)
		left join marcas m on (ma.cod_marca=m.codigo)
		where codigo_material='$cod_material'";
		
		$resp_nombre_material=mysqli_query($enlaceCon,$sql_nombre_material);
		$dat_nombre_material=mysqli_fetch_array($resp_nombre_material);
		
		$nombre_subgrupo=$dat_nombre_material[0];
		$nombre_grupo=$dat_nombre_material[1];
		$nombre_marca=$dat_nombre_material[2];
		$codigo2=$dat_nombre_material[3];
		$codigoBarras=$dat_nombre_material[4];
		$talla=$dat_nombre_material[5];
		$color=$dat_nombre_material[6];
		

		
		
		$costoTotal+=$costoItem;
		
		$cantidadF=redondear2($cantidad_unitaria);
		$costoUnitF=redondear2($costoUnitario);
		$costoItemF=redondear2($costoItem);
		
		echo "<tr>
		<td class='bordeNegroTdMod'>$nroCorr</td>
		<td class='bordeNegroTdMod'>$nombre_grupo / $nombre_subgrupo </td>
		<td class='bordeNegroTdMod'>$nombre_marca</td>
		<td class='bordeNegroTdMod'>$codigoBarras $codigo2</td>
			<td class='bordeNegroTdMod'>$nombre_material</td>
		
			<td class='bordeNegroTdMod'>$cantidadF</td>
			<td class='bordeNegroTdMod'>$costoUnitF</td>
			<td class='bordeNegroTdMod'>$costoItemF</td>
			<td class='bordeNegroTdMod'>$precio_traspaso</td>
			<td class='bordeNegroTdMod'>$precio_traspaso2</td>
			</tr>";
		$indice++;
		$montoTotal=$montoTotal+$montoUnitario;
		$montoTotal=redondear2($montoTotal);	
	}
	$costoTotalF=redondear2($costoTotal);
	
	echo "<tr><th colspan='6'>-</th><th>Costo Total</th><th>$costoTotalF</th></tr>";
	echo "</table><br><br><br><br><br>";
	echo "<div><table width='90%'>
	<tr class='bordeNegroTdMod'><td width='33%' align='center'>Despachado</td><td width='33%' align='center'>Entregue Conforme</td><td width='33%' align='center'>Recibi Conforme</td></tr>
	</table></div>";
?>