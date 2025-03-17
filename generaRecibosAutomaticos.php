<?php
require("conexionmysqli.inc");
require("estilos.inc");
require("funciones.php");
?>

<html>
    <head>
        <title>Busqueda</title>
        <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="dlcalendar.js"></script>
        <script type="text/javascript" src="functionsGeneral.js"></script>
        <script src="lib/sweetalert2/sweetalert2.all.js"></script>
<script>

function validar(f){    

 var i;
                        var j=0;
                        datos=new Array();
                        for(i=0;i<=f.length-1;i++)
                        {
                                if(f.elements[i].type=='checkbox')
                                {       if(f.elements[i].checked==true)
                                        {       datos[j]=f.elements[i].value;
                                                j=j+1;
                                        }
                                }
                        }
						
                        if(j==0)
                        {       alert('Debe seleccionar al menos algun registro.');
                        }
                        else
                        {	
                                if(confirm('Se generara los Recibos Automaticos de los registros seleccionados,desea continuar?.'))
                                { 	f.datosProvRec.value=datos;                                        
                                }
                                else
                                {
                                        return(false);
                                }
								f.submit();
                        }

}
function cancelar(f)
{  location.href="listaRecibos.php";
}
	</script>
<?php

$fecha_ini=$_GET['fecha_ini'];
$fecha_fin=$_GET['fecha_fin'];

//desde esta parte viene el reporte en si
$fecha_iniconsulta=($fecha_ini);
$fecha_finconsulta=($fecha_fin);

$rptGrupoRecibo=$_GET['rpt_gruporecibo'];
$fechaActual=date("d/m/Y");

$cadenaGruposRecibo="TODOS";	
if($rptGrupoRecibo=="-1"){
	 $rptGrupoRecibo=""; $swGrupoRecibo=0;	 
	 $sqlGrupoRecibo="select cod_gruporecibo from grupos_recibo where estado= 1";

	$respGrupoRecibo=mysqli_query($enlaceCon,$sqlGrupoRecibo);
	while($datGrupoRecibo=mysqli_fetch_array($respGrupoRecibo))
	{
		if($swGrupoRecibo==0){
			$rptGrupoRecibo=$datGrupoRecibo[0];
			$swGrupoRecibo=1;
		}else{
			$rptGrupoRecibo=$rptGrupoRecibo.",";
			$rptGrupoRecibo=$rptGrupoRecibo.$datGrupoRecibo[0];
		}
	}
	
}else{
	$swCadenaGrupoRecibo=0;	
	$sqlGrupoRecibo="select cod_gruporecibo,nombre_gruporecibo from grupos_recibo where estado= 1";
	$sqlCadenaGrupoRecibo="select cod_gruporecibo,nombre_gruporecibo from grupos_recibo where estado=1 and cod_gruporecibo in(".$rptGrupoRecibo.")	order by cod_gruporecibo asc";
	$respCadenaGrupoRecibo=mysqli_query($enlaceCon,$sqlCadenaGrupoRecibo);
	while($datCadenaGrupoRecibo=mysqli_fetch_array($respCadenaGrupoRecibo)){	
		if($swCadenaGrupoRecibo==0){
			$cadenaGruposRecibo=$datCadenaGrupoRecibo[1];
			$swCadenaGrupoRecibo=1;
		}else{
			$cadenaGruposRecibo=$cadenaGruposRecibo." <strong>;</strong> ";
			$cadenaGruposRecibo=$cadenaGruposRecibo.$datCadenaGrupoRecibo[1];
		}
		
	}
	
}

?>
<form id='guarda_recibo_automaticos' action='guarda_recibo_automaticos.php' method='post' name='form1' >
<input type="hidden" id="datosProvRec" name="datosProvRec">

