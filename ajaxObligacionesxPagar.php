<?php
require("conexionmysqli.php");
require('estilos.inc');
$codProveedor=$_GET['cod_proveedor'];


$totalDeuda=0;
$acuentaDeuda=0;
$saldoDeuda=0;
$sqlObligxPagar="select lpc.cod_lote,lpc.cod_proceso_const, pc.nombre_proceso_const, lpc.cod_proveedor, lpc.cantidad, lpc.precio,
lpc.obligacionxpagar_si_no, lpc.obligacionxpagar_fecha, lpc.cod_estado_pago,lp.nro_lote,
lp.nombre_lote,lp.codigo_material, ma.descripcion_material,lp.cod_estado_lote, el.nombre_estado as nombre_estado_lote,lp.cant_lote
from lote_procesoconst lpc
left join lotes_produccion lp on ( lp.cod_lote=lpc.cod_lote)
left join procesos_construccion pc on ( lpc.cod_proceso_const=pc.cod_proceso_const)
left join material_apoyo ma on (lp.codigo_material=ma.codigo_material)
left join estados_lote el on (lp.cod_estado_lote=el.cod_estado)
where  lp.cod_estado_lote<>4
and lpc.obligacionxpagar_si_no=1
and  lpc.cod_estado_pago<>3
and lpc.cod_proveedor=".$codProveedor."
order by lpc.obligacionxpagar_fecha asc,lp.nro_lote asc";

//echo $sqlObligxPagar."<br>";
$respObligxPagar=mysqli_query($enlaceCon,$sqlObligxPagar);

?>
<table class='texto'>
	<tr><th colspan="9">OBLIGACIONES X PAGAR</th></tr>
	<tr class="titulo_tabla" align="center">
		<td>&nbsp;</td>
		<td ><input type="checkbox" id="selectReg" name="selectReg" onclick="checkearRegistros()" checked ></td>
		<td >Tipo Doc</td><td>Nro Doc</td><td>Fecha</td><td>Desc/Obs</td><td>Monto</td><td>A cuenta</td><td>Saldo</td><td>Monto Pago</td>
	</tr>
<?php
$corr=0;
while($datObligxPagar=mysqli_fetch_array($respObligxPagar)){
	$corr++;

	$cod_lote=$datObligxPagar['cod_lote'];
	$cod_proceso_const=$datObligxPagar['cod_proceso_const'];
	$nombre_proceso_const=$datObligxPagar['nombre_proceso_const'];
	$cod_proveedor=$datObligxPagar['cod_proveedor'];
	$cantidad=$datObligxPagar['cantidad'];
	$precio=$datObligxPagar['precio'];
	$obligacionxpagar_si_no=$datObligxPagar['obligacionxpagar_si_no'];
	$obligacionxpagar_fecha=$datObligxPagar['obligacionxpagar_fecha'];
	$cod_estado_pago=$datObligxPagar['cod_estado_pago'];
	$nro_lote=$datObligxPagar['nro_lote'];
    $nombre_lote=$datObligxPagar['nombre_lote'];
    $codigo_material=$datObligxPagar['codigo_material'];
    $descripcion_material=$datObligxPagar['descripcion_material'];
    $cod_estado_lote=$datObligxPagar['cod_estado_lote'];
    $nombre_estado_lote=$datObligxPagar['nombre_estado_lote'];
    $cant_lote=$datObligxPagar['cant_lote'];

    $sqlAuxi1="select tdo.cod_tipo_doc_obligxpagar,tdo.nombre_tipo_doc_obligxpagar,
		tdo.abrev_tipo_doc_obligxpagar FROM tipos_docs_obligacionxpagar tdo 
 		where tdo.cod_tipo_doc_obligxpagar=2 ";
 	$respAuxi1=mysqli_query($enlaceCon,$sqlAuxi1);
 	while($datAuxi1=mysqli_fetch_array($respAuxi1)){
 		$abrev_tipo_doc_obligxpagar=$datAuxi1['abrev_tipo_doc_obligxpagar'];
 	}

 	$precioProcesoCons=$precio*$cant_lote;
 	// suma total Deuda
 	$totalDeuda=$totalDeuda+$precioProcesoCons;

 	//Revisar Pagos que se hicieron//
//ppd.cod_proveedor=".$cod_proveedor."
 	$sqlAuxi2=" select sum(ppd.monto_pago)
			from pagos_proveedor_detalle ppd
			left join pagos_proveedor_cab ppc on(ppd.cod_pago=ppc.cod_pago)
			where ppc.cod_estado=1
			and ppc.cod_proveedor=".$cod_proveedor."
			and ppd.codigo_doc=".$cod_lote."
			and ppd.cod_proceso_const=".$cod_proceso_const;
			//echo $sqlAux2."<br>";
	$respAuxi2=mysqli_query($enlaceCon,$sqlAuxi2);
	$acuentaProcesoCons=0;
 	while($datAuxi2=mysqli_fetch_array($respAuxi2)){
 		//echo "Ingresaa<br>";
 		$acuentaProcesoCons=$datAuxi2[0];
 		if($acuentaProcesoCons==null){
 			$acuentaProcesoCons=0;
 		}
 	}
 	// suma total Acuenta Deuda
 	 	$acuentaDeuda=$acuentaDeuda+$acuentaProcesoCons;


 	$saldoProcesoCons=$precioProcesoCons-$acuentaProcesoCons;
 	// suma total Saldo
 	$saldoDeuda=$saldoDeuda+$saldoProcesoCons;
?>

	<tr >
		<td><?=$corr;?></td>
		<td><input type="checkbox"  id="docPagar<?=$cod_lote.$cod_proceso_const.$cod_proveedor;?>"
			name="docPagar<?=$cod_lote.$cod_proceso_const.$cod_proveedor;?>"
		 checked onclick="sumarPagoDeudaLote()" ></td>
		<td><?=$abrev_tipo_doc_obligxpagar;?></td>
		<td><?=$nro_lote;?></td>
		<td><?=$obligacionxpagar_fecha;?></td>
		<td><?=$descripcion_material."<br>".$nombre_proceso_const."<br>Cant:".$cant_lote." Precio  x Unidad:".$precio;?></td>
		<td><?=$precioProcesoCons;?></td><td><?=$acuentaProcesoCons;?></td><td><?=$saldoProcesoCons;?></td>
		<td>
			<input type="hidden" name="saldoDeudaLote<?=$cod_lote.$cod_proceso_const.$cod_proveedor?>" 
			id="saldoDeudaLote<?=$cod_lote.$cod_proceso_const.$cod_proveedor?>" value="<?=$saldoProcesoCons;?>">
			<input size="7" class="inputnumber" type="number" 
			name="montoPagoDeudaLote<?=$cod_lote.$cod_proceso_const.$cod_proveedor?>" 
			id="montoPagoDeudaLote<?=$cod_lote.$cod_proceso_const.$cod_proveedor?>" 
			value="<?=$saldoProcesoCons;?>" onKeyUp="sumarPagoDeudaLote();">
		</td>
	</tr>


<?php	
}

