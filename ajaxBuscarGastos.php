<?php
require("conexionmysqli.php");
require("funciones.php");
require('function_formatofecha.php');
require("estilos_almacenes.inc");

$fechaIniBusqueda=$_GET['fechaIniBusqueda'];
$fechaFinBusqueda=$_GET['fechaFinBusqueda'];

$detalle=$_GET['detalle'];
$tipoGasto=$_GET['tipoGasto'];
$grupoGasto=$_GET['grupoGasto'];
$proveedor=$_GET['proveedor'];
$tipoPago=$_GET['tipoPago'];
$global_agencia=$_COOKIE['global_agencia'];


if(!empty($fechaIniBusqueda) && !empty($fechaFinBusqueda) ){
$fechaIniBusqueda=formateaFechaVista($fechaIniBusqueda);
$fechaFinBusqueda=formateaFechaVista($fechaFinBusqueda);
}
?>

<br><center>
<table class='texto'>
<tr>
<th>&nbsp;</th>
<th>Tipo</th>
<th>Nro Gasto</th>
<th>Fecha</th>
<th>Forma Pago</th>
<th>Monto</th>
<th>Grupo Gasto</th>
<th>Detalle</th>
<th>&nbsp;</th>
<th>Tipo<br/>Distribucion</th>
<th>Registrado Por</th>
<th>Modificado Por</th>
<th></th>
</tr>
<?php
$consulta="select g.cod_gasto,g.descripcion_gasto,g.cod_tipogasto,tg.nombre_tipogasto,g.fecha_gasto,g.monto,g.cod_ciudad,
g.created_by,g.modified_by,g.created_date,g.modified_date,g.gasto_anulado,g.cod_proveedor, p.nombre_proveedor,g.cod_grupogasto, gg.nombre_grupogasto,
g.cod_tipopago, tp.nombre_tipopago, g.cod_tipo_distribucion_costo,
tdc.desc_tipo_distribucion_costo
from gastos g
inner join tipos_gasto tg on (g.cod_tipogasto=tg.cod_tipogasto)
inner join grupos_gasto gg on (g.cod_grupogasto=gg.cod_grupogasto)
inner join tipos_pago tp on (g.cod_tipopago=tp.cod_tipopago)
left  join proveedores p on (g.cod_proveedor=p.cod_proveedor)
left  join tipos_distribucion_costo tdc on (g.cod_tipo_distribucion_costo=tdc.cod_tipo_distribucion_costo)
where g.cod_ciudad=".$global_agencia." ";
if(!empty($fechaIniBusqueda) && !empty($fechaFinBusqueda) ){
	$consulta = $consulta." AND '$fechaIniBusqueda'<=g.fecha_gasto AND g.fecha_gasto<='$fechaFinBusqueda' ";
}
if(!empty($tipoGasto)){
	$consulta=$consulta." and g.cod_tipogasto like '%".$tipoGasto."%' ";
} 
if(!empty($grupoGasto)){
	$consulta=$consulta." and g.cod_grupogasto like '%".$grupoGasto."%' ";
} 
if(!empty($tipoPago)){
	$consulta=$consulta." and g.cod_tipogasto like '%".$tipoPago."%' ";
} 
if(!empty($proveedor)){
	$consulta=$consulta." and g.cod_proveedor like '%".$proveedor."%' ";
} 
if(!empty($detalle)){
	$consulta=$consulta." and g.descripcion_gasto like '%".$detalle."%' ";
} 
$consulta.=" order by g.fecha_gasto desc";
//echo "consulta=".$consulta;

