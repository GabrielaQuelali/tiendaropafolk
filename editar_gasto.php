<?php
require("conexionmysqli.inc");
require("estilos_almacenes.inc");
require("funciones.php");
?>

<html>
    <head>
        <title>Busqueda</title>
  <script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
        <script type="text/javascript" src="functionsGeneral.js"></script>
        <script src="lib/sweetalert2/sweetalert2.all.js"></script>
<script>

function validar(f)
{   return(true);
}
function cancelar(f)
{  location.href="listaGastos.php";
}
	</script>
<?php



if($fecha=="")
{   $fecha=date("d/m/Y");
}
$global_agencia=$_COOKIE['global_agencia'];
$idGastoEditar=$_GET["idGasto"];



	$sql=" select descripcion_gasto,cod_tipogasto,fecha_gasto, monto,created_by,modified_by,created_date,
modified_date,gasto_anulado,cod_proveedor,cod_grupogasto,cod_tipopago,cod_tipo_distribucion_costo
from gastos where cod_gasto='".$idGastoEditar."' and cod_ciudad='".$global_agencia."'";	
	 

	$resp= mysqli_query($enlaceCon,$sql);				
	while($dat=mysqli_fetch_array($resp)){	
		$descripcion_gasto=$dat['descripcion_gasto'];
		$cod_tipogasto=$dat['cod_tipogasto'];
		$fecha_gasto=$dat['fecha_gasto'];
		//echo $fecha_gasto;
		$monto=$dat['monto'];
		$created_by=$dat['created_by'];
		$modified_by=$dat['modified_by'];
		$created_date=$dat['created_date'];
		$modified_date=$dat['modified_date'];
		$gasto_anulado=$dat['gasto_anulado'];
		$cod_proveedor=$dat['cod_proveedor'];
		$cod_grupogasto=$dat['cod_grupogasto'];
		$cod_tipopago=$dat['cod_tipopago'];
		$cod_tipo_distribucion_costo=$dat['cod_tipo_distribucion_costo'];


	}

?>

<form id="guarda_editarGasto" action="guarda_editarGasto.php" method="post" name="form1" >
<input type="hidden" name="idGastoEditar" id="idGastoEditar" value="<?=$idGastoEditar;?>">

<h1>Editar Gasto</h1>

<table border="0" class="texto" cellspacing="0" align="center" width="70%" style="border:#ccc 1px solid;">
<tr><th>Tipo Gasto</th><th>Nro de Gasto</th><th>Fecha de Gasto</th><th>Forma de Pago</th><th>Monto</th></tr>
<tr>
<td>
	<select name="tipoGasto" id="tipoGasto" class="selectpicker" data-style="btn btn-success" >
<?php	
	$sqlTipoGasto="select cod_tipogasto, nombre_tipogasto from tipos_gasto where estado=1  order by cod_tipogasto asc";
	$respTipoGasto=mysqli_query($enlaceCon,$sqlTipoGasto);
	while($datTipoGasto=mysqli_fetch_array($respTipoGasto))
	{	
?>
<?php	$codTipogasto=$datTipoGasto[0];
		$nombreTipogasto=$datTipoGasto[1];
	?>
		<option value="<?=$codTipogasto;?>" <?php if($codTipogasto==$cod_tipogasto){echo "selected";}?> ><?=$nombreTipogasto;?></option>
		
<?php	}?>
	</select>
	</td>

<td align="center"><?=$idGastoEditar;?></td>

<td align="center">
	<input type="date" class="texto" value="<?=$fecha_gasto;?>" id="fecha" size="10" name="fecha">
</td>

<td>
	<select name="tipoPago" id="tipoPago" class="selectpicker" data-style="btn btn-warning" >
