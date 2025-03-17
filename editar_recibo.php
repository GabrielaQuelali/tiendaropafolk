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

function validar(f)
{   return(true);
}
function cancelar(f)
{  location.href="listaRecibos.php";
}
	</script>
	</head>
<?php



if($fecha=="")
{   $fecha=date("d/m/Y");
}
$global_agencia=$_COOKIE['global_agencia'];
$global_almacen=$_COOKIE['global_almacen'];
$idReciboEditar=$_GET["idRecibo"];

	$sql=" select fecha_recibo,nombre_recibo,
desc_recibo,monto_recibo,created_by,modified_by,created_date,modified_date, cel_recibo,recibo_anulado,cod_tipopago, cod_tiporecibo, cod_proveedor, resta_ventas_proveedor,
cod_gruporecibo
from recibos  where id_recibo='".$idReciboEditar."' and cod_ciudad='".$global_agencia."'";	
	 

	$resp= mysqli_query($enlaceCon,$sql);				
	while($dat=mysqli_fetch_array($resp)){	
		$fecha_recibo=$dat['fecha_recibo'];
		$vector_fecha_recibo=explode("-",$fecha_recibo);
		$fecha_recibo_mostrar=$vector_fecha_recibo[2]."/".$vector_fecha_recibo[1]."/".$vector_fecha_recibo[0];
		$nombre_recibo=$dat['nombre_recibo'];
		$desc_recibo=$dat['desc_recibo'];
		$monto_recibo=$dat['monto_recibo'];
		$created_by=$dat['created_by'];
		$modified_by=$dat['modified_by'];
		$created_date=$dat['created_date'];
		$modified_date=$dat['modified_date'];
		$cel_recibo=$dat['cel_recibo'];
		$recibo_anulado=$dat['recibo_anulado'];
		$cod_tipopago=$dat['cod_tipopago'];
		$cod_tiporecibo=$dat['cod_tiporecibo'];
		$cod_proveedor=$dat['cod_proveedor'];
		$resta_ventas_proveedor=$dat['resta_ventas_proveedor'];
		$cod_gruporecibo=$dat['cod_gruporecibo'];
	}

?>
<form id="guarda_editarRecibo" action="guarda_editarRecibo.php" method="post" name="form1" >
<input type="hidden" name="idReciboEditar" id="idReciboEditar" value="<?=$idReciboEditar;?>">
<table border='0' class='textotit' align='center'><tr><th>Edicion de Recibo</th></tr></table><br>
<table border="0" class="texto" cellspacing="0" align="center" width="80%" style="border:#ccc 1px solid;">
<tr><th>Tipo Recibo</th><th>Grupo Recibo</th><th>Nro de Recibo</th><th>Fecha de Recibo</th><th>Forma Pago</th><th>Monto Recibido</th></tr>
<tr>
<td>
	<select name="tipoRecibo" id="tipoRecibo" class="texto"  >
<?php	
	$sqlTipoRecibo="select cod_tiporecibo, nombre_tiporecibo from tipos_recibo where estado=1  order by cod_tiporecibo asc";
	$respTipoRecibo=mysqli_query($enlaceCon,$sqlTipoRecibo);
	while($datTipoRecibo=mysqli_fetch_array($respTipoRecibo))
	{	
?>
<?php	$codTiporecibo=$datTipoRecibo[0];
		$nombreTiporecibo=$datTipoRecibo[1];
	?>
		<option value="<?=$codTiporecibo;?>" <?php if($codTiporecibo==$cod_tiporecibo){echo "selected";}?> ><?=$nombreTiporecibo;?></option>
		
<?php	}?>
	</select>
	</td>
	<td>
	<select name="grupoRecibo" id="grupoRecibo" class="texto"  >
<?php	
	$sqlGrupoRecibo="select cod_gruporecibo, nombre_gruporecibo from grupos_recibo where estado=1  order by cod_gruporecibo asc";
	$respGrupoRecibo=mysqli_query($enlaceCon,$sqlGrupoRecibo);
	while($datGrupoRecibo=mysqli_fetch_array($respGrupoRecibo))
	{	
?>
<?php	$codGrupoRecibo=$datGrupoRecibo[0];
		$nombreGrupoRecibo=$datGrupoRecibo[1];
	?>
		<option value="<?=$codGrupoRecibo;?>" <?php if($codGrupoRecibo==$cod_gruporecibo){echo "selected";}?> ><?=$nombreGrupoRecibo;?></option>
		
<?php	}?>
	</select>
	</td>
<td align="center"><?=$idReciboEditar;?></td>

<td align="left"><input type="text" align="left"  class="texto" value="<?=$fecha_recibo_mostrar;?>" id="fecha" size="10" name="fecha"></td>
<td>
	<select name="tipoPago" id="tipoPago" class="texto"  >
<?php	
	$sqlTipoPago="select cod_tipopago, nombre_tipopago from tipos_pago where estado=1  order by cod_tipopago asc";
	$respTipoPago=mysqli_query($enlaceCon,$sqlTipoPago);
	while($datTipoPago=mysqli_fetch_array($respTipoPago))
	{	
?>
<?php	$codTipopago=$datTipoPago[0];
		$nombreTipopago=$datTipoPago[1];
	?>
		<option value="<?=$codTipopago;?>"  <?php if($codTipopago==$cod_tipopago){echo "selected";}?>><?=$nombreTipopago;?></option>
		
<?php	}?>
	</select>
	</td>
<td align="left"><input type="number" step="0.01"  class="texto" name="monto"  id="monto"  value="<?=$monto_recibo;?>" required>

</tr>

<tr><th >Cliente</th><th >Telefono Cliente</th><th colspan="2" >Detalle</th><th colspan="2">Proveedor</th></tr>
<tr>
<td align="left" ><input type="text" class="texto" name="nombre" size="35"  id="nombre" value="<?=$nombre_recibo;?>" required></td>
<td align="left" ><input type="text" class="texto" name="nro_contacto"  size="15" id="nro_contacto" value="<?=$cel_recibo;?>" required></td>
<td colspan="2" ><input type='text' class='texto' name='desc_recibo' value="<?=$desc_recibo;?>" size='60'></td>
<td colspan="2">
	<select name="proveedor" id="proveedor" class="texto"  >
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
<tr>
<th>Restar de Venta Proveedor</th> 
<th colspan="5">
 SI<input type="radio" name="restarVentaProv" id="restarVentaProv" value="1" <?php if($resta_ventas_proveedor=="1"){ echo "checked";} ?>  >&nbsp;&nbsp;&nbsp;&nbsp;
 NO<input type="radio" name="restarVentaProv" id="restarVentaProv" value="0" <?php if($resta_ventas_proveedor=="0"){ echo "checked";} ?>> 
</th>
</tr>
</table>


<div class="divBotones">
<input type="submit" class="boton" value="Guardar" onClick="return validar(this.form);"></center>
<input type="button" class="boton2" value="Cancelar" onClick="cancelar(this.form);"></center>
</div>




</form>
</body>
</html>