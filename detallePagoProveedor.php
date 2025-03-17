<?php
	require("conexionmysqli.php");
	require('estilos_almacenes_central_sincab.php');
	require("funciones.php");
	error_reporting(E_ALL);
// ini_set('display_errors', '1');

	$codCargo=$_COOKIE['global_cargo'];
	
$cod_pago=$_GET['cod_pago'];
		

	$sql="select ppc.cod_pago,ppc.fecha,ppc.monto_pago,ppc.observaciones,
ppc.cod_proveedor,p.nombre_proveedor,ppc.cod_estado,e.nombre_estado ,ppc.nro_pago,
ppc.created_by,concat(fc.nombres, fc.paterno,fc.materno) creado_por, ppc.created_date,
ppc.modified_by,concat(fm.nombres, fm.paterno,fm.materno) modificado_por, ppc.modified_date
from pagos_proveedor_cab ppc
left join estados e on (ppc.cod_estado=e.cod_estado)
left join proveedores p on (ppc.cod_proveedor=p.cod_proveedor)
left join funcionarios fc on (ppc.created_by=fc.codigo_funcionario)
left join funcionarios fm on (ppc.modified_by=fm.codigo_funcionario)

where ppc.cod_pago=".$cod_pago;

	
	//echo $sql;
?>	
<br>
	<center>
		<table border="1">

<?php

	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp)){
		$fecha=$dat['fecha'];
		$monto_pago=$dat['monto_pago'];
		$observaciones=$dat['observaciones'];
		$cod_proveedor=$dat['cod_proveedor'];
		$nombre_proveedor=$dat['nombre_proveedor'];
		$cod_estado=$dat['cod_estado'];
		$nombre_estadoPagoProv=$dat['nombre_estado'];
		$nro_pago=$dat['nro_pago'];
		$created_by=$dat['created_by'];
		$creado_por=$dat['creado_por'];
		$created_date=$dat['created_date'];
		$modified_by=$dat['modified_by'];
		$modificado_por=$dat['modificado_por'];
 		$modified_date=$dat['modified_date'];
		
		$fecha_registro= explode(' ',$created_date);
		$fecha_reg=$fecha_registro[0];
    	$fecha_reg_mostrar = "$fecha_reg[8]$fecha_reg[9]/$fecha_reg[5]$fecha_reg[6]/$fecha_reg[0]$fecha_reg[1]$fecha_reg[2]$fecha_reg[3] $fecha_registro[1]";

    	
    	$fecha_pago_mostrar="";
    	if($modified_date!=null){
    		$fecha_modificado= explode(' ',$modified_date);
			$fecha_mod=$fecha_modificado[0];
    		$fecha_mod_mostrar = "$fecha_mod[8]$fecha_mod[9]/$fecha_mod[5]$fecha_mod[6]/$fecha_mod[0]$fecha_mod[1]$fecha_mod[2]$fecha_mod[3] $fecha_modificado[1]";		
		}

		$fecha_pago= explode('-',$fecha);
		$fecha_pago_mostrar=$fecha_pago[2]."/".$fecha_pago[1]."/".$fecha_pago[0];
	}
	?>
	<tr>
		<td align="center" colspan="7"><strong>Pago <?="Nro:".$nro_pago;?><br/> </strong>
			<strong><?="Fecha:".$fecha_pago_mostrar;?> </strong>
		</td>
	</tr>
	<tr>
		<td><strong>Proveedor:</strong></td>
		<td><?=$nombre_proveedor;?></td>
		<td><strong>Monto:</strong></td>
		<td><?=$monto_pago;?></td>
		<td><strong>Fecha Registro:</strong></td>
		<td colspan="2"><?=$fecha_reg_mostrar;?></td>
	</tr>		
	<tr>
		<td><strong>Observaciones:</strong></td>
		<td colspan="6"><?=$observaciones;?></td>
	</tr>
			<tr align="center">
			<td colspan="7"><strong>DETALLE TIPOS DE PAGO</strong></td>
			

	</tr>
	<tr align="center">
			<td><strong>Nro</strong></td>
			<td><strong>Monto</strong></td>
			<td><strong>Tipo Pago</strong></td>
			<td><strong>Banco</strong></td>
			<td><strong>Nro Cuenta</strong></td>
			<td><strong>Nombre Cuenta</strong></td>
			<td><strong>Inf Adicional</strong></td>

	</tr>
	<?php
		$sqlPagos="select ppdp.cod_pago,ppdp.cod_tipo_pago,tp.nombre_tipopago,
		ppdp.cod_moneda,ppdp.monto_pago,ppdp.cod_banco,b.nombre as nomBanco, ppdp.nro_cuenta,ppdp.nombre_cuenta,ppdp.inf_adicional
			from pagos_proveedor_detalle_pago ppdp
		left join tipos_pago tp on (ppdp.cod_tipo_pago=tp.cod_tipopago)
		left join bancos b on (ppdp.cod_banco=b.codigo)
		where ppdp.cod_pago=".$cod_pago;
		$respPagos=mysqli_query($enlaceCon,$sqlPagos);
		$corrTiposPago=0;
		while($datPagos=mysqli_fetch_array($respPagos)){
			$corrTiposPago++;
			$cod_tipo_pago=$datPagos['cod_tipo_pago'];
			$nombre_tipopago=$datPagos['nombre_tipopago'];
			$cod_moneda=$datPagos['cod_moneda'];
			$monto_pago=$datPagos['monto_pago'];
			$cod_banco=$datPagos['cod_banco'];
			$nomBanco=$datPagos['nomBanco'];
			$nro_cuenta=$datPagos['nro_cuenta'];
			$nombre_cuenta=$datPagos['nombre_cuenta'];
			$inf_adicional=$datPagos['inf_adicional'];
		?>
		<tr>
			<td><?=$corrTiposPago;?></td>			
			<td><?=$monto_pago;?></td>
			<td><?=$nombre_tipopago;?></td>
			<td><?=$nomBanco;?></td>
			<td><?=$nro_cuenta;?></td>
			<td><?=$nombre_cuenta;?></td>
			<td><?=$inf_adicional;?></td>
		</tr>
		<?php
		
		}
	?>
		</table></center><br>
	<center><table border="1">	
					<tr align="center">
			<td colspan="5"><strong>DETALLE</strong></td>
			

	</tr>
	<tr align="center">
			<td><strong>Nro</strong></td>
			<td><strong>Tipo Doc</strong></td>			
			<td><strong>Documento</strong></td>
			<td><strong>Fecha</strong></td>
			<td><strong>Descripcion</strong></td>
			<td><strong>Monto</strong></td>

	</tr>		
	<?php 
		$sqlDetalle=" select ppd.cod_tipo_doc_obligxpagar, ppd.codigo_doc, ppd.monto_pago, ppd.cod_proceso_const  from pagos_proveedor_detalle ppd
           where ppd.cod_pago=".$cod_pago." order by ppd.orden asc ";