//echo "consulta=".$consulta;
$resp = mysqli_query($enlaceCon,$consulta);
while ($dat = mysqli_fetch_array($resp)) {
	$cod_gasto= $dat['cod_gasto'];
	$descripcion_gasto= $dat['descripcion_gasto'];
	$cod_tipogasto= $dat['cod_tipogasto'];
	$nombre_tipogasto= $dat['nombre_tipogasto'];
	$fecha_gasto= $dat['fecha_gasto'];	
	$vector_fecha_gasto=explode("-",$fecha_gasto);
	$fecha_gasto_mostrar=$vector_fecha_gasto[2]."/".$vector_fecha_gasto[1]."/".$vector_fecha_gasto[0];
	$monto= $dat['monto'];
	$cod_ciudad= $dat['cod_ciudad'];
	$created_by= $dat['created_by'];
	$modified_by= $dat['modified_by'];
	$created_date= $dat['created_date'];
	$modified_date= $dat['modified_date'];
	$gasto_anulado= $dat['gasto_anulado'];
	$cod_proveedor= $dat['cod_proveedor'];
	$nombre_proveedor= $dat['nombre_proveedor'];
	$cod_grupogasto= $dat['cod_grupogasto'];
	$nombre_grupogasto= $dat['nombre_grupogasto'];
	$cod_tipopago= $dat['cod_tipopago'];
	$nombre_tipopago= $dat['nombre_tipopago'];
	$cod_tipo_distribucion_costo= $dat['cod_tipo_distribucion_costo'];
	$desc_tipo_distribucion_costo= $dat['desc_tipo_distribucion_costo'];

	$created_date_mostrar="";
	// formatoFechaHora
	if(!empty($created_date)){
		$vector_created_date = explode(" ",$created_date);
		$fechaReg=explode("-",$vector_created_date[0]);
		$created_date_mostrar = $fechaReg[2]."/".$fechaReg[1]."/".$fechaReg[0]." ".$vector_created_date[1];
	}
	// fin formatoFechaHora

	$modified_date_mostrar="";
	// formatoFechaHora
	if(!empty($modified_date)){
		$vector_modified_date = explode(" ",$modified_date);
		$fechaEdit=explode("-",$vector_modified_date[0]);
		$modified_date_mostrar = $fechaEdit[2]."/".$fechaEdit[1]."/".$fechaEdit[0]." ".$vector_modified_date[1];
	}
	// fin formatoFechaHora
	
	/////	
		$sqlRegUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$created_by;
		$respRegUsu=mysqli_query($enlaceCon,$sqlRegUsu);
		$usuReg =" ";
		while($datRegUsu=mysqli_fetch_array($respRegUsu)){
			$usuReg =$datRegUsu['nombres'][0].$datRegUsu['paterno'];		
		}
	//////
	$usuMod ="";
	 if(!empty($modified_by)){
		$sqlModUsu=" select nombres,paterno  from funcionarios where codigo_funcionario=".$modified_by;
		$respModUsu=mysqli_query($enlaceCon,$sqlModUsu);
		$usuMod ="";
		while($datModUsu=mysqli_fetch_array($respModUsu)){
			$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
		}
	}
	////////////
	  $color_fondo = "";
	if ($gasto_anulado == 2) {
        $color_fondo = "#ff8080";
        
    }

?>	
   <tr style="background-color: <?=$color_fondo;?>;">
	<td><?php 
	if ($gasto_anulado == 1) {
	?>	
		<input type="checkbox" name="cod_gasto" id="cod_gasto" value="<?=$cod_gasto;?>">
	<?php 
	}
	?>	
	</td>
	<td><?=$nombre_tipogasto;?></td>
	<td><?=$cod_gasto;?></td>
	<td><?=$fecha_gasto_mostrar;?></td>
	<td><?=$nombre_tipopago;?></td>
	<td><?=$monto;?></td>
	<td><?=$nombre_grupogasto;?></td>
	<td><?=$descripcion_gasto;?></td>	
	<td>&nbsp;<?=$nombre_proveedor;?></td>
	<td><?=$desc_tipo_distribucion_costo;?></td>
	<td><?=$usuReg;?><br><?=$created_date_mostrar;?></td>
	<td><?=$usuMod;?><br><?=$modified_date_mostrar;?></td>	
	
	<td><a href="formatoGasto.php?idGasto=<?=$cod_gasto;?>" target="_BLANK">Ver Gasto</a></td>
	</tr>
<?php	
}


?>
</table>

</center><br>