<table align="center"  >
<tr class="textotit" align='center' ><th  colspan="2"  >Registro de Recibo Automaticos</th></tr>	
	<tr><th>De: </th> <td><?php  echo $fecha_ini;?> <strong>a</strong> <?php  echo $fecha_fin;?></td></tr>
	<tr><th>Grupo de Recibo: </th><td><?php  echo $cadenaGruposRecibo;?></td></tr>	
	<tr><th>Fecha Registro:</th> <td><?php  echo $fechaActual;?></td></tr>	
	</table>

<br>
<table align='center'  width='85%' border="1">
<tr>
<th>Marca</th>
<th>Fecha</th>
<th>Cliente</th>
<th>Razon Social</th>
<th>Documento</th>
<th>Tipo Pago</th>
<th>Monto Factura</th>
<th>Descuento Factura</th>
<th>Cajero</th>
<th >Cod Producto</th>
<th>Producto</th>
<th >Color/Talla</th>
<th>Cantidad</th>
<th>Precio Producto</th>
<th>Descuento</th>
<th>Precio Venta Producto</th>

</th>
</tr>
<?php
$sqlVentas="select mar.nombre, concat(s.fecha,' ',s.hora_salida)as fecha,c.nombre_cliente,
s.razon_social, s.observaciones,t.abreviatura,
s.nro_correlativo, s.cod_tipopago, tp.nombre_tipopago, s.descuento,s.monto_final, s.cod_salida_almacenes,s.cod_chofer,
m.codigo_barras, m.descripcion_material, 
sd.monto_unitario,sd.descuento_unitario, 
sd.cantidad_unitaria, m.color, m.talla, m.cod_marca,m.codigo2,
p.cod_proveedor,p.nombre_proveedor
from salida_detalle_almacenes sd
inner join salida_almacenes s on (sd.cod_salida_almacen=s.cod_salida_almacenes) 
inner join material_apoyo m on (sd.cod_material=m.codigo_material)
inner join marcas  mar on (m.cod_marca=mar.codigo)
inner join proveedores_marcas  pm on (pm.codigo=mar.codigo)
inner join proveedores p on (p.cod_proveedor=pm.cod_proveedor)
left join clientes c on(s.cod_cliente=c.cod_cliente)
inner join tipos_docs t on (t.codigo=s.cod_tipo_doc)
inner join tipos_pago tp on (tp.cod_tipopago=s.cod_tipopago)
where  s.salida_anulada=0 
and s.fecha BETWEEN '".$fecha_iniconsulta."' and '".$fecha_finconsulta."' 
order by mar.nombre asc , s.fecha asc ";



