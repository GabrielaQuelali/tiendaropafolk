<?php
require("conexionmysqli.php");
require('estilos.inc');
require('funciones.php');

$codProducto=$_GET['codigo'];
$nombreProducto=$_GET['nombre'];
$tipo=$_GET['tipo'];
$estado=$_GET['estado'];
?>
<form  action='guardaProcesosProducto.php' method='post' name='form1'>

<h1>PROCESOS</h1><h2><?=$nombreProducto;?></h2>


<input type="hidden" name="codProducto" id="codProducto" value="<?=$codProducto;?>">
<input type="hidden" name="nombreProducto" id="nombreProducto" value="<?=$nombreProducto;?>">
<input type="hidden" name="tipo" id="tipo" value="<?=$tipo;?>">
<input type="hidden" name="estado" id="estado" value="<?=$estado;?>">
<center><table class='texto'>
<tr><th>Nro</th><th>Proceso</th><th>Descripcion</th><th>Costo Proceso</th></tr>
<?php
	$sql="select cod_proceso_const,nombre_proceso_const, descripcion_proceso_const,cod_estado,created_by,created_date
 from procesos_construccion where cod_estado=1
 order by nombre_proceso_const asc ";
		//echo $sql;
		$resp=mysqli_query($enlaceCon,$sql);
		$costo_proceso_const=0;
		while($dat=mysqli_fetch_array($resp)){
			$cod_proceso_const=$dat['cod_proceso_const'];
			$nombre_proceso_const=$dat['nombre_proceso_const'];
			$descripcion_proceso_const=$dat['descripcion_proceso_const'];
			$cod_estado=$dat['cod_estado'];
			$created_by=$dat['created_by'];
			$created_date=$dat['created_date'];
			$sw=0;
			$sqlProcesosProducto="select * from procesos_construccion_producto
			 where cod_proceso_const='".$cod_proceso_const."' and cod_producto='".$codProducto."'";
			 //echo $sqlProcesosProducto;
			 $respProcesosProducto=mysqli_query($enlaceCon,$sqlProcesosProducto);
			 $costo_proceso_const=0;
			while($datProcesosProducto=mysqli_fetch_array($respProcesosProducto)){			
				$sw=1;
				$costo_proceso_const=$datProcesosProducto['costo_proceso_const'];
			}

		
	?>
	<tr>
		<td><input type="checkbox" name="cod_proceso_const<?=$cod_proceso_const;?>" id="cod_proceso_const<?=$cod_proceso_const;?>" value="<?=$cod_proceso_const;?>" <?php if($sw==1){ echo "checked"; }?> ></td>
		<td><?=$nombre_proceso_const;?></td>
		<td><?=$descripcion_proceso_const;?></td>
		<td><input type="number" class="inputnumber"  max="100000" 
id="costo<?=$cod_proceso_const;?>" name="costo<?=$cod_proceso_const;?>" size="5"  value="<?=$costo_proceso_const;?>" step="0.001"required></td>
		
		
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

