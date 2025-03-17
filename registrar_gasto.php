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
{   $fecha=date("Y-m-d");
}
$global_agencia=$_COOKIE['global_agencia'];
?>

<form id='guarda_gasto' action='guarda_gasto.php' method='post' name='form1' >

<h1>Registro de Gasto</h1>

<table border="0" class="texto" cellspacing="0" align="center" width="70%" style="border:#ccc 1px solid;">
<tr><th>Tipo Gasto</th><th>Nro de Gasto</th><th>Fecha de Gasto</th><th>Forma de Pago</th><th>Monto</th></tr>
<tr>
<td>
	<select name="tipoGasto" id="tipoGasto" class="selectpicker" data-style='btn-info'>
<?php	
	$sqlTipoGasto="select cod_tipogasto, nombre_tipogasto from tipos_gasto where estado=1  order by cod_tipogasto asc";
	$respTipoGasto=mysqli_query($enlaceCon,$sqlTipoGasto);
	while($datTipoGasto=mysqli_fetch_array($respTipoGasto))
	{	
?>
<?php	$codTipogasto=$datTipoGasto[0];
		$nombreTipogasto=$datTipoGasto[1];
	?>
		<option value="<?=$codTipogasto;?>" ><?=$nombreTipogasto;?></option>
		
<?php	}?>
	</select>
	</td>
<?php
$sql="select cod_gasto from gastos where cod_ciudad=".$global_agencia." order by cod_gasto desc";
$resp=mysqli_query($enlaceCon,$sql);
$dat=mysqli_fetch_array($resp);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{   $nro_correlativo=1;
}
else
{   $nro_correlativo=$dat[0];
    $nro_correlativo++;
}
?>
<td align="center"><?=$nro_correlativo;?></td>
<td align="center">
	<input type="date" align="left"  class="texto" value="<?=$fecha;?>" id="fecha" size="10" name="fecha">
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
		<option value="<?=$codTipopago;?>" ><?=$nombreTipopago;?></option>
		
<?php	}?>
	</select>
	</td>
	

<td align="left"><input type="number" step="0.01"  name="monto"  id="monto" required></td>
</tr>
<tr><th>Grupo Gasto</th><th colspan="3">Descripcion de Gasto </th> <th >Proveedor</th></tr>
<tr>
<td>
	<select name="grupoGasto" id="grupoGasto" class="selectpicker" data-style="btn btn-info" >
<?php	
	$sqlGrupoGasto="select cod_grupogasto, nombre_grupogasto from grupos_gasto where estado=1  order by cod_grupogasto asc";
	$respGrupoGasto=mysqli_query($enlaceCon,$sqlGrupoGasto);
	while($datGrupoGasto=mysqli_fetch_array($respGrupoGasto))
	{	
?>
<?php	$codGrupoGasto=$datGrupoGasto[0];
		$nombreGrupoGasto=$datGrupoGasto[1];
	?>
		<option value="<?=$codGrupoGasto;?>" ><?=$nombreGrupoGasto;?></option>
		
<?php	}?>
	</select>
	</td>

<td colspan="3" >
	<textarea class='texto' name="descripcion_gasto" id="descripcion_gasto" cols="50" rows="3" required></textarea>
</td>

<td>
	<select name="proveedor" id="proveedor" class="selectpicker" data-style="btn btn-warning" >
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
		<option value="<?=$codProveedor;?>" ><?=$nombreProveedor;?></option>
		
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
		<option value="<?=$codTipoDistribucionCosto;?>" ><?=$descTipoDistribucionCosto;?></option>
		
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