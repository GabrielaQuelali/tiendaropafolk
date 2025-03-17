<?php
	require("conexionmysqli2.inc");
	require('estilos.inc');
	require("funciones.php");

echo "<script language='Javascript'>
		function enviar_nav(f)
		{	
			var estado=f.estado.value;
			location.href='registrar_procesoConstruccion.php?estado='+estado;
		}

		function eliminar_nav(f)
		{
			var estado=f.estado.value;
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
					location.href='eliminar_procesoConstruccion.php?datos='+datos+'&estado=$estado';
				}
				else
				{
					return(false);
				}
			}
		}
		function editar_nav(f)
		{
			var estado=f.estado.value;
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
			{	alert('Debe seleccionar solamente un registro  para editar sus datos.');
			}
			else
			{
				if(j==0)
				{
					alert('Debe seleccionar un registro para editar sus datos.');
				}
				else
				{
					location.href='editar_procesoConstruccion.php?codigo='+j_ciclo+'&estado='+estado;
				}
			}
		}
    function cambiar_vista(f)
		{
			var estado;
			estado=f.estado.value;					
			location.href='navegador_procesosConstruccion.php?estado='+estado;
		}
		
		
		</script>";
		

	?><script type="text/javascript" src="lib/externos/jquery/jquery-1.4.4.min.js"></script>
<script type="text/javascript" src="functionsGeneral.js">


</script>
<?php

	$estado=$_GET['estado'];
	$globalAgencia=$_COOKIE['global_agencia'];

    

	echo "<h3 align='center'>Listado de Procesos</h3>";

	echo "<form method='post' action=''>";


		echo "<table align='center' class='texto'><tr><th>Ver Procesos:
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
	</tr></table><br>";

$sql="SELECT pc.cod_proceso_const,pc.nombre_proceso_const,
pc.descripcion_proceso_const,pc.cod_estado, es.nombre_estado,  
pc.created_by, concat(f.paterno,' ',f.materno,' ',f.nombres)  funcionario, pc.created_date
FROM procesos_construccion pc

left join estados es on(pc.cod_estado=es.cod_estado)
left join funcionarios f on (pc.created_by=f.codigo_funcionario)
";
if($estado<>'-1'){
 $sql=$sql." and pc.cod_estado='".$estado."'";
}

$sql=$sql."  order by pc.nombre_proceso_const asc";



	$resp=mysqli_query($enlaceCon,$sql);
	


	echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav(this.form)'>		
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Anular' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
			
		</div> <br> <br>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th>
	<th>Nro</th>
	<th>Nombre</th>
		<th>Descripcion</th>		
		<th>Fecha Registro</th>		
		<th>Estado</th>
	
		</tr>";
	
	$indice_tabla=1;
	while($dat=mysqli_fetch_array($resp))
	{
		$cod_proceso_const=$dat['cod_proceso_const'];
		$nombre_proceso_const=$dat['nombre_proceso_const'];		
		$descripcion_proceso_const=$dat['descripcion_proceso_const'];
		$cod_estado=$dat['cod_estado'];	
		$nombre_estado=$dat['nombre_estado']; 	
		$funcionario=$dat['funcionario'];
		$created_by=$dat['created_by'];	
		$fecha_creacion=$dat['created_date'];				
		
		//////////////
		$fecha_registro= explode(' ',$fecha_creacion);
		$fecha_reg=$fecha_registro[0];
    $fecha_reg_mostrar = "$fecha_reg[8]$fecha_reg[9]-$fecha_reg[5]$fecha_reg[6]-$fecha_reg[0]$fecha_reg[1]$fecha_reg[2]$fecha_reg[3] $fecha_registro[1]";


	
		echo "<tr>
		<td align='center'>";
		if($cod_estado<>2){
			echo" <input type='checkbox' name='codigo' id='codigo' value='$cod_proceso_const'>";
		}
		echo "</td>
		<td>$indice_tabla</td>";

		echo" <td>$nombre_proceso_const</td>
		<td>$descripcion_proceso_const</td>
					
		<td align='center'>".$funcionario."<br/>".$fecha_reg_mostrar."</td>
		<td>$nombre_estado</td>";
		
	
		echo "</tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	
		echo "<div class='divBotones'>
		<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
		<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
		<input type='button' value='Anular' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		
		</div>";

?>

</form>