<html>
<body>

<?php
require("conexionmysqli2.inc");
require("funciones.php");

$tipo=$_GET['tipo'];
$global_almacen=$_GET['global_almacen'];
$fechaNotaRemision=$_GET['fechaNotaRemision'];
$global_ciudad=$_GET['global_ciudad'];

/*echo "tipo=".$tipo;
echo "global_almacen=".$global_almacen;
echo "fechaNotaRemision=".$fechaNotaRemision;*/
	
?>
<table  border="0" align="center" cellSpacing="1" cellPadding="1" width="100%" style="border:#ccc 1px solid;">
<?php	
	$sqlIng=" select id.cod_material,sum(id.cantidad_unitaria)  as  cantIngreso ";
	$sqlIng.=" from ingreso_almacenes i ";
	$sqlIng.=" left join  ingreso_detalle_almacenes id on (i.cod_ingreso_almacen=id.cod_ingreso_almacen) ";
	$sqlIng.=" where i.fecha between '2020-11-20 00:00:00' and '".$fechaNotaRemision." 23:59:59' ";
	$sqlIng.=" and i.cod_almacen='".$global_almacen."' and i.ingreso_anulado=1 ";
	$sqlIng.=" and i.cod_tipo='".$tipo."'";
	$sqlIng.=" group by id.cod_material asc";

	//echo $sqlIng;
	$indiceMaterial=1;
	$respIng=mysqli_query($enlaceCon,$sqlIng);
	while($datIng=mysqli_fetch_array($respIng)){

		$codigo_material=$datIng['cod_material'];
		$cantIngreso=$datIng['cantIngreso'];
		$sqlProducto="select  descripcion_material from material_apoyo where codigo_material=".$codigo_material;
		$respProducto=mysqli_query($enlaceCon,$sqlProducto);
		while($datProducto=mysqli_fetch_array($respProducto)){
			$descripcion_material=$datProducto['descripcion_material'];
		}
		$sql_salidas="select sum(sd.cantidad_unitaria) as cantSalida from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen 
			and s.fecha between '2020-11-20 00:00:00' and '".$fechaNotaRemision."' and s.cod_almacen='".$global_almacen."'
			and sd.cod_material='".$codigo_material."' and s.salida_anulada=1";
			
			//echo $sql_salidas;
			
			$resp_salidas=mysqli_query($enlaceCon,$sql_salidas);
			$dat_salidas=mysqli_fetch_array($resp_salidas);
			$cantSalida=$dat_salidas['cantSalida'];
			$stockProducto=$cantIngreso-$cantSalida;

			$consulta="select p.`precio` from precios p where p.`codigo_material`='".$codigo_material."' and p.`cod_precio`='1' and cod_ciudad='".$global_ciudad."'";
			//echo $consulta;
			$precio=0;
			$rs=mysqli_query($enlaceCon,$consulta);
			$registro=mysqli_fetch_array($rs);
			if(mysqli_num_rows($rs)>0){
					$precio=$registro[0];
			}

		$num=$indiceMaterial;
		if($stockProducto>0){	
	?>

<tr bgcolor="#FFFFFF">
<td width="5%" align="center">
<?=$num;?>
	<input type='checkbox' id='codigoMaterial<?php echo $num;?>' name='codigoMaterial<?php echo $num;?>' value='<?=$codigo_material;?>' onChange='calculaTotalGeneral(this.form);' >
</td>
<td width="35%" align="center"><?=$descripcion_material?></td>
<td width="12%" align="center">
		<input type='text' id='stock<?php echo $num;?>' name='stock<?php echo $num;?>' value='<?=$stockProducto;?>' readonly size='4'>
</td>
<td align="center" width="12%">
	<input class="inputnumber" type="number" value="0"  id="cantidad_venta<?=$num;?>" name="cantidad_venta<?=$num;?>" onChange='calculaTotal(this.form,<?=$num;?>);' onKeyUp='calculaTotal(this.form,<?=$num;?>);' required > 
</td>
<td align="center" width="12%">
	<input class="inputnumber" type="number" value="<?=$precio;?>" min="0.01" id="precio<?=$num;?>" 
	name="precio<?=$num;?>" step="0.01" readonly> 
</td>

<td align="center" width="12%">
	<input class="inputnumber" type="number" value="<?=$precio;?>"  id="precio_venta<?=$num;?>" 
	name="precio_venta<?=$num;?>" onChange='calculaTotal(this.form,<?=$num;?>);' onKeyUp='calculaTotal(this.form,<?=$num;?>);' required> 
</td>
<td align="center" width="12%">
	<input class="inputnumber" type="number" value="0" id="total<?=$num;?>" 
	name="total<?=$num;?>"  readonly> 
</td>

</tr>

<?php
	$indiceMaterial++;
	}
	}
?>
<tr>
	<td colspan="6" align="right">TOTAL</td><td align="center" width="12%">
	<input class="inputnumber" type="number" value="0" id="totalGeneral" 
	name="totalGeneral"  readonly> 
</td></tr>
</table>
<input type="hidden" name="cantidad_material" id="cantidad_material" value="<?=$indiceMaterial;?>">
</body>
</html>