?>
<tr >
		<td colspan="5" align="right"><strong>TOTAL A PAGAR</strong></td>

		<td><?=$totalDeuda;?></td><td><?=$acuentaDeuda;?></td><td><?=$saldoDeuda;?></td>
		<td><div id="divMontoTotalaPagarDeuda" name="divMontoTotalaPagarDeuda"><?=$saldoDeuda;?></div></td>
	</tr>
</table>

<center><table  class='texto'>
		<tr class="titulo_tabla" align="left">
		<td >Tipo Pago</td><td>Monto</td><td>Banco</td><td>Nro Cuenta</td>
		<td>Nombre Cuenta</td><td>Inf</td>
	</tr>
<?php	
	$sqlTipoPago="select cod_tipopago, nombre_tipopago,estado,contabiliza,banco,nro_cta,nombre_cta
			from tipos_pago where estado=1 order by cod_tipopago asc";
		
		$respTipoPago=mysqli_query($enlaceCon,$sqlTipoPago);
 		while($datTipoPago=mysqli_fetch_array($respTipoPago)){
 			$cod_tipopago=$datTipoPago['cod_tipopago'];
 			$nombre_tipopago=$datTipoPago['nombre_tipopago'];
 			$contabiliza=$datTipoPago['contabiliza'];
 			$banco=$datTipoPago['banco'];
 			$nro_cta=$datTipoPago['nro_cta'];
 			$nombre_cta=$datTipoPago['nombre_cta'];


?>
<tr >
		<td><?=$nombre_tipopago;?></td>
		<td><input size="7" class="inputnumber" type="number" name="montoTipoPago<?=$cod_tipopago;?>"
		 id="montoTipoPago<?=$cod_tipopago;?>" step="0.01"  value="0" onKeyUp="calcularTotalPago();"></td>
		 <td>
<?php 
	if($banco==1){
		$sqlBanco="select codigo,nombre, abreviatura from bancos where estado=1 ";
		$respBanco=mysqli_query($enlaceCon,$sqlBanco);
?>
	<select name="cod_banco<?=$cod_tipopago;?>" id="cod_banco<?=$cod_tipopago;?>" >
			
<?php		
 		while($datBanco=mysqli_fetch_array($respBanco)){
 			$codigo=$datBanco['codigo'];
 			$nombre=$datBanco['nombre'];
 			$abreviatura=$datBanco['abreviatura'];
?>
		<option value="<?=$codigo;?>"><?=$nombre;?></option>
<?php 			
 		}
?>		
<?php 
	}
?>	
	</select> 
	</td>
<td>
<?php 
	if($nro_cta==1){
?>	

<input type="text" class="texto" name="nro_cta<?=$cod_tipopago;?>" id="nro_cta<?=$cod_tipopago;?>" size="20" 
	>
<?php 
	}
?>	
</td>
<td>
<?php 
	if($nombre_cta==1){
?>	

<input type="text" class="texto" name="nombre_cta<?=$cod_tipopago;?>" id="nombre_cta<?=$cod_tipopago;?>" size="20" 
	>
<?php 
	}
?>	
</td>
<td>
<input type="text" class="texto" name="infAdicional<?=$cod_tipopago;?>" id="infAdicional<?=$cod_tipopago;?>" size="20" 
	>
</td>
	</tr>
<?php 			
 		}
?>
<tr>
	<td>Total Bs</td><td><div id="montoTotalPago" name="montoTotalPago" >0</div></td>
	<td ><a onclick="distribuirMontoTotalPago();" ><img src="imagenes/repartir.jpg" width="40" ></a></td>
	<td colspan="3">&nbsp;</td>
</tr>
</table>
</center>


