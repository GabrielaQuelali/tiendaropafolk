<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="STYLESHEET" type="text/css" href="stilos.css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<table align="center" class="texto" width="80%" border="0" id="data0" style="border:#ccc 1px solid;">
	<tr>
		<td align="center" colspan="9">
			<b>Detalle de la Transaccion   </b><input class="boton" type="button" value="Agregar (+)" onclick="mas(this)" />
		</td>
	</tr>
	<tr align="center">
		<th width="10%">-</th>
		<th width="40%">Producto</th>
		<th width="10%">Stock</th>
		<th width="10%">Cantidad</th>
		<th width="10%">Precio</th>
		<th width="10%">Precio por Mayor</th>
		<th width="10%">&nbsp;</th>
	</tr>
	</table>
<?php 

require_once 'conexionmysqli2.inc';
require_once 'funciones.php';

$global_almacen=$_COOKIE['global_almacen'];
$globalAgencia=$_COOKIE["global_agencia"];
	$banderaEditPrecios=0;
	//$banderaEditPrecios=obtenerValorConfiguracion($enlaceCon, 20);

	$num=$_GET['codigo'];
	$cod_lote=$_GET['lote'];

	$sqlLote="select lp.codigo_material, ma.descripcion_material,lp.cant_lote, ip.cod_insumo, 
ins.descripcion_material as descInsumo,ip.cod_unidad_medida,ip.cant,
um.nombre as nomUnidadMedida,um.abreviatura as abrevUnidadMedida
from lotes_produccion lp
left join material_apoyo ma on (lp.codigo_material=ma.codigo_material) 
left join insumos_productos ip on (lp.codigo_material=ip.cod_producto)
left join material_apoyo ins on (ip.cod_insumo=ins.codigo_material) 
left join unidades_medida um on (ip.cod_unidad_medida=um.codigo) 
	where lp.cod_lote='".$cod_lote."'";
	//echo $sqlLote;

	$respLote=mysqli_query($enlaceCon,$sqlLote);
	while($datLote=mysqli_fetch_array($respLote)){
		$cant_lote=$datLote['cant_lote'];
		$cod_insumo=$datLote['cod_insumo'];
		$descInsumo=$datLote['descInsumo'];
		$cod_unidad_medida=$datLote['cod_unidad_medida'];
		$cant=$datLote['cant'];
		$nomUnidadMedida=$datLote['nomUnidadMedida'];
		$abrevUnidadMedida=$datLote['abrevUnidadMedida'];
		$stockInsumo=stockProducto($enlaceCon,$global_almacen, $cod_insumo);

		$precioProducto=0;
		$precioProductoMayor=0;
		/************  sacamos el precio por normal   **************/
			$consulta="select p.`precio` from precios p where p.`codigo_material`='$cod_insumo' and p.`cod_precio`='1' and cod_ciudad='$globalAgencia'";
			//echo $consulta;
			$rs=mysqli_query($enlaceCon,$consulta);
			$registro=mysqli_fetch_array($rs);
			if(mysqli_num_rows($rs)>0){
				$precioProducto=$registro[0];
			}
			/************  sacamos el precio por mayor   **************/
			$consulta="select p.`precio` from precios p where p.`codigo_material`='$cod_insumo' and p.`cod_precio`='2' and cod_ciudad='$globalAgencia'";
			$rs=mysqli_query($enlaceCon,$consulta);
			$registro=mysqli_fetch_array($rs);
			if(mysqli_num_rows($rs)>0){
				$precioProductoMayor=$registro[0];
			}
			/************  fin sacar precios   **************/


			$precioProducto=redondear2($precioProducto);
			$precioProductoMayor=redondear2($precioProductoMayor);
?>
<div id="div<?=$num;?>">
<table border="0" align="center" width="100%"  class="texto" id="data<?php echo $num?>" >
<tr bgcolor="#FFFFFF">

<td width="10%" align="center">
	<?php echo $num;?>
	<a href="javascript:buscarMaterial(form1, <?php echo $num;?>)"><img src='imagenes/buscar2.png' title="Buscar Producto" width="30"></a>
</td>

<td width="40%" align="center">
	<input type="hidden" name="materiales<?php echo $num;?>" id="materiales<?php echo $num;?>" value="<?=$cod_insumo;?>">
	<div id="cod_material<?php echo $num;?>" class='textomedianonegro'><?=$descInsumo;?></div>
</td>

<td width="10%" align="center">
	<div id='idstock<?php echo $num;?>'>
		<?=$stockInsumo;?>
		<input type='text' id='stock<?php echo $num;?>'  size='4' name='stock<?php echo $num;?>' value='<?=$stockInsumo;?>' readonly>
	</div>
</td>

<td align="center" width="10%">

	<input class="inputnumber" type="number" value="<?=($cant*$cant_lote);?>" min="0.001" id="cantidad_unitaria<?php echo $num;?>" onKeyUp='calculaMontoMaterial(<?php echo $num;?>);' name="cantidad_unitaria<?php echo $num;?>" onChange='calculaMontoMaterial(<?php echo $num;?>);' step="0.001" required><br/> 
	<?=$cant_lote." X ".$cant;?>
</td>

<td align="center" width="10%">
	<input class="inputnumber" type="number" value="<?=$precioProducto;?>" min="0.01" id="precio_normal<?php echo $num;?>" name="precio_normal<?php echo $num;?>" step="0.01"   <?=($banderaEditPrecios==0)?"disabled":"";?> required> 
</td>

<td align="center" width="10%">
	<input class="inputnumber" type="number" value="<?=$precioProductoMayor;?>" min="0.01" id="precio_mayor<?=$num;?>" name="precio_mayor<?=$num;?>" step="0.01"  <?=($banderaEditPrecios==0)?"disabled":"";?> required> 
</td>


<td align="center"  width="10%" ><input class="boton2peque" type="button" value="-" onclick="menos(<?=$num;?>)" /></td>

</tr>
</table>
</div>
<?php		
 		$num=$num+1;
	}


?>



</head>
</html>