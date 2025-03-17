<?php
	require("conexionmysqli2.inc");
	require('estilos.inc');
	require("funciones.php");

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_pagoProv.php';
		}

		function eliminar_nav(f)
		{
			var i;
			var j=0;
			var datos;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos=(f.elements[i].value).split('/');
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un registro.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro.');
				}
				else
				{
					if(confirm('Esta seguro de Anular el PAGO Nro.'+datos[1]))
					{
						location.href='eliminar_PagoProveedor.php?cod_pago='+datos[0];
					}
					else
					{
					return(false);
					}


				}
			}
		}
    function cambiar_vista(f)
		{
			var estado;

			estado=f.estado.value;
			
		
			location.href='listadoPagosProveedor.php?estado='+estado;
		}

		
		</script>";
		

	?><script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="functionsGeneral.js">


</script>
<?php

	$estado=$_GET['estado'];
	$globalAgencia=$_COOKIE['global_agencia'];
	$global_almacen=$_COOKIE['global_almacen'];

   ?>

<h3 align="center">Listado de Pagos a Proveedor</h3>

	<form method="post" action="">
<?php
	$sql="select ppc.cod_pago,ppc.fecha,ppc.monto_pago,ppc.observaciones,
ppc.cod_proveedor,p.nombre_proveedor,ppc.cod_estado,e.nombre_estado ,ppc.nro_pago,
ppc.created_by,concat(fc.nombres, fc.paterno,fc.materno) creado_por, ppc.created_date,
ppc.modified_by,concat(fm.nombres, fm.paterno,fm.materno) modificado_por, ppc.modified_date
from pagos_proveedor_cab ppc
left join estados e on (ppc.cod_estado=e.cod_estado)
left join proveedores p on (ppc.cod_proveedor=p.cod_proveedor)
left join funcionarios fc on (ppc.created_by=fc.codigo_funcionario)
left join funcionarios fm on (ppc.modified_by=fm.codigo_funcionario)

where ppc.cod_pago<>0 ";
if($estado<>''){
 $sql=$sql." and ppc.cod_estado='".$estado."'";
}
$sql=$sql." order by ppc.fecha desc, ppc.nro_pago desc";		
	//echo $sql;
?>
	
<table align="center" class="texto"><tr><th>Ver Pagos:
	<select name="estado" id="estado" class="texto" onChange="cambiar_vista(this.form)">
	<?php		
			$sql2="select e.cod_estado, e.nombre_estado from estados e order by e.cod_estado asc";
			$resp2=mysqli_query($enlaceCon,$sql2);
	?>
		<option value="" selected>TODOS</option>
		<?php
			while($dat2=mysqli_fetch_array($resp2)){
				$codEstado=$dat2[0];
				$nombreEstado=$dat2[1];
				if($codEstado==$estado){
		?>
				  <option value="<?=$codEstado;?>" selected><?=$nombreEstado;?></option>
		<?php		}else{ ?>
				<option value="<?=$codEstado;?>"><?=$nombreEstado;?></option>
		<?php		}
			}
		?>
			 </select>
	</th>
	</tr></table><br>
<div class='divBotones'>
		<input type="button" value="Adicionar" name="adicionar" class="boton" onclick="enviar_nav()">		
		<input type="button"  value="Editar" name="Editar" class="boton"  onclick="editar_nav(this.form)">
		<input type="button"  value="Anular" name="eliminar" class="boton2"  onclick="eliminar_nav(this.form)">
		
		</div> <br> 
	
	<center>
		<table class="texto">
	<tr>
		<th>&nbsp;</th>
		<th>Nro Pago</th>
		<th>Fecha </th>
		<th>Proveedor</th>
		<th>Monto de Pago</th>
		<th>Documentos</th>
		<th>Obs</th>
		<th>Fecha Registro</th>
		<th>Fecha Modificacion</th>		
		<th>Estado</th>
		<th>&nbsp;</th>
	</tr>
