<?php

require('estilos_reportes_almacencentral.php');
	//require('estilos_almacenes_central_sincab.php');
require('function_formatofecha.php');
require('conexionmysqli.inc');
require('funcion_nombres.php');
require('funciones.php');

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];
//echo "fecha inicio=".$fecha_ini;
//echo "fecha fin=".$fecha_fin;
$fecha_ini=$fecha_ini."-01";
$fecha_fin=date("Y-m-t", strtotime($fecha_fin));
//echo "fecha inicio=".$fecha_ini;
//echo "fecha fin=".$fecha_fin;


$global_usuario=$_COOKIE['global_usuario'];
$globalTipoFuncionario=$_COOKIE['globalTipoFuncionario'];
$globalAlmacen=$_COOKIE['global_almacen'];

$sqlFuncProv="select * from funcionarios_proveedores where codigo_funcionario=$global_usuario";
$respFuncProv=mysqli_query($enlaceCon,$sqlFuncProv);
$cantFuncProv=mysqli_num_rows($respFuncProv);
$global_agencia=$_COOKIE['global_agencia'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=($fecha_ini);
$fecha_finconsulta=($fecha_fin);

$rptTerritorio=$_GET['rpt_territorio'];


$cadenaTerritorio="TODOS";	
if($rptTerritorio=="-1"){
	$cadenaTerritorio="TODOS";
	$rptTerritorio=""; $swTerritorio=0;	 
	$sqlCiudades="select cod_ciudad, descripcion from ciudades  order by descripcion asc";
	$respCiudades=mysqli_query($enlaceCon,$sqlCiudades);
	while($datCiudades=mysqli_fetch_array($respCiudades))
	{	$codCiudad=$datCiudades[0];
		if($swTerritorio==0){
			$rptTerritorio=$datCiudades[0];
			$swTerritorio=1;
		}else{
			$rptTerritorio=$rptTerritorio.",";
			$rptTerritorio=$rptTerritorio.$datCiudades[0];
		}
	}

}else{
	$swCadenaTerritorio=0;	
	$sqlCiudades="select cod_ciudad, descripcion from ciudades where  cod_ciudad in(".$rptTerritorio.")	order by descripcion asc";
	$respCiudades=mysqli_query($enlaceCon,$sqlCiudades);
	while($datCiudades=mysqli_fetch_array($respCiudades)){	
		if($swCadenaTerritorio==0){
			$cadenaTerritorio=$datCiudades[1];
			$swCadenaTerritorio=1;
		}else{
			$cadenaTerritorio=$cadenaTerritorio.";";
			$cadenaTerritorio=$cadenaTerritorio.$datCiudades[1];
		}
		
	}

	
}

$fecha_reporte=date("d/m/Y");

?>
<html>
<body>
	
<table align='center'  >
<tr class='textotit' align='center' ><th  colspan='2'>OBLIGACIONES X PAGAR</th></tr>
	<tr ><th>Territorio:</th><td><?=$cadenaTerritorio;?></td> </tr>
	<tr><th>De:</th> <td> <?=$fecha_ini." A:".$fecha_fin;?></td></tr>
	<tr><th>Fecha Reporte:</th> <td><?=$fecha_reporte;?></td></tr>	
	</table>
<br/>

<?php

setlocale(LC_ALL, 'es_ES');
$fecha_iniconsulta=$fecha_ini;
$fecha_finconsulta=$fecha_fin;

$fechaInicioTime = strtotime($fecha_ini);
$mesInicio=date("n",$fechaInicioTime);
$anioInicio=date("Y",$fechaInicioTime);
//echo "mes=".$mesInicio." anio=".$anioInicio;

$fechaFinTime = strtotime($fecha_fin);
$mesFin=date("n",$fechaFinTime);
$anioFin=date("Y",$fechaFinTime);
?>

<br/>
<center>

	<table border="1">

<?php

$montoTotal=0;
$acuentaTotal=0;
$saldoTotal=0;

$sqlProveedores="select distinct(aaa.cod_proveedor) as cod_proveedor,prov.nombre_proveedor
from (select  lp.cod_lote,lp.fecha_lote,lp.nro_lote,lp.nombre_lote,lp.obs_lote,lp.codigo_material,lp.cant_lote,
lp.cod_estado_lote,lp.created_by,lp.created_date,lp.fecha_inicio_lote,lp.fecha_fin_lote,lp.obligacionxpagar_si_no as obligacionxpagar_lote ,
lp.cod_estado_pago as estado_pago_lote,
lpc.cod_proceso_const,lpc.cod_proveedor,lpc.cantidad,lpc.precio,(lpc.cantidad*lpc.precio) deuda,IFNULL(pa.acuenta,0) acuenta,((lpc.cantidad*lpc.precio)- IFNULL(pa.acuenta,0)) saldo,
lpc.obligacionxpagar_si_no ,
lpc.obligacionxpagar_fecha,lpc.cod_estado_pago
from lotes_produccion lp
left join lote_procesoconst lpc on (lp.cod_lote=lpc.cod_lote)
left join (select ppc.cod_proveedor,ppd.codigo_doc as cod_lote,ppd.cod_proceso_const, sum(ppd.monto_pago) acuenta
from pagos_proveedor_cab ppc 
left join pagos_proveedor_detalle ppd on (ppc.cod_pago=ppd.cod_pago)
where ppc.cod_estado=1
group by ppc.cod_proveedor,ppd.codigo_doc,ppd.cod_proceso_const) pa 
on (pa.cod_lote=lpc.cod_lote and pa.cod_proveedor=lpc.cod_proveedor and pa.cod_proceso_const=lpc.cod_proceso_const)
where lp.cod_estado_lote<>4
and lp.cod_ciudad in (".$rptTerritorio.")
and lpc.obligacionxpagar_si_no=1
and ((lpc.cantidad*lpc.precio)- IFNULL(pa.acuenta,0)) >0) aaa
left join proveedores prov on ( aaa.cod_proveedor=prov.cod_proveedor)
order by prov.nombre_proveedor asc";
$nroCorr=0;
	$respProveedores=mysqli_query($enlaceCon,$sqlProveedores);
	$montoProveedorTotal=0;
	$acuentaProveedorTotal=0;
	$saldoProveedorTotal=0;

	while($datProveedores=mysqli_fetch_array($respProveedores)){	
		$nroCorr++;
		$cod_proveedor=$datProveedores['cod_proveedor'];
		$nombre_proveedor=$datProveedores['nombre_proveedor'];
?>
	<tr bgcolor="#CDFAFC">
		<td><strong><?=$nroCorr;?></strong></td>
		<td colspan="7" align="center"><strong><?=$nombre_proveedor;?></strong></td>
	</tr>
<?php		
		
		$sqlCiu="select distinct(bbb.cod_ciudad) cod_ciudad,ciu.descripcion descCiudad 
				from(select  lp.cod_lote,lp.fecha_lote,lp.nro_lote,lp.nombre_lote,lp.obs_lote,
				lp.codigo_material,lp.cant_lote,lp.cod_estado_lote,lp.created_by,lp.created_date,
				lp.fecha_inicio_lote,lp.fecha_fin_lote,lp.obligacionxpagar_si_no as obligacionxpagar_lote ,
				lp.cod_estado_pago as estado_pago_lote,lp.cod_ciudad,
				lpc.cod_proceso_const,lpc.cod_proveedor,lpc.cantidad,lpc.precio,
				(lpc.cantidad*lpc.precio) deuda,IFNULL(pa.acuenta,0) acuenta,
				((lpc.cantidad*lpc.precio)- IFNULL(pa.acuenta,0)) saldo,
				lpc.obligacionxpagar_si_no ,
				lpc.obligacionxpagar_fecha,lpc.cod_estado_pago
				from lotes_produccion lp
				left join lote_procesoconst lpc on (lp.cod_lote=lpc.cod_lote)
				left join (select ppc.cod_proveedor,ppd.codigo_doc as cod_lote,ppd.cod_proceso_const, sum(ppd.monto_pago) acuenta
					from pagos_proveedor_cab ppc 
					left join pagos_proveedor_detalle ppd on (ppc.cod_pago=ppd.cod_pago)
					where ppc.cod_estado=1
					group by ppc.cod_proveedor,ppd.codigo_doc,ppd.cod_proceso_const) pa 
					on (pa.cod_lote=lpc.cod_lote and pa.cod_proveedor=lpc.cod_proveedor and pa.cod_proceso_const=lpc.cod_proceso_const)
				where lp.cod_estado_lote<>4
				and lpc.cod_proveedor in (".$cod_proveedor.")
				and lpc.obligacionxpagar_si_no=1
				and ((lpc.cantidad*lpc.precio)- IFNULL(pa.acuenta,0)) >0) bbb
				left join ciudades ciu on (bbb.cod_ciudad=ciu.cod_ciudad)
				order by ciu.descripcion  asc";

			$respCiu=mysqli_query($enlaceCon,$sqlCiu);
			while($datCiu=mysqli_fetch_array($respCiu)){

				$cod_ciudad=$datCiu['cod_ciudad'];
				$descCiudad=$datCiu['descCiudad'] ;


	?>
	<tr bgcolor="#DBFCCD">
		<td>&nbsp;</td>
		<td colspan="7" align="center"><strong><?=$descCiudad;?></strong></td>
	</tr>
	<tr>
		<td>&nbsp;</td>
				<td align="center"><strong>Nro</strong></td>
				<td align="center"><strong>Fecha</strong></td>
				<td align="center"><strong>Proceso</strong></td>				
				<td align="center"><strong>Monto</strong></td>
				<td align="center"><strong>A cuenta</strong></td>
				<td align="center"><strong>Saldo</strong></td>
				<td align="center"><strong>Estado Pago</strong></td>
	</tr>
	<?php	
			///////////////////////////////
				$montoProveedorCiudadTotal=0;
				$acuentaProveedorCiudadTotal=0;
				$saldoProveedorCiudadTotal=0;

			$sqlDet="select  lp.cod_lote,lp.fecha_lote,lp.nro_lote,lp.nombre_lote,lp.obs_lote,
			lp.codigo_material, ma.descripcion_material,lp.cant_lote,lp.cod_estado_lote,lp.created_by,
			lp.created_date,
			lp.fecha_inicio_lote,lp.fecha_fin_lote,lp.obligacionxpagar_si_no as obligacionxpagar_lote ,
			lp.cod_estado_pago as estadoPagoLote,ep2.nombre_estado_pago as nomEstadoPagoLote,
			lp.cod_ciudad,lpc.cod_proceso_const, pc.nombre_proceso_const, lpc.cod_proveedor,lpc.cantidad,lpc.precio,
			(lpc.cantidad*lpc.precio) deuda,IFNULL(pa.acuenta,0) acuenta,
			((lpc.cantidad*lpc.precio)- IFNULL(pa.acuenta,0)) saldo,lpc.obligacionxpagar_si_no ,
			lpc.obligacionxpagar_fecha,lpc.cod_estado_pago, ep.nombre_estado_pago
			from lotes_produccion lp
			left join lote_procesoconst lpc on (lp.cod_lote=lpc.cod_lote)
			left join (select ppc.cod_proveedor,ppd.codigo_doc as cod_lote,
						ppd.cod_proceso_const, sum(ppd.monto_pago) acuenta
						from pagos_proveedor_cab ppc 
						left join pagos_proveedor_detalle ppd on (ppc.cod_pago=ppd.cod_pago)
						where ppc.cod_estado=1
						group by ppc.cod_proveedor,ppd.codigo_doc,ppd.cod_proceso_const) pa 
						on (pa.cod_lote=lpc.cod_lote and pa.cod_proveedor=lpc.cod_proveedor and pa.cod_proceso_const=lpc.cod_proceso_const)

			left join estados_pago ep2 on (lp.cod_estado_pago=ep2.cod_estado_pago)
			left join estados_pago ep on (lpc.cod_estado_pago=ep.cod_estado_pago)
			left join material_apoyo ma on (lp.codigo_material=ma.codigo_material)
			left join procesos_construccion pc on (lpc.cod_proceso_const=pc.cod_proceso_const)

			where lp.cod_estado_lote<>4
			and lp.cod_ciudad=".$cod_ciudad."
			and lpc.cod_proveedor=".$cod_proveedor."
			and lpc.obligacionxpagar_si_no=1
			and ((lpc.cantidad*lpc.precio)- IFNULL(pa.acuenta,0)) >0
			order by lpc.obligacionxpagar_fecha";
			$conDet=0;
			$deuda=0;
			$acuenta=0;
			$saldo=0;
			$respDet=mysqli_query($enlaceCon,$sqlDet);
			while($datDet=mysqli_fetch_array($respDet)){
				$conDet++;
				$nro_lote=$datDet['nro_lote'];
				$fecha_lote=$datDet['fecha_lote'];
				$fecha_lote= explode('-',$fecha_lote);
				$fecha_lote_mostrar=$fecha_lote[2]."/".$fecha_lote[1]."/".$fecha_lote[0];
				$descripcion_material=$datDet['descripcion_material'];
				$nombre_proceso_const=$datDet['nombre_proceso_const'];
				$obligacionxpagar_fecha=$datDet['obligacionxpagar_fecha'];
				$obligacionxpagar_fecha= explode('-',$obligacionxpagar_fecha);
				$obligacionxpagar_fecha_mostrar=$obligacionxpagar_fecha[2]."/".$obligacionxpagar_fecha[1]."/".$obligacionxpagar_fecha[0];

				$deuda=$datDet['deuda'];
				$acuenta=$datDet['acuenta'];
				$saldo=$datDet['saldo'];
				$nombre_estado_pago=$datDet['nombre_estado_pago'];

				$montoProveedorCiudadTotal=$montoProveedorCiudadTotal+$deuda;
				$acuentaProveedorCiudadTotal=$acuentaProveedorCiudadTotal+$acuenta;
				$saldoProveedorCiudadTotal=$saldoProveedorCiudadTotal+$saldo;

			?>
			<tr>
				<td >&nbsp;</td>
				<td ><?=$conDet;?></td>
				<td><?=$obligacionxpagar_fecha_mostrar;?></td>
				<td><?=$nombre_proceso_const;?><br/><?="Producto: ".$descripcion_material;?><br/><?="Nro Lote: ".$nro_lote;?></td>				
				<td align="right"><?=number_format($deuda,2,'.',',');?></td>
				<td align="right"><?=number_format($acuenta,2,'.',',');?></td>
				<td align="right"><?=number_format($saldo,2,'.',',');?></td>
				<td align="center"><?=$nombre_estado_pago;?></td>
			</tr>
		<?php	

			}

		?>
			<tr bgcolor="#DBFCCD">
				<td >&nbsp;</td>
				<td colspan="3" align="right"><strong>TOTAL <?=$nombre_proveedor;?> <?=$descCiudad;?></strong></td>				
				<td align="right"><strong><?=number_format($montoProveedorCiudadTotal,2,'.',',');?></strong></td>
				<td align="right"><strong><?=number_format($acuentaProveedorCiudadTotal,2,'.',',');?></strong></td>
				<td align="right"><strong><?=number_format($saldoProveedorCiudadTotal,2,'.',',');?></strong></td>
				<td>&nbsp;</td>
			</tr>


			
		<?php

				$montoTotal=$montoTotal+$montoProveedorCiudadTotal;
				$acuentaTotal=$acuentaTotal+$acuentaProveedorCiudadTotal;
				$saldoTotal=$saldoTotal+$saldoProveedorCiudadTotal;

			//////////////////////////////

			}


	}
?>
<tr bgcolor="#CDFAFC">
				<td >&nbsp;</td>
				<td colspan="3" align="right"><strong>TOTALES</strong></td>				
				<td align="right"><strong><?=number_format($montoTotal,2,'.',',');?></strong></td>
				<td align="right"><strong><?=number_format($acuentaTotal,2,'.',',');?></strong></td>
				<td align="right"><strong><?=number_format($saldoTotal,2,'.',',');?></strong></td>
				<td>&nbsp;</td>
			</tr>




	
</table>
</center>
</body>
</html>

<?php 

include("imprimirInc.php");
?>