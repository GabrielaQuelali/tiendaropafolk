<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php 

require_once 'conexionmysqli2.inc';
require_once 'funciones.php';

	$num=$_GET['codigo'];
	$tipo=$_GET['tipo'];
	/// en las salidas de Insumos no se edita precios
	if($tipo==2){
	$banderaEditPrecios=0;
	}else{
	$banderaEditPrecios=obtenerValorConfiguracion($enlaceCon, 20);
	}
?>

<table border="0" align="center" width="100%"  class="texto" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="10%" align="center">
	<?php echo $num;?>
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>

<td width="40%" align="center">
	<input type="hidden" name="materiales<?php echo $num;?>" id="materiales<?php echo $num;?>" value="0">
	<div id="cod_material<?php echo $num;?>" class='textomedianonegro'>-</div>
</td>

<td width="10%" align="center">
	<div id='idstock<?php echo $num;?>'>
		<input type='hidden' id='stock<?php echo $num;?>' name='stock<?php echo $num;?>' value=''>
	</div>
</td>

<td align="center" width="10%">
	<input class="inputnumber" type="number" value="" min="0.01" id="cantidad_unitaria<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' name="cantidad_unitaria<?php echo $num;?>" onChange='calculaMontoMaterial(<?php echo $num;?>);' step="0.01" required> 
</td>

<td align="center" width="10%">
	<input class="inputnumber" type="number" value="" min="0.01" id="precio_normal<?php echo $num;?>" name="precio_normal<?php echo $num;?>" step="0.01" <?=($banderaEditPrecios==0)?"disabled":"";?> required> 
</td>

<td align="center" width="10%">
	<input class="inputnumber" type="number" value="" min="0.01" id="precio_mayor<?=$num;?>" name="precio_mayor<?=$num;?>" step="0.01" <?=($banderaEditPrecios==0)?"disabled":"";?> required> 
</td>


<td align="center"  width="10%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?php echo $num;?>)" /></td>

</tr>
</table>

</head>
</html>