<?php
	$indice_tabla=1;
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp)){


		$cod_pago=$dat['cod_pago'];
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
    $fecha_reg_mostrar = "$fecha_reg[8]$fecha_reg[9]-$fecha_reg[5]$fecha_reg[6]-$fecha_reg[0]$fecha_reg[1]$fecha_reg[2]$fecha_reg[3] $fecha_registro[1]";
    	$fecha_modificado= explode(' ',$modified_date);
		$fecha_mod=$fecha_modificado[0];
    $fecha_mod_mostrar = "$fecha_mod[8]$fecha_mod[9]-$fecha_mod[5]$fecha_mod[6]-$fecha_mod[0]$fecha_mod[1]$fecha_mod[2]$fecha_mod[3] $fecha_modificado[1]";		

		$fecha_pago= explode('-',$fecha);
		$fecha_pago_mostrar=$fecha_pago[2]."/".$fecha_pago[1]."/".$fecha_pago[0];
	?>
	<tr>
		<td align="center">
	<?php	
		if($cod_estado==1){
	?>
			<input type="checkbox" name="codigo" id="codigo" value="<?=$cod_pago."/".$nro_pago;?>">
	<?php
		}
	?>
	
		</td>

		<td><?=$nro_pago;?></td>
		<td><?=$fecha_pago_mostrar;?></td>
		<td><?=$nombre_proveedor;?></td>
		<td><?=$monto_pago;?></td>
		<td>
	<?php 
		$sqlDetalle=" select ppd.cod_tipo_doc_obligxpagar, ppd.codigo_doc, ppd.monto_pago, ppd.cod_proceso_const  from pagos_proveedor_detalle ppd
           where ppd.cod_pago=".$cod_pago." order by ppd.orden asc ";
         $respDetalle=mysqli_query($enlaceCon,$sqlDetalle);
		while($datDetalle=mysqli_fetch_array($respDetalle)){
			$cod_tipo_doc_obligxpagar=$datDetalle['cod_tipo_doc_obligxpagar'];
			$codigo_doc=$datDetalle['codigo_doc'];
			$monto_pago=$datDetalle['monto_pago'];
			$cod_proceso_const=$datDetalle['cod_proceso_const'];

			/// LOTES
			if($cod_tipo_doc_obligxpagar==2){
					$sqlLotes="select lpc.cod_lote,lpc.cod_proceso_const,pc.nombre_proceso_const, lpc.cod_proveedor,lp.nro_lote, lp.fecha_lote, lpc.cantidad,
					lpc.precio, lpc.obligacionxpagar_fecha, lpc.cod_estado_pago
					from lote_procesoconst lpc
					left join lotes_produccion lp on (lpc.cod_lote=lp.cod_lote)
					left join procesos_construccion pc on (lpc.cod_proceso_const=pc.cod_proceso_const)
					where lpc.cod_lote=".$codigo_doc."
					and lpc.cod_proveedor=".$cod_proveedor."
					and lpc.cod_proceso_const=".$cod_proceso_const;
					$respLotes=mysqli_query($enlaceCon,$sqlLotes);
					while($datLotes=mysqli_fetch_array($respLotes)){
						$nro_lote=$datLotes['nro_lote'];
						$fecha_lote=$datLotes['fecha_lote'];
						$fecha_lote= explode('-',$fecha_lote);
		$fecha_lote_mostrar=$fecha_lote[2]."/".$fecha_lote[1]."/".$fecha_lote[0];
						$nombre_proceso_const=$datLotes['nombre_proceso_const'];
						/*echo "<a href='reporteLote.php?codLote=".$codigo_doc."&codPago=".$cod_pago."' target='_blank'>LOTE ".$nro_lote."</a> ".$fecha_lote_mostrar." (".$nombre_proceso_const.") ".number_format($monto_pago,2,'.',',')." Bs.<br>";*/
					}			

					
			}
			// FIN LOTES
		}
	?>
		</td>
		<td><?=$observaciones;?></td>
		<td><?=$fecha_reg_mostrar;?></td>
		<td><?=$fecha_mod_mostrar;?></td>
		<td><?=$nombre_estadoPagoProv;?></td>
		<td><a target="_BLANK" href="detallePagoProveedor.php?cod_pago=<?=$cod_pago?>"><img src="imagenes/detalles.png" border="0" width="30" heigth="30" title='Ver Detalle del Pago '></a></td>

		</tr>
	<?php
		$indice_tabla++;
	}
	?>
	</table></center><br>
	
	<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Anular' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		
		</div>



</form>