<?php	
	$sqlTipoPago="select cod_tipopago, nombre_tipopago from tipos_pago where estado=1  order by cod_tipopago asc";
	$respTipoPago=mysqli_query($enlaceCon,$sqlTipoPago);
	while($datTipoPago=mysqli_fetch_array($respTipoPago))
	{	
?>
<?php	$codTipopago=$datTipoPago[0];
		$nombreTipopago=$datTipoPago[1];
	?>
		<option value="<?=$codTipopago;?>" <?php if($codTipopago==$cod_tipopago){echo "selected";}?>><?=$nombreTipopago;?></option>
		
<?php	}?>
	</select>
	</td>
	

<td align="left"><input type="number" step="0.01"  name="monto" value="<?=$monto;?>"  id="monto" required></td>
</tr>
<tr><th>Grupo Gasto</th><th colspan="3">Descripcion de Gasto </th> <th >Proveedor</th></tr>
<tr>
<td>
	<select name="grupoGasto" id="grupoGasto" class="selectpicker" data-style="btn btn-success">
<?php	
	$sqlGrupoGasto="select cod_grupogasto, nombre_grupogasto from grupos_gasto where estado=1  order by cod_grupogasto asc";
	$respGrupoGasto=mysqli_query($enlaceCon,$sqlGrupoGasto);
	while($datGrupoGasto=mysqli_fetch_array($respGrupoGasto))
	{	
?>
<?php	$codGrupoGasto=$datGrupoGasto[0];
		$nombreGrupoGasto=$datGrupoGasto[1];
	?>
		<option value="<?=$codGrupoGasto;?>" <?php if($codGrupoGasto==$cod_grupogasto){echo "selected";}?> ><?=$nombreGrupoGasto;?></option>
		
<?php	}?>
	</select>
	</td>

<td colspan="3">
	<textarea class='texto' name='descripcion_gasto' cols="50" size='100' required><?=$descripcion_gasto;?></textarea>
</td>

<td>
	<select name="proveedor" id="proveedor" class="selectpicker" data-style="btn btn-warning">
	<option value="" >NINGUNO</option>
<?php	
	$sql3="select cod_proveedor, nombre_proveedor from proveedores where estado=1  order by nombre_proveedor asc";
	$resp3=mysqli_query($enlaceCon,$sql3);
	while($dat3=mysqli_fetch_array($resp3))
	{	
?>
<?php	$codProveedor=$dat3[0];
		$nombreProveedor=$dat3[1];
	?>
		<option value="<?=$codProveedor;?>" <?php if($codProveedor==$cod_proveedor){echo "selected";}?>  ><?=$nombreProveedor;?></option>
		
<?php	}?>
	</select>
	</td>
</tr>
<tr><th>Tipo Distribucion Costo</th><th colspan="4">&nbsp;</th></tr>
<tr>
	<td>
	<select name="tipoDistribucion" id="tipoDistribucion" class="selectpicker" data-style="btn btn-info" >
<?php	
	$sql5="select cod_tipo_distribucion_costo, desc_tipo_distribucion_costo from tipos_distribucion_costo   order by cod_tipo_distribucion_costo asc";
	$resp5=mysqli_query($enlaceCon,$sql5);
	while($dat5=mysqli_fetch_array($resp5))
	{	
?>
<?php	$codTipoDistribucionCosto=$dat5['cod_tipo_distribucion_costo'];
		$descTipoDistribucionCosto=$dat5['desc_tipo_distribucion_costo'];
	?>
		<option value="<?=$codTipoDistribucionCosto;?>" <?php if($codTipoDistribucionCosto==$cod_tipo_distribucion_costo){echo "selected";}?> ><?=$descTipoDistribucionCosto;?></option>
		
<?php	}?>
	</select>
	</td>
	<td colspan="4">&nbsp;</td>
</tr>
</table>


<div class="divBotones">
<input type="submit" class="boton" value="Guardar" onClick="return validar(this.form);"></center>
<input type="button" class="boton2" value="Cancelar" onClick="cancelar(this.form);"></center>
</div>
</div>



</form>
</body>