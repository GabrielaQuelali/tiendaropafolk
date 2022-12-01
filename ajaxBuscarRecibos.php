<?php
require("conexionmysqli.inc");
require("funciones.php");
require('function_formatofecha.php');
require("estilos_almacenes.inc");

$fechaIniBusqueda=$_GET['fechaIniBusqueda'];
$fechaFinBusqueda=$_GET['fechaFinBusqueda'];
$proveedor=$_GET['proveedor'];
$tipoRecibo=$_GET['tipoRecibo'];
$cliente=$_GET['cliente'];
$detalle=$_GET['detalle'];
$global_agencia=$_COOKIE['global_agencia'];
$globalAlmacen=$_COOKIE['global_almacen'];


if(!empty($fechaIniBusqueda) && !empty($fechaFinBusqueda) ){
	$fechaIniBusqueda=formateaFechaVista($fechaIniBusqueda);
	$fechaFinBusqueda=formateaFechaVista($fechaFinBusqueda);
}
?>
<br><center><table class='texto'>
<tr>
<th>&nbsp;</th>
<th>Tipo Recibo</th>
<th>Recibo</th>
<th>Fecha</th>
<th>Forma Pago</th>
<th>Monto</th>
<th>Contacto</th>
<th>Grupo de Recibo</th>
<th>Descripcion</th>
<th>Proveedor</th>
<th>Resta Venta<br>Proveedor</th>
<th>Registrado Por</th>
<th>Modificado Por</th>
<th>&nbsp;</th>
<th>&nbsp;</th>
<th>Estado</th>
</tr>
<?php
$consulta = " select r.id_recibo,r.fecha_recibo,r.cod_ciudad,ciu.descripcion,
r.nombre_recibo,r.desc_recibo,r.monto_recibo,
r.created_by,r.modified_by,r.created_date,r.modified_date, r.cel_recibo,r.recibo_anulado,r.cod_tipopago, tp.nombre_tipopago,
r.cod_tiporecibo, tr.nombre_tiporecibo, r.cod_proveedor, p.nombre_proveedor, r.cod_salida_almacen,
r.cod_estadorecibo, er.nombre_estado,r.resta_ventas_proveedor
from recibos r 
inner join ciudades ciu on (r.cod_ciudad=ciu.cod_ciudad)
inner join tipos_pago tp on(r.cod_tipopago=tp.cod_tipopago)
inner join tipos_recibo tr on(r.cod_tiporecibo=tr.cod_tiporecibo)
left  join proveedores p on (r.cod_proveedor=p.cod_proveedor)
left  join estados_recibo er on (r.cod_estadorecibo=er.cod_estado)
left join grupos_recibo gr on (r.cod_gruporecibo=gr.cod_gruporecibo) 
where r.cod_ciudad=".$global_agencia." ";
if(!empty($fechaIniBusqueda) && !empty($fechaFinBusqueda) ){
	$consulta = $consulta." AND '$fechaIniBusqueda'<=r.fecha_recibo AND r.fecha_recibo<='$fechaFinBusqueda' ";
}
if(!empty($tipoRecibo)){
	$consulta=$consulta." and r.cod_tiporecibo like '%".$tipoRecibo."%' ";
} 
if(!empty($proveedor)){
	$consulta=$consulta." and r.cod_proveedor like '%".$proveedor."%' ";
} 
if(!empty($cliente)){
	$consulta=$consulta." and r.nombre_recibo like '%".$cliente."%' ";
} 
if(!empty($detalle)){
	$consulta=$consulta." and r.desc_recibo like '%".$detalle."%' ";
} 
	$consulta=$consulta." order by r.id_recibo DESC,r.cod_ciudad desc ";

