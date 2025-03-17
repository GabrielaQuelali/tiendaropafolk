<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 

require("conexionmysqli.php");
require("funciones.php");
$num=$_GET['codigo'];
$global_almacen=$_COOKIE['global_almacen'];
$global_agencia=$_COOKIE['global_agencia'];
$fechaActual=date("Y-m-d");
?>

<table border="0" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="5%" align="center">
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)" accesskey="B"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>

<td width="70%" align="center">
<input type="hidden" name="material<?php echo $num;?>" id="material<?php echo $num;?>" value="0">
<div id="cod_material<?php echo $num;?>" class='textograndenegro'>-</div>
</td>

<td width="15%"  align="center">

<input type="number" class="inputnumber" min="0.001" max="1000000" id="cantidad_unitaria<?php echo $num;?>" name="cantidad_unitaria<?php echo $num;?>" size="5"  value="1" step="0.001" onchange='cambiaCosto(this.form,<?php echo $num;?>)' onkeyup='cambiaCosto(this.form,<?php echo $num;?>)' required>
</td>

<td align="center"  width="10%" ><input class="boton1" type="button" value="(-)" onclick="menos(<?php echo $num;?>)" size="5"/></td>

</tr>
</table>

</head>
</html>