<?php
	require("conexionmysqli2.inc");
	require('estilos.inc');
	require("funciones.php");

echo "<script language='Javascript'>
		function enviar_nav()
		{	location.href='registrar_cotizacion.php';
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
					location.href='eliminar_cotizacion.php?datos='+datos+'';
				}
				else
				{
					return(false);
				}
			}
		}
		function editar_nav(f)
		{
			var i;
			var j=0;
			var j_ciclo;
			for(i=0;i<=f.length-1;i++)
			{
				if(f.elements[i].type=='checkbox')
				{	if(f.elements[i].checked==true)
					{	j_ciclo=f.elements[i].value;
						j=j+1;
					}
				}
			}
			if(j>1)
			{	alert('Debe seleccionar solamente un material de apoyo para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un material de apoyo para editar sus datos.');
				}
				else
				{
					location.href='editar_lote.php?cod_lote='+j_ciclo+'';
				}
			}
		}
    function cambiar_vista(f)
		{
			var estado;

			estado=f.estado.value;
			
		
			location.href='navegador_cotizaciones.php?estado='+estado;
		}

		
		</script>";
		

	?><script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="functionsGeneral.js">


</script>
<?php

	$estado=$_GET['estado'];
	$globalAgencia=$_COOKIE['global_agencia'];
	$global_almacen=$_COOKIE['global_almacen'];

	echo "<h3 align='center'>Listado de Cotizaciones</h3>";

	echo "<form method='post' action=''>";
	
	$sql="select c.cod_cotizacion,c.nro_cotizacion,c.fecha_cotizacion,
c.desc_cotizacion,c.cod_estado,e.nombre_estado,c.cod_almacen, al.nombre_almacen
from cotizaciones c
left join almacenes al on (c.cod_almacen=al.cod_almacen)
left join estados e on (c.cod_estado=e.cod_estado)
where c.cod_almacen=".$global_almacen;
if($estado<>-1){
 $sql=$sql." and c.cod_estado='".$estado."'";
}
$sql=$sql."  order by c.fecha_cotizacion desc,c.nro_cotizacion desc";		
	
	
	
	echo "<table align='center' class='texto'><tr><th>Ver Lotes:
	<select name='estado' id='estado'class='texto' onChange='cambiar_vista(this.form)'>";
			
			$sql2="select el.cod_estado, el.nombre_estado from estados_lote el order by cod_estado asc";
			$resp2=mysqli_query($enlaceCon,$sql2);
		echo"	<option value='' selected>TODOS</option>";
			while($dat2=mysqli_fetch_array($resp2)){
				$codEstado=$dat2[0];
				$nombreEstado=$dat2[1];
				if($codEstado==$estado){
				  echo "<option value=$codEstado selected>$nombreEstado</option>";	
				}else{
					echo "<option value=$codEstado>$nombreEstado</option>";
				}
			}
			echo " </select>";
?>
	</th>
	</tr></table><br>
	<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>		
		
		<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		
		</div> <br> <br>
	
	<center><table class='texto'>
	<tr><th>&nbsp;</th><th>Nro<br/>Cotizacion</th><th>Fecha</th>
		<th>Cotizacion</th> <th>Productos</th><th>Estado</th>
		<th>&nbsp;</th>
	
		</tr>
	<?php 
	$indice_tabla=1;
	$resp=mysqli_query($enlaceCon,$sql);
	while($dat=mysqli_fetch_array($resp)){


		$cod_cotizacion=$dat['cod_cotizacion'];
		$nro_cotizacion=$dat['nro_cotizacion'];
		$fecha_cotizacion=$dat['fecha_cotizacion'];
		$desc_cotizacion=$dat['desc_cotizacion'];
		$cod_estado=$dat['cod_estado'];
		$nombre_estado=$dat['nombre_estado'];
		$cod_almacen=$dat['cod_almacen'];
		$nombre_almacen=$dat['nombre_almacen'];
	
		$fecha= explode('-',$fecha_cotizacion);
	
    $fecha_cotizacion_mostrar = $fecha[2]."/".$fecha[1]."/".$fecha[0];
	?>

<tr>
		<td align="center">
		<?php if($cod_estado==1){ ?>
			<input type="checkbox" name="codigo" id="codigo" value="<?=$cod_cotizacion;?>">
		<?php } ?>
		</td>
		<td><?=$nro_cotizacion;?></td>
		<td><?=$fecha_cotizacion_mostrar;?></td>
		<td><?=$desc_cotizacion;?></td>
		<td>
			<table border='0' cellspacing='0' cellpadding='0' class='textomini'>
			<?php
			$sqlDet=" select cd.cod_producto,ma.descripcion_material,cd.cantidad
					from cotizaciones_detalle cd
					left join material_apoyo ma on( cd.cod_producto=ma.codigo_material) 
					where cd.cod_cotizacion=".$cod_cotizacion." order by orden asc";
			$respDet=mysqli_query($enlaceCon,$sqlDet);
			while($datDet=mysqli_fetch_array($respDet)){

				$descripcion_material=$datDet['descripcion_material'];
				$cantidad=$datDet['cantidad'];
			?>
			<tr><td><?=$descripcion_material;?></td><td><?=$cantidad;?></td></tr>
			<?php
				}
			?>
		</table>

		</td>
		<td><?=$nombre_estado?></td>
		<td><a target="_BLANK" href="reporteCotizacion.php?cod_cotizacion=<?=$cod_cotizacion?>">
			<img src="imagenes/detalles.png" border="0" width="30" heigth="30" title="Cotizacion"></a>
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