<?php
	require("conexionmysqli.php");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");
	error_reporting(E_ALL);
 ini_set('display_errors', '1');
	echo "<form method='post' action=''>";
	$codCargo=$_COOKIE['global_cargo'];
	
	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nro_correlativo, 
(select p.nombre_proveedor from proveedores p where p.cod_proveedor=i.cod_proveedor) as nombre_proveedor FROM ingreso_almacenes i, tipos_ingreso ti where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$global_almacen' and i.cod_ingreso_almacen='$codigo_ingreso'";
	
	//echo $sql;
	
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<center><table border='0' class='textotit'><tr><th>Detalle de Ingreso</th></tr></table></center><br>";
	
	echo "<table border='0' class='texto' align='center'>";
	echo "<tr><th>Nro. de Ingreso</th><th>Fecha</th><th>Proveedor</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>";
	$dat=mysqli_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_ingreso=$dat[1];
	$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	$nombre_tipoingreso=$dat[2];
	$obs_ingreso=$dat[3];
	$nro_correlativo=$dat[4];
	$nombre_proveedor=$dat[5];
	echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_ingreso_mostrar</td>
	<td>$nombre_proveedor</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td></tr>";
	echo "</table>";
	$sql_detalle="select i.cod_material, i.cantidad_unitaria, i.precio_neto, i.lote, DATE_FORMAT(i.fecha_vencimiento, '%d/%m/%Y'), m.descripcion_material, m.color, m.talla, m.codigo_barras,i.precio_venta,i.precio_venta2 
	from ingreso_detalle_almacenes i, material_apoyo m
	where i.cod_ingreso_almacen='$codigo' and m.codigo_material=i.cod_material order by i.orden_detalle asc";
	$resp_detalle=mysqli_query($enlaceCon,$sql_detalle);

	echo "<br><table border=0 class='textoform' align='center'>";
	echo "<tr><th>Nro</th><th>&nbsp;</th><th>&nbsp;</th><th>Grupo/Subgrupo</th><th>Marca</th><th>Material</th><th>Cantidad</th><th>Lote</th><th>Costo(Bs.)</th><th>Precio Normal (Bs.)</th><th>Precio x Mayor (Bs.)</th></tr>";
	$indice=1;
	$totalCantProd=0;
	while($dat_detalle=mysqli_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$cantidad_unitaria=$dat_detalle[1];
		$precioNeto=redondear2($dat_detalle[2]);
		$loteProducto=$dat_detalle[3];
		$fechaVenc=$dat_detalle[4];
		$color=$dat_detalle[6];
		$talla=$dat_detalle[7];
		$barCode=$dat_detalle[8];
		$precioVenta=$dat_detalle['precio_venta'];
		$precioVenta2=$dat_detalle['precio_venta2'];
		
		
		$totalValorItem=$cantidad_unitaria*$precioNeto;
		
		$cantidad_unitaria=redondear2($cantidad_unitaria);
		$totalCantProd=$totalCantProd+$cantidad_unitaria;
				$sql_nombre_material="select ma.descripcion_material,s.nombre,g.nombre,m.nombre,ma.codigo2
		from material_apoyo ma
		left join subgrupos s on (ma.cod_subgrupo=s.codigo)
		left join grupos g on (s.cod_grupo=g.codigo)
		left join marcas m on (ma.cod_marca=m.codigo)
		where codigo_material='$cod_material'";
		
		$resp_nombre_material=mysqli_query($enlaceCon,$sql_nombre_material);
		$dat_nombre_material=mysqli_fetch_array($resp_nombre_material);
		$nombre_material=$dat_nombre_material[0];
			$nombre_subgrupo=$dat_nombre_material[1];
		$nombre_grupo=$dat_nombre_material[2];
		$nombre_marca=$dat_nombre_material[3];
		$codigo2=$dat_nombre_material[4];
								
			
		
		if($cantidad_unitaria>1){
		echo "<tr bgcolor='#d3ffce' border ='1'>";
		}else{
			echo "<tr>";
		}		
		echo "<td align='center'><strong>$indice</strong></td>
		<td>$barCode</td>
		<td>$codigo2</td>
		<td>$nombre_grupo - $nombre_subgrupo</td>
		<td>$nombre_marca</td>
		<td>$nombre_material</td>";
		if($cantidad_unitaria >1){
			echo "<td align='center' bgcolor='#d3ffce' > <strong><font size='2' color ='#ff0076'>$cantidad_unitaria</font></strong></td>";
		}else{
			echo "<td align='center' >$cantidad_unitaria</td>";
		}
		echo "<td align='center'>$loteProducto</td>";

		if($codCargo==1000 ||  $codCargo==1002){
		echo"<td align='center'>$precioNeto</td>";
	}else{
		echo"<td align='center'></td>";

	}
		echo" <td align='center'>$precioVenta</td>
		<td align='center'>$precioVenta2</td>
	
		</tr>";
		$indice++;
	}
		echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'><strong>Total Productos</strong></td>
	<td align='center'><strong>$totalCantProd</strong></td><td colspan='4'>&nbsp;</td><tr>";
	echo "</table>";
	
	echo "<center><a href='javascript:window.print();'><IMG border='no'
	 src='imagenes/print.jpg' width='40'></a></center>";
	
?>