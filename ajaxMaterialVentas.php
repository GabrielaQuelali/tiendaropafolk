<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 

require("conexionmysqli2.inc");
require_once 'funciones.php';


$num=$_GET['codigo'];
$globalAdmin=$_COOKIE["global_admin_cargo"];


 error_reporting(E_ALL);
 ini_set('display_errors', '1');


/*Bandera de descuento abierto en Venta*/
$banderaDescuentoAbierto=obtenerValorConfiguracion($enlaceCon,15);
?>


<table border="0" align="center" width="100%"  class="texto" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="5%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>

<td width="30%" align="center">
	<input type="hidden" name="materiales<?php echo $num;?>" id="materiales<?php echo $num;?>" value="0">
	<div id="cod_material<?php echo $num;?>" class='textomedianonegro'>-</div>
</td>

<td width="10%" align="center">
	<div id='idstock<?php echo $num;?>'>
		<input type='number' id='stock<?php echo $num;?>' name='stock<?php echo $num;?>' value='' style="height:20px;font-size:19px;width:60px;color:blue;">
	</div>
</td>

<td align="center" width="10%">
	<input class="inputnumber" type="number" min="1" id="cantidad_unitaria<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' name="cantidad_unitaria<?php echo $num;?>" onChange='calculaMontoMaterial(<?php echo $num;?>);' step="1" value="1" required> 
</td>


<td align="center" width="10%">
	<div id='idprecio<?php echo $num;?>'>
		<input class="inputnumber" type="number" min="1" value="0" id="precio_unitario<?php echo $num;?>" name="precio_unitario<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' onChange='calculaMontoMaterial(<?php echo $num;?>);' step="0.01" required>
	</div>
</td>

<td align="center" width="15%">
	<input class="inputnumber" type="number" max="90" step="0.01" value="0" id="tipoPrecio<?php echo $num;?>" name="tipoPrecio<?php echo $num;?>" style="background:#ADF8FA;" onkeyup='calculaMontoMaterial(<?php echo $num;?>);' onchange='calculaMontoMaterial(<?php echo $num;?>);' <?=($banderaDescuentoAbierto==0)?'readonly':'';?> >%

	<input class="inputnumber" type="number" value="0" id="descuentoProducto<?php echo $num;?>" name="descuentoProducto<?php echo $num;?>" step="0.01" style='background:#ADF8FA;' onkeyup='calculaMontoMaterial_bs(<?php echo $num;?>);' onchange='calculaMontoMaterial_bs(<?php echo $num;?>);' <?=($banderaDescuentoAbierto==0)?'readonly':'';?>>
	<div id="divMensajeOferta<?=$num;?>" class="textomedianosangre"></div>
</td>

<td align="center" width="10%">
	<input class="inputnumber" type="number" value="0" id="montoMaterial<?php echo $num;?>" name="montoMaterial<?php echo $num;?>" value="0"  step="0.01" style="height:20px;font-size:19px;width:120px;color:red;" required readonly> 
</td>

<input type="hidden" name="precio_normal<?php echo $num;?>" id="precio_normal<?php echo $num;?>" value="">
<input type="hidden" name="precio_mayor<?php echo $num;?>" id="precio_mayor<?php echo $num;?>" value="">

<td align="center"  width="10%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" /></td>

</tr>
</table>

</head>
</html>