//echo $sqlDetalle;
         $respDetalle=mysqli_query($enlaceCon,$sqlDetalle);
         $corrDet=0;
         $montotDetalleTotal=0;
		while($datDetalle=mysqli_fetch_array($respDetalle)){
			$corrDet++;
			$cod_tipo_doc_obligxpagar=$datDetalle['cod_tipo_doc_obligxpagar'];
			$codigo_doc=$datDetalle['codigo_doc'];
			$monto_pagoDet=$datDetalle['monto_pago'];
			$cod_proceso_const=$datDetalle['cod_proceso_const'];
			$montotDetalleTotal=$montotDetalleTotal+$monto_pagoDet;

			/// LOTES
			if($cod_tipo_doc_obligxpagar==2){
					$sqlLotes="select lpc.cod_lote,lpc.cod_proceso_const,pc.nombre_proceso_const, lpc.cod_proveedor,lp.nro_lote, lp.fecha_lote, lpc.cantidad,
					lpc.precio, lpc.obligacionxpagar_fecha, lpc.cod_estado_pago,ma.descripcion_material
					from lote_procesoconst lpc
					left join lotes_produccion lp on (lpc.cod_lote=lp.cod_lote)
					left join material_apoyo ma on (lp.codigo_material=ma.codigo_material)
					left join procesos_construccion pc on (lpc.cod_proceso_const=pc.cod_proceso_const)
					where lpc.cod_lote=".$codigo_doc."
					and lpc.cod_proveedor=".$cod_proveedor."
					and lpc.cod_proceso_const=".$cod_proceso_const."
					 order by lpc.obligacionxpagar_fecha asc, lp.nro_lote asc";
					 //echo $sqlLotes;
					$respLotes=mysqli_query($enlaceCon,$sqlLotes);
					while($datLotes=mysqli_fetch_array($respLotes)){
						$nro_lote=$datLotes['nro_lote'];
						$fecha_lote=$datLotes['fecha_lote'];
						$fecha_lote= explode('-',$fecha_lote);
						$fecha_lote_mostrar=$fecha_lote[2]."/".$fecha_lote[1]."/".$fecha_lote[0];
						$obligacionxpagar_fecha=$datLotes['obligacionxpagar_fecha'];
						$obligacionxpagar_fecha= explode('-',$obligacionxpagar_fecha);
						$fobligacionxpagar_fecha_mostrar=$obligacionxpagar_fecha[2]."/".$obligacionxpagar_fecha[1]."/".$obligacionxpagar_fecha[0];
						$nombre_proceso_const=$datLotes['nombre_proceso_const'];
						$descripcion_material=$datLotes['descripcion_material'];
					}	
	?>		
		<tr >
			<td><?=$corrDet;?></td>
			<td>Lote</td>
			<td><?="Nro Lote.".$nro_lote." ".$fecha_lote_mostrar;?></td>
			<td><?=$fecha_lote_mostrar;?></td>
			<td><?=$nombre_proceso_const;?><br><strong><?=$descripcion_material;?></strong></td>
			<td align="right"><?=$monto_pagoDet?></td>

		</tr>	
	
	<?php				
			}
			// FIN LOTES
		}
	?>
		<tr >
			<td colspan="5" align="right"><strong>TOTAL</strong></td>
			<td align="right"><?=$montotDetalleTotal?></td>

		</tr>	
	</table></center><br>

<center><a href='javascript:window.print();'><IMG border='no'
	src='imagenes/print.jpg' width='40'></a></center>