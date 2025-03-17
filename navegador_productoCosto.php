<?php
	require("conexionmysqli2.inc");
	require('estilos.inc');
	require("funciones.php");

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_productoCosto.php';
		}

		function eliminar_nav(f)
		{
			var i;
			var j=0;
			datos=new Array();
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	datos[j]=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j==0)
			{	alert('Debe seleccionar al menos un registro para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_productoCosto.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}

    function cambiar_vista(f)
		{
			var estado;

			estado=f.estado.value;
			
		
			location.href='navegador_productoCosto.php?estado='+estado;
		}

		
		</script>";
		

	?><script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="functionsGeneral.js">


</script>
<h3 align='center'>Listado de Productos para Costo</h3>
<form method='post' action=''>

<?php

	$estado=$_GET['estado'];
	$globalAgencia=$_COOKIE['global_agencia'];
	$global_almacen=$_COOKIE['global_almacen'];


?>	
	
	<table align='center' class='texto'><tr><th>Producto Costo:
	<select name='estado' id='estado'class='texto' onChange='cambiar_vista(this.form)'>";
			
		<?php
			$sql2="select cod_estado, nombre_estado from estados order by cod_estado asc";
			$resp2=mysqli_query($enlaceCon,$sql2);
		?>
			<option value='' selected>TODOS</option>
		<?php
			while($dat2=mysqli_fetch_array($resp2)){
				$codEstado=$dat2[0];
				$nombreEstado=$dat2[1];
				if($codEstado==$estado){
		?>
				  <option value="<?=$codEstado;?>" selected><?=$nombreEstado;?></option>

		<?php		}else{  ?>

					<option value="<?=$codEstado;?>"><?=$nombreEstado;?></option>
		<?php	
				}
			}
		?>
		 </select>

	</th>
	</tr></table><br>
	<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>		
		
		<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		
		</div> <br/> 
<center><a href='resumenProductosCosto.php'  target="_blank" >Resumen de Productos para Costeo</a></center>
		<br/>
	
	<center><table class='texto'>
	<tr><th>&nbsp;</th>
		<th>Nro</th>
		<th>Producto Costo</th>
		<th>Estado</th>
		<th>Fecha</th>
		<th>Productos</th>
	
	
		</tr>
	<?php 
	$sql="select pc.cod_producto_costo,pc.nombre_producto_costo,pc.cod_estado, e.nombre_estado,
pc.created_by,pc.created_date
from producto_costo pc
left join estados e on (pc.cod_estado=e.cod_estado)
where pc.cod_producto_costo<>0";
if($estado<>-1){
 $sql=$sql." and pc.cod_estado=".$estado."";
}
$sql=$sql."  order by pc.nombre_producto_costo asc";		
	//echo $sql;
	
	$indice_tabla=1;
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp)){
		$cod_producto_costo=$dat['cod_producto_costo'];
		$nombre_producto_costo=$dat['nombre_producto_costo'];
		$cod_estado=$dat['cod_estado'];
		$nombre_estado=$dat['nombre_estado'];
		$created_by=$dat['created_by'];
		$created_date=$dat['created_date'];	
		$fecha= explode('-',$created_date);	
    	$fecha_mostrar = $fecha[2]."/".$fecha[1]."/".$fecha[0];
	?>

<tr>
		<td align="center">
		<?php if($cod_estado==1){ ?>
			<input type="checkbox" name="codigo" id="codigo" value="<?=$cod_producto_costo;?>">
		<?php } ?>
		</td>
		<td><?=$indice_tabla;?></td>
		<td><?=$nombre_producto_costo;?></td>
		<td><?=$nombre_estado;?></td>
		<td><?=$fecha_mostrar;?></td>
		
		<td>
			<table border='0' cellspacing='0' cellpadding='0' class='textomini'>
			<?php
			$sqlDet=" select pcd.cod_producto, ma.descripcion_material
				from producto_costo_detalle pcd
				left join material_apoyo ma on (pcd.cod_producto=ma.codigo_material)
				where pcd.cod_producto_costo=".$cod_producto_costo."
 				order by descripcion_material asc";
 			//	echo $sqlDet;
			$respDet=mysqli_query($enlaceCon,$sqlDet);
			$correlativo=0;
			while($datDet=mysqli_fetch_array($respDet)){
				$correlativo++;
				$descripcion_material=$datDet['descripcion_material'];
				
			?>
			<tr><td><?=$correlativo;?></td><td><?=$descripcion_material;?></td></tr>
			<?php
				}
			?>
		</table>

		</td>
		
		

 </tr>
	<?php	$indice_tabla++;
}
	?>
</table></center><br>
	
<div class="divBotones">
		<input type="button" value="Adicionar" name="adicionar" class="boton" onclick="enviar_nav()">
		
		<input type="button" value="Eliminar" name="eliminar" class="boton2" onclick="eliminar_nav(this.form)">		
</div>

</form>