$resp = mysqli_query($enlaceCon,$consulta);
while ($dat = mysqli_fetch_array($resp)) {
	$id_recibo= $dat['id_recibo'];
	$fecha_recibo= $dat['fecha_recibo'];
	$vector_fecha_recibo=explode("-",$fecha_recibo);
	$fecha_recibo_mostrar=$vector_fecha_recibo[2]."/".$vector_fecha_recibo[1]."/".$vector_fecha_recibo[0];
	$cod_ciudad= $dat['cod_ciudad'];
	$descripcion= $dat['descripcion'];
	$nombre_recibo= $dat['nombre_recibo'];
	$desc_recibo= $dat['desc_recibo'];
	$monto_recibo= $dat['monto_recibo'];
	$created_by= $dat['created_by'];
	$modified_by= $dat['modified_by'];
	$created_date= $dat['created_date'];
	$modified_date= $dat['modified_date'];
	$cel_recibo = $dat['cel_recibo'];
	$recibo_anulado= $dat['recibo_anulado'];
	$cod_tipopago= $dat['cod_tipopago'];
	$nombre_tipopago= $dat['nombre_tipopago'];
	$cod_tiporecibo= $dat['cod_tiporecibo'];
	$nombre_tiporecibo= $dat['nombre_tiporecibo'];
	$cod_proveedor= $dat['cod_proveedor'];
	$nombre_proveedor= $dat['nombre_proveedor'];
	$cod_salida_almacen= $dat['cod_salida_almacen'];
	$cod_estadorecibo= $dat['cod_estadorecibo'];
	$nombre_estadorecibo= $dat['nombre_estado'];
	$resta_ventas_proveedor= $dat['resta_ventas_proveedor'];
	$nombre_gruporecibo= $dat['nombre_gruporecibo'];
	//Datos de la Venta///
	
	$sqlVenta = " SELECT s.fecha, s.hora_salida, s.nro_correlativo, s.cod_tipo_doc, td.abreviatura, razon_social, nit,
    s.monto_total,s.monto_final,concat(f.paterno,' ',f.materno,' ',f.nombres) as vendedor
    FROM salida_almacenes s
	left join tipos_salida ts  on (s.cod_tiposalida = ts.cod_tiposalida)
	left join tipos_docs td   on (s.cod_tipo_doc = td.codigo)
	left join funcionarios f   on (s.cod_chofer = f.codigo_funcionario)
    WHERE  s.cod_almacen = '".$globalAlmacen."' and s.cod_tiposalida=1001 and s.cod_salida_almacenes=".$cod_salida_almacen;		
	$respVenta = mysqli_query($enlaceCon,$sqlVenta);
	/////////
		$fecha_salida = ""; $fecha_salida_mostrar = "";		$hora_salida = "";
		$nro_correlativo= ""; $cod_tipo_doc= "";$abreviatura_tipodoc= "";
		$razon_social= "";$nit="";$monto_total="";$monto_final= "";	$vendedor= "";
	/////////
	while ($datVenta = mysqli_fetch_array($respVenta)) {	
		$fecha_salida = $datVenta['fecha'];
		$fecha_salida_mostrar = $fecha_salida[8].$fecha_salida[9]."-".$fecha_salida[5].$fecha_salida[6]."-".$fecha_salida[0].$fecha_salida[1].$fecha_salida[2].$fecha_salida[3];
		$hora_salida = $datVenta['hora_salida'];
		$nro_correlativo= $datVenta['nro_correlativo'];
		$cod_tipo_doc= $datVenta['cod_tipo_doc'];
		$abreviatura_tipodoc= $datVenta['abreviatura'];
		$razon_social= $datVenta['razon_social'];
		$nit= $datVenta['nit'];
		$monto_total= $datVenta['monto_total'];
		$monto_final= $datVenta['monto_final'];		
		$vendedor= $datVenta['vendedor'];
	}
	///////////////
	$created_date_mostrar="";
	// formatoFechaHora
	if(!empty($created_date)){
		$vector_created_date = explode(" ",$created_date);
		$fechaReg=explode("-",$vector_created_date[0]);
		$created_date_mostrar = $fechaReg[2]."/".$fechaReg[1]."/".$fechaReg[0]." ".$vector_created_date[1];
	}
	// fin formatoFechaHora
	$modified_date= $dat['modified_date'];
	$cel_recibo = $dat['cel_recibo'];
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
		
		while($datModUsu=mysqli_fetch_array($respModUsu)){
			$usuMod =$datModUsu['nombres'][0].$datModUsu['paterno'];		
		}
	 }
	////////////
	  $color_fondo = "";
	if ($recibo_anulado == 1) {
        $color_fondo = "#ff8080";
        
    }

?>	
   
 <tr style="background-color: <?=$color_fondo;?>;">
	<td><?php 
	if ($recibo_anulado == 0) {
		
		if($cod_estadorecibo==1){
	?>	
		<div id="divCheckRecibo<?php echo $id_recibo;?>"><input type="checkbox" name="id_recibo" id="id_recibo" value="<?=$id_recibo;?>"></div>
	<?php 
		}
	}
	?>	
	</td>
	<td><?=$nombre_tiporecibo;?></td>	
	<td><?=$id_recibo;?></td>
	<td><?=$fecha_recibo_mostrar;?></td>
	<td><?=$nombre_tipopago;?></td>
	<td><?=$monto_recibo;?></td>
	<td><?=$nombre_recibo;?></td>
	<td><?=$cel_recibo;?></td>
	<td><?=$desc_recibo;?></td>
	<td><?=$nombre_proveedor;?></td>
	<td>
	<?php    

		if($resta_ventas_proveedor=="0"){
			echo "NO";
		}
		if($resta_ventas_proveedor=="1"){
			echo "SI";
		}
		
		?>
	</td>	
	<td><?=$usuReg;?><br><?=$created_date_mostrar;?></td>
	<td><?=$usuMod;?><br><?=$modified_date_mostrar;?></td>		
	<td><a href="formatoRecibo.php?idRecibo=<?=$id_recibo;?>" target="_BLANK">Ver Recibo</a></td>

	<td>
	
	<div id="divVenta<?php echo $id_recibo;?>">
	<?=$abreviatura_tipodoc." ".$nro_correlativo."<br>".$fecha_salida_mostrar." ".$hora_salida." <strong>".$monto_final."</strong>";?>
	<?php 
	
	if ($recibo_anulado == 0) {
	  if (empty($cod_salida_almacen)){?>
		<a  onclick='ShowBuscarVenta(<?=$id_recibo;?>)' >Enlazar a Venta</a>
	<?php  }else{?>
		<a  onclick='quitarVenta(<?=$id_recibo;?>)' >Quitar Venta</a>
	<?php  }
		}
	?>
	</div>
		
	</td>
	<td>
		<?php 	if ($recibo_anulado == 0) { ?>
		
		<div id="divEstadoRecibo<?php echo $id_recibo;?>"><?=$nombre_estadorecibo;?>
		<?php if ($cod_estadorecibo == 1) {?>
		<a  onclick='cerrarRecibo(<?=$id_recibo;?>)' >Cerrar Recibo</a>
	
	<?php  }

	?>
		</div>
		
		<?php 	}?>
		</td>
	</tr>
<?php	
}
?>
</table></center><br>