$totalVentaProd=0;
$totalVentaDesc=0;
$totalVentaCobrado=0;
$swTotalMarca=0;
$codMarcaAnteriorPivote=0;
$codMarcaPivote=0;
$descMarcaAnteriorPivote="";
$descMarcaPivote="";
////////////////
$codProveedorAnteriorPivote=0;
$codProveedorPivote=0;
$descProveedorAnteriorPivote="";
$descProveedorMarcaPivote="";
//////////////
$totalMarcaProd=0;
$totalMarcaDescProd=0;
$totalMarcaVentaProd=0;
$formaPagoArrayMarca=array();
$respVentas=mysqli_query($enlaceCon,$sqlVentas);
while($datos=mysqli_fetch_array($respVentas)){	

	
	$nombreMarca=$datos['nombre'];
	$fechaVenta=$datos['fecha'];
	$nombreCliente=$datos['nombre_cliente'];
	$razonSocial=$datos['razon_social'];
	$obsVenta=$datos['observaciones'];
	$datosDoc= $datos['abreviatura']."-".$datos['nro_correlativo'];
	$codTipopago= $datos['cod_tipopago'];
	$nombreTipopago= $datos['nombre_tipopago'];
	$descuentoVenta= $datos['descuento'];
	$montoVenta= $datos['monto_final'];
	$codSalida=$datos['cod_salida_almacenes'];
	$cod_funcionario=$datos['cod_chofer'];
	$codigoBarras=$datos['codigo_barras'];
	$descripcionMaterial=$datos['descripcion_material'];
	$montoUnitario=$datos['monto_unitario'];
	$descuentoUnitario=$datos['descuento_unitario'];
	$cantidadUnitaria=$datos['cantidad_unitaria'];
	$colorProducto=$datos['color'];
	$tallaProducto=$datos['talla'];
	$codMarca=$datos['cod_marca'];
	$codigo2=$datos['codigo2'];
	$codProveedor=$datos['cod_proveedor'];
	$nombreProveedor=$datos['nombre_proveedor'];
	$montoUnitarioProdVenta=0;
	$montoUnitarioProdVentaFormato=0;
	$montoUnitarioDesc=0;
	$montoUnitarioDescFormato=0;

	
	// Porcentaje de descuento
	$porcentajeDescVenta=0;
	if($descuentoVenta>0){
		$porcentajeDescVenta=($descuentoVenta*100)/($montoVenta+$descuentoVenta);
		$montoUnitarioDesc=($porcentajeDescVenta*$montoUnitario)/100;		
		$montoUnitarioProdVenta=$montoUnitario-$montoUnitarioDesc;			
	}else{
		$montoUnitarioProdVenta=$montoUnitario;
	}
	
	$sqlResponsable="select CONCAT(SUBSTRING_INDEX(nombres,' ', 1),' ',SUBSTR(paterno, 1,1),'.') from funcionarios where codigo_funcionario='".$cod_funcionario."'";
	$respResponsable=mysqli_query($enlaceCon,$sqlResponsable);
	$datResponsable=mysqli_fetch_array($respResponsable);
	$nombreResponsable=$datResponsable[0];
		
	$totalVentaProd=$totalVentaProd+$montoUnitario;
	$totalVentaDesc=$totalVentaDesc+$montoUnitarioDesc;
	$totalVentaCobrado=$totalVentaCobrado+$montoUnitarioProdVenta;
	
	if($swTotalMarca==0){
		$swTotalMarca=1;
		$codMarcaAnteriorPivote=$datos['cod_marca'];
		$codMarcaPivote=$datos['cod_marca'];
		$descMarcaAnteriorPivote=$datos['nombre'];
		$descMarcaPivote=$datos['nombre'];
		///////////////
		$codProveedorAnteriorPivote=$datos['cod_proveedor'];
		$codProveedorPivote=$datos['cod_proveedor'];
		$descProveedorAnteriorPivote=$datos['nombre_proveedor'];
		$descProveedorPivote=$datos['nombre_proveedor'];		
		///////////////////

		$totalMarcaProd=$totalMarcaProd+$montoUnitario;
		$totalMarcaDescProd=$totalMarcaDescProd+$montoUnitarioDesc;
		$totalMarcaVentaProd=$totalMarcaVentaProd+$montoUnitarioProdVenta;
		
		$formaPagoArrayMarca[$codTipopago]=$montoUnitarioProdVenta;
		

		
	}else{
		$codMarcaAnteriorPivote=$codMarcaPivote;
		$codMarcaPivote=$datos['cod_marca'];
		$descMarcaAnteriorPivote=$descMarcaPivote;
		$descMarcaPivote=$datos['nombre'];
		
		///////////////
		$codProveedorAnteriorPivote=$codProveedorPivote;
		$codProveedorPivote=$datos['cod_proveedor'];
		$descProveedorAnteriorPivote=$descProveedorPivote;
		$descProveedorPivote=$datos['nombre_proveedor'];		
		///////////////////
		
		if($codMarcaAnteriorPivote==$codMarcaPivote){
			
			$totalMarcaProd=$totalMarcaProd+$montoUnitario;
			$totalMarcaDescProd=$totalMarcaDescProd+$montoUnitarioDesc;
			$totalMarcaVentaProd=$totalMarcaVentaProd+$montoUnitarioProdVenta;
			
			$formaPagoArrayMarca[$codTipopago]=$formaPagoArrayMarca[$codTipopago]+$montoUnitarioProdVenta;
			
			
			
		}else{
			
			
			$sqlFormaPago="select cod_tipopago,nombre_tipopago,estado,contabiliza FROM tipos_pago where estado=1 order by cod_tipopago asc";
			$respFormaPago=mysqli_query($enlaceCon,$sqlFormaPago);
			while($datoFormaPago=mysqli_fetch_array($respFormaPago)){
				if(!empty($formaPagoArrayMarca[$datoFormaPago['cod_tipopago']])){
?>
			<tr>
			<td colspan="12"></td>
			<td><strong><?=$datoFormaPago['nombre_tipopago'];?></strong></td>
			<td colspan="2">&nbsp;</td>
			<td align="right"><?=$formaPagoArrayMarca[$datoFormaPago['cod_tipopago']];?></td>
			</tr>
<?php		
				}
			}				
?>	
		
		<tr>
		<td align="right" colspan="13"><strong>TOTAL  VENTA <?php echo $descMarcaAnteriorPivote;?></strong></td>
		<td align="right"><strong><?php echo $totalMarcaProd;?></strong></td>		
		<td align="right"><strong><?php echo $totalMarcaDescProd;?></strong></td>		
		<td align="right"><strong><?php echo $totalMarcaVentaProd;?></strong></td>			
		</tr>
		<tr><td colspan="16" align="center"><strong> RECIBOS</strong></td></tr>
		<tr>
		<td>&nbsp;</td>
		<td><strong>Tipo de Recibo</strong></td>
		<td><strong>Proveedor</strong></td>
		<td colspan="10"><strong>Grupo de Recibo</strong></td>
		<td colspan="2"><strong>Restar de Venta Proveedor</strong></td>
		<td align="center"><strong>Monto</strong></td>
		
		</tr>
		<?php

				$sqlGrupoRecibo=" select cod_gruporecibo,nombre_gruporecibo,porcentaje,cod_tipopago from grupos_recibo where estado=1 and automatico=1 and cod_gruporecibo in(".$rptGrupoRecibo.") ";
				$respGrupoRecibo=mysqli_query($enlaceCon,$sqlGrupoRecibo);
				while($datoGrupoRecibo=mysqli_fetch_array($respGrupoRecibo)){
					$codGrupoRecibo=$datoGrupoRecibo['cod_gruporecibo'];
					$nombreGruporecibo=$datoGrupoRecibo['nombre_gruporecibo'];
					
					$sqlTipoRecibo="select cod_tiporecibo, nombre_tiporecibo from tipos_recibo where estado=1 and cod_tiporecibo=1  order by cod_tiporecibo asc";
					$respTipoRecibo=mysqli_query($enlaceCon,$sqlTipoRecibo);
					$nombreTipoRecibo="";
					while($datTipoRecibo=mysqli_fetch_array($respTipoRecibo)){
						$nombreTipoRecibo=$datTipoRecibo['nombre_tiporecibo'];
					}
					$montoCalculo=0;
					if(empty($datoGrupoRecibo['cod_tipopago'])){
						$montoCalculo=$totalMarcaVentaProd;
					}else{
						if(!empty($formaPagoArrayMarca[$datoGrupoRecibo['cod_tipopago']])){
							$montoCalculo=	$formaPagoArrayMarca[$datoGrupoRecibo['cod_tipopago']];
						}
					}
					
					$montoRecibo=0;
					if($montoCalculo>0){
						$montoRecibo=($montoCalculo*$datoGrupoRecibo['porcentaje'])/100;
					
		?>
						<tr>
					<td><input type="checkbox" name="id_recibo" id="id_recibo<?=$codProveedorAnteriorPivote."/".$codGrupoRecibo;?>" value="<?=$codProveedorAnteriorPivote."/".$codGrupoRecibo;?>" checked></td>
					<td><?=$nombreTipoRecibo;?></td>
					<td><?=$descProveedorAnteriorPivote;?></td>
					<td colspan="10"><strong><?=$nombreGruporecibo;?></strong></td>
					<td  colspan="2" align="center"><strong>SI</strong></td>				
					<td align="right">
					<strong><input type="hidden" name="monto<?=$codProveedorAnteriorPivote.'/'.$codGrupoRecibo;?>" id="monto<?=$codProveedorAnteriorPivote."/".$codGrupoRecibo;?>" value="<?=$montoRecibo;?>"><?=$montoRecibo;?></strong>
					</td>				
					</tr>
		<?php
					}
				
				}					
			
			$totalMarcaProd=0;
			$totalMarcaDescProd=0;
			$totalMarcaVentaProd=0;
			$formaPagoArrayMarca=array();
			$totalMarcaProd=$totalMarcaProd+$montoUnitario;
			$totalMarcaDescProd=$totalMarcaDescProd+$montoUnitarioDesc;
			$totalMarcaVentaProd=$totalMarcaVentaProd+$montoUnitarioProdVenta;
			$formaPagoArrayMarca[$codTipopago]=$formaPagoArrayMarca[$codTipopago]+$montoUnitarioProdVenta;
			
			
		}
		
	}


	$montoVentaFormat=number_format($montoVenta,2,".",",");
	$montoUnitarioFormato=number_format($montoUnitario,2,".",",");
	$cantidadFormat=number_format($cantidadUnitaria,0,".",",");
	$montoUnitarioDescFormato=number_format($montoUnitarioDesc,2,".",",");
	$montoUnitarioProdVentaFormato=number_format($montoUnitarioProdVenta,2,".",",");

?>	

		<tr>
		<td><?php echo $nombreMarca;?></td>
		<td><?php echo $fechaVenta;?></td>
		<td><?php echo $nombreCliente;?></td>
		<td><?php echo $razonSocial;?></td>
		<td><?php echo $datosDoc;?></td>
		<td><?php echo $nombreTipopago;?></td>
		<td align="right"><?php echo $montoVentaFormat;?></td>
		<td align="right"><?php echo number_format($descuentoVenta,2,".",",");?></td>	
		<td><?php echo $nombreResponsable;?></td>
		<td><?php echo $codigoBarras." ".$codigo2;?></td>
		<td><?php echo $descripcionMaterial;?></td>
		<td><?php echo $colorProducto."/".$tallaProducto;?></td>
		<td><?php echo $cantidadFormat;?></td>
		<td align="right"><?php echo $montoUnitarioFormato;?></td>		
		<td align="right"><?php echo $montoUnitarioDescFormato;?></td>		
		<td align="right"><?php echo $montoUnitarioProdVentaFormato;?></td>		
		</tr>
		
	

<?php 

}
 
 $sqlFormaPago="select cod_tipopago,nombre_tipopago,estado,contabiliza FROM tipos_pago where estado=1 order by cod_tipopago asc";
			$respFormaPago=mysqli_query($enlaceCon,$sqlFormaPago);
			while($datoFormaPago=mysqli_fetch_array($respFormaPago)){
		if(!empty($formaPagoArrayMarca[$datoFormaPago['cod_tipopago']])){
?>
			<tr>
			<td colspan="12"></td>
			<td><strong><?=$datoFormaPago['nombre_tipopago'];?></strong></td>
			<td colspan="2">&nbsp;</td>
			<td align="right"><?=$formaPagoArrayMarca[$datoFormaPago['cod_tipopago']];?></td>
			</tr>
<?php		
			}
			}
				
			

