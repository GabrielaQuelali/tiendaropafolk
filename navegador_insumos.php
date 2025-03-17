<?php
	require("conexionmysqli2.inc");
	require('estilos.inc');
	require("funciones.php");

echo "<script language='Javascript'>
		function enviar_nav(f)
		{	

			var estado;
			var grupo;
			var tipo;

			estado=f.estado.value;
			grupo=f.grupo.value;
			tipo=f.tipo.value;
			
		
			location.href='registrar_insumo.php?estado='+estado+'&grupo='+grupo+'&tipo='+tipo;
			
		}

		function eliminar_nav(f)
		{
			var estado;
			var grupo;
			var tipo;

			estado=f.estado.value;
			grupo=f.grupo.value;
			tipo=f.tipo.value;

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
			{	alert('Debe seleccionar al menos un material de apoyo para proceder a su eliminación.');
			}
			else
			{
				if(confirm('Esta seguro de eliminar los datos.'))
				{
					location.href='eliminar_insumo.php?datos='+datos+'&estado='+estado+'&grupo='+grupo+'&tipo='+tipo;
				}
				else
				{
					return(false);
				}
			}
		}
		function editar_nav(f)
		{

			var estado;
			var grupo;
			var tipo;

			estado=f.estado.value;
			grupo=f.grupo.value;
			tipo=f.tipo.value;
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
					location.href='editar_insumo.php?codigo='+j_ciclo+'&estado='+estado+'&grupo='+grupo+'&tipo='+tipo;
				}
			}
		}
    function cambiar_vista(f)
		{
			var estado;
			var grupo;
			var tipo;

			estado=f.estado.value;
			grupo=f.grupo.value;
			tipo=f.tipo.value;
			
		
			location.href='navegador_insumos.php?estado='+estado+'&grupo='+grupo+'&tipo='+tipo;
		}
		
		
		</script>";
		

	?><script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="functionsGeneral.js">


</script>
<?php

	$estado=$_GET['estado'];
	$tipo=$_GET['tipo'];
	$grupo=$_GET['grupo'];
	$globalAgencia=$_COOKIE['global_agencia'];

    

	echo "<h3 align='center'>Listado de Insumos</h3>";

	echo "<form method='post' action=''>";
	echo "<input type='hidden' value='$tipo' name='tipo' id='tipo'>";
	

		echo "<table align='center' class='texto'>
		<tr><th>Ver Insumos:
	<select name='estado' id='estado'class='texto' onChange='cambiar_vista(this.form)'>";
			
			$sql2="select es.cod_estado, es.nombre_estado from estados es order by es.cod_estado asc";
			$resp2=mysqli_query($enlaceCon,$sql2);
		echo"	<option value='-1' selected>TODOS</option>";
			while($dat2=mysqli_fetch_array($resp2)){
				$codEstado=$dat2[0];
				$nombreEstado=$dat2[1];
				if($codEstado==$estado){
				  echo "<option value=$codEstado selected>$nombreEstado</option>";	
				}else{
					echo "<option value=$codEstado>$nombreEstado</option>";
				}
			}
			echo " </select>
	</th>
	<th>Ver Grupos:
	<select name='grupo' id='grupo'class='texto' onChange='cambiar_vista(this.form)'>";
			
			$sql3="select codigo,nombre from grupos where estado=1 and cod_tipo='".$tipo."'  order by  nombre asc";
			//echo $sql3."<br/>";
			$resp3=mysqli_query($enlaceCon,$sql3);
		echo"	<option value='-1' selected>TODOS</option>";
			while($dat3=mysqli_fetch_array($resp3)){
				$codGrupo=$dat3[0];
				$nombreGrupo=$dat3[1];
				if($codGrupo==$grupo){
				  echo "<option value=$codGrupo selected>$nombreGrupo</option>";	
				}else{
					echo "<option value=$codGrupo>$nombreGrupo</option>";
				}
			}
			echo " </select>
	</th>
	</tr></table><br>";

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
where ma.cod_tipo=$tipo";
if($estado<>'-1'){
 $sql=$sql." and ma.estado='".$estado."'";
}
if($grupo<>'-1'){
 $sql=$sql." and sub.cod_grupo='".$grupo."'";
}
$sql=$sql."  order by ma.descripcion_material asc";

//echo $sql;

	$resp=mysqli_query($enlaceCon,$sql);
	


	echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav(this.form)'>		
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Anular' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
			
		</div> <br> <br>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Nro</th><th>Codigo</th><th>Nombre</th>
		<th>Descripcion</th><th>Unidad<br/>Medida</th><th>&nbsp;</th>
		<th>Grupo<br/>Subgrupo</th>
		<th>Fecha Registro</th>		
		<th>Estado</th>
	
		</tr>";
	
	$indice_tabla=1;
	while($dat=mysqli_fetch_array($resp))
	{
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
		//////////////
		$fecha_registro= explode(' ',$fecha_creacion);
		$fecha_reg=$fecha_registro[0];
    $fecha_reg_mostrar = "$fecha_reg[8]$fecha_reg[9]-$fecha_reg[5]$fecha_reg[6]-$fecha_reg[0]$fecha_reg[1]$fecha_reg[2]$fecha_reg[3] $fecha_registro[1]";
		////////////
		$cod_estado=$dat['cod_estado'];
		$nombre_estado=$dat['nombre_estado'];

	
		echo "<tr>
		<td align='center'>";
		if($cod_estado<>2){
			echo" <input type='checkbox' name='codigo' id='codigo' value='$codigo_material'>";
		}
		echo "</td>";

		echo"<td>$indice_tabla</td> 
		<td>$codigo2</td>
		<td>$descripcion_material</td>
		<td>$observaciones</td>
		<td>$nombre_unidad_medida</td>
		<td>";

		$sqlListPrecios="select p.codigo_material,p.cod_precio,gp.nombre ,gp.abreviatura ,p.precio,p.cod_ciudad,c.nombre_ciudad,
		p.cant_inicio,p.cant_final, p.created_by, 
		concat(f.nombres,' ',f.paterno,' ',f.materno) as creado_por, p.created_date		
		from precios p
		left join grupos_precio gp on (p.cod_precio=gp.codigo)
		left join ciudades c on (p.cod_ciudad=c.cod_ciudad)
		left join funcionarios f on (p.created_by=f.codigo_funcionario)
		where p.codigo_material='".$codigo_material."'and p.cod_ciudad='".$globalAgencia."' order by p.cod_precio asc";

		$respListPrecios=mysqli_query($enlaceCon,$sqlListPrecios);
		echo" <table border='0'>";
		while($datListPrecios=mysqli_fetch_array($respListPrecios)){
			$nombreGrupoPrecio=$datListPrecios['nombre'];
			$abrevGrupoPrecio=$datListPrecios['abreviatura'];
			$precio=$datListPrecios['precio'];
			echo "<tr><td>".redondear2($precio)."</td><td>".$abrevGrupoPrecio."</td></tr>";

		}
		echo" </table>";
		echo"</td>
		<td>$grupo/$subgrupo</td>		
		<td align='center'>$funcionario<br/>$fecha_reg_mostrar</td>
		<td>$nombre_estado</td>";
		
	
		echo "</tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	
		echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav(this.form)'>
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Anular' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		
		</div>";

?>

</form>