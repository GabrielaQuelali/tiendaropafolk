<?php
require("conexionmysqli.php");
require('estilos.inc');
require('funciones.php');

$codProducto=$_GET['codigo'];
$nombreProducto=$_GET['nombre'];
$tipo=$_GET['tipo'];
$estado=$_GET['estado'];
?>
<form  action='guardaInsumosProductos.php' method='post' name='form1'>

<h1>Insumos</h1><h2><?=$nombreProducto;?></h2>


<input type="hidden" name="codProducto" id="codProducto" value="<?=$codProducto;?>">
<input type="hidden" name="nombreProducto" id="nombreProducto" value="<?=$nombreProducto;?>">
<input type="hidden" name="tipo" id="tipo" value="<?=$tipo;?>">
<input type="hidden" name="estado" id="estado" value="<?=$estado;?>">
<center><table class='texto'>
<tr><th>Nro</th><th>Grupo</th><th>Subgrupo</th><th>Insumo</th><th>Cantidad</th><th>Unidad Medida</th></tr>
<?php
	$sql="select ma.codigo_material,ma.descripcion_material,ma.estado as cod_estado, es.nombre_estado,ma.cod_linea_proveedor,ma.cod_grupo,sub.cod_grupo as cod_grupo2,gru.nombre as grupo, ma.cod_tipomaterial,ma.cantidad_presentacion,ma.observaciones,ma.imagen,ma.cod_unidad,um.abreviatura as nombre_unidad_medida,ma.peso,ma.cod_subgrupo,sub.nombre as subgrupo,ma.cod_marca,ma.codigo_barras,
		ma.talla,ma.color,ma.codigo_anterior,ma.codigo2,ma.fecha_creacion,ma.creado_por,
		concat(f.paterno,' ',f.materno,' ',f.nombres) funcionario,
		ma.cod_modelo, ma.cod_material,ma.cod_genero,ma.cod_tipo,es.nombre_estado
 		from material_apoyo ma
		left join estados es on (ma.estado=es.cod_estado)
		left join subgrupos sub on (ma.cod_subgrupo=sub.codigo)
		left join grupos gru on (sub.cod_grupo=gru.codigo)
		left join unidades_medida um on (ma.cod_unidad=um.codigo)
		left join funcionarios f on (ma.creado_por=f.codigo_funcionario)
		where ma.cod_tipo=2 and ma.estado=1 
		order by gru.nombre asc, sub.nombre asc, ma.descripcion_material asc ";
		//echo $sql;
		$resp=mysqli_query($enlaceCon,$sql);
		while($dat=mysqli_fetch_array($resp)){
			$codigo_material=$dat['codigo_material'];
			$codigo2=$dat['codigo2'];
			$descripcion_material=$dat['descripcion_material'];
			$observaciones=$dat['observaciones'];
			$cod_unidad=$dat['cod_unidad'];
			$nombre_unidad_medida=$dat['nombre_unidad_medida'];	
			$cod_subgrupo=$dat['cod_subgrupo']; 
			$subgrupo=$dat['subgrupo'];
			$grupo=$dat['grupo'];
			$fecha_creacion=$dat['fecha_creacion'];				
			$funcionario=$dat['funcionario'];		
			$fecha_registro= explode(' ',$fecha_creacion);
			$fecha_reg=$fecha_registro[0];
    		$fecha_reg_mostrar = "$fecha_reg[8]$fecha_reg[9]-$fecha_reg[5]$fecha_reg[6]-$fecha_reg[0]$fecha_reg[1]$fecha_reg[2]$fecha_reg[3] $fecha_registro[1]";
		
			$cod_estado=$dat['cod_estado'];
			$nombre_estado=$dat['nombre_estado'];
			$cantidad=0;
			$sw=0;
			$sqlInsumoProducto="select cant from insumos_productos
			 where cod_insumo='".$codigo_material."' and cod_producto='".$codProducto."'";
			 $respInsumoProducto=mysqli_query($enlaceCon,$sqlInsumoProducto);
			while($datInsumoProducto=mysqli_fetch_array($respInsumoProducto)){
				$cantidad=$datInsumoProducto['cant'];
				$sw=1;
			}
	?>
	<tr>
		<td><input type="checkbox" name="codigo_material<?=$codigo_material;?>" id="codigo_material<?=$codigo_material;?>" value="<?=$codigo_material;?>" <?php if($sw==1){ echo "checked"; }?> ></td>
		<td><?=$grupo;?></td>
		<td><?=$subgrupo;?></td>
		<td><?=$descripcion_material;?></td>
		<td><input type="number" value="<?=$cantidad;?>" id="cantidad<?=$codigo_material?>" step="0.001" name="cantidad<?=$codigo_material?>"></td>
		<td><?=$nombre_unidad_medida;?>
		</td>
		
	</tr>
<?php	
	}			
?>
</table></center>


<div class="divBotones">
<input type="submit" class="boton" value="Guardar">
<input type="button" class="boton2" value="Cancelar" 
onClick="location.href='navegador_material.php?estado=<?=$estado;?>&tipo=<?=$tipo;?>'">
</div>
</form>