$totalVentaProdFormato=number_format($totalVentaProd,2,".",",");
$totalVentaDescFormato=number_format($totalVentaDesc,2,".",",");
$totalVentaCobradoFormato=number_format($totalVentaCobrado,2,".",",");
 ?>
 <tr>
		<td align="right" colspan="13"><strong>TOTAL VENTA <?php echo $descMarcaPivote;?></strong></td>
		<td align="right"><strong><?php echo $totalMarcaProd;?></strong></td>		
		<td align="right"><strong><?php echo $totalMarcaDescProd;?></strong></td>		
		<td align="right"><strong><?php echo $totalMarcaVentaProd;?></strong></td>		
		</tr>
		<tr><td colspan="16" align="center"><strong> RECIBOS</strong></td></tr>
		<tr>
		<td>&nbsp;</td>
		<td><strong>Tipo de Recibo</strong></td>
		<td><strong>Proveedor</strong></td>
		<td colspan="10"><strong>Grupo de Recibo</strong></td>
		<td colspan="2"><strong>Restar de Venta Proveedor</strong></td>
		<td align="center"><strong>Monto</strong></td>
		
		</tr>
		<?php

				$sqlGrupoRecibo=" select cod_gruporecibo,nombre_gruporecibo,porcentaje,cod_tipopago from grupos_recibo where estado=1 and automatico=1 and cod_gruporecibo in(".$rptGrupoRecibo.") ";
				$respGrupoRecibo=mysqli_query($enlaceCon,$sqlGrupoRecibo);
				while($datoGrupoRecibo=mysqli_fetch_array($respGrupoRecibo)){
					$codGrupoRecibo=$datoGrupoRecibo['cod_gruporecibo'];
					$nombreGruporecibo=$datoGrupoRecibo['nombre_gruporecibo'];
					
					$sqlTipoRecibo="select cod_tiporecibo, nombre_tiporecibo from tipos_recibo where estado=1 and cod_tiporecibo=1  order by cod_tiporecibo asc";
					$respTipoRecibo=mysqli_query($enlaceCon,$sqlTipoRecibo);
					$nombreTipoRecibo="";
					while($datTipoRecibo=mysqli_fetch_array($respTipoRecibo)){
						$nombreTipoRecibo=$datTipoRecibo['nombre_tiporecibo'];
					}
					$montoCalculo=0;
					if(empty($datoGrupoRecibo['cod_tipopago'])){
						$montoCalculo=$totalMarcaVentaProd;
					}else{
						if(!empty($formaPagoArrayMarca[$datoGrupoRecibo['cod_tipopago']])){
							$montoCalculo=	$formaPagoArrayMarca[$datoGrupoRecibo['cod_tipopago']];
						}
					}
					
					$montoRecibo=0;
					if($montoCalculo>0){
						$montoRecibo=($montoCalculo*$datoGrupoRecibo['porcentaje'])/100;
					
		?>
						<tr>
					<td><input type="checkbox" name="id_recibo" id="id_recibo<?=$codProveedorAnteriorPivote."/".$codGrupoRecibo;?>" value="<?=$codProveedorAnteriorPivote."/".$codGrupoRecibo;?>" checked></td>
					<td><?=$nombreTipoRecibo;?></td>
					<td><?=$descProveedorAnteriorPivote;?></td>
					<td colspan="10"><strong><?=$nombreGruporecibo;?></strong></td>
					<td  colspan="2" align="center"><strong>SI</strong></td>				
					<td align="right">
					<strong><input type="hidden" name="monto<?=$codProveedorAnteriorPivote.'/'.$codGrupoRecibo;?>" id="monto<?=$codProveedorAnteriorPivote."/".$codGrupoRecibo;?>" value="<?=$montoRecibo;?>"><?=$montoRecibo;?></strong>
					</td>				
					</tr>
		<?php
					}
				
				}		
?>



</table>


<div class="divBotones">
<input type="submit" class="boton" value="Guardar" onClick="return validar(this.form);"></center>
<input type="button" class="boton2" value="Cancelar" onClick="cancelar(this.form);"></center>
</div>
</div>



</form>
</body>