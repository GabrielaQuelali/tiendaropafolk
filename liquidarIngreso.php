<script language='JavaScript'>
function calcularPrecio(fila){

	var precioCompra=document.getElementById('precioBruto'+fila).value;
	var cantidadUnitaria=document.getElementById('cantidad_unitaria'+fila).value;
	
	var importeNeto=parseFloat(cantidadUnitaria)*(parseFloat(precioCompra));
	
	document.getElementById('precioNeto'+fila).value=importeNeto;
}

</script>

<?php
	require("conexion.inc");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");
	$codigo_ingreso=$_GET['codigo_registro'];
	
	echo "<form method='post' action='guardaLiquidarIngreso.php' name='form1'>";
	
	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nro_correlativo 
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$global_almacen' and i.cod_ingreso_almacen='$codigo_ingreso'";
	$resp=mysql_query($sql);
	
	echo "<input type='hidden' name='codigoIngreso' value='$codigo_ingreso'>";
	
	echo "<center><h1>Liquidar Ingreso</h1>";
	
	echo "<table class='texto' align='center'>";
	echo "<tr><th>Nro. Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>";
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_ingreso=$dat[1];
	$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	$nombre_tipoingreso=$dat[2];
	$obs_ingreso=$dat[3];
	$nro_correlativo=$dat[4];
	echo "<tr>
		<td align='center'>$nro_correlativo</td>
		<td align='center'>$fecha_ingreso_mostrar</td>
		<td>$nombre_tipoingreso</td>
		<td>&nbsp;$obs_ingreso</td>
		</tr>";
	echo "</table>";
	
	$sql_detalle="select i.cod_material, i.cantidad_unitaria, i.precio_bruto from ingreso_detalle_almacenes i, material_apoyo m
	where i.cod_ingreso_almacen='$codigo' and m.codigo_material=i.cod_material";
	$resp_detalle=mysql_query($sql_detalle);
	echo "<br><table class='texto' align='center'>";
	echo "<tr><th>&nbsp;</th><th>Material</th><th>Cantidad</th><th>Precio[u] Compra</th><th>Precio Total Compra</th></tr>";
	$indice=1;
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$cantidad_unitaria=$dat_detalle[1];
		$precio_bruto=$dat_detalle[2];
		
		$precioTotal=$precio_bruto*$cantidad_unitaria;
		
		$cantidad_unitaria=redondear2($cantidad_unitaria);
		$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
		$resp_nombre_material=mysql_query($sql_nombre_material);
		$dat_nombre_material=mysql_fetch_array($resp_nombre_material);
		$nombre_material=$dat_nombre_material[0];
		echo "<tr>
		<td align='center'>$indice</td>
		<td>$nombre_material</td>
		<td align='center'>$cantidad_unitaria</td>
		<input type='hidden' name='cantidad_unitaria$indice' id='cantidad_unitaria$indice' value='$cantidad_unitaria'>
		<td align='center'><input type='number' min='0.1' class='texto' value='$precio_bruto' id='precioBruto$indice' name='precioBruto$indice' onKeyUp='calcularPrecio($indice);' step='0.1' required></td>
		<td align='center'><input type='number' class='texto' value='$precioTotal' id='precioNeto$indice' name='precioNeto$indice' readonly></td>
		<input type='hidden' name='material$indice' id='material$indice' value='$cod_material'>
		</tr>";
		$indice++;
	}
	echo "</table>";
	echo "<input type='hidden' value='$indice' name='numeroItems'>";
	
	echo "<br><center>
		<input type='submit' value='Guardar' name='adicionar' class='boton'>
		</center>";
	echo "</form>";
?>