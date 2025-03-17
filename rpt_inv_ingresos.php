<?php
require('estilos_reportes_almacencentral.php');
require('conexionmysqli.php');
require('function_formatofecha.php');
require('funciones.php');

$rpt_territorio=$_POST['rpt_territorio'];
$rpt_almacen=$_POST['rpt_almacen'];
$tipo_ingreso=$_POST['tipo_ingreso'];
$tipoIngresoString=implode(",", $tipo_ingreso);

$proveedor=$_POST['proveedor'];
$proveedorString=implode(",", $proveedor);

$fecha_ini=$_POST['exafinicial'];
$fecha_fin=$_POST['exaffinal'];


$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";
$sql_tipo_ingreso="select nombre_tipoingreso from tipos_ingreso where cod_tipoingreso in ($tipoIngresoString)";
$resp_tipo_ingreso=mysqli_query($enlaceCon,$sql_tipo_ingreso);
$nombre_tipoingreso="";
while($datos_tipo_ingreso=mysqli_fetch_array($resp_tipo_ingreso)){
	$nombre_tipoingreso=$nombre_tipoingreso."-".$datos_tipo_ingreso[0];
}

echo "<h1>Reporte Ingresos Almacen</h1>
	<h1>$nombre_tipoingresomostrar Fecha inicio: <strong>$fecha_ini</strong> Fecha final: <strong>$fecha_fin</strong><br>$txt_reporte</h1>";

	//desde esta parte viene el reporte en si
	
	//$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
	//$fecha_finconsulta=cambia_formatofecha($fecha_fin);
	
	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado, 
	(select pr.nombre_proveedor from proveedores pr where pr.cod_proveedor=i.cod_proveedor)as proveedor
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$rpt_almacen' and i.fecha>='$fecha_ini' and i.fecha<='$fecha_fin' and i.cod_tipoingreso in ($tipoIngresoString) and i.cod_proveedor in ($proveedorString) and i.ingreso_anulado=0 order by i.nro_correlativo";
	
	//echo $sql;
	$resp=mysqli_query($enlaceCon,$sql);
	echo "<center><br><table class='texto' width='100%'>";
	echo "<tr class='textomini'><th>Nro.</th><th>Nota de Entrega</th><th>Fecha</th><th>Tipo de Ingreso</th>
	<th>Proveedor</th><th>Observaciones</th><th>Estado</th><th>
		<table border=0 cellspacing='0' align='center' class='textomini' width='100%'>
			<tr><th width='40%'>Material</th><th width='20%'>Cantidad</th><th width='20%'>CUnit</th><th width='20%'>CItem</th></tr>
		</table>
	</th></tr>";
	$costoTotal=0;
	while($dat=mysqli_fetch_array($resp))
	{
		$codigo=$dat[0];
		$fecha_ingreso=$dat[1];
		$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
		$nombre_tipoingreso=$dat[2];
		$obs_ingreso=$dat[3];
		$nota_entrega=$dat[4];
		$nro_correlativo=$dat[5];
		$anulado=$dat[6];
		$nombreProveedor=$dat[7];
		echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
		$bandera=0;
		$sql_verifica_movimiento="select s.cod_salida_almacenes from salida_almacenes s, salida_detalle_ingreso sdi
		where s.cod_salida_almacenes=sdi.cod_salida_almacen and s.salida_anulada=0 and sdi.cod_ingreso_almacen='$codigo'";
		$resp_verifica_movimiento=mysqli_query($enlaceCon,$sql_verifica_movimiento);
		$num_filas_movimiento=mysqli_num_rows($resp_verifica_movimiento);
		if($num_filas_movimiento!=0)
		{	$estado_ingreso="Con Movimiento";
		}
		if($anulado==1)
		{	$estado_ingreso="Anulado";
		}
		if($num_filas_movimiento==0 and $anulado==0)
		{	$estado_ingreso="Sin Movimiento";
		}
		//desde esta parte sacamos el detalle del ingreso
		$sql_detalle="select i.cod_material, i.cantidad_unitaria, i.costo_almacen from ingreso_detalle_almacenes i
		where i.cod_ingreso_almacen='$codigo'";
		$resp_detalle=mysqli_query($enlaceCon,$sql_detalle);
		$bandera=0;
		$detalle_ingreso="";
		$detalle_ingreso.="<table border=0 cellspacing='0' align='center' class='textomini' width='100%'>";
		$numFilas=mysqli_num_rows($resp_detalle);
		if($numFilas>0){
			while($dat_detalle=mysqli_fetch_array($resp_detalle))
			{	$cod_material=$dat_detalle[0];
				$cantidad_unitaria=$dat_detalle[1];
				$cantidad_unitariaF=redondear2($cantidad_unitaria);
				$costo_unitario=$dat_detalle[2];
				$costo_unitarioF=redondear2($costo_unitario);
				$costoItem=$cantidad_unitaria*$costo_unitario;
				$costoTotal=$costoTotal+$costoItem;
				$costoItemF=redondear2($costoItem);
				
				if($tipo_item==1)
				{	$sql_nombre_material="select descripcion, presentacion from muestras_medicas where codigo='$cod_material'";
				}
				else
				{	$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
				}
				$resp_nombre_material=mysqli_query($enlaceCon,$sql_nombre_material);
				$dat_nombre_material=mysqli_fetch_array($resp_nombre_material);
				$nombre_material=$dat_nombre_material[0];
				$presentacion=$dat_nombre_material[1];
				$detalle_ingreso.="<tr><td width='40%'>$nombre_material $presentacion</td>
				<td align='center' width='20%'>$cantidad_unitariaF</td>
				<td align='center' width='20%'>$costo_unitarioF</td>
				<td align='center' width='20%'>$costoItemF</td>
				</tr>";
			}
		}
		$detalle_ingreso.="</table>";
		if($rpt_linea==0)
		{	echo "<tr bgcolor='$color_fondo'><td align='center'>$nro_correlativo</td><td align='center'>&nbsp;$nota_entrega</td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>$nombreProveedor</td><td>&nbsp;$obs_ingreso</td><td>&nbsp;$estado_ingreso</td><td align='center'>$detalle_ingreso</td></tr>";
		}
		if($rpt_linea!=0 and $bandera==1)
		{	echo "<tr bgcolor='$color_fondo'><td align='center'>$nro_correlativo</td><td align='center'>&nbsp;$nota_entrega</td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>$nombreProveedor</td><td>&nbsp;$obs_ingreso</td><td>&nbsp;$estado_ingreso</td><td align='center'>$detalle_ingreso</td></tr>";
		}
	}
	$costoTotalF=formatonumeroDec($costoTotal);
	echo "<tr><th colspan='7'>Costo Total</th><td align='right'>$costoTotalF</td></tr>";
	echo "</table></center><br